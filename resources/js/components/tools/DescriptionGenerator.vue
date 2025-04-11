<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Beschreibungs-Generator</h1>
        <p class="mt-2 text-gray-600">Erstellen Sie SEO-optimierte Beschreibungen für Ihre YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Video Details Form -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Details</h2>
            <form @submit.prevent="generateDescription" class="space-y-6">
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
                <label for="keywords" class="block text-sm font-medium text-gray-700">Hauptschlüsselwörter</label>
                <div class="mt-1">
                  <input type="text"
                         id="keywords"
                         v-model="keywords"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="z.B. Produktivität, YouTube, Tools, Zeitmanagement">
                </div>
              </div>

              <div>
                <label for="mainPoints" class="block text-sm font-medium text-gray-700">Hauptpunkte des Videos</label>
                <div class="mt-1">
                  <textarea id="mainPoints"
                           v-model="mainPoints"
                           rows="4"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Wichtigste Punkte, die im Video behandelt werden"></textarea>
                </div>
              </div>

              <div>
                <label for="links" class="block text-sm font-medium text-gray-700">Wichtige Links</label>
                <div class="mt-1">
                  <textarea id="links"
                           v-model="links"
                           rows="3"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Website, Social Media, erwähnte Tools (einer pro Zeile)"></textarea>
                </div>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        :disabled="loading || !title || !keywords"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Generiere...' : 'Beschreibung generieren' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Generated Description -->
          <div v-if="generatedDescription" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Generierte Beschreibung</h2>
              <button @click="copyDescription"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Kopieren
              </button>
            </div>
            <div class="prose max-w-none">
              <div class="space-y-4 text-gray-700">
                <div v-for="(section, index) in generatedDescription" :key="index">
                  <h3 class="text-sm font-medium text-gray-900">{{ section.title }}</h3>
                  <div class="whitespace-pre-wrap text-sm">{{ section.content }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Description Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Beschreibungs-Tipps</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in descriptionTips" :key="index" class="flex gap-3">
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

          <!-- SEO Score -->
          <div v-if="seoScore" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">SEO Score</h2>
            <div class="space-y-4">
              <div class="flex items-center">
                <div class="flex-1">
                  <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-900">{{ seoScore.overall }}/100</span>
                    <span class="ml-2 text-sm" :class="getSeoScoreClass(seoScore.overall)">
                      {{ getSeoScoreLabel(seoScore.overall) }}
                    </span>
                  </div>
                  <div class="mt-1 text-sm text-gray-500">Gesamt-Score</div>
                </div>
              </div>
              <div class="space-y-3">
                <div v-for="(metric, index) in seoScore.metrics" :key="index"
                     class="flex items-center">
                  <div class="flex-1">
                    <div class="text-sm font-medium text-gray-900">{{ metric.label }}</div>
                    <div class="mt-1 relative">
                      <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-100">
                        <div :style="{ width: metric.score + '%' }"
                             :class="getSeoMetricClass(metric.score)"
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center"></div>
                      </div>
                    </div>
                  </div>
                  <div class="ml-4 text-sm font-medium" :class="getSeoScoreClass(metric.score)">
                    {{ metric.score }}%
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
  name: 'DescriptionGenerator',

  data() {
    return {
      title: '',
      keywords: '',
      mainPoints: '',
      links: '',
      loading: false,
      generatedDescription: null,
      seoScore: null,
      descriptionTips: [
        'Verwenden Sie die wichtigsten Keywords in den ersten 2-3 Sätzen',
        'Fügen Sie einen klaren Call-to-Action hinzu',
        'Strukturieren Sie mit Timestamps für bessere Navigation',
        'Verlinken Sie auf relevante Ressourcen und Social Media'
      ]
    }
  },

  methods: {
    async generateDescription() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Generierung stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.generatedDescription = [
          {
            title: '📝 Übersicht',
            content: `In diesem Video zeige ich euch die 5 besten Produktivitäts-Tools, die jeder YouTuber kennen sollte. Diese Tools haben mir geholfen, meine Produktivität zu verdoppeln und mehr qualitativ hochwertige Videos zu produzieren.`
          },
          {
            title: '⏱ Timestamps',
            content: `00:00 - Intro
02:15 - Tool #1: Projektmanagement
05:30 - Tool #2: Videoplanung
08:45 - Tool #3: Automatisierung
12:00 - Tool #4: Zeiterfassung
15:30 - Tool #5: Fokus & Konzentration
18:00 - Fazit & Bonus-Tipps`
          },
          {
            title: '🔍 Erwähnte Tools & Links',
            content: this.formatLinks(this.links)
          },
          {
            title: '🌟 Zusätzliche Ressourcen',
            content: `💡 Kostenlose Produktivitäts-Checkliste: [Link]
📱 Folgt mir für mehr Tipps:
   → Instagram: @username
   → Twitter: @username
   → Website: www.example.com`
          }
        ]

        this.seoScore = {
          overall: 85,
          metrics: [
            { label: 'Keyword-Optimierung', score: 90 },
            { label: 'Lesbarkeit', score: 85 },
            { label: 'Länge & Struktur', score: 80 },
            { label: 'Links & CTAs', score: 85 }
          ]
        }
      } catch (error) {
        console.error('Error generating description:', error)
      } finally {
        this.loading = false
      }
    },

    formatLinks(links) {
      if (!links) return ''
      return links.split('\n')
        .filter(link => link.trim())
        .map(link => `→ ${link.trim()}`)
        .join('\n')
    },

    copyDescription() {
      if (!this.generatedDescription) return

      const fullDescription = this.generatedDescription
        .map(section => `${section.title}\n\n${section.content}`)
        .join('\n\n')

      navigator.clipboard.writeText(fullDescription)
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
        })
        .catch(err => {
          console.error('Error copying description:', err)
        })
    },

    getSeoScoreClass(score) {
      if (score >= 80) return 'text-green-600'
      if (score >= 60) return 'text-yellow-600'
      return 'text-red-600'
    },

    getSeoMetricClass(score) {
      if (score >= 80) return 'bg-green-500'
      if (score >= 60) return 'bg-yellow-500'
      return 'bg-red-500'
    },

    getSeoScoreLabel(score) {
      if (score >= 80) return 'Sehr gut'
      if (score >= 60) return 'Gut'
      return 'Verbesserungswürdig'
    }
  }
}
</script> 