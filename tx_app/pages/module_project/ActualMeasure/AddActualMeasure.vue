<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">标题名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.titleName" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							工程类别：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="proCategoryChange" :value="index" :range="categoryArr">
								<view class="uni-input">{{categoryArr[index]}}</view>
							</picker>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查项目：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="inspectItemChange" :value="itemIndex" :range="inspectProArr" range-key="inspectItem">
								<view class="uni-input">{{inspectProArr[itemIndex]}}</view>
								<!-- <view  v-if="categoryArr[index]=='建筑工程'" class="uni-input">{{inspectProArr[itemIndex].inspectionItem}}</view>
								<view v-if="categoryArr[index]=='装修工程'" class="uni-input">{{inspectProArr[itemIndex].inspectionItem}}</view> -->
							</picker>
						</view>
					</view>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查日期：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectDate" /></view>
			</view>
		</view> 
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查部位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPosition" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查人员：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">施工班组：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.constructionTeam" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">组长名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.leaderName" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">联系方式：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.contactMode" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="施工日期" @date="getConstrunctionDate"></pick-date>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="confirm()">确定</button>
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
export default {
	props:{},
	components: {
		'pick-date': pickdate
	},
	data() {
		return {
			isconfirm:false,
			formData: {
				titleName:'',//标题名称
				projectType: '',//工程类别
				inspectDate: '',//检查日期
				inspectPosition:'',//检查部位
				inspectPerson:'',//检查人员
				constructionTeam:'',//施工班组
				leaderName:'',//组长名称
				contactMode:'',//联系方式
				construnctionDate:''
			},
			categoryArr: ['建筑工程', '装修工程'],
			index: 0,
			inspectProArr: [],
			itemIndex: 0,
			currentData:[],
			buildSelData: {},
			projectInfo: {},
			incompleteCardList:[] // 已新建的项目
		};
	}, 
	onLoad:function(option) {
		const that = this;
		that.currentData = JSON.parse(option.currentData);
		// console.log(that.currentData);
		this.incompleteCardList = JSON.parse(option.incompleteCardList) // 已创建的卡片项目
		// console.log(this.incompleteCardList);
		// uni.getStorage({
		// 	key: 'incompleteCardList',
		// 	success:function(res){
		// 		that.incompleteCardList = res.data // 已创建的卡片项目
		// 		console.log(res.data);
		// 	}
		// })
	},
	mounted() {
		let that = this
		this.getStoragedata()
		setTimeout(function(){
			let data = that.categoryArr[that.index]
			that.getNewCardDataFunc(data)
		},200)
		
	},
	methods: {
		//获取检查日期
		getInsDate(val){
			this.formData.inspectDate = val
		},
		//获取施工日期
		getConstrunctionDate(val){
			this.formData.construnctionDate = val
		},
		proCategoryChange(e) {
			this.index = e.target.value
			var proItem = this.categoryArr[this.index]
			// console.log(11);
			// console.log(proItem)
			this.getNewCardDataFunc(proItem)
		},
		inspectItemChange(e) {
			this.itemIndex = e.target.value
			// console.log(222)
			console.log(this.inspectProArr)
		},
		getNewCardDataFunc(data) {
			const that = this;
			this.formData.titleName = this.buildSelData.build +'-'+ this.buildSelData.floor +'-'+ this.buildSelData.unit
			let proTimeStamp = this.projectInfo.proTimeStamp
			this.formData.inspectPosition = this.buildSelData.section + this.buildSelData.build + this.buildSelData.floor + this.buildSelData.unit
			this.formData.inspectDate = this.commonFunc.getNowDate() // 获取当前计算机的时间
			this.formData.inspectPerson = this.currentData.username
			this.formData.contactMode = this.currentData.phone
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'getInspectItemInfo',
				proCategory:data,
				proTimeStamp:proTimeStamp
			}
			let isLoading = true//是否需要加载动画
			that.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				that.isloading = false//取消遮罩层
				// console.log(res)
				if(res.data.code){
					that.inspectProArr = res.data.data
					const oldProArr = that.incompleteCardList;
					// console.log(oldProArr)
					// console.log(that.inspectProArr)
					let temp1 = that.inspectProArr;
					let temp2 = that.inspectProArr;
					if (data == '建筑工程') {
						if (oldProArr.length != 0) {
							for (let i = 0; i < oldProArr.length; i++) { // 循环历遍上个页面的工程, 把已经创建的工程在这个页面不不显示
								// console.log(oldProArr[i].projectCategory)
								if (oldProArr[i].projectCategory == '建筑工程') {
									for (let j = 0; j < that.inspectProArr.length; j++ ) {
										if (oldProArr[i].inspectItem == that.inspectProArr[j]){
											temp1.splice(j,1);
										}
									}
								}
							}							
							// console.log(temp1);
							that.inspectProArr = temp1;
						}
					} else {
						// console.log(222)
						if (oldProArr.length != 0) {
							for (let i = 0; i < oldProArr.length; i++) {
								if (oldProArr[i].projectCategory == '装修工程') {
									 for (let j = 0; j < that.inspectProArr.length; j++ ) {
									 	if (oldProArr[i].inspectItem == that.inspectProArr[j]){
									 		temp2.splice(j,1)
									 	}
									 }
								}
							}
							console.log(temp2);
							that.inspectProArr = temp2;
						}
					}
					// console.log(that.inspectProArr);
					
					
				}
			}, error => {
				console.log(error);
			})
		},
		//获取本地缓存
		getStoragedata() {
			let that = this
			uni.getStorage({ 
				key: 'buildInfo',
				success: function (res) {
					that.buildSelData = res.data
				}
			});
			uni.getStorage({
				key: 'changeProRecord',
				success: function (res) {
					that.projectInfo = res.data
					// console.log(res.data)
				}
			});
			
		},
		confirm() {		
			let projectType = this.categoryArr[this.index]
			let inspectItem = this.inspectProArr[this.itemIndex]
			let proTimeStamp = this.projectInfo.proTimeStamp
			let buildInfo = JSON.stringify(this.buildSelData)
			let inputValue = JSON.stringify(this.formData)
			let projectName = this.projectInfo.projectName
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'addMeasureTaskCard',
				inputValue: inputValue,
				projectType: projectType,
				inspectItem: inspectItem,
				proTimeStamp: proTimeStamp,
				buildInfo: buildInfo,
				createUserName: this.currentData.username,
				createUserId: this.currentData.userId,
				projectName: projectName
			}
			console.log(param)
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				// console.log(res.data)
				if(res.data.code){
					uni.showToast({
					    title: '新增卡片成功！',
					    duration: 2000,
						icon: "none"
					});
					setTimeout(() => {
						uni.navigateBack({ delta: 1 });    // 返回上一页
					}, 1000)
				}else{
					uni.showToast({
					    title: '新增卡片失败！',
					    duration: 2000,
						icon: "none"
					});
				}
			}, error => {
				console.log(error);
			})
		}
	} 
};
</script> 

<style lang="scss">
page {
	background-color: #f8f8f8;
}

.row {
	display: flex;
	align-items: center;
	position: relative;
	padding: 0 30upx;
	height: 90upx;
	background: #fff;

	.tit {
		flex-shrink: 0;
		// width: 120upx;
		font-size: 30upx;
	}

	.input {
		flex: 1;
		font-size: 30upx;
	}
}

</style>
