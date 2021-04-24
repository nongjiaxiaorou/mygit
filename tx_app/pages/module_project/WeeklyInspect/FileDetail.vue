<template>
	<view style="background-color: #FFFFFF;">
		<!-- 页面0 -->
		<view >
			<view class="grid" style="margin-top: 6%;">
				<uni-grid :column="2" @change="uploadModel">
					<uni-grid-item v-for="(item, index) in list" :index="index" :key="index">
						<view class="grid-item-box">
							<image :src="item.url" class="image" mode="aspectFill" />
							<text class="text">{{ item.text }}</text>
						</view>
					</uni-grid-item>
				</uni-grid>
			</view>
		</view>
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniDrawer from '@/components/uni-app/uni-drawer/uni-drawer.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import uniIcons from '@/components/uni-app/uni-icons/icons.js'
	import uniGrid from "@/components/uni-app/uni-grid/uni-grid.vue"
	import uniGridItem from "@/components/uni-app/uni-grid-item/uni-grid-item.vue"
	let that = null
	export default {
		components: {
			uniFab,
			uniNavBar,
			uniDrawer,
			uniCard,
			uniIcons,
			uniGrid,
			uniGridItem
		},
		data() {
			return {
				list: [{
						url: '/static/images/null.jpg',
						text: '上传方案资料问题汇总',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '上传会议照片',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '上传会议纪要',
						path:''
					},
					{
						url: '/static/images/null.jpg',
						text: '其他照片',
						path:''
					},
				],
				currentData: [],
				cardId:'',
				fileTimeStamp:'',
				noticeData:[],
				uploadFlag:'',
				fileCategory:'',//资料照片类型
				imageList:[],
				problemPicList:[],
				meetingPicList:[],
				summaryPicList:[],
				otherPicList:[],
				pageFrom:'',//标识从什么页面进入
				defectStr:''
			}
		},
		onLoad:function(option) {
			that = this
			that.cardId = option.id
		},
		onShow() {
			uni.$on('updateFileUpload',res=>{
				this.getFilePic()
				uni.$off('updateFileUpload')
			})
		},
		mounted() {
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					// console.log(res.data)
					that.currentData = res.data
					that.currentData = JSON.stringify(res.data)
					// console.log(JSON.parse(this.currentData))
				}
			})
			this.getFilePic()
		},
		methods: {
			//获取资料照片
			getFilePic() {
				console.log(that.cardId)
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/FileDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getFilePic',
					cardId : that.cardId
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					this.problemPicList = []
					this.meetingPicList = []
					this.summaryPicList = []
					this.otherPicList = []
					this.fileTimeStamp = res.data.timeStamp
					if(!res.data.code){
						return
					}
					if (res.data.problemPic != '') {
						let problemArr = res.data.problemPic[0].split("||")
						let problemLen = problemArr.length-1
						for (let i = 0; i < problemLen; i++) {
							problemArr[i] = this.imageUrl+'/programmePic/'+problemArr[i]
							this.problemPicList.push(problemArr[i])
						}
						this.list[0].url = problemArr[0]
					}
					if (res.data.meetingPic != '') {
						let meetingArr = res.data.meetingPic[0].split("||")
						let meetingLen = meetingArr.length-1
						for (let i = 0; i < meetingLen; i++) {
							meetingArr[i] = this.imageUrl+'/programmePic/'+meetingArr[i]
							this.meetingPicList.push(meetingArr[i])
						}
						this.list[1].url = meetingArr[0]
					}
					if (res.data.summaryPic != '') {
						let summaryArr = res.data.summaryPic[0].split("||")
						let summaryLen = summaryArr.length-1
						for (let i = 0; i < summaryLen; i++) {
							summaryArr[i] = this.imageUrl+'/programmePic/'+summaryArr[i]
							this.summaryPicList.push(summaryArr[i])
						}
						this.list[2].url = summaryArr[0]
					}
					if (res.data.otherPic != '') {
						let otherArr = res.data.otherPic[0].split("||")
						let otherLen = otherArr.length-1
						for (let i = 0; i < otherLen; i++) {
							otherArr[i] = this.imageUrl+'/programmePic/'+otherArr[i]
							this.otherPicList.push(otherArr[i])
						}
						this.list[3].url = otherArr[0]
					}
				}, error => {
			
				})
			},
			//选中图片上传模块
			uploadModel(e){
				let indexOf = e.detail.index
				if(indexOf==0){//问题汇总
					this.fileCategory = 'problem'
					this.imageList = JSON.stringify(this.problemPicList)
				}else if(indexOf==1){//会议照片
					this.fileCategory = 'meeting'
					this.imageList = JSON.stringify(this.meetingPicList)
				}else if(indexOf==2){//会议纪要
					this.fileCategory = 'summary'
					this.imageList = JSON.stringify(this.summaryPicList)
				}else if(indexOf==3){//其他照片
					this.fileCategory = 'other'
					this.imageList = JSON.stringify(this.otherPicList)
				}
				uni.navigateTo({
					url:`FileUploadPicture?fileCategory=${this.fileCategory}&imageList=${this.imageList}&timeStamp=${that.fileTimeStamp}`
				})
			},
		}
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
		background-color: #f8f8f8;
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

	/* 处理抽屉内容滚动 */
	.scroll-view-box {
		flex: 1;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
	}
	
	.grid {
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: center;
		padding: 0;
		font-size: 14px;
		// margin-top: 40rpx;
		background-color: #ffffff;

		.image {
			width: 280rpx;
			height: 280rpx;
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
