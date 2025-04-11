<template>
  <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
    <!-- Logo -->
    <div class="mb-12">
      <div class="bg-black rounded-lg px-6 py-3 inline-block transform rotate-1">
        <h1 class="text-white text-2xl font-bold tracking-wider">GROWTH</h1>
      </div>
    </div>

    <!-- Thank You Card -->
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
          <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Vielen Dank!</h2>
        
        <p class="text-lg text-gray-600 mb-8">
          Dein Sales Script wurde erfolgreich angefordert und wird in Kürze an deine E-Mail-Adresse gesendet.
        </p>
        
        <div class="mt-6">
          <router-link 
            :to="{ name: 'dashboard' }" 
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Zurück zum Dashboard
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ThankYou'
};
</script> 