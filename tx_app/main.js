import Vue from 'vue'
import App from './App'
import store from './store'

Vue.config.productionTip = false

//全局引入网络请求封装
import myRequest from './utils/api.js'
Vue.prototype.myRequest = myRequest;


//全局引入公共方法
import commonFunc from './utils/utils'
Vue.prototype.commonFunc = commonFunc;

//引入-样式
// import './common/icon/icon.css';
// import './static/icon/icon.css';

//引入全局组件
import divider from './components/common/divider.vue'
Vue.prototype.$store = store
// Vue.prototype.api='http://192.168.3.12:8082/tx-admin/src/app'
Vue.prototype.api='http://192.168.0.115:8081/tx_admin/src/app'
// Vue.prototype.api='http:/112.74.34.150:888/tx-admin/src/app'
// Vue.prototype.api='http://192.168.0.163:8082/tx-admin/src/app'
// Vue.prototype.iamgeUrl='http://192.168.0.178:8081/tx-admin/src/images/app_pic'

// Vue.prototype.imageUrl='http://192.168.3.12:8082/tx-admin/src/images/app_pic'
Vue.prototype.imageUrl='http://192.168.0.115:8081/tx_admin/src/images/app_pic'
// Vue.prototype.imageUrl='http://192.168.0.163:8082/tx-admin/src/images/app_pic'
// Vue.prototype.api='http://112.74.34.150:888/tx-admin/src/app'
Vue.component('divider',divider)
App.mpType = 'app'

const app = new Vue({
	store,
    ...App
})
app.$mount()
 