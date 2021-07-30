<template>
    <div>
        <div style="" id="leftBox">
            <el-tabs :tab-position="tabPosition" @tab-click="handleChange">
                <el-tab-pane :label="item.inspectItem" style="margin-left: 10%;width: 88%;" v-for="item in measuredTypes" :key="item.index">
                    <el-table :data="item.subItem" style="width: 100%" >
                        <el-table-column
                            prop="inspectContent"
                            label="测试类型名称"
                            width="300">
                        </el-table-column>
                        <el-table-column
                            prop="qualityNum"
                            label="合格点数"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="qualifiedPersentage"
                            label="合格率">
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>
<script>
export default {
    name: 'basetable',
    props: {
        registerBaseData: Object
    },
    components: {

    },
    data() {
        return {
            tabPosition:'left',
            buildInfo: {},
            userInfo: {},
            measuredTypes: [
                {
                    inspectItem:'导航一',
                    index: '1',
                    subItem: [{
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }, {
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }, {
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }, {
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }]
                },
                {
                    inspectItem:'导航二',
                    index: '2',
                    subItem: [{
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }]
                },
                {
                    inspectItem:'导航三',
                    index: '3',
                    subItem: [{
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }, {
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }, {
                        inspectContent: '2016-05-02',
                        qualityNum: '王小虎',
                        qualifiedPersentage: '上海市普陀区金沙江路 1518 弄'
                    }]
                }
            ],
        }
    },
    mounted() {
        this.getLocalStorage()
        this.getData()
    },
    methods: {
        handleOpen () {

        },
        handleClose () {

        },
        handleChange(){
            //点击触发子组件方法
            console.log(this.$refs);
            // this.$refs.anagement.getSectionInfo()
        },
        getLocalStorage () {
            const that = this;
            let buildInfo = sessionStorage.getItem('registerBaseData1');
            let userInfo = sessionStorage.getItem('registerBaseData');
            that.buildInfo = JSON.parse(buildInfo)
            that.userInfo = JSON.parse(userInfo)
            console.log(that.buildInfo.id);
            console.log(userInfo);
        },
        getData () {
            const that = this;
            let fd = new FormData() // FormData 对象
            fd.append('flag', 'getProQualifiedPecent') 
            fd.append('proTimeStamp', this.userInfo.timeStamp)
            fd.append('projectId', this.userInfo.projectId)
            fd.append('section', this.buildInfo.section)
            fd.append('build', this.buildInfo.build)
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            this.$axios.post(this.$adminUrl + `/project/projectStatistics.php`, fd, config).then(res => {
                console.log(res.data);
                that.measuredTypes = res.data.data;
                
            })
        }
    },
}
</script>
<style scoped>
.sideBar {
    width: 10%;
}
.bbox {
    display: flex;

}

</style>
