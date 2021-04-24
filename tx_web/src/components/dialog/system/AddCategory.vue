<template>
	<div class="AddViolations">
		<el-dialog title="新建工程类别" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="90px">
				<el-form-item label="工程类别" prop="category" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.category"  placeholder="请输入工程类别"></el-input>
				</el-form-item>
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
		name: 'Add',
		props: {
			dialogAdd: Object,
		},
		data() {
			return {
				formDate: {
					category: '',//工程类别
				},
			};
		},
		watch: {

		},
		mounted() {
			
		},
		methods: {
			//新增工程类别
			dialogFormAdd() {
				const that = this
				let fd = new FormData();
				fd.append('flag', 'addCategory')
				fd.append('category', that.formDate.category)
				
				that.$axios.post(that.$adminUrl + `/system/Category.php`, fd).then((res) => {
					console.log(res)
					that.dialogAdd.show = false
					that.FormTabelEmpty()
					that.$emit('update')
				}).catch(() => {
				
				})
			},
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.dialogAdd.show = false;
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
