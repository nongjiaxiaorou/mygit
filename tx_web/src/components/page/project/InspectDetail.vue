<template>
	<div id="ProDetail" class="basic container flex justify-center">
		<div style="flex: 0.3;" id="leftBox">
			<el-row class="tac" style="width: 130%;">
				<el-col :span="12">
					<el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose">
						<el-menu-item index="1-1">
						    <span slot="title">质量巡查</span>
						  </el-menu-item>
						<el-submenu index="1-2">
							<template slot="title" @click="test">质量报告</template>
							<el-menu-item index="1-2-1" @click="test">质量日报</el-menu-item>
						</el-submenu>
						<el-submenu index="1">
						    <template slot="title">
						      <span slot="title">巡查实测</span>
						    </template>
						    <el-submenu index="1-3">
						      <span slot="title">建筑工程</span>
						      <el-menu-item index="1-3-1">混凝土结构工程</el-menu-item>
						    </el-submenu>
							<el-submenu index="1-4">
							  <span slot="title">装饰工程</span>
							  <el-menu-item index="1-4-1">地面饰面砖工程</el-menu-item>
							</el-submenu>
						  </el-submenu>
					</el-menu>
				</el-col>
			</el-row>
		</div>
		<div id="rightBox" style="flex: 0.7;margin-right: 8%;">
			<el-row v-if="">
				<el-col :span="24">
					<div class="grid-content title-text title-box">质量巡查</div>
				</el-col>
			</el-row>
		</div>
	</div>
</template>
<script>
	import registerBase from '../../dialog/project/register-base'
	import registerPerson from '../../dialog/project/register-person'
	import registerUnit from '../../dialog/project/register-unit'
	import registerStandard from '../../dialog/project/register-standard'
	import inspectionZone from '../../dialog/project/inspection-zone'
	export default {
		name: 'basetable',
		components: {
			registerBase,
			registerPerson,
			registerUnit,
			registerStandard,
			inspectionZone
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
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			test(val){
				console.log(val.index)
			},
			handleOpen(key, keyPath) {
			        console.log(key, keyPath);
			      },
			      handleClose(key, keyPath) {
			        console.log(key, keyPath);
			      }
		},
	};
</script>
<style scoped>
	
</style>
