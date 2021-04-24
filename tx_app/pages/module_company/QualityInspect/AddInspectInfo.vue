<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查名称：</view>
				<view class="uni-list-cell-db">
					<picker @change="inspectNameChange" :value="options1Index" :range="options1">
					    <view class="uni-input">{{options1[options1Index]}}</view>
					</picker>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">组员分配：</view>
					<view class="uni-list-cell-db"><input class="uni-input" type="text" @click="changeIsShow" v-model="value"
						 disabled="true" /></view>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">
					检查对象：
				</view>
				<view class="uni-list-cell-db">
					<view class="list-item" @tap="handleTap('picker3')">
						<view class="item-content">
							<view class="item-value">
								<!-- <text class="item-label">{{ label2 }}</text> -->
								<view v-if="!label3" class="uni-input">请选择检查对象</view>
								<view v-if="label3" class="uni-input">{{label3}}</view>
							</view>
						</view>
						<lb-picker ref="picker3" v-model="formData.inspectObj" mode="multiSelector" :list="list3" :level="2" :dataset="{ name: 'label3' }"
						 :formatter="formatter" @change="handleChange3" @confirm="handleConfirm" @cancel="handleCancel">
						</lb-picker>
					</view>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1" v-if="isShow">
			<view>
				<view class="uni-flex uni-row">
					<view class="flex-item uni-border-bottom">姓名</view>
					<view class="flex-item uni-border-x">扣分大类</view>
					<view class="flex-item uni-border-bottom">扣分状态</view>
				</view>

				<scroll-view :scroll-top="scrollTop" scroll-y="true" class="scroll-Y">
					<block v-for="(item,index) in groupAssign" :key="index">
						<view class="uni-flex uni-row">
							<view class="flex-item ">{{item.name}}</view>
							<view class="flex-item uni-border-x" @click="toggle(index)">选择了{{item.items.length}}项</view>
							<view class="flex-item ">{{item.completeState}}</view>
						</view>
					</block>
				</scroll-view>
			</view>
		</view>
		<view class="uni-title uni-common-pl"></view>
		<!-- <zhilin-picker v-model="show" :title="title" :options="options3" :initSelected="initSelected" :showSearch="true" @change="onChange"
		 @confirm="onConfirm" /> -->
		 <multiple-select v-model="show" :data="options3" :default-selected="initSelected" @confirm="confirm"></multiple-select>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="submit()">确定</button>
	</view>
</template>

<script>
	import ldSelect from '@/components/common/ld-select/ld-select.vue'
	import zhilinPicker from "@/components/common/zhilin-picker/zhilin-picker/zhilin-picker.vue"
	import LbPicker from '../../../components/common/lb-picker'
	import multipleSelect from "@/components/common/multiple-select/multiple-select";
	export default {
		props: {},
		components: {
			ldSelect,
			zhilinPicker,
			LbPicker,
			multipleSelect
		},
		data() {
			return {
				isconfirm: false,
				isShow: false,
				show: false,
				title:'选择检查大类',
				value:'点击定义组员对应扣分大类',
				initSelected: [],
				scrollTop: 0,
				old: {
					scrollTop: 0
				},
				options1: ['请选择检查名称'],
				options1Index: 0,
				groupAssign: [],
				options3: ['五险', '加班补助', '出国旅行', '生日福利', '年终分红', '带薪年假', '节日福利', '包吃', '包住', '聚餐经费', '交通补贴'],
				type: 'top',
				indexOf:'',//点击获取下标
				
				formData:{
					projectId:'',
					company:'',
					companyRank:'',
					taskId:[],
					groupId:'',
					inspectObj: [],
					inspectName: [],
				},
				label3: '',
				list3: [],
			};
		},
		onReady() {
			this.$nextTick(() => {
				// 此处可以调用getColumnsInfo方法获取默认值的选项信息
				const info3 = this.$refs.picker3.getColumnsInfo(this.formData.inspectObj)
				// this.label3 = info3.item.map(m => m.label).join('-')
			})
		},
		mounted() {
			this.getComProject()
			this.getInspectName()
		},
		methods: {
			//异步获取检查名称
			getInspectName(){
				let opts = {
					url: this.api + '/module_company/QualityInspect/AddInspectInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getInspectName'
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					console.log(res.data.data)
					let oldArr = res.data.data
					for(let i=0;i<oldArr.length;i++){
						this.options1.push(oldArr[i].inspectName)
						this.formData.taskId.push(oldArr[i].id)
					}
				}, error => {
					console.log(error);
				})
			},
			//异步获取检查对象（公司项目）
			getComProject(){
				let opts = {
					url: this.api + '/module_company/QualityInspect/AddInspectInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getComProject'
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					let companyArr = res.data.company
					let projectId = res.data.projectId
					let companyRank = res.data.companyRank
					let projectNameArr = res.data.projectName
					let child_arry = []
					for (let i = 0; i < companyArr.length; i++) {
						child_arry = []
						for (let j = 0; j < projectNameArr[i].split(',').length; j++) {
							child_arry.push({
								value: projectNameArr[i].split(',')[j],
								label: projectNameArr[i].split(',')[j]+"/"+projectId[i].split(',')[j],
							})
						}
						this.list3.push({
							value: companyArr[i],
							label: companyArr[i]+"/"+companyRank[i],
							children: child_arry
						})
					}
				}, error => {
					console.log(error);
				})
			},
			//选择检查名称
			inspectNameChange(e){
			    this.options1Index = e.target.value
				this.formData.inspectName = this.options1[this.options1Index]
				this.formData.taskId = this.formData.taskId[this.options1Index-1]
				//根据活动名称获取检查小组
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/AddInspectInfo.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'getInsGroup',
					inspectName:this.formData.inspectName
				}
				let isLoading1 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					console.log(res)
					let oldArr = res.data.data
					this.groupAssign = []
					this.formData.groupId = oldArr[0].groupID
					for(let i=0;i<oldArr.length;i++){
						this.groupAssign.push({
							name:oldArr[i].name,
							userId:oldArr[i].userID,
							groupPost:oldArr[i].groupPost,
							items:[],
							completeState:'未完成'
						})
					}
				}, error => {
					console.log(error);
				})
				
				//根据活动名称获取扣分大类
				let opts2 = {
					url: this.api + '/module_company/QualityInspect/AddInspectInfo.php',
					method: 'POST'
				}
				let param2 = {
					flag: 'getInsCate',
					inspectName:this.formData.inspectName
				}
				let isLoading2 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts2, param2, isLoading2).then(res => {
					// console.log(res)
					let oldArr = res.data.data
					this.options3 = []
					for(let i=0;i<oldArr.length;i++){
						this.options3.push({
							value:oldArr[i].inspectCate,
							label:oldArr[i].inspectCate,
							disabled:false
						})
					}
				}, error => {
					console.log(error);
				})
			},
			//点击触发检查对象选择
			handleTap(picker) {
				this.$refs[picker].show()
			},
			formatter({
				item,
				rowIndex,
				columnIndex
			}) {
				return `${item.value}`
			},
			handleChange3(e) {
				this.label3 = e.value[0] + "-" + e.value[1]
				this.formData.projectId = e.item[1].label.split('/')[1]
				this.formData.company = e.item[0].label.split('/')[0]
				this.formData.companyRank = e.item[0].label.split('/')[1]
				console.log('change::', e.value)
			},
			handleConfirm(e) {},
			handleCancel(e) {
				console.log('cancel::', e)
			},
			//点击扣分大类
			toggle(e) {
				this.indexOf = e
				//此次点击的人员所选项可选 其余人禁止选中
				for(let i=0;i<this.groupAssign.length;i++){
					if(this.groupAssign[this.indexOf].userId != this.groupAssign[i].userId){//其余人
						for(let j=0;j<this.groupAssign[i].items.length;j++){
							this.groupAssign[i].items[j].disabled = true
						}
					}else{//此次点击的人
						for(let j=0;j<this.groupAssign[i].items.length;j++){
							this.groupAssign[i].items[j].disabled = false
						}
					}
				}
				this.initSelected = this.groupAssign[this.indexOf].items
				this.show = !this.show
			},
			// 确定事件
			confirm(data) {
				console.log(data);
				this.groupAssign[this.indexOf].items = data
				console.log(this.groupAssign)
				// this.info = data.map(el => el.label).join(",");
			},
			onChange(val) {
				console.log(val)
			},
			onConfirm(val) {
				console.log(val)
				let newarr = val.split(',')
				this.groupAssign[this.indexOf].items = []
				this.groupAssign[this.indexOf].items = newarr
			},
			//显示隐藏组员定义
			changeIsShow() {
				this.isShow = !this.isShow
			},
			//提交
			submit(){
				//新建检查信息任务单
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/AddInspectInfo.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'addInsTask',
					groupAssign:JSON.stringify(this.groupAssign),
					timeStamp:new Date().getTime(),
					formData:JSON.stringify(this.formData)
				}
				let isLoading1 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res)
					if(res.data.states === 'success'){
						this.value = '分配完成'
						uni.showToast({
						    title: '分配完成',
							icon:"success"
						});
						setTimeout(function () {
							uni.$emit('update')
							uni.navigateBack()
						}, 2000);
					}else{
						uni.showToast({
							title: '该工地已存在此巡查任务！',
							icon:'none'
						});
					}
				}, error => {
					console.log(error);
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

	.flex-item {
		width: 33.3%;
		height: 100rpx;
		text-align: center;
		line-height: 100rpx;
		border-bottom: 1rpx solid #e4e5e5;
	}

	.uni-border-x {
		// border-bottom: 1rpx solid #494F54;
		border-left: 1rpx solid #e4e5e5;
		border-right: 1rpx solid #e4e5e5;
	}

	.scroll-Y {
		height: 300rpx;
	}

	.scroll-view_H {
		white-space: nowrap;
		width: 100%;
	}

	.scroll-view-item {
		height: 300rpx;
		line-height: 300rpx;
		text-align: center;
		font-size: 36rpx;
	}

	.scroll-view-item_H {
		display: inline-block;
		width: 100%;
		height: 300rpx;
		line-height: 300rpx;
		text-align: center;
		font-size: 36rpx;
	}

	.popup-content {
		background-color: #fff;
		padding: 15px;
	}
</style>
