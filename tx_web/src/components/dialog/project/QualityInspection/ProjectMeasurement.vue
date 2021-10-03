<template>
	<div class="basic" style="flex:1;display:flex=1">
		<div class="container" id="userManage1"> 
			<div class="boxFather" style="" id="leftBox">
				<el-row class="tac">
					<el-col :span="4">
						<el-menu
						default-active="2"
						class="el-menu-vertical-demo"
						@open="handleOpen"
						@close="handleClose">
							<el-submenu index="1">
								<template slot="title">
									<span>项目实测</span>
								</template>
							</el-submenu>
							<el-submenu index="2">
								<template slot="title">
									<span>项目排查</span>
								</template>
							</el-submenu>
							<el-submenu index="3">
								<template slot="title">
									<span>项目验收</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="3" @click.native="handleQualityInspections">实名制工序验收</el-menu-item>
									<el-menu-item index="4" @click.native="handleInspectMeasured">工序交接验收</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
						</el-menu>
					</el-col>
				</el-row>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '1'">
					<div class="grid-content title-text title-box">项目实测/{{projectData.projectName}}/{{inspectPosition0}}</div>
					<div class="handle-box">
						<el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
					</div>
					<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
					:data="table">
						<el-table-column type="selection"></el-table-column>
						<!-- <el-table-column prop="id" label="编号" align="center"></el-table-column> -->
						<el-table-column prop="inspectPosition" label="标题名称" align="center"></el-table-column>
						<el-table-column prop="inspectItem" label="检查项目" align="center"></el-table-column>
						<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
						<el-table-column prop="inspectPerson" label="检查人员" align="center"></el-table-column>
						<el-table-column label="操作" align="center">
							<template slot-scope="scope">
								<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
							</template>
						</el-table-column>
					</el-table>			
					<div class="pagination">
						<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
						:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData.length">
						</el-pagination>
					</div>
				</div>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '2'">
					<div class="grid-content title-text title-box">项目排查/{{projectData.projectName}}/{{inspectPosition0}}</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <div class="handle-box">
                            <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                        </div>
                        <el-tab-pane label="草稿" name="first">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table1" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane label="整改中" name="second">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table2" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="已完成" name="third">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table3" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData3.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="已关闭" name="fourth">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table4" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData4.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
				</div>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '3'">
					<div class="grid-content title-text title-box">实名制工序验收/{{projectData.projectName}}/{{inspectPosition0}}</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <div class="handle-box">
                            <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                        </div>
                        <el-tab-pane label="地基基础工程" name="first">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table5" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData5.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane label="外墙工程" name="second">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table6" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData6.length">
                                </el-pagination>
                            </div>                           
                        </el-tab-pane>
                        <el-tab-pane label="模板工程" name="third">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table7" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData7.length">
                                </el-pagination>
                            </div>                            
                        </el-tab-pane>
                        <el-tab-pane label="铝模工程" name="fourth">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table8" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData8.length">
                                </el-pagination>
                            </div>                            
                        </el-tab-pane>
                        <el-tab-pane label="混泥土工程" name="five">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table9" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData9.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="蓄淋水试验" name="six">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table10" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData10.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="钢筋工程" name="seven">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table11" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData11.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="防水工程" name="eight">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table12" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData12.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="砌体工程" name="nine">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table13" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData13.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="门窗边填塞" name="ten">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table14" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData14.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="内抹灰工程" name="eleven">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table15" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData15.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="屋楼地面工程" name="twelve">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table16" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData16.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="室内装修工程（铺贴）" name="thirteen">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table17" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData17.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
				</div>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '4'">
					<div class="grid-content title-text title-box">红线工序验收/{{projectData.projectName}}/{{inspectPosition0}}</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <div class="handle-box">
                            <el-input placeholder="请输入内容" v-model="search_world" style="width: 200px;margin-left:10px ;"></el-input>
                        </div>
                        <el-tab-pane label="拆模后联合验收" name="first">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table18" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData18.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane label="后浇带封闭验收" name="second">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table19" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData19.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="工序移交：砌体→抹灰" name="third">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table20" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData20.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="工序移交：土建→防水" name="fourth">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table21" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData21.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="工序移交：抹灰→精装" name="five">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table22" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData22.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="工序移交：防水→土建" name="six">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table23" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData23.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="管道安装→沉箱盖板" name="seven">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table24" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData24.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="混泥土浇筑令" name="eight">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table25" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData25.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="防水联合验收" name="nine">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table26" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData26.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="饰面基层验收表" name="ten">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table27" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData27.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="外墙拆架令" name="eleven">
                            <el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
                            :data="table28" >
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="验收部位" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="状态" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="验收对象" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
                                    <template slot-scope="">
                                        <el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">查看详情</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>			
                            <div class="pagination">
                                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
                                :page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData28.length">
                                </el-pagination>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
				</div>

			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'ProjectMeasurement',
		components: {

		},
		data() {
			return {
				loading: false,
				type: 'edit',
				ByShow: '1',
				activeName: 'first',
				currentPage: 1, //初始页
				pagesize: 10, //每页的数据
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				projectData: {},
				inspectPosition0:'',
				projectUserId1: '',
				projectUserId2:'',
				inspectPosition3:'',
				search_world: '',
				dialogEdit: {
					show: false
				},
				dialogImport: {
					show: false
				},
				dialogAdd: {
					show: false
				},
				tableData: [],
				tableData1: [],
				tableData2: [],
				tableData3: [],
				tableData4: [],
				tableData5: [],
				tableData6: [],
				tableData7: [],
				tableData8: [],
				tableData9: [],
				tableData10: [],
				tableData11: [],
				tableData12: [],
				tableData13: [],
				tableData14: [],
				tableData15: [],
				tableData16: [],
				tableData17: [],
				tableData18: [],
				tableData19: [],
				tableData20: [],
				tableData21: [],
				tableData22: [],
				tableData23: [],
				tableData24: [],
				tableData25: [],
				tableData26: [],
				tableData27: [],
				tableData28: [],
				multipleSelection: [],
				form: {},
				options: [],
				categoryList:[],
				isLoading:true
			};
		},
		computed: {
			//过滤筛选
			table() {
				const search_world = this.search_world
				if (search_world) {
					var filterData =  this.tableData.filter(data => {
						return Object.keys(data).some(key => { 
							return String(data[key]).toLowerCase().indexOf(search_world) > -1 
						})
					})
					this.total = filterData.length
					return filterData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
				}
				this.total = this.tableData.length
				return this.tableData.slice((this.currentPage - 1)*this.pagesize, this.currentPage*this.pagesize)
			},
		},

		watch: {
            '$route.params': 'getMeasureData'
		},

		mounted() {
			// this.getCategories()
			// this.getCompany()
			// this.getNoticeData();
			// 获取工程数据
			var EngineeringData = sessionStorage.getItem('registerBaseData');			
			EngineeringData = JSON.parse(EngineeringData);
			console.log(EngineeringData);
			this.projectData = EngineeringData;

			var inspectPosition1 = sessionStorage.getItem('item');			
			inspectPosition1 = JSON.parse(inspectPosition1);
			console.log(inspectPosition1);
			this.projectUserId1 = inspectPosition1;

			var unitContent = sessionStorage.getItem('unitContent');			
			// unitContent = JSON.parse(unitContent);
			console.log(unitContent);
			this.projectUserId2 = unitContent;

			const section = this.projectUserId1.section;
			const build = this.projectUserId1.build;
			const floor = this.projectUserId1.floor;
			const unit = this.projectUserId2;					
			const inspectPosition0 = section + '/' + build + '/' + floor + '/' + unit;
			this.inspectPosition3 = section + build + floor + unit;
			console.log(this.inspectPosition3);
			this.inspectPosition0 = inspectPosition0;
			this.getMeasureData()


		},
		methods: {
			
			//获取表格项目数据
			getMeasureData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getMeasureData')
				console.log(that.projectData)
				fd.append('inspectPosition',this.inspectPosition3)
				fd.append('proTimeStamp',this.projectData.timeStamp)
				fd.append('projectName',this.projectData.projectName)
				// console.log(fd)
				// console.log(fd.flag)
				// console.log(this.$loading())				
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
					console.log(res.data.data)
					that.tableData = res.data.data
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
					}
				}).catch(() => {
					console.error("获取失败")
				})
			},
			
			// 触发搜索按钮
			handleSearch() {
				this.$set(this.query, 'pageIndex', 1);
			},
			// 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			handleClick(tab, event) { //表格tab切换触发的操作函数
				// console.log(tab, event);
			},
			//详情
			handleJump(index, row) {
				// console.log(index);
				// console.log(row);
				//每次进入存储session值，以防止页面刷新数据丢失
				sessionStorage.setItem('itemInspectInformation',JSON.stringify(row))
				this.$router.push({
					name: 'itemInspectInformation',
					params:row
				})
			},
			handleOpen(key, keyPath) {
                console.log(key, keyPath);
                if(key==1){
                    this.ByShow = 1;
                }
                else if(key==2){
                    this.ByShow = 2;
                }
                else if(key==3){
                    this.ByShow = 3;
                }
                else {};              
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
                if(key==1){
                    this.ByShow = 1;
                }
                else if(key==2){
                    this.ByShow = 2;
                }
                else if(key==3){
                    this.ByShow = 3;
                }
                else {};
            },
			handleQualityInspections() {
				this.ByShow = 3;
            },
            handleInspectMeasured() {
				this.ByShow = 4;
            },
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
		},
	};
</script>
<style scoped>
	.boxFather {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
	.button1{
		width: 50px;
        height: 30px;
		float:right;
	}
	.title-box {
		background-color: #d9edf7;
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
	.handle-box {
		margin-bottom: 20px;
	}

	.handle-select {
		width: 120px;
	}

	.handle-input {
		width: 300px;
		display: inline-block;
	}

	.table {
		width: 100%;
		font-size: 14px;
	}

	.red {
		color: #ff0000;
	}

	.green {
		color: #67c23a;
	}

	.mr10 {
		margin-right: 10px;
	}

	.table-td-thumb {
		display: block;
		margin: auto;
		width: 40px;
		height: 40px;
	}
</style>
