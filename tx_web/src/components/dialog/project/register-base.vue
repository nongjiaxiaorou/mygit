<template>
	<div :key="registerBaseData.timeStamp">
		<el-row style="width: 80%;">
			<el-col :span="24">
				<div class="grid-content title-text title-box">基本信息</div>
			</el-col>
			<div style="width: 90%;margin-left: 5%;">
				<el-col :span="6">
					<div class="grid-content info-label title-box">工程名称：</div>
				</el-col>
				<el-col :span="18">
					<div class="grid-content title-text title-box">
						<el-input v-model="registerBaseData.projectName" readonly></el-input>
					</div>
				</el-col>

				<el-col :span="4">
					<div class="grid-content info-label title-box">工程类别：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-input v-model="registerBaseData.categories"></el-input>
					</div>
				</el-col>
				<el-col :span="4">
					<div class="grid-content info-label title-box">所属公司：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-input v-model="registerBaseData.company"></el-input>
					</div>
				</el-col>

				<el-col :span="4">
					<div class="grid-content info-label title-box">地区省：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-input v-model="registerBaseData.province"></el-input>
					</div>
				</el-col>
				<el-col :span="4">
					<div class="grid-content info-label title-box">地区市：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-input v-model="registerBaseData.city"></el-input>
					</div>
				</el-col>

				<el-col :span="6">
					<div class="grid-content info-label title-box">工程位置：</div>
				</el-col>
				<el-col :span="18">
					<div class="grid-content title-text title-box">
						<el-input v-model="registerBaseData.proPosition"></el-input>
					</div>
				</el-col>

				<el-col :span="6">
					<div class="grid-content info-label title-box">建筑面积(㎡)：</div>
				</el-col>
				<el-col :span="18">
					<div class="grid-content info-label title-box">
						<el-input v-model="registerBaseData.architecture"></el-input>
					</div>
				</el-col>
				
				<el-col :span="4">
					<div class="grid-content info-label title-box">开工日期：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-date-picker
							v-model="registerBaseData.startTime"
							type="datetime"
							style="width: 100%;"
							placeholder="选择日期时间">
						</el-date-picker>
					</div>
				</el-col>
				<el-col :span="4">
					<div class="grid-content info-label title-box">竣工日期：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-date-picker
							v-model="registerBaseData.completedTime"
							type="datetime"
							style="width: 100%;"
							placeholder="选择日期时间">
						</el-date-picker>
						<!-- <el-input v-model="registerBaseData.completedTime"></el-input> -->
					</div>
				</el-col>
				
				<el-col :span="4">
					<div class="grid-content info-label title-box">区段：</div>
				</el-col>
				<el-col :span="8">
					<div class="grid-content info-label title-box">
						<el-button style="width: 100%;height: 32px;color: #a9a5a0;" @click="setZone" v-if="this.definedColor">进入区段设置</el-button>
						<el-button type="primary" style="width: 100%;height: 32px;" @click="setZone" v-if="this.undefinedColor">进入区段设置</el-button>
					</div>
				</el-col>
				
				<el-col :span="24">
					<div class="grid-content info-label">
						<el-button type="primary" style="float: right;width: 10%;" @click="submit">提交</el-button>
					</div>
				</el-col>
			</div>
		</el-row>
		<SetProZone :dialogSet="dialogSet" :form="form" @func="getMsgFormSon"></SetProZone>
	</div>
</template>

<script>
	import SetProZone from '../../dialog/project/SetProZone';
	export default {
		props:{
			// registerBaseData:Object
		},
		components:{
			SetProZone
		},
		data(){
			return{
				dialogSet: {
					show: false
				},
				form:{},
				color: '',
				definedColor: '',
				undefinedColor: '',
				registerBaseData: [],
			}
		},
		mounted() {
			let registerBaseData = sessionStorage.getItem('registerBaseData')
            this.registerBaseData = JSON.parse(registerBaseData)
			this.getMsgFormSon()
		},
		methods : {
			getMsgFormSon(data){
				this.color = data
				console.log(this.color)
				console.log(1111)
				if (this.color == 'bule') {
					this.definedColor = false
					this.undefinedColor = true
				}
				else {
					this.definedColor = true
					this.undefinedColor = false
				}
				console.log(this.definedColor)
				console.log(this.undefinedColor)
			},
			
			// buttonColor(){
			// 	if (this.color == 'bule') {
			// 		this.definedColor = false
			// 		this.undefinedColor = true
			// 	}
			// 	else {
			// 		this.definedColor = true
			// 		this.undefinedColor = false
			// 	}
			// 	console.log(this.definedColor)
			// 	console.log(this.undefinedColor)
			// },

			//区段设置
			setZone(){
				//打开模态框
				this.dialogSet.show = true
				this.form = {
					proTimeStamp : this.registerBaseData.timeStamp,
					projectName : this.registerBaseData.projectName,
				}
				this.getMsgFormSon()
				console.log(this.color)
			},
			//提交
			submit(){
				const that = this
				let fd = new FormData()
				fd.append('flag','changeBaseInfo')
				fd.append('timeStamp',that.registerBaseData.timeStamp)
				fd.append('categories',that.registerBaseData.categories)
				fd.append('company',that.registerBaseData.company)
				fd.append('province',that.registerBaseData.province)
				fd.append('city',that.registerBaseData.city)
				fd.append('proPosition',that.registerBaseData.proPosition)
				fd.append('architecture',that.registerBaseData.architecture)
				fd.append('startTime',that.commonFunc.dateFormat(that.registerBaseData.startTime))
				fd.append('completedTime',that.commonFunc.dateFormat(that.registerBaseData.completedTime))
				
				that.$axios.post(that.$adminUrl+`/project/detail.php`,fd).then(res=>{
					console.log(res)
					if(res.data.states == 'success'){
						alert('提交成功')
					}
				}).catch({
					
				})
			}
		}
	}
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
</style>
