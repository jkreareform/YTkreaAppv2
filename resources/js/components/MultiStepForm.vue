<template>
  <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
    <!-- Logo -->
    <div class="mb-12">
      <div class="bg-black rounded-lg px-6 py-3 inline-block transform rotate-1">
        <h1 class="text-white text-2xl font-bold tracking-wider">GROWTH</h1>
      </div>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Progress -->
      <div class="p-8">
        <p class="text-gray-600 mb-2">Schritt {{ currentStep }} von {{ totalSteps }}</p>
        <div class="w-full bg-gray-100 rounded-full h-2">
          <div
            class="bg-green-500 h-2 rounded-full"
            :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
          ></div>
        </div>
      </div>

      <!-- Form Content -->
      <div class="px-8 pb-8">
        <!-- Step 1: Channel Type -->
        <div v-if="currentStep === 1">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Welche Art von YouTube-Kanal betreibst du?
          </h2>
          
          <div class="space-y-4">
            <div
              v-for="(type, index) in channelTypes"
              :key="`channel-${index}`"
              class="relative rounded-lg border border-gray-300 bg-white p-4 transition hover:border-primary-500 cursor-pointer"
              :class="{ 'border-primary-500 ring-2 ring-primary-500': formData.channelType === type.value }"
              @click="formData.channelType = type.value"
            >
              <label :for="`channel-${index}`" class="flex cursor-pointer">
                <span class="flex items-center">
                  <input
                    type="radio"
                    :id="`channel-${index}`"
                    :value="type.value"
                    v-model="formData.channelType"
                    class="sr-only"
                  />
                  <span
                    class="h-5 w-5 rounded-full border flex items-center justify-center mr-3"
                    :class="formData.channelType === type.value ? 'border-primary-600 bg-primary-600' : 'border-gray-300'"
                  >
                    <span v-if="formData.channelType === type.value" class="h-2 w-2 rounded-full bg-white"></span>
                  </span>
                </span>
                <span class="flex flex-col text-sm">
                  <span class="font-medium text-gray-900">{{ type.label }}</span>
                  <span class="text-gray-500">{{ type.description }}</span>
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Step 2: Experience Level -->
        <div v-else-if="currentStep === 2">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Wie viel Erfahrung hast du mit YouTube?
          </h2>
          
          <div class="space-y-4">
            <div
              v-for="(level, index) in experienceLevels"
              :key="`experience-${index}`"
              class="relative rounded-lg border border-gray-300 bg-white p-4 transition hover:border-primary-500 cursor-pointer"
              :class="{ 'border-primary-500 ring-2 ring-primary-500': formData.experienceLevel === level.value }"
              @click="formData.experienceLevel = level.value"
            >
              <label :for="`experience-${index}`" class="flex cursor-pointer">
                <span class="flex items-center">
                  <input
                    type="radio"
                    :id="`experience-${index}`"
                    :value="level.value"
                    v-model="formData.experienceLevel"
                    class="sr-only"
                  />
                  <span
                    class="h-5 w-5 rounded-full border flex items-center justify-center mr-3"
                    :class="formData.experienceLevel === level.value ? 'border-primary-600 bg-primary-600' : 'border-gray-300'"
                  >
                    <span v-if="formData.experienceLevel === level.value" class="h-2 w-2 rounded-full bg-white"></span>
                  </span>
                </span>
                <span class="flex flex-col text-sm">
                  <span class="font-medium text-gray-900">{{ level.label }}</span>
                  <span class="text-gray-500">{{ level.description }}</span>
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Step 3: Goals -->
        <div v-else-if="currentStep === 3">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Was sind deine Hauptziele für deinen YouTube-Kanal?
          </h2>
          
          <div class="space-y-4">
            <div
              v-for="(goal, index) in goals"
              :key="`goal-${index}`"
              class="relative rounded-lg border border-gray-300 bg-white p-4 transition hover:border-gray-400 cursor-pointer"
              :class="{ 'border-primary-500 ring-2 ring-primary-500': formData.goals.includes(goal.value) }"
              @click="toggleGoal(goal.value)"
            >
              <label :for="`goal-${index}`" class="flex cursor-pointer">
                <span class="flex items-center">
                  <input
                    type="checkbox"
                    :id="`goal-${index}`"
                    :value="goal.value"
                    v-model="formData.goals"
                    class="sr-only"
                  />
                  <span
                    class="h-5 w-5 rounded border flex items-center justify-center mr-3"
                    :class="formData.goals.includes(goal.value) ? 'border-primary-600 bg-primary-600' : 'border-gray-300'"
                  >
                    <svg v-if="formData.goals.includes(goal.value)" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </span>
                </span>
                <span class="flex flex-col text-sm">
                  <span class="font-medium text-gray-900">{{ goal.label }}</span>
                  <span class="text-gray-500">{{ goal.description }}</span>
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Step 4: Content Type -->
        <div v-else-if="currentStep === 4">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Welche Art von Content möchtest du erstellen?
          </h2>
          
          <div class="space-y-4">
            <div
              v-for="(type, index) in contentTypes"
              :key="`content-${index}`"
              class="relative rounded-lg border border-gray-300 bg-white p-4 transition hover:border-primary-500 cursor-pointer"
              :class="{ 'border-primary-500 ring-2 ring-primary-500': formData.contentType === type.value }"
              @click="formData.contentType = type.value"
            >
              <label :for="`content-${index}`" class="flex cursor-pointer">
                <span class="flex items-center">
                  <input
                    type="radio"
                    :id="`content-${index}`"
                    :value="type.value"
                    v-model="formData.contentType"
                    class="sr-only"
                  />
                  <span
                    class="h-5 w-5 rounded-full border flex items-center justify-center mr-3"
                    :class="formData.contentType === type.value ? 'border-primary-600 bg-primary-600' : 'border-gray-300'"
                  >
                    <span v-if="formData.contentType === type.value" class="h-2 w-2 rounded-full bg-white"></span>
                  </span>
                </span>
                <span class="flex flex-col text-sm">
                  <span class="font-medium text-gray-900">{{ type.label }}</span>
                  <span class="text-gray-500">{{ type.description }}</span>
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Step 5: Audience -->
        <div v-else-if="currentStep === 5">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Wer ist deine Zielgruppe?
          </h2>
          
          <div class="space-y-6">
            <div>
              <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Beschreibe deine Zielgruppe</label>
              <textarea
                id="audience"
                v-model="formData.audience"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                placeholder="Alter, Interessen, Probleme die sie lösen wollen, etc."
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Step 6: Contact Information -->
        <div v-else-if="currentStep === 6">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            Fast geschafft: Wohin dürfen wir dein Sales Script senden? 🎉
          </h2>

          <form @submit.prevent="submitForm" class="mt-6 space-y-6">
            <div>
              <input
                type="text"
                id="company"
                v-model="formData.company"
                placeholder="Dein Unternehmen*"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                required
              />
            </div>

            <div>
              <input
                type="email"
                id="email"
                v-model="formData.email"
                placeholder="deine@mail.de* (hier senden wir dein Sales Script hin)"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                required
              />
            </div>

            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="terms"
                  type="checkbox"
                  v-model="formData.termsAccepted"
                  class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                  required
                />
              </div>
              <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700">
                  Ich akzeptiere die
                  <a href="#" class="text-gray-500 underline">Datenschutzbestimmungen</a>
                  und die
                  <a href="#" class="text-gray-500 underline">allgemeine Geschäftsbedingungen</a>.
                </label>
              </div>
            </div>
          </form>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between pt-6 mt-8">
          <button
            v-if="currentStep > 1"
            type="button"
            @click="prevStep"
            class="bg-gray-100 rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          <div v-else class="w-10"></div>
          
          <button
            v-if="currentStep < totalSteps"
            type="button"
            @click="nextStep"
            class="w-full ml-3 bg-indigo-600 rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Weiter
          </button>
          <button
            v-else
            type="button"
            @click="submitForm"
            class="w-full ml-3 bg-indigo-600 rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Sales Script erhalten
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MultiStepForm',
  data() {
    return {
      currentStep: 1,
      totalSteps: 6,
      formData: {
        channelType: '',
        experienceLevel: '',
        goals: [],
        contentType: '',
        audience: '',
        company: '',
        email: '',
        termsAccepted: false
      },
      channelTypes: [
        { value: 'educational', label: 'Bildungskanal', description: 'Tutorials, How-Tos, Wissensvideos' },
        { value: 'entertainment', label: 'Unterhaltungskanal', description: 'Vlogs, Comedy, Challenges' },
        { value: 'business', label: 'Business & Marketing', description: 'Unternehmens- und Marketing-Inhalte' },
        { value: 'lifestyle', label: 'Lifestyle', description: 'Mode, Fitness, Reisen, Beauty' },
        { value: 'tech', label: 'Tech & Gaming', description: 'Technik-Reviews, Gaming, Software' }
      ],
      experienceLevels: [
        { value: 'beginner', label: 'Anfänger', description: 'Ich habe noch keinen Kanal oder bin gerade erst gestartet' },
        { value: 'intermediate', label: 'Fortgeschritten', description: 'Ich habe bereits Videos veröffentlicht und erste Erfolge erzielt' },
        { value: 'expert', label: 'Experte', description: 'Ich bin bereits etabliert und möchte weiter wachsen' }
      ],
      goals: [
        { value: 'growth', label: 'Kanalwachstum', description: 'Mehr Abonnenten und Reichweite' },
        { value: 'monetization', label: 'Monetarisierung', description: 'Einnahmen über YouTube generieren' },
        { value: 'branding', label: 'Markenaufbau', description: 'Bekanntheit steigern und Marke aufbauen' },
        { value: 'community', label: 'Community aufbauen', description: 'Engagierte Zuschauer und aktive Community' },
        { value: 'business', label: 'Geschäftsförderung', description: 'YouTube als Marketing-Kanal für mein Unternehmen' }
      ],
      contentTypes: [
        { value: 'tutorials', label: 'Tutorials & How-Tos', description: 'Anleitungen und Schritt-für-Schritt-Guides' },
        { value: 'vlogs', label: 'Vlogs', description: 'Persönliche Einblicke in deinen Alltag' },
        { value: 'reviews', label: 'Reviews & Tests', description: 'Produkt- oder Dienstleistungsbewertungen' },
        { value: 'entertainment', label: 'Unterhaltung', description: 'Sketche, Comedy, Reaktionen, Challenges' },
        { value: 'interviews', label: 'Interviews & Podcasts', description: 'Gespräche mit Experten oder Gästen' }
      ]
    };
  },
  methods: {
    nextStep() {
      if (this.currentStep < this.totalSteps) {
        this.currentStep++;
        window.scrollTo(0, 0);
      }
    },
    prevStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
        window.scrollTo(0, 0);
      }
    },
    toggleGoal(goal) {
      const index = this.formData.goals.indexOf(goal);
      if (index === -1) {
        this.formData.goals.push(goal);
      } else {
        this.formData.goals.splice(index, 1);
      }
    },
    submitForm() {
      // Hier Logik zum Absenden des Formulars implementieren
      console.log('Form data submitted:', this.formData);
      
      // Weiterleitung zur Dankeseite
      this.$router.push({ name: 'thank-you' });
    }
  }
};
</script> 