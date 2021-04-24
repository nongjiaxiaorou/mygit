<template>
	<div class="basic container">
		<div class="" style="flex:1;">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd'></addBtn>
				<importBtn @handleImportChaild='handleImport'></importBtn>
				<el-button type="danger" icon="el-icon-delete" size class="handle-del" @click="delAllSelection">批量删除
				</el-button>
				<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;" class="px-1"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" stripe @selection-change="handleSelectionChange"
			 :data="searchUserinfo(keyWorld)">
				<el-table-column type="selection"></el-table-column>
				<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
				<el-table-column prop="companyName" label="公司名称" align="center"></el-table-column>
				<el-table-column prop="companyRank" label="公司层级" align="center"></el-table-column>
				<el-table-column prop="createTime" label="录入时间" align="center"></el-table-column>
				<el-table-column label="操作" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-edit" @click="handleChange(scope.$index, scope.row)">修改</el-button>
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
		<Import :dialogImport="dialogImport" :form="form"></Import>
		<!-- 新增工程 -->
		<AddCompany :dialogAdd="dialogAdd" @update="update" :form="form"></AddCompany>
		<!-- 修改 -->
		<ChangeCompany :dialogChange="dialogChange"  @update="update" :form="form"></ChangeCompany>
	</div>
</template>
<script>
	import AddCompany from "../../dialog/system/AddCompany"
	import ChangeCompany from "@/components/dialog/system/ChangeCompany"
	import Import from "../../dialog/system/ImportCompany"
	import addBtn from "../../common/add-btn"
	import importBtn from '@/components/common/import-btn';
	export default {
		name: 'basetable',
		components: {
			Import,
			AddCompany,
			ChangeCompany,
			addBtn,
			importBtn
		},
		data() {
			return {
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				query: {
					address: '',
					name: '',
				},
				keyWorld: '',
				search_world: '',
				loadding:'',
				framework:'s',//架构等级
				dialogChange: {
					show: false
				},
				dialogImport: {
					show: false
				},
				dialogAdd: {
					show: false
				},
				searchKey: '',
				tableData: [],
				multipleSelection: [],
				form: {},
			};
		},
		watch: {
			search_world: function(val) {
				return this.keyWorld = val
			}
		},
		created() {
			this.getCompany()
		},
		mounted() {
			
		},
		methods: {
			//获取集团公司
			getCompany() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getCompany')
				this.$axios.post(this.$adminUrl+`/system/Company.php`, fd).then(res => {
					console.log(res.data.framework)
					that.framework = res.data.framework
					that.tableData = res.data.data
				}).catch({

				})
			},
			//新增
			handleAdd() {
				this.dialogAdd.show = true;
				if(this.framework=='三级架构'){
					this.form = {
						options: [{
							value: '选项1',
							label: '三级架构'
						}, {
							value: '选项2',
							label: '二级架构'
						}],
						framework:this.framework
					}
				}else if(this.framework=='二级架构'){
					this.form = {
						options: [{
							value: '选项2',
							label: '二级架构'
						}],
					}
				}else{//未确定架构
					this.form = {
						options: [{
							value: '选项1',
							label: '三级架构'
						},{
							value: '选项2',
							label: '二级架构'
						} ],
					}
				}
			},
			//返回刷新页面
			update(){
				this.getCompany()
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
			//过滤筛选
			searchUserinfo(keyWorld) {
				return this.tableData.filter((NewTableDate) => {
					//shares_state code未定义，不可过滤
					// console.log(toString.call(this.tableData.shares_state))//判断字符类型
					if (NewTableDate.companyName !== null) {
						if (NewTableDate.companyName.includes(keyWorld)) {
							return NewTableDate
						}
					}
					if (NewTableDate.companyRank !== null) {
						if (NewTableDate.companyRank.includes(keyWorld)) {
							return NewTableDate
						}
					}
				})
			},
			//编辑
			handleChange(index, row) {
				this.dialogChange.show = true
				this.form = row
			},
			//删除
			handleDelete(index, row) {
				this.flag = "delete_info"
				var cardId = row.cardId
				this.commentDelete(this.flag, cardId)
			},
			//批量删除
			delAllSelection() {
				const length = this.multipleSelection.length;
				let str = '';
				this.flag = "delete_all"
				for (let i = 0; i < length; i++) {
					str += this.multipleSelection[i].cardId + ',';
				}
				this.commentDelete(this.flag, str)
				this.multipleSelection = [];
			},
			//公共删除函数
			commentDelete(flag, cardId) {
				this.$confirm('此操作将永久删除该信息, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning',
					center: true
				}).then(() => {
					var fd = new FormData()
					fd.append('flag', flag)
					fd.append('cardId', cardId)
					fd.append('account', localStorage.getItem('ms_username'))
					this.$axios.post(`http://localhost:8081/dormphp/src/Mes_Show.php`, fd).then(res => {
						if (res.data.success == 'error') {
							this.$message.error('你无权操作！！！')
						} else {
							this.$message.success('删除成功')
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
</style>
