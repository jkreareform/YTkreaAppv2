-- Löschen bestehender Richtlinien
DROP POLICY IF EXISTS "Users can view own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can insert own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can update own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can delete own chats" ON public.idea_chats; 