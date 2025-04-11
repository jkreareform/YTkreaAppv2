-- Drop existing triggers if they exist
drop trigger if exists on_auth_user_created on auth.users;
drop trigger if exists handle_profiles_updated_at on public.profiles;

-- Drop existing functions if they exist
drop function if exists public.handle_new_user();
drop function if exists public.handle_updated_at();

-- Drop existing table if it exists
drop table if exists public.profiles;

-- Create profiles table
create table public.profiles (
    id uuid not null primary key default uuid_generate_v4(),
    user_id uuid references auth.users on delete cascade not null,
    username text,
    full_name text,
    website text,
    about text,
    created_at timestamp with time zone default timezone('utc'::text, now()) not null,
    updated_at timestamp with time zone default timezone('utc'::text, now()) not null,
    constraint username_length check (char_length(username) >= 3)
);

-- Create indexes
create index profiles_user_id_idx on public.profiles(user_id);
create index profiles_username_idx on public.profiles(username);

-- Set up Row Level Security (RLS)
alter table public.profiles enable row level security;

-- Create policies
create policy "Users can view their own profile" 
    on public.profiles for select 
    using (auth.uid() = user_id);

create policy "Users can update their own profile" 
    on public.profiles for update 
    using (auth.uid() = user_id);

create policy "Users can insert their own profile" 
    on public.profiles for insert 
    with check (auth.uid() = user_id);

-- Create a trigger to set updated_at on updates
create or replace function public.handle_updated_at()
returns trigger as $$
begin
    new.updated_at = now();
    return new;
end;
$$ language plpgsql;

create trigger handle_profiles_updated_at
    before update on public.profiles
    for each row
    execute procedure public.handle_updated_at();

-- Create a function to handle new user signup
create or replace function public.handle_new_user()
returns trigger as $$
begin
    insert into public.profiles (user_id)
    values (new.id);
    return new;
end;
$$ language plpgsql security definer;

-- Create a trigger for new user signup
create trigger on_auth_user_created
    after insert on auth.users
    for each row execute procedure public.handle_new_user(); 