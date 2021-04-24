<template>
	<el-dialog title="楼层图纸导入" :visible.sync="dialogImportPic.show" :modal-append-to-body="false" width="40%" class="dialog"
	 @close="clearFileFunc">
		<el-upload :on-success="success" :on-exceed="fileExceed" :on-change="onchangeFunc" drag :auto-upload="false" ref="upload"
		 :limit="1" :action="target" :http-request="httpRequest" multiple class="el-upload">
			<i class="el-icon-upload"></i>
			<div class="el-upload__text" width="100%">
				将文件拖到此处，或
				<em>点击上传</em>
			</div>
		</el-upload>
		<div class="el-upload__tip0">1、需要勾选对应的楼层才能上传图片</div>
		<div class="el-upload__tip0">2、图片支持如下格式:</div>
		<div class="el-upload__tip">JPEG、PNG格式</div>
		<div class="el-upload__tip">像素为宽x高 （1200x840）</div>
		<!-- <div class="el-upload__tip">身份证号_姓名_学号_宿舍栋号_宿舍号_性别</div>
        <div class="el-upload__tip">身份证号_姓名_学号_班级_学院_专业_宿舍栋号_宿舍号_性别_联系电话_邮箱_籍贯</div>
        <div class="el-upload__tip0">3、导入完成后请刷新页面</div> -->
		<div slot="footer" class="dialog-footer">
			<el-button @click="canel">取 消</el-button>
			<el-button type="primary" @click="upload()">导 入</el-button>
		</div>
	</el-dialog>
</template>

<script>
	export default {
		name: 'Import',
		props: {
			registerBaseData: Object,
			dialogImportPic: Object,
			floorData: Array
		},
		data() {
			return {
				target: '',
				fileList: []
			};
		},
		mounted() {
		},
		methods: {
			//选择文件时触发
			onchangeFunc(file, fileList) {
				this.fileList = fileList;
			},
			//导入按钮
			upload() {
				this.dialogImportPic.show = false;
				this.fileList = ""
				// console.log(this.$refs.upload.uploadFiles)
				if (this.$refs.upload.uploadFiles) {
					this.$refs.upload.submit(); // 此函数执行后会调用httpRequest函数
				} else {
					this.$message({
						type: 'warning',
						message: '您未添加任何文件！'
					});
				}
			},
			canel() {
				this.dialogImportPic.show = false;
			},
			// 超出上传限制提示框
			fileExceed(e) {
				let options = {
					message: '已添加文件！',
					type: 'warning',
					duration: 2000
				};
				// Message(options);
			},
			// 上传成功回调
			success(res) {
				console.log(res);
				this.$emit('updateImp');
			},
			httpRequest(param) {
				// console.log(this.floorData)
				let fileObj = param.file // 相当于input里取得的files
				let fd = new FormData() // FormData 对象
				fd.append('file', fileObj) // 文件对象
				var registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				let length = this.floorData.floor
				for(var i=0; i<length;i++){
					
				}
				fd.append('flag', 'saveUploadPic')
				fd.append('proTimeStamp', registerBaseData.timeStamp)
				fd.append('projectName', registerBaseData.projectName)
				fd.append('floorData', JSON.stringify(this.floorData))
				let config = {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
				this.$axios.post(this.$adminUrl + `/project/uploadFileFloor.php`, fd, config).then(res => {
					// console.log(res.data)
					setTimeout(() => {
						this.$emit('handleImportFloorChild'); //初始化webscoket刷新视图
						this.$message.success('楼层图纸更新成功！');
					}, 500);
				})
			},
			//当dialog关闭时，清空已选中的文件
			clearFileFunc() {
				this.$refs.upload.clearFiles();
			}
		}
	};
</script>
<style lang="scss" scoped>
	.el-upload__tip0 {
		margin-left: 60px;
		font-size: 15px;
		margin-top: 10px;
	}

	.el-upload__tip {
		margin-left: 85px;
		font-size: 15px;
	}

	.el-upload {
		display: block !important;
		margin: 15px 0;
	}

	.el-button {
		margin-left: 60px;
	}
</style>
