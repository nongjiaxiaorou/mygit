<template>
	<view class="uni-fab-box">
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">草稿({{draftNum}})</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">整改中({{rectifyNum}})</view>
			<view :class="['inv-h', Inv == 2 ? 'inv-h-se' : '']" @click="Inv = 2">延期({{delayNum}})</view>
			<view :class="['inv-h', Inv == 3 ? 'inv-h-se' : '']" @click="Inv = 3">逾期({{overdueNum}})</view>
			<view :class="['inv-h', Inv == 4 ? 'inv-h-se' : '']" @click="Inv = 4">未完成({{unfinishNum}})</view>
			<view :class="['inv-h', Inv == 5 ? 'inv-h-se' : '']" @click="Inv = 5">已完成({{finishNum}})</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<view class="example-body">
				<view v-for="item in draftList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
				<!-- 悬浮按钮 -->
				<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
				 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
			</view>
		</view>
		<!-- 页面1 -->
		<view class="" v-show="Inv == 1">
			<view class="example-body">
				<view v-for="item in rectifyList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
				<!-- 悬浮按钮 -->
				<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content1" :horizontal="horizontal" :vertical="vertical"
				 :direction="direction" @trigger="trigger1" @fabClick="fabClick" />
			</view>
		</view>
		<!-- 页面2 -->
		<view class="" v-show="Inv == 2">
			<view class="example-body">
				<view v-for="item in delayList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
				<!-- 悬浮按钮 -->
				<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content2" :horizontal="horizontal" :vertical="vertical"
				 :direction="direction" @trigger="trigger2" @fabClick="fabClick" />
			</view>
		</view>
		<!-- 页面3 -->
		<view class="" v-show="Inv == 3">
			<view class="example-body">
				<view v-for="item in overdueList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
			</view>
		</view>
		<!-- 页面4 -->
		<view class="" v-show="Inv == 4">
			<view class="example-body">
				<view v-for="item in unfinishList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
			</view>
		</view>
		<!-- 页面5 -->
		<view class="" v-show="Inv == 5">
			<view class="example-body">
				<view v-for="item in finishList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" @click='draftDetail(item)' :title="'巡检编号：'+item.inspectCode">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查人:项目经理
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view>
							</view>
							<view class=" uni-list-card">检查类型:{{item.subItem}}</view>
						</view>
					</uni-card>
				</view>
			</view>
		</view>
		<!-- 悬浮按钮 -->
		<!-- <uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
		 :direction="direction" @trigger="trigger" @fabClick="fabClick" /> -->
		<!-- 抽屉 -->
		<view class="example-body">
			<uni-drawer ref="showLeft" mode="left" :width="300" @change="change($event,'showLeft')">
				<view class="close">
					<view class="scroll-view">
						<scroll-view class="scroll-view-box" scroll-y="true">
							<view class="info-content">
								<buildTree :props="prop" @sendChildData="selDataFunc" :isCheck="true" :trees="tree"></buildTree>
								<!-- <text>可滚动内容 {{item}}</text> -->
							</view>
							<view class="closeCustom">
								<view class="btn-block">
									<view @click="closeDrawer('showLeft')">
										<button type="primary">选择楼栋信息</button>
									</view>
								</view>
								<!-- <view class="word-btn" @click="closeDrawer('showLeft')"><text class="word-btn-white">关闭Drawer</text></view> -->
							</view>
						</scroll-view>
					</view>
				</view>
			</uni-drawer>
		</view>
		<uni-popup id="popup1" ref="popup1" type="center" :animation="false" >
			<view class="popup-content">
				<view>
					<pick-date class="mt-1" datename="截止日期：" @date="getDelayDate"></pick-date>
					<button class="mini-btn" style="margin: 20rpx 0 0 0;" type="primary" size="mini" @click="cancel">取消</button>
					<button class="mini-btn" style="float: right;margin: 20rpx 0 0 0;" type="primary" size="mini" @click="saveDelay">确定</button>
				</view>
			</view>
		</uni-popup>
		<messagePush ref="select_person" :flag="'completeInspectPush'" :person="'allPerson'" @refresh="complete"></messagePush>
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniDrawer from '@/components/uni-app/uni-drawer/uni-drawer.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import buildTree from '@/components/common/build-tree/build-tree.vue'
	import uniToast from "@/components/uni-app/uni-toast/uni-toast.vue"
	import uniIcons from '@/components/uni-app/uni-icons/icons.js'
	import uniPopup from '@/components/uni-app/uni-popup/uni-popup'
	import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
	import messagePush from '@/pages/module_project/WeeklyInspect/message-push/message-push.vue';
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
			'pick-date': pickdate,
			messagePush
		},
		data() {
			return {
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
						iconPath: '/static/images/xinzeng.png',
						selectedIconPath: '/static/images/xinzenged.png',
						text: '新建',
						active: false
					},
					{
						iconPath: '/static/images/xiafa.png',
						selectedIconPath: '/static/images/xiafaed.png',
						text: '下发',
						active: false
					},
					{
						iconPath: '/static/images/shanchu.png',
						selectedIconPath: '/static/images/shanchued.png',
						text: '删除',
						active: false
					}
				],
				content1: [{
						iconPath: '/static/images/xinzeng.png',
						selectedIconPath: '/static/images/xinzenged.png',
						text: '申请延期',
						active: false
					},
					{
						iconPath: '/static/images/xiafa.png',
						selectedIconPath: '/static/images/xiafaed.png',
						text: '延期回复',
						active: false
					},
					{
						iconPath: '/static/images/shanchu.png',
						selectedIconPath: '/static/images/shanchued.png',
						text: '删除',
						active: false
					}
				],
				content2: [
					{
						iconPath: '/static/images/xiafa.png',
						selectedIconPath: '/static/images/xiafaed.png',
						text: '撤销延期',
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
				section:'',
				build:'',
				treeArr: [],
				selDataFromChild: {},

				draftList: [], //草稿
				rectifyList: [], //整改中
				delayList: [], //延期
				overdueList: [], //逾期
				unfinishList: [], //未完成
				finishList: [], //已完成
				
				draftNum: 0, //草稿数量
				rectifyNum: 0, //整改中数量
				delayNum: 0, //延期数量
				overdueNum: 0, //逾期数量
				unfinishNum: 0, //未完成数量
				finishNum: 0, //已完成数量
				selectcard: [],
				buildSelData: [],
				isStroge: false,
				delayTime:''
			}
		},
		onLoad() {
			that = this
			uni.getStorage({
				key: 'buildInfo',
				success: function(res) {
					that.buildSelData = res.data
					that.isSelBuild = 1
				}
			})
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					that.currentData = res.data
					that.currentData = JSON.stringify(res.data)
					that.proTimeStamp = res.data.proTimeStamp
					that.projectId = res.data.projectId
					// console.log(JSON.parse(this.currentData))
				}
			})
		},
		onShow() {
			uni.$on('updateSceneIns', res => {
				this.getNotice()
				uni.$off('updateSceneIns');
			})
			uni.$on('updateNoticeDetail', res => {
				this.getNotice()
				uni.$off('updateNoticeDetail');
			})
		},
		mounted() {
			this.getNotice()
		},
		onBackPress() {
			if (this.$refs.fab.isShow) {
				this.$refs.fab.close()
				return true
			}
			return false
		},
		methods: {
			//下发函数
			complete(){
				//异步下发选中通知单进入整改中
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
					method: 'POST'
				}
				let param = {
					flag: 'completeInspect',
					selectcard: JSON.stringify(this.selectcard)
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data.msg)
					uni.showToast({
						title: '下发成功,通知单进入'+res.data.msg,
						icon: 'none',
						duration:1500
					});
					setTimeout(res=> {
						this.getNotice()
					}, 1500);
				}, error => {
					console.log(error);
				})
			},
			//监听原生导航栏
			onNavigationBarButtonTap(e) {
				if (e.index == 0) {
					this.showDrawer('showLeft')
				}
			},
			//
			checkboxChange(e, item) {
				var list = this.list,
					id = e.detail.value; //卡片id
				let box = (item.checked = !item.checked);
			},
			//草稿详情
			draftDetail(val) {
				console.log(val.id)
				uni.navigateTo({
					url: `NoticeDetail?id=${val.id}`
				})
			},
			//获取通知单
			getNotice() {
				//获取现场质量巡检草稿通知单
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
					method: 'POST'
				}
				let param = {
					flag: 'getNotice',
					projectId:that.projectId,
					buildSelData:JSON.stringify(that.buildSelData)
				}
				let isLoading = true
				this.myRequest.httpRequest(opts, param ,isLoading).then(res => {
					// console.log(res)
					// res.data.data['checked']=false
					this.draftList = []
					this.rectifyList = []
					this.delayList = []
					this.overdueList = []
					this.unfinishList = []
					this.finishList = []
					this.draftNum = 0
					this.rectifyNum = 0
					this.delayNum = 0
					this.overdueList = 0
					this.unfinishList = 0
					this.finishList = 0
					if (res.data.draft != undefined) {
						this.draftList = res.data.draft
						this.draftNum = Object.values(this.draftList).length
						uni.showToast({
							title: '请进入通知单完成缺陷提交！',
							icon: 'none',
							duration:1500
						});
						uni.hideLoading() //隐藏加载中转圈圈
					}
					if (res.data.rectify != undefined) {
						this.rectifyList = res.data.rectify
						this.rectifyNum = Object.values(this.rectifyList).length
					}
					if (res.data.delay != undefined) {
						this.delayList = res.data.delay
						this.delayNum = this.delayList.length
						this.delayNum = Object.values( this.delayList).length
					}
					if (res.data.overdue != undefined) {
						this.overdueList = res.data.overdue
						this.overdueNum = Object.values( this.overdueList).length
					}
					if (res.data.unfinish != undefined) {
						this.unfinishList = res.data.unfinish
						this.unfinishNum = Object.values( this.unfinishList).length
					}
					if (res.data.finish != undefined) {
						this.finishList = res.data.finish
						this.finishNum = Object.values( this.finishList).length
					}
					uni.hideLoading() //隐藏加载中转圈圈
					// this.isloading = false //取消遮罩层
				}, error => {

				})
			},
			//触发悬浮按钮内事件
			trigger(e) {
				// console.log()
				let indexOf = e.index
				this.selectcard = [];
				let noticeList = []
				if (this.Inv == 0) {
					noticeList = Object.values(this.draftList)
				} else if (this.Inv == 1) {
					noticeList = Object.values(this.rectifyList)
				} else if (this.Inv == 2) {
					noticeList = Object.values(this.delayList)
				} else if (this.Inv == 3) {
					noticeList = Object.values(this.overdueList)
				} else if (this.Inv == 4) {
					noticeList = Object.values(this.unfinishList)
				} else if (this.Inv == 5) {
					noticeList = Object.values(this.finishList)
				}
				noticeList.forEach(item => {
					if (item.checked == true) {
						this.selectcard.push(item.id);
					}
				})
				if (indexOf == 0) { //新建
					//跳转到新增页面
					uni.navigateTo({
						url: `AddSceneInspect?currentData=${this.currentData}&buildSelData=${JSON.stringify(that.buildSelData)}`
					})
				} else if (indexOf == 1) { //下发
					var length = that.selectcard.length;
					if (length == 0) {
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '未选择卡片'
						});
						return;
					}
					this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
					for (var i = 0; i < length; i++) {
						this.$refs.select_person.$emit('cardId',that.selectcard[i])
					}
				} else { //删除
					var length = that.selectcard.length;
					if (length == 0) {
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '未选择卡片'
						});
						return;
					}
					uni.showModal({
						title: '提示',
						content: '是否确认删除通知单！',
						showCancel: true,
						cancelText: '取消',
						confirmText: '确认',
						success: res => {
							if (res.confirm) {
								let opts = {
									url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
									method: 'POST'
								}
								let param = {
									flag: 'deleteNotice',
									selectcard: JSON.stringify(this.selectcard)
								}
								let isLoading = false //是否需要加载动画
								this.myRequest.httpRequest(opts, param, isLoading).then(res => {
									// console.log(res)
									uni.showToast({
										title: '删除成功！',
										icon: 'none',
										duration:1500
									});
									setTimeout(res =>{
										this.getNotice()
									},1500)
								}, error => {
									console.log(error);
								})
							}
						}
					});
					
				}
			},
			//延期确定
			saveDelay(){
				console.log(this.delayTime)
				
				this.$refs.popup1.close()
			},
			//触发悬浮按钮内事件
			trigger1(e) {
				// console.log()
				let indexOf = e.index
				this.selectcard = [];
				let noticeList = []
				if (this.Inv == 0) {
					noticeList = Object.values(this.draftList)
				} else if (this.Inv == 1) {
					noticeList = Object.values(this.rectifyList)
				} else if (this.Inv == 2) {
					noticeList = Object.values(this.delayList)
				} else if (this.Inv == 3) {
					noticeList = Object.values(this.overdueList)
				} else if (this.Inv == 4) {
					noticeList = Object.values(this.unfinishList)
				} else if (this.Inv == 5) {
					noticeList = Object.values(this.finishList)
				}
				noticeList.forEach(item => {
					if (item.checked == true) {
						this.selectcard.push(item.id);
					}
				})
				var length = that.selectcard.length;
				if (length == 0) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '未选择卡片'
					});
					return;
				}
				if (indexOf == 0) { //申请延期
					this.$refs.popup1.open()
					// console.log(this.selectcard)
				}else if (indexOf == 1) { //延期回复
					uni.showModal({
						title: '提示',
						content: '申请延期，确定?',
						showCancel: true,
						cancelText: '不同意',
						confirmText: '同意',
						success: res => {
							if (res.confirm) {
								let opts = {
									url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
									method: 'POST'
								}
								let param = {
									flag: 'applyDelay',
									selectcard: JSON.stringify(this.selectcard),
									delayTime:this.delayTime
								}
								let isLoading = false //是否需要加载动画
								this.myRequest.httpRequest(opts, param, isLoading).then(res => {
									// console.log(res)
									uni.showToast({
										title: '延期成功，通知单进入延期状态！',
										icon:'none',
										duration:1500
									});
									setTimeout(res =>{
										this.getNotice()
									},1500)
								}, error => {
									console.log(error);
								})
							}
						}
					});
					
				} else { //删除
					uni.showModal({
						title: '提示',
						content: '是否确认删除通知单！',
						showCancel: true,
						cancelText: '取消',
						confirmText: '确认',
						success: res => {
							if (res.confirm) {
								let opts = {
									url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
									method: 'POST'
								}
								let param = {
									flag: 'deleteNotice',
									selectcard: JSON.stringify(this.selectcard)
								}
								let isLoading = false //是否需要加载动画
								this.myRequest.httpRequest(opts, param, isLoading).then(res => {
									// console.log(res)
									uni.showToast({
										title: '删除成功！',
										icon: 'none',
										duration:1500
									});
									setTimeout(res =>{
										this.getNotice()
									},1500)
								}, error => {
									console.log(error);
								})
							}
						}
					});
					
				}
			},
			//触发悬浮按钮内事件
			trigger2(e) {
				// console.log()
				let indexOf = e.index
				this.selectcard = [];
				let noticeList = []
				if (this.Inv == 0) {
					noticeList = Object.values(this.draftList)
				} else if (this.Inv == 1) {
					noticeList = Object.values(this.rectifyList)
				} else if (this.Inv == 2) {
					noticeList = Object.values(this.delayList)
				} else if (this.Inv == 3) {
					noticeList = Object.values(this.overdueList)
				} else if (this.Inv == 4) {
					noticeList = Object.values(this.unfinishList)
				} else if (this.Inv == 5) {
					noticeList = Object.values(this.finishList)
				}
				noticeList.forEach(item => {
					if (item.checked == true) {
						this.selectcard.push(item.id);
					}
				})
				var length = that.selectcard.length;
				if (length == 0) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '未选择卡片'
					});
					return;
				}
				if (indexOf == 0) { //延期回复
					uni.showModal({
						title: '提示',
						content: '撤销延期，确定?',
						showCancel: true,
						cancelText: '取消',
						confirmText: '确定',
						success: res => {
							if (res.confirm) {
								let opts = {
									url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
									method: 'POST'
								}
								let param = {
									flag: 'cancelDelay',
									selectcard: JSON.stringify(this.selectcard)
								}
								let isLoading = false //是否需要加载动画
								this.myRequest.httpRequest(opts, param, isLoading).then(res => {
									// console.log(res)
									uni.showToast({
										title: '撤销成功，通知单进入未完成状态！',
										icon:'none',
										duration:1500
									});
									setTimeout(res =>{
										this.getNotice()
									},1500)
								}, error => {
									console.log(error);
								})
							}
						}
					});
					
				} else { //删除
					uni.showModal({
						title: '提示',
						content: '是否确认删除通知单！',
						showCancel: true,
						cancelText: '取消',
						confirmText: '确认',
						success: res => {
							if (res.confirm) {
								let opts = {
									url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
									method: 'POST'
								}
								let param = {
									flag: 'deleteNotice',
									selectcard: JSON.stringify(this.selectcard)
								}
								let isLoading = false //是否需要加载动画
								this.myRequest.httpRequest(opts, param, isLoading).then(res => {
									// console.log(res)
									uni.showToast({
										title: '删除成功！',
										icon: 'none',
										duration:1500
									});
									setTimeout(res =>{
										this.getNotice()
									},1500)
								}, error => {
									console.log(error);
								})
							}
						}
					});
					
				}
			},
			//获取检查日期
			getDelayDate(val){
				this.delayTime = val
			},
			//延期取消
			cancel(){
				this.$refs.popup1.close()
			},
			
			fabClick() {
				// uni.showToast({
				// 	title: '点击了悬浮按钮',
				// 	icon: 'none'
				// })
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
			//返回上页
			back() {
				uni.navigateBack()
			},
			showDrawer(e) {
				this.$refs[e].open()
			},
			// 关闭窗口
			closeDrawer(e) {
				uni.getStorage({
					key: 'buildInfo',
					success: (res) => {
						that.isStroge = true
					}
				})
				console.log(this.selDataFromChild)
				if (this.selDataFromChild.length == 5) {
					let obj = {
						section: this.selDataFromChild[1].name,
						build: this.selDataFromChild[2].name,
						floor: this.selDataFromChild[3].name,
						unit: this.selDataFromChild[4].name
					}
					this.buildSelData = obj
					uni.setStorage({
						key: 'buildInfo',
						data: obj,
						success: function(res) {
							// console.log(res)
						}
					})
					this.$refs[e].close()
				} else {
					if (!that.isStroge) {
						uni.showToast({
							title: '请选中区段、栋号、楼层、单元',
							duration: 2000,
							icon: "none"
							// image:'/static/lost.png',   //要写根路径，不要写相对路径
						});
					} else {
						this.$refs[e].close()
					}
				}
				this.getNotice()
			},
			// 抽屉状态发生变化触发
			change(e, type) {
				//打开
				if (e) {
					let that = this
					let opts = {
						url: this.api + '/module_project/WeeklyInspect/SceneInspect.php',
						method: 'POST'
					}
					let param = {
						flag: 'getBuildInfo',
						proTimeStamp: this.proTimeStamp
					}
					let isLoading = true //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res.data)
						uni.hideLoading() //隐藏加载中转圈圈
						this.isloading = false //取消遮罩层
						console.log(res.data)
						if (res.data.code) {
							this.createFloorFunc(res.data.data)
						}
					}, error => {
						console.log(error);
					})
				} else { //关闭

				}
			},
			//数字转中文
			toChinesNum(num) {
				var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
				var unit = ["", "十", "百", "千", "万"];
				num = parseInt(num);
				var getWan = function(temp) {
					var strArr = temp.toString().split("").reverse();
					var newNum = "";
					for (var i = 0; i < strArr.length; i++) {
						newNum = (i == 0 && strArr[i] == 0 ? "" : (i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? "" : changeNum[strArr[
							i]] + (strArr[i] == 0 ? unit[0] : unit[i]))) + newNum;
					}
					return newNum;
				}
				var overWan = Math.floor(num / 10000);
				var noWan = num % 10000;
				if (noWan.toString().length < 4) noWan = "0" + noWan;
				return overWan ? getWan(overWan) + "万" + getWan(noWan) : getWan(num);
			},
			// 遍历区段楼层并存储在数组中
			createFloorFunc(data) {
				// console.log(data)
				let buildData = data
				let sectionData = this.commonFunc.Es5duplicate(data, 'section')
				let floorData = data
				for (var i = 0; i < sectionData.length; i++) {
					var obj = {
						id: '',
						name: '',
						user: false,
						children: []
					};
					obj.id = sectionData[i].id
					obj.name = sectionData[i].section
					for (var j = 0; j < buildData.length; j++) {
						if (buildData[j].section == sectionData[i].section) {
							let objBuild = {
								name: buildData[j].build,
								user: false,
								children: []
							};
							for (var k = 0; k < obj.children.length; k++) {
								if (buildData[j].build == obj.children[k].name) {
									var repeatFlag = true
								}
							}
							if (repeatFlag) { //去除重复栋号
								break;
							} else {

								//遍历地下楼层
								var undergroundNum = parseInt(floorData[j].undergroundNumber);
								if (undergroundNum) {
									let unit_arr = floorData[j].unitName.split('||')
									var index = undergroundNum;
									for (var z = 0; z < undergroundNum + 1; z++) {
										if (z == 0) {
											var FloorName = "基础层";
										} else {
											var FloorName = "负" + this.toChinesNum(index) + "层";
											index--;
										}
										var floorObj = {
											name: FloorName,
											user: false,
											children: []
										};
										//遍历单元
										for (var x = 0; x < unit_arr.length; x++) {
											let unitObj = {
												name: unit_arr[x],
												user: false,
												children: []
											}
											floorObj.children.push(unitObj)
										}
										objBuild.children.push(floorObj)
									}
								}
								obj.children.push(objBuild)

								//遍历地上楼层
								var abovegroundNumber = parseInt(floorData[j].abovegroundNumber);
								if (abovegroundNumber) {
									let unit_arr = floorData[j].unitName.split('||')
									var index = abovegroundNumber;
									for (var z = 0; z < abovegroundNumber + 1; z++) {
										if (i == abovegroundNumber) {
											var FloorName = "屋面层";
										} else {
											var FloorName = this.toChinesNum(z + 1) + "层";
										}
										var floorObj = {
											name: FloorName,
											user: false,
											children: []
										};
										//遍历单元
										for (var x = 0; x < unit_arr.length; x++) {
											let unitObj = {
												name: unit_arr[x],
												user: false,
												children: []
											}
											floorObj.children.push(unitObj)
										}
										objBuild.children.push(floorObj)
									}
								}
								obj.children.push(objBuild)
							}
						}
					}
					this.tree.push(obj)
				}
				this.treeArr = JSON.parse(JSON.stringify(this.tree)) //防止this指向数据修改联动
				for (var i = 0; i < this.treeArr.length; i++) {
					this.tree[i].children = []
					let buildData = this.commonFunc.Es5duplicate(this.treeArr[i].children, 'name')
					this.tree[i].children = buildData
				}
			},
			selDataFunc(data) {
				this.selDataFromChild = data
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

	/* 处理抽屉内容滚动 */
	.scroll-view-box {
		flex: 1;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
	}

	.btn-block {
		position: fixed; //将button按钮固定在页面底部，注意，：和；是英文的哦，一定不要写成中文哦。
		bottom: 5px;
		text-align: center;
		width: 80%;
		left: 50%;
		margin-left: -115px;
	}

	.btn-block button {
		/* margin-left: 50%;
		transform:translateX(-50%); */
	}
	
	.popup-content {
		background-color: #fff;
		padding: 15px;
	}
</style>
