<template>
	<view class="list">
		<!-- 页签 -->
		<view class="inv-h-w">
			<view :class="['inv-h', Inv == 0 ? 'inv-h-se' : '']" @click="Inv = 0">验收详情</view>
			<view :class="['inv-h', Inv == 1 ? 'inv-h-se' : '']" @click="Inv = 1">排查管理</view>
		</view>
		<!-- 页面0 -->
		<view v-show="Inv == 0">
			<view class="flex">
				<view class="custom-content">
					<view class="uni-list mt-1 custom-box">
						<view class="uni-list-cell">
							<view class="uni-list">
								<view class="uni-list-cell">
									<view class="uni-list-cell-left">
										选择验收人：
									</view>
									<view class="uni-list-cell-db">
										 <ld-select3 :multiple="true" :list="options1" list-key="label" value-key="value" :placeholder="placeholder" clearable v-model="acceptPerson" @change="selectPerson" @confirm = "selectAcceptPerson"></ld-select3>
									</view>
								</view>
							</view>
						</view>
					</view>
					<view class="uni-list mt-1 custom-box">
						<view class="uni-list-cell">
							<view class="uni-list-cell-left">验收部位：</view>
							<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="acceptPositon" /></view>
						</view>
					</view> 
					<view class="uni-list mt-1 custom-box">
						<view class="uni-list-cell">
							<view class="uni-list-cell-left">验收对象：</view>
							<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="acceptObject" /></view>
						</view>
					</view> 
					<view class="uni-list mt-1 custom-box">
						<view class="uni-list-cell">
							<view class="uni-list">
								<view class="uni-list-cell">
									<view class="uni-list-cell-left">
										验收流程：
									</view>
									<view class="uni-list-cell-db">
										<picker @change="acceptProcessChange" :value="acceptIndex" :range="acceptProcessArr" range-key="acceptProcessCon">
											<view class="uni-input">{{acceptProcessArr[acceptIndex].acceptProcessCon}}</view>
										</picker>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="custom-person">
				<uni-view v-if="firstLevel.length>0" nvue="true" class="uni-section">
					<uni-view class="uni-section__head">
						<uni-view class="uni-section__head-tag line"></uni-view>
					</uni-view>
					<uni-view class="uni-section__content">
						<uni-text class="uni-section__content-title distraction">
							<span>第一层级</span>
						</uni-text>
					</uni-view>
				</uni-view>
				<uni-list>
					<view v-for="(item,index) in firstLevel" :key="index">
						<view class="flex">
							<!-- <span class="iconfont icon-gongcheng icon-custom"></span> -->
							<uni-list-item :showExtraIcon="true" :extraIcon="extraIcon1" style="flex: 1;" :title="item.title" :rightText="item.isSign"></uni-list-item>
						</view>
					</view>
				</uni-list>
				<uni-view v-if="secondLevel.length>0" nvue="true" class="uni-section">
					<uni-view class="uni-section__head">
						<uni-view class="uni-section__head-tag line"></uni-view>
					</uni-view>
					<uni-view class="uni-section__content">
						<uni-text class="uni-section__content-title distraction">
							<span>第二层级</span></uni-text>
					</uni-view>
				</uni-view>
				<uni-list>
					<view v-for="(item,index) in secondLevel" :key="index">
						<view class="flex">
							<uni-list-item :showExtraIcon="true" :extraIcon="extraIcon1" style="flex: 1;" :title="item.title" :rightText="item.isSign"></uni-list-item>
						</view>
					</view>
				</uni-list>
				<uni-view v-if="thirdLevel.length>0" nvue="true" class="uni-section">
					<uni-view class="uni-section__head">
						<uni-view class="uni-section__head-tag line"></uni-view>
					</uni-view>
					<uni-view class="uni-section__content">
						<uni-text class="uni-section__content-title distraction">
							<span>第三层级</span></uni-text>
					</uni-view>
				</uni-view>
				<uni-list>
					<view v-for="(item,index) in thirdLevel" :key="index">
						<view class="flex">
							<uni-list-item :showExtraIcon="true" :extraIcon="extraIcon1" style="flex: 1;" :title="item.title" :rightText="item.isSign"></uni-list-item>
						</view>
					</view>
				</uni-list>
				<uni-view v-if="fouthLevel.length>0" nvue="true" class="uni-section">
					<uni-view class="uni-section__head">
						<uni-view class="uni-section__head-tag line"></uni-view>
					</uni-view>
					<uni-view class="uni-section__content">
						<uni-text class="uni-section__content-title distraction">
							<span>第四层级</span></uni-text>
					</uni-view>
				</uni-view>
				<uni-list>
					<view v-for="(item,index) in fouthLevel" :key="index">
						<view class="flex">
							<uni-list-item :showExtraIcon="true" :extraIcon="extraIcon1" style="flex: 1;" :title="item.title" :rightText="item.isSign"></uni-list-item>
						</view>
					</view>
				</uni-list>
			</view>
		</view>
		
		<!-- 页面1 -->
		<view v-show="Inv == 1">
			<view class="box">
				<view class="Table TableHead">
					<ul class="ul">
						<li class="li listHead">
							<view class="Left">
								检查内容
							</view>
							<view class="Right">
								剩/需
							</view>
						</li>
					</ul>
				</view>
				<view class="Table">
					<ul class="ul ulBody">
						<li class="li listBody" v-for="(item,index) in inspectData" :key="index">
							<view class="Left content">{{item.inspectContent}}</view>
							<view class="Right num">{{item.remain}}</view>
						</li>
					</ul>
				</view>
			</view>
		</view>
		<show-signature :acceptTimeStamp="acceptTimeStamp" :userid="currentData.userId" canvasId="acceptController" @close="close" @saveSign="signSign" :visible="isShow"></show-signature>
		<uni-fab :pattern="pattern"
            :content="content"
            :horizontal="horizontal"
            :vertical="vertical"
            :direction="direction"
            @trigger="trigger"></uni-fab>
	</view>
</template>

<script>
	import uniList from "@/components/uni-app/uni-list/uni-list.vue"
	import uniListItem from "@/components/uni-app/uni-list-item/uni-list-item.vue"
	import ldSelect3 from '@/components/common/ld-select/ld-select3.vue'; //多选组件
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import showSignature from '@/components/common/show-signature/show-signature.vue'
	export default {
		components: {
			uniList,
			uniListItem,
			ldSelect3,
			uniFab,
			showSignature
		},
		data() {
			return {
				Inv: 0,
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
						iconPath: '/static/images/save.png',
						selectedIconPath: '/static/images/baocuned.png',
						text: '保存',
						active: false
				},{
						iconPath: '/static/images/uploadPic.png',
						selectedIconPath: '/static/images/shangchuaned.png',
						text: '上传图片',
						active: false
				},{
						iconPath: '/static/images/push.png',
						selectedIconPath: '/static/images/fasonged.png',
						text: '推送消息',
						active: false
				},{
						iconPath: '/static/images/sign.png',
						selectedIconPath: '/static/images/qianminged.png',
						text: '签名',
						active: false 
				}],
				lists: [], // 列表数据
				cardParam: {},
				options1: [], 
				acceptPerson: [],
				acceptPositon: '',
				acceptObject: '',
				acceptIndex: 0,
				acceptProcessArr: [{acceptProcessCon:'简化流程'},{acceptProcessCon:'标准流程'},{acceptProcessCon:'特殊流程'}],
				extraIcon1: {color: 'color: rgb(143, 143, 148)',size: '16',type: 'person'},
				firstLevel: [],
				secondLevel: [],
				thirdLevel: [],
				fouthLevel: [],
				proTimeStamp: '',
				buildSelData: {},
				procedureInfo: {},
				placeholder: '现已选择0个验收人',
				projectId: '',
				allLevelPerson: {},
				inspectData: [{inspectContent:'暂无内容',remain: '0/0'}],
				acceptTimeStamp: '',
				isShow: false ,//签名,
				currentData: {}
			};
		},
		onLoad(option) {
			
			let enterFlag = option.enterFlag
			if(enterFlag=='normal'){
				// 初始化页面数据
				this.currentData = JSON.parse(option.currentData)
				this.cardParam = JSON.parse(option.cardParam)
				this.proTimeStamp = option.proTimeStamp
				this.buildSelData = JSON.parse(option.buildSelData)
				this.procedureInfo = JSON.parse(option.itemStr)
				console.log(this.currentData)
				console.log(this.cardParam)
				console.log(this.proTimeStamp)
				console.log(this.buildSelData)
				console.log(this.procedureInfo)
				//获取本地缓存
				this.getStoragedata() 
				this.getAllPersonList()
				this.getLevelPersonFunc()
				this.getInspectContent()
				this.getAcceptTimeStamp()
			}else if(enterFlag=='message'){
				this.getAcceptParam(option.cardTimeStamp)
			}
		},
		methods: {
			trigger(e) {
				// console.log(e)
				let indexOf = e.index
				// 保存
				if(indexOf==0){
					// console.log(this.allLevelPerson)
					let opts = {
						url: this.api+'/module_project/InspectAccept/AcceptCheck.php',
						method: 'POST'
					} 
					let param = {
						flag: 'personSave',
						acceptPositon: this.acceptPositon,
						acceptObject: this.acceptObject,
						measureId: this.cardParam.id,
						procedure: this.procedureInfo.content,
						inspectItem: this.cardParam.inspectItem,
						acceptProcess: this.acceptProcessArr[this.acceptIndex].acceptProcessCon,
						projectId: this.projectId,
						allLevelPerson: JSON.stringify(this.allLevelPerson)
						
					}
					let isLoading = true//是否需要加载动画
					this.myRequest.httpRequest (opts, param,isLoading).then(res => {
						console.log(res.data)
						uni.hideLoading()//隐藏加载中转圈圈
						this.isloading = false//取消遮罩层
						if(res.data.code){
							this.getLevelPersonFunc()
						} 
					}, error => {
						console.log(error);
					})
				}else if(indexOf==1){ //进入上传图片界面
					let cardParam = JSON.stringify(this.cardParam)
					let inspectCardParam = JSON.stringify(this.inspectCardParam)
					let procedureInfo = JSON.stringify(this.procedureInfo)
					uni.navigateTo({
						url:`AcceptPicDetail?currentData=${this.currentData}`+`&cardParam=${cardParam}`+`&inspectCardParam=${inspectCardParam}`+`&procedureInfo=${procedureInfo}`+`&acceptTimeStamp=${this.acceptTimeStamp}`
					})
				}else if(indexOf==2){ //推送消息
					console.log(this.procedureInfo.content)
					let that = this
					uni.showModal({
					    title: '提交质量员确认',
					    content: '是否提交质量员确认',
					    success: function (res) {
					        if (res.confirm) {
								let opts = {
									url: that.api + '/module_project/InspectAccept/AcceptCheck.php',
									method: 'POST'
								}
								let param = {
									flag: 'submitSure',
									inspectItem: that.cardParam.inspectItem,
									measureId: that.cardParam.id,
									procedure: that.procedureInfo.content,
									structurePositon: '', //结构部位
									projectId: that.projectId 
								}
								let isLoading = false
								that.myRequest.httpRequest(opts, param, isLoading).then(res => {
									console.log(res.data)
									if(res.data.code){
										uni.showToast({
											title: '验收内容已提交质量员确认！',
											icon: 'none'
										})
										that.getLevelPersonFunc()
									}
								}, error => {
								
								})
					        } else if (res.cancel) {
					            uni.showToast({
					            	title: '您已取消提交验收内容！',
					            	icon: 'none'
					            })
					        }
					    }
					});
				}else if(indexOf==3){//进入签名界面
				console.log(this.isShow)
					this.openSignModal()
				}
			},
			//获取本地缓存
			getStoragedata() {
				let that = this
				uni.getStorage({
					key: 'changeProRecord',
					success: function (res) {
						that.projectId = res.data.projectId
					}
				});
			},
			// 获取人员
			getAllPersonList() {
				this.acceptPositon = this.buildSelData.build+this.buildSelData.floor+this.buildSelData.unit
				this.acceptObject = this.currentData.username
				console.log(this.proTimeStamp)
				let opts = {
					url: this.api+'/module_project/InspectAccept/GetPeople.php',
					method: 'POST'
				}
				let param = {
					flag: 'allPerson',
					proTimeStamp: this.proTimeStamp
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						// for(var i=0;i<res.data.data.length;i++){
						for(var key in res.data.data){
							let itemArr = res.data.data[key].split('|')
							if(itemArr[3]!=''){
								let obj = {value:itemArr[3]+"||"+itemArr[2]+"："+itemArr[0],label:itemArr[2]+'：'+itemArr[0]}
								// console.log(obj)
								this.options1.push(obj)
							}
						}
					}
				}, error => {
					console.log(error);
				})

			},
			//获取层级人员
			getLevelPersonFunc() {
				const that = this;
				let opts = {
					url: this.api+'/module_project/InspectAccept/GetPeople.php',
					method: 'POST'
				}
				let param = {
					flag: 'allPerson',
					proTimeStamp: this.proTimeStamp
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						that.createSelect(res.data.data)
					}
				}, error => {
					console.log(error);
				})
			},
			//选择验收人
			selectAcceptPerson(val){
				console.log(val)
				console.log(this.acceptPerson)
				this.acceptPerson = val 
				this.firstLevel = []
				this.secondLevel = []
				this.thirdLevel = []
				this.fouthLevel = []
				var n=0;
				for(var i=0;i<val.length;i++){
					let personArr = val[i].split("||")
					let post = personArr[1].split("：")[0]
					if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="简化流程"){
						if(post=="质量员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="栋号长"||post=="高级施工员"||post=="施工员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="标准流程"){
						if(post=="质量员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="栋号长"||post=="高级施工员"||post=="施工员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="项目总工"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.thirdLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="特殊流程"){
						if(post=="质量员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="栋号长"||post=="高级施工员"||post=="施工员"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							console.log(this.secondLevel);
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="项目总工"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.thirdLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							}) 
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(post=="项目经理"){
							// this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.fouthLevel.push({
								userId: personArr[0],
								title: personArr[1],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}
				}
				this.allLevelPerson["first"] = this.firstLevel
				this.allLevelPerson["second"] = this.secondLevel
				this.allLevelPerson["third"] = this.thirdLevel
				this.allLevelPerson["fouth"] = this.fouthLevel
				
			},
			selectPerson(val) {
				this.acceptPerson = val
			},
			//验收流程改变触发
			acceptProcessChange(e) {
				this.acceptIndex = e.target.value
			},
			//判断数据库是否有数据 有数据调用createTwo，没数据调用createOne
			createSelect(data){
				var temporaryVal = data;
				let opts = {
					url: this.api+'/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				}
				let param = {
					flag: 'getPersonInfo',
					measureId: this.cardParam.id,
					inspectItem: this.cardParam.inspectItem,
					procedure: this.procedureInfo.content
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					this.firstLevel = []
					this.secondLevel = []
					this.thirdLevel = []
					this.fouthLevel = []
					if(res.data.code){
						if(res.data.acceptProcess=="简化流程"){
							this.acceptIndex = 0
						}else if(res.data.acceptProcess=="标准流程"){
							this.acceptIndex = 1
						}else if(res.data.acceptProcess=="特殊流程"){
							this.acceptIndex = 2
						}
						var personName = res.data.personName;
						var acceptProcess = res.data.acceptProcess;
						var acceptTimeStamp = res.data.acceptTimeStamp;
						this.createTwo(res.data.item,temporaryVal,personName,acceptProcess,res.data.opinion,acceptTimeStamp);
					}else{
						this.createOne(temporaryVal);
					}
				}, error => {
					console.log(error);
				})
				
			},
			// 初次创建
			createOne(data) {
				var perArr = new Array();
				var n=0;
				// console.log(data)
				for(var key in data){
					var obj = data[key]
					var personArr = obj.split('|')
					// var name = Object.keys(obj)[0];
					var phone = personArr[1]
					if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="简化流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="标准流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(personArr[2]=="项目总工"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.thirdLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="特殊流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.firstLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.secondLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(personArr[2]=="项目总工"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.thirdLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}else if(personArr[2]=="项目经理"&&personArr[3]!=""){
							this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
							this.fouthLevel.push({
								userId: personArr[3],
								title: personArr[2]+'：'+personArr[0],
								isSign: '未签名'
							})  
							n++;
							this.placeholder = "现已选择"+n+"个验收人"
						}
					}
				}
				
				
				this.allLevelPerson["first"] = this.firstLevel
				this.allLevelPerson["second"] = this.secondLevel
				this.allLevelPerson["third"] = this.thirdLevel
				this.allLevelPerson["fouth"] = this.fouthLevel
				let opts = {
					url: this.api+'/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				} 
				let param = {
					flag: 'personSave',
					acceptPositon: this.acceptPositon,
					acceptObject: this.acceptObject,
					measureId: this.cardParam.id,
					procedure: this.procedureInfo.content,
					inspectItem: this.cardParam.inspectItem,
					acceptProcess: this.acceptProcessArr[this.acceptIndex].acceptProcessCon,
					projectId: this.projectId,
					allLevelPerson: JSON.stringify(this.allLevelPerson)
					
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
					if(res.data.code){
						
					} 
				}, error => {
					console.log(error);
				})
			},
			//第二次创建
			createTwo(item,temporaryVal,personName,acceptProcess,opinion,acceptTimeStamp) {
				var n=0; 
				console.log(item);
				console.log(temporaryVal);
				for(var key in temporaryVal){
					var obj = temporaryVal[key]
					var personArr = obj.split('|')
					// var name = Object.keys(obj)[0];
					var phone = personArr[1]
					if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="简化流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.firstLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.secondLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="标准流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.firstLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.secondLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if(personArr[2]=="项目总工"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.thirdLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}
					}else if(this.acceptProcessArr[this.acceptIndex].acceptProcessCon=="特殊流程"){
						if(personArr[2]=="质量员"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.firstLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if((personArr[2]=="栋号长"||personArr[2]=="高级施工员"||personArr[2]=="施工员")&&personArr[3]!=""){
							for(var key1 in item){
								console.log(item[key1]);
								console.log(personArr[1]);
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.secondLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									console.log(this.secondLevel);
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if(personArr[2]=="项目总工"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.thirdLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}else if(personArr[2]=="项目经理"&&personArr[3]!=""){
							for(var key1 in item){
								if(personArr[1]==item[key1]){
									this.acceptPerson.push(personArr[3]+'||'+personArr[2]+'：'+personArr[0])
									this.fouthLevel.push({
										userId: personArr[3],
										title: personArr[2]+'：'+personArr[0],
										isSign: '未签名'
									})  
									n++;
									this.placeholder = "现已选择"+n+"个验收人"
								}
							}
						}
					}
				}
				this.allLevelPerson["first"] = this.firstLevel
				this.allLevelPerson["second"] = this.secondLevel
				this.allLevelPerson["third"] = this.thirdLevel
				this.allLevelPerson["fouth"] = this.fouthLevel
			},
			//获取检查内容
			getInspectContent() {
				let opts = {
					url: this.api+'/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				} 
				console.log(this.procedureInfo);
				let param = {
					flag: 'getInspectContent',
					inspectTimeStamp: this.procedureInfo.inspectStr.split('|')[1]
					
				}
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(111);
					console.log(res.data)  
					uni.hideLoading()//隐藏加载中转圈圈 
					this.isloading = false//取消遮罩层
					if(res.data.code){
						// inspectData: [{inspectContent:'暂无内容',remain: '0/0'}]
						this.inspectData = []
						var item = res.data.item; 
						console.log(item)  
						for(var key in item){
							var length = item[key].length;
							var obj = item[key];
							var idArr = new Array;
							var jcnrArr = new Array;
							for(var i=0,j=0;i<length;i++){
								var flag = obj[i].split("&"); 
								if(flag[2]=="通过"){
									j++;
								}
								idArr[i] = flag[3]; 
								jcnrArr[i] = flag[0];
							}  
							var idStr = idArr.join(",");
							var jcnrStr = jcnrArr.join("");
							this.inspectData.push({
								inspectContent:jcnrStr,
								remain: (length-j) +'/'+ length
							})
						}
					} 
				}, error => {
					console.log(error);
				})
			},
			//获取验收时间戳
			getAcceptTimeStamp() {
				console.log(this.cardParam.id)
				console.log(this.procedureInfo.content)
				let opts = {
					url: this.api + '/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				}
				let param = {
					flag: 'getAcceptTimeStamp',
					measureId: this.cardParam.id,
					procedure: this.procedureInfo.content
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						this.acceptTimeStamp = res.data.data
						// console.log(this.acceptTimeStamp)
					}
				}, error => {
				
				})
			},
			//打开签名模态框
			openSignModal() {
				this.isShow = true
			},
			// 关闭签名模态框
			close() {
				this.isShow = false;
			},
			signSign() {
				this.isShow = false;
			},
			//通知代办进入前获取参数
			getAcceptParam(cardTimeStamp) {
				let that = this
				let opts = {
					url: this.api + '/module_project/InspectAccept/AcceptCheck.php',
					method: 'POST'
				}
				let param = {
					flag: 'getAcceptParam',
					acceptCardTimeStamp: cardTimeStamp
				}
				let isLoading = false //是否需要加载动画
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					if(res.data.data.accept!=undefined){
						this.cardParam = res.data.data.accept
					}
					if(res.data.data.inspect!=undefined){
						this.procedureInfo.content = res.data.data.inspect.content
						let obj = res.data.data.inspect.timeStamp
						for(var key in obj){  
						  this.procedureInfo.inspectStr = obj[key]
						}  
					}
					// console.log(this.procedureInfo.inspectStr)
					this.getStorageMessageData()
					setTimeout(function(){
						that.getAllPersonList()
						that.getLevelPersonFunc()
						that.getInspectContent()
						that.getAcceptTimeStamp()
					},1500)
					
					// uni.hideLoading() //隐藏加载中转圈圈
				}, error => {
					console.log(error);
				})
			},
			//消息代办进入
			getStorageMessageData(){
				let that = this
				uni.getStorage({
					key:'userInfo',
					success:function(res) {
						that.currentData = res.data
					}
				})
				uni.getStorage({
					key: 'changeProRecord',
					success: function (res) {
						that.proTimeStamp = res.data.proTimeStamp
						console.log(that.proTimeStamp)
					}
				});
				uni.getStorage({
					key: 'buildInfo',
					success: function (res) {
						that.buildSelData = res.data
						that.isSelBuild = 1
					}
				});
			}
		}
	};
</script>

<style lang="scss">
	@import '@/common/uni-ui.scss';

	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4;
		min-height: 100%;
		height: auto;
	}
	
	.inv-h-w {
		background-color: #ffffff;
		height: 80upx;
		display: flex;
	}
	
	.inv-h {
		font-size: 30upx;
		flex: 1;
		text-align: center;
		color: #c9c9c9;
		height: 80upx;
		line-height: 80upx;
	}
	.inv-h-se {
		color: #5ba7ff;
		border-bottom: 4upx solid #5ba7ff;
	}
	
	.mg-10 {
		margin-top: 10px;
	}
	
	.tips {
		color: #67c23a;
		font-size: 14px;
		line-height: 40px;
		text-align: center;
		background-color: #f0f9eb;
		height: 0;
		opacity: 0;
		transform: translateY(-100%);
		transition: all 0.3s;
	}
	
	.tips-ani {
		transform: translateY(0);
		height: 40px;
		opacity: 1;
	}
	
	.content {
		width: 100%;
		display: flex;
		justify-content: center;
	}
	
	.list-picture {
		width: 100%;
		height: 145px;
	}
	
	.thumb-image {
		width: 100%;
		height: 100%;
	}
	
	.ellipsis {
		display: flex;
		overflow: hidden;
	}
	
	.uni-ellipsis-1 {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	
	.uni-ellipsis-2 {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	
	.uni-title1{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    font-size: 13px;
	    font-weight: bold;
	    color: #e71010;
	}
	
	.uni-title{
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
		margin-bottom: 0;
		padding: 0 0;
	    font-size: 13px;
	    font-weight: bold;
	    color: #3b4144;
	}
	
	.uni-list-box {
	    -webkit-box-flex: 1;
	    -webkit-flex: 1;
	    flex: 1;
	    margin-top: 0px;
	}
	
	.uni-note {
		margin-top: 0;
	}
	
	.custom-content {
		border: 1px solid #ddd;
		margin: 3px auto;
		width: 90%;
		border-radius: 10px;
	}
	
	.custom-box {
		width: 98%;
		margin: 3px auto;
	}
	
	.uni-section__head {
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-pack: center;
	    -webkit-justify-content: center;
	    justify-content: center;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	    margin-right: 10px;
	}
	
	.line {
	    height: 15px;
	    background-color: silver;
	    border-radius: 5px;
	    width: 3px;
	}
	
	.uni-section {
	    position: relative;
	    display: -webkit-box;
	    display: -webkit-flex;
	    display: flex;
	    margin-top: 4px;
	    -webkit-box-orient: horizontal;
	    -webkit-box-direction: normal;
	    -webkit-flex-direction: row;
	    flex-direction: row;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	    padding: 0 10px;
	    height: 50px;
	    background-color: #f8f8f8;
	    font-weight: 400;
		border-radius: 10px 10px 0 0;
	}
	
	.icon-custom {
	    align-items: center;
	    margin-top: 10px;
	    padding-left: 10px;
	}
	
	.custom-person {
		width: 90%;
		margin: 10px auto;
		border: 1px solid #ddd;
		border-radius: 10px;
	}
	
	.box {
		width: 100%;
		margin: 0 auto;
	}
	
	.Table{
		width: 94%;
		margin-left: 3%;
		display: flex;
		flex-direction: row;
		overflow-y: scroll;
	}
	
	.TableHead{
		margin-top: 10px;
	}
	
	.Table .ul{
		list-style: none;
		width: 100%;
		margin: 0;
		padding: 0;
		background-color: #fff;
	}
	
	.Table .ul .li{
		width: 100%;
		display: flex;
		flex-direction: row;
		text-align: center;
	}
	
	.listHead{
		border: #000000 solid 1px;
		height: 40px;
		line-height: 40px;
	}
	
	.Left{
		width: 70%;
		border-right: #000000 solid 1px;
	}
	
	.Right{
		width: 30%;
		border-right: #000000 solid 2px;
	}
	
	.num{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
	}
	
	.listBody{
		border-style: none solid solid solid;
		border-width: 1px;
	}
	
</style>
