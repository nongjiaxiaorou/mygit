<template>
    <div>
		<el-dialog title="统计详情" :visible.sync="dialogProSta.show">
			<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                <!-- 红线逾期页面 -->
                <el-tab-pane label="红线逾期" name="first">
                    aa
                </el-tab-pane> 
                <!-- 红线问题页面 -->
                <el-tab-pane label="红线问题" name="second">
                    bb
                </el-tab-pane>
                <!-- 普通逾期页面 -->
                <el-tab-pane label="普通逾期" name="third">
                    cc
                </el-tab-pane>
                <!-- 普通隐患页面 -->
                <el-tab-pane label="普通隐患" name="fourth">
                    dd
                </el-tab-pane>
                <!-- 项目合格率页面 -->
                <el-tab-pane label="项目合格率" name="fifth">
                    <el-table :data="tableData" style="width: 100%" stripe="true" >
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
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: {
        dialogProSta:Object,
    },
    data() {
        return {
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
          }]
        }
    },
}
</script>