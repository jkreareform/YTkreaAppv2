-- Row Level Security aktivieren
ALTER TABLE public.idea_chats ENABLE ROW LEVEL SECURITY;

-- Erstellen einer einzelnen Richtlinie
CREATE POLICY "Users can view own chats" ON public.idea_chats
  FOR SELECT USING (auth.uid() = user_id); 