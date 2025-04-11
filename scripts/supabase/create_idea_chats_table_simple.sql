-- Vereinfachte Version ohne Fremdschlüssel
CREATE TABLE IF NOT EXISTS public.idea_chats (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL,
  title TEXT NOT NULL,
  messages JSONB NOT NULL,
  context JSONB,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idea_chats_user_id_idx ON public.idea_chats(user_id);
CREATE INDEX IF NOT EXISTS idea_chats_updated_at_idx ON public.idea_chats(updated_at);

-- Enable Row Level Security
ALTER TABLE public.idea_chats ENABLE ROW LEVEL SECURITY;

-- Create policies for row level security
CREATE POLICY "Users can view own chats" ON public.idea_chats
  FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can insert own chats" ON public.idea_chats
  FOR INSERT WITH CHECK (auth.uid() = user_id);

CREATE POLICY "Users can update own chats" ON public.idea_chats
  FOR UPDATE USING (auth.uid() = user_id);

CREATE POLICY "Users can delete own chats" ON public.idea_chats
  FOR DELETE USING (auth.uid() = user_id); 