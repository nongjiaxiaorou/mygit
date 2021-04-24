<template>
	<div class="basic" style="flex:1;display:flex=1">
		<div class="container" id="userManage">
			<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
				<!-- 总公司特权账号 -->
				<el-tab-pane label="总公司特权账号" name="first">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables1">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column type="department" prop="department" label="公司" align="center" width="80"></el-table-column>
						<el-table-column prop="username" label="姓名" align="center" width="80%"></el-table-column>
						<el-table-column prop="phone" label="手机" align="center"></el-table-column>
						<el-table-column prop="e_mail" label="邮箱" align="center"></el-table-column>
						<el-table-column prop="account" label="账号" align="center"></el-table-column>
						<el-table-column prop="password" label="密码" align="center"></el-table-column>
						<el-table-column prop="state" label="状态" align="center"></el-table-column>
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
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 分公司特权账号 -->
				<el-tab-pane label="分公司特权账号" name="second">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables2">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column type="department" prop="department" label="公司" align="center" width="80"></el-table-column>
						<el-table-column prop="username" label="姓名" align="center" width="80%"></el-table-column>
						<el-table-column prop="phone" label="手机" align="center"></el-table-column>
						<el-table-column prop="e_mail" label="邮箱" align="center"></el-table-column>
						<el-table-column prop="account" label="账号" align="center"></el-table-column>
						<el-table-column prop="password" label="密码" align="center"></el-table-column>
						<el-table-column prop="state" label="状态" align="center"></el-table-column>
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
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 自定义账号 -->
				<el-tab-pane label="自定义账号" name="third">
					<div class="handle-box">
						<addBtn @handleAddChild='handleAdd'></addBtn>
						<el-button type="primary" title="导入学生信息" size icon="el-icon-folder-add" @click="handleImport()">批量导入</el-button>
						<el-button type="danger" icon="el-icon-delete" size class="handle-del" @click="delAllSelection">批量删除</el-button>
						<el-button type="primary" icon="el-icon-search" size @click="searchUserinfo()">搜索</el-button>
					</div>
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables3">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column type="department" prop="department" label="公司" align="center" width="80"></el-table-column>
						<el-table-column prop="username" label="姓名" align="center" width="80%"></el-table-column>
						<el-table-column prop="phone" label="手机" align="center"></el-table-column>
						<el-table-column prop="e_mail" label="邮箱" align="center"></el-table-column>
						<el-table-column prop="account" label="账号" align="center"></el-table-column>
						<el-table-column prop="password" label="密码" align="center"></el-table-column>
						<el-table-column prop="state" label="状态" align="center"></el-table-column>
						<el-table-column prop="authority" label="操作" width="180" align="center">
							<template slot="header" slot-scope="scope">
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
								<el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
								<el-button type="text" class="green" icon="el-icon-edit" @click="handleAuthority(scope.$index, scope.row)"
								 v-show="isShow">权限</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData3.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 账号绑定工程 -->
				<el-tab-pane label="账号绑定工程" name="fourth">
					<div class="handle-box">
						<el-input placeholder="请输入账号" v-model="search_world" style="width: 20%;"></el-input>
						<el-button type="primary" icon="el-icon-search" size @click="searchUserPro()">搜索</el-button>
					</div>
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables4">
						<el-table-column type="selection"></el-table-column>
						<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="categories" label="项目类别" align="center"></el-table-column>
						<el-table-column prop="proState" label="工程状态" align="center"></el-table-column>
						<el-table-column prop="company" label="所属公司" align="center"></el-table-column>
						<el-table-column label="操作" align="center">
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData4.length">
						</el-pagination>
					</div>
				</el-tab-pane>
			</el-tabs>
		</div>
		<!-- 编辑弹出框 -->
		<EditUser :dialogEdit="dialogEdit" :form="form" @handleUpdateUserChild="handleUpdateUser" :type="type"></EditUser>
		<!-- 批量导入 -->
		<!-- <Import :dialogImport="dialogImport" :form="form" @updateImp="initWebSocket"></Import> -->
		<!-- 新增用户 -->
		<AddUser :dialogAdd="dialogAdd" @handleAddUserChild="handleAddUser"></AddUser>
		<!-- 赋予权限 -->
		<addAuthority :authorityDialog="authorityDialog" :form="form" @handleAddAuthorityChild="handleAddAuthority"></addAuthority>
	</div>
</template>
<script>
	import AddUser from "../../dialog/system/AddUser"
	import EditUser from "../../dialog/system/EditUser"
	// import Import from "../../common/Import"
	import addBtn from "../../common/add-btn"
	import addAuthority from "../../dialog/system/AddAuthority"
	export default {
		name: 'basetable',
		components: {
			// EditUser,
			// Import,
			AddUser,
			addBtn,
			EditUser,
			addAuthority
		},
		data() {
			return {
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //    每页的数据
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
				search_world: '',
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
				tableData3: [],
				tableData4: [],
				isShow: false
			};
		},
		mounted() {
			this.companyFunc();
			this.midcompanyFunc();
			this.userDefinedFunc();
			this.authorityFunc(); //权限限制函数
		},
		computed: {
			newName() {
				return this.query.name;
			},
			// 模糊搜索
			tables1() {
				const search = this.search
				if (search) {
					return this.tableData1.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData1
			},
			tables2() {
				const search = this.search
				if (search) {
					return this.tableData2.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData2
			},
			tables3() {
				const search = this.search
				if (search) {
					// filter() 方法创建一个新的数组，新数组中的元素是通过检查指定数组中符合条件的所有元素。
					// 注意： filter() 不会对空数组进行检测。
					// 注意： filter() 不会改变原始数组。
					return this.tableData3.filter(data => {
						// some() 方法用于检测数组中的元素是否满足指定条件;
						// some() 方法会依次执行数组的每个元素：
						// 如果有一个元素满足条件，则表达式返回true , 剩余的元素不会再执行检测;
						// 如果没有满足条件的元素，则返回false。
						// 注意： some() 不会对空数组进行检测。
						// 注意： some() 不会改变原始数组。
						return Object.keys(data).some(key => {
							// indexOf() 返回某个指定的字符在某个字符串中首次出现的位置，如果没有找到就返回-1；
							// 该方法对大小写敏感！所以之前需要toLowerCase()方法将所有查询到内容变为小写。
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData3
			},
			tables4() {
				this.total = this.tableData4.length
				return this.tableData4.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
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
			handleAddUser() { //调取最新的自定义账号
				this.userDefinedFunc();
			},
			handleAddAuthority() {
				// console.log('成功')
				this.userDefinedFunc();
			},
			handleUpdateUser() { //调取最新的账号
				this.companyFunc();
				this.midcompanyFunc();
				this.userDefinedFunc();
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
				console.log(this.query.name)
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
				this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					// if(res.data.success=='error'){
					if (false) {
						this.$message.error('你无权操作！！！')
					} else {
						this.dialogEdit.show = true;
						this.form = {
							id: row.id,
							department: row.department,
							username: row.username,
							phone: row.phone,
							email: row.e_mail,
							account: row.account,
							password: row.password,
							authority: row.authority
						}
					}
				})
			},
			//删除
			handleDelete(index, row) {
				// console.log(row)
				let arr = new Array();
				arr[0] = row.id
				this.commentDelete(arr)
			},
			//授予权限
			handleAuthority(index, row) {
				// console.log(row.authority)
				this.form = {
					id: row.id,
					department: row.department,
					username: row.username,
					phone: row.phone,
					email: row.e_mail,
					account: row.account,
					password: row.password,
					authority: row.authority
				}
				// console.log(this.form)
				this.authorityDialog.show = true
			},
			//批量删除
			delAllSelection() {
				const length = this.multipleSelection.length;
				let arr = new Array();
				this.flag = "delete_all"
				for (let i = 0; i < length; i++) {
					arr[i] = this.multipleSelection[i].id;
				}
				// console.log(this.multipleSelection.id)
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
					fd.append('flag', 'deleteAccount')
					fd.append('id', row_id)
					// fd.append('account',localStorage.getItem('ms_username'))
					this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
						console.log(res.data)
						if (res.data.code) {
							setTimeout(() => {
								this.$message.success('删除成功！');
								this.handleUpdateUser();
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
			companyFunc() {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'CompanyAccount')
				console.log(that.$adminUrl+`/system/User_manage.php`)
				this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					console.log(res.data)
					if (res.data.code) {
						this.tableData1 = res.data.data
					}
				})
			},
			midcompanyFunc() {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'midCompanyAccount')
				// console.log(that.$adminUrl+`/system/User_manage.php`)
				this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					// console.log(res.data)
					if (res.data.code) {
						this.tableData2 = res.data.data
					}
				})
			},
			userDefinedFunc() {
				var that = this
				var fd = new FormData()
				fd.append('flag', 'userDefinedAccount')
				const loading = this.$loading({ // 声明一个loading对象
					lock: true, // 是否锁屏
					text: '正在加载...', // 加载动画的文字
					spinner: 'el-icon-loading', // 引入的loading图标
					// background: 'rgba(0, 0, 0, 0.3)',// 背景颜色
					target: document.querySelector('#userManage'), // 需要遮罩的区域
				})
				loading.close()
				this.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					// console.log(res.data)
					if (res.data.code) {
						this.tableData3 = res.data.data
					}
				})
			},
			authorityFunc() {
				// console.log(localStorage.getItem('ms_username'))
				var username = localStorage.getItem('ms_username');
				if (username === 'admin') {
					this.isShow = 'true'
				}
			},
			//详情
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				sessionStorage.setItem('registerBaseData', JSON.stringify(row))
				this.$router.push({
					name: 'prodetail',
					params: row
				})
			},
			//搜索账号绑定工程
			searchUserPro() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'searchPro')
				fd.append('account', that.search_world)
				that.$axios.post(that.$adminUrl + `/system/User_manage.php`, fd).then(res => {
					// console.log(res.data.data.length)
					let arr = res.data.data.data
					const loading = this.$loading({ // 声明一个loading对象
						lock: true, // 是否锁屏
						text: '正在加载...', // 加载动画的文字
						spinner: 'el-icon-loading', // 引入的loading图标
						// background: 'rgba(0, 0, 0, 0.3)',// 背景颜色
						target: document.querySelector('#userManage'), // 需要遮罩的区域
					})
					loading.close()
					if(res.data.data.length===0){
						this.tableData4 = []
					}else{
						this.tableData4 = this.commonFunc.Es5duplicate(arr, "projectName")
					}
				})
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
</style>
