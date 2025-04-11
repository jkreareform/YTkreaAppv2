<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Keyword Research</h1>
        <p class="mt-2 text-gray-600">Finden Sie die besten Keywords für Ihre YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Search Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Keyword Input -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Keyword Analyse</h2>
            <form @submit.prevent="analyzeKeywords" class="space-y-6">
              <div>
                <label for="mainKeyword" class="block text-sm font-medium text-gray-700">Haupt-Keyword</label>
                <div class="mt-1">
                  <input type="text"
                         id="mainKeyword"
                         v-model="mainKeyword"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. YouTube Tutorial">
                </div>
              </div>

              <div>
                <label for="language" class="block text-sm font-medium text-gray-700">Sprache</label>
                <select id="language"
                        v-model="language"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="de">Deutsch</option>
                  <option value="en">Englisch</option>
                  <option value="es">Spanisch</option>
                  <option value="fr">Französisch</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !mainKeyword"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Analysiere...' : 'Analysieren' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Results Section -->
          <div v-if="keywordData" class="space-y-8">
            <!-- Related Keywords -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Verwandte Keywords</h2>
              <div class="space-y-4">
                <div v-for="keyword in keywordData.relatedKeywords" :key="keyword.term"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-base font-medium text-gray-900">{{ keyword.term }}</h3>
                      <p class="text-sm text-gray-500">{{ keyword.searchVolume }} monatliche Suchen</p>
                    </div>
                    <div class="flex items-center space-x-4">
                      <div class="text-sm">
                        <span class="font-medium" :class="getDifficultyClass(keyword.difficulty)">
                          {{ getDifficultyLabel(keyword.difficulty) }}
                        </span>
                      </div>
                      <button @click="saveKeyword(keyword)"
                              class="inline-flex items-center p-1 border border-transparent rounded-full text-indigo-600 hover:bg-indigo-50 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Search Intent -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Suchintention</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="intent in keywordData.searchIntent" :key="intent.type"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                      <div class="h-10 w-10 rounded-lg flex items-center justify-center"
                           :class="getIntentClass(intent.type)">
                        <component :is="intent.icon" class="h-6 w-6 text-white"/>
                      </div>
                    </div>
                    <div>
                      <h3 class="text-sm font-medium text-gray-900">{{ intent.type }}</h3>
                      <p class="text-sm text-gray-500">{{ intent.percentage }}% der Suchen</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Trend Analysis -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Trend Analyse</h2>
                <div class="flex space-x-2">
                  <button v-for="period in ['7D', '30D', '90D', '12M']" :key="period"
                          @click="changeTrendPeriod(period)"
                          class="px-3 py-1 text-sm rounded-md"
                          :class="trendPeriod === period ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'">
                    {{ period }}
                  </button>
                </div>
              </div>
              <div class="h-64 bg-gray-50 rounded-lg p-4">
                <!-- Hier würde das Trend-Chart eingefügt -->
                <div class="h-full flex items-center justify-center text-gray-500">
                  Trend Chart Platzhalter
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Quick Stats -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Keyword Übersicht</h2>
            <div v-if="keywordData" class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm text-gray-500">Suchvolumen</div>
                  <div class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ keywordData.overview.searchVolume }}
                  </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm text-gray-500">Schwierigkeit</div>
                  <div class="mt-1 text-2xl font-semibold" :class="getDifficultyClass(keywordData.overview.difficulty)">
                    {{ keywordData.overview.difficulty }}/100
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Saved Keywords -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Gespeicherte Keywords</h2>
              <button @click="clearSavedKeywords"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Alle löschen
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="keyword in savedKeywords" :key="keyword.term"
                   class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ keyword.term }}</div>
                  <div class="text-sm text-gray-500">{{ keyword.searchVolume }} Suchen</div>
                </div>
                <button @click="removeKeyword(keyword.term)"
                        class="text-gray-400 hover:text-gray-500">
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
  name: 'KeywordResearch',

  data() {
    return {
      mainKeyword: '',
      language: 'de',
      loading: false,
      keywordData: null,
      savedKeywords: [],
      trendPeriod: '30D'
    }
  },

  methods: {
    async analyzeKeywords() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.keywordData = {
          overview: {
            searchVolume: '45K',
            difficulty: 65
          },
          relatedKeywords: [
            {
              term: 'YouTube Tutorial erstellen',
              searchVolume: '12.5K',
              difficulty: 45
            },
            {
              term: 'YouTube Video bearbeiten',
              searchVolume: '28.3K',
              difficulty: 35
            },
            {
              term: 'YouTube Thumbnail Design',
              searchVolume: '15.7K',
              difficulty: 55
            }
          ],
          searchIntent: [
            {
              type: 'How-To',
              percentage: 45,
              icon: 'AcademicCapIcon'
            },
            {
              type: 'Informational',
              percentage: 30,
              icon: 'InformationCircleIcon'
            },
            {
              type: 'Commercial',
              percentage: 15,
              icon: 'ShoppingCartIcon'
            },
            {
              type: 'Navigational',
              percentage: 10,
              icon: 'SearchIcon'
            }
          ]
        }
      } catch (error) {
        console.error('Error analyzing keywords:', error)
      } finally {
        this.loading = false
      }
    },

    getDifficultyClass(difficulty) {
      if (difficulty <= 33) return 'text-green-600'
      if (difficulty <= 66) return 'text-yellow-600'
      return 'text-red-600'
    },

    getDifficultyLabel(difficulty) {
      if (difficulty <= 33) return 'Einfach'
      if (difficulty <= 66) return 'Mittel'
      return 'Schwer'
    },

    getIntentClass(type) {
      const classes = {
        'How-To': 'bg-blue-600',
        'Informational': 'bg-green-600',
        'Commercial': 'bg-purple-600',
        'Navigational': 'bg-indigo-600'
      }
      return classes[type]
    },

    saveKeyword(keyword) {
      if (!this.savedKeywords.find(k => k.term === keyword.term)) {
        this.savedKeywords.push(keyword)
      }
    },

    removeKeyword(term) {
      this.savedKeywords = this.savedKeywords.filter(k => k.term !== term)
    },

    clearSavedKeywords() {
      this.savedKeywords = []
    },

    changeTrendPeriod(period) {
      this.trendPeriod = period
      // Hier würde die Trend-Daten Aktualisierung stattfinden
    }
  }
}
</script> 