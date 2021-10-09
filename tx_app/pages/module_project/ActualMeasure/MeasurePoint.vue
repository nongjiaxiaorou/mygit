<template>
	<view class="list" >
		<uni-section :title="statusTitle" type="line" style="margin-top: 0px;">
			<button  type="primary" v-show="isIntoReTest"  @click="tapRetest">点击进行复测</button>
			<button v-show="isIntoFinalTest" type="warn" @click="tapFinalTest">点击进行终测</button>
		</uni-section>
		<view class="uni-list">
			<!-- <view class="">
				{{isIntoReTest}}
			</view> -->
			<uni-collapse :accordion="true">
				<!-- <view v-for="(item,index) in pointArr" :key="item.id"  @click.middle ="pointStatus == '初测'? openModal(item.id,item) : pointStatus == '复测'? openModal1(item.id,item) : null " > -->
				<view v-for="(item,index) in pointArr" :key="item.id"  @longtap = "pointStatus == '初测'? openModal(item.id,item) : pointStatus == '复测'? openModal1(item.id,item) : null " >
					<uni-collapse-item-badge :title="item.measureType+' '+item.range" :qualifiedBadge="item.qualifiedBadge" :unQualifiedBadge="item.unQualifiedBadge" size="small" type="success" :disabled="item.missItem=='true'? true:false" >
						<view class="collapse-content" style="padding: 30rpx;" >
					        <t-table borderColor="#494F54">
					        	<t-tr fontSize="12">
									<t-th>编号</t-th>
					        		<t-th>初测值</t-th>
					        		<t-th>复测值</t-th>
					        		<t-th>终测值</t-th>
					        	</t-tr>
					        	<t-tr v-if="item.children.length" v-for="(item1,index) in item.children" :key="index" fontSize="12">
					        		<t-td>{{item1.number}}</t-td>
					        		<t-td :id="item1.initStatus=='合格'? 'font-green':'font-red'" >{{item1.initTestVal}}</t-td>
					        		<t-td :id="item1.retestStatus=='合格'? 'font-green':'font-red'" >{{item1.retestVal}}</t-td>
									<t-td :id="item1.finalStatus=='合格'? 'font-green':'font-red'" >{{item1.finalTestVal}}</t-td>
					        	</t-tr>
								<!-- <t-tr v-if="item.children.length==0" fontSize="12">
									<t-td></t-td>
									<t-td></t-td>
									<t-td></t-td>
									<t-td></t-td>
								</t-tr> -->
					        </t-table>
					    </view>
					</uni-collapse-item-badge>
					<!-- 初测新增测点 -->
					<neil-modal v-show="show=='show'+item.id"   @close="closeModal(item.id)" title="新增测点" @confirm="saveMeasurePoint(item)" @reset="resetPointFunc(item)">
					<!-- <neil-modal v-show="true" @close="closeModal(item.id)" title="新增测点"> -->
					    <view class="input-view" style="height: 10%;">
							<!-- <scroll-view scroll-y="true" class="scroll-Y"> -->
								<view class="input-name">
									<view>检查内容：</view>
									<input type="text" :value="item.measureType" disabled="disabled"/>
								</view>
								<view class="input-name">
									<view>编号类型：</view>
									<input type="text" :value="item.number" disabled="disabled"/>
								</view>
								<view class="input-name">
									<view>合格范围：</view>
									<input type="text" :value="item.range" disabled="disabled"/>
								</view>
								<view class="input-name">
									<view>测点总数：</view>
									<input type="number" v-model="totalNum" placeholder="测点总数(必填)" @confirm="enter()"/>
								</view>
								<view class="input-name">
									<view>不合格数：</view>
									<input type="number"   adjust-position="true" v-model="unqualifiedNum" placeholder="不合格数量(必填)" @input="onChangeHandler($event,item)" />
								</view>
								<view class="input-name" >
									<view>缺项：</view>
									<radio value="true" style="flex: 1;" @click="missItemFunc" :checked="missItem" />
								</view>
								<view class="scroll-style" style="height: 70px;">
									<scroll-view scroll-y="true" class="scroll-Y">
										<view id="unQualifiedPoint" class="input-name" v-for="(item,index) in unQualifiedArr" :key="index">									
											<view class="num">{{item.number}}：</view>
											<input type="number" ref="inputson" confirm-type="go" v-model="item.value" :id="item.isCorrect=='true'?'font-red':'font-green'" placeholder="请输入不合格值" @input="unQualifiedChange($event,item)"  />
										</view>
									</scroll-view>
								</view>
							<!-- </scroll-view> -->
					    </view>
					</neil-modal>
					<!-- 复测新增测点 -->
					<neil-modal v-show="show=='show1'+item.id" ref="reTestModal" @close="closeModal(item.id)" title="修改复测值" @confirm="saveMeasureRetestPoint(item.children)" >
					    <view class="input-view">
					        <view class="input-name">
					            <view>检查内容：</view>
					            <input type="text" :value="item.measureType" disabled="disabled"/>
					        </view>
							<view class="input-name">
							    <view>编号类型：</view>
							    <input type="text" :value="item.number" disabled="disabled" />
							</view>
							<view class="input-name">
							    <view>合格范围：</view>
							    <input type="text" :value="item.range" disabled="disabled" />
							</view> 
							<view class="scroll-style" >
								<scroll-view scroll-y="true" class="scroll-Y" scroll-with-animation="true" show-scrollbar="true">
									<view class="input-name" v-for="(item3,index3) in item.children" :key="index3">
										<view class="num">{{item3.number}}：</view>
										<input  type="number" auto-blur="true" v-model="item3.retestVal" :id="item3.status=='合格'?'font-green':'font-red'" placeholder="测点复测值" @input="unQualifiedChange1($event,item3)"/>
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
	import uniCollapseItemBadge from '@/components/uni-app/uni-collapse-item/uni-collapse-item-badge.vue'
	import neilModal from '@/components/common/neil-modal/neil-modal.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	import uniBadge from '@/components/uni-app/uni-badge/uni-badge.vue';
	export default {
		components: {
			uniCollapse,
			uniCollapseItemBadge,
			neilModal,
			tTable,
			tTh,
			tTr,
			tTd,
			uniBadge,
		},
		data() {
			return {
				statusTitle: '长按类型可进行测点录入',
				show: '',
				isOpened: 'none',
				swipeList: [],
				unQualifiedArr: [],
				initTestArr: [],
				reTestArr: [], 
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
				missItem: false,
				
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
		watch: {
			// unQualifiedArr: function() {
			// 	let arr = [];
			// 	for (let i = 0; i < this.unQualifiedArr.length; i++) {
			// 		arr.push(false)
			// 	}
			// 	this.focusIndex = arr;
			// 	// console.log(this.focusIndex);
			// }
		},
		computed: {
			// focusIndex () {
			// 	let focusIndex = [];
			// 	for (let i = 0; i < this.unQualifiedArr.length; i++) {
			// 		focusIndex.push(false)
			// 	}
			// 	console.log(focusIndex);
			// 	return focusIndex
			// },
			isIntoReTest () {
				if (this.pointStatus == '初测') {
					let missItemNum = 0;
					let flag = Boolean;
					for (let i=0; i<this.pointArr.length; i++) {
						if (this.pointArr[i].missItem == null || this.pointArr[i].missItem == 'false') {
							if (this.pointArr[i].children.length == 0 ) {
								flag = false;
								return flag
							} 
						} else {
							missItemNum++
						}
						if (missItemNum == this.pointArr.length && this.pointArr[i].missItem == 'true') {
							uni.showToast({
								icon: 'none',
								position: 'center',
								title: '全部缺项不需进行复测和终测',
								duration:2000
							});
							console.log(111)
							flag = false
							return flag
						}
					}
					if (flag == true ) {
						uni.showToast({
							icon: 'none',
							position: 'center',
							title: '已完成初测, 可点击右上角按钮进行复测',
							duration:2000
						});
					}
					return flag
					
				} else {
					return false
				}
				
			},
			isIntoFinalTest () {
				if(this.pointStatus == '复测' ) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '已进入复测状态.可点击右上角的按钮进行终测',
						duration:1500,
					})
					return true
				}
			}
		},
		methods: { 
			// nextOne (index,s) {
			// 	const a = this.$refs.inputson[index];
			// 	const b = this.$refs.inputson[index+1];
			// 	// b.addElementListener('click', function() {console.log('click11');})
			// 	// this.$refs.inputson[index+1].click();
			// 	console.log(b);
			// 	b.setAttribute("focus",true)
			// 	// a.removeAttribute("focus")
			// 	console.log(a.focus);
				// console.log(document.getElementById("input1"));
				// document.getElementById("input1").focus()
			// },
			// focus (index) {
				// const num = index - 1;
				// if (num > 0)
				// // 	this.focusIndex[num] = false;
				// const a = this.$refs.inputson;
				// const b = this.$refs.inputson[index+1];
				 // this.$refs.input1.value;
				// c.focus();
				// console.log(b);
				// // console.log(a);				
				// console.log(document.getElementById("input1"))
				// document.getElementById("input1").focus()
				// console.log(that.$refs.input1.value);
				// b.focus()
				// const query = uni.createSelectorQuery().in(this).selectAll('.inputUnqualified')
				// console.log(a);
			// },
			// 复测按钮函数
			tapRetest () {
				const that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'intoReTest',
					measureId:this.cardParam.id,
					projectId: this.projectId,
					pointArr: JSON.stringify(this.pointArr)
				}
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					if(res.data.code1){
						console.log(res.data)
						that.pointStatus = '复测'
						that.statusTitle = '长按类型可进行测点值修改'
						that.isIntoReTest = false
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '已进入复测状态!'
						});
						this.getMeasureType()
					}else{
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '请更换到网络良好的环境删除！'
						});
						this.getMeasureType()
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
				
			},
			
			tapFinalTest () {
				const that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'finalTest',
					measureId:this.cardParam.id,
					projectId: this.projectId,
					pointArr: JSON.stringify(this.pointArr)
				}
				let isLoading = true//是否需要加载动画
				that.pointStatus = '终测'
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					if(res.data.code1){
						console.log(res.data)
						// that.isIntoReTest = false
						that.statusTitle = '终测状态无法录入测点'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '已完成实测实量!'
						});
						this.getMeasureType()	
					}else{
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '请更换到网络良好的环境删除！'
						});
						this.getMeasureType()	
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})		
			},
			openModal(type,item) {
				console.log(item)
				this.measureType = item.measureType
				this.number = item.number
				this.range = item.range
				this.show='show'+type
				this.missItem = false
				console.log(this.pointStatus)
			},
			openModal1(type,item) {						
				let that = this
				console.log(item)
				let reTestModal = this.$refs.reTestModal[0]
				reTestModal.changeIsShow();
				console.log(reTestModal)
				this.measureType = item.measureType
				this.number = item.number
				this.range = item.range
				this.show = 'show1'+type
				uni.getStorage({
					key: 'initTestArr',
					success: function(res) {
						console.log(res)
						that.reTestArr = res.data
					}
				})
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
				const that = this;
				this.unQualifiedArr = []
				let unQualifiedNum = parseInt(e.target.value) 
				for (let j = 0; j < that.pointArr.length; j++) {
					let temp = that.pointArr[j];
					console.log(temp)
					if (item.measureType == temp.measureType) {
						console.log(temp)
						let qualifiedNum = parseInt(temp.qualifiedBadge) ;
						let unqualifiedNumOld = parseInt(temp.unQualifiedBadge) 
						let length = qualifiedNum + unqualifiedNumOld;
						for (var i=length;i<unQualifiedNum+length;i++) {
							that.unQualifiedArr.push({
								measureType: item.measureType,
								number: item.number+(i+1),
								value: '',
								isCorrect: ''
							})
						}
					}
				}
				console.log(that.unQualifiedArr)
				
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
					pointStatus: that.pointStatus,
					projectId: that.projectId,
					measureId:that.cardParam.id
				} 
				console.log(that.pointStatus)
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data);
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
				// console.log(that.pointStatus)
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
						if (this.pointStatus == '初测') {
							this.statusTitle = '长按类型可进行测点录入'
						}
						if (this.pointStatus == '复测') {
							this.statusTitle = '长按类型可进行测点值修改'
						}
						if (this.pointStatus == '终测') {
							this.statusTitle = '终测状态无法录入测点'
						}
						this.getMeasureType()
					}else{
						this.pointStatus = '初测'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为初测状态!'
						});
						this.getMeasureType()
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			saveMeasurePoint(item) {
				console.log(item)
				const unqualifiedNum = parseInt(item.unQualifiedBadge) ;
				const qualifiedNum = parseInt(item.qualifiedBadge);
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
						missItem: this.missItem,
						totalNumOld: unqualifiedNum + qualifiedNum
					} 
					console.log(param)
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
						// console.log(this.pointArr)
						uni.hideLoading()//隐藏加载中转圈圈
						that.isloading = false//取消遮罩层
					}, error => {
						console.log(error);
					})
				}
				this.initTestArr.push({
					measureType: this.measureType,
					pointStatus: this.pointStatus,
					number: this.number,
					unQualifiedInfo:this.unQualifiedArr
				})
				uni.setStorage({
					key:'initTestArr',
					data: this.initTestArr
				})
				this.unQualifiedArr = []
				this.qualifiedNum = ''
				this.unqualifiedNum = ''
				this.totalNum = ''
				console.log(this.initTestArr)
			},
			saveMeasureRetestPoint(item) {
				console.log(item)
				let that = this
				// itemRange.s
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST' 
				}
				let param = {
					flag: 'saveMeasureRetestPoint',
					measureId:this.cardParam.id,
					projectId: this.projectId,
					allQualifiedInfo: JSON.stringify(item),
					measureType: this.measureType,
				} 
				// console.log(param)
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
					uni.$emit('changeStatus',{
						point: 'item'
					})
					this.getMeasureType()
					// console.log(this.pointArr)
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
				if (this.isIntoFinalTest == true) {
					uni.showToast({
						icon: 'none',
						position: 'center',
						title: '已完成复测, 可点击右上角按钮进行终测',
						duration:1500
					});
				}
				// this.isCorrect = 'false'
			},
			//不合格值改变时触发判断不合格值是否在区间内
			unQualifiedChange1(e,item) {
				// if(this.preventTwice){
					console.log(item.status)
					// this.preventTwice = 0
					let Arr = this.range.substr(1, this.range.length-2).split(',')
					if(item.retestVal >= parseInt(Arr[0]) && item.retestVal <= parseInt(Arr[1])){
						item.status = '合格'
						
					}else{
						item.status = '不合格'
					}
					// return
				// }
				// this.preventTwice++
			},
			//不合格值改变时触发判断不合格值是否在区间内
			unQualifiedChange(e,item) {
				let Arr = this.range.substr(1, this.range.length-2).split(',')
				if(item.value <parseInt(Arr[0]) || item.value> parseInt(Arr[1])){
					item.isCorrect = 'true'
				}else{
					item.isCorrect = 'false'
				}
			},
			//重置测点
			resetPointFunc(item) {
				let that = this
				that.show = item.id
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
				            	console.log(res.data)
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
							that.show = 'show' + item.id
				            console.log('用户点击取消');
				        }
				    }
				});
			},
			// resetPointFunc1 (item) {
			// 	console.log(item)
			// 	uni.showToast({
			// 		icon: 'none',
			// 		position: 'bottom',
			// 		title: '复测状态无法重置'
			// 	});
			// },
			// 设置测点类型为缺项
			missItemFunc() {
				const that = this;
				that.missItem = !that.missItem;
				// console.log(that.missItem);
			},
		},

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
		height: 100%;
	}
	
	.scroll-style {
		margin-top: 10px;
		height: 100px;
	}
	
	.num {
		text-align: right;
	}
	
	#font-green {
		color: green;
	}
	
	#font-red {
		color: red;
	}
	
	
	uni-modal{
			z-index: 19999 !important;
		}
</style>
