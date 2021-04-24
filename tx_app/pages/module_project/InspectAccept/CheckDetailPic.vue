<template>
	<view>
		<!-- 刷新页面后的顶部提示框 -->
		<view class="tips" :class="{ 'tips-ani': tipShow }">当前有{{lists.length}}条违章条目,请点击上传违章照片</view>
		<!-- 基于 uni-list 的页面布局 -->
		<uni-list>
			<uni-list-item direction="row" v-for="(item,index) in lists" :key="item.id" :title="item.title" clickable>
				<!-- 通过body插槽定义列表内容显示 -->
				<template v-slot:body>
					<view class="uni-list-box uni-content" @touchstart.native="uploadPicFile(item)">
						<view :class="[item.title=='重大问题'?'uni-title1':'uni-title','uni-ellipsis-2']">{{item.title}}</view>
						<view class="uni-note">
							<text>{{item.itemDescribe}}</text>
						</view>
					</view>
				</template>
				<!-- 通过footer插槽自定义图片右侧显示 -->
				<template v-slot:footer>
					<view class="uni-thumb" style="margin: 0;">
						<image :src="item.picPath === '../../../static/images/null.jpg'?'../../../static/images/null.jpg' : item.picPath" mode="aspectFill" :data-src="item.picPath" @tap="previewImage(index,item.picPath)"></image>
					</view>
				</template>
			</uni-list-item>
		</uni-list>
	</view>
</template>

<script>
	import uniList from "@/components/uni-app/uni-list/uni-list.vue"
	import uniListItem from "@/components/uni-app/uni-list-item/uni-list-item.vue"
	export default {
		components: {
			uniList,
			uniListItem
		},
		data() {
			return {
				lists: [], // 列表数据
				tipShow: true, // 是否显示顶部提示框
				formData: {},
				inspectCardParam: {}
				
			};
		},
		onLoad(option) {
			this.formData = JSON.parse(option.formData)
			this.inspectCardParam = JSON.parse(option.inspectCardParam)
			this.getNewsList();
		},
		mounted() {
			this.getViolationConFunc()
		},
		//返回上一页面触发
		onBackPress(event) {
			uni.$emit('sendList', this.lists)//传给平行子组件
		},
		methods: {
			getNewsList(reload) {
				// console.log(this.formData.violationItem)
				if(this.lists.length==0){
					for(var i=0;i<this.formData.violationItem.length;i++){
						let item = this.formData.violationItem[i]
						let title = item.split('||')[0]
						let itemDescribe = item.split('||')[1]
						let number = item.split('||')[2]
						this.lists.push({
							index: i,
							title: title,
							itemDescribe: itemDescribe,
							number: number,
							picPath: '../../../static/images/null.jpg'
						})
					}
				}
				
			},
			//点击预览图片
			previewImage: function(index,url) {
				var imgArr = [];
				imgArr.push(url);
				let that = this
				// console.log(url)
				uni.previewImage({
					current: index,
					urls: imgArr,
					longPressActions: {  
						itemList: ['保存图片到相册'],  
						success: function(ress) {
							 uni.downloadFile({
								url: imgArr[ress.index],
								success: (res) =>{
									if (res.statusCode === 200){
										uni.saveImageToPhotosAlbum({
											filePath: res.tempFilePath,
											success: function() {
												//uniapp提供的消息提示框。
												uni.showToast({
													title: "保存成功",
													icon: "success"
												});
											},
											fail: function() {
												uni.showToast({
													title: "保存失败，请稍后重试",
													icon: "none"
												});
											}
										});
									}
								}
							})
						},  
						fail: function(res) {  
							console.log(res.errMsg);  
						}  
					}
				})
			},
			uploadPicFile(e) {
				// console.log(e)
				let _self = this
				uni.chooseImage({
					count: 1,
					sizeType:['copressed'],
					success(res) {
						//因为有一张图片， 输出下标[0]， 直接输出地址
						var imgFiles = res.tempFilePaths[0]
						_self.lists[e.index].picPath = imgFiles
					}
				})
			},
			//获取违章条目内容
			getViolationConFunc() {
				let opts = {
					url: this.api+'/module_project/InspectAccept/CheckDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getDetailItemInfo',
					noticeTimeStamp: this.inspectCardParam.noticeTimeStamp
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){ 
						for(var i=0;i<res.data.data.length;i++){
							let item = res.data.data[i]
							let picPath = this.imageUrl+'/inspectPic/'+ item.picName
							this.lists.push({
								index: i,
								id: item.id,
								title: item.title,
								itemDescribe: item.itemDescribe,
								number: item.number,
								picPath: (item.picName==='')?'../../../static/images/null.jpg':picPath,
							})
						}
						console.log(this.lists)
					}
				}, error => {
					console.log(error);
				})
			}
		}
	};
</script>

<style lang="scss">
	@import '@/common/uni-ui.scss';

	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4;
		min-height: 100%;
		height: auto;
	}

	.uni-list-box {
		margin-top: 0;
	}

	.uni-content {
		padding-right: 10px;
	}

	.uni-note {
		display: flex;
		margin: 0;
		justify-content: space-between;
	}

	.tips {
		color: #67c23a;
		font-size: 14px;
		line-height: 40px;
		text-align: center;
		background-color: #f0f9eb;
		height: 0;
		opacity: 0;
		transform: translateY(-100%);
		transition: all 0.3s;
	}

	.tips-ani {
		transform: translateY(0);
		height: 40px;
		opacity: 1;
	}

	.content {
		width: 100%;
		display: flex;
	}

	.list-picture {
		width: 100%;
		height: 145px;
	}

	.thumb-image {
		width: 100%;
		height: 100%;
	}

	.ellipsis {
		display: flex;
		overflow: hidden;
	}

	.uni-ellipsis-1 {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	.uni-ellipsis-2 {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	.uni-content{
	    justify-content: normal;
	}
	.uni-title{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    font-size: 13px;
	    font-weight: bold;
	    color: black;
		padding: 0;
	}
	.uni-title1{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    font-size: 13px;
		margin-bottom: 8px;
	    font-weight: bold;
	    color: #e71010;
	}
	
</style>
