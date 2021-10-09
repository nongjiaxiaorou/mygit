<template>
	<view>
		<uni-list>
		    <uni-list-item to="pour">
		        <view slot="header" class="slot-box"><image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image></view>
		        <text slot="body" class="slot-box slot-text">砼浇筑管理</text>
		    </uni-list-item>
		</uni-list>
		
		<uni-list>
		    <uni-list-item to="removal">
		        <view slot="header" class="slot-box"><image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image></view>
		        <text slot="body" class="slot-box slot-text">砼拆模管理</text>
		    </uni-list-item>
		</uni-list>
		
		<!-- <uni-list>
		    <uni-list-item to="Concrete">
		        <view slot="header" class="slot-box"><image class="slot-image" src="../../../static/images/wbjc.png" mode="widthFix"></image></view>
		        <text slot="body" class="slot-box slot-text">砼强度管理</text>
		    </uni-list-item>
		</uni-list> -->
		
		
	</view>
</template>

<script>
	import uniList from '@/components/uni-app/uni-list/uni-list';
	import uniListItem from '@/components/uni-app/uni-list-item/uni-list-item';
	export default {
		data() {
			return {};
		},
		components:{
			"uni-list":uniList,
			"uni-list-item":uniListItem
		},
		methods: {}
	};
</script>

<style>
	.uni-list{
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
