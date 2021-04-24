<template class="body-color">
	<view>
		<text class="uni-list-cell-pd" style="font-size: 24rpx;">账户信息</text>
		<view class="uni-list">
			<navigator class="uni-list-cell uni-list-cell-pd" url="Account_information">
				我的账户
				<text style="float: right;">></text>
			</navigator>
		</view>
		
		<text class="uni-list-cell-pd" style="font-size: 24rpx;">登录设置</text>
		<view class="uni-list">
			<view class="uni-list-cell uni-list-cell-pd">
				<view class="uni-list-cell-db">自动登录</view>
				<switch @change="login" :checked="autologin" />
			</view>
		</view>

		<text class="uni-list-cell-pd" style="font-size: 24rpx;">升级设置</text>
		<view class="uni-list">
			<view class="uni-list-cell uni-list-cell-pd">
				<view class="uni-list-cell-db">自动检查更新</view>
				<switch @change="Update" :checked="autoUpdate"  />
			</view>
			<view class="uni-list-cell uni-list-cell-pd" @click="update">检测新版本下载</view>
			<view class="uni-list-cell uni-list-cell-pd">当前版本信息：{{ version }}</view>
		</view>
		<view class="uni-list"><view style="height: 100rpx;background-color: #efeff4;"></view></view>
		<button @click="quit" style="background-color: #FFFFFF;">退出</button>
	</view>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
export default {
	computed: mapState(['hasLogin', 'userInfo']),
	data() {
		return {
			version: '2020.10.0108',
			cvz: '' ,//用于存放compareVersion函数的判断结果,比较版本大小，如果新版本nv大于旧版本ov则cvz=true，否则cvz=false
			autologin:Boolean,
			autoUpdate:Boolean,
		};
	},
	onLoad() {
		this.version = plus.runtime.version//打包后才能生效
		// console.log(this.version);
		uni.getStorage({
			key: 'autologin',
			success: res => {
				this.autologin = true
			},
			fail: error => {
				this.autologin = false
			}
		});
		uni.getStorage({
			key: 'autoUpdate',
			success: res => {
				this.autoUpdate = true
			},
			fail: error => {
				this.autoUpdate = false
			}
		});
	},
	mounted() {
		uni.$on("autoUpdate", res => {
			// console.log("nimasile");
			this.update()
			uni.$off('autoUpdate');//清除监听
		})
	},
	methods: {
		...mapMutations(['logout']),
		//改函数用于下载安装
		createDownload(DownUrl){
			uni.showLoading({title:'下载中...'})
			uni.downloadFile({
			    url: DownUrl, //仅为示例，并非真实的资源
			    success: (res) => {
					uni.hideLoading()
			        if (res.statusCode === 200) {
						let that = this;
						uni.saveFile({
						    tempFilePath: res.tempFilePath,
							success: function(ress) {
								plus.runtime.install(ress.savedFilePath);
							}
						});
			            console.log('下载成功');
			        }else{
						console.log(res);
					}
			    }
			});
		},
		//检查新版本
		//检测服务器是否有新版本
		compareVersion(ov, nv) {
			if (!ov || !nv || ov == '' || nv == '') {
				this.cvz = 'false';
				return false;
			}
			var ova = ov.split('.', 4);
			var nva = nv.split('.', 4);

			for (var i = 0; i < ova.length && i < nva.length; i++) {
				var so = ova[i],
					no = parseInt(so),
					sn = nva[i],
					nn = parseInt(sn);
				if (nn > no || sn.length > so.length) {
					this.cvz = 'true';
					return true;
				} else if (nn < no) {
					this.cvz = 'false';
					return false;
				}
			}
			if (nva.length > ova.length && 0 == nv.indexOf(ov)) {
				this.cvz = 'true';
				return true;
			}
		},
		//检测新版本
		update() {
			let opts = {
				url: this.api + '/update.php',
				method: 'POST'
			};
			let param = {};
			let isLoading = true; //是否需要加载动画
			this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
					var version = res.data.version;
					var appurl = res.data.appurl;
					this.compareVersion(this.version, res.data.version);
					if (this.cvz == 'true') {
						uni.showModal({
						    title: '提示',
						    content: '是否更新到最新版本',
						    success: (res) => {
						        if (res.confirm) {
						            this.createDownload(appurl);
						        }
						    }
						});
					} else {
						uni.showToast({
							icon: 'success',
							position: 'bottom',
							title: '已经是最新版本'
						});
					}
				},
				error => {
					console.log(error);
				}
			);
		},
		//退出
		quit() {
			// console.log(this.hasLogin);
			if (this.hasLogin) {
				this.logout(); //清除自动登录
			}
			uni.showModal({
				content: '确定关闭系统吗',
				success: function(res) {
					if (res.confirm) {
						uni.reLaunch({
							url: '../login/Login'
						});
					} else if (res.cancel) {
						// console.log('用户点击取消');
					}
				}
			});
		},
		//自动检查更新开关切换
		Update: function(e) {
			if(e.target.value){
				uni.setStorage({
				    key: 'autoUpdate',
					data:'autoUpdate',
				});	
			}else{
				uni.removeStorage({
				    key: 'autoUpdate',
				});
			}
		},
		//自动登录更新开关切换
		login: function(e) {
			if(e.target.value){
				uni.setStorage({
				    key: 'autologin',
					data:'autologin',
					success: function (res) {
						console.log('success');
					}
				});	
			}else{
				uni.removeStorage({
				    key: 'autologin',
				});
			}
		}
	}
};
</script>

<style>
page {
	background-color: #efeff4;
}

</style>
