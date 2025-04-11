import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Dashboard from './components/Dashboard.vue';
import ForgotPassword from './components/ForgotPassword.vue';
import ResetPassword from './components/ResetPassword.vue';
import Profile from './components/Profile.vue';
import IdeaChat from './components/IdeaChat.vue';
import SupabaseTest from './components/SupabaseTest.vue';
import Home from './components/Home.vue';
import { requireAuth, redirectIfAuthenticated } from './auth';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: redirectIfAuthenticated
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        beforeEnter: redirectIfAuthenticated
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        beforeEnter: requireAuth
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        beforeEnter: redirectIfAuthenticated
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: ResetPassword
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        beforeEnter: requireAuth
    },
    // Tool Routes
    {
        path: '/chat',
        name: 'chat',
        component: () => import('./components/Chat.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/idea-chat',
        name: 'idea-chat',
        component: IdeaChat,
        beforeEnter: requireAuth
    },
    {
        path: '/idea-chat/:chatId',
        name: 'specific-chat',
        component: IdeaChat,
        beforeEnter: requireAuth
    },
    {
        path: '/channel-analysis',
        name: 'channel-analysis',
        component: () => import('./components/tools/ChannelAnalysis.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/video-research',
        name: 'video-research',
        component: () => import('./components/tools/VideoResearch.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/topic-research',
        name: 'topic-research',
        component: () => import('./components/tools/TopicResearch.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/audience-research',
        name: 'audience-research',
        component: () => import('./components/tools/AudienceResearch.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/breakdown',
        name: 'breakdown',
        component: () => import('./components/tools/Breakdown.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/keyword-research',
        name: 'keyword-research',
        component: () => import('./components/tools/KeywordResearch.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/title-generator',
        name: 'title-generator',
        component: () => import('./components/tools/TitleGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/hook-generator',
        name: 'hook-generator',
        component: () => import('./components/tools/HookGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/description-generator',
        name: 'description-generator',
        component: () => import('./components/tools/DescriptionGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/tag-generator',
        name: 'tag-generator',
        component: () => import('./components/tools/TagGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/thumbnail-grabber',
        name: 'thumbnail-grabber',
        component: () => import('./components/tools/ThumbnailGrabber.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/transcript-generator',
        name: 'transcript-generator',
        component: () => import('./components/tools/TranscriptGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/funnel-generator',
        name: 'funnel-generator',
        component: () => import('./components/tools/FunnelGenerator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/ad-creator',
        name: 'ad-creator',
        component: () => import('./components/tools/AdCreator.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/add-channel',
        name: 'add-channel',
        component: () => import('./components/AddChannel.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/scripts',
        name: 'scripts',
        component: () => import('./components/ScriptsManager.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/sales-script-form',
        name: 'sales-script-form',
        component: () => import('./components/SalesScriptForm.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/thank-you',
        name: 'thank-you',
        component: () => import('./components/ThankYou.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/multi-step-form',
        name: 'multi-step-form',
        component: () => import('./components/MultiStepForm.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/guide/:id',
        name: 'guide',
        component: () => import('./components/Guide.vue'),
        beforeEnter: requireAuth
    },
    {
        path: '/referral-program',
        name: 'referral-program',
        component: () => import('./components/ReferralProgram.vue'),
        beforeEnter: requireAuth
    },
    // Supabase Test Route - kein Auth erforderlich für einfacheres Testen
    {
        path: '/supabase-test',
        name: 'supabase-test',
        component: SupabaseTest,
        // Ausdrücklich alle Auth-Checks deaktivieren
        beforeEnter: (to, from, next) => {
            console.log('Navigating to supabase-test, bypassing auth');
            next();
        }
    }
];

export default routes; 