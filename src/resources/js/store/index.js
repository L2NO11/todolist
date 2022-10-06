import { createStore } from "vuex";
import {AES, enc}from 'crypto-js';
import { useRouter } from "vue-router";
const store = createStore({
    state:{
        authenticated:false,
        token:{}
    },
    getters:{
        authenticated(state){
            return state.authenticated
        },
        token(state){
            return state.token
        }
    },
    mutations:{
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
                commit('SET_TOKEN',data)
                commit('SET_AUTHENTICATED',true)
                const envryptedString = AES.encrypt(JSON.stringify(data),process.env.MIX_MY_STORAGE_KEY);
                localStorage.setItem('token',envryptedString)
            }).catch((data)=>{
                commit('SET_TOKEN',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        checklogined({commit}){
            const router = useRouter();
            const token = localStorage.getItem("token");
            if (token) {
                const decrypted = AES.decrypt(token, process.env.MIX_MY_STORAGE_KEY).toString(enc.Utf8);
                commit('SET_TOKEN',JSON.parse(decrypted))
                commit('SET_AUTHENTICATED',true)
                router.push({ name: "home" });
            }
        },
        logout({commit}){
            window.localStorage.clear();
            commit('SET_TOKEN',{})
            commit('SET_AUTHENTICATED',false)
        }
    }
})
export default store
