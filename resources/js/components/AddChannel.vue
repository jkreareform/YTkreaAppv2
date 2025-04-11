<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kanal hinzufügen</h1>
        <p class="mt-2 text-gray-600">Fügen Sie einen YouTube-Kanal für die Analyse hinzu.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Input Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Channel Form -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Kanal-Details</h2>
            <form @submit.prevent="addChannel" class="space-y-6">
              <div>
                <label for="channelUrl" class="block text-sm font-medium text-gray-700">YouTube Kanal URL</label>
                <div class="mt-1">
                  <input type="text"
                         id="channelUrl"
                         v-model="channelUrl"
                         class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="https://www.youtube.com/c/...">
                </div>
              </div>

              <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Kategorie</label>
                <select id="category"
                        v-model="category"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option value="tech">Tech & Software</option>
                  <option value="education">Bildung & Tutorial</option>
                  <option value="entertainment">Unterhaltung</option>
                  <option value="gaming">Gaming</option>
                  <option value="lifestyle">Lifestyle & Vlogs</option>
                  <option value="business">Business & Marketing</option>
                  <option value="other">Sonstiges</option>
                </select>
              </div>

              <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notizen</label>
                <div class="mt-1">
                  <textarea id="notes"
                           v-model="notes"
                           rows="3"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Wichtige Anmerkungen zum Kanal..."></textarea>
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
                  {{ loading ? 'Wird hinzugefügt...' : 'Kanal hinzufügen' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Channel Preview -->
          <div v-if="channelData" class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-start space-x-4">
              <img :src="channelData.thumbnail"
                   :alt="channelData.title"
                   class="h-16 w-16 rounded-full">
              <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-gray-900 truncate">{{ channelData.title }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ channelData.description }}</p>
                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                  <div class="flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ formatNumber(channelData.subscribers) }} Abonnenten
                  </div>
                  <div class="flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ formatNumber(channelData.videos) }} Videos
                  </div>
                  <div class="flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ formatNumber(channelData.views) }} Aufrufe
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
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Tipps</h2>
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

          <!-- Recent Channels -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-gray-900">Letzte Kanäle</h2>
              <button @click="clearHistory"
                      class="text-sm text-gray-500 hover:text-gray-700">
                Verlauf löschen
              </button>
            </div>
            <div class="space-y-3">
              <div v-for="(channel, index) in recentChannels" :key="index"
                   class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                <img :src="channel.thumbnail"
                     :alt="channel.title"
                     class="h-10 w-10 rounded-full">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ channel.title }}</p>
                  <p class="text-sm text-gray-500">{{ channel.category }}</p>
                </div>
                <button @click="reloadChannel(channel)"
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
import { getChannelDetails } from '../services/youtubeService';

export default {
  name: 'AddChannel',

  data() {
    return {
      channelUrl: '',
      category: 'tech',
      notes: '',
      loading: false,
      channelData: null,
      recentChannels: [],
      tips: [
        'Verwenden Sie die vollständige Kanal-URL für beste Ergebnisse',
        'Wählen Sie die passendste Kategorie für genauere Analysen',
        'Fügen Sie relevante Notizen für spätere Referenz hinzu',
        'Überprüfen Sie die Kanal-Statistiken vor dem Hinzufügen'
      ]
    }
  },

  methods: {
    async addChannel() {
      this.loading = true;
      
      try {
        const channelId = this.extractChannelId(this.channelUrl);
        if (!channelId) {
          throw new Error('Invalid YouTube channel URL');
        }
        
        const channelDetails = await getChannelDetails(channelId);

        this.channelData = {
          title: channelDetails.snippet.title,
          description: channelDetails.snippet.description,
          thumbnail: channelDetails.snippet.thumbnails.default.url,
          subscribers: channelDetails.statistics.subscriberCount,
          videos: channelDetails.statistics.videoCount,
          views: channelDetails.statistics.viewCount
        };

        // Add channel to history
        this.addToHistory({
          title: this.channelData.title,
          thumbnail: this.channelData.thumbnail,
          category: this.category,
          url: this.channelUrl
        });
      } catch (error) {
        console.error('Error adding channel:', error);
        alert('Fehler beim Hinzufügen des Kanals: ' + error.message);
      } finally {
        this.loading = false;
      }
    },

    extractChannelId(url) {
      if (!url || typeof url !== 'string') return null;
      
      try {
        // Unterstützt @handles, channel/ID, /c/, /user/ und youtu.be Links
        const patterns = [
          // @username Format
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/@([\w._-]+)/i,
          // Channel ID Format
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/channel\/([\w-]+)/i,
          // User Format
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/user\/([\w-]+)/i,
          // /c/ Format
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/c\/([\w-]+)/i,
          // Shortlink Format
          /(?:https?:\/\/)?(?:www\.)?youtu\.be\/([\w-]+)/i
        ];
        
        for (const pattern of patterns) {
          const match = url.match(pattern);
          if (match && match[1]) return match[1];
        }
        
        // Einfache @username ohne vollständige URL
        if (url.startsWith('@')) {
          return url.substring(1);
        }
        
        console.log('Keine passende YouTube URL gefunden:', url);
        return null;
      } catch (error) {
        console.error('Fehler beim Extrahieren der Channel ID:', error);
        return null;
      }
    },

    formatNumber(number) {
      if (number >= 1000000) {
        return (number / 1000000).toFixed(1) + 'M'
      }
      if (number >= 1000) {
        return (number / 1000).toFixed(1) + 'K'
      }
      return number.toString()
    },

    addToHistory(channel) {
      this.recentChannels.unshift(channel)
      if (this.recentChannels.length > 5) {
        this.recentChannels.pop()
      }
    },

    reloadChannel(channel) {
      this.channelUrl = channel.url
      this.category = channel.category
      this.addChannel()
    },

    clearHistory() {
      this.recentChannels = []
    }
  }
}
</script> 