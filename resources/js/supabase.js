import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY || import.meta.env.VITE_SUPABASE_KEY

let supabase;

try {
    if (!supabaseUrl) {
        throw new Error('❌ VITE_SUPABASE_URL fehlt in der .env-Datei');
    }
    
    if (!supabaseAnonKey) {
        throw new Error('❌ VITE_SUPABASE_ANON_KEY fehlt in der .env-Datei');
    }
    
    supabase = createClient(supabaseUrl, supabaseAnonKey, {
        auth: {
            persistSession: true,
            autoRefreshToken: true,
        }
    });
    
    // Mache Supabase global verfügbar für Debugging
    window.debugSupabase = supabase;
    console.log('✅ Supabase erfolgreich initialisiert und als debugSupabase verfügbar');
} catch (error) {
    console.error('Supabase Initialisierungsfehler:', error.message);
    
    // Zeige eine visuelle Fehlermeldung an, wenn wir im Browser sind
    if (typeof document !== 'undefined') {
        // Nach 1 Sekunde Verzögerung, damit die App zuerst laden kann
        setTimeout(() => {
            const errorBox = document.createElement('div');
            errorBox.style.position = 'fixed';
            errorBox.style.top = '10px';
            errorBox.style.left = '50%';
            errorBox.style.transform = 'translateX(-50%)';
            errorBox.style.backgroundColor = '#f8d7da';
            errorBox.style.color = '#721c24';
            errorBox.style.padding = '10px 20px';
            errorBox.style.borderRadius = '4px';
            errorBox.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            errorBox.style.zIndex = '9999';
            errorBox.style.maxWidth = '80%';
            errorBox.style.fontFamily = 'sans-serif';
            errorBox.innerHTML = `
                <strong>Supabase-Konfigurationsfehler</strong><br>
                ${error.message}<br>
                <small>Bitte überprüfe deine .env-Datei und starte die Anwendung neu.</small>
            `;
            document.body.appendChild(errorBox);
        }, 1000);
    }
    
    // Erstelle einen Mock-Client, der keine Fehler wirft, aber nichts tut
    supabase = {
        auth: { 
            getSession: () => Promise.resolve({ data: { session: null }, error: null }),
            onAuthStateChange: () => ({ data: null })
        },
        from: () => ({
            select: () => Promise.resolve({ data: [], error: null }),
            insert: () => Promise.resolve({ data: null, error: null }),
            update: () => Promise.resolve({ data: null, error: null }),
            delete: () => Promise.resolve({ data: null, error: null }),
        }),
        storage: { from: () => ({ upload: () => Promise.resolve({ data: null, error: null }) }) },
        rpc: () => Promise.resolve({ data: null, error: null })
    };
}

export { supabase }; 