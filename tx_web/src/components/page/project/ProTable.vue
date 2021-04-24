<template>
	<div id="ProTable" class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd'></addBtn>
				<importBtn @handleImportChaild='handleImport'></importBtn>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-form>
				<el-form-item label="集团公司">
					<el-cascader ref="myCascader" :options="options" v-model="form.options" @change="changeCompany"></el-cascader>
				</el-form-item>
			</el-form>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
			 :data="table" stripe>
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
				<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
				<el-table-column prop="categories" label="项目类别" align="center"></el-table-column>
				<el-table-column prop="proState" label="工程状态" align="center"></el-table-column>
				<el-table-column prop="company" label="所属公司" align="center"></el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
						<!-- <router-link to="/Engineering">详情</router-link> -->
						<el-button v-if="scope.row.isCompleted=='0'" :class="scope.row.isCompleted== '0'? 'red' : ''" type="text" icon="el-icon-star-off"
						 @click="handleCompleted(scope.$index, scope.row)">竣工</el-button>
						<el-button v-if="scope.row.isCompleted=='1'" :class="scope.row.isCompleted== '1'? 'green' : ''" type="text" icon="el-icon-star-off"
						 @click="handleCompleted(scope.$index, scope.row)">已竣工</el-button>
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
		<AddPro :dialogAdd="dialogAdd" :form="form" @update="update"></AddPro>
	</div>
</template>
<script>
	import AddPro from "@/components/dialog/project/AddPro"
	// import EditPro from "../../dialog/index/EditPro"
	import ImportPro from "../../dialog/project/ImportPro"
	import addBtn from "../../common/add-btn"
	import importBtn from '@/components/common/import-btn';
	export default {
		name: 'basetable',
		components: {
			// EditPro,
			ImportPro,
			AddPro,
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
			this.getProData()
			this.getCompany()
			this.getCategories()
		},
		methods: {
			//获取工程类别
			getCategories() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getCategory')
				// console.log(this.$loading())
			
				that.$axios.post(that.$adminUrl + `/system/Category.php`, fd).then(res => {
					// console.log(res.data.data.length)
					for(let i=0;i<res.data.data.length;i++){
						console.log(i)
						that.categoryList.push({
							value:res.data.data[i].category,
							label:res.data.data[i].category,
							categories:res.data.data[i].category
						})
					}
					console.log(that.categoryList)
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//获取集团公司
			getCompany() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getCompany')
				that.$axios.post(that.$adminUrl + `/system/Company.php`, fd).then(res => {
					console.log(res.data.data)
					for (let i = 0; i < res.data.data.length; i++) {
						that.options.push({
							value: res.data.data[i].companyName,
							label: res.data.data[i].companyName,
							companyId: res.data.data[i].companyId
						})
					}
				}).catch({

				})
			},
			//获取项目
			getProData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getProject')
				// console.log(this.$loading())
				
				that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then(res => {
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
			//获取下拉框集团公司筛选出相关的工程
			changeCompany(item) {
				// console.log(item[0]) //公司名称
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getFilterProject')
				fd.append('company', item[0])
				// console.log(this.$loading())
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#ProTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then(res => {
					console.log(res)
					if(res.data.data.length=='0'){
						that.tableData = []
					}else{
						that.tableData = res.data.data
					}
					loading.close();
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
				console.log(form.options);
			},
			//新增成功刷新页面
			update() {
				this.getProData()
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
			//详情
			handleJump(index, row) {
				// console.log(index);
				// console.log(row);
				//每次进入存储session值，以防止页面刷新数据丢失
				sessionStorage.setItem('registerBaseData',JSON.stringify(row))
				this.$router.push({
					name: 'prodetail',
					params:row
				})
			},
			//竣工
			handleCompleted(index, row) {
				// console.log(row.timeStamp)
				const that = this
				let fd = new FormData()
				fd.append('flag', 'editProject')
				fd.append('timeStamp', row.timeStamp)

				that.$confirm('竣工之后将无法取消, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					that.$axios.post(that.$adminUrl + `/project/index.php`, fd).then(res => {
						// console.log(res)
						that.$message({
							type: 'success',
							message: '竣工成功!'
						});
						that.getProData()
					}).catch(() => {

					})
				}).catch(() => {
					that.$message({
						type: 'info',
						message: '已取消竣工'
					});
				});
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
