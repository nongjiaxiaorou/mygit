<template>
	<view>
		<upload-image @change='changeImage' :imageListPar="imageList"></upload-image>
		<button type="primary" @click="submit">确定</button>
		
		<view class="uni-uploader-body">
			<view class="uni-uploader__files">
				<block v-for="(image,index) in imageListHave" :key="index">
					<view class="uni-uploader__file" style="position: relative;">
						<image class="uni-uploader__img rounded" mode="aspectFill" :src="image" :data-src="image" @tap="_previewImage(image)"></image>
					</view>
				</block>
			</view>
		</view>
	</view>
</template>

<script>
	import uploadImage from '../../../components/common/upload-image.vue';
	import {pathToBase64,base64ToPath} from '../../../js_sdk/gsq-image-tools/image-tools/index.js'
	export default {
		components: {
			uploadImage
		},
		data() {
			return {
				imageList: [],
				imageListHave:[],
				imageBaseList: [],
				insTaskId: '',
				fileCategory:'',//资料照片类型
				taskTimeStamp:''
			}
		},
		onLoad: function(option) {
			this.imageListHave = JSON.parse(option.imageList)
			this.fileCategory = option.fileCategory
			this.taskTimeStamp = option.taskTimeStamp
		},
		mounted() {
			
		},
		methods: {
			//提交
			submit() {
				// console.log(JSON.stringify(this.imageBaseList)+" "+this.fileCategory+" "+this.insTaskId+" "+this.taskTimeStamp)
				let opts = {
					url: this.api + '/module_project/ExternalInspect/ExternalUploadPicture.php',
					method: 'POST'
				}
				let param = {
					flag: 'saveScorePic',
					imageBaseList: JSON.stringify(this.imageBaseList),
					fileCategory:this.fileCategory,
					taskTimeStamp:this.taskTimeStamp
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					uni.$emit('updateScoreDetail')
					uni.navigateBack()
					
				}, error => {

				})
			},
			//路径转bse64
			urlTobase64(url) {
				this.imageBaseList = []
				for (let i = 0; i < url.length; i++) {
					pathToBase64(url[i])
					.then(base64 =>{
						this.imageBaseList.push(base64)
					})
					.catch(error =>{
						console.log(error)
					})
				}
			},
			//接收返回的图片数组
			changeImage(e) {
				// console.log(e)
				this.urlTobase64(e)
			},
			//点击查看图片
			_previewImage(image) {
				// console.log(image)
				var imgArr = [];
				imgArr.push(image);
				//预览图片
				uni.previewImage({
					urls: imgArr,
					current: imgArr[0]
				});
			},
		}
	}
</script>

<style>

</style>
