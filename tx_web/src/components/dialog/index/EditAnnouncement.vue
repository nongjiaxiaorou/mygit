<template>
	<div class="AddAnnouncement">
		<el-dialog title="新增部门公告" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogEdit.show">
			<el-form v-model="form" ref="dialogEdit" label-width="80px">
				<div class="flex">
					<el-form-item label="公告标题" class="flex-1 px-2">
						<el-input placeholder="请输入公告标题" v-model="form.title"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="公告内容" class="flex-1 px-2">
						<el-input type="textarea" :rows="2" placeholder="请输入公告内容" v-model="form.content">
						</el-input>
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
		props: {
			dialogEdit: Object,
			form: Object
		},
		data() {
			return {
				
			}
		},
		methods:{
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				fd.append('flag', 'editAnnouncement');
				fd.append('formData', JSON.stringify(that.form));
				that.$axios.post(that.$adminUrl + `/index/EditAnnouncement.php`, fd).then((res) => {
					// console.log(res)
					if (res.data.code == 1) {
						that.dialogEdit.show = false
						that.$emit('update')
						that.FormTabelEmpty();
					} else {
							
					}
				}).catch(() => {
							
				})
			},
			//关闭模态框
			dialogFormClose(dialogEdit) {
				this.FormTabelEmpty();
				this.dialogEdit.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
			},
		}
	}
</script>

<style>
</style>
