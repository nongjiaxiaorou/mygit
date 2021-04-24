<template>
	<div class="ChangeCategory">
		<el-dialog title="修改工程类别" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogChange.show">
			<el-form :model="form" ref="dialogChange" label-width="90px">
				<el-form-item label="工程类别" prop="category" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.category" placeholder="请输入内容"></el-input>
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
		name: 'change',
		props: {
			dialogChange: Object,
			form:Object
		},
		data() {
			return {
				options: [{
					value: '选项1',
					label: '重大问题'
				}, {
					value: '选项2',
					label: '一般问题'
				}],
				value: '',
				// formrules: {
				// 	companyRank: [{
				// 		required: true,
				// 		message: '公司层级不能为空',
				// 		trigger: 'blur'
				// 	}]
				// }
			};
		},
		watch: {

		},
		mounted() {
			
		},
		methods: {
			//修改条目
			dialogFormChange() {
				const that = this
				let fd = new FormData();
				fd.append('flag', 'changeCategory')
				fd.append('id', that.form.id)
				fd.append('category', that.form.category)
				
				that.$axios.post(that.$adminUrl + `/system/Category.php`, fd).then((res) => {
					console.log(res)
					that.dialogChange.show = false
					that.FormTabelEmpty()
				}).catch(() => {
				
				})
			},
			//关闭模态框
			dialogFormClose(dialogChange) {
				this.dialogChange.show = false;
				this.FormTabelEmpty()
			},
			//清空表单
			FormTabelEmpty() {
			    for (var i in this.formDate) {
			        this.formDate[i] = '';
			    }
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
