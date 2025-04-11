-- Löschen bestehender Richtlinien
DROP POLICY IF EXISTS "Users can view own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can insert own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can update own chats" ON public.idea_chats;
DROP POLICY IF EXISTS "Users can delete own chats" ON public.idea_chats;

-- Prüfen, ob die Tabelle existiert, und anpassen
DO $$
BEGIN
  -- Überprüfen, ob die messages-Spalte den richtigen Typ hat
  IF EXISTS (
    SELECT 1 FROM information_schema.columns 
    WHERE table_schema = 'public' 
    AND table_name = 'idea_chats' 
    AND column_name = 'messages'
    AND data_type != 'jsonb'
  ) THEN
    -- Ändern des Typs, falls nötig
    ALTER TABLE public.idea_chats ALTER COLUMN messages TYPE JSONB USING messages::jsonb;
  END IF;
  
  -- Row Level Security aktivieren
  ALTER TABLE public.idea_chats ENABLE ROW LEVEL SECURITY;
  
  -- Neue Richtlinien erstellen
  CREATE POLICY "Users can view own chats" ON public.idea_chats
    FOR SELECT USING (auth.uid() = user_id);
  
  CREATE POLICY "Users can insert own chats" ON public.idea_chats
    FOR INSERT WITH CHECK (auth.uid() = user_id);
  
  CREATE POLICY "Users can update own chats" ON public.idea_chats
    FOR UPDATE USING (auth.uid() = user_id);
  
  CREATE POLICY "Users can delete own chats" ON public.idea_chats
    FOR DELETE USING (auth.uid() = user_id);
    
END;
$$; 