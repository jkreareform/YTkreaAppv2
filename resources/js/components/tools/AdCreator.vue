<template>
  <div class="min-h-screen bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ad Creator</h1>
        <p class="mt-2 text-gray-600">Generate professional advertisements and creatives for your marketing campaigns.</p>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Form -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Ad Creator Form -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Advertisement Details</h2>
            
            <form @submit.prevent="generateAd" class="space-y-6">
              <!-- Platform -->
              <div>
                <label for="platform" class="block text-sm font-medium text-gray-700">Platform</label>
                <select v-model="formData.platform" id="platform" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Select platform...</option>
                  <option value="facebook">Facebook</option>
                  <option value="instagram">Instagram</option>
                  <option value="google">Google Ads</option>
                  <option value="linkedin">LinkedIn</option>
                  <option value="tiktok">TikTok</option>
                </select>
              </div>

              <!-- Ad Type -->
              <div>
                <label for="adType" class="block text-sm font-medium text-gray-700">Ad Type</label>
                <select v-model="formData.adType" id="adType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Select ad type...</option>
                  <option value="image">Image Ad</option>
                  <option value="carousel">Carousel Ad</option>
                  <option value="video">Video Ad</option>
                  <option value="text">Text Ad</option>
                  <option value="story">Story Ad</option>
                </select>
              </div>

              <!-- Campaign Objective -->
              <div>
                <label for="objective" class="block text-sm font-medium text-gray-700">Campaign Objective</label>
                <select v-model="formData.objective" id="objective" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Select objective...</option>
                  <option value="awareness">Brand Awareness</option>
                  <option value="consideration">Consideration</option>
                  <option value="conversion">Conversion</option>
                  <option value="lead_gen">Lead Generation</option>
                  <option value="traffic">Website Traffic</option>
                </select>
              </div>

              <!-- Target Audience -->
              <div>
                <label for="targetAudience" class="block text-sm font-medium text-gray-700">Target Audience</label>
                <textarea v-model="formData.targetAudience" id="targetAudience" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Describe your target audience (age, interests, location, etc.)"></textarea>
              </div>

              <!-- Value Proposition -->
              <div>
                <label for="valueProposition" class="block text-sm font-medium text-gray-700">Value Proposition</label>
                <textarea v-model="formData.valueProposition" id="valueProposition" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="What makes your product/service valuable to customers?"></textarea>
              </div>

              <!-- Call to Action -->
              <div>
                <label for="cta" class="block text-sm font-medium text-gray-700">Call to Action</label>
                <select v-model="formData.cta" id="cta" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                  <option value="">Select CTA...</option>
                  <option value="learn_more">Learn More</option>
                  <option value="shop_now">Shop Now</option>
                  <option value="sign_up">Sign Up</option>
                  <option value="book_now">Book Now</option>
                  <option value="contact_us">Contact Us</option>
                  <option value="download">Download</option>
                </select>
              </div>

              <!-- Landing Page URL -->
              <div>
                <label for="landingPageUrl" class="block text-sm font-medium text-gray-700">Landing Page URL</label>
                <input type="url" v-model="formData.landingPageUrl" id="landingPageUrl" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="https://example.com/landing-page">
              </div>

              <!-- Brand Guidelines -->
              <div>
                <label for="brandGuidelines" class="block text-sm font-medium text-gray-700">Brand Guidelines (optional)</label>
                <textarea v-model="formData.brandGuidelines" id="brandGuidelines" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Any specific brand guidelines, colors, tone of voice, etc."></textarea>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end">
                <button type="submit" :disabled="isLoading" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                  <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isLoading ? 'Generating Ad...' : 'Generate Ad' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Column: Tips and Results -->
        <div class="space-y-8">
          <!-- Ad Creation Tips -->
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Ad Creation Tips</h2>
            <div class="text-gray-600">
              <ul class="list-disc pl-5 space-y-2">
                <li>Keep headlines short and attention-grabbing</li>
                <li>Use clear and compelling visuals</li>
                <li>Focus on benefits, not features</li>
                <li>Include a strong call-to-action</li>
                <li>Use social proof when possible</li>
                <li>Test different versions of your ads</li>
              </ul>
            </div>
          </div>

          <!-- Ad Result (shown after generation) -->
          <div v-if="adResult" class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Your Advertisement</h2>
            
            <!-- Ad Preview -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
              <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500">AD PREVIEW</span>
                <span class="text-xs font-medium px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ formData.platform }}</span>
              </div>
              
              <h3 class="font-bold text-lg text-gray-900">{{ adResult.headline }}</h3>
              <p class="text-gray-700 mt-2">{{ adResult.description }}</p>
              
              <div class="mt-3 flex items-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                  {{ getCTAText(formData.cta) }}
                </span>
              </div>
            </div>
            
            <!-- Ad Variants -->
            <div class="space-y-4">
              <h3 class="font-medium text-gray-900">Ad Variants</h3>
              
              <div v-for="(variant, index) in adResult.variants" :key="index" class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="font-medium text-sm text-gray-700">Variant {{ index + 1 }}</h4>
                <p class="text-sm text-gray-600 mt-1">{{ variant }}</p>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 pt-4 border-t border-gray-200 flex flex-wrap gap-3">
              <button @click="copyAdToClipboard" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
                Copy Ad Text
              </button>
              
              <button @click="downloadAd" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdCreator',
  
  data() {
    return {
      isLoading: false,
      formData: {
        platform: '',
        adType: '',
        objective: '',
        targetAudience: '',
        valueProposition: '',
        cta: '',
        landingPageUrl: '',
        brandGuidelines: ''
      },
      adResult: null,
      transitionComplete: false
    };
  },
  
  mounted() {
    // Check if there's a funnelId passed as a query parameter
    const funnelId = this.$route.query.funnelId;
    if (funnelId) {
      // Could fetch funnel data and pre-populate the form
      console.log('Loading ad creator with funnel ID:', funnelId);
    }
  },
  
  methods: {
    async generateAd() {
      this.isLoading = true;
      
      try {
        // Simulate API request with timeout
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Here would normally be an API call
        // Example result for demo purposes
        this.adResult = {
          headline: "Transform Your Local Business with Our Expert Solutions",
          description: "Boost visibility, attract more customers, and grow your business with our proven strategies tailored for local businesses.",
          variants: [
            "Looking to stand out in your local market? Our solutions help you connect with customers right in your neighborhood.",
            "Don't get lost in the crowd. Our local business marketing strategies help you become the go-to choice in your area.",
            "From search to social, we'll help your local business get discovered by the right customers at the right time."
          ]
        };

        this.$nextTick(() => {
          this.scrollToBottom();
          // Animation für die Antwort aktivieren
          setTimeout(() => {
            this.transitionComplete = true;
          }, 300);
        });
      } catch (error) {
        console.error('Error generating ad:', error);
        // Error handling here
      } finally {
        this.isLoading = false;
      }
    },
    
    getCTAText(ctaCode) {
      const ctaMap = {
        'learn_more': 'Learn More',
        'shop_now': 'Shop Now',
        'sign_up': 'Sign Up',
        'book_now': 'Book Now',
        'contact_us': 'Contact Us',
        'download': 'Download'
      };
      
      return ctaMap[ctaCode] || 'Learn More';
    },
    
    copyAdToClipboard() {
      if (!this.adResult) return;
      
      const adText = `Headline: ${this.adResult.headline}\n\nDescription: ${this.adResult.description}\n\nCTA: ${this.getCTAText(this.formData.cta)}\n\nVariants:\n${this.adResult.variants.map((v, i) => `${i+1}. ${v}`).join('\n')}`;
      
      navigator.clipboard.writeText(adText)
        .then(() => {
          alert('Ad text copied to clipboard!');
        })
        .catch(err => {
          console.error('Error copying to clipboard:', err);
        });
    },
    
    downloadAd() {
      if (!this.adResult) return;
      
      const adText = `# Generated Advertisement\n\nPlatform: ${this.formData.platform}\nAd Type: ${this.formData.adType}\nObjective: ${this.formData.objective}\n\n## Main Ad\nHeadline: ${this.adResult.headline}\nDescription: ${this.adResult.description}\nCTA: ${this.getCTAText(this.formData.cta)}\n\n## Variants\n${this.adResult.variants.map((v, i) => `${i+1}. ${v}`).join('\n')}`;
      
      const blob = new Blob([adText], { type: 'text/plain' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `ad-${Date.now()}.txt`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    },
    
    scrollToBottom() {
      // Implement the logic to scroll to the bottom of the component
    }
  }
};
</script> 