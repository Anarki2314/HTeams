import { createStore } from "vuex";

export default createStore({
    state: {
        isLoggedIn: !!localStorage.getItem('token'),
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
        message: {
            type: '',
            text: '',
        },
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
        },
        
        setMessage(state, message) {
            state.message = message;
        }

    },

    getters: {
        isLoggedIn(state) {
            return state.isLoggedIn;
        },

        getUser(state) {
            return state.user;
        },

        getUserRole(state) {
            return state.user.role;
        },

        getMessage(state) {
            return state.message;
        },
        getToken(state) {
            return state.token;
        },
        haveTeam(state) {
        return state.user.haveTeam;
        },
        
        isAdmin(state) {
            return state.user?.isAdmin;
        },

        isUser(state) {
            return state.user?.isUser;
        },

        isOrganizer(state) {
            return state.user?.isOrganizer;
        },

        isLeader(state) {
            return state.user?.isLeader;
        }
    },
    actions: {
        login({ commit }, data) {
            commit('login', data);
        },
        logout({ commit }) {
            commit('logout');
        },

        setMessage({ commit }, message) {
            commit('setMessage', message);
        }
    }
})