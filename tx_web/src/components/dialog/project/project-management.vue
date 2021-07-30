<!-- popover样式需要到main.css文件中修改 -->
<template>
	<div :style="{height:this.commonFunc.screeHeight*1.4 +'px'}">
		<el-row>
			<el-col :span="8" style="height: 150px;border: 1px solid #000000;">
				<div class="grid-content title-text title-box"> 区号及类别筛/{{registerBaseData.projectName}} <i class="el-icon-info"
					 title="若选项框没有数据,需要到项目登记的基本信息中进行区段定义!"></i></div>
				<div class="grid-content-box">
					<div class="block">
						<!-- <span class="demonstration">hover 触发子菜单</span> -->
						<el-cascader v-model="value" :options="options" :props="{ expandTrigger: 'hover' }" @change="getBuildInfo"></el-cascader>
					</div>
				</div>
			</el-col>
			<el-col :span="16" style="height: 150px;border: 1px solid #000000;">
				<div class="grid-content title-text title-box">增加栋号（注：请先完成 区号及类别筛选）</div>
				<div class="grid-content-box">
					<el-cascader class="customCascader" v-model="newAddValue" :options="options" :props="{ expandTrigger: 'hover' }" @change="newAddInfo"></el-cascader>
					<el-button type="primary" class="circle-mg" icon="el-icon-edit" circle @click="openAddBuildDialog"></el-button>
				</div>
			</el-col>
		</el-row>
		<el-row>
			<el-col :span="8" style="height: 400px;border: 1px solid #000000;">
				<el-row>
					<div class="grid-content title-text title-box"> 楼号信息</div>
				</el-row>
				<div class="content-table-box">
					<el-table :height="this.commonFunc.screeHeight/1.5" :data="tableData1" style="width: 100%" max-height="300" empty-text="请选择区号"
					 v-loading="loading" element-loading-text="数据加载中">
						<el-table-column type="selection"></el-table-column>
						<el-table-column prop="build" label="栋号" align="center">
						</el-table-column>
						<el-table-column label="操作" align="center">
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
								<el-button type="text" icon="el-icon-s-promotion" class="green" @click="checkDetail(scope.$index, scope.row)">查看</el-button>
							</template>
						</el-table-column>
					</el-table>
				</div>
			</el-col>
			<el-col :span="16" class="row-table">
				<el-row>
					<el-col :span="24">
						<div class="flex grid-content title-text title-box flex-box">
							<div>
								 实测单元信息{{buildSel}}
								<i class="el-icon-info" title="若当前行楼层的字体为绿色,则已上传图片,请将鼠标移至相应楼层查看图片"></i>
							</div>
							<div>
								<el-button class="el-icon-picture" @click="openDialog" >图纸批量上传</el-button> 
								<el-button class="el-icon-s-custom" @click="openPersonDialog">责任人</el-button> 
							</div>
						</div>
					</el-col>
				</el-row>
				<!-- <span>{{tableData2}}</span> -->
				<div class="grid-content bg-purple-light">
					<el-table :height="this.commonFunc.screeHeight/1.5" ref="multipleTable" @selection-change="handlefloorSelectionChange"
					 :data="tableData2" stripe style="width: 100%;">
						<el-table-column type="selection" width="55">
						</el-table-column>
						<el-table-column prop="floor" align="center" label="楼层" width="100">
							<template slot-scope="scope">
								<el-popover placement="left-start" width="300" trigger="hover">
									<img height="300" width="300" :src="scope.row.picName" />
									<span :class="scope.row.isHasPicture?'green':''" slot="reference">{{ scope.row.floor }}</span>
									<!-- <el-button slot="reference">hover 激活</el-button> -->
								</el-popover>
							</template>
						</el-table-column>
						<el-table-column prop="unit" align="center" label="查看单元">
							<template slot-scope="scope">
								<el-popover title="查看单元" placement="right-start" width="60" trigger="hover" ref="myPopover">
									<span slot="reference">{{ scope.row.unit }}</span>
									<div class="box-boder">
										<div v-for="item in scope.row.children"><span>{{item.unitContent}}</span></div>
									</div>
								</el-popover>
							</template>
						</el-table-column>
						<el-table-column fixed="right" align="center" label="操作">
							<template slot-scope="scope">
								<el-button @click.native.prevent="changeUnit(scope.$index, scope.row)" type="text" size="small">
									修改单元
								</el-button>
								<!-- <el-button @click.native.prevent="deleteRow(scope.$index, tableData2)" type="text" size="small">
									上传图纸
								</el-button> -->
							</template>
						</el-table-column>
					</el-table>
				</div>
			</el-col>
		</el-row>
		<importFloorPic @handleImportFloorChild="handleImportFloor" :registerBaseData = "registerBaseData" :floorData = "floorData" :dialogImportPic = "dialogImportPic"></importFloorPic>
		<addNewBuild @handleNewBuildChild="handleNewBuild" :registerBaseData = "registerBaseData" :sectionData = "sectionData" :dialogNewBuild = "dialogNewBuild"></addNewBuild>
		<updateUnit @handleNewUnitChild="handleNewUnit" :unitInfo = "unitInfo" :dialogNewUnit = "dialogNewUnit"></updateUnit>
		<addPesponsiblePerson handleNewBuildPersonChild="handleNewBuildPerson" :currentRow = "currentRow" :buildPerson = "buildPerson" :dialogNewResPerson = "dialogNewResPerson"></addPesponsiblePerson>
	</div>
</template>

<script>
	import importFloorPic from "./ImportFloorPic"
	import addNewBuild from "./AddNewBuild"
	import updateUnit from "./UpdateUnit"
	import addPesponsiblePerson from "./AddResponsiblePerson"
	export default {
		name: 'project-management',
		components: {
			importFloorPic,
			addNewBuild,
			updateUnit,
			addPesponsiblePerson
		},
		props: {
			registerBaseData: Object
		},
		data() {
			return {
				tableData2: [],
				floorSel: [],
				value: [],
				options: [],
				tableData1: [],
				loading: false,
				buildSel: '',
				dialogImportPic: {
					show: false
				},
				floorData: [],
				currentRow: {},
				newAddValue: [],
				dialogNewBuild: {
					show: false
				},
				sectionData: [],
				dialogNewUnit: {
					show: false
				},
				unitInfo: {},
				dialogNewResPerson: {
					show: false
				},
				buildPerson: {}
			}
		},
		mounted() {

		},
		methods: {
			handlefloorSelectionChange(val) {
				this.floorSel = val;
				console.log(this.floorSel)
			},
			//获取栋号信息
			getBuildInfo(value) {
				this.loading = true
				// console.log(value);
				this.tableData1 = []
				let fd = new FormData()
				fd.append('flag', 'getBuildInfo')
				let sessionData = sessionStorage.getItem('registerBaseData')
				let registerBaseData = JSON.parse(sessionData)
				fd.append('timeStamp', registerBaseData.timeStamp)
				fd.append('sectionSel', this.value)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					// console.log(res.data)
					this.tableData1 = res.data.data
					this.loading = false
				}).catch(function(err) {
					console.log(err)
				})
			},
			newAddInfo() {
				this.sectionData = this.newAddValue
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
			changeUnit(index, rows) {
				this.unitInfo = rows
				this.dialogNewUnit.show = true
				// console.log(rows)
			},
			handleEdit(index, rows) {
				// console.log(rows)
				this.$prompt('请输入栋号', '修改栋号', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					inputValue: rows.build
				}).then(({
					value
				}) => {
					// console.log(value)
					let fd = new FormData()
					fd.append('flag', 'updateBuildInfo')
					fd.append('buildId', rows.id)
					fd.append('newBuild', value)
					let sessionData = sessionStorage.getItem('registerBaseData')
					let registerBaseData = JSON.parse(sessionData)
					fd.append('timeStamp', registerBaseData.timeStamp)
					fd.append('sectionSel', this.value)
					this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
						// console.log(res.data)
						if (res.data.code) {
							this.getBuildInfo()
							this.$message({
								type: 'success',
								message: '你将: ' + rows.build + ' 修改为 ' + value
							});
						} else {
							this.$message({
								type: 'warning',
								message: '该栋号已存在，请重新设置！'
							});
						}
					}).catch(function(err) {
						console.log(err)
					})
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '取消输入'
					});
				});
			},
			//数字转中文
			toChinesNum(num){
				var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
				var unit = ["", "十", "百", "千", "万"];
				num = parseInt(num);
				var getWan = function(temp){
				var strArr = temp.toString().split("").reverse();
				var newNum = "";
				for (var i = 0; i < strArr.length; i++) {
				  newNum = (i == 0 && strArr[i] == 0 ? "" : (i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? "" : changeNum[strArr[i]] + (strArr[i] == 0 ? unit[0] : unit[i]))) + newNum;
				}
				 return newNum;
			   }
			   var overWan = Math.floor(num / 10000);
			   var noWan = num % 10000; 
			   if (noWan.toString().length < 4) noWan = "0" + noWan;
			   return overWan ? getWan(overWan) + "万" + getWan(noWan) : getWan(num);
			},
			//获取楼层图纸
			getFloorPicFunc() {
				let fd = new FormData()
				fd.append('flag', 'getPicInfo')
				let sessionData = sessionStorage.getItem('registerBaseData')
				let registerBaseData = JSON.parse(sessionData)
				fd.append('proTimeStamp', registerBaseData.timeStamp)
				fd.append('section', this.currentRow.section)
				fd.append('build', this.currentRow.build)
				// console.log(registerBaseData.timeStamp)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					// console.log(res.data)
					// this.options = res.data.data
					this.createFloorFunc(res.data.data)
				}).catch(function(err) {
					console.log(err)
				})
			},
			// 遍历创建楼层
			createFloorFunc(data) {
				// console.log(data)
				this.tableData2 = []
				let rows = this.currentRow
				//遍历地下楼层
				var undergroundNum = parseInt(rows.undergroundNumber);
				if (undergroundNum) {
					let unit_arr = rows.unitName.split('||')
					var index = undergroundNum;
					for (var i = 0; i < undergroundNum + 1; i++) {
						if (i == 0) {
							var FloorName = "基础层";
						} else {
							var FloorName = "负" + this.toChinesNum(index) + "层";
							index--;
						}
						var obj = {
							id: rows.id,
							floor: FloorName,
							unit: '移入查看所有单元',
							build: rows.build,
							section: this.value[0],
							picName: this.$adminUrl+'/../images/pc_pic/floorPic/null.jpg',
							children: [],
							isHasPicture: false
						};
						//判断是否有图纸
						if(data.length>0){
							for(var z=0;z<data.length;z++){
								if(data[z].floor==FloorName){
									obj.picName =  this.$adminUrl+'/../images/pc_pic/floorPic/'+data[z].picName
									obj.isHasPicture = true
									break;
								}
							}
						}
						//遍历单元
						for(var j=0;j<unit_arr.length;j++){
							let unitObj = {
								unitContent: unit_arr[j]
							}
							obj.children.push(unitObj)
						}
						this.tableData2.push(obj);
					}
				}
				
				//遍历地上楼层
				var abovegroundNum = parseInt(rows.abovegroundNumber);
				if (abovegroundNum) {
					let unit_arr = rows.unitName.split('||')
					var index = abovegroundNum;
					for (var i = 0; i < abovegroundNum + 1; i++) {
						if(i==abovegroundNum){
							var FloorName = "屋面层";
						}else{
							var FloorName = this.toChinesNum(i+1)+"层";
						}
						var obj = {
							id: rows.id,
							floor: FloorName,
							unit: '移入查看所有单元',
							build: rows.build,
							section: this.value[0],
							picName: this.$adminUrl+'/../images/pc_pic/floorPic/null.jpg',
							children: [],
							isHasPicture: false
						};
						//判断是否有图纸
						if(data.length>0){
							for(var z=0;z<data.length;z++){
								if(data[z].floor==FloorName){
									obj.picName =  this.$adminUrl+'/../images/pc_pic/floorPic/'+data[z].picName
									obj.isHasPicture = true
									break;
								}
							}
						}
						//遍历单元
						for(var j=0;j<unit_arr.length;j++){
							let unitObj = {
								unitContent: unit_arr[j]
							}
							obj.children.push(unitObj)
						}
						this.tableData2.push(obj);
					}
				}
			},
			//查看楼层信息
			checkDetail(index, rows) {
				this.currentRow = rows
				this.buildSel = '/' + rows.build
				// console.log(this.value[0])
				this.getFloorPicFunc()
				this.getBuildPersonFunc()
			},
			openDialog() {
				console.log(this.floorSel)
				this.floorData = this.floorSel
				this.dialogImportPic.show = true
			},
			//子组件调取的函数
			handleImportFloor() {
				this.getFloorPicFunc()
			},
			openAddBuildDialog() {
				// console.log(this.sectionData)
				if(this.sectionData.length==0){
					this.$message({
						message: '请选择区号后重新点击此按钮！',
						type: 'warning'
					});
				}else{
					this.dialogNewBuild.show = true
				}
			},
			getUnitInfo() {
				let fd = new FormData()
				fd.append('flag', 'getUnitInfo')
				fd.append('buildId', this.currentRow.id)
				// console.log(registerBaseData.timeStamp)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					if(res.data.code){
						this.currentRow.unitName = res.data.data[0].unitName
					}
				}).catch(function(err) {
					console.log(err)
				})
			},
			handleNewBuild() {
				this.getBuildInfo()
			},
			handleNewUnit() {
				this.getUnitInfo()
				const that = this
				setTimeout(function(){
					that.getFloorPicFunc()
				},0)
			},
			handleNewBuildPerson() {
				this.getBuildPersonFunc()
			},
			//获取栋号责任人
			getBuildPersonFunc() {
				// console.log(this.currentRow)
				let fd = new FormData()
				fd.append('flag', 'getBuildPerson')
				fd.append('buildId', this.currentRow.id)
				this.$axios.post(this.$adminUrl + `/project/projectManagement.php`, fd).then(res => {
					// console.log(res.data)
					if(res.data.data.length!=0){
						this.buildPerson = res.data.data
					}
				}).catch(function(err) {
					console.log(err)
				})
			},
			openPersonDialog() {
				// console.log(this.currentRow)
				var length =0;
				for(var ever in this.currentRow){
					length++;
				}
				if(length==0){
					this.$notify({
						title: '警告',
						message: '请先选择对应的楼栋再对栋号责任人进行定义',
						type: 'warning'
					});
				}else{
					console.log(this.buildPerson)
					this.dialogNewResPerson.show = true
				}
			}
		},
		watch: {
			"registerBaseData.timeStamp": {
				handler(newValue, oldValue) {
					this.getSectionInfo()
				}
			}
		}
	}
</script>

<style scoped>
	.el-row {
		margin-bottom: 10px;

		&:last-child {
			margin-bottom: 0;
		}
	}

	.el-col {
		border-radius: 4px;
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
		text-align: center;
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

	.grid-content-box {
		width: 100%;
		text-align: center;
		margin-top: 20px;
	}

	.green {
		color: #67c23a;
	}

	/* .titleZise {
		width: 100%;
		background-color: #eef1f6;
		text-align: center;
	} */
	.font-sm {
		font-size: 25px;
	}

	.row-table {
		height: 400px;
		border: 1px solid #000000;
	}

	.box-boder div {
		border-bottom: medium dashed #EEE9E9;
		text-align: center;
	}

	.box-boder div span:hover {
		color: #315efb;
		cursor: pointer;
	}
	.flex-box {
		justify-content:space-between;align-items: center;
	}
	.flex-box div:nth-child(1) {
		margin-left: 30px;
	}
	.flex-box div:nth-child(2) {
		display: flex; 
		align-items: center;
		margin-right: 10px;
	}
	.circle-mg{
		margin-left: 5px;
	}
	.content-table-box {
		margin: 0.625rem;
	}
</style>
