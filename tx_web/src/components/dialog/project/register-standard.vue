<template>
	<div>
		<el-row style="width: 80%;">
			<el-col :span="24">
				<div class="grid-content title-text title-box">实测标准</div>
			</el-col>
			<el-col :span="4" class="px-1">
				<div class="grid-content info-label title-box">标准选择：</div>
			</el-col>
			<el-col :span="18">
				<div class="grid-content title-text title-box" style="width: 30%;">
					<el-input
						:disabled="true" v-model="currentStandard">
					</el-input>
				</div>
				<div class="grid-content title-text title-box" style="width: 30%;">
					<el-select v-model="value" placeholder="请选择标准" @change="updateStandardTable">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</div>
			</el-col>
		</el-row>
	</div>
</template>

<script>
	export default {
		name: 'register-person',
		data() {
			return {
				options: [{
					value: 'tb_system_standard1',
					label: '国家标准'
				}, {
					value: 'tb_system_standard2',
					label: '实测标准2'
				}, {
					value: 'tb_system_standard3',
					label: '实测标准3'
				}, {
					value: 'tb_system_standard4',
					label: '实测标准4'
				}, {
					value: 'tb_system_standard5',
					label: '实测标准5'
				}],
				value: '',
				currentStandard: ''
			}
		},
		computed: {
	
		},
		mounted() {
			this.getCurrentStandard()
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
			},
			updateStandardTable() {
				var that = this
				var fd = new FormData();
				// console.log(this.form)
				fd.append('flag', 'updateStandardTable');
				var registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				fd.append('timeStamp', registerBaseData.timeStamp);
				fd.append('tableType', this.value);
				this.$axios.post(that.$adminUrl+`/project/unit.php`, fd).then((res) => {
				    // console.log(res.data)
				    if (res.data.code) { 
				        setTimeout(() => {
							this.getCurrentStandard()
				            this.$message.success('更新成功！');
				            this.FormTabelEmpty();
				        }, 500);
				    } else {
				        this.$message.error('当前网络不稳定,请更换网络后重试!')
				    }
				});
			},
			addNode() {
				this.nodeList.push({
					department: '',
					adminPerson: '',
					post: ''
				})
			},
			delNode(index) {
				// console.log(this.nodeList)
				// console.log(this.$refs.div[index])
				this.nodeList.splice(index, 1) // 点击调用删除方法，在nodeList数组里删除index这个下标项，逗号后面的1就代表删除当前的1项
			},
			getCurrentStandard() {
				var that = this
				var fd = new FormData();
				// console.log(this.form)
				fd.append('flag', 'getStandardTable');
				var registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				fd.append('timeStamp', registerBaseData.timeStamp);
				this.$axios.post(that.$adminUrl+`/project/unit.php`, fd).then((res) => {
				    // console.log(res.data)
				    if (res.data.code) {
						if(res.data.data=="tb_standard1"){
							this.currentStandard = "国家标准"
						}else if(res.data.data=="tb_standard2"){
							this.currentStandard = "实测标准2"
						}else if(res.data.data=="tb_standard3"){
							this.currentStandard = "实测标准3"
						}else if(res.data.data=="tb_standard4"){
							this.currentStandard = "实测标准4"
						}else if(res.data.data=="tb_standard5"){
							this.currentStandard = "实测标准5"
						}
				    } else {
				        this.$message.error('当前网络不稳定,请更换网络后重试!')
				    }
				});
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
