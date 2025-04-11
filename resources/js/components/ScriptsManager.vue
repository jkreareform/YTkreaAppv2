<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-6">Script-Manager</h1>

        <!-- Script hinzufügen -->
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
          <h2 class="text-lg font-medium mb-3">Neues Script hinzufügen</h2>
          <div class="flex flex-col md:flex-row gap-4">
            <input 
              v-model="newScript.title" 
              type="text" 
              placeholder="Script-Titel" 
              class="flex-1 p-2 border border-gray-300 rounded-md"
            />
            <button 
              @click="addScript" 
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
              :disabled="!newScript.title"
            >
              Script erstellen
            </button>
          </div>
        </div>

        <!-- Script-Liste -->
        <div v-if="loading" class="p-4 text-center text-gray-500">
          <div class="animate-spin inline-block w-6 h-6 border-2 border-current border-t-transparent rounded-full" aria-hidden="true"></div>
          <p>Daten werden geladen...</p>
        </div>
        
        <div v-else-if="scripts.length === 0" class="p-8 text-center bg-gray-50 rounded-lg">
          <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Keine Scripts vorhanden</h3>
          <p class="mt-1 text-gray-500">Erstelle dein erstes Script, um mit der Arbeit zu beginnen.</p>
        </div>
        
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Titel
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Erstellt am
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Aktionen
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="script in scripts" :key="script.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ script.title }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ formatDate(script.created_at) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="editScript(script)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                    Bearbeiten
                  </button>
                  <button @click="confirmDelete(script)" class="text-red-600 hover:text-red-900">
                    Löschen
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Bestätigungsdialog -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium mb-4">Script löschen</h3>
        <p class="mb-4">Bist du sicher, dass du das Script "{{ scriptToDelete.title }}" löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.</p>
        <div class="flex justify-end gap-3">
          <button @click="showDeleteConfirm = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
            Abbrechen
          </button>
          <button @click="deleteScript" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            Löschen
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'ScriptsManager',
  data() {
    return {
      scripts: [],
      loading: true,
      newScript: {
        title: ''
      },
      showDeleteConfirm: false,
      scriptToDelete: null
    };
  },
  async created() {
    await this.fetchScripts();
  },
  methods: {
    async fetchScripts() {
      try {
        this.loading = true;
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session) {
          this.$router.push('/login');
          return;
        }
        
        try {
          const { data, error } = await supabase
            .from('scripts')
            .select('*')
            .eq('user_id', session.user.id)
            .order('created_at', { ascending: false });
            
          if (error) {
            console.error('Fehler beim Laden der Scripts:', error);
            return;
          }
          
          this.scripts = data || [];
        } catch (error) {
          console.error('Fehler beim Abfragen der Datenbank:', error);
        } finally {
          this.loading = false;
        }
      } catch (error) {
        console.error('Fehler beim Überprüfen der Authentifizierung:', error);
        this.loading = false;
      }
    },
    async addScript() {
      if (!this.newScript.title.trim()) return;
      
      try {
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session) {
          this.$router.push('/login');
          return;
        }
        
        const { data, error } = await supabase
          .from('scripts')
          .insert([{
            title: this.newScript.title,
            user_id: session.user.id,
            content: ''
          }])
          .select();
          
        if (error) {
          console.error('Fehler beim Erstellen des Scripts:', error);
          return;
        }
        
        // Script zur Liste hinzufügen
        if (data && data.length > 0) {
          this.scripts.unshift(data[0]);
        }
        
        // Formular zurücksetzen
        this.newScript.title = '';
      } catch (error) {
        console.error('Fehler beim Erstellen des Scripts:', error);
      }
    },
    editScript(script) {
      // Hier könnte eine Weiterleitung zur Script-Editor-Seite erfolgen
      // this.$router.push(`/script-editor/${script.id}`);
      alert('Script-Editor für "' + script.title + '" wird in einer zukünftigen Version verfügbar sein.');
    },
    confirmDelete(script) {
      this.scriptToDelete = script;
      this.showDeleteConfirm = true;
    },
    async deleteScript() {
      if (!this.scriptToDelete) return;
      
      try {
        const { error } = await supabase
          .from('scripts')
          .delete()
          .eq('id', this.scriptToDelete.id);
          
        if (error) {
          console.error('Fehler beim Löschen des Scripts:', error);
          return;
        }
        
        // Script aus der Liste entfernen
        this.scripts = this.scripts.filter(s => s.id !== this.scriptToDelete.id);
        
        // Dialog schließen
        this.showDeleteConfirm = false;
        this.scriptToDelete = null;
      } catch (error) {
        console.error('Fehler beim Löschen des Scripts:', error);
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('de-DE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    }
  }
};
</script> 