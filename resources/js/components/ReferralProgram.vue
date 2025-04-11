<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Empfehlungsprogramm</h1>
        <p class="mt-2 text-gray-600">Laden Sie Freunde ein und verdienen Sie Belohnungen.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Referral Stats -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Stats Overview -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Ihre Statistiken</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="p-4 bg-indigo-50 rounded-lg">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Empfehlungen</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.referrals }}</p>
                  </div>
                </div>
              </div>
              <div class="p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verdiente Credits</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.credits }}</p>
                  </div>
                </div>
              </div>
              <div class="p-4 bg-purple-50 rounded-lg">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktive Nutzer</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.activeUsers }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Referral Link -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Ihr Empfehlungslink</h2>
            <div class="flex items-center space-x-4">
              <input type="text"
                     :value="referralLink"
                     readonly
                     class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50">
              <button @click="copyLink"
                      class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                </svg>
                Link kopieren
              </button>
            </div>
          </div>

          <!-- Recent Referrals -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Letzte Empfehlungen</h2>
            <div class="space-y-4">
              <div v-for="(referral, index) in recentReferrals" :key="index"
                   class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                  <img :src="referral.avatar" :alt="referral.name" class="h-10 w-10 rounded-full">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ referral.name }}</p>
                    <p class="text-sm text-gray-500">{{ referral.date }}</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="[
                          referral.status === 'active' ? 'bg-green-100 text-green-800' :
                          referral.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                          'bg-gray-100 text-gray-800'
                        ]">
                    {{ referral.status === 'active' ? 'Aktiv' :
                       referral.status === 'pending' ? 'Ausstehend' : 'Inaktiv' }}
                  </span>
                  <span class="text-sm font-medium text-gray-900">+{{ referral.credits }} Credits</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Rewards -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Belohnungen</h2>
            <div class="space-y-4">
              <div v-for="(reward, index) in rewards" :key="index"
                   class="p-4 bg-gray-50 rounded-lg">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg"
                         :class="[
                           reward.type === 'premium' ? 'bg-indigo-100 text-indigo-600' :
                           reward.type === 'feature' ? 'bg-green-100 text-green-600' :
                           'bg-purple-100 text-purple-600'
                         ]">
                      <component :is="reward.icon" class="h-6 w-6" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">{{ reward.title }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ reward.description }}</p>
                    <p class="mt-2 text-sm font-medium text-gray-900">
                      {{ reward.requiredReferrals }} Empfehlungen erforderlich
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- How It Works -->
          <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">So funktioniert's</h2>
            <div class="space-y-4">
              <div v-for="(step, index) in steps" :key="index" class="flex gap-3">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600">
                    {{ index + 1 }}
                  </div>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ step }}</p>
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
  name: 'ReferralProgram',

  data() {
    return {
      stats: {
        referrals: 12,
        credits: 240,
        activeUsers: 8
      },
      referralLink: 'https://youtube-creator.app/ref/user123',
      recentReferrals: [
        {
          name: 'Max Mustermann',
          avatar: 'https://example.com/avatar1.jpg',
          date: 'Vor 2 Tagen',
          status: 'active',
          credits: 20
        },
        {
          name: 'Anna Schmidt',
          avatar: 'https://example.com/avatar2.jpg',
          date: 'Vor 5 Tagen',
          status: 'pending',
          credits: 20
        },
        {
          name: 'Tom Weber',
          avatar: 'https://example.com/avatar3.jpg',
          date: 'Vor 1 Woche',
          status: 'active',
          credits: 20
        }
      ],
      rewards: [
        {
          type: 'premium',
          icon: 'StarIcon',
          title: '1 Monat Premium',
          description: 'Schalten Sie alle Premium-Funktionen für einen Monat frei.',
          requiredReferrals: 5
        },
        {
          type: 'feature',
          icon: 'SparklesIcon',
          title: 'KI-Titel-Generator',
          description: 'Unbegrenzter Zugriff auf den KI-gestützten Titel-Generator.',
          requiredReferrals: 3
        },
        {
          type: 'credits',
          icon: 'GiftIcon',
          title: '500 Credits',
          description: 'Erhalten Sie 500 zusätzliche Credits für Ihre Analysen.',
          requiredReferrals: 10
        }
      ],
      steps: [
        'Teilen Sie Ihren persönlichen Empfehlungslink mit Freunden',
        'Ihre Freunde registrieren sich über Ihren Link',
        'Sobald sie aktiv werden, erhalten Sie 20 Credits',
        'Sammeln Sie Empfehlungen für exklusive Belohnungen'
      ]
    }
  },

  methods: {
    copyLink() {
      navigator.clipboard.writeText(this.referralLink)
        .then(() => {
          // Hier könnte eine Erfolgsmeldung angezeigt werden
          console.log('Link kopiert')
        })
        .catch(err => {
          console.error('Fehler beim Kopieren:', err)
        })
    }
  }
}
</script> 