<template>
  <div class="app-container">
    <!-- Navbar Komponente -->
    <Navbar />
    
    <!-- Hauptinhalt mit Router View -->
    <main class="main-content">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
import Navbar from './Navbar.vue';

export default {
  name: 'AppLayout',
  components: {
    Navbar
  }
}
</script>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #f9fafb;
}

.main-content {
  flex: 1;
  padding-top: 64px; /* Entspricht der Navbar-Höhe */
}
</style> 