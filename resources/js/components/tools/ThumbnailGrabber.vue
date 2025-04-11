<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Thumbnail Grabber</h1>
        <p class="mt-2 text-gray-600">Laden und analysieren Sie Thumbnails von erfolgreichen YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input and Results Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- URL Input Form -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Video URL</h2>
            <form @submit.prevent="grabThumbnail" class="space-y-6">
              <div>
                <label for="videoUrl" class="block text-sm font-medium text-gray-700">YouTube Video URL oder ID</label>
                <div class="mt-1">
                  <input type="text"
                         id="videoUrl"
                         v-model="videoUrl"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="https://www.youtube.com/watch?v=...">
                </div>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !videoUrl"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Lade...' : 'Thumbnail laden' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Thumbnail Results -->
          <div v-if="thumbnailData" class="space-y-8">
            <!-- Preview Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Thumbnail Vorschau</h2>
                <div class="flex space-x-2">
                  <button v-for="quality in ['maxres', 'hq', 'mq', 'sd']" :key="quality"
                          @click="currentQuality = quality"
                          class="px-2 py-1 text-sm rounded-md"
                          :class="currentQuality === quality ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'">
                    {{ quality.toUpperCase() }}
                  </button>
                </div>
              </div>
              <div class="relative">
                <img :src="thumbnailData.urls[currentQuality]"
                     :alt="'Thumbnail ' + currentQuality"
                     class="w-full rounded-lg shadow-sm">
                <div class="absolute top-2 right-2 flex space-x-2">
                  <button @click="downloadThumbnail(currentQuality)"
                          class="inline-flex items-center p-2 bg-white rounded-full shadow-sm text-gray-700 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                  </button>
                  <button @click="copyThumbnailUrl(currentQuality)"
                          class="inline-flex items-center p-2 bg-white rounded-full shadow-sm text-gray-700 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-500">
                <div>
                  <span class="font-medium text-gray-700">Auflösung:</span>
                  {{ thumbnailData.dimensions[currentQuality].width }} x {{ thumbnailData.dimensions[currentQuality].height }}
                </div>
                <div>
                  <span class="font-medium text-gray-700">Dateigröße:</span>
                  {{ formatFileSize(thumbnailData.sizes[currentQuality]) }}
                </div>
              </div>
            </div>

            <!-- Analysis Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Thumbnail Analyse</h2>
              <div class="space-y-6">
                <!-- Color Analysis -->
                <div>
                  <h3 class="text-sm font-medium text-gray-900 mb-2">Farbpalette</h3>
                  <div class="flex space-x-2">
                    <div v-for="(color, index) in thumbnailData.analysis.colors" :key="index"
                         class="w-12 h-12 rounded-lg shadow-sm"
                         :style="{ backgroundColor: color }"
                         :title="color"></div>
                  </div>
                </div>

                <!-- Composition Analysis -->
                <div>
                  <h3 class="text-sm font-medium text-gray-900 mb-2">Komposition</h3>
                  <div class="grid grid-cols-2 gap-4">
                    <div v-for="(score, aspect) in thumbnailData.analysis.composition" :key="aspect"
                         class="bg-gray-50 rounded-lg p-3">
                      <div class="text-sm font-medium text-gray-700">{{ getCompositionLabel(aspect) }}</div>
                      <div class="mt-1 relative">
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                          <div :style="{ width: score + '%' }"
                               class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
                        </div>
                      </div>
                      <div class="mt-1 text-xs text-gray-500">{{ score }}%</div>
                    </div>
                  </div>
                </div>

                <!-- Recommendations -->
                <div>
                  <h3 class="text-sm font-medium text-gray-900 mb-2">Verbesserungsvorschläge</h3>
                  <ul class="space-y-2">
                    <li v-for="(tip, index) in thumbnailData.analysis.tips" :key="index"
                        class="flex items-start">
                      <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span class="text-sm text-gray-600">{{ tip }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Thumbnail Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Thumbnail-Tipps</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in thumbnailTips" :key="index" class="flex gap-3">
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

          <!-- Recent Thumbnails -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Letzte Thumbnails</h2>
              <button @click="clearHistory"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Verlauf löschen
              </button>
            </div>
            <div class="space-y-4">
              <div v-for="(thumb, index) in recentThumbnails" :key="index"
                   class="group relative rounded-lg overflow-hidden">
                <img :src="thumb.url" :alt="thumb.title" class="w-full h-24 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                  <button @click="reloadThumbnail(thumb.url)"
                          class="p-2 bg-white rounded-full text-gray-700 hover:text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                  </button>
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
  name: 'ThumbnailGrabber',

  data() {
    return {
      videoUrl: '',
      loading: false,
      currentQuality: 'maxres',
      thumbnailData: null,
      recentThumbnails: [],
      thumbnailTips: [
        'Verwenden Sie kontrastreiche Farben für bessere Sichtbarkeit',
        'Integrieren Sie Text, der auch in kleiner Größe lesbar ist',
        'Nutzen Sie emotionale Gesichtsausdrücke oder Reaktionen',
        'Halten Sie das Design klar und nicht überladen'
      ]
    }
  },

  methods: {
    async grabThumbnail() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Abfrage stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.thumbnailData = {
          urls: {
            maxres: 'https://example.com/maxres.jpg',
            hq: 'https://example.com/hq.jpg',
            mq: 'https://example.com/mq.jpg',
            sd: 'https://example.com/sd.jpg'
          },
          dimensions: {
            maxres: { width: 1920, height: 1080 },
            hq: { width: 1280, height: 720 },
            mq: { width: 720, height: 480 },
            sd: { width: 640, height: 480 }
          },
          sizes: {
            maxres: 524288, // 512KB
            hq: 262144, // 256KB
            mq: 131072, // 128KB
            sd: 65536 // 64KB
          },
          analysis: {
            colors: ['#FF5733', '#33FF57', '#5733FF', '#FFFF33'],
            composition: {
              contrast: 85,
              brightness: 70,
              textReadability: 90,
              focalPoint: 75
            },
            tips: [
              'Der Kontrast könnte in den dunklen Bereichen erhöht werden',
              'Text ist gut lesbar, aber könnte größer sein',
              'Gute Farbwahl für die Zielgruppe',
              'Klarer Fokuspunkt vorhanden'
            ]
          }
        }

        // Thumbnail zum Verlauf hinzufügen
        this.addToHistory({
          url: this.thumbnailData.urls.mq,
          title: 'Thumbnail ' + (this.recentThumbnails.length + 1)
        })
      } catch (error) {
        console.error('Error grabbing thumbnail:', error)
      } finally {
        this.loading = false
      }
    },

    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes'
      const k = 1024
      const sizes = ['Bytes', 'KB', 'MB']
      const i = Math.floor(Math.log(bytes) / Math.log(k))
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
    },

    getCompositionLabel(aspect) {
      const labels = {
        contrast: 'Kontrast',
        brightness: 'Helligkeit',
        textReadability: 'Textlesbarkeit',
        focalPoint: 'Fokuspunkt'
      }
      return labels[aspect]
    },

    downloadThumbnail(quality) {
      if (!this.thumbnailData) return
      // Hier würde der tatsächliche Download implementiert
      const link = document.createElement('a')
      link.href = this.thumbnailData.urls[quality]
      link.download = `thumbnail-${quality}.jpg`
      link.click()
    },

    copyThumbnailUrl(quality) {
      if (!this.thumbnailData) return
      navigator.clipboard.writeText(this.thumbnailData.urls[quality])
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
        })
        .catch(err => {
          console.error('Error copying URL:', err)
        })
    },

    addToHistory(thumbnail) {
      this.recentThumbnails.unshift(thumbnail)
      if (this.recentThumbnails.length > 4) {
        this.recentThumbnails.pop()
      }
    },

    reloadThumbnail(url) {
      this.videoUrl = url
      this.grabThumbnail()
    },

    clearHistory() {
      this.recentThumbnails = []
    }
  }
}
</script> 