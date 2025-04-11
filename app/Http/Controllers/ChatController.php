<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Channel;
use App\Models\Script;

class ChatController extends Controller
{
    /**
     * Sende eine Chat-Nachricht an die KI
     */
    public function send(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'model' => 'required|string',
            'context' => 'nullable'
        ]);

        $prompt = $request->input('prompt');
        $model = $request->input('model');
        $context = $request->input('context');

        // Einfaches Request-ID für Cache und Logging
        $requestId = Str::random(10);
        Log::info("Chat request [{$requestId}] started", [
            'prompt_length' => strlen($prompt),
            'model' => $model,
            'has_context' => !empty($context)
        ]);

        $startTime = microtime(true);
        
        try {
            // Kontext aufbereiten, wenn vorhanden
            $contextContent = null;
            if ($context) {
                if ($context['type'] === 'channel') {
                    $channel = Channel::find($context['id']);
                    if ($channel) {
                        $contextContent = "YouTube-Kanal: {$channel->title}\n";
                        $contextContent .= "Themen: {$channel->topics}\n";
                        $contextContent .= "Beschreibung: {$channel->description}\n";
                        
                        if (!empty($channel->keywords)) {
                            $contextContent .= "Keywords: {$channel->keywords}\n";
                        }
                    }
                } elseif ($context['type'] === 'script') {
                    $script = Script::find($context['id']);
                    if ($script) {
                        $contextContent = "Skript: {$script->title}\n";
                        $contextContent .= "Inhalt:\n{$script->content}\n";
                    }
                }
            }

            // Erstelle einen Cache-Key basierend auf Prompt und Kontext
            $cacheKey = 'chat_' . md5($prompt . ($contextContent ?? '') . $model);
            
            // Prüfe, ob wir eine gecachte Antwort haben (10 Minuten Cache-Zeit)
            if (Cache::has($cacheKey)) {
                $response = Cache::get($cacheKey);
                Log::info("Chat request [{$requestId}] served from cache", [
                    'time' => round(microtime(true) - $startTime, 2) . 's'
                ]);
                
                return response()->json([
                    'success' => true,
                    'response' => $response,
                    'cached' => true
                ]);
            }

            // Antwort von OpenAI API abrufen
            $response = $this->callOpenAIAPI($prompt, $contextContent, $model);
            
            // Cache die Antwort für 10 Minuten
            Cache::put($cacheKey, $response, now()->addMinutes(10));
            
            $responseTime = round(microtime(true) - $startTime, 2);
            Log::info("Chat request [{$requestId}] completed successfully", [
                'time' => $responseTime . 's',
                'response_length' => strlen($response)
            ]);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        } catch (\Exception $e) {
            $responseTime = round(microtime(true) - $startTime, 2);
            Log::error("Chat request [{$requestId}] failed", [
                'time' => $responseTime . 's',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Generiere eine Fallback-Antwort wenn die API nicht verfügbar ist
            $fallbackResponse = $this->generateFallbackResponse($prompt, $contextContent);
            
            return response()->json([
                'success' => true, // Wir behandeln es als Erfolg, um die UI-Erfahrung nicht zu stören
                'response' => $fallbackResponse,
                'fallback' => true,
                'message' => 'Verwendet Fallback-Modus aufgrund temporärer Probleme mit der OpenAI API'
            ]);
        }
    }

    /**
     * Rufe die KI-API auf (GPT oder DeepSeek)
     */
    private function callAI($prompt, $context = null, $model = 'gpt')
    {
        // Hier würde die konkrete Implementierung kommen
        // Im Beispiel wird angenommen, dass wir einen Service verwenden
        
        $messages = [];
        
        // System-Prompt mit Kontext
        $systemPrompt = "Du bist ein hilfreicher Assistent für YouTube-Content-Ersteller.";
        
        if ($context) {
            // Vereinfachte Kontextinformationen ohne Abhängigkeit von Supabase
            if ($context['type'] === 'channel') {
                $systemPrompt .= " Der Benutzer möchte Hilfe zum Kanal mit der ID {$context['id']}.";
            } elseif ($context['type'] === 'script') {
                $systemPrompt .= " Der Benutzer arbeitet an einem Skript mit der ID {$context['id']}.";
            }
        }
        
        $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        $messages[] = ['role' => 'user', 'content' => $prompt];
        
        // Je nach Modell die entsprechende API aufrufen
        switch ($model) {
            case 'claude':
                return $this->callClaudeAPI($messages);
            case 'deepseek':
                return $this->callDeepseekAPI($messages);
            case 'gpt':
            default:
                return $this->callOpenAIAPI($messages);
        }
    }
    
    /**
     * Rufe die OpenAI API auf
     */
    private function callOpenAIAPI($prompt, $contextContent, $model)
    {
        // System-Prompt erstellen
        $systemPrompt = "Du bist ein YouTube-Kreativer-Assistent namens YTkrea. ";
        $systemPrompt .= "Deine Aufgabe ist es, Creators beim Ideenfinden, Scriptwriting und Channel-Optimierung zu helfen. ";
        $systemPrompt .= "Du bist praktisch, präzise und up-to-date mit den neuesten YouTube-Trends.";
        
        // Verbesserte Fehlerbehandlung mit Retries
        $maxRetries = 2;
        $retryCount = 0;
        $lastError = null;
        
        while ($retryCount <= $maxRetries) {
            try {
                // API-Anfrage je nach gewähltem Modell
                $modelEndpoint = 'gpt-4';
                
                if ($model === 'gpt3') {
                    $modelEndpoint = 'gpt-3.5-turbo';
                }
                
                // Vollständige Nachrichtenliste erstellen
                $messages = [
                    ['role' => 'system', 'content' => $systemPrompt]
                ];
                
                // Kontext hinzufügen, wenn vorhanden
                if ($contextContent) {
                    $messages[] = [
                        'role' => 'system', 
                        'content' => "Hier ist der Kontext für die Anfrage:\n\n" . $contextContent
                    ];
                }
                
                // Benutzeranfrage hinzufügen
                $messages[] = ['role' => 'user', 'content' => $prompt];
                
                // Bestimme API-Host (entweder Proxy oder direkt)
                $endpoint = env('OPENAI_API_HOST', 'https://api.openai.com') . '/v1/chat/completions';
                
                // Anfrage an OpenAI senden
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->timeout(60)->post($endpoint, [
                    'model' => $modelEndpoint,
                    'messages' => $messages,
                    'max_tokens' => 2000,
                    'temperature' => 0.7
                ]);
                
                if ($response->successful()) {
                    return $response->json()['choices'][0]['message']['content'];
                } else {
                    $errorDetail = $response->json();
                    $errorMessage = $errorDetail['error']['message'] ?? 'Unbekannter API-Fehler';
                    throw new \Exception("OpenAI API-Fehler: {$errorMessage}");
                }
            } catch (\Exception $e) {
                $lastError = $e;
                $retryCount++;
                
                if ($retryCount <= $maxRetries) {
                    // Exponentielles Backoff (0.5s, 1s)
                    $sleepTime = 0.5 * pow(2, $retryCount - 1);
                    sleep($sleepTime);
                    
                    Log::warning("Wiederholen der OpenAI-Anfrage nach Fehler ({$retryCount}/{$maxRetries})", [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
                
                // Alle Retries fehlgeschlagen
                throw $lastError;
            }
        }
    }
    
    /**
     * Rufe die DeepSeek API auf
     */
    private function callDeepseekAPI($messages)
    {
        $deepseekKey = env('DEEPSEEK_API_KEY');
        
        // Wenn kein API-Key vorhanden ist, gib eine Fallback-Antwort zurück
        if (empty($deepseekKey)) {
            Log::warning('DeepSeek API-Key fehlt. Verwende Fallback-Antwort.');
            return $this->getFallbackResponse($messages);
        }
        
        // Cache-Key generieren
        $cacheKey = 'deepseek_response_' . md5(json_encode($messages));
        
        // Prüfen, ob eine gecachte Antwort existiert
        if (config('app.env') !== 'production' && \Cache::has($cacheKey)) {
            Log::info('Verwende gecachte DeepSeek-Antwort');
            return \Cache::get($cacheKey);
        }
        
        try {
            // Tracking für API-Anfragen
            Log::info('Sende Anfrage an DeepSeek', [
                'message_count' => count($messages),
                'first_words' => substr(end($messages)['content'], 0, 30) . '...'
            ]);
            
            $startTime = microtime(true);
            
            $response = Http::withoutVerifying()
                ->timeout(30) // Längeres Timeout für große Anfragen
                ->retry(2, 1000) // Wiederholungsversuche bei Fehlern
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $deepseekKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.deepseek.com/v1/chat/completions', [
                    'model' => 'deepseek-chat',
                    'messages' => $messages,
                    'max_tokens' => 1000,
                    'temperature' => 0.7,
                ]);
            
            $duration = microtime(true) - $startTime;
            Log::info('DeepSeek Antwort erhalten', ['duration' => round($duration, 2) . 's']);
            
            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                
                // Cache die Antwort für 1 Stunde in Nicht-Produktionsumgebungen
                if (config('app.env') !== 'production') {
                    \Cache::put($cacheKey, $content, now()->addHour());
                }
                
                return $content;
            }
            
            // Detaillierte Fehlerprotokolle
            $errorBody = $response->body();
            Log::error('DeepSeek API error response', [
                'status' => $response->status(),
                'body' => $errorBody,
                'headers' => $response->headers()
            ]);
            
            throw new \Exception('DeepSeek API error: ' . $errorBody);
        } catch (\Exception $e) {
            Log::error('DeepSeek API error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Bei einem API-Fehler gib eine Fallback-Antwort zurück
            return $this->getFallbackResponse($messages);
        }
    }
    
    /**
     * Rufe die Claude/Anthropic API auf
     */
    private function callClaudeAPI($messages)
    {
        $claudeKey = env('ANTHROPIC_API_KEY');
        
        // Wenn kein API-Key vorhanden ist, gib eine Fallback-Antwort zurück
        if (empty($claudeKey)) {
            Log::warning('Claude API-Key fehlt. Verwende Fallback-Antwort.');
            return $this->getFallbackResponse($messages);
        }
        
        // Cache-Key generieren
        $cacheKey = 'claude_response_' . md5(json_encode($messages));
        
        // Prüfen, ob eine gecachte Antwort existiert
        if (config('app.env') !== 'production' && \Cache::has($cacheKey)) {
            Log::info('Verwende gecachte Claude-Antwort');
            return \Cache::get($cacheKey);
        }
        
        try {
            // Tracking für API-Anfragen
            Log::info('Sende Anfrage an Claude', [
                'message_count' => count($messages),
                'first_words' => substr(end($messages)['content'], 0, 30) . '...'
            ]);
            
            $startTime = microtime(true);
            
            // Format system message and user messages for Claude
            $systemMessage = '';
            $convertedMessages = [];
            
            foreach ($messages as $message) {
                if ($message['role'] === 'system') {
                    if (!empty($systemMessage)) {
                        $systemMessage .= "\n\n";
                    }
                    $systemMessage .= $message['content'];
                } else {
                    $convertedMessages[] = [
                        'role' => $message['role'],
                        'content' => $message['content']
                    ];
                }
            }
            
            $response = Http::withHeaders([
                'x-api-key' => $claudeKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->timeout(30)
              ->retry(2, 1000)
              ->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-sonnet-20240229',
                'system' => $systemMessage,
                'messages' => $convertedMessages,
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);
            
            $duration = microtime(true) - $startTime;
            Log::info('Claude Antwort erhalten', ['duration' => round($duration, 2) . 's']);
            
            if ($response->successful()) {
                $content = $response->json()['content'][0]['text'];
                
                // Cache die Antwort für 1 Stunde in Nicht-Produktionsumgebungen
                if (config('app.env') !== 'production') {
                    \Cache::put($cacheKey, $content, now()->addHour());
                }
                
                return $content;
            }
            
            // Detaillierte Fehlerprotokolle
            $errorBody = $response->body();
            Log::error('Claude API error response', [
                'status' => $response->status(),
                'body' => $errorBody,
                'headers' => $response->headers()
            ]);
            
            throw new \Exception('Claude API error: ' . $errorBody);
        } catch (\Exception $e) {
            Log::error('Claude API error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Bei einem API-Fehler gib eine Fallback-Antwort zurück
            return $this->getFallbackResponse($messages);
        }
    }
    
    /**
     * Generiert eine intelligente Fallback-Antwort, wenn die API nicht verfügbar ist
     */
    protected function generateFallbackResponse($prompt, $contextContent = null)
    {
        // Prompt analysieren, um relevante Schlüsselwörter zu erkennen
        $lowercasePrompt = strtolower($prompt);
        
        // Allgemeine Einleitung
        $response = "Entschuldige die Verzögerung. Es scheint, dass ich aktuell eingeschränkten Zugriff auf externe Dienste habe. Hier sind einige Gedanken zu deiner Anfrage:\n\n";
        
        // Kontext-spezifische Antwort
        if (strpos($lowercasePrompt, 'idee') !== false || strpos($lowercasePrompt, 'thema') !== false) {
            // Anfrage zu Video-Ideen
            $response .= "**Video-Ideen entwickeln:**\n\n";
            $response .= "1. Recherchiere Trends in deiner Nische mit Google Trends und YouTube Trending\n";
            $response .= "2. Prüfe erfolgreiche Videos deiner Konkurrenten für Inspiration\n";
            $response .= "3. Betrachte deine Analytics, um zu sehen, welche deiner bisherigen Inhalte am besten performt haben\n";
            $response .= "4. Verbinde aktuelle Ereignisse mit deinem Themenbereich\n";
            $response .= "5. Beantworte häufig gestellte Fragen deiner Community\n\n";
            $response .= "Wenn du wieder online bist, kann ich dir spezifischere Ideen zu deinen Themen vorschlagen.";
        }
        elseif (strpos($lowercasePrompt, 'skript') !== false || strpos($lowercasePrompt, 'script') !== false) {
            // Anfrage zu Skript/Script-Struktur
            $response .= "**Effektive Skript-Struktur:**\n\n";
            $response .= "1. **Hook (0-30 Sek)**: Fessle deine Zuschauer mit einer überraschenden Aussage oder Frage\n";
            $response .= "2. **Intro (30-60 Sek)**: Stelle dich vor und erkläre, worum es im Video geht\n";
            $response .= "3. **Hauptteil**: Teile deine Inhalte in 3-5 klare Abschnitte ein\n";
            $response .= "4. **Call-to-Action**: Fordere zum Abonnieren, Liken und Kommentieren auf\n";
            $response .= "5. **Outro**: Verweise auf verwandte Videos oder deine anderen Kanäle\n\n";
            $response .= "Halte dein Skript konversationell, aber präzise. Verwende kurze Sätze und eine aktive Sprache.";
        }
        elseif (strpos($lowercasePrompt, 'seo') !== false || strpos($lowercasePrompt, 'keyword') !== false) {
            // Anfrage zu SEO/Keywords
            $response .= "**YouTube SEO-Grundlagen:**\n\n";
            $response .= "1. Verwende ein Hauptkeyword im Titel, idealerweise am Anfang\n";
            $response .= "2. Füge 3-5 relevante Keywords in deine Beschreibung ein (natürlich klingend)\n";
            $response .= "3. Füge 10-15 relevante Tags hinzu, beginnend mit deinem Hauptkeyword\n";
            $response .= "4. Erstelle aussagekräftige Thumbnails mit wenig Text und hohem Kontrast\n";
            $response .= "5. Füge im Video Untertitel hinzu und optimiere diese für Keywords\n\n";
            $response .= "Tools wie VidIQ, TubeBuddy oder KeywordTool.io können dir helfen, relevante Keywords zu finden.";
        }
        elseif (strpos($lowercasePrompt, 'thumbnail') !== false) {
            // Anfrage zu Thumbnails
            $response .= "**Effektive Thumbnails erstellen:**\n\n";
            $response .= "1. Verwende helle, kontrastreiche Farben, die sich vom YouTube-Interface abheben\n";
            $response .= "2. Zeige ausdrucksstarke Gesichter oder Emotionen, wenn möglich\n";
            $response .= "3. Beschränke Text auf 1-3 Wörter in großer, lesbarer Schrift\n";
            $response .= "4. Halte das Design konsistent mit deiner Kanalästhetik\n";
            $response .= "5. Teste verschiedene Varianten und analysiere die Performance\n\n";
            $response .= "Tools wie Canva oder Adobe Express bieten gute Vorlagen für YouTube-Thumbnails.";
        }
        else {
            // Generische Antwort für nicht kategorisierte Anfragen
            $response .= "Um auf YouTube erfolgreich zu sein, solltest du auf diese Kernelemente achten:\n\n";
            $response .= "1. **Qualität über Quantität**: Konzentriere dich auf gut produzierte Videos statt auf viele mittelmäßige\n";
            $response .= "2. **Konsistenz**: Veröffentliche regelmäßig und halte einen festen Zeitplan ein\n";
            $response .= "3. **Audience Retention**: Optimiere deine Videos für längere Wiedergabezeiten\n";
            $response .= "4. **Interaktion fördern**: Frage aktiv nach Kommentaren und interagiere mit deiner Community\n";
            $response .= "5. **Analyse**: Nutze YouTube Analytics, um zu verstehen, was bei deinem Publikum ankommt\n\n";
            $response .= "Wenn du eine spezifischere Frage hast, versuche es später noch einmal, wenn die Verbindung wiederhergestellt ist.";
        }
        
        // Wenn Kontext vorhanden, etwas Relevantes dazu hinzufügen
        if ($contextContent) {
            if (strpos($contextContent, 'YouTube-Kanal') !== false) {
                $response .= "\n\nFür die Optimierung deines Kanals empfehle ich, dein Branding konsistent zu halten und eine klare Nische zu definieren, damit Zuschauer genau wissen, was sie von dir erwarten können.";
            } else if (strpos($contextContent, 'Skript') !== false) {
                $response .= "\n\nWenn du an einem Skript arbeitest, versuche auch visuelle Elemente zu notieren und Übergänge zwischen Abschnitten zu planen, um den Fluss zu verbessern.";
            }
        }
        
        $response .= "\n\n_Hinweis: Dies ist eine Offline-Antwort. Bitte versuche es später erneut für eine personalisierte KI-Unterstützung._";
        
        return $response;
    }
    
    /**
     * Lade die Prompt-Bibliothek mit Caching
     */
    public function getPromptLibrary()
    {
        // Cache für 24 Stunden
        return Cache::remember('prompt_library', now()->addHours(24), function () {
            return [
                [
                    'category' => 'Video-Ideen',
                    'prompts' => [
                        'Erstelle mir 10 Video-Ideen für einen Kanal über [Thema].',
                        'Welche Videoinhalte würden gut zum aktuellen Trend [Trend] passen?',
                        'Ich suche virale Video-Ideen für meine Zielgruppe: [Zielgruppe].',
                        'Wie kann ich das Thema [Thema] in einer unterhaltsamen Serie darstellen?'
                    ]
                ],
                [
                    'category' => 'Skript-Entwicklung',
                    'prompts' => [
                        'Schreibe ein Skript-Intro für ein Video über [Thema].',
                        'Entwickle eine Hook für ein Video über [Thema], die Aufmerksamkeit erregt.',
                        'Wie sollte ich mein Video über [Thema] strukturieren?',
                        'Erstelle einen Call-to-Action für mein Video über [Thema].'
                    ]
                ],
                [
                    'category' => 'SEO & Keywords',
                    'prompts' => [
                        'Welche Keywords sollte ich für ein Video über [Thema] verwenden?',
                        'Generiere eine SEO-optimierte Videobeschreibung für [Thema].',
                        'Wie kann ich meine CTR für Videos über [Thema] verbessern?',
                        'Erstelle 20 relevante Tags für ein Video über [Thema].'
                    ]
                ],
                [
                    'category' => 'Kanal-Wachstum',
                    'prompts' => [
                        'Wie kann ich die Watch-Time für meinen Kanal zu [Thema] erhöhen?',
                        'Welche Strategien helfen mir, schneller Abonnenten für [Nische] zu gewinnen?',
                        'Wie optimiere ich meine Playlist-Strategie für mehr Views?',
                        'Analysiere meine Kanalstatistiken und gib Optimierungsvorschläge.'
                    ]
                ],
                [
                    'category' => 'Monetarisierung',
                    'prompts' => [
                        'Welche Einnahmequellen neben AdSense kann ich für meinen Kanal erschließen?',
                        'Wie kann ich ein erfolreiches Sponsoring-Angebot für meine Videos erstellen?',
                        'Ideen für digitale Produkte, die zu meinem Kanal über [Thema] passen.',
                        'Wie baue ich eine Community auf, die meine Arbeit finanziell unterstützt?'
                    ]
                ]
            ];
        });
    }
}
