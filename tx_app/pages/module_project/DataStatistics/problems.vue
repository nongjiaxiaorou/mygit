<template>
	<view class="content">
		<block>
			<ChooseLits :list="list" :arr="arr" @dateChange="dateChange" @chooseLike="chooseLike"></ChooseLits>
			<view class="main-box">
				<t-table borderColor="#494F54">
					<t-tr fontSize="12">
						<t-th>部位</t-th>
						<t-th>分部工程</t-th>
						<t-th style="width: 80%;">问题描述</t-th>
						<t-th>属性</t-th>
						<t-th style="width: 10%;">逾期</t-th>
					</t-tr>
					<t-tr  fontSize="12" v-for="(item,index) in trList" :key="index" v-show="item.isShow==true">
						<t-td>{{item.inspectPosition}}</t-td>
						<t-td>{{item.inspectItemHead}}</t-td>
						<t-td style="width: 80%;">{{item.violationContent}}</t-td>
						<t-td>{{item.itemType}}</t-td>
						<t-td style="width: 10%;">{{item.state}}</t-td>
					</t-tr>
				</t-table>
			</view> 
		</block>
		<mpvue-picker
			:themeColor="themeColor"
			ref="mpvuePicker"
			:mode="mode"
			:deepLength="deepLength"
			:pickerValueDefault="pickerValueDefault"
			@onConfirm="onConfirm"
			@onCancel="onCancel"
			:pickerValueArray="pickerValueArray"
		></mpvue-picker>
	</view>
	
</template>

<script>
	import mpvuePicker from '../../../components/common/mpvue-picker/mpvuePicker.vue';
	import ChooseLits from '../../../components/common/choose-Cade/choose-Cade.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	export default {
		components: {
			ChooseLits,
			tTable,
			tTh,
			tTr,
			tTd,
			mpvuePicker
		},
		data() {
			return {
				list: ['时间段', '分部工程', '属性','是否逾期'],
				arr: [
					['起始时间'],
					['抹灰工程', 'B公司', 'C公司'],
					['全部问题','一般问题', '重大问题'],
					[ '是', '否']
				],
				i2: [ 0, 0, 0, 0],
				pickerValueArray: [
					{
						label: '实测数据'
					},
					{
						label: '砼强度'
					}
				],
				themeColor: '#007AFF',
				mode: '',
				deepLength: 1,
				pickerValueDefault: [0],
				trList: [],
				startTime: '',
				endTime: '',
				position: ['','','']
			}
		},
		onBackPress() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
				return true;
			}
		},
		onUnload() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
			}
		},
		onNavigationBarButtonTap(e) {
			if (e.index === 0) {
				this.showSinglePicker();
			}
		},
		onLoad() {
			uni.getStorage({
				key:'userInfo',
				success:(res)=>{
					this.title = res.data.projectName
					this.getProblemDetail()
				}
			})
		},
		methods: {
			//选了时间后
			dateChange(e,flag){
				flag=='startTime'?(this.startTime=e):(this.endTime=e)
				if(this.startTime!=''&&this.endTime!=''){
					this.getProblemDetail()
				}
			},
			//获取问题隐患数据
			getProblemDetail() {
				let opts = {
					url: this.api + '/module_project/DataStatistics/DataStatisticsDetail.php',
					method: 'POST'
				}
				let param = {
					flag: "getProblemDetail",
					startTime: this.startTime,
					endTime: this.endTime,
					build: '',
					floor: '',
					unit: '',
					title: this.title
				}
				let isLoading = true
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						this.trList = []
						for(var i=0;i<res.data.data.length;i++){
							if(res.data.data[i].state == '逾期'){
								var state = '是'
							}else{
								var state = '否'
							}
							this.trList.push({
								id: res.data.data[i].id,
								inspectPosition: res.data.data[i].inspectPosition,
								state: state,
								rectificationDate: res.data.data[i].rectificationDate,
								itemType:res.data.data[i].itemType,
								inspectItemHead:res.data.data[i].inspectItemHead,
								violationContent:res.data.data[i].violationContent,
								isShow: true
							})
						}
						uni.hideLoading()//隐藏加载中转圈圈
						this.isloading = false//取消遮罩层
					}
				}, error => {
				
				})
			},
			//选择问题类型
			onCancel(e) {
				console.log(e);
			},
			// 单列
			showSinglePicker() {
				this.mode = 'selector';
				this.deepLength = 1;
				this.pickerValueDefault = [0];
				this.$refs.mpvuePicker.show();
			},
			onConfirm(e) {
				console.log(e.label);
				switch (e.label) {
					case '实测数据': 
						uni.redirectTo({
							url:'measure'
						})
						break;
					case '砼强度': 
						uni.redirectTo({
							url:'concrete'
						})
						break;
				}
				// this.setStyle(0, e.label);
			},
			//下拉筛选
			chooseLike(i1) {
				var length = this.trList.length
				if(i1[0]==1){
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.trList[i].inspectItemHead==this.arr[1][i1[1]]) continue 
							this.trList[i].isShow = false
					}
				}
				if(i1[0]==2){
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.arr[2][i1[1]]=='全部问题') continue
						if(this.trList[i].itemType==this.arr[2][i1[1]]) continue 
							this.trList[i].isShow = false
					}
				}
				if(i1[0]==3){ //第四个选项框改变
					for(var i=0;i<length;i++){
						this.trList[i].isShow = true
						if(this.trList[i].state==this.arr[3][i1[1]]) continue 
							this.trList[i].isShow = false
					}
				}
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
			},
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
