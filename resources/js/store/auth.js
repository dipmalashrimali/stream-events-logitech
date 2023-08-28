import axiosInstance from './axiosInstance';
const  namespaced = true;
const state = {
    authenticated:false,
        user:{}
};
const getters = {
    authenticated(state){
        return state.authenticated
    },
    user(state){
        return state.user
    }
};
const mutations = {
    SET_AUTHENTICATED (state, value) {
        state.authenticated = value
    },
    SET_USER (state, value) {
            state.user = value
    }
};
const actions = {
    login({commit}){
        return axiosInstance.get('/api/user')
            .then(({data})=>{

                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)

            }).catch(({response:{data}})=>{
                commit('SET_USER',{})
                commit('SET_AUTHENTICATED',false)
            })

    },
    logout({commit}){
        commit('SET_USER',{})
        commit('SET_AUTHENTICATED',false)
        localStorage.removeItem('token');
        window.location.href = '/facebook/logout';
    }
};
export default {
    namespaced,
    state,
    getters,
    actions,
    mutations
};
