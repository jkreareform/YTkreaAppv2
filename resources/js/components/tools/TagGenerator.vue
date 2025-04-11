<template>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Tag-Generator</h1>
          <p class="mt-2 text-gray-600">Erstellen Sie optimierte Tags für bessere YouTube-Sichtbarkeit.</p>
        </div>
  
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Input Section -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Video Details Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Details</h2>
              <form @submit.prevent="generateTags" class="space-y-6">
                <div>
                  <label for="title" class="block text-sm font-medium text-gray-700">Videotitel</label>
                  <div class="mt-1">
                    <input type="text"
                           id="title"
                           v-model="title"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="z.B. Die 5 besten Produktivitäts-Tools für YouTuber">
                  </div>
                </div>
  
                <div>
                  <label for="topic" class="block text-sm font-medium text-gray-700">Hauptthema</label>
                  <div class="mt-1">
                    <input type="text"
                           id="topic"
                           v-model="topic"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="z.B. Produktivität">
                  </div>
                </div>
  
                <div>
                  <label for="niche" class="block text-sm font-medium text-gray-700">Nische</label>
                  <select id="niche"
                          v-model="niche"
                          class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="tech">Tech & Software</option>
                    <option value="business">Business & Marketing</option>
                    <option value="education">Bildung & Tutorial</option>
                    <option value="entertainment">Unterhaltung</option>
                    <option value="lifestyle">Lifestyle & Vlogs</option>
                  </select>
                </div>
  
                <div>
                  <label for="keywords" class="block text-sm font-medium text-gray-700">Zusätzliche Keywords</label>
                  <div class="mt-1">
                    <textarea id="keywords"
                             v-model="keywords"
                             rows="3"
                             class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                             placeholder="Ein Keyword pro Zeile"></textarea>
                  </div>
                </div>
  
                <div class="flex justify-end">
                  <button type="submit"
                          :disabled="loading || !title || !topic"
                          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ loading ? 'Generiere...' : 'Tags generieren' }}
                  </button>
                </div>
              </form>
            </div>
  
            <!-- Generated Tags -->
            <div v-if="generatedTags" class="space-y-8">
              <!-- Primary Tags -->
              <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-xl font-semibold text-gray-900">Primäre Tags</h2>
                  <span class="text-sm text-gray-500">Hohe Relevanz & Suchvolumen</span>
                </div>
                <div class="flex flex-wrap gap-2">
                  <div v-for="(tag, index) in generatedTags.primary" :key="'primary-'+index"
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ tag }}
                    <button @click="copyTag(tag)" class="ml-2 text-indigo-600 hover:text-indigo-700">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
  
              <!-- Secondary Tags -->
              <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-xl font-semibold text-gray-900">Sekundäre Tags</h2>
                  <span class="text-sm text-gray-500">Mittlere Relevanz</span>
                </div>
                <div class="flex flex-wrap gap-2">
                  <div v-for="(tag, index) in generatedTags.secondary" :key="'secondary-'+index"
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ tag }}
                    <button @click="copyTag(tag)" class="ml-2 text-blue-600 hover:text-blue-700">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
  
              <!-- Long-tail Tags -->
              <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-xl font-semibold text-gray-900">Long-tail Tags</h2>
                  <span class="text-sm text-gray-500">Spezifische Nischen-Tags</span>
                </div>
                <div class="flex flex-wrap gap-2">
                  <div v-for="(tag, index) in generatedTags.longTail" :key="'longTail-'+index"
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    {{ tag }}
                    <button @click="copyTag(tag)" class="ml-2 text-green-600 hover:text-green-700">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Sidebar -->
          <div class="space-y-8">
            <!-- Tag Tips -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Tag-Tipps</h2>
              <div class="space-y-4">
                <div v-for="(tip, index) in tagTips" :key="index" class="flex gap-3">
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
  
            <!-- Tag Stats -->
            <div v-if="tagStats" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Tag-Statistiken</h2>
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-500">Gesamtanzahl Tags</span>
                  <span class="text-sm font-medium text-gray-900">{{ tagStats.total }}/500</span>
                </div>
                <div>
                  <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Zeichennutzung</span>
                    <span class="font-medium text-gray-900">{{ tagStats.charCount }}/500</span>
                  </div>
                  <div class="mt-1">
                    <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-100">
                      <div :style="{ width: (tagStats.charCount / 500 * 100) + '%' }"
                           :class="getUsageClass(tagStats.charCount, 500)"
                           class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center"></div>
                    </div>
                  </div>
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
    name: 'TagGenerator',
  
    data() {
      return {
        title: '',
        topic: '',
        niche: 'tech',
        keywords: '',
        loading: false,
        generatedTags: null,
        tagStats: null,
        tagTips: [
          'Verwenden Sie eine Mischung aus breiten und spezifischen Tags',
          'Platzieren Sie die wichtigsten Tags am Anfang',
          'Nutzen Sie verschiedene Schreibweisen (z.B. Tutorial/Anleitung)',
          'Beachten Sie das Limit von 500 Zeichen'
        ]
      }
    },
  
    methods: {
      async generateTags() {
        this.loading = true
        
        try {
          // Hier würde die tatsächliche API-Generierung stattfinden
          await new Promise(resolve => setTimeout(resolve, 2000))
          
          this.generatedTags = {
            primary: [
              'produktivität youtube',
              'youtube tools',
              'content creator tools',
              'youtube produktivität',
              'youtube workflow'
            ],
            secondary: [
              'youtube tipps deutsch',
              'produktiver arbeiten',
              'youtube organisation',
              'content creation tools',
              'youtube automation'
            ],
            longTail: [
              'beste tools für youtuber 2024',
              'youtube workflow optimierung',
              'produktivität steigern youtube',
              'youtube content workflow',
              'youtube creator tools deutsch'
            ]
          }
  
          this.tagStats = {
            total: 15,
            charCount: 320
          }
        } catch (error) {
          console.error('Error generating tags:', error)
        } finally {
          this.loading = false
        }
      },
  
      copyTag(tag) {
        navigator.clipboard.writeText(tag)
          .then(() => {
            // Hier könnte eine Erfolgsmeldung angezeigt werden
          })
          .catch(err => {
            console.error('Error copying tag:', err)
          })
      },
  
      getUsageClass(current, max) {
        const percentage = (current / max) * 100
        if (percentage >= 90) return 'bg-red-500'
        if (percentage >= 70) return 'bg-yellow-500'
        return 'bg-green-500'
      }
    }
  }
  </script>