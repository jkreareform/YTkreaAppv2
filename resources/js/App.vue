<template>
  <div>
    <!-- Supabase Config Error -->
    <div v-if="supabaseConfigError" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-lg">
      <strong class="font-bold">Konfigurationsfehler!</strong>
      <span class="block sm:inline ml-2">{{ supabaseConfigError }}</span>
    </div>

    <!-- Navbar-Komponente -->
    <Navbar />

    <!-- Page Content -->
    <main class="pt-16">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
import { supabase } from './supabase';
import Navbar from './components/layout/Navbar.vue';

export default {
  name: 'App',
  components: {
    Navbar
  },
  data() {
    return {
      supabaseConfigError: null
    };
  },
  async mounted() {
    // Prüfe Supabase-Konfiguration
    try {
      // Prüfe, ob die Umgebungsvariablen vorhanden sind
      const supabaseUrl = import.meta.env.VITE_SUPABASE_URL;
      const supabaseKey = import.meta.env.VITE_SUPABASE_ANON_KEY || import.meta.env.VITE_SUPABASE_KEY;
      
      if (!supabaseUrl || !supabaseKey) {
        this.supabaseConfigError = 'Supabase-Konfiguration fehlt. Bitte prüfe die .env-Datei.';
        console.error('❌ Supabase-Konfigurationsfehler:', this.supabaseConfigError);
      }
    } catch (error) {
      this.supabaseConfigError = `Supabase-Fehler: ${error.message}`;
      console.error('❌ Supabase-Konfigurationsfehler:', error);
    }
  }
};
</script> 