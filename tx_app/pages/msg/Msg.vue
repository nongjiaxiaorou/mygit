<template>
	<view class="message">
		
		<uni-list>
			<uni-list-item title="质量预警" :note="warningText" showArrow thumb="../../static/images/zlyj.png" thumb-size="lg" to="QualityWarning"
			 :show-badge="true" :badge-text="warningNum" badgeType="error" />
			<text class="time">2020-05-01 10:30</text>
			</uni-list-item>
		</uni-list>

		<uni-list>
			<uni-list-item title="通知待办" :note="noticeText" to="TodoNotice" showArrow thumb="../../static/images/tzdb.png" thumb-size="lg"
			 :show-badge="true" :badge-text="noticeNum" badgeType="error" />
			<text class="time">2020-05-01 10:30</text>
			</uni-list-item>
		</uni-list>
	</view>
</template>

<script>
	let that = null
	export default {
		data() {
			return {
				userId: '',
				announceText: '当前合计0条',
				warningNum: '0',
				warningText: '当前合计0条',
				noticeNum: '0',
				noticeText:'当前合计0条'
			};
		},
		components: {},
		onLoad: function(option) {
			that = this
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					// console.log(res.data.userId)
					that.userId = res.data.userId
				}
			})
		},
		onShow() {
			this.getMsgNum()
		},
		mounted() {
			this.getMsgNum()
		},
		methods: {
			//获取待办事件数量
			getMsgNum() {
				let opts1 = {
					url: this.api + '/msg/Msg.php',
					method: 'POST'
				}
				let param1 = { 
					flag: 'getMsgNum',
					userId: that.userId
				}
				let isLoading1 = true //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					console.log(res.data)
					// this.announce = res.data.announceTotal
					this.announceText = '当前合计' + res.data.announceTotal + '条'
					
					this.warningNum = res.data.warning
					this.warningText = '当前合计' + res.data.warningTotal + '条'
					
					this.noticeNum = res.data.notice
					this.noticeText = '当前合计' + res.data.noticeTotal + '条'
					uni.hideLoading()
				}, error => {
					console.log(error);
				})
			}
		}
	};
</script>

<style lang="scss">
	page {
		background-color: #F5F4F2;
	}

	.message {
		.uni-list {
			margin-top: 20px;
		}
	}

	.time {
		display: flex;
		justify-content: flex-end;
		color: #999999;
		margin-right: 6%;
	}
</style>
