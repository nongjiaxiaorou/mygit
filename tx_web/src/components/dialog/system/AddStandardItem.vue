<template>
    <div class="AddPro">
        <el-dialog title="新建账号" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
            <el-form :model="formDate" ref="dialogAdd" label-width="100px" :rules="formrules">
                <!-- <el-form-item label="工程名称" prop="projectName" class="px-2">
                    <el-input v-model="formDate.projectName"></el-input>
                </el-form-item> -->
				<div class="flex">
					<el-form-item label="工程类别" class="flex-1 px-2" prop="projectType">
					    <el-select v-model="formDate.projectType" placeholder="请选择工程类别">
					        <el-option v-for="(option,index) in options" :label="option.label" :value="option.value" :key="index"></el-option>
					    </el-select>
					</el-form-item>
					<el-form-item label="实测类型" class="flex-1 px-2" prop="measurementType">
					    <el-select v-model="formDate.measurementType" placeholder="请选择实测类型">
					        <el-option v-for="(option,index) in measureOptions" :label="option.label" :value="option.value" :key="index"></el-option>
					    </el-select>
					</el-form-item>
				</div>
                <div class="flex">
					<el-form-item label="检查项目" prop="inspectionItem" class="flex-1 px-2">
					    <el-input v-model="formDate.inspectionItem" placeholder="例如: 抹灰工程"></el-input>
					</el-form-item>
                    <el-form-item label="检查内容" prop="inspectionContent" class="flex-1 px-2">
                        <el-input v-model="formDate.inspectionContent" placeholder="请输入检查内容"></el-input>
						<span class="Warning">{{tip}}</span>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="最小标准值" prop="miniStandardValue" class="flex-1 px-2">
                        <el-input v-model="formDate.miniStandardValue" placeholder="例如: 1.0"></el-input>
                    </el-form-item>
                    <el-form-item label="最大标准值" prop="maxStandardValue" class="flex-1 px-2">
                        <el-input v-model="formDate.maxStandardValue" placeholder="例如: 2.0"></el-input>
                    </el-form-item>
                </div>
				<div class="flex">
					<el-form-item label="编号" prop="number" class="flex-1 px-2">
					    <el-input v-model="formDate.number" placeholder="请输入编号"></el-input>
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
		name: 'AddStandardItem',
		props: ['dialogAdd','selectValue1'],
		data() {
			return {
				formDate: {
					projectType: '',
					inspectionItem: '',
					inspectionContent: '',
					number: '',
					miniStandardValue: '',
					maxStandardValue: '',
					measurementType: ''
				},
				// ProductActive: '',
				formrules: {
					projectType: [{ required: true, message: '工程类别不能为空', trigger: 'blur' }],
					measurementType: [{ required: true, message: '实测类型不能为空', trigger: 'blur' }],
					inspectionItem: [{ required: true, message: '检查项目不能为空', trigger: 'blur' }],
					inspectionContent: [{ required: true, message: '检查内容不能为空', trigger: 'blur' }],
					miniStandardValue: [{ required: true, message: '最小标准值不能为空', trigger: 'blur' }],
					maxStandardValue: [{ required: true, message: '最大标准值不能为空', trigger: 'blur' }],
					number: [{ required: true, message: '编号不能为空', trigger: "blur" }],
				},
				options: [
					{
					  value: '建筑工程',
					  label: '建筑工程'
					}, {
					  value: '装修工程',
					  label: '装修工程'
					}
				],
				measureOptions: [
					{
					  value: '实测实量',
					  label: '实测实量'
					}, {
					  value: '检查验收',
					  label: '检查验收'
					}
				],
				isChecked: false,
				isShowCheckbox: false,
				tip: ''
			};
		},
		mounted(){
		},
		methods: {
			// getDepartmentFunc(){
				
			// }, 
			dialogFormAdd() {
				var that = this
				var fd = new FormData();
				fd.append('flag', 'addInspectionItem');
				fd.append('projectType', this.formDate.projectType);
				fd.append('inspectionItem', this.formDate.inspectionItem);
				fd.append('inspectionContent', this.formDate.inspectionContent);
				fd.append('number', this.formDate.number);
				fd.append('miniStandardValue', this.formDate.miniStandardValue);
				fd.append('maxStandardValue', this.formDate.maxStandardValue);
				fd.append('measurementType', this.formDate.measurementType);
				fd.append('tableType', this.selectValue1)
				// console.log(this.$refs.dialogAdd.validate())
				// console.log(this.$refs)
				this.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						// console.log(valid)
						this.$axios.post(that.$adminUrl+`/system/measureStandard.php`, fd).then((res) => {
							console.log(res.data)
							if (res.data.code) {
								setTimeout(() => {
									this.$emit('handleAddInspectionItemChild'); //初始化webscoket刷新视图
									this.$message.success('新增成功！');
									this.FormTabelEmpty();
								}, 500);
								this.dialogAdd.show = false;
							} else {
								this.$message.error('当前网络不稳定,请更换网络后重试!')
							}
						});
					} else {
						console.log('error submit!!');
					}
				})
			},
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogAdd.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				// this.imageUrl = '';
				// this.formDate.rge_time = this.adminApi.getNowDate();
			},
			timeFormate(timeStamp) {
				let year = new Date(timeStamp).getFullYear();
				let month =new Date(timeStamp).getMonth() + 1 < 10? "0" + (new Date(timeStamp).getMonth() + 1): new Date(timeStamp).getMonth() + 1;
				let date =new Date(timeStamp).getDate() < 10? "0" + new Date(timeStamp).getDate(): new Date(timeStamp).getDate();
				let hh =new Date(timeStamp).getHours() < 10? "0" + new Date(timeStamp).getHours(): new Date(timeStamp).getHours();
				let mm =new Date(timeStamp).getMinutes() < 10? "0" + new Date(timeStamp).getMinutes(): new Date(timeStamp).getMinutes();
				let ss =new Date(timeStamp).getSeconds() < 10? "0" + new Date(timeStamp).getSeconds(): new Date(timeStamp).getSeconds();
				return year + "-" + month + "-" + date +" "+hh+":"+mm+':'+ss ;
			},
		},
		watch: {
		},
	};
</script>
<style>
    .customClass{
        width: 40%;
    }
	.Warning{
		color: #E6A23C;
	}
</style>
