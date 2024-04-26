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
function requireAuth(to, from, next) {
    if (localStorage.getItem('token')) {
        next();
    } else {
        next('/signIn');
    }
}

const routes = [
    
    // Main Routes
    {
        path: '/',
        component: Home

    },
    {
        path: '/signIn',
        component: SignIn
    },
    {
        path: '/signUp',
        component: SignUp
    },
    {
        path: '/events',
        component: Events
    },
    {
        path: '/teams',
        component: Teams
    },

    // Profile Routes /profile/
    {
        path: '/profile',
        component: Profile,
        // beforeEnter: requireAuth
    },
    {
        path: '/profile/upcoming',
        component: UpcomingEvents,
        // beforeEnter: requireAuth
    },
    {
        path: '/profile/finished',
        component: FinishedEvents,
        // beforeEnter: requireAuth
    },

    // Team Routes
    {
        path: '/teams/:teamId',
        component: Team
    },

    // Event Routes
    {
        path: '/events/:eventId',
        component: Event
    },

    // Admin Routes
    {
        path: '/_admin/',
        component: AdminRequestsEvents,
    },
    {
        path: '/_admin/users',
        component: AdminUsers
    },
    {
        path: '/_admin/teams',
        component: AdminTeams
    },
    {
        path: '/_admin/tags',
        component: AdminTags
    },
    {
        path: '/_admin/organizers',
        component: AdminOrganizers
    },
    {
        path: '/_admin/users/:userId',
        component: AdminUser
    },
    {
        path: '/_admin/organizers/:userId',
        component: AdminUser
    },
    {
        path: '/_admin/teams/:teamId',
        component: AdminTeam
    },

];

export default createRouter({
    history: createWebHistory(),
    routes
})