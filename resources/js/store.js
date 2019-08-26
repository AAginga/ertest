import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'
Vue.use(Vuex);

axios.defaults.baseURL = 'http://ertest.test/api'

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem('token') || '',
      },
      mutations: {
        auth_request(state){
            state.status = 'loading'
        },
        auth_success(state, token){
            state.status = 'success'
            state.token = token
        },
        auth_error(state){
            state.status = 'error'
        },
        logout(state){
            state.status = ''
            state.token = ''
        },
    
      },
      actions: {
        login({commit}, data){
            return new Promise((resolve, reject) => {
              axios.post('/login', data)
                .then(response => {
                  const token = response.data.access_token     
                  localStorage.setItem('token', token)
                  commit('auth_success', token)
                  resolve(response)
                })
                .catch(error => {
                    console.log(error)
                    commit('auth_error')
                    localStorage.removeItem('token')               
                    reject(error)
                })
              })
          },  
        register({commit}, data){
            return new Promise((resolve, reject) => {
              commit('auth_request')
              axios.post('/register', data)
              .then(resp => {
                resolve(resp)
              })
              .catch(err => {
                commit('auth_error', err)
                reject(err)
              })
            })
        },
        logout(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token     
            if (context.getters.isLoggedIn) {
              return new Promise((resolve, reject) => {
                axios.post('/logout')
                  .then(response => {
                    localStorage.removeItem('token')
                    context.commit('logout')
                    resolve(response)
                  })
                  .catch(error => {
                    localStorage.removeItem('token')
                    context.commit('logout')
                    reject(error)
                  })
              })
            }
          },   
      },
      getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
    
      }

});