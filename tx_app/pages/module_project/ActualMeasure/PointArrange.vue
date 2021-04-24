<template>
	<view>
		<uni-list>
		    <uni-list-item  title="检查项目:混凝土结构工程" ></uni-list-item>
		    <uni-list-item  title="检查人员:某某某" ></uni-list-item>
		</uni-list>
		<view style="text-align: center;padding-top: 15px;">
			<view id="budian" style="width: 100%;">
				<view>
					<image class="Pic" mode="scaleToFill" :src="src"></image>
				</view>
					
				<view>
					<button style="width: 300px;height: 40px;background-color: #00BFFF;" @click="toPointSetting">实测布点</button>
				</view>
					
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
				src: 'https://vkceyugu.cdn.bspapp.com/VKCEYUGU-uni-app-doc/6acec660-4f31-11eb-a16f-5b3e54966275.jpg',
				currentData: '',
				cardParam: '',
				projectId: ''
			};
		},
		onReady() {
		},
		onLoad(option) {
			this.currentData = JSON.parse(option.currentData)
			this.cardParam = JSON.parse(option.cardParam)
			this.projectId = option.projectId
		},
		methods: {
			toPointSetting() {
				let currentStr = JSON.stringify(this.currentData)
				let cardParamStr = JSON.stringify(this.cardParam)
				uni.navigateTo({
					url:`PointSetting?currentData=${cardParamStr}`+`&projectId=${this.projectId}`+`&cardParam=${cardParamStr}`
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
</style>
