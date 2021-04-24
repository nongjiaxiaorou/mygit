<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectName" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">检查对象：</view>
					<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectObj" /></view>
				</view>
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">检查人：</view>
					<view class="uni-list-cell-db"><input style="margin-left: 28rpx;" class="uni-input" type="text" v-model="formData.userName" /></view>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">组员分配：</view>
					<view class="uni-list-cell-db"><input class="uni-input" type="text" @click="showGroupAssign" placeholder="点击查看"
						 disabled="true" /></view>
				</view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list">
				<uni-collapse :accordion="true">
					<uni-collapse-item v-for="(item,index) in accordion" :key="item.id" :title="item.title" :show-animation="item.animation">
						<view class="uni-list" style="border-radius: 20rpx;">
							<checkbox-group>
								<label class="uni-list-cell uni-list-cell-pd" v-for="(item1,index1) in item.itemsList" :key="item1.value">
									<view>{{item1.value}}({{item1.fullMarks}}分)</view>
									<radio-group ref="gender" @change="checkRadio(item1,$event,index,index1)">
										<label>
											<radio value="满分" :checked="true" style="transform:scale(0.7)"/><text>满分</text>
										</label>
										<label>
											<radio value="扣分" style="transform:scale(0.7)"/><text>扣分</text>
										</label>
										<label>
											<radio value="缺项" style="transform:scale(0.7)"/><text>缺项</text>
										</label>
									</radio-group>
								</label>
							</checkbox-group>
						</view>
					</uni-collapse-item>
				</uni-collapse>
			</view>
		</view>
		<uni-popup id="popup1" ref="popup1" type="center" :animation="false" @change="change1">
			<view class="popup-content">
				<view>
					<input v-model="deductMarks" type="digit" placeholder="请输入扣分值,允许小数点,如1.5" style="border: 1rpx solid #e0e0df;" />
					<textarea v-model="deductReason" placeholder="请输入扣分原因,扣分值允许超过满分值" style="border: 1rpx solid #e0e0df;margin-top: 20rpx;height: 200rpx;"></textarea>
				</view>
			</view>
		</uni-popup>
		<uni-popup id="popup2" ref="popup2" type="center" :animation="false">
			<view class="popup-content">
				<view style="width: 100%;">
					<textarea style="height: 1rpx;margin: 0;padding: 0;"></textarea>
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
		</uni-popup>
		<multiple-select v-model="show" :data="options3" :default-selected="initSelected"></multiple-select>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="submit()">确定</button>
	</view>
</template>

<script>
	import ldSelect from '@/components/common/ld-select/ld-select.vue'
	import multipleSelect from "@/components/common/multiple-select/multiple-select";
	import LbPicker from '../../../components/common/lb-picker'
	import uniCollapse from '@/components/uni-app/uni-collapse/uni-collapse.vue'
	import uniCollapseItem from '@/components/uni-app/uni-collapse-item/uni-collapse-item.vue'
	let that = null
	export default {
		props: {},
		components: {
			ldSelect,
			multipleSelect,
			LbPicker,
			uniCollapse,
			uniCollapseItem,
		},
		data() {
			return {
				isconfirm: false,
				formData: {},
				options1: ['请选择检查名称'],
				options1Index: 0,
				accordion: [],
				radioValue:[],
				deductMarks:'',//扣分值
				deductReason:'',//扣分原因
				accordionIndex:'',
				itemsListIndex:'',
				scrollTop: 0,
				old: {
					scrollTop: 0
				},
				groupAssign: [],
				options3: [],
				initSelected: [],
				show: false,
			};
		},
		watch: {

		},
		onLoad:function(option){
			that = this
			this.formData = JSON.parse(option.data)
			console.log(this.formData)
		},
		onShow() {

		},
		mounted() {
			this.getGroup()
			this.getUserTaskAssign()
		},
		methods: {
			/**
			 * popup 状态发生变化触发
			 * @param {Object} e
			 */
			showGroupAssign(e){
				this.$refs.popup2.open()
			},
			change1(e) {
				 this.accordion[this.accordionIndex].itemsList[this.itemsListIndex].deductMarks = this.deductMarks
				 this.accordion[this.accordionIndex].itemsList[this.itemsListIndex].deductReason = this.deductReason
			},
			//切换勾选项
			checkRadio(val,e,accordionIndex,itemsListIndex){
				this.accordionIndex = accordionIndex
				this.itemsListIndex = itemsListIndex
				this.deductMarks =	this.accordion[this.accordionIndex].itemsList[this.itemsListIndex].deductMarks
				this.deductReason =	this.accordion[this.accordionIndex].itemsList[this.itemsListIndex].deductReason
				this.accordion[this.accordionIndex].itemsList[this.itemsListIndex].radioValue =	e.target.value
				if(e.target.value=='扣分'){
					this.$refs.popup1.open()
				}
			},
			//根据活动名称获取检查小组
			getGroup(){
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/InspectListDetail.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'getInsGroupAssign',
					formData:JSON.stringify(this.formData)
				}
				let isLoading1 = false //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res)
					let memberArr = res.data.member
					let itemsArr = res.data.items
					this.groupAssign = []
					let newItemsList = []
					let completeStateArr = []
					
					for(let i=0;i<memberArr.length;i++){
						newItemsList = []
						
						for(let j=0;j<itemsArr.length;j++){
							completeStateArr = []
							if(itemsArr[j].userId == memberArr[i].userId){
								newItemsList.push({
									value:itemsArr[j].inspectItems,
									checked:true,
									radioValue:'',
									fullMarks:'',
									deductMarks:'',//扣分值
									deductReason:''//扣分原因
								})
								completeStateArr.push(itemsArr[0].completeState)
							}
						}
						if(completeStateArr[0] == undefined){
							completeStateArr[0] = '未完成'
						}
						this.groupAssign.push({
							name:memberArr[i].userName,
							userId:memberArr[i].userID,
							groupPost:memberArr[i].groupPost,
							items:newItemsList,
							completeState:completeStateArr[0]
						})
					}
				}, error => {
					console.log(error);
				})
			},
			//获取该账号在该工程的小组巡查分配任务
			getUserTaskAssign(){
				let opts1 = {
					url: this.api + '/module_company/QualityInspect/InspectListDetail.php',
					method: 'POST'
				}
				let param1 = {
					flag: 'getUserTaskAssign',
					formData:JSON.stringify(this.formData)
				}
				let isLoading1 = true //是否需要加载动画       
				this.myRequest.httpRequest(opts1, param1, isLoading1).then(res => {
					// console.log(res.data.data[0].deductTableCate)
					let oldArr = res.data.data
					this.accordion = []
					let newItemsList = []
					if(oldArr[0].inspectItems != null){
						for(let i=0;i<oldArr.length;i++){
							newItemsList = []
							let inspectQuesArr =  oldArr[i].inspectQues.split('||')
							let fullMarksArr =  oldArr[i].fullMarks.split('||')
							for(let j=0;j<inspectQuesArr.length;j++){
								newItemsList.push({
									value:inspectQuesArr[j],
									checked:true,
									radioValue:'满分',
									fullMarks:fullMarksArr[j],
									deductMarks:'',//扣分值
									deductReason:''//扣分原因
								})
							}
							this.accordion.push({
								id: i,
								title: oldArr[i].inspectItems,
								deductTableCate: oldArr[i].deductTableCate,
								animation: true,
								itemsList:newItemsList
							})
						}
					}else{
						this.accordion.push({
							id: 0,
							title: '该人员没有分配扣分大类',
							animation: true,
							itemsList:[]
						})
					}
					uni.hideLoading()
					isLoading1 = false
				}, error => {
					console.log(error);
				})
			},
			//点击扣分大类
			toggle(e) {
				this.indexOf = e
				this.options3 = []
				for(let i=0;i<this.groupAssign[e].items.length;i++){
					this.options3.push({
						value:this.groupAssign[e].items[i].value,
						label:this.groupAssign[e].items[i].value,
						disabled:true
					})
				}
				this.initSelected = this.options3
				this.show = !this.show
			},
			//提交
			submit(){
				// console.log(this.accordion)
				// console.log(this.formData)
				let opts = {
					url: this.api + '/module_company/QualityInspect/InspectListDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'saveItemsMarks',
					formData:JSON.stringify(this.formData),
					accordion:JSON.stringify(this.accordion)
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					uni.showToast({
					    title: '评分完成，请下发完成质量检查列表',
						icon:"success"
					});
					setTimeout(function () {
						uni.$emit('update')
						uni.navigateBack()
					}, 2000);
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
