<template>
  <div class="min-h-screen bg-white">
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900">Willkommen bei KreaContent</h1>
        <p class="mt-3 text-xl text-gray-600 max-w-3xl mx-auto">
          Deine Plattform für YouTube-Wachstum und Contentoptimierung
        </p>
      </div>

      <!-- Main Dashboard Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Main Tools Section -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Ideation Chat Card -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 hover:border-primary-300 transition duration-300 card-hover-effect">
            <div class="flex items-start justify-between mb-6">
              <div>
                <div class="flex items-center gap-3">
                  <div class="p-3 bg-primary-100 rounded-lg">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                  </div>
                  <h2 class="text-2xl font-bold text-gray-900">Ideen Chat</h2>
                </div>
                <p class="mt-3 text-gray-600">Analysiere Wettbewerber und erkunde Ideen für deinen Kanal</p>
              </div>
              <button @click="startChat" class="px-6 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-300 font-medium">
                Chat starten
              </button>
            </div>
            
            <!-- Prompt Shortcuts -->
            <div class="mt-4">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Schnelleinstieg mit Prompts:</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div @click="startChatWithPrompt('content-ideas')" class="p-5 bg-primary-50 rounded-lg cursor-pointer hover:bg-primary-100 transition duration-300">
                  <h4 class="font-medium text-primary-800 text-lg mb-1">Content-Ideen</h4>
                  <p class="text-sm text-gray-600">Frische Videoideen für deinen Kanal generieren</p>
                </div>
                <div @click="startChatWithPrompt('competitor-analysis')" class="p-5 bg-blue-50 rounded-lg cursor-pointer hover:bg-blue-100 transition duration-300">
                  <h4 class="font-medium text-blue-800 text-lg mb-1">Wettbewerber-Analyse</h4>
                  <p class="text-sm text-gray-600">Erfolgreiche Videos deiner Konkurrenz analysieren</p>
                </div>
                <div @click="startChatWithPrompt('funnel-strategy')" class="p-5 bg-amber-50 rounded-lg cursor-pointer hover:bg-amber-100 transition duration-300">
                  <h4 class="font-medium text-amber-800 text-lg mb-1">Funnel-Strategie</h4>
                  <p class="text-sm text-gray-600">Marketing-Funnel für lokale Kunden planen</p>
                </div>
                <div @click="startChatWithPrompt('ad-ideas')" class="p-5 bg-pink-50 rounded-lg cursor-pointer hover:bg-pink-100 transition duration-300">
                  <h4 class="font-medium text-pink-800 text-lg mb-1">Werbeanzeigen</h4>
                  <p class="text-sm text-gray-600">Kreative Ideen für erfolgreiche Werbekampagnen</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Research Tools Section -->
          <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="tool in toolsHighlighted" :key="tool.id" 
                 class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 hover:border-primary-300 transition duration-300 cursor-pointer card-hover-effect"
                 @click="openTool(tool.id)">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-3 rounded-lg" :class="tool.bgColor">
                  <component :is="tool.icon" class="w-6 h-6" :class="tool.iconColor"/>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ tool.name }}</h3>
              </div>
              <p class="text-gray-600">{{ tool.description }}</p>
            </div>
          </div> -->

          <!-- Channel Action Card -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 card-hover-effect" v-if="!hasChannel">
            <div class="text-center py-6">
              <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-primary-100 mb-6">
                <svg class="h-10 w-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-3">Füge deinen ersten Kanal hinzu</h3>
              <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Du möchtest Skripte erstellen? Lass uns zuerst deinen Kanal hinzufügen.<br>
                Das hilft dem System, deine Zielgruppe und deinen Stil zu verstehen.
              </p>
              <button @click="addChannel" class="px-8 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-300 font-medium">
                Kanal hinzufügen
              </button>
            </div>
          </div>

          <!-- New Forms Section -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 card-hover-effect">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Neue Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-primary-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold text-primary-900 mb-3">Sales Script Generator</h3>
                <p class="text-gray-600 mb-6">Erhalte ein personalisiertes Verkaufsskript für deinen YouTube-Kanal.</p>
                <button @click="goToSalesScriptForm" class="px-6 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-300 font-medium w-full">
                  Script anfordern
                </button>
              </div>
              <div class="bg-green-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold text-green-900 mb-3">Kanal-Optimierung</h3>
                <p class="text-gray-600 mb-6">Optimiere deinen Kanal mit unserem mehrstufigen Optimierungs-Tool.</p>
                <button @click="goToMultiStepForm" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 font-medium w-full">
                  Jetzt optimieren
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Tools Card -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 card-hover-effect">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Alle Tools</h2>
            <div class="space-y-4">
              <div v-for="tool in tools" :key="tool.id" 
                   class="flex items-center gap-4 p-4 rounded-lg cursor-pointer transition duration-300 hover:bg-gray-50"
                   @click="openTool(tool.id)">
                <div class="p-3 rounded-lg" :class="tool.bgColor">
                  <component :is="tool.icon" class="w-5 h-5" :class="tool.iconColor"/>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">{{ tool.name }}</h3>
                  <p class="text-sm text-gray-600">{{ tool.description }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Guides Card -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100 card-hover-effect">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Guides</h2>
            <div class="space-y-4">
              <!-- Guide 1 -->
              <div class="flex items-center gap-4 p-4 rounded-lg cursor-pointer transition duration-300 hover:bg-gray-50" @click="openGuide('ideating-scripting')">
                <div class="p-3 bg-red-50 rounded-lg">
                  <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Ideation & Scripting</h3>
                  <p class="text-sm text-gray-600">Komplette Anleitung zur Growth-Plattform</p>
                </div>
              </div>

              <!-- Guide 2 -->
              <div class="flex items-center gap-4 p-4 rounded-lg cursor-pointer transition duration-300 hover:bg-gray-50" @click="openGuide('writing-style')">
                <div class="p-3 bg-green-50 rounded-lg">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Schreibstil anpassen</h3>
                  <p class="text-sm text-gray-600">Lerne, wie du deinen Kanalstil optimierst</p>
                </div>
              </div>

              <!-- Guide 3 -->
              <div class="flex items-center gap-4 p-4 rounded-lg cursor-pointer transition duration-300 hover:bg-gray-50" @click="openGuide('help-docs')">
                <div class="p-3 bg-blue-50 rounded-lg">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Growth Hilfe-Zentrum</h3>
                  <p class="text-sm text-gray-600">Hilfe zur Nutzung und Optimierung</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Referral Banner -->
          <div class="bg-gradient-to-r from-primary-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white card-hover-effect">
            <h3 class="text-xl font-bold">Verdiene 30% durch Empfehlungen</h3>
            <p class="mt-3 text-white/90">Refinanziere dein Abonnement mit nur wenigen Empfehlungen auf derselben Stufe.</p>
            <button @click="learnMoreReferral" class="mt-6 w-full bg-white text-primary-600 px-6 py-3 rounded-md font-bold hover:bg-gray-50 transition duration-300">
              Mehr erfahren
            </button>
          </div>
        </div>
      </div>

      <!-- Debug Section -->
      <div class="max-w-7xl mx-auto mt-8 p-6 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-bold mb-4">Debug Tools</h3>
        <router-link to="/supabase-test" class="inline-block bg-gray-800 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition duration-300">
          Supabase Test Page
        </router-link>
      </div>
    </main>
  </div>
</template>

<script>
import {
  ChartBarIcon,
  MagnifyingGlassIcon as SearchIcon,
  PencilSquareIcon as PencilAltIcon,
  BoltIcon as LightningBoltIcon,
  DocumentTextIcon,
  TagIcon,
  PhotoIcon as PhotographIcon,
  DocumentDuplicateIcon
} from '@heroicons/vue/24/outline'

export default {
  name: 'Dashboard',
  
  components: {
    ChartBarIcon,
    SearchIcon,
    PencilAltIcon,
    LightningBoltIcon,
    DocumentTextIcon,
    TagIcon,
    PhotographIcon,
    DocumentDuplicateIcon
  },

  data() {
    return {
      hasChannel: false,
      toolsHighlighted: [
        {
          id: 'funnel-generator',
          name: 'Funnel Generator',
          description: 'Marketing-Funnels für lokale Kunden erstellen',
          icon: 'ChartBarIcon',
          bgColor: 'bg-amber-100',
          iconColor: 'text-amber-600'
        },
        {
          id: 'ad-creator',
          name: 'Ad Creator',
          description: 'Creatives und Werbetexte für Kampagnen erstellen',
          icon: 'PencilAltIcon',
          bgColor: 'bg-pink-100',
          iconColor: 'text-pink-600'
        },
        {
          id: 'channel-analysis',
          name: 'Kanal-Analyse',
          description: 'Erfolgreiche Kanäle in deiner Nische analysieren',
          icon: 'ChartBarIcon',
          bgColor: 'bg-blue-100',
          iconColor: 'text-blue-600'
        },
        {
          id: 'video-research',
          name: 'Video-Recherche',
          description: 'Detaillierte Analyse performanter Videos',
          icon: 'SearchIcon',
          bgColor: 'bg-green-100',
          iconColor: 'text-green-600'
        }
      ],
      tools: [
        {
          id: 'funnel-generator',
          name: 'Funnel Generator',
          description: 'Marketing-Funnels für lokale Kunden erstellen',
          icon: 'ChartBarIcon',
          bgColor: 'bg-amber-100',
          iconColor: 'text-amber-600'
        },
        {
          id: 'ad-creator',
          name: 'Ad Creator',
          description: 'Creatives und Werbetexte für Kampagnen erstellen',
          icon: 'PencilAltIcon',
          bgColor: 'bg-pink-100',
          iconColor: 'text-pink-600'
        },
        {
          id: 'breakdown',
          name: 'Breakdown',
          description: 'Erfolgreiche Videos analysieren',
          icon: 'ChartBarIcon',
          bgColor: 'bg-blue-100',
          iconColor: 'text-blue-600'
        },
        {
          id: 'keyword-research',
          name: 'Keyword Research',
          description: 'Videothemen recherchieren',
          icon: 'SearchIcon',
          bgColor: 'bg-purple-100',
          iconColor: 'text-purple-600'
        },
        {
          id: 'title-generator',
          name: 'Titel Generator',
          description: 'Ansprechende Videotitel erstellen',
          icon: 'PencilAltIcon',
          bgColor: 'bg-green-100',
          iconColor: 'text-green-600'
        },
        {
          id: 'hook-generator',
          name: 'Hook Generator',
          description: 'Packende Video-Intros entwerfen',
          icon: 'LightningBoltIcon',
          bgColor: 'bg-yellow-100',
          iconColor: 'text-yellow-600'
        },
        {
          id: 'description-generator',
          name: 'Beschreibungs-Generator',
          description: 'Videobeschreibungen erstellen',
          icon: 'DocumentTextIcon',
          bgColor: 'bg-red-100',
          iconColor: 'text-red-600'
        },
        {
          id: 'tag-generator',
          name: 'Tag Generator',
          description: 'Relevante Video-Tags generieren',
          icon: 'TagIcon',
          bgColor: 'bg-indigo-100',
          iconColor: 'text-indigo-600'
        },
        {
          id: 'thumbnail-grabber',
          name: 'Thumbnail Grabber',
          description: 'YouTube-Thumbnails herunterladen',
          icon: 'PhotographIcon',
          bgColor: 'bg-pink-100',
          iconColor: 'text-pink-600'
        },
        {
          id: 'transcript-generator',
          name: 'Transkript Generator',
          description: 'YouTube-Transkripte erhalten und formatieren',
          icon: 'DocumentDuplicateIcon',
          bgColor: 'bg-gray-100',
          iconColor: 'text-gray-600'
        }
      ]
    }
  },

  methods: {
    startChat() {
      this.$router.push({ name: 'idea-chat' });
    },

    openTool(toolId) {
      this.$router.push({ name: toolId })
    },

    addChannel() {
      this.$router.push({ name: 'add-channel' })
    },

    goToSalesScriptForm() {
      this.$router.push({ name: 'sales-script-form' })
    },

    goToMultiStepForm() {
      this.$router.push({ name: 'multi-step-form' })
    },

    openGuide(guideId) {
      this.$router.push({ name: 'guide', params: { id: guideId } })
    },

    learnMoreReferral() {
      this.$router.push({ name: 'referral-program' })
    },

    startChatWithPrompt(promptType) {
      // Definiere Prompts basierend auf dem Typ
      const prompts = {
        'trending-topics': 'Analysiere die aktuellen Trends in meiner Nische und schlage 5 Videothemen vor, die gerade hohe Nachfrage haben.',
        'competitor-analysis': 'Untersuche die erfolgreichsten Videos meiner Konkurrenz und erkläre, warum sie so gut funktionieren. Was kann ich daraus lernen?',
        'content-ideas': 'Generiere 10 kreative Videoideen für meinen Kanal, die meiner Zielgruppe einen echten Mehrwert bieten.',
        'hook-ideas': 'Entwickle 5 verschiedene Hook-Stile für meine Videos, die in den ersten 5 Sekunden die Aufmerksamkeit fesseln.',
        'funnel-strategy': 'Entwickle eine Marketing-Funnel-Strategie für lokale Kunden. Wie kann ich von der Aufmerksamkeit bis zur Conversion führen?',
        'ad-ideas': 'Entwirf kreative Werbeanzeigen-Konzepte für meine Zielgruppe, die hohe Klickraten und Conversions erzielen können.'
      };
      
      // Wähle den entsprechenden Prompt aus oder verwende einen Standardwert
      const selectedPrompt = prompts[promptType] || 'Wie kann ich meinen YouTube-Kanal verbessern?';
      
      // Navigiere zum Chat mit dem vordefinierten Prompt
      this.$router.push({ 
        name: 'idea-chat',
        query: { prompt: selectedPrompt }
      });
    }
  }
}
</script> 