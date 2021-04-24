<template>
	<view>
		<ChooseLits :list="list" :arr="arr" @chooseLike="chooseLike"></ChooseLits>
		<view class="example-body">
			<view class="example-box">
				<uni-card v-for="(item,index) in cardList" :key="index" :title="item.title" :isFull="true" :isShadow="true" :note="item.extra" :extra="item.rightCornerInfo" @click="clickCard">
					<view class="flex main-box">
						<!-- <view class="child-box">
							<view class="font-custom">20</view>
							<view>项目总数</view>
						</view> -->
						<view class="child-box1 flex" style="flex-direction:column;justify-content: center;">
							<view>整改累计数：20</view>
							<view>未整改累计数：4</view>
							<view class="font-right">2018.6.2</view>
						</view>
					</view>
				</uni-card>
			</view>
		</view>
		<lb-picker ref="picker1" v-model="selectObj" mode="selector" :list="tabBarArray" :dataset="{ name: 'label3' }" @confirm="handleConfirm" @cancel="handleCancel">
		</lb-picker>
	</view>
</template>
 
<script>
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade2.vue'
	import uniCard from '@/components/uni-app/uni-data-card/uni-card.vue'
	import LbPicker from '@/components/common/lb-picker/index.vue'
	export default {
		props: {
		  // cardDataList: {
		  //   type: Array,
		  //   default: []
		  // },
		},
		components: {
			uniCard,
			ChooseLits,
			LbPicker
		},
		data() {
			return {
				cardList: this.cardList,
				list: ['时间', '项目','验收项'],
				arr: [
					['起始时间', '截止时间'],
					['A工程', 'B工程', 'C工程', 'D工程','A工程', 'B工程', 'C工程', 'D工程','A工程', 'B工程', 'C工程', 'D工程'],
					['基础', '钢筋']
				],
				i2: [0, 0, 0],
				cardList: [{
					id: 1,
					title: 'A工程',
					content: '',
					rightCornerInfo: '20%',
					extra: '点击卡片可查看详情'
				}, {
					id: 2,
					title: 'B工程',
					content: '',
					rightCornerInfo: '40%',
					extra: '点击卡片可查看详情'
				},{
					id: 3,
					title: 'C工程',
					content: '',
					rightCornerInfo: '60%',
					extra: '点击卡片可查看详情'
				},{
					id: 4,
					title: 'D工程',
					content: '',
					rightCornerInfo: '60%',
					extra: '点击卡片可查看详情'
				},{
					id: 5,
					title: 'E工程',
					content: '',
					rightCornerInfo: '60%',
					extra: '点击卡片可查看详情'
				},{
					id: 6,
					title: 'F工程',
					content: '',
					rightCornerInfo: '60%',
					extra: '点击卡片可查看详情'
				}],
				selectObj: '',
				tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'],
			};
		},
		onNavigationBarButtonTap() {
			this.$refs.picker1.show()
		},
		onReady() {
			let _this = this
			uni.setNavigationBarTitle({
				title: _this.tabBarArray[1]
			})
		},
		methods:{
			//点击卡片跳转页面
			clickCard() {
				uni.navigateTo({
					url:`AbnormalAcceptDetail`
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.main-box {
		height: 80px;
	}
	.child-box {
		padding-left: 10px;
	}
	.child-box1 {
		flex: 1;
		text-align: center;
	}
	.font-custom {
		font-size: 30px;
		text-align: center;
		font-weight: bold;
	}
	
	.example-body {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: center;
		padding: 0;
		font-size: 14px;
		background-color: #ffffff;
	}
	
	.example-box {
		margin: 12px 0;
	}
	
	.uni-card {
	    display: flex;
	    flex: 1;
	    box-shadow: 0 0 0 rgba(0, 0, 0, 0);
	    margin: 12px 15px !important;
	    background-color: #ffffff;
	    position: relative;
	    flex-direction: column;
	    border-radius: 5px;
	    overflow: hidden;
	}
	
	.uni-card--shadow {
	    position: relative;
	    box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.1);
	}
	
	@charset "UTF-8";
	
	/* #ifndef APP-NVUE */
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4;
		min-height: 100%;
		height: auto;
	}
	
	view {
		font-size: 14px;
		line-height: inherit;
	}
	
	.example {
		padding: 0 15px 15px;
	}
	
	.example-info {
		padding: 15px;
		color: #3b4144;
		background: #ffffff;
	}
	
	.example-body {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: center;
		padding: 0;
		font-size: 14px;
		background-color: #ffffff;
	}
	
	/* #endif */
	.example {
		padding: 0 15px;
	}
	
	.example-info {
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		padding: 15px;
		color: #3b4144;
		background-color: #ffffff;
		font-size: 14px;
		line-height: 20px;
	}
	
	.example-info-text {
		font-size: 14px;
		line-height: 20px;
		color: #3b4144;
	}
	
	.example-body {
		flex-direction: column;
		padding: 15px;
		background-color: #ffffff;
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
	
	.word-btn--hover {
		background-color: #4ca2ff;
	}
	
	.example-body {
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		padding: 1px 0;
	}
	
	.example-box {
		margin: 12px 0;
	}
	
	.image-box {
		/* #ifndef APP-NVUE */
		display: flex;
		flex-direction: column;
		/* #endif */
		height: 350rpx;
		overflow: hidden;
	}
	
	.image {
		/* #ifndef APP-NVUE */
		width: 100%;
		height: 100%;
		/* #endif */
		flex: 1;
	}
	
	.content-box {
		padding-top: 20rpx;
	}
	
	.content-box-text {
		font-size: 12px;
		line-height: 22px;
	}
	
	.footer-box {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		justify-content: space-between;
		flex-direction: row;
	}
	
	.footer-box__item {
		align-items: center;
		padding: 2px 0;
		font-size: 12px;
		color: #666;
	}
	
	/deep/.uni-card__content--pd {
	    padding: 12px 12px 4px 12px !important;
	}
	
	.font-right {
		text-align: right;
	}
</style>
