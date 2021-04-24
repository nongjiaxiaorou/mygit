<template>
	<div>
		<el-row :gutter="20">
			<el-col :span="8">
				<el-card shadow="hover" class="mgb20" style="height:252px;">
					<div class="user-info">
						<img src="../../../assets/img/photo.jpg" class="user-avator" alt />
						<div class="user-info-cont">
							<div class="user-info-name">{{name}}</div>
							<div>{{role}}</div>
						</div>
					</div>
					<div class="user-info-list">
						上次登录时间：
						<span>2020-12-25</span>
					</div>
					<div class="user-info-list">
						上次登录地点：
						<span>广东</span>
					</div>
				</el-card>
				<el-card shadow="hover" style="height:252px;">
					<div slot="header" class="clearfix">
						<span>重要指标</span>
					</div>
					一般隐患
					<el-progress :percentage="noticeData.generalProblem" color="#42b983"></el-progress>
					重大隐患
					<el-progress :percentage="noticeData.bigProblem" color="#f1e05a"></el-progress>
					每周巡检
					<el-progress :percentage="noticeData.sceneTotal"></el-progress>
					整改闭合
					<el-progress :percentage="noticeData.rectifyClose" color="#f56c6c"></el-progress>
				</el-card>
			</el-col>
			<el-col :span="16">
				<el-row :gutter="20" class="mgb20">
					<el-col :span="8">
						<el-card shadow="hover" :body-style="{padding: '0px'}">
							<div class="grid-content grid-con-1">
								<i class="el-icon-lx-notice grid-con-icon"></i>
								<div class="grid-cont-right">
									<div class="grid-num">{{noticeData.notice}}</div>
									<!-- <div class="grid-num">3</div> -->
									<div>通知待办</div>
								</div>
							</div>
						</el-card>
					</el-col>
					<el-col :span="8">
						<el-card shadow="hover" :body-style="{padding: '0px'}">
							<div class="grid-content grid-con-3">
								<i class="el-icon-warning grid-con-icon"></i>
								<div class="grid-cont-right">
									<div class="grid-num">{{noticeData.warning}}</div>
									<!-- <div class="grid-num">2</div> -->
									<div>预警消息</div>
								</div>
							</div>
						</el-card>
					</el-col>
					<el-col :span="8">
						<el-card shadow="hover" :body-style="{padding: '0px'}">
							<div class="grid-content grid-con-2">
								<i class="el-icon-folder-opened
 grid-con-icon"></i>
								<div class="grid-cont-right">
									<div class="grid-num">{{noticeData.projectNum}}</div>
									<!-- <div class="grid-num">1</div> -->
									<div>项目数量</div>
								</div>
							</div>
						</el-card>
					</el-col>
				</el-row>
				<el-card shadow="hover" style="height:403px;">
					<div slot="header" class="clearfix">
						<span>部门公告</span>
						<el-button style="float: right; padding: 3px 0" type="text" @click="add">添加</el-button>
					</div>
					<el-table :show-header="false" :data="announceList" style="width:100%;" :height="this.commonFunc.screeHeight*0.8">
						<el-table-column>
							<template slot-scope="scope">
								<div class="todo-item">
									{{scope.row.title}}：
								</div>
								<div class="todo-item" style="margin: 10px 0 0 0;">{{scope.row.content}}</div>
							</template>
						</el-table-column>
						<el-table-column width="40">
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="editAnnounce(scope.$index, scope.row)"></el-button>
							</template>
						</el-table-column>
						<el-table-column width="50">
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-delete" class="red" @click="deleteAnnounce(scope.$index, scope.row)"></el-button>
							</template>
						</el-table-column>
					</el-table>
				</el-card>
			</el-col>
		</el-row>
		<el-row :gutter="20">
			<el-col :span="12">
				<el-card shadow="hover">
					<schart ref="bar" class="schart" canvasId="bar" :options="options"></schart>
				</el-card>
			</el-col>
			<el-col :span="12">
				<el-card shadow="hover">
					<schart ref="line" class="schart" canvasId="line" :options="options2"></schart>
				</el-card>
			</el-col>
		</el-row>
		<AddAnnouncement :dialogAdd="dialogAdd" @update = 'update'></AddAnnouncement>
		<EditAnnouncement :dialogEdit="dialogEdit" :form='form' @update = 'update'></EditAnnouncement>
	</div>
</template>

<script>
	import Schart from 'vue-schart';
	import AddAnnouncement from '@/components/dialog/index/AddAnnouncement';
	import EditAnnouncement from '@/components/dialog/index/EditAnnouncement';
	import moment from 'moment';
	export default {
		name: 'dashboard',
		components: {
			Schart,
			AddAnnouncement,
			EditAnnouncement
		},

		data() {
			return {
				name: localStorage.getItem('ms_username'), 
				dialogAdd:{
					show:false
				},
				dialogEdit:{
					show:false
				},
				announceList: [],
				noticeData: {},
				data: [{
						name: '2018/09/04',
						value: 1083
					},
					{
						name: '2018/09/05',
						value: 941
					},
					{
						name: '2018/09/06',
						value: 1139
					},
					{
						name: '2018/09/07',
						value: 816
					},
					{
						name: '2018/09/08',
						value: 327
					},
					{
						name: '2018/09/09',
						value: 228
					},
					{
						name: '2018/09/10',
						value: 1065
					}
				],
				options: {
					type: 'bar',
					title: {
						text: '最近七天安全隐患图'
					},
					xRorate: 25,
					labels: [moment().subtract(6,'day').format('YY-MM-DD'),moment().subtract(5,'day').format('YY-MM-DD'),moment().subtract(4,'day').format('YY-MM-DD'),moment().subtract(3,'day').format('YY-MM-DD'),moment().subtract(2,'day').format('YY-MM-DD'),moment().subtract(1,'day').format('YY-MM-DD'),moment().subtract(0,'day').format('YY-MM-DD')],
					datasets: [{
							label: '一般隐患',
							data: [3, 7, 7, 0, 3,5,4]
						},
						{
							label: '重大隐患',
							data: [4, 8, 1, 9, 6,8,0]
						},
						{
							label: '整改闭合',
							data: [1, 9, 1, 3, 2,1,7]
						}
					]
				},
				options2: {
					type: 'line',
					title: {
						text: '最近几个月安全隐患趋势图'
					},
					labels: [moment().subtract(3,'month').format('YYYY-MM'),moment().subtract(2,'month').format('YYYY-MM'),moment().subtract(1,'month').format('YYYY-MM'),moment().subtract(0,'month').format('YYYY-MM')], //最近四个月
					datasets: [{
							label: '一般隐患',
							data: [23, 27, 27, 19, 23]
						},
						{
							label: '重大隐患',
							data: [16, 17, 15, 13, 16]
						},
						{
							label: '整改闭合',
							data: [14, 11, 20, 23, 9]
						}
					]
				},
				form:{}
			};
		},
		
		computed: {
			role() {
				return this.name === 'admin' ? '超级管理员' : '普通用户';
			},
		},
		mounted() {
			this.getAnnounce();
			this.getNoticeData();
			this.getChartData();
			// console.log(moment().subtract('year').format('YY'));
		},
		// created() {
		//     this.handleListener();
		//     this.changeDate();
		// },
		// activated() {
		//     this.handleListener();
		// },
		// deactivated() {
		//     window.removeEventListener('resize', this.renderChart);
		//     bus.$off('collapse', this.handleBus);
		// },
		methods: {
			//获取数据看板
			getNoticeData () {
				const that = this;				
				const userInfo = JSON.parse(localStorage.getItem('userInfo'));
				// console.log(userInfo.userId);
				var fd = new FormData();
				fd.append('flag', 'getNotice');
				fd.append('userId',userInfo.userId)
				that.$axios.post(that.$adminUrl + `/index/index.php`, fd).then((res) => {
					this.noticeData = res.data;	
					console.log(this.noticeData);	
				}).catch(() => {
							
				})
			},
			// 获取图表数据
			getChartData () {
				const that = this;
				let fd = new FormData();
				fd.append('flag','getChart');
				that.$axios.post(that.$adminUrl + `/index/index.php`, fd).then((res) => {
					that.options2.datasets[2].data = res.data.charRectifyClose_MM.reverse();
					that.options2.datasets[1].data = res.data.charBigProblem_MM.reverse();
					that.options2.datasets[0].data = res.data.charProblem_MM.reverse();
					that.options.datasets[2].data = res.data.charRectifyClose_DD.reverse();
					that.options.datasets[1].data = res.data.charBigProblem_DD.reverse();
					that.options.datasets[0].data = res.data.charProblem_DD.reverse();
					console.log(res.data);
					// console.log(res.data.month);	
				}).catch(() => {
							
				})
			},
			//获取部门公告
			getAnnounce(){
				const that = this
				var fd = new FormData();
				fd.append('flag', 'getAnnounce');
				that.$axios.post(that.$adminUrl + `/index/index.php`, fd).then((res) => {
					// console.log(res.data.data);
					this.announceList = res.data.data;
				}).catch(() => {
							
				})
			},
			changeDate() {
				const now = new Date().getTime();
				this.data.forEach((item, index) => {
					const date = new Date(now - (6 - index) * 86400000);
					item.name = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`;
				});
			},
			//新增部门公告
			add() {
				this.dialogAdd.show = true
			},
			//新增成功刷新页面
			update() {
				this.getAnnounce()
			},
			//修改部门公告
			editAnnounce(index,row){
				this.dialogEdit.show = true
				this.form = row
			},
			//删除部门公告
			deleteAnnounce(index,row){
				const that = this
				that.$confirm('删除之后将无法恢复, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					var fd = new FormData();
					fd.append('flag', 'deleteAnnounce');
					fd.append('id', row.id);
					that.$axios.post(that.$adminUrl + `/index/index.php`, fd).then((res) => {
						// console.log(res.data.data)
						that.announceList = res.data.data
						that.update()
						that.$message({type: 'success',message: '删除成功!'});
					}).catch(() => {
								
					})
				}).catch(() => {
					that.$message({
						type: 'info',
						message: '已取消删除'
					});
				});
			},
		}
	};
</script>


<style scoped>
	.red {
		color: #ff0000;
	}
	
	.el-row {
		margin-bottom: 20px;
	}

	.grid-content {
		display: flex;
		align-items: center;
		height: 100px;
	}

	.grid-cont-right {
		flex: 1;
		text-align: center;
		font-size: 14px;
		color: #999;
	}

	.grid-num {
		font-size: 30px;
		font-weight: bold;
	}

	.grid-con-icon {
		font-size: 50px;
		width: 100px;
		height: 100px;
		text-align: center;
		line-height: 100px;
		color: #fff;
	}

	.grid-con-1 .grid-con-icon {
		background: rgb(45, 140, 240);
	}

	.grid-con-1 .grid-num {
		color: rgb(45, 140, 240);
	}

	.grid-con-2 .grid-con-icon {
		background: rgb(100, 213, 114);
	}

	.grid-con-2 .grid-num {
		color: rgb(45, 140, 240);
	}

	.grid-con-3 .grid-con-icon {
		background: rgb(242, 94, 67);
	}

	.grid-con-3 .grid-num {
		color: rgb(242, 94, 67);
	}

	.user-info {
		display: flex;
		align-items: center;
		padding-bottom: 20px;
		border-bottom: 2px solid #ccc;
		margin-bottom: 20px;
	}

	.user-avator {
		width: 120px;
		height: 120px;
		border-radius: 50%;
	}

	.user-info-cont {
		padding-left: 50px;
		flex: 1;
		font-size: 14px;
		color: #999;
	}

	.user-info-cont div:first-child {
		font-size: 30px;
		color: #222;
	}

	.user-info-list {
		font-size: 14px;
		color: #999;
		line-height: 25px;
	}

	.user-info-list span {
		margin-left: 70px;
	}

	.mgb20 {
		margin-bottom: 20px;
	}

	.todo-item {
		font-size: 14px;
	}

	.todo-item-del {
		text-decoration: line-through;
		color: #999;
	}

	.schart {
		width: 100%;
		height: 300px;
	}
</style>
