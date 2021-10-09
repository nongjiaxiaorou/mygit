<template>
    <div class="sidebar">
        <el-menu class="sidebar-el-menu" :default-active="onRoutes" :collapse="collapse" background-color="#324157"
            text-color="#bfcbd9" active-text-color="#20a0ff" unique-opened router>
            <template v-for="item in items">
                <template v-if="item.subs">
                    <el-submenu :index="item.index" :key="item.index">
                        <template slot="title">
                            <i :class="item.icon"></i>
                            <span slot="title">{{ item.title }}</span>
                        </template>
                        <template v-for="subItem in item.subs">
                            <el-submenu v-if="subItem.subs" :index="subItem.index" :key="subItem.index">
                                <template slot="title">{{ subItem.title }}</template>
                                <el-menu-item v-for="(threeItem,i) in subItem.subs" :key="i" :index="threeItem.index">
                                    {{ threeItem.title }}</el-menu-item>
                            </el-submenu>
                            <el-menu-item v-else :index="subItem.index" :key="subItem.index">{{ subItem.title }}
                            </el-menu-item>
                        </template>
                    </el-submenu>
                </template>
                <template v-else>
                    <!-- 通过数组 item 的 index 下标来区分 -->
                    <el-menu-item :index="item.index" :key="item.index"> 
                        <i :class="item.icon"></i>
                        <span slot="title">{{ item.title }}</span>
                    </el-menu-item>
                </template>
            </template>
        </el-menu>
    </div>
</template>

<script>
    import bus from '../common/bus';
    export default {
        data() {
            return {
                collapse: false,
                items: [
                    {
                        icon: 'el-icon-lx-home',
                        index: 'dashboard',
                        title: '数据看板'
                    },
					{
					    icon: 'el-icon-lx-copy',
					    index: 'tabs',
					    title: '消息查看'
					},
                    {
                        icon: 'el-icon-lx-cascades',
                        index: 'table',
                        title: '项目管理',
						// subs: [
						//     {
						//         index: 'register',
						//         title: '项目登记'
						//     },
						//     {
						//         index: 'engineering',
						//         title: '项目管理'
						//     },
						// 	{
						// 	    index: 'statistics',
						// 	    title: '项目统计'
						// 	},
						// 	{
						// 	    index: 'inspect',
						// 	    title: '质量巡查'
						// 	}
						// ]
                    },
					{		
							icon: 'el-icon-lx-cascades',
						    index: 'inspect',
						    title: '质量巡查'
					},
					{
					    icon: 'el-icon-view',
					    index: '7',
					    title: '数据查看',
						subs: [
						    {
						        index: 'datacheck',
						        title: '项目检查'
						    },
						    {
						        index: 'itemAccept',
						        title: '项目验收'
						    },
							{
							    index: 'weekly',
							    title: '每周巡检'
							},
							{
							    index: 'concrete',
							    title: '混凝土专项管理'
							}
						]
					},
                    {
                        icon: 'el-icon-s-tools',
                        index: '8',
                        title: '系统管理',
                        subs: [
                            {
                                index: 'user',
                                title: '用户管理'//只有超级管理员可查看
                            },
							{
							    index: 'Company',
							    title: '公司集团定义'//只有超级管理员可查看
							},
                            {
                                index: 'database',
                                title: '违章数据库管理'//编辑个人信息
                            },
                            {
                                index: 'standard',
                                title: '实测实量标准'//编辑个人信息
                            },
                            {
                                index: 'Category',
                                title: '工程类别定义'//编辑个人信息
                            },
                            {
                                index: 'journal',
                                title: '系统操作日志'//编辑个人信息
                            },

                        ]
                    },
                ]
            };
        },
        computed: {
            onRoutes() {
                return this.$route.path.replace('/', '');
            }
        },
        created() {
            // 通过 Event Bus 进行组件间通信，来折叠侧边栏
            bus.$on('collapse', msg => {
                // console.log(msg);
                this.collapse = msg;
                bus.$emit('collapse-content', msg);
            });
        }
    };
</script>

<style scoped>
    .sidebar {
        display: block;
        position: absolute;
        left: 0;
        top: 70px;
        bottom: 0;
        overflow-y: scroll;
    }

    .sidebar::-webkit-scrollbar {
        width: 0;
    }

    .sidebar-el-menu:not(.el-menu--collapse) {
        width: 250px;
    }

    .sidebar>ul {
        height: 100%;
    }
</style>