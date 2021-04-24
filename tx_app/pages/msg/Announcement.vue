<template>
	<view>
		<view class="example-body">
			<view v-for="(item,index) in announceList" :key="item.id" class="example-box">
				<uni-card @click='draftDetail(item)'>
					<view class="content-box-text" @click="!item.readFlag && changeRead(item.id)">
						{{item.announceCate}}
						<view class=" uni-list-card" style="color: #121416;">{{item.content}}</view>
						<view class=" uni-list-card" style="float: right;color: black;">{{item.announceTime}}</view>
					</view>
				</uni-card>
			</view>
		</view>
	</view>
</template>

<script> 
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	let that = null
	export default {
		components: {
			ChooseLits,
			uniCard,
		},
		data() {
			return {
				announceList: [],
				userId: '',
				isShow: true,
			}
		},
		onLoad() {
			this.getAnnounce('true')
		},
		methods: {
			//获取问题隐患
			getAnnounce(load) {
				let opts = {
					url: this.api + '/msg/Announce.php',
					method: 'POST'
				}
				let param = {
					flag: 'getAnnounce'
				}
				let isLoading = load
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					this.announceList = res.data.data
					uni.hideLoading()
				}, error => {

				})
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
