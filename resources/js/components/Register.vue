<template>
  <div class="min-h-screen bg-white">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Logo/Heading -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Starte deine Growth-Reise</h1>
        <p class="mt-3 text-xl text-gray-600 max-w-3xl mx-auto">
          Erstelle einen Account und beginne YouTube-Content zu optimieren
        </p>
      </div>
      
      <!-- Register Card -->
      <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Registrieren</h2>
            <p class="mt-2 text-gray-600">
              Oder
              <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-800 transition duration-300">
                bei bestehendem Account anmelden
              </router-link>
            </p>
            
            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
              <p class="text-sm text-blue-700">
                Nach der Registrierung wirst du durch einen kurzen Onboarding-Prozess geführt, um deine YouTube-Erfahrung zu personalisieren.
              </p>
            </div>
          </div>
          
          <form @submit.prevent="register" class="space-y-6">
            <div v-if="error" class="p-4 bg-red-50 border border-red-400 rounded-lg">
              <p class="text-sm text-red-700">{{ error }}</p>
            </div>
            
            <div class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input 
                  id="name" 
                  name="name" 
                  type="text" 
                  autocomplete="name" 
                  required 
                  v-model="form.name"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Dein vollständiger Name"
                >
              </div>
              
              <div>
                <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                <input 
                  id="email-address" 
                  name="email" 
                  type="email" 
                  autocomplete="email" 
                  required 
                  v-model="form.email"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="deine@email.de"
                >
              </div>
              
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passwort</label>
                <input 
                  id="password" 
                  name="password" 
                  type="password" 
                  autocomplete="new-password" 
                  required 
                  v-model="form.password"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Mindestens 8 Zeichen"
                >
              </div>
              
              <div>
                <label for="password-confirmation" class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                <input 
                  id="password-confirmation" 
                  name="password-confirmation" 
                  type="password" 
                  autocomplete="new-password" 
                  required 
                  v-model="form.password_confirmation"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Passwort wiederholen"
                >
              </div>
            </div>

            <div class="pt-4">
              <button 
                type="submit"
                class="w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 text-white font-medium rounded-lg transition duration-300"
                :disabled="loading"
              >
                <span class="inline-flex items-center">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  {{ loading ? 'Registrierung läuft...' : 'Account erstellen' }}
                </span>
              </button>
            </div>
            
            <p class="text-xs text-gray-500 text-center mt-4">
              Mit der Registrierung akzeptierst du unsere
              <a href="#" class="text-primary-600 hover:text-primary-800">Nutzungsbedingungen</a>
              und
              <a href="#" class="text-primary-600 hover:text-primary-800">Datenschutzrichtlinien</a>.
            </p>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'Register',
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      loading: false,
      error: null
    };
  },
  methods: {
    async register() {
      try {
        this.loading = true;
        this.error = null;
        
        // Validations
        if (!this.form.name || !this.form.email || !this.form.password) {
          this.error = 'Alle Felder müssen ausgefüllt werden.';
          return;
        }
        
        if (this.form.password !== this.form.password_confirmation) {
          this.error = 'Die Passwörter stimmen nicht überein.';
          return;
        }
        
        // Register with Supabase
        const { data, error } = await supabase.auth.signUp({
          email: this.form.email,
          password: this.form.password,
          options: {
            data: {
              name: this.form.name
            }
          }
        });
        
        if (error) {
          this.error = error.message;
          return;
        }
        
        console.log('User registered successfully');
        
        // Create initial user profile to mark as needing onboarding
        if (data && data.user) {
          try {
            const { error: profileError } = await supabase
              .from('profiles')
              .upsert([
                {
                  id: data.user.id,
                  onboarding_completed: false,
                  onboarding_seen: false
                }
              ], { 
                onConflict: 'id',
                returning: 'minimal'
              });
              
            if (profileError) {
              console.error('Error creating initial profile:', profileError);
            }
          } catch (profileError) {
            console.error('Error in profile creation:', profileError);
          }
          
          // Explicitly redirect to onboarding
          this.$router.push({ name: 'onboarding' });
        }
      } catch (err) {
        this.error = 'Ein unerwarteter Fehler ist aufgetreten.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    }
  }
};
</script> 