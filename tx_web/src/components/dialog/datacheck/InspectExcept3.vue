<template>
    <div class="AddPro">
        <el-dialog title="筛选维度" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogExcept.show">
            <el-form ref="dialogExcept" label-width="100px">
                <div class="flex">
					<el-form-item label="时间选择：" class="flex-1 px-2">
					     <el-date-picker
					     	v-model="value2"
					     	type="daterange"
					     	align="right"
					     	unlink-panels
					     	range-separator="至"
					     	start-placeholder="开始日期"
					     	end-placeholder="结束日期"
					     	:picker-options="pickerOptions">
					     </el-date-picker>
					 </el-form-item>
                </div>
				<div class="flex" v-show="companyLevel=='总公司'">
					<el-form-item label="分公司：" class="">
						<el-select v-model="companyValue" clearable placeholder="请选择" class="select-style" @change="getProjectFunc">
						    <el-option
						      v-for="item in companyOptions"
						      :key="item.value"
						      :label="item.label"
						      :value="item.value">
						    </el-option>
						  </el-select>
					</el-form-item>
				</div>
				<div class="flex" v-show="companyLevel=='总公司'||companyLevel=='分公司'">
					<el-form-item label="选择工程：" class="">
						<el-select v-model="projectValue" clearable placeholder="请选择" class="select-style">
						    <el-option
						      v-for="item in projectOptions"
						      :key="item.value"
						      :label="item.label"
						      :value="item.value">
						    </el-option>
						  </el-select>
					</el-form-item>
				</div>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormClose('dialogExcept')">取 消</el-button>
                <el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
 
<script>
export default {
    props: {
        dialogExcept: Object
    },
    data() {
        return {
            pickerOptions: {
			  shortcuts: [{
				text: '最近一周',
				onClick(picker) {
				  const end = new Date();
				  const start = new Date();
				  start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
				  picker.$emit('pick', [start, end]); 
				}
			  }, { 
				text: '最近一个月',
				onClick(picker) {
				  const end = new Date();
				  const start = new Date();
				  start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
				  picker.$emit('pick', [start, end]);
				}
			  }, {
				text: '最近三个月',
				onClick(picker) {
					const end = new Date();
					const start = new Date();
					start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
					picker.$emit('pick', [start, end]);
				}
			}]
			},
			value2: null,
			projectOptions: [],
			companyValue: '',
			companyOptions: [],
			projectValue: '',
			itemOptions: [],
			itemValue: '',
			companyLevel: '总公司',
			userInfo: {}
        };
    },
    mounted() {
		this.userInfo = JSON.parse(localStorage.getItem('userInfo'))
        this.getCompanyFunc();
		this.getItemFunc();
		if(this.companyLevel=='分公司'){
			this.getCurrentProject()
		}
    },
    methods: {
        getpour(){
			var that = this
            var fd = new FormData();
            // console.log(this.form)
            fd.append('flag', 'selectExcept');
			// console.log(this.projectValue)
			let startTime = this.commonFunc.dateFormat(this.value2[0])
			let endTime = this.commonFunc.dateFormat(this.value2[1])
			fd.append('companyId', this.companyValue); //公司id
			fd.append('projectValue', this.projectValue);
            fd.append('itemValue', this.itemValue);
			fd.append('companyLevel', this.companyLevel);
			fd.append('startTime', startTime);
			fd.append('endTime', endTime);
            // console.log(this.$refs.dialogAdd.validate())
			this.$axios.post(that.$adminUrl+`/datacheck/ConcreteManage/ConcreteManage.php`, fd).then((res) => {
				console.log(res.data)
				if (res.data.code) {  
				    setTimeout(() => {
				        this.$emit('handleSelectChild',res.data,'pour'); //初始化webscoket刷新视图
				        this.$message.success('已成功筛选！');
				    }, 500);
				    this.dialogExcept.show = false;
				} else {
				    this.$message.info('浇筑或旁站暂无数据!')
				}
            })
		},
		getremoval(){
			var that = this
            var fd = new FormData();
            // console.log(this.form)
            fd.append('flag', 'selectExcept1');
			// console.log(this.projectValue)
			let startTime = this.commonFunc.dateFormat(this.value2[0])
			let endTime = this.commonFunc.dateFormat(this.value2[1])
			fd.append('companyId', this.companyValue); //公司id
			fd.append('projectValue', this.projectValue);
            fd.append('itemValue', this.itemValue);
			fd.append('companyLevel', this.companyLevel);
			fd.append('startTime', startTime);
			fd.append('endTime', endTime);
            // console.log(startTime+endTime)
            // console.log(this.$refs.dialogAdd.validate())
			this.$axios.post(that.$adminUrl+`/datacheck/ConcreteManage/ConcreteManage.php`, fd).then((res) => {
				console.log(res.data)
				if (res.data.code) {  
				    setTimeout(() => {
				        this.$emit('handleSelectChild',res.data,'removal'); //初始化webscoket刷新视图
				        this.$message.success('已成功筛选！');
				    }, 500);
				    this.dialogExcept.show = false;
				} else {
				    this.$message.info('拆模记录暂无数据!')
				}
            })
        },
        dialogFormAdd() {
			if(this.value2==null) return this.$message('请选择时间段进行筛选！');
			this.getpour();
			this.getremoval();
        },
        dialogFormClose(dialogExcept) {
            this.dialogExcept.show = false;
        },
		//获取公司
		getCompanyFunc() {
			var that = this
			var fd = new FormData()
			fd.append('flag', 'getCompany')
			this.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
				// console.log(res.data)
				if (res.data.code) {
					this.companyOptions = []
					for(var i=0;i<res.data.data.length;i++){
						this.companyOptions.push({
							id: res.data.data[i].id,
							label: res.data.data[i].companyName,
							value: res.data.data[i].id
						})
					}
				}
			})
		},
		//获取工程
		getProjectFunc() {
			var that = this
			var fd = new FormData()
			fd.append('flag', 'getProject')
			fd.append('companyId', this.companyValue)
			this.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
				// console.log(res.data)
				if (res.data.code) {
					this.projectOptions = []
					for(var i=0;i<res.data.data.length;i++){
						this.projectOptions.push({
							id: res.data.data[i].id,
							label: res.data.data[i].projectName,
							value: res.data.data[i].projectName
						})
					}
				}
			})
		},
		//获取分部工程
		getItemFunc() {
			var that = this
			var fd = new FormData()
			fd.append('flag', 'getItem')
			this.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
				// console.log(res.data)
				if (res.data.code) {
					this.itemOptions = []
					for(var i=0;i<res.data.data.length;i++){
						this.itemOptions.push({
							id: res.data.data[i].id,
							label: res.data.data[i].inspectionItem,
							value: res.data.data[i].inspectionItem
						})
					}
				}
			})
		},
		//获取当前分公司工程
		getCurrentProject() {
			var that = this
			var fd = new FormData()
			fd.append('flag', 'getCurrentProject')
			fd.append('company', this.userInfo.company)
			this.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
				// console.log(res.data)
				if (res.data.code) {
					this.projectOptions = []
					for(var i=0;i<res.data.data.length;i++){
						this.projectOptions.push({
							id: res.data.data[i].id,
							label: res.data.data[i].projectName,
							value: res.data.data[i].projectName
						})
					}
				}
			})
		}
    }
};
</script>
<style>
    .customClass{
        width: 40%;
    }
	
	.select-style {
		width: 350px;
	}
</style>