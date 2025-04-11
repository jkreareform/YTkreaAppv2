<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Hook Generator</h1>
        <p class="mt-2 text-gray-600">Erstellen Sie fesselnde Einstiege für Ihre YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Video Details -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Details</h2>
            <form @submit.prevent="generateHooks" class="space-y-6">
              <div>
                <label for="topic" class="block text-sm font-medium text-gray-700">Videothema</label>
                <div class="mt-1">
                  <input type="text"
                         id="topic"
                         v-model="topic"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. Produktivitäts-Tipps für Content Creator">
                </div>
              </div>

              <div>
                <label for="target" class="block text-sm font-medium text-gray-700">Zielgruppe</label>
                <div class="mt-1">
                  <input type="text"
                         id="target"
                         v-model="target"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. YouTube Creator, Anfänger">
                </div>
              </div>

              <div>
                <label for="style" class="block text-sm font-medium text-gray-700">Hook-Stil</label>
                <select id="style"
                        v-model="style"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="problem">Problem-Lösung</option>
                  <option value="curiosity">Neugier wecken</option>
                  <option value="story">Story-basiert</option>
                  <option value="statistic">Überraschende Statistik</option>
                  <option value="challenge">Challenge/Test</option>
                </select>
              </div>

              <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Gewünschte Länge</label>
                <select id="duration"
                        v-model="duration"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="5">5 Sekunden</option>
                  <option value="10">10 Sekunden</option>
                  <option value="15">15 Sekunden</option>
                  <option value="20">20 Sekunden</option>
                </select>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !topic || !target"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Generiere...' : 'Hooks generieren' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Generated Hooks -->
          <div v-if="generatedHooks" class="space-y-8">
            <!-- Hook Suggestions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-6">Hook-Vorschläge</h2>
              <div class="space-y-6">
                <div v-for="(hook, index) in generatedHooks" :key="index"
                     class="bg-gray-50 rounded-lg p-6">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <div class="flex items-center space-x-2 mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                              :class="getStyleClass(hook.style)">
                          {{ getStyleLabel(hook.style) }}
                        </span>
                        <span class="text-sm text-gray-500">
                          ~{{ hook.duration }}s
                        </span>
                      </div>
                      <h3 class="text-base font-medium text-gray-900 mb-2">{{ hook.text }}</h3>
                      <div class="text-sm text-gray-500 space-y-2">
                        <div v-if="hook.visualTip" class="flex items-center space-x-2">
                          <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                          </svg>
                          <span>{{ hook.visualTip }}</span>
                        </div>
                        <div v-if="hook.audioTip" class="flex items-center space-x-2">
                          <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                          </svg>
                          <span>{{ hook.audioTip }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                      <button @click="copyHook(hook)"
                              class="inline-flex items-center p-2 border border-transparent rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                      </button>
                      <button @click="saveHook(hook)"
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
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Hook Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Hook-Tipps</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in hookTips" :key="index" class="flex gap-3">
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

          <!-- Saved Hooks -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Gespeicherte Hooks</h2>
              <button @click="clearSavedHooks"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Alle löschen
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="(hook, index) in savedHooks" :key="index"
                   class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-900">{{ hook.text }}</div>
                  <div class="text-sm text-gray-500">{{ getStyleLabel(hook.style) }} • {{ hook.duration }}s</div>
                </div>
                <button @click="removeSavedHook(index)"
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
  name: 'HookGenerator',

  data() {
    return {
      topic: '',
      target: '',
      style: 'problem',
      duration: '10',
      loading: false,
      generatedHooks: null,
      savedHooks: [],
      hookTips: [
        'Stellen Sie in den ersten 3 Sekunden eine fesselnde Frage',
        'Zeigen Sie sofort den Mehrwert für den Zuschauer',
        'Verwenden Sie starke visuelle Elemente',
        'Erzeugen Sie einen "Pattern Interrupt"'
      ]
    }
  },

  methods: {
    async generateHooks() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Generierung stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.generatedHooks = [
          {
            text: 'Verschwenden Sie 2 Stunden am Tag mit schlechter Planung? In diesem Video zeige ich Ihnen 3 Profi-Tools, die Ihre Produktivität SOFORT verdoppeln werden!',
            style: 'problem',
            duration: 15,
            visualTip: 'Zeigen Sie eine Zeitraffer-Aufnahme von chaotischer Arbeit',
            audioTip: 'Beginnen Sie mit einer überraschenden Statistik'
          },
          {
            text: 'Das EINE Tool, das erfolgreiche Content Creator täglich nutzen - und Sie wahrscheinlich noch nie davon gehört haben...',
            style: 'curiosity',
            duration: 10,
            visualTip: 'Zeigen Sie einen Screen mit vielen Tools, einer wird hervorgehoben',
            audioTip: 'Fügen Sie ein Spannungsaufbau-Soundeffekt hinzu'
          },
          {
            text: 'Ich habe 30 Tage lang JEDE Produktivitäts-App getestet. Das Ergebnis hat mich schockiert...',
            style: 'challenge',
            duration: 12,
            visualTip: 'Montage von App-Tests mit Zeitstempel',
            audioTip: 'Dramatische Musik mit plötzlichem Stop beim "schockiert"'
          }
        ]
      } catch (error) {
        console.error('Error generating hooks:', error)
      } finally {
        this.loading = false
      }
    },

    getStyleClass(style) {
      const classes = {
        problem: 'bg-red-100 text-red-800',
        curiosity: 'bg-purple-100 text-purple-800',
        story: 'bg-blue-100 text-blue-800',
        statistic: 'bg-green-100 text-green-800',
        challenge: 'bg-yellow-100 text-yellow-800'
      }
      return classes[style]
    },

    getStyleLabel(style) {
      const labels = {
        problem: 'Problem-Lösung',
        curiosity: 'Neugier',
        story: 'Story',
        statistic: 'Statistik',
        challenge: 'Challenge'
      }
      return labels[style]
    },

    copyHook(hook) {
      navigator.clipboard.writeText(hook.text)
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
        })
        .catch(err => {
          console.error('Error copying hook:', err)
        })
    },

    saveHook(hook) {
      if (!this.savedHooks.find(h => h.text === hook.text)) {
        this.savedHooks.push(hook)
      }
    },

    removeSavedHook(index) {
      this.savedHooks.splice(index, 1)
    },

    clearSavedHooks() {
      this.savedHooks = []
    }
  }
}
</script> 