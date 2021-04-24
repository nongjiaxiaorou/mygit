<template>
	<view style="padding: 20rpx;">
		<uni-collapse :accordion="true">
			<uni-collapse-item v-for="item in accordion" :key="item.id" :title="item.title" :show-animation="item.animation">
				<view class="uni-list" style="border-radius: 20rpx;">
					<checkbox-group v-if="item.id===0" @change="checkboxChange0">
						<label class="uni-list-cell uni-list-cell-pd" v-for="item in checkItemList" :key="item.value">
							<view>
								<checkbox :value="item.value" :checked="item.checked" />
							</view>
							<view>{{item.name}}</view>
						</label>
					</checkbox-group>
					<checkbox-group v-if="item.id===1" @change="checkboxChange1">
						<label class="uni-list-cell uni-list-cell-pd" v-for="item in commonlyItemList" :key="item.value">
							<view>
								<checkbox :value="item.value" :checked="item.checked" />
							</view>
							<view>{{item.name}}</view>
						</label>
					</checkbox-group>
					<checkbox-group v-if="item.id===2" @change="checkboxChange2">
						<label class="uni-list-cell uni-list-cell-pd" v-for="item in majorItemList" :key="item.value">
							<view>
								<checkbox :value="item.value" :checked="item.checked" />
							</view>
							<view>{{item.name}}</view>
						</label>
					</checkbox-group>
				</view>
			</uni-collapse-item>
		</uni-collapse>
		<!-- 提交信息 -->
		<uni-popup id="dialogInput" ref="dialogInput" type="dialog">
			<uni-popup-dialog mode="input" title="输入内容" placeholder="请输入条目关键字" @confirm="dialogInputConfirm"></uni-popup-dialog>
		</uni-popup>
		<!-- 悬浮按钮 -->
		<uni-fab ref="fab" :pattern="pattern" style="width: 24px;" :content="content" :horizontal="horizontal" :vertical="vertical"
		 :direction="direction" @trigger="trigger" @fabClick="fabClick" />
	</view>
</template>

<script>
	import uniCollapse from '@/components/uni-app/uni-collapse/uni-collapse.vue'
	import uniCollapseItem from '@/components/uni-app/uni-collapse-item/uni-collapse-item.vue'
	import uniList from '@/components/uni-app/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni-app/uni-list-item/uni-list-item.vue'
	import uniSection from '@/components/uni-app/uni-section/uni-section.vue'
	import uniFab from '@/components/uni-app/uni-fab/uni-fab.vue'
	import uniPopup from '@/components/uni-app/uni-popup/uni-popup.vue'
	import uniPopupDialog from '@/components/uni-app/uni-popup-dialog/uni-popup-dialog.vue'

	export default {
		components: {
			uniSection,
			uniCollapse,
			uniCollapseItem,
			uniList,
			uniListItem,
			uniFab,
			uniPopup,
			uniPopupDialog
		},
		data() {
			return {
				title: 'uni-fab',
				directionStr: '垂直',
				horizontal: 'right',
				vertical: 'bottom',
				direction: 'horizontal',
				pattern: {
					color: '#7A7E83',
					backgroundColor: '#fff',
					selectedColor: '#007AFF',
					buttonColor: '#007AFF'
				},
				content: [{
						iconPath: '/static/images/chaxun.png',
						selectedIconPath: '/static/images/chaxuned.png',
						text: '查询',
						active: false
					},
					{
						iconPath: '/static/images/xiafa.png',
						selectedIconPath: '/static/images/xiafaed.png',
						text: '提交',
						active: false
					}
				],
				accordion: [{
						id: 0,
						title: '自主查询条目',
						animation: true,
						value: 'CHN',
						name: '中国',
						checked: 'true'
					},
					{
						id: 1,
						title: '一般问题',
						animation: true,
						value: 'BRA',
						name: '巴西'

					},
					{
						id: 2,
						title: '重大问题',
						animation: true,
						value: 'USA',
						name: '美国',
					}
				],
				checkItemList: [],
				commonlyItemList: [],
				majorItemList: [],
				formData:{},
				checkedList0:[],//自主查询选中
				checkedList1:[],//一般问题选中
				checkedList2:[],//重大问题选中
				pageFrom:'',
				violationItem:[]
			}
		},
		watch:{
			// checkedList2:'sliceList'
		},
		onLoad:function(option){
			this.pageFrom = option.pageFrom
			if(this.pageFrom ==='notice'){
				this.formData = JSON.parse(option.formData)[0]
			}else{
				this.formData = JSON.parse(option.formData)
			}
			this.violationItem = option.defectStr.split('||')
			//获取违章数据库与违章大类对应的违章条目
			let opts = {
				url:this.api+'/module_project/WeeklyInspect/SelectItem.php',
				method:'POST'
			}
			let param = {
				flag:'getViolationItem',
				depotName:this.formData.database,
				majorCate:this.formData.majorCate
			}
			let isLoading = false
			this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
				this.commonlyItemList = []
				this.majorItemList = []
				
				if(res.data.一般问题!=undefined){
					let itemsArr = res.data.一般问题.items.split('||')
					for(let i=0;i<itemsArr.length;i++){
						this.commonlyItemList.push({
							value:itemsArr[i],
							name:itemsArr[i]
						})
					}
				}
				if(res.data.重大问题!=undefined){
					let itemsArr = res.data.重大问题.items.split('||')
					for(let i=0;i<itemsArr.length;i++){
						// console.log(itemsArr[i])
						this.majorItemList.push({
							value:itemsArr[i],
							name:itemsArr[i]
						})
					}
				}
				if(this.pageFrom=='notice'){//从缺陷详情处进入是为了直接新增违章条目，所以应去除之前新增通知单时所选的违章条目
				// console.log(this.formData.majorCate)
					for(let i=0;i<this.violationItem.length;i++){
						for (let j=0; j<this.commonlyItemList.length; j++){
							if(this.commonlyItemList[j].value==this.violationItem[i]){
								this.commonlyItemList.splice(j,1)
								break
							}
						}
					}
					for(let i=0;i<this.violationItem.length;i++){
						for (let j=0; j<this.majorItemList.length; j++){
							if(this.majorItemList[j].value==this.violationItem[i]){
								this.majorItemList.splice(j,1)
								break
							}
						}
					}
				}
			},error=>{
				
			})
		},
		methods: {
			//提交对话框内容
			dialogInputConfirm(done, val) {
				console.log(val)
				console.log(this.formData.majorCate)
				//模糊查询条目
				let opts = {
					url:this.api+'/module_project/WeeklyInspect/SelectItem.php',
					method:'POST'
				}
				let param = {
					flag:'searchViolationItem',
					depotName:this.formData.database,
					majorCate:this.formData.majorCate,
					keyWorld:val
				}
				let isLoading = false
				this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
					console.log(res)
					if(res.data.一般问题!=undefined){
						let itemsArr = res.data.一般问题.items.split('||')
						for(let i=0;i<itemsArr.length;i++){
							this.checkItemList.push({
								value:itemsArr[i],
								name:itemsArr[i]
							})
						}
					}
					if(res.data.重大问题!=undefined){
						let itemsArr = res.data.重大问题.items.split('||')
						for(let i=0;i<itemsArr.length;i++){
							console.log(itemsArr[i])
							this.checkItemList.push({
								value:itemsArr[i],
								name:itemsArr[i]
							})
						}
					}
					for(let i=0;i<this.violationItem.length;i++){
						for (let j=0; j<this.checkItemList.length; j++){
							if(this.checkItemList[j].value==this.violationItem[i]){
								this.checkItemList.splice(j,1)
								break
							}
						}
					}
				},error=>{
					
				})
				setTimeout(() => {
					uni.hideLoading()
					// 关闭窗口后，恢复默认内容
					done()
				}, 1000)
			},
			//对话框取消按钮
			dialogClose(done) {
				this.msgType = 'info'
				this.message = '点击了对话框的取消按钮'
				this.$refs.popupMessage.open()
				// 需要执行 done 才能关闭对话框
				done()
			},
			//触发悬浮按钮中的事件
			trigger(e) {
				// console.log(e)
				let indexOf = e.index
				if(indexOf==0){//查询事件
					this.$refs.dialogInput.open()
				}else{//提交事件
					let checkedList = []
					checkedList = checkedList.concat(this.checkedList0).concat(this.checkedList1).concat(this.checkedList2)
					console.log(checkedList)
					if(this.pageFrom=='notice'){
						//插入新的缺陷记录
						let opts = {
							url:this.api+'/module_project/WeeklyInspect/SelectItem.php',
							method:'POST'
						}
						let param = {
							flag:'addDefect',
							formData:JSON.stringify(this.formData),
							violationItem:checkedList
						}
						let isLoading = false
						this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
							console.log(res)
							uni.$emit('updateNotice')
						},error=>{
							
						})
					}else{
						let checkedList = []
						checkedList = checkedList.concat(this.checkedList0).concat(this.checkedList1).concat(this.checkedList2)
						console.log(checkedList.length)
						uni.$emit('selectItem',{
							itemsNum:checkedList.length,
							violationItem:checkedList
						})
					}
					uni.navigateBack()
				}
			},
			//点击悬浮按钮
			fabClick() {
				// uni.showToast({
				// 	title: '点击了悬浮按钮',
				// 	icon: 'none'
				// })
			},
			change(e) {},
			checkboxChange0: function(e) {
				console.log(e)
				var items = this.checkItemList,
					values = e.detail.value;
					this.checkedList0 = values
				for (var i = 0, lenI = items.length; i < lenI; ++i) {
					const item = items[i]
					if (values.indexOf(item.value) >= 0) {
						this.$set(item, 'checked', true)
					} else {
						this.$set(item, 'checked', false)
					}
				}
			},
			checkboxChange1: function(e) {
				console.log(e)
				var items = this.commonlyItemList,
					values = e.detail.value;
					this.checkedList1 = values
				for (var i = 0, lenI = items.length; i < lenI; ++i) {
					const item = items[i]
					if (values.indexOf(item.value) >= 0) {
						this.$set(item, 'checked', true)
					} else {
						this.$set(item, 'checked', false)
					}
				}
				console.log(values)
			},
			checkboxChange2: function(e) {
				console.log(e)
				var items = this.majorItemList,
					values = e.detail.value;
					this.checkedList2 = values
				for (var i = 0, lenI = items.length; i < lenI; ++i) {
					const item = items[i]
					if (values.indexOf(item.value) >= 0) {
						this.$set(item, 'checked', true)
					} else {
						this.$set(item, 'checked', false)
					}
				}
			}
		}
	}
</script>

<style>
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4;
		min-height: 100%;
		height: auto;
	}

	view {
		font-size: 28rpx;
		line-height: inherit;
	}

	.uni-list-cell {
		justify-content: flex-start
	}

	.content {
		padding: 15px;
		font-size: 14px;
		line-height: 20px;
		background-color: #f9f9f9;
		color: #666;
	}
	
	.main{
		font-size: 28rpx;
	}
	.bg-white{
		background-color: #FFFFFF;
	}
	.text-blue{
		color: #0081ff;
	}
	.text-green{
		color: #39b54a;
	}
	.input {
		display: flex;
		align-items:center;
		font-size: 28rpx;
		height: 60rpx;
		padding: 10rpx 20rpx;
		border-radius: 10rpx;
		border-style: solid;
		border-width: 1rpx;
		border-color: rgba(0, 0, 0, 0.1);
		input{
			flex: 1;
		}
	}
	.select-modal {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 9999;
		opacity: 0;
		outline: 0;
		text-align: center;
		-ms-transform: scale(1.185);
		transform: scale(1.185);
		backface-visibility: hidden;
		perspective: 2000rpx;
		background: rgba(0, 0, 0, 0.6);
		transition: all 0.3s ease-in-out 0s;
		pointer-events: none;
		margin-bottom: -1000rpx;
		&::before {
			content: "\200B";
			display: inline-block;
			height: 100%;
			vertical-align: bottom;
		}
		.select-dialog {
			position: relative;
			display: inline-block;
			margin-left: auto;
			margin-right: auto;
			background-color: #f8f8f8;
			overflow: hidden;
			width: 100%;
			border-radius: 0;
			.select-content{
				// background-color: #F1F1F1;
				max-height: 420rpx;
				overflow:auto;
				.select-item{
					padding: 20rpx;
					display: flex;
					.title{
						flex: 1;
					}
				}
			}
		}
	}
	.select-modal.show {
		opacity: 1;
		transition-duration: 0.3s;
		-ms-transform: scale(1);
		transform: scale(1);
		overflow-x: hidden;
		overflow-y: auto;
		pointer-events: auto;
		margin-bottom: 0;
	}
	.select-bar {
		padding: 0 20rpx;
		display: flex;
		position: relative;
		align-items: center;
		min-height: 80rpx;
		justify-content: space-between;
		.action {
			display: flex;
			align-items: center;
			height: 100%;
			justify-content: center;
			max-width: 100%;
		}
	}
</style>
