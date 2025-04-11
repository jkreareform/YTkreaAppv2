import { supabase } from './supabase';
import router from './router';

// Check if user is authenticated
export function checkAuth() {
    return new Promise((resolve) => {
        supabase.auth.getSession().then(({ data: { session }, error }) => {
            if (session) {
                resolve(true);
            } else {
                resolve(false);
            }
        });
    });
}

// Navigation guard for protected routes
export async function requireAuth(to, from, next) {
    const { data: { session } } = await supabase.auth.getSession();
    
    if (!session) {
        return next({ name: 'login' });
    }
    
    return next();
}

// Navigation guard for guest routes
export async function redirectIfAuthenticated(to, from, next) {
    const { data: { session } } = await supabase.auth.getSession();
    
    if (session) {
        return next({ name: 'dashboard' });
    }
    
    return next();
}

// Initialize auth state listener
export function initAuthListener() {
    supabase.auth.onAuthStateChange((event, session) => {
        if (event === 'SIGNED_IN') {
            const currentRoute = router.currentRoute.value.name;
            if (currentRoute === 'login' || currentRoute === 'register' || currentRoute === 'home') {
                router.push({ name: 'dashboard' });
            }
        }
    });
} 