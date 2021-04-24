<template>
	<view>
		<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
		<view class="example-body">
			<view v-for="(item,index) in newWarningList" :key="item.id" class="example-box">
				<uni-card @click='draftDetail(item)'>
					<view class="content-box-text" @click="!item.readFlag && changeRead(item.id)">
						<view class="uni-list-cell uni-list-card" style="color: #121416;">
							<i :class="[item.readFlag === true ? 'reTipNone':'reTipRed']"></i>
							{{item.warningCate}}
							<view style="height: 41rpx;line-height: 31rpx;">
								<checkbox-group @change="checkboxChange($event, item)">
									<checkbox :value="item.id" :checked="item.checked" />
								</checkbox-group>
							</view> 
						</view>
						<view class=" uni-list-card" style="color: #121416;">{{item.content}}</view>
						<view class=" uni-list-card" style="float: right;color: black;">{{item.warningTime}}</view>
					</view>
				</uni-card>
			</view>
		</view>
		<!-- 悬浮按钮 -->
		<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
		 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
	</view>
</template>

<script> 
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	let that = null
	export default {
		components: {
			ChooseLits,
			uniCard,
			uniFab
		},
		data() {
			return {
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
				list: ['时间', '类型'],
				arr: [
					['起始时间', '截止时间'],
					['问题隐患', '整改闭合率', '每周巡检'],
				],
				i2: [0, 0],
				warningList: [],
				userId: '',
				isShow: true,
				initWarningCate:'',
				startTime:'',
				endTime:''
			}
		},
		computed: {
			newWarningList: function() {
				let deleteList = []
				let len = this.warningList.length
				return this.warningList.filter(function(item) {
					deleteList = item.isDelete.split('/')
					let deleteIndex = deleteList.indexOf(that.userId)
					// console.log(that.userId)
					if (deleteIndex != -1) { //存在该userId 则在该用户上已删除

					} else {
						let readList = item.isRead.split('/')
						let readIndex = readList.indexOf(that.userId)
						if (readIndex != -1) { //存在该userId 则在该用户上已读
							item.readFlag = true
						} else {
							item.readFlag = false
						}
						if (item.warningCate == '问题隐患') {

							if (item.warningPost == '生产经理' || item.warningPost == '项目总工' || item.warningPost == '项目经理') {
								return item.content = item.inspectPosition + '(' + item.noticeNumber + ')' + item.violationContent + item.endDate +
									'为整改期限，请及时整改！'
							} else {
								return item.content = item.projectName + item.inspectPosition + '(' + item.noticeNumber + ')' + item.violationContent +
									item.endDate + '为整改期限，请及时整改！'
							}

						} else if (item.warningCate == '整改闭合率') {

							if (item.warningPost == '分公司部门经理' || item.warningPost == '总工') { //预警给分公司部门经理和总工
								return item.content = item.projectName + '隐患问题整改闭合率低于70%，敬请关注！'
							} else {
								return item.content = item.company + item.projectName + '隐患问题整改闭合率低于60%，敬请关注！'
							}

						} else if (item.warningCate == '现场质量周巡检') {

							return item.content = '贵项目' + item.projectName + '这周未进行现场质量周巡检，特此告知！'

						} else if (item.warningCate == '方案资料周巡检') {

							return item.content = '贵项目' + item.projectName + '这周未进行方案资料周巡检，特此告知！'

						}
					}
				})
			}
		},
		onLoad() {
			that = this
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					// console.log(res.data.userId)
					that.userId = res.data.userId
				}
			})
			this.getHiddenDanger('true')
		},
		methods: {
			//获取问题隐患
			getHiddenDanger(load) {
				let opts = {
					url: this.api + '/msg/QualityWarning.php',
					method: 'POST'
				}
				let param = {
					flag: 'getWarningMsg',
					initWarningCate:this.initWarningCate,
					startTime:this.startTime,
					endTime:this.endTime
				}
				let isLoading = load
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					this.warningList = res.data.data
					uni.hideLoading()
				}, error => {

				})
			},
			//时间选择
			dateChange(e,flag){
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getHiddenDanger('true')
				}
			},
			//类型选中
			chooseLike(i1) {
				console.log(i1)
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				// console.log(this.i2[1])
				switch (this.i2[1]) {
					case 0:
						console.log("问题隐患")
						this.initWarningCate = '问题隐患'
						break
					case 1:
						console.log("整改闭合率")
						this.initWarningCate = '整改闭合率'
						break
					case 2:
						console.log("每周巡检")
						this.initWarningCate = '每周巡检'
						break
				}
				this.getHiddenDanger('true')
			},
			//点击已读
			changeRead(id) {
				let opts = {
					url: this.api + '/msg/QualityWarning.php',
					method: 'POST'
				}
				let param = {
					flag: 'changeRead',
					id: id,
					userId: that.userId
				}
				let isLoading = true
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					uni.showToast({
						title: '消息已读',
						icon: 'none',
						duration: 1500
					});
					isLoading = false
					setTimeout(res => {
						uni.hideLoading()
						this.getHiddenDanger(isLoading)
					}, 1500);
				}, error => {

				})
			},
			//勾选
			checkboxChange(e, item) {
				var list = this.list,
					id = e.detail.value; //卡片id
				let box = (item.checked = !item.checked);
			},
			//触发悬浮按钮内事件
			trigger(e) {
				// console.log()
				let indexOf = e.index
				this.selectcard = [];
				let noticeList = []
				noticeList = Object.values(this.warningList)
				noticeList.forEach(item => {
					if (item.checked == true) {
						this.selectcard.push(item.id);
					}
				})
				// console.log(this.selectcard)
				if (indexOf == 0) { //删除
					// console.log(this.selectcard)
					let opts = {
						url: this.api + '/msg/QualityWarning.php',
						method: 'POST'
					}
					let param = {
						flag: 'deleteWarning',
						selectcard: JSON.stringify(this.selectcard),
						userId: that.userId
					}
					let isLoading = false //是否需要加载动画
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res)
						uni.showModal({
							title: '提示',
							content: '是否确认删除预警消息！',
							showCancel: true,
							cancelText: '取消',
							confirmText: '确认',
							success: res => {
								if (res.confirm) {
									uni.showToast({
										title: '删除成功！',
										icon: 'none'
									});
									this.getHiddenDanger(isLoading)
								}
							}
						});
					}, error => {
						console.log(error);
					})
				}
			},
			fabClick() {

			},
		}
	}
</script>

<style lang="scss">
	page{
		background-color: #efeff4;
	}
	/* 头条小程序组件内不能引入字体 */
	/* #ifdef MP-TOUTIAO */
	@font-face {
		font-family: uniicons;
		font-weight: normal;
		font-style: normal;
		src: url('~@/static/uni.ttf') format('truetype');
	}

	/* #endif */

	page{
		background-color: #efeff4;
	}

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

	.reTipRed {
		display: block;
		background: #f00;
		border-radius: 50%;
		width: 0.6rem;
		height: 0.6rem;
		top: -0.2rem;
		right: -0.2rem;
		position: absolute;
		z-index: 4;
	}

	.reTipNone {
		display: block;
		// background:#f00;
		border-radius: 50%;
		width: 0.6rem;
		height: 0.6rem;
		top: -0.2rem;
		right: -0.2rem;
		position: absolute;
		z-index: 4;
	}
</style>
