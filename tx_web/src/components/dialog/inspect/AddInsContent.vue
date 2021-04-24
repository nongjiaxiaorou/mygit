<template>
	<div class="AddPro">
		<el-dialog title="新建任务检查项" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formData" ref="dialogAdd" label-width="100px" :rules="formrules">
				<el-form-item label="扣分表大类" prop="deductTableCate" class="px-2">
					<!-- <el-input v-model="formData.inspectName"></el-input> -->
					<el-select v-model="formData.deductTableCate" @change="tableCateChange" placeholder="请选择" style="width: 100%;">
						<el-option v-for="(item,index) in deductTableCateList" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</el-form-item>
				<div class="flex">
					<el-form-item label="巡查类型" prop="inspectCate" class="flex-1 px-2">
						<el-select v-model="formData.inspectCate" @change="inspectCateChange" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in inspectCateList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="检查问题" class="flex-1 px-2">
						<el-select multiple collapse-tags v-model="formData.inspectQues" @change="selectAll" placeholder="请选择" style="width: 100%;">
							<el-option v-for="item in inspectQuesList" :key="item.key" :label="item.label" :value="item.value">
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
			inspectDefineData: Object
		},
		data() {
			return {
				formData: {
					deductTableCate: [],
					inspectCate: [],
					inspectQues: []
				},
				deductTableCateList: [],
				inspectCateList: [],
				inspectQuesList: [{value: '选项0',label: '所有选项',key:'0'}],
				value11: [],
				oldOptions: [
					[]
				],
				allValues:[],
				formrules: {
					// username: [{ required: true, message: '班级信息不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			this.getTableCate()
		},
		methods: {
			//异步获取扣分表大类
			getTableCate(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getTableCate')
				// console.log(this.$loading())
				that.$axios.post(that.$adminUrl + `/inspect/AddInsContent.php`, fd).then(res => {
					// console.log(res.data.data[0].deductTableCate)
					if (res.data.data.length > 0) {
						let oldArr = res.data.data
						for (let i = 0; i < oldArr.length; i++) {
							that.deductTableCateList.push({
								value: oldArr[i].deductTableCate,
								label: oldArr[i].deductTableCate
							})
						}
					} else {
						this.formData.deductTableCate = '注意！请先导入扣分表'
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
			//获取选中扣分表大类
			tableCateChange(e) {
				this.formData.inspectCate = ''
				this.formData.inspectQues = ''
				//根据扣分表大类获取巡查类型
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getInspectCate')
				fd.append('deductTableCate', e)
				// console.log(this.$loading())
				that.$axios.post(that.$adminUrl + `/inspect/AddInsContent.php`, fd).then(res => {
					// console.log(res.data.data)
					let oldArr = res.data.data
					that.inspectCateList = []
					for (let i = 0; i < oldArr.length; i++) {
						that.inspectCateList.push({
							value: oldArr[i].itemsCate,
							label: oldArr[i].itemsCate
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
			//获取选中巡查大类
			inspectCateChange(e) {
				this.formData.inspectQues = ''
				//根据巡查大类获取检查问题
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getInspectQues')
				fd.append('itemsCate', e)
				// console.log(this.$loading())
				that.$axios.post(that.$adminUrl + `/inspect/AddInsContent.php`, fd).then(res => {
					// console.log(res.data.data[0].probDescribe)
					let oldArr = res.data.data
					let newArr = this.commonFunc.Es5duplicate(oldArr,'probDescribe')//去重
					that.inspectQuesList = [{value: '选项0',label: '所有选项',key:'0'}]
					that.options = [{value: '选项0',label: '所有选项',key:'0'}]
					for (let i = 0; i < newArr.length; i++) {
						that.inspectQuesList.push({
							value: newArr[i].probDescribe+"||"+newArr[i].fullMarks,
							label: newArr[i].probDescribe,
							key: newArr[i].id
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
			/**
			 * 选择
			 */
			selectAll(val) {
				this.allValues = []
				//保留所有值
				for (let item of this.inspectQuesList) {
					this.allValues.push(item.value)
				}
				// 用来储存上一次的值，可以进行对比
				const oldVal = this.oldOptions.length === 1 ? [] : this.oldOptions[1]

				// 若是全部选择
				if (val.includes('选项0')) this.formData.inspectQues = this.allValues

				// 取消全部选中  上次有 当前没有 表示取消全选
				if (oldVal.includes('选项0') && !val.includes('选项0')) this.formData.inspectQues = []

				// 点击非全部选中  需要排除全部选中 以及 当前点击的选项 
				// 新老数据都有全部选中 
				if (oldVal.includes('选项0') && val.includes('选项0')) {
					const index = val.indexOf('选项0')
					val.splice(index, 1) // 排除全选选项
					this.formData.inspectQues = val
				}

				//全选未选 但是其他选项全部选上 则全选选上 上次和当前 都没有全选
				if (!oldVal.includes('选项0') && !val.includes('选项0')) {
					if (val.length === this.allValues.length - 1) this.formData.inspectQues = ['选项0'].concat(val)
				}

				//储存当前最后的结果 作为下次的老数据 
				this.oldOptions[1] = this.formData.inspectQues
			},
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData()
				fd.append('flag', 'saveInsItems')
				fd.append('formData', JSON.stringify(this.formData))
				fd.append('taskId', that.inspectDefineData.taskId)
				// console.log(JSON.stringify(this.formData)+" "+that.inspectDefineData.taskId)
				that.$axios.post(that.$adminUrl + `/inspect/AddInsContent.php`, fd).then((res) => {
					console.log(res)
					this.dialogFormClose()
					this.$emit('update')
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
				for (var i in this.formData) {
					this.formData[i] = '';
				}
				this.formData.rge_time = this.commonFunc.getNowDate();
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
