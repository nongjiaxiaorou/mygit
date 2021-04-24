<template>
	<div class="basic container">
		<div class="" style="flex:1;" id="Category">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd'></addBtn>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
			 :data="table" stripe>
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="100" align="center"></el-table-column>
				<el-table-column prop="category" label="工程类别" align="center"></el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-edit" @click="handleChange(scope.$index, scope.row)">修改</el-button>
						<el-button type="text" icon="el-icon-delete" class="red" @click="handleDel(scope.$index, scope.row)">删除</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
				 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
				</el-pagination>
			</div>
		</div>
		<!-- 新增 -->
		<AddCategory :dialogAdd="dialogAdd" :form="form" @update="update"></AddCategory>
		<!-- 修改 -->
		<ChangeCategory :dialogChange='dialogChange' :form='form' @update='update'></ChangeCategory>
	</div>
</template>
<script>
	import AddCategory from "@/components/dialog/system/AddCategory"
	import ChangeCategory from "@/components/dialog/system/ChangeCategory"
	import addBtn from "../../common/add-btn"
	export default {
		name: 'basetable',
		components: {
			addBtn,
			AddCategory,
			ChangeCategory
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
				dialogAdd: {
					show: false
				},
				dialogChange: {
					show: false
				},
				tableData: [],
				multipleSelection: [],
				form: {},
				options: [],
				isLoading: true
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				if (search_world) {
					return this.tableData.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search_world) > -1
						})
					})
				}
				return this.tableData
			},
		},
		mounted() {
			this.getTableData()
		},
		methods: {
			//获取工程类别
			getTableData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getCategory')
				// console.log(this.$loading())

				that.$axios.post(that.$adminUrl + `/system/Category.php`, fd).then(res => {
					console.log(res)
					that.tableData = res.data.data
					const loading = that.$loading({
						lock: true,
						text: 'Loading',
						target: document.querySelector('#Category'),
						spinner: 'el-icon-loading'
					});
					loading.close();

				}).catch(() => {
					console.error("获取失败")
				})
			},
			//获取下拉框集团公司筛选出相关的工程
			changeCompany(item) {},
			//新增
			handleAdd() {
				this.dialogAdd.show = true
				this.form = {
					options: this.options
				}
			},
			//新增成功刷新页面
			update() {
				this.getTableData()
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//详情
			handleJump(index, row) {},
			//竣工
			handleCompleted(index, row) {},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
			//修改
			handleChange(index,row){
				this.dialogChange.show = true
				this.form = row
			},
			//删除
			handleDel(index, row) {
				console.log(row.id)
				const that = this
				let fd = new FormData()
				fd.append('flag', 'delCategory')
				fd.append('id', row.id)
				
				this.$confirm('此操作将永久删除该工程类别, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					that.$axios.post(that.$adminUrl + `/system/Category.php`, fd).then(res => {
						// console.log(res)
						this.getTableData()
						this.$message({type: 'success',message: '删除成功!'});
					}).catch({
					
					})
				}).catch(() => {
					this.$message({type: 'info',message: '已取消删除'});
				});
			}
		},

	}
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
