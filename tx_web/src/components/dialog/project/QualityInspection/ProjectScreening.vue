<template>
	<div class="basic" style="flex:1;display:flex=1">
		<div class="container" id="userManage1"> 
			<div class="boxFather" style="" id="leftBox">
				<el-row class="tac">
					<el-col :span="4">
						<el-menu
						default-active="2"
						class="el-menu-vertical-demo"
						@open="handleOpen"
						@close="handleClose">
							<el-submenu index="1">
								<template slot="title">
									<span>项目实测</span>
								</template>
								<!-- <el-submenu index="1-1">
									<template slot="title">建筑工程</template>
									<el-menu-item index="1-1-1">混凝土结构工程</el-menu-item>
									<el-menu-item index="1-1-2">砌筑工程</el-menu-item>
									<el-menu-item index="1-1-3">抹灰工程</el-menu-item>
									<el-menu-item index="1-1-4">设备安装工程</el-menu-item>
								</el-submenu>
								<el-submenu index="1-2">
									<template slot="title">装饰工程</template>
									<el-menu-item index="1-2-1">地面饰面砖工程</el-menu-item>
									<el-menu-item index="1-2-2">墙面涂饰面工程</el-menu-item>
									<el-menu-item index="1-2-3">墙面饰面砖工程</el-menu-item>
									<el-menu-item index="1-2-4">木地板安装工程</el-menu-item>
									<el-menu-item index="1-2-5">电梯前室、首层大堂</el-menu-item>
									<el-menu-item index="1-2-6">设备安装工程</el-menu-item>
								</el-submenu> -->
							</el-submenu>
							<el-submenu index="2">
								<template slot="title">
									<span>项目排查</span>
								</template>
							</el-submenu>
							<el-submenu index="3">
								<template slot="title">
									<span>项目验收</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="3-1" @click.native="handleQualityInspections">实名制工序验收</el-menu-item>
									<el-menu-item index="3-2" @click.native="handleInspectMeasured">工序交接验收</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
							<!-- <el-submenu index="4">
								<template slot="title">
									<span>结构混泥土质量管理</span>
								</template>
								<el-menu-item-group>

								</el-menu-item-group>
							</el-submenu>						 -->
						</el-menu>
					</el-col>
				</el-row>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%">
					<div class="grid-content title-text title-box">项目排查/</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <div class="handle-box">
                            <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                        </div>
                        <el-tab-pane label="草稿" name="first">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
                                </el-pagination>
                            </div>
                            <el-button class="button1">返回</el-button>
                        </el-tab-pane>

                        <el-tab-pane label="整改中" name="second">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
                                </el-pagination>
                            </div>
                            <el-button class="button1">返回</el-button>
                        </el-tab-pane>
                        <el-tab-pane label="已完成" name="third">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
                                </el-pagination>
                            </div>
                            <el-button class="button1">返回</el-button>
                        </el-tab-pane>
                                                <el-tab-pane label="已关闭" name="fourth">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
                                </el-pagination>
                            </div>
                            <el-button class="button1">返回</el-button>
                        </el-tab-pane>
                    </el-tabs>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'ProjectScreening',
		components: {

		},
		data() {
			return {
                activeName: 'first',
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

		},
		methods: {
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
			// 触发搜索按钮
			handleSearch() {
				this.$set(this.query, 'pageIndex', 1);
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//详情
			// handleJump(index, row) {
			// 	// console.log(index);
			// 	// console.log(row);
			// 	//每次进入存储session值，以防止页面刷新数据丢失
			// 	sessionStorage.setItem('registerBaseData',JSON.stringify(row))
			// 	this.$router.push({
			// 		name: 'aaa',
			// 		params:row
			// 	})
            // },
            handleClick(tab, event) {
                console.log(tab, event);
            },
			handleOpen(key, keyPath, row) {
                console.log(key, keyPath);
                if(key==1){
                    this.jump = 'ProjectMeasurement';
                }
                else if(key==2){
                    this.jump = 'ProjectScreening';
                }
                else if(key==3){
                    this.jump = 'projectAcceptance1';
                }
                else {

                };
                this.$router.push({
					name: this.jump,
					params:row
				});                 
            },
            handleClose(key, keyPath, row) {
                console.log(key, keyPath);
                if(key==1){
                    this.jump = 'ProjectMeasurement';
                }
                else if(key==2){
                    this.jump = 'ProjectScreening';
                }
                else if(key==3){
                    this.jump = 'projectAcceptance1';
                }
                else {

                };
                this.$router.push({
					name: this.jump,
					params:row
				});
            },
			handleQualityInspections(row) {
				this.$router.push({
					name: 'projectAcceptance1',
					params:row
				});
            },
            handleInspectMeasured(row) {
				this.$router.push({
					name: 'projectAcceptance2',
					params:row
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
	.boxFather {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
	.button1{
		width: 50px;
        height: 30px;
		float:right;
	}
	.title-box {
		background-color: #d9edf7;
	}
	.grid-content {
		border-radius: 4px;
		min-height: 32px;
		margin: 0.625rem;
	}
	.title-text {
		color: #31708f;
		line-height: 32px;
	}
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
