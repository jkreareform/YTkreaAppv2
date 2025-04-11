-- Aktualisiere profiles Tabelle für das überarbeitete Onboarding
-- Das fokussiert sich auf Skripte und Content-Erstellung statt auf YouTube-Kanäle

-- Führe Änderungen in einer Transaktion aus
BEGIN;

-- Prüfe, ob die Spalten bereits existieren und füge sie hinzu falls nicht
DO $$
BEGIN
    -- Erfahrungslevel
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'experience_level') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "experience_level" TEXT;
    END IF;

    -- Plattformen für Content
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'content_platforms') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "content_platforms" TEXT[];
    END IF;

    -- Content-Erstellungshäufigkeit
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'content_frequency') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "content_frequency" TEXT;
    END IF;

    -- Content-Typen
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'content_types') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "content_types" TEXT[];
    END IF;

    -- Anderer Content-Typ
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'other_content_type') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "other_content_type" TEXT;
    END IF;

    -- Ziele
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'goals') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "goals" TEXT[];
    END IF;

    -- Anderes Ziel
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'other_goal') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "other_goal" TEXT;
    END IF;

    -- Onboarding-Status
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'onboarding_completed') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "onboarding_completed" BOOLEAN DEFAULT FALSE;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'onboarding_completed_at') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "onboarding_completed_at" TIMESTAMPTZ;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'onboarding_seen') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "onboarding_seen" BOOLEAN DEFAULT FALSE;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns 
                   WHERE table_schema = 'public' 
                   AND table_name = 'profiles' 
                   AND column_name = 'onboarding_seen_at') THEN
        ALTER TABLE "public"."profiles" ADD COLUMN "onboarding_seen_at" TIMESTAMPTZ;
    END IF;

END $$;

-- Kommentare für die neuen Spalten
COMMENT ON COLUMN "public"."profiles"."experience_level" IS 'Erfahrungsniveau des Benutzers bei der Content-Erstellung (beginner, intermediate, advanced)';
COMMENT ON COLUMN "public"."profiles"."content_platforms" IS 'Plattformen, auf denen der Benutzer Content erstellt (Array: YouTube, TikTok, Instagram, etc.)';
COMMENT ON COLUMN "public"."profiles"."content_frequency" IS 'Häufigkeit, mit der der Benutzer Content erstellt (daily, weekly, etc.)';
COMMENT ON COLUMN "public"."profiles"."content_types" IS 'Arten von Content, die der Benutzer erstellt (Gaming, Vlogs, etc.)';
COMMENT ON COLUMN "public"."profiles"."goals" IS 'Ziele des Benutzers für seine Content-Erstellung';
COMMENT ON COLUMN "public"."profiles"."onboarding_completed" IS 'Zeigt an, ob der Benutzer das Onboarding abgeschlossen hat';
COMMENT ON COLUMN "public"."profiles"."onboarding_seen" IS 'Zeigt an, ob der Benutzer das Onboarding gesehen hat';

COMMIT; 