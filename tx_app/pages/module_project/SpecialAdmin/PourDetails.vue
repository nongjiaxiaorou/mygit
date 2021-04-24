<template>
	<view class="content">
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">工程名称:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.projectName" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">砼等级：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.concreteGrade" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑部位:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.pouringPosition" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑日期:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.pouringDate" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑方量:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.pouringVolume" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">主管施工员:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.builder" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">质量员意见：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="SignDetails.idea1" /></view>
				<view class="uni-list-cell-right">
					<button type="default" class="btn" @tap="sign">{{ SignDetails.sign1 === null ? '未签名' : '已签名' }}</button>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">生产经理意见:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="SignDetails.idea2" /></view>
				<view class="uni-list-cell-right">
					<button type="default" class="btn" @tap="sign1">{{ SignDetails.sign2 === null ? '未签名' : '已签名' }}</button>
				</view>
			</view>
		</view>
		
        <messagepush ref="select_person" :flag="'side'" :person="'allPerson'" @assignside="assignside"></messagepush>
		<catSignature :cardid="Pouring_Data.id" :userid="userInfo.userId" canvasId="qualityController" @close="close" @save="save" :visible="isShow" />
		<catSignature :cardid="Pouring_Data.id" :userid="userInfo.userId" canvasId="ProductionManager" @close="close1" @save="save1" :visible="isShow1" />
	</view>
</template>

<script>
import catSignature from '@/components/common/cat-signature/cat-signature.vue';
import messagepush from '@/pages/module_project/SpecialAdmin/message-push/message-push.vue';
export default {
	components: { catSignature,messagepush },
	data() {
		return {
			cardId:'',
			Pouring_Data: {},
			isShow: false, //签名
			isShow1: false, //签名
			userInfo: [], //用户信息
			SignDetails: {
				sign1: null,
				sign2: null,
				idea1: null,
				idea2: null
			} //签名信息
		};
	},
	onLoad(option) {
		let id = option.id;
		this.cardId = option.id;
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/pour.php',
			data: {
				flag: 'PourDetails',
				id: id
			},
			method: 'POST',
			sslVerify: false,
			dataType: 'json',
			header: {
				'content-type': 'application/x-www-form-urlencoded' //level头信息
			},
			success: res => {
				if ((res.data.status = 'success')) {
					let data = res.data.data;
					this.Pouring_Data = data[0];
				}
			},
			fail: error => {
				console.log(error);
			}
		});
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/pour.php',
			data: {
				flag: 'PourSign',
				cardid: id
			},
			method: 'POST',
			sslVerify: false,
			dataType: 'json',
			header: {
				'content-type': 'application/x-www-form-urlencoded' //level头信息
			},
			success: res => {
				if ((res.data.status = 'success')) {
					let data = res.data.data;
					if (data != '') {
						this.SignDetails = data[0];
					}
				} else {
				}
			},
			fail: error => {
				console.log(error);
			}
		});
		uni.getStorage({
			key: 'userInfo',
			success: res => {
				this.userInfo = res.data;
			},
			fail: error => {
				console.log(error);
			}
		});
	},
	methods: {
		sign() {
			this.isShow = true;
		},
		close() {
			this.isShow = false;
		},
		save(e) {
			this.isShow = false;
			let id = e;
			uni.redirectTo({
				url: 'PourDetails?id=' + id
			});
		},
		sign1() {
			this.isShow1 = true;
		},
		close1() {
			this.isShow1 = false;
		},
		save1(e) {
			//生产经理签完名下达旁站
			this.isShow1 = false;
			let id = e;
			this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
			this.$refs.select_person.$emit('cardId',id)
		},
		//下达旁站
		assignside(id) {
			uni.request({
				url: this.api + '/module_project/SpecialAdmin/pour.php',
				data: {
					flag: 'assignside',
					bystander: this.Pouring_Data.builder,
					cardid: id
				},
				method: 'POST',
				sslVerify: false,
				dataType: 'json',
				header: {
					'content-type': 'application/x-www-form-urlencoded' //level头信息
				},
				success: res => {
					console.log(res);
					if ((res.data.status = 'success')) {
						uni.showModal({
							title: '提示',
							content: '已下达旁站',
							showCancel: false
						});
						setTimeout(function() {
							uni.navigateBack();
						}, 1000);
					}
				},
				fail: error => {
					console.log(error);
				}
			});
		}
	}
};
</script>

<style>
page {
	background-color: #efeff4;
}
.btn {
	line-height: 60rpx;
	width: 66%;
	font-size: 12px;
	float: right;
	margin-right: 10px;
}
</style>
