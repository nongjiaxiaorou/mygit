<template>
	<div class="AddAnnouncement">
		<el-dialog title="新增部门公告" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form ref="dialogAdd" label-width="80px">
				<div class="flex">
					<el-form-item label="公告标题" class="flex-1 px-2">
						<el-input placeholder="请输入公告标题" v-model="formData.title"></el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="公告内容" class="flex-1 px-2">
						<el-input type="textarea" :rows="2" placeholder="请输入公告内容" v-model="formData.content">
						</el-input>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="外部链接" class="flex-1 px-2">
						<el-input type="textarea" :rows="2" placeholder="请输入https://" v-model="formData.content">
						</el-input>
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
		props: {
			dialogAdd: Object,
			form: Object
		},
		data() {
			return {
				formData:{
					title:'',
					content:''
				}
			}
		},
		methods:{
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				fd.append('flag', 'addAnnouncement');
				fd.append('formData', JSON.stringify(that.formData));
				fd.append('timeStamp', new Date().getTime());
				that.$axios.post(that.$adminUrl + `/index/AddAnnouncement.php`, fd).then((res) => {
					// console.log(res.data.code)
					if (res.data.code == 1) {
						that.dialogAdd.show = false
						that.$emit('update')
						that.FormTabelEmpty();
					} else {
							
					}
				}).catch(() => {
							
				})
			},
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogAdd.show = false;
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
