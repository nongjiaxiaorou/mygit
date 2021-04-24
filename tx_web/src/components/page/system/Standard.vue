<template>
	<div class="basic" style="flex:1;display:flex=1" id="projectManage">
		<div class="container">
			<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
				<el-tab-pane label="建筑工程" name="first">
					<div class="handle-box">
						<addBtn @handleAddChild='handleAdd'></addBtn>
						<batchDelBtn @handleDelChild='delAllSelection'></batchDelBtn>
						<template>
							<el-select v-model="selectValue1" placeholder="请选择" class="custom-select">
								<el-option v-for="(item,index) in options" :key="item.value" :label="item.label" :value="item.value">
								</el-option>
							</el-select>
						</template>
					</div>
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables1">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column prop="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column type="projectType" prop="projectType" label="工程类别" align="center" width="80"></el-table-column>
						<el-table-column prop="inspectionItem" label="检查项目" align="center" width="80%"></el-table-column>
						<el-table-column prop="inspectionContent" label="检查内容" align="center"></el-table-column>
						<el-table-column prop="number" label="编号" align="center"></el-table-column>
						<el-table-column prop="miniStandardValue" label="最小标准值" align="center"></el-table-column>
						<el-table-column prop="maxStandardValue" label="最大标准值" align="center"></el-table-column>
						<el-table-column prop="measurementType" label="实测类型" align="center"></el-table-column>
						<el-table-column label="操作" width="180" align="center">
							<template slot="header" slot-scope="scope">
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
								<el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="total1">
						</el-pagination>
					</div>
				</el-tab-pane>
				<el-tab-pane label="装修工程" name="third">
					<div class="handle-box">
						<addBtn @handleAddChild='handleAdd'></addBtn>
						<batchDelBtn @handleDelChild='delAllSelection'></batchDelBtn>
						<template>
							<el-select v-model="selectValue1" placeholder="请选择" class="custom-select">
								<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
								</el-option>
							</el-select>
						</template>
					</div>
					<el-table  :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables2">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column prop="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column type="projectType" prop="projectType" label="工程类别" align="center" width="80"></el-table-column>
						<el-table-column prop="inspectionItem" label="检查项目" align="center" width="80%"></el-table-column>
						<el-table-column prop="inspectionContent" label="检查内容" align="center"></el-table-column>
						<el-table-column prop="number" label="编号" align="center"></el-table-column>
						<el-table-column prop="miniStandardValue" label="最小标准值" align="center"></el-table-column>
						<el-table-column prop="maxStandardValue" label="最大标准值" align="center"></el-table-column>
						<el-table-column prop="measurementType" label="实测类型" align="center"></el-table-column>
						<el-table-column prop="authority" label="操作" width="180" align="center">
							<template slot="header" slot-scope="scope">
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
								<el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="total2">
						</el-pagination>
					</div>
				</el-tab-pane>
			</el-tabs>


		</div>
		<!-- 编辑弹出框 -->
		<EditStandardItem :dialogEdit="dialogEdit"  :selectValue1="selectValue1" :form="form" @handleUpdateInspectionItemChild="handleAddInspectionItem" :type="type"></EditStandardItem>
		<!-- 批量导入 -->
		<!-- <Import :dialogImport="dialogImport" :form="form" @updateImp="initWebSocket"></Import> -->
		<!-- 新增标准条目 -->
		<AddStandardItem :dialogAdd="dialogAdd" :selectValue1="selectValue1" @handleAddInspectionItemChild="handleAddInspectionItem"></AddStandardItem>
		<!-- 赋予权限 -->
		<!-- <addAuthority :authorityDialog="authorityDialog" :form="form" @handleAddAuthorityChild="handleAddAuthority"></addAuthority> -->
	</div>
</template>
<script>
	import AddStandardItem from "../../dialog/system/AddStandardItem"
	import EditStandardItem from "../../dialog/system/EditStandardItem"
	// import Import from "../../common/Import"
	import addBtn from "../../common/add-btn"
	import batchDelBtn from "../../common/batchDel-btn"
	// import addAuthority from "../../dialog/system/AddAuthority"
	export default {
		name: 'basetable',
		components: {
			// EditUser,
			// Import,
			AddStandardItem,
			addBtn,
			batchDelBtn,
			EditStandardItem
		},
		data() {
			return {
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //    每页的数据
				total1:0,
				total2:0,
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				dialogEdit: {
					show: false
				},
				dialogImport: {
					show: false
				},
				dialogAdd: {
					show: false
				},
				authorityDialog: {
					show: false
				},
				activeName: 'first',
				search: '',
				form: {
					department: '',
					username: '',
					phone: '',
					email: '',
					account: '',
					password: '',
					authority: ''
				},
				tableData1: [],
				tableData2: [],
				isShow: false,
				options: [{
					value: 'tb_system_standard1',
					label: '国家标准'
				}, {
					value: 'tb_system_standard2',
					label: '实测标准2'
				}, {
					value: 'tb_system_standard3',
					label: '实测标准3'
				}, {
					value: 'tb_system_standard4',
					label: '实测标准4'
				}, {
					value: 'tb_system_standard5',
					label: '实测标准5'
				}],
				//默认选中第一项
				selectValue1: 'tb_system_standard1'
				// loadingCompany: true,
				// loadingMidCompany: true,
				// loadingUserDefined: true
			};
		},
		created() {
			// this.initWebSocket();
		},
		mounted() {
			this.getArchitecture();
			this.getDecoration();
		},
		destroyed() {
			// this.websock.close() //离开路由之后断开websocket连接
		},
		computed: {
			newName() {
				return this.query.name;
			},
			// 模糊搜索
			tables1() {
				const search = this.search
				if (search) {
					var filterData = this.tableData1.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
					this.total1 = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total1 = this.tableData1.length
				return this.tableData1.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
			tables2() {
				const search = this.search
				if (search) {
					var filterData = this.tableData2.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
					this.total2 = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total2 = this.tableData2.length
				return this.tableData2.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
		},
		watch: {
			newName(val) {
				if (val == '') {
					this.searchKey = 'show';
				}
			}
		},
		methods: {
			//新增
			handleAdd() {
				// console.log(this.dialogAdd)
				this.dialogAdd.show = true;
			},
			handleAddInspectionItem() { //调取最新的检查项目
				this.getArchitecture();
				this.getDecoration();
			},
			//导入
			handleImport() {
				this.dialogImport = true;
				this.form = {
					dialogFormUpload: this.dialogImport
				}
			},
			// 触发搜索按钮
			handleSearch() {
				this.$set(this.query, 'pageIndex', 1);
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//搜索
			searchUserinfo() {
				//搜索
				this.$set(this.query, 'pageIndex', 1);
				// console.log(this.query.name)
				if (this.query.name != '') {
					this.searchKey = 'searchName';
				}
			},
			//编辑
			handleEdit(index, row) {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'edit_info')
				fd.append('id', row.id)
				// fd.append('account',localStorage.getItem('ms_username'))
				// this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					// if(res.data.success=='error'){
					if (false) {
						this.$message.error('你无权操作！！！')
					} else {
						this.dialogEdit.show = true;
						this.form = {
							id: row.id,
							projectType: row.projectType,
							inspectionItem: row.inspectionItem,
							inspectionContent: row.inspectionContent,
							number: row.number,
							miniStandardValue: row.miniStandardValue,
							maxStandardValue: row.maxStandardValue,
							measurementType: row.measurementType
						}
						console.log(this.form)
					}
				//})
			},
			//删除
			handleDelete(index, row) {
				// console.log(row)
				let arr = new Array();
				arr[0] = row.id
				console.log(arr[0])
				this.commentDelete(arr)
			},
			//批量删除
			delAllSelection() {
				const length = this.multipleSelection.length;
				let arr = new Array();
				this.flag = "delete_all"
				for (let i = 0; i < length; i++) {
					arr[i] = this.multipleSelection[i].id;
				}
				// console.log(arr)
				this.commentDelete(arr)
				this.multipleSelection = [];
			},
			//公共删除函数
			commentDelete(row_id) {
				// console.log(row_id)
				var row_id = JSON.stringify(row_id);
				var that = this
				this.$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning',
					center: true
				}).then(() => {
					var fd = new FormData()
					fd.append('flag', 'deleteItem')
					fd.append('tableType', this.selectValue1)
					fd.append('id', row_id)
					// fd.append('account',localStorage.getItem('ms_username'))
					this.$axios.post(that.$adminUrl + `/system/measureStandard.php`, fd).then(res => {
						console.log(res.data)
						if (res.data.code) {
							setTimeout(() => {
								this.$message.success('删除成功！');
								this.handleAddInspectionItem();
							}, 500);
						} else {
							this.$message.error('当前网络不稳定,请更换网络后重试!')
						}
					})
				}).catch(() => {});
			},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
			handleClick(tab, event) { //表格tab切换触发的操作函数
				// console.log(tab, event);
			},
			//获取建筑工程数据
			getArchitecture() {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'getArchitectureData')
				fd.append('tableType', this.selectValue1)
				// console.log(that.$adminUrl+`/system/User_manage.php`)
				this.$axios.post(that.$adminUrl + `/system/measureStandard.php`, fd).then(res => {
					console.log(res.data)
					if (res.data.code) {
						this.total1 = res.data.data.length //设置动态total，以免搜索框变化时不能更新数据长度
						this.tableData1 = res.data.data
					}
				})
			},
			//获取装修工程数据
			getDecoration() {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'getDecorationData')
				fd.append('tableType', this.selectValue1)
				// console.log(that.$adminUrl+`/system/User_manage.php`)
				const loading = this.$loading({ // 声明一个loading对象
					lock: true, // 是否锁屏
					text: '正在加载...', // 加载动画的文字
					spinner: 'el-icon-loading', // 引入的loading图标
					// background: 'rgba(0, 0, 0, 0.3)',       // 背景颜色
					target: document.querySelector('#projectManage'), // 需要遮罩的区域
				})
				loading.close();
				this.$axios.post(that.$adminUrl + `/system/measureStandard.php`, fd).then(res => {
					// console.log(res.data)
					if (res.data.code) {
						this.total2 = res.data.data.length //设置动态total，以免搜索框变化时不能更新数据长度
						this.tableData2 = res.data.data
					}
				})
			}
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

	.mr10 {
		margin-right: 10px;
	}

	.table-td-thumb {
		display: block;
		margin: auto;
		width: 40px;
		height: 40px;
	}

	.green {
		color: #67c23a;
	}

	.custom-select {
		margin-left: 10px;
	}
</style>
