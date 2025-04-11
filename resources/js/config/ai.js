export const AI_CONFIG = {
    openai: {
        apiKey: process.env.VITE_OPENAI_API_KEY,
        model: 'gpt-4-turbo-preview',
        maxTokens: 2000,
        temperature: 0.7,
    },
    deepseek: {
        apiKey: process.env.VITE_DEEPSEEK_API_KEY,
        model: 'deepseek-chat',
        maxTokens: 2000,
        temperature: 0.7,
    }
}; 