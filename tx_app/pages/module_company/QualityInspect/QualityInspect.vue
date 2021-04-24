<template>
	<view>

		<uni-list>
			<view class="flex">
				<view class="" style="flex: 0.9;">
					<uni-list-item to="InspectInfo">
						<view slot="header" class="slot-box">
							<image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image>
						</view>
						<text slot="body" class="slot-box slot-text" style="margin-left: 8%;">检查信息</text>
					</uni-list-item>
				</view>
				<view class="" style="flex: 0.1;display: flex;align-items: center;justify-content: center;">
					<!-- <uni-badge text="123" type="error" size="small" /> -->
				</view>
			</view>
		</uni-list>

		<uni-list>
			<view class="flex">
				<view class="" style="flex: 0.9;">
					<uni-list-item to="InspectList">
						<view slot="header" class="slot-box">
							<image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image>
						</view>
						<text slot="body" class="slot-box slot-text" style="margin-left: 8%;">质量检查列表</text>
					</uni-list-item>
				</view>
				<view class="" style="flex: 0.1;display: flex;align-items: center;justify-content: center;">
					<uni-badge :text="listNum" type="error" size="small" />
				</view>
			</view>
		</uni-list>
		
		<uni-list>
			<view class="flex">
				<view class="" style="flex: 0.9;">
					<uni-list-item to="InspectScore">
						<view slot="header" class="slot-box">
							<image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image>
						</view>
						<text slot="body" class="slot-box slot-text" style="margin-left: 8%;">检查评分汇总</text>
					</uni-list-item>
				</view>
				<view class="" style="flex: 0.1;display: flex;align-items: center;justify-content: center;">
					<uni-badge :text="scoreNum" type="error" size="small" />
				</view>
			</view>
		</uni-list>

	</view>
</template>

<script>
	import uniList from '@/components/uni-app/uni-list/uni-list';
	import uniListItem from '@/components/uni-app/uni-list-item/uni-list-item';
	import uniBadge from '@/components/uni-app/uni-badge/uni-badge';
	let that = null
	export default {
		data() {
			return {
				userId:'',
				listNum:'',
				scoreNum:''
			};
		},
		components: {
			"uni-list": uniList,
			"uni-list-item": uniListItem,
			uniBadge
		},
		onLoad: function(option){
			that = this
			uni.getStorage({
				key:'userInfo',
				success: (res) => {
					// console.log(res.data.userId)
					that.userId = res.data.userId
				}
			})
			console.log("1")
		},
		onShow() {
			console.log("2")
			this.getTaskNum()
		},
		mounted() {

		},
		methods: {
			//获取待办事件数量
			getTaskNum(){
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/QualityInspect.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'getTaskNum',
					userId:this.userId
				}
				let isLoading1 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res)
					this.listNum = res.data.listNum
					this.scoreNum = res.data.scoreNum
				}, error => {
					console.log(error);
				})
			}
		}
	};
</script>

<style>
	.uni-list {
		margin-top: 30px;
	}

	.slot-box {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		align-items: center;
		justify-content: center;
	}

	.slot-image {
		/* #ifndef APP-NVUE */
		display: block;
		/* #endif */
		margin-left: 20px;
		width: 40px;
		height: 40px;
	}

	.slot-text {
		flex: 0.8;
		font-size: 16px;
		/* margin-right: 10px; */
	}
</style>
