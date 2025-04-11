<template>
  <nav class="bg-white border-b border-gray-100 fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo - zum Dashboard wenn eingeloggt, sonst zur Home-Seite -->
          <div class="shrink-0 flex items-center">
            <router-link :to="{ name: isAuthenticated ? 'dashboard' : 'home' }">
              <h1 class="text-xl font-bold text-gray-800">KreaContent App</h1>
            </router-link>
          </div>

          <!-- Navigation Links - nur wenn eingeloggt -->
          <div v-if="isAuthenticated" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <router-link :to="{ name: 'dashboard' }"
              class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              Dashboard
            </router-link>
            <router-link :to="{ name: 'idea-chat' }"
              class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              Ideen-Chat
            </router-link>
            <router-link :to="{ name: 'profile' }"
              class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              Profil
            </router-link>
          </div>
        </div>

        <!-- Auth Buttons - immer sichtbar -->
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <template v-if="isAuthenticated">
            <div class="relative">
              <button @click="toggleDropdown" class="flex items-center text-sm focus:outline-none">
                <img class="h-8 w-8 rounded-full" :src="profilePicture || '/default-profile.jpg'" alt="Profile">
              </button>
              <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                <router-link :to="{ name: 'profile' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</router-link>
                <button @click="logout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
              </div>
            </div>
          </template>
          <template v-else>
            <router-link :to="{ name: 'login' }"
              class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
              Anmelden
            </router-link>
            <router-link :to="{ name: 'register' }"
              class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
              Registrieren
            </router-link>
          </template>
        </div>

        <!-- Mobile menu button -->
        <div class="flex items-center sm:hidden">
          <button @click="toggleMobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, toggle based on menu state -->
    <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <template v-if="isAuthenticated">
          <router-link :to="{ name: 'dashboard' }" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Dashboard
          </router-link>
          <router-link :to="{ name: 'idea-chat' }" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Ideen-Chat
          </router-link>
          <router-link :to="{ name: 'profile' }" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Profil
          </router-link>
          <button @click="logout" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Logout
          </button>
        </template>
        <template v-else>
          <router-link :to="{ name: 'login' }" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Anmelden
          </router-link>
          <router-link :to="{ name: 'register' }" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
            Registrieren
          </router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script>
import { supabase } from '../../supabase';

export default {
  name: 'Navbar',
  data() {
    return {
      isAuthenticated: false,
      mobileMenuOpen: false,
      dropdownOpen: false,
      profilePicture: ''
    };
  },
  async mounted() {
    await this.checkAuth();
    await this.fetchProfilePicture();
    
    // Auth state listener
    supabase.auth.onAuthStateChange((event, session) => {
      this.isAuthenticated = !!session;
      if (session) {
        this.fetchProfilePicture();
      }
    });
    
    // Listener für Klicks außerhalb des Dropdown-Menüs
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  methods: {
    async checkAuth() {
      const { data: { session } } = await supabase.auth.getSession();
      this.isAuthenticated = !!session;
    },
    async logout() {
      const { error } = await supabase.auth.signOut();
      if (!error) {
        this.$router.push({ 
          name: 'login',
          query: { message: 'Erfolgreich ausgeloggt' }
        });
        this.dropdownOpen = false;
      }
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    handleClickOutside(event) {
      const dropdown = this.$el.querySelector('.relative');
      if (dropdown && !dropdown.contains(event.target)) {
        this.dropdownOpen = false;
      }
    },
    async fetchProfilePicture() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) {
          this.profilePicture = '/default-profile.jpg';
          return;
        }
        
        try {
          const { data, error } = await supabase
            .from('profiles')
            .select('avatar_url')
            .eq('id', session.user.id)
            .maybeSingle();
            
          if (error) {
            console.error('Error fetching profile picture:', error);
            this.profilePicture = '/default-profile.jpg';
            return;
          }
          
          // Direkte Zuweisung mit Fallback
          this.profilePicture = data?.avatar_url || '/default-profile.jpg';
        } catch (dbError) {
          console.error('Database error fetching profile picture:', dbError);
          this.profilePicture = '/default-profile.jpg';
        }
      } catch (err) {
        console.error('Error in fetchProfilePicture:', err);
        this.profilePicture = '/default-profile.jpg';
      }
    }
  }
};
</script>

<style scoped>
.router-link-active {
  @apply border-primary-500 text-primary-600;
}

/* Mobile menu active links */
.router-link-active.block {
  @apply border-primary-500 text-primary-600 bg-primary-50;
}
</style> 