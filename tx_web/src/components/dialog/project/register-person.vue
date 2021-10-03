<template>
	<div  style="width: 80%;">
		<el-row style="margin: 0;padding: 0;">
			<el-col :span="24">
				<div class="grid-content title-text title-box">管理人员</div>
			</el-col>
		</el-row>
		
		<el-row style="width: 90%;margin-left:5%;">
			<el-col :span="24">
				<div class="grid-content info-title-box">项目部</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">项目经理：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.proManager" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="(item,index) in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">项目总工：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.proChief" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">生产经理：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.produceManager" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
		</el-row>

		<el-row v-if="framework==='三级架构' " style="width: 90%;margin-left:5%;">
			<el-col :span="24">
				<div class="grid-content info-title-box">分公司</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">总工程师：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.chiefEngineer" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">部门经理：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.qualityManBranch" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">片区负责人：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.areaLead" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
		</el-row>
		
		<el-row style="width: 90%;margin-left:5%;">
			<el-col :span="24">
				<div class="grid-content info-title-box">总公司</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">质量经理：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.qualityManHead" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">技术主管：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.technicalLead" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
			<el-col :span="6" class="px-2">
				<div class="grid-content info-label title-box">质量检查员：</div>
			</el-col>
			<el-col :span="18" class="px-2">
				<div class="grid-content info-label title-box">
					<el-select v-model="presonList.quailtyInspector" filterable placeholder="请选择" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
		</el-row>
		
		<el-row style="width: 90%;margin-left:5%;">
			<el-col :span="24">
				<div class="grid-content info-title-box">管理人员</div>
			</el-col>
			<!-- 可动态添加以下节点 -->
			<div v-for="(item,index) in nodeList" :key="index">
				<div ref="div">
					<el-col :span="6" class="px-2">
						<div class="grid-content info-label title-box">部门归属：</div>
					</el-col>
					<el-col :span="7" class="px-2">
						<div class="grid-content info-label title-box">
							<!-- <el-input placeholder="管理人员" :value="item.adminPerson" v-model="item.adminPerson"></el-input> -->
							<el-select v-model="item.adminPerson" filterable placeholder="请选择" style="width: 100%;" @change="selectUser">
								<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
								</el-option>
							</el-select>
						</div>
					</el-col>
					<el-col :span="24" v-show="isShow">
						<div class="grid-content info-label title-box">
							<el-input placeholder="用户id" :value="item.userId" v-model="item.userId"></el-input>
						</div>
					</el-col>
					<el-col :span="4">
						<div class="grid-content info-label title-box">
							<el-input placeholder="岗位" :value="item.post" v-model="item.post"></el-input>
						</div>
					</el-col>
					<el-col :span="4">
						<div class="grid-content info-label title-box">
							<el-select v-model="item.department" filterable placeholder="请选择" style="width: 100%;">
								<el-option v-for="item2 in item.departList" :key="item2.value" :label="item2.label" :value="item2.value">
								</el-option>
							</el-select>
						</div>
					</el-col>
					<el-col :span="3">
						<div class="grid-content info-label title-box">
							<el-button :value="index"  @click="delNode(index,item.userId)" style="width: 100%;">删除</el-button>
						</div>
					</el-col>
				</div>
		
			</div>
			<el-col :span="6" class="px-3 flex" style="float: left;">
				<el-button type="primary" @click="addNode">增加管理人员</el-button>
			</el-col>
			<el-col :span="4" class="px-5 flex" style="float: right;">
				<el-button type="primary"  @click="submit">提交</el-button>
			</el-col>
		</el-row>
	</div>
</template>

<script>
import {eventBus} from "./QualityInspection/event-bus.js";
	export default {
		name: 'register-person',
		props:{
			framework:String,
			registerBaseData:Object
		},
		data() {
			return {
				loading: false,
				nodeList: [{
					adminPerson: '',
					userId:'',
					post: '',
					isHidden:false,
					department:'',
					departList:[{value:'项目部'},{value:'分公司'},{value:'总公司'}]
				}],
				options: [],
				presonList:{
					proManager: '',//项目经理
					proChief:'',//项目总工
					produceManager:'',//生产经理
					
					chiefEngineer:'',//总工程师
					qualityManBranch:'',//部门经理
					areaLead:'',//片区负责人
					
					qualityManHead:'',//质量经理
					technicalLead:'',//技术主管
					quailtyInspector:'',//质量检查员
				},
				username:'',
				userphone:'',
				proTimeStamp:'',
				isShow:false
			}
		},
		computed: {
	
		},
		watch:{
			'registerBaseData.timeStamp': 'getTabelValueReset'
		},
		mounted() {
			this.getUser()
		},
		methods: {
			//获取所有用户
			getUser(){
				const that = this
				let fd = new FormData()
				fd.append('flag','getUser')
				that.$axios.post(that.$adminUrl +`/project/detail.php`,fd).then(res => {
					// console.log(res.data.data.length)
					let length = res.data.data.length
					for(let i =0;i<length;i++){
						this.options.push({
							value:res.data.data[i].name+"/"+res.data.data[i].phone+"/"+res.data.data[i].companyName+"/"+res.data.data[i].id,
							label:res.data.data[i].name+"/"+res.data.data[i].phone+"/"+res.data.data[i].companyName,
							userId:res.data.data[i].id
						})
					}
				}).catch({
					
				})
			},
			//
			getTabelValueReset(){
				this.proTimeStamp = this.registerBaseData.timeStamp
				// console.log(this.proTimeStamp)
				if(this.proTimeStamp===undefined){
					return
				}
				this.getAdminPerson(this.proTimeStamp)				
			},
			//获取工程管理人员
			getAdminPerson(proTimeStamp){
				const that = this
				let fd = new FormData()
				fd.append('flag','getAdminPerson')
				fd.append('timeStamp',proTimeStamp)
				that.$axios.post(that.$adminUrl +`/project/detail.php`,fd).then(res => {
					console.log(res.data)
					that.nodeList = res.data.nodeList.data
					if(res.data.data.length !== 0){
						that.presonList = res.data.data[0]
					}else{
						that.presonList = {
							proManager: '',//项目经理
							proChief:'',//项目总工
							produceManager:'',//生产经理
							
							chiefEngineer:'',//总工程师
							qualityManBranch:'',//部门经理
							areaLead:'',//片区负责人
							
							qualityManHead:'',//质量经理
							technicalLead:'',//技术主管
							quailtyInspector:'',//质量检查员
						}
					}
				}).catch({
					
				})
			},
			//获取选中人
			selectUser(e){
				console.log(this.nodeList.length-1)
				let obj = {}
				obj = this.options.find(item=>{
					return item.value === e
				})
				// console.log(obj.userId)
				this.nodeList[this.nodeList.length-1].userId = obj.userId
			},
			handleClick(tab, event) {
				console.log(tab, event);
			},
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			//添加管理人员
			addNode() {
				if(this.nodeList===undefined){
					this.nodeList = [{
						adminPerson: '',
						userId:'',
						post: '',
						isHidden:false,
						department:'',
						departList:[{value:'项目部'},{value:'分公司'},{value:'总公司'}]
					}]
				}else{
					this.nodeList.push({
						adminPerson: '',
						userId:'',
						post: '',
						isHidden:false,
						department:'',
						departList:[{value:'项目部'},{value:'分公司'},{value:'总公司'}]
					})
				}
			},
			//删除
			delNode(index,id) {
				console.log(id)
				const that = this
				let fd = new FormData()
				fd.append('flag','delPerson')
				fd.append('userId',id)
				that.$axios.post(that.$adminUrl+`/project/detail.php`,fd).then(res=>{
					console.log(res)
					that.$message.success('删除成功！')
					this.nodeList.splice(index, 1) // 点击调用删除方法，在nodeList数组里删除index这个下标项，逗号后面的1就代表删除当前的1项
				}).catch({
					
				})
			},
			//提交一条人员管理
			submit(){
				const that = this
				let fd = new FormData()
				fd.append('flag','saveAdminPerson')
				fd.append('timeStamp',that.registerBaseData.timeStamp)
				fd.append('nodeList',JSON.stringify(that.nodeList))
				fd.append('presonList',JSON.stringify(that.presonList))
				// console.log(JSON.stringify(that.presonList))
				that.$axios.post(that.$adminUrl+`/project/detail.php`,fd).then(res=>{
					console.log(res)
					that.$message.success('提交成功！')
					console.log(that.$EventBus)
					sessionStorage.setItem('nodeList',JSON.stringify(that.nodeList))
					that.$EventBus.$emit("SubmittedManagement", {
						nodeList:that.nodeList,
					});
					// this.$router.push({
					// 	path: '../QualityInspection/blank',
					// 	name: 'blank'
					// })
					// location.reload()
					// this.reload()
				}).catch({
					
				})
				
			}
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
		font-size: 14px;
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
	.sub_btn{
		display: none;
	}
</style>
