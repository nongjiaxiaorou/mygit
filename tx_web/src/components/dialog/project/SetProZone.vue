<template>
	<div class="AddPro px-5">
		<el-dialog title="实测楼层信息" :modal-append-to-body="false" :fullscreen="true" style="padding: 4%;margin: 0 100px 0 100px;"
		 customClass="customClass" :visible.sync="dialogSet.show">
			<el-form ref="dialogSet" style="margin: 0 40px 0 40px;">
				<el-form-item>
					<addBtn @handleAddChild='handleAdd'></addBtn>
					<importBtn @handleImportChaild='handleImport'></importBtn>
				</el-form-item>
				<el-form-item>
					<div>
						<el-table :height="this.commonFunc.screeHeight*0.8" border class="table" ref="multipleTable"
						 header-cell-class-name="table-header" :data="table" stripe>
							<el-table-column type="index" label="序号" width="180" align="center"></el-table-column>
							<el-table-column prop="section" label="区段名称" align="center">
								<template slot-scope="scope">
									<el-form :model="scope.row">
										<el-input v-model="scope.row.section" ref="input" v-on:input="checkSectionName" style="width:100%;hight:100%;"></el-input>
									</el-form>
								</template>
							</el-table-column>
							<el-table-column label="操作" align="center">
								<template slot-scope="scope">
									<el-button type="text" icon="el-icon-edit" @click="setFloor(scope.$index, scope.row)">栋号定义</el-button>
									<el-button type="text" icon="el-icon-delete" class="red" style="margin-left: 40px;" @click="deleteSection(scope.$index, scope.row)">删除</el-button>
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
		<SetProBuild :dialogSetFloor="dialogSetFloor" :zoneForm="zoneForm"></SetProBuild>		
		<!-- 批量导入 -->
		<ImportProZone :dialogImport="dialogImport" @handleImportProZoneChild='handleImportProZone'></ImportProZone>
	</div>
</template>

<script>
	import addBtn from "../../common/add-btn"
	import SetProBuild from '@/components/dialog/project/SetProBuild';
	import importBtn from '@/components/common/import-btn';
	import ImportProZone from "../../dialog/project/ImportProZone"

	export default {
		name: 'AddUser',
		components: {
			addBtn,
			SetProBuild,
			ImportProZone,
			importBtn,
		},
		props: {
			dialogSet: Object,
			form: Object
		},
		data() {
			return {
				color:'bule',
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				tableData: [],
				sectionNum: '', //区段数目
				dialogSetFloor: {
					show: false
				},
				zoneForm: {},
				isExist: false, //区段名是否存在标志
				dialogImport: {
					show: false
				},
				registerBaseData: [],
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				// console.log(this.search_world);
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
			form: {
				handler(newValue, oldValue) {
					// console.log(newValue)
					this.getSection()
				}
			},
			tableData: 
				function (){
					this.passColor()
				}
			
		},
		mounted() {
			let registerBaseData = sessionStorage.getItem('registerBaseData')
            this.registerBaseData = JSON.parse(registerBaseData)
			this.getSection()
			this.passColor()
			
		},

		methods: {

			passColor() {
				console.log(this.tableData)
				if(this.tableData == '') {
					this.color = 'bule'
				}
				else {
					this.color = ''
				}
				console.log(this.color)
				this.$emit('func',this.color)				
			},
			
			checkSectionName(section) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'checkSection')
				fd.append('proTimeStamp', that.form.proTimeStamp)
				fd.append('section', section)
				console.log(fd);

				that.$axios.post(that.$adminUrl + `/project/SetProZone.php`, fd).then(res => {
					console.log(res)
					if (res.data.code === 1) {
						this.isExist = false
					} else {
						this.isExist = true
						that.$message.error('该工程已存在这个区段！！！')
						that.$nextTick(() => {
							that.$refs.input.focus()
						})
					}
				}).catch({

				})
			},
			//导入
			handleImport() {
				this.dialogImport.show = true;
				// console.log(this.dialogImport.show);
			},
			//导入成功后触发刷新视图
			handleImportProZone(){
				this.getSection()
			},
			//新增
			handleAdd() {
				this.$prompt('请输入区段数目', '提示', { // 阻止点击确定关闭弹窗
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					inputPattern: /[0-9\-]/
				}).then(({
					value
				}) => {
					const that = this
					that.sectionNum = value
					for (let i = 0; i < that.sectionNum; i++) {
						this.tableData.push({
							section: '',
							proTimeStamp: that.form.proTimeStamp,
							projectName: that.form.projectName,
						})
					}
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '取消输入'
					});
				});
			},
			//获取区段
			getSection() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getSection')
				fd.append('proTimeStamp', that.registerBaseData.timeStamp)
				console.log(that.form)
				that.$axios.post(that.$adminUrl + `/project/SetProZone.php`, fd).then(res => {
					console.log(res)
					this.tableData = []
					this.tableData = res.data
				}).catch({

				})
			},
			//栋号定义
			setFloor(index, row) {
				if (row.section !== '') {
					//判断该工程该区段是否存在
					if (!this.isExist) { //区段不存在即可跳转定义
						this.dialogSetFloor.show = true;
						// console.log(row)
						this.zoneForm = row
					} else {
						this.isExist = true
						this.$message.error('该工程已存在这个区段！！！')
						this.$nextTick(() => {
							this.$refs.input.focus()
						})
					}
				} else {
					this.$message.warning('请先定义区段名称！！！')
				}
			},
			//删除区段
			deleteSection(index, row) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'deleteSection')
				fd.append('section', row.section)
				// console.log(row.section)
				that.$confirm('是否删除该区段', '取消', '确定', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warring'
				}).then(() => {
					that.commonFunc.removeByValue(that.tableData,'section',row.section)
					// console.log(that.tableData)
					that.$axios.post(that.$adminUrl + `/project/SetProZone.php`,fd).then(res=>{
						// console.log(res)
						that.$message.success("删除区段成功！")
					}).catch({

					})
				}).catch(() => {
					that.$message.info("取消！")
				})

			},
			//初始化页面
			initGetSection() {
				this.getSection()
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
				this.FormTabelEmpty();
				this.dialogSet.show = false;
				
			},
			//关闭模态框
			dialogFormClose(dialogSet) {
				this.FormTabelEmpty();
				this.dialogSet.show = false;
				this.passColor();
				
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
</style>
