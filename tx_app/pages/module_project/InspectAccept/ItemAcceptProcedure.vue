<template>
	<view>
		<!-- 右侧显示角标、switch -->
		<uni-view nvue="true" class="uni-section">
			<uni-view class="uni-section__head">
				<uni-view class="uni-section__head-tag line"></uni-view>
			</uni-view>
			<uni-view class="uni-section__content">
				<uni-text class="uni-section__content-title distraction">
					<span>实名制工序验收</span>
				</uni-text>
			</uni-view>
		</uni-view>
		<uni-list>
			<view v-for="(item,index) in processAcceptList" :key="processAcceptList.id">
				<view class="flex">
					<span class="iconfont icon-gongcheng icon-custom"></span>
					<uni-list-item style="flex: 1;" :title="item.content" link @tap="toItemProcessAccept(item)" :showBadge="item.BadgeType" :badgeText="item.badge" ></uni-list-item>
				</view>
			</view>
		</uni-list>
		<uni-view nvue="true" class="uni-section">
			<uni-view class="uni-section__head">
				<uni-view class="uni-section__head-tag line"></uni-view>
			</uni-view>
			<uni-view class="uni-section__content">
				<uni-text class="uni-section__content-title distraction">
					<span>工序交接验收</span></uni-text>
			</uni-view>
		</uni-view>
		<uni-list>
			<view class="flex" v-for="(item,index) in handoverAcceptList" :key="item.id">
				<span class="iconfont icon-gongcheng icon-custom"></span>
				<uni-list-item style="flex: 1;" :title="item.content" link @tap="toItemProcessAccept(item)" :showBadge="item.BadgeType" :badgeText="item.badge" ></uni-list-item>
			</view>
		</uni-list>
	</view>
</template>

<script>
	import uniList from "@/components/uni-app/uni-list/uni-list.vue"
	import uniListItem from "@/components/uni-app/uni-list-item/uni-list-item.vue"
	import uniIcons from "@/components/uni-app/uni-icons/uni-icons.vue"
	export default {
		components: {
			uniList,
			uniListItem,
			uniIcons
		},
		data() {
			return {
				imageList: [],
				imageBaseList: [],
				cardParam: '',
				flag: '',
				fileCategory:'',//资料照片类型
				timeStamp:'',
				lists: [],
				src: 'https://img-cdn-qiniu.dcloud.net.cn/uniapp/images/shuijiao.jpg',
				badge: '8',
				extraIcon: {color: 'rgb(143, 143, 148)',size: '22',type: 'home-filled'},
				processAcceptList : [{id:'1',content:'基础', BadgeType:'error',badge:'0'},{id:'2',content:'钢筋', BadgeType:'error',badge:'0'},{id:'3',content:'模板', BadgeType:'error',badge:'0'},{id:'4',content:'铝模', BadgeType:'error',badge:'0'},{id:'5',content:'混凝土', BadgeType:'error',badge:'0'},{id:'6',content:'防水', BadgeType:'error',badge:'0'},{id:'7',content:'砌体', BadgeType:'error',badge:'0'},{id:'8',content:'门窗填塞', BadgeType:'error',badge:'0'},{id:'9',content:'抹灰', BadgeType:'error',badge:'0'},{id:'10',content:'外墙基层', BadgeType:'error',badge:'0'},{id:'25',content:'外墙面层', BadgeType:'error',badge:'0'},{id:'11',content:'楼地面', BadgeType:'error',badge:'0'},{id:'12',content:'饰面砖', BadgeType:'error',badge:'0'},{id:'13',content:'装配式', BadgeType:'error',badge:'0'},{id:'26',content:'涂饰', BadgeType:'error',badge:'0'}],
				handoverAcceptList:[{id:'14',content:'外墙拆架', BadgeType:'error',badge:'0'},{id:'15',content:'拆模验收', BadgeType:'error',badge:'0'},{id:'16',content:'防水验收', BadgeType:'error',badge:'0'},{id:'17',content:'后浇带', BadgeType:'error',badge:'0'},{id:'18',content:'土建/防水', BadgeType:'error',badge:'0'},{id:'19',content:'防水/土建', BadgeType:'error',badge:'0'},{id:'20',content:'管道/沉箱', BadgeType:'error',badge:'0'},{id:'21',content:'抹灰/精装', BadgeType:'error',badge:'0'},{id:'22',content:'砌体/抹灰', BadgeType:'error',badge:'0'},{id:'23',content:'饰面基层', BadgeType:'error',badge:'0'}],
				proTimeStamp: '',
				buildSelData: {},
				currentData:[],
				inspectStr: {}
			}
		},
		onLoad: function(option) {
			console.log(option)
			this.currentData = option.currentData
			this.cardParam = option.cardParam
			this.proTimeStamp = option.proTimeStamp
			this.buildSelData = JSON.parse(option.buildSelData)
			this.getAcceptType()
		},
		mounted() {
		},
		methods: {
			getAcceptType() {
				let opts = {
					url: this.api+'/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				} 
				let param = {
					flag: 'getAcceptType',
					measureId: JSON.parse(this.cardParam).id
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						for(var key in res.data.data){
							for(var i=0;i<this.processAcceptList.length;i++){
								if(this.processAcceptList[i].id==key){
									console.log(key);
									console.log(i);
									this.processAcceptList[i].badge = res.data.data[key].split('|')[0]
								}
							}
						}
						console.log(this.processAcceptList);
						this.inspectStr = res.data.data
					} 
				}, error => {
					console.log(error);
				})
			},
			//进入工序验收界面
			toItemProcessAccept(item) { 
				console.log(item)
				console.log(this.inspectStr);
				item.inspectStr = this.inspectStr[item.id]==undefined?'':this.inspectStr[item.id] 
				let itemStr = JSON.stringify(item)
				console.log(itemStr)
				let buildSelData = JSON.stringify(this.buildSelData)
				let currentData = this.currentData
				console.log(currentData);
				console.log(buildSelData);
				uni.navigateTo({
					url:`AcceptCheck?currentData=${currentData}`+`&cardParam=${this.cardParam}`+`&proTimeStamp=${this.proTimeStamp}`+`&buildSelData=${buildSelData}`+`&itemStr=${itemStr}`+`&enterFlag=normal`
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

	.button-box {
		height: 30px;
		line-height: 30px;
		font-size: 12px;
		background: #007AFF;
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
	
	.uni-section {
	    position: relative;
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    margin-top: 10px;
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	    padding: 0 10px;
	    height: 50px;
	    background-color: #f8f8f8;
	    font-weight: 400;
	}
	
	.line {
	    height: 15px;
	    background-color: silver;
	    border-radius: 5px;
	    width: 3px;
	}
	
	.uni-section__head {
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-pack: center;
	    -webkit-justify-content: center;
	    justify-content: center;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	    margin-right: 10px;
	}
	
	.icon-custom {
	    align-items: center;
	    margin-top: 10px;
	    padding-left: 10px;
	}
	
	/deep/ .uni-list-item__container {
	    padding: 13px 32px;
	}
	
	.uni-section {
	    margin-top: 0px;
	}
</style>