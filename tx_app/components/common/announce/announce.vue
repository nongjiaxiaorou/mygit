<template>
	<view>
		<!-- 轮播图 -->
		<swiper indicator-dots circular :style="{height:height1+'px'}">
			<block v-for="(item, index) in list" :index="index" :key="index">
				<swiper-item class=" flex justify-center align-center">
					<uni-card :is-shadow="isShow" :style="{height:height2+'px'}" style="position: relative;">
						<view :style="{height:height2+'px'}">
							<text class="uni-list-card flex justify-center align-center font-weight-bold">{{item.title}}</text>
							<text class="uni-list-card">{{item.content}}</text>
							<text class="time">2020-05-01 10:30</text>
						</view>
					</uni-card>
				</swiper-item>
			</block>
		</swiper>
	</view>
</template>

<script>
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue';
	export default {
		props:[
			'height'
		],
		components:{
			uniCard
		},
		data() {
			return {
				isShow:true,
				list:{},
				height1:null,
				height2:null,
			}
		},
		mounted() {
			this.getAnnounce()
			// console.log(this.height);
			this.height1 = this.height - 60;
			this.height2 = this.height1 *0.8;
		},
		methods: {
			getAnnounce(){
				// console.log('res');
				let opts = {
					url: this.api + '/msg/Announce.php',
					method: 'POST'
				}
				let param = {
					flag: 'getAnnounce',
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res);
					if ((res.data.statusCode = '200')) {
						this.list = res.data.data
					}
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			
		}
	}
</script>

<style>
.badge{
		display: flex;
		flex-direction: row; 
		justify-content: space-between;
	}
	swiper{
		width: 750rpx;
	}
	.time {
		position: absolute;
		bottom: 10px;
		right: 6px;
		color: #999999;
	}
</style>
