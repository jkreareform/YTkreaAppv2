<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Hilfe & Dokumentation</h1>
        <p class="mt-2 text-gray-600">Alles was Sie über die Nutzung der App wissen müssen.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Navigation -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 sticky top-8">
            <nav class="space-y-1">
              <a v-for="(section, index) in sections" 
                 :key="index"
                 :href="'#' + section.id"
                 class="block px-3 py-2 rounded-md text-sm font-medium"
                 :class="[
                   activeSection === section.id
                     ? 'bg-indigo-50 text-indigo-700'
                     : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                 ]">
                {{ section.title }}
              </a>
            </nav>
          </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3 space-y-8">
          <!-- Getting Started -->
          <div id="getting-started" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Erste Schritte</h2>
            <div class="prose max-w-none">
              <p class="text-gray-600 mb-4">
                Willkommen bei der YouTube Kreator App! Hier finden Sie eine Übersicht der wichtigsten Funktionen und wie Sie diese nutzen können.
              </p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div v-for="(step, index) in gettingStarted" :key="index"
                     class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                  <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                      {{ index + 1 }}
                    </div>
                  </div>
                  <div>
                    <h3 class="text-sm font-medium text-gray-900">{{ step.title }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ step.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Features -->
          <div id="features" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Funktionen</h2>
            <div class="space-y-6">
              <div v-for="(feature, index) in features" :key="index" class="border-b border-gray-200 pb-6 last:border-0">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100">
                      <component :is="feature.icon" class="h-6 w-6 text-indigo-600" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ feature.title }}</h3>
                    <p class="mt-1 text-gray-600">{{ feature.description }}</p>
                    <ul class="mt-2 space-y-2">
                      <li v-for="(tip, tipIndex) in feature.tips" :key="tipIndex"
                          class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ tip }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- FAQ -->
          <div id="faq" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Häufig gestellte Fragen</h2>
            <div class="space-y-4">
              <div v-for="(faq, index) in faqs" :key="index"
                   class="border-b border-gray-200 pb-4 last:border-0">
                <button @click="toggleFaq(index)"
                        class="flex justify-between items-center w-full text-left">
                  <h3 class="text-lg font-medium text-gray-900">{{ faq.question }}</h3>
                  <svg class="h-5 w-5 text-gray-500 transform transition-transform"
                       :class="{ 'rotate-180': openFaqs[index] }"
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <div v-show="openFaqs[index]" class="mt-2 text-gray-600">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>

          <!-- Support -->
          <div id="support" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Support</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Kontakt</h3>
                <p class="text-gray-600 mb-4">
                  Haben Sie Fragen oder benötigen Sie Hilfe? Unser Support-Team steht Ihnen zur Verfügung.
                </p>
                <a href="mailto:support@example.com"
                   class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                  <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  support@example.com
                </a>
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Dokumentation</h3>
                <p class="text-gray-600 mb-4">
                  Detaillierte Informationen und Anleitungen finden Sie in unserer Dokumentation.
                </p>
                <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                  <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                  </svg>
                  Zur Dokumentation
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Guide',

  data() {
    return {
      activeSection: 'getting-started',
      openFaqs: {},
      sections: [
        { id: 'getting-started', title: 'Erste Schritte' },
        { id: 'features', title: 'Funktionen' },
        { id: 'faq', title: 'FAQ' },
        { id: 'support', title: 'Support' }
      ],
      gettingStarted: [
        {
          title: 'Kanal hinzufügen',
          description: 'Fügen Sie Ihren YouTube-Kanal hinzu, um mit der Analyse zu beginnen.'
        },
        {
          title: 'Video analysieren',
          description: 'Wählen Sie ein Video aus, um detaillierte Einblicke zu erhalten.'
        },
        {
          title: 'Tools nutzen',
          description: 'Verwenden Sie unsere Tools für Titel, Beschreibungen und mehr.'
        },
        {
          title: 'Ergebnisse speichern',
          description: 'Speichern Sie Ihre Analysen und Optimierungen für späteren Zugriff.'
        }
      ],
      features: [
        {
          title: 'Kanal-Analyse',
          icon: 'ChartBarIcon',
          description: 'Detaillierte Einblicke in Ihre Kanal-Performance.',
          tips: [
            'Überwachung von Abonnenten-Wachstum',
            'Analyse von Video-Performance',
            'Engagement-Metriken'
          ]
        },
        {
          title: 'Video-Optimierung',
          icon: 'LightBulbIcon',
          description: 'Tools zur Optimierung Ihrer Videos.',
          tips: [
            'Titel-Generator mit KI-Unterstützung',
            'Beschreibungs-Optimierung',
            'Tag-Empfehlungen'
          ]
        },
        {
          title: 'Keyword-Recherche',
          icon: 'SearchIcon',
          description: 'Finden Sie die besten Keywords für Ihre Videos.',
          tips: [
            'Volumen-Analyse',
            'Schwierigkeitsgrad-Bewertung',
            'Trend-Analyse'
          ]
        }
      ],
      faqs: [
        {
          question: 'Wie füge ich einen YouTube-Kanal hinzu?',
          answer: 'Geben Sie einfach die URL Ihres YouTube-Kanals in das entsprechende Feld ein. Die App wird automatisch die Kanal-Informationen abrufen und analysieren.'
        },
        {
          question: 'Welche Daten werden analysiert?',
          answer: 'Wir analysieren öffentlich verfügbare Daten wie Abonnenten, Aufrufe, Engagement-Raten und mehr. Alle Analysen erfolgen unter Einhaltung der YouTube-Richtlinien.'
        },
        {
          question: 'Wie oft werden die Daten aktualisiert?',
          answer: 'Die Daten werden täglich aktualisiert. Sie können die Aktualisierung auch manuell durchführen, indem Sie auf den "Aktualisieren"-Button klicken.'
        }
      ]
    }
  },

  methods: {
    toggleFaq(index) {
      this.$set(this.openFaqs, index, !this.openFaqs[index])
    }
  },

  mounted() {
    // Intersection Observer für die Navigation
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.activeSection = entry.target.id
        }
      })
    }, { threshold: 0.5 })

    // Beobachte alle Sektionen
    this.sections.forEach(section => {
      const element = document.getElementById(section.id)
      if (element) observer.observe(element)
    })
  }
}
</script> 