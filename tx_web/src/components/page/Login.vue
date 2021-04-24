<template>
    <div class="login-wrap">
        <div class="ms-login">
            <div class="ms-title">同欣质量管理系统</div>
            <el-form :model="param" :rules="rules" ref="login" label-width="0px" class="ms-content">
                <el-form-item prop="account">
                    <el-input v-model="param.account" placeholder="account">
                        <el-button slot="prepend" icon="el-icon-lx-people"></el-button>
                    </el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input
                        type="password"
                        placeholder="password"
                        v-model="param.password"
                        @keyup.enter.native="submitForm()"
                    >
                        <el-button slot="prepend" icon="el-icon-lx-lock"></el-button>
                    </el-input>
                </el-form-item>
                <div class="login-btn">
                    <el-button type="primary" @click="submitForm()">登录</el-button>
                </div>
                <p class="login-tips">Tips : 用户名和密码随便填。</p>
            </el-form>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            param: {
                account: 'admin',
                password: '123456',
            },
            rules: {
                account: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
                password: [{ required: true, message: '请输入密码', trigger: 'blur' }],
            },
        };
    },
    methods: {
        submitForm() {
            this.$refs.login.validate(valid => {
                if (valid) {
                    var fd  = new FormData()
                    const that = this
                    // console.log(this);
                    fd.append("account",that.param.account)
                    fd.append("password",that.param.password)
                    fd.append("flag",'Login')
                    // console.log(this.$adminUrl)
                    
                    that.$axios.post(this.$adminUrl+`/login/Login.php`,fd).then(res=>{
                        console.log(res.data.code) 
                        console.log(res) 
                        if(res.data.code=== 20000){//密码正确
							localStorage.setItem('ms_username', this.param.account); // 为啥要缓存用户名?
							let userObj = {
								userId:res.data.data.userid,
								userName:res.data.data.username,
								company:res.data.data.department,
								phone:res.data.data.phone,
								account:res.data.data.account,
							}
							localStorage.setItem('userInfo',JSON.stringify(userObj))
							localStorage.setItem('privilegeAccount', res.data.data.privilegeAccount);
							localStorage.setItem('authority', res.data.data.authority);
                            that.$message.success('登录成功');
                            that.$router.push('/');
                        }else{
                            this.$message.error("密码或账号错误，请重新输入！")
                        }
                    })
                    // this.$router.push('/');
                } else {
                    this.$message.error('请输入账号和密码');
                    // console.log('error submit!!');
                    return false;
                }
            });
        },
    },
};
</script>

<style scoped>
.login-wrap {
    position: relative;
    width: 100%;
    height: 100%;
    background-image: url(../../assets/img/login-bg.jpg);
    background-size: 100%;
}
.ms-title {
    width: 100%;
    line-height: 50px;
    text-align: center;
    font-size: 20px;
    color: #fff;
    border-bottom: 1px solid #ddd;
}
.ms-login {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 350px;
    margin: -190px 0 0 -175px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.3);
    overflow: hidden;
}
.ms-content {
    padding: 30px 30px;
}
.login-btn {
    text-align: center;
}
.login-btn button {
    width: 100%;
    height: 36px;
    margin-bottom: 10px;
}
.login-tips {
    font-size: 12px;
    line-height: 30px;
    color: #fff;
}
</style>