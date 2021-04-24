<template>
	<div id="ProTable" class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd'></addBtn>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header"
			 @selection-change="handleSelectionChange" :data="table" stripe>
				<el-table-column type="expand">
					<template slot-scope="props">
						<el-form label-position="left" inline class="demo-table-expand">
							<el-form-item label="组长：">
								<span>{{ props.row.groupLeader.substring(9) }}</span>
							</el-form-item>
							<el-form-item label="副组长：">
								<span>{{ props.row.groupDeputy.substring(9) }}</span>
							</el-form-item>
							<el-form-item label="组员：">
								<span>{{ props.row.groupMember.substring(9) }}</span>
							</el-form-item>
						</el-form>
					</template>
				</el-table-column>
				<el-table-column label="小组名称" prop="groupName" align="center">
				</el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">修改</el-button>
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
		<EditInsGroup :dialogEdit="dialogEdit" :form="form" :type="type"></EditInsGroup>
		<!-- 新增工程 -->
		<AddInsGroup :dialogAdd="dialogAdd" :form="form" @update="update"></AddInsGroup>
	</div>
</template>
<script>
	import AddInsGroup from "@/components/dialog/inspect/AddInsGroup"
	import EditInsGroup from "@/components/dialog/inspect/EditInsGroup"
	import addBtn from "../../common/add-btn"
	export default {
		name: 'basetable',
		components: {
			EditInsGroup,
			AddInsGroup,
			addBtn,
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
				dialogAdd: {
					show: false
				},
				tableData: [],
				multipleSelection: [],
				form: {},
				options: [],
				categoryList: [],
				isLoading: true
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				if (search_world) {
					var filterData = this.tableData.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search_world) > -1
						})
					})
					this.total = filterData.length
					return filterData.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
				}
				this.total = this.tableData.length
				return this.tableData.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
			},
		},
		mounted() {
			this.getGroupData()
		},
		methods: {
			//获取小组
			getGroupData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getGroupData')
				// console.log(this.$loading())
				that.$axios.post(that.$adminUrl + `/inspect/InspectGroup.php`, fd).then(res => {
					console.log(res.data.data)
					let oldArr = res.data.data
					let newArr = that.commonFunc.Es5duplicate(oldArr,'groupName')
					that.tableData = that.commonFunc.Es5duplicate(oldArr,'groupName')
					for(let i=0;i<newArr.length;i++){
						for(let j=0;j<oldArr.length;j++){
							if(newArr[i].groupID == oldArr[j].groupID){
								if(oldArr[j].groupPost == 2){
									that.tableData[i]['groupLeader'] += oldArr[j].userName+'/'+oldArr[j].account+'||'
								}else if(oldArr[j].groupPost == 1){
									that.tableData[i]['groupDeputy'] += oldArr[j].userName+'/'+oldArr[j].account+'||'
								}else{
									that.tableData[i]['groupMember'] += oldArr[j].userName+'/'+oldArr[j].account+'||'
								}
							}
						}
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
			//新增
			handleAdd() {
				this.dialogAdd.show = true
			},
			//新增成功刷新页面
			update() {
				
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//修改
			handleEdit(index, row) {
				this.dialogEdit.show = true
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

	.demo-table-expand {
		font-size: 0;
	}

	.demo-table-expand label {
		width: 90px;
		color: #99a9bf;
	}

	.demo-table-expand .el-form-item {
		margin-right: 0;
		margin-bottom: 0;
		width: 50%;
	}
</style>
