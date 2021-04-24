<template>
	<div class="updateUnit">
		<el-dialog title="修改单元" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogNewUnit.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
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
		name: 'updateUnit',
		props: {
			dialogNewUnit: Object,
			unitInfo: Object
		},
		data() {
			return {
				formDate: {
					unit: '',
				},
				formrules: {
					unit: [{ required: true, message: '单元不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			
		},
		methods: {
			//模态框的新增
			dialogFormAdd() {
				let fd = new FormData()
				fd.append('flag', 'updateUnitInfo')
				fd.append('buildId', this.unitInfo.id)
				fd.append('newUnit', this.formDate.unit)
				this.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
							// console.log(res.data)
							if(res.data.code){
								this.dialogNewUnit.show = false
								this.$emit('handleNewUnitChild')
								this.$message({
									type: 'success',
									message: '单元修改成功！'
								});
							}else{
								this.$message({
									type: 'warning',
									message: '单元值未修改，请重新定义！'
								});
							}
						}).catch(function(err) {
							console.log(err)
						})
					} else {
						console.log('error submit!!');
					}
				});
			},
			
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogNewUnit.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
		},
		watch: {
			"unitInfo": {
				handler(newValue, oldValue) {
					let arr = new Array()
					for(var i=0;i<newValue.children.length;i++){
						arr[i] = newValue.children[i].unitContent
					}
					this.formDate.unit = arr.join('||')
				}
			}
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
