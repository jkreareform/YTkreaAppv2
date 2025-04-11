-- Überprüfen der messages-Spalte
SELECT column_name, data_type 
FROM information_schema.columns 
WHERE table_schema = 'public' 
AND table_name = 'idea_chats' 
AND column_name = 'messages';

-- Um die Spalte zu ändern, falls nötig, entkommentiere die folgende Zeile:
-- ALTER TABLE public.idea_chats ALTER COLUMN messages TYPE JSONB USING messages::jsonb; 