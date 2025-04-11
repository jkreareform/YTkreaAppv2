<template>
  <div class="chat-container bg-white">
    <!-- Fester Haupttitel mit MenÃ¼-Toggle fÃ¼r MobilgerÃ¤te -->
    <div class="fixed-header bg-white shadow-sm border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
    <button 
      v-if="isMobile" 
      @click="toggleMobileMenu" 
              class="p-2 text-gray-600 focus:outline-none mr-3"
    >
      <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
            <h1 class="text-2xl font-bold text-gray-900">Ideen Chat</h1>
          </div>
          <div class="w-6"></div> <!-- Platzhalter fÃ¼r Ausrichtung -->
        </div>
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div 
      v-if="isMobile && mobileMenuOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-40"
      @click="closeMobileMenu"
    ></div>

    <!-- Linke Sidebar - auf Desktop immer sichtbar, auf Mobile als Overlay -->
    <div 
      :class="{
        'w-80': (!isMobile || mobileMenuOpen) && !sidebarCollapsed, 
        'w-0': isMobile && !mobileMenuOpen,
        'w-16': !isMobile && sidebarCollapsed,
        'fixed inset-y-0 left-0 z-50 bg-white shadow-lg': isMobile,
        'top-0': isMobile
      }" 
      class="bg-white h-full border-r border-gray-100 flex flex-col overflow-hidden transition-all duration-300 ease-in-out group sidebar-container"
      @mouseenter="!isMobile && (sidebarCollapsed = false)"
      @mouseleave="!isMobile && (sidebarCollapsed = true)"
    >
      <!-- Mobile Sidebar Header -->
      <div v-if="isMobile" class="bg-pink-600 text-white p-4 flex items-center justify-between">
        <h2 class="font-bold">Chat-Verlauf</h2>
        <button @click="closeMobileMenu" class="p-2 -mr-2 text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Neuer Chat Button -->
      <div class="p-4 flex">
        <router-link 
          :to="{ name: 'idea-chat' }" 
          custom
          v-slot="{ navigate }"
        >
          <button 
            @click="() => { navigate(); if (isMobile) closeMobileMenu(); }"
            :class="{
              'w-full justify-center': !sidebarCollapsed || mobileMenuOpen,
              'w-10 h-10': sidebarCollapsed && !mobileMenuOpen && !isMobile
            }"
            class="flex items-center bg-pink-600 hover:bg-pink-700 text-white rounded-md shadow-md transition-all duration-300 hover:shadow-lg"
          >
            <div class="flex items-center justify-center w-10 h-10 flex-shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <span 
              v-if="!sidebarCollapsed || mobileMenuOpen"
              class="pl-1 pr-4 font-medium flex-1 text-center whitespace-nowrap"
            >
              Neuer Chat
            </span>
          </button>
        </router-link>
      </div>
      
      <!-- Chat-Verlauf -->
      <div 
        class="flex-1 overflow-auto px-3 py-2"
        :class="{'hidden': sidebarCollapsed && !isMobile, 'block': !sidebarCollapsed || isMobile}"
      >
        <!-- Heutige Chats -->
        <div v-if="todayChats.length > 0" class="mb-6">
          <h3 class="text-xs font-medium text-gray-500 mb-2 px-3">HEUTE</h3>
          <div class="space-y-1">
            <div 
              v-for="chat in todayChats" 
              :key="chat.id"
              class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer hover:bg-gray-50 w-full sidebar-chat-item"
              :class="{'bg-pink-50 sidebar-chat-item-active': isChatActive(chat)}"
            >
              <p 
                @click="loadChat(chat)" 
                class="text-sm text-gray-800 truncate max-w-[calc(100%-30px)] overflow-hidden text-ellipsis whitespace-nowrap"
              >
                {{ chat.title || 'Unbenannter Chat' }}
              </p>
              <div @click.stop="confirmDeleteChat(chat)" class="trash-icon ml-1 w-5 h-5 flex items-center justify-center text-gray-400 hover:text-pink-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Chats der letzten Woche -->
        <div v-if="lastWeekChats.length > 0" class="mb-6">
          <h3 class="text-xs font-medium text-gray-500 mb-2 px-3">LAST WEEK</h3>
          <div class="space-y-1">
            <div 
              v-for="chat in lastWeekChats" 
              :key="chat.id"
              class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 w-full sidebar-chat-item"
              :class="{'bg-gray-100 dark:bg-gray-700 sidebar-chat-item-active': isChatActive(chat)}"
            >
              <p 
                @click="loadChat(chat)" 
                class="text-sm text-gray-800 dark:text-gray-200 truncate max-w-[calc(100%-30px)] overflow-hidden text-ellipsis whitespace-nowrap"
              >
                {{ chat.title || 'Untitled Chat' }}
              </p>
              <div @click.stop="confirmDeleteChat(chat)" class="trash-icon ml-1 w-5 h-5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Ã„ltere Chats -->
        <div v-if="olderChats.length > 0" class="mb-6">
          <h3 class="text-xs font-medium text-gray-500 mb-2 px-3">OLDER</h3>
          <div class="space-y-1">
            <div 
              v-for="chat in olderChats" 
              :key="chat.id"
              class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 w-full sidebar-chat-item"
              :class="{'bg-gray-100 dark:bg-gray-700 sidebar-chat-item-active': isChatActive(chat)}"
            >
              <p 
                @click="loadChat(chat)" 
                class="text-sm text-gray-800 dark:text-gray-200 truncate max-w-[calc(100%-30px)] overflow-hidden text-ellipsis whitespace-nowrap"
              >
                {{ chat.title || 'Untitled Chat' }}
              </p>
              <div @click.stop="confirmDeleteChat(chat)" class="trash-icon ml-1 w-5 h-5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Hilfebuttton unten -->
      <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <!-- Question mark button removed -->
      </div>
    </div>
    
    <!-- Chat-Hauptbereich -->
    <div class="chat-main">
      <!-- Chat Header mit Titel und Aktionen - NUR anzeigen, wenn eine Nachricht existiert -->
      <div v-if="messages.length > 0" class="chat-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between">
          <div class="flex items-center">
              <!-- Mobile Menu Button (nur auf mobilen GerÃ¤ten und wenn Nachrichten vorhanden sind) -->
              <button 
                v-if="isMobile" 
                @click="toggleMobileMenu" 
                class="mr-3 p-2 text-gray-600 hover:text-pink-600 focus:outline-none"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
              </button>
              
              <h2 class="text-lg font-medium text-gray-900">{{ chatTitle || 'Neuer Chat' }}</h2>
              <button @click="editChatTitle" class="text-gray-400 hover:text-gray-600 ml-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
                </button>
          </div>
          <div class="flex space-x-2">
            <!-- Header buttons -->
            </div>
          </div>
        </div>
      </div>
      
      <!-- Chat Messages Area - ONLY THIS SHOULD SCROLL -->
      <div class="chat-messages bg-gradient-to-b from-gray-50 to-white sm:bg-white">
        <!-- Willkommensbildschirm, wenn keine Nachrichten vorhanden -->
        <div v-if="messages.length === 0" class="flex flex-col items-center justify-start h-full pt-4">
          <h2 class="text-2xl font-bold mb-3 text-gray-900">Willkommen beim Ideen Chat</h2>
          <p class="text-gray-600 mb-6 max-w-md text-center">Dein Partner fÃ¼r kreative YouTube-Video-Ideen</p>
          
          <div class="text-left w-full max-w-2xl mb-6">
            <h3 class="font-semibold mb-3 text-gray-800">Beispiele zum Ausprobieren</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Analysiere die Content-Strategie von @MrBeast')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">ðŸ"</span>
                  <div>
                    <p class="font-medium text-gray-900">MrBeast Strategie analysieren</p>
                    <p class="text-sm text-gray-600">Erfolgreiche Content-Strategien verstehen</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Generiere ansprechende Video-Titel')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">âœ¨</span>
                  <div>
                    <p class="font-medium text-gray-900">Video-Titel erstellen</p>
                    <p class="text-sm text-gray-600">Ansprechende Titel generieren</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Entwickle eine Marketing-Funnel-Strategie fÃ¼r lokale Kunden')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">ðŸ"Š</span>
                  <div>
                    <p class="font-medium text-gray-900">Marketing-Funnel planen</p>
                    <p class="text-sm text-gray-600">Strategie fÃ¼r lokale Kunden entwickeln</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Entwirf kreative Werbeanzeigen fÃ¼r YouTube')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">ðŸŽ¯</span>
                  <div>
                    <p class="font-medium text-gray-900">Werbeanzeigen erstellen</p>
                    <p class="text-sm text-gray-600">Kreative AnsÃ¤tze fÃ¼r Anzeigen</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Chat Nachrichten -->
        <div v-else class="message-container">
          <div v-for="(message, index) in formattedMessages" :key="index" class="message-wrapper">
            <!-- User Message -->
            <div v-if="message.isUser" class="user-message">
              <div class="message-content bg-pink-50 border border-pink-100 text-gray-900 shadow-sm">
                <p>{{ message.content }}</p>
              </div>
            </div>
            
            <!-- Assistant Message -->
            <div v-else class="assistant-message">
              <div class="flex items-start">
                <div class="assistant-avatar flex-shrink-0">
                  <div class="avatar-circle bg-primary-100 text-primary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                  </div>
                </div>
                
                <div class="message-and-actions flex-1 max-w-full">
                  <div class="message-content bg-white sm:bg-gray-50 border border-gray-100 text-gray-900 shadow-sm">
                    <!-- Loading state -->
                    <div v-if="message.isLoading" class="flex flex-col">
                      <div class="flex space-x-2">
                        <div class="w-3 h-3 bg-gray-200 rounded-full animate-bounce"></div>
                        <div class="w-3 h-3 bg-gray-200 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-3 h-3 bg-gray-200 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                      </div>
                      <p class="text-sm text-gray-600 mt-2">{{ message.content }}</p>
                    </div>
                    <!-- Rendered message -->
                    <div v-else class="prose max-w-none text-gray-800 text-base markdown-content" v-html="message.formattedContent"></div>
                  </div>
                  
                  <!-- Message Actions -->
                  <div class="message-actions">
                    <button class="action-button text-gray-400 hover:text-pink-600" @click="copyToClipboard(message.content)">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Input area - Fixed at bottom -->
      <div class="chat-input bg-white border-t border-gray-100 shadow-md">
        <div class="max-w-4xl mx-auto">
          <!-- Kontext-Anzeige, wenn vorhanden -->
          <div v-if="activeContext" class="mb-2 px-4 py-1 bg-pink-50 rounded-md flex items-center justify-between">
            <div class="flex items-center text-sm text-pink-700">
              <span v-if="activeContext.type === 'channel'" class="mr-2">ðŸ"º</span>
              <span v-else-if="activeContext.type === 'script'" class="mr-2">ðŸ"</span>
              <span>{{ activeContext.name }}</span>
            </div>
            <button @click="clearContext" class="text-pink-500 hover:text-pink-700">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Texteingabe mit Buttons -->
          <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mx-4 mb-4">
            <!-- Button to add context -->
            <button 
              @click="showContextDropdown = !showContextDropdown" 
              class="text-gray-500 hover:text-pink-600 p-4 bg-gray-50 context-button transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </button>
            
            <div class="relative flex-1">
                  <textarea 
                    v-model="newMessage" 
                rows="1"
                placeholder="Stelle Fragen zu YouTube-Content und Marketing..." 
                class="w-full px-4 py-4 bg-white border-0 focus:ring-0 focus:outline-none resize-none text-base"
                :class="{'h-12': newMessage.length < 100, 'h-24': newMessage.length >= 100}"
                @keydown.enter.ctrl="sendMessage"
                @keydown="e => e.key === 'Enter' && !e.shiftKey && (e.preventDefault(), sendMessage())"
                  ></textarea>
              
              <!-- Hints -->
              <div class="absolute right-3 bottom-2 flex items-center gap-2 text-gray-400 text-xs">
                <span>Shift + Enter fÃ¼r neue Zeile</span>
                </div>
            </div>
            
            <!-- Send Button -->
                  <button 
              @click="sendMessage" 
                    :disabled="isLoading || !newMessage.trim()"
              class="bg-pink-600 hover:bg-pink-700 text-white p-4 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
                  </button>
            </div>
          </div>
        </div>
      </div>
    
    <!-- Prompt-Bibliothek Modal -->
    <div v-if="showPromptLibrary" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl w-full max-w-5xl h-3/4 overflow-hidden flex flex-col shadow-2xl">
        <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Prompt Library</h2>
          <button @click="showPromptLibrary = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="flex flex-1 overflow-hidden">
          <!-- Categories Sidebar -->
          <div class="w-64 bg-gray-50 dark:bg-gray-700 overflow-y-auto p-3 border-r border-gray-200 dark:border-gray-600">
            <div 
              v-for="category in promptCategories" 
              :key="category.name"
              @click="selectPromptCategory(category)"
              :class="{'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300': selectedCategory === category}"
              class="p-3 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer rounded-lg mb-2 flex items-center transition-colors"
            >
              <span class="mr-2 text-xl">
                <template v-if="category.name === 'YouTube Ideas'">ðŸš€</template>
                <template v-else-if="category.name === 'Content Strategy'">ðŸ"Š</template>
                <template v-else-if="category.name === 'Video Production'">ðŸŽ¬</template>
                <template v-else>ðŸ"</template>
              </span>
              <span class="font-medium">{{ category.name }}</span>
            </div>
          </div>
          
          <!-- Prompts -->
          <div class="flex-1 overflow-y-auto p-5 bg-white dark:bg-gray-800">
            <div v-if="selectedCategory">
              <h3 class="text-xl font-semibold mb-5 text-gray-900 dark:text-white">{{ selectedCategory.name }} Prompts</h3>
              
              <div 
                v-for="prompt in selectedCategory.prompts" 
                :key="prompt.title"
                class="mb-6 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow"
              >
                <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                  <span class="mr-3 text-blue-500 text-xl">ðŸ"</span>
                  <span class="font-medium text-gray-900 dark:text-white text-lg">{{ prompt.title }}</span>
                </div>
                <div class="p-5 bg-white dark:bg-gray-800">
                  <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ prompt.content }}</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-700 text-right">
                  <button 
                    @click="usePrompt(prompt)" 
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 shadow-sm hover:shadow-md transition-all"
                  >
                    Use Prompt
                  </button>
                </div>
              </div>
            </div>
            
            <div v-else class="flex flex-col items-center justify-center h-full">
              <div class="text-center p-8">
                <div class="text-5xl mb-4">ðŸ"š</div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Select a Category</h3>
                <p class="text-gray-600 dark:text-gray-400">Choose a prompt category from the sidebar to view available prompts.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Chat Title Edit Modal -->
    <div v-if="showTitleEdit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Chat Title</h2>
        </div>
        <div class="p-5">
          <input 
            v-model="editedTitle" 
            type="text" 
            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base"
            placeholder="Enter a title for this chat"
          >
        </div>
        <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700">
          <button @click="showTitleEdit = false" class="text-gray-600 dark:text-gray-400 px-4 py-2 mr-2">Cancel</button>
          <button @click="saveChatTitle" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save</button>
        </div>
      </div>
    </div>
    
    <!-- Chat-Historie Modal -->
    <div v-if="showChatHistory" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
          <h2 class="text-lg font-bold">Chat History</h2>
          <button @click="showChatHistory = false" class="text-gray-500 hover:text-gray-700">Ã—</button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4">
          <div v-if="chatHistory.length === 0" class="text-center text-gray-500 py-8">
            No saved chats found.
          </div>
          <div v-else class="space-y-3">
            <div 
              v-for="(chat, index) in chatHistory" 
              :key="index"
              class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100"
              @click="loadChat(chat)"
            >
              <div class="font-medium">{{ chat.title || 'Untitled Chat' }}</div>
              <div class="text-sm text-gray-500">{{ formatDate(chat.date) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kontext-Dropdown -->
    <div 
      v-if="showContextDropdown" 
      class="fixed right-0 bottom-20 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-20 overflow-hidden context-dropdown"
    >
      <!-- Tab Navigation -->
      <div class="flex border-b border-gray-200 dark:border-gray-700">
        <button 
          @click="activeTab = 'channels'" 
          class="flex-1 py-2 px-4 text-sm font-medium"
          :class="{
            'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white': activeTab === 'channels',
            'text-gray-600 dark:text-gray-400': activeTab !== 'channels'
          }"
        >
          YouTube Channels
        </button>
        <button 
          @click="activeTab = 'scripts'" 
          class="flex-1 py-2 px-4 text-sm font-medium"
          :class="{
            'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white': activeTab === 'scripts',
            'text-gray-600 dark:text-gray-400': activeTab !== 'scripts'
          }"
        >
          Video Scripts
        </button>
      </div>
      
      <!-- Search & Content -->
      <div class="p-4">
        <input 
          type="text" 
          v-model="contextSearch"
          :placeholder="activeTab === 'channels' ? 'Search channels...' : 'Search scripts...'" 
          class="w-full p-2 mb-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
        
        <!-- Channels Tab -->
        <div v-if="activeTab === 'channels'" class="max-h-48 overflow-y-auto">
          <div v-if="filteredChannels.length === 0" class="text-center p-4 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-md">
            No channels found
            <div class="mt-2">
              <router-link to="/channels/new" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                + Add a new channel
              </router-link>
            </div>
          </div>
          
          <div 
            v-for="channel in filteredChannels" 
            :key="channel.id" 
            @click="selectContext('channel', channel.id, channel.name)"
            class="p-3 mb-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border border-gray-200 dark:border-gray-700"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-2 rounded-full">
                <span>ðŸ"º</span>
              </div>
              <div class="ml-3">
                <div class="font-medium">{{ channel.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ channel.description || 'No description' }}</div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Scripts Tab -->
        <div v-if="activeTab === 'scripts'" class="max-h-48 overflow-y-auto">
          <div v-if="filteredScripts.length === 0" class="text-center p-4 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-md">
            No scripts found
            <div class="mt-2">
              <router-link to="/scripts/new" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                + Create a new script
              </router-link>
            </div>
          </div>
          
          <div 
            v-for="script in filteredScripts" 
            :key="script.id" 
            @click="selectContext('script', script.id, script.name)"
            class="p-3 mb-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border border-gray-200 dark:border-gray-700"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-2 rounded-full">
                <span>ðŸ"</span>
              </div>
              <div class="ml-3">
                <div class="font-medium">{{ script.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                  {{ script.description || (script.content ? script.content.substring(0, 50) + '...' : 'No content') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- BestÃ¤tigungs-Modal fÃ¼r Chat-LÃ¶schung -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md overflow-hidden shadow-xl">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Delete Chat</h2>
        </div>
        <div class="p-4">
          <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this chat? This action cannot be undone.</p>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ chatToDelete?.title || 'Untitled Chat' }}</p>
        </div>
        <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700">
          <button @click="cancelDeleteChat" class="text-gray-600 dark:text-gray-400 px-4 py-2 mr-2">Cancel</button>
          <button @click="confirmAndDeleteChat" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';
import { supabase } from '@/supabase';
import { marked } from 'marked'; // Markdown Parser
import DOMPurify from 'dompurify'; // FÃ¼r sicheres HTML
import emojione from 'emojione'; // Emoji-Parser

export default {
  name: 'IdeaChat',
  data() {
    return {
      messages: [],
      newMessage: '',
      chatTitle: '',
      isLoading: false,
      themePreference: localStorage.getItem('theme') || 'light',
      typing: null,
      mobileMenuOpen: false,
      isMobile: false,
      sidebarCollapsed: window.innerWidth < 1280, // StandardmÃ¤ÃŸig bei kleineren Bildschirmen reduziert
      selectedAI: localStorage.getItem('selectedAI') || 'gpt',
      showModelSelector: false,
      activeContext: null,
      channels: [],
      scripts: [],
      contextSearch: '',
      activeTab: 'channels',
      showContextDropdown: false,
      isTypingEffect: localStorage.getItem('typingEffect') === 'true',
      autoScrollEnabled: localStorage.getItem('autoScroll') !== 'false',
      currentChatId: null,
      chatHistory: [],
      promptCategories: [
        {
          name: 'YouTube Ideas',
          prompts: [
            {
              title: 'Trending Topic Ideas',
              content: 'Generate 10 trending YouTube video ideas for my channel about [YOUR NICHE]. Include a catchy title, brief description, and why each idea would work well.'
            },
            {
              title: 'Content Calendar',
              content: 'Create a 4-week content calendar for my YouTube channel about [YOUR NICHE]. Include video ideas, optimal posting schedule, and how these videos could work together as a series.'
            },
            {
              title: 'Searchable Content Ideas',
              content: 'Generate 10 highly searchable YouTube video ideas for my channel about [YOUR NICHE]. Include the main keyword to target, estimated search volume, and why these would perform well in search results.'
            }
          ]
        },
        {
          name: 'Content Strategy',
          prompts: [
            {
              title: 'Audience Growth Strategy',
              content: 'Analyze my YouTube channel about [YOUR NICHE] and suggest a comprehensive strategy to grow my audience from [CURRENT SUBS] to [TARGET SUBS]. Include content types, posting frequency, and community engagement tactics.'
            },
            {
              title: 'Monetization Plan',
              content: 'Create a 6-month monetization plan for my YouTube channel about [YOUR NICHE]. Include various revenue streams, when to implement each, and estimated earnings potential.'
            }
          ]
        }
      ],
      selectedCategory: null,
      editedTitle: '',
      showTitleEdit: false,
      showDeleteConfirm: false,
      chatToDelete: null,
      supabaseStatus: {
        loggedIn: false,
        userId: null,
        tableExists: false,
        error: null,
        syncInProgress: false,
        lastSync: null,
        lastSyncResult: null,
        message: 'Verbindung zum Server wird hergestellt...'
      },
      debugInfo: '',
      showPromptLibrary: false,
      showChatHistory: false
    };
  },
  computed: {
    todayChats() {
      if (!this.chatHistory) return [];
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      return this.chatHistory.filter(chat => {
        if (!chat.date) return false;
        const chatDate = new Date(chat.date);
        return chatDate >= today;
      });
    },
    lastWeekChats() {
      if (!this.chatHistory) return [];
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      const lastWeek = new Date(today);
      lastWeek.setDate(today.getDate() - 7);
      
      return this.chatHistory.filter(chat => {
        if (!chat.date) return false;
        const chatDate = new Date(chat.date);
        return chatDate >= lastWeek && chatDate < today;
      });
    },
    olderChats() {
      if (!this.chatHistory) return [];
      const lastWeek = new Date();
      lastWeek.setDate(lastWeek.getDate() - 7);
      lastWeek.setHours(0, 0, 0, 0);
      
      return this.chatHistory.filter(chat => {
        if (!chat.date) return false;
        const chatDate = new Date(chat.date);
        return chatDate < lastWeek;
      });
    },
    filteredChannels() {
      if (!this.channels) return [];
      if (!this.contextSearch) return this.channels;
      
      const search = this.contextSearch.toLowerCase();
      return this.channels.filter(channel => 
        channel.name.toLowerCase().includes(search)
      );
    },
    filteredScripts() {
      if (!this.scripts) return [];
      if (!this.contextSearch) return this.scripts;
      
      const search = this.contextSearch.toLowerCase();
      return this.scripts.filter(script => 
        script.name.toLowerCase().includes(search)
      );
    },
    // Formatierte Nachrichten mit Markdown und Emojis
    formattedMessages() {
      return this.messages.map(message => {
        if (!message.isUser) {
          // Markdown und Emojis fÃ¼r AI-Antworten
          return {
            ...message,
            formattedContent: this.formatMessageContent(message.content)
          };
        }
        return message;
      });
    }
  },
  watch: {
    // Auto-Scrollen, wenn neue Nachrichten hinzugefÃ¼gt werden
    messages: {
      deep: true,
      handler() {
        if (this.autoScrollEnabled) {
          this.$nextTick(() => {
            this.scrollToBottom();
          });
        }
      }
    },
    // Darkmode/Light Mode Wechsel
    themePreference: {
      handler(newValue) {
        document.documentElement.classList.toggle('dark', newValue === 'dark');
        localStorage.setItem('theme', newValue);
      },
      immediate: true
    },
    // Watch for route changes
    '$route': {
      handler(to, from) {
        if (to.name === 'idea-chat' && !to.params.chatId) {
          // When navigating to the base route, clear the current chat
          this.clearCurrentChat();
        } else if (to.name === 'specific-chat' && to.params.chatId) {
          // When navigating to a specific chat, load it if it's different from current
          if (this.currentChatId !== to.params.chatId) {
            this.loadChatById(to.params.chatId);
          }
        }
      },
      immediate: true
    }
  },
  async created() {
    // Lade zuerst die Chat-Historie
    await this.loadChatHistory();
    
    // Test Supabase connection
    await this.checkSupabaseConnection();
    
    // Then fetch channels and scripts
    await this.fetchChannelsAndScripts();
    await this.fetchPromptLibrary();
    
    // Handle the initial route
    if (this.$route.name === 'specific-chat' && this.$route.params.chatId) {
      // Try to load the specific chat
      const loaded = await this.loadChatById(this.$route.params.chatId);
      if (!loaded) {
        // If chat not found, redirect to the base chat route
        this.$router.push({ name: 'idea-chat' });
      }
    } else if (this.$route.name === 'idea-chat') {
      // Clear any existing chat when on the base route
      this.clearCurrentChat();
    }
    
    this.detectMobile();
    window.addEventListener('resize', this.detectMobile);
    
    // Add event listener to close dropdowns when clicking outside
    document.addEventListener('click', this.handleOutsideClick);
  },

  // Einfache Funktion zur ÃœberprÃ¼fung der Supabase-Verbindung
  async checkSupabaseConnection() {
    try {
      // Einfacher Verbindungstest
      await supabase.from('idea_chats').select('count').limit(1);
    } catch (error) {
      // Verbindungsfehler - Kein Logging nÃ¶tig
    }
  },
  mounted() {
    // Initial scroll
    this.scrollToBottom();
    
    // Event-Listener fÃ¼r Tastenkombinationen
    document.addEventListener('keydown', this.handleKeyboardShortcuts);
    
    // Detect navbar height and set CSS variable
    this.setNavbarHeight();
    window.addEventListener('resize', this.setNavbarHeight);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.detectMobile);
    document.removeEventListener('keydown', this.handleKeyboardShortcuts);
    document.removeEventListener('click', this.handleOutsideClick);
    window.removeEventListener('resize', this.setNavbarHeight);
  },
  methods: {
    async fetchChannelsAndScripts() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) return;
        
        // Initialisiere leere Arrays fÃ¼r den Fall, dass die Tabellen nicht existieren
        this.channels = [];
        this.scripts = [];
        
        try {
          // Versuche channels zu laden
          const { data: channels, error: channelsError } = await supabase
            .from('channels')
            .select('id, title')
            .eq('user_id', session.user.id);
            
          if (channelsError) {
            // PrÃ¼fe, ob es sich um einen "Tabelle existiert nicht" Fehler handelt
            if (channelsError.code === '42P01') {
              console.warn('Channels table does not exist. Will be created when you add your first channel.');
            } else {
              console.error('Error fetching channels:', channelsError);
            }
          } else if (channels) {
            this.channels = channels.map(c => ({
              id: c.id,
              name: c.title
            }));
          }
        } catch (error) {
          console.error('Error with channels request:', error);
        }

        try {
          // Versuche scripts zu laden
          const { data: scripts, error: scriptsError } = await supabase
            .from('scripts')
            .select('id, title')
            .eq('user_id', session.user.id);
            
          if (scriptsError) {
            // PrÃ¼fe, ob es sich um einen "Tabelle existiert nicht" Fehler handelt
            if (scriptsError.code === '42P01') {
              console.warn('Scripts table does not exist. Will be created when you add your first script.');
            } else {
              console.error('Error fetching scripts:', scriptsError);
            }
          } else if (scripts) {
            this.scripts = scripts.map(s => ({
              id: s.id,
              name: s.title
            }));
          }
        } catch (error) {
          console.error('Error with scripts request:', error);
        }
      } catch (error) {
        console.error('Error in fetchChannelsAndScripts:', error);
      }
    },
    async fetchPromptLibrary() {
      try {
        const response = await axios.get('/chat/prompt-library');
        this.promptLibrary = response.data;
        
        if (this.promptLibrary.length > 0) {
          this.activeCategory = this.promptLibrary[0].category;
        }
      } catch (error) {
        console.error('Error fetching prompt library:', error);
      }
    },
    async sendMessage() {
      if (!this.newMessage.trim() || this.isLoading) return;

      const userMessage = {
        content: this.newMessage,
        isUser: true
      };

      this.messages.push(userMessage);
      this.newMessage = '';
      this.isLoading = true;
      
      // Generate a smart chat title from the first message
      if (this.messages.length === 1 && !this.chatTitle) {
        // Extract the main topic from the message
        let title = userMessage.content;
        
        // If it's too long, try to extract the main subject
        if (title.length > 30) {
          // Check for common question patterns
          if (title.toLowerCase().startsWith('how to')) {
            title = title.substring(0, Math.min(50, title.length));
          } else if (title.toLowerCase().startsWith('what')) {
            title = title.substring(0, Math.min(50, title.length));
          } else if (title.toLowerCase().startsWith('generate')) {
            const match = title.match(/generate\s+(.*?)(?:\s+for|about|on|related to|$)/i);
            if (match && match[1]) {
              title = match[1] + " ideas";
            } else {
              title = title.substring(0, Math.min(40, title.length));
            }
          } else if (title.toLowerCase().includes('ideas') || title.toLowerCase().includes('suggestions')) {
            const match = title.match(/(.*?ideas|suggestions)(?:\s+for|about|on|$)/i);
            if (match && match[1]) {
              title = match[1] + " for " + 
                (title.match(/for\s+(.*?)(?:\s+video|\s+channel|\s+content|$)/i)?.[1] || "");
            } else {
              title = title.substring(0, Math.min(40, title.length));
            }
          } else {
            // Take the first 40 chars by default, don't cut in the middle of a word
            const cutPoint = title.lastIndexOf(' ', 40);
            title = title.substring(0, cutPoint > 20 ? cutPoint : 40);
          }
        }
        
        // Add ellipsis if we shortened the title
        if (title.length < userMessage.content.length) {
          title = title.trim() + '...';
        }
        
        this.chatTitle = title;
      }
      
      try {
        const contextData = this.activeContext ? {
          type: this.activeContext.type,
          id: this.activeContext.id
        } : null;
        
        // Start der Ladeanimation
        const loadingIndicators = [
          'Generating ideas...',
          'Analyzing content...',
          'Forming creative thoughts...',
          'Checking YouTube trends...'
        ];
        
        const randomLoadingText = loadingIndicators[Math.floor(Math.random() * loadingIndicators.length)];
        
        // Zeige Ladeanimation mit Text
        const loadingMessage = {
          content: randomLoadingText,
          isUser: false,
          isLoading: true
        };
        this.messages.push(loadingMessage);
        
        // Speichere den Chat automatisch, auch wenn nur eine User-Nachricht vorhanden ist
        await this.autoSaveChat();
        
        // Versuche, die API aufzurufen
        let response;
        try {
          response = await axios.post('/chat/send', {
            prompt: userMessage.content,
            context: contextData,
            model: this.selectedAI
          });
        } catch (apiError) {
          console.error('API error:', apiError);
          
          // Entferne Ladeanimation
          this.messages = this.messages.filter(m => !m.isLoading);
          
          // PrÃ¼fe, ob es eine Antwort mit Fehlermeldung gibt
          if (apiError.response && apiError.response.data) {
            throw new Error(apiError.response.data.message || 'Error connecting to server');
          } else {
            throw new Error('Error connecting to server. Please try again later.');
          }
        }
        
        // Entferne Ladeanimation
        this.messages = this.messages.filter(m => !m.isLoading);
        
        if (response.data.success) {
          // Typing-Effekt fÃ¼r die Antwort
          if (this.isTypingEffect) {
            // FÃ¼ge leere Nachricht hinzu, die schrittweise gefÃ¼llt wird
            const aiMessage = {
              content: '',
              fullContent: response.data.response,
              isUser: false
            };
            this.messages.push(aiMessage);
            
            // Starte Typing-Effekt
            this.startTypingEffect(this.messages.length - 1);
          } else {
            this.messages.push({
              content: response.data.response,
              isUser: false
            });
          }
          
          // Speichere den Chat automatisch
          await this.autoSaveChat();
        } else {
          throw new Error(response.data.message || 'Unknown error occurred');
        }
      } catch (error) {
        console.error('Error sending message:', error);
        
        // Benutzerfreundliche Fehlermeldung anzeigen
        const errorMessage = error.message || 'An unknown error occurred. Please try again later.';
        
        this.messages.push({
          content: `I'm sorry, an error occurred: ${errorMessage}. Try asking a different question or try again later.`,
          isUser: false
        });
        
        // Auch im Fehlerfall den Chat speichern
        await this.autoSaveChat();
      } finally {
        this.isLoading = false;
      }
    },
    async autoSaveChat() {
      console.log("=== AUTO SAVE CHAT STARTED ===");
      if (this.messages.length === 0) {
        console.log("Keine Nachrichten zum Speichern.");
        return;
      }
      
      try {
        // Get current user session
        const { data: { session } } = await supabase.auth.getSession();
        console.log("Session erhalten:", session?.user?.email);
        
        // Make sure we have a title
        if (!this.chatTitle) {
          const firstUserMessage = this.messages.find(m => m.isUser);
          if (firstUserMessage) {
            let title = firstUserMessage.content;
            // If it's too long, extract the main subject by cutting appropriately
            if (title.length > 40) {
              const cutPoint = title.lastIndexOf(' ', 40);
              title = title.substring(0, cutPoint > 20 ? cutPoint : 40) + '...';
            }
            this.chatTitle = title;
          } else {
            this.chatTitle = 'New Chat';
          }
        }
        
        // We need a logged in user to save to Supabase
        let userId;
        if (session && session.user) {
          userId = session.user.id;
          console.log('Speichere Chat in Supabase fÃ¼r User:', userId);
          
          // Create chat data - direkt ohne JSON.stringify
          const chatData = {
            id: this.currentChatId || uuidv4(), // Use existing ID or generate new one
            user_id: userId,
            title: this.chatTitle,
            messages: this.messages, // Direktes JSON-Objekt statt JSON.stringify
            context: this.activeContext,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
          };
          
          console.log("Chat-Daten erstellt:", chatData.id, chatData.title);
          console.log("Messages Typ:", typeof chatData.messages, Array.isArray(chatData.messages) ? 'Array' : 'Nicht-Array');
          console.log("Messages Anzahl:", chatData.messages.length);
          console.log("Beispiel-Message:", chatData.messages[0]);
          
          // Check if chat already exists in Supabase
          if (this.currentChatId) {
            console.log('Aktualisiere existierenden Chat in Supabase:', this.currentChatId);
            // Update existing chat
            const { data, error } = await supabase
              .from('idea_chats')
              .update({
                title: chatData.title,
                messages: chatData.messages,
                context: chatData.context,
                updated_at: chatData.updated_at
              })
              .eq('id', this.currentChatId)
              .eq('user_id', userId);
            
            if (error) {
              console.error('Fehler beim Aktualisieren des Chats in Supabase:', error);
              
              // Zeige Fehlerdetails
              console.log("Fehlercode:", error.code);
              console.log("Fehlermeldung:", error.message);
              if (error.details) console.log("Fehlerdetails:", error.details);
              
              // If the chat doesn't exist yet (first save), insert it
              console.log('Versuche Insert nach fehlgeschlagenem Update');
              const { data: insertData, error: insertError } = await supabase
                .from('idea_chats')
                .insert([chatData]);
              
              if (insertError) {
                console.error('Fehler beim EinfÃ¼gen des Chats nach fehlgeschlagenem Update:', insertError);
                console.log("Insert-Fehlercode:", insertError.code);
                console.log("Insert-Fehlermeldung:", insertError.message);
                if (insertError.details) console.log("Insert-Fehlerdetails:", insertError.details);
                throw insertError;
              } else {
                console.log('Chat erfolgreich eingefÃ¼gt nach fehlgeschlagenem Update');
              }
              
              // Save the new chat ID
              this.currentChatId = chatData.id;
            } else {
              console.log('Chat erfolgreich in Supabase aktualisiert');
            }
          } else {
            console.log('FÃ¼ge neuen Chat in Supabase ein');
            // Insert new chat
            const { data, error } = await supabase
              .from('idea_chats')
              .insert([chatData]);
            
            if (error) {
              console.error('Fehler beim EinfÃ¼gen des neuen Chats in Supabase:', error);
              console.log("Fehlercode:", error.code);
              console.log("Fehlermeldung:", error.message);
              if (error.details) console.log("Fehlerdetails:", error.details);
              throw error;
            } else {
              console.log('Neuer Chat erfolgreich in Supabase eingefÃ¼gt');
            }
            
            // Save the new chat ID
            this.currentChatId = chatData.id;
          }
          
          // Also save to localStorage as backup
          let savedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          const localChatData = {
            id: chatData.id,
            user_id: userId,
            title: chatData.title,
            messages: this.messages,
            context: this.activeContext,
            date: chatData.updated_at
          };
          
          // Update or add to local storage
          const existingChatIndex = savedChats.findIndex(c => c.id === localChatData.id);
          if (existingChatIndex >= 0) {
            savedChats[existingChatIndex] = localChatData;
          } else {
            savedChats.unshift(localChatData);
          }
          localStorage.setItem('chatHistory', JSON.stringify(savedChats));
          console.log('Saved chat to localStorage as backup');
          
        } else {
          // No logged in user, fall back to localStorage only
          userId = sessionStorage.getItem('temp_user_id') || this.generateTempUserId();
          console.log('No user session, saving to localStorage only with temp ID:', userId);
          
          // Create chat data for localStorage
          const chatData = {
            id: this.currentChatId || uuidv4(),
            user_id: userId,
            title: this.chatTitle,
            messages: this.messages,
            context: this.activeContext,
            date: new Date().toISOString()
          };
          
          // Save to localStorage
          let savedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          
          // Check if there's an existing chat to update
          const existingChatIndex = savedChats.findIndex(c => c.id === chatData.id);
          if (existingChatIndex >= 0) {
            // Update existing chat
            savedChats[existingChatIndex] = chatData;
          } else {
            // Add new chat
            savedChats.unshift(chatData);
            // Save the new chat ID
            this.currentChatId = chatData.id;
          }
          
          localStorage.setItem('chatHistory', JSON.stringify(savedChats));
        }
        
        // Update chat history
        await this.loadChatHistory();
        
        // Update URL if needed (only when first creating the chat)
        if (this.$route.name !== 'specific-chat' && this.currentChatId) {
          this.$router.push({ 
            name: 'specific-chat', 
            params: { chatId: this.currentChatId }
          }).catch(err => {
            if (err.name !== 'NavigationDuplicated') {
              console.error('Navigation error:', err);
            }
          });
        }
      } catch (error) {
        console.error('Error auto-saving chat:', error);
      }
    },
    scrollToBottom() {
      const chatContainer = document.querySelector('.chat-messages');
      if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
      }
    },
    detectMobile() {
      this.isMobile = window.innerWidth < 768;
      if (this.isMobile) {
        this.mobileMenuOpen = false;
      }
    },
    toggleTheme() {
      this.themePreference = this.themePreference === 'light' ? 'dark' : 'light';
    },
    formatMessageContent(content) {
      // Zuerst Markdown zu HTML konvertieren
      let html = marked(content);
      
      // Emojis konvertieren
      html = emojione.toImage(html);
      
      // HTML sÃ¤ubern, um XSS zu verhindern
      html = DOMPurify.sanitize(html);
      
      return html;
    },
    startTypingEffect(messageIndex) {
      if (this.typing) {
        clearInterval(this.typing);
      }
      
      const message = this.messages[messageIndex];
      const fullContent = message.fullContent;
      let typeIndex = 0;
      
      this.typing = setInterval(() => {
        if (typeIndex < fullContent.length) {
          // FÃ¼ge jeweils einen Buchstaben hinzu
          this.messages[messageIndex].content = fullContent.substring(0, typeIndex + 1);
          typeIndex++;
        } else {
          // Stoppe den Effekt, wenn fertig
          clearInterval(this.typing);
        }
      }, 10);  // Schnelle Geschwindigkeit fÃ¼r realistische Darstellung
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
      if (this.mobileMenuOpen) {
        // Prevent scrolling on body when menu is open
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    },
    closeMobileMenu() {
      this.mobileMenuOpen = false;
      document.body.style.overflow = '';
    },
    toggleTypingEffect() {
      this.isTypingEffect = !this.isTypingEffect;
      localStorage.setItem('typingEffect', this.isTypingEffect);
    },
    toggleAutoScroll() {
      this.autoScrollEnabled = !this.autoScrollEnabled;
      localStorage.setItem('autoScroll', this.autoScrollEnabled);
    },
    handleKeyboardShortcuts(e) {
      // Strg+Enter zum Senden
      if (e.ctrlKey && e.key === 'Enter') {
        this.sendMessage();
        e.preventDefault();
      }
      
      // Esc zum SchlieÃŸen von Popups
      if (e.key === 'Escape') {
        this.showPromptLibrary = false;
        this.showTitleEdit = false;
        this.showChatHistory = false;
        this.showContextDropdown = false;
        e.preventDefault();
      }
    },
    toggleContextDropdown() {
      this.showContextDropdown = !this.showContextDropdown;
    },
    selectContext(type, id, name) {
      this.activeContext = {
        type,
        id,
        name
      };
      this.showContextDropdown = false;
    },
    clearContext() {
      this.activeContext = null;
    },
    openPromptLibrary() {
      this.showPromptLibrary = true;
      this.selectedCategory = null;
    },
    selectPromptCategory(category) {
      this.selectedCategory = category;
    },
    usePrompt(prompt) {
      this.newMessage = prompt.content;
      this.showPromptLibrary = false;
      this.selectedCategory = null;
      // Focus on textarea
      this.$nextTick(() => {
        document.querySelector('textarea').focus();
      });
    },
    useExample(exampleText) {
      this.newMessage = exampleText;
      this.sendMessage();
    },
    getModelName(modelId) {
      switch (modelId) {
        case 'gpt': return 'GPT-4';
        case 'gpt3': return 'GPT-3.5 Turbo';
        case 'claude': return 'Claude 3';
        case 'deepseek': return 'DeepSeek';
        default: return 'KI-Modell';
      }
    },
    handleOutsideClick(event) {
      // Close model selector if clicking outside
      if (this.showModelSelector && !event.target.closest('.model-selector')) {
        this.showModelSelector = false;
      }
      
      // Close context dropdown if clicking outside
      if (this.showContextDropdown && !event.target.closest('.context-button') && !event.target.closest('.context-dropdown')) {
        this.showContextDropdown = false;
      }
    },
    async loadChatById(chatId) {
      if (!chatId) return false;
      
      try {
        // Get current user session
        const { data: { session } } = await supabase.auth.getSession();
        
        // First check if we already have this chat loaded
        if (this.currentChatId === chatId && this.messages.length > 0) {
          return true;
        }
        
        if (session?.user?.id) {
          // Try to load from Supabase first
          const { data: chat, error } = await supabase
            .from('idea_chats')
            .select('*')
            .eq('id', chatId)
            .eq('user_id', session.user.id)
            .single();
          
          if (!error && chat) {
            // Set all chat data - Handle messages format (could be string or object)
            try {
              // Wenn messages als String vorliegt, parsen
              this.messages = typeof chat.messages === 'string' ? 
                JSON.parse(chat.messages) : 
                (Array.isArray(chat.messages) ? chat.messages : []);
                
              // Setze den Chat-Titel
              this.chatTitle = chat.title || 'Untitled Chat';
              
              // Wenn context als String vorliegt, parsen
              this.activeContext = chat.context ? 
                (typeof chat.context === 'string' ? JSON.parse(chat.context) : chat.context) : 
                null;
                
              this.currentChatId = chat.id;
              
              console.log('Loaded chat from Supabase:', chat.id, chat.title);
              return true;
            } catch (parseError) {
              console.error('Error parsing chat data:', parseError);
              throw new Error('UngÃ¼ltiges Datenformat: ' + parseError.message);
            }
          }
        }
        
        // Fall back to localStorage if not found in Supabase
        const currentUserId = session?.user?.id || 
          sessionStorage.getItem('temp_user_id') || 
          this.generateTempUserId();
        
        const chatHistory = JSON.parse(localStorage.getItem('chatHistory') || '[]');
        const localChat = chatHistory.find(c => c.id === chatId);
        
        // Verify the chat belongs to the current user
        if (localChat && (localChat.user_id === currentUserId || !localChat.user_id)) {
          // Set all chat data - mit Fehlerbehandlung
          try {
            this.messages = Array.isArray(localChat.messages) ? 
              localChat.messages : 
              (typeof localChat.messages === 'string' ? JSON.parse(localChat.messages) : []);
              
            this.chatTitle = localChat.title || 'Untitled Chat';
            
            this.activeContext = localChat.context ? 
              (typeof localChat.context === 'string' ? JSON.parse(localChat.context) : localChat.context) : 
              null;
              
            this.currentChatId = localChat.id;
            
            console.log('Loaded chat from localStorage:', localChat.id, localChat.title);
            return true;
          } catch (parseError) {
            console.error('Error parsing local chat data:', parseError);
            throw new Error('UngÃ¼ltiges lokales Datenformat: ' + parseError.message);
          }
        }
        
        console.warn('Chat not found:', chatId);
        return false;
      } catch (error) {
        console.error('Error loading chat by ID:', error);
        return false;
      }
    },
    clearCurrentChat() {
      // Only clear if we have a current chat
      if (this.messages.length > 0 || this.currentChatId) {
        this.messages = [];
        this.chatTitle = '';
        this.activeContext = null;
        this.currentChatId = null;
      }
    },
    confirmDeleteChat(chat) {
      this.chatToDelete = chat;
      this.showDeleteConfirm = true;
    },
    cancelDeleteChat() {
      this.showDeleteConfirm = false;
      this.chatToDelete = null;
    },
    async confirmAndDeleteChat() {
      if (!this.chatToDelete) return;
      
      await this.deleteChat(this.chatToDelete);
        this.chatToDelete = null;
      this.showDeleteConfirm = false;
    },
    async deleteChat(chat) {
      if (!chat || !chat.id) return;
      
      try {
        // Get current user session
        const { data: { session } } = await supabase.auth.getSession();
        
        // If logged in, delete from Supabase first
        if (session && session.user) {
          const { error } = await supabase
            .from('idea_chats')
            .delete()
            .eq('id', chat.id)
            .eq('user_id', session.user.id);
            
          if (error) {
            console.error('Error deleting chat from Supabase:', error);
          }
        }
        
        // Always delete from localStorage
        let savedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
        savedChats = savedChats.filter(c => c.id !== chat.id);
        localStorage.setItem('chatHistory', JSON.stringify(savedChats));
        
        // If this was the active chat, clear it
        if (this.currentChatId === chat.id) {
          this.clearCurrentChat();
          // Redirect to the main chat page if we're on a specific chat
          if (this.$route.name === 'specific-chat') {
            this.$router.push({ name: 'idea-chat' });
          }
        }
        
        // Update chat history
        await this.loadChatHistory();
        
        return true;
      } catch (error) {
        console.error('Error deleting chat:', error);
        return false;
      }
    },
    openChannelLink(message) {
      // Implement the logic to open a channel link
      console.log('Opening channel link:', message);
      alert('Channel link functionality will be implemented');
    },
    openScriptLink(message) {
      // Implement the logic to open a script link
      console.log('Opening script link:', message);
      alert('Script link functionality will be implemented');
    },
    async loadChatHistory() {
      try {
        // Get current user session
        const { data: { session }, error: sessionError } = await supabase.auth.getSession();
        if (sessionError) {
          throw new Error(`Session-Fehler: ${sessionError.message}`);
        }
        
        if (session && session.user) {
          // Logged in user - Get chats from Supabase
          const { data: supabaseChats, error } = await supabase
            .from('idea_chats')
            .select('*')
            .eq('user_id', session.user.id)
            .order('updated_at', { ascending: false });
          
          if (error) {
            throw error;
          }
          
          if (supabaseChats && supabaseChats.length > 0) {
            // Format the chats from Supabase
            this.chatHistory = supabaseChats.map(chat => ({
              id: chat.id,
              user_id: chat.user_id,
              title: chat.title || 'Unbenannter Chat',
              messages: chat.messages,  // Direkt verwenden
              context: chat.context,    // Direkt verwenden
              date: chat.updated_at
            }));

            // Aktualisiere zusÃ¤tzlich localStorage als Backup
            localStorage.setItem('chatHistory', JSON.stringify(this.chatHistory));
          } else {
            this.chatHistory = [];
          }
        } else {
          // Not logged in - Use localStorage
          const allSavedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          const tempUserId = sessionStorage.getItem('temp_user_id') || this.generateTempUserId();
          this.chatHistory = allSavedChats.filter(chat => 
            !chat.user_id || chat.user_id === tempUserId
          );
        }
      } catch (error) {
        // Fallback to localStorage
        try {
          const allSavedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          this.chatHistory = allSavedChats;
        } catch (e) {
          this.chatHistory = [];
        }
      }
      
      // Force UI update
      this.$forceUpdate();
      return this.chatHistory;
    },
    generateTempUserId() {
      // Generate a temporary user ID for non-logged in users
      let tempId = sessionStorage.getItem('temp_user_id');
      if (!tempId) {
        tempId = 'temp_' + uuidv4();
        sessionStorage.setItem('temp_user_id', tempId);
      }
      return tempId;
    },
    loadChat(chat) {
      // Load a saved chat
      this.messages = chat.messages;
      this.chatTitle = chat.title;
      this.activeContext = chat.context;
      this.currentChatId = chat.id;
      
      // Update the URL to reflect the loaded chat ID
      if (this.$route.params.chatId !== chat.id) {
        this.$router.push({ 
          name: 'specific-chat', 
          params: { chatId: chat.id }
        }).catch(err => {
          if (err.name !== 'NavigationDuplicated') {
            console.error('Navigation error:', err);
          }
        });
      }
      
      // Close the modal
      this.showChatHistory = false;
    },
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('de-DE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    },
    isChatActive(chat) {
      // PrÃ¼fen, ob der aktuelle Chat der Ã¼bergebene Chat ist
      if (this.currentChatId && chat.id) {
        return this.currentChatId === chat.id;
      }
      return false;
    },
    switchAI(model) {
      this.selectedAI = model;
    },
    editChatTitle() {
      this.editedTitle = this.chatTitle;
      this.showTitleEdit = true;
    },
    saveChatTitle() {
      this.chatTitle = this.editedTitle;
      this.showTitleEdit = false;
      this.saveChat(); // Speichere den Chat mit dem neuen Titel
    },
    async startNewChat() {
      // Clear current chat data
      this.clearCurrentChat();
      
      // Generate a new chat ID
      this.currentChatId = uuidv4();
      
      // Ensure we have a user id (either real or temporary)
      const { data: { session } } = await supabase.auth.getSession();
      if (!session?.user?.id) {
        const tempUserId = sessionStorage.getItem('temp_user_id') || this.generateTempUserId();
        sessionStorage.setItem('temp_user_id', tempUserId);
      }
      
      // Navigate to the new chat URL
      this.$router.push({ path: `/idea-chat/${this.currentChatId}` });
      
      return this.currentChatId;
    },
    async saveChat(silent = false) {
      if (this.messages.length === 0) return Promise.resolve(false);
      
      try {
        // Get current user session
        const { data: { session } } = await supabase.auth.getSession();
        
        // Make sure we have a title
        if (!this.chatTitle) {
          const firstUserMessage = this.messages.find(m => m.isUser);
          if (firstUserMessage) {
            let title = firstUserMessage.content;
            // If it's too long, extract the main subject by cutting appropriately
            if (title.length > 40) {
              const cutPoint = title.lastIndexOf(' ', 40);
              title = title.substring(0, cutPoint > 20 ? cutPoint : 40) + '...';
            }
            this.chatTitle = title;
          } else {
            this.chatTitle = 'New Chat';
          }
        }
        
        // We need a logged in user to save to Supabase
        let userId;
        let savedToSupabase = false;
        
        if (session && session.user) {
          userId = session.user.id;
          
          // Create chat data
          const chatData = {
            id: this.currentChatId || uuidv4(), // Use existing ID or generate new one
            user_id: userId,
            title: this.chatTitle,
            messages: this.messages, // Direktes JSON-Objekt statt JSON.stringify
            context: this.activeContext,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
          };
          
          // Check if chat already exists in Supabase
          if (this.currentChatId) {
            // Update existing chat
            const { data, error } = await supabase
              .from('idea_chats')
              .update({
                title: chatData.title,
                messages: chatData.messages,
                context: chatData.context,
                updated_at: chatData.updated_at
              })
              .eq('id', this.currentChatId)
              .eq('user_id', userId);
            
            if (error) {
              // If the chat doesn't exist yet (first save), insert it
              const { data: insertData, error: insertError } = await supabase
                .from('idea_chats')
                .insert([chatData]);
              
              if (insertError) throw insertError;
              savedToSupabase = true;
            } else {
              savedToSupabase = true;
            }
          } else {
            // Insert new chat
            const { data, error } = await supabase
              .from('idea_chats')
              .insert([chatData]);
            
            if (error) throw error;
            savedToSupabase = true;
            
            // Save the new chat ID
            this.currentChatId = chatData.id;
          }
        } else {
          // No logged in user, fall back to localStorage only
          userId = sessionStorage.getItem('temp_user_id') || this.generateTempUserId();
        }
        
        // Always save to localStorage as backup
        let savedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
        
        // Create chat data for localStorage
        const localChatData = {
          id: this.currentChatId || uuidv4(),
          user_id: userId,
          title: this.chatTitle,
          messages: this.messages,
          context: this.activeContext,
          date: new Date().toISOString()
        };
        
        // Check if there's an existing chat to update
        const existingChatIndex = savedChats.findIndex(c => c.id === localChatData.id);
        if (existingChatIndex >= 0) {
          // Update existing chat
          savedChats[existingChatIndex] = localChatData;
        } else {
          // Add new chat
          savedChats.unshift(localChatData);
          // Save the new chat ID if not already set
          if (!this.currentChatId) {
            this.currentChatId = localChatData.id;
          }
        }
        
        localStorage.setItem('chatHistory', JSON.stringify(savedChats));
        
        // Update chat history
        await this.loadChatHistory();
        
        // Update URL if needed (only when first creating the chat)
        if (this.$route.name !== 'specific-chat' && this.currentChatId) {
          this.$router.push({ 
            name: 'specific-chat', 
            params: { chatId: this.currentChatId }
          }).catch(err => {
            if (err.name !== 'NavigationDuplicated') {
              console.error('Navigation error:', err);
            }
          });
        }
        
        // Show success message unless silent is true
        if (!silent) {
          if (savedToSupabase) {
            alert('Chat saved to database successfully');
          } else {
            alert('Chat saved to local storage (login to save to database)');
          }
        }
        
        return Promise.resolve(true);
      } catch (error) {
        console.error('Error saving chat:', error);
        if (!silent) {
          alert('Error saving chat: ' + error.message);
        }
        return Promise.resolve(false);
      }
    },
    shareChat() {
      if (this.messages.length === 0) {
        alert('There is nothing to share.');
        return;
      }
      
      // Save the chat first to ensure it has an ID
      this.saveChat(true).then(() => {
        if (this.currentChatId) {
          // Create the shareable URL
          const shareableUrl = `${window.location.origin}/idea-chat/${this.currentChatId}`;
          
          // Copy to clipboard
          navigator.clipboard.writeText(shareableUrl)
            .then(() => {
              alert('Chat link copied to clipboard. You can now share it with others.');
            })
            .catch(err => {
              alert(`Share link: ${shareableUrl}`);
              console.error('Could not copy to clipboard:', err);
            });
        }
      });
    },
    // Add new method to detect and set navbar height
    setNavbarHeight() {
      // Get navbar element - adjust selector to match your navbar
      const navbar = document.querySelector('nav') || document.querySelector('.navbar');
      if (navbar) {
        const navbarHeight = navbar.offsetHeight;
        document.documentElement.style.setProperty('--navbar-height', `${navbarHeight}px`);
      }
    },
    formatTime(date) {
      if (!date) return '';
      
      // Verwende einen Formatter, der nur die Zeit anzeigt
      const formatter = new Intl.DateTimeFormat('de-DE', {
        hour: '2-digit',
        minute: '2-digit'
      });
      
      return formatter.format(new Date(date));
    },
    async forceSyncWithSupabase() {
      if (!this.supabaseStatus.loggedIn || !this.supabaseStatus.tableExists) {
        alert('Bitte einloggen und sicherstellen, dass die Tabelle existiert');
        return;
      }
      
      this.supabaseStatus.syncInProgress = true;
      this.supabaseStatus.message = 'Synchronisiere...';
      
      try {
        // Aktueller Chat speichern, falls vorhanden
        if (this.messages.length > 0) {
          await this.autoSaveChat();
        }
        
        // Alle Chats aus Supabase laden
        const { data: supabaseChats, error } = await supabase
          .from('idea_chats')
          .select('*')
          .eq('user_id', this.supabaseStatus.userId)
          .order('updated_at', { ascending: false });
        
        if (error) {
          throw error;
        }
        
        // Chats verarbeiten und in localStorage speichern
        if (supabaseChats && supabaseChats.length > 0) {
          const formattedChats = supabaseChats.map(chat => ({
            id: chat.id,
            user_id: chat.user_id,
            title: chat.title,
            messages: chat.messages, // Direkt das JSON-Objekt verwenden
            context: chat.context,
            date: chat.updated_at
          }));
          
          // In localStorage speichern
          localStorage.setItem('chatHistory', JSON.stringify(formattedChats));
          
          // Chat-Historie aktualisieren
          this.chatHistory = formattedChats;
          
          this.supabaseStatus.lastSync = new Date();
          this.supabaseStatus.lastSyncResult = `Erfolgreich (${formattedChats.length} Chats)`;
          this.supabaseStatus.message = 'Synchronisation erfolgreich';
        } else {
          this.supabaseStatus.lastSync = new Date();
          this.supabaseStatus.lastSyncResult = 'Keine Chats gefunden';
          this.supabaseStatus.message = 'Keine Chats in der Cloud gefunden';
        }
      } catch (error) {
        console.error('Sync error:', error);
        this.supabaseStatus.error = error.message;
        this.supabaseStatus.lastSync = new Date();
        this.supabaseStatus.lastSyncResult = 'Fehler: ' + error.message;
        this.supabaseStatus.message = 'Synchronisation fehlgeschlagen';
      } finally {
        this.supabaseStatus.syncInProgress = false;
      }
    },
    async createSupabaseTable() {
      // Login-Check entfernt
      this.supabaseStatus.message = 'Erstelle Tabelle...';
      
      try {
        // VerbindungsprÃ¼fung
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session) {
          this.supabaseStatus.message = 'Nicht eingeloggt, aber versuche trotzdem...';
          console.warn('Nicht eingeloggt bei dem Versuch, die Tabelle zu erstellen');
        }
        
        // SQL zur Tabellenerstellung ausfÃ¼hren
        const { error } = await supabase.rpc('create_idea_chats_table');
        
        if (error) {
          throw error;
        }
        
        this.supabaseStatus.tableExists = true;
        this.supabaseStatus.message = 'Tabelle erfolgreich erstellt';
        this.supabaseStatus.error = null;
      } catch (error) {
        console.error('Error creating table:', error);
        this.supabaseStatus.error = error.message;
        this.supabaseStatus.message = 'Fehler beim Erstellen der Tabelle';
        
        // Alternativer Ansatz mit SQL-Anzeige
        alert('MÃ¶glicherweise musst du das SQL-Skript manuell in der Supabase SQL-Konsole ausfÃ¼hren. Fehler: ' + error.message);
      }
    },
    async testSaveChat() {
      this.debugInfo = "Versuche, Test-Chat zu speichern...";
      
      try {
        // PrÃ¼fe, ob wir eingeloggt sind
        const { data: { session } } = await supabase.auth.getSession();
        
        let userId;
        if (session && session.user) {
          userId = session.user.id;
          this.debugInfo += "\nEingeloggt als: " + session.user.email;
        } else {
          // Anonymer Test-Benutzer
          userId = 'anon-' + uuidv4().substring(0, 8);
          this.debugInfo += "\nNicht eingeloggt, verwende temporÃ¤re ID: " + userId;
        }
        
        // Erstelle einen Test-Chat
        const testChat = {
          id: uuidv4(),
          user_id: userId,
          title: "Test Chat " + new Date().toLocaleTimeString(),
          messages: [
            { content: "Test message", isUser: true },
            { content: "Test response", isUser: false }
          ],
          context: null,
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString()
        };
        
        this.debugInfo += `\nTest-Chat erstellt mit ID: ${testChat.id}`;
        this.debugInfo += `\nUser ID: ${userId}`;
        
        // Direktes EinfÃ¼gen in die Datenbank - Vereinfachte Version ohne JSON.stringify
        this.debugInfo += "\nFÃ¼ge in Supabase ein...";
        const { data, error } = await supabase
          .from('idea_chats')
          .insert([testChat]);
        
        if (error) {
          this.debugInfo += "\nFehler beim Speichern: " + error.message;
          console.error("Error saving test chat:", error);
          
          // Weitere Fehlerdetails
          if (error.details) {
            this.debugInfo += "\nFehlerdetails: " + error.details;
          }
          
          // ÃœberprÃ¼fe auf hÃ¤ufige Fehler
          if (error.code === "23503") {
            this.debugInfo += "\nReferenzfehler: MÃ¶glicherweise ein Problem mit der user_id oder dem FremdschlÃ¼ssel.";
          } else if (error.code === "42P01") {
            this.debugInfo += "\nTabelle existiert nicht. Bitte erstelle die Tabelle.";
          } else if (error.code === "42703") {
            this.debugInfo += "\nSpalte existiert nicht. Tabellenschema kÃ¶nnte fehlerhaft sein.";
          } else if (error.code === "22P02") {
            this.debugInfo += "\nUngÃ¼ltiger JSON-Typ. ÃœberprÃ¼fe die JSON-Struktur der Nachricht.";
          }
        } else {
          this.debugInfo += "\nTest-Chat erfolgreich gespeichert!";
          
          // Lade die Chats neu
          await this.loadChatHistory();
          this.debugInfo += "\nChat-Historie neu geladen.";
        }
      } catch (error) {
        this.debugInfo += "\nAllgemeiner Fehler: " + error.message;
        console.error("General error in testSaveChat:", error);
      }
    },
    
    async checkChatTable() {
      this.debugInfo = "ÃœberprÃ¼fe idea_chats Tabelle...";
      
      try {
        // PrÃ¼fe, ob der Benutzer eingeloggt ist
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session || !session.user) {
          this.debugInfo += "\nNicht eingeloggt. Bitte einloggen.";
          return;
        }
        
        // Tabellenschema Ã¼berprÃ¼fen
        this.debugInfo += "\nRufe Tabellenschema ab...";
        
        const { data: tableData, error: tableError } = await supabase
          .from('idea_chats')
          .select('*')
          .limit(0);
        
        if (tableError) {
          this.debugInfo += "\nFehler beim PrÃ¼fen der Tabelle: " + tableError.message;
          console.error("Error checking table:", tableError);
          return;
        }
        
        this.debugInfo += "\nTabelle existiert und ist zugÃ¤nglich.";
        
        // Anzahl der EintrÃ¤ge abrufen
        const { count, error: countError } = await supabase
          .from('idea_chats')
          .select('*', { count: 'exact', head: true });
        
        if (countError) {
          this.debugInfo += "\nFehler beim ZÃ¤hlen der EintrÃ¤ge: " + countError.message;
        } else {
          this.debugInfo += `\nAnzahl der EintrÃ¤ge in der Tabelle: ${count || 0}`;
        }
        
        // PrÃ¼fe spezifisch fÃ¼r den aktuellen Benutzer
        const { data: userChats, error: userChatsError } = await supabase
          .from('idea_chats')
          .select('id, title, updated_at')
          .eq('user_id', session.user.id);
        
        if (userChatsError) {
          this.debugInfo += "\nFehler beim Abrufen der Benutzer-Chats: " + userChatsError.message;
        } else if (userChats && userChats.length > 0) {
          this.debugInfo += `\nGefundene Chats fÃ¼r Benutzer: ${userChats.length}`;
          this.debugInfo += "\nChats:";
          userChats.forEach((chat, index) => {
            this.debugInfo += `\n${index + 1}. ${chat.title} (ID: ${chat.id.substring(0, 8)}...) - ${new Date(chat.updated_at).toLocaleString()}`;
          });
        } else {
          this.debugInfo += "\nKeine Chats fÃ¼r den aktuellen Benutzer gefunden.";
        }
        
        // ÃœberprÃ¼fe, ob die RLS-Richtlinien korrekt eingerichtet sind
        this.debugInfo += "\nRLS-Richtlinien kÃ¶nnen nicht direkt Ã¼berprÃ¼ft werden.";
        
      } catch (error) {
        this.debugInfo += "\nAllgemeiner Fehler: " + error.message;
        console.error("General error in checkChatTable:", error);
      }
    },
    // Nach der testSaveChat-Methode
    async dumpSupabaseState() {
      this.debugInfo = "Dumping Supabase state...";
      
      try {
        console.log("=== SUPABASE STATUS DUMP ===");
        
        // 1. PrÃ¼fe Session und Benutzer
        const { data: { session } } = await supabase.auth.getSession();
        console.log("Session:", session);
        
        if (session && session.user) {
          console.log("Logged in as:", session.user.email);
          console.log("User ID:", session.user.id);
          
          // 2. PrÃ¼fe, ob die Tabelle existiert
          const { data: tableInfo, error: tableError } = await supabase
            .from('idea_chats')
            .select('*')
            .limit(1);
          
          console.log("Table exists:", tableError ? "NO" : "YES");
          if (tableError) {
            console.error("Table error:", tableError);
          }
          
          // 3. PrÃ¼fe vorhandene Chats
          const { data: chats, error: chatsError } = await supabase
            .from('idea_chats')
            .select('id, title, updated_at')
            .eq('user_id', session.user.id);
          
          console.log("User chats found:", chats ? chats.length : 0);
          if (chats && chats.length) {
            console.log("Chats:", chats);
          }
          if (chatsError) {
            console.error("Chats error:", chatsError);
          }
          
          // 4. Versuche einen einfachen Test-Insert
          const testId = uuidv4();
          const simpleTestData = {
            id: testId,
            user_id: session.user.id,
            title: "Simple Test " + new Date().toLocaleTimeString(),
            messages: [{ content: "Simple test", isUser: true }],
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
          };
          
          console.log("Attempting simple insert:", simpleTestData);
          
          const { data: insertData, error: insertError } = await supabase
            .from('idea_chats')
            .insert([simpleTestData]);
          
          console.log("Insert result:", insertError ? "FAILED" : "SUCCESS");
          if (insertError) {
            console.error("Insert error:", insertError);
            this.debugInfo += "\nEinfÃ¼gen fehlgeschlagen: " + insertError.message;
            if (insertError.details) {
              this.debugInfo += "\nDetails: " + insertError.details;
            }
          } else {
            this.debugInfo += "\nEinfÃ¼gen erfolgreich! ID: " + testId;
            
            // 5. Versuche den eingefÃ¼gten Datensatz zu lesen
            const { data: readData, error: readError } = await supabase
              .from('idea_chats')
              .select('*')
              .eq('id', testId);
            
            console.log("Read result:", readError ? "FAILED" : "SUCCESS");
            if (readError) {
              console.error("Read error:", readError);
              this.debugInfo += "\nLesen fehlgeschlagen: " + readError.message;
            } else if (readData && readData.length) {
              console.log("Retrieved data:", readData[0]);
              this.debugInfo += "\nLesen erfolgreich! Daten erhalten.";
            } else {
              console.log("No data found for ID:", testId);
              this.debugInfo += "\nKeine Daten fÃ¼r ID " + testId + " gefunden.";
            }
          }
        } else {
          console.log("Not logged in");
          this.debugInfo += "\nNicht eingeloggt. Bitte einloggen.";
        }
        
        console.log("=== END SUPABASE STATUS DUMP ===");
      } catch (error) {
        console.error("Error in dumpSupabaseState:", error);
        this.debugInfo += "\nFehler: " + error.message;
      }
    },
  }
};
</script> 

<style scoped>
.chat-container {
  display: grid;
  grid-template-rows: auto 1fr auto;
  height: calc(100vh - 64px);
  position: relative;
  overflow: hidden;
}

.fixed-header {
  grid-row: 1;
  width: 100%;
  z-index: 20;
  height: 64px; /* Feste HÃ¶he fÃ¼r den Header */
}

.sidebar-container {
  position: fixed;
  top: 136px;
  left: 0;
  bottom: 0;
  z-index: 30;
}

.chat-main {
  grid-row: 2 / 4;
  display: grid;
  grid-template-rows: auto 1fr auto;
  margin-left: 16px;
  overflow: hidden;
}

.chat-header {
  padding: 1rem;
  border-bottom: 1px solid var(--border-color);
}

.chat-messages {
  overflow-y: auto;
  padding: 1rem 1rem 2rem;
  margin-bottom: 80px;
}

.chat-input {
  grid-row: 3;
  padding: 1rem 0;
  width: 100%;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 10;
  background-color: white;
  border-top: 1px solid rgb(229, 231, 235);
}

.dark .chat-input {
  background-color: rgb(31, 41, 55);
  border-color: rgb(55, 65, 81);
}

/* Message container styling */
.message-container {
  width: 100%;
  max-width: 900px;
  margin: 0 auto;
  padding: 1rem;
}

.message-wrapper {
  margin-bottom: 2rem;
}

.user-message {
  display: flex;
  justify-content: flex-end;
}

.assistant-message {
  display: flex;
  justify-content: flex-start;
}

.message-content {
  padding: 1rem;
  border-radius: 0.75rem;
  max-width: 80%;
}

.user-message .message-content {
  margin-left: auto;
}

.assistant-avatar {
  margin-right: 1rem;
}

.avatar-circle {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.message-and-actions {
  display: flex;
  flex-direction: column;
}

.message-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 0.5rem;
}

.action-button {
  padding: 0.25rem;
  border-radius: 9999px;
  transition: all 0.2s;
}

.sidebar-chat-item:hover .trash-icon {
  opacity: 1;
}

.trash-icon {
  opacity: 0;
  transition: opacity 0.2s ease-in-out;
}

.sidebar-chat-item-active:hover .trash-icon {
  opacity: 1;
}

/* Markdown Styling */
.markdown-content h1 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}

.markdown-content h2 {
  font-size: 1.25rem;
  font-weight: bold;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}

.markdown-content h3 {
  font-size: 1.1rem;
  font-weight: bold;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}

.markdown-content p {
  margin-bottom: 0.75rem;
}

.markdown-content ul, .markdown-content ol {
  margin-left: 1.5rem;
  margin-bottom: 1rem;
}

.markdown-content ul {
  list-style-type: disc;
}

.markdown-content ol {
  list-style-type: decimal;
}

.markdown-content li {
  margin-bottom: 0.25rem;
}

.markdown-content code {
  font-family: monospace;
  background-color: rgba(0,0,0,0.05);
  padding: 0.1rem 0.3rem;
  border-radius: 0.25rem;
}

.dark .markdown-content code {
  background-color: rgba(255,255,255,0.1);
}

.markdown-content pre {
  background-color: rgba(0,0,0,0.05);
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin-bottom: 1rem;
}

.dark .markdown-content pre {
  background-color: rgba(0,0,0,0.3);
}

.markdown-content a {
  color: #4f46e5;
  text-decoration: underline;
}

.dark .markdown-content a {
  color: #818cf8;
}

.markdown-content blockquote {
  border-left: 4px solid #e5e7eb;
  padding-left: 1rem;
  margin-left: 0;
  margin-right: 0;
  font-style: italic;
}

.dark .markdown-content blockquote {
  border-left-color: #4b5563;
}

.markdown-content table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}

.markdown-content table th,
.markdown-content table td {
  border: 1px solid #e5e7eb;
  padding: 0.5rem;
  text-align: left;
}

.dark .markdown-content table th,
.dark .markdown-content table td {
  border-color: #4b5563;
}

.markdown-content table th {
  background-color: rgba(0,0,0,0.05);
}

.dark .markdown-content table th {
  background-color: rgba(255,255,255,0.05);
}

/* Typing indicator animation */
.typing-indicator {
  display: flex;
  align-items: center;
}

.typing-indicator span {
  height: 8px;
  width: 8px;
  margin: 0 2px;
  background-color: #a0aec0;
  border-radius: 50%;
  display: inline-block;
  animation: typing 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
  animation-delay: 0s;
}

.typing-indicator span:nth-child(2) {
  animation-delay: 0.3s;
}

.typing-indicator span:nth-child(3) {
  animation-delay: 0.6s;
}

@keyframes typing {
  0%, 80%, 100% { transform: scale(0.6); opacity: 0.6; }
  40% { transform: scale(1); opacity: 1; }
}

/* Responsive styles */
@media (min-width: 640px) {
  .chat-messages {
    background: white;
  }
  
  .message-content {
    border-color: #f3f4f6;
  }
}

@media (max-width: 768px) {
  .chat-container {
    height: 100vh;
  }
  
  .sidebar-container {
    top: 0;
    z-index: 100;
  }
  
  .message-content {
    max-width: 90%;
  }
  
  .chat-main {
    margin-left: 0;
    padding-left: 0;
    padding-top: 70px; /* Abstand fÃ¼r den festen Header */
  }
  
  .fixed-header {
    z-index: 90;
  }
}

/* Supabase Status Debug ausblenden */
.supabase-status-debug {
  display: none !important;
}
</style> 
</template>
