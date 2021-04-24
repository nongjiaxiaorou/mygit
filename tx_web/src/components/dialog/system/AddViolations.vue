<template>
	<div class="AddViolations">
		
		<el-dialog title="新建违章条目" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="90px">
				<div class="flex">
					<el-form-item label="违章数据库" prop="createTime" style="width: 45%;">
						<el-select v-model="formDate.depotId" placeholder="请选择违章数据库" style="width: 100%;">
							<el-option v-for="item in form.depotList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="违章大类" prop="createTime" style="width: 45%;">
						<el-select v-model="formDate.majorCategories" placeholder="请选择违章大类" style="width: 100%;">
							<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.label">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<el-form-item label="分部分项" prop="subItems" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.subItems"  placeholder="请输入分部分项,例:1、地基与基础工程	"></el-input>
				</el-form-item>
				<el-form-item label="管控要点" prop="mainPoints" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.mainPoints"  placeholder="请输入管控要点"></el-input>
				</el-form-item>
				<el-form-item label="条目编号" prop="number" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.number"  placeholder="请输入条目编号,例:1.1"></el-input>
				</el-form-item>
				<el-form-item label="问题描述" prop="describe" class="flex-1 px-2 my-3" style="width:90%;">
					<el-input v-model="formDate.describe" type="textarea" autosize placeholder="请输入内容"></el-input>
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
			form : Object
		},
		data() {
			return {
				formDate: {
					depotId: '',//违章数据库id
					majorCategories: '',//违章大类
					subItems: '',//分部分项
					mainPoints: '',//管控要点
					describe:'',//问题面描述
					number:''//编号
				},
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
			//新增公司
			dialogFormAdd() {
				const that = this
				let fd = new FormData();
				fd.append('flag', 'addItem')
				fd.append('majorCategories', that.formDate.majorCategories)
				fd.append('subItems', that.formDate.subItems)
				fd.append('mainPoints', that.formDate.mainPoints)
				fd.append('describe', that.formDate.describe)
				fd.append('number', that.formDate.number)
				fd.append('depotId', that.formDate.depotId)
				// console.log(that.formDate.majorCategories)
				that.$axios.post(that.$adminUrl + `/system/Database.php`, fd).then((res) => {
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
