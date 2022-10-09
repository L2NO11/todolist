import { createStore } from "vuex";
import {AES, enc}from 'crypto-js';
const store = createStore({
    state:{
        todolist: [],
        authenticated:false,
        token:{}
    },
    getters:{
        todolist(state){
            return state.todolist
        },
        authenticated(state){
            return state.authenticated
        },
        token(state){
            return state.token
        }
    },
    mutations:{
        SET_TODOLIST (state, value) {
            state.todolist = value
        },
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_TOKEN (state, value) {
            state.token = value
        }
    },
    actions: {
        async login({commit},body){
            const requestBody = {
                "grant_type": "password",
                "client_id": process.env.MIX_CLIENT_ID,
                "client_secret": process.env.MIX_CLIENT_SECRET,
                ...body,
                "scope": "*"
            }
            return await axios.post('/oauth/token',requestBody).then(({data})=>{
                data.start = new Date
                commit('SET_TOKEN',data)
                commit('SET_AUTHENTICATED',true)
                const envryptedString = AES.encrypt(JSON.stringify(data),process.env.MIX_MY_STORAGE_KEY);
                localStorage.setItem('token',envryptedString)
            }).catch((data)=>{
                commit('SET_TOKEN',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        init({commit,getters}){
            const token = localStorage.getItem("token");
            if (token) {
                const decrypted = JSON.parse(AES.decrypt(token, process.env.MIX_MY_STORAGE_KEY).toString(enc.Utf8));
                const lifetime = (new Date() - new Date(decrypted.start)) / 1000
                const {expires_in} = decrypted
                if(expires_in > lifetime ){
                    commit('SET_TOKEN',decrypted)
                    commit('SET_AUTHENTICATED',true)
                    console.log("init success");
                }else{
                    localStorage.clear();
                }
            }
        },
        logout({commit}){
            localStorage.clear();
            commit('SET_TOKEN',{})
            commit('SET_AUTHENTICATED',false)
            console.log("logout success");
        }
    }
})
export default store
