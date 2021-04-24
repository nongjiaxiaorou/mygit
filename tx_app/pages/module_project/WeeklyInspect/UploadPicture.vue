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
				id: '',
				flag: '',
				fileCategory:'',//资料照片类型
				timeStamp:''
			}
		},
		onLoad: function(option) {
			this.id = option.id
			this.imageListHave = JSON.parse(option.imageList)
			this.flag = option.flag
			this.fileCategory = option.fileCategory
			this.timeStamp = option.timeStamp
		},
		mounted() {
			
		},
		methods: {
			//提交
			submit() {
				// console.log(JSON.stringify(this.imageBaseList))
				let uploadFlag = ''
				if (this.flag == 'defect') {
					uploadFlag = 'saveDefectPic'
				} else if (this.flag == 'reply'){
					uploadFlag = 'saveReplyPic'
				}else if(this.flag=='filePic'){
					uploadFlag = 'saveFilePic'
				}
				// console.log(uploadFlag+" "+this.fileCategory+" "+this.id)
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/UploadPicture.php',
					method: 'POST'
				}
				let param = {
					flag: uploadFlag,
					imageBaseList: JSON.stringify(this.imageBaseList),
					id: this.id,
					fileCategory:this.fileCategory,
					timeStamp:this.timeStamp
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					if(this.flag=='filePic'){
						uni.$emit('updateNotice')
					}else{
						uni.$emit('updateDefect')
					}
					uni.navigateBack()
					// this.draftList = res.data.data
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
