<template>
  <div 
    class="chat-container bg-white" 
    :class="{ 'mobile-view': isMobile }"
  >
    <!-- Fester Haupttitel - wird entfernt -->
    
    <!-- Hauptcontainer für Sidebar und Chat -->
    <div class="main-content-wrapper">
    <!-- Mobile Menu Overlay -->
    <div 
      v-if="isMobile && mobileMenuOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"
      @click="closeMobileMenu"
    ></div>

    <!-- Mobile Menu Toggle Button (ersetzt den entfernten Header-Button) -->
    <button 
      v-if="isMobile" 
      @click="toggleMobileMenu" 
      class="fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md text-gray-600 focus:outline-none mobile-toggle-button"
    >
      <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Fester Chat-Titel oben -->
    <div class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 shadow-sm z-30 py-3 px-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center">
          <!-- Platzhalter für mobile toggle -->
          <div class="w-10 h-10 mr-2" v-if="isMobile"></div>
          
          <h1 class="text-lg font-medium text-gray-900">{{ chatTitle || 'Neuer Chat' }}</h1>
          <button @click="editChatTitle" class="text-gray-400 hover:text-gray-600 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
          </button>
        </div>
      </div>
    </div>

      <!-- Linke Sidebar -->
    <div 
      :class="{
        'w-80': (!isMobile || mobileMenuOpen) && !sidebarCollapsed, 
        'w-0': isMobile && !mobileMenuOpen,
        'w-16': !isMobile && sidebarCollapsed,
          'fixed z-40': true,
          'transform translate-x-0': isMobile && mobileMenuOpen,
          'transform -translate-x-full': isMobile && !mobileMenuOpen,
          'left-0 top-16 bottom-0': !isMobile,
          'inset-y-0 left-0 top-0': isMobile,
          'mobile-open': isMobile && mobileMenuOpen
        }" 
        class="bg-white border-r border-gray-100 flex flex-col transition-all duration-300 ease-in-out sidebar-container overflow-visible"
      @mouseenter="!isMobile && (sidebarCollapsed = false)"
      @mouseleave="!isMobile && (sidebarCollapsed = true)"
    >
        <!-- Toggle Button für die Sidebar -->
        <button 
          v-if="!isMobile"
          @click="sidebarCollapsed = !sidebarCollapsed" 
          class="toggle-button absolute -right-5 top-24 bg-white rounded-full p-2 shadow-md border border-gray-200 z-50 flex items-center justify-center w-8 h-8"
        >
          <svg v-if="sidebarCollapsed" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <svg v-else class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

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
                'w-10 h-10 mx-auto': sidebarCollapsed && !mobileMenuOpen && !isMobile
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
      
        <!-- Rest der Sidebar-Inhalte (Chat-Verlauf usw.) -->
      <div 
        class="flex-1 overflow-auto px-3 py-2"
          :class="{'hidden': sidebarCollapsed && !isMobile && !isHoveringCollapsedSidebar, 'block': !sidebarCollapsed || isMobile || isHoveringCollapsedSidebar}"
        >
          <!-- Heutige Chats - Collapsible -->
          <div class="mb-4">
            <div @click="toggleTodayChats" class="chat-category-header flex items-center justify-between cursor-pointer px-2 py-1 rounded">
              <div class="flex items-center">
                <span class="chat-icon">📅</span>
                <h3 class="text-xs font-medium text-gray-500">HEUTE</h3>
              </div>
              <svg :class="{'transform rotate-180': !showTodayChats}" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            
            <div :class="{'expanded': showTodayChats}" class="chat-category-content">
              <div class="space-y-1 pl-1">
            <div 
              v-for="chat in todayChats" 
              :key="chat.id"
                  class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer w-full sidebar-chat-item"
                  :class="{'sidebar-chat-item-active': isChatActive(chat)}"
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
                <div v-if="todayChats.length === 0" class="text-xs text-gray-400 italic px-3 py-1">
                  Keine Chats heute
              </div>
            </div>
          </div>
        </div>
        
          <!-- Chats der letzten Woche - Collapsible -->
          <div class="mb-4">
            <div @click="toggleLastWeekChats" class="chat-category-header flex items-center justify-between cursor-pointer px-2 py-1 rounded">
              <div class="flex items-center">
                <span class="chat-icon">🗓️</span>
                <h3 class="text-xs font-medium text-gray-500">LETZTE WOCHE</h3>
              </div>
              <svg :class="{'transform rotate-180': !showLastWeekChats}" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            
            <div :class="{'expanded': showLastWeekChats}" class="chat-category-content">
              <div class="space-y-1 pl-1">
            <div 
              v-for="chat in lastWeekChats" 
              :key="chat.id"
                  class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer w-full sidebar-chat-item"
                  :class="{'sidebar-chat-item-active': isChatActive(chat)}"
            >
              <p 
                @click="loadChat(chat)" 
                    class="text-sm text-gray-800 truncate max-w-[calc(100%-30px)] overflow-hidden text-ellipsis whitespace-nowrap"
              >
                {{ chat.title || 'Untitled Chat' }}
              </p>
              <div @click.stop="confirmDeleteChat(chat)" class="trash-icon ml-1 w-5 h-5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                </svg>
                  </div>
                </div>
                <div v-if="lastWeekChats.length === 0" class="text-xs text-gray-400 italic px-3 py-1">
                  Keine Chats in der letzten Woche
              </div>
            </div>
          </div>
        </div>
        
          <!-- Ältere Chats - Collapsible -->
          <div class="mb-4">
            <div @click="toggleOlderChats" class="chat-category-header flex items-center justify-between cursor-pointer px-2 py-1 rounded">
              <div class="flex items-center">
                <span class="chat-icon">📚</span>
                <h3 class="text-xs font-medium text-gray-500">ÄLTERE CHATS</h3>
              </div>
              <svg :class="{'transform rotate-180': !showOlderChats}" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            
            <div :class="{'expanded': showOlderChats}" class="chat-category-content">
              <div class="space-y-1 pl-1">
            <div 
              v-for="chat in olderChats" 
              :key="chat.id"
                  class="flex items-center justify-between px-3 py-2 rounded-md cursor-pointer w-full sidebar-chat-item"
                  :class="{'sidebar-chat-item-active': isChatActive(chat)}"
            >
              <p 
                @click="loadChat(chat)" 
                    class="text-sm text-gray-800 truncate max-w-[calc(100%-30px)] overflow-hidden text-ellipsis whitespace-nowrap"
              >
                {{ chat.title || 'Untitled Chat' }}
              </p>
              <div @click.stop="confirmDeleteChat(chat)" class="trash-icon ml-1 w-5 h-5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                </svg>
                  </div>
                </div>
                <div v-if="olderChats.length === 0" class="text-xs text-gray-400 italic px-3 py-1">
                  Keine älteren Chats
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
      <div 
        class="chat-main"
        :class="{'expanded': !isMobile && sidebarCollapsed, 'full-width': !isMobile && !sidebarCollapsed}"
      >
        <!-- Hinzufügen des Kontext-Stacks über dem Chat -->
        <div v-if="contextStack.length > 0" class="context-stack bg-gray-50 p-3 rounded-md mb-4 flex flex-wrap items-center gap-2">
          <div class="text-sm text-gray-500 mr-1">Aktiver Kontext:</div>
          <div 
            v-for="(item, index) in contextStack" 
            :key="index"
            class="context-item flex items-center px-2 py-1 bg-white border border-gray-200 rounded-md text-sm"
          >
            <span v-if="item.type === 'channel'" class="context-icon mr-1">📺</span>
            <span v-else-if="item.type === 'script'" class="context-icon mr-1">📝</span>
            <span v-else-if="item.type === 'audience'" class="context-icon mr-1">👥</span>
            <span v-else-if="item.type === 'format'" class="context-icon mr-1">🎬</span>
            <span class="truncate max-w-xs">{{ item.name }}</span>
            <button @click="removeFromContextStack(index)" class="ml-1 text-gray-400 hover:text-gray-600">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
          </div>
          <button 
            v-if="contextStack.length > 0" 
            @click="clearContextStack" 
            class="text-xs text-pink-600 hover:text-pink-800 ml-auto"
          >
            Alle entfernen
                </button>
      </div>
        
      <!-- Chat Header mit Titel und Aktionen - NUR anzeigen, wenn eine Nachricht existiert -->
      <!-- Der Chat Header wird entfernt, da wir bereits einen festen Titel oben haben -->
      
      <!-- Chat Messages Area - ONLY THIS SHOULD SCROLL -->
      <div class="chat-messages bg-gradient-to-b from-gray-50 to-white sm:bg-white pt-16 w-full max-w-none">
        <!-- Willkommensbildschirm, wenn keine Nachrichten vorhanden -->
        <div v-if="messages.length === 0" class="flex flex-col items-center justify-start h-full pt-4">
          <h2 class="text-2xl font-bold mb-3 text-gray-900">Willkommen beim Ideen Chat</h2>
          <p class="text-gray-600 mb-6 max-w-md text-center">Dein Partner für kreative YouTube-Video-Ideen</p>
          
          <div class="text-left w-full max-w-2xl mb-6">
            <h3 class="font-semibold mb-3 text-gray-800">Beispiele zum Ausprobieren</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Analysiere die Content-Strategie von @MrBeast')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">🔍</span>
                  <div>
                    <p class="font-medium text-gray-900">MrBeast Strategie analysieren</p>
                    <p class="text-sm text-gray-600">Erfolgreiche Content-Strategien verstehen</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Generiere ansprechende Video-Titel')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">✨</span>
                  <div>
                    <p class="font-medium text-gray-900">Video-Titel erstellen</p>
                    <p class="text-sm text-gray-600">Ansprechende Titel generieren</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Entwickle eine Marketing-Funnel-Strategie für lokale Kunden')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">📊</span>
                  <div>
                    <p class="font-medium text-gray-900">Marketing-Funnel planen</p>
                    <p class="text-sm text-gray-600">Strategie für lokale Kunden entwickeln</p>
                  </div>
                </div>
              </div>
              <div class="bg-white sm:bg-gray-50 p-4 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:border-pink-200 transition-colors" @click="useExample('Entwirf kreative Werbeanzeigen für YouTube')">
                <div class="flex items-center">
                  <span class="text-pink-500 mr-3 text-xl">🎯</span>
                  <div>
                    <p class="font-medium text-gray-900">Werbeanzeigen erstellen</p>
                    <p class="text-sm text-gray-600">Kreative Ansätze für Anzeigen</p>
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
                  <div class="message-content bg-white sm:bg-gray-50 border border-gray-100 text-gray-900 shadow-sm rounded-lg p-4">
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
                    <div v-else class="prose prose-sm max-w-none text-gray-800 text-base markdown-content" v-html="message.formattedContent || formatMessageContent(message.content)"></div>
                  </div>
                  
                  <!-- Message Actions -->
                  <div class="message-actions mt-1 flex justify-end">
                    <button class="action-button text-gray-400 hover:text-pink-600 p-1" @click="copyToClipboard(message.content)" title="Kopieren">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                      </svg>
                    </button>
                    <button 
                      class="action-button text-gray-400 hover:text-pink-600 p-1 ml-2" 
                      @click="regenerateResponse()" 
                      title="Neu generieren"
                      v-if="index === messages.length - 1 && !message.isUser"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
              <span v-if="activeContext.type === 'channel'" class="mr-2">📺</span>
              <span v-else-if="activeContext.type === 'script'" class="mr-2">📝</span>
              <span>{{ activeContext.name }}</span>
            </div>
            <button @click="clearContext" class="text-pink-500 hover:text-pink-700">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
            
            <!-- Schnellzugriffs-Buttons für Analysen -->
            <div class="mb-2 px-4 flex gap-2">
              <button @click="showChannelAnalysisModal = true" class="text-sm flex items-center gap-1 px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                <span class="text-pink-500">📺</span> Channel analysieren
              </button>
              <button @click="showVideoAnalysisModal = true" class="text-sm flex items-center gap-1 px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                <span class="text-pink-500">📊</span> Video analysieren
              </button>
              <button @click="showPromptLibrary = true" class="text-sm flex items-center gap-1 px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                <span class="text-pink-500">📚</span> Prompt-Bibliothek
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
                <span>Shift + Enter für neue Zeile</span>
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
          <div class="flex justify-between items-center p-5 border-b border-gray-200">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
              </svg>
              <h2 class="text-xl font-bold text-gray-900">Ideation Prompt Bibliothek</h2>
            </div>
            <button @click="showPromptLibrary = false" class="text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="flex flex-1 overflow-hidden">
          <!-- Categories Sidebar -->
            <div class="w-64 bg-gray-50 overflow-y-auto p-3 border-r border-gray-200">
              <div class="mb-4">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <input 
                    type="text" 
                    v-model="promptSearch" 
                    placeholder="Suche Prompts..." 
                    class="pl-10 pr-4 py-2 w-full bg-white border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
                  />
                </div>
              </div>
              
              <div 
                v-for="category in promptLibraryCategories" 
                :key="category.id"
              @click="selectPromptCategory(category)"
                :class="{'bg-pink-50 text-pink-700': selectedCategory === category}"
                class="p-3 hover:bg-gray-100 cursor-pointer rounded-lg mb-2 flex items-center transition-colors"
              >
                <span class="w-8 h-8 flex items-center justify-center bg-white rounded-md shadow-sm mr-3">
                  <component :is="category.icon" class="w-5 h-5 text-pink-600" />
              </span>
              <span class="font-medium">{{ category.name }}</span>
            </div>
          </div>
          
          <!-- Prompts -->
            <div class="flex-1 overflow-y-auto bg-white flex flex-col">
              <div class="p-5">
            <div v-if="selectedCategory">
                  <h3 class="text-xl font-semibold mb-1 text-gray-900">{{ selectedCategory.name }}</h3>
                  <p class="text-sm text-gray-600 mb-4">{{ selectedCategory.description }}</p>
                  
                  <div class="grid grid-cols-1 gap-5">
                    <div 
                      v-for="prompt in filteredPrompts" 
                      :key="prompt.id"
                      class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                    >
                      <div class="flex items-center p-4 border-b border-gray-200 bg-gray-50">
                        <div class="w-8 h-8 bg-pink-100 rounded-md flex items-center justify-center text-pink-600 mr-3">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                          </svg>
                </div>
                        <span class="font-medium text-gray-900 text-lg">{{ prompt.title }}</span>
                </div>
                      <div class="p-4 bg-white">
                        <p class="text-gray-700 whitespace-pre-wrap font-mono text-sm bg-gray-50 p-3 rounded-md border border-gray-100 mb-3">{{ prompt.content }}</p>
                        <div class="text-xs text-gray-500 mb-4">
                          <span v-if="prompt.placeholders && prompt.placeholders.length > 0">Platzhalter: 
                            <span v-for="placeholder in prompt.placeholders" :key="placeholder" class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1 text-gray-700">{{ placeholder }}</span>
                          </span>
                        </div>
                      </div>
                      <div class="border-t border-gray-200 p-3 bg-gray-50 text-right">
                  <button 
                    @click="usePrompt(prompt)" 
                          class="bg-pink-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-pink-700 shadow-sm hover:shadow-md transition-all"
                  >
                          Prompt verwenden
                  </button>
                      </div>
                </div>
              </div>
            </div>
            
                <div v-else class="flex flex-col items-center justify-center h-full py-16">
              <div class="text-center p-8">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                      <svg class="w-8 h-8 text-pink-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                      </svg>
              </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">Prompt-Bibliothek</h3>
                    <p class="text-gray-600 max-w-md mx-auto">Wähle eine Prompt-Kategorie aus der Seitenleiste, um effektive Vorlagen für deine YouTube-Content-Entwicklung zu sehen.</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-auto p-4 border-t border-gray-200 bg-gray-50">
                <div class="text-xs text-gray-600">
                  <p class="mb-1">Diese Prompts sind optimiert für die Erstellung von YouTube-Content. Du kannst sie anpassen, bevor du sie absendest.</p>
                  <p>Prompts mit [variables] können durch deine eigenen Angaben ersetzt werden.</p>
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
          <button @click="showChatHistory = false" class="text-gray-500 hover:text-gray-700">×</button>
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
                <span>📺</span>
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
                <span>📝</span>
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
    
    <!-- Bestätigungs-Modal für Chat-Löschung -->
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
      
      <!-- Channel Analysis Modal -->
      <div v-if="showChannelAnalysisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-2xl">
          <div class="p-5 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Channel analysieren</h2>
            <button @click="showChannelAnalysisModal = false" class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="p-5">
            <div class="mb-4">
              <label for="channel-name" class="block text-sm font-medium text-gray-700 mb-1">YouTube Channel eintragen</label>
              <div class="flex">
                <input 
                  type="text" 
                  id="channel-name" 
                  v-model="channelToAnalyze" 
                  placeholder="@MrBeast oder Channel-Name"
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                  @keyup.enter="analyzeChannel"
                />
                <button 
                  @click="analyzeChannel" 
                  class="px-4 py-2 bg-pink-600 text-white rounded-r-md hover:bg-pink-700 transition-colors"
                  :disabled="isAnalyzing"
                >
                  <span v-if="!isAnalyzing">Analysieren</span>
                  <span v-else class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Analysiere...
                  </span>
                </button>
              </div>
              <p class="text-xs text-gray-500 mt-1">Gib den Channelnamen ein (mit oder ohne @). Beispiel: @MrBeast</p>
            </div>
            
            <div v-if="channelAnalysisError" class="bg-red-50 text-red-700 p-3 rounded-md mb-4">
              {{ channelAnalysisError }}
            </div>
            
            <div v-if="channelAnalysisResult" class="bg-gray-50 p-4 rounded-md overflow-y-auto max-h-96">
              <div class="flex items-center mb-4">
                <img v-if="channelAnalysisResult.profilePic" :src="channelAnalysisResult.profilePic" class="w-12 h-12 rounded-full mr-3" alt="Channel Profile" />
                <div>
                  <h3 class="font-bold text-lg">{{ channelAnalysisResult.name }}</h3>
                  <p class="text-sm text-gray-600">{{ channelAnalysisResult.subscriberCount }} Abonnenten</p>
                </div>
                <button 
                  @click="addChannelToContextStack(channelAnalysisResult)" 
                  class="ml-auto bg-pink-600 text-white px-3 py-1 rounded-md text-sm hover:bg-pink-700 transition-colors"
                >
                  Zum Kontext hinzufügen
                </button>
              </div>
              
              <div class="border-t border-gray-200 pt-4 mt-4">
                <h4 class="font-semibold mb-2">Top Video-Formate:</h4>
                <div class="flex flex-wrap gap-2 mb-4">
                  <span v-for="(format, index) in channelAnalysisResult.videoFormats" :key="index" class="bg-white px-2 py-1 rounded-md text-sm border border-gray-200">
                    {{ format }}
                  </span>
                </div>
                
                <h4 class="font-semibold mb-2">Erfolgreiche Themen:</h4>
                <div class="flex flex-wrap gap-2 mb-4">
                  <span v-for="(topic, index) in channelAnalysisResult.topics" :key="index" class="bg-white px-2 py-1 rounded-md text-sm border border-gray-200">
                    {{ topic }}
                  </span>
                </div>
                
                <h4 class="font-semibold mb-2">Top 3 Videos:</h4>
                <div class="space-y-3">
                  <div v-for="(video, index) in channelAnalysisResult.topVideos" :key="index" class="bg-white p-3 rounded-md shadow-sm">
                    <div class="flex">
                      <div class="w-24 h-16 bg-gray-200 rounded-md overflow-hidden mr-3 flex-shrink-0">
                        <img v-if="video.thumbnail" :src="video.thumbnail" class="w-full h-full object-cover" alt="Video thumbnail" />
                      </div>
                      <div>
                        <h5 class="font-medium text-sm">{{ video.title }}</h5>
                        <p class="text-xs text-gray-600">{{ video.views }} Aufrufe</p>
                        <div class="flex gap-2 mt-1">
                          <span class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ video.format }}</span>
                          <span class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ video.duration }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="!channelAnalysisResult && !isAnalyzing && !channelAnalysisError" class="bg-gray-50 p-4 rounded-md text-center py-8">
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-600">Gib einen YouTube-Channelnamen ein, um wichtige Insights zu erhalten</p>
            </div>
          </div>
          
          <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button @click="showChannelAnalysisModal = false" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors mr-2">
              Schließen
            </button>
            <button 
              @click="generateChannelContentIdeas" 
              class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors"
              :disabled="!channelAnalysisResult || isGeneratingIdeas"
            >
              <span v-if="!isGeneratingIdeas">Ideen generieren</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generiere...
              </span>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Video Analysis Modal -->
      <div v-if="showVideoAnalysisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-2xl overflow-hidden shadow-2xl">
          <div class="p-5 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Video analysieren</h2>
            <button @click="showVideoAnalysisModal = false" class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="p-5">
            <div class="mb-4">
              <label for="video-url" class="block text-sm font-medium text-gray-700 mb-1">YouTube Video URL einfügen</label>
              <div class="flex">
                <input 
                  type="text" 
                  id="video-url" 
                  v-model="videoToAnalyze" 
                  placeholder="https://www.youtube.com/watch?v=..."
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                  @keyup.enter="analyzeVideo"
                />
                <button 
                  @click="analyzeVideo" 
                  class="px-4 py-2 bg-pink-600 text-white rounded-r-md hover:bg-pink-700 transition-colors"
                  :disabled="isVideoAnalyzing"
                >
                  <span v-if="!isVideoAnalyzing">Analysieren</span>
                  <span v-else class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Analysiere...
                  </span>
                </button>
              </div>
              <p class="text-xs text-gray-500 mt-1">Füge die komplette URL eines YouTube-Videos ein.</p>
            </div>
            
            <!-- Rest der Video-Analyse-UI hier -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { createClient } from '@supabase/supabase-js';
import { v4 as uuidv4 } from 'uuid';
import { marked } from 'marked';
import DOMPurify from 'dompurify';
import highlightjs from 'highlight.js';
import axios from 'axios';
import { supabase } from '../supabase';
import emojione from 'emojione';

// Icon-Komponenten
const RocketIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
  </svg>`
};

const ChartBarIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
  </svg>`
};

const VideoCameraIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
  </svg>`
};

const SearchIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
  </svg>`
};

const AdjustmentsIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
  </svg>`
};

const PhotographIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
  </svg>`
};

const LightningBoltIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
  </svg>`
};

export default {
  name: 'IdeaChat',
  components: {
    RocketIcon,
    ChartBarIcon,
    VideoCameraIcon,
    SearchIcon,
    AdjustmentsIcon,
    PhotographIcon,
    LightningBoltIcon
  },
  mounted() {
    // Initialisiere die Mobile-Erkennung beim Laden
    this.checkMobile();
    
    // Event-Listener für Resize-Events
    window.addEventListener('resize', this.checkMobile);
    
    // Event-Listener für Klick außerhalb der Sidebar
    document.addEventListener('click', this.handleClickOutside);
    
    // Prüfe den Auth-Status von Supabase
    this.checkAuthStatus();
    
    // Add click event listener for outside clicks
    document.addEventListener('click', this.handleOutsideClick);
    
    // Check if mobile on mount
    this.checkIfMobile();
    
    // Add resize listener to handle responsive behavior
    window.addEventListener('resize', this.checkIfMobile);
    
    // Close mobile menu when escape key is pressed
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        this.closeMobileMenu();
      }
    });
    
    // Sidebar-Status aus localStorage lesen (nur für Desktop)
    if (!this.isMobile) {
      const savedCollapsed = localStorage.getItem('sidebarCollapsed');
      if (savedCollapsed !== null) {
        this.sidebarCollapsed = savedCollapsed === 'true';
      }
    }
    
    // Event-Listener für Klicks außerhalb der Sidebar hinzufügen (für automatisches Schließen)
    document.addEventListener('click', this.handleOutsideClick);
    
    // Resize-Event-Listener hinzufügen
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    // Event-Listener entfernen
    window.removeEventListener('resize', this.checkMobile);
    document.removeEventListener('click', this.handleClickOutside);
  },
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
      sidebarCollapsed: window.innerWidth < 1280, // Standardmäßig bei kleineren Bildschirmen reduziert
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
      showChatHistory: false,
      showTodayChats: true,
      showLastWeekChats: true,
      showOlderChats: true,
      contextStack: [],
      channelToAnalyze: '',
      isAnalyzing: false,
      channelAnalysisError: '',
      channelAnalysisResult: null,
      videoToAnalyze: '',
      isVideoAnalyzing: false,
      promptSearch: '',
      showChannelAnalysisModal: false,
      showVideoAnalysisModal: false,
      isGeneratingIdeas: false,
      promptLibraryCategories: [
        {
          id: 'new-channel',
          name: 'Neue Channels',
          description: 'Erstelle und plane neue YouTube Channels basierend auf bestehenden erfolgreichen Formaten.',
          icon: 'RocketIcon',
          prompts: [
            {
              id: 'channel-template-1',
              title: 'Channel basierend auf Vorbild erstellen',
              content: 'Ich möchte einen Channel ähnlich zu @[ChannelName] erstellen, aber für [Thema]. Analysiere deren erfolgreichste Videos und schlage mir meine ersten 3 Videoideen mit ähnlichem Format vor. Meine Idee und Ziel für den Channel ist [Ziele]. Gib mir einen vollständigen Channel-Blueprint.',
              placeholders: ['@ChannelName', 'Thema', 'Ziele']
            },
            {
              id: 'channel-template-2',
              title: 'Channel basierend auf TV-Show',
              content: 'Ich möchte einen neuen Channel ähnlich zur TV-Show [Name] erstellen. Mein Fokus wird auf [Fokus] liegen. Gib mir eine Liste von 20 Themenideen und einen Blueprint für den Channel.',
              placeholders: ['Name', 'Fokus']
            },
            {
              id: 'audience-analysis',
              title: 'Zielgruppenanalyse',
              content: 'Hilf mir, die Zielgruppe für einen Channel ähnlich zu @[ChannelName] aber im [Nische] Bereich zu definieren. Inkludiere Demographie, Interessen, Schmerzpunkte und Sehgewohnheiten.',
              placeholders: ['@ChannelName', 'Nische']
            },
            {
              id: 'topic-brainstorm',
              title: '20 Videothemen generieren',
              content: 'Basierend auf @[ChannelName]\'s Erfolg, generiere 20 Video-Themen für einen neuen Channel im [Nische] Bereich. Inkludiere potenzielle Titelformate und kurze Beschreibungen für jedes Thema. Verwende ein Tabellenformat.',
              placeholders: ['@ChannelName', 'Nische']
            }
          ]
        },
        {
          id: 'content-strategy',
          name: 'Content Strategie',
          description: 'Entwickle umfassende Strategien für Wachstum, Monetarisierung und langfristigen Erfolg deines Channels.',
          icon: 'ChartBarIcon',
          prompts: [
            {
              id: 'growth-strategy',
              title: 'Wachstumsstrategie',
              content: 'Analysiere meinen YouTube Channel über [Thema] und schlage eine umfassende Strategie vor, um meine Zuschauerzahl von [aktuelle Abos] auf [Ziel Abos] zu steigern. Inkludiere Content-Typen, Upload-Frequenz und Community-Engagement-Taktiken.',
              placeholders: ['Thema', 'aktuelle Abos', 'Ziel Abos']
            },
            {
              id: 'monetization-strategy',
              title: 'Monetarisierungsplan',
              content: 'Erstelle einen 6-Monats-Monetarisierungsplan für meinen YouTube Channel über [Thema]. Inkludiere verschiedene Einnahmequellen, wann diese implementiert werden sollten und geschätztes Einnahmenpotenzial.',
              placeholders: ['Thema']
            }
          ]
        },
        {
          id: 'video-formats',
          name: 'Video Formate',
          description: 'Entwickle einzigartige und ansprechende Videoformate, die deine Zuschauer begeistern werden.',
          icon: 'VideoCameraIcon',
          prompts: [
            {
              id: 'format-development',
              title: 'Formatentwicklung',
              content: 'Entwickle ein einzigartiges Videoformat für meinen [Nische] Channel, das sich von bestehenden Formaten abhebt. Berücksichtige [spezifische Anforderungen] und stelle sicher, dass es für [Zielgruppe] ansprechend ist.',
              placeholders: ['Nische', 'spezifische Anforderungen', 'Zielgruppe']
            },
            {
              id: 'series-planning',
              title: 'Serienplanung',
              content: 'Plane eine 10-teilige Videoserie für meinen Channel über [Thema]. Jedes Video sollte aufeinander aufbauen, aber auch eigenständig funktionieren. Inkludiere Titel, Hauptpunkte und Hooks für jedes Video.',
              placeholders: ['Thema']
            }
          ]
        },
        {
          id: 'script-research',
          name: 'Script Research',
          description: 'Verbessere deine Skripte durch fundierte Recherche und strukturierten Aufbau.',
          icon: 'SearchIcon',
          prompts: [
            {
              id: 'detailed-research',
              title: 'Detaillierte Recherche',
              content: 'Führe eine umfassende Recherche zum Thema [Thema] für mein nächstes YouTube-Video durch. Fokussiere auf [Aspekte] und stelle sicher, dass du die neuesten Entwicklungen/Studien/Trends berücksichtigst. Inkludiere interessante Fakten und Statistiken, die ich in meinem Skript verwenden kann.',
              placeholders: ['Thema', 'Aspekte']
            },
            {
              id: 'script-outline',
              title: 'Skript-Gliederung',
              content: 'Erstelle eine detaillierte Gliederung für ein [Länge]-minütiges YouTube-Skript über [Thema]. Inkludiere Intro-Hook, Segment-Struktur, B-Roll-Ideen, Call-to-Actions und Outro. Der Stil sollte [Stil] sein und auf [Zielgruppe] abzielen.',
              placeholders: ['Länge', 'Thema', 'Stil', 'Zielgruppe']
            }
          ]
        },
        {
          id: 'channel-management',
          name: 'Channel Management',
          description: 'Optimiere deine Arbeitsabläufe, definiere Rollen und verbessere die Effizienz deines Teams.',
          icon: 'AdjustmentsIcon',
          prompts: [
            {
              id: 'job-descriptions',
              title: 'Stellenbeschreibungen',
              content: 'Erstelle detaillierte Stellenbeschreibungen für einen Thumbnail-Designer und Video-Editor für einen Channel im [Nische] Bereich mit folgenden Spezifikationen: Thumbnail-Stil: [z.B. minimalistisch, fett, reaktionsfreudig] Video-Stil: [z.B. schnell geschnitten, lehrreich, erzählend] Lieferzeitplan: [z.B. 24h Umschlag, 3 Tage] Überarbeitungsrunden: [Anzahl] Kommunikationstool: [z.B. Slack, Discord, E-Mail] Dateilieferungsmethode: [z.B. Google Drive, Dropbox] Für jede Rolle inkludiere: 1) Erforderliche technische Fähigkeiten 2) Erforderliche Softwarekenntnisse 3) Erfahrungsniveau 4) Kommunikationserwartungen 5) Workflow- und Prozessdetails 6) Stilrichtlinien 7) Lieferbare Spezifikationen 8) Qualitätsstandards. Fasse jede Stellenbeschreibung in einem Zitat zusammen.',
              placeholders: ['Nische', 'Thumbnail-Stil', 'Video-Stil', 'Lieferzeitplan', 'Anzahl', 'Kommunikationstool', 'Dateilieferungsmethode']
            },
            {
              id: 'sop-creation',
              title: 'SOP erstellen',
              content: 'Erstelle ein detailliertes Standard Operating Procedure (SOP) für das Betreiben eines YouTube-Channels im [Nische] Bereich mit folgenden Spezifikationen: Wöchentliches Videoziel: [Anzahl Videos] Bevorzugte Kommunikationstools: [z.B. Slack, Discord, etc.] Projektmanagement-Tool: [z.B. ClickUp, Asana, etc.] Team-Meeting-Frequenz: [z.B. täglich, wöchentlich] Review-Prozess-Präferenz: [z.B. sequentiell, parallel] Inkludiere im SOP: 1) Content-Ideation-Prozess unter Verwendung von Subscribr 2) Skript-Entwicklungsworkflow mit Subscribr 3) Produktions-Timeline basierend auf der angegebenen Videofrequenz 4) Team-Kommunikationsrichtlinien unter Verwendung der angegebenen Tools 5) Qualitätskontroll-Checkpoints 6) Veröffentlichungsplan 7) Analytics-Review-Prozess und -Frequenz 8) Asset-Management-Workflow',
              placeholders: ['Nische', 'Anzahl Videos', 'Kommunikationstools', 'Projektmanagement-Tool', 'Frequenz', 'Präferenz']
            }
          ]
        },
        {
          id: 'thumbnails-titles',
          name: 'Thumbnails & Titel',
          description: 'Erstelle ansprechende Thumbnails und Titel, die mehr Klicks und Engagement generieren.',
          icon: 'PhotographIcon',
          prompts: [
            {
              id: 'thumbnail-concepts',
              title: 'Thumbnail-Konzepte',
              content: 'Entwerfe 5 Thumbnail-Konzepte für mein YouTube-Video mit dem Titel "[Videotitel]". Mein Channel ist im [Nische] Bereich und mein Thumbnail-Stil ist [Stil]. Für jedes Konzept beschreibe:  1) Hauptbild/Szene 2) Textoverlay (kurz und knackig) 3) Farbschema 4) Ausdrücke/Emotionen (falls Gesichter verwendet werden) 5) Komposition/Layout Berücksichtige, dass das Thumbnail auf mobilen Geräten und Desktop gut aussehen muss.',
              placeholders: ['Videotitel', 'Nische', 'Stil']
            },
            {
              id: 'title-variations',
              title: 'Titelvariationen',
              content: 'Generiere 10 ansprechende Titelvariationen für mein YouTube-Video über [Thema]. Mein Channel ist im [Nische] Bereich und meine Zielgruppe ist [Zielgruppe]. Inkludiere eine Mischung aus Fragen, Statements und "Wie man"-Formaten. Für jeden Titel erkläre, warum er in der YouTube-Suche und auf der Homepage gut performen würde.',
              placeholders: ['Thema', 'Nische', 'Zielgruppe']
            }
          ]
        },
        {
          id: 'ai-prompting',
          name: 'AI Prompting',
          description: 'Maximiere deine Ergebnisse mit AI-Tools durch optimierte Prompts für verschiedene Aufgaben.',
          icon: 'LightningBoltIcon',
          prompts: [
            {
              id: 'script-prompt',
              title: 'Skript-Prompt',
              content: 'Ich möchte ein [Länge]-minütiges Skript für ein YouTube-Video über [Thema] erstellen. Der Stil sollte [Stil] sein und für [Zielgruppe] geeignet sein. Bitte schreibe ein vollständiges Skript mit einem starken Hook in den ersten 30 Sekunden, deutlichen Segmentübergängen, B-Roll-Anweisungen in [Klammern] und Call-to-Actions an den richtigen Stellen. Verwende einen konversationellen Ton und inkludiere [Schlüsselaspekte], die unbedingt behandelt werden sollten.',
              placeholders: ['Länge', 'Thema', 'Stil', 'Zielgruppe', 'Schlüsselaspekte']
            },
            {
              id: 'hook-prompt',
              title: 'Hook-Prompt',
              content: 'Schreibe 5 verschiedene starke Hook-Intros (jeweils 30 Sekunden) für ein YouTube-Video über [Thema]. Die Hooks sollten [Emotion] hervorrufen und die Zuschauer dazu bringen, weiterzuschauen. Meine Zielgruppe ist [Zielgruppe] und der Stil meines Channels ist [Stil]. Baue in jeden Hook ein klares "was du lernen wirst"-Element ein und verwende einen der folgenden Ansätze für jeden: 1) Überraschende Statistik 2) Kontroverse Aussage 3) Relatable Problem 4) Story-basiert 5) Direktes Versprechen',
              placeholders: ['Thema', 'Emotion', 'Zielgruppe', 'Stil']
            }
          ]
        }
      ]
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
          // Markdown und Emojis für AI-Antworten
          return {
            ...message,
            formattedContent: this.formatMessageContent(message.content)
          };
        }
        return message;
      });
    },
    // Gefilterte Prompts für die Prompt-Bibliothek
    filteredPrompts() {
      if (!this.selectedCategory) return [];
      
      // Wenn keine Suche aktiv ist, gib alle Prompts der Kategorie zurück
      if (!this.promptSearch) return this.selectedCategory.prompts;
      
      // Andernfalls filtere nach Suchbegriff
      const search = this.promptSearch.toLowerCase();
      return this.selectedCategory.prompts.filter(prompt => 
        prompt.title.toLowerCase().includes(search) || 
        prompt.content.toLowerCase().includes(search)
      );
    }
  },
  watch: {
    // Auto-Scrollen, wenn neue Nachrichten hinzugefügt werden
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
    
    // Test Supabase connection - jetzt ist die Methode verfügbar
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

  // Einfache Funktion zur Überprüfung der Supabase-Verbindung
  // Diese Methode muss in das methods-Objekt verschoben werden!
  // async checkSupabaseConnection() {
  //   try {
  //     // Einfacher Verbindungstest
  //     await supabase.from('idea_chats').select('count').limit(1);
  //   } catch (error) {
  //     // Verbindungsfehler - Kein Logging nötig
  //   }
  // },
  mounted() {
    // Initial scroll
    this.scrollToBottom();
    
    // Event-Listener für Tastenkombinationen
    document.addEventListener('keydown', this.handleKeyboardShortcuts);
    
    // Outside click handler
    document.addEventListener('click', this.handleOutsideClick);
    
    // Initialize mobile detection
    this.checkMobile();
    
    // Add initial mobile-view class if needed
    if (this.isMobile) {
      const container = document.querySelector('.chat-container');
      if (container) {
        container.classList.add('mobile-view');
      }
    }
    
    window.addEventListener('resize', this.checkMobile);
    
    // Detect navbar height and set CSS variable
    this.setNavbarHeight();
    window.addEventListener('resize', this.setNavbarHeight);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.checkMobile);
    document.removeEventListener('keydown', this.handleKeyboardShortcuts);
    document.removeEventListener('click', this.handleOutsideClick);
    window.removeEventListener('resize', this.setNavbarHeight);
  },
  methods: {
    checkMobile() {
      const wasMobile = this.isMobile;
      this.isMobile = window.innerWidth < 768;
      
      if (!this.isMobile) {
        this.mobileMenuOpen = false;
        
        // Remove mobile-specific classes when switching to desktop
        const sidebar = document.querySelector('.sidebar-container');
        if (sidebar) {
          sidebar.classList.remove('mobile-open');
        }
        document.body.classList.remove('overflow-hidden');
      }
      
      // Add/remove mobile-view class on root element based on current state
      if (wasMobile !== this.isMobile) {
        const container = document.querySelector('.chat-container');
        if (container) {
          if (this.isMobile) {
            container.classList.add('mobile-view');
          } else {
            container.classList.remove('mobile-view');
          }
        }
      }
    },
    async fetchChannelsAndScripts() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        if (!session) return;
        
        // Initialisiere leere Arrays für den Fall, dass die Tabellen nicht existieren
        this.channels = [];
        this.scripts = [];
        
        try {
          // Versuche channels zu laden
          const { data: channels, error: channelsError } = await supabase
            .from('channels')
            .select('id, title')
            .eq('user_id', session.user.id);
            
          if (channelsError) {
            // Prüfe, ob es sich um einen "Tabelle existiert nicht" Fehler handelt
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
            // Prüfe, ob es sich um einen "Tabelle existiert nicht" Fehler handelt
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
      if (!this.newMessage.trim()) return;
      
      // Nachricht des Benutzers hinzufügen
      this.messages.push({
        isUser: true,
        content: this.newMessage
      });
      
      // Leere das Eingabefeld
      const userMessage = this.newMessage;
      this.newMessage = '';
      
      // Scrolle nach unten, um die neue Nachricht zu zeigen
      this.$nextTick(() => {
        this.scrollToBottom();
      });
      
      // Zeige Ladeindikator
      const loadingMessageIndex = this.messages.length;
      this.messages.push({
          isUser: false,
        isLoading: true,
        content: 'Nachricht wird verarbeitet...'
      });
      
      try {
        // Speichere vorherigen Chat-Verlauf
        await this.saveChat(true);
        
        // Bereite den Kontext vor, falls vorhanden
        let contextPrompt = '';
        if (this.contextStack.length > 0) {
          contextPrompt = "Berücksichtige folgenden Kontext:\n";
          this.contextStack.forEach(item => {
            contextPrompt += `${item.type.toUpperCase()}: ${item.name}\n`;
          });
          contextPrompt += "\n";
        }
        
        console.log("Sende API-Anfrage an", "http://localhost:9000/api/chat");
          
        // Zeige Request-Daten im Konsolenlog
        const requestData = {
          message: contextPrompt + userMessage,
          model: this.selectedAI
        };
        console.log("Request-Daten:", requestData);
        
        // Sende Anfrage an ChatGPT API
        const response = await axios.post("http://localhost:9000/api/chat", requestData);
        
        console.log("API-Antwort erhalten:", response.data);
        
        // Entferne Ladeindikator und zeige die echte Antwort
        this.messages.pop();
        
        // ChatGPT-Antwort hinzufügen
        this.messages.push({
          isUser: false,
          content: response.data.response,
          fullContent: response.data.response
        });
        
        // Speichere den Chat nach erfolgreicher Antwort
        await this.saveChat(true);
      } catch (error) {
        console.error('Error sending message to ChatGPT:', error);
        console.log("Fehlerdetails:", error.response ? error.response.data : "Keine Antwort");
        
        // Entferne Ladeindikator und zeige Fehlermeldung
        this.messages.pop();
        this.messages.push({
          isUser: false,
          content: "Es tut mir leid, ich konnte deine Anfrage nicht verarbeiten. Bitte versuche es später erneut oder kontaktiere den Support, wenn das Problem weiterhin besteht.",
          error: true
        });
      }
      
      // Scrolle nach unten, um die neue Antwort zu zeigen
      this.$nextTick(() => {
        this.scrollToBottom();
      });
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
          console.log('Speichere Chat in Supabase für User:', userId);
          
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
                console.error('Fehler beim Einfügen des Chats nach fehlgeschlagenem Update:', insertError);
                console.log("Insert-Fehlercode:", insertError.code);
                console.log("Insert-Fehlermeldung:", insertError.message);
                if (insertError.details) console.log("Insert-Fehlerdetails:", insertError.details);
                throw insertError;
              } else {
                console.log('Chat erfolgreich eingefügt nach fehlgeschlagenem Update');
              }
              
              // Save the new chat ID
              this.currentChatId = chatData.id;
            } else {
              console.log('Chat erfolgreich in Supabase aktualisiert');
            }
          } else {
            console.log('Füge neuen Chat in Supabase ein');
            // Insert new chat
            const { data, error } = await supabase
              .from('idea_chats')
              .insert([chatData]);
            
            if (error) {
              console.error('Fehler beim Einfügen des neuen Chats in Supabase:', error);
              console.log("Fehlercode:", error.code);
              console.log("Fehlermeldung:", error.message);
              if (error.details) console.log("Fehlerdetails:", error.details);
              throw error;
            } else {
              console.log('Neuer Chat erfolgreich in Supabase eingefügt');
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
      
      // HTML säubern, um XSS zu verhindern
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
          // Füge jeweils einen Buchstaben hinzu
          this.messages[messageIndex].content = fullContent.substring(0, typeIndex + 1);
          typeIndex++;
        } else {
          // Stoppe den Effekt, wenn fertig
          clearInterval(this.typing);
        }
      }, 10);  // Schnelle Geschwindigkeit für realistische Darstellung
    },
    toggleMobileMenu() {
      if (this.isMobile) {
        // Auf Mobilgeräten das Menü umschalten und Scrolling sperren
      this.mobileMenuOpen = !this.mobileMenuOpen;
        document.body.classList.toggle('overflow-hidden', this.mobileMenuOpen);
        
        // Add mobile-open class to the sidebar for additional CSS targeting
        const sidebar = document.querySelector('.sidebar-container');
        if (sidebar) {
      if (this.mobileMenuOpen) {
            sidebar.classList.add('mobile-open');
      } else {
            sidebar.classList.remove('mobile-open');
          }
        }
        
        // Event an den Body senden, damit andere Komponenten reagieren können
        const event = new CustomEvent('mobile-menu-toggle', { 
          detail: { open: this.mobileMenuOpen } 
        });
        document.body.dispatchEvent(event);
      } else {
        // Auf Desktop die Sidebar ein-/ausklappen
        this.sidebarCollapsed = !this.sidebarCollapsed;
        
        // Lokale Speicherung der Einstellung für Desktop
        localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
      }
    },
    closeMobileMenu() {
      if (this.isMobile && this.mobileMenuOpen) {
      this.mobileMenuOpen = false;
        document.body.classList.remove('overflow-hidden');
        
        // Remove mobile-open class from sidebar
        const sidebar = document.querySelector('.sidebar-container');
        if (sidebar) {
          sidebar.classList.remove('mobile-open');
        }
        
        // Event an den Body senden
        const event = new CustomEvent('mobile-menu-toggle', { 
          detail: { open: false } 
        });
        document.body.dispatchEvent(event);
      }
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
      
      // Esc zum Schließen von Popups
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
      // Fülle Platzhalter automatisch aus dem Kontext, wenn möglich
      let promptContent = prompt.content;
      
      // Wenn Kontextelemente vorhanden sind, versuche sie einzufügen
      if (this.contextStack.length > 0) {
        this.contextStack.forEach(item => {
          if (item.type === 'channel' && promptContent.includes('@[ChannelName]')) {
            promptContent = promptContent.replace('@[ChannelName]', '@' + item.name);
          }
        });
      }
      
      this.newMessage = promptContent;
      this.showPromptLibrary = false;
      
      // Fokus auf Textarea
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
      
      // Sidebar automatically close when clicking outside (only on mobile devices)
      if (this.isMobile && this.mobileMenuOpen) {
        const sidebar = document.querySelector('.sidebar-container');
        const toggleButton = document.querySelector('.mobile-toggle-button');
        
        // Make sure we're not clicking inside the sidebar or toggle button
        if (sidebar && toggleButton && 
            !sidebar.contains(event.target) && 
            !toggleButton.contains(event.target)) {
          // Only close if we clicked outside both elements
          this.closeMobileMenu();
        }
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
            
            return true;
            } catch (parseError) {
              console.error('Error parsing chat data:', parseError);
              throw new Error('Ungültiges Datenformat: ' + parseError.message);
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
          
          return true;
          } catch (parseError) {
            console.error('Error parsing local chat data:', parseError);
            throw new Error('Ungültiges lokales Datenformat: ' + parseError.message);
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
              // Stelle sicher, dass messages als Objekt oder Array verfügbar ist
              messages: typeof chat.messages === 'string' ? 
                JSON.parse(chat.messages) : 
                (Array.isArray(chat.messages) ? chat.messages : []),
              // Stelle sicher, dass context als Objekt oder null verfügbar ist
              context: chat.context ? 
                (typeof chat.context === 'string' ? JSON.parse(chat.context) : chat.context) : 
                null,
              date: chat.updated_at
            }));

            // Aktualisiere zusätzlich localStorage als Backup
            localStorage.setItem('chatHistory', JSON.stringify(this.chatHistory));
          } else {
            this.chatHistory = [];
          }
        } else {
          // Not logged in - Use localStorage
          try {
          const allSavedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          const tempUserId = sessionStorage.getItem('temp_user_id') || this.generateTempUserId();
          this.chatHistory = allSavedChats.filter(chat => 
            !chat.user_id || chat.user_id === tempUserId
          );
          } catch (parseError) {
            console.error('Error parsing localStorage chat history:', parseError);
            this.chatHistory = [];
          }
        }
      } catch (error) {
        console.error('Error loading chat history:', error);
        
        // Fallback to localStorage
        try {
          const allSavedChats = JSON.parse(localStorage.getItem('chatHistory') || '[]');
          this.chatHistory = allSavedChats;
        } catch (e) {
          console.error('Error parsing localStorage fallback:', e);
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
      // Prüfen, ob der aktuelle Chat der übergebene Chat ist
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
        // Verbindungsprüfung
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session) {
          this.supabaseStatus.message = 'Nicht eingeloggt, aber versuche trotzdem...';
          console.warn('Nicht eingeloggt bei dem Versuch, die Tabelle zu erstellen');
        }
        
        // SQL zur Tabellenerstellung ausführen
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
        alert('Möglicherweise musst du das SQL-Skript manuell in der Supabase SQL-Konsole ausführen. Fehler: ' + error.message);
      }
    },
    async testSaveChat() {
      this.debugInfo = "Versuche, Test-Chat zu speichern...";
      
      try {
        // Prüfe, ob wir eingeloggt sind
        const { data: { session } } = await supabase.auth.getSession();
        
        let userId;
        if (session && session.user) {
          userId = session.user.id;
          this.debugInfo += "\nEingeloggt als: " + session.user.email;
        } else {
          // Anonymer Test-Benutzer
          userId = 'anon-' + uuidv4().substring(0, 8);
          this.debugInfo += "\nNicht eingeloggt, verwende temporäre ID: " + userId;
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
        
        // Direktes Einfügen in die Datenbank - Vereinfachte Version ohne JSON.stringify
        this.debugInfo += "\nFüge in Supabase ein...";
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
          
          // Überprüfe auf häufige Fehler
          if (error.code === "23503") {
            this.debugInfo += "\nReferenzfehler: Möglicherweise ein Problem mit der user_id oder dem Fremdschlüssel.";
          } else if (error.code === "42P01") {
            this.debugInfo += "\nTabelle existiert nicht. Bitte erstelle die Tabelle.";
          } else if (error.code === "42703") {
            this.debugInfo += "\nSpalte existiert nicht. Tabellenschema könnte fehlerhaft sein.";
          } else if (error.code === "22P02") {
            this.debugInfo += "\nUngültiger JSON-Typ. Überprüfe die JSON-Struktur der Nachricht.";
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
      this.debugInfo = "Überprüfe idea_chats Tabelle...";
      
      try {
        // Prüfe, ob der Benutzer eingeloggt ist
        const { data: { session } } = await supabase.auth.getSession();
        
        if (!session || !session.user) {
          this.debugInfo += "\nNicht eingeloggt. Bitte einloggen.";
          return;
        }
        
        // Tabellenschema überprüfen
        this.debugInfo += "\nRufe Tabellenschema ab...";
        
        const { data: tableData, error: tableError } = await supabase
          .from('idea_chats')
          .select('*')
          .limit(0);
        
        if (tableError) {
          this.debugInfo += "\nFehler beim Prüfen der Tabelle: " + tableError.message;
          console.error("Error checking table:", tableError);
          return;
        }
        
        this.debugInfo += "\nTabelle existiert und ist zugänglich.";
        
        // Anzahl der Einträge abrufen
        const { count, error: countError } = await supabase
          .from('idea_chats')
          .select('*', { count: 'exact', head: true });
        
        if (countError) {
          this.debugInfo += "\nFehler beim Zählen der Einträge: " + countError.message;
        } else {
          this.debugInfo += `\nAnzahl der Einträge in der Tabelle: ${count || 0}`;
        }
        
        // Prüfe spezifisch für den aktuellen Benutzer
        const { data: userChats, error: userChatsError } = await supabase
          .from('idea_chats')
          .select('id, title, updated_at')
          .eq('user_id', session.user.id);
        
        if (userChatsError) {
          this.debugInfo += "\nFehler beim Abrufen der Benutzer-Chats: " + userChatsError.message;
        } else if (userChats && userChats.length > 0) {
          this.debugInfo += `\nGefundene Chats für Benutzer: ${userChats.length}`;
          this.debugInfo += "\nChats:";
          userChats.forEach((chat, index) => {
            this.debugInfo += `\n${index + 1}. ${chat.title} (ID: ${chat.id.substring(0, 8)}...) - ${new Date(chat.updated_at).toLocaleString()}`;
          });
        } else {
          this.debugInfo += "\nKeine Chats für den aktuellen Benutzer gefunden.";
        }
        
        // Überprüfe, ob die RLS-Richtlinien korrekt eingerichtet sind
        this.debugInfo += "\nRLS-Richtlinien können nicht direkt überprüft werden.";
        
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
        
        // 1. Prüfe Session und Benutzer
        const { data: { session } } = await supabase.auth.getSession();
        console.log("Session:", session);
        
        if (session && session.user) {
          console.log("Logged in as:", session.user.email);
          console.log("User ID:", session.user.id);
          
          // 2. Prüfe, ob die Tabelle existiert
          const { data: tableInfo, error: tableError } = await supabase
            .from('idea_chats')
            .select('*')
            .limit(1);
          
          console.log("Table exists:", tableError ? "NO" : "YES");
          if (tableError) {
            console.error("Table error:", tableError);
          }
          
          // 3. Prüfe vorhandene Chats
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
            this.debugInfo += "\nEinfügen fehlgeschlagen: " + insertError.message;
            if (insertError.details) {
              this.debugInfo += "\nDetails: " + insertError.details;
            }
          } else {
            this.debugInfo += "\nEinfügen erfolgreich! ID: " + testId;
            
            // 5. Versuche den eingefügten Datensatz zu lesen
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
              this.debugInfo += "\nKeine Daten für ID " + testId + " gefunden.";
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
    // Hinzufügen der checkSupabaseConnection-Methode
    async checkSupabaseConnection() {
      try {
        // Einfacher Verbindungstest
        await supabase.from('idea_chats').select('count').limit(1);
      } catch (error) {
        // Verbindungsfehler - Kein Logging nötig
      }
    },
    toggleTodayChats() {
      this.showTodayChats = !this.showTodayChats;
    },
    toggleLastWeekChats() {
      this.showLastWeekChats = !this.showLastWeekChats;
    },
    toggleOlderChats() {
      this.showOlderChats = !this.showOlderChats;
    },
    removeFromContextStack(index) {
      this.contextStack.splice(index, 1);
    },
    clearContextStack() {
      this.contextStack = [];
    },
    analyzeChannel() {
      // Implement the logic to analyze the channel
      console.log('Analyzing channel:', this.channelToAnalyze);
      this.isAnalyzing = true;
      this.channelAnalysisError = '';
      this.channelAnalysisResult = null;
      
      // Simulate an analysis process
      setTimeout(() => {
        this.isAnalyzing = false;
        this.channelAnalysisError = '';
        this.channelAnalysisResult = {
          name: this.channelToAnalyze,
          subscriberCount: Math.floor(Math.random() * 1000000),
          videoFormats: ['Short', 'Long', 'Documentary'],
          topics: ['Technology', 'Travel', 'Food'],
          topVideos: [
            { title: 'Top Tech Video', views: 100000, format: 'Short', duration: '5:00' },
            { title: 'Top Travel Video', views: 80000, format: 'Long', duration: '10:00' },
            { title: 'Top Food Video', views: 70000, format: 'Documentary', duration: '15:00' }
          ],
          profilePic: 'https://via.placeholder.com/150'
        };
      }, 2000);
    },
    generateChannelContentIdeas() {
      // Implement the logic to generate content ideas based on the analyzed channel
      console.log('Generating content ideas for:', this.channelToAnalyze);
      // Add your logic here to generate content ideas
    },
    analyzeVideo() {
      // Implement the logic to analyze the video
      console.log('Analyzing video:', this.videoToAnalyze);
      this.isVideoAnalyzing = true;
      
      // Simulate an analysis process
      setTimeout(() => {
        this.isVideoAnalyzing = false;
        // Add your logic here to handle video analysis results
      }, 2000);
    },
    addChannelToContextStack(channel) {
      if (this.contextStack.length >= 5) {
        alert('Du kannst maximal 5 Kontextelemente hinzufügen. Bitte entferne zuerst ein Element.');
        return;
      }
      
      this.contextStack.push({
        type: 'channel',
        id: channel.id || '',
        name: channel.name
      });
      this.showChannelAnalysisModal = false;
    },
    selectPromptCategory(category) {
      this.selectedCategory = category;
    },
    usePrompt(prompt) {
      // Fülle Platzhalter automatisch aus dem Kontext, wenn möglich
      let promptContent = prompt.content;
      
      // Wenn Kontextelemente vorhanden sind, versuche sie einzufügen
      if (this.contextStack.length > 0) {
        this.contextStack.forEach(item => {
          if (item.type === 'channel' && promptContent.includes('@[ChannelName]')) {
            promptContent = promptContent.replace('@[ChannelName]', '@' + item.name);
          }
        });
      }
      
      this.newMessage = promptContent;
      this.showPromptLibrary = false;
      
      // Fokus auf Textarea
      this.$nextTick(() => {
        document.querySelector('textarea').focus();
      });
    },
    removeFromContextStack(index) {
      this.contextStack.splice(index, 1);
    },
    // Close menu when clicking on links or outside the sidebar
    handleClickOutside(event) {
      if (this.isMobile && this.mobileMenuOpen) {
        // Only check if we're on mobile and the menu is open
        const sidebar = document.querySelector('.sidebar-container');
        const mobileToggle = document.querySelector('.mobile-toggle-button');
        
        // If we clicked outside both the sidebar and the toggle button, close the menu
        if (sidebar && !sidebar.contains(event.target) && 
            (!mobileToggle || !mobileToggle.contains(event.target))) {
          this.closeMobileMenu();
        }
      }
    },
    checkMobile() {
      this.isMobile = window.innerWidth < 768;
      // If not mobile anymore but menu was open, close it properly
      if (!this.isMobile && this.mobileMenuOpen) {
        this.mobileMenuOpen = false;
        document.body.style.overflow = '';
      }
    },
    async checkAuthStatus() {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        this.supabaseStatus.loggedIn = !!session;
        this.supabaseStatus.userId = session?.user?.id || null;
        
        // Prüfe, ob die Tabelle existiert
        if (session) {
          try {
            const { data, error } = await supabase
              .from('idea_chats')
              .select('count', { count: 'exact', head: true });
            
            this.supabaseStatus.tableExists = !error;
            
            if (error) {
              console.warn('Tabelle existiert möglicherweise nicht:', error.message);
              this.supabaseStatus.error = error.message;
            }
          } catch (e) {
            console.error('Fehler beim Prüfen der Tabelle:', e);
          }
        }
      } catch (error) {
        console.error('Auth status check error:', error);
      }
    },
    handleSidebarMouseEnter() {
      if (!this.isMobile && this.sidebarCollapsed) {
        this.isHoveringCollapsedSidebar = true;
      }
    },
    handleSidebarMouseLeave() {
      if (!this.isMobile) {
        this.isHoveringCollapsedSidebar = false;
        // Entferne diese Zeile, falls vorhanden
        // this.sidebarCollapsed = true;
      }
    },
    // Add method to handle clicks outside sidebar for desktop view
    handleOutsideClick(event) {
      // Only handle if we're on desktop and sidebar is expanded
      if (!this.isMobile && !this.sidebarCollapsed) {
        // Check if click is outside sidebar
        const sidebarEl = document.querySelector('.sidebar-container');
        // Entfernen wir diese automatische Sidebar-Schließung, damit die Sidebar sichtbar bleibt
        // if (sidebarEl && !sidebarEl.contains(event.target)) {
        //   this.sidebarCollapsed = true;
        // }
      }
    },
    checkIfMobile() {
      this.isMobile = window.innerWidth < 768;
      // If not mobile anymore but menu was open, close it properly
      if (!this.isMobile && this.mobileMenuOpen) {
        this.mobileMenuOpen = false;
        document.body.style.overflow = '';
      }
    },
    handleResize() {
      // Bildschirmgröße prüfen und isMobile aktualisieren
      const width = window.innerWidth;
      const wasMobile = this.isMobile;
      
      this.isMobile = width < 768; // Mobile unter 768px
      
      // Wenn von Desktop zu Mobile gewechselt wird
      if (!wasMobile && this.isMobile) {
        this.mobileMenuOpen = false; // Mobiles Menü schließen
      }
      
      // Wenn von Mobile zu Desktop gewechselt wird
      if (wasMobile && !this.isMobile) {
        this.mobileMenuOpen = false; // Mobile-Menü-Status zurücksetzen
        // Desktop-Sidebar-Status aus localStorage wiederherstellen
        const savedCollapsed = localStorage.getItem('sidebarCollapsed');
        if (savedCollapsed !== null) {
          this.sidebarCollapsed = savedCollapsed === 'true';
        }
      }
    },
    async regenerateResponse() {
      // Finde die letzte Benutzeranfrage
      const lastUserMessageIndex = [...this.messages].reverse().findIndex(m => m.isUser);
      
      if (lastUserMessageIndex < 0) return; // Keine Benutzeranfrage gefunden
      
      // Berechne den tatsächlichen Index im messages-Array
      const userMessageIndex = this.messages.length - 1 - lastUserMessageIndex;
      const userMessage = this.messages[userMessageIndex].content;
      
      // Entferne die letzte ChatGPT-Antwort
      this.messages.pop();
      
      // Zeige Ladeindikator
      this.messages.push({
        isUser: false,
        isLoading: true,
        content: 'Generiere neue Antwort...'
      });
      
      try {
        // Bereite den Kontext vor, falls vorhanden
        let contextPrompt = '';
        if (this.contextStack.length > 0) {
          contextPrompt = "Berücksichtige folgenden Kontext:\n";
          this.contextStack.forEach(item => {
            contextPrompt += `${item.type.toUpperCase()}: ${item.name}\n`;
          });
          contextPrompt += "\n";
        }
        
        console.log("Sende Regenerierungs-Anfrage an", "http://localhost:9000/api/chat");
          
        // Zeige Request-Daten im Konsolenlog
        const requestData = {
          message: contextPrompt + userMessage,
          model: this.selectedAI
        };
        console.log("Request-Daten:", requestData);
        
        // Sende Anfrage an ChatGPT API
        const response = await axios.post("http://localhost:9000/api/chat", requestData);
        
        console.log("API-Antwort erhalten:", response.data);
        
        
        // Entferne Ladeindikator
        this.messages.pop();
        
        // ChatGPT-Antwort hinzufügen
        this.messages.push({
          isUser: false,
          content: response.data.response,
          fullContent: response.data.response
        });
        
        // Speichere den Chat nach erfolgreicher Antwort
        await this.saveChat(true);
      } catch (error) {
        console.error('Error regenerating response:', error);
        console.log("Fehlerdetails:", error.response ? error.response.data : "Keine Antwort");
        
        // Entferne Ladeindikator und zeige Fehlermeldung
        this.messages.pop();
        this.messages.push({
          isUser: false,
          content: "Es tut mir leid, ich konnte keine neue Antwort generieren. Bitte versuche es später erneut.",
          error: true
        });
      }
      
      // Scrolle nach unten, um die neue Antwort zu zeigen
      this.$nextTick(() => {
        this.scrollToBottom();
      });
    }
  }
};
</script> 

<style scoped>
.chat-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: relative;
  overflow: hidden;
}

.fixed-header {
  width: 100%;
  z-index: 50; /* Höherer z-index als Sidebar */
  height: 64px; /* Feste Höhe für den Header */
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
}

.main-content-wrapper {
  display: flex;
  height: calc(100vh - 64px);
  margin-top: 64px; /* Entspricht der Header-Höhe */
  position: relative;
  width: 100%;
}

.sidebar-container {
  background-color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  z-index: 40;
  overflow-y: auto;
  overflow-x: hidden;
}

.toggle-button {
  width: 28px;
  height: 28px;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.toggle-button:hover {
  transform: scale(1.1);
}

.chat-main {
  flex: 1;
  padding: 1rem;
  overflow-y: auto;
  transition: all 0.3s ease-in-out;
}

.chat-main.expanded {
  margin-left: 64px; /* Breite der eingeklappten Sidebar */
}

.chat-main.full-width {
  margin-left: 320px; /* Breite der ausgeklappten Sidebar */
}

/* Mobile styles */
@media (max-width: 768px) {
  .mobile-toggle-button {
    display: flex;
  }
  
  .mobile-view .sidebar-container {
    height: 100vh;
    z-index: 50;
  }
  
  .chat-main {
    margin-left: 0 !important;
    width: 100% !important;
  }
}

/* Desktop styles */
@media (min-width: 769px) {
  .mobile-toggle-button {
    display: none;
  }
}

.sidebar-toggle-btn {
  right: -14px;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 35;
}

.sidebar-toggle-btn:hover {
  transform: translateY(-50%) translateX(50%) scale(1.1);
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
  z-index: 20; /* Erhöhter z-index */
  background-color: white;
  border-top: 1px solid rgb(229, 231, 235);
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05); /* Subtiler Schatten für bessere Sichtbarkeit */
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
  padding: 1rem 1.25rem;
  border-radius: 1rem;
  max-width: 80%;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.user-message .message-content {
  margin-left: auto;
  background-color: #f9e6f1; /* Hellere Pinktöne für Benutzernachrichten */
  border: 1px solid #f9d6e9;
}

.assistant-message .message-content {
  background-color: #f8f9fa; /* Hellgrauer Hintergrund für Assistentenantworten */
  border: 1px solid #e9ecef;
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
    overflow: hidden;
    position: relative;
  }
  
  .sidebar-container {
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 50;
    width: 85%;
    max-width: 320px;
    position: fixed;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }
  
  .transform {
    transition: transform 0.3s ease-in-out;
  }
  
  .transform.translate-x-0 {
    transform: translateX(0);
  }
  
  .transform.-translate-x-full {
    transform: translateX(-100%);
  }
  
  .chat-main {
    margin-left: 0;
    padding-left: 12px;
    padding-right: 12px;
    padding-top: 0;
    width: 100%;
  }
  
  /* fixed-header Regel entfernt */
  
  .message-container {
    padding: 0.5rem;
  }
  
  /* Zusätzliche Mobile-Stile */
  body.mobile-menu-open {
    overflow: hidden;
  }
  
  .mobile-view .mobile-toggle-button {
    z-index: 60;
  }
  
  .mobile-view .sidebar-container {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  
  .mobile-view .sidebar-container.translate-x-0 {
    opacity: 1;
  }
  
  .mobile-view .sidebar-container.-translate-x-full {
    opacity: 0;
  }
}

/* Desktop styles */
@media (min-width: 769px) {
  .sidebar-desktop {
    position: fixed;
    left: 0;
    top: 0; /* Änderung: war 64px */
    height: 100vh; /* Änderung: war calc(100vh - 64px) */
    z-index: 30;
    transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
    border-right: 1px solid rgba(0, 0, 0, 0.1);
    transform: translateX(0) !important;
  }
  
  .sidebar-container.w-16 {
    width: 64px;
  }
  
  .sidebar-container.w-80 {
    width: 280px;
  }
  
  .sidebar-container:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }
  
  .chat-main {
    transition: margin-left 0.3s ease-in-out;
    min-height: 100vh; /* Änderung: war calc(100vh - 64px) */
    margin-left: 64px;
    padding-top: 0; /* Änderung: war 64px */
    width: 100%;
    max-width: none;
  }
  
  /* Hauptinhalt verschieben basierend auf Sidebar-Status */
  .sidebar-container.w-80 ~ .chat-main {
    margin-left: 280px;
  }
  
  /* Button für Mobile nur auf Mobilgeräten anzeigen */
  .mobile-toggle-button {
    display: none;
  }
}

/* Mobile styles */
@media (max-width: 768px) {
  .mobile-toggle-button {
    display: flex;
  }
  
  .chat-main {
    margin-left: 0 !important;
    width: 100% !important;
    padding-top: 0; /* Änderung: war 64px */
  }
  
  /* Ensure fixed header stays on top */
  /*.fixed-header {
    z-index: 40;
  }*/
  
  /* Make sure sidebar is on top of everything when opened */
  .sidebar-container.translate-x-0 {
    z-index: 50;
  }
  
  /* Adjust sidebar overlay */
  .fixed.inset-0.bg-black.bg-opacity-50 {
    z-index: 45;
  }
}

/* Supabase Status Debug ausblenden */
.supabase-status-debug {
  display: none !important;
}

.mobile-view .sidebar-container.-translate-x-full {
  transform: translateX(-100%) !important;
}

.mobile-view .sidebar-container.translate-x-0 {
  transform: translateX(0) !important;
}

.chat-category-header {
  transition: background-color 0.2s ease;
}

.chat-category-header:hover {
  background-color: #f3f4f6;
}

.chat-category-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out, margin 0.3s ease;
}

.chat-category-content.expanded {
  max-height: 500px;
  margin-top: 8px;
}

.chat-icon {
  @apply flex-shrink-0 mr-2 text-gray-400;
  font-size: 16px;
  width: 16px;
}

.sidebar-chat-item {
  transition: all 0.2s ease-in-out;
}

.sidebar-chat-item:hover {
  background-color: #f3f4f6;
}

.sidebar-chat-item-active {
  background-color: #fce7f3 !important;
  border-left: 2px solid #ec4899;
}

.sidebar-chat-item .trash-icon {
  opacity: 0;
  transition: opacity 0.15s ease-in-out;
}

.sidebar-chat-item:hover .trash-icon {
  opacity: 1;
}

.context-stack {
  background-color: #f9f9f9;
  padding: 0.75rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
}

.context-item {
  display: flex;
  align-items: center;
  margin-right: 0.5rem;
  margin-bottom: 0.5rem;
  padding: 0.25rem 0.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.25rem;
}

.context-icon {
  margin-right: 0.25rem;
  color: #4b5563;
}

/* Add styles to improve mobile menu */
.mobile-view .sidebar-container {
  height: 100vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

/* Improve transition for smoother animation */
.sidebar-container {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Add overlay transition */
.bg-black.bg-opacity-50 {
  transition: opacity 0.3s ease;
}

/* Mobile menu styles */
@media (max-width: 767px) {
  .sidebar-container {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 250px;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 50;
  }
  
  .sidebar-container.mobile-open {
    transform: translateX(0);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }
  
  /* Make sidebar overlay appear above header but below toggle button */
  .mobile-view .fixed.inset-0.bg-black.bg-opacity-50 {
    z-index: 45;
  }
  
  .hamburger-button {
    display: block;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 60;
    background: white;
    border-radius: 50%;
    padding: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }
  
  /* fixed-header-Block entfernt */
  
  .chat-main {
    padding-top: 0;
  }
}

/* Anpassungen der Nachrichtenbreite */
.message-container {
  max-width: 90%;
  margin: 0 auto;
}

.message-content {
  max-width: 100%;
  width: 100%;
}

/* Kontextmenü direkt neben dem Eingabefeld */
.chat-input-wrapper {
  position: relative;
  display: flex;
  width: 100%;
}

.context-button {
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  bottom: 50%;
  transform: translateY(50%);
  left: 10px;
  width: 34px;
  height: 34px;
}
</style> 
