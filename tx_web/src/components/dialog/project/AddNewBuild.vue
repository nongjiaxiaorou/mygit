<template>
	<div class="AddPro">
		<el-dialog title="新建楼栋" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogNewBuild.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
				<div class="flex">
					<el-form-item label="栋号" prop="build" class="flex-1 px-2">
						<el-input placeholder="请输入栋号 (例如:1栋)" v-model="formDate.build"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="地下层数" prop="undergroundNumber" class="flex-1 px-2">
						<el-input placeholder="请输入地下楼层 (例如:地下一层,填入正数1)" v-model="formDate.undergroundNumber"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="地上层数" prop="abovegroundNumer" class="flex-1 px-2">
						<el-input placeholder="请输入地上楼层 (例如:地上一层,填入正数1)" v-model="formDate.abovegroundNumer"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="单元" prop="unit" class="flex-1 px-2">
						<el-input placeholder="请填入单元 (例如: 东单元||西单元,需以双杆隔开)" v-model="formDate.unit"></el-input>
					</el-form-item>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogAdd')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'AddNewBuild',
		props: {
			dialogNewBuild: Object,
			registerBaseData: Object,
			sectionData: Array
		},
		data() {
			return {
				formDate: {
					build: '',
					undergroundNumber: '',
					abovegroundNumer: '',
					unit: ''
				},
				formrules: {
					build: [{ required: true, message: '栋号不能为空', trigger: 'blur' }],
					undergroundNumber: [{ required: true, message: '地下层数不能为空', trigger: 'blur' }],
					abovegroundNumer: [{ required: true, message: '地上层数不能为空', trigger: 'blur' }],
					unit: [{ required: true, message: '单元不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			
		},
		methods: {
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				let registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				fd.append('flag', 'addNewBuild');
				fd.append('protimeStamp', registerBaseData.timeStamp);
				fd.append('projectName', registerBaseData.projectName);
				fd.append('build', that.formDate.build);
				fd.append('undergroundNumber', that.formDate.undergroundNumber);
				fd.append('abovegroundNumer', that.formDate.abovegroundNumer);
				fd.append('unit', that.formDate.unit);
				fd.append('sectionData', that.sectionData);
				that.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						that.$axios.post(that.$adminUrl + `/project/projectManagement.php`, fd).then((res) => {
							// console.log(res.data.states)
							if (res.data.code) {
								that.dialogNewBuild.show = false
								that.$emit('handleNewBuildChild')
								that.FormTabelEmpty();
							} else {
								this.$message({
									type: 'warning',
									message: '该栋号已存在，请重新定义！'
								});
							}
						}).catch(() => {
				
						})
					} else {
						console.log('error submit!!');
					}
				});
			},
			
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogNewBuild.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
