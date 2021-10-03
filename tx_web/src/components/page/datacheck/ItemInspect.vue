<template>
	<div class="basic" style="flex:1;display:flex=1">
		<div class="container" id="userManage">
			<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
				<el-button type="primary" class="button-style" title="选择筛选维度" size icon="el-icon-folder-add" @click="handleExcept()">选择筛选维度</el-button>
				<!-- 草稿 -->
				<el-tab-pane label="草稿" name="first">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables1">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="noticeNumber" label="通知单编号" align="center"></el-table-column>
						<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
						<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
						<el-table-column prop="inspectItem" label="检查项目" align="b"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
						<el-table-column label="操作" width="180" align="center">
							<template slot="header" slot-scope="scope">  
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
						</el-pagination> 
					</div>
				</el-tab-pane>
				<!-- 整改中 -->
				<el-tab-pane label="整改中" name="second">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables2">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="noticeNumber" label="通知单编号" align="center"></el-table-column>
						<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
						<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
						<el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
						<el-table-column label="操作" width="180" align="center">
							<template slot="header" slot-scope="scope">
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 已完成 -->
				<el-tab-pane label="已完成" name="third">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables3">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="noticeNumber" label="通知单编号" align="center"></el-table-column>
						<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
						<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
						<el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
							<template slot="header" slot-scope="scope">
								<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
							</template>
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
							</template>
						</el-table-column>
					</el-table>
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData3.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 已关闭 -->
				<el-tab-pane label="已关闭" name="fourth">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables4">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="noticeNumber" label="通知单编号" align="center"></el-table-column>
						<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
						<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
						<el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
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
		<InspectExcept :dialogExcept="dialogExcept" @handleSelectChild="handleSelect"></InspectExcept>
	</div>
</template>
<script>
	import InspectExcept from "../../dialog/datacheck/InspectExcept.vue"
	export default {
		name: 'basetable',
		components: {
			InspectExcept
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
				dialogExcept: {
					show: false
				},
				activeName: 'first',
				search: '',
				search_world: '',
				tableData1: [],
				tableData2: [],
				tableData3: [],
				tableData4: [],
				isShow: false,
				level: '总公司'
			};
		},
		mounted() {
			this.getNoticeData();
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
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
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
			//获取通知单数据
			getNoticeData() {
				var that = this
				var fd = new FormData()
				let userInfo = localStorage.getItem('userInfo')
				userInfo = JSON.parse(userInfo)
				fd.append('flag', 'getNoticeData')
				fd.append('userId', userInfo.userId)
				fd.append('level', this.level)
				this.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
					// console.log(res.data)
					if (res.data.code) {
						let draftIndex = 1
						let rectification = 1
						let finishedIndex = 1
						let closedIndex = 1
						for(var i=0;i<res.data.data.length;i++){
							if(res.data.data[i].state=="草稿"){
								res.data.data[i].index = draftIndex
								this.tableData1.push(res.data.data[i])
								draftIndex++
							}else if(res.data.data[i].state=="整改中"){
								res.data.data[i].index = rectification
								this.tableData2.push(res.data.data[i])
								rectification++
							}else if(res.data.data[i].state=="已完成"){
								res.data.data[i].index = finishedIndex
								this.tableData3.push(res.data.data[i])
								finishedIndex++
							}else if(res.data.data[i].state=="已关闭"){
								res.data.data[i].index = closedIndex
								this.tableData4.push(res.data.data[i])
								closedIndex++
							}
						}
					}
				})
			},
			//详情
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				sessionStorage.setItem('itemInspectData', JSON.stringify(row))
				// console.log(row)
				this.$router.push({
					name: 'inspectDetail',
					params: row
				})
			},
			//打开筛选模态框
			handleExcept() {
				this.dialogExcept.show = true;
			},
			//接收子组件传值
			handleSelect(data) {
				let res = data
				if (res.code) {
					let draftIndex = 1
					let rectification = 1
					let finishedIndex = 1
					let closedIndex = 1
					this.tableData1 = []
					this.tableData2 = []
					this.tableData3 = []
					this.tableData4 = []
					for(var i=0;i<res.data.length;i++){
						if(res.data[i].state=="草稿"){
							res.data[i].index = draftIndex
							this.tableData1.push(res.data[i])
							draftIndex++
						}else if(res.data[i].state=="整改中"){
							res.data[i].index = draftIndex
							this.tableData2.push(res.data[i])
							rectification++
						}else if(res.data[i].state=="已完成"){
							res.data[i].index = finishedIndex
							this.tableData3.push(res.data[i])
							finishedIndex++
						}else if(res.data[i].state=="已关闭"){
							res.data[i].index = closedIndex
							this.tableData4.push(res.data[i])
							closedIndex++
						}
					}
				}
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
	
	.button-style {
		margin: 5px;
	}
</style>
