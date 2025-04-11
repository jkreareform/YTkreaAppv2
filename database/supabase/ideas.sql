-- Create a table for ideas
create table ideas (
  id uuid default uuid_generate_v4() primary key,
  user_id uuid references auth.users not null,
  title text not null,
  description text,
  status text not null default 'draft' check (status in ('draft', 'in_progress', 'completed', 'archived')),
  created_at timestamp with time zone default timezone('utc'::text, now()) not null,
  updated_at timestamp with time zone default timezone('utc'::text, now()) not null,
  tags text[],
  chat_id uuid references chats(id),
  is_favorite boolean default false
);

-- Create a table for idea notes
create table idea_notes (
  id uuid default uuid_generate_v4() primary key,
  idea_id uuid references ideas on delete cascade not null,
  content text not null,
  created_at timestamp with time zone default timezone('utc'::text, now()) not null,
  updated_at timestamp with time zone default timezone('utc'::text, now()) not null
);

-- Set up Row Level Security (RLS)
alter table ideas enable row level security;
alter table idea_notes enable row level security;

-- Policies for ideas table
create policy "Users can view their own ideas"
  on ideas for select
  using ( auth.uid() = user_id );

create policy "Users can create their own ideas"
  on ideas for insert
  with check ( auth.uid() = user_id );

create policy "Users can update their own ideas"
  on ideas for update
  using ( auth.uid() = user_id );

create policy "Users can delete their own ideas"
  on ideas for delete
  using ( auth.uid() = user_id );

-- Policies for idea notes
create policy "Users can view notes from their ideas"
  on idea_notes for select
  using (
    exists (
      select 1 from ideas
      where ideas.id = idea_notes.idea_id
      and ideas.user_id = auth.uid()
    )
  );

create policy "Users can create notes for their ideas"
  on idea_notes for insert
  with check (
    exists (
      select 1 from ideas
      where ideas.id = idea_notes.idea_id
      and ideas.user_id = auth.uid()
    )
  );

create policy "Users can update notes from their ideas"
  on idea_notes for update
  using (
    exists (
      select 1 from ideas
      where ideas.id = idea_notes.idea_id
      and ideas.user_id = auth.uid()
    )
  );

create policy "Users can delete notes from their ideas"
  on idea_notes for delete
  using (
    exists (
      select 1 from ideas
      where ideas.id = idea_notes.idea_id
      and ideas.user_id = auth.uid()
    )
  );

-- Create indexes for better performance
create index ideas_user_id_idx on ideas(user_id);
create index ideas_status_idx on ideas(status);
create index idea_notes_idea_id_idx on idea_notes(idea_id);

-- Function to update the updated_at timestamp
create trigger update_ideas_updated_at
  before update on ideas
  for each row execute procedure update_updated_at_column();

create trigger update_idea_notes_updated_at
  before update on idea_notes
  for each row execute procedure update_updated_at_column(); 