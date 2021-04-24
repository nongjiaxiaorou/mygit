<template>
	<view class="">
		<ChooseLits @dateChange="dateChange" :list="list" :arr="arr" @chooseLike="chooseLike"></ChooseLits>
		<view class="example-body">
			<view class="example-box">
				<!-- 消息模块 -->
				<block v-for="item in notice" :key="item.id">
					<uni-card>
						<view @click="clicknotice(item)">{{item.messagetype}}
						<view class="uni-list-cell card">{{item.content}}
						<i :class="[item.read === '1' ? 'reTipNone':'reTipRed']"></i>
							<view>
								<checkbox-group @change="checkboxChange($event, item)">
									<checkbox :value="item.id" :checked="item.checked" />
								</checkbox-group>
							</view>
						</view>
						<view style="float: right;color: #888888;">{{item.time}}</view>
						</view>
					</uni-card>
					<!-- <uni-card @click="clicknotice(item)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card" style="color: #121416;">
								<i :class="[item.read === '1' ? 'reTipNone':'reTipRed']"></i>
								{{item.messagetype}}
								<view style="height: 41rpx;line-height: 31rpx;">
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox :value="item.id" :checked="item.checked" />
									</checkbox-group>
								</view> 
							</view>
							<view class=" uni-list-card" style="color: #121416;">{{item.content}}</view>
							<view class=" uni-list-card" style="float: right;color: black;">{{item.time}}</view>
						</view>
					</uni-card> -->
				</block>
			</view>
		</view>

		<!-- 悬浮按钮 -->
		<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
		 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
	</view>
	</view>
</template>

<script>
	import ChooseLits from '../../components/common/choose-Cade/choose-Cade2.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue';
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue';
	export default {
		components: {
			ChooseLits,
			uniCard,
			uniFab
		},
		data() {
			return {
				list: ['时间段', '类型'],
				arr: [
					['起始时间', '截止时间'],
					['项目检查', '项目验收', '砼浇筑管理', '砼拆模管理', '每周巡检', '质量检查', '外部检查'],
				],
				i2: [0, 0],
				startTime: '',
				endTime: '',
				messagetype: '', //选择的消息类型
				selectcard: [],
				userInfo: [],
				notice: [], //通知卡片
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
					iconPath: '/static/images/shanchu.png',
					selectedIconPath: '/static/images/shanchued.png',
					text: '删除',
					active: false
				}],
			}
		},
		onShow() {
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					this.userInfo = res.data
					this.getNotice()
				}
			})
		},
		mounted() {
			this.getNotice()
		},
		methods: {
			//获取通知卡片
			getNotice() {
				console.log(this.userInfo.userId)
				this.notice = []
				let opts = {
					url: this.api + '/msg/TodoNotice.php',
					method: 'POST'
				}
				let param = {
					flag: 'getNotice',
					userId: this.userInfo.userId
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					let data = res.data.data
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
					let length = data.length;
					this.notice = []
					for (var i = 0; i < length; i++) {
						this.$set(data[i], 'checked', false);
						this.notice.push(data[i]);
					}
				}, error => {
					console.log(error);
				})
			},
			//时间选择
			dateChange(e, flag) {
				flag == 'startTime' ? (this.startTime = e) : (this.endTime = e)
				if (this.startTime != '' && this.endTime != '') {
					console.log(this.endTime);
					this.notice = []
					let opts = {
						url: this.api + '/msg/TodoNotice.php',
						method: 'POST'
					}
					let param = {
						flag: 'getNotice',
						userId: this.userInfo.userId,
						messagetype: this.messagetype,
						startTime: this.startTime,
						endTime: this.endTime
					}
					let isLoading = true //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						let data = res.data.data 
						console.log(res);
						
						if (res.data.code == '0') {
							uni.showToast({
								icon: 'error',
								position: 'bottom',
								title: res.data.msg
							});
						} else {
							let length = data.length;
							for (var i = 0; i < length; i++) {
								this.$set(data[i], 'checked', false);
								this.notice.push(data[i]);
							}
						}
						uni.hideLoading() //隐藏加载中转圈圈
						this.isloading = false //取消遮罩层
					}, error => {
						console.log(error);
					})
				}
			},
			//下拉类型筛选
			chooseLike(i1) {
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				console.log(this.arr[1][this.i2[1]]);
				let messagetype = this.arr[1][this.i2[1]]
				this.messagetype = messagetype
				this.notice = []
				let opts = {
					url: this.api + '/msg/TodoNotice.php',
					method: 'POST'
				}
				let param = {
					flag: 'getNotice',
					userId: this.userInfo.userId,
					messagetype: this.messagetype,
					startTime: this.startTime,
					endTime: this.endTime
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					let data = res.data.data
					console.log(res);
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
					if (res.data.code == '0') {
						uni.showToast({
							icon: 'error',
							position: 'bottom',
							title: res.data.msg
						});
					} else {
						let length = data.length;
						for (var i = 0; i < length; i++) {
							this.$set(data[i], 'checked', false);
							this.notice.push(data[i]);
						}
					}
				}, error => {
					console.log(error);
				})
			},
			// 点击了悬浮按钮
			fabClick() {},
			//浇筑令悬浮按钮
			trigger(e) {
				let indexOf = e.index;
				var that = this;
				// 循环遍历ID
				that.selectcard = [];
				that.notice.forEach(item => {
					if (item.checked == true) {
						that.selectcard.push(item.id);
					}
				});
				var length = that.selectcard.length;
				if (length == 0) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '未选择卡片'
					});
					return;
				}
				console.log(that.selectcard);
				switch (indexOf) {
					case 0: //删除
						uni.showModal({
							title: '提示',
							content: '是否确认删除',
							success: function(res) {
								if (res.confirm) {
									for (var i = 0; i < length; i++) {
										let opts = {
											url: that.api + '/msg/TodoNotice.php',
											method: 'POST'
										}
										let param = {
											flag: 'deleteNotice',
											id: that.selectcard[i]
										}
										let isLoading = true //是否需要加载动画
										that.myRequest.httpRequest(opts, param, isLoading).then(res => {
											console.log(res);
											uni.hideLoading() //隐藏加载中转圈圈
											that.isloading = false //取消遮罩层
										}, error => {
											console.log(error);
										})
									}
									uni.showToast({
										icon: 'none',
										position: 'bottom',
										title: '删除成功',
										success: res => {
											//刷新本页面
											setTimeout(function() {
												uni.redirectTo({
													url: 'notice'
												});
											}, 1000);
										}
									});
								}
							}
						});
						break;
				}
			},
			checkboxChange(e, item) {
				var list = this.list,
					id = e.detail.value; //卡片id
				let box = (item.checked = !item.checked);
			},
			// 点击消息
			clicknotice(item) {
				let id = item.id;
				let cardId = item.cardId;
				let cardTimeStamp = item.cardTimeStamp;
				let messagetype = item.messagetype;
				let proState = item.proState;
				let title = item.title;

				if (item.read === '0') {
					//改变已读
					let opts = {
						url: this.api + '/msg/TodoNotice.php',
						method: 'POST'
					}
					let param = {
						flag: 'changeRed',
						id: id
					}
					let isLoading = false //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res);
						// uni.hideLoading()//隐藏加载中转圈圈
						// this.isloading = false//取消遮罩层
					}, error => {
						console.log(error);
					})
				}

				switch (messagetype) {
					case '砼浇筑管理': //删除
						if (proState == '浇筑') {
							uni.navigateTo({
								url: '../module_project/SpecialAdmin/PourDetails?id=' + cardId
							})
						} else {
							uni.navigateTo({
								url: '../module_project/SpecialAdmin/SideDetails?id=' + cardId
							})
						}
						break;
					case '砼拆模管理':
						uni.navigateTo({
							url: '../module_project/SpecialAdmin/removalDetails?id=' + cardId
						})
						break;
					case '每周巡检':
						if (title === '现场质量巡检') {
							uni.navigateTo({
								url: '../module_project/WeeklyInspect/NoticeDetail?id=' + cardId
							})
						} else {
							uni.navigateTo({
								url: '../module_project/WeeklyInspect/FileDetail?id=' + cardId
							})
						}
						break;
					case '质量检查':
						if (title === '检查信息') {
							let opts = {
								url: this.api + '/module_company/QualityInspect/InspectList.php',
								method: 'POST'
							}
							let param = {
								flag: 'getDefinedTask',
								userId: this.userInfo.userId
							}
							let isLoading = true //是否需要加载动画
							this.myRequest.httpRequest(opts, param, isLoading).then(res => {
								// console.log(res.data)
								let listData = [];
								if (res.data.taskList != undefined) {
									Object.values(res.data.taskList).forEach(item => {
										if (item.taskTimeStamp == cardTimeStamp) {
											listData = item
										}
									})
								}
								if (res.data.finishList != undefined) {
									Object.values(res.data.finishList).forEach(item => {
										if (item.taskTimeStamp == cardTimeStamp) {
											listData = item
										}
									})
								}
								uni.hideLoading() //隐藏加载中转圈圈
								uni.navigateTo({
									url: '../module_company/QualityInspect/InspectListDetail?data=' + JSON.stringify(listData)
								})
							}, error => {
								console.log(error);
							})

						} else if(title === '评分确认') {
							let opts = {
								url: this.api + '/module_company/QualityInspect/inspectScore.php',
								method: 'POST'
							}
							let param = {
								flag: 'getScoredTask',
								userId: this.userInfo.userId
							}
							let isLoading = false //是否需要加载动画
							this.myRequest.httpRequest(opts, param, isLoading).then(res => {
								// console.log(res.data)
								let listData = [];
								if (res.data.taskList != undefined) {
									Object.values(res.data.taskList).forEach(item => {
										if (item.taskTimeStamp == cardTimeStamp) {
											listData = item
										}
									})
								}
								if (res.data.finishList != undefined) {
									Object.values(res.data.finishList).forEach(item => {
										if (item.taskTimeStamp == cardTimeStamp) {
											listData = item
										}
									})
								}
								uni.navigateTo({
									url: '../module_company/QualityInspect/InspectScoreDetail?data=' + JSON.stringify(listData)
								})
							}, error => {
								console.log(error);
							})
						}
						break;
					case '项目检查':
						uni.navigateTo({
							url: `../module_project/InspectAccept/ViolationInvestigation?enterFlag=message`+`&cardTimeStamp=${cardTimeStamp}`
						})
						break;
					case '项目验收':
						uni.navigateTo({
							url: `../module_project/InspectAccept/AcceptCheck?enterFlag=message`+`&cardTimeStamp=${cardTimeStamp}`
						})
						break;
				}

			},
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #efeff4;
	}

	.card {
		padding: 12rpx 26rpx;
	}

	.reTipRed {
		display: block;
		background: #f00;
		border-radius: 50%;
		width: 0.6rem;
		height: 0.6rem;
		top: 0.2rem;
		right: 0.2rem;
		position: absolute;
		z-index: 4;
	}

	.reTipNone {
		display: block;
		// background:#f00;
		border-radius: 50%;
		width: 0.6rem;
		height: 0.6rem;
		top: 0.2rem;
		right: 0.2rem;
		position: absolute;
		z-index: 4;
	}
</style>
