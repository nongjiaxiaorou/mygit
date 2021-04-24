<template>
	<div class="basic">
		<div class="container">
			<div class="handle-box">
				<addBtn @handleAddChild='handleAdd' :buttonTitle='buttonTitle1'></addBtn>
				<addBtn @handleAddChild='handleAddDepot' :buttonTitle='buttonTitle2'></addBtn>
				<el-select v-model="value" placeholder="请选择违章数据库" style="margin-left: 10px;" @change="selectDepot">
					<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
					</el-option>
				</el-select>
				<el-input placeholder="请输入内容筛选" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
			</div>
			<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header"
			 @selection-change="handleSelectionChange" :data="table">
				<el-table-column type="selection" width="55"></el-table-column>
				<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
				<el-table-column prop="majorCategories" label="违章大类" align="center" width="120"></el-table-column>
				<el-table-column prop="subItems" label="分项分部" align="center" width="150"></el-table-column>
				<el-table-column prop="mainPoints" label="管控要点" align="center" width="120"></el-table-column>
				<el-table-column prop="describe" label="问题描述" align="center"></el-table-column>
				<el-table-column prop="number" label="编号" align="center" width="80"></el-table-column>
				<el-table-column label="操作" width="180" align="center">
					<template slot-scope="scope">
						<el-button type="text" icon="el-icon-edit" @click="handleChange(scope.$index, scope.row)">修改</el-button>
						<!-- <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button> -->
					</template>
				</el-table-column>
			</el-table>
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
				 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
				</el-pagination>
			</div>
			<AddViolations :dialogAdd='dialogAdd' @update='update' :form='form'></AddViolations>
			<ChaneViolations :dialogChange='dialogChange' :form='form'></ChaneViolations>
		</div>
	</div>
</template>
<script>
	import addBtn from "../../common/add-btn"
	import AddViolations from "@/components/dialog/system/AddViolations"
	import ChaneViolations from "@/components/dialog/system/ChaneViolations"

	export default {
		name: 'basetable',
		components: {
			addBtn,
			AddViolations,
			ChaneViolations
		},
		data() {
			return {
				currentPage: 1, //初始页
				pagesize: 10, //    每页的数据
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				dialogChange: {
					show: false
				},
				dialogAdd: {
					show: false
				},
				search_world: '',
				tableData: [],
				multipleSelection: [],
				form: {},
				hideRow: false,
				buttonTitle1: '新增条目',
				buttonTitle2: '新增自定义库',
				options: [],
				value: ''
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
			newName() {
				return this.query.name;
			}
		},
		watch: {
			newName(val) {
				if (val == '') {
					this.searchKey = 'show';
				}
			}
		},
		mounted() {
			this.getTableData()
		},
		methods: {
			//获取违章条目
			getTableData() {
				const that = this
				let fd = new FormData();
				fd.append('flag', 'getItem')

				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('.container'),
					spinner: 'el-icon-loading'
				});
				loading.close();
				that.$axios.post(that.$adminUrl + `/system/Database.php`, fd).then(res => {
					// console.log(res.data.data)
					that.tableData = res.data.data
					this.getDepot()
				}).catch({

				})
			},
			//获取违章自定义库
			getDepot() {
				const that = this
				let fd = new FormData();
				fd.append('flag', 'getDepot')
				that.$axios.post(that.$adminUrl + `/system/Database.php`, fd).then(res => {
					// console.log(res.data.data)
					that.options = []
					that.value = ''
					for(let i =0;i<res.data.data.length;i++){
						that.options.push({
							value:res.data.data[i].id,
							label:res.data.data[i].depotName
						})
					}
				}).catch({
			
				})
			},
			//选择自定义库后
			selectDepot(val){
				const that = this
				let fd = new FormData();
				fd.append('flag', 'selectDepot')
				fd.append('depotId', val)
				that.$axios.post(that.$adminUrl + `/system/Database.php`, fd).then(res => {
					// console.log(res.data.data)
					that.tableData = res.data.data
				}).catch({
							
				})
			},
			//新增
			handleAdd() {
				this.dialogAdd.show = true;
				this.form = {
					depotList:this.options
				}
			},
			//新增自定义库
			handleAddDepot() {
				this.$prompt('请输入自定义库名', '新增自定义库', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(({
					value
				}) => {
					const that = this
					let fd = new FormData()
					fd.append('flag','addDepot')
					fd.append('depotName',value)
					that.$axios.post(that.$adminUrl+`/system/Database.php`,fd).then(res=>{
						// console.log(res)
						that.$message.success('新增成功！')
						that.getDepot()
					}).catch({
						
					})
				}).catch(() => {

				});
			},
			//新增后刷新视图
			update() {
				this.getTableData()
			},
			// 触发搜索按钮
			handleSearch() {
				this.$set(this.query, 'pageIndex', 1);
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//修改条目
			handleChange(index, row) {
				this.dialogChange.show = true
				this.form = row
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
