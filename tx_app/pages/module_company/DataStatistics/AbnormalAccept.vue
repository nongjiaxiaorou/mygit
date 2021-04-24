<template>
	<view class="example-body">
		<view class="example-box">
			<uni-card v-for="(item,index) in cardList" :key="index" :title="item.title" :isFull="true" :isShadow="true" :note="item.extra" :extra="item.rightCornerInfo" @click="clickCard" v-show="item.isShow===true">
				<view class="flex main-box">
					<view class="child-box">
						<view class="font-custom">20</view>
						<view>项目总数</view>
					</view>
					<view class="child-box1 flex" style="flex-direction:column;justify-content: center;">
						<view>非正常验收工序累计数：20</view>
						<view>当日非正常验收工序：4</view>
						<view class="font-right">2018.6.2</view>
					</view>
				</view>
			</uni-card>
		</view>
	</view>
</template>
 
<script>
	import uniCard from '@/components/uni-app/uni-data-card/uni-card.vue'
	export default {
		props: {
		  cardDataList: {
		    type: Array,
		    default: []
		  },
		},
		components: {
			uniCard
		},
		data() {
			return {
				cardList: this.cardDataList,
			};
		},
		methods: {
			//点击卡片跳转页面
			clickCard() {
				uni.navigateTo({
					// url:`SelectDescribe?formData=${JSON.stringify(this.formData)}&defectStr=''`
					url:`AbnormalAcceptContent`
				})
			},
		},
		watch: {
			"cardDataList": {
				handler(newValue, oldValue) {
				  this.cardList = newValue
				}
			}
		}
	}
</script>

<style lang="scss">
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
