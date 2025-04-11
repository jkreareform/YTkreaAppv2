-- Erstellen weiterer Richtlinien
CREATE POLICY "Users can insert own chats" ON public.idea_chats
  FOR INSERT WITH CHECK (auth.uid() = user_id);

CREATE POLICY "Users can update own chats" ON public.idea_chats
  FOR UPDATE USING (auth.uid() = user_id);

CREATE POLICY "Users can delete own chats" ON public.idea_chats
  FOR DELETE USING (auth.uid() = user_id); 