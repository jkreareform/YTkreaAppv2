<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Passwort zurücksetzen
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Oder
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
            zurück zum Login
          </router-link>
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="resetPassword">
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
            <label for="email-address" class="sr-only">E-Mail-Adresse</label>
            <input id="email-address" name="email" type="email" autocomplete="email" required v-model="email"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="E-Mail-Adresse">
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd"
                  d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd" />
              </svg>
            </span>
            Reset-Link senden
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'ForgotPassword',
  data() {
    return {
      email: '',
      error: null,
      success: null
    };
  },
  methods: {
    async resetPassword() {
      try {
        const { data, error } = await supabase.auth.resetPasswordForEmail(this.email, {
          redirectTo: `${window.location.origin}/reset-password`,
        });
        
        if (error) {
          this.error = error.message;
          this.success = null;
        } else {
          this.success = 'Wenn ein Konto mit dieser E-Mail-Adresse existiert, erhalten Sie in Kürze einen Reset-Link.';
          this.error = null;
          this.email = '';
        }
      } catch (error) {
        this.error = error.message;
        this.success = null;
      }
    }
  }
};
</script> 