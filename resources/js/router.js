import {createRouter, createWebHistory} from 'vue-router';

// Main Pages
import Home from '@/pages/Home.vue';
import SignIn from '@/pages/SignIn.vue';
import SignUp from '@/pages/SignUp.vue';
import Events from '@/pages/Events.vue';
import Teams from '@/pages/Teams.vue';

// Profile Pages
import Profile from '@/pages/profile/Profile.vue';
import UpcomingEvents from '@/pages/profile/UpcomingEvents.vue';
import FinishedEvents from '@/pages/profile/FinishedEvents.vue';

// Team view pages
import Team from '@/pages/teams/Team.vue';

// Event view pages
import Event from '@/pages/events/Event.vue';

// Admin Pages
import AdminRequestsEvents from '@/pages/_admin/RequestsEvents.vue';
import AdminUsers from '@/pages/_admin/Users.vue';
import AdminTeams from '@/pages/_admin/Teams.vue';
import AdminTags from '@/pages/_admin/Tags.vue';
import AdminOrganizers from '@/pages/_admin/Organizers.vue';
import AdminUser from '@/pages/_admin/User.vue';
import AdminTeam from '@/pages/_admin/Team.vue';
import AdminRequest from '@/pages/_admin/Request.vue';


import api from '@/api';
import store from '@/store/Auth-store.js';
async function checkToken() {
    try {
        const response = await api.post('/auth/refresh');
        store.commit('login', response.data.data);
        localStorage.setItem('token', response.data.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.data.user));
        localStorage.setItem('team', JSON.stringify(response.data.data.team));
        return true
    } catch (error) {
        if (error.status === 401) {
            store.dispatch('logout');
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('team');
        }
        return false 
    }

} 
async function requireAuth(to, from, next) {

    if (await checkToken()) {
        next();
    } else {
        next('/signIn');
    }
}

const routes = [
    
    // Main Routes
    {
        path: '/',
        name: 'home',
        component: Home

    },
    {
        path: '/signIn',
        name: 'signIn',
        component: SignIn
    },
    {
        path: '/signUp',
        name: 'signUp',
        component: SignUp
    },
    {
        path: '/events',
        name: 'events',
        component: Events
    },
    {
        path: '/teams',
        name: 'teams',
        component: Teams
    },

    // Profile Routes /profile/
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        beforeEnter: requireAuth
    },
    {
        path: '/profile/upcoming',
        name: 'upcoming',
        component: UpcomingEvents,
        // beforeEnter: requireAuth
    },
    {
        path: '/profile/finished',
        name: 'finished',
        component: FinishedEvents,
        // beforeEnter: requireAuth
    },

    // Team Routes
    {
        path: '/teams/:teamId',
        name: 'team',
        component: Team
    },

    // Event Routes
    {
        path: '/events/:eventId',
        name: 'event',
        component: Event
    },

    // Admin Routes
    {
        path: '/_admin/',
        name: 'admin',
        component: AdminRequestsEvents,
    },
    {
        path: '/_admin/requests',
        name: 'admin-requests',
        component: AdminRequestsEvents,
    },
    {
        path: '/_admin/users',
        name: 'admin-users',
        component: AdminUsers
    },
    {
        path: '/_admin/teams',
        name: 'admin-teams',
        component: AdminTeams
    },
    {
        path: '/_admin/tags',
        name: 'admin-tags',
        component: AdminTags
    },
    {
        path: '/_admin/organizers',
        name: 'admin-organizers',
        component: AdminOrganizers
    },
    {
        path: '/_admin/users/:userId',
        name: 'admin-user',
        component: AdminUser
    },
    {
        path: '/_admin/requests/:requestId',
        name: 'admin-request',
        component: AdminRequest
    },
    {
        path: '/_admin/organizers/:userId',
        name: 'admin-organizer',
        component: AdminUser
    },
    {
        path: '/_admin/teams/:teamId',
        name: 'admin-team',
        component: AdminTeam
    },

];

export default createRouter({
    history: createWebHistory(),
    routes
})