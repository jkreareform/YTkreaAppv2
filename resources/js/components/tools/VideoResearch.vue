<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Video Research</h1>
        <p class="mt-2 text-gray-600">Deep dive into top-performing videos to understand what makes them successful.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Video Input Section -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Analyze Video</h2>
            
            <form @submit.prevent="analyzeVideo" class="space-y-6">
              <div>
                <label for="videoUrl" class="block text-sm font-medium text-gray-700">Video URL</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                  <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                    youtube.com/watch?v=
                  </span>
                  <input type="text" 
                         id="videoUrl" 
                         v-model="videoId"
                         class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="dQw4w9WgXcQ">
                </div>
              </div>

              <div>
                <label for="analysisDepth" class="block text-sm font-medium text-gray-700">Analysis Depth</label>
                <select id="analysisDepth" 
                        v-model="analysisDepth"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="basic">Basic Analysis</option>
                  <option value="detailed">Detailed Analysis</option>
                  <option value="comprehensive">Comprehensive Analysis</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit" 
                        :disabled="loading || !videoId"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Analyzing...' : 'Analyze Video' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Results Section -->
          <div v-if="results" class="mt-8 space-y-8">
            <!-- Video Preview -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <div class="aspect-w-16 aspect-h-9">
                <iframe 
                  :src="'https://www.youtube.com/embed/' + videoId" 
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                  allowfullscreen
                  class="rounded-lg"
                ></iframe>
              </div>
              <div class="mt-4">
                <h3 class="text-lg font-medium text-gray-900">{{ results.title }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ results.views }} views • {{ results.publishedAt }}</p>
              </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Metrics</h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="metric in results.metrics" :key="metric.name" 
                     class="bg-gray-50 rounded-lg p-4">
                  <h4 class="text-sm font-medium text-gray-500">{{ metric.name }}</h4>
                  <p class="mt-1 text-2xl font-semibold text-gray-900">{{ metric.value }}</p>
                  <p class="mt-1 text-sm text-gray-500">{{ metric.context }}</p>
                </div>
              </div>
            </div>

            <!-- Content Analysis -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Content Analysis</h3>
              <div class="space-y-6">
                <!-- Title Analysis -->
                <div class="border-b border-gray-200 pb-6">
                  <h4 class="font-medium text-gray-900">Title Analysis</h4>
                  <div class="mt-2 space-y-2">
                    <p v-for="point in results.titleAnalysis" :key="point" 
                       class="text-sm text-gray-600 flex items-start gap-2">
                      <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ point }}
                    </p>
                  </div>
                </div>

                <!-- Thumbnail Analysis -->
                <div class="border-b border-gray-200 pb-6">
                  <h4 class="font-medium text-gray-900">Thumbnail Analysis</h4>
                  <div class="mt-2 space-y-2">
                    <p v-for="point in results.thumbnailAnalysis" :key="point" 
                       class="text-sm text-gray-600 flex items-start gap-2">
                      <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ point }}
                    </p>
                  </div>
                </div>

                <!-- Retention Analysis -->
                <div>
                  <h4 class="font-medium text-gray-900">Audience Retention</h4>
                  <div class="mt-4 h-64 bg-gray-50 rounded-lg p-4">
                    <!-- Hier würde ein Chart für die Audience Retention kommen -->
                    <div class="h-full flex items-center justify-center text-gray-500">
                      Audience Retention Chart
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Quick Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Analysis Tips</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in tips" :key="index" class="flex gap-3">
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

          <!-- Similar Videos -->
          <div v-if="results && results.similarVideos" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Similar Successful Videos</h2>
            <div class="space-y-4">
              <div v-for="video in results.similarVideos" :key="video.id" 
                   class="flex gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded-lg"
                   @click="analyzeVideo(video.id)">
                <img :src="video.thumbnail" class="h-20 w-36 object-cover rounded" :alt="video.title">
                <div>
                  <h3 class="text-sm font-medium text-gray-900 line-clamp-2">{{ video.title }}</h3>
                  <p class="text-xs text-gray-500">{{ video.views }} views</p>
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
  name: 'VideoResearch',

  data() {
    return {
      videoId: '',
      analysisDepth: 'detailed',
      loading: false,
      results: null,
      tips: [
        'Use full video URLs or just the video ID after "watch?v="',
        'Choose detailed analysis for more in-depth metrics',
        'Compare with similar videos in your niche',
        'Look for patterns in successful thumbnails and titles'
      ]
    }
  },

  methods: {
    async analyzeVideo(videoId = this.videoId) {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        // Für jetzt verwenden wir Mock-Daten
        await new Promise(resolve => setTimeout(resolve, 2000)) // Simuliere API-Aufruf
        
        this.results = {
          title: 'How to Build a Successful YouTube Channel in 2024',
          views: '1.2M',
          publishedAt: '2 months ago',
          metrics: [
            {
              name: 'Engagement Rate',
              value: '8.7%',
              context: 'Above average for niche'
            },
            {
              name: 'Avg. View Duration',
              value: '8:45',
              context: '73% retention rate'
            },
            {
              name: 'CTR',
              value: '12.3%',
              context: 'Top 10% in category'
            }
          ],
          titleAnalysis: [
            'Uses current year (2024) for relevance',
            'Contains actionable promise ("How to")',
            'Includes emotional trigger ("Successful")',
            'Optimal length (47 characters)'
          ],
          thumbnailAnalysis: [
            'High contrast colors grab attention',
            'Clear, readable text overlay',
            'Human element creates connection',
            'Professional design quality'
          ],
          similarVideos: [
            {
              id: 'abc123',
              title: '10 YouTube Success Secrets Nobody Tells You',
              thumbnail: 'https://via.placeholder.com/360x200',
              views: '857K'
            },
            {
              id: 'def456',
              title: 'YouTube Algorithm Explained (2024 Update)',
              thumbnail: 'https://via.placeholder.com/360x200',
              views: '1.1M'
            },
            {
              id: 'ghi789',
              title: 'How I Grew From 0 to 100K Subscribers',
              thumbnail: 'https://via.placeholder.com/360x200',
              views: '925K'
            }
          ]
        }
      } catch (error) {
        console.error('Error analyzing video:', error)
        // Hier würde Fehlerbehandlung stattfinden
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style>
.aspect-w-16 {
  position: relative;
  padding-bottom: 56.25%;
}

.aspect-w-16 iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style> 