<template>
  <div class="min-h-screen bg-white">
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Profile Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dein Profil</h1>
        <p class="mt-3 text-xl text-gray-600 max-w-3xl mx-auto">
          Passe deine persönlichen Einstellungen an
        </p>
      </div>

      <!-- Profile Content -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <div v-if="error" class="mb-6 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ error }}
          </div>
          
          <div v-if="success" class="mb-6 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ success }}
          </div>

          <!-- Profile Picture -->
          <div class="flex items-center space-x-6 mb-8">
            <div class="shrink-0">
              <img 
                :src="profilePicture || '/default-profile.jpg'" 
                alt="Profile" 
                class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg"
              >
            </div>
            <label class="block">
              <span class="sr-only">Wähle ein Profilbild</span>
              <input 
                type="file" 
                @change="uploadProfilePicture" 
                class="block w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0
                  file:text-sm file:font-medium
                  file:bg-primary-50 file:text-primary-700
                  hover:file:bg-primary-100
                  cursor-pointer focus:outline-none"
              >
            </label>
          </div>

          <!-- Profile Form -->
          <form @submit.prevent="updateProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Benutzername</label>
                <input 
                  type="text" 
                  id="username" 
                  v-model="profile.username" 
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                >
              </div>

              <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Vollständiger Name</label>
                <input 
                  type="text" 
                  id="full_name" 
                  v-model="profile.full_name" 
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                >
              </div>
            </div>

            <div>
              <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
              <input 
                type="url" 
                id="website" 
                v-model="profile.website" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
              >
            </div>

            <div>
              <label for="about" class="block text-sm font-medium text-gray-700 mb-1">Über mich</label>
              <textarea 
                id="about" 
                v-model="profile.about" 
                rows="4" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
              ></textarea>
            </div>

            <div class="pt-4">
              <button 
                type="submit" 
                class="w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 text-white font-medium rounded-lg transition duration-300"
                :disabled="loading"
              >
                {{ loading ? 'Speichert...' : 'Änderungen speichern' }}
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Back to Dashboard -->
      <div class="mt-8 text-center">
        <router-link to="/dashboard" class="text-primary-600 hover:text-primary-800 font-medium">
          Zurück zum Dashboard
        </router-link>
      </div>
    </main>
  </div>
</template>

<script>
import { supabase } from '../supabase';
import { DatabaseService } from '../services/database';
import { getChannelDetails } from '../services/youtubeService';

export default {
  name: 'Profile',
  data() {
    return {
      profile: {
        username: '',
        full_name: '',
        website: '',
        about: ''
      },
      loading: false,
      error: null,
      success: null,
      profilePicture: ''
    };
  },
  async mounted() {
    await this.getProfile();
    await this.fetchProfilePicture();
  },
  methods: {
    async getProfile() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) return;

        this.loading = true;
        const profile = await DatabaseService.getProfile(session.user.id);
        if (profile) {
          this.profile = {
            username: profile.username || '',
            full_name: profile.full_name || '',
            website: profile.website || '',
            about: profile.about || ''
          };
        }
      } catch (error) {
        this.error = 'Fehler beim Laden des Profils';
        console.error('Error loading profile:', error);
      } finally {
        this.loading = false;
      }
    },
    async updateProfile() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) return;

        this.loading = true;
        this.error = null;
        this.success = null;

        await DatabaseService.updateProfile(session.user.id, this.profile);
        
        this.success = 'Profil erfolgreich aktualisiert';
      } catch (error) {
        this.error = 'Fehler beim Aktualisieren des Profils';
        console.error('Error updating profile:', error);
      } finally {
        this.loading = false;
      }
    },
    async fetchProfilePicture() {
      const { data: { session } } = await supabase.auth.getSession();
      if (!session) return;

      const { data, error } = await supabase
        .from('profiles')
        .select('avatar_url')
        .eq('id', session.user.id)
        .maybeSingle();
        
      if (!error && data) {
        this.profilePicture = data.avatar_url;
      }
    },
    async uploadProfilePicture(event) {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) return;

        const file = event.target.files[0];
        if (!file) return;
        
        const filePath = `public/${session.user.id}/${file.name}`;
        
        // Hochladen der Datei
        const { data: uploadData, error: uploadError } = await supabase.storage
          .from('avatars')
          .upload(filePath, file, { upsert: true });
          
        if (uploadError) {
          console.error('Upload error:', uploadError);
          return;
        }
        
        // URL abrufen
        const { data: urlData } = supabase.storage
          .from('avatars')
          .getPublicUrl(filePath);
          
        if (!urlData || !urlData.publicUrl) {
          console.error('URL error: No public URL available');
          return;
        }
        
        const publicURL = urlData.publicUrl;
        
        // Profildaten aktualisieren
        const { error: updateError } = await supabase
          .from('profiles')
          .update({ avatar_url: publicURL })
          .eq('id', session.user.id);
          
        if (updateError) {
          console.error('Update error:', updateError);
          return;
        }
        
        // UI aktualisieren
        this.profilePicture = publicURL;
        console.log('Profile picture updated successfully:', publicURL);
      } catch (err) {
        console.error('Error in uploadProfilePicture:', err);
      }
    },
    async addChannel(channelId) {
      try {
        const channelDetails = await getChannelDetails(channelId);
        // Handle channel details (e.g., save to database)
        console.log('Channel details:', channelDetails);
      } catch (error) {
        console.error('Error adding channel:', error);
      }
    }
  }
};
</script> 