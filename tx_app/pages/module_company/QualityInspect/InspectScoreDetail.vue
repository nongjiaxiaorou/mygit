<template>
	<view>
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">查看评分</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">检查问题</view>
		</view>
		<!-- 页面0 -->
		<view v-if="Inv == 0">
			<view class="example-body" style="margin-top: 4%;">
				<view class="flex" style="margin-left: 8%;">
					<view class="align-center justify-center" style="flex: 0.2;text-align: center;">
						<view class="number">2</view>
					</view>
					<view class="align-center justify-center title">{{formData.inspectObj}}</view>
				</view>
				<view class="" style="margin-left: 6%;margin-right: 10%;">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">检查名称：</view>
						<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectName" placeholder="检查名称"
							 disabled="true" /></view>
					</view>
				</view>
				
				<view class="" style="margin-left: 6%;margin-right: 10%;">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">初始总分：</view>
						<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.actualMarks" placeholder="初始总分"
							 disabled="true" /></view>
					</view>
				</view>
				
				<view class="" style="margin-left: 6%;margin-right: 10%;">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">修正总分：</view>
						<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.correctMarks" placeholder="修正总分"
							 disabled="true" /></view>
					</view>
				</view>
				
				<view class="uni-title uni-common-pl"></view>
				<view class="uni-list mt-1" v-if="true">
					<view>
						<view class="uni-flex uni-row">
							<view class="flex-item uni-border-bottom">检查问题</view>
							<view class="flex-item uni-border-x">满分值</view>
							<view class="flex-item uni-border-bottom">扣分值</view>
						</view>
				
						<scroll-view :scroll-top="scrollTop" scroll-y="true" class="scroll-Y" :style="{height: height+'rpx'}">
							<block v-for="(item,index) in questionList" :key="index">
								<view class="uni-flex uni-row">
									<view class="flex-item ">{{item.inspectQues}}</view>
									<view class="flex-item uni-border-x">{{item.fullMarks}}</view>
									<view class="flex-item">
										<input :value="item.deductMarks" @input="onInput($event,item.quesId)" />
									</view>
								</view>
							</block>
						</scroll-view>
					</view>
				</view>
			</view>
		</view>
		<!-- 页面1 -->
		<view class="" v-else="Inv == 1">
			<view class="flex" style="height: 10%;margin-top: 40rpx;">
				<view class="flex-item second-title" style="flex: 0.5;border: 1rpx solid #C9C9C9;">问题缺陷</view>
				<view class="flex-item second-title" style="flex: 0.5;border: 1rpx solid #C9C9C9;">问题整改</view>
			</view>
			
			<scroll-view :scroll-top="scrollTop" scroll-y="true" class="scroll-Y" :style="{height: height1+'rpx'}">
				<view class="grid">
					<uni-grid :column="2" :highlight="true" @change="uploadModel">
						<uni-grid-item v-for="(item, index) in list" :index="index" :key="index">
							<view class="grid-item-box">
								<image :src="item.url" class="image" mode="aspectFill" />
								<text class="text">{{ item.text}}</text>
							</view>
						</uni-grid-item>
					</uni-grid>
				</view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import uniToast from "@/components/uni-app/uni-toast/uni-toast.vue"
	import uniIcons from '@/components/uni-app/uni-icons/icons.js';
	import uniGrid from "@/components/uni-app/uni-grid/uni-grid.vue"
	import uniGridItem from "@/components/uni-app/uni-grid-item/uni-grid-item.vue"
	let that = null
	export default {
		components: {
			uniFab,
			uniNavBar,
			uniCard,
			uniIcons,
			uniGrid,
			uniGridItem
		},
		data() {
			return {
				Inv: 0,
				list: [{
						url: '/static/images/null.jpg',
						text: '质量隐患期限整改通知单',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '质量隐患期限整改回复',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '施工方案及现场问整改通知单',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '施工方案及现场问题整改回复',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '资料问题通知单',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '资料问题整改回复',
						path:''
					},
				],
				formData:{},
				scrollTop: 0,
				old: {
					scrollTop: 0
				},
				draftList: [], //草稿
				finishList: [], //已完成
				questionList: [],
				selectcard: [],
				height:'',
				height1:'',
				fileCategory:'',
				imageList:[],
				qualityPicList:[],
				qualityReplyPicPicList:[],
				constructPicList:[],
				constructReplyPicPicList:[],
				filePicList:[],
				fileReplyPicList:[],
			}
		},
		onLoad:function(option){
			that = this
			uni.getSystemInfo({
				success: function(data) {
					// 将其赋值给this
					that.height = data.windowHeight/1.15;
					that.height1 = data.windowHeight*1.5;
				}
			})
			this.formData = JSON.parse(option.data)
			// console.log(this.formData)
		},
		onShow() {
			uni.$on('updateScoreDetail',res=>{
				that.getScorePic()
				uni.$off('updateScoreDetail')
			})
		},
		mounted() {
			this.inspectQues()
			this.getScorePic()
		},
		methods: {
			//根据活动名称获取检查小组
			inspectQues(){
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/InspectScoreDetail.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'getInspectQues',
					formData:JSON.stringify(this.formData)
				}
				let isLoading1 = true //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res)
					let oldArr = res.data.data
					this.questionList = []
					for(let i=0;i<oldArr.length;i++){
						this.questionList.push({
							quesId:oldArr[i].quesId,
							inspectQues:oldArr[i].inspectQues,
							fullMarks:oldArr[i].fullMarks,
							deductMarks:oldArr[i].deductMarks,
						})
					}
					uni.hideLoading()
					isLoading1 = false
				}, error => {
					console.log(error);
				})
			},
			//修改扣分值
			onInput(e,quesId){
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/InspectScoreDetail.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'changeMarks',
					quesId:quesId,
					deductMarks:e.target.value
				}
				let isLoading1 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res)
				}, error => {
					console.log(error);
				})
			},
			//获取资料照片
			getScorePic() {
				let opts = {
					url: this.api + '/module_company/QualityInspect/InspectScoreDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getScorePic',
					taskTimeStamp : that.formData.taskTimeStamp
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data.qualityPic) 
					this.qualityPicList = []
					this.qualityReplyPicPicList = []
					this.constructPicList = []
					this.constructReplyPicPicList = []
					this.filePicList = []
					this.fileReplyPicList = []
					if (res.data.qualityPic != undefined) {
						let qualityArr = res.data.qualityPic[0].split('||')
						let qualityLen = qualityArr.length-1
						for (let i = 0; i < qualityLen; i++) {
							qualityArr[i] = this.imageUrl+'/scorePic/'+qualityArr[i]
							this.qualityPicList.push(qualityArr[i])
						}
						this.list[0].url = qualityArr[0]
					}
					if (res.data.qualityReplyPic != undefined) {
						let qualityReplyPicArr = res.data.qualityReplyPic[0].split('||')
						let qualityReplyPicLen = qualityReplyPicArr.length-1
						for (let i = 0; i < qualityReplyPicLen; i++) {
							qualityReplyPicArr[i] = this.imageUrl+'/scorePic/'+qualityReplyPicArr[i]
							this.qualityReplyPicPicList.push(qualityReplyPicArr[i])
						}
						this.list[1].url = qualityReplyPicArr[0]
					}
					if (res.data.constructPic != undefined) {
						let constructPicArr = res.data.constructPic[0].split('||')
						let constructPicLen = constructPicArr.length-1
						for (let i = 0; i < constructPicLen; i++) {
							constructPicArr[i] = this.imageUrl+'/scorePic/'+constructPicArr[i]
							this.constructPicList.push(constructPicArr[i])
						}
						this.list[2].url = constructPicArr[0]
					}
					if (res.data.constructReplyPic != undefined) {
						let constructReplyPicArr = res.data.constructReplyPic[0].split('||')
						let constructReplyPicLen = constructReplyPicArr.length-1
						for (let i = 0; i < constructReplyPicLen; i++) {
							constructReplyPicArr[i] = this.imageUrl+'/scorePic/'+constructReplyPicArr[i]
							this.constructReplyPicPicList.push(constructReplyPicArr[i])
						}
						this.list[3].url = constructReplyPicArr[0]
					}
					if (res.data.filePic != undefined) {
						let filePicArr = res.data.filePic[0].split('||')
						let filePicLen = filePicArr.length-1
						for (let i = 0; i < filePicLen; i++) {
							filePicArr[i] = this.imageUrl+'/scorePic/'+filePicArr[i]
							this.filePicList.push(filePicArr[i])
						}
						this.list[4].url = filePicArr[0]
					}
					if (res.data.fileReplyPic != undefined) {
						let fileReplyPicArr = res.data.fileReplyPic[0].split('||')
						let fileReplyPicLen = fileReplyPicArr.length-1
						for (let i = 0; i < fileReplyPicLen; i++) {
							fileReplyPicArr[i] = this.imageUrl+'/scorePic/'+fileReplyPicArr[i]
							this.fileReplyPicList.push(fileReplyPicArr[i])
						}
						this.list[5].url = fileReplyPicArr[0]
					}
				}, error => {
			
				})
			},
			//选中图片上传模块
			uploadModel(e){
				let indexOf = e.detail.index
				if(indexOf==0){//质量
					this.fileCategory = 'quality'
					this.imageList = JSON.stringify(this.qualityPicList)
				}else if(indexOf==1){//质量回复
					this.fileCategory = 'qualityReply'
					this.imageList = JSON.stringify(this.qualityReplyPicPicList)
				}else if(indexOf==2){//施工
					this.fileCategory = 'construct'
					this.imageList = JSON.stringify(this.constructPicList)
				}else if(indexOf==3){//施工回复
					this.fileCategory = 'constructReply'
					this.imageList = JSON.stringify(this.constructReplyPicPicList)
				}else if(indexOf==4){//资料
					this.fileCategory = 'file'
					this.imageList = JSON.stringify(this.filePicList)
				}else if(indexOf==5){//资料回复
					this.fileCategory = 'fileReply'
					this.imageList = JSON.stringify(this.fileReplyPicList)
				}
				// console.log(this.imageList)
				uni.navigateTo({
					url:`ScoreUploadPicture?id=${that.formData.insTaskId}&fileCategory=${this.fileCategory}&imageList=${this.imageList}&taskTimeStamp=${that.formData.taskTimeStamp}`
				})
			},
			//返回上页
			back() {
				uni.navigateBack()
			},
		},
	}
</script>

<style lang="scss">
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
	
	.inv-h {
		font-size: 30upx;
		flex: 1;
		text-align: center;
		color: #c9c9c9;
		height: 80upx;
		line-height: 80upx;
	}
	
	.inv-h-se {
		color: #5ba7ff;
		border-bottom: 4upx solid #5ba7ff;
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
	
	.flex-item {
		width: 33.3%;
		text-align: center;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 16rpx;
		border-bottom: 1rpx solid #e4e5e5;
	}
	
	.second-title {
		height: 60rpx;
		text-align: center;
		line-height: 60rpx;
		border-bottom: 1rpx solid #e4e5e5;
	}
	
	.uni-border-x {
		// border-bottom: 1rpx solid #494F54;
		border-left: 1rpx solid #e4e5e5;
		border-right: 1rpx solid #e4e5e5;
	}
	
	.scroll-Y {
		height: 300rpx;
	}
	
	.scroll-view_H {
		white-space: nowrap;
		width: 100%;
	}
	
	.scroll-view-item {
		height: 300rpx;
		line-height: 300rpx;
		text-align: center;
		font-size: 36rpx;
	}
	
	.scroll-view-item_H {
		display: inline-block;
		width: 100%;
		height: 300rpx;
		line-height: 300rpx;
		text-align: center;
		font-size: 36rpx;
	}
	
	.number {
		width: 100%;
		height: 92%;
		background: url(../../../static/images/rank.png);
		background-size:contain;
		padding-top: 8%;
		font-weight: 600;
		font-size: xx-large;
	}
	
	.title {
		flex: 0.8;
		font-size:larger;
		text-align: center;
		font-size: x-large;
		height: 120rpx;
		line-height: 120rpx;
	}
	.grid {
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: center;
		padding: 0;
		font-size: 14px;
		background-color: #ffffff;

		.image {
			width: 220rpx;
			height: 220rpx;
		}

		.text {
			font-size: 26rpx;
			margin-top: 10rpx;
		}

		.grid-item-box {
			flex: 1;
			/* position: relative;
			*/
			/* #ifndef APP-NVUE */
			display: flex;
			/* #endif */
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 15px 0;
		}
	}
</style>
