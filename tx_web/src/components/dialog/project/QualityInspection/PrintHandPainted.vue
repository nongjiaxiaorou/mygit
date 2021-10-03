<template>
    <div style="flex:1;display:flex=1" class="basic1 container">
        <div  ref="print">
            <h1 style="text-align: center;padding-top:10px;padding-bottom:20px">项目实测表</h1>
            <div>项目名称：{{formDate.projectName}} </div>
            <div>实测项目：{{formDate.categories}} / {{formDate.inspectPosition}} / {{formDate.inspectItem}} </div>
            <el-table  border class="table" ref="multipleTable" header-cell-class-name="table-header" 
            :data="tableData">
                <el-table-column prop="measurePointName" label="实测项" align="center"></el-table-column>
                <el-table-column prop="measurePointNumber" label="测点" align="center"></el-table-column>
                <el-table-column prop="minStandardValue" label="最小标准值" align="center"></el-table-column>
                <el-table-column prop="maxStandardValue" label="最大标准值" align="center"></el-table-column>
                <el-table-column prop="pointInitialValue" label="初测值" align="center"></el-table-column>
                <el-table-column prop="pointRetestValue" label="复测值" align="center"></el-table-column>
                <el-table-column prop="pointFinaltestValue" label="终测值" align="center"></el-table-column>
            </el-table>
            <div class="text">{{this.$route.params.text}}</div>
            <div class="picture">   
                <img :src="this.$route.params.PicSrc" alt="" style="height:500px">
            </div>
        </div>
        <el-button type="primary" size="medium" @click="prints">打印</el-button> 
    </div>			
</template>

<script>
	export default {
        name: 'PrintHandPainted',
		data(){
			return{

				formDate:{
					inspectDate:'',
					inspectItem:'',
					inspectPerson:'',
                    inspectPosition:'',
                    id:'',
                    timeStamp: '',
                    categories: '',
                    projectName: '',
                },
                tableData: [],
			}
        },
        
        mounted() { 
            let sessionData = sessionStorage.getItem('itemInspectInformation')
            sessionData = JSON.parse(sessionData)
            let sessionData1 = sessionStorage.getItem('registerBaseData')
            sessionData1 = JSON.parse(sessionData1)
            this.formDate.id = sessionData.id
            this.formDate.inspectDate = sessionData.inspectDate
            this.formDate.inspectItem = sessionData.inspectItem
            this.formDate.inspectPerson = sessionData.inspectPerson
            this.formDate.inspectPosition = sessionData.inspectPosition
            this.formDate.timeStamp = sessionData.timeStamp
            this.formDate.categories = sessionData1.categories
            this.formDate.projectName = sessionData1.projectName
            console.log(this.formDate.id)
            this.getTableData()
        },

		methods:{

            prints () {
                this.$print(this.$refs.print)
            },

			getTableData() {
                const that = this              
                let fd = new FormData() 
                fd.append('flag', 'getTableData')
                fd.append('id',that.formDate.id)
                fd.append('inspectItem',that.formDate.inspectItem)
                that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
                    console.log(res.data.data)                                       
                    that.tableData = res.data.data
                    console.log(that.tableData)
                }).catch(() => {
                    console.error("获取失败")
                }) 
            }
			
		}
	}
</script>

<style>
    .basic1{
		margin: 2%;
		padding: 2%;
		padding-left: 10%;		
	}
    
	.table {
        margin:auto;
		width: 100%;
		font-size: 14px;
	}

    .picture {
        text-align:center;
        margin: 20px 0;
    }

    .text {
        margin: 30px 80px;
    }
</style>