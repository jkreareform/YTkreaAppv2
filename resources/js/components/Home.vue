<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <header class="relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="md:flex md:items-center md:justify-between">
          <div class="md:w-1/2 md:pr-12">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-gray-900 leading-tight">
              <span class="block">Steigere dein</span>
              <span class="block text-primary-600">YouTube-Wachstum</span>
              <span class="block">mit KreaContent</span>
        </h1>
            
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-3xl">
              Optimiere deine Inhalte, verstehe deine Zielgruppe und erstelle wirkungsvolle Videos mit unseren KI-gestützten Tools für Content-Creator.
            </p>
            
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
              <router-link to="/register" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300">
                Jetzt starten
              </router-link>
              <router-link to="/login" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-primary-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300">
                Anmelden
              </router-link>
            </div>
          </div>
          
          <div class="mt-10 md:mt-0 md:w-1/2">
            <img src="/images/hero-image.jpg" alt="KreaContent Dashboard" class="w-full rounded-xl shadow-2xl" 
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=KreaContent';">
          </div>
        </div>
      </div>
    </header>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-gray-900">Deine Vorteile mit KreaContent</h2>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            Alle Tools und Ressourcen, die du für erfolgreiches YouTube-Wachstum benötigst
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Feature 1 -->
          <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="p-3 bg-primary-100 rounded-lg inline-block mb-4">
              <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">KI-Gestützter Content-Chat</h3>
            <p class="text-gray-600">
              Generiere kreative Ideen, analysiere Trends und verbessere deine Videostrategie mit unserem intelligenten Chat-System.
            </p>
          </div>

          <!-- Feature 2 -->
          <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="p-3 bg-green-100 rounded-lg inline-block mb-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Kanaloptimierung</h3>
            <p class="text-gray-600">
              Personalisierte Empfehlungen zur Verbesserung deiner Inhalte, Metadaten und Kanalstrategie für mehr Reichweite.
            </p>
          </div>

          <!-- Feature 3 -->
          <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="p-3 bg-amber-100 rounded-lg inline-block mb-4">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Sales Funnel Generator</h3>
            <p class="text-gray-600">
              Erstelle maßgeschneiderte Marketing-Funnels, die dir helfen, deinen YouTube-Kanal in ein profitables Geschäft zu verwandeln.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-primary-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white">Starte jetzt deine YouTube-Wachstumsreise</h2>
        <p class="mt-4 text-xl text-white/90 max-w-3xl mx-auto">
          Schließe dich Tausenden von Content-Erstellern an, die mit KreaContent ihren Kanal auf die nächste Stufe bringen
        </p>
        <div class="mt-8">
          <router-link to="/register" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-base font-medium rounded-lg shadow-sm text-primary-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-600 focus:ring-white transition duration-300">
            Kostenlosen Account erstellen
            </router-link>
        </div>
      </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between">
          <div class="mb-8 md:mb-0">
            <h3 class="text-xl font-bold">KreaContent</h3>
            <p class="mt-2 text-gray-400 max-w-md">
              Die führende Plattform für YouTube-Wachstum und Content-Optimierung.
            </p>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
            <div>
              <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Produkt</h4>
              <ul class="mt-4 space-y-2">
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Features</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Preise</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Partner-Programm</a></li>
              </ul>
            </div>
            
            <div>
              <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Ressourcen</h4>
              <ul class="mt-4 space-y-2">
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Dokumentation</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Tutorials</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Blog</a></li>
              </ul>
            </div>
            
            <div>
              <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Kontakt</h4>
              <ul class="mt-4 space-y-2">
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Support</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Über uns</a></li>
                <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Impressum</a></li>
              </ul>
            </div>
          </div>
        </div>
        
        <div class="mt-12 border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p class="text-sm text-gray-400">&copy; 2024 KreaContent. Alle Rechte vorbehalten.</p>
          <div class="mt-4 md:mt-0 flex space-x-6">
            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
              <span class="sr-only">Facebook</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
              <span class="sr-only">Instagram</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
              <span class="sr-only">YouTube</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'Home'
};
</script> 