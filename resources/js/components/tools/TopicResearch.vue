<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Topic Research</h1>
        <p class="mt-2 text-gray-600">Find trending topics and content opportunities in your niche.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Search Section -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Search Topics</h2>
            
            <form @submit.prevent="searchTopics" class="space-y-6">
              <div>
                <label for="niche" class="block text-sm font-medium text-gray-700">Your Niche</label>
                <div class="mt-1">
                  <input type="text" 
                         id="niche" 
                         v-model="niche"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="e.g. Tech Reviews, Cooking, Gaming">
                </div>
              </div>

              <div>
                <label for="keywords" class="block text-sm font-medium text-gray-700">Related Keywords</label>
                <div class="mt-1">
                  <input type="text" 
                         id="keywords" 
                         v-model="keywords"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="e.g. smartphone, review, unboxing">
                </div>
                <p class="mt-1 text-sm text-gray-500">Separate keywords with commas</p>
              </div>

              <div>
                <label for="timeRange" class="block text-sm font-medium text-gray-700">Time Range</label>
                <select id="timeRange" 
                        v-model="timeRange"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="last7days">Last 7 Days</option>
                  <option value="last30days">Last 30 Days</option>
                  <option value="last90days">Last 90 Days</option>
                  <option value="last12months">Last 12 Months</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit" 
                        :disabled="loading || !niche"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Searching...' : 'Search Topics' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Results Section -->
          <div v-if="results" class="mt-8 space-y-8">
            <!-- Trending Topics -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Trending Topics</h3>
              <div class="space-y-4">
                <div v-for="topic in results.trendingTopics" :key="topic.id"
                     class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <h4 class="text-base font-medium text-gray-900">{{ topic.title }}</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="getTrendClass(topic.trend)">
                      {{ topic.trend > 0 ? '↑' : '↓' }} {{ Math.abs(topic.trend) }}%
                    </span>
                  </div>
                  <p class="mt-1 text-sm text-gray-600">{{ topic.description }}</p>
                  <div class="mt-2 flex flex-wrap gap-2">
                    <span v-for="tag in topic.tags" :key="tag"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                      {{ tag }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Competition Analysis -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Competition Analysis</h3>
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Topic</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Competition</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Volume</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Opportunity</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="item in results.competitionAnalysis" :key="item.topic">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900">{{ item.topic }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <div class="flex items-center">
                          <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 rounded-full h-2" :style="{ width: item.competition + '%' }"></div>
                          </div>
                          <span class="ml-2 text-gray-500">{{ item.competition }}%</span>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.volume }}</td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span :class="getOpportunityClass(item.opportunity)">
                          {{ item.opportunity }}
                        </span>
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
          <!-- Quick Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Research Tips</h2>
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

          <!-- Saved Topics -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Saved Topics</h2>
              <button @click="clearSavedTopics" 
                      class="text-sm text-gray-500 hover:text-gray-700">
                Clear All
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="topic in savedTopics" :key="topic.id"
                   class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                <span class="text-sm text-gray-900">{{ topic.title }}</span>
                <button @click="removeSavedTopic(topic.id)"
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
  name: 'TopicResearch',

  data() {
    return {
      niche: '',
      keywords: '',
      timeRange: 'last30days',
      loading: false,
      results: null,
      savedTopics: [],
      tips: [
        'Be specific with your niche to get more relevant results',
        'Use multiple related keywords to broaden your search',
        'Look for topics with high volume and low competition',
        'Save interesting topics for later reference'
      ]
    }
  },

  methods: {
    async searchTopics() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        // Für jetzt verwenden wir Mock-Daten
        await new Promise(resolve => setTimeout(resolve, 2000)) // Simuliere API-Aufruf
        
        this.results = {
          trendingTopics: [
            {
              id: 1,
              title: 'AI Tools for Content Creators',
              description: 'Growing interest in AI-powered tools for video editing and content creation',
              trend: 45,
              tags: ['AI', 'Technology', 'Content Creation']
            },
            {
              id: 2,
              title: 'Sustainable Tech Reviews',
              description: 'Rising demand for eco-friendly tech product reviews and comparisons',
              trend: 28,
              tags: ['Tech Reviews', 'Sustainability', 'Green Tech']
            },
            {
              id: 3,
              title: 'Short-Form Video Strategy',
              description: 'Techniques for growing audience through YouTube Shorts and vertical video',
              trend: -12,
              tags: ['YouTube Shorts', 'Strategy', 'Growth']
            }
          ],
          competitionAnalysis: [
            {
              topic: 'AI Tools Overview',
              competition: 65,
              volume: '250K/month',
              opportunity: 'High'
            },
            {
              topic: 'Sustainable Tech',
              competition: 45,
              volume: '180K/month',
              opportunity: 'Medium'
            },
            {
              topic: 'YouTube Shorts Tips',
              competition: 85,
              volume: '500K/month',
              opportunity: 'Low'
            }
          ]
        }
      } catch (error) {
        console.error('Error searching topics:', error)
        // Hier würde Fehlerbehandlung stattfinden
      } finally {
        this.loading = false
      }
    },

    getTrendClass(trend) {
      return trend > 0 
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800'
    },

    getOpportunityClass(opportunity) {
      const classes = {
        'High': 'text-green-800 bg-green-100',
        'Medium': 'text-yellow-800 bg-yellow-100',
        'Low': 'text-red-800 bg-red-100'
      }
      return `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${classes[opportunity]}`
    },

    clearSavedTopics() {
      this.savedTopics = []
    },

    removeSavedTopic(topicId) {
      this.savedTopics = this.savedTopics.filter(topic => topic.id !== topicId)
    }
  }
}
</script> 