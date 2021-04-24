<template>
	<!-- <el-button type="text" @click="dialogFormVisible = true">打开嵌套表单的 Dialog</el-button> -->
	<div style="">
		<el-dialog title="新增分包商" width="30%" :visible.sync="dialogUnit.show" @close="resetForm">
			<el-form :model="form" ref="dialogUnitRef" class="custom-class" :rules="formrules">
				<el-row :gutter="20">
					<el-col :span="20">
						<el-form-item label="分包单位" :label-width="formLabelWidth" prop="name">
							<el-input v-model="form.name" autocomplete="off"></el-input>
						</el-form-item>
					</el-col>
				</el-row>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="cancelDialog">取 消</el-button>
				<el-button type="primary" @click="saveUnit">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'add-unit',
		props: {
			dialogUnit: Object,
			registerBaseData: Object
		},
		data() {
			return {
				form: {
					name: ''
				},
				timeStamp: '',
				formLabelWidth: '120px',
				formrules: {
					Unit: [{ required: true, message: "分包单位不能为空", trigger: "blur" }]
				},
			}
		},
		computed: {

		},
		mounted() {
		},
		methods: {
			cancelDialog(){
				this.dialogUnit.show = false
			},
			saveUnit(){
				var fd = new FormData();
				fd.append('flag', 'addUnit');
				var registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				
				fd.append('timeStamp', registerBaseData.timeStamp);
				fd.append('Unit', this.form.name);
				fd.append('createTime', this.timeFormate(new Date()));
				// console.log(this.$adminUrl +`/project/detail.php`)
				this.$refs.dialogUnitRef.validate((valid) => {
					if (valid) {
						this.$axios.post(this.$adminUrl+`/project/unit.php`, fd).then((res) => {
							// console.log(res.data)
							if (res.data.code) {
								setTimeout(() => {
									this.$emit('handleAddUnitChild'); //初始化webscoket刷新视图
									this.$message.success('新增成功！');
								}, 500);
								this.dialogUnit.show = false;
							} else {
								this.$message.warning('该分包单位已存在，请更换分包单位后重试!')
							}
						});
					} else {
						console.log('error submit!!');
					}
				})
			},
			//清空表单数据
			resetForm() {
				this.$refs['dialogUnitRef'].resetFields();
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
			registerBaseData(){
				this.timeStamp = this.registerBaseData.timeStamp
			}
		}
	}
</script>
	
<style>
	/* .custom-class{
		display: flex;
		flex-direction: column;
		justify-content: center;
		width: 500px;
		height: 80px;
		align-items: center;
		font-size: 8rem;
		background: hsl(240, 100%, 67%);

	} */
</style>
