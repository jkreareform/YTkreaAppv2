<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Neues Passwort festlegen
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Bitte geben Sie Ihr neues Passwort ein
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="updatePassword">
        <div v-if="error" class="rounded-md bg-red-50 p-4">
          <div class="text-sm text-red-700">
            {{ error }}
          </div>
        </div>
        <div v-if="success" class="rounded-md bg-green-50 p-4">
          <div class="text-sm text-green-700">
            {{ success }}
          </div>
        </div>
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="password" class="sr-only">Neues Passwort</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required v-model="password"
              class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Neues Passwort">
          </div>
          <div>
            <label for="password-confirmation" class="sr-only">Passwort bestätigen</label>
            <input id="password-confirmation" name="password-confirmation" type="password" autocomplete="new-password" required v-model="passwordConfirmation"
              class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Passwort bestätigen">
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd" />
              </svg>
            </span>
            Passwort aktualisieren
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'ResetPassword',
  data() {
    return {
      password: '',
      passwordConfirmation: '',
      error: null,
      success: null
    };
  },
  async mounted() {
    // Überprüfen Sie, ob wir einen Reset-Token in der URL haben
    const hash = window.location.hash;
    if (!hash) {
      this.error = 'Ungültiger oder abgelaufener Reset-Link. Bitte fordern Sie einen neuen Link an.';
      return;
    }

    try {
      // Versuchen Sie, die Session mit dem Reset-Token zu aktualisieren
      const { data, error } = await supabase.auth.getSession();
      if (error) throw error;
      
      if (!data.session) {
        this.error = 'Ungültiger oder abgelaufener Reset-Link. Bitte fordern Sie einen neuen Link an.';
      }
    } catch (error) {
      this.error = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
    }
  },
  methods: {
    async updatePassword() {
      if (this.password !== this.passwordConfirmation) {
        this.error = 'Die Passwörter stimmen nicht überein';
        return;
      }

      if (this.password.length < 6) {
        this.error = 'Das Passwort muss mindestens 6 Zeichen lang sein';
        return;
      }

      try {
        const { data, error } = await supabase.auth.updateUser({
          password: this.password
        });

        if (error) {
          this.error = error.message;
        } else {
          this.success = 'Ihr Passwort wurde erfolgreich aktualisiert. Sie werden in Kürze weitergeleitet.';
          setTimeout(() => {
            this.$router.push('/login');
          }, 2000);
        }
      } catch (error) {
        this.error = error.message;
      }
    }
  }
};
</script> 