┌─common        		放置公共样式，目前在free.css已经封装了一些公共样式
│─components            符合vue组件规范的uni-app组件目录
│  └─common
│  │ 	└─comp-a.vue	全局可复用的a组件，命名规范xxx-xx.vue	
│  └─index       		index相关组件及模态框
│     └─index-a.vue     index页面
│     └─dialog       	index模态框存放位置
├─pages                 业务页面文件存放的目录
│  ├─index
│  │  └─index.vue       index页面(除了index页面外，其余页面开头字母大写)
│  └─list
│     └─List.vue        list页面
├─static                存放应用引用静态资源（如图片、视频等）的目录，注意：静态资源只能存放于此
├─main.js               Vue初始化入口文件
├─App.vue               应用配置，用来配置App全局样式以及监听 应用生命周期
├─manifest.json         配置应用名称、appid、logo、版本等打包信息，详见
└─pages.json            配置页面路由、导航条、选项卡等页面类信息，详见

