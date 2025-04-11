<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Performance Analyse</h1>
        <p class="mt-2 text-gray-600">Detaillierte Aufschlüsselung Ihrer Kanal- und Videoperformance.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Analysis Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Channel Selection -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Kanal & Video Auswahl</h2>
            <form @submit.prevent="analyzePerformance" class="space-y-6">
              <div>
                <label for="channelUrl" class="block text-sm font-medium text-gray-700">Kanal URL</label>
                <div class="mt-1">
                  <input type="text"
                         id="channelUrl"
                         v-model="channelUrl"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. https://youtube.com/c/IhrKanal">
                </div>
              </div>

              <div>
                <label for="timeRange" class="block text-sm font-medium text-gray-700">Zeitraum</label>
                <select id="timeRange"
                        v-model="timeRange"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="7">Letzte 7 Tage</option>
                  <option value="30">Letzte 30 Tage</option>
                  <option value="90">Letzte 90 Tage</option>
                  <option value="365">Letztes Jahr</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !channelUrl"
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

          <!-- Performance Metrics -->
          <div v-if="performanceData" class="space-y-8">
            <!-- Growth Metrics -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Wachstumsmetriken</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="metric in performanceData.growthMetrics" :key="metric.name"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between mb-2">
                    <span class="font-medium text-gray-900">{{ metric.name }}</span>
                    <div class="flex items-center">
                      <span :class="getGrowthClass(metric.growth)" class="text-sm font-medium">
                        {{ metric.growth > 0 ? '+' : '' }}{{ metric.growth }}%
                      </span>
                    </div>
                  </div>
                  <div class="text-2xl font-bold text-gray-900 mb-2">{{ metric.value }}</div>
                  <div class="text-sm text-gray-500">vs. vorheriger Zeitraum</div>
                </div>
              </div>
            </div>

            <!-- Video Performance -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Video Performance</h2>
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Video</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Aufrufe</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Watchtime</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">CTR</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Retention</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="video in performanceData.topVideos" :key="video.id">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm">
                        <div class="flex items-center">
                          <img :src="video.thumbnail" alt="" class="h-10 w-16 object-cover rounded">
                          <div class="ml-4">
                            <div class="font-medium text-gray-900">{{ video.title }}</div>
                            <div class="text-gray-500">{{ video.publishedAt }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ video.views }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ video.watchTime }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <div class="flex items-center">
                          <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 rounded-full h-2" :style="{ width: video.ctr + '%' }"></div>
                          </div>
                          <span class="ml-2 text-gray-500">{{ video.ctr }}%</span>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <div class="flex items-center">
                          <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 rounded-full h-2" :style="{ width: video.retention + '%' }"></div>
                          </div>
                          <span class="ml-2 text-gray-500">{{ video.retention }}%</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Key Insights -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Key Insights</h2>
            <div class="space-y-4">
              <div v-if="performanceData" v-for="insight in performanceData.insights" :key="insight.id"
                   class="p-4 rounded-lg" :class="getInsightClass(insight.type)">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <component :is="insight.icon" 
                             class="h-6 w-6"
                             :class="getInsightIconClass(insight.type)" />
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium" :class="getInsightTextClass(insight.type)">
                      {{ insight.title }}
                    </h3>
                    <div class="mt-1 text-sm text-gray-600">
                      {{ insight.description }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recommendations -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Empfehlungen</h2>
            <div class="space-y-4">
              <div v-for="(recommendation, index) in recommendations" :key="index"
                   class="flex gap-3">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                    {{ index + 1 }}
                  </div>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ recommendation }}</p>
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
  name: 'Breakdown',

  data() {
    return {
      channelUrl: '',
      timeRange: '30',
      loading: false,
      performanceData: null,
      recommendations: [
        'Optimieren Sie Ihre Thumbnails für Videos mit hoher Impression aber niedriger CTR',
        'Fokussieren Sie sich auf Inhalte mit hoher Zuschauerretention',
        'Planen Sie mehr Videos zu Themen, die überdurchschnittlich performen',
        'Verbessern Sie Ihre Titel basierend auf erfolgreichen Videos'
      ]
    }
  },

  methods: {
    async analyzePerformance() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.performanceData = {
          growthMetrics: [
            {
              name: 'Abonnenten',
              value: '12.5K',
              growth: 15
            },
            {
              name: 'Aufrufe',
              value: '458K',
              growth: 23
            },
            {
              name: 'Watchtime',
              value: '25.4K Std',
              growth: 18
            },
            {
              name: 'Engagement',
              value: '8.2%',
              growth: -5
            }
          ],
          topVideos: [
            {
              id: 1,
              title: 'Die besten YouTube Tools 2024',
              thumbnail: 'https://picsum.photos/160/90',
              publishedAt: 'Vor 2 Tagen',
              views: '24.5K',
              watchTime: '1.2K Std',
              ctr: 12.5,
              retention: 45
            },
            {
              id: 2,
              title: 'Content Strategie Tutorial',
              thumbnail: 'https://picsum.photos/160/90',
              publishedAt: 'Vor 5 Tagen',
              views: '18.2K',
              watchTime: '956 Std',
              ctr: 10.8,
              retention: 42
            },
            {
              id: 3,
              title: 'Thumbnail Design Masterclass',
              thumbnail: 'https://picsum.photos/160/90',
              publishedAt: 'Vor 1 Woche',
              views: '32.1K',
              watchTime: '1.8K Std',
              ctr: 15.2,
              retention: 48
            }
          ],
          insights: [
            {
              id: 1,
              type: 'success',
              icon: 'TrendingUpIcon',
              title: 'Starkes Wachstum',
              description: 'Ihre Aufrufe sind um 23% gestiegen - weiter so!'
            },
            {
              id: 2,
              type: 'warning',
              icon: 'ExclamationIcon',
              title: 'Engagement Rückgang',
              description: 'Das Engagement ist um 5% gesunken. Fördern Sie mehr Interaktion.'
            },
            {
              id: 3,
              type: 'info',
              icon: 'LightBulbIcon',
              title: 'Optimierungspotential',
              description: 'Tutorial-Videos performen überdurchschnittlich gut.'
            }
          ]
        }
      } catch (error) {
        console.error('Error analyzing performance:', error)
      } finally {
        this.loading = false
      }
    },

    getGrowthClass(growth) {
      return growth >= 0
        ? 'text-green-600'
        : 'text-red-600'
    },

    getInsightClass(type) {
      const classes = {
        success: 'bg-green-50',
        warning: 'bg-yellow-50',
        info: 'bg-blue-50'
      }
      return classes[type]
    },

    getInsightIconClass(type) {
      const classes = {
        success: 'text-green-400',
        warning: 'text-yellow-400',
        info: 'text-blue-400'
      }
      return classes[type]
    },

    getInsightTextClass(type) {
      const classes = {
        success: 'text-green-800',
        warning: 'text-yellow-800',
        info: 'text-blue-800'
      }
      return classes[type]
    }
  }
}
</script> 