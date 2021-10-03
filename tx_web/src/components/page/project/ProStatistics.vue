<template>
	<div>
		<el-row style="width: 80%;">
			<el-col :span="24">
				<div class="grid-content title-text title-box">区段楼号信息/{{registerBaseData.projectName}}</div>
			</el-col>
			<div style="margin-left:20px;">
				<el-form>
					<el-form-item label="区号/类别" >
						<el-cascader v-model="value" :options="options" :props="{ expandTrigger: 'hover' }" @change="getBuildInfo"></el-cascader>
					</el-form-item>
				</el-form>
			</div>
			<div class="grid-content bg-purple-light">
				<el-table :height="this.commonFunc.screeHeight" ref="multipleTable" @selection-change="handleSelectionChange" :data="table" stripe style="width: 100%;">
					<el-table-column type="selection" width="55">
					</el-table-column>
					<el-table-column align="center" label="序号" type="index" width="100">
					</el-table-column>
					<el-table-column prop="build" align="center" label="栋号">
					</el-table-column>
					<el-table-column prop="section" align="center" label="区号">
					</el-table-column>
					<el-table-column align="center" label="操作" width="200">
						<template slot-scope="scope">
							<el-button @click.native.prevent="handleJump(scope.$index, scope.row)" type="text" size="small">
								查看详情
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</div>
            <ProStatisticsDetail :dialogProSta=dialogProSta ></ProStatisticsDetail>
			<div class="pagination">
				<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
				 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
				</el-pagination>
			</div>
		</el-row>
	</div>
</template>

<script>
import ProStatisticsDetail from '../../dialog/project/ProStaticsDetail'
	export default {
		name: 'register-person',
		props: {
			registerBaseData:Object
        },
        components: {
            ProStatisticsDetail
        },
		data() {
			return {
				loading: false,
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				tableData: [],
				value: '',
                options: [],
                dialogProSta: {
                    show:false
                }
			}
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
			}
		},
		mounted() {
			this.getSectionInfo()
		},
		watch: {
			"registerBaseData.timeStamp": {
				handler(newValue, oldValue) {
					this.getSectionInfo()
				}
			}
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
			},
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
			// 获取区号信息
			getSectionInfo() {
				let fd = new FormData()
				fd.append('flag', 'getSectionInfo')
				let sessionData = sessionStorage.getItem('registerBaseData')
				let registerBaseData = JSON.parse(sessionData)
				fd.append('timeStamp', registerBaseData.timeStamp)
				// console.log(registerBaseData.timeStamp)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					// console.log(res.data)
					this.options = res.data.data
				}).catch(function(err) {
					console.log(err)
				})
			},
			//获取栋号信息
			getBuildInfo(value) {
				this.loading = true
				// console.log(value)
				this.tableData1 = []
				let fd = new FormData()
				fd.append('flag', 'getBuildInfo')
				let sessionData = sessionStorage.getItem('registerBaseData')
				let registerBaseData = JSON.parse(sessionData)
				fd.append('timeStamp', registerBaseData.timeStamp)
				fd.append('sectionSel', this.value)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					// console.log(res.data)
					this.tableData = res.data.data
					this.loading = false
				}).catch(function(err) {
					console.log(err)
				})
			},
			//跳转到质量巡查详细页面
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
                console.log(row);
                this.dialogProSta.show = true;
				// sessionStorage.setItem('registerBaseData',JSON.stringify(row))
				// this.$router.push({
				// 	name: 'inspect',
				// 	params:row
				// })
			},
		},
		watch: {
		    "registerBaseData.timeStamp": {
				handler(newValue, oldValue) {
					// this.getTableData()
				}
		    }
		}
	};
</script>

<style scoped>
	.el-row {
		margin-bottom: 20px;
	
		&:last-child {
			margin-bottom: 0;
		}
	}
	
	.el-col {
		border-radius: 4px;
	}
	
	.bg-purple-dark {
		background: #99a9bf;
	}
	
	.bg-purple {
		background: #d3dce6;
	}
	
	.bg-purple-light {
		background: #e5e9f2;
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
	
	.title-box {
		background-color: #d9edf7;
	}
	
	.info-label {
		color: #31708f;
		line-height: 32px;
		text-align: center;
	}
	
	.info-title {
		color: inherit;
		line-height: 32px;
		text-align: center;
	}
	
	.info-title-box {
		width: 10%;
		background-color: #5bc0de;
		height: 32px;
		line-height: 32px;
		text-align: center;
	}
	
	.row-bg {
		padding: 10px 0;
		background-color: #f9fafc;
	}
	
	.box-boder div span:hover {
		color: #315efb;
		cursor: pointer;
	}
</style>
