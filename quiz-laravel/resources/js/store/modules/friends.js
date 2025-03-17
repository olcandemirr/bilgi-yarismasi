import axios from 'axios';

const state = {
    friends: [],
    pendingRequests: [],
    sentRequests: [],
    loading: false,
    error: null
};

const getters = {
    allFriends: state => state.friends,
    pendingRequests: state => state.pendingRequests,
    sentRequests: state => state.sentRequests,
    loading: state => state.loading,
    error: state => state.error
};

const actions = {
    async fetchFriends({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.get('/api/friends');
            commit('setFriends', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch friends');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async fetchPendingRequests({ commit }) {
        commit('setLoading', true);
        
        try {
            const response = await axios.get('/api/friends/pending');
            commit('setPendingRequests', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch pending requests');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async fetchSentRequests({ commit }) {
        commit('setLoading', true);
        
        try {
            const response = await axios.get('/api/friends/sent');
            commit('setSentRequests', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch sent requests');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async sendFriendRequest({ commit }, userId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post(`/api/friends/request/${userId}`);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to send friend request');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async acceptFriendRequest({ commit, dispatch }, userId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post(`/api/friends/accept/${userId}`);
            // Refresh friends and pending requests lists
            dispatch('fetchFriends');
            dispatch('fetchPendingRequests');
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to accept friend request');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async rejectFriendRequest({ commit, dispatch }, userId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post(`/api/friends/reject/${userId}`);
            // Refresh pending requests list
            dispatch('fetchPendingRequests');
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to reject friend request');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async removeFriend({ commit, dispatch }, userId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.delete(`/api/friends/${userId}`);
            // Refresh friends list
            dispatch('fetchFriends');
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to remove friend');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async searchUsers({ commit }, query) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.get(`/api/users/search?q=${query}`);
            return response.data;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to search users');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    }
};

const mutations = {
    setFriends(state, friends) {
        state.friends = friends;
    },
    setPendingRequests(state, requests) {
        state.pendingRequests = requests;
    },
    setSentRequests(state, requests) {
        state.sentRequests = requests;
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