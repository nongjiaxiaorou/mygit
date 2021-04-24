<template>
	<view class="uni-fab-box">
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">整改中({{rectificationList.length}})</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">已关闭({{closedList.length}})</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<view class="example-body">
				<view v-for="item in rectificationList" :key="item.taskTimeStamp" class="example-box">
					<uni-card :is-shadow="true" :title="'检查对象：'+item.inspectObj" :extra="item.correctMarks" @click="ExternalDetail(item)">
						<view class="flex">
							<view class="content-box-text left-box-parent">
								<view class="content-box-text left-box-childen">{{item.companyRank}}</view>
							</view>
							<view class="content-box-text" style="flex: 0.7;margin-left: 6%;">
								<view class="content-box-text">
									名称：{{item.inspectName}}
									<view style="height: 41rpx;float: right;">
										<checkbox-group @change="checkboxChange($event, item)">
											<checkbox :value="item.taskTimeStamp" :checked="item.checked" />
										</checkbox-group>
									</view>
								</view>
								<view class="content-box-text">
									组长：{{item.groupLeader}}
								</view>
								<view class="content-box-text">
									副组长：{{item.deputyLeader}}
								</view>
							</view>
						</view>
						<view class="content-box-text" style="float: right;">
							{{item.inspectTime}}
						</view>
					</uni-card>
				</view>
			</view>
			<!-- 悬浮按钮 -->
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" />
		</view>
		<!-- 页面1 -->
		<view class="" v-show="Inv == 1">
			<view class="example-body">
				<view v-for="item in closedList" :key="item.taskTimeStamp" class="example-box">
					<uni-card :is-shadow="true" :title="'检查对象：'+item.inspectObj" :extra="item.correctMarks" @click="ExternalDetail(item)">
						<view class="flex">
							<view class="content-box-text left-box-parent">
								<view class="content-box-text left-box-childen">{{item.companyRank}}</view>
							</view>
							<view class="content-box-text" style="flex: 0.7;margin-left: 6%;">
								<view class="content-box-text">
									名称：{{item.inspectName}}
									<view style="height: 41rpx;float: right;">
										<checkbox-group @change="checkboxChange($event, item)">
											<checkbox :value="item.taskTimeStamp" :checked="item.checked" />
										</checkbox-group>
									</view>
								</view>
								<view class="content-box-text">
									组长：{{item.groupLeader}}
								</view>
								<view class="content-box-text">
									副组长：{{item.deputyLeader}}
								</view>
							</view>
						</view>
						<view class="content-box-text" style="float: right;">
							{{item.inspectTime}}
						</view>
					</uni-card>
				</view>
			</view>
		</view>

		<!-- 签名 -->
		<!-- <messagepush ref="select_person" :flag="'side'" :person="'allPerson'" @assignside="assignside"></messagepush> -->
		<catSignature1 canvasId="qualityController" @close="close" @save="save" :visible="isShow" />
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniDrawer from '@/components/uni-app/uni-drawer/uni-drawer.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import buildTree from '@/components/common/build-tree/build-tree.vue'
	import uniToast from "@/components/uni-app/uni-toast/uni-toast.vue"
	import uniIcons from '@/components/uni-app/uni-icons/icons.js';
	import uniPopup from '@/components/uni-app/uni-popup/uni-popup';
	import uniPopupDialog from '@/components/uni-app/uni-popup-dialog/uni-popup-dialog';
	import catSignature1 from '@/components/common/cat-signature/cat-signature1.vue';
	// import messagepush from '@/pages/module_project/SpecialAdmin/message-push/message-push.vue';
	let that = null
	export default {
		components: {
			uniFab,
			uniNavBar,
			uniDrawer,
			uniCard,
			buildTree,
			uniIcons,
			uniPopup,
			uniPopupDialog,
			catSignature1,
			// messagepush
		},
		data() {
			return {
				fabShow: false,
				Inv: 0,
				title: 'uni-fab',
				directionStr: '垂直',
				horizontal: 'right',
				vertical: 'bottom',
				direction: 'horizontal',
				pattern: {
					color: '#7A7E83',
					backgroundColor: '#fff',
					selectedColor: '#007AFF',
					buttonColor: '#007AFF'
				},
				content: [{
						iconPath: '/static/images/wancheng.png',
						selectedIconPath: '/static/images/wanchenged.png',
						text: '完成',
						active: false
					},
					{
						iconPath: '/static/images/shanchu.png',
						selectedIconPath: '/static/images/shanchued.png',
						text: '删除',
						active: false
					}
				],
				currentData: [],
				tree: [],
				prop: {
					label: 'name',
					children: 'children'
				},
				proTimeStamp: '',
				projectId: '',
				section: '',
				build: '',
				rectificationList: [], //整改任务单
				closedList: [], //已关闭任务单
				selectcard: [],
				buildSelData: [],
				isStroge: false,
				isShow: false, //签名
			}
		},
		onLoad() {
			that = this
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					// console.log(res.data)
					that.currentData = res.data
					// that.currentData = JSON.stringify(res.data)
					that.proTimeStamp = res.data.proTimeStamp
					that.projectId = res.data.projectId
				}
			})
		},
		mounted() {
			this.getExternalTask()
		},
		onBackPress() {
			if (this.$refs.fab.isShow) {
				this.$refs.fab.close()
				return true
			}
			return false
		},
		methods: {
			//获取外部检查任务
			getExternalTask() {
				// console.log(this.currentData) 
				let opts = {
					url: this.api + '/module_project/ExternalInspect/ExternalInspect.php',
					method: 'POST'
				}
				let param = {
					flag: 'getExternalTask',
					userId: that.currentData.userId
				} 
				let isLoading = true //是否需要加载动画 
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					this.rectificationList = []
					for(var i=0;i<res.data.data.length;i++){
						console.log(res.data.data[i].rectifyState)
						if(res.data.data[i].rectifyState=='整改中'){
							this.rectificationList.push({
								id: res.data.data[i].id,
								taskTimeStamp: res.data.data[i].taskTimeStamp,
								projectId: res.data.data[i].projectId,
								inspectObj: res.data.data[i].inspectObj,
								companyRank: res.data.data[i].companyRank,
								inspectName: res.data.data[i].inspectName,
								groupLeader: res.data.data[i].groupLeader,
								deputyLeader: res.data.data[i].deputyLeader,
								actualMarks: res.data.data[i].actualMarks,
								correctMarks: res.data.data[i].correctMarks
							})
						}else{
							this.closedList.push({
								id: res.data.data[i].id,
								taskTimeStamp: res.data.data[i].taskTimeStamp,
								projectId: res.data.data[i].projectId,
								inspectObj: res.data.data[i].inspectObj,
								companyRank: res.data.data[i].companyRank,
								inspectName: res.data.data[i].inspectName,
								groupLeader: res.data.data[i].groupLeader,
								deputyLeader: res.data.data[i].deputyLeader,
								actualMarks: res.data.data[i].actualMarks,
								correctMarks: res.data.data[i].correctMarks
							})
						}
					}
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			completeScoreList() {},
			//跳转列表详情
			ExternalDetail(e) {
				let data = JSON.stringify(e)
				console.log(e)
				uni.navigateTo({
					url: `ExternalInspectDetail?data=${data}`
				})
			},
			//监听卡片选择状态
			checkboxChange(e, item) {
				var list = this.list,
					id = e.detail.value; //卡片id
				let box = (item.checked = !item.checked);
			},
			//触发悬浮按钮内事件
			trigger(e) {
				// console.log()
				let indexOf = e.index
				if (indexOf == 0) { //完成

					this.selectcard = [];
					this.rectificationList.forEach(item => {
						if (item.checked == true) {
							this.selectcard.push(item.taskTimeStamp);
						}
					})
					this.completeTaskFunc()
					// console.log(this.selectcard)

				} else if (indexOf == 1) { //删除
					this.selectcard = [];
					this.taskList.forEach(item => {
						if (item.checked == true) {
							this.selectcard.push(item.taskTimeStamp);
						}
					})

				}
			},
			switchBtn(hor, ver) {
				if (hor === 0) {
					this.direction = this.direction === 'horizontal' ? 'vertical' : 'horizontal'
					this.directionStr = this.direction === 'horizontal' ? '垂直' : '水平'
				} else {
					this.horizontal = hor
					this.vertical = ver
				}
				this.$forceUpdate()
			},
			close() {
				this.isShow = false;
			},
			save(e) {
				console.log(e)
				// this.isShow = false;

				// let id = e;
				// uni.redirectTo({
				// 	url: 'PourDetails?id=' + id
				// });
			},
			// 完成任务单
			completeTaskFunc() {
				console.log(this.selectcard)
				let opts = {
					url: this.api + '/module_project/ExternalInspect/ExternalInspect.php',
					method: 'POST'
				}
				let param = {
					flag: 'completeTaskNotice',
					selectcard: JSON.stringify(this.selectcard)
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					if(res.data.code){
						uni.showToast({
							title: '外部检查任务单已完成',
							icon: 'success'
						});
						setTimeout(res => {
							this.getExternalTask()
						}, 1500);
					}
				}, error => {
					console.log(error);
				})
			}
		},
	}
</script>

<style>
	/* 头条小程序组件内不能引入字体 */
	/* #ifdef MP-TOUTIAO */
	@font-face {
		font-family: uniicons;
		font-weight: normal;
		font-style: normal;
		src: url('~@/static/uni.ttf') format('truetype');
	}

	/* #endif */

	/* #ifndef APP-NVUE */
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #ffffff;
		min-height: 100%;
		height: auto;
	}

	view {
		line-height: inherit;
	}

	.inv-h-w {
		background-color: #ffffff;
		height: 80upx;
		display: flex;
	}

	/* #endif */

	.word-btn-white {
		font-size: 18px;
		color: #FFFFFF;
	}

	.word-btn {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		align-items: center;
		justify-content: center;
		border-radius: 6px;
		height: 48px;
		margin: 15px;
		background-color: #007AFF;
	}

	.close {
		padding: 15px;
	}

	.left-box-parent {
		flex: 0.3;
		border: 1px solid #8d939a;
		border-radius: 80%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.left-box-childen {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
