import { createStore } from "vuex";

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
                "client_id": "1",
                "client_secret": "axUKRRXG4HHX7ayICF205sziRbinvu2X727046jw",
                ...body,
                "scope": "*"
            }
            return await axios.post('/oauth/token',requestBody).then(({data})=>{
                commit('SET_TOKEN',data)
                commit('SET_AUTHENTICATED',true)
                // router.push({name:'dashboard'})
            }).catch((data)=>{
                commit('SET_TOKEN',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        logout({commit}){
            commit('SET_USER',{})
            commit('SET_AUTHENTICATED',false)
        }
    }
})
export default store
