<template>
	<div id="ProTable" class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
			 :data="table" stripe>
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
				<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
				<el-table-column prop="groupName" label="检查小组" align="center"></el-table-column>
				<el-table-column prop="issueTime" label="下发日期" align="center"></el-table-column>
				<el-table-column prop="correctMarks" label="检查得分" align="center"></el-table-column>
				<el-table-column prop="rank" label="检查排名" align="center"></el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-delete" @click="handleJump(scope.$index, scope.row)">详情查看</el-button>
					</template>
				</el-table-column>
			</el-table>
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
				 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
				</el-pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'basetable',
		components: {
			
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
				tableData: [],
				multipleSelection: [],
				form: {},
				options: [],
				categoryList:[],
				isLoading:true
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				if (search_world) {
					let filterData =  this.tableData.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search_world) > -1
						})
					})
					// console.log(filterData);
					this.total = filterData.length;
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total = this.tableData.length
				return this.tableData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
		},
		mounted() {
			this.getInsTask()
		},
		methods: {
			//获取工程类别
			getInsTask() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getInsTask')
			
				that.$axios.post(that.$adminUrl + `/inspect/InspectAdmin.php`, fd).then(res => {
					console.log(res)
					this.tableData = res.data.data
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//新增成功刷新页面
			update() {
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
			//详情
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				console.log(row)
				sessionStorage.setItem('inspectAdminData',JSON.stringify(row))
				this.$router.push({
					name: 'information',
					params:row
				})
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
