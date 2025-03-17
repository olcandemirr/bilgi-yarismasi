import { createStore } from 'vuex';
import axios from 'axios';

// Auth module
const auth = {
    namespaced: true,
    state: () => ({
        user: null,
        loading: false,
        error: null
    }),
    getters: {
        isAuthenticated: (state) => !!state.user,
        currentUser: (state) => state.user,
        isLoading: (state) => state.loading,
        authError: (state) => state.error
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setLoading(state, loading) {
            state.loading = loading;
        },
        setError(state, error) {
            state.error = error;
        },
        clearUser(state) {
            state.user = null;
        }
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                commit('setLoading', true);
                commit('setError', null);
                
                // Get CSRF cookie
                await axios.get('/sanctum/csrf-cookie');
                
                // Login request
                const response = await axios.post('/api/login', credentials);
                
                // Set user data
                commit('setUser', response.data.user);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Login failed');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async register({ commit }, userData) {
            try {
                commit('setLoading', true);
                commit('setError', null);
                
                // Get CSRF cookie
                await axios.get('/sanctum/csrf-cookie');
                
                // Register request
                const response = await axios.post('/api/register', userData);
                
                // Set user data
                commit('setUser', response.data.user);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Registration failed');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async logout({ commit }) {
            try {
                commit('setLoading', true);
                
                // Logout request
                await axios.post('/api/logout');
                
                // Clear user data
                commit('clearUser');
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Logout failed');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchUser({ commit }) {
            try {
                commit('setLoading', true);
                
                // Get user data
                const response = await axios.get('/api/user');
                
                // Set user data
                commit('setUser', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch user data');
                // User is not authenticated, don't throw an error here
                commit('clearUser');
            } finally {
                commit('setLoading', false);
            }
        },
        
        async updateProfile({ commit }, userData) {
            try {
                commit('setLoading', true);
                
                // Update profile request
                const response = await axios.put('/api/user/profile', userData);
                
                // Update user data
                commit('setUser', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to update profile');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async updatePassword({ commit }, passwordData) {
            try {
                commit('setLoading', true);
                
                // Update password request
                const response = await axios.put('/api/user/password', passwordData);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to update password');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        }
    }
};

// Friends module
const friends = {
    namespaced: true,
    state: () => ({
        friends: [],
        pendingRequests: [],
        gameInvites: [],
        loading: false,
        error: null
    }),
    getters: {
        allFriends: (state) => state.friends,
        pendingRequests: (state) => state.pendingRequests,
        gameInvites: (state) => state.gameInvites,
        isLoading: (state) => state.loading,
        friendsError: (state) => state.error
    },
    mutations: {
        setFriends(state, friends) {
            state.friends = friends;
        },
        setPendingRequests(state, requests) {
            state.pendingRequests = requests;
        },
        setGameInvites(state, invites) {
            state.gameInvites = invites;
        },
        setLoading(state, loading) {
            state.loading = loading;
        },
        setError(state, error) {
            state.error = error;
        }
    },
    actions: {
        async fetchFriends({ commit }) {
            try {
                commit('setLoading', true);
                
                // Get friends list
                const response = await axios.get('/api/friends');
                
                // Set friends
                commit('setFriends', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch friends');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchPendingRequests({ commit }) {
            try {
                commit('setLoading', true);
                
                // Get pending requests
                const response = await axios.get('/api/friends/requests');
                
                // Set pending requests
                commit('setPendingRequests', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch pending requests');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchGameInvites({ commit }) {
            try {
                commit('setLoading', true);
                
                // Get game invites
                const response = await axios.get('/api/game-invites');
                
                // Set game invites
                commit('setGameInvites', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch game invites');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async sendFriendRequest({ commit }, username) {
            try {
                commit('setLoading', true);
                
                // Send friend request
                const response = await axios.post('/api/friends/request', { username });
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to send friend request');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async acceptFriendRequest({ commit, dispatch }, requestId) {
            try {
                commit('setLoading', true);
                
                // Accept friend request
                const response = await axios.post(`/api/friends/accept/${requestId}`);
                
                // Refresh friends and pending requests
                await dispatch('fetchFriends');
                await dispatch('fetchPendingRequests');
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to accept friend request');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async rejectFriendRequest({ commit, dispatch }, requestId) {
            try {
                commit('setLoading', true);
                
                // Reject friend request
                const response = await axios.post(`/api/friends/reject/${requestId}`);
                
                // Refresh pending requests
                await dispatch('fetchPendingRequests');
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to reject friend request');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async removeFriend({ commit, dispatch }, friendId) {
            try {
                commit('setLoading', true);
                
                // Remove friend
                const response = await axios.delete(`/api/friends/${friendId}`);
                
                // Refresh friends
                await dispatch('fetchFriends');
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to remove friend');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async sendGameInvite({ commit }, friendId) {
            try {
                commit('setLoading', true);
                
                // Send game invite
                const response = await axios.post('/api/game-invites', { friend_id: friendId });
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to send game invite');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async acceptGameInvite({ commit, dispatch }, inviteId) {
            try {
                commit('setLoading', true);
                
                // Accept game invite
                const response = await axios.post(`/api/game-invites/accept/${inviteId}`);
                
                // Refresh game invites
                await dispatch('fetchGameInvites');
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to accept game invite');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async rejectGameInvite({ commit, dispatch }, inviteId) {
            try {
                commit('setLoading', true);
                
                // Reject game invite
                const response = await axios.post(`/api/game-invites/reject/${inviteId}`);
                
                // Refresh game invites
                await dispatch('fetchGameInvites');
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to reject game invite');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        }
    }
};

// Game module
const game = {
    namespaced: true,
    state: () => ({
        categories: [],
        gameState: null,
        currentQuestion: null,
        answers: [],
        score: 0,
        gameResults: null,
        loading: false,
        error: null
    }),
    getters: {
        allCategories: (state) => state.categories,
        currentGameState: (state) => state.gameState,
        currentQuestion: (state) => state.currentQuestion,
        userAnswers: (state) => state.answers,
        gameScore: (state) => state.score,
        results: (state) => state.gameResults,
        isLoading: (state) => state.loading,
        gameError: (state) => state.error,
        gameActive: (state) => !!state.gameState && state.gameState.status === 'active'
    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        },
        setGameState(state, gameState) {
            state.gameState = gameState;
        },
        setCurrentQuestion(state, question) {
            state.currentQuestion = question;
        },
        addAnswer(state, { questionId, answerId, isCorrect }) {
            state.answers.push({ questionId, answerId, isCorrect });
            if (isCorrect) {
                state.score += 10; // Basic scoring logic
            }
        },
        setGameResults(state, results) {
            state.gameResults = results;
        },
        setLoading(state, loading) {
            state.loading = loading;
        },
        setError(state, error) {
            state.error = error;
        },
        resetGame(state) {
            state.gameState = null;
            state.currentQuestion = null;
            state.answers = [];
            state.score = 0;
            state.gameResults = null;
        }
    },
    actions: {
        async fetchCategories({ commit }) {
            try {
                commit('setLoading', true);
                
                // Get categories
                const response = await axios.get('/api/categories');
                
                // Set categories
                commit('setCategories', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch categories');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async startGame({ commit }, { categories, gameMode, players }) {
            try {
                commit('setLoading', true);
                commit('resetGame');
                
                // Start new game
                const response = await axios.post('/api/games', {
                    categories,
                    game_mode: gameMode,
                    players
                });
                
                // Set game state
                commit('setGameState', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to start game');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchNextQuestion({ commit, state }) {
            try {
                commit('setLoading', true);
                
                if (!state.gameState) {
                    throw new Error('No active game');
                }
                
                // Get next question
                const response = await axios.get(`/api/games/${state.gameState.id}/question`);
                
                // Set current question
                commit('setCurrentQuestion', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch question');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async submitAnswer({ commit, state, dispatch }, { answerId }) {
            try {
                commit('setLoading', true);
                
                if (!state.gameState || !state.currentQuestion) {
                    throw new Error('No active game or question');
                }
                
                // Submit answer
                const response = await axios.post(`/api/games/${state.gameState.id}/answer`, {
                    question_id: state.currentQuestion.id,
                    answer_id: answerId
                });
                
                // Record the answer
                commit('addAnswer', {
                    questionId: state.currentQuestion.id,
                    answerId,
                    isCorrect: response.data.correct
                });
                
                // If game is finished, get results
                if (response.data.game_finished) {
                    await dispatch('fetchGameResults');
                }
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to submit answer');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchGameResults({ commit, state }) {
            try {
                commit('setLoading', true);
                
                if (!state.gameState) {
                    throw new Error('No active game');
                }
                
                // Get game results
                const response = await axios.get(`/api/games/${state.gameState.id}/results`);
                
                // Set game results
                commit('setGameResults', response.data);
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch game results');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        },
        
        async fetchLeaderboard({ commit }, { period = 'all', gameMode = 'individual' } = {}) {
            try {
                commit('setLoading', true);
                
                // Get leaderboard
                const response = await axios.get('/api/leaderboard', {
                    params: { period, game_mode: gameMode }
                });
                
                return response.data;
            } catch (error) {
                commit('setError', error.response?.data?.message || 'Failed to fetch leaderboard');
                throw error;
            } finally {
                commit('setLoading', false);
            }
        }
    }
};

export default createStore({
    modules: {
        auth,
        friends,
        game
    }
}); 