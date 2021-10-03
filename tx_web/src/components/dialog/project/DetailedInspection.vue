<template>
    <div class="basic" style="flex:1;display:flex=1">
        <div class="container" id="userManage1">  
            <div class="boxFather" style="" id="leftBox"   >
                <div style="margin-left: 0%; margin-height: 10%">  
                    <el-row class="tac">
                        <el-col :span="3">
                            <el-menu
                            default-active="2"
                            class="el-menu-vertical-demo"
                            @open="handleOpen"
                            @close="handleClose"
                            @select="handleSelectIndex">
								<el-submenu index="1">
									<template slot="title" @click.native="handleQualityInspections">
										<span>质量巡查</span>
									</template>
									<el-menu-item-group>
										<el-menu-item index="1-1">质量日报</el-menu-item>
										<el-menu-item index="1-2">阅读评价表</el-menu-item>
										<el-menu-item index="1-3">综合检查（施工方案）</el-menu-item>
										<el-menu-item index="1-4">综合检查（工程资料）</el-menu-item>
										<el-menu-item index="1-5">风险管控检查评分表</el-menu-item>
										<el-menu-item index="1-6">规定动作落实评分表</el-menu-item>
										<el-menu-item index="1-7">规定动作落实评分表</el-menu-item>
										<el-menu-item index="1-8">项目检查得分表</el-menu-item>
										<el-menu-item index="1-9">管理规定动作评分表</el-menu-item>
									</el-menu-item-group>
								</el-submenu>

								<el-submenu index="2">
									<template slot="title" @click.native="handleInspectMeasured">
										<span>巡查实测</span>
									</template>
									<el-menu-item-group>

									</el-menu-item-group>
                            	</el-submenu>

								<el-submenu index="3">
									<template slot="title">
										<span>质量报告</span>
									</template>
									<el-menu-item-group>
										<el-menu-item index="3-1">总体管理评分</el-menu-item>
										<el-menu-item index="3-2">实测实量</el-menu-item>
										<el-menu-item index="3-3">观感问题</el-menu-item>
										<el-menu-item index="3-4">资料方案问题</el-menu-item>
									</el-menu-item-group>
                            	</el-submenu>
                            </el-menu>
                        </el-col>
                    </el-row>
                </div>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '1'">
					<div class="grid-content title-text title-box" >质量巡查/{{projectData.projectName}}</div>
					<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
						<el-button type="primary" class="button-style" title="选择筛选维度" size icon="el-icon-folder-add" @click="handleExcept()">选择筛选维度</el-button>
						<!-- 草稿 -->
						<el-tab-pane label="草稿" name="first">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables1">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
								</el-pagination> 
							</div>
						</el-tab-pane>
						<!-- 整改中 -->
						<el-tab-pane label="整改中" name="second">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables2">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 延期 -->
						<el-tab-pane label="延期" name="third">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables3">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData3.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 逾期 -->
						<el-tab-pane label="逾期" name="fourth">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables4">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData4.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 未完成 -->
						<el-tab-pane label="未完成" name="five">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables5">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJumpRouter(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData5.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 已完成 -->
						<el-tab-pane label="已完成" name="six">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables6">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData6.length">
								</el-pagination>
							</div>
						</el-tab-pane>
					</el-tabs>
				</div>

				<div style="margin-left: 0%;width: 78%; margin-height: 10%" v-if="ByShow == '2'">
					<div class="grid-content title-text title-box">巡查实测/</div>
					<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
						<el-button type="primary" class="button-style" title="选择筛选维度" size icon="el-icon-folder-add" @click="handleExcept()">选择筛选维度</el-button>
						<!-- 草稿 -->
						<el-tab-pane label="草稿" name="first">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables1">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData1.length">
								</el-pagination> 
							</div>
						</el-tab-pane>
						<!-- 整改中 -->
						<el-tab-pane label="整改中" name="second">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables2">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData2.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 延期 -->
						<el-tab-pane label="延期" name="third">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables3">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData3.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 逾期 -->
						<el-tab-pane label="逾期" name="fourth">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables4">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData4.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 未完成 -->
						<el-tab-pane label="未完成1" name="five">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables5">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJumpRouter(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData5.length">
								</el-pagination>
							</div>
						</el-tab-pane>
						<!-- 已完成 -->
						<el-tab-pane label="已完成" name="six">
							<el-table :height="this.commonFunc.screeHeight" border class="table" ref="multipleTable" header-cell-class-name="table-header" @selection-change="handleSelectionChange"
							:data="tables6">
								<el-table-column type="selection" width="55"></el-table-column>
								<el-table-column type="index" label="序号" align="center" width="80"></el-table-column>
								<el-table-column prop="inspectCode" label="通知单编号" align="center"></el-table-column>
								<el-table-column prop="inspectLevel" label="检查层级" align="center"></el-table-column>
								<el-table-column prop="inspectPosition" label="楼层信息" align="center"></el-table-column>
								<el-table-column prop="inspectUnit" label="检查单位" align="center"></el-table-column>
								<el-table-column prop="inspectObj" label="检查对象" align="center"></el-table-column>
								<el-table-column prop="inspectDate" label="检查日期" align="center"></el-table-column>
								<el-table-column label="操作" width="180" align="center">
									<template slot="header" slot-scope="scope">  
										<el-input v-model="search" size="mini" placeholder="输入关键字搜索" />
									</template>
									<template slot-scope="scope">
										<el-button type="text" icon="el-icon-edit" @click="handleJump(scope.$index, scope.row)">详情</el-button>
									</template>
								</el-table-column>
							</el-table>
							<div class="pagination">
								<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
								:page-sizes="[5, 10, 20, 40]" :page-size="pagesize" layout="total, sizes, prev, pager, next, jumper" :total="tableData6.length">
								</el-pagination>
							</div>
						</el-tab-pane>
					</el-tabs>
				</div>

                <div style="margin-right: 0%;width: 78%; margin-height: 10% " v-if="ByShow == '3-1' ">
                    <div class="grid-content title-text title-box" >巡查报告/</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <el-tab-pane label="总体管理评分" name="first">
                            <div class="panel-body">
                                <div align="center">总体管理评分</div>
                                <span id="fgsmc" style="float:left;font-weight: bold;">所属分公司：</span>
                                <span id="xmmc" style="margin-left:5%;font-weight: bold;">项目：</span>
                                <span id="jcrq" style="float:right;font-weight: bold;">检查日期：</span>
                                <table border="1" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <th colspan="6" style="height: 40px;text-align: center;">检查得分汇总表</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" style="height: 50px;text-align: center;">检查板块</th>
                                        <th colspan="1" style="height: 50px;">加分项</th>
                                    </tr>
                                    <tr> 
                                        <th style="height: 30px;width: 10%;">检查项</th>
                                        <th style="height: 30px;">重大风险管控70%</th>
                                        <th style="height: 30px;">资料10%</th>
                                        <th style="height: 30px;">方案10%</th>
                                        <th style="height: 30px;">规定动作落实10%</th>
                                        <th style="height: 30px;">四新技术应用</th>
                                    </tr>
                                    <tr> 
                                        <th>得分</th>
                                        <th><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                        <th><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                        <th><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                        <th><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                        <th><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1">综合得分</th>
                                        <th colspan="5" ><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="1">检查组长</th>
                                        <th colspan="3"><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                        <th colspan="1">项目负责人</th>
                                        <th colspan="1"><input style="width:100%;border:none;height:50px;text-align:center;"></th>
                                    </tr>
                                </table>
                                <button style="float: right; margin: 10px; width:60px; height:30px;" onclick="submit()">提交</button>
                            </div>	
                        </el-tab-pane>
                        <el-tab-pane label="总体管理评分清单" name="second">
                        </el-tab-pane>

                    </el-tabs>
                </div>
				<div style="margin-right: 0%;width: 78%; margin-height: 10% " v-if="ByShow == '3-2' ">
                    <div class="grid-content title-text title-box" >巡查报告/</div>
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <el-tab-pane label="较好的实测项" name="first">
							<div class="panel-body">
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane fade in active" id="jh">
										<div class="panel-body">
											<h2 align="center">较好的实测项</h2>
											<span id="fgsmc" style="float:left;font-weight: bold;">所属分公司：</span>
											<span id="xmmc" style="margin-left:5%;font-weight: bold;">项目：</span>
											<span id="jcrq" style="float:right;font-weight: bold;">检查日期：</span>
											<table border="1" cellspacing="0" cellpadding="0" class="table_jhsc" > 
												<tr style="height: 50px;">
													<th  colspan="1">序号</th>
													<th colspan="1">名称</th>
													<th colspan="1">合格率</th>
													<th colspan="1">序号</th>
													<th colspan="1">名称</th>
													<th colspan="1">合格率</th>
												</tr>
												<tr> 
													<td ><input class="inputStyle" value="1" style="height: 50px;text-align:center;"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle" value="2"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle"></td>
												</tr>
												<tr> 
													<td><input class="inputStyle" value="3"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle" value="4"></td>
													<td><input class="inputStyle"></td>
													<td><input class="inputStyle"></td>
												</tr>
												<tbody id="tbody">
													
												</tbody>						
											</table>
											<span style="float: left;font-style: italic;">条件：实测较好项从项目的实测中数据提取，单项土建初测率95%以及精装初测90%的检测项；</span>
											<button style="float: right;margin: 60px; width:60px; height:30px;" onclick="sub()">提交</button>
											<button style="float: left;margin: 40px; width:60px; height:30px;" onclick="add()">增加行</button>
											<button style="float: left;margin: 40px; width:60px; height:30px;" onclick="del()">删除行</button>								
										</div>		
									</div>	
								</div>
							</div>
						</el-tab-pane>

						<el-tab-pane label="较差的实测项" name="second">
							<div class="panel-body">
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane fade in active" id="jc">
										<div class="panel-body">
										<h2 align="center">较差的实测项</h2>
										<span id="fgsmc1" style="float:left;font-weight: bold;">所属分公司：</span>
										<span id="xmmc1" style="margin-left:5%;font-weight: bold;">项目：</span>
										<span id="jcrq1" style="float:right;font-weight: bold;">检查日期：</span>
										<table border="1" cellspacing="0" cellpadding="0" class="table_jcsc">
											<tr style="height: 50px;">
												<th style="height: 30px;" colspan="1">序号</th>
												<th colspan="1">名称</th>
												<th colspan="1">初测合格率</th>
												<th colspan="1">序号</th>
												<th colspan="1">名称</th>
												<th colspan="1">复测合格率</th>
											</tr>
											<tr> 
												<td><input class="inputStyle" value="1"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle" value="2"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle"></td>
											</tr>
											<tr> 
												<td><input class="inputStyle" value="3"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle" value="4"></td>
												<td><input class="inputStyle"></td>
												<td><input class="inputStyle"></td>
											</tr>
											<tbody id="tbody1">
												
											</tbody>						
										</table>
										<span style="float: left;font-style: italic;">条件：从项目较差项中抽出3-5项抽测，与原来项目的初测、复测进行对比，以检验考核项目是否有提升;</span>
										<button style="float: right;margin: 40px; width:60px; height:30px;" onclick="sub()">提交</button>
										<button style="float: left;margin: 40px; width:60px; height:30px;" onclick="add()">增加行</button>
										<button style="float: left;margin: 40px; width:60px; height:30px;" onclick="del()">删除行</button>							
										</div>		
									</div>
								</div>	
							</div>
						</el-tab-pane>
						<el-tab-pane label="实测实量清单" name="third">
						</el-tab-pane>

				    </el-tabs>
                </div>			
            </div> 
        </div>
        <InspectExcept1 :dialogExcept="dialogExcept" @handleSelectChild="handleSelect"></InspectExcept1>
    </div>
</template>

<script>
	import InspectExcept1 from "../datacheck/InspectExcept1.vue"
    export default {
		name: 'DetailedInspection',
		components: {
			InspectExcept1
        },
        data() {
			return {
                defaultProps: {
					children: 'children',
					label: 'label'
				},
				projectData: {},
				ByShow: '1',
				tabPosition: 'left',
				type: 'edit',
				currentPage: 1, //初始页
				pagesize: 10, //    每页的数据
				query: {
					address: '',
					name: '',
					pageIndex: 1,
					pageSize: 10
				},
				dialogExcept: {
					show: false
				},
				activeName: 'first',
				search: '',
				search_world: '',
				tableData1: [],
				tableData2: [],
				tableData3: [],
				tableData4: [],
				tableData5: [],
				tableData6: [],
				isShow: false,
				level: '总公司'
			};
		},
		mounted() {
			this.getNoticeData();
			var a = sessionStorage.getItem('registerBaseData');
			a = JSON.parse(a);
			console.log(a);
			this.projectData = a;
			// console.log(this.byId);			
		},
		computed: {
			newName() {
				return this.query.name;
			},
			// 模糊搜索
			tables1() {
				const search = this.search
				if (search) {
					return this.tableData1.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData1
			},
			tables2() {
				const search = this.search
				if (search) {
					return this.tableData2.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData2
			},
			tables3() {
				const search = this.search
				if (search) {
					return this.tableData3.filter(data => {
						return Object.keys(data).some(key => {
							return String(data[key]).toLowerCase().indexOf(search) > -1
						})
					})
				}
				return this.tableData3
			},
			tables4() {
				this.total = this.tableData4.length
				return this.tableData4.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
			},
			tables5() {
				this.total = this.tableData5.length
				return this.tableData5.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
			},
			tables6() {
				this.total = this.tableData6.length
				return this.tableData6.slice((this.currentPage - 1) * this.pagesize, this.currentPage * this.pagesize)
			},
		},
		watch: {
			newName(val) {
				if (val == '') {
					this.searchKey = 'show';
				}
			},
		},
        methods: {
			
			handleQualityInspections() {
				this.ByShow = 1
			},
			handleInspectMeasured() {
				this.ByShow = 2
			},
            handleOpen(key, keyPath) {
				console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
				console.log(key, keyPath);
			},			
            handleSelectIndex(key, keyPath) {
                console.log(key, keyPath);
				this.ByShow = key
            },
            // 多选操作
			handleSelectionChange(val) {
				this.multipleSelection = val;
			},
			// 初始页currentPage、初始每页数据数pagesize和数据data
			handleSizeChange: function(size) {
				this.pagesize = size;
			},
			handleCurrentChange: function(currentPage) {
				this.currentPage = currentPage;
			},
			handleClick(tab, event) { //表格tab切换触发的操作函数
				// console.log(tab, event);
			},
			handle(tab, event){ //表格tab切换触发的操作函数
				// console.log(tab, event);
			},
			//获取通知单数据
			getNoticeData() {
				var that = this
				var fd = new FormData()
				let userInfo = localStorage.getItem('userInfo')
				userInfo = JSON.parse(userInfo)
				fd.append('flag', 'getNoticeData')
				fd.append('userId', userInfo.userId)
				fd.append('level', this.level)
				this.$axios.post(that.$adminUrl + `/datacheck/WeeklyInspect/WeeklySceneInspect.php`, fd).then(res => {
					console.log(res.data.data)
					if (res.data.code) {
						let draftIndex = 1
						let rectification = 1
						let finishedIndex = 1
						let closedIndex = 1
						for(var i=0;i<res.data.data.length;i++){
							if(res.data.data[i].noticeState=="草稿"){
								res.data.data[i].index = draftIndex
								this.tableData1.push(res.data.data[i])
								draftIndex++
							}else if(res.data.data[i].noticeState=="整改中"){
								res.data.data[i].index = draftIndex
								this.tableData2.push(res.data.data[i])
								rectification++
							}else if(res.data.data[i].noticeState=="延期"){
								res.data.data[i].index = finishedIndex
								this.tableData3.push(res.data.data[i])
								finishedIndex++
							}else if(res.data.data[i].noticeState=="逾期"){
								res.data.data[i].index = closedIndex
								this.tableData4.push(res.data.data[i])
								closedIndex++
							}else if(res.data.data[i].noticeState=="未完成"){
								res.data.data[i].index = closedIndex
								this.tableData5.push(res.data.data[i])
								closedIndex++
							}else if(res.data.data[i].noticeState=="已完成"){
								res.data.data[i].index = closedIndex
								this.tableData6.push(res.data.data[i])
								closedIndex++
							}
						}
					}
				})
			},
			//详情
			handleJump(index, row) {
				//每次进入存储session值，以防子页面刷新数据丢失
				sessionStorage.setItem('sceneInsData', JSON.stringify(row))
				console.log(row)
				this.$router.push({
					name: 'sceneInsDetail',
					params: row
				})
			},
			//打开筛选模态框
			handleExcept() {
				this.dialogExcept.show = true;
			},
			//接收子组件传值
			handleSelect(data) {
				let res = data
				if (res.code) {
					let draftIndex = 1
					let rectification = 1
					let delayIndex = 1
					let overdueIndex = 1
					let unfinishedIndex = 1
					let finishIndex = 1
					this.tableData1 = []
					this.tableData2 = []
					this.tableData3 = []
					this.tableData4 = []
					this.tableData5 = []
					this.tableData6 = []
					for(var i=0;i<res.data.length;i++){
						if(res.data[i].noticeState=="草稿" && res.data[i].inspectObj==this.a){
							res.data[i].index = draftIndex
							this.tableData1.push(res.data[i])
							draftIndex++
						}else if(res.data[i].noticeState=="整改中"){
							res.data[i].index = draftIndex
							this.tableData2.push(res.data[i])
							rectification++
						}else if(res.data[i].noticeState=="延期"){
							res.data[i].index = delayIndex
							this.tableData3.push(res.data[i])
							delayIndex++
						}else if(res.data[i].noticeState=="逾期"){
							res.data[i].index = overdueIndex
							this.tableData4.push(res.data[i])
							overdueIndex++
						}else if(res.data[i].noticeState=="未完成"){
							res.data[i].index = unfinishedIndex
							this.tableData5.push(res.data[i])
							unfinishedIndex++
						}else if(res.data[i].noticeState=="已完成"){
							res.data[i].index = finishIndex
							this.tableData6.push(res.data[i])
							finishIndex++
						}
					}
				}
			}
		}       
    }
</script>

<style scoped>
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

	.mr10 {
		margin-right: 10px;
	}

	.table-td-thumb {
		display: block;
		margin: auto;
		width: 40px;
		height: 40px;
	}

	.green {
		color: #67c23a;
	}
	
	.button-style {
		margin: 5px;
	}
    .boxFather {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
	.inputStyle {
		width:100%;
		height: 50px;
		text-align:center;
		border:none;
	}
</style>
