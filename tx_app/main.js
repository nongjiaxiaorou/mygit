import Vue from 'vue'
import App from './App'
import store from './store'


Vue.config.productionTip = false // 为false时确保启用生产模式,为true时为开发模式

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
// Vue.prototype.api='http:/112.74.34.150:888/tx-admin/src/app'  //服务器IP
// Vue.prototype.imageUrl='http://112.74.34.150:888/tx-admin/src/images/app_pic'  //服务器IP
// Vue.prototype.imageUrl_pc='http://112.74.34.150:888/tx-admin/src/images/pc_pic' // 服务器
// Vue.prototype.api='http://192.168.3.12:8082/tx-admin/src/app'
// Vue.prototype.api='http://192.168.0.163:8082/tx-admin/src/app'
// Vue.prototype.iamgeUrl='http://192.168.0.178:8081/tx-admin/src/images/app_pic'

// Vue.prototype.imageUrl='http://192.168.3.12:8082/tx-admin/src/images/app_pic'
Vue.prototype.api='http://192.168.0.116:8081/tongxin/tx_admin/src/app'  // 602调试
Vue.prototype.imageUrl='http://192.168.0.116:8081/tongxin/tx_admin/src/images/app_pic' //602调试
Vue.prototype.imageUrl_pc='http://192.168.0.116:8081/tongxin/tx_admin/src/images/pc_pic' // 602调试
// Vue.prototype.api='http://192.168.1.110:8081/tongxin/tx_admin/src/app'  // 
// Vue.prototype.imageUrl='http://192.168.1.110:8081/tongxin/tx_admin/src/images/app_pic' //
// Vue.prototype.imageUrl_pc='http://192.168.1.110:8081/tongxin/tx_admin/src/images/pc_pic' // 
// Vue.prototype.api='http://172.19.220.5:8081/tongxin/tx_admin/src/app'  // 宿舍调试
// Vue.prototype.imageUrl='http://172.19.220.5:8081/tongxin/tx_admin/src/images/app_pic' //宿舍调试
// Vue.prototype.imageUrl_pc='http://172.19.220.5:8081/tongxin/tx_admin/src/images/pc_pic' //宿舍调试
// Vue.prototype.imageUrl='http://192.168.0.163:8082/tx-admin/src/images/app_pic'

// Vue.prototype.api='http://112.74.34.150:888/tx-admin/src/app'
Vue.component('divider',divider)
App.mpType = 'app'

const app = new Vue({
	store,
    ...App
})
app.$mount()
// console.log(app.$el)
// console.log(app.$data)
// console.log(app)
 