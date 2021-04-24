<template>
	<div class="AddPro">
		<el-dialog title="新建工程" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
				<el-form-item label="工程名称" prop="projectName" class="px-2">
					<el-input v-model="formDate.projectName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="工程类别" prop="categories" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.categories"></el-input> -->
						<el-select v-model="formDate.categoryIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.categoryList" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="所属公司" prop="company" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.company"></el-input> -->
						<el-select v-model="formDate.optionsIndex" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in form.options" :key="item.value" :label="item.label" :value="index">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="地区省" prop="province" class="flex-1 px-2">
						<el-input v-model="formDate.province"></el-input>
					</el-form-item>
					<el-form-item label="地区市" prop="city" class="flex-1 px-2">
						<el-input v-model="formDate.city"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="工程位置" prop="proPosition" class="flex-1 px-2">
						<el-input v-model="formDate.proPosition"></el-input>
					</el-form-item>
					<el-form-item label="建筑面积(㎡)" prop="architecture" class="flex-1 px-2">
						<el-input v-model="formDate.architecture"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="开工日期" prop="startTime" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.startTime"></el-input> -->
						<el-date-picker v-model="formDate.startTime" type="datetime" style="width: 100%;" placeholder="选择日期">
						</el-date-picker>
					</el-form-item>
					<el-form-item label="竣工日期" prop="completedTime" class="flex-1 px-2">
						<!-- <el-input v-model="formDate.completedTime"></el-input> -->
						<el-date-picker v-model="formDate.completedTime" type="datetime" style="width: 100%;" placeholder="选择日期">
						</el-date-picker>
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
		name: 'AddUser',
		props: {
			dialogAdd: Object,
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
				that.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then((res) => {
							// console.log(res.data.states)
							if (res.data.states == 'success') {
								that.dialogAdd.show = false
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
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogAdd.show = false;
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
