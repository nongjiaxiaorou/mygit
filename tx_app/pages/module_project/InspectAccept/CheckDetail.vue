<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查层级：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectLevel" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">通知单号：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.noticeNumber" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查项目：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectItem" /></view>
			</view>
		</view> 
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查工序：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectProcedure" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查部位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPosition" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="检查日期：" :dateAppoint="formData.inspectDate" @date="getInspectDate"></pick-date>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">排查人员：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell" @click="violationPicPage">
				<view class="uni-list-cell-left">问题描述：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.questionDescribe" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="截止日期：" :dateAppoint="formData.endDate" @date="getEndDate"></pick-date>
		<view class="flex">
			<button type="primary" class="button" @click="back()">关闭</button>
			<!-- <button type="primary" class="button" @click="violationPicPage()">违章照片</button> -->
			<button :disabled="isconfirm" type="primary" class="button" @click="confirm()">保存</button>
		</view>
		
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
import ldSelect3 from '@/components/common/ld-select/ld-select3.vue'; //多选组件
import ldSelect4 from '@/components/common/ld-select/ld-select4.vue'; //多选组件
import { pathToBase64, base64ToPath } from '../../../js_sdk/gsq-image-tools/image-tools/index.js'
export default {
	props:{},
	components: {
		'pick-date': pickdate,
		'ldSelect3': ldSelect3,
		'ldSelect4': ldSelect4
	},
	data() {
		return {
			isconfirm:false,
			formData: {
				inspectLevel:'',//检查层级
				noticeNumber: '',//通知单编号
				inspectItem: '',//检查项目
				inspectProcedure: '', //检查工序
				inspectPosition:'',//检查部位
				inspectContent:'',//检查内容 
				inspectDate:'',//检查日期 
				leaderName:'',//组长名称
				questionDescribe:'查看违章条目',//问题描述
				violationItem:'',
				itemList: [],
				endDate: '',
				number: ''
			},
			// dateAppoint: '2017-01-04',
			index: 0,
			currentData:[],
			buildSelData: {},
			projectInfo: {},
			inspectOrderArr: [{id:'',inspectOrder:''}],
			orderIndex: 0,
			cardParam: {},
			inspectContentIndex: 0,
			inspectContentArr: [{id:'',inspectContent:''}],
			options1: [],
			options2: [],
			imageList: [],
			inspectCardParam: {}
		};
	}, 
	onLoad:function(option) {
		this.currentData = JSON.parse(option.currentData)
		this.cardParam = JSON.parse(option.cardParam)
		this.inspectCardParam = JSON.parse(option.inspectCardParam)
		// console.log(this.currentData,this.cardParam)
	},
	onShow() {
		uni.$on('sendList',res=>{
			this.formData.itemList = res
		})
	},
	mounted() {
		this.getStoragedata()
		this.getNewCardDataFunc() 
	},
	methods: {
		//获取检查日期
		getInspectDate(val){ 
			this.formData.inspectDate = val
		},
		//获取施工日期
		getEndDate(val){
			this.formData.endDate = val
		},
		 //产生随机数函数
		RndNum(n){
			var rnd="";
			for(var i=0;i<n;i++)
				rnd+=Math.floor(Math.random()*10);
			return rnd;
		},
		getNewCardDataFunc() {
			let opts = {
				url: this.api+'/module_project/InspectAccept/CheckDetail.php',
				method: 'POST'
			}
			let param = {
				flag: 'getDetailInfo',
				inspectCardId: this.inspectCardParam.id
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){ 
					this.formData.inspectLevel = res.data.data.inspectLevel
					this.formData.noticeNumber = res.data.data.noticeNumber
					this.formData.inspectItem = res.data.data.inspectItemHead
					this.formData.inspectPosition = res.data.data.inspectPosition
					this.formData.inspectDate = res.data.data.inspectDate
					this.formData.inspectPerson = res.data.data.inspectPersonName
					this.formData.inspectProcedure = res.data.data.inspectProcedure
					this.formData.number = res.data.data.number
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
		inspectOrderChange(e) {
			this.orderIndex = e.target.value
		},
		//路径转bse64
		urlTobase64(obj) {
			for (let i = 0; i < obj.length; i++) {
				// this.formData.itemList[i].picPath = pathToBase64(obj[i].picPath)
				// console.log(this.formData.itemList[i].picPath)
				pathToBase64(obj[i].picPath).then(base64 => {
					this.formData.itemList[i].picPath = base64
				})
				.catch(error => {
					console.error(error)
				})
				console.log(this.formData.itemList[i].picPath)
			}
		},
		//修改通知单以及图片
		alterCardDataFunc() {
			// console.log(this.formData)
			console.log(this.inspectCardParam.id)
			// console.log(this.cardParam.id)
			let opts = {
				url: this.api+'/module_project/InspectAccept/UploadPicture.php',
				method: 'POST'
			}
			let param = {
				flag: "alterViolationPic",
				formData: JSON.stringify(this.formData),
				inspectCardParam: this.inspectCardParam.id
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){ 
					uni.$emit('alterCardData')
					uni.navigateBack()
				}
			}, error => {
				console.log(error);
			})
		},
		confirm() {
			// this.urlTobase64(this.formData.itemList)
			let that = this
			setTimeout(that.alterCardDataFunc,1000)
		},
		selectProcedure(val) {
			this.formData.inspectProcedure = val
			let projectId = this.projectInfo.projectId
			let opts = {
				url: this.api+'/module_project/InspectAccept/AddItemInspect.php',
				method: 'POST'
			}
			let param = {
				flag: 'getInspectInfo',
				projectId: projectId,
				procedureName: val
			}
			uni.$emit('send1', this.formData.inspectContent)//传给平行子组件
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){ 
					this.options2 = []
					for(var i=0;i<res.data.data.length;i++){
						this.options2.push({
							value:res.data.data[i].number,
							label:res.data.data[i].inspectItem
						})
					}
				}
			}, error => {
				console.log(error);
			})
		},
		inspectContentChange(val) {
			this.formData.inspectContent = val
			// if(this.formData.inspectContent.length>0){
			// 	this.isconfirm = false
			// }
		},
		//选择问题描述
		selectDescribe(){
			// console.log(this.formData)
			uni.navigateTo({
				url:`SelectDescribe?formData=${JSON.stringify(this.formData)}&defectStr=''`
			})
		},
		back() {
			uni.navigateBack({ delta: 1 });    // 返回上一页
		},
		violationPicPage(){
			let inspectCardParam = JSON.stringify(this.inspectCardParam)
			uni.navigateTo({
				url:`CheckDetailPic?formData=${JSON.stringify(this.formData)}`+`&inspectCardParam=${inspectCardParam}`
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
.button {
    height: 50%;
    width: 30%;
    margin-top: 20px;
}

</style>
