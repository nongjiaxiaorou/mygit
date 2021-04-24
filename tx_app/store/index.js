import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		userInfo: {},
		hasLogin: false
	},
	mutations: {
		login(state, provider) {
			// console.log(provider.account);
			state.hasLogin = true
			// state.userInfo.token = provider.token
			state.userInfo.userName = provider.account
			uni.setStorage({
				key: 'userInfo',
				data: provider
			})
		},
		logout(state) {
			state.hasLogin = false
			state.userInfo = {}
			uni.removeStorage({
				key: 'userInfo'
			});
		}
	}
})

export default store
