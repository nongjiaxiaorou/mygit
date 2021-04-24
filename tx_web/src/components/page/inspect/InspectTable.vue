<template>
	<div id="ProDetail" class="basic container flex align-center justify-center">
		<div class="flex-1 px-5" style="" id="leftBox">
			<el-tabs :tab-position="tabPosition">
				<el-tab-pane label="巡查小组" style="margin-left: 6%;width: 88%;">
					<InspectGroup></InspectGroup>
				</el-tab-pane>

				<el-tab-pane label="巡查定义" style="margin-left: 6%;width: 88%;">
					<InspectDefine :registerBaseData="registerBaseData"></InspectDefine>
				</el-tab-pane>

				<el-tab-pane label="巡查管理" style="margin-left: 6%;width: 88%;">
					<InspectAdmin></InspectAdmin>
				</el-tab-pane>
			</el-tabs>
		</div>
	</div>
</template>
<script>
	import InspectGroup from '../../dialog/inspect/InspectGroup.vue'
	import InspectAdmin from '../../dialog/inspect/InspectAdmin.vue'
	import InspectDefine from '../../dialog/inspect/InspectDefine.vue'
	export default {
		name: 'basetable',
		components: {
			InspectGroup,
			InspectAdmin,
			InspectDefine
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
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('registerBaseData')
				this.registerBaseData = JSON.parse(sessionData)
				// console.log(this.registerBaseData)
			},
			//点击切换页签
			handleClick(tab, event) {
				// console.log(tab.label, event);
				// if(tab.label=='基本信息'){
				// console.log(this.framework)
				// 	console.log(this.$route.params)
				// }
				// console.log(this.registerBaseDate)
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
