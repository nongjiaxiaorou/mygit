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
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查工序：
						</view>
						<view class="uni-list-cell-db">
							 <ld-select3 :multiple="true" :list="options1" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.inspectProcedure"
							  @change="selectProcedure"></ld-select3>
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
				<view class="uni-list">
					<view class="uni-list-cell">
						<view class="uni-list-cell-left">
							检查内容：
						</view>
						<view class="uni-list-cell-db">
							<ld-select4 :multiple="true" :list="options2" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.inspectContent"
							 @change="inspectContentChange"></ld-select4>
						</view>
					</view>
				</view>
			</view>
		</view>
		<pick-date class="mt-1" datename="检查日期：" @date="getInspectDate"></pick-date>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">排查人员：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell" @click="selectDescribe">
				<view class="uni-list-cell-left">问题描述：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.questionDescribe" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="截止日期：" @date="getEndDate"></pick-date>
		<view class="flex">
			<button type="primary" class="button" @click="back()">关闭</button>
			<button type="primary" class="button" @click="violationPicPage()">违章照片</button>
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
			isconfirm:true,
			formData: {
				inspectLevel:'',//检查层级
				noticeNumber: '',//通知单编号
				inspectItem: '',//检查项目
				inspectProcedure: [], //检查工序
				inspectPosition:'',//检查部位
				inspectContent:[],//检查内容
				inspectDate:'',//检查日期
				leaderName:'',//组长名称
				questionDescribe:'选中了0项',//问题描述
				violationItem:[],
				itemList: [],
				endDate: ''
			},
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
			imageList: []
		};
	}, 
	onLoad:function(option) {
		this.currentData = JSON.parse(option.currentData)
		this.cardParam = JSON.parse(option.cardParam)
		// console.log(this.currentData,this.cardParam)
	},
	onShow() {
		uni.$on('selectItem',res=>{
			// console.log(res)
			this.formData.questionDescribe = '选中了'+res.itemsNum+'项'
			this.formData.violationItem = res.violationItem
		})
		uni.$on('sendChildPageData',res=>{
			console.log(res)
			this.formData.itemList = res.lists
		})
	},
	mounted() {
		let that = this 
		this.getStoragedata()
		setTimeout(function(){
			that.getNewCardDataFunc() 
		},1000)
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
			this.formData.inspectLevel = "项目部"
			var myDate = new Date();//获取系统当前时间
			var timesTamp = myDate.toLocaleTimeString('chinese', { hour12: false });
			timesTamp=timesTamp.replace(/:/g,"");
			let rndVal = this.RndNum(2)
			let month = myDate.getMonth() + 1;
			let strDate = myDate.getDate();
			this.formData.noticeNumber = "项—"+month+strDate+timesTamp+rndVal
			this.formData.inspectItem = this.cardParam.inspectItem
			this.formData.inspectPosition = this.buildSelData.build + this.buildSelData.floor + this.buildSelData.unit
			this.formData.inspectDate = this.commonFunc.getNowDate()
			this.formData.inspectPerson = this.currentData.username
			this.getProcedureFunc()
			
		},
		//获取检查工序
		getProcedureFunc() {
			let projectId = this.projectInfo.projectId
			let opts = {
				url: this.api+'/module_project/InspectAccept/AddItemInspect.php',
				method: 'POST'
			}
			let param = {
				flag: 'getProcedureInfo',
				projectId: projectId,
				inspectItem: this.cardParam.inspectItem,
				inspectAcceptId: this.cardParam.id
			}
			uni.$emit('send', this.formData.inspectProcedure)//传给平行子组件
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层 
				// console.log(res.data.code)
				if(res.data.code){ 
					this.options1 = []
					// console.log(res.data.data)
					for(var i=0;i<res.data.data.length;i++){
						let label = res.data.data[i].procedureName.split('.')[1]
						this.options1.push({
							value:label,
							label:label
						})
					}
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
				pathToBase64(obj[i].picPath).then(base64 => {
					this.formData.itemList[i].picPath = base64
				})
				.catch(error => {
					console.error(error)
				})
				console.log(this.formData.itemList[i].picPath)
			}
		},
		//保存卡片信息
		saveCardDataFunc() {
			console.log(111111)
			var arr = []
			for(var i=0;i<this.formData.inspectContent.length;i++){
				arr[i] = this.formData.inspectContent[i].split('.')[0]
			}
			let opts = {
				url: this.api+'/module_project/InspectAccept/UploadPicture.php',
				method: 'POST'
			}
			let param = {
				flag: "saveViolationPic",
				imageList: JSON.stringify(this.imageList),
				formData: JSON.stringify(this.formData),
				projectId: this.projectInfo.projectId,
				measureId: this.cardParam.id,
				number: arr.join('||')
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res.data)
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				if(res.data.code){ 
					uni.$emit('updateCardData')
					uni.navigateBack()
				}
			}, error => {
				console.log(error);
			})
		},
		confirm() {
			this.urlTobase64(this.formData.itemList)
			let that = this
			setTimeout(that.saveCardDataFunc,1000)
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
			if(this.formData.inspectContent.length>0){
				this.isconfirm = false
			}
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
			// console.log(this.formData)
			uni.navigateTo({
				url:`ViolationContent?formData=${JSON.stringify(this.formData)}`
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
	font-size: 28rpx;
}

</style>
