<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Channel Analysis</h1>
        <p class="mt-2 text-gray-600">Analyze successful channels in your niche to understand what works.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Channel Input Section -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Analyze Channel</h2>
            
            <form @submit.prevent="analyzeChannel" class="space-y-6">
              <div>
                <label for="channelUrl" class="block text-sm font-medium text-gray-700">Channel URL or ID</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                  <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                    youtube.com/
                  </span>
                  <input type="text" 
                         id="channelUrl" 
                         v-model="channelUrl"
                         class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="channel/UCxxxxxxxxxx">
                </div>
              </div>

              <div>
                <label for="analysisType" class="block text-sm font-medium text-gray-700">Analysis Type</label>
                <select id="analysisType" 
                        v-model="analysisType"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="overview">Channel Overview</option>
                  <option value="content">Content Strategy</option>
                  <option value="growth">Growth Analysis</option>
                  <option value="engagement">Engagement Metrics</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit" 
                        :disabled="loading"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Analyzing...' : 'Analyze Channel' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Results Section -->
          <div v-if="results" class="mt-8 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Analysis Results</h2>
            
            <!-- Channel Overview -->
            <div class="mb-8">
              <div class="flex items-center gap-4">
                <img :src="results.channelAvatar" class="w-16 h-16 rounded-full" alt="Channel Avatar">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">{{ results.channelName }}</h3>
                  <p class="text-sm text-gray-500">{{ results.subscriberCount }} subscribers</p>
                </div>
              </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="metric in results.metrics" :key="metric.name" 
                   class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500">{{ metric.name }}</h4>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ metric.value }}</p>
                <div class="mt-1 flex items-center">
                  <span :class="metric.trend > 0 ? 'text-green-600' : 'text-red-600'"
                        class="text-sm font-medium">
                    {{ metric.trend > 0 ? '↑' : '↓' }} {{ Math.abs(metric.trend) }}%
                  </span>
                  <span class="ml-2 text-sm text-gray-500">vs last period</span>
                </div>
              </div>
            </div>

            <!-- Content Analysis -->
            <div class="mt-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Content Analysis</h3>
              <div class="space-y-4">
                <div v-for="insight in results.insights" :key="insight.title"
                     class="bg-gray-50 rounded-lg p-4">
                  <h4 class="font-medium text-gray-900">{{ insight.title }}</h4>
                  <p class="mt-1 text-sm text-gray-600">{{ insight.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Quick Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Tips</h2>
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

          <!-- Save Analysis -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Save Analysis</h2>
            <p class="text-sm text-gray-600 mb-4">Save this analysis to reference later or compare with other channels.</p>
            <button @click="saveAnalysis" 
                    :disabled="!results"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
              Save Analysis
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ChannelAnalysis',

  data() {
    return {
      channelUrl: '',
      analysisType: 'overview',
      loading: false,
      results: null,
      tips: [
        'Enter a full channel URL or just the channel ID',
        'Choose different analysis types for different insights',
        'Compare multiple channels to spot trends',
        'Save analyses to track changes over time'
      ]
    }
  },

  methods: {
    async analyzeChannel() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        // Für jetzt verwenden wir Mock-Daten
        await new Promise(resolve => setTimeout(resolve, 2000)) // Simuliere API-Aufruf
        
        this.results = {
          channelName: 'Example Channel',
          channelAvatar: 'https://via.placeholder.com/150',
          subscriberCount: '100K',
          metrics: [
            {
              name: 'Average Views',
              value: '25.4K',
              trend: 12
            },
            {
              name: 'Engagement Rate',
              value: '8.2%',
              trend: -2
            },
            {
              name: 'Upload Frequency',
              value: '2.3/week',
              trend: 5
            },
            {
              name: 'Avg. Video Length',
              value: '12:45',
              trend: 8
            }
          ],
          insights: [
            {
              title: 'Most Successful Content Type',
              description: 'Tutorial videos perform 43% better than other content types'
            },
            {
              title: 'Optimal Upload Time',
              description: 'Videos published between 2-4 PM GMT get 27% more initial views'
            },
            {
              title: 'Engagement Patterns',
              description: 'Videos with custom thumbnails receive 62% more comments'
            }
          ]
        }
      } catch (error) {
        console.error('Error analyzing channel:', error)
        // Hier würde Fehlerbehandlung stattfinden
      } finally {
        this.loading = false
      }
    },

    async saveAnalysis() {
      // Hier würde die Analyse in der Datenbank gespeichert werden
      alert('Analysis saved successfully!')
    }
  }
}
</script> 