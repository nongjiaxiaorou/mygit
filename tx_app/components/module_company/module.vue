<template>
	<view>
		<!-- 功能模块 -->
		<view class="uni-list"></view>
		<view style="margin-top: 20rpx;">
			<ju-navigator-grid
			    row-count="1"
			    col-count="4"
			    height="140"
				:list="list"
				@press="onPress"
			/>
		</view>
	</view>
</template>

<script>
	import juNavigatorGrid from "@/components/ju-navigator-grid/ju-navigator-grid.vue"
	export default {
		components:{
			juNavigatorGrid
		},
		data() {
			return {
				list: [
					{
						icon: '../../static/images/scsl.png',
						title: '质量检查',
						url:'../../pages/module_company/QualityInspect/QualityInspect'
					},
					{
						icon: '../../static/images/scsl.png',
						title: '绩效考核',
						url:''
					},
					{
						icon: '../../static/images/scsl.png',
						title: '数据统计',
						url:'../../pages/module_company/DataStatistics/DataStatistics'
					},
				]
			}
		},
		methods: {
			//进入模块
			onPress(e) {
			    console.log(e)
				uni.navigateTo({
					url:e.url
				});
			},
		}
	}
</script>

<style lang="scss">
</style>
