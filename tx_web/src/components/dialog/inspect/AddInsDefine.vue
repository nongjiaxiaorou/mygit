<template>
	<div class="AddPro">
		<el-dialog title="新建定义" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
				<el-form-item label="巡查名称" prop="inspectName" class="px-2">
					<el-input v-model="formDate.inspectName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="巡查层级" prop="inspectLevel" class="flex-1 px-2">
						<el-select v-model="formDate.inspectLevel" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectLevelList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="巡查类型" prop="inspectCate" class="flex-1 px-2">
						<el-select v-model="formDate.inspectCate" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectCateList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="巡查小组" prop="inspectGroup" class="flex-1 px-2">
						<el-select v-model="formDate.inspectGroup" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectGroupList" :key="item.value" :label="item.label" :value="item.label">
							</el-option>
						</el-select>
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
		name: 'AddUser',
		props: {
			dialogAdd: Object,
			form: Object
		},
		data() {
			return {
				formDate: {
					inspectName: '',
					inspectLevel: '',
					inspectCate: '',
					inspectGroup: '',
					creatorId:''
				},
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
			this.formDate.creatorId = JSON.parse(localStorage.getItem('userInfo')).userId
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
			//模态框的新增
			dialogFormAdd() {
				console.log()
				const that = this
				var fd = new FormData()
				fd.append('flag', 'addTask')
				fd.append('formDate', JSON.stringify(this.formDate))
				fd.append('timeStamp', new Date().getTime())
				console.log(JSON.stringify(this.formDate))
				that.$axios.post(that.$adminUrl + `/inspect/AddInsDefine.php`, fd).then((res) => {
					console.log(res)
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
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
