import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

//解决路由跳转报错问题
const originalPush = Router.prototype.push

	Router.prototype.push = function push (location) {

		return originalPush.call(this, location).catch(err => err)

}

export default new Router({
    routes: [
        {
            path: '/',
            redirect: '/dashboard'
        },
        {
            path: '/',
            // component: () => import(/* webpackChunkName: "home" */ '@/components/common/Home.vue'),
			component: resolve =>require(['@/components/common/Home.vue'],resolve),//路由懒加载
            meta: { title: '自述文件' },
            children: [
                {
                    path: '/dashboard',
                    // component: () => import(/* webpackChunkName: "dashboard" */ '@/components/page/Dashboard.vue'),
					component: resolve =>require(['@/components/page/index/Dashboard.vue'],resolve),
                    meta: { title: '系统首页' }
                },
				/* 项目管理栏目 */
                {
                    path: '/table',
					component: resolve =>require(['@/components/page/project/ProTable.vue'],resolve),
                    meta: { title: '项目管理' }
				},
				{
					path: '/test',
					component: resolve =>require(['@/components/test.vue'],resolve),
					meta: {title: '测试'}

				},
				{
				    path: '/prodetail',
					name:'prodetail',
					component: resolve =>require(['@/components/page/project/ProDetail.vue'],resolve),
				    meta: { title: '项目详情' }
				},
				{
				    path: '/statistics',
					component: resolve =>require(['@/components/page/project/ProStatistics.vue'],resolve),
				    meta: { title: '项目统计' }
				},
				{
				    path: '/inspect',
					name: 'inspect',
					component: resolve =>require(['@/components/page/inspect/InspectTable.vue'],resolve),
				    meta: { title: '质量巡查' }
				},
				{
				    path: '/define',
					name: 'define',
					component: resolve =>require(['@/components/page/inspect/InsDefineDetail.vue'],resolve),
				    meta: { title: '检查项定义' }
				},
				{
				    path: '/information',
					name: 'information',
					component: resolve =>require(['@/components/page/inspect/InspectAdminDetail.vue'],resolve),
				    meta: { title: '巡查管理信息' }
				},
                {
                    path: '/tabs',
					component: resolve =>require(['@/components/page/Tabs.vue'],resolve),
                    meta: { title: '消息查看' }
                },
                {
                    path: '/form',
					component: resolve =>require(['@/components/page/BaseForm.vue'],resolve),
                    meta: { title: '基本表单' }
                },
                {
                    // 富文本编辑器组件
                    path: '/editor',
					component: resolve =>require(['@/components/page/VueEditor.vue'],resolve),
                    meta: { title: '富文本编辑器' }
                },
                {
                    // markdown组件
                    path: '/markdown',
					component: resolve =>require(['@/components/page/Markdown.vue'],resolve),
                    meta: { title: 'markdown编辑器' }
                },
                {
                    // 图片上传组件
                    path: '/upload',
					component: resolve =>require(['@/components/page/Upload.vue'],resolve),
                    meta: { title: '文件上传' }
                },
                {
                    // vue-schart组件
                    path: '/charts',
					component: resolve =>require(['@/components/page/BaseCharts.vue'],resolve),
                    meta: { title: 'schart图表' }
                },
                {
                    // 拖拽列表组件
                    path: '/drag',
					component: resolve =>require(['@/components/page/DragList.vue'],resolve),
                    meta: { title: '拖拽列表' }
                },
                {
                    // 拖拽Dialog组件
                    path: '/dialog',
					component: resolve =>require(['@/components/page/DragDialog.vue'],resolve),
                    meta: { title: '拖拽弹框' }
                },
                {
                    // 国际化组件
                    path: '/i18n',
					component: resolve =>require(['@/components/page/I18n.vue'],resolve),
                    meta: { title: '国际化' }
                },
                {
                    // 权限页面
                    path: '/permission',
					component: resolve =>require(['@/components/page/Permission.vue'],resolve),
                    meta: { title: '权限测试', permission: true }
                },
				// 数据查看
				{
				    path: '/datacheck',
					component: resolve =>require(['@/components/page/datacheck/ItemInspect.vue'],resolve),
				    meta: { title: '项目检查' }
				},
				{
				    path: '/inspectDetail',
					name: 'inspectDetail',
					component: resolve =>require(['@/components/page/datacheck/ItemInspectDetail.vue'],resolve),
				    meta: { title: '项目检查详情' }
				},
				{
				    path: '/itemAccept',
					component: resolve =>require(['@/components/page/datacheck/ItemAccept.vue'],resolve),
				    meta: { title: '项目验收' }
				},
				{
				    path: '/acceptDetail',
					name: 'acceptDetail',
					component: resolve =>require(['@/components/page/datacheck/ItemAcceptDetail.vue'],resolve),
				    meta: { title: '项目验收详情' }
				},
				{
				    path: '/weekly',
					component: resolve =>require(['@/components/page/datacheck/WeeklyInspect.vue'],resolve),
				    meta: { title: '每周巡检' }
				},
				{
				    path: '/sceneInsDetail',
				    name: 'sceneInsDetail',
					component: resolve =>require(['@/components/page/datacheck/SceneInsDetail.vue'],resolve),
				    meta: { title: '现场质量巡检' }
				},
				{
				    path: '/fileInsDetail',
				    name: 'fileInsDetail',
					component: resolve =>require(['@/components/page/datacheck/FileInsDetail.vue'],resolve),
				    meta: { title: '方案资料巡查' }
				},
				{
				    path: '/concrete',
					component: resolve =>require(['@/components/page/datacheck/ConcreteManage.vue'],resolve),
				    meta: { title: '混凝土专项管理' }
                },
                {
                    path: '/concreteDetail',
					name: 'concreteDetail',
					component: resolve =>require(['@/components/page/datacheck/ConcreteDetail.vue'],resolve),
				    meta: { title: '混凝土专项管理详情' }
				},
				{
					path: '/ProStaticsDetail',
					name: 'ProStaticsDetail',
					component: resolve =>require(['@/components/page/project/projectStatistics/ProStaticsDetail'],resolve),
				    meta: { title: '统计详情' }
				},
                /** 系统管理 **/
                {
                    path: '/user',
					component: resolve =>require(['@/components/page/system/User.vue'],resolve),
                    meta: { title: '用户管理', permission: true }
                },
				{
				    path: '/company',
					component: resolve =>require(['@/components/page/system/Company.vue'],resolve),
				    meta: { title: '公司集团定义', permission: true }
				},
                {
                    path: '/database',
					component: resolve =>require(['@/components/page/system/Database.vue'],resolve),
                    meta: { title: '违章数据库管理', permission: true }
                },
                {
                    path: '/standard',
					component: resolve =>require(['@/components/page/system/Standard.vue'],resolve),
                    meta: { title: '实测实量标准', permission: true }
                },
                {
                    path: '/category',
					component: resolve =>require(['@/components/page/system/Category.vue'],resolve),
                    meta: { title: '工程类别定义', permission: true }
                },
                {
                    path: '/journal',
					component: resolve =>require(['@/components/page/system/Journal.vue'],resolve),
                    meta: { title: '系统操作日志', permission: true }
                },
				{
				    path: '/403',
					component: resolve =>require(['@/components/page/403.vue'],resolve),
				    meta: { title: '403' }
				}
            ]
        },
        {
            path: '/login',
            component: () => import(/* webpackChunkName: "login" */ '@/components/page/Login.vue'),
            meta: { title: '登录' }
        },
        {
            path: '*',
            redirect: '/404'
        }
    ]
});
