<template>
	<view class="">
		<uni-table  stripe emptyText="暂无更多数据" width="10px">
			<!-- 表头行 -->
			<uni-tr >
				<uni-th align="center" width="0" >编号</uni-th>
				<uni-th align="center" width="0" >测试类型</uni-th>
				<uni-th align="center" width="1" >合格率</uni-th>
				<uni-th align="center" width="20" >预警状态</uni-th>
			</uni-tr>
			<!-- 表格数据行 -->
			<view v-for="(item,index) in pointArr" :key="index">
				<uni-tr v-show="item.missItem!='true'">
					<uni-td width="10" align="center">{{item.number}}</uni-td>
					<uni-td width="10" >{{item.measureType}}</uni-td>
					<uni-td width="10" align="center">{{item.qualityPercentage}}</uni-td>
					<uni-td width="10" align="center">{{item.qualityInspectionStatus}}</uni-td>
				</uni-tr>
			</view>
			

		
		</uni-table>
	</view>
</template>

<script>
	// import uniTable from '@/components/common/uni-table/components/uni-table/uni-table.vue'
	// import uniTd from '@/components/common/uni-table/components/uni-td/uni-td.vue'
	// import uniTr from '@/components/common/uni-table/components/uni-tr/uni-tr.vue'
	// import uniTh from '@/components/common/uni-table/components/uni-th/uni-th.vue'
	import uniTable from '@/components/common/t-table/t-table.vue'
	import uniTd from '@/components/common/t-table/t-td.vue'
	import uniTr from '@/components/common/t-table/t-tr.vue'
	import uniTh from '@/components/common/t-table/t-th.vue'

	export default {
		components: {
			uniTable,
			uniTr,
			uniTd,
			uniTh
		},
		props: {
			
		},
		data () {
			return {
				pointArr:[]
			}
		},
		onLoad(option) {
			this.currentData = JSON.parse(option.currentData)
			this.cardParam = JSON.parse(option.cardParam)
			this.projectId = JSON.parse(option.projectId)
		},
		created () {
			this.getPointStatus();
		},
		methods: {
			getPointStatus() {
				let that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'getPointStatus',
					measureId:this.cardParam.id,
					projectId: this.projectId
				} 
				console.log(param)
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						this.pointStatus = res.data.data[0].status
						console.log(res.data.data[0].status)
						this.getPointArr()
					}else{
						this.getPointArr()
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			getPointArr () {	
				let that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'getMeasureType',
					inspectItem: JSON.stringify(this.cardParam.inspectItem),
					pointStatus: this.pointStatus,
					projectId: this.projectId,
					measureId:this.cardParam.id
				} 
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					if(res.data.code){
						this.pointArr = res.data.data
						console.log(this.pointArr)
					}else{
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '请更换到网络良好的环境删除！'
						});
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
				
			}
		}	
	}
</script>


<style>
</style>
