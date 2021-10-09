<template>
    <view>
		<view v-if="isshow">
			<view class="uni-title uni-common-pl"></view>
			<view class="uni-list">
			    <view class="uni-list-cell">
			        <view class="uni-list-cell-left">
			            选择公司
			        </view>
			        <view class="uni-list-cell-db">
			            <picker @change="pickCompany" :value="companyIndex" :range="companyList">
			                <view class="uni-input">{{companyList[companyIndex]}}</view>
			            </picker>
			        </view>
			    </view>
			</view>
		</view>
		
		<view class="uni-title uni-common-pl"></view>
		<view class="uni-list">
		    <view class="uni-list-cell">
		        <view class="uni-list-cell-left">
		            选择项目：
		        </view>
		        <view class="uni-list-cell-db">
		            <picker @change="pickProject" :value="projectIndex" :range="projectNameList">
		                <view class="uni-input">{{projectNameList[projectIndex]}}</view>
		            </picker>
		        </view>
		    </view>
		</view>
		
		<view class="uni-title uni-common-pl"></view>
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">
					区段栋号：
				</view>
				<view class="uni-list-cell-db">
					<view class="list-item" @tap="handleTap('picker2')">
						<view class="item-content">
							<view class="item-value">
								<!-- <text class="item-label">{{ label2 }}</text> -->
								<view v-if="!label2" class="uni-input">请选择区段栋号</view>
								<view v-if="label2" class="uni-input">{{label2}}</view>
							</view>
						</view>
						<lb-picker ref="picker2" v-model="value2" mode="multiSelector" :list="list2" :level="2" :dataset="{ name: 'label2' }"
						 :formatter="formatter" @change="handleChange2" @confirm="handleConfirm2" @cancel="handleCancel2">
						</lb-picker>
					</view>
				</view>
			</view>
		</view>
		
		<view class="uni-title uni-common-pl"></view>
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">
					楼层单元：
				</view>
				<view class="uni-list-cell-db">
					<view class="list-item" @tap="handleTap('picker3')">
						<view class="item-content">
							<view class="item-value">
								<!-- <text class="item-label">{{ label2 }}</text> -->
								<view v-if="!label3" class="uni-input">请选择楼层单元</view>
								<view v-if="label3" class="uni-input">{{label3}}</view>
							</view>
						</view>
						<lb-picker ref="picker3" v-model="value3" mode="multiSelector" :list="list3" :level="2" :dataset="{ name: 'label3' }"
						 :formatter="formatter" @change="handleChange3" @confirm="handleConfirm3" @cancel="handleCancel3">
						</lb-picker>
					</view>
				</view>
			</view>
		</view>
		
		<button type="primary" class="flex align-center justify-center button" @click="submint()">确定</button>
		
    </view>
</template>

<script>
	import LbPicker from '../../../components/common/lb-picker'
	export default {
		components: {
			LbPicker
		},
	    data() {
	        return {
	            companyList: ['选择分公司'],
	            companyIndex: 0,
				projectNameList: ['请选择项目'],
				projectList:[],
				projectIndex: 0,
				isshow:Boolean,
				fgs:'',
				projectName:'',
				projectId:'',
				database:'',
				isSelectPro:false,
				companyName:'',
				level:'',
				requestFlag:'',
				userInfo:[],
				proTimeStamp:'',
				value2: [],
				label2: '',
				list2: [],
				value3: [],
				label3: '',
				list3: [],
	        }
	    },
		watch:{
			// companyList:'getComProject'
		},
		mounted() {
			console.log(111);
			this.getCompany()
        },
		onReady() {
			console.log(222);
			this.$nextTick(() => {
				// 此处可以调用getColumnsInfo方法获取默认值的选项信息
				console.log(this.value2)
				const info2 = this.$refs.picker2.getColumnsInfo(this.value2)
				const info3 = this.$refs.picker3.getColumnsInfo(this.value3)
				console.log(info2)
				console.log(info3) 
				this.label2 = info2.item.map(m => m.label).join('-')
				this.label3 = info3.item.map(m => m.label).join('-')
				console.log(this.label2)
			})
		},
		onLoad: function(option){
			console.log(333);
			if(option.isShowComView==='false'){ // 判断是否为项目层级
				this.isshow = false
			}else{
				this.isshow = true
			}
			uni.getStorage({
				key: 'userInfo',
				success:(res) => {
					console.log(res.data)
					this.userInfo = res.data
					this.level = res.data.level
					if(this.level==='总公司'){
						this.requestFlag ='getCompany'//获取所有分公司
					}else{
						this.requestFlag = 'getProject'//直接账号所在获取工程
						this.getProject()
					}
					 
					this.userInfo = res.data
					this.level = res.data.level
					this.projectName = res.data.projectName
					this.proTimeStamp = res.data.proTimeStamp
					
				},
				fail: (error) => {
					console.log(error);
				}
			})
			// uni.getStorage({
			// 	key:'changeProRecord',
			// 	success:(res)=>{
			// 		this.companyName = res.data.companyName
			// 		this.companyIndex = res.data.companyIndex
			// 		if(this.level==='总公司'){
			// 			this.requestFlag ='getCompany'//获取所有分公司
			// 			this.isSelectPro = true
			// 		}else if(this.level==='项目部'){
			// 			this.projectNameList.push(res.data.projectName)
			// 			this.projectList.push({
			// 				projectId:res.data.projectId,
			// 				proTimeStamp:res.data.proTimeStamp,
			// 				companyName:res.data.companyName
			// 			})
			// 		}
			// 	}
			// })
		},
	    methods: {
			handleTap(picker) {
				this.$refs[picker].show()
			},
			formatter({
				item,
				rowIndex,
				columnIndex
			}) {
				return `${item.value}`
			},
			getCompany(){
				const that = this;
				if(this.level==='分公司'){
					this.companyName = this.userInfo.companyName
					this.companyList.push(this.companyName)
					return
				}
				// console.log(this.requestFlag)
				uni.request({
					url: this.api + '/index/change_project/Project.php',
					data: {
						flag:this.requestFlag
					},
					method: 'POST',
					sslVerify:false,
					dataType: 'json',
					header: {
						'content-type': 'application/x-www-form-urlencoded', //level头信息
					},
					success: (res) => {
						// console.log(res)
						let length = res.data.data.length
						if(this.level=='总公司'||this.level=='分公司'){
							if(length>=1){
								for(var i =0;i<length;i++){
									this.companyList.push(res.data.data[i].companyName)
								}
							}
						}else{
							
						}
					},
					fail: (error) => {
						console.log(error);
					}
				});
			},
			//获取公司对应工程
			getComProject(){
				uni.request({
					url: this.api + '/index/change_project/Project.php',
					data: {
						flag:'getComPro',//选择分公司后获取工程
						company:this.companyName
					},
					method: 'POST',
					sslVerify:false,
					dataType: 'json',
					header: {
						'content-type': 'application/x-www-form-urlencoded', //level头信息
					},
					success: (res) => {
						console.log(res.data);
						let length = res.data.data.length
						let data = this.commonFunc.Es5duplicate(res.data.data,'projectName')
						// this.projectNameList = ['']
						this.projectList = ['']
						if(length>=1){
							for(var i =0;i<length;i++){ 
								this.projectNameList.push(data[i].projectName)
								this.projectList.push({
									projectId:data[i].projectId,
									proTimeStamp:data[i].timeStamp,
									companyName:this.companyName,
									database:data[i].violationStandard
								})
							}
						}
					},
					fail: (error) => {
						console.log(error);
					}
				});
			},
			//获取项目
			getProject(){
				// console.log(this.requestFlag)
				let userInfo = this.userInfo.username+'/'+this.userInfo.phone+'/'+this.userInfo.companyName+'/'+this.userInfo.userId
				// console.log(userInfo)
				uni.request({
					url: this.api + '/index/change_project/Project.php',
					data: {
						flag:this.requestFlag,
						userInfo:userInfo
					},
					method: 'POST',
					sslVerify:false,
					dataType: 'json',
					header: {
						'content-type': 'application/x-www-form-urlencoded', //level头信息
					},
					success: (res) => {
						console.log(res.data.data);
						let length = res.data.data.length
						let data = this.commonFunc.Es5duplicate(res.data.data,'projectName')
						if(this.projectNameList!=''){//选中缓存中的
							if(length>=1){
								for(var i =1;i<length;i++){
									console.log(data[i-1].projectName)
									if(data[i-1].projectName==this.projectNameList[0]){
										
									}else{
										this.projectNameList.push(data[i-1].projectName)
									}
									if(data[i-1].projectId==this.projectList[0].projectId){
										
									}else{
										this.projectList.push({
											projectId:data[i-1].projectId,
											proTimeStamp:data[i-1].timeStamp,
											companyName:this.companyName,
											database:data[i-1].violationStandard
										})
									}
								}
							}
						}else{//重新获取
							this.projectNameList = ['请选择工程项目']
							this.projectList = ['']
							if(length>=1){
								for(var i =1;i<length;i++){
									this.projectNameList.push(data[i-1].projectName)
									this.projectList.push({
										projectId:data[i-1].projectId,
										proTimeStamp:data[i-1].timeStamp,
										companyName:this.companyName,
										database:data[i-1].violationStandard
									})
								}
							}
						}
					},
					fail: (error) => {
						console.log(error);
					}
				});
			},
			//异步获取楼层信息
			getBuildInfo(){
				let opts = {
					url: this.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getSectionBuild',
					proTimeStamp: this.proTimeStamp
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					let sectionArr = res.data.section
					let buildArr = res.data.build
					let child_arry = []
					for (let i = 0; i < sectionArr.length; i++) {
						child_arry = []
						for (let j = 0; j < buildArr[i].split(',').length; j++) {
							child_arry.push({
								value: buildArr[i].split(',')[j],
								label: buildArr[i].split(',')[j],
							})
						}
						this.list2.push({
							value: sectionArr[i],
							label: sectionArr[i],
							children: child_arry
						})
					}
				}, error => {
					console.log(error);
				})
			},
			//选择分公司
			pickCompany: function(e) {
			    this.companyIndex = e.target.value
				// console.log(this.companyIndex);
				this.companyName = this.companyList[this.companyIndex]//获取选择的分公司
				this.getComProject()
			},
			//选择工程
			pickProject: function(e){
				this.isSelectPro = true
				this.projectIndex = e.target.value
				console.log(this.projectNameList);
				this.projectName = this.projectNameList[this.projectIndex]//获取选择的工程名称
				this.projectId = this.projectList[this.projectIndex].projectId//获取选择的工程id
				this.database = this.projectList[this.projectIndex].database//获取选择的工程违章数据库
				this.proTimeStamp = this.projectList[this.projectIndex].proTimeStamp//获取选择的工程时间戳
				this.getBuildInfo()
			},
			handleChange2(e) {},
			handleChange3(e) {},
			handleConfirm2(e) {
				this.label2 = e.value[0] + "-" + e.value[1]
				console.log(this.label2)
				//根据栋号异步获取楼层单元
				let opts = {
					url: this.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getFloorUnit',
					proTimeStamp: this.proTimeStamp,
					build: e.value[1],
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res)
					let unitName = res.data.unitName[0].split('||')
					let undergroundNumber = res.data.undergroundNumber
					let abovegroundNumber = res.data.abovegroundNumber 
					// console.log(undergroundNumber+" "+abovegroundNumber+" "+unitName.split('||'))
					let floorArr = this.commonFunc.toChinesNum_floor(undergroundNumber, abovegroundNumber)
					let unitArr = unitName
					let child_arry = [];
					let list3 = [];
					for (let i = 0; i < floorArr.length; i++) {
						child_arry = []
						for (let j = 0; j < unitArr.length; j++) {
							child_arry.push({
								value: unitArr[j],
								label: unitArr[j]
							})
						}
						// list3 = [];
						list3.push({
							value: floorArr[i],
							label: floorArr[i],
							children: child_arry,
						})
					}
					console.log(list3)
					this.list3 = list3;
				}, error => {
					console.log(error);
				})
			},
			handleConfirm3(e) {
				this.label3 = e.value[0] + "-" + e.value[1]
				console.log('change::', e.value)
			},
			handleCancel2(e) {
				this.label2 = '';
				this.value2 = [];
				console.log('cancel::', e)
			},
			handleCancel3(e) {
				this.label3 = '';
				this.value3 = [];
				console.log('cancel::', e)
			},
			//确定提交
			submint(){
				if(!this.isSelectPro){
					console.log("ss")
					this.companyName = this.companyList[this.companyIndex];
					this.projectName = ''//获取选择的工程名称
					this.projectId = this.projectList[0].projectId//获取选择的工程id
					this.database = this.projectList[0].database//获取选择的工程违章数据库
					this.proTimeStamp = this.projectList[0].proTimeStamp//获取选择的工程时间戳
					this.companyName = this.projectList[0].companyName//获取选择的公司名称
				}
				// console.log('database:'+this.database+" "+'projectId:'+this.projectId+" "+'companyName'+this.companyName)
				uni.setStorage({
					key:'changeProRecord',
					data: {
						companyName:this.companyName,
						projectName: this.projectName,
						projectId:this.projectId,
						companyName:this.companyName,
						companyIndex:this.companyIndex,
						projectIndex:this.projectIndex,
						proTimeStamp:this.proTimeStamp
					}
				})
				uni.$emit("changePro",{
					projectName: this.projectName,
					projectId:this.projectId,
					isShow : false,
					companyName:this.companyName,
					proTimeStamp:this.proTimeStamp,
					database:this.database
				});
				//存储栋号缓存信息
				let data = {
					section: this.label2.split('-')[0],
					build: this.label2.split('-')[1],
					floor: this.label3.split('-')[0],
					unit: this.label3.split('-')[1]
				}
				uni.setStorage({
					key: 'buildInfo',
					data: data
				})
				uni.navigateBack()
			}
	    }
	}
</script>

<style>
</style>
