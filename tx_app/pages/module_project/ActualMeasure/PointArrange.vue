<template>
	<view>
		<uni-list>
		    <uni-list-item  :title="'检查项目:' + cardParam.inspectItem" ></uni-list-item>
		    <uni-list-item  :title="'检查人员:' + cardParam.inspectPerson" ></uni-list-item>
		</uni-list>
		<view style="text-align: center;padding-top: 15px;">
			<view id="budian" style="width: 100%;">
				<view>
					<image class="Pic" mode="scaleToFill" :src="measureSrc"></image>
				</view>
					
				<view>
					<button style="width: 300px;height: 40px;background-color: #00BFFF; font-size: 28rpx; " @click="toPointSetting"><h3>实测布点</h3></button>
				</view>
					
			</view>
		</view>
		<view class="handDrawFather">
			<view class="">
				<h3 style="font-size: 30rpx;">手绘布点:{{picName}}</h3>
			</view>
			<view class="">
				<image style="width: 300px; height: 200px;" :src="handDrawSrc" mode=""></image>
			</view>
		</view>
	</view> 
</template>

<script>
	import uniList from '@/components/uni-app/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni-app/uni-list-item/uni-list-item.vue'
	export default {
		components: {
			uniList,
			uniListItem
		},
		data() {
			return {
				measureSrc: '../../../static/images/null.jpg',  // 实测布点图
				handDrawSrc: '../../../static/images/null.jpg', // 手绘布点图
				picName: '暂无图片',
				currentData: '',
				cardParam: '',
				projectId: '',
				proTimeStamp: '',
				section: '',
				buildNum: '',
				floor: '',
				unit: '',
				buildSelData: {},
				pointStatus: '',
			};
		},
		onReady() {
			const that = this
			const result = uni.getStorageSync ('buildInfo');
			console.log(result)
			if (result) {
				that.buildSelData = result
				that.section = result.section
				that.buildNum = result.build
				that.floor = result.floor
				that.unit = result.unit
			}
			const result1 = uni.getStorageSync ('userInfo');
			if (result) {
				that.proTimeStamp = result1.proTimeStamp
			}
			that.getFloorPic ();
			that.getPointStatus();
		},
		onLoad(option) {
			this.currentData = JSON.parse(option.currentData)
			this.cardParam = JSON.parse(option.cardParam)
			console.log(this.currentData)
			console.log(this.cardParam)
			this.projectId = option.projectId
		},
		methods: {
			toPointSetting() {
				let currentStr = JSON.stringify(this.currentData)
				let cardParamStr = JSON.stringify(this.cardParam)
				uni.setStorage({
					key:'cardParam',
					data: this.cardParam,					
				})
				uni.setStorage({
					key: 'PicSrc',
					data: this.measureSrc
				})
				uni.navigateTo({
					url:`PointSetting3?currentData=${cardParamStr}`+`&projectId=${this.projectId}`+`&cardParam=${cardParamStr}` +`&measureSrc=${this.measureSrc}`
				})
			},
			getFloorPic () {
				let that = this
				let opts = {
					url: this.api+'/module_project/SelectBuildInfo.php',
					method: 'POST' 
				}
				let param = {
					flag: 'getFloorPic',
					proTimeStamp: that.proTimeStamp,
					section: that.section,
					buildNum: that.buildNum,
					floor: that.floor
				} 
				console.log(param)
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					that.measureSrc = that.imageUrl_pc + '/floorPic/' + res.data.floorPicName
					// that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})									
			},
			getHandDrawPic () {
				const that = this;
				const opts = {
					url: that.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				const param = {
					flag: 'getHandDrawPic',
					proTimeStamp: that.proTimeStamp,
					inspectPosition:that.section + that.buildNum +that.floor + that.unit,
					inspectItem: that.cardParam.inspectItem,
					
				}
				let isLoading = false;
				that.myRequest.httpRequest (opts , param , isLoading).then (res => {
					console.log(res.data)
					if (res.data.code) {
						if (that.pointStatus == '初测') {
							if (res.data.manualPrimaryPic != '') {
								that.handDrawSrc = that.imageUrl_pc + '/floorPic/manualPic/' + res.data.manualPrimaryPic;
								that .picName = res.data.primaryPicName
							}
						} else {
							if (res.data.manualRetestPic != '') {
								that.handDrawSrc = that.imageUrl_pc + '/floorPic/manualPic/' + res.data.manualRetestPic;
								that .picName = res.data.retestPicName
							}
						}
						console.log(that.picName);
					} else {}
				}) , error => {
					console.log(error);
				}
			},
			getPointStatus() {
				let that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'getPointStatus',
					measureId:this.cardParam.id,
					projectId: this.currentData.projectId
				} 
				console.log(that.pointStatus)
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						that.pointStatus = res.data.data[0].status
						that.getHandDrawPic ();
						console.log(res.data.data[0].status)
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为'+res.data.data[0].status+'状态！'
						});
					}else{
						this.pointStatus = '初测'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为初测状态!'
						});
						that.getHandDrawPic ();
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
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
	
	.Pic {
		width: 300px;
		height: 200px;
		background-color: #eeeeee;
	}
	.handDrawFather {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
</style>
