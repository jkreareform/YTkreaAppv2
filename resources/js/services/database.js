import { supabase } from '../supabase';

export const DatabaseService = {
    // Profile Operationen
    async getProfile(userId) {
        const { data, error } = await supabase
            .from('profiles')
            .select('*')
            .eq('user_id', userId)
            .single();
        
        if (error) throw error;
        return data;
    },

    async updateProfile(userId, updates) {
        const { data, error } = await supabase
            .from('profiles')
            .update(updates)
            .eq('user_id', userId);
        
        if (error) throw error;
        return data;
    },

    // Chat Operationen
    async createChat(userId, title, aiModel) {
        const { data, error } = await supabase
            .from('chats')
            .insert([{
                user_id: userId,
                title,
                ai_model: aiModel
            }])
            .select()
            .single();
        
        if (error) throw error;
        return data;
    },

    async getChats(userId) {
        const { data, error } = await supabase
            .from('chats')
            .select('*')
            .eq('user_id', userId)
            .order('created_at', { ascending: false });
        
        if (error) throw error;
        return data;
    },

    async addChatMessage(chatId, content, role) {
        const { data, error } = await supabase
            .from('chat_messages')
            .insert([{
                chat_id: chatId,
                content,
                role
            }])
            .select()
            .single();
        
        if (error) throw error;
        return data;
    },

    async getChatMessages(chatId) {
        const { data, error } = await supabase
            .from('chat_messages')
            .select('*')
            .eq('chat_id', chatId)
            .order('created_at', { ascending: true });
        
        if (error) throw error;
        return data;
    },

    // Ideen Operationen
    async createIdea(userId, { title, description, tags = [] }) {
        const { data, error } = await supabase
            .from('ideas')
            .insert([{
                user_id: userId,
                title,
                description,
                tags,
                status: 'draft'
            }])
            .select()
            .single();
        
        if (error) throw error;
        return data;
    },

    async getIdeas(userId) {
        const { data, error } = await supabase
            .from('ideas')
            .select('*')
            .eq('user_id', userId)
            .order('created_at', { ascending: false });
        
        if (error) throw error;
        return data;
    },

    async updateIdea(ideaId, updates) {
        const { data, error } = await supabase
            .from('ideas')
            .update(updates)
            .eq('id', ideaId)
            .select()
            .single();
        
        if (error) throw error;
        return data;
    },

    async addIdeaNote(ideaId, content) {
        const { data, error } = await supabase
            .from('idea_notes')
            .insert([{
                idea_id: ideaId,
                content
            }])
            .select()
            .single();
        
        if (error) throw error;
        return data;
    },

    async getIdeaNotes(ideaId) {
        const { data, error } = await supabase
            .from('idea_notes')
            .select('*')
            .eq('idea_id', ideaId)
            .order('created_at', { ascending: true });
        
        if (error) throw error;
        return data;
    },

    // Real-time Subscriptions
    subscribeToChats(chatId, callback) {
        return supabase
            .channel(`chat:${chatId}`)
            .on('postgres_changes', {
                event: '*',
                schema: 'public',
                table: 'chat_messages',
                filter: `chat_id=eq.${chatId}`
            }, callback)
            .subscribe();
    },

    subscribeToIdeas(userId, callback) {
        return supabase
            .channel(`ideas:${userId}`)
            .on('postgres_changes', {
                event: '*',
                schema: 'public',
                table: 'ideas',
                filter: `user_id=eq.${userId}`
            }, callback)
            .subscribe();
    }
}; 