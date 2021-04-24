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
				<view class="uni-list-cell-left">巡查类别：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectCate" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">巡检编码：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectCode" /></view>
			</view>
		</view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查单位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectUnit" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查对象：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectObj" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">违章大类：</view>
				<view class="uni-list-cell-db">
					<ld-select :multiple="true" :list="options1" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.majorCate"
					 @change="selectMajorCate"></ld-select>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">违章数据库:</view>
				<view class="uni-list-cell-db">
					<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.database" /></view>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">分部分项：</view>
				<view class="uni-list-cell-db">
					<ld-select1 :multiple="true" :list="options3" list-key="label" value-key="value" placeholder="请选择" clearable v-model="formData.subItem"
					 @change="selectSubItem"></ld-select1>
				</view>
			</view>
		</view>
		
		<pick-date class="mt-1" datename="检查日期：" @date="getInsDate"></pick-date>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">违章状态：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.violationState" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">组长电话：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.leaderPhone" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="截止日期：" @date="getEndDate"></pick-date>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">违章条目：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" placeholder="请选择" v-model="formData.violationItemNum" @click="selectItem" disabled="true"/></view>
			</view>
		</view>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="submit()">确定</button>
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
import ldSelect from '@/components/common/ld-select/ld-select.vue'
import ldSelect1 from '@/components/common/ld-select/ld-select1.vue'
export default {
	props:{},
	components: {
		'pick-date': pickdate,
		ldSelect,
		ldSelect1
	},
	data() {
		return {
			isconfirm:false,
			formData: {
				projectId:'',//工程id
				inspectLevel:'周巡检',//检查层级
				inspectCate: '质量巡查',//巡查类别
				inspectCode: '',//巡检编码
				inspectUnit:'项目部',//检查单位
				inspectObj: '',//检查对象
				majorCate: [],//违章大类
				database:'',//违章数据库
				subItem:[],//分部分项
				violationState:'未下达',//违章状态
				leaderPhone:'',//组长手机
				violationItem:'',//违章条目
				violationItemNum:'选中了0项',//违章条目数量
				inspectDate:'',
				endDate:''
			},
			currentData:[],
			pournum: ['1', '2', '3', '4', '5', '6', '7', '9', '10'],
			options1: [{
				value: '一般问题',
				label: '一般问题'
			}, {
				value: '重大问题',
				label: '重大问题'
			}],
			options2: [],
			depotName:'',
			options3: [],
			index2:0,
			selectItems:'',
			buildSelData:{
				section:'',
				build:'',
				floor:'',
				unit:''
			}
		};
	},
	onLoad:function(option) {
		this.buildSelData = JSON.parse(option.buildSelData)
		this.currentData = JSON.parse(option.currentData)
		this.formData.inspectObj = this.currentData.projectName
		this.formData.leaderPhone = this.currentData.phone
		this.formData.projectId = this.currentData.projectId
		this.formData.database = this.currentData.database
		console.log(this.formData.database)
		//获取通知单编码
		let opts = {
			url:this.api + '/module_project/WeeklyInspect/AddSceneInspect.php',
			method:'POST'
		}
		let param = {
			flag:'getInspectCode',
			projectId:this.currentData.projectId,
			nowDateStr:this.commonFunc.getNowDateStr()
		}
		let isLoading = false
		this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
			this.formData.inspectCode = this.commonFunc.getNowDateStr()+'-'+res.data[0].bianhao+'-'+this.currentData.projectId
		},error=>{
			
		})
	},
	onShow() {
		uni.$on('selectItem',res=>{
			// console.log(res)
			this.formData.violationItemNum = '选中了'+res.itemsNum+'项'
			this.formData.violationItem = res.violationItem
		})
	},
	mounted() {
		
	},
	methods: {
		//选择违章数据库
		pickerDatabase(e){
			// console.log(this.formData.database)
			this.index2 = e.target.value
			this.formData.database = this.options2[this.index2]
			uni.$emit('send', this.formData.database)//传给平行子组件
			
			//根据所选违章数据库获取对应分部分项
			let opts = {
				url:this.api + '/module_project/WeeklyInspect/AddSceneInspect.php',
				method:'POST'
			}
			let param = {
				flag:'getSubItem',
				depotName:this.formData.database
			}
			let isLoading = false
			this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
				let dataItems = this.commonFunc.Es5duplicate(res.data.data,'subItems')
				this.options3 = []
				for(let i=0;i<dataItems.length;i++){
					this.options3.push({
						value:dataItems[i].subItems,
						label:dataItems[i].subItems
					})
				}
			},error=>{
				
			})
		},
		//选择获取违章条目
		selectItem(){
			uni.navigateTo({
				url:`SelectItem?formData=${JSON.stringify(this.formData)}&defectStr=''`
			})
		},
		//选择违章大类
		selectMajorCate(val) {
			// console.log(val)
			if(val==''){
				this.formData.subItem = []
				this.options3 = []
			}
			this.formData.majorCate = val
			
			//获取分部分项
			let opts = {
				url:this.api + '/module_project/WeeklyInspect/AddSceneInspect.php',
				method:'POST'
			}
			let param = {
				flag:'getSubItem',
				depotName:this.formData.database
			}
			let isLoading = false
			this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
				let dataItems = this.commonFunc.Es5duplicate(res.data.data,'subItems')
				this.options3 = []
				for(let i=0;i<dataItems.length;i++){
					this.options3.push({
						value:dataItems[i].subItems,
						label:dataItems[i].subItems
					})
				}
			},error=>{
				
			})
		},
		selectSubItem(val) {
			this.formData.subItem = val
		},
		//获取检查日期
		getInsDate(val){
			this.formData.inspectDate = val
		},
		//获取截止日期
		getEndDate(val){
			this.formData.endDate = val
		},
		//提交
		submit() {
			//新建现场质量巡检草稿通知单
			let opts = {
				url:this.api + '/module_project/WeeklyInspect/AddSceneInspect.php',
				method:'POST'
			}
			let param = {
				flag:'saveSceneInspect',
				formData:JSON.stringify(this.formData),
				buildSelData:JSON.stringify(this.buildSelData),
				timeStamp:new Date().getTime()
			}
			let isLoading = false
			this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
				console.log(res)
				uni.$emit('updateSceneIns')
				uni.navigateBack()
			},error=>{
				
			})
		},
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
