<template>
	<view>
		<!-- 自定义导航栏 -->
		<view class="status_bar" :style="{height:height+'px'}"></view>
		<uni-nav-bar right-icon="map" :title="companyPro" @clickRight="changeProject"></uni-nav-bar>
		<!-- 部门公告 -->
		<view v-show="isShow">
			<!-- 公司层级 -->
			<announce :height="height2" ></announce>
		</view>
		<view v-show="!isShow">
			<!-- 项目层级 -->
			<announce :height="height1" ></announce>
		</view>
		<view class="uni-list"></view>
		<!-- 公司层级 -->
		<view v-if="isShow" class="companyRank" :style="{height:height3+'px'}"><!-- 高度 = 【屏幕高-（顶部导航栏高度+底部导航栏高度）】*0.198 -->
			<view class="companyTatol">
				<text class="font-big">{{projectNum}}</text>
				<text class="font">项目总数</text>
			</view>
			<view class="companyData" style="margin-right: 2%;">
				<text>整改闭合率80%</text>
				<text>实测合格率80%</text>
				<text>非正常验收通过率80%</text>
			</view>
		</view>
		
		<!-- 导航栏滚动 -->
		<scroll></scroll>
		<!-- 公司层级功能模块 -->
		<companyModule v-if="isShow"></companyModule>
		<!-- 项目层级功能模块 -->
		<projectModule v-else="!isShow"></projectModule>
		<!-- 遮罩层 -->
		<view class="mask" v-if="isloading"></view>
	</view>
</template>

<script>
	import scroll from "@/components/common/scroll/scroll"; //导航栏
	import projectModule from "@/components/module_project/module"; //项目层级功能模块
	import companyModule from "@/components/module_company/module"; //公司层级功能模块
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar"
	import announce from "@/components/common/announce/announce.vue"//部门公告
	export default {
		components: {
			scroll,
			projectModule,
			companyModule,
			uniNavBar,
			announce
		},
		data() {
			return {
				projectName: '',
				projectNum: '',
				companyPro: '',
				level: '', //总公司层级/分公司层级/项目层级
				isShow: Boolean,
				isShowComView: Boolean,
				userInfo: [],
				isloading: true, //遮罩层
				height: null, //获取的状态栏高度
				height1: null, //项目层级公告高度
				height2: null, //公司层级公告高度
				height3: null, //公司层级项目个数和各种率高度
			}
		},
		onLoad() {
			var _this = this;
			// 获取手机状态栏高度
			uni.getSystemInfo({
				success: function(data) {
					// ifios
					// console.log('iflos');
					// var currentWebview = this.$scope.$getAppWebview()
					// 将其赋值给this 
					_this.height = data.statusBarHeight;
					_this.height1 = (data.windowHeight-140-100)//屏幕高度减去模块140-滚动指标100
					//公司层级设定高度
					_this.height2 = (data.windowHeight-100-70)*0.7//屏幕高度减去模块70-滚动指标100
					_this.height3 = (data.windowHeight-100-70)*0.3
					if(data.platform=='android'){
						uni.getStorage({
							key: 'autoUpdate',
							success: (res) => {
								 uni.$emit('autoUpdate', 'autoUpdate');//调用我的页面自动更新方法
							},
							fail: (error) => {
								console.log(error);
							}
						});
					}
				}
			})
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					this.userInfo = res.data
					this.getCID()
				},
			});
			
		},
		onShow() {
			//监听切换工程
			uni.$on("changePro", res => {
				// console.log(res)
				this.projectName = res.projectName
				// console.log(this.projectName)
				this.companyPro = res.companyName + '/' + res.projectName
				this.projectId = res.projectId //工程id
				this.isShow = res.isShow
				this.userInfo['projectName'] = res.projectName
				this.userInfo['projectId'] = res.projectId //工程id
				this.userInfo['proTimeStamp'] = res.proTimeStamp //工程时间戳
				this.userInfo['database'] = res.database //工程时间戳
				// console.log(this.userInfo)
				uni.setStorage({
					key: 'userInfo',
					data: this.userInfo
				})
				// 清除监听
				uni.$off('changePro');
			})
		},
		mounted() {
			this.getLevel()
			this.getPost()
		},
		methods: {
			//获取设备CID
			getCID() {
				let cid = plus.push.getClientInfo().clientid; //获取设备CID
				console.log(cid);
				let opts = {
					url: this.api + '/message_push/saveCID.php',
					method: 'POST'
				}
				let param = {
					cid: cid,
					userId: this.userInfo.userId
				}
				this.myRequest.httpRequest(opts, param).then(res => {}, error => {
					console.log(error);
				})
			},
			//切换工程
			changeProject() {
				console.log(this.isShow)
				uni.navigateTo({
					url: '../index/change_project/change_project?isShowComView=' + this.isShowComView + '&id=' + '123' + '&level=' + this.level + ''
				}); 
			},
			//获取层级
			getLevel() {
				let opts = {
					url: this.api + '/index/index.php',
					method: 'POST'
				}
				let param = {
					flag: 'getLevel',
					username: this.userInfo.username,
					phone: this.userInfo.phone,
					company: this.userInfo.companyName,
					userId: this.userInfo.userId
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层

					this.projectNum = res.data.projectNum
					this.level = res.data.data.split('|')[0]
					this.companyPro = res.data.data.split('|')[1]
					this.userInfo['level'] = res.data.data.split('|')[0]
					// console.log(this.userInfo)
					if (this.level == '总公司' || this.level == '分公司') {
						this.getCompanyData()
						this.isShow = true
						this.isShowComView = true
					} else {
						this.isShow = false
						this.isShowComView = false
						this.userInfo['projectName'] = this.projectName
						this.userInfo['projectId'] = res.data.projectId
						this.userInfo['proTimeStamp'] = res.data.timeStamp //工程时间戳
						this.userInfo['database'] = res.data.database
					}
					uni.setStorage({
						key: 'userInfo',
						data: this.userInfo,
						success: (res) => {
							uni.$emit('scrolldata', this.userInfo);//获取数据指标
						},
					})
				}, error => {
					console.log(error);
				})


			},
			// 获取人员的职位
			getPost() {
				
			},

			//获取项目个数以及各种闭合率和导航栏数据
			getCompanyData() {

			}
		},
	}
</script>

<style lang="scss">
	.projectName {
		display: flex;
		flex-direction: row;
		// margin-top: 10px;

		.project {
			flex: 0.8;
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 18px;
			text-align: center;
		}

		.change {
			flex: 0.2;
			text-align: center;
			float: right;
		}
	}

	.companyRank {
		margin-top: 8px;
		display: flex;

		.companyTatol {
			flex: 1;
			display: flex;
			flex-direction: column;
			text-align: center;
			line-height: 80rpx;
			justify-content: center;
		}

		.companyData {
			flex: 1;
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			justify-content: center;
		}
	}

	.mask {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
		width: 100%;
		height: 100vh;
		background: rgba(0, 0, 0, 0.4);
	}

	.status_bar {
		width: 100%;
		background: #007AFF;
		position: relative;
	}
	
</style>
