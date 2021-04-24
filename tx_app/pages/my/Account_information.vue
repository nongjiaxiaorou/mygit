<template>
	<view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">用户:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="userInfo.username" disabled="true"/></view>
			</view>
		</view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">账号:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="userInfo.account" disabled="true"/></view>
			</view>
		</view>
		
		<!-- <view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">密码：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" /></view>
			</view>
		</view> -->
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">手机:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="userInfo.phone" disabled="true"/></view>
			</view>
		</view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">邮箱:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="userInfo.email" disabled="true"/></view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				userInfo:[]
			}
		},
		onLoad() {
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					this.userInfo = res.data
					// console.log(res.data);
				},
				fail: (error) => {
					console.log(error);
				}
			});
		},
	}
</script>

<style>
</style>
