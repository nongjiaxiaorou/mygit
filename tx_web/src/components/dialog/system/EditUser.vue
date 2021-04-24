<template>
    <div class="AddPro">
        <el-dialog title="修改账号" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogEdit.show">
            <el-form :model="form" ref="dialogEdit" label-width="80px">
                <!-- <el-form-item label="工程名称" prop="projectName" class="px-2">
                    <el-input v-model="formDate.projectName"></el-input>
                </el-form-item> -->
                <div class="flex">
                    <el-form-item label="账号" prop="account" class="flex-1 px-2">
                        <el-input v-model="form.account" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="密码" prop="password" class="flex-1 px-2">
                        <el-input v-model="form.password"></el-input>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="姓名" prop="username" class="flex-1 px-2">
                        <el-input v-model="form.username"></el-input>
                    </el-form-item>
                    <el-form-item label="部门" class="flex-1 px-2" prop="department">
                        <el-select v-model="form.department" placeholder="请选择部门">
                            <el-option v-for="(option,index) in options" :value="option.department" :key="index">{{option.department}}</el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="flex">
                    <el-form-item label="邮箱" prop="email" class="flex-1 px-2">
                        <el-input v-model="form.email"></el-input>
                    </el-form-item>
                    <el-form-item label="手机" prop="phone" class="flex-1 px-2">
                        <el-input v-model="form.phone"></el-input>
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
    name: 'EditUser',
    props: {
        dialogEdit: Object,
        form: Object
    },
    data() {
        return {
            options: [
                // {id:'1',department:'总公司'},
                // {id:'2',department:'江中公司'}
            ]
        };
    },
    mounted() {
        this.getDepartmentFunc();
    },
    methods: {
        // getDepartmentFunc(){
            
        // },
        dialogFormAdd() {
            var that = this
            var fd = new FormData();
            // console.log(this.form)
            fd.append('flag', 'updateUserAccount');
            fd.append('id', this.form.id);
            // fd.append('account', this.form.account);
            fd.append('password', this.form.password);
            fd.append('username', this.form.username);
            fd.append('email', this.form.email);
            fd.append('phone', this.form.phone);
            fd.append('department', this.form.department);
            fd.append('createTime', this.timeFormate(new Date()));
            // console.log(this.$refs.dialogAdd.validate())
            this.$refs.dialogEdit.validate((valid) => {
                if (valid) {
                    // console.log(valid)
                    this.$axios.post(that.$adminUrl+`/system/User_manage.php`, fd).then((res) => {
                        // console.log(res.data)
                        if (res.data.code) { 
                            setTimeout(() => {
                                this.$emit('handleUpdateUserChild'); //初始化webscoket刷新视图
                                this.$message.success('更新成功！');
                                this.FormTabelEmpty();
                            }, 500);
                            this.dialogEdit.show = false;
                        } else {
                            this.$message.error('当前网络不稳定,请更换网络后重试!')
                        }
                    });
                } else {
                    console.log('error submit!!');
                }
            })
        },
        dialogFormClose(dialogEdit) {
            this.FormTabelEmpty();
            this.dialogEdit.show = false;
        },
        //清空表单
        FormTabelEmpty() {
            for (var i in this.formDate) {
                this.formDate[i] = '';
            }
            this.imageUrl = '';
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
		}
    }
};
</script>
<style>
    .customClass{
        width: 40%;
    }
</style>