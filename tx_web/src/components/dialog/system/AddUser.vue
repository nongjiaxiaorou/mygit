<template>
    <div class="AddPro">
        <el-dialog title="新建账号" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
            <el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
                <!-- <el-form-item label="工程名称" prop="projectName" class="px-2">
                    <el-input v-model="formDate.projectName"></el-input>
                </el-form-item> -->
                <div class="flex">
                    <el-form-item label="账号" prop="account" class="flex-1 px-2">
                        <el-input v-model="formDate.account"></el-input>
						<span class="Warning">{{tip}}</span>
						
                    </el-form-item>
                    <el-form-item label="密码" prop="password" class="flex-1 px-2">
                        <el-input v-model="formDate.password"></el-input>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="姓名" prop="username" class="flex-1 px-2">
                        <el-input v-model="formDate.username"></el-input>
                    </el-form-item>
                    <el-form-item label="公司" class="flex-1 px-2" prop="department">
                        <el-select v-model="formDate.department" placeholder="请选择公司" style="width: 100%;">
                            <el-option v-for="(option,index) in options" :label="option.department" :value="option.id+'/'+option.department" :key="index"></el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="邮箱" prop="email" class="flex-1 px-2">
                        <el-input v-model="formDate.email"></el-input>
                    </el-form-item>
                    <el-form-item label="手机" prop="phone" class="flex-1 px-2">
                        <el-input v-model="formDate.phone"></el-input>
                    </el-form-item>
                </div>
				<div class="flex" v-if="isShowCheckbox">
					<el-form-item label="权限" class="flex-2 px-2">
					    <el-checkbox v-model="isChecked" label="是否设为特权账号"  border></el-checkbox>
					</el-form-item>
					<!-- <el-checkbox v-model="checked" class="flex-1 px-5" v-if="true">是否设为特权账号</el-checkbox> -->
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
	import {isvalidPhone,isvalidEmail} from '@/validate.js';
	export default {
		name: 'AddUser',
		props: {
			dialogAdd: Object
		},
		data() {
			var validPhone = (rule, value, callback) => {
			  if (value === "") {
				callback(new Error("请输入手机号码"));
			  } else if (!isvalidPhone(value)) {
				  // console.log(111)
				callback(new Error("请填写正确的手机号码"));
			  }
			};
			var validEmail = (rule, value, callback) => {
			  if (value === "") {
				callback(new Error("请输入电子邮箱"));
			  } else if (!isvalidEmail(value)) {
				  // console.log(111)
				callback(new Error("请填写正确的电子邮箱"));
			  }
			};
			return {
				formDate: {
					account: '',
					password: '',
					username: '',
					email: '',
					phone: '',
					department: '',
					nowTime: ''
				},
				// ProductActive: '',
				formrules: {
					account: [{ required: true, message: '用户账号不能为空', trigger: 'blur' }],
					password: [{ required: true, message: '用户密码不能为空', trigger: 'blur' },{ min: 6, max: 15, message: '用户密码需要6~15位数', trigger: "blur"}],
					username: [{ required: true, message: '用户姓名不能为空', trigger: 'blur' }],
					email: [{ required: true, message: '邮箱不能为空', trigger: 'blur' }],
					phone: [{ required: true, message: '手机号不能为空', trigger: "blur" }],
					department: [{ required: true, message: "请选择部门", trigger: "blur" }]
				},
				options: [
					// {id:'1',department:'总公司'},
					// {id:'2',department:'江中公司'}
				],
				isChecked: false,
				isShowCheckbox: false,
				tip: ''
			};
		},
		mounted(){
			this.getDepartmentFunc();
			this.authorityFunc(); //判断是否有权限
		},
		methods: {
			// getDepartmentFunc(){
				
			// },
			dialogFormAdd() {
				var that = this
				var fd = new FormData();
				fd.append('flag', 'addUserAccount');
				fd.append('account', this.formDate.account);
				fd.append('password', this.formDate.password);
				fd.append('username', this.formDate.username);
				fd.append('email', this.formDate.email);
				fd.append('phone', this.formDate.phone);
				var departArr = this.formDate.department.split('/');
				var companyId = departArr[0];
				var department = departArr[1];
				fd.append('companyId', companyId);
				fd.append('department', department);
				fd.append('isChecked', this.isChecked);
				fd.append('createTime', this.timeFormate(new Date()));
				// console.log(this.$refs.dialogAdd.validate())
				console.log(this.$refs)
				this.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						// console.log(valid)
						this.$axios.post(that.$adminUrl+`/system/User_manage.php`, fd).then((res) => {
							console.log(res.data)
							if (res.data.code) {
								setTimeout(() => {
									this.$emit('handleAddUserChild'); //初始化webscoket刷新视图
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
				this.isChecked = false
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
			getDepartmentFunc(){
				var that = this
				var fd = new FormData()
				fd.append('flag','getDepartment')
				// console.log(that.$adminUrl+`/system/User_manage.php`)
				this.$axios.post(that.$adminUrl+`/system/User_manage.php`,fd).then(res =>{
					// console.log(res.data)
					if(res.data.code){
						// console.log(res.data.data)
						this.options = res.data.data
					}
				}) 
			},
			authorityFunc(){
				// console.log(localStorage.getItem('ms_username'))
				var username = localStorage.getItem('ms_username');
				if(username==='admin'){
					this.isShowCheckbox = 'true'
				}
			},
			checkAccount(account){
				var that = this;
				var fd = new FormData()
				fd.append('flag','checkAccountFlag')
				fd.append('account',account)
				// console.log(that.$adminUrl+`/system/User_manage.php`)
				this.$axios.post(that.$adminUrl+`/system/User_manage.php`,fd).then(res =>{
					// console.log(res.data)
					if(res.data.code){
						this.tip = '该用户已存在'
					}else{
						this.tip = ''
					}
				}) 
			}
		},
		watch: {
			"formDate.account": {
				handler(newValue, oldValue) {
					// console.log(newValue);
					// 调用后台接口验证用户名的合法性
					this.checkAccount(newValue);
					//修改验证信息
					this.tip = '正在验证。。。'
				}
			}
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