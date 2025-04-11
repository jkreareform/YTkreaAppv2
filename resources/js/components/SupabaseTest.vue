<template>
  <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-4xl mx-auto my-8">
    <h1 class="text-2xl font-bold mb-6">Supabase Connection Test</h1>
    
    <div class="space-y-4">
      <!-- Status anzeigen -->
      <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
        <h2 class="font-bold mb-2">Status</h2>
        <div class="grid grid-cols-2 gap-2">
          <div>Version:</div>
          <div>{{ status.version }}</div>
          
          <div>Connected:</div>
          <div :class="status.connected ? 'text-green-600' : 'text-red-600'">
            {{ status.connected ? 'Ja' : 'Nein' }}
          </div>
          
          <div>Authenticated:</div>
          <div :class="status.authenticated ? 'text-green-600' : 'text-red-600'">
            {{ status.authenticated ? 'Ja' : 'Nein' }}
          </div>
          
          <div v-if="status.email">Email:</div>
          <div v-if="status.email">{{ status.email }}</div>
          
          <div v-if="status.userId">User ID:</div>
          <div v-if="status.userId">{{ status.userId }}</div>
          
          <div v-if="status.error">Error:</div>
          <div v-if="status.error" class="text-red-600">{{ status.error }}</div>
        </div>
      </div>
      
      <!-- Tabellenstatus -->
      <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
        <h2 class="font-bold mb-2">Tabellen-Status</h2>
        <div class="grid grid-cols-2 gap-2">
          <div>Idea Chats Table:</div>
          <div :class="tableStatus.exists ? 'text-green-600' : 'text-red-600'">
            {{ tableStatus.exists ? 'Existiert' : 'Existiert nicht' }}
          </div>
          
          <div v-if="tableStatus.count !== null">Anzahl Einträge:</div>
          <div v-if="tableStatus.count !== null">{{ tableStatus.count }}</div>
          
          <div v-if="tableStatus.error">Error:</div>
          <div v-if="tableStatus.error" class="text-red-600">{{ tableStatus.error }}</div>
        </div>
      </div>
      
      <!-- Aktionen -->
      <div class="flex flex-wrap gap-3">
        <button 
          @click="checkConnection" 
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Verbindung prüfen
        </button>
        
        <button 
          @click="login" 
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          :disabled="status.authenticated"
        >
          Anmelden
        </button>
        
        <button 
          @click="logout" 
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
          :disabled="!status.authenticated"
        >
          Abmelden
        </button>
        
        <button 
          @click="checkTable" 
          class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
        >
          Tabelle prüfen
        </button>
        
        <button 
          @click="createTable" 
          class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"
          :disabled="tableStatus.exists"
        >
          Tabelle erstellen
        </button>
        
        <button 
          @click="testInsert" 
          class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700"
        >
          Test-Eintrag erstellen
        </button>
        
        <button 
          @click="fetchData" 
          class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700"
        >
          Daten abrufen
        </button>
      </div>
      
      <!-- Logs -->
      <div class="mt-6">
        <h2 class="font-bold mb-2">Log</h2>
        <div class="bg-black text-green-400 p-4 rounded h-80 overflow-auto font-mono text-sm">
          <div v-for="(log, index) in logs" :key="index" class="mb-1">
            <span class="text-gray-500">{{ log.time }}</span> 
            <span :class="logTypeClass(log.type)">{{ log.message }}</span>
          </div>
        </div>
      </div>
      
      <!-- Daten anzeigen -->
      <div v-if="retrievedData.length > 0" class="mt-6">
        <h2 class="font-bold mb-2">Abgerufene Daten</h2>
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded max-h-60 overflow-auto">
          <div v-for="(item, index) in retrievedData" :key="index" class="mb-4 p-2 border-b border-gray-300">
            <div><strong>ID:</strong> {{ item.id }}</div>
            <div><strong>Titel:</strong> {{ item.title }}</div>
            <div><strong>Benutzer:</strong> {{ item.user_id }}</div>
            <div><strong>Erstellt:</strong> {{ formatDate(item.created_at) }}</div>
            <div><strong>Nachrichten:</strong> {{ item.messages ? item.messages.length : 0 }} Messages</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { createClient } from '@supabase/supabase-js';
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'SupabaseTest',
  
  data() {
    return {
      supabase: null,
      
      status: {
        version: '@supabase/supabase-js v2',
        connected: false,
        authenticated: false,
        email: null,
        userId: null,
        error: null
      },
      
      tableStatus: {
        exists: false,
        count: null,
        error: null
      },
      
      logs: [],
      retrievedData: []
    };
  },
  
  async created() {
    this.log('info', 'Initialisiere Supabase Client...');
    
    try {
      const supabaseUrl = import.meta.env.VITE_SUPABASE_URL;
      const supabaseKey = import.meta.env.VITE_SUPABASE_ANON_KEY;
      
      if (!supabaseUrl || !supabaseKey) {
        throw new Error('Supabase URL oder Key fehlt in den Umgebungsvariablen!');
      }
      
      this.log('info', `Supabase URL: ${supabaseUrl.substring(0, 30)}...`);
      this.supabase = createClient(supabaseUrl, supabaseKey);
      this.log('success', 'Supabase Client initialisiert');
      
      this.status.connected = true;
      
      // Prüfe Session
      await this.checkConnection();
      
      // Prüfe Tabelle
      await this.checkTable();
      
    } catch (error) {
      this.log('error', `Initialisierungsfehler: ${error.message}`);
      this.status.error = error.message;
      this.status.connected = false;
    }
  },
  
  methods: {
    async checkConnection() {
      this.log('info', 'Prüfe Verbindung und Session...');
      
      try {
        const { data, error } = await this.supabase.auth.getSession();
        
        if (error) {
          throw error;
        }
        
        if (data && data.session) {
          this.status.authenticated = true;
          this.status.email = data.session.user.email;
          this.status.userId = data.session.user.id;
          this.log('success', `Eingeloggt als ${data.session.user.email}`);
        } else {
          this.status.authenticated = false;
          this.status.email = null;
          this.status.userId = null;
          this.log('warning', 'Nicht eingeloggt');
        }
        
      } catch (error) {
        this.log('error', `Fehler beim Prüfen der Session: ${error.message}`);
        this.status.error = error.message;
        this.status.authenticated = false;
      }
    },
    
    async login() {
      this.log('info', 'Öffne Login-Dialog...');
      try {
        const { data, error } = await this.supabase.auth.signInWithOAuth({
          provider: 'google',
          options: {
            redirectTo: window.location.href
          }
        });
        
        if (error) {
          throw error;
        }
        
        this.log('info', 'Login-Prozess gestartet, warte auf Redirect...');
      } catch (error) {
        this.log('error', `Login-Fehler: ${error.message}`);
      }
    },
    
    async logout() {
      this.log('info', 'Melde ab...');
      
      try {
        const { error } = await this.supabase.auth.signOut();
        
        if (error) {
          throw error;
        }
        
        this.status.authenticated = false;
        this.status.email = null;
        this.status.userId = null;
        this.log('success', 'Erfolgreich abgemeldet');
      } catch (error) {
        this.log('error', `Abmelde-Fehler: ${error.message}`);
      }
    },
    
    async checkTable() {
      this.log('info', 'Prüfe idea_chats Tabelle...');
      
      try {
        // Versuche eine einfache Abfrage
        const { data, error } = await this.supabase
          .from('idea_chats')
          .select('id')
          .limit(1);
          
        if (error) {
          if (error.code === '42P01') { // Relation does not exist
            this.tableStatus.exists = false;
            this.log('warning', 'Tabelle existiert nicht');
          } else {
            throw error;
          }
        } else {
          this.tableStatus.exists = true;
          this.log('success', 'Tabelle existiert');
          
          // Zähle Einträge
          const { count, error: countError } = await this.supabase
            .from('idea_chats')
            .select('*', { count: 'exact', head: true });
            
          if (countError) {
            this.log('error', `Fehler beim Zählen: ${countError.message}`);
          } else {
            this.tableStatus.count = count;
            this.log('info', `Tabelle enthält ${count} Einträge`);
          }
        }
      } catch (error) {
        this.log('error', `Fehler beim Prüfen der Tabelle: ${error.message}`);
        this.tableStatus.error = error.message;
      }
    },
    
    async createTable() {
      this.log('info', 'Erstelle idea_chats Tabelle...');
      
      try {
        // Da wir keine direkte Möglichkeit haben, tabellen über die JS API zu erstellen,
        // versuchen wir es über eine SQL-Funktion oder zeigen eine Anleitung
        this.log('warning', 'Tabellenerstellung muss in der Supabase-Konsole erfolgen');
        this.log('info', 'Führe dieses SQL in der Supabase SQL Editor aus:');
        
        const sql = `
CREATE TABLE IF NOT EXISTS public.idea_chats (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL,
  title TEXT NOT NULL,
  messages JSONB NOT NULL,
  context JSONB,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idea_chats_user_id_idx ON public.idea_chats(user_id);
CREATE INDEX IF NOT EXISTS idea_chats_updated_at_idx ON public.idea_chats(updated_at);

-- Enable Row Level Security
ALTER TABLE public.idea_chats ENABLE ROW LEVEL SECURITY;

-- Create policies for row level security
DROP POLICY IF EXISTS "Users can view own chats" ON public.idea_chats;
CREATE POLICY "Users can view own chats" ON public.idea_chats
  FOR SELECT USING (auth.uid() = user_id);

DROP POLICY IF EXISTS "Users can insert own chats" ON public.idea_chats;
CREATE POLICY "Users can insert own chats" ON public.idea_chats
  FOR INSERT WITH CHECK (auth.uid() = user_id);

DROP POLICY IF EXISTS "Users can update own chats" ON public.idea_chats;
CREATE POLICY "Users can update own chats" ON public.idea_chats
  FOR UPDATE USING (auth.uid() = user_id);

DROP POLICY IF EXISTS "Users can delete own chats" ON public.idea_chats;
CREATE POLICY "Users can delete own chats" ON public.idea_chats
  FOR DELETE USING (auth.uid() = user_id);
`;
        
        this.log('code', sql);
        
        // Optional: Versuche RPC-Aufruf, wenn eine entsprechende Funktion existiert
        try {
          const { error } = await this.supabase.rpc('create_idea_chats_table');
          if (error) {
            this.log('warning', `RPC Fehler: ${error.message}`);
            this.log('info', 'Bitte verwende das obige SQL-Script');
          } else {
            this.log('success', 'Tabelle erfolgreich erstellt via RPC');
            await this.checkTable();
          }
        } catch (rpcError) {
          this.log('warning', 'RPC-Funktion nicht verfügbar');
          this.log('info', 'Bitte verwende das obige SQL-Script');
        }
        
      } catch (error) {
        this.log('error', `Fehler bei Tabellenerstellung: ${error.message}`);
      }
    },
    
    async testInsert() {
      this.log('info', 'Erstelle Test-Eintrag...');
      
      try {
        // Prüfe Authentifizierung
        if (!this.status.authenticated) {
          this.log('warning', 'Nicht eingeloggt, verwende temporäre User ID');
          // Verwende eine temporäre ID für Tests
          var userId = 'test-user-' + uuidv4().substring(0, 8);
        } else {
          var userId = this.status.userId;
        }
        
        // Prüfe Tabelle
        if (!this.tableStatus.exists) {
          this.log('warning', 'Tabelle existiert möglicherweise nicht');
        }
        
        const testData = {
          id: uuidv4(),
          user_id: userId,
          title: `Test ${new Date().toLocaleTimeString()}`,
          messages: [
            { content: "Dies ist eine Test-Nachricht", isUser: true },
            { content: "Dies ist eine Test-Antwort", isUser: false }
          ],
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString()
        };
        
        this.log('info', `Einfügen mit ID: ${testData.id}`);
        
        const { data, error } = await this.supabase
          .from('idea_chats')
          .insert([testData]);
          
        if (error) {
          throw error;
        }
        
        this.log('success', 'Test-Eintrag erfolgreich erstellt!');
        await this.checkTable();
      } catch (error) {
        this.log('error', `Fehler beim Einfügen: ${error.message}`);
        if (error.details) {
          this.log('error', `Details: ${error.details}`);
        }
      }
    },
    
    async fetchData() {
      this.log('info', 'Rufe Daten ab...');
      
      try {
        // Wenn eingeloggt, nur eigene Daten, sonst alle
        let query = this.supabase
          .from('idea_chats')
          .select('*')
          .order('updated_at', { ascending: false })
          .limit(10);
          
        if (this.status.authenticated) {
          this.log('info', `Rufe Daten für Benutzer ${this.status.userId} ab`);
          query = query.eq('user_id', this.status.userId);
        } else {
          this.log('warning', 'Nicht eingeloggt, versuche alle Daten abzurufen');
        }
        
        const { data, error } = await query;
          
        if (error) {
          throw error;
        }
        
        if (data && data.length > 0) {
          this.retrievedData = data;
          this.log('success', `${data.length} Einträge abgerufen`);
        } else {
          this.retrievedData = [];
          this.log('info', 'Keine Daten gefunden');
        }
      } catch (error) {
        this.log('error', `Fehler beim Abrufen: ${error.message}`);
      }
    },
    
    log(type, message) {
      const time = new Date().toLocaleTimeString();
      this.logs.unshift({ type, message, time });
      
      // Beschränke auf 100 Logs
      if (this.logs.length > 100) {
        this.logs = this.logs.slice(0, 100);
      }
      
      // Auch in die Konsole loggen
      switch (type) {
        case 'error':
          console.error(message);
          break;
        case 'warning':
          console.warn(message);
          break;
        case 'success':
          console.log('%c' + message, 'color: green');
          break;
        default:
          console.log(message);
      }
    },
    
    logTypeClass(type) {
      switch (type) {
        case 'error':
          return 'text-red-500';
        case 'warning':
          return 'text-yellow-500';
        case 'success':
          return 'text-green-500';
        case 'code':
          return 'text-blue-400';
        default:
          return 'text-green-400';
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleString();
    }
  }
};
</script> 