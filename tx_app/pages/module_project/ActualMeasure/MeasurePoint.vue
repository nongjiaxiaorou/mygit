<template>
	<view class="list">
		<uni-section title="测点录入(长按类型进行录入)" type="line"></uni-section>
		<view class="uni-list">
			<uni-collapse :accordion="true">
				<view v-for="(item,index) in pointArr" :key="index">
					<uni-collapse-item :title="item.measureType+' '+item.range" :qualifiedBadge="item.qualifiedBadge" :unQualifiedBadge="item.unQualifiedBadge" size="small" type="success" v-longpress="openModal(item.id,item)" :disabled="item.missItem=='true'?true:false">
					    <view class="collapse-content" style="padding: 30rpx;">
					        <t-table borderColor="#494F54">
					        	<t-tr fontSize="12">
									<t-th>编号</t-th>
					        		<t-th>初测值</t-th>
					        		<t-th>复测值</t-th>
					        		<t-th>终测值</t-th>
					        	</t-tr>
					        	<t-tr v-if="item.children.length" v-for="(item1,index) in item.children" :key="index" fontSize="12">
					        		<t-td>{{item1.number}}</t-td>
					        		<t-td>{{item1.initTestVal}}</t-td>
					        		<t-td>{{item1.retestVal}}</t-td>
									<t-td>{{item1.finalTestVal}}</t-td>
					        	</t-tr>
								<!-- <t-tr v-if="item.children.length==0" fontSize="12">
									<t-td></t-td>
									<t-td></t-td>
									<t-td></t-td>
									<t-td></t-td>
								</t-tr> -->
					        </t-table>
					    </view>
					</uni-collapse-item>
					<neil-modal v-show="show=='show'+item.id" @close="closeModal(item.id)" title="新增测点" @confirm="saveMeasurePoint" @reset="resetPointFunc(item)">
					<!-- <neil-modal v-show="true" @close="closeModal(item.id)" title="新增测点"> -->
					    <view class="input-view">
					        <view class="input-name">
					            <view>检查内容：</view>
					            <input type="text" :value="item.measureType" disabled="disabled"/>
					        </view>
							<view class="input-name">
							    <view>编号类型：</view>
							    <input type="text" :value="item.number" disabled="disabled"/>
							</view>
							<view class="input-name">
							    <view>测点总数：</view>
							    <input type="text" v-model="totalNum" placeholder="测点总数(必填)"/>
							</view>
							<view class="input-name">
							    <view>不合格数：</view>
							    <input type="text" v-model="unqualifiedNum" placeholder="不合格数量(必填)" @input="onChangeHandler($event,item)"/>
							</view>
							<view class="input-name">
								<view>缺项：</view>
								<radio value="r1" style="flex: 1;" @click="missItemFunc" :checked="missItem" />
							</view>
							<view class="scroll-style">
								<scroll-view scroll-y="true" class="scroll-Y">
									<view class="input-name" v-for="(item,index) in unQualifiedArr" :key="index">
									    <view class="num">{{item.number}}：</view>
									    <input type="text" v-model="item.value" :class="item.isCorrect=='true'?'font-green':'font-red'" placeholder="请输入不合格值" @input="unQualifiedChange($event,item)"/>
									</view>
								</scroll-view>
							</view>
							
					    </view>
					</neil-modal>
				</view>
			</uni-collapse>
		</view>
	</view>
</template>

<script>
	import uniCollapse from '@/components/uni-app/uni-collapse/uni-collapse.vue'
	import uniCollapseItem from '@/components/uni-app/uni-collapse-item/uni-collapse-item.vue'
	import neilModal from '@/components/common/neil-modal/neil-modal.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	export default {
		components: {
			uniCollapse,
			uniCollapseItem,
			neilModal,
			tTable,
			tTh,
			tTr,
			tTd
		},
		data() {
			return {
				show: '',
				isOpened: 'none',
				swipeList: [],
				unQualifiedArr: [],
				currentData: [],
				cardParam: [],
				projectId: '',
				pointStatus: '',
				totalNum: '',
				unqualifiedNum: '',
				measureType: '',
				number: '',
				range: '',
				preventTwice: 0,
				pointArr: [],
				missItem: false
			};
		},
		onReady() {
			// 模拟延迟赋值
			setTimeout(() => {
				this.isOpened = 'right';
				this.swipeList = [{
						options: [{
							text: '添加',
							style: {
								backgroundColor: 'rgb(255,58,49)'
							}
						}],
						id: 0,
						content: 'item1'
					},
					{
						id: 1,
						options: [{
								text: '置顶'
							},
							{
								text: '删除',
								style: {
									backgroundColor: 'rgb(255,58,49)'
								}
							}
						],
						content: 'item2'
					},
					{
						id: 2,
						options: [{
								text: '置顶'
							},
							{
								text: '标记为已读',
								style: {
									backgroundColor: 'rgb(254,156,1)'
								}
							},
							{
								text: '删除',
								style: {
									backgroundColor: 'rgb(255,58,49)'
								}
							}
						],
						content: 'item3'
					}
				]
			}, 1000);
		},
		onLoad(option) {
			this.currentData = JSON.parse(option.currentData)
			this.cardParam = JSON.parse(option.cardParam)
			this.projectId = JSON.parse(option.projectId)
			this.getPointStatus()
		},
		methods: { 
			openModal(type,item) {
				console.log(item)
				this.measureType = item.measureType
				this.number = item.number
				this.range = item.range
			    this.show = 'show'+type
				this.unQualifiedArr = []
				this.qualifiedNum = ''
				this.unqualifiedNum = ''
				this.totalNum = ''
				this.missItem = false
			},
			closeModal(type) {
			    this.show = ''
			},
			bindBtn(type) {
			    uni.showToast({
			        title: `点击了${type==='cancel'?'取消':'确定'}按钮`,
			        icon: 'none'
			    })
			},
			onChangeHandler(e,item) {
				this.unQualifiedArr = []
				let unQualifiedNum = e.target.value
				for (var i=0;i<unQualifiedNum;i++) {
					this.unQualifiedArr.push({
						measureType: item.measureType,
						number: item.number+(i+1),
						value: '',
						isCorrect: ''
					})
				}
			},
			//获取测点类型
			getMeasureType() {
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
					console.log(res.data)
					if(res.data.code){
						this.pointArr = res.data.data
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
			},
			//获取测点状态
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
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						this.pointStatus = res.data.data[0].status
						console.log(res.data.data[0].status)
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为'+res.data.data[0].status+'状态！'
						});
						this.getMeasureType()
					}else{
						this.pointStatus = '初测'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为初测状态！'
						});
						this.getMeasureType()
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			saveMeasurePoint() {
				let unQualifiedStr = JSON.stringify(this.unQualifiedArr)
				if(unQualifiedStr.indexOf('false') >= 0){
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请输入正确区间范围内的不合格值！'
					});
				}else{
					let that = this
					let opts = {
						url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
						method: 'POST' 
					}
					let param = {
						flag: 'saveMeasurePoint',
						measureId:this.cardParam.id,
						projectId: this.projectId,
						unQualifiedInfo: JSON.stringify(this.unQualifiedArr),
						qualifiedNum: this.totalNum-this.unQualifiedArr.length,
						measureType: this.measureType,
						pointStatus: this.pointStatus,
						number: this.number,
						missItem: this.missItem
					} 
					let isLoading = true//是否需要加载动画
					this.myRequest.httpRequest (opts, param,isLoading).then(res => {
						console.log(res.data)
						if(res.data.code){
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: '已保存测点信息！'
							});
						}else{
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: '请更换到网络良好的环境删除！'
							});
						}
						this.getMeasureType()
						uni.hideLoading()//隐藏加载中转圈圈
						that.isloading = false//取消遮罩层
					}, error => {
						console.log(error);
					})
				}
				
			},
			//不合格值改变时触发判断不合格值是否在区间内
			unQualifiedChange(e,item) {
				// if(this.preventTwice){
					// console.log(this.preventTwice)
					// this.preventTwice = 0
					let Arr = this.range.substr(1, this.range.length-2).split(',')
					if(item.value<parseInt(Arr[0])||item.value>parseInt(Arr[1])){
						item.isCorrect = 'true'
					}else{
						item.isCorrect = 'false'
					}
					// return
				// }
				// this.preventTwice++
			},
			//重置测点
			resetPointFunc(item) {
				let that = this
				uni.showModal({
				    title: '重置测点',
				    content: '请确认是否重置测点数据，重置后需重新录入！',
				    success: function (res) {
				        if (res.confirm) {
				            let opts = {
				            	url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
				            	method: 'POST' 
				            }
				            let param = {
				            	flag: 'resetPoint',
				            	measureId:that.cardParam.id,
				            	projectId: that.projectId,
				            	measureType: that.measureType
				            } 
				            let isLoading = true//是否需要加载动画
				            that.myRequest.httpRequest (opts, param,isLoading).then(res => {
				            	// console.log(res.data)
				            	if(res.data.code){
				            		uni.showToast({
				            			icon: 'none',
				            			position: 'bottom',
				            			title: '已重置测点信息！'
				            		});
				            	}else{
				            		uni.showToast({
				            			icon: 'none',
				            			position: 'bottom',
				            			title: '请更换到网络良好的环境再重置！'
				            		});
				            	}
								that.show = ''
				            	that.getMeasureType()
				            	uni.hideLoading()//隐藏加载中转圈圈
				            	that.isloading = false//取消遮罩层
				            }, error => {
				            	console.log(error);
				            })
				        } else if (res.cancel) {
				            console.log('用户点击取消');
				        }
				    }
				});
			},
			// 设置测点类型为缺项
			missItemFunc() {
				this.missItem = !this.missItem
			}
		}
	};
</script>

<style lang="scss">
	@import '@/common/uni-ui.scss';

	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4;
		min-height: 100%;
		height: auto;
	}
	
	.inv-h-w {
		background-color: #ffffff;
		height: 80upx;
		display: flex;
	}
	
	.collapse-content{
		background-color: #F9F9FD;
		font-size: 10px;
	}
	
	/deep/ .uni-collapse-cell__title-text{
		font-size: 13px;
		flex: 1;
	}
	
	.input-name{
	    height: 80upx;
	    width: 100%;
	    display: flex;
	    flex-direction: row;
	    justify-content: center;
	    align-items: center;
	    position: relative;
	    padding-left: 30upx;
	    box-sizing: border-box;
	}
	
	.input-name::after {
	    content: " ";
	    position: absolute;
	    left: 30upx;
	    bottom: 0;
	    right: 0;
	    height: 1px;
	    border-top: 1px solid #e5e5e5;
	    transform-origin: 0 0;
	    transform: scaleY(.5);
	}
	
	.input-name view {
	    width: 150upx;
	    height: 50upx;
	    line-height: 50upx;
	    font-size: 28upx;
	    color: #333333;
	}
	
	.input-name input {
	    flex: 1;
	    height: 50upx;
	    line-height: 50upx;
	}
	
	.scroll-Y {
		height: 100px;
	}
	
	.scroll-style {
		margin-top: 10px;
		height: 100px;
	}
	
	.num {
		text-align: right;
	}
	
	.font-green {
		color: green;
	}
	
	.font-red {
		color: red;
	}
	
	uni-modal{
			z-index: 19999 !important;
		}
</style>
