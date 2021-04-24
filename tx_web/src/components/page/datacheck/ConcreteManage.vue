<template>
	<div class="basic" style="flex:1;display:flex=1">
		<div class="container" id="userManage">
			<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
				<el-button type="primary" class="button-style" title="选择筛选维度" size icon="el-icon-folder-add" @click="handleExcept()">选择筛选维度</el-button>
				<!-- 浇筑通知 -->
				<el-tab-pane label="浇筑通知" name="first">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables1">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="pouringPosition" label="浇筑部位" align="center"></el-table-column>
						<el-table-column prop="proState" label="阶段" align="center"></el-table-column>
						<el-table-column prop="pouringVolume" label="计划浇筑方量" align="center"></el-table-column>
						<el-table-column prop="concreteGrade" label="混凝土浇筑等级" align="b"></el-table-column>
						<el-table-column prop="pouringDate" label="浇筑日期" align="center"></el-table-column>
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
				<!-- 旁站记录 -->
				<el-tab-pane label="旁站记录" name="second">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables2">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="pouringPosition" label="施工部位" align="center"></el-table-column>
						<el-table-column prop="proState" label="阶段" align="center"></el-table-column>
						<el-table-column prop="concreteGrade" label="混凝土强度等级" align="center"></el-table-column>
						<el-table-column prop="sideDate" label="旁站时间" align="center"></el-table-column>
						<el-table-column prop="bystander" label="旁站人" align="center"></el-table-column>
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
				<!-- 拆模记录 -->
				<el-tab-pane label="拆模记录" name="third">
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					 :data="tables3">
						<el-table-column type="selection" width="55"></el-table-column>
						<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
						<el-table-column prop="projectName" label="工程名称" align="center"></el-table-column>
						<el-table-column prop="proState" label="阶段" align="center"></el-table-column>
						<el-table-column prop="concreteGrade" label="混凝土强度等级" align="center"></el-table-column>
						<el-table-column prop="pouringDate" label="浇筑日期" align="center"></el-table-column>
						<el-table-column prop="createDate" label="创建日期" align="center"></el-table-column>
						<el-table-column label="操作" align="center">
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
						 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData4.length">
						</el-pagination>
					</div>
				</el-tab-pane>
				<!-- 砼强度清单 -->
				<el-tab-pane label="砼强度清单" name="fourth">
					<!-- <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
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
					</div> -->
				</el-tab-pane>
			</el-tabs>
		</div>
		<InspectExcept :dialogExcept="dialogExcept" @handleSelectChild="handleSelect"></InspectExcept>
	</div>
</template>
<script>
	import InspectExcept from "../../dialog/datacheck/InspectExcept3.vue"
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
			this.getNoticeData1();
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
			//获取浇筑旁站通知单数据
			getNoticeData() {
				var that = this
				var fd = new FormData()
				let userInfo = localStorage.getItem('userInfo')
				userInfo = JSON.parse(userInfo)
				fd.append('flag', 'getNoticeData')
				fd.append('userId', userInfo.userId)
				fd.append('level', this.level)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				//获取浇筑
				this.$axios.post(that.$adminUrl + `/datacheck/ConcreteManage/ConcreteManage.php`, fd).then(res => {
					console.log(res.data)
					if (res.data.code) {
						let draftIndex = 1
						let rectification = 1
						for(var i=0;i<res.data.data.length;i++){
							if(res.data.data[i].proState=="浇筑"){
								res.data.data[i].index = draftIndex
								this.tableData1.push(res.data.data[i])
								draftIndex++
							}else{
								res.data.data[i].index = draftIndex
								this.tableData2.push(res.data.data[i])
								rectification++
							}
						}
						loading.close();
					}
				}).catch(() => {
                    console.error("获取失败")
                    loading.close();
				})
			},
			//获取拆模通知单
			getNoticeData1() {
				var that = this
				var fd = new FormData()
				let userInfo = localStorage.getItem('userInfo')
				userInfo = JSON.parse(userInfo)
				fd.append('flag', 'getNoticeData1')
				fd.append('userId', userInfo.userId)
				fd.append('level', this.level)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				//获取浇筑
				this.$axios.post(that.$adminUrl + `/datacheck/ConcreteManage/ConcreteManage.php`, fd).then(res => {
					console.log(res.data)
					if (res.data.code) {
						let finishedIndex = 1
						for(var i=0;i<res.data.data.length;i++){
								res.data.data[i].index = finishedIndex
								this.tableData3.push(res.data.data[i])
								finishedIndex++
						}
						loading.close();
					}
				}).catch(() => {
                    console.error("获取失败")
                    loading.close();
				})
			},
			//详情
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				sessionStorage.setItem('concreteData', JSON.stringify(row))
				// console.log(row)
				this.$router.push({
					name: 'concreteDetail',
					params: row
				})
			},
			//打开筛选模态框
			handleExcept() {
				this.dialogExcept.show = true;
			},
			//接收子组件传值
			handleSelect(data,flag) {
				let res = data
				if (res.code) {
					let draftIndex = 1
					let rectification = 1
					let finishedIndex = 1
					console.log(res.data)
					// this.tableData4 = []
					if(flag=='pour'){
						this.tableData1 = []
						this.tableData2 = []
						for(var i=0;i<res.data.length;i++){
							if(res.data[i].proState=="浇筑"){
									res.data[i].index = draftIndex
									this.tableData1.push(res.data[i])
									draftIndex++
							}else{
								res.data[i].index = draftIndex
								this.tableData2.push(res.data[i])
								rectification++
							}
						}
					}else{
						this.tableData3 = []
						let finishedIndex = 1
						for(var i=0;i<res.data.length;i++){
								res.data[i].index = finishedIndex
								this.tableData3.push(res.data[i])
								finishedIndex++
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
