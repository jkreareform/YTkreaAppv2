-- Create a table for chat conversations
create table chats (
  id uuid default uuid_generate_v4() primary key,
  user_id uuid references auth.users not null,
  title text not null,
  ai_model text not null,
  created_at timestamp with time zone default timezone('utc'::text, now()) not null,
  updated_at timestamp with time zone default timezone('utc'::text, now()) not null,
  is_archived boolean default false
);

-- Create a table for chat messages
create table chat_messages (
  id uuid default uuid_generate_v4() primary key,
  chat_id uuid references chats on delete cascade not null,
  content text not null,
  role text not null check (role in ('user', 'assistant')),
  created_at timestamp with time zone default timezone('utc'::text, now()) not null
);

-- Set up Row Level Security (RLS)
alter table chats enable row level security;
alter table chat_messages enable row level security;

-- Policies for chats table
create policy "Users can view their own chats"
  on chats for select
  using ( auth.uid() = user_id );

create policy "Users can create their own chats"
  on chats for insert
  with check ( auth.uid() = user_id );

create policy "Users can update their own chats"
  on chats for update
  using ( auth.uid() = user_id );

create policy "Users can delete their own chats"
  on chats for delete
  using ( auth.uid() = user_id );

-- Policies for chat_messages table
create policy "Users can view messages from their chats"
  on chat_messages for select
  using (
    exists (
      select 1 from chats
      where chats.id = chat_messages.chat_id
      and chats.user_id = auth.uid()
    )
  );

create policy "Users can insert messages to their chats"
  on chat_messages for insert
  with check (
    exists (
      select 1 from chats
      where chats.id = chat_messages.chat_id
      and chats.user_id = auth.uid()
    )
  );

-- Create indexes for better performance
create index chats_user_id_idx on chats(user_id);
create index chat_messages_chat_id_idx on chat_messages(chat_id);

-- Function to update the updated_at timestamp
create function update_updated_at_column()
returns trigger as $$
begin
  new.updated_at = timezone('utc'::text, now());
  return new;
end;
$$ language plpgsql;

-- Create trigger to automatically update the updated_at column
create trigger update_chats_updated_at
  before update on chats
  for each row execute procedure update_updated_at_column(); 