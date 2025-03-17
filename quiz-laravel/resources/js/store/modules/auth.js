import axios from 'axios';

const state = {
    user: null,
    authenticated: false,
    loading: false,
    error: null
};

const getters = {
    user: state => state.user,
    isAuthenticated: state => state.authenticated,
    loading: state => state.loading,
    error: state => state.error
};

const actions = {
    async register({ commit }, userData) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.post('/api/register', userData);
            commit('setUser', response.data.user);
            commit('setAuthenticated', true);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Registration failed');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async login({ commit }, credentials) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.post('/api/login', credentials);
            commit('setUser', response.data.user);
            commit('setAuthenticated', true);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Login failed');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async logout({ commit }) {
        commit('setLoading', true);
        
        try {
            await axios.post('/api/logout');
            commit('setUser', null);
            commit('setAuthenticated', false);
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Logout failed');
        } finally {
            commit('setLoading', false);
        }
    },
    
    async fetchUser({ commit }) {
        commit('setLoading', true);
        
        try {
            const response = await axios.get('/api/user');
            commit('setUser', response.data);
            commit('setAuthenticated', true);
            return response;
        } catch (error) {
            commit('setUser', null);
            commit('setAuthenticated', false);
            commit('setError', error.response?.data?.message || 'Failed to fetch user');
        } finally {
            commit('setLoading', false);
        }
    },
    
    async updateProfile({ commit }, userData) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post('/api/user/profile', userData);
            commit('setUser', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to update profile');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    }
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setAuthenticated(state, value) {
        state.authenticated = value;
    },
    setLoading(state, value) {
        state.loading = value;
    },
    setError(state, error) {
        state.error = error;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}; 