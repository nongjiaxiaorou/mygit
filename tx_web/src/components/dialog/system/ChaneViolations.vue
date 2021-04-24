<template>
	<div class="ChangeViolations">
		<el-dialog title="修改违章条目" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogChange.show">
			<el-form :model="form" ref="dialogChange" label-width="90px">
				<el-form-item label="违章大类" prop="createTime" class="flex-1 px-2 my-3" style="width:90%;">
					<el-select v-model="form.majorCategories" placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="分部分项" prop="subItems" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.subItems"  placeholder="请输入分部分项,例:1、地基与基础工程	"></el-input>
				</el-form-item>
				<el-form-item label="管控要点" prop="mainPoints" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.mainPoints"  placeholder="请输入管控要点"></el-input>
				</el-form-item>
				<el-form-item label="条目编号" prop="number" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.number"  placeholder="请输入条目编号,例:1.1"></el-input>
				</el-form-item>
				<el-form-item label="问题描述" prop="describe" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="form.describe" type="textarea" autosize placeholder="请输入内容"></el-input>
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
				fd.append('flag', 'changeItem')
				fd.append('id', that.form.id)
				fd.append('majorCategories', that.form.majorCategories)
				fd.append('subItems', that.form.subItems)
				fd.append('mainPoints', that.form.mainPoints)
				fd.append('describe', that.form.describe)
				fd.append('number', that.form.number)
				
				that.$axios.post(that.$adminUrl + `/system/Database.php`, fd).then((res) => {
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
