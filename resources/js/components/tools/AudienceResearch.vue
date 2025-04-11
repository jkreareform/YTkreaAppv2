<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Zielgruppen-Analyse</h1>
        <p class="mt-2 text-gray-600">Verstehen Sie Ihre YouTube-Zielgruppe und optimieren Sie Ihre Inhalte.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Analysis Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Channel Input -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Kanal auswählen</h2>
            <form @submit.prevent="analyzeChannel" class="space-y-4">
              <div>
                <label for="channelUrl" class="block text-sm font-medium text-gray-700">Kanal URL oder ID</label>
                <div class="mt-1">
                  <input type="text"
                         id="channelUrl"
                         v-model="channelUrl"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. https://youtube.com/c/IhrKanal">
                </div>
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

          <!-- Demographics -->
          <div v-if="audienceData" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Demografische Daten</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Age Distribution -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Altersverteilung</h3>
                <div class="space-y-3">
                  <div v-for="(percentage, age) in audienceData.ageDistribution" :key="age" class="flex items-center">
                    <span class="w-24 text-sm text-gray-500">{{ age }}</span>
                    <div class="flex-1">
                      <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" :style="{ width: percentage + '%' }"></div>
                      </div>
                    </div>
                    <span class="ml-3 text-sm text-gray-500">{{ percentage }}%</span>
                  </div>
                </div>
              </div>

              <!-- Gender Distribution -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Geschlechterverteilung</h3>
                <div class="flex items-center justify-center h-48">
                  <div class="w-48 h-48 relative">
                    <!-- Placeholder for a pie chart - would be replaced with a proper chart library -->
                    <div class="absolute inset-0 flex items-center justify-center">
                      <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ audienceData.genderDistribution.male }}%</div>
                        <div class="text-sm text-gray-500">Männlich</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Interests & Behavior -->
          <div v-if="audienceData" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Interessen & Verhalten</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Top Interests -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Top Interessen</h3>
                <div class="space-y-3">
                  <div v-for="interest in audienceData.interests" :key="interest.name"
                       class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                      <div class="font-medium text-gray-900">{{ interest.name }}</div>
                      <div class="text-sm text-gray-500">{{ interest.description }}</div>
                    </div>
                    <div class="ml-4">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ interest.percentage }}%
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Viewing Habits -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sehgewohnheiten</h3>
                <div class="space-y-4">
                  <div v-for="habit in audienceData.viewingHabits" :key="habit.name" class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                      <span class="font-medium text-gray-900">{{ habit.name }}</span>
                      <span class="text-sm text-gray-500">{{ habit.value }}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full">
                      <div class="h-full bg-indigo-600 rounded-full" :style="{ width: habit.percentage + '%' }"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Quick Stats -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Schnellübersicht</h2>
            <div class="space-y-4">
              <div v-if="audienceData" v-for="stat in quickStats" :key="stat.label" 
                   class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 flex items-center justify-center rounded-lg" :class="stat.bgColor">
                    <component :is="stat.icon" class="w-6 h-6" :class="stat.iconColor" />
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-500">{{ stat.label }}</div>
                  <div class="text-lg font-semibold text-gray-900">{{ stat.value }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recommendations -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Empfehlungen</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in recommendations" :key="index" class="flex gap-3">
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AudienceResearch',

  data() {
    return {
      channelUrl: '',
      loading: false,
      audienceData: null,
      recommendations: [
        'Passen Sie Ihre Veröffentlichungszeiten an die aktivsten Zeiten Ihrer Zielgruppe an',
        'Erstellen Sie Inhalte, die die Hauptinteressen Ihrer Zielgruppe ansprechen',
        'Optimieren Sie Ihre Thumbnails für die dominante Altersgruppe',
        'Berücksichtigen Sie kulturelle Präferenzen in Ihren Videos'
      ]
    }
  },

  computed: {
    quickStats() {
      if (!this.audienceData) return []
      
      return [
        {
          label: 'Durchschn. Wiedergabezeit',
          value: this.audienceData.avgWatchTime,
          bgColor: 'bg-blue-50',
          iconColor: 'text-blue-600',
          icon: 'ClockIcon'
        },
        {
          label: 'Interaktionsrate',
          value: this.audienceData.engagementRate + '%',
          bgColor: 'bg-green-50',
          iconColor: 'text-green-600',
          icon: 'ChartBarIcon'
        },
        {
          label: 'Wiederkehrende Zuschauer',
          value: this.audienceData.returningViewers + '%',
          bgColor: 'bg-purple-50',
          iconColor: 'text-purple-600',
          icon: 'UsersIcon'
        }
      ]
    }
  },

  methods: {
    async analyzeChannel() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Analyse stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.audienceData = {
          ageDistribution: {
            '13-17': 5,
            '18-24': 25,
            '25-34': 35,
            '35-44': 20,
            '45-54': 10,
            '55+': 5
          },
          genderDistribution: {
            male: 65,
            female: 35
          },
          interests: [
            {
              name: 'Technologie',
              description: 'Hardware, Software, Gadgets',
              percentage: 78
            },
            {
              name: 'Gaming',
              description: 'PC & Konsolen Spiele',
              percentage: 65
            },
            {
              name: 'Bildung',
              description: 'Tutorials & How-Tos',
              percentage: 45
            }
          ],
          viewingHabits: [
            {
              name: 'Mobile Nutzung',
              value: '4.2 Std/Woche',
              percentage: 75
            },
            {
              name: 'Desktop Nutzung',
              value: '2.8 Std/Woche',
              percentage: 45
            },
            {
              name: 'TV Nutzung',
              value: '1.5 Std/Woche',
              percentage: 25
            }
          ],
          avgWatchTime: '6:45 Min',
          engagementRate: 12.5,
          returningViewers: 45
        }
      } catch (error) {
        console.error('Error analyzing channel:', error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script> 