<template>
  <div class="flex flex-col h-full">
    <!-- Chat Header -->
    <div class="bg-white border-b border-gray-200 p-4">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">KI Chat</h2>
        <div class="flex space-x-2">
          <button @click="switchAI('gpt')" 
            :class="{'bg-indigo-600 text-white': selectedAI === 'gpt', 'bg-gray-200 text-gray-700': selectedAI !== 'gpt'}"
            class="px-3 py-1 rounded-md text-sm font-medium">
            ChatGPT
          </button>
          <button @click="switchAI('deepseek')"
            :class="{'bg-indigo-600 text-white': selectedAI === 'deepseek', 'bg-gray-200 text-gray-700': selectedAI !== 'deepseek'}"
            class="px-3 py-1 rounded-md text-sm font-medium">
            Deepseek
          </button>
        </div>
      </div>
    </div>

    <!-- Chat Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4">
      <div v-for="(message, index) in messages" :key="index" 
        :class="{'flex justify-end': message.isUser, 'flex justify-start': !message.isUser}">
        <div :class="{'bg-indigo-600 text-white': message.isUser, 'bg-gray-100 text-gray-800': !message.isUser}"
          class="max-w-[70%] rounded-lg px-4 py-2">
          <p class="text-sm">{{ message.content }}</p>
        </div>
      </div>
      <div v-if="isLoading" class="flex justify-start">
        <div class="bg-gray-100 rounded-lg px-4 py-2">
          <p class="text-sm text-gray-600">Denke nach...</p>
        </div>
      </div>
    </div>

    <!-- Chat Input -->
    <div class="bg-white border-t border-gray-200 p-4">
      <form @submit.prevent="sendMessage" class="flex space-x-2">
        <input v-model="newMessage" type="text" 
          class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          placeholder="Schreiben Sie Ihre Nachricht..." :disabled="isLoading">
        <button type="submit" 
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          :disabled="isLoading || !newMessage.trim()">
          Senden
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Chat',
  data() {
    return {
      messages: [],
      newMessage: '',
      isLoading: false,
      selectedAI: 'gpt' // Standardmäßig ChatGPT
    };
  },
  methods: {
    async sendMessage() {
      if (!this.newMessage.trim() || this.isLoading) return;

      const userMessage = {
        content: this.newMessage,
        isUser: true
      };

      this.messages.push(userMessage);
      this.newMessage = '';
      this.isLoading = true;

      try {
        // Sende Anfrage an Server-Endpunkt anstelle der direkten API
        const response = await axios.post('/chat/send', {
          prompt: userMessage.content,
          model: this.selectedAI
        });

        if (response.data.success) {
          this.messages.push({
            content: response.data.response,
            isUser: false
          });
        } else {
          throw new Error(response.data.message || 'Ein Fehler ist aufgetreten');
        }
      } catch (error) {
        console.error('Error sending message:', error);
        this.messages.push({
          content: `Entschuldigung, es ist ein Fehler aufgetreten: ${error.message || 'Unbekannter Fehler'}`,
          isUser: false
        });
      } finally {
        this.isLoading = false;
      }
    },
    switchAI(ai) {
      this.selectedAI = ai;
      // Optional: Hier können Sie die Konversation zurücksetzen oder eine neue starten
      this.messages = [];
    }
  }
};
</script> 