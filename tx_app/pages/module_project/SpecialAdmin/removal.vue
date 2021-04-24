<template>
	<view>
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">拆模({{this.list.length}})</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">完成({{this.list1.length}})</view>
		</view>
		<view v-show="Inv == 0">
			<view class="example-body">
				<!-- 拆模卡片 -->
				<view v-for="item in list" :key="item.id" class="example-box">
					<uni-card :is-shadow="isShow" :title="item.projectName" @click="checkremoval(item.id)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">
								创建人:{{ item.creater }}
								<view>
									<checkbox-group @change="checkboxChange($event, item)"><checkbox :value="item.id" :checked="item.checked" /></checkbox-group>
								</view>
							</view>
							<view class="uni-list-cell uni-list-card">混凝土强度等级:{{ item.concreteGrade }}</view>
							<view class="uni-list-cell uni-list-card">拆模部位:{{ item.removalPosition }}</view>
							<view class=" uni-list-card">浇筑时间:{{ item.pouringDate }}</view>
						</view>
					</uni-card>
				</view>
			</view>

			<!-- 悬浮按钮 -->
			<uni-fab
				ref="fab"
				:pattern="pattern"
				style="width: 24px;"
				:content="content"
				:horizontal="horizontal"
				:vertical="vertical"
				:direction="direction"
				@trigger="trigger"
				@fabClick="fabClick"
			/>
		</view>

		<view class="" v-show="Inv == 1">
			<view class="example-body">
				<!-- 完成拆模卡片 -->
				<view v-for="item in list1" :key="item.id" class="example-box">
					<uni-card :complete="complete" :is-shadow="isShow" :title="item.projectName" @click="checkremoval(item.id)">
						<view class="content-box-text">
							<view class="uni-list-cell uni-list-card">创建人:{{ item.creater }}</view>
							<view class="uni-list-cell uni-list-card">混凝土强度等级:{{ item.concreteGrade }}</view>
							<view class="uni-list-cell uni-list-card">拆模部位:{{ item.removalPosition }}</view>
							<view class=" uni-list-card">浇筑时间:{{ item.pouringDate }}</view>
						</view>
					</uni-card>
				</view>
			</view>
		</view>

		<!-- 抽屉 -->
		<view class="example-body">
			<uni-drawer ref="showLeft" mode="left" :width="300" @change="change($event, 'showLeft')">
				<view class="close">
					<view class="scroll-view">
						<scroll-view class="scroll-view-box" scroll-y="true">
							<view class="info-content"><buildTree :props="prop" @sendChildData="selDataFunc" :isCheck="true" :trees="tree"></buildTree></view>
							<view class="closeCustom">
								<view class="btn-block">
									<view @click="closeDrawer('showLeft')"><button type="primary">选择楼栋信息</button></view>
								</view>
							</view>
						</scroll-view>
					</view>
				</view>
			</uni-drawer>
		</view>
		<messagepush ref="select_person" :flag="'removal'" :person="'allPerson'" @refresh="refresh"></messagepush>
	</view>
</template>

<script>
import uniCard from '@/components/uni-app/uni-card/uni-card.vue';
import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue';
import uniDrawer from '@/components/uni-app/uni-drawer/uni-drawer.vue';
import buildTree from '@/components/common/build-tree/build-tree.vue';
import uniToast from '@/components/uni-app/uni-toast/uni-toast.vue';
import messagepush from '@/pages/module_project/SpecialAdmin/message-push/message-push.vue';
export default {
	data() {
		return {
			tree: [],
			prop: {
				label: 'name',
				children: 'children'
			},
			proTimeStamp: '',
			treeArr: [],
			selDataFromChild: {},
			Inv: 0,
			list: [], //拆模卡片数据
			list1: [], //完成拆模卡片数据
			isShow: false, //多选框
			istrue: true, //按钮
			selectcard: [], //选中的卡片id
			title: 'uni-fab',
			directionStr: '垂直',
			horizontal: 'right',
			vertical: 'bottom',
			direction: 'horizontal',
			complete: '', //如果值为完工,卡屏第一栏变灰色
			buildSelData: {},
			isSelBuild: 0,
			pattern: {
				color: '#7A7E83',
				backgroundColor: '#fff',
				selectedColor: '#007AFF',
				buttonColor: '#007AFF'
			},
			content: [
				{
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
			]
		};
	},
	components: {
		uniFab,
		uniDrawer,
		uniCard,
		buildTree,
		messagepush
	},
	// 监听原生导航栏
	onNavigationBarButtonTap(e) {
		if (e.index == 0) {
			this.showDrawer('showLeft');
		}
	},
	onShow() {
		let that = this
		this.getStoragedata()
		// 获取缓存数据后再调用函数
		setTimeout(function(){
			that.getCard();
		},100)
	},
	methods: {
		getCard(){
			let buildSelData = JSON.stringify(this.buildSelData)
			console.log(buildSelData);
			//获取拆模卡片数据
			let opts = {
				url: this.api + '/module_project/SpecialAdmin/removal.php',
				method: 'POST'
			}
			let param = {
				flag: 'getremoval',
				buildSelData: buildSelData,
				isSelBuild: this.isSelBuild
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				if ((res.data.status = 'success')) {
					this.list = [];
					let data = res.data.data;
					let length = res.data.data.length;
					for (var i = 0; i < length; i++) {
						this.$set(data[i], 'checked', false);
						this.list.push(data[i]);
					}
				}
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
			}, error => {
				console.log(error);
			})
			
			//获取完成卡片拆模数据
			let opts1 = {
				url: this.api + '/module_project/SpecialAdmin/removal.php',
				method: 'POST'
			}
			let param1 = {
				flag: 'CompleteRemoval',
				buildSelData: buildSelData,
				isSelBuild: this.isSelBuild
			}
			let isLoading1 = true//是否需要加载动画
			this.myRequest.httpRequest (opts1, param1,isLoading1).then(res => {
				if ((res.data.status = 'success')) {
					this.list1 = [];
					let data = res.data.data;
					let length = res.data.data.length;
					for (var i = 0; i < length; i++) {
						this.list1.push(data[i]);
					}
					this.complete = '完工';
				}
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading1 = false//取消遮罩层
			}, error => {
				console.log(error);
			})

		},
		//刷新页面
		refresh(){
			uni.redirectTo({
				url: 'removal'
			});
		},
		//获取本地缓存
		getStoragedata() {
			let that = this;
			uni.getStorage({
				key: 'buildInfo',
				success: function(res) {
					that.buildSelData = res.data;
					that.isSelBuild = 1;
				}
			});
			uni.getStorage({
				key: 'userInfo',
				success: function(res) {
					that.proTimeStamp = res.data.proTimeStamp;
					that.currentData = res.data;
					that.currentData = JSON.stringify(res.data);
					// console.log(JSON.parse(this.currentData))
				}
			});
		},
		showDrawer(e) {
			this.$refs[e].open();
		},
		// 关闭窗口
		closeDrawer(e) {
			let that = this
			// console.log(this.selDataFromChild)
			if (this.selDataFromChild.length == 5) {
				let obj = {
					section: this.selDataFromChild[1].name,
					build: this.selDataFromChild[2].name,
					floor: this.selDataFromChild[3].name,
					unit: this.selDataFromChild[4].name
				};
				uni.setStorage({
					key: 'buildInfo',
					data: obj,
					success: function(res) {
						that.getStoragedata()
					}
				});
				//切换工程定位
				setTimeout(function(){
					that.getCard();
				},1000)
				this.$refs[e].close();
			} else {
				uni.showToast({
					title: '请选中区段、栋号、楼层、单元',
					duration: 2000,
					icon: 'none'
					// image:'/static/lost.png',   //要写根路径，不要写相对路径
				});
			}
		},
		// 抽屉状态发生变化触发
		change(e, type) {
			//打开
			if (e) {
				let opts = {
					url: this.api + '/module_project/InspectAccept/InspectAccept.php',
					method: 'POST'
				};
				let param = {
					flag: 'getBuildInfo',
					proTimeStamp: this.proTimeStamp
				};
				let isLoading = true; //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(
					res => {
						// console.log(res.data)
						uni.hideLoading(); //隐藏加载中转圈圈
						this.isloading = false; //取消遮罩层
						console.log(res.data);
						if (res.data.code) {
							setTimeout(() => {
								this.createFloorFunc(res.data.data);
							}, 100);
						}
					},
					error => {
						console.log(error);
					}
				);
			} else {
				//关闭
			}
		},
		//数字转中文
		toChinesNum(num) {
			var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
			var unit = ['', '十', '百', '千', '万'];
			num = parseInt(num);
			var getWan = function(temp) {
				var strArr = temp
					.toString()
					.split('')
					.reverse();
				var newNum = '';
				for (var i = 0; i < strArr.length; i++) {
					newNum =
						(i == 0 && strArr[i] == 0 ? '' : i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? '' : changeNum[strArr[i]] + (strArr[i] == 0 ? unit[0] : unit[i])) + newNum;
				}
				return newNum;
			};
			var overWan = Math.floor(num / 10000);
			var noWan = num % 10000;
			if (noWan.toString().length < 4) noWan = '0' + noWan;
			return overWan ? getWan(overWan) + '万' + getWan(noWan) : getWan(num);
		},
		// 遍历区段楼层并存储在数组中
		createFloorFunc(data) {
			// console.log(data)
			let buildData = data;
			let sectionData = this.commonFunc.Es5duplicate(data, 'section');
			let floorData = data;
			for (var i = 0; i < sectionData.length; i++) {
				var obj = {
					id: '',
					name: '',
					user: false,
					children: []
				};
				obj.id = sectionData[i].id;
				obj.name = sectionData[i].section;
				for (var j = 0; j < buildData.length; j++) {
					if (buildData[j].section == sectionData[i].section) {
						let objBuild = {
							name: buildData[j].build,
							user: false,
							children: []
						};
						for (var k = 0; k < obj.children.length; k++) {
							if (buildData[j].build == obj.children[k].name) {
								var repeatFlag = true;
							}
						}
						if (repeatFlag) {
							//去除重复栋号
							break;
						} else {
							//遍历地下楼层
							var undergroundNum = parseInt(floorData[j].undergroundNumber);
							if (undergroundNum) {
								let unit_arr = floorData[j].unitName.split('||');
								var index = undergroundNum;
								for (var z = 0; z < undergroundNum + 1; z++) {
									if (z == 0) {
										var FloorName = '基础层';
									} else {
										var FloorName = '负' + this.toChinesNum(index) + '层';
										index--;
									}
									var floorObj = {
										name: FloorName,
										user: false,
										children: []
									};
									//遍历单元
									for (var x = 0; x < unit_arr.length; x++) {
										let unitObj = {
											name: unit_arr[x],
											user: false,
											isSel: false,
											children: []
										};
										floorObj.children.push(unitObj);
									}
									objBuild.children.push(floorObj);
								}
							}
							obj.children.push(objBuild);

							//遍历地上楼层
							var abovegroundNumber = parseInt(floorData[j].abovegroundNumber);
							if (abovegroundNumber) {
								let unit_arr = floorData[j].unitName.split('||');
								var index = abovegroundNumber;
								for (var z = 0; z < abovegroundNumber + 1; z++) {
									if (i == abovegroundNumber) {
										var FloorName = '屋面层';
									} else {
										var FloorName = this.toChinesNum(z + 1) + '层';
									}
									var floorObj = {
										name: FloorName,
										user: false,
										children: []
									};
									//遍历单元
									for (var x = 0; x < unit_arr.length; x++) {
										let unitObj = {
											name: unit_arr[x],
											user: false,
											isSel: false,
											children: []
										};
										floorObj.children.push(unitObj);
									}
									objBuild.children.push(floorObj);
								}
							}
							obj.children.push(objBuild);
						}
					}
				}
				this.tree.push(obj);
			}
			this.treeArr = JSON.parse(JSON.stringify(this.tree)); //防止this指向数据修改联动
			for (var i = 0; i < this.treeArr.length; i++) {
				this.tree[i].children = [];
				let buildData = this.commonFunc.Es5duplicate(this.treeArr[i].children, 'name');
				this.tree[i].children = buildData;
			}
		},
		selDataFunc(data) {
			this.selDataFromChild = data;
		},
		checkboxChange(e, item) {
			var list = this.list,
				id = e.detail.value; //卡片id
			let box = (item.checked = !item.checked);
		},
		//拆模卡片悬浮按钮
		trigger(e) {
			let indexOf = e.index;
			if (indexOf != '0') {
				var that = this;
				// 循环遍历ID
				that.selectcard = [];
				that.list.forEach(item => {
					if (item.checked == true) {
						that.selectcard.push(item.id);
					}
				});
				var length = that.selectcard.length;
				if (length == 0) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '未选择卡片'
					});
					return;
				}
			}
			switch (indexOf) {
				case 0: //新建
					uni.navigateTo({
						url: 'Create_removal'
					});
					break;
				case 1: //下发
					this.$refs.select_person.toggle('bottom') // 直接调用子组件方法
					for (var i = 0; i < length; i++) {
						this.$refs.select_person.$emit('cardId',that.selectcard[i])
					}
					break;
				case 2: //删除
					uni.showModal({
						title: '提示',
						content: '是否确认删除',
						success: function(res) {
							if (res.confirm) {
								console.log(length);
								for (var i = 0; i < length; i++) {
									uni.request({
										url: that.api + '/module_project/SpecialAdmin/removal.php',
										data: {
											flag: 'deleteRemoval',
											id: that.selectcard[i]
										},
										method: 'POST',
										sslVerify: false,
										dataType: 'json',
										header: {
											'content-type': 'application/x-www-form-urlencoded' //level头信息
										},
										success: res => {
											console.log(res);
											if (res.data.states != 'success') {
												uni.showToast({
													icon: 'none',
													position: 'bottom',
													title: '删除失败'
												});
												return;
											}
										},
										fail: error => {
											return;
										}
									});
								}
								uni.showToast({
									icon: 'none',
									position: 'bottom',
									title: '删除成功',
									success: res => {
										//刷新本页面
										setTimeout(function() {
											uni.redirectTo({
												url: 'removal'
											});
										}, 1000);
									}
								});
							}
						}
					});
					break;
			}
		},
		// 点击了悬浮按钮
		fabClick() {},
		//切换列表
		changeTab(Inv) {
			that.navIdx = Inv;
		},
		//查看拆模卡片详情
		checkremoval(e) {
			let id = e;
			uni.navigateTo({
				url: 'RemovalDetails?id=' + id
			});
		},
	}
};
</script>

<style>
</style>
