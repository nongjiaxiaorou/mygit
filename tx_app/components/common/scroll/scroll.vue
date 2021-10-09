<template>
	<view>
		<view class="uni-list"></view>
		<!-- 导航栏滚动 -->
		<scroll-view scroll-x="true" class="scroll-row"
		:scroll-into-view="scrollInto" 
		scroll-with-animation style="height: 140rpx;">
			<view  v-for="(item,index) in tabBars" :key="index" 
			class="scroll-row-item px-3 py-2  font-sm"
			:id="'tab'+index" 
			>{{item.name}}
			<view class="badge">
				<uni-badge  :text="item.grey" size="small" />
				<uni-badge  :text="item.red" type="error" size="small" />
			</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue';
	import uniBadge from '@/components/uni-app/uni-badge/uni-badge.vue';
	export default {
		components:{
			uniBadge,
			uniCard
		},
		mounted() {
			uni.$on("scrolldata", res => {
				// console.log(res);
				this.getscrolldata(res)
				uni.$off('scrolldata');
			})
		},
		data() {
			return {
				isShow:true,
				//顶部选项卡
				tabIndex:0,
				scrollInto:'',
				scrollH:500,
				list:{},
				tabBars:[
					{
						name:'逾期隐患',
						grey:'0',//累计
						red:'0'//今日新增
					},
					{
						name:'重大隐患',
						grey:'0',
						red:'0'
					},
					{
						name:'每周巡检',
						grey:'0',
						red:'0'
					},
					{
						name:'整改闭合',
						grey:'0',
						red:'0'
					},
				],
			}
		},
		methods: {
			getscrolldata(e){
				let opts = {
					url: this.api + '/index/DataIndicators.php',
					method: 'POST'
				}
				let param = {
					flag: e.level,
					projectId: e.projectId,
					company:e.companyName
				}
				let isLoading = true //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res);
					const data = JSON.parse(res.data)
					// console.log(data);
					for(let key in data){
						let num = data[key].split("|");
						this.tabBars.forEach(item => {
							if (item.name == key) {
							    item.grey = num[0];
								item.red = num[1];
							}
						});
					}
					uni.hideLoading() //隐藏加载中转圈圈
					this.isloading = false //取消遮罩层
				}, error => {
					console.log(error);
				})
				
			}
			// //切换选项
			// changeTab(index,type){
			// 	if(this.tabIndex===index ){
			// 		return;
			// 	}
			// 	console.log(this.project+type);
			// 	this.tabIndex = index
			// 	this.scrollInto = 'tab'+index
			// },
			
		}
	}
</script>

<style>
.badge{
		display: flex;
		flex-direction: row; 
		justify-content: space-between;
	}
</style>
