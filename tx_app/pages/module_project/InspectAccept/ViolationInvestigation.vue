<template>
	<view class="list">
		<view class="tips" :class="{ 'tips-ani': tipShow }">当前有{{lists.length}}违章条目</view>
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">初查</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">复查</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<uni-list class="mg-10">
				<uni-list-item :class="[item.rectificationStatus=='通过'?'list-pass':'list-unpass']" direction="row" v-for="(item,index) in lists" :key="item.id" :title="item.title" :note="item.violationCon">
					<!-- 通过header插槽定义列表左侧的图片显示，其他内容通过List组件内置属性实现-->
					<template v-slot:header>
						<view class="uni-thumb">
							<image :src="item.picPath === '../../../static/images/null.jpg'?'../../../static/images/null.jpg' : item.picPath" mode="aspectFill" :data-src="item.picPath" @tap="previewImage(index,item.picPath)"></image>
						</view>
					</template>
					<template v-slot:body>
						<view class="uni-list-box uni-content">
							<view :class="[item.title=='重大问题'?'uni-title1':'uni-title']">{{item.title}}</view>
							<view class="uni-note">
								<text>{{item.violationCon}}</text>
							</view>
						</view>
					</template>
				</uni-list-item>
			</uni-list>
		</view>
		
		<!-- 页面1 -->
		<view v-show="Inv == 1">
			<uni-list class="mg-10">
				<uni-list-item :class="[item.rectificationStatus=='通过'?'list-pass':'list-unpass']" direction="row" v-for="(item,index) in lists" :key="item.id" :title="item.title" :note="item.violationCon">
					<!-- 通过header插槽定义列表左侧的图片显示，其他内容通过List组件内置属性实现-->
					<template v-slot:header>
						<view class="uni-thumb">
							<image :src="item.picPath === '../../../static/images/null.jpg'?'../../../static/images/null.jpg' : item.picPath" mode="aspectFill" :data-src="item.picPath" @tap="previewImage(index,item.picPath)"></image>
						</view>
					</template>
					<template v-slot:body>
						<view class="uni-list-box uni-content" @tap="ToViolationinReply(item)">
							<view :class="[item.title=='重大问题'?'uni-title1':'uni-title']">{{item.title}}</view>
							<view class="uni-note">
								<text>{{item.violationCon}}</text>
							</view>
						</view>
					</template>
				</uni-list-item>
			</uni-list>
		</view>
		
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
				Inv: 0,
				lists: [], // 列表数据
				waterfall: true, // 布局方向切换
				tipShow: true, // 是否显示顶部提示框
				pageSize: 20, // 每页显示的数据条数
				current: 1, // 当前页数	
				cardParam: {},
				inspectCardParam: {}
				
			};
		},
		onLoad(option) {
			// 初始化页面数据
			let enterFlag = option.enterFlag
			if(enterFlag=='normal'){
				this.cardParam = JSON.parse(option.cardParam)
				this.inspectCardParam = JSON.parse(option.inspectCardParam)
				this.getNewsList()
			}else if(enterFlag=='message'){
				this.getRectificationParam(option.cardTimeStamp)
			}
		},
		methods: {
			//点击预览图片
			previewImage: function(index,url) {
				var imgArr = [];
				imgArr.push(url);
				console.log(url)
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
			getNewsList() {
				let opts = {
					url: this.api+'/module_project/InspectAccept/ViolationInvestigation.php',
					method: 'POST'
				}
				let param = {
					flag: 'getViolationItem',
					noticeTimeStamp: this.inspectCardParam.noticeTimeStamp
				}
				console.log(param)
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						this.lists = []
						for(var i=0;i<res.data.data.length;i++){
							let picPath = this.imageUrl+'/inspectPic/'+ res.data.data[i].picName
							this.lists.push({
								index: i,
								id: res.data.data[i].id,
								title: res.data.data[i].itemType,
								violationCon: res.data.data[i].violationContent,
								picPath: (res.data.data[i].picName==='')?'../../../static/images/null.jpg':picPath,
								rectificationStatus: res.data.data[i].rectificationStatus
							})
						}
					}
				}, error => {
					console.log(error);
				})

			},
			//通知代办进入前获取参数
			getRectificationParam(cardTimeStamp) {
				let opts = {
					url: this.api + '/module_project/InspectAccept/ViolationInvestigation.php',
					method: 'POST'
				}
				let param = {
					flag: 'getRectificationParam',
					noticeCardTimeStamp: cardTimeStamp
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					for(var i=0;i<res.data.data.length;i++){
						if(res.data.data[i].notice!=undefined){
							this.inspectCardParam = res.data.data[i].notice
						}
						if(res.data.data[i].measure!=undefined){
							this.cardParam = res.data.data[i].measure
						}
					}
					this.getNewsList()
					// uni.hideLoading() //隐藏加载中转圈圈
				}, error => {
					console.log(error);
				})
			},
			// 跳转到违章条目内容回复
			ToViolationinReply(item) {
				console.log(item);
				let cardParam = JSON.stringify(this.cardParam)
				let inspectCardParam = JSON.stringify(this.inspectCardParam)
				console.log(inspectCardParam);
				uni.navigateTo({
					url:`ViolationReply?currentData=${this.currentData}`+`&cardParam=${cardParam}`+`&inspectCardParam=${inspectCardParam}`+`&violationId=${item.id}`
				})
			},
			//接收页面传参
			receivePageParam(object) {
				if(!!object){
					this.getNewsList()
				}
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
	
	.mg-10 {
		margin-top: 10px;
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
	
	.uni-title1{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    font-size: 13px;
	    font-weight: bold;
	    color: #e71010;
	}
	
	.uni-title{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
		margin-bottom: 0;
		padding: 0 0;
	    font-size: 13px;
	    font-weight: bold;
	    color: #3b4144;
	}
	
	.uni-list-box {
	    -webkit-box-flex: 1;
	    -webkit-flex: 1;
	    flex: 1;
	    margin-top: 0px;
	}
	
	.uni-note {
		margin-top: 0;
	}
	
	.list-pass {
		border: 2px solid green;
		margin: 3px 0px;
		border-radius: 3px;
	}
	
	.list-unpass {
		border: 2px solid red;
		margin: 3px 0px;
		border-radius: 3px;
	}
</style>
