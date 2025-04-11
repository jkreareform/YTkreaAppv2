<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Titel Generator</h1>
        <p class="mt-2 text-gray-600">Erstellen Sie optimierte Titel für Ihre YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Topic Input -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Details</h2>
            <form @submit.prevent="generateTitles" class="space-y-6">
              <div>
                <label for="topic" class="block text-sm font-medium text-gray-700">Hauptthema</label>
                <div class="mt-1">
                  <input type="text"
                         id="topic"
                         v-model="topic"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. Photoshop Tutorial">
                </div>
              </div>

              <div>
                <label for="keywords" class="block text-sm font-medium text-gray-700">Wichtige Keywords</label>
                <div class="mt-1">
                  <input type="text"
                         id="keywords"
                         v-model="keywords"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. Anfänger, Effekte, Design">
                  <p class="mt-1 text-sm text-gray-500">Trennen Sie Keywords mit Kommas</p>
                </div>
              </div>

              <div>
                <label for="style" class="block text-sm font-medium text-gray-700">Titel-Stil</label>
                <select id="style"
                        v-model="style"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="how-to">How-To & Tutorial</option>
                  <option value="listicle">Listicle (Top 10, etc.)</option>
                  <option value="question">Frage & Antwort</option>
                  <option value="dramatic">Dramatisch & Clickbait</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !topic"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Generiere...' : 'Titel generieren' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Generated Titles -->
          <div v-if="generatedTitles" class="space-y-8">
            <!-- Title Suggestions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Titel-Vorschläge</h2>
              <div class="space-y-4">
                <div v-for="(title, index) in generatedTitles" :key="index"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <div class="flex-1">
                      <h3 class="text-base font-medium text-gray-900">{{ title.text }}</h3>
                      <div class="mt-1 flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                          {{ title.length }}/100 Zeichen
                        </span>
                        <span class="text-sm" :class="getScoreClass(title.score)">
                          Score: {{ title.score }}/100
                        </span>
                      </div>
                    </div>
                    <div class="flex items-center space-x-2">
                      <button @click="copyTitle(title.text)"
                              class="inline-flex items-center p-2 border border-transparent rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                      </button>
                      <button @click="saveTitle(title)"
                              class="inline-flex items-center p-2 border border-transparent rounded-full text-indigo-600 hover:bg-indigo-50 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Title Analysis -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Titel-Analyse</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="metric in titleMetrics" :key="metric.name"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-500">{{ metric.name }}</span>
                    <span class="text-sm font-medium" :class="getMetricClass(metric.value, metric.target)">
                      {{ metric.value }}{{ metric.unit }}
                    </span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full" 
                         :class="getMetricBarClass(metric.value, metric.target)"
                         :style="{ width: getMetricPercentage(metric.value, metric.target) + '%' }">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Titel-Tipps</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in titleTips" :key="index" class="flex gap-3">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                    {{ index + 1 }}
                  </div>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ tip }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Saved Titles -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Gespeicherte Titel</h2>
              <button @click="clearSavedTitles"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Alle löschen
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="(title, index) in savedTitles" :key="index"
                   class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-900">{{ title.text }}</div>
                  <div class="text-sm text-gray-500">Score: {{ title.score }}/100</div>
                </div>
                <button @click="removeSavedTitle(index)"
                        class="text-gray-400 hover:text-gray-500 ml-2">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
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
  name: 'TitleGenerator',

  data() {
    return {
      topic: '',
      keywords: '',
      style: 'how-to',
      loading: false,
      generatedTitles: null,
      savedTitles: [],
      titleTips: [
        'Verwenden Sie Zahlen und Listen (z.B. "Top 10", "5 beste")',
        'Fügen Sie emotionale Trigger-Wörter hinzu',
        'Halten Sie den Titel unter 60 Zeichen für optimale Anzeige',
        'Platzieren Sie wichtige Keywords am Anfang'
      ],
      titleMetrics: [
        {
          name: 'Länge',
          value: 0,
          target: 60,
          unit: ' Zeichen'
        },
        {
          name: 'Keywords',
          value: 0,
          target: 3,
          unit: ''
        },
        {
          name: 'CTR Potenzial',
          value: 0,
          target: 100,
          unit: '%'
        }
      ]
    }
  },

  methods: {
    async generateTitles() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Generierung stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.generatedTitles = [
          {
            text: '🔥 Photoshop Tutorial: Profi-Effekte in 10 Minuten (Anfänger-Guide 2024)',
            length: 65,
            score: 95
          },
          {
            text: 'Die 5 BESTEN Photoshop-Tricks für beeindruckende Designs',
            length: 55,
            score: 88
          },
          {
            text: 'Photoshop Anfänger? Diese 3 Effekte MUSST du kennen!',
            length: 50,
            score: 85
          }
        ]

        // Update metrics
        this.titleMetrics[0].value = this.generatedTitles[0].length
        this.titleMetrics[1].value = 2
        this.titleMetrics[2].value = 85
      } catch (error) {
        console.error('Error generating titles:', error)
      } finally {
        this.loading = false
      }
    },

    getScoreClass(score) {
      if (score >= 90) return 'text-green-600'
      if (score >= 70) return 'text-yellow-600'
      return 'text-red-600'
    },

    getMetricClass(value, target) {
      const percentage = (value / target) * 100
      if (percentage >= 90) return 'text-green-600'
      if (percentage >= 70) return 'text-yellow-600'
      return 'text-red-600'
    },

    getMetricBarClass(value, target) {
      const percentage = (value / target) * 100
      if (percentage >= 90) return 'bg-green-600'
      if (percentage >= 70) return 'bg-yellow-600'
      return 'bg-red-600'
    },

    getMetricPercentage(value, target) {
      return Math.min((value / target) * 100, 100)
    },

    copyTitle(title) {
      navigator.clipboard.writeText(title)
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
        })
        .catch(err => {
          console.error('Error copying title:', err)
        })
    },

    saveTitle(title) {
      if (!this.savedTitles.find(t => t.text === title.text)) {
        this.savedTitles.push(title)
      }
    },

    removeSavedTitle(index) {
      this.savedTitles.splice(index, 1)
    },

    clearSavedTitles() {
      this.savedTitles = []
    }
  }
}
</script> 