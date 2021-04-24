<template>
	<view class="content">

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">巡检编码：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectCode" /></view>
			</view>
		</view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查人：</view>
				<view class="uni-list-cell-db" style="margin-left: 28rpx;"><input class="uni-input" type="text" v-model="formData.inspectPerson" /></view>
			</view>
		</view>
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">资料名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.fileName" /></view>
			</view>
		</view>
		
		<pick-date class="mt-1" datename="检查日期：" @date="getInsDate"></pick-date>
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
				inspectCode: '',//巡检编码
				inspectPerson: '',//检查人
				fileName:'',//资料名称
				inspectDate:'',//检查日期
				timeStamp:new Date().getTime(),//时间戳
			},
			currentData:[],
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
		this.formData.projectId = this.currentData.projectId
		uni.getStorage({
			key:'userInfo',
			success: (res) => {
				console.log()
				this.formData.inspectPerson = res.data.username
			}
		})
		//获取通知单编码
		let opts = {
			url:this.api + '/module_project/WeeklyInspect/AddFileInspect.php',
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
	mounted() {
		
	},
	methods: {
		//获取检查日期
		getInsDate(val){
			this.formData.inspectDate = val
		},
		//提交
		submit() {
			// console.log(JSON.stringify(this.buildSelData))
			//新建现场质量巡检草稿通知单
			let opts = {
				url:this.api + '/module_project/WeeklyInspect/AddFileInspect.php',
				method:'POST'
			}
			let param = {
				flag:'saveFileInspect',
				formData:JSON.stringify(this.formData),
				buildSelData:JSON.stringify(this.buildSelData),
			}
			let isLoading = false
			this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
				console.log(res)
				uni.$emit('updateFileIns')
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
