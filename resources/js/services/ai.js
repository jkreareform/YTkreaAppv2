import { AI_CONFIG } from '../config/ai';

class AIService {
    constructor() {
        this.openaiConfig = AI_CONFIG.openai;
        this.deepseekConfig = AI_CONFIG.deepseek;
    }

    async chatWithGPT(messages) {
        try {
            const response = await fetch('https://api.openai.com/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.openaiConfig.apiKey}`
                },
                body: JSON.stringify({
                    model: this.openaiConfig.model,
                    messages: messages,
                    max_tokens: this.openaiConfig.maxTokens,
                    temperature: this.openaiConfig.temperature
                })
            });

            if (!response.ok) {
                throw new Error(`OpenAI API error: ${response.statusText}`);
            }

            const data = await response.json();
            return data.choices[0].message.content;
        } catch (error) {
            console.error('Error in chatWithGPT:', error);
            throw error;
        }
    }

    async chatWithDeepseek(messages) {
        try {
            const response = await fetch('https://api.deepseek.com/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.deepseekConfig.apiKey}`
                },
                body: JSON.stringify({
                    model: this.deepseekConfig.model,
                    messages: messages,
                    max_tokens: this.deepseekConfig.maxTokens,
                    temperature: this.deepseekConfig.temperature
                })
            });

            if (!response.ok) {
                throw new Error(`Deepseek API error: ${response.statusText}`);
            }

            const data = await response.json();
            return data.choices[0].message.content;
        } catch (error) {
            console.error('Error in chatWithDeepseek:', error);
            throw error;
        }
    }

    // Hilfsmethode zum Formatieren der Nachrichten für beide APIs
    formatMessages(conversation) {
        return conversation.map(msg => ({
            role: msg.role || (msg.isUser ? 'user' : 'assistant'),
            content: msg.content
        }));
    }
}

export const aiService = new AIService(); 