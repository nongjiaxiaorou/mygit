<template>
	<view class="content">
		<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
		<RectificationClose v-show="comShow=='ShowRectification'" :cardDataList="cardList"></RectificationClose>
		<AbnormalAccept v-show="comShow=='ShowAbnormalAccept'" :cardDataList="cardList"></AbnormalAccept>
		<MeasureQualified v-show="comShow=='ShowMeasureQualified'" :cardDataList="cardList"></MeasureQualified>
		<TotalHiddenDanger v-show="comShow=='ShowTotalHiddenDanger'" :cardDataList="cardList"></TotalHiddenDanger>
		<CommonHiddenDanger v-show="comShow=='ShowCommonHiddenDanger'" :cardDataList="cardList"></CommonHiddenDanger>
		<MajorHiddenDanger v-show="comShow=='ShowMajorHiddenDanger'" :cardDataList="cardList"></MajorHiddenDanger>
		<lb-picker ref="picker1" v-model="selectObj" mode="selector" :list="tabBarArray" :dataset="{ name: 'label3' }" @confirm="handleConfirm" @cancel="handleCancel">
		</lb-picker>
	</view>
	
</template>
 
<script>
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade2.vue'
	// import uniCard from '@/components/uni-app/uni-data-card/uni-card.vue'
	import LbPicker from '@/components/common/lb-picker/index.vue'
	import RectificationClose from '@/pages/module_company/DataStatistics/RectificationClose.vue'
	import AbnormalAccept from '@/pages/module_company/DataStatistics/AbnormalAccept.vue'
	import MeasureQualified from '@/pages/module_company/DataStatistics/MeasureQualified.vue'
	import TotalHiddenDanger from '@/pages/module_company/DataStatistics/TotalHiddenDanger.vue'
	import CommonHiddenDanger from '@/pages/module_company/DataStatistics/CommonHiddenDanger.vue'
	import MajorHiddenDanger from '@/pages/module_company/DataStatistics/MajorHiddenDanger.vue'
	
	export default {
		components: {
			ChooseLits,
			// uniCard,
			LbPicker,
			RectificationClose,
			AbnormalAccept,
			MeasureQualified,
			TotalHiddenDanger,
			CommonHiddenDanger,
			MajorHiddenDanger
		},
		data() {
			return {
				list: ['时间', '公司'],
				arr: [
					['起始时间', '截止时间']
					// ['A公司', 'B公司', 'C公司'],
					// ['A工程', 'B工程', 'C工程', 'D工程','A工程', 'B工程', 'C工程', 'D工程','A工程', 'B工程', 'C工程', 'D工程']
				],
				i2: [0, 0, 0],
				cardList: [
				// 	{
				// 	id: 1,
				// 	title: 'A公司',
				// 	content: '',
				// 	rightCornerInfo: '20%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// }, {
				// 	id: 2, 
				// 	title: 'A公司',
				// 	content: '',
				// 	rightCornerInfo: '40%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// },{
				// 	id: 3,
				// 	title: 'B公司',
				// 	content: '',
				// 	rightCornerInfo: '60%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// },{
				// 	id: 4,
				// 	title: 'B公司',
				// 	content: '',
				// 	rightCornerInfo: '60%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// },{
				// 	id: 5,
				// 	title: 'C公司',
				// 	content: '',
				// 	rightCornerInfo: '60%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// },{
				// 	id: 6,
				// 	title: 'C公司',
				// 	content: '',
				// 	rightCornerInfo: '60%',
				// 	extra: '点击卡片可查看详情',
				// 	isShow: true
				// },
				],
				company: '',
				tabBarindex: 0,
				// tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'], //暂时不开发其他类型
				tabBarArray: ['整改闭合率','一般隐患','重大隐患'],
				selectObj: '',
				comShow: 'ShowRectification',
				selectVal: '',
				startTime: '',
				endTime: '',
				companyLevel: '总公司',
				itemFlag: 'getRectificationAllData'
			}
		},
		onReady() {
			let _this = this
			uni.setNavigationBarTitle({
				title: _this.tabBarArray[0]
			})
		},
		onLoad() {
			this.getAllData()
			this.getCompany()
		},
		methods: {
			onNavigationBarButtonTap() {  
				this.$refs.picker1.show()
			},
			chooseLike(i1) {
				// console.log(i1)
				if(this.selectVal.toString()==i1.toString()) return //判断选中值是否变化
				this.selectVal = i1
				if(i1[0]==1){
					let length = this.cardList.length
					for(var i=0;i<length;i++){
						this.cardList[i].isShow = true
						if(this.cardList[i].title==this.arr[1][i1[1]]) continue 
							this.cardList[i].isShow = false
					}
				}
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				// console.log(this.i2)
			},
			//第一次进入获取
			getAllData(){
				if(this.companyLevel=='总公司'){
					let opts = {
						url: this.api + '/module_company/DataStatistics/RectificationClose.php',
						method: 'POST'
					}
					let param = {
						flag: this.itemFlag,
						startTime: this.startTime,
						endTime: this.endTime,
						companyLevel: this.companyLevel
					}
					let isLoading = false
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						console.log(res.data)
						if(res.data.code){
							this.cardList = []
							if(this.itemFlag=="getRectificationAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										totalNum: res.data.data[i].rectificationNum,
										unTotalNum: res.data.data[i].unRectificationNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}else if(this.itemFlag=="getMajorHiddenDangerAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										projectNum:  res.data.data[i].projectNum,
										rectificationNum: res.data.data[i].rectificationNum,
										totalNum: res.data.data[i].totalNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}else if(this.itemFlag=="getCommonHiddenDangerAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										projectNum:  res.data.data[i].projectNum,
										rectificationNum: res.data.data[i].rectificationNum,
										totalNum: res.data.data[i].totalNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}
							
						}
					}, error => {
					
					})
				}else if(this.companyLevel!='总公司'){
					let opts = {
						url: this.api + '/module_company/DataStatistics/RectificationClose.php',
						method: 'POST'
					}
					let param = {
						flag: this.itemFlag,
						startTime: this.startTime,
						endTime: this.endTime,
						companyLevel: this.companyLevel
					}
					let isLoading = false
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						// console.log(res.data)
						if(res.data.code){
							this.cardList = []
							if(this.itemFlag=="getRectificationAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										totalNum: res.data.data[i].rectificationNum,
										unTotalNum: res.data.data[i].unRectificationNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}else if(this.itemFlag=="getMajorHiddenDangerAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										projectNum:  res.data.data[i].projectNum,
										rectificationNum: res.data.data[i].rectificationNum,
										totalNum: res.data.data[i].totalNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}else if(this.itemFlag=="getCommonHiddenDangerAllData"){
								for(var i=0;i<res.data.data.length;i++){
									this.cardList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].title,
										rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
										projectNum:  res.data.data[i].projectNum,
										rectificationNum: res.data.data[i].rectificationNum,
										totalNum: res.data.data[i].totalNum,
										extra: '点击卡片可查看详情',
										isShow: true,
										flag: 'company'
									})
								}
							}
						}
					}, error => {
					
					})
				}
				
			},
			//获取已选择时间的数据
			getDataSelectDate() {
				if(this.companyLevel=='总公司'){
					let opts = {
						url: this.api + '/module_company/DataStatistics/RectificationClose.php',
						method: 'POST'
					}
					let param = {
						flag: this.itemFlag+'Sel',
						startTime: this.startTime,
						endTime: this.endTime,
						companyLevel: this.companyLevel
					}
					let isLoading = false
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						console.log(res.data)
						if(res.data.code){
							this.cardList = []
							for(var i=0;i<res.data.data.length;i++){
								this.cardList.push({
									id: res.data.data[i].id,
									title: res.data.data[i].title,
									rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
									totalNum: res.data.data[i].rectificationNum,
									unTotalNum: res.data.data[i].unRectificationNum,
									extra: '点击卡片可查看详情',
									isShow: true,
									flag: 'company'
								})
							}
						}
					}, error => {
					
					})
				}else if(this.companyLevel!='总公司'){
					let opts = {
						url: this.api + '/module_company/DataStatistics/RectificationClose.php',
						method: 'POST'
					}
					let param = {
						flag: this.itemFlag,
						startTime: this.startTime,
						endTime: this.endTime,
						companyLevel: this.companyLevel
					}
					let isLoading = false
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						if(res.data.code){
							this.cardList = []
							for(var i=0;i<res.data.data.length;i++){
								this.cardList.push({
									id: res.data.data[i].id,
									title: res.data.data[i].title,
									rightCornerInfo: res.data.data[i].rectificationCloseRate+'%',
									totalNum: res.data.data[i].rectificationNum,
									unTotalNum: res.data.data[i].unRectificationNum,
									extra: '点击卡片可查看详情',
									isShow: true,
									flag: 'project'
								})
							}
						}
					}, error => {
					
					})
				}
			},
			tabBarChange(e) {
				this.tabBarindex = e.target.value
			},
			handleCancel() {
				uni.showToast({
					title: '您已取消了改变',
					icon: 'none'
				});
			},
			handleConfirm(e) {
				// console.log(e)
				uni.setNavigationBarTitle({
				    title: e.item
				});
				switch(e.item) {
					case "整改闭合率":
						this.itemFlag = "getRectificationAllData"
				        this.comShow = "ShowRectification"
						this.getAllData()
					break;
					case "非正常验收通过率":
						this.itemFlag = "getAbnormalAcceptAllData"
						if(this.list.length>3){
							for(var i=0;i<this.list.length;i++){
								if(i>2){
									this.list.splice(i, 1)
									this.arr.splice(i, 1)
								}
							}
						}
						this.list.push('验收项')
						var Arr = ['钢筋', '基础']
						this.arr.push(Arr)
				        this.comShow = "ShowAbnormalAccept"
						this.getAllData()
					break;
					case "实测合格率":
						this.itemFlag = "getMeasureQualifiedAllData"
						if(this.list.length>3){
							for(var i=0;i<this.list.length;i++){
								if(i>2){
									this.list.splice(i, 1);
									this.arr.splice(i, 1)
								}
							}
						}
						this.list.push('11大项')
						var Arr = ['钢筋工程', '基础工程']
						this.arr.push(Arr)
					    this.comShow = "ShowMeasureQualified"
						this.getAllData()
					break;
					case "隐患总数":
						this.itemFlag = "getTotalHiddenDangerAllData"
						if(this.list.length>3){
							for(var i=0;i<this.list.length;i++){
								if(i>2){
									this.list.splice(i, 1)
									this.arr.splice(i, 1)
								}
							}
						}
					    this.comShow = "ShowTotalHiddenDanger"
						this.getAllData()
					break;
					case "一般隐患":
						this.itemFlag = "getCommonHiddenDangerAllData"
						if(this.list.length>3){
							for(var i=0;i<this.list.length;i++){
								if(i>2){
									this.list.splice(i, 1);
									this.arr.splice(i, 1)
								}
							}
						}
					    this.comShow = "ShowCommonHiddenDanger"
						this.getAllData()
					break;
					case "重大隐患":
						this.itemFlag = "getMajorHiddenDangerAllData"
						if(this.list.length>3){
							for(var i=0;i<this.list.length;i++){
								if(i>2){
									this.list.splice(i, 1);
									this.arr.splice(i, 1)
								}
							}
						}
					    this.comShow = "ShowMajorHiddenDanger"
						this.getAllData()
					break;
				} 
			},
			dateChange(e,flag){
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getDataSelectDate()
				}
			},
			//获取公司
			getCompany(){
				let opts = {
					url: this.api + '/module_company/DataStatistics/RectificationClose.php',
					method: 'POST'
				}
				let param = {
					flag: 'getCompany',
					companyLevel: this.companyLevel
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						let Arr = []
						for(var i=0;i<res.data.data.length;i++){
							Arr.push(res.data.data[i].companyName)
						}
						// console.log(Arr)
						this.arr.push(Arr)
					}
				}, error => {
				
				})
				
			},
		}
	}
</script>

<style  lang="scss">
	
</style>
