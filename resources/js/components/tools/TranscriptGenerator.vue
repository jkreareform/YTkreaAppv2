<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Transkript-Generator</h1>
        <p class="mt-2 text-gray-600">Erstellen und bearbeiten Sie Transkripte für Ihre YouTube-Videos.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input and Results Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- URL Input Form -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Video URL</h2>
            <form @submit.prevent="generateTranscript" class="space-y-6">
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

              <div class="grid grid-cols-2 gap-4">
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

                <div>
                  <label for="format" class="block text-sm font-medium text-gray-700">Format</label>
                  <select id="format"
                          v-model="format"
                          class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="text">Nur Text</option>
                    <option value="timestamps">Mit Zeitstempeln</option>
                    <option value="srt">SRT Untertitel</option>
                    <option value="vtt">VTT Untertitel</option>
                  </select>
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
                  {{ loading ? 'Generiere...' : 'Transkript generieren' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Generated Transcript -->
          <div v-if="transcriptData" class="space-y-8">
            <!-- Editor Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Transkript Editor</h2>
                <div class="flex space-x-2">
                  <button @click="copyTranscript"
                          class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Kopieren
                  </button>
                  <button @click="downloadTranscript"
                          class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Herunterladen
                  </button>
                </div>
              </div>

              <div class="space-y-4">
                <div v-for="(segment, index) in transcriptData.segments" :key="index"
                     class="relative group">
                  <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50">
                    <div v-if="format !== 'text'" class="flex-shrink-0 w-24 text-sm text-gray-500">
                      {{ formatTimestamp(segment.start) }} - {{ formatTimestamp(segment.end) }}
                    </div>
                    <div class="flex-1">
                      <textarea v-model="segment.text"
                                rows="2"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                :placeholder="'Segment ' + (index + 1)"></textarea>
                    </div>
                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button @click="deleteSegment(index)"
                              class="text-gray-400 hover:text-red-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiken</h2>
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm font-medium text-gray-500">Wortanzahl</div>
                  <div class="mt-1 text-2xl font-semibold text-gray-900">{{ transcriptData.stats.wordCount }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm font-medium text-gray-500">Durchschn. Wörter/Min</div>
                  <div class="mt-1 text-2xl font-semibold text-gray-900">{{ transcriptData.stats.wordsPerMinute }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm font-medium text-gray-500">Segmente</div>
                  <div class="mt-1 text-2xl font-semibold text-gray-900">{{ transcriptData.segments.length }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="text-sm font-medium text-gray-500">Gesamtdauer</div>
                  <div class="mt-1 text-2xl font-semibold text-gray-900">{{ formatDuration(transcriptData.stats.duration) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Transcript Tips -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Transkript-Tipps</h2>
            <div class="space-y-4">
              <div v-for="(tip, index) in transcriptTips" :key="index" class="flex gap-3">
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

          <!-- Recent Transcripts -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Letzte Transkripte</h2>
              <button @click="clearHistory"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Verlauf löschen
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="(transcript, index) in recentTranscripts" :key="index"
                   class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-900">{{ transcript.title }}</div>
                  <div class="text-sm text-gray-500">{{ transcript.language }} • {{ transcript.format }}</div>
                </div>
                <button @click="reloadTranscript(transcript)"
                        class="text-gray-400 hover:text-indigo-600">
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
</template>

<script>
export default {
  name: 'TranscriptGenerator',

  data() {
    return {
      videoUrl: '',
      language: 'de',
      format: 'timestamps',
      loading: false,
      transcriptData: null,
      recentTranscripts: [],
      transcriptTips: [
        'Überprüfen Sie die Zeitstempel auf Genauigkeit',
        'Korrigieren Sie Namen und Fachbegriffe',
        'Achten Sie auf korrekte Interpunktion',
        'Teilen Sie lange Sätze in kürzere Segmente'
      ]
    }
  },

  methods: {
    async generateTranscript() {
      this.loading = true
      
      try {
        // Hier würde die tatsächliche API-Abfrage stattfinden
        await new Promise(resolve => setTimeout(resolve, 2000))
        
        this.transcriptData = {
          segments: [
            {
              start: 0,
              end: 5.2,
              text: 'Willkommen zu diesem Tutorial über Produktivitäts-Tools für Content Creator.'
            },
            {
              start: 5.2,
              end: 12.8,
              text: 'Heute zeige ich euch die fünf besten Tools, die mir geholfen haben, meine Produktivität zu verdoppeln.'
            },
            {
              start: 12.8,
              end: 18.4,
              text: 'Diese Tools sind nicht nur einfach zu bedienen, sondern auch erschwinglich für Anfänger.'
            }
          ],
          stats: {
            wordCount: 42,
            wordsPerMinute: 140,
            duration: 18.4
          }
        }

        // Transkript zum Verlauf hinzufügen
        this.addToHistory({
          title: 'Transkript ' + (this.recentTranscripts.length + 1),
          language: this.language,
          format: this.format,
          data: this.transcriptData
        })
      } catch (error) {
        console.error('Error generating transcript:', error)
      } finally {
        this.loading = false
      }
    },

    formatTimestamp(seconds) {
      const minutes = Math.floor(seconds / 60)
      const remainingSeconds = Math.floor(seconds % 60)
      const ms = Math.floor((seconds % 1) * 1000)
      return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')},${ms.toString().padStart(3, '0')}`
    },

    formatDuration(seconds) {
      const minutes = Math.floor(seconds / 60)
      const remainingSeconds = Math.floor(seconds % 60)
      return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
    },

    copyTranscript() {
      if (!this.transcriptData) return

      const text = this.transcriptData.segments
        .map(segment => {
          if (this.format === 'text') return segment.text
          return `${this.formatTimestamp(segment.start)} - ${this.formatTimestamp(segment.end)}\n${segment.text}`
        })
        .join('\n\n')

      navigator.clipboard.writeText(text)
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
        })
        .catch(err => {
          console.error('Error copying transcript:', err)
        })
    },

    downloadTranscript() {
      if (!this.transcriptData) return

      let content = ''
      let extension = ''

      switch (this.format) {
        case 'text':
          content = this.transcriptData.segments.map(s => s.text).join('\n\n')
          extension = 'txt'
          break
        case 'timestamps':
          content = this.transcriptData.segments
            .map(s => `${this.formatTimestamp(s.start)} - ${this.formatTimestamp(s.end)}\n${s.text}`)
            .join('\n\n')
          extension = 'txt'
          break
        case 'srt':
          content = this.transcriptData.segments
            .map((s, i) => `${i + 1}\n${this.formatTimestamp(s.start)} --> ${this.formatTimestamp(s.end)}\n${s.text}`)
            .join('\n\n')
          extension = 'srt'
          break
        case 'vtt':
          content = `WEBVTT\n\n` + this.transcriptData.segments
            .map((s, i) => `${i + 1}\n${this.formatTimestamp(s.start)} --> ${this.formatTimestamp(s.end)}\n${s.text}`)
            .join('\n\n')
          extension = 'vtt'
          break
      }

      const blob = new Blob([content], { type: 'text/plain' })
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = `transcript.${extension}`
      link.click()
      URL.revokeObjectURL(url)
    },

    deleteSegment(index) {
      this.transcriptData.segments.splice(index, 1)
      this.updateStats()
    },

    updateStats() {
      if (!this.transcriptData) return

      const text = this.transcriptData.segments.map(s => s.text).join(' ')
      const wordCount = text.split(/\s+/).filter(Boolean).length
      const duration = this.transcriptData.segments[this.transcriptData.segments.length - 1]?.end || 0

      this.transcriptData.stats = {
        wordCount,
        wordsPerMinute: Math.round((wordCount / duration) * 60),
        duration
      }
    },

    addToHistory(transcript) {
      this.recentTranscripts.unshift(transcript)
      if (this.recentTranscripts.length > 5) {
        this.recentTranscripts.pop()
      }
    },

    reloadTranscript(transcript) {
      this.language = transcript.language
      this.format = transcript.format
      this.transcriptData = transcript.data
    },

    clearHistory() {
      this.recentTranscripts = []
    }
  }
}
</script> 