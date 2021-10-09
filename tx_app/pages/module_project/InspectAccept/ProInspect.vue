<template>
	<view class="uni-fab-box">
		<!-- 自定义导航栏 -->
		<!-- <uni-nav-bar left-icon="back" right-icon='list' statusBar title="项目检查" :border="false" 
		@clickLeft="back" 
		@clickRight="showDrawer('showLeft')"
		></uni-nav-bar> -->
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">草稿({{draftNum}})</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">整改中({{rectificationNum}})</view>
			<view :class="['inv-h', Inv == 2 ? 'inv-h-se' : '']" @click="Inv = 2">已关闭({{closedNum}})</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<view class="example-body">
				<view v-for="item in draftCardList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" :title="item.noticeNumber" @click="toCheckDetail(item)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查工序:{{item.inspectProcedure}}
								<view>
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox value="item.id" :checked="false" />
									</checkbox-group>
								</view>
							</view>
							<view class="uni-list-cell uni-list-card">检查部位:{{item.inspectPosition}}</view>
							<view class="uni-list-cell uni-list-card">检查项目:{{item.inspectItemHead}}</view>
							<view class="uni-list-cell uni-list-card">检查日期:{{item.inspectDate}}</view>
							<view class=" uni-list-card">排查人姓名:{{item.inspectPersonName}}</view>
							<!-- <view class=" uni-list-card button_style" style="display:flex; justify-content: space-between;">
								<view>
									<button @click="toItemInspect(item)" type="primary">下发整改</button>
								</view>
								<view>
									<button @click="toCheckDetail(item)" type="primary">详情信息</button>
								</view>
							</view> -->
						</view>
					</uni-card>
				</view>
			</view>
			<!-- 悬浮按钮 -->
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
		</view>
		<!-- 页面1 -->
		<view class="" v-show="Inv == 1">
			<view class="example-body">
				<view v-for="item in rectificationCardList" :key="item.id" class="example-box">
					<uni-card :is-shadow="true" :title="item.noticeNumber" @click="toViolationInvestigation(item)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查工序:{{item.inspectProcedure}}
								<view>
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox value="item.id" :checked="false" />
									</checkbox-group>
								</view>
							</view> 
							<view class="uni-list-cell uni-list-card">检查部位:{{item.inspectPosition}}</view>
							<view class="uni-list-cell uni-list-card">检查项目:{{item.inspectItemHead}}</view>
							<view class="uni-list-cell uni-list-card">检查日期:{{item.inspectDate}}</view>
							<view class=" uni-list-card">排查人姓名:{{item.inspectPersonName}}</view>
							<!-- <view class=" uni-list-card button_style" style="display:flex; justify-content: space-between;">
								<view>
									<button @click="toItemInspect(item)" type="primary">下发整改</button>
								</view>
								<view>
									<button @click="toViolationInvestigation(item)" type="primary">违章问题回复</button>
								</view>
							</view> -->
						</view>
					</uni-card>
				</view>
			</view>
			<!-- 悬浮按钮 -->
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
		</view>
		<!-- 页面2 -->
		<view v-show="Inv == 2">
			<view class="example-body">
				<view v-for="item in closedCardList" :key="item.id" class="example-box">
					<uni-card :complete="complete" :is-shadow="true" :title="item.noticeNumber" @click="toCheckDetail(item)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								检查工序:{{item.inspectProcedure}}
								<view>
									<checkbox-group @change="checkboxChange($event, item)">
										<checkbox value="item.id" :checked="false" />
									</checkbox-group>
								</view>
							</view>
							<view class="uni-list-cell uni-list-card">检查部位:{{item.inspectPosition}}</view>
							<view class="uni-list-cell uni-list-card">检查项目:{{item.inspectItemHead}}</view>
							<view class="uni-list-cell uni-list-card">检查日期:{{item.inspectDate}}</view>
							<view class=" uni-list-card">排查人姓名:{{item.inspectPersonName}}</view>
							<!-- <view class=" uni-list-card button_style" style="display:flex; justify-content: space-between;">
								<view>
									<button @click="toItemInspect" type="primary">下发整改</button>
								</view>
								<view>
									<button @click="toCheckDetail(item)" type="primary">详情信息</button>
								</view>
							</view> -->
						</view>
					</uni-card>
				</view>
			</view>
			<!-- 悬浮按钮 -->
			<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
			 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
		</view>
		<!-- 抽屉 -->
		<view class="example-body">
			<uni-drawer ref="showLeft" mode="left" :width="300" @change="change($event,'showLeft')">
				<view class="close">
					<view class="scroll-view">
						<scroll-view class="scroll-view-box" scroll-y="true">
							<view class="info-content">
								<buildTree :props="prop" @sendChildData="selDataFunc" :isCheck="true" :trees="tree"></buildTree>
								<!-- <text>可滚动内容 {{item}}</text> -->
							</view>
							<view class="closeCustom">
								<view class="btn-block">
									<view  @click="closeDrawer('showLeft')">
										<button type="primary">选择楼栋信息</button>
									</view>
								</view>
								<!-- <view class="word-btn" @click="closeDrawer('showLeft')"><text class="word-btn-white">关闭Drawer</text></view> -->
							</view>
						</scroll-view>
					</view>
				</view>
			</uni-drawer>
		</view>
		<messagePush ref="select_person" :flag="'InspectMessagePush'" :person="'allPerson'" @refresh="completePush"></messagePush>
	</view>
</template>

<script>
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniDrawer from '@/components/uni-app/uni-drawer/uni-drawer.vue'
	import uniCard from '@/components/uni-app/uni-card/uni-card.vue'
	import buildTree from '@/components/common/build-tree/build-tree.vue'
	import uniToast from "@/components/uni-app/uni-toast/uni-toast.vue"
	import messagePush from '@/pages/module_project/InspectAccept/message-push/message-push.vue';
	let that = null
	export default {
		components: {
			uniFab,
			uniNavBar,
			uniDrawer,
			uniCard, 
			buildTree,
			messagePush
		},
		data() {
			return {
				Inv: 0,
				title: 'uni-fab',
				directionStr: '垂直',
				horizontal: 'right',
				vertical: 'bottom',
				direction: 'horizontal',
				pattern: {
					color: '#7A7E83',
					backgroundColor: '#fff',
					selectedColor: '#007AFF',
					buttonColor: '#007AFF'
				},
				content: [{
						iconPath: '/static/images/xinzeng.png',
						selectedIconPath: '/static/images/xinzenged.png',
						text: '新建',
						active: false
					},
					{
						iconPath: '/static/images/xiafa.png',
						selectedIconPath: '/static/images/xiafaed.png',
						text: '下发',
						active: false
					},
					{
						iconPath: '/static/images/shanchu.png',
						selectedIconPath: '/static/images/shanchued.png',
						text: '删除',
						active: false
					}
				],
				currentData:[],
				tree: [],
				prop:{
					label:'name',
					children:'children'
				},
				proTimeStamp: '',
				treeArr: [],
				selDataFromChild: {},
				complete: '完工',
				buildSelData: {},
				draftCardList: [],
				isSelBuild: 0,
				rectificationCardList: [],
				closedCardList: [],
				draftNum: 0,
				rectificationNum: 0,
				closedNum: 0,
				cardParam: {},
				selectcard: []
			}
		},
		onLoad(option) {
			that = this
			this.cardParam = JSON.parse(option.cardParam)
			console.log(this.cardParam)
			this.currentData = JSON.parse(option.currentData)
			console.log(this.currentData)
		},
		onShow() {
			uni.$on('updateCardData',res=>{
				setTimeout(function(){
					uni.showToast({
						title: '新增卡片成功！',
						icon: 'none'
					})
				},1000)
				
			})
			uni.$on('alterCardData',res=>{
				setTimeout(function(){
					uni.showToast({
						title: '修改卡片内容成功！',
						icon: 'none'
					})
				},1000)
				
			})
			this.getStoragedata()
			this.getDraftCardFunc()
			this.getRectificationCardFunc()
			this.getClosedCardFunc()
		},
		mounted() {
		},
		onBackPress() {
			if (this.$refs.fab.isShow) {
				this.$refs.fab.close()
				return true
			}
			return false
		},
		methods: {
			onNavigationBarButtonTap() {
				this.showDrawer('showLeft')
			},
			trigger(e) {
				// console.log(e)
				this.draftCardList.forEach(item => {
					if (item.checked == true) {
						this.selectcard.push(item.noticeTimeStamp);
					}
				})
				let indexOf = e.index
				let cardParam = JSON.stringify(this.cardParam)
				if(indexOf==0){ //新建
					//跳转到新增页面
					uni.navigateTo({
						url:`AddItemInspect?currentData=${this.currentData}`+`&cardParam=${cardParam}`
					})
				}else if(indexOf==1){ //下发
					var length = that.selectcard.length;
					if (length == 0) {
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '未选择卡片'
						});
						return;
					}
					this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
					var length = this.selectcard.length;
					for (var i = 0; i < length; i++) {
						this.$refs.select_person.$emit('cardTimeStamp',that.selectcard[i])
					}
				}else if(indexOf==2){ //删除
					let that = this
					uni.showModal({
					    title: '提示',
					    content: '是否确定删除已选中卡片?',
					    success: function (res) {
					        if (res.confirm) {
					            let opts = {
					            	url: that.api+'/module_project/InspectAccept/InspectAccept.php',
					            	method: 'POST'
					            }
					            let param = {
					            	flag: 'deleteCard',
					            	cardTimeStamp:JSON.stringify(that.selectcard)
					            }
					            let isLoading = true//是否需要加载动画
					            that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					            	// console.log(res.data)
					            	if(res.data.code){
					            		that.getDraftCardFunc()
					            		uni.showToast({
					            			icon: 'none',
					            			position: 'bottom',
					            			title: '卡片已删除成功！'
					            		});
					            	}else{
					            		uni.showToast({
					            			icon: 'none',
					            			position: 'bottom',
					            			title: '请更换到网络良好的环境删除！'
					            		});
					            	}
					            	uni.hideLoading()//隐藏加载中转圈圈
					            	that.isloading = false//取消遮罩层
					            }, error => {
					            	console.log(error);
					            })
					        } else if (res.cancel) {
								uni.showToast({
									icon: 'none',
									position: 'bottom',
									title: '您已取消删除卡片！'
								});
					        }
					    }
					});
				}
			},
			//获取本地缓存
			getStoragedata() {
				let that = this
				uni.getStorage({ 
					key: 'buildInfo',
					success: function (res) {
						that.buildSelData = res.data
						that.isSelBuild = 1
					}
				});
				uni.getStorage({
					key: 'changeProRecord',
					success: function (res) {
						that.proTimeStamp = res.data.proTimeStamp
						// console.log(that.proTimeStamp)
					}
				});
				uni.getStorage({
					key:'userInfo',
					success:function(res) {
						that.currentData = res.data
						that.currentData = JSON.stringify(res.data)
						// console.log(JSON.parse(this.currentData))
					}
				})
			},
			fabClick() {
				uni.showToast({
					title: '点击了悬浮按钮',
					icon: 'none'
				})
			},
			switchBtn(hor, ver) {
				if (hor === 0) {
					this.direction = this.direction === 'horizontal' ? 'vertical' : 'horizontal'
					this.directionStr = this.direction === 'horizontal' ? '垂直' : '水平'
				} else {
					this.horizontal = hor
					this.vertical = ver
				}
				this.$forceUpdate()
			},
			//返回上页
			back() {
				uni.navigateBack({ //上一页面不刷新
					delta: 1 // 代表返回上 1 页
				})
			},
			showDrawer(e) {
				this.$refs[e].open()
			},
			// 关闭窗口
			closeDrawer(e) {
				// console.log(this.selDataFromChild)
				if(this.selDataFromChild.length==5){
					let obj = {
						section: this.selDataFromChild[1].name,
						build: this.selDataFromChild[2].name,
						floor: this.selDataFromChild[3].name,
						unit: this.selDataFromChild[4].name
					}
					uni.setStorage({
					    key: 'buildInfo',　
					    data: obj,
					    success: function (res) {
					        // console.log(res)
					    }
					})   
					this.$refs[e].close()
				}else{
					uni.showToast({
					    title: '请选中区段、栋号、楼层、单元',
					    duration: 2000,
						icon: "none"
						// image:'/static/lost.png',   //要写根路径，不要写相对路径
					});
				}
			},
			// 抽屉状态发生变化触发
			change(e, type) {
				//打开
				if(e){
					let opts = {
						url: this.api+'/module_project/InspectAccept/InspectAccept.php',
						method: 'POST'
					}
					let param = {
						flag: 'getBuildInfo',
						proTimeStamp:this.proTimeStamp
					}
					let isLoading = true//是否需要加载动画
					this.myRequest.httpRequest (opts, param,isLoading).then(res => {
						// console.log(res.data)
						uni.hideLoading()//隐藏加载中转圈圈
						this.isloading = false//取消遮罩层
						// console.log(res.data)
						if(res.data.code){
							this.createFloorFunc(res.data.data)
						}
					}, error => {
						console.log(error);
					})
				}else{ //关闭
					
				}
			},
			//数字转中文
			toChinesNum(num){
				var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
				var unit = ["", "十", "百", "千", "万"];
				num = parseInt(num);
				var getWan = function(temp){
				var strArr = temp.toString().split("").reverse();
				var newNum = "";
				for (var i = 0; i < strArr.length; i++) {
				  newNum = (i == 0 && strArr[i] == 0 ? "" : (i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? "" : changeNum[strArr[i]] + (strArr[i] == 0 ? unit[0] : unit[i]))) + newNum;
				}
				 return newNum;
			   }
			   var overWan = Math.floor(num / 10000);
			   var noWan = num % 10000; 
			   if (noWan.toString().length < 4) noWan = "0" + noWan;
			   return overWan ? getWan(overWan) + "万" + getWan(noWan) : getWan(num);
			},
			// 遍历区段楼层并存储在数组中
			createFloorFunc(data) {
				// console.log(data)
				let buildData = data
				let sectionData = this.commonFunc.Es5duplicate(data,'section')
				let floorData = data
				for(var i=0;i<sectionData.length;i++){
					var obj = {
						id: '',
						name: '',
						user: false,
						children: []
					};
					obj.id = sectionData[i].id
					obj.name = sectionData[i].section
					for(var j=0;j<buildData.length;j++){
						if(buildData[j].section == sectionData[i].section){
							let objBuild = {
								name: buildData[j].build,
								user: false,
								children: []
							};
							for(var k=0;k<obj.children.length;k++){
								if(buildData[j].build==obj.children[k].name){
									var repeatFlag = true
								}
							}
							if(repeatFlag){ //去除重复栋号
								break;
							}else{
								
								//遍历地下楼层
								var undergroundNum = parseInt(floorData[j].undergroundNumber);
								if (undergroundNum) {
									let unit_arr = floorData[j].unitName.split('||')
									var index = undergroundNum;
									for (var z = 0; z < undergroundNum + 1; z++) {
										if (z == 0) {
											var FloorName = "基础层";
										} else {
											var FloorName = "负" + this.toChinesNum(index) + "层";
											index--;
										}
										var floorObj = {
											name: FloorName,
											user: false,
											children: []
										};
										//遍历单元
										for(var x=0;x<unit_arr.length;x++){
											let unitObj = {
												name: unit_arr[x],
												user: false,
												isSel:false,
												children: []
											}
											floorObj.children.push(unitObj)
										}
										objBuild.children.push(floorObj)
									}
								}
								obj.children.push(objBuild)
								
								//遍历地上楼层
								var abovegroundNumber = parseInt(floorData[j].abovegroundNumber);
								if (abovegroundNumber) {
									let unit_arr = floorData[j].unitName.split('||')
									var index = abovegroundNumber;
									for (var z = 0; z < abovegroundNumber + 1; z++) {
										if(i==abovegroundNumber){
											var FloorName = "屋面层";
										}else{
											var FloorName = this.toChinesNum(z+1)+"层";
										}
										var floorObj = {
											name: FloorName,
											user: false,
											children: []
										};
										//遍历单元
										for(var x=0;x<unit_arr.length;x++){
											let unitObj = {
												name: unit_arr[x],
												user: false,
												isSel:false,
												children: []
											}
											floorObj.children.push(unitObj)
										}
										objBuild.children.push(floorObj)
									}
								}
								obj.children.push(objBuild)
							}
						}
					}
					this.tree.push(obj)
				}
				this.treeArr=JSON.parse(JSON.stringify(this.tree)) //防止this指向数据修改联动
				for(var i=0;i<this.treeArr.length;i++){
					this.tree[i].children = []
					let buildData = this.commonFunc.Es5duplicate(this.treeArr[i].children,'name')
					this.tree[i].children = buildData
				}
			},
			selDataFunc(data) {
				this.selDataFromChild = data
			},
			// 获取项目检查草稿卡片
			getDraftCardFunc() {
				// console.log(this.cardParam)
				this.draftCardList = []
				this.draftNum = 0
				let buildSelData = JSON.stringify(this.buildSelData)
				console.log(this.api)
				let opts = {
					url: this.api+'/module_project/InspectAccept/InspectAccept.php',
					method: 'POST'
				} 
				let param = { 
					flag: 'getDraftCard',
					proTimeStamp: this.proTimeStamp,
					measureId: this.cardParam.id  
				} 
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						console.log(res.data)
						this.draftNum = res.data.data.length
						this.draftCardList = res.data.data
						// this.createFloorFunc(res.data.data)
					}
				}, error => {
					console.log(error);
				})
			},
			// 获取检查验收整改中卡片
			getRectificationCardFunc() {
				let buildSelData = JSON.stringify(this.buildSelData)
				let opts = {
					url: this.api+'/module_project/InspectAccept/InspectAccept.php',
					method: 'POST'
				}
				let param = {
					flag: 'getRectificationCard',
					proTimeStamp: this.proTimeStamp,
					measureId: this.cardParam.id  
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						// console.log(res.data)
						this.rectificationNum = res.data.data.length
						this.rectificationCardList = res.data.data
						// this.createFloorFunc(res.data.data)
					}
				}, error => {
					console.log(error);
				})
			},
			// 获取检查验收已关闭卡片
			getClosedCardFunc() {
				let buildSelData = JSON.stringify(this.buildSelData)
				let opts = {
					url: this.api+'/module_project/InspectAccept/InspectAccept.php',
					method: 'POST'
				}
				let param = {
					flag: 'getClosedCard',
					proTimeStamp: this.proTimeStamp,
					measureId: this.cardParam.id  
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						// console.log(res.data)
						this.closedNum = res.data.data.length
						this.closedCardList = res.data.data
						// this.createFloorFunc(res.data.data)
					}
				}, error => {
					console.log(error);
				})
			},
			//跳转到项目检查页面
			// toItemInspect(item) {
			// 	// console.log(item)
			// 	this.draftCardList.forEach(item => {
			// 		if (item.checked == true) {
			// 			this.selectcard.push(item.noticeTimeStamp);
			// 		}
			// 	})
			// 	this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
			// 	var length = this.selectcard.length;
			// 	for (var i = 0; i < length; i++) {
			// 		this.$refs.select_person.$emit('cardTimeStamp',that.selectcard[i])
			// 	}
			// 	// uni.navigateTo({
			// 	// 	url:`AddInspectAccept?currentData=${this.currentData}`
			// 	// })
			// },
			// 跳转详情查看页面
			toCheckDetail(item) {
				let cardParam = JSON.stringify(this.cardParam)
				let inspectCardParam = JSON.stringify(item)
				uni.navigateTo({
					url:`CheckDetail?currentData=${this.currentData}`+`&cardParam=${cardParam}`+`&inspectCardParam=${inspectCardParam}`
				})
			},
			// 跳转到违章回复查看页面
			toViolationInvestigation(item) {
				let cardParam = JSON.stringify(this.cardParam)
				let inspectCardParam = JSON.stringify(item)
				console.log(cardParam)
				console.log(inspectCardParam)
				uni.navigateTo({
					url:`ViolationInvestigation?currentData=${this.currentData}`+`&cardParam=${cardParam}`+`&inspectCardParam=${inspectCardParam}`+`&enterFlag=normal`,
					// success: function () {
					// 	console.log(1111)
					// },
					// fail: function (e) {
					// 	console.log(222)
					// }
				})
				// uni.redirectTo({})
				console.log(11)
			},
			//下发函数
			completePush(){
				console.log(111);
				this.getDraftCardFunc() 
				this.getRectificationCardFunc()
				this.getClosedCardFunc()
			},
			checkboxChange(e, item) {
				var list = this.list,
					id = e.detail.value; //卡片id
				let box = (item.checked = !item.checked);
			},
		},
	}
</script>

<style>
	/* 头条小程序组件内不能引入字体 */
	/* #ifdef MP-TOUTIAO */
	@font-face {
		font-family: uniicons;
		font-weight: normal;
		font-style: normal;
		src: url('~@/static/uni.ttf') format('truetype');
	}

	/* #endif */

	/* #ifndef APP-NVUE */
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #ffffff;
		min-height: 100%;
		height: auto;
	}

	view {
		line-height: inherit;
	}
	
	.example {
		padding: 0 15px 15px;
	}

	/* #endif */

	.word-btn-white {
		font-size: 18px;
		color: #FFFFFF;
	}

	.word-btn {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		align-items: center;
		justify-content: center;
		border-radius: 6px;
		height: 48px;
		margin: 15px;
		background-color: #007AFF;
	}

	.close {
		padding: 15px;
	}

	/* 处理抽屉内容滚动 */
	.scroll-view-box {
		flex: 1;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
	}
	
	.btn-block{
	    position: fixed;  //将button按钮固定在页面底部
	    bottom: 5px;
		text-align:center;
		width: 80%;
		left: 50%;
		margin-left: -115px;
	}
	.button_custom{
		background-color: #BBBBBB !important;
	}

</style>
