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
        <p class="text-gray-600 mb-2">Schritt 6 von 6</p>
        <div class="w-full bg-gray-100 rounded-full h-2">
          <div class="bg-green-500 h-2 rounded-full w-full"></div>
        </div>
      </div>

      <!-- Form Content -->
      <div class="px-8 pb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-1">
          Fast geschafft: Wohin dürfen wir dein Sales Script senden? 🎉
        </h2>

        <form @submit.prevent="submitForm" class="mt-6 space-y-6">
          <div>
            <input
              type="text"
              id="company"
              v-model="company"
              placeholder="Dein Unternehmen*"
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
              required
            />
          </div>

          <div>
            <input
              type="email"
              id="email"
              v-model="email"
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
                v-model="termsAccepted"
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

          <div class="flex justify-between pt-4">
            <button
              type="button"
              @click="goBack"
              class="bg-gray-100 rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <button
              type="submit"
              class="w-full ml-3 bg-indigo-600 rounded-md py-3 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Sales Script erhalten
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SalesScriptForm',
  data() {
    return {
      company: '',
      email: '',
      termsAccepted: false
    };
  },
  methods: {
    submitForm() {
      // Hier die Logik für das Absenden des Formulars implementieren
      console.log('Form submitted:', {
        company: this.company,
        email: this.email
      });
      
      // Weiterleitung oder Anzeige einer Bestätigung
      this.$router.push({ name: 'thank-you' });
    },
    goBack() {
      // Zurück zum vorherigen Schritt
      this.$router.go(-1);
    }
  }
};
</script> 