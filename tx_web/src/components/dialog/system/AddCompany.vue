<template>
	<div class="AddCompany">
		<el-dialog title="新建公司" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="90px" :rules="formrules">
				<el-form-item v-if="form.framework!=='三级架构' " label="公司架构" prop="companyRank1" class="flex-1 px-2 my-3" style="width:90%;">
					<el-select v-model="value" placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in form.options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</el-form-item>
				<el-form-item v-if="form.framework==='三级架构' " label="公司架构" prop="companyName" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.framework"></el-input>
				</el-form-item>
				<el-form-item label="公司名称" prop="companyName" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.companyName"></el-input>
				</el-form-item>
				<el-form-item label="公司层级" prop="companyRank" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-if="form.framework==='三级架构'? formDate.companyRank='分公司':formDate.companyRank " v-model="formDate.companyRank"></el-input>
				</el-form-item>
				<el-form-item label="录入时间" prop="createTime" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.createTime"></el-input>
				</el-form-item>
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
		name: 'AddUser',
		props: {
			dialogAdd: Object,
			form: Object,
			framework: String,
			
		},
		data() {
			return {
				formDate: {
					companyName: '',
					companyRank: '未选择公司架构',
					createTime: ''
				},
				value: '',
				formrules: {
					companyRank: [{ required: true, message: '公司层级不能为空', trigger: 'blur' }]
				}
			};
		},
		watch:{
			
			value(val){
				console.log(val)
				if(val=='选项1'){
					this.formDate.companyRank = '总公司'
				}else{
					this.formDate.companyRank = '公司'
				}
			}
		},
		mounted() {
			this.formDate.createTime = this.commonFunc.getNowDate() //调用全局方法获取当前时间
		},
		methods: {
			//新增公司
			dialogFormAdd() {
				var fd = new FormData();
				fd.append('flag', 'addCompany');
				fd.append('companyName', this.formDate.companyName);
				fd.append('companyRank', this.formDate.companyRank);
				fd.append('createTime', this.formDate.createTime);
				fd.append('timeStamp', new Date().getTime());
				this.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						this.$axios.post(this.$adminUrl + `/system/Company.php`, fd).then((res) => {
							if (res.data.states == 'success') {
								this.dialogAdd.show = false
								this.formDate.companyName = ''
								this.$emit('update')
							} else {
								this.$message.error('该公司名已经存在！！！')
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
				this.formDate.companyName = ''
				this.dialogAdd.show = false;
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
