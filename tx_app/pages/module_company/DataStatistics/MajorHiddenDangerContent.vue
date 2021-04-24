<template>
	<view>
		<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
		<view class="example-body">
			<view class="example-box">
				<uni-card v-for="(item,index) in cardList" :key="index" :title="item.title" :isFull="true" :isShadow="true" :note="item.extra" :extra="item.rightCornerInfo" @click="clickCard(item)" v-show="item.isShow===true">
					<view class="flex main-box">
						<!-- <view class="child-box">
							<view class="font-custom">20</view>
							<view>项目总数</view>
						</view> -->
						<view class="child-box1 flex" style="flex-direction:column;justify-content: center;">
							<view>整改累计数：{{item.totalNum}}  当日数：{{item.rectificationNum}}</view>
							<view>整改闭合率：{{item.rightCornerInfo}}</view>
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
				list: ['时间', '项目'],
				arr: [
					['起始时间', '截止时间']
				],
				i2: [0, 0, 0],
				cardList: [],
				selectObj: '',
				// tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'],
				tabBarArray: ['整改闭合率','一般隐患','重大隐患'],
				currentData: {}
			};
		},
		onLoad:function(option) {
			this.currentData = JSON.parse(option.currentData)
			this.getMajorHiddenDanger()
			this.getProject()
			console.log(this.currentData)
		},
		onNavigationBarButtonTap() {
			this.$refs.picker1.show()
		},
		methods:{
			//点击卡片跳转页面
			clickCard(item) {
				let currentData = JSON.stringify(item)
				uni.navigateTo({
					url:`MajorHiddenDangerDetail?currentData=${currentData}`
				})
			},
			//获取重大隐患数据 未选择时间
			getMajorHiddenDanger() {
				let opts = {
					url: this.api + '/module_company/DataStatistics/RectificationClose.php',
					method: 'POST'
				}
				let param = {
					flag: "getMajorHiddenDangerContent",
					company: this.currentData.title
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						this.cardList = []
						for(var i=0;i<res.data.data.length;i++){
							this.cardList.push({
								id: res.data.data[i].id,
								title: res.data.data[i].title,
								rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
								rectificationNum: res.data.data[i].rectificationNum,
								totalNum: res.data.data[i].totalNum,
								extra: '点击卡片可查看详情',
								isShow: true
							})
						}
					}
				}, error => {
				
				})
			},
			//获取重大隐患数据 已选择时间
			getMajorHiddenSelDate() {
				let opts = {
					url: this.api + '/module_company/DataStatistics/RectificationClose.php',
					method: 'POST'
				}
				let param = {
					flag: "getMajorHiddenSelDate",
					company: this.currentData.title,
					startTime: this.startTime,
					endTime: this.endTime,
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						this.cardList = []
						for(var i=0;i<res.data.data.length;i++){
							this.cardList.push({
								id: res.data.data[i].id,
								title: res.data.data[i].title,
								rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
								rectificationNum: res.data.data[i].rectificationNum,
								totalNum: res.data.data[i].totalNum,
								extra: '点击卡片可查看详情',
								isShow: true
							})
						}
					}
				}, error => {
				
				})
			},
			dateChange(e,flag){
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getMajorHiddenSelDate()
				}
			},
			//获取工程
			getProject(){
				let opts = {
					url: this.api + '/module_company/DataStatistics/RectificationClose.php',
					method: 'POST'
				}
				let param = {
					flag: 'getProject',
					company: this.currentData.title
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						let Arr = []
						for(var i=0;i<res.data.data.length;i++){
							Arr.push(res.data.data[i].projectName)
						}
						// console.log(Arr)
						this.arr.push(Arr)
					}
				}, error => {
				
				})
				
			},
			chooseLike(i1) {
				// console.log(i1)
				if(i1[0]==1){
					let length = this.cardList.length
					for(var i=0;i<length;i++){
						this.cardList[i].isShow = true
						if(this.cardList[i].title==this.arr[1][i1[1]]) continue 
							this.cardList[i].isShow = false
					}
				}
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				// console.log(this.i2)
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
