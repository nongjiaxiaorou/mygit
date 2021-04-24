<template>
	<div id="ProTable" class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd' :buttonTitle="'选择检查内容'"></addBtn>
				<importBtn @handleImportChaild='handleImport'></importBtn>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
			 :data="table" stripe>
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
				<el-table-column prop="inspectQues" label="检查问题" align="center"></el-table-column>
				<el-table-column prop="fullMarks" label="满分值" width="100" align="center"></el-table-column>
				<el-table-column prop="inspectCate" label="检查项大类" align="center"></el-table-column>
				<el-table-column prop="deductTableCate" label="扣分表大类" align="center"></el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-delete" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
				 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
				</el-pagination>
			</div>
		</div>
		<!-- 编辑弹出框 -->
		<!-- <EditPro :dialogEdit="dialogEdit" :form="form" :type="type"></EditPro> -->
		<!-- 批量导入 -->
		<ImportPro :dialogImport="dialogImport" @handleImportProChild='handleImportPro'></ImportPro>
		<!-- 新增工程 -->
		<AddInsContent :dialogAdd="dialogAdd" :inspectDefineData="inspectDefineData" @update="update"></AddInsContent>
	</div>
</template>
<script>
	import AddInsContent from "@/components/dialog/inspect/AddInsContent"
	// import EditPro from "../../dialog/index/EditPro"
	import ImportPro from "../../dialog/project/ImportPro"
	import addBtn from "../../common/add-btn"
	import importBtn from '@/components/common/import-btn';
	export default {
		name: 'basetable',
		components: {
			// EditPro,
			ImportPro,
			AddInsContent,
			addBtn,
			importBtn
		},
		data() {
			return {
				loading: false,
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				search_world: '',
				dialogEdit: {
					show: false
				},
				dialogImport: {
					show: false
				},
				dialogAdd: {
					show: false
				},
				tableData: [],
				multipleSelection: [],
				form: {},
				options: [],
				categoryList:[],
				isLoading:true,
				inspectDefineData:{}
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				if (search_world) {
					var filterData =  this.tableData.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search_world) > -1
						})
					})
					this.total = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total = this.tableData.length
				return this.tableData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('inspectDefineData')
			this.inspectDefineData = JSON.parse(sessionData)
			this.getDefineData()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('inspectDefineData')
				this.inspectDefineData = JSON.parse(sessionData)
				this.getDefineData()
			},
			//获取检查项目定义
			getDefineData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getDefineData')
				fd.append('taskId', that.inspectDefineData.taskId)
				// console.log(this.$loading())
				that.tableData = []
				that.$axios.post(that.$adminUrl + `/inspect/InsDefineDetail.php`, fd).then(res => {
					// console.log(res)
					that.tableData = res.data.data
					if(that.isLoading){
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
			//新增
			handleAdd() {
				this.dialogAdd.show = true
			},
			//新增成功刷新页面
			update() {
				this.getDefineData()
			},
			//导入
			handleImport() {
				this.dialogImport.show = true;
			},
			//导入成功后触发刷新视图
			handleImportPro(){
				this.getProData()
			},
			// 触发搜索按钮
			handleSearch() {
				this.$set(this.query, 'pageIndex', 1);
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//删除方法
			deleteFun(id){
				const that = this
				let fd = new FormData()
				fd.append('flag', 'deleteInsItem')
				fd.append('id', id)
				that.$axios.post(that.$adminUrl + `/inspect/InsDefineDetail.php`, fd).then(res => {
					// console.log(res)
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//删除
			handleDelete(index, row) {
				if(this.multipleSelection.length>0){//批量选择删除
					this.$confirm('此操作将批量删除选中检查问题, 是否继续?', '提示', {
						confirmButtonText: '确定',
						cancelButtonText: '取消',
						type: 'warning'
					}).then(() => {
						for(let i=0;i<this.multipleSelection.length;i++){
							this.deleteFun(this.multipleSelection[i].id)
						}
						setTimeout(() => {
							this.getDefineData()
							this.$message({type: 'success',message: '删除成功!'})
						},500)
					}).catch(() => {
						this.$message({type: 'info',message: '已取消删除'})
					});
				}else{//单个删除
					this.$confirm('此操作将删除该检查问题, 是否继续?', '提示', {
						confirmButtonText: '确定',
						cancelButtonText: '取消',
						type: 'warning'
					}).then(() => {
						this.deleteFun(row.id)
						setTimeout(() => {
							this.getDefineData()
							this.$message({type: 'success',message: '删除成功!'})
						},500)
					}).catch(() => {
						this.$message({type: 'info',message: '已取消删除'})
					});
				}
				
			},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
		},
	};
</script>
<style scoped>
	.handle-box {
		margin-bottom: 20px;
	}

	.handle-select {
		width: 120px;
	}

	.handle-input {
		width: 300px;
		display: inline-block;
	}

	.table {
		width: 100%;
		font-size: 14px;
	}

	.red {
		color: #ff0000;
	}

	.green {
		color: #67c23a;
	}

	.mr10 {
		margin-right: 10px;
	}

	.table-td-thumb {
		display: block;
		margin: auto;
		width: 40px;
		height: 40px;
	}
</style>
