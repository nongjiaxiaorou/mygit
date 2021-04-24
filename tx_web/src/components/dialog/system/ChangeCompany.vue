<template>
	<div class="ChangeCompany">
		<el-dialog title="修改公司名称" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogChange.show">
			<el-form :model="form" ref="dialogChange" label-width="90px">
				<el-form-item label="公司名称" prop="companyName" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.companyName"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogChange')">取 消</el-button>
				<el-button type="primary" @click="dialogFormChange()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'AddUser',
		props: {
			dialogChange: Object,
			form: Object,
		},
		data() {
			return {
				value: '',
			};
		},
		methods: {
			//新增公司
			dialogFormChange() {
				var fd = new FormData();
				fd.append('flag', 'changeCompany')
				fd.append('companyName', this.form.companyName)
				fd.append('companyId', this.form.companyId)
				
				this.$refs.dialogChange.validate((valid) => {
					if (valid) {
						this.$axios.post(this.$adminUrl + `/system/Company.php`, fd).then((res) => {
							console.log(res)
							this.$emit('update')
							this.dialogChange.show = false
						}).catch(() => {
							
						})
					} else {
						console.log('error submit!!');
					}
				});
			},
			//关闭模态框
			dialogFormClose(dialogChange) {
				this.dialogChange.show = false;
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
