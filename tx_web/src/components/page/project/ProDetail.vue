<template>
	<div id="ProDetail" class="basic container flex align-center justify-center">
		<div class="flex-1 px-5" style="" id="leftBox">
			<el-tabs :tab-position="tabPosition" @tab-click="handleChange">
				<el-tab-pane label="项目登记">
					<el-tabs style="margin-left: 10%;width: 88%;" v-model="activeName" type="card" @tab-click="handleClick">
						<!-- 基本信息页面 -->
						<el-tab-pane label="基本信息" name="first">
							<registerBase :registerBaseData="registerBaseData"></registerBase>
						</el-tab-pane> 
						<!-- 管理人员页面 -->
						<el-tab-pane label="管理人员" name="second">
							<registerPerson :framework="framework" :registerBaseData="registerBaseData"></registerPerson>
						</el-tab-pane>
						<!-- 分包单位页面 -->
						<el-tab-pane label="分包单位" name="third">
							<registerUnit :registerBaseData="registerBaseData"></registerUnit>
						</el-tab-pane>
						<!-- 实测标准页面 -->
						<el-tab-pane label="实测标准" name="fourth">
							<registerStandard></registerStandard>
						</el-tab-pane>
					</el-tabs>
				</el-tab-pane>


				<el-tab-pane label="项目管理" style="margin-left: 10%;width: 88%;" >
					<projectManagement :registerBaseData="registerBaseData"></projectManagement>
				</el-tab-pane>


				<!-- <el-tab-pane label="项目统计"  style="margin-left: 10%;width: 88%;">
					<projectStatistics :registerBaseData="registerBaseData">aaa</projectStatistics>
				</el-tab-pane> -->

				<el-tab-pane label="质量巡查" style="margin-left: 10%;width: 88%;">
					<inspectionZone :registerBaseData="registerBaseData"></inspectionZone>
				</el-tab-pane>
			</el-tabs>
		</div>
		<div class="px-5" id="rightBox">




		</div>
	</div>
</template>
<script>
	import registerBase from '../../dialog/project/register-base'
	import registerPerson from '../../dialog/project/register-person'
	import registerUnit from '../../dialog/project/register-unit'
	import registerStandard from '../../dialog/project/register-standard'
	import projectManagement from '../../dialog/project/project-management'
	import inspectionZone from '../../dialog/project/inspection-zone'
	import projectStatistics from './ProStatistics'
	export default {
		name: 'basetable',
		components: {
			registerBase,
			registerPerson,
			registerUnit,
			registerStandard,
			projectManagement,
			inspectionZone,
			projectStatistics
		},
		data() {
			return {
				loading: false,
				tabPosition: 'left',
				activeName: '',
				registerBaseData: {},
				framework: '',
				tableData: [{
					date: '2016-05-02',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1518 弄'
				}, {
					date: '2016-05-04',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1517 弄'
				}, {
					date: '2016-05-01',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1519 弄'
				}, {
					date: '2016-05-03',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1516 弄'
				}],

			}
		},
		computed: {

		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('registerBaseData')
			this.registerBaseData = JSON.parse(sessionData)
			this.getFramework()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('registerBaseData')
				this.registerBaseData = JSON.parse(sessionData)
				console.log(this.registerBaseData);
				// console.log(this.registerBaseData)
			},
			//点击切换页签
			handleClick(tab, event) {
				console.log(tab);
				console.log(event);
			},
			//点击切换左侧导航
			handleChange(){
				//点击触发子组件方法
				console.log(this.$refs);
				// this.$refs.anagement.getSectionInfo()
				
			},
			//获取架构等级
			getFramework() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getCompany')
				this.$axios.post(this.$adminUrl + `/system/Company.php`, fd).then(res => {
					console.log(res.data.framework)
					that.framework = res.data.framework
				}).catch({

				})
			},
		},
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
</style>
