<template>
	<div class="AddPro">
		<el-dialog title="巡查活动修改" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogEdit.show">
			<el-form :model="form" ref="dialogAdd" label-width="80px" :rules="formrules">
				<el-form-item label="巡查名称" prop="inspectName" class="px-2">
					<el-input v-model="form.inspectName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="巡查层级" prop="inspectLevel" class="flex-1 px-2">
						<el-select v-model="form.inspectLevel" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectLevelList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="巡查类型" prop="inspectCate" class="flex-1 px-2">
						<el-select v-model="form.inspectCate" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectCateList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="巡查小组" prop="inspectGroup" class="flex-1 px-2">
						<el-select v-model="form.inspectGroup" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectGroupList" :key="item.value" :label="item.label" :value="item.label">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogEdit')">取 消</el-button>
				<el-button type="primary" @click="dialogFormEdit()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'AddUser',
		props: {
			dialogEdit: Object,
			form: Object
		},
		data() {
			return {
				inspectLevelList: [{
					value:'总公司',
					label:'总公司'
				},
				{
					value:'分公司',
					label:'分公司'
				}],
				inspectCateList:[{
					value:'日常质量巡检',
					label:'日常质量巡检'
				},
				{
					value:'专项质量巡检',
					label:'专项质量巡检'
				},
				{
					value:'月度质量巡检',
					label:'月度质量巡检'
				},
				{
					value:'季度质量巡检',
					label:'季度质量巡检'
				}],
				inspectGroupList:[],
				formrules: {
					// username: [{ required: true, message: '班级信息不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			this.getGroupData()
		},
		methods: {
			//异步获取巡查小组
			getGroupData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getGroupData')
				// console.log(this.$loading())
				that.$axios.post(that.$adminUrl + `/inspect/InspectGroup.php`, fd).then(res => {
					// console.log(res.data.data)
					let oldArr = res.data.data
					let newArr = that.commonFunc.Es5duplicate(oldArr,'groupName')
					for(let i=0;i<newArr.length;i++){
						that.inspectGroupList.push({
							value:newArr[i].groupName+"/"+newArr[i].groupID,
							label:newArr[i].groupName
						})
					}
					if (that.isLoading) {
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
					}
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//模态框的修改
			dialogFormEdit() {
				console.log()
				const that = this
				var fd = new FormData()
				fd.append('flag', 'changeTask')
				fd.append('formData', JSON.stringify(this.form))
				that.$axios.post(that.$adminUrl + `/inspect/EditInsDefine.php`, fd).then((res) => {
					console.log(res)
					this.dialogFormClose()
					this.$emit('update')
				}).catch(() => {
				
				})
			},
			
			//关闭模态框
			dialogFormClose(dialogEdit) {
				this.dialogEdit.show = false;
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
