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
							<picker @change="inspectItemChange" :value="itemIndex" :range="inspectProArr" range-key="inspectionItem">
								<view class="uni-input">{{inspectProArr[itemIndex].inspectionItem}}</view>
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
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							分包单位：
						</view>
						<view class="uni-list-cell-db">
							<picker @change="subcontractChange" :value="subIndex" :range="subcontractorArr" range-key="subcontractor">
								<view class="uni-input">{{subcontractorArr[subIndex].subcontractor}}</view>
							</picker>
						</view>
					</view>
				</view>
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
			inspectProArr: [{id:'',inspectionItem:''}],
			itemIndex: 0,
			currentData:[],
			buildSelData: {},
			projectInfo: {},
			subcontractorArr: [{id:'',subcontractor:''}],
			subIndex: 0
		};
	}, 
	onLoad:function(option) {
		this.currentData = JSON.parse(option.currentData)
		// console.log(this.currentData)
	},
	mounted() {
		let that = this
		this.getStoragedata()
		setTimeout(function(){
			let data = that.categoryArr[that.index]
			that.getNewCardDataFunc(data)
			that.getSubcontractorFunc()
		},1000)
		
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
			this.getNewCardDataFunc(proItem)
		},
		inspectItemChange(e) {
			this.itemIndex = e.target.value
		},
		getNewCardDataFunc(data) {
			this.formData.titleName = this.buildSelData.build +'-'+ this.buildSelData.floor +'-'+ this.buildSelData.unit
			let proTimeStamp = this.projectInfo.proTimeStamp
			this.formData.inspectPosition = this.buildSelData.build + this.buildSelData.floor + this.buildSelData.unit
			this.formData.inspectDate = this.commonFunc.getNowDate()
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
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				// console.log(res.data)
				if(res.data.code){
					this.inspectProArr = res.data.data
					// this.createFloorFunc(res.data.data)
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
					console.log(res.data)
				}
			});
		},
		//获取分包单位函数
		getSubcontractorFunc() {
			let proTimeStamp = this.projectInfo.proTimeStamp
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'getSubcontractor',
				proTimeStamp:proTimeStamp
			}
			this.myRequest.httpRequest (opts, param).then(res => {
				console.log(res.data)
				if(res.data.code){
					this.subcontractorArr = res.data.data
				}
			}, error => {
				console.log(error);
			})
		},
		subcontractChange(e) {
			this.subIndex = e.target.value
		},
		confirm() {
			
			let projectType = this.categoryArr[this.index]
			let inspectItem = this.inspectProArr[this.itemIndex].inspectionItem
			let substractor = this.subcontractorArr[this.subIndex].subcontractor
			let proTimeStamp = this.projectInfo.proTimeStamp
			let buildInfo = JSON.stringify(this.buildSelData)
			let inputValue = JSON.stringify(this.formData)
			let projectName = this.projectInfo.projectName
			let opts = {
				url: this.api+'/module_project/InspectAccept/InspectAccept.php',
				method: 'POST'
			}
			let param = {
				flag: 'addTaskCard',
				inputValue: inputValue,
				projectType: projectType,
				inspectItem: inspectItem,
				proTimeStamp: proTimeStamp,
				buildInfo: buildInfo,
				createUserName: this.currentData.username,
				createUserId: this.currentData.userId,
				substractor: substractor,
				projectName: projectName
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
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
