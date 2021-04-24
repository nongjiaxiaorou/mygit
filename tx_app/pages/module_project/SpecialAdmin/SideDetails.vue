<template>
	<view class="content">
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">工程名称:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Side_Data.projectName" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">砼等级:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Side_Data.concreteGrade" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">部位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Side_Data.pouringPosition" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">旁站时间:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Side_Data.sideDate" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">旁站人:</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Side_Data.pouringVolume" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑前描述:</view>
				<view class="uni-list-cell-db">
					<view class="uni-textarea"><textarea  auto-height v-model="poured_describe" /></view>
				</view>
			</view>
		</view>
		<image class="img" v-for="(item, index) in pouredImage" :key="index" :src="item.url" @tap="_previewImage(item.url)" mode="widthFix"></image>
		<view class="tx-center"><button type="primary" size="mini" @click="upload('poured', Side_Data.id)">上传浇筑前图片</button></view>
		
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑中描述:</view>
				<view class="uni-list-cell-db">
					<view class="uni-textarea"><textarea  auto-height v-model="pouring_describe" /></view>
				</view>
			</view>
		</view>
		<image class="img" v-for="(item, index) in pouringImage" :key=" 'img1' + index " :src="item.url" @tap="_previewImage(item.url)" mode="widthFix"></image>
		<view class="tx-center"><button type="primary" size="mini" @click="upload('pouring', Side_Data.id)">上传浇筑中图片</button></view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑后描述:</view>
				<view class="uni-list-cell-db">
					<view class="uni-textarea"><textarea auto-height v-model="pour_describe" /></view>
				</view>
			</view>
		</view>
		<image class="img" v-for="(item, index) in pourImage" :key=" 'img2' + index " :src="item.url" @tap="_previewImage(item.url)" mode="widthFix"></image>
		<view class="tx-center"><button type="primary" size="mini" @click="upload('pour', Side_Data.id)">上传浇筑后图片</button></view>

		


	</view>
</template>

<script>
export default {
	components: {},
	data() {
		return {
			id:'',//卡片id
			Side_Data: {},
			userInfo: [], //用户信息
			poured_describe:'',//浇筑前
			pouring_describe:'',//浇筑中
			pour_describe:'',//浇筑后
			pouredImage: [
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
			pouringImage: [
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
			pourImage: [
				{
					url: '../../../static/images/null.jpg'
				},
				{
					url: '../../../static/images/null.jpg'
				},
				{
					url: '../../../static/images/null.jpg'
				}
			]
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
		//获取旁站数据
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/pour.php',
			data: {
				flag: 'SideDetails',
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
					this.Side_Data = data[0];
				}
			},
			fail: error => {
				console.log(error);
			}
		});
		
		//获取图片和描述数据
		uni.request({
			url: this.api + '/module_project/SpecialAdmin/pour.php',
			data: {
				flag: 'PourImg',
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
					//浇筑前
					let poured_img = new Array();
					poured_img = data[0].poured_img.split("||");
					this.pouredImage = []
					for(let i = 0;i<poured_img.length-1;i++){
						 let url = {url:this.imageUrl+'/concrete_image/'+poured_img[i]}
						 this.pouredImage.push(url)
					}
					this.poured_describe = data[0].poured_describe
					//浇筑中
					let pouring_img = new Array();
					pouring_img = data[0].pouring_img.split("||");
					this.pouringImage = []
					for(let i = 0;i<pouring_img.length-1;i++){
						let url = {url:this.imageUrl+'/concrete_image/'+pouring_img[i]}
						this.pouringImage.push(url)
					}
					this.pouring_describe = data[0].pouring_describe
					//浇筑后
					let pour_img = new Array();
					pour_img = data[0].pour_img.split("||");
					this.pourImage = []
					for(let i = 0;i<pour_img.length-1;i++){
						let url = {url:this.imageUrl+'/concrete_image/'+pour_img[i]}
						this.pourImage.push(url)
					}
					this.pour_describe = data[0].pour_describe
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
