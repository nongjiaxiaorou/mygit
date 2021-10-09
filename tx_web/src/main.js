import Vue from 'vue';
import App from './App.vue';
import router from './router';
import ElementUI from 'element-ui';
import VueI18n from 'vue-i18n';
import axios from 'axios';
import { messages } from './components/common/i18n';
import 'element-ui/lib/theme-chalk/index.css'; // 默认主题
// import './assets/css/theme-green/index.css'; // 浅绿色主题
import './assets/css/icon.css';
import './assets/css/common.css';
import './components/common/directives';
import 'babel-polyfill';

Vue.prototype.$EventBus = new Vue()
//引入moment.js
import moment from 'moment';
Vue.prototype.$moment = moment //赋值使用 就是调用一下
moment.locale('zh-cn') // 这里是进行了汉化

//全局引入公共后端地址
import adminUrl from './api/index.js'
Vue.prototype.$adminUrl = adminUrl.commonUrl
Vue.prototype.$adminImgUrl = adminUrl.commonImgUrl

//注册打印
import Print from './print'
Vue.use(Print)

//全局引入公共方法
import commonFunc from '../src/utils/utils'
Vue.prototype.commonFunc = commonFunc;

Vue.config.productionTip = false;
Vue.use(VueI18n);
Vue.use(ElementUI, {
    size: 'small'
});
const i18n = new VueI18n({
    locale: 'zh',
    messages
});
console.log('当前环境：', process.env.NODE_ENV);

Vue.prototype.$axios = axios;//全局引入axios

//使用钩子函数对路由进行权限跳转
router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} | 同欣质量管理系统`;
    const role = localStorage.getItem('ms_username');
	const privilegeAccount = localStorage.getItem('privilegeAccount');
	const authority = localStorage.getItem('authority');
    if (!role && to.path !== '/login') {
        next('/login');
    } else if (to.meta.permission) {
		console.log(privilegeAccount)
        // 如果是管理员权限则可进入，这里只是简单的模拟管理员权限而已
        // role === 'admin' || privilegeAccount === 1 ? next() : next('/403');
		if( role === 'admin' || privilegeAccount === '1' ){
			next()
		}else if(privilegeAccount !== '1'){
			if(authority.indexOf('用户管理')>=0){
				next()
			}else{
				next('/403')
			}
			if(authority.indexOf('公司集团定义')>=0){
				next()
			}else{
				next('/403')
			}
			if(authority.indexOf('违章数据库管理')>=0){
				next()
			}else{
				next('/403')
			}
			if(authority.indexOf('实测实量标准')>=0){
				next()
			}else{
				next('/403')
			}
			if(authority.indexOf('工程类别定义')>=0){
				next()
			}else{
				next('/403')
			}
			if(authority.indexOf('系统操作日志')>=0){
				next()
			}else{
				next('/403')
			}
		}else{
			 next('/403')
		}
    }  else {
        // 简单的判断IE10及以下不进入富文本编辑器，该组件不兼容
        if (navigator.userAgent.indexOf('MSIE') > -1 && to.path === '/editor') {
            Vue.prototype.$alert('vue-quill-editor组件不兼容IE10及以下浏览器，请使用更高版本的浏览器查看', '浏览器不兼容通知', {
                confirmButtonText: '确定'
            });
        } else {
            next();
        }
    }
});

new Vue({
    router,
    i18n,
    render: h => h(App)
}).$mount('#app');
