<template>
	<view class="content">
		<view class="avatorWrapper">
			<view class="tit">同欣质量管理系统</view>
		</view>
		<view class="form">
			<view class="inputWrapper">
				<input class="input" type="text" v-model="account" placeholder="请输入用户名" />
			</view>
			<view class="inputWrapper">
				<input class="input" type="password" v-model="password" placeholder="请输入密码" />
			</view>
			<view class="loginBtn">
				<button type="primary" @tap="bindLogin()" :loading="loading">登录</button>
			</view>
			<view class="action-row">
				<navigator>注册账号</navigator>
				<text>|</text>
				<navigator>忘记密码</navigator>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapMutations
	} from 'vuex';

	export default {
		data() {
			return {
				account: 'tx',
				password: '123456',
				loading: false, //是否加载旋转
			}
		},
		methods: {
			bindLogin() {
				// 登录
				if (this.loading) {
					//判断是否加载中，避免重复点击请求
					return false;
				}
				this.loading = true;
				if (this.account.length == "") {
					this.loading = false;
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '账号不能为空'
					});
					return;
				}
				if (this.password.length < 6) {
					this.loading = false;
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '密码不正确'
					});
					return;
				}
				// alert(this.api)
				uni.request({
					url: this.api + '/login/login.php',
					data: {
						account: this.account,
						password: this.password,
						flag: 'Login'
					},
					method: 'POST',
					dataType: 'json',
					header: {
						'content-type': 'application/x-www-form-urlencoded', //POST头信息
					},
					success: (res) => {
						// console.log(res.data);
						if (res.data.result == "success") {
							// console.log(res.data);
							this.login(res.data)
							uni.showToast({
								icon: 'success',
								position: 'bottom',
								title: '登录成功'
							});
							setTimeout(function() {
								uni.reLaunch({
									url: '../index/index',
								});
							}, 2000);

						} else {
							this.loading = false;
							this.password = ""
							uni.showToast({
								icon: 'error',
								position: 'bottom',
								title: '账号或密码错误'
							});
						}

					},
					fail: (error) => {
						console.log(error);
					}
				});


			},
			...mapMutations(['login'])
		}
	}
</script>

<style lang="scss">
	.content {
		background: #377EB4;
		width: 100vw;
		height: 100vh;
	}

	.avatorWrapper {
		height: 30vh;
		width: 100vw;
		display: flex;
		justify-content: center;
		align-items: flex-end;
	}

	.tit {
		/* height: 50px; */
		font-size: 25px;
		color: #FFFFFF;
	}

	.avator {
		width: 200upx;
		height: 200upx;
		overflow: hidden;
	}

	.avator .img {
		width: 100%
	}

	.form {
		padding: 0 100upx;
		margin-top: 80px;
	}

	.inputWrapper {
		width: 100%;
		height: 80upx;
		background: white;
		border-radius: 20px;
		box-sizing: border-box;
		padding: 0 20px;
		margin-top: 25px;
	}

	.inputWrapper .input {
		width: 100%;
		height: 100%;
		text-align: center;
		font-size: 15px;
	}

	.loginBtn {
		width: 100%;
		height: 80upx;
		margin-top: 50px;
		display: flex;
		justify-content: center;
		align-items: center;

		button {
			width: 100%;
			border-radius: 50rpx;
		}
	}

	.loginBtn .btnValue {
		color: white;
	}

	// .forgotBtn{
	// 	text-align: center;
	// 	color: #EAF6F9;
	// 	font-size: 15px;
	// 	margin-top: 20px;
	// }
	.action-row {
		display: flex;
		flex-direction: row;
		justify-content: center;
		margin-top: 20px;

		navigator {
			color: #EAF6F9;
			padding: 0 10px;
		}
	}
</style>
