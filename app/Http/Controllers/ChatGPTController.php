<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatGPTController extends Controller
{
    /**
     * Sende eine Nachricht an die ChatGPT API und erhalte eine Antwort
     */
    public function sendMessage(Request $request)
    {
        try {
            Log::info('ChatGPT Anfrage empfangen', [
                'ip' => $request->ip(),
                'model' => $request->model,
                'message_length' => strlen($request->message ?? ''),
                'request_data' => $request->all()
            ]);
            
            // API-Schlüssel aus der .env-Datei holen
            $apiKey = env('OPENAI_API_KEY');
            
            // Prüfe, ob API-Schlüssel vorhanden ist
            if (!$apiKey) {
                Log::error('OPENAI_API_KEY fehlt in der Konfiguration');
                return response()->json([
                    'success' => false,
                    'response' => 'API-Konfiguration fehlt'
                ], 500);
            }

            // Modell bestimmen - mit Fallback für projektbasierte API-Schlüssel
            $model = $request->model ?: 'gpt-3.5-turbo';
            
            // Bei projektbasierten Schlüsseln (sk-proj-) zu einem kompatiblen Modell wechseln
            if (strpos($apiKey, 'sk-proj-') === 0) {
                // Log für projektbasierten Schlüssel
                Log::info('Projektbasierter API-Schlüssel erkannt, verwende kompatibles Modell');
                
                // Abhängig vom angeforderten Modell ein kompatibles GPT-4-Modell wählen
                if (strpos($model, 'gpt-4') === 0) {
                    $model = 'gpt-4-turbo';
                } else {
                    $model = 'gpt-3.5-turbo';
                }
            }
            
            // Nachricht extrahieren
            $userMessage = $request->message;
            
            if (empty($userMessage)) {
                Log::error('Keine Nachricht in der Anfrage gefunden');
                return response()->json([
                    'success' => false,
                    'response' => 'Keine Nachricht angegeben'
                ], 400);
            }
            
            // Chatverlauf für Kontext vorbereiten
            $messages = [];
            
            // System-Message für Kontext
            $messages[] = [
                'role' => 'system',
                'content' => 'Du bist ein hilfreicher KI-Assistent für YouTube-Content-Erstellung. Du hilfst dabei, Ideen zu generieren, Content-Strategien zu entwickeln und Skripte zu schreiben. Deine Antworten sind kreativ, gut strukturiert und praktisch umsetzbar.'
            ];

            // Benutzer-Nachricht hinzufügen
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage
            ];

            Log::info('Sende Anfrage an OpenAI API mit Modell: ' . $model);
            
            // API-Anfrage an OpenAI senden
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v1'  // Zusätzlicher Header für Projektschlüssel
            ])
            ->withOptions([
                'verify' => false, // SSL-Überprüfung deaktivieren
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1500,
            ]);

            // Protokolliere die Anfrage-Header für Debugging
            Log::debug('OpenAI Anfrage-Header:', [
                'Authorization' => 'Bearer ' . substr($apiKey, 0, 10) . '...',
                'Content-Type' => 'application/json'
            ]);
            
            // Protokolliere die Antwort für Debugging
            Log::debug('OpenAI Antwort-Status: ' . $response->status());
            Log::debug('OpenAI Antwort-Body: ' . $response->body());
            
            // Prüfe auf HTTP-Fehler
            if ($response->failed()) {
                $statusCode = $response->status();
                $errorBody = $response->body();
                
                // Detaillierte Fehlerbehandlung für häufige OpenAI-Fehler
                if ($statusCode === 429) {
                    Log::warning('OpenAI API Rate Limit erreicht (429)', [
                        'body' => $errorBody,
                        'headers' => $response->headers()
                    ]);
                    return response()->json([
                        'success' => false,
                        'response' => 'Die Anfragelimits der API wurden überschritten. Bitte versuchen Sie es in einigen Minuten erneut.'
                    ], 429);
                }
                
                Log::error('OpenAI API HTTP-Fehler: ' . $statusCode, [
                    'body' => $errorBody,
                    'headers' => $response->headers()
                ]);
                return response()->json([
                    'success' => false,
                    'response' => 'Fehler bei der Kommunikation mit OpenAI: ' . $statusCode
                ], 500);
            }
            
            // Extrahiere die Antwort als Array
            $responseData = json_decode($response->body(), true);
            
            // Prüfe auf json_decode-Fehler
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON-Dekodierungsfehler: ' . json_last_error_msg(), [
                    'raw_response' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'response' => 'Fehler beim Verarbeiten der API-Antwort'
                ], 500);
            }
            
            // Prüfe, ob die erwartete Struktur vorhanden ist
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Unerwartetes Antwortformat von OpenAI', [
                    'response_data' => $responseData
                ]);
                return response()->json([
                    'success' => false,
                    'response' => 'Unerwartetes Antwortformat von OpenAI'
                ], 500);
            }
            
            $chatGptResponse = $responseData['choices'][0]['message']['content'];
            
            Log::info('Erfolgreiche OpenAI-Antwort erhalten', [
                'response_length' => strlen($chatGptResponse)
            ]);

            // Erfolgreiche Antwort zurückgeben
            return response()->json([
                'success' => true,
                'response' => $chatGptResponse
            ]);
            
        } catch (Throwable $e) {
            Log::error('Fehler bei der Verarbeitung der ChatGPT-Anfrage', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'response' => 'Interner Serverfehler: ' . $e->getMessage()
            ], 500);
        }
    }
} 