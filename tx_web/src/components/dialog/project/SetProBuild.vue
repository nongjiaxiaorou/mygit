<template>
	<div class="AddPro px-5">
		<el-dialog :title="zoneForm.section+'/栋号定义'" :modal-append-to-body="false" :fullscreen="true" style="padding: 1%;margin: 0 100px 0 100px;"
		 customClass="customClass" :visible.sync="dialogSetFloor.show">
			<el-form ref="dialogSetFloor" style="margin: 0 40px 0 40px;">
				<el-form-item>
					<addBtn @handleAddChild='handleAdd'></addBtn>
				</el-form-item>
				<!-- <div>
					<el-row style="margin-bottom: 1%;">
						<el-col :span="3" style="text-align: center;background-color: #409EFF;color: white;">
							<div style="height: 32px;line-height: 32px;">区段名称</div>
						</el-col>
						<el-col :span="7">
							<el-input style="width: 60%;margin-left: 2%;" v-model="zoneForm.section"></el-input>
						</el-col>
					</el-row>
				</div> -->
				<el-form-item>
					<div>
						<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable"
						 header-cell-class-name="table-header" :data="tableData" stripe>
							<el-table-column type="index" label="序号" width="55" align="center"></el-table-column>
							<el-table-column prop="category" label="类别" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-cascader ref="myCascader" :options="categoryList" v-model="scope.row.category">
										</el-cascader>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column prop="build" label="栋号" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-input v-model="scope.row.build" style="width:100%;hight:100%"></el-input>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column prop="undergroundNumber" label="地下层数" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-input v-model="scope.row.undergroundNumber" style="width:100%;hight:100%"></el-input>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column prop="abovegroundNumber" label="地上层数" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-input v-model="scope.row.abovegroundNumber" style="width:100%;hight:100%"></el-input>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column prop="unitNum" label="实测单元数" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-input v-model="scope.row.unitNum" style="width:100%;hight:100%"></el-input>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column prop="unitName" label="实测单名称" align="center">
								<template slot-scope="scope">
									<el-button type="text" icon="el-icon-edit" @click="defineUnitName(scope.$index, scope.row)">定义</el-button>
								</template>
							</el-table-column>
							<el-table-column label="操作" align="center">
								<template slot-scope="scope">
									<el-button type="text" icon="el-icon-delete" class="red" @click="deleteBuild(scope.$index, scope.row)">删除</el-button>
								</template>
							</el-table-column>
						</el-table>
						<div class="pagination">
							<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
							 :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
							</el-pagination>
						</div>
					</div>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogSetFloor')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
		<DefineProBuild :dialogDefineBuild='dialogDefineBuild' :buildForm='buildForm'></DefineProBuild>
	</div>
</template>

<script>
	import addBtn from "../../common/add-btn"
	import DefineProBuild from '@/components/dialog/project/DefineProBuild';
	export default {
		name: 'AddUser',
		components: {
			addBtn,
			DefineProBuild
		},
		props: {
			dialogSetFloor: Object,
			zoneForm: Object
		},
		data() {
			return {
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				tableData: [],
				sectionNum: '', //区段数目
				dialogDefineBuild:{
					show:false
				},
				categoryList: [{
						value: '厂房项目',
						label: '厂房项目'
					},
					{
						value: '公建项目',
						label: '公建项目'
					},
					{
						value: '房建项目',
						label: '房建项目'
					},
					{
						value: '住宅项目',
						label: '住宅项目'
					},
					{
						value: '房建工程',
						label: '房建工程'
					}
				], //类别
				buildForm:{}
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
		},
		watch: {
			zoneForm: {
				handler(newValue, oldValue) {
					// console.log(newValue)
					this.getBuild()
				}
			}
		},
		mounted() {
			this.getBuild()
		},
		methods: {
			//新增该区段栋号
			handleAdd() {
				this.$prompt('请输入栋号数目', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					inputPattern: /[0-9\-]/
				}).then(({
					value
				}) => {
					const that = this
					that.buildNum = value
					let fd = new FormData()
					fd.append('flag', 'addBuild')
					fd.append('buildNum', that.buildNum)
					fd.append('zoneForm', JSON.stringify(that.zoneForm))

					that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
						console.log(res)
						this.$message({
							type: 'success',
							message: '楼栋数目为: ' + value
						});
						that.initGetSection()
					}).catch({

					})
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '取消输入'
					});
				});
			},
			//获取区段
			getBuild() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getBuild')
				fd.append('section', that.zoneForm.section)
			
				that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
					console.log(res.data)
					let dataLen = res.data.length
					that.tableData = []
					res.data.forEach(function(element) {
						that.tableData.push({
							id:element.id,
							category:element.category,
							build:element.build,
							unitNum:element.unitNum,
							undergroundNumber:element.undergroundNumber,
							abovegroundNumber:element.abovegroundNumber,
						})
					});
				}).catch({

				})
			},
			//初始化页面
			initGetSection(){
				this.getBuild()
			},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
			//确定
			dialogFormAdd(){
				const that = this
				let fd = new FormData()
				fd.append('flag', 'defineBuild')
				fd.append('tableData', JSON.stringify(that.tableData))
				fd.append('zoneForm', JSON.stringify(that.zoneForm))
				// console.log(this.tableData)
				that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
					// console.log(res)
					this.$message({
						type: 'success',
						message: '定义完成！'
					});
					that.dialogFormClose()
				}).catch({
				
				})
			},
			//定义单元名称
			defineUnitName(index,row){
				let buildNameList = []
				for(let i=0;i<row.unitNum;i++){
					buildNameList.push({
						name:''
					})
				}
				this.dialogDefineBuild.show = true
				this.buildForm = {
					options:row,
					buildNameList:buildNameList
				}
			},
			//删除楼栋
			deleteBuild(index,row){
				console.log(row.id)
				const that = this
				that.$confirm('是否删除该楼栋','取消','确定',{
					confirmButtonText:'确定',
					cancelButtonText:'取消',
					type:'warning'
				}).then(()=>{
					let fd = new FormData()
					fd.append('flag', 'deleteBuild')
					fd.append('buildId', row.id)
					// console.log(this.tableData)
					that.$axios.post(that.$adminUrl + `/project/SetProBuild.php`, fd).then(res => {
						// console.log(res)
						this.$message({
							type: 'success',
							message: '删除成功！'
						});
						that.commonFunc.removeByValue(that.tableData,'id',row.id)
					}).catch({
					
					})
				}).catch(()=>{
					
				})
			},
			//关闭模态框
			dialogFormClose(dialogSetFloor) {
				this.FormTabelEmpty();
				this.dialogSetFloor.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}

	.red {
		color: #ff0000;
	}

	.title-box {
		background-color: #d9edf7;
		height: 32px;
	}

	.info-label {
		color: #31708f;
		text-align: center;
		font-size: 14px;
	}
</style>
