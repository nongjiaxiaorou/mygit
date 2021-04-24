<template>
	<view class="uni-fab-box">
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">任务单({{taskNum}})</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">已完成({{finishNum}})</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<view class="example-body">
				<view v-for="item in taskList" :key="item.taskTimeStamp" class="example-box">
					<uni-card :is-shadow="true" :title="'检查对象：'+item.inspectObj" @click="listDetail(item)">
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
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :show='fabShow' :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" />
		</view>
		<!-- 页面1 -->
		<view class="" v-show="Inv == 1">
			<view class="example-body">
				<view v-for="item in finishList" :key="item.taskTimeStamp" class="example-box">
					<uni-card :is-shadow="true" :title="'检查对象：'+item.inspectObj" @click="listDetail(item)">
						<view class="flex">
							<view class="content-box-text left-box-parent">
								<view class="content-box-text left-box-childen">{{item.companyRank}}</view>
							</view>
							<view class="content-box-text" style="flex: 0.7;margin-left: 6%;">
								<view class="content-box-text">
									名称：{{item.inspectName}}
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
	let that = null
	export default {
		components: {
			uniFab,
			uniNavBar,
			uniDrawer,
			uniCard,
			buildTree,
			uniIcons
		},
		data() {
			return {
				fabShow:false,
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
				content: [
					{
						iconPath: '/static/images/wancheng.png',
						selectedIconPath: '/static/images/wanchenged.png',
						text: '完成',
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

				taskList: [], //任务单
				finishList: [], //已完成
				taskNum: 0, //任务单数量
				finishNum: 0, //已完成数量
				selectcard: [],
				buildSelData: [],
				isStroge: false
			}
		},
		onLoad() {
			that = this
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					that.currentData = res.data
					// that.currentData = JSON.stringify(res.data)
					that.proTimeStamp = res.data.proTimeStamp
					that.projectId = res.data.projectId
				}
			})
		},
		onShow: (res) => {
			uni.$on('updateList', res => {
				this.getDefinedTask()
				uni.$off('updateList');
			})
		},
		mounted() {
			this.getDefinedTask()
		},
		onBackPress() {
			if (this.$refs.fab.isShow) {
				this.$refs.fab.close()
				return true
			}
			return false
		},
		methods: {
			//异步获取定义完成的任务单（仅显示与该账号有关的任务）
			getDefinedTask() {
				let opts = {
					url: this.api + '/module_company/QualityInspect/InspectList.php',
					method: 'POST'
				}
				let param = {
					flag: 'getDefinedTask',
					userId:that.currentData.userId
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					this.taskList = []
					this.finishList = []
					this.taskNum = 0
					this.finishNum = 0
					if (res.data.taskList != undefined) {
						this.taskList = res.data.taskList
						this.taskNum = Object.values(this.taskList).length
					}
					if (res.data.finishList != undefined) {
						this.finishList = res.data.finishList
						this.finishNum = Object.values(this.finishList).length
					}
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			//跳转列表详情
			listDetail(e){
				let data = JSON.stringify(e)
				
				uni.navigateTo({
					url:`InspectListDetail?data=${data}`
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
				if (indexOf == 0) { //下发
					this.selectcard = [];
					Object.values(this.taskList).forEach(item => {
						if (item.checked == true) {
							this.selectcard.push(item.taskTimeStamp);
						}
					})
					//异步下发选中通知单进入整改中
					let opts = {
						url: this.api + '/module_company/QualityInspect/InspectList.php',
						method: 'POST'
					}
					let param = {
						flag: 'completeDefine',
						selectcard: JSON.stringify(this.selectcard)
					}
					let isLoading = false //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res)
						if(res.data.msg == '未完成'){
							let oldArr = res.data.data
							let text = ''
							for(let i=0;i<oldArr.length;i++){
								text +=oldArr[i].userName+'在'+oldArr[i].inspectObj+'的'+oldArr[i].inspectName+'未完成'+"\n"
							}
							uni.showModal({
								content: text,
								success: function (res) {
									if (res.confirm) {
										
									} else if (res.cancel) {
										
									}
								}
							});
						}else{
							uni.showToast({
								title: '下发完成，请进入检查评分汇总进行评分修正'
							});
							setTimeout(res =>{
								this.getDefinedTask()
							},1500)
						}
					}, error => {
						console.log(error);
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
			showDrawer(e) {
				this.$refs[e].open()
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

	.example {
		padding: 0 15px 15px;
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
