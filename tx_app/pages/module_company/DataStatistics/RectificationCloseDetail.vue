<template>
	<view class="content">
		<block>
			<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
			<view class="main-box">
				<t-table borderColor="#494F54">
					<t-tr fontSize="12">
						<t-th>检查部位</t-th>
						<t-th>状态</t-th>
						<t-th>下发时间</t-th>
					</t-tr>
					<t-tr  fontSize="12" v-for="(item,index) in trList" :key="index" v-show="item.isShow==true">
						<t-td>{{item.inspectPosition}}</t-td>
						<t-td>{{item.state}}</t-td>
						<t-td>{{item.rectificationDate}}</t-td>
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
					['起始时间', '截止时间'],
					['1栋', '2栋', '3栋'],
					['基础层','负一层', '一层', '二层', '三层'],
					['东单元', '西单元']
				],
				i2: [0, 0, 0],
				themeColor: '#007AFF',
				selectObj: '',
				tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'],
				currentData: {},
				trList: [],
				startTime: '',
				endTime: '',
				position: ['','','']
			}
		},
		onLoad:function(option) {
			this.currentData = JSON.parse(option.currentData)
			this.getRectificationDetail()
			// console.log(this.currentData)
		},
		onNavigationBarButtonTap() {
			this.$refs.picker1.show()
		},
		methods: {
			//下拉筛选
			chooseLike(i1) {
				// console.log(i1)
				// console.log(this.i2)
				if(i1[0]==1){ //第二个选项框改变
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
			//获取整改闭合数据
			getRectificationDetail() {
				let opts = {
					url: this.api + '/module_company/DataStatistics/DataStatisticsDetail.php',
					method: 'POST'
				}
				let param = {
					flag: "getRectificationDetail",
					startTime: this.startTime,
					endTime: this.endTime,
					build: '',
					floor: '',
					unit: '',
					title: this.currentData.title
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						this.trList = []
						for(var i=0;i<res.data.data.length;i++){
							this.trList.push({
								id: res.data.data[i].id,
								inspectPosition: res.data.data[i].inspectPosition,
								state: res.data.data[i].state,
								rectificationDate: res.data.data[i].rectificationDate,
								isShow: true
							})
						}
					}
				}, error => {
				
				})
			},
			dateChange(e,flag){
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getRectificationDetail()
				}
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
