<template>
	<view class="content">
		<block>
			<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
			<view class="main-box">
				<t-table borderColor="#494F54"> 
					<t-tr fontSize="12">
						<t-th>检查部位</t-th>
						<t-th>问题类型</t-th>
						<t-th>问题描述</t-th>
						<t-th>累计数</t-th>
					</t-tr>
					<t-tr  fontSize="12" v-for="(item,index) in trList" :key="index" v-show="item.isShow==true">
						<t-td>{{item.inspectPosition}}</t-td>
						<t-td>{{item.itemType}}</t-td>
						<t-td>{{item.violationContent}}</t-td>
						<t-td>{{item.totalNum}}</t-td>
					</t-tr>
				</t-table>
			</view>
		</block>
		<lb-picker ref="picker1" v-model="selectObj" mode="selector" :list="tabBarArray" :dataset="{ name: 'label3' }" @confirm="handleConfirm" @cancel="handleCancel">
		</lb-picker>
	</view>
	
</template>

<script>
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	import LbPicker from '@/components/common/lb-picker/index.vue'
	export default {
		components: {
			ChooseLits,
			tTable,
			tTh,
			tTr,
			tTd,
			LbPicker
		},
		data() {
			return {
				list: ['时间', '栋号', '楼层' , '单元'],
				arr: [
					['起始时间', '截止时间']
				],
				i2: [0, 0, 0],
				themeColor: '#007AFF',
				selectObj: '',
				tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'],
				currentData: {},
				trList: [],
				startTime: '',
				endTime: '',
				position: ['','',''],
				proTimeStamp: '',
				buildArr: []
				
			}
		},
		onNavigationBarButtonTap() {
			this.$refs.picker1.show()
		},
		onLoad:function(option) {
			this.currentData = JSON.parse(option.currentData)
			let that = this
			this.getStoragedata()
			// 获取缓存数据后再调用函数
			setTimeout(function(){
				that.getMajorHiddenDetail()
				that.getBuildInfo()
			},1000)
			// console.log(this.currentData)
		},
		methods: {
			//下拉筛选
			chooseLike(i1) {
				// console.log(this.i2)
				if(i1[0]==1){ //第二个选项框改变
					//改变时拿到children值
					for(var j=0;j<this.buildArr.length;j++){
						if(this.arr[1][i1[1]]==this.buildArr[j].name){
							let floor = []
							for(var k=0;k<this.buildArr[j].children.length;k++){
								floor.push(this.buildArr[j].children[k].name)
							}
							this.arr[2]==undefined?(this.arr.push(floor)):(this.arr[2]=floor)
							
						}
					}
					let length = this.trList.length
					this.position[0] = this.arr[1][i1[1]]
					let str = this.position.join('')
					// console.log(str)
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.trList[i].inspectPosition.indexOf(str)>=0) continue 
							this.trList[i].isShow = false
					}
				}
				if(i1[0]==2){ //第三个选项框改变
					//改变时拿到children值
					for(var j=0;j<this.buildArr.length;j++){
						if(this.arr[1][i1[1]]==this.buildArr[j].name){
							let unit = []
							for(var k=0;k<this.buildArr[j].children.length;k++){
								if(this.arr[2][i1[1]]==this.buildArr[j].children[k].name){
									for(var l=0;l<this.buildArr[j].children[k].children.length;l++){
										unit.push(this.buildArr[j].children[k].children[l].name)
									}	
								}
								// floor.push(this.buildArr[j].children[k].name)
							}
							// console.log(unit)
							this.arr[3]==undefined?(this.arr.push(unit)):(this.arr[3]=unit)
							
						}
					}
					let length = this.trList.length
					this.position[1] = this.arr[2][i1[1]]
					let str = this.position.join('')
					// console.log(str)
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.trList[i].inspectPosition.indexOf(str)>=0) continue 
							this.trList[i].isShow = false
					}
				}
				if(i1[0]==3){ //第四个选项框改变
					let length = this.trList.length
					this.position[2] = this.arr[3][i1[1]]
					let str = this.position.join('')
					// console.log(str)
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.trList[i].inspectPosition.indexOf(str)>=0) continue 
							this.trList[i].isShow = false
					}
				}
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				// console.log(this.i2)
			},
			//获取重大隐患数据
			getMajorHiddenDetail() {
				let opts = {
					url: this.api + '/module_company/DataStatistics/DataStatisticsDetail.php',
					method: 'POST'
				}
				let param = {
					flag: "getMajorHiddenDetail",
					startTime: this.startTime,
					endTime: this.endTime,
					// build: '',
					// floor: '',
					// unit: '',
					title: this.currentData.title
				} 
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					this.trList = []
					if(res.data.code){
						for(var i=0;i<res.data.data.length;i++){
							this.trList.push({
								id: res.data.data[i].id,
								inspectPosition: res.data.data[i].inspectPosition,
								itemType: res.data.data[i].itemType,
								violationContent: res.data.data[i].violationContent,
								totalNum: res.data.data[i].totalNum,
								isShow: true
							})
						}
					}
				}, error => {
				
				})
			},
			dateChange(e,flag){
				console.log(e)
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getMajorHiddenDetail()
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
			//获取楼栋信息
			getBuildInfo() {
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
					if(res.data.code){
						let buildInfo = []
						let floorData = res.data.data
						for(var i=0;i<floorData.length;i++){
							var obj = {
								name:'',
								children:[]
							}
							obj.name = floorData[i].build
							//遍历地下楼层
							var undergroundNum = parseInt(floorData[i].undergroundNumber);
							if (undergroundNum) {
								let unit_arr = floorData[i].unitName.split('||')
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
										children: []
									};
									//遍历单元
									for(var x=0;x<unit_arr.length;x++){
										let unitObj = {
											name: unit_arr[x],
											isSel:false,
											children: []
										}
										floorObj.children.push(unitObj)
									}
									obj.children.push(floorObj)
								}
								buildInfo.push(obj)
							}
							//遍历地上楼层
							var abovegroundNumber = parseInt(floorData[i].abovegroundNumber);
							if (abovegroundNumber) {
								let unit_arr = floorData[i].unitName.split('||')
								var index = abovegroundNumber;
								for (var z = 0; z < abovegroundNumber + 1; z++) {
									if(i==abovegroundNumber){
										var FloorName = "屋面层";
									}else{
										var FloorName = this.toChinesNum(z+1)+"层";
									}
									var floorObj = {
										name: FloorName,
										children: []
									};
									//遍历单元
									for(var x=0;x<unit_arr.length;x++){
										let unitObj = {
											name: unit_arr[x],
											isSel:false,
											children: []
										}
										floorObj.children.push(unitObj)
									}
									obj.children.push(floorObj)
								}
								buildInfo.push(obj)
							}
						}
						let buildArr = this.commonFunc.Es5duplicate(buildInfo,'name')
						this.buildArr = buildArr
						let build = []
						for(var i=0;i<buildArr.length;i++){
							build.push(buildArr[i].name)
						}
						this.arr.push(build)
					}
				}, error => {
					console.log(error);
				})
			},
			//获取本地缓存
			getStoragedata() {
				let that = this
				uni.getStorage({
					key: 'changeProRecord',
					success: function (res) {
						that.proTimeStamp = res.data.proTimeStamp
						// console.log(that.proTimeStamp)
					}
				});
			}
		}
	}
</script>

<style  lang="scss">
	.main-box {
		position: absolute;
		top: 55px;
		width: 100%;
	}
</style>
