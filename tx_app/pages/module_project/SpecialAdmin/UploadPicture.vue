<template>
	<view>
		<view class="uni-title " style="padding-left: 10rpx;">{{describeType}}:</view>
		<view class="uni-textarea"><textarea @blur="pour_describe" auto-height /></view>
		
		<upload-image @change='changeImage' :imageListPar="imageList"></upload-image>
		<view class="tx-center">
			<button type="primary" @click="submit" size="mini">确定</button>
		</view>
	</view>
</template>

<script>
	import uploadImage from '../../../components/common/upload-image.vue';
	import { pathToBase64, base64ToPath } from '../../../js_sdk/gsq-image-tools/image-tools/index.js'
	export default {
		components:{
			uploadImage
		},
		data() {
			return {
				imageList:[],
				imageBaseList:[],
				cardid:'',
				flag:'',
				describeType:'',//分为浇筑前浇筑中浇筑后描述
				describe:''//意见栏
			}
		},
		onLoad:function(option){
			this.cardid = option.id
			this.flag = option.flag
			if(option.flag=='poured'){
				this.describeType = '浇筑前描述'
			}
			else if(option.flag=='pouring'){
				this.describeType = '浇筑中描述'
			}
			else if(option.flag=='pour'){
				this.describeType = '浇筑后描述'
			}
			else{
				this.describeType = '拆模意见'
			}
		},
		methods: {
			//意见栏
			pour_describe: function(e) {
				this.describe = e.detail.value
			},
			//提交
			submit(){
				// console.log(JSON.stringify(this.imageBaseList))
				if(JSON.stringify(this.imageBaseList)=='[]'){
					uni.showToast({
						icon: 'success',
						position: 'bottom',
						title: '未选择图片'
					});
					return
				}
				let opts = {
					url:this.api + '/module_project/SpecialAdmin/UploadPicture.php',
					method:'POST'
				}
				let param = {
					flag:this.flag,
					imageBaseList:JSON.stringify(this.imageBaseList),
					id:this.cardid,
					describe:this.describe
				}
				let isLoading = false
				this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
					if(res.data.status=='1'){
						if(this.flag=='removal'){
							uni.showToast({
								icon: 'success',
								position: 'bottom',
								title: '完成拆模'
							});
						}else{
							uni.showToast({
								icon: 'success',
								position: 'bottom',
								title: '上传成功'
							});
						}
						setTimeout(function() {
							uni.navigateBack()
						}, 1000);
					}
				},error=>{
					
				})
			},
			//路径转bse64
			urlTobase64(url){
				this.imageBaseList = []
				for(let i=0;i<url.length;i++){
					pathToBase64(url[i])
					  .then(base64 => {
					    this.imageBaseList.push(base64)
					  })
					  .catch(error => {
					    console.error(error)
					  })
				}
			},
			//接收返回的图片数组
			changeImage(e){
				console.log(e)
				this.urlTobase64(e)
			},
		}
	}
</script>

<style>
page{
	background-color: #efeff4;
}
</style>
