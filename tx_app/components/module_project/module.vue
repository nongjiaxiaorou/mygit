<template>
	<view>
		<!-- 功能模块 -->
		<view class="uni-list"></view>
		<view style="margin-top: 32rpx;">
			<ju-navigator-grid
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
			juNavigatorGrid,
		},
		data() {
			return {
				list: [{
						icon: '../../static/images/scsl.png',
						title: '实测实量',
						url:'../../pages/module_project/ActualMeasure/ActualMeasure'
					},
					{
						icon: '../../static/images/jcys.png',
						title: '检查验收',
						url:'../../pages/module_project/InspectAccept/InspectAccept'
					},
					{
						icon: '../../static/images/mzxj.png',
						title: '每周巡检',
						url:'../../pages/module_project/WeeklyInspect/WeeklyInspect'
					},
					{
						icon: '../../static/images/sygl.png',
						title: '砼专项管理',
						url:'../../pages/module_project/SpecialAdmin/SpecialAdmin'
					},
					{
						icon: '../../static/images/wbjc.png',
						title: '外部检查',
						url:'../../pages/module_project/ExternalInspect/ExternalInspect'
					},
					{
						icon: '../../static/images/sjtj.png',
						title: '数据统计',
						url:'../../pages/module_project/DataStatistics/problems'
					},
					{
						icon: '../../static/images/sjtj.png',
						title: '测试模块',
						url:'../../pages/module_project/WeeklyInspect/SelectItem'
					},
					{
						icon: '../../static/images/sign.png',
						title: 'test',
						url:'../../pages/test'
					}
				],
				modulePath:''
			}
		},
		methods: {
			//进入模块
			onPress(e) {
			    console.log(e)
				uni.getStorage({
					key: 'buildInfo',
					success:(res) => {//有缓存 直接跳转到对应模块路径
						console.log(res)
						this.modulePath = e.url
						uni.navigateTo({
							url:this.modulePath
						});
						
					},fail:(res)=>{//没缓存 跳转工作定位页面
						console.log(res)
						this.modulePath = `../../pages/module_project/SelectBuildInfo?modulePath=${e.url}`
						uni.navigateTo({
							url:this.modulePath
						});
						console.log(e.url)
					}
				})
			},
		}
	}
</script>

<style lang="scss">
</style>
