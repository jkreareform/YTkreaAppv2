<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Welcome, <span id="user-name">User</span>!</h3>
                    </div>
                    
                    <div class="mt-4">
                        <button id="logout-button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get current user
        supabase.auth.getUser().then(({ data: { user }, error }) => {
            if (user) {
                document.getElementById('user-name').textContent = user.user_metadata.name || user.email;
            }
        });

        // Logout functionality
        document.getElementById('logout-button').addEventListener('click', function() {
            supabase.auth.signOut().then(() => {
                window.location.href = '/login';
            });
        });
    });
</script>
@endpush
