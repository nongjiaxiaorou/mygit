<template>
	<view>
		<view class="tips" :class="{ 'tips-ani': tipShow }">当前有{{lists.length}}条违章条目,请点击上传违章照片</view>
		<uni-list>
			<uni-list-item direction="row" v-for="(item,index) in lists" :key="index" :title="item.title" clickable>
				<!-- 通过body插槽定义列表内容显示 -->
				<template v-slot:body>
					<view class="uni-list-box uni-content" @tap="chooseImage(item)">
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
				sourceTypeIndex: 2,
				sourceType: ['拍照', '相册', '拍照或相册'],
				sizeTypeIndex: 2,
				sizeType: ['压缩', '原图', '压缩或原图'],
				countIndex: 8,
				count: [1, 2, 3, 4, 5, 6, 7, 8, 9]
			};
		},
		onLoad(option) {
			this.formData = JSON.parse(option.formData)
			this.lists = this.formData.itemList
			// console.log(this.formData.itemList)
			this.getNewsList();
		},
		methods: { 
			getNewsList() {
				 if(this.lists.length==0){
					var itemStr = JSON.stringify(this.formData.violationItem)
					var items = JSON.parse(itemStr) 
					for(var i=0;i<items.length;i++){
						var itemArr = items[i].split('||')
						var title = itemArr[0]
						var itemDescribe = itemArr[1]
						var number = itemArr[2]
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
			chooseImage: async function(e) {
				var _self = this
				// #ifdef APP-PLUS
				// TODO 选择相机或相册时 需要弹出actionsheet，目前无法获得是相机还是相册，在失败回调中处理
				if (this.sourceTypeIndex !== 2) {
					let status = await this.checkPermission();
					if (status !== 1) {
						return;
					}
				}
				// #endif
			
				if (this.lists.length === 9) {
					let isContinue = await this.isFullImg();
					console.log("是否继续?", isContinue);
					if (!isContinue) {
						return;
					}
				}
				uni.chooseImage({
					// sourceType: sourceType[this.sourceTypeIndex],
					// sizeType: sizeType[this.sizeTypeIndex],
					count: 1,
					success: (res) => {
						var imgFiles = res.tempFilePaths[0]
						_self.lists[e.index].picPath = imgFiles
						// this.imageList = this.imageList.concat(res.tempFilePaths)
						// this.$emit('change',this.imageList)
					},
					fail: (err) => {
						// #ifdef APP-PLUS
						if (err['code'] && err.code !== 0 && this.sourceTypeIndex === 2) {
							this.checkPermission(err.code);
						}
						// #endif
						// #ifdef MP
						uni.getSetting({
							success: (res) => {
								let authStatus = false;
								switch (this.sourceTypeIndex) {
									case 0:
										authStatus = res.authSetting['scope.camera'];
										break;
									case 1:
										authStatus = res.authSetting['scope.album'];
										break;
									case 2:
										authStatus = res.authSetting['scope.album'] && res.authSetting['scope.camera'];
										break;
									default:
										break;
								}
								if (!authStatus) {
									uni.showModal({
										title: '授权失败',
										content: 'Hello uni-app需要从您的相机或相册获取图片，请在设置界面打开相关权限',
										success: (res) => {
											if (res.confirm) {
												uni.openSetting()
											}
										}
									})
								}
							}
						})
						// #endif
					}
				})
			},
			onBackPress() {
				uni.$emit('sendChildPageData',{lists:this.lists})
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
	
	.uni-title {
	    font-size: 17px;
	    font-weight: 500;
	    margin-bottom: 0px;
	    line-height: 1.5;
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
