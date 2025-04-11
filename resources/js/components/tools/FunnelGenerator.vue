<template>
  <div class="min-h-screen bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Marketing-Funnel Generator</h1>
        <p class="mt-2 text-gray-600">Erstelle effektive Marketing-Funnels für lokale Kunden und Geschäfte.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Form -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Funnel Form -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Funnel-Details</h2>
            
            <form @submit.prevent="generateFunnel" class="space-y-6">
              <!-- Business Type -->
              <div>
                <label for="businessType" class="block text-sm font-medium text-gray-700">Art des Unternehmens</label>
                <select v-model="formData.businessType" id="businessType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Bitte wählen...</option>
                  <option value="retail">Einzelhandel</option>
                  <option value="restaurant">Restaurant/Café</option>
                  <option value="service">Dienstleistung</option>
                  <option value="professional">Freiberufler</option>
                  <option value="healthcare">Gesundheitswesen</option>
                  <option value="other">Andere</option>
                </select>
              </div>

              <!-- Other Business Type -->
              <div v-if="formData.businessType === 'other'">
                <label for="otherBusinessType" class="block text-sm font-medium text-gray-700">Bitte spezifizieren</label>
                <input type="text" v-model="formData.otherBusinessType" id="otherBusinessType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Beschreibe deine Branche">
              </div>

              <!-- Target Audience -->
              <div>
                <label for="targetAudience" class="block text-sm font-medium text-gray-700">Zielgruppe</label>
                <textarea v-model="formData.targetAudience" id="targetAudience" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Beschreibe deine Zielgruppe (Alter, Interessen, Standort, etc.)"></textarea>
              </div>

              <!-- Main Goal -->
              <div>
                <label for="primaryGoal" class="block text-sm font-medium text-gray-700">Hauptziel des Funnels</label>
                <select v-model="formData.primaryGoal" id="primaryGoal" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Bitte wählen...</option>
                  <option value="leads">Lead-Generierung</option>
                  <option value="sales">Direktverkäufe</option>
                  <option value="appointments">Terminvereinbarungen</option>
                  <option value="visits">Geschäftsbesuche</option>
                  <option value="subscribers">Newsletter-Abonnenten</option>
                  <option value="awareness">Markenbekanntheit</option>
                </select>
              </div>

              <!-- Marketing Channels -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Marketing-Kanäle</label>
                <div class="grid grid-cols-2 gap-4">
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-social" type="checkbox" v-model="formData.channels.social" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-social" class="font-medium text-gray-700">Social Media</label>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-email" type="checkbox" v-model="formData.channels.email" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-email" class="font-medium text-gray-700">E-Mail</label>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-search" type="checkbox" v-model="formData.channels.search" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-search" class="font-medium text-gray-700">Suchmaschinen</label>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-local" type="checkbox" v-model="formData.channels.local" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-local" class="font-medium text-gray-700">Lokales Marketing</label>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-print" type="checkbox" v-model="formData.channels.print" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-print" class="font-medium text-gray-700">Print</label>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="channels-referral" type="checkbox" v-model="formData.channels.referral" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="channels-referral" class="font-medium text-gray-700">Empfehlungsmarketing</label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Budget Range -->
              <div>
                <label for="budget" class="block text-sm font-medium text-gray-700">Budget</label>
                <select v-model="formData.budget" id="budget" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Bitte wählen...</option>
                  <option value="minimal">Minimal (unter 500€/Monat)</option>
                  <option value="low">Gering (500€ - 1.000€/Monat)</option>
                  <option value="medium">Mittel (1.000€ - 3.000€/Monat)</option>
                  <option value="high">Hoch (3.000€ - 10.000€/Monat)</option>
                  <option value="enterprise">Enterprise (über 10.000€/Monat)</option>
                </select>
              </div>

              <!-- Timeframe -->
              <div>
                <label for="timeframe" class="block text-sm font-medium text-gray-700">Zeitrahmen</label>
                <select v-model="formData.timeframe" id="timeframe" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Bitte wählen...</option>
                  <option value="immediate">Kurzfristig (sofort Ergebnisse)</option>
                  <option value="short">Kurzfristig (1-3 Monate)</option>
                  <option value="medium">Mittelfristig (3-6 Monate)</option>
                  <option value="long">Langfristig (6-12 Monate)</option>
                </select>
              </div>

              <!-- USP -->
              <div>
                <label for="usp" class="block text-sm font-medium text-gray-700">Alleinstellungsmerkmal</label>
                <textarea v-model="formData.usp" id="usp" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Was unterscheidet dein Unternehmen von anderen? Was ist dein Wertversprechen?"></textarea>
              </div>

              <!-- Additional Info -->
              <div>
                <label for="additionalInfo" class="block text-sm font-medium text-gray-700">Weitere Informationen (optional)</label>
                <textarea v-model="formData.additionalInfo" id="additionalInfo" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Gibt es weitere wichtige Informationen, die bei der Funnel-Erstellung helfen könnten?"></textarea>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end">
                <button type="submit" :disabled="isLoading" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                  <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isLoading ? 'Generiere Funnel...' : 'Funnel generieren' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Column: Tips and Results -->
        <div class="space-y-8">
          <!-- Funnel Creation Tips -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Funnel-Tipps für lokale Unternehmen</h2>
            <div class="text-gray-600">
              <ul class="list-disc pl-5 space-y-2">
                <li>Fokussiere auf lokale Suchmaschinenoptimierung (Local SEO)</li>
                <li>Nutze Google My Business und lokale Verzeichnisse</li>
                <li>Setze auf geotargeted Anzeigen für mehr Relevanz</li>
                <li>Biete lokalisierte Inhalte und Angebote an</li>
                <li>Nutze lokale Veranstaltungen zur Lead-Generierung</li>
                <li>Baue Kundenbewertungen und Testimonials ein</li>
              </ul>
            </div>
          </div>

          <!-- Funnel Result (shown after generation) -->
          <div v-if="funnelResult" class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Dein Marketing-Funnel</h2>
            
            <!-- Funnel Stages Visualization -->
            <div class="relative py-8">
              <div class="flex flex-col space-y-4">
                <!-- Awareness Stage -->
                <div class="bg-gradient-to-r from-pink-100 to-purple-100 p-4 rounded-lg border border-pink-200 relative">
                  <div class="absolute -top-3 left-2 bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded">PHASE 1</div>
                  <h3 class="font-bold text-pink-800">Aufmerksamkeit</h3>
                  <p class="text-sm text-gray-700 mt-1">{{ funnelResult.stages.awareness.description }}</p>
                  <div class="mt-3">
                    <span class="text-xs font-bold text-gray-500">KANÄLE:</span>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <span v-for="(channel, i) in funnelResult.stages.awareness.channels" :key="`awareness-${i}`" class="bg-white text-pink-600 text-xs px-2 py-0.5 rounded-full border border-pink-200">
                        {{ channel }}
                      </span>
                    </div>
                  </div>
                </div>
                
                <!-- Consideration Stage -->
                <div class="bg-gradient-to-r from-purple-100 to-indigo-100 p-4 rounded-lg border border-purple-200 relative">
                  <div class="absolute -top-3 left-2 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded">PHASE 2</div>
                  <h3 class="font-bold text-purple-800">Interesse</h3>
                  <p class="text-sm text-gray-700 mt-1">{{ funnelResult.stages.consideration.description }}</p>
                  <div class="mt-3">
                    <span class="text-xs font-bold text-gray-500">KANÄLE:</span>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <span v-for="(channel, i) in funnelResult.stages.consideration.channels" :key="`consideration-${i}`" class="bg-white text-purple-600 text-xs px-2 py-0.5 rounded-full border border-purple-200">
                        {{ channel }}
                      </span>
                    </div>
                  </div>
                </div>
                
                <!-- Conversion Stage -->
                <div class="bg-gradient-to-r from-indigo-100 to-blue-100 p-4 rounded-lg border border-indigo-200 relative">
                  <div class="absolute -top-3 left-2 bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded">PHASE 3</div>
                  <h3 class="font-bold text-indigo-800">Konversion</h3>
                  <p class="text-sm text-gray-700 mt-1">{{ funnelResult.stages.conversion.description }}</p>
                  <div class="mt-3">
                    <span class="text-xs font-bold text-gray-500">KANÄLE:</span>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <span v-for="(channel, i) in funnelResult.stages.conversion.channels" :key="`conversion-${i}`" class="bg-white text-indigo-600 text-xs px-2 py-0.5 rounded-full border border-indigo-200">
                        {{ channel }}
                      </span>
                    </div>
                  </div>
                </div>
                
                <!-- Retention Stage -->
                <div class="bg-gradient-to-r from-blue-100 to-teal-100 p-4 rounded-lg border border-blue-200 relative">
                  <div class="absolute -top-3 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">PHASE 4</div>
                  <h3 class="font-bold text-blue-800">Kundenbindung</h3>
                  <p class="text-sm text-gray-700 mt-1">{{ funnelResult.stages.retention.description }}</p>
                  <div class="mt-3">
                    <span class="text-xs font-bold text-gray-500">KANÄLE:</span>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <span v-for="(channel, i) in funnelResult.stages.retention.channels" :key="`retention-${i}`" class="bg-white text-blue-600 text-xs px-2 py-0.5 rounded-full border border-blue-200">
                        {{ channel }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Connecting Lines -->
              <div class="absolute left-1/2 top-12 bottom-12 w-0.5 bg-gradient-to-b from-pink-300 via-purple-300 to-blue-300 -ml-0.5"></div>
            </div>
            
            <!-- Recommendations Section -->
            <div class="mt-6 pt-4 border-t border-gray-200">
              <h3 class="font-bold text-gray-900 mb-3">Empfehlungen</h3>
              <ul class="space-y-2 text-sm text-gray-700">
                <li v-for="(recommendation, i) in funnelResult.recommendations" :key="`rec-${i}`" class="flex items-start">
                  <svg class="h-5 w-5 text-pink-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="ml-2">{{ recommendation }}</span>
                </li>
              </ul>
            </div>
            
            <!-- Call-to-Action for Ad Creator -->
            <div class="mt-6 pt-4 border-t border-gray-200">
              <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-4 border border-pink-100">
                <h3 class="font-bold text-gray-900 mb-2">Nächster Schritt: Erstelle Werbeanzeigen für deinen Funnel</h3>
                <p class="text-sm text-gray-600 mb-4">Erstelle passende Werbeanzeigen für jede Phase deines Marketing-Funnels.</p>
                <router-link 
                  :to="{ name: 'ad-creator', query: { funnelId: funnelResult.id } }" 
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700"
                >
                  Werbeanzeigen erstellen
                </router-link>
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
  name: 'FunnelGenerator',
  
  data() {
    return {
      isLoading: false,
      formData: {
        businessType: '',
        otherBusinessType: '',
        targetAudience: '',
        primaryGoal: '',
        channels: {
          social: false,
          email: false,
          search: false,
          local: false,
          print: false,
          referral: false
        },
        budget: '',
        timeframe: '',
        usp: '',
        additionalInfo: ''
      },
      funnelResult: null
    };
  },
  
  methods: {
    async generateFunnel() {
      this.isLoading = true;
      
      try {
        // Simuliere API-Anfrage mit Timeout
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Hier würde normalerweise ein API-Aufruf stehen
        // Beispiel-Ergebnis für Demo-Zwecke
        this.funnelResult = {
          id: 'funnel-' + Date.now(),
          name: 'Lokaler Kunden-Funnel',
          stages: {
            awareness: {
              description: 'Lokale Sichtbarkeit aufbauen durch gezielte Anzeigen und Local SEO, um potenzielle Kunden in deiner Umgebung zu erreichen.',
              channels: ['Google My Business', 'Lokale Anzeigen', 'Social Media']
            },
            consideration: {
              description: 'Interesse wecken mit wertvollen Inhalten und Angeboten, die deine Expertise und den Nutzen deiner Dienstleistungen hervorheben.',
              channels: ['Blog-Artikel', 'E-Mail-Marketing', 'Testimonials']
            },
            conversion: {
              description: 'Potenzielle Kunden zu Erstkäufern konvertieren durch überzeugende Angebote und reibungslose Kaufprozesse.',
              channels: ['Landing Pages', 'Spezialangebote', 'Chat/Beratung']
            },
            retention: {
              description: 'Kundenbindung stärken durch Nachbetreuung, Follow-up-Kommunikation und Treueprogramme.',
              channels: ['E-Mail-Sequenzen', 'Kundenbindungsprogramm', 'Bewertungsanfragen']
            }
          },
          recommendations: [
            'Optimiere deine Google My Business Präsenz mit regelmäßigen Updates und Fotos',
            'Erstelle lokalisierte Content-Angebote für deine spezifische Zielgruppe',
            'Implementiere ein einfaches Empfehlungsprogramm für Bestandskunden',
            'Nutze geotargeted Facebook und Instagram Ads für höhere Relevanz',
            'Baue eine E-Mail-Strategie für regelmäßige Kommunikation mit lokalen Interessenten auf',
            'Plane saisonale Angebote und Events für kontinuierliche Aufmerksamkeit'
          ]
        };
      } catch (error) {
        console.error('Fehler beim Generieren des Funnels:', error);
        // Fehlerbehandlung hier
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script> 