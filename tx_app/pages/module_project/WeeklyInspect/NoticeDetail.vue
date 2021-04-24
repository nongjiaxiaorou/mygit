<template>
	<view>
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">整改/回复</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">资料</view>
		</view>
		<!-- 页面0 -->
		<view v-if="Inv == 0">
			<view class="example-body">
				<view v-for="item in defectList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true">
						<view class="content-box-text" @click="defectDetail(item)">
							<view class="uni-list-cell uni-list-card">质量缺陷:{{item.defect}}</view>
							<view class="uni-list-cell uni-list-card">发现时间:{{item.createTime}}</view>
							<view class="uni-list-card">整改期限:{{item.endTime}} 00:00:00</view>
						</view>
					</uni-card>
				</view>
			</view>
			<!-- 悬浮按钮 -->
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
		</view>
		<!-- 页面1 -->
		<view class="" v-else="Inv == 1">
			<view class="grid">
				<uni-grid :column="2" :highlight="true" @change="uploadModel">
					<uni-grid-item v-for="(item, index) in list" :index="index" :key="index">
						<view class="grid-item-box">
							<image :src="item.url" class="image" mode="aspectFill" />
							<text class="text">{{ item.text }}</text>
						</view>
					</uni-grid-item>
				</uni-grid>
			</view>
		</view>
		<!-- 抽屉 -->
		<view class="example-body">
			<uni-drawer ref="showLeft" mode="left" :width="320" @change="change($event,'showLeft')">
				<view class="close">
					<view class="scroll-view">
						<scroll-view class="scroll-view-box" scroll-y="true">
							<view class="info-content" v-for="item in 100" :key="item">
								<text>可滚动内容 {{item}}</text>
							</view>
							<view class="close">
								<view class="word-btn" hover-class="word-btn--hover" :hover-start-time="20" :hover-stay-time="70" @click="closeDrawer('showRight')"><text
									 class="word-btn-white">关闭Drawer</text></view>
							</view>
						</scroll-view>
					</view>
				</view>
			</uni-drawer>
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
						text: '上传现场照片',
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
						text: '新增',
						active: false
					},
					{
						iconPath: '/static/images/wancheng.png',
						selectedIconPath: '/static/images/wanchengaed.png',
						text: '完成',
						active: false
					}
				],
				currentData: [],
				cardId:'',
				noticeTimeStamp:'',
				defectList: [] ,//缺陷列表
				majorCate:'',//违章大类
				uploadFlag:'',
				fileCategory:'',//资料照片类型
				imageList:[],
				scenePicList:[],
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
			console.log(that.cardId)
		},
		onShow() {
			uni.$on('updateNotice',res=>{
				this.getFilePic()
				this.getDefect()
				uni.$off('updateNotice')
			})
		},
		mounted() {
			this.getDefect()
			this.getFilePic()
			
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					// console.log(res.data)
					that.currentData = res.data
					that.currentData = JSON.stringify(res.data)
					// console.log(JSON.parse(this.currentData))
				}
			})
		},
		methods: {
			//获取资料照片
			getFilePic() {
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/NoticeDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getFilePic',
					cardId : that.cardId
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					this.scenePicList = []
					this.meetingPicList = []
					this.summaryPicList = []
					this.otherPicList = []
					if (res.data.scenePic != undefined) {
						let sceneArr = res.data.scenePic
						let sceneLen = sceneArr.length
						for (let i = 0; i < sceneLen; i++) {
							this.scenePicList.push(this.commonFunc.b64oblobTourl(sceneArr[i]))
						}
						this.list[0].url = this.commonFunc.b64oblobTourl(sceneArr[0])
					}
					if (res.data.meetingPic != undefined) {
						let meetingArr = res.data.meetingPic
						let meetingLen = meetingArr.length
						for (let i = 0; i < meetingLen; i++) {
							this.meetingPicList.push(this.commonFunc.b64oblobTourl(meetingArr[i]))
						}
						this.list[1].url = this.commonFunc.b64oblobTourl(meetingArr[0])
					}
					if (res.data.summaryPic != undefined) {
						let summaryArr = res.data.summaryPic
						let summaryLen = summaryArr.length
						for (let i = 0; i < summaryLen; i++) {
							this.summaryPicList.push(this.commonFunc.b64oblobTourl(summaryArr[i]))
						}
						this.list[2].url = this.commonFunc.b64oblobTourl(summaryArr[0])
					}
					if (res.data.otherPic != undefined) {
						let otherArr = res.data.otherPic
						let otherLen = otherArr.length
						for (let i = 0; i < otherLen; i++) {
							this.otherPicList.push(this.commonFunc.b64oblobTourl(otherArr[i]))
						}
						this.list[3].url = this.commonFunc.b64oblobTourl(otherArr[0])
					}
					// this.defectList = res.data.data
				}, error => {
			
				})
			},
			//缺陷详情
			defectDetail(val) {
				let data = val
				// console.log(JSON.stringify(data))
				uni.navigateTo({
					url:`DefectDetail?data=${JSON.stringify(data)}`
				})
			},
			//获取缺陷
			getDefect() {
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/NoticeDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getDefect',
					cardId : that.cardId
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data.data)
					this.defectList = res.data.data
					let defectArr = []
					for(let i=0;i<res.data.data.length;i++){
						defectArr[i] = res.data.data[i].defect
					}
					this.defectStr = defectArr.join('||')
					this.noticeTimeStamp = res.data.data[0].timeStamp
				}, error => {

				})
			},
			//触发悬浮按钮内事件
			trigger(e) {
				// console.log(this.defectStr)
				let indexOf = e.index
				if(indexOf==0){//新增
					this.pageFrom = 'notice'
					uni.navigateTo({
						url:`SelectItem?formData=${JSON.stringify(this.defectList)}&pageFrom=${this.pageFrom}&defectStr=${this.defectStr}`
					})
				}else{//完成
					let opts = {
						url: this.api + '/module_project/WeeklyInspect/NoticeDetail.php',
						method: 'POST'
					}
					let param = {
						flag: 'changeNoticeState',
						cardId : that.cardId
					}
					let isLoading = false
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res)
						if(res.data.msg == '未下达'){
							uni.showModal({
								content: '请将通知单下发整改后再进行缺陷完成！',
								success: function (res) {
									if (res.confirm) {
										
									} else if (res.cancel) {
										
									}
								}
							});
						}else{
							if(res.data.msg == '未完成'){
								uni.showToast({
									title: '有缺陷未完成,通知单未完成',
									icon: 'success',
									direction:1500
								});
							}else{
								uni.showToast({
									title: '缺陷完成成功,通知单已完成',
									icon: 'success',
									direction:1500
								});
							}
							setTimeout(res=>{
								uni.$emit('updateNoticeDetail')
								uni.navigateBack()
							},1500)
						}
					}, error => {
					
					})
				}
			},
			fabClick() {
				
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
				uni.redirectTo({
					url: 'WeeklyInspect'
				})
			},
			showDrawer(e) {
				this.$refs[e].open()
			},
			// 关闭窗口
			closeDrawer(e) {
				this.$refs[e].close()
			},
			// 抽屉状态发生变化触发
			change(e, type) {
				console.log((type === 'showLeft' ? '左窗口' : '右窗口') + (e ? '打开' : '关闭'));
				this[type] = e
			},
			//选中图片上传模块
			uploadModel(e){
				let indexOf = e.detail.index
				if(indexOf==0){//现场照片
					this.fileCategory = 'scene'
					this.imageList = JSON.stringify(this.scenePicList)
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
				this.uploadFlag = 'filePic'
				console.log(that.cardId)
				uni.navigateTo({
					url:`UploadPicture?id=${that.cardId}&flag=${this.uploadFlag}&fileCategory=${this.fileCategory}&imageList=${this.imageList}&timeStamp=${that.noticeTimeStamp}`
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
		margin-top: 40rpx;
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
