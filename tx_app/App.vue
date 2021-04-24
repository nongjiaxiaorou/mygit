<script>
import { mapMutations } from 'vuex';
export default {
	onLaunch: function() {
		uni.getStorage({
			key: 'userInfo',
			success: res => {
				this.login(res.data);
				
				uni.getStorage({
					key: 'autologin',
					success: res => {
						uni.reLaunch({
							url: '/pages/index/index',
							success: () => {
								//跳转完页面后再关闭启动页
								plus.navigator.closeSplashscreen();
							}
						});
					},
					fail: error => {
						plus.navigator.closeSplashscreen();
					}
				});
				
			},
			fail: error => {
				plus.navigator.closeSplashscreen();
			}
		});
	},
	methods: {
		...mapMutations(['login'])
	},
	onShow: function() {
		console.log('App Show');
	},
	onHide: function() {
		console.log('App Hide');
	}
};
</script>

<style>
/*每个页面公共css */
/* 官方css库 */
@import './common/uni.css';
/* 自定义图标库 */
/* @import "./common/icon/icon.css"; */
@import './static/icon/icon.css';
/* 动画库 */
@import './common/animate.css';
/* 自定义样式库（适合所有项目） */
@import './common/free.css';
/* 全局样式 */
@import './common/common.css';
</style>
