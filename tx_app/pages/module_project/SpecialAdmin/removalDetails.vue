<template>
	<view class="content">
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">工程名称:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.projectName" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">砼等级:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.concreteGrade" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">拆模部位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.removalPosition" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑日期:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.pouringDate" /></view>
			</view>
		</view>


		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">拆模意见:</view>
				<view class="uni-list-cell-db">
					<view class="uni-textarea"><textarea  auto-height v-model="removal_describe" /></view>
				</view>
			</view>
		</view>
		<image class="img" v-for="(item, index) in removalImage" :key="index" :src="item.url" @tap="_previewImage(item.url)" mode="widthFix"></image>
		<view class="tx-center"><button type="primary" size="mini" @click="upload('removal', removal_Data.id)">上传拆模报告</button></view>
		

		


	</view>
</template>

<script>
export default {
	components: {},
	data() {
		return {
			id:'',//卡片id
			removal_Data: {},
			userInfo: [], //用户信息
			removal_describe:'',//拆模意见
			removalImage: [
				{
					url: '../../../static/images/null.jpg'
				},
				{
					url: '../../../static/images/null.jpg'
				},
				{
					url: '../../../static/images/null.jpg'
				}
			],
		};
	},
	onLoad(option) {
		this.id = option.id;
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
	onShow() {
		//获取拆模卡片数据
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/removal.php',
			data: {
				flag: 'removalDetails',
				id: this.id
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
					this.removal_Data = data[0];
				}
			},
			fail: error => {
				console.log(error);
			}
		});
		
		//获取图片和描述数据
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/removal.php',
			data: {
				flag: 'RemovalImg',
				id: this.id
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
					//拆模报告
					let removal_img = new Array();
					removal_img = data[0].removal_img.split("||");
					this.removalImage = []
					for(let i = 0;i<removal_img.length-1;i++){
						 let url = {url:this.imageUrl+'/concrete_image/'+removal_img[i]}
						 this.removalImage.push(url)
					}
					this.removal_describe = data[0].removal_describe
				}
			},
			fail: error => {
				console.log(error);
			}
		});
	},
	methods: {
		//点击查看图片
		_previewImage(image) {
			var imgArr = [];
			imgArr.push(image);
			//预览图片
			uni.previewImage({
				urls: imgArr,
				current: imgArr[0]
			});
		},
		upload(e, id) {
			uni.navigateTo({
				url: 'UploadPicture?flag=' + e + '&id=' + id
			});
		}
	}
};
</script>

<style>
page {
	background-color: #efeff4;
}
.img {
	margin-top: 10px;
	margin-left: 3%;
	width: 30%;
	height: 150px;
}
</style>
