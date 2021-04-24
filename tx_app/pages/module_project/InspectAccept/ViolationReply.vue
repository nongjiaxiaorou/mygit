<template>
	<view>
		<view v-show="personPost=='施工员'">
			<upload-image @change='changeImage' :imageListPar="imageList"></upload-image>
			<button type="primary" @click="submit">确定</button>
		</view>
		<view class="list">
			<view style="width: 100%;">
				<uni-view class="uni-page-head-title">图片查看</uni-view>
			</view>
			<uni-list class="uni-list--waterfall">
				<uni-list-item :border="false" class="uni-list-item--waterfall" v-for="(item,index) in lists" :key="index">
					<!-- 通过header插槽定义列表左侧图片 -->
					<template v-slot:header>
						<view class="uni-thumb shop-picture shop-picture-column">
							<image :src="item.picPath" @tap="previewImage(index,item.picPath)" mode="aspectFill"></image>
						</view>
					</template>
					<!-- 通过body插槽定义商品布局 -->
					<view slot="body" class="shop">
						<!-- <view>
							<view class="uni-title">
								<text class="uni-ellipsis-2">{{ item.name }}</text>
							</view>
						</view> -->
						<view class="uni-textarea">
							<textarea style="height: 60px; text-align: center;" placeholder-style="color:#F76260" :value="item.content" @blur="saveReplyContent(index,$event)"/>
						</view>
					</view>
					
				</uni-list-item>
			</uni-list>
			<view class="flex button-box" v-show="personPost=='质量员'">
				<button type="primary" @click="updateStateFunc('通过')">通过</button>
				<button type="primary" @click="updateStateFunc('不通过')">不通过</button>
			</view>
		</view>
	</view>
</template>

<script>
	import uploadImage from './UploadImage.vue';
	import uniList from "@/components/uni-app/uni-list/uni-list.vue"
	import uniListItem from "@/components/uni-app/uni-list-item/uni-list-item.vue"
	import { pathToBase64, base64ToPath } from '../../../js_sdk/gsq-image-tools/image-tools/index.js'
	export default {
		components: {
			uploadImage,
			uniList,
			uniListItem
		},
		data() {
			return {
				imageList: [],
				imageBaseList: [],
				violationId: '',
				flag: '',
				fileCategory:'',//资料照片类型
				timeStamp:'',
				lists: [],
				src: 'https://img-cdn-qiniu.dcloud.net.cn/uniapp/images/shuijiao.jpg',
				personPost: '施工员'
			}
		},
		onLoad: function(option) {
			this.violationId = option.violationId
			this.getViolationFunc()
			this.authorityCheck()
		},
		mounted() {
		},
		methods: {
			authorityCheck() {
			},
			//提交
			submit() {
				let opts = {
					url: this.api + '/module_project/InspectAccept/ViolationReply.php',
					method: 'POST'
				}
				let param = {
					flag: 'saveReplyPic',
					imageBaseList: JSON.stringify(this.imageBaseList),
					violationId: this.violationId
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						uni.showToast({
							title: '您已保存回复图片及内容！',
							icon: 'none'
						})
						setTimeout(function(){
							uni.navigateBack()
						},2000)
					}
				}, error => {

				})
			},
			//接收返回的图片数组
			changeImage(urls) {
				this.imageBaseList = []
				for (let i = 0; i < urls.length; i++) {
					pathToBase64(urls[i]).then(base64 => {
						this.imageBaseList.push(base64)
						// console.log(this.imageBaseList)
					})
					.catch(error => {
						console.error(error)
					})
				}
			}, 
			//点击预览图片
			previewImage: function(index,url) {
				var imgArr = [];
				imgArr.push(url);
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
			// 保存修改的内容
			saveReplyContent(index,e) {
				this.lists[index].content = e.detail.value
				let that = this
				uni.showModal({
				    title: '修改',
				    content: '是否修改回复内容',
				    success: function (res) {
				        if (res.confirm) {
							let opts = {
								url: that.api + '/module_project/InspectAccept/ViolationReply.php',
								method: 'POST'
							}
							let param = {
								flag: 'changeReplyContent',
								violationId: that.violationId,
								itemList: JSON.stringify(that.lists)
							}
							let isLoading = false
							that.myRequest.httpRequest(opts, param, isLoading).then(res => {
								// console.log(res.data)
								if(res.data.code){
									uni.showToast({
										title: '回复内容修改完成！',
										icon: 'none'
									})
									that.getViolationFunc()
								}
							}, error => {
							
							})
				        } else if (res.cancel) {
				            uni.showToast({
				            	title: '您已取消修改回复内容！',
				            	icon: 'none'
				            })
				        }
				    }
				});
			},
			//获取违章图片及内容
			getViolationFunc() {
				let opts = {
					url: this.api + '/module_project/InspectAccept/ViolationReply.php',
					method: 'POST'
				}
				let param = {
					flag: 'getReplyPic',
					violationId: this.violationId
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						this.lists = []
						for(var i=0;i<res.data.data.length;i++){
							let item = res.data.data[i]
							let picPath = this.imageUrl+'/inspectReplyPic/'+ item.picName
							this.lists.push({
								index: i,
								content: item.content,
								picPath: picPath,
							})
						}
					}else{
						this.lists = []
						this.lists.push({
							index: 0,
							content: '暂无回复内容',
							picPath: '../../../static/images/null.jpg',
						})
					}
				}, error => {
				
				})
			},
			//更新违章条目状态
			updateStateFunc(state) {
				let opts = {
					url: this.api + '/module_project/InspectAccept/ViolationReply.php',
					method: 'POST'
				}
				let param = {
					flag: 'updateViolationItemState',
					violationId: this.violationId,
					state: state
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						var pages = getCurrentPages();
						var prevPage = pages[pages.length - 2]; //上一个页面
						var object={
							flag:"updateState",
						}
						prevPage.$vm.receivePageParam(object);//重点$vm 返回上一页面并传参
						uni.navigateBack()
					}
				}, error => {
				
				})
			}
		}
	}
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

	.shop {
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}

	.shop-picture { 
		width: 110px;
		height: 110px;
	}

	.shop-picture-column {
		width: 100%;
		height: 170px;
		margin-bottom: 10px;
	}

	.shop-price {
		margin-top: 5px;
		font-size: 12px;
		color: #ff5a5f;
	}

	.shop-price-text {
		font-size: 16px;
	}

	.hot-tag {
		background: #ff5a5f;
		border: none;
		color: #fff;
	}

	.uni-link {
		flex-shrink: 0;
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


	// 默认加入 scoped ，所以外面加一层提升权重
	.list {
		.uni-list--waterfall {

			/* #ifndef H5 || APP-VUE */
			// 小程序 编译后会多一层标签，而其他平台没有，所以需要特殊处理一下
			/deep/ .uni-list {
				/* #endif */
				display: flex;
				flex-direction: row;
				flex-wrap: wrap;
				padding: 5px;
				box-sizing: border-box;

				/* #ifdef H5 || APP-VUE */
				// h5 和 app-vue 使用深度选择器，因为默认使用了 scoped ，所以样式会无法穿透
				/deep/
				/* #endif */
				.uni-list-item--waterfall {
					width: 50%;
					box-sizing: border-box;

					.uni-list-item__container {
						padding: 5px;
						flex-direction: column;
					}
				}

				/* #ifndef H5 || APP-VUE */
			}

			/* #endif */
		}
	}
	
	.uni-page-head-title {
		display: block;
	    margin: 10px auto;
	    font-size: 15px;
	    height: 51px;
	    line-height: 51px;
	    color: #BEBEBE;
	    box-sizing: border-box;
	    border-bottom: 1px solid #D8D8D8;
	    width: 40%;
	    text-align: center;
	}
	
	.uni-textarea {
	    width: 100%;
	    background: #FFF;
	    height: 60px;
	}
	
	.uni-textarea-textarea {
	    resize: none;
	    background: none;
	    color: inherit;
	    opacity: 1;
	    -webkit-text-fill-color: currentcolor;
	    font: inherit;
	    line-height: inherit;
	    letter-spacing: inherit;
	    text-align: inherit;
	    text-indent: inherit;
	    text-transform: inherit;
	    text-shadow: inherit;
		height: 60px !important;
	}
	
	.button-box {
		margin-top: 10px;
		button {
			width: 35%;
		}
	}
	
</style>