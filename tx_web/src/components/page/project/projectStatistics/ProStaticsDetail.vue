<template>
    <div>
        <el-tabs id="ProStaticsDetail" :tab-position="tabPosition" @tab-click="handleChange" class="basic container align-center ">
            <el-tabs type="card" @tab-click="handleClick">
                <!-- 一般隐患逾期页面 -->
                <el-tab-pane label="一般隐患逾期" name="first">
                   <usualProblemLake></usualProblemLake>
                </el-tab-pane> 
                <!-- 重大隐患逾期页面 -->
                <el-tab-pane label="重大隐患逾期" name="second">
                    <big-problem-lake  ></big-problem-lake>
                </el-tab-pane>
                <!-- 一般隐患页面 -->
                <el-tab-pane label="一般隐患" name="third">
                    <usual-problem  ></usual-problem>
                </el-tab-pane>
                <!-- 重大隐患页面 -->
                <el-tab-pane label="重大隐患" name="fourth">
                    <big-problem  ></big-problem>
                </el-tab-pane>
                <!-- 项目合格率页面 -->
                <el-tab-pane label="项目合格率" name="fifth">
                    <!-- <el-table :data="tableData" style="width: 100%">
                        <el-table-column
                            prop="testFormName"
                            label="测试类型名称"
                            width="300">
                        </el-table-column>
                        <el-table-column
                            prop="QualifiedPoints"
                            label="合格点数"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="PassPercent"
                            label="合格率">
                        </el-table-column>       
                    </el-table> -->
                    <projectYeild></projectYeild>
                    
                </el-tab-pane>
            </el-tabs>
        </el-tabs>
    </div>
</template>
<script>
import usualProblem from './usualProblem'
import usualProblemLake from './usualProblemLate'
import bigProblem from './bigProblem'
import bigProblemLake from './bigProblemLake'
import projectYeild from './projectYeild'



export default {
    props: {
        // dialogProSta:Object,
    },
    components: {
        usualProblemLake,
        bigProblemLake,
        usualProblem,
        bigProblem,
        projectYeild
    },
    data() {
        return {
            tabPosition: 'left',
            tableData: [{
                testFormName: '2016-05-02',
                QualifiedPoints: '王小虎',
                PassPercent: '上海市普陀区金沙江路 1518 弄'
            }, {
                testFormName: '2016-05-02',
                QualifiedPoints: '王小虎',
                PassPercent: '上海市普陀区金沙江路 1518 弄'
            }, {
                testFormName: '2016-05-02',
                QualifiedPoints: '王小虎',
                PassPercent: '上海市普陀区金沙江路 1518 弄'
            }, {
                testFormName: '2016-05-02',
                QualifiedPoints: '王小虎',
                PassPercent: '上海市普陀区金沙江路 1518 弄'
          }],
        }
    },
    mounted () {
        // getData ()    
        // let registerBaseData = sessionStorage.getItem('registerBaseData')
        // this.registerBaseData = JSON.parse(registerBaseData)
        // console.log(this.registerBaseData);
    },
    methods: {
        getData () {
            
        },
        handleChange(){
            //点击触发子组件方法
            console.log(this.$refs);
            this.$refs.anagement.getSectionInfo()
        },
        handleClick(tab, event) {
            // console.log(tab);
            // console.log(event);
        },
    }
}
</script>