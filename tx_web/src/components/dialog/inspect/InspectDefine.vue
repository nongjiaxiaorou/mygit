<template>
	<div id="ProTable" class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd'></addBtn>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
			 :data="table" stripe>
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
				<el-table-column prop="inspectLevel" label="巡查层级" align="center"></el-table-column>
				<el-table-column prop="inspectName" label="巡查任务名称" align="center"></el-table-column>
				<el-table-column prop="inspectGroup" label="巡查小组" align="center"></el-table-column>
				<el-table-column prop="isEnable" label="巡查状态" align="center">
					<template slot-scope="scope">{{scope.row.isEnable=='0'? '已关闭':'已启用'}}</template>
				</el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button v-if="scope.row.isEnable=='1'" :class="scope.row.isEnable== '1'? 'red' : ''" type="text" icon="el-icon-star-off"
						 @click="handleCompleted(scope.$index, scope.row)">关闭</el-button>
						<el-button v-if="scope.row.isEnable=='0'" :class="scope.row.isEnable== '0'? 'green' : ''" type="text" icon="el-icon-star-off"
						 @click="handleCompleted(scope.$index, scope.row)">启用</el-button>
						 <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">修改</el-button>
						 <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">定义</el-button>
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
		<EditInsDefine :dialogEdit="dialogEdit" :form="form" :type="type"></EditInsDefine>
		<!-- 新增工程 -->
		<AddInsDefine :dialogAdd="dialogAdd" :form="form" @update="update"></AddInsDefine>
	</div>
</template>
<script>
	import AddInsDefine from "@/components/dialog/inspect/AddInsDefine"
	import EditInsDefine from "@/components/dialog/inspect/EditInsDefine"
	import ImportPro from "../../dialog/project/ImportPro"
	import addBtn from "../../common/add-btn"
	import importBtn from '@/components/common/import-btn';
	export default {
		name: 'basetable',
		components: {
			EditInsDefine,
			ImportPro,
			AddInsDefine,
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
				isLoading:true
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
		mounted() {
			this.getTaskData()
		},
		methods: {
			//获取任务
			getTaskData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getTask')
				
				that.$axios.post(that.$adminUrl + `/inspect/InspectDefine.php`, fd).then(res => {
					// console.log(res.data.data)
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
				this.form = {
					options: this.options,
					categoryList:this.categoryList
				}
			},
			//新增成功刷新页面
			update() {
				this.getProData()
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//修改
			handleEdit(index, row) {
				this.dialogEdit.show = true
				this.form = row
			},
			//定义
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				sessionStorage.setItem('inspectDefineData',JSON.stringify(row))
				this.$router.push({
					name: 'define',
					params:row
				})
			},
			//竣工
			handleCompleted(index, row) {
				let isEnable = 0
				if(row.isEnable==0){
					isEnable = 1
				}
				const that = this
				let fd = new FormData()
				fd.append('flag', 'changeTaskState')
				fd.append('isEnable',isEnable)
				fd.append('timeStamp',row.timeStamp)
				that.$axios.post(that.$adminUrl + `/inspect/InspectDefine.php`, fd).then(res => {
					// console.log(res)
					this.getTaskData()
				}).catch(() => {
				
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
