<template>
	<div>
		<el-row style="width: 80%;">
			<el-col :span="24">
				<div class="grid-content title-text title-box">分包单位
					<el-button type="primary" @click="addUnitFunc" style="float: right;" class="mx-1">新增</el-button>
					<el-button type="primary" style="float: right;" class="mx-1" @click="importExcelFunc">Excel批量导入</el-button>
				</div>
			</el-col>
			<div class="grid-content bg-purple-light">
				<el-table ref="multipleTable" @selection-change="handleSelectionChange" :data="tableData" stripe style="width: 100%;">
					<el-table-column type="selection" width="55">
					</el-table-column>
					<el-table-column align="center" label="序号" type="index" width="100">
					</el-table-column>
					<el-table-column prop="createTime" align="center" label="创建时间">
					</el-table-column>
					<el-table-column prop="subcontractor" align="center" label="分包单位">
					</el-table-column>
					<el-table-column align="center" label="操作" width="200">
						<template slot-scope="scope">
							<el-button @click.native.prevent="deleteRow(scope.$index, scope.row)" type="text" size="small">
								删除
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</div>
		</el-row>
		<ImportUnit @handleImportUnitChild="handleImportUnit" :registerBaseData = "registerBaseData" :dialogImportUnit = "dialogImportUnit"></ImportUnit>
		<AddUnit @handleAddUnitChild="handleAddUnit" :registerBaseData = "registerBaseData" :dialogUnit = "dialogUnit"></AddUnit>
	</div>
</template>

<script>
	import AddUnit from "./AddUnit"
	import ImportUnit from "./ImportUnit"
	export default {
		name: 'register-person',
		components: {
		    AddUnit,
			ImportUnit
		},
		props: {
			registerBaseData:Object
		},
		data() {
			return {
				loading: false,
				tabPosition: 'left',
				activeName: '',
				tableData: [
				],
				value: '',
				dialogUnit: {
					show: false
				},
				dialogImportUnit: {
					show: false
				}
			}
		},
		computed: {
	
		},
		mounted() {
			this.getUnitFunc()
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
			},
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			addNode() {
				this.nodeList.push({
					department: '',
					adminPerson: '',
					post: ''
				})
			},
			delNode(index) {
				// console.log(this.nodeList)
				// console.log(this.$refs.div[index])
				this.nodeList.splice(index, 1) // 点击调用删除方法，在nodeList数组里删除index这个下标项，逗号后面的1就代表删除当前的1项
			},
			addUnitFunc(){
				this.dialogUnit.show = true
			},
			//处理子组件触发的事件
			handleAddUnit() {
				this.getUnitFunc()
			},
			// 获取分包单位
			getUnitFunc(newValue) {
				var that = this  
				var fd = new FormData();
				// console.log(this.registerBaseData)
				fd.append('flag', 'getUnit');
				var registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				// console.log(registerBaseData)
				fd.append('timeStamp', registerBaseData.timeStamp);
				this.$axios.post(that.$adminUrl+`/project/unit.php`, fd).then((res) => {
					// console.log(res.data)
					if (res.data.code) {
						this.tableData = res.data.data
					} else {
						this.tableData = []
						// this.$message.error('当前网络不稳定,请更换网络后重试!')
					}
				});
			},
			//删除当前行
			deleteRow(index,row) {
				
				this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
				  confirmButtonText: '确定',
				  cancelButtonText: '取消',
				  type: 'warning'
				}).then(() => {
					var fd = new FormData();
					fd.append('flag', 'deleteUnit');
					fd.append('unitId', row.id);
					console.log(row.id)
					this.$axios.post(this.$adminUrl+`/project/unit.php`, fd).then((res) => {
						// console.log(res.data)
						if (res.data.code) {
							this.getUnitFunc()
							this.$message.success('分包单位删除成功！')
						} else {
							this.$message.error('当前网络不稳定,请更换网络后重试!')
						}
					});
				}).catch(() => {
						this.$message({
						type: 'info',
						message: '已取消删除'
					});          
				});
			},
			//导入Excel
			importExcelFunc() {
				this.dialogImportUnit.show = true
			},
			//更新数据
			handleImportUnit() {
				this.getUnitFunc()
			}
		},
		watch: {
		    "registerBaseData.timeStamp": {
				handler(newValue, oldValue) {
					this.getUnitFunc()
				}
		    }
		}
	};
</script>

<style scoped>
	.el-row {
		margin-bottom: 20px;
	
		&:last-child {
			margin-bottom: 0;
		}
	}
	
	.el-col {
		border-radius: 4px;
	}
	
	.bg-purple-dark {
		background: #99a9bf;
	}
	
	.bg-purple {
		background: #d3dce6;
	}
	
	.bg-purple-light {
		background: #e5e9f2;
	}
	
	.grid-content {
		border-radius: 4px;
		min-height: 32px;
		margin: 0.625rem;
	}
	
	.title-text {
		color: #31708f;
		line-height: 32px;
	}
	
	.title-box {
		background-color: #d9edf7;
	}
	
	.info-label {
		color: #31708f;
		line-height: 32px;
		text-align: center;
	}
	
	.info-title {
		color: inherit;
		line-height: 32px;
		text-align: center;
	}
	
	.info-title-box {
		width: 10%;
		background-color: #5bc0de;
		height: 32px;
		line-height: 32px;
		text-align: center;
	}
	
	.row-bg {
		padding: 10px 0;
		background-color: #f9fafc;
	}
</style>
