<template>
	<div class="AddPro px-5">
		<el-dialog title="实测单元名称定义" :modal-append-to-body="false" :fullscreen="true" style="padding: 8% 30%;"
		 customClass="customClass" :visible.sync="dialogDefineBuild.show">
			<el-form ref="dialogDefineBuild" style="margin: 0 40px 0 40px;">
				<div v-for="(item,index) in buildForm.buildNameList" :key = "index">
					<el-row style="margin-bottom: 1%;">
						<el-col :span="6" style="text-align: center;background-color: #409EFF;color: white;">
							<div style="height: 32px;line-height: 32px;">单元名称：</div>
						</el-col>
						<el-col :span="18">
							<el-input style="margin-left: 2%;" v-model="item.name" placeholder="请输入实测单元名称"></el-input>
						</el-col>
					</el-row>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogDefineBuild')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	import addBtn from "../../common/add-btn"
	export default {
		name: 'AddUser',
		components: {
			addBtn,
		},
		props: {
			dialogDefineBuild: Object,
			buildForm: Object
		},
		data() {
			return {
				unitName:[{
					name:''
				}]
			};
		},
		computed: {
			
		},
		watch: {
			buildForm: {
				handler(newValue, oldValue) {
					// console.log(newValue)
					// this.unitNum = buildForm.unitNum
					this.getUnitName()
				}
			}
		},
		mounted() {
			this.getUnitName()
		},
		methods: {
			//定义单元名称
			dialogFormAdd() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'defineUnitName')
				fd.append('buildForm', JSON.stringify(that.buildForm))
				// console.log(JSON.stringify(that.buildForm))
				that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
					// console.log(res)
					this.$message({
						type: 'success',
						message: '定义完成！'
					});
					that.dialogFormClose()
				}).catch({
				
				})
			},
			//获取单元名称
			getUnitName() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getUnitName')
				fd.append('buildForm', JSON.stringify(that.buildForm))
				// console.log(that.buildForm)
				that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
					// console.log(res)
					let unitNameArr = res.data.name
					let unitNameArrLen = res.data.name.length-1
					let buildNameArr = that.buildForm.buildNameList
					// console.log(buildNameArr)
					let buildNameArrLen = that.buildForm.buildNameList.length
					for(let i=0;i<buildNameArrLen;i++){
						that.buildForm.buildNameList[i].name = unitNameArr[i]
					}
				}).catch({

				})
			},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
			//关闭模态框
			dialogFormClose(dialogDefineBuild) {
				this.FormTabelEmpty();
				this.dialogDefineBuild.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}

	.red {
		color: #ff0000;
	}

	.title-box {
		background-color: #d9edf7;
		height: 32px;
	}

	.info-label {
		color: #31708f;
		text-align: center;
		font-size: 14px;
	}
</style>
