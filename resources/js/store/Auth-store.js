import { createStore } from "vuex";

export default createStore({
    state: {
        isLoggedIn: !!localStorage.getItem('token'),
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
    },
    mutations: {
        login(state, data) {
            state.isLoggedIn = true;
            state.token = data.token;
            state.user = data.user;
        },
        logout(state) {
            state.isLoggedIn = false;
            state.token = null;
            state.user = null;
        }

    },

    getters: {
        isLoggedIn(state) {
            return state.isLoggedIn
        },

        getUser(state) {
            return state.user
        },

        getToken(state) {
            return state.token
        },

        isAdmin(state) {
            return state.user?.isAdmin;
        },

        isUser(state) {
            return state.user?.isUser;
        },

        isOrganizer(state) {
            return state.user?.isOrganizer;
        }
    },
    actions: {
        login({ commit }, data) {
            commit('login', data);
        },
        logout({ commit }) {
            commit('logout');


        },
    }
})