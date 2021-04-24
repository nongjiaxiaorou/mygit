<template>
	<div class="AddPro">
		<el-dialog title="小组信息修改" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogEdit.show">
			<el-form :model="formDate" ref="dialogEdit" label-width="80px" :rules="formrules">
				<el-form-item label="小组名称" prop="projectName" class="px-2">
					<el-input v-model="formDate.projectName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="组长" prop="categories" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.categories"></el-input> -->
						<el-select v-model="formDate.categoryIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.categoryList" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="副组长" prop="company" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.company"></el-input> -->
						<el-select v-model="formDate.optionsIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.options" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="组员" prop="province" class="flex-1 px-2">
						<el-input v-model="formDate.province"></el-input>
					</el-form-item>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogEdit')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'AddUser',
		props: {
			dialogEdit: Object,
			form: Object
		},
		data() {
			return {
				formDate: {
					projectName: '',
					categories: '',
					company: '',
					province: '',
					city: '',
					proPosition: '',
					architecture: '',
					startTime: '',
					completedTime: '',
					optionsIndex: '',
					categoryIndex:''
				},
				categoryList: [],
				formrules: {
					// username: [{ required: true, message: '班级信息不能为空', trigger: 'blur' }]
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
				let indexOf1 = that.formDate.categoryIndex
				let indexOf2 = that.formDate.optionsIndex
				fd.append('flag', 'addProject');
				fd.append('projectName', that.formDate.projectName);
				fd.append('categories', that.form.categoryList[indexOf1].label);
				fd.append('company', that.form.options[indexOf2].label);
				fd.append('companyId', that.form.options[indexOf2].companyId);
				fd.append('province', that.formDate.province);
				fd.append('city', that.formDate.city);
				fd.append('proPosition', that.formDate.proPosition);
				fd.append('architecture', that.formDate.architecture);
				fd.append('startTime', that.commonFunc.dateFormat(that.formDate.startTime));
				fd.append('completedTime', that.commonFunc.dateFormat(that.formDate.completedTime));
				fd.append('timeStamp', new Date().getTime());
				that.$refs.dialogEdit.validate((valid) => {
					if (valid) {
						that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then((res) => {
							console.log(res.data.states)
							if (res.data.states == 'success') {
								that.dialogEdit.show = false
								that.$emit('update')
								that.FormTabelEmpty();
							} else {

							}
						}).catch(() => {

						})
					} else {
						console.log('error submit!!');
					}
				});
			},
			
			//关闭模态框
			dialogFormClose(dialogEdit) {
				this.FormTabelEmpty();
				this.dialogEdit.show = false;
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
