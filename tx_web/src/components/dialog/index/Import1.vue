<template>
    <el-dialog
        title="人员导入"
        :visible.sync="form.dialogFormUpload"
        :modal-append-to-body="false"
        width="40%"
        class="dialog"
    >
        <el-upload
            :on-success="success"
            :on-exceed="fileExceed"
            :on-change="onchangeFunc"
            drag
            :auto-upload="false"
            ref="upload"
            :limit="1"
            :action="target"
            multiple
            class="el-upload"
        >
            <i class="el-icon-upload"></i>
            <div class="el-upload__text" width="100%">
                将文件拖到此处，或
                <em>点击上传</em>
            </div>
        </el-upload>
        <div class="el-upload__tip0">1、只能上传xlsx、lsx文件</div>
        <div class="el-upload__tip0">2、excel表格支持如下格式:</div>
        <!-- <div class="el-upload__tip">身份证号_姓名</div>
        <div class="el-upload__tip">身份证号_姓名_学号_宿舍栋号_宿舍号_性别</div>
        <div class="el-upload__tip">身份证号_姓名_学号_班级_学院_专业_宿舍栋号_宿舍号_性别_联系电话_邮箱_籍贯</div>
        <div class="el-upload__tip0">3、导入完成后请刷新页面</div> -->
        <div slot="footer" class="dialog-footer">
            <el-button @click="canel('upload')">取 消</el-button>
            <el-button type="primary" @click="upload()">导 入</el-button>
        </div>
    </el-dialog>
</template>

<script>
export default {
    name: 'Import',
    props: {
        //期待
        form: Object
    },
    data() {
        return {
            dialogFormUpload: '',
            target: `http://localhost:8081/dormphp/src/Import_Info.php`
        };
    },
    methods: {
        //选择文件时触发
        onchangeFunc(file, fileList) {
            this.fileList = fileList;
            // console.log(this.fileList)
        },
        //导入按钮
        upload() {
            this.form.dialogFormUpload = false;
            this.fileList = ""
            if (this.$refs.upload.uploadFiles) {
                this.$refs.upload.submit(); // 上传
            } else {
                this.$message({
                    type: 'warning',
                    message: '您未添加任何文件！'
                });
            }
        },
        canel() {
            this.form.dialogFormUpload = false;
        },
        // 超出上传限制提示框
        fileExceed(e) {
            let options = {
                message: '已添加文件！',
                type: 'warning',
                duration: 2000
            };
            Message(options);
        },
        // 上传成功回调
        success(res) {
            console.log(res);
            this.$emit('updateImp');
        }
    }
};
</script>
<style lang="scss" scoped>
.el-upload__tip0 {
    margin-left: 60px;
    font-size: 15px;
    margin-top: 10px;
}
.el-upload__tip {
    margin-left: 85px;
    font-size: 15px;
}
.el-upload {
    display: block !important;
}
</style>