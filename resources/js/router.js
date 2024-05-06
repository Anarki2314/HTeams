import { createRouter, createWebHistory } from "vue-router";

// Main Pages
import Home from "@/pages/Home.vue";
import SignIn from "@/pages/SignIn.vue";
import SignUp from "@/pages/SignUp.vue";
import Events from "@/pages/Events.vue";
import Teams from "@/pages/Teams.vue";

// Profile Pages
import Profile from "@/pages/profile/Profile.vue";
import ProfileTeam from "@/pages/profile/Team.vue";
import ProfileTeamInvites from "@/pages/profile/Invites.vue";
import UpcomingEvents from "@/pages/profile/UpcomingEvents.vue";
import FinishedEvents from "@/pages/profile/FinishedEvents.vue";

// Team view pages
import Team from "@/pages/teams/Team.vue";

// Event view pages
import Event from "@/pages/events/Event.vue";

// Admin Pages
import AdminRequestsEvents from "@/pages/_admin/RequestsEvents.vue";
import AdminUsers from "@/pages/_admin/Users.vue";
import AdminTeams from "@/pages/_admin/Teams.vue";
import AdminTags from "@/pages/_admin/Tags.vue";
import AdminOrganizers from "@/pages/_admin/Organizers.vue";
import AdminUser from "@/pages/_admin/User.vue";
import AdminTeam from "@/pages/_admin/Team.vue";
import AdminRequest from "@/pages/_admin/Request.vue";

// 404 Page
import NotFound from "@/pages/NotFound.vue";

import api from "@/api";
import store from "@/store/Auth-store.js";
import { push } from "notivue";
async function checkToken() {
    if (!store.getters.getToken){
        push.error("Войдите в аккаунт");
        return false;
    }
    try {
        const response = await api.post("/auth/refresh");
        store.commit("login", response.data.data);
        localStorage.setItem("token", response.data.data.token);
        localStorage.setItem("user", JSON.stringify(response.data.data.user));
        localStorage.setItem("team", JSON.stringify(response.data.data.team));
        return true;
    } catch (error) {
        if (error.status === 401) {
            push.error("Сессия истекла. Пожалуйста, войдите в систему еще раз");
            store.dispatch("logout");
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            localStorage.removeItem("team");
        }
        return false;
    }
}

function getCurrentUserRole() {
    const $userRole = store.getters.getUserRole;
    return $userRole;
}


const routes = [
    // Main Routes
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/signIn",
        name: "signIn",
        component: SignIn,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/signUp",
        name: "signUp",
        component: SignUp,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/events",
        name: "events",
        component: Events,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/teams",
        name: "teams",
        component: Teams,
        meta: {
            requiresAuth: false,
        },
    },

    // Profile Routes /profile/
    {
        path: "/profile",
        name: "profile",
        component: Profile,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/profile/team",
        name: "profile-team",
        component: ProfileTeam,
        meta: {
            requiresAuth: true,
            role: "Пользователь",
        },
    },
    {
        path: "/profile/team/invites",
        name: "profile-team-invites",
        component: ProfileTeamInvites,
        meta: {
            requiresAuth: true,
            role: "Пользователь",
        },
    },
    {
        path: "/profile/upcoming",
        name: "upcoming",
        component: UpcomingEvents,
        meta: {
            requiresAuth: true,            
        }
        // beforeEnter: requireAuth
    },
    {
        path: "/profile/finished",
        name: "finished",
        component: FinishedEvents,
        meta: {
            requiresAuth: true,
        }
        // beforeEnter: requireAuth
    },

    // Team Routes
    {
        path: "/teams/:teamId",
        name: "team",
        component: Team,
    },

    // Event Routes
    {
        path: "/events/:eventId",
        name: "event",
        component: Event,
    },

    // Admin Routes
    {
        path: "/_admin/",
        name: "admin",
        component: AdminRequestsEvents,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/requests",
        name: "admin-requests",
        component: AdminRequestsEvents,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/users",
        name: "admin-users",
        component: AdminUsers,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/teams",
        name: "admin-teams",
        component: AdminTeams,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/tags",
        name: "admin-tags",
        component: AdminTags,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    
    },
    {
        path: "/_admin/organizers",
        name: "admin-organizers",
        component: AdminOrganizers,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/users/:userId",
        name: "admin-user",
        component: AdminUser,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/requests/:requestId",
        name: "admin-request",
        component: AdminRequest,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/organizers/:userId",
        name: "admin-organizer",
        component: AdminUser,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },
    {
        path: "/_admin/teams/:teamId",
        name: "admin-team",
        component: AdminTeam,
        meta: {
            requiresAuth: true,
            role: "Админ",
        },
    },

    // 404
    {
        path: "/:pathMatch(.*)*",
        name: "404",
        component: NotFound,
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach( async (to, from, next) => {
    if (to.meta.requiresAuth && !await checkToken()) {
        next("/signIn");
    } else if (to.meta.role && getCurrentUserRole() !== to.meta.role) {
        push.error("У вас нет доступа к этой странице");
        next("/");
    } else {
        next();
    }
});

export default router;
