<template>
	<view>
		<view style="padding: 20rpx;">
			<view class="flex align-center justify-between">
				<view class="flex align-center">
					<!-- 头像 -->
					<image class="rounded-circle mr-2" :src="item.userpic" style="width: 65rpx;height: 65rpx;"
					lazy-load="" mode=""
					@click="openSpace()"></image>
					<!-- 昵称发布时间 -->
					<view class="">
						<view class="font" style="line-height: 1.5;">{{item.username}}</view>
						<view class="font-sm text-light-muted" style="line-height: 1.5;">{{item.newstime}}</view>
					</view>
				</view>
				<view class="flex align-center justify-center rounded text-wihte animate__animated" 
				 style="width: 90rpx;height: 50rpx;background-color: #FF4A6A;"
				 hover-class="animate__jello"
				 v-if="!item.isFllow"
				 @click="fllow()">关注</view>
			</view>
		</view>
		<!-- 标题 -->
		<view class="font" @click="openDetail()">
			{{item.title}}
		</view>
		<!-- 图片 -->
		<image v-if="item.titlepic" :src="item.titlepic" class="w-100 rounded" style="height: 350rpx;" mode=""
		 @click="openDetail()"></image>
		<!-- 图标按钮 -->
		<view class="flex align-center">
			<!-- 顶 -->
			<view class="flex align-center justify-center flex-1 animate__animated"
			hover-class="animate__jello text-main" @click="doSupport('support')"
			:class="item.support.type === 'support' ? 'support-active' : ''">
				<text class="iconfont icon-shouye mr-2"></text>
				<text>{{item.support.suppory_count}}</text>
			</view>
			<!-- 踩 -->
			<view class="flex align-center justify-center flex-1 animate__animated" 
			hover-class="animate__jello text-main" @click="doSupport('unsupport')"
			:class="item.support.type === 'unsupport' ? 'support-active' : ''">
				<text class="iconfont icon-shouye mr-2"></text>
				<text>{{item.support.unsupport_count}}</text>
			</view>
			<view class="flex align-center justify-center flex-1 animate__animated" 
			hover-class="animate__jello text-main" @click="openDetail()">
				<text class="iconfont icon-shouye mr-2"></text>
				<text>{{item.comment_count}}</text>
			</view>
			<view class="flex align-center justify-center flex-1 animate__animated" 
			hover-class="animate__jello text-main" @click="openDetail()">
				<text class="iconfont icon-shouye mr-2"></text>
				<text>{{item.share_num}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			item: Object,
			index: Number
		},
		methods:{
			openSpace(){
				console.log("打开个人空间")
			},
			fllow(){
				this.$emit('fllow',this.index)
			},
			openDetail(){
				console.log("进入详情页")
			},
			//顶踩操作
			doSupport(type){
				this.$emit('doSupport',{
					type:type,
					index:this.index
				})
			}
			
		},
	}
</script>

<style>
	.support-active{
		color: #FF4A6A;
	}
</style>
