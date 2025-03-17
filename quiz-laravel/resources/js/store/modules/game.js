import axios from 'axios';

const state = {
    categories: [],
    questions: [],
    currentQuestion: null,
    currentQuestionIndex: 0,
    score: 0,
    gameMode: null, // 'individual' or 'team'
    playerInfo: null,
    gameInvites: [],
    sentInvites: [],
    gameState: 'idle', // 'idle', 'category-selection', 'playing', 'finished'
    timeRemaining: 0,
    timerId: null,
    loading: false,
    error: null
};

const getters = {
    categories: state => state.categories,
    questions: state => state.questions,
    currentQuestion: state => state.currentQuestion,
    currentQuestionIndex: state => state.currentQuestionIndex,
    score: state => state.score,
    gameMode: state => state.gameMode,
    playerInfo: state => state.playerInfo,
    gameInvites: state => state.gameInvites,
    sentInvites: state => state.sentInvites,
    gameState: state => state.gameState,
    timeRemaining: state => state.timeRemaining,
    loading: state => state.loading,
    error: state => state.error,
    totalQuestions: state => state.questions.length,
    progress: state => ((state.currentQuestionIndex + 1) / state.questions.length) * 100,
    isGameActive: state => state.gameState === 'playing',
    isGameFinished: state => state.gameState === 'finished'
};

const actions = {
    async fetchCategories({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.get('/api/categories');
            commit('setCategories', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch categories');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async startGame({ commit, dispatch }, { gameMode, playerInfo, categories }) {
        commit('setLoading', true);
        commit('setError', null);
        commit('setGameMode', gameMode);
        commit('setPlayerInfo', playerInfo);
        
        try {
            // Fetch questions based on selected categories
            let url = '/api/questions';
            if (categories && categories.length > 0) {
                url += `?categories=${categories.join(',')}`;
            }
            
            const response = await axios.get(url);
            commit('setQuestions', response.data);
            commit('setCurrentQuestionIndex', 0);
            commit('setCurrentQuestion', response.data[0]);
            commit('setScore', 0);
            commit('setGameState', 'playing');
            
            // Start timer for first question
            dispatch('startTimer', response.data[0].time);
            
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to start game');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    submitAnswer({ commit, dispatch, state }, answerIndex) {
        // Stop the timer
        if (state.timerId) {
            clearInterval(state.timerId);
            commit('setTimerId', null);
        }
        
        const isCorrect = state.currentQuestion.correct_answer === answerIndex;
        let pointsEarned = 0;
        
        if (isCorrect) {
            // Calculate points based on difficulty and time remaining
            const basePoints = {
                'easy': 10,
                'medium': 20,
                'hard': 30
            }[state.currentQuestion.difficulty] || 10;
            
            // Add time bonus: up to double points for answering quickly
            const timeBonus = (state.timeRemaining / state.currentQuestion.time) * basePoints;
            pointsEarned = Math.round(basePoints + timeBonus);
            
            commit('incrementScore', pointsEarned);
        }
        
        // Move to next question after a delay
        setTimeout(() => {
            if (state.currentQuestionIndex < state.questions.length - 1) {
                const nextIndex = state.currentQuestionIndex + 1;
                commit('setCurrentQuestionIndex', nextIndex);
                commit('setCurrentQuestion', state.questions[nextIndex]);
                
                // Start timer for next question
                dispatch('startTimer', state.questions[nextIndex].time);
            } else {
                // Game is finished
                commit('setGameState', 'finished');
                
                // Save result if authenticated
                if (state.gameMode) {
                    dispatch('saveGameResult');
                }
            }
        }, 2000); // 2 second delay to show result
        
        return {
            isCorrect,
            pointsEarned
        };
    },
    
    startTimer({ commit, state, dispatch }, seconds) {
        // Clear existing timer if any
        if (state.timerId) {
            clearInterval(state.timerId);
        }
        
        // Set initial time
        commit('setTimeRemaining', seconds);
        
        // Create new timer
        const timerId = setInterval(() => {
            if (state.timeRemaining <= 0) {
                // Time's up, move to next question
                clearInterval(timerId);
                commit('setTimerId', null);
                dispatch('submitAnswer', -1); // -1 indicates no answer
            } else {
                commit('decrementTimeRemaining');
            }
        }, 1000);
        
        commit('setTimerId', timerId);
    },
    
    async saveGameResult({ state }) {
        if (!state.playerInfo) return;
        
        try {
            const resultData = {
                team_name: state.gameMode === 'team' ? state.playerInfo.teamName : state.playerInfo.name,
                participants: state.gameMode === 'team' ? state.playerInfo.participants : 1,
                score: state.score,
                game_mode: state.gameMode
            };
            
            await axios.post('/api/results', resultData);
        } catch (error) {
            console.error('Failed to save game result', error);
        }
    },
    
    restartGame({ commit, dispatch }) {
        commit('setCurrentQuestionIndex', 0);
        commit('setScore', 0);
        commit('setGameState', 'category-selection');
        
        // Clear timer if active
        if (state.timerId) {
            clearInterval(state.timerId);
            commit('setTimerId', null);
        }
    },
    
    async fetchGameInvites({ commit }) {
        commit('setLoading', true);
        
        try {
            const response = await axios.get('/api/game-invites');
            commit('setGameInvites', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch game invites');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async fetchSentInvites({ commit }) {
        commit('setLoading', true);
        
        try {
            const response = await axios.get('/api/game-invites/sent');
            commit('setSentInvites', response.data);
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to fetch sent invites');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async sendGameInvite({ commit, dispatch }, { userId, categories }) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post('/api/game-invites', {
                user_id: userId,
                categories: categories
            });
            
            // Refresh sent invites list
            dispatch('fetchSentInvites');
            
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to send game invite');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async acceptGameInvite({ commit, dispatch }, inviteId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post(`/api/game-invites/${inviteId}/accept`);
            
            // Refresh game invites list
            dispatch('fetchGameInvites');
            
            // Start game with the categories from the invite
            if (response.data.categories) {
                dispatch('startGame', {
                    gameMode: 'individual',
                    playerInfo: { name: 'Player' }, // This would be replaced with actual user info
                    categories: response.data.categories
                });
            }
            
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to accept game invite');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    },
    
    async rejectGameInvite({ commit, dispatch }, inviteId) {
        commit('setLoading', true);
        commit('setError', null);
        
        try {
            const response = await axios.post(`/api/game-invites/${inviteId}/reject`);
            
            // Refresh game invites list
            dispatch('fetchGameInvites');
            
            return response;
        } catch (error) {
            commit('setError', error.response?.data?.message || 'Failed to reject game invite');
            throw error;
        } finally {
            commit('setLoading', false);
        }
    }
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories;
    },
    setQuestions(state, questions) {
        state.questions = questions;
    },
    setCurrentQuestion(state, question) {
        state.currentQuestion = question;
    },
    setCurrentQuestionIndex(state, index) {
        state.currentQuestionIndex = index;
    },
    setScore(state, score) {
        state.score = score;
    },
    incrementScore(state, points) {
        state.score += points;
    },
    setGameMode(state, mode) {
        state.gameMode = mode;
    },
    setPlayerInfo(state, info) {
        state.playerInfo = info;
    },
    setGameInvites(state, invites) {
        state.gameInvites = invites;
    },
    setSentInvites(state, invites) {
        state.sentInvites = invites;
    },
    setGameState(state, gameState) {
        state.gameState = gameState;
    },
    setTimeRemaining(state, time) {
        state.timeRemaining = time;
    },
    decrementTimeRemaining(state) {
        state.timeRemaining--;
    },
    setTimerId(state, id) {
        state.timerId = id;
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