<template>
	<el-dialog title="区段楼层信息导入" :visible.sync="dialogImport.show" :modal-append-to-body="false" width="40%" class="dialog"
	 @close="clearFileFunc">
		<!-- 下载Excel注意事项：1.模板文档必须放在public文件夹下的static下 2.href的路径必须以public下的index.html的相对路径 3.a标签需要添加download属性 -->
		<a :href="ExcelFile">
			<el-button type="primary">下载模板</el-button>
		</a>
		<el-upload :on-success="success" 
		:on-exceed="fileExceed" 
		:on-change="onchangeFunc" drag 
		:auto-upload="false" 
		ref="upload"
		:limit="1" 
		:action="target" 
		:http-request="httpRequest" multiple class="el-upload">
			<i class="el-icon-upload"></i>
			<div class="el-upload__text" width="100%">
				将文件拖到此处，或
				<em>点击上传</em>
			</div>
		</el-upload>
		<div class="el-upload__tip0">1、需要先下载模板，按照模板提示信息输入</div>
		<div class="el-upload__tip0">2、excel表格支持如下格式:</div>
		<div class="el-upload__tip">分包单位不能有特殊字符，例如（#、￥、^、$等）</div>
		<!-- <div class="el-upload__tip">身份证号_姓名_学号_宿舍栋号_宿舍号_性别</div>
        <div class="el-upload__tip">身份证号_姓名_学号_班级_学院_专业_宿舍栋号_宿舍号_性别_联系电话_邮箱_籍贯</div>
        <div class="el-upload__tip0">3、导入完成后请刷新页面</div> -->
		<div slot="footer" class="dialog-footer">
			<el-button @click="canel('upload')">取 消</el-button>
			<el-button type="primary" @click="upload()">导 入</el-button>
		</div>
	</el-dialog>
</template>

<script>
	export default {
		name: 'Import',
		props: {
			dialogImport: Object
		},
		data() {
			return {
				target: '',
				fileList: [],
				ExcelFile: ''
			};
		},
		mounted() {
			this.ExcelFile = this.$adminUrl + `/excel_template/实测区段楼层信息导入.xlsx`
			// this.target = this.$adminUrl+`/PHPExcel/ExcelUpload.php`
		},
		methods: {
			//选择文件时触发
			onchangeFunc(file, fileList) {
				this.fileList = fileList;
			},
			//导入按钮
			upload() {
				this.dialogImport.show = false;
				this.fileList = ""
				console.log(this.$refs.upload.uploadFiles)
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
				this.dialogImport.show = false;
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
				// console.log(param)
				let fileObj = param.file // 相当于input里取得的files
				let fd = new FormData() // FormData 对象
				fd.append('file', fileObj) // 文件对象
				fd.append('flag', 'saveProZone')
				let config = {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
				console.log(this.$adminUrl + `/PHPExcel/ExcelUpload.php`)
				this.$axios.post(this.$adminUrl + `/PHPExcel/ExcelUpload.php`, fd, config).then(res => {
					setTimeout(() => {
						this.$emit('handleImportProZoneChild'); //初始化webscoket刷新视图
						this.$message.success('实测楼层信息导入成功！');
					}, 500);
					// if(res.code===0){
					// 	this.submitForm();//提交表单
					// }
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
