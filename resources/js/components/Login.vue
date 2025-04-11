<template>
  <div class="min-h-screen bg-white">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Logo/Heading -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Willkommen bei KreaContent</h1>
        <p class="mt-3 text-xl text-gray-600 max-w-3xl mx-auto">
          Melde dich an, um deine YouTube-Wachstumsreise fortzusetzen
        </p>
      </div>
      
      <!-- Login Card -->
      <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Anmelden</h2>
            <p class="mt-2 text-gray-600">
              Oder
              <router-link :to="{ name: 'register' }" class="font-medium text-primary-600 hover:text-primary-800 transition duration-300">
                neuen Account erstellen
              </router-link>
            </p>
          </div>
          
          <!-- Success Message (für Logout) -->
          <div v-if="successMessage" class="p-4 mb-6 bg-green-50 border border-green-400 rounded-lg">
            <p class="text-sm text-green-700">{{ successMessage }}</p>
          </div>
          
          <form @submit.prevent="login" class="space-y-6" autocomplete="on">
            <div v-if="error" class="p-4 bg-red-50 border border-red-400 rounded-lg">
              <p class="text-sm text-red-700">{{ error }}</p>
            </div>
            
            <div class="space-y-4">
              <div>
                <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                <input 
                  id="email-address" 
                  name="email" 
                  type="email" 
                  autocomplete="email" 
                  required 
                  v-model="email"
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
                  autocomplete="current-password" 
                  required 
                  v-model="password"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Dein Passwort"
                >
              </div>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input 
                  id="remember-me" 
                  name="remember-me" 
                  type="checkbox" 
                  v-model="rememberMe"
                  class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                >
                <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                  Angemeldet bleiben
                </label>
              </div>

              <div>
                <router-link :to="{ name: 'forgot-password' }" class="text-sm font-medium text-primary-600 hover:text-primary-800 transition duration-300">
                  Passwort vergessen?
                </router-link>
              </div>
            </div>

            <div class="pt-4">
              <button 
                type="submit"
                class="w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 text-white font-medium rounded-lg transition duration-300"
              >
                <span class="inline-flex items-center">
                  <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                  </svg>
                  Anmelden
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
      error: null,
      successMessage: null
    };
  },
  created() {
    // Prüfe auf Logout-Nachricht in URL-Parametern
    if (this.$route.query.message) {
      this.successMessage = this.$route.query.message;
      
      // Entferne die Nachricht aus der URL
      if (window.history.replaceState) {
        const { protocol, host, pathname } = window.location;
        const url = `${protocol}//${host}${pathname}`;
        window.history.replaceState({ path: url }, '', url);
      }
    }
  },
  methods: {
    async login() {
      try {
        const { data, error } = await supabase.auth.signInWithPassword({
          email: this.email,
          password: this.password
        });

        if (error) throw error;

        // Erfolgreiche Anmeldung - Weiterleitung zum Dashboard
        this.$router.push({ name: 'dashboard' });
      } catch (error) {
        this.error = 'Ungültige Anmeldedaten. Bitte überprüfe deine E-Mail und dein Passwort.';
      }
    }
  }
};
</script> 