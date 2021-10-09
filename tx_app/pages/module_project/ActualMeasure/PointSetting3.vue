<template>
	<view >
		<view  style="background-color: #f7f7f7;">
			<span style="margin: 10rpx; margin-top: 5rpx; margin-left: 35rpx; float: left;">已布点:</span>
			<uni-badge :text="putPointNum" type="primary" size="small" style=" float: left; margin-right: 20rpx; margin-top: 5rpx;"></uni-badge>
			<span style="margin: 10rpx; margin-top: 5rpx; float: left;">未布点:</span>
			<uni-badge :text="notPointNum" type="error" size="small" style=" float: left; margin-right: 20rpx; margin-top: 5rpx;"></uni-badge>
			<uni-icons v-show="pointStatus=='初测'" class="icon-style" type="undo" size="20" style="position: absolute;right: 35rpx;" @click="resetPoint" z-index="2"/>
			<uni-icons v-show="pointStatus=='复测'" class="icon-style" type="compose" size="20" style="position: absolute;right: 35rpx;" @click="modifyReTestValue"/>
		</view>
		<view class="container">
			<view @touchmove="touchmove" >
			<!-- <view @click="touchmove" class='box1'> -->
				<img ref="pic" :src="picSrc" hidden="hidden" id="pic" />				
				<canvas type="webgl" canvas-id="myCanvas"  disable-scroll="true" id="myCanvas" ref="myCanvas" class="my-canvas" :style="{width:canvasWidth +'px',height:canvasHeight +'px',margin:'0 0 0 50% ',left:'-170px'}"
				 width="100px" height="100px" ></canvas>
				 <canvas type="webgl" canvas-id="myCanvas"  disable-scroll="true" id="myCanvas" ref="myCanvas" class="my-canvas" :style="{width:canvasWidth +'px',height:canvasHeight +'px',margin:'0 0 0 50% ',left:'-170px'}"
				  ></canvas>
				 <view ref="smalldiv" :style="{position:'absolute',width:smallWidth+'px',height:smallHeight+'px',backgroundColor:'rgba(63,10,217,0.5)',border:'1px solid gray',top:smallTop+'px',left:smallLeft+'px', display: smallDivShow}"></view>
			</view>
			<view class="port" id="dian">
				<span id="port" class="bar" style="position: absolute;z-index: -1;">0</span>
				<block v-for="item in pointList">
					<span :style="{marginTop:item.top +'px',marginLeft:item.left +'px',display:item.display }" :class="[item.title=='x'? 'bar' : 'Bar']" class="commonbar"  :ref="item.id"  @touchstart="setPoint(item,$event)">{{item.value}}</span>
				</block>
			</view> 
			<view class=""> 
				<img ref="pic_biger" :src="picSrc" width="1602.5px" height="1132.5" hidden="hidden" />
				<!-- <canvas id="my_Canvas" style="margin-top: 340px;"></canvas> -->
				<canvas @touchstart="touchstart" :style="{width:canvasWidth1 +'px',height:canvasHeight1 +'px',margin:'300px 0 0 50% ',left:'-170px', border: '1px solid #d3d3d3' }"
				 canvas-id="my_Canvas" id="my_Canvas"></canvas>
				 <!-- <canvas @click.middle="touchstart" :style="{width:canvasWidth1 +'px',height:canvasHeight1 +'px',margin:'300px 0 0 50% ',left:'-170px'}"
				  canvas-id="my_Canvas" id="my_Canvas"></canvas> -->
			</view>
			<!-- <view ref="smalldiv" :style="{position:'absolute',width:smallWidth+'px',height:smallHeight+'px',backgroundColor:'rgba(63,10,217,0.5)',border:'1px solid gray',top:smallTop+'px',left:smallLeft+'px', display: smallDivShow}"></view> -->
			<view class="uni-box" v-show="buttonShow">
				<button class="point" @click="savePointFunc">保存</button>
				<button class="point" @click="openPopover">选择测点类型</button>
			</view>  
			<!-- <transition-group name="animation"> -->
			<view v-if="animationShow" :key="1">
				<view class="uni-popover-arrow uni-bottom" style="left:164.171875px"></view>
				<view id="middlePopover" class="uni-popover uni-active" style="display: block; top: 198.5px; left: 96px;">
					<view class="uni-popover-arrow uni-bottom" style="left:164.171875px"></view>
					<view class="uni-scroll-wrapper" data-scroll="10">
						<view class="uni-scroll" style="transform: translate3d(0px, 0px, 0px) translateZ(0px);">
							<ul class="uni-table-view">
								<li v-for="(item,index) in pointArr" :key="index" class="uni-table-view-cell" @click="selectType(item)" v-show="item.missItem != true && item.notPutNum != 0"><p>{{item.measureType}}</p></li>
							</ul> 
						</view>  
					<view class="uni-scrollbar mui-scrollbar-vertical"><view class="uni-scrollbar-indicator" style="transition-duration: 0ms; display: none; height: 8px; transform: translate3d(0px, -8px, 0px) translateZ(0px);"></view></view></view>
				</view>
			</view>
			<!-- </transition-group> -->
		</view>
		<!-- 撤销布点窗口 -->
		<view class="">
			<neil-modal  v-show="isAble" ref="reTestModal" @close="closeModal()" title="撤销测点" @confirm="clearPoint(pointDataPart.sonNumber,pointDataPart.j,pointDataPart.measurePointType)" isWidth="true" >
			    <scroll-view scroll-y="true" scroll-with-animation="true" show-scrollbar="true">
					<view class="input-view">
						<view class="input-name">
							<view style="width: 33%;">测点类型：</view>
							<input type="text" :value="pointDataPart.measurePointType" disabled="disabled" style="width: 100%; font-size: 25rpx;" placeholder="无布点"/>
							<!-- <input type="text" :value="pointTypeArr[0]" disabled="disabled"/> -->
							<!-- <view class="uni-list-cell-db">
								<picker @change="changePointType" :value="index" :range="pointData">
									<view class="uni-input">{{pointData[index].measurePointType}}</view>
								</picker>
							</view> -->
						</view>
						<view class="input-name">
							<view>编号类型：</view>
							<input id="inputType" type="text" :value="pointDataPart.number" disabled="disabled" style="padding-left: 10rpx;"/>
						</view>
						<view class="">
							<uni-collapse :accordion="true">
								<view class="typePoint" >
									<uni-collapse-item :title="titlePointType" size="small" type="success"  ref="uniCollapse"  >
										<view class="uni-list">
											<radio-group @change="radioChange">
												<label class="uni-list-cell uni-list-cell-rd" v-for="(item, index) in pointData" :key="index">
													<view>
														<radio :value="item.number" :checked="index === currentRadio" />
													</view>
													
													<view style="padding-left: 30upx;">{{item.measurePointType}}</view>
												</label>
											</radio-group>
										</view>
									</uni-collapse-item>
								</view>
							</uni-collapse >
						</view>
						<view class="scroll-style" v-show="checkboxShow">
							<scroll-view scroll-y="true" class="scroll-Y" scroll-with-animation="true" show-scrollbar="true">
								<view class="checkbox-name" >
									<view>
										<checkbox-group @change="checkboxChange">
											<label class="uni-list-cell1 uni-list-cell-pd" v-for="(item,index) in pointDataPart.sonNumber" :key="index" >
												<view>
													<checkbox :value="item.num" :checked="item.checked" />
												</view>
												<view class="num">{{item.num}}</view>
											</label>
										</checkbox-group>
									</view>
								</view>
							</scroll-view>
						</view>
						
					</view>
				</scroll-view>
			</neil-modal>
		</view>
		<!-- 修改复测值窗口 -->
		<neil-modal v-show="isAble1" ref="reTestModal0" @close="closeModal()" title="修改复测值" @confirm="saveMeasureRetestPoint(pointTypeArr.children)" isWidth="true">
		    <view class="input-view">
		        <scroll-view scroll-y="true" class="scroll-Y" scroll-with-animation="true" show-scrollbar="true">
					<view class="input-name1">
						<view style="width: 160rpx;">检查内容：</view>
						<!-- <input type="text" :value="pointTypeArr.measureType" disabled="disabled"/> -->
						<view class="">
							<uni-collapse :accordion="true">
								<view class="typePoint" >
									<uni-collapse-item :title="titlePointType" size="small" type="success"  ref="uniCollapse1"  >
										<view class="uni-list">
											<radio-group v-if="item.missItem == null" @change="radioChange" v-for="(item, index) in pointArr" :key="index">
												<label class="uni-list-cell uni-list-cell-rd" >
													<view>
														<radio :value="item.measureType" :checked="index === currentRadio" />
													</view>
													<view style="padding-left: 30upx;">{{item.measureType}}</view>
												</label>
											</radio-group>
										</view>
									</uni-collapse-item>
								</view>
							</uni-collapse >
						</view>
					</view>
					<view class="input-name">
						<view>编号类型：</view>
						<input type="text" :value="pointTypeArr.number" disabled="disabled"/>
					</view>
					<view class="input-name">
						<view>合格范围：</view>
						<input type="text" :value="pointTypeArr.range" disabled="disabled"/>
					</view> 
					<view class="scroll-style" >
							<view class="input-name" v-for="(item,index) in pointTypeArr.children" :key="index">
								<view class="num">{{item.number}}：</view>
								<input type="number" auto-blur="true" v-model="item.retestVal" :id="item.status=='合格'?'font-green':'font-red'" placeholder="测点复测值" @input="unQualifiedChange1($event,item)"/>
							</view>
					</view>
				</scroll-view>
		    </view>
		</neil-modal>
	</view>
</template>

<script>
	let that = null
	import uniDrawer from "@/components/uni-app/uni-drawer/uni-drawer.vue"
	import neilModal from '@/components/common/neil-modal/neil-modal.vue'
	import uniCollapse from '@/components/uni-app/uni-collapse/uni-collapse.vue'
	import uniCollapseItem from '@/components/uni-app/uni-collapse-item/uni-collapse-item.vue'
	import uniBadge from '@/components/uni-app/uni-badge/uni-badge.vue'
	import { pathToBase64, base64ToPath } from 'image-tools'
	// let myCanvas = uni.createCanvasContext('myCanvas')
	// let my_Canvas = uni.createCanvasContext('my_Canvas')
	export default {
		components: {
			uniDrawer,
			neilModal,
			uniCollapse,
			uniCollapseItem,
			uniBadge
		},  
		data() { 
			return { 
				titlePointType: '请选择测点类型',
				picSrc: '../../../static/images/projectPic.jpg',
				picSrcInit: '../../../static/images/projectPic.jpg',
				ImageFile: '',  
				phone_width: '342',
				set_height: '242',
				img_w: '1282',
				img_h: '906',
				imgCanvas_height: '',
				imgCanvas_width:'',
				canvasWidth: '342',
				canvasHeight: '242',
				canvasWidth1: '342',
				canvasHeight1: '242',
				biao: 'move',
				small_div: {},
				smalldiv: {},
				small_width: '',
				small_height: '',
				divX: '',
				divY: '',
				smallWidth: '30',
				smallHeight: '30',
				smallTop: '', // 小紫框的上边距
				smallLeft: '',
				mar_top: '',
				mar_left: '',
				pointList: [{
					name: 'save',
					value: '',
					id: ''
				}],
				Name: [],
				j: '',
				flage: '',
				box_X: '',
				box_Y: '',
				port_obj:{},//点
				tomyCanvas:{},//窗口坐标转换为canvas坐标
				tomyCanvas1:{},//放大窗口坐标转换为canvas坐标
				select_point:{},//选中的布点
				num: '',
				poiotType: '',
				animationShow: false,
				currentData: {},
				cardParam: {},
				pointStatus: '',
				pointTypeArr: [],
				pointArr: [],
				buttonShow: false,
				smallDivShow: 'block',
				por_obj: {},
				pointData:[],
				pointDataPart: {},
				myCanvas:'',
				my_Canvas:'',
				plus:'',//获取手机像素比
				tempE:'',
				savePointX: '',
				savePointY:'',
				X: '', //放大图的坐标
				Y: '',
				M: '', //放大图的大小
				N: '', //放大图的大小
				savePicFlag: false, // 保存点时的存图标志
				putPointNum: 0, // 已布点
				notPointNum: 0, // 未布点,
				isAble: false, //控制窗口是否弹出
				isAble1: false, //控制窗口是否弹出
				currentRadio: 0 , // 单选框
				checkboxShow: true,
				index: '',
				
			
			};
		},
		onReady() {
			const that = this;
			this.myCanvas = uni.createCanvasContext('myCanvas',this) // 创建一个myCanvas ,(id,组件实例this), 用来操作相应Id的canvas组件
			this.my_Canvas = uni.createCanvasContext('my_Canvas',this)
			console.log(this.myCanvas)
			// console.log(this.my_Canvas)
			this.getFirst() 
			// var url_load = "../../../static/images/projectPic.jpg";
			// myCanvas.drawImage(url_load, 0, 0, 1282, 906, 0, 0, 342, 243);
		},
		onLoad(option) {}, 
		mounted() {
			let that = this
			that.getStorageInfo(); // 获取缓存
			uni.getSystemInfo({ // 获取app系统信息
			    success: (res) => {
					// console.log(res);
			            that.plus = res.pixelRatio // 获取像素比
			    }	
		   })
		   // console.log(that.plus);  
		   const plus = that.plus
			// var img = this.$refs["pic"];
			
			this.set_height = this.phone_width * (this.img_h / this.img_w)
			this.small_width = this.phone_width * 0.2 //smalldiv的宽度
			this.small_height = this.set_height * 0.2 //samlldiv的高度
			// console.log(this.small_width + ',' + this.small_height)
			this.smallWidth = this.small_width 
            this.smallHeight = this.small_height   
			
			this.canvasWidth = this.phone_width ;
			this.canvasHeight = this.set_height ; 
			console.log(this.canvasHeight+ ',' + this.canvasWidth);
		},
		watch: {},
		methods: {
			getStorageInfo() {
				const that = this;
				uni.getStorage({
					key:'cardParam',
					success:(res) =>{
						// console.log(res)
						that.cardParam = res.data
						uni.getStorage({
							key: 'userInfo',
							success:(res) =>{
								// console.log(res)
								that.currentData = res.data
								that.projectId = that.currentData.projectId
								that.getPointStatus();
							}
						})
					}
				})
				uni.getStorage({
					key: 'PicSrc',
					success: (res) => {
						// console.log(res)
						that.picSrcInit = res.data
						that.picSrc = res.data
						// 获取图片信息
						uni.getImageInfo({
							src: that.picSrc,
							success: function (image) {
								that.img_w = image.width;
								that.img_h = image.height;
								this.imgCanvas_height = image.height;
								this.imgCanvas_width = image.width;
								console.log(that.img_h+','+that.img_w);
								if(image.height > 242) {
									 that.imgCanvas_height = 242;
									 that.imgCanvas_width = image.width * 242 / image.height;
									 that.phone_width = that.imgCanvas_width;
									console.log(that.img_w+ ',' + that.img_h);
								}
								that.set_height = that.phone_width * (that.img_h / that.img_w)
								that.canvasWidth = that.phone_width * that.plus;
								that.canvasHeight = that.set_height * that.plus; 
								that.small_width = that.phone_width * 0.2 //smalldiv的宽度
								that.small_height = that.set_height * 0.2 //samlldiv的高度
								that.smallWidth = that.small_width
								that.smallHeight = that.small_height
								
								
							}
						});
					}
				})
			},
			getFirst() { 
				// this.creat(1, 1, 2, 3, 4, 5, 6, 7); //将点布置到相应位置
				this.pointList = []
				// console.log(this.$refs.reTestModal.isShow)
				this.$refs.reTestModal.isShow = false // 隐藏弹框的图标
				this.$refs.reTestModal0.isShow = false // 隐藏弹框的图标
				// this.budian(1, 2, 3, 4, 5, 6, 7); //生成点
				// this.budian(11, 22, 33, 44, 55, 66, 77); //生成点
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
					// console.log(res.data)
					if(res.data.code){
						that.pointStatus = res.data.data[0].status
						// console.log(res.data.data[0].status)
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为'+res.data.data[0].status+'状态！'
						});
						that.getSavedPoint()
						that.getpointNum ();
						that.getMeasureType()
						// that.getPicSrc();
					}else{
						that.pointStatus = '初测'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为初测状态！'
						});
						// that.getPointType()
						that.getSavedPoint()
						that.getpointNum ();
						this.getMeasureType()
						// that.getPicSrcs();
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			// 获取已布点数和未布点数
			getpointNum () {
				let that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/PointSetting.php',
					method: 'POST'
				}
				let param = {
					flag: 'getpointNum',
					measureId:this.cardParam.id,
					pointStatus: this.pointStatus
				} 
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						that.notPointNum = res.data.notPointNum
						that.putPointNum = res.data.putPointNum
					}else{
						
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			// 获取图片路径
			getPicSrc() {
				let that = this
				that.getpointNum ();
				// that.getSavedPoint();
				let opts = {
					url: this.api+'/module_project/ActualMeasure/PointSetting.php',
					method: 'POST'
				}
				let param = {
					flag: 'getPicSrc',
					measureId:this.cardParam.id,
					pointStatus: this.pointStatus
				} 
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					that.getSavedPoint()
					if(res.data.code){
						// console.log(res.data.img)
						if (res.data.img != '') {
							that.picSrc =  that.imageUrl + '/inspectPic/upload/' + res.data.img
						}
						console.log(that.picSrc);
						// 获取图片信息
						uni.getImageInfo({
							src: that.picSrc,
							success: function (image) {
								that.img_w = image.width;
								that.img_h = image.height;
								let height = image.height;
								let width = image.width;
								console.log(that.img_h+','+that.img_w);
								if(image.height > 242) {
									 height = 242;
									 width = image.width * 242 / image.height;
									 that.phone_width = width;
									console.log(that.img_w+ ',' + that.img_h);
								}
								that.set_height = that.phone_width * (that.img_h / that.img_w)
								that.canvasWidth = that.phone_width * that.plus;
								that.canvasHeight = that.set_height * that.plus; 
								that.small_width = that.phone_width * 0.2 //smalldiv的宽度
								that.small_height = that.set_height * 0.2 //samlldiv的高度
								that.smallWidth = that.small_width
								that.smallHeight = that.small_height
								
								that.myCanvas.drawImage(that.picSrc,0, 0, width, height); // 绘图
								that.myCanvas.draw(); //绘制图片
							}
						});
						
						// uni.getImageInfo({
						// 	src: that.picSrcInit,
						// 	success: function (image) {
						// 		// that.img_w = image.width;
						// 		// that.img_h = image.height;
						// 		// console.log(image.height)
						// 		// console.log(image.width)
						// 	}
						// });
					}else{
						
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			//窗口坐标转换为canvas坐标
			windowTocanvas(flag, x, y) {
				const that = this
				let view = uni.createSelectorQuery().in(this).select("#myCanvas");
				// console.log(view);
				view.boundingClientRect(data => {
					// console.log(data);
					// console.log(x,y);
					that.tomyCanvas = {
						x: x - data.left,//当前小紫色方框与canvas左内边距
						y: y - data.top,
						mar_t: data.top,
						mar_l: data.left,//canvas左边距
					};
				// console.log(this.tomyCanvas);
				}).exec();
			},
			touchmove(e){
				let that = this;
				// that.tempE = e;
				
				//pageX 当前点击点X左边
				if (that.smallDivShow == 'none') return ; // 处于布点状态无法移动小紫框放大
				that.windowTocanvas(that.myCanvas, e.touches[0].pageX, e.touches[0].pageY); // 计算当前小方框相对于canvas的XY左边
				// console.log(that.windowTocanvas)
				that.buttonShow = true //显示下方按钮
				let loc = that.tomyCanvas  // 小紫框到canvas的边距
				// console.log(loc);
				// var x = parseInt(loc.x);
				// var y = parseInt(loc.y);
				var x = loc.x
				var y = loc.y;
				// console.log(loc.x + ',' +loc.y); 
				var LimitHeight = that.set_height
				if (y < LimitHeight) { 
					// that.mar_top = loc.mar_t;  // canvas的上边距
					// that.mar_left = loc.mar_l;  // mar_left = divX
					if (x < 0 && y < that.set_height) { //当鼠标的X坐标小于图片与div遮罩层的x坐标和是ｄｉｖｘ＝０；
						that.divX = loc.mar_l; 
					} else if (x >= 0 && x < that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标在图片内部并且小于图片最右边的X坐标
						that.divX = x + loc.mar_l;
					} else if (x >= that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标大于图片的最右边的X坐标 （Y轴同理）
						// that.divX = that.divX
					}
				
					if (y < 0) {
						that.divY = loc.mar_t;
					} else if (y >= 0 && y < that.set_height - that.smallHeight) {
						that.divY = loc.mar_t + y;
					} else if (y > that.set_height - that.smallHeight && y <= that.set_height) {
						that.divY = loc.mar_t + that.set_height - that.smallHeight;
					} else if (y > that.set_height) {}
					// console.log(that.divX + ',' +that.divY);
					that.smallTop = that.divY
					that.smallLeft = that.divX
					// console.log(that.img_w+ "," + that.img_h)
					// console.log(that.phone_width+ "," + that.set_height)
					that.box_X = that.divX - loc.mar_l; // 小紫框相对于canvas的X坐标, 小紫框的坐标 - canvas的坐标
					that.box_Y = that.divY - loc.mar_t;
					
					that.X = that.box_X * (that.img_w / that.phone_width); // 放大图的坐标
					that.Y = that.box_Y * (that.img_h  / that.set_height);
					that.M = that.small_width * (that.img_w/ that.phone_width);  // 放大图的截取大小
					that.N = that.small_height* (that.img_h /that.set_height);
					// console.log(that.X+"@"+that.Y+"@"+that.M+"@"+that.N)
					// that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
					// console.log(that.picSrc); 
					that.my_Canvas.drawImage(that.picSrc, that.X, that.Y ,that.M, that.N , 0, 0, 342, 242); //绘制图片
					that.my_Canvas.draw(); //绘制图片 
					
					// console.log(that.box_X + ',' +that.box_Y);
				}
			},
			// 选择测点类型
			selectType(item) {
				let that = this
				that.smallDivShow = 'block'
				console.log(item.children);
				that.por_obj = {};
				that.pointList.forEach(item => {
						item.display = 'none',
						item.title = 'x'
				})
				that.poiotType = item.measureType
				that.animationShow = false
				let opts = {
					url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'getPoint',
					measureId: that.cardParam.id,
					projectId: that.projectId,
					poiotType: item.measureType,
					pointStatus: that.pointStatus
				} 
				let isLoading = false//是否需要加载动画
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					that.pointList = []
					if(res.data.code){
						for(var i=0;i<res.data.data.length;i++){
							that.budian(0,0,0,0,res.data.data[i].measurePointNumber,res.data.data[i].measurePointName,res.data.data[i].id)
						}
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			//待布点显示在box中
			budian(X_zb, Y_zb, X_father, Y_father, num, Name, point_id) {
				this.pointList.push({
					value: num,
					id: Name + "|" + point_id + "|" + num, //编号+实测卡片id
					title: 'x',
					top:'0',
					left:'5',
					display:'block'
				})
				// console.log(this.pointList);
			},
			//点击布点
			setPoint(val, event) {
				// console.log(val);
				const that = this;
				// console.log(that.smallDivShow)
				that.smallDivShow = 'none';
				//布点点击
				let num = val.value //编号
				let point_name = that.poiotType; 
				// console.log(point_name)
				that.num = num 				 
				let port_obj = uni.createSelectorQuery().in(this).select("#port");//用于获取点相对于视窗的位置集合
				let my_Canvas = uni.createSelectorQuery().in(this).select("#my_Canvas");//用于获取点相对于视窗的位置集合
				// console.log(port_obj);
				// console.log(my_Canvas);
				port_obj.boundingClientRect(data => {
					that.port_obj = data
					// console.log(that.por_obj);
				}).exec();
                if (val.title == "x") { //判断是否为待点区的点
                	that.Name[that.j] = point_name + "|" + num;
					// console.log(that.Name)
                	that.flage = 0;
                	if (that.flage == 0) {
                		val.title = "y";
                	}
                }
				that.select_point = val ;
                that.pointList.forEach(item => { 
                	if (item.title == 'y' && item.id==that.select_point.id) {
						// console.log(11)
                		item.display = 'none';
                	}
                });
			},
			touchstart(e) { //布点
				const that = this 
				that.flage = 1;
				let por = new Array(); //存点坐标
				let point_name = that.poiotType; 
				console.log(e.touches[0].x + ',' + e.touches[0].y) ;
				that.pointList.forEach(item => {				
					if (item.title == 'y' && item.id==that.select_point.id) {
						item.display = 'block'
						item.left = (e.touches[0].x - that.port_obj.left ) + 6.5
						// item.left = (e.touches[0].x - that.port_obj.left - 10.00000000000000)
						item.top = (e.touches[0].y + that.port_obj.height ) + 10.5
						console.log(item.top + "子" + item.left)						
						por = [(item.left+18) , (item.top-37.5) , that.box_X, that.box_Y, that.num]; // 点相对于canvas的坐标
						that.por_obj[point_name + "|" + that.num] = por; // 将点及对应的坐标存入对象	
						console.log(that.por_obj);
					}  					 
				}); 
			}, 
			
			//展示测点类型函数
			openPopover() {
				// let isShow = false;
				// for (let i = 0; i < this.pointArr.length; i++) {
				// 	const children = this.pointArr[i].children;
				// 	if (children.length != 0) {
				// 		isShow = true;
				// 		break;
				// 	}
				// 	if(this.pointArr[i].notPutNum == 0) {
				// 		sum++;
				// 	}
				// }
				let sum = 0;
				for (let i = 0; i < this.pointArr.length; i++) {
					if(this.pointArr[i].notPutNum == 0) {
						sum++;
					}
				}
				// console.log(sum );
				// console.log( this.pointArr.length);
				if (sum != this.pointArr.length) {
					this.animationShow = !this.animationShow
				} else {
					this.animationShow  = false;
					uni.showToast({
						icon: 'none',
						title: '暂无测点类型'
					})
				}
				// console.log(this.pointArr)
				// console.log(this.pointData)
			},
			
			getMeasureType() {
				const that = this
				const opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				const param = {
					flag: 'getMeasureType',
					inspectItem: JSON.stringify(this.cardParam.inspectItem),
					pointStatus: that.pointStatus,
					projectId: that.projectId,
					measureId:that.cardParam.id
				} 
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					if(res.data.code){
						that.pointArr = res.data.data				
						// console.log(that.pointArr)
						let i = that.currentRadio;
						that.pointTypeArr = that.pointArr[i];
						// console.log(that.pointArr)
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
			savePointFunc() {
				// console.log(JSON.stringify(this.por_obj))
				let that = this
				that.smallDivShow = 'block'
				that.animationShow = false
				let opts = {
					url: that.api+'/module_project/ActualMeasure/pointSetting.php',
					method: 'POST'
				}
				console.log(that.por_obj)
				let param = {
					flag: 'savePoint', 
					measureId: that.cardParam.id,
					por_obj: JSON.stringify(that.por_obj),
					picSrcInit: that.picSrcInit,
					pointStatus: that.pointStatus,
					small_width: that.small_width,
					small_height: that.small_height,
					phone_width: that.phone_width,
					set_height: that.set_height
				} 
				let isLoading = false//是否需要加载动画
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
						// that.getSavedPoint()
						// that.getPicSrc();
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '测点布置成功！'
						});
						// that.por_obj = {}
						uni.redirectTo({
							url: 'PointSetting3'
						})
						that.pointList.forEach(item => {
							// if (item.title == 'y'&&item.id==that.select_point.id) {
								item.display = 'none',
								item.title = 'x'
							// }
						})
						that.savePicFlag = true;
						// selectType(that.poiotType)
					}else{
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '测点布置失败！'
						});
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			
			
			// 获取已保存的点
			getSavedPoint() {
				const that = this
				// that.myCanvas.drawImage(that.picSrc,0, 0, 342, 243);
				let opts = {
					url: this.api+'/module_project/ActualMeasure/PointSetting.php',
					method: 'POST'
				}
				// console.log(that.pointStatus)
				let param = {
					flag: 'getSavedPoint',
					measureId: this.cardParam.id,
					pointStatus: that.pointStatus,
					picSrcInit: that.picSrcInit,
					small_width: that.small_width,
					small_height: that.small_height,
					phone_width: that.phone_width,
					set_height: that.set_height
				} 
				let isLoading = false//是否需要加载动画
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// that.getPicSrc()
					console.log(res.data)
					if(res.data.code){
						that.pointData = res.data.data
						// console.log(res.data.notPointNum)
						const i = that.currentRadio;
						that.pointDataPart = that.pointData[i];
						if (res.data.$$imgTimeStamp != '') {
							that.picSrc =  that.imageUrl + '/inspectPic/upload/' + res.data.$imgTimeStamp
							console.log(that.picSrc);
						}
						that.myCanvas.drawImage(that.picSrc,0, 0, that.imgCanvas_width, that.imgCanvas_height); // 绘图
						that.myCanvas.draw(); //绘制图片
					}else{ 
						that.pointData = [];
						that.pointDataPart  = [];
						that.myCanvas.drawImage(that.picSrc,0, 0, that.imgCanvas_width, that.imgCanvas_height); // 绘图
						that.myCanvas.draw(); //绘制图片
					} 
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
				})
			}, 
			
			savePicSrc () {
				const that = this;
				// console.log(base64)
				const opts = { 
						url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
						method: 'POST' ,
					}
				const param = {
					flag: 'savePicSrc',
					measureId:that.cardParam.id,
					status: that.pointStatus,
					base64Pic: that.picSrc,
				}
				// console.log(param)
				let isLoading = false;
				that.myRequest.httpRequest(opts, param, isLoading).then(res => {
					if (res.data.code) {
						// console.log(that.cardParam.id)
						// console.log(res.data.img)
						that.picSrc = that.imageUrl + '/inspectPic/upload/' + res.data.img
						// console.log(that.picSrc);
						that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
						that.my_Canvas.drawImage(that.picSrc, that.X*2, that.Y*2 , that.M *2, that.N*2, 0, 0, 342, 242); //绘制图片
						that.my_Canvas.draw();
					}
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
					error => {
						console.log(error);
					}
				})
				
			},
			resetPoint () {
				const that = this;
				that.isAble = true;
			},
			modifyReTestValue () {
				this.isAble1 = true;
				this.$refs.uniCollapse1.onClick();
			},
			closeModal () { 
				const that = this
				// console.log('close')
				that.isAble = false
				that.isAble1 = false
				that.$refs.uniCollapse1.onClick();
			},
			// 保存复测点的值
			saveMeasureRetestPoint(item) {
				// console.log(item)
				let that = this
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST' 
				}
				let param = {
					flag: 'saveMeasureRetestPoint',
					measureId:this.cardParam.id,
					projectId: this.projectId,
					allQualifiedInfo: JSON.stringify(item),
					measureType: this.pointTypeArr.measureType,
				} 
				// console.log(param)
				let isLoading = true//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						// that.getSavedPoint()
						uni.redirectTo({
							url: 'PointSetting3'
						})
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
					// that.getMeasureType()
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
			},
			// 撤销布点
			clearPoint (items,length,measureType) {
				const that = this
				// console.log(items)
				// console.log(length)
				const opts = {
					url: this.api+'/module_project/ActualMeasure/PointSetting.php',
					method: 'POST'
				}
				let isLoading = false//是否需要加载动画
				const param = {
					flag: 'clearPoint',
					measureId:that.cardParam.id,
					measureType: measureType,
					picSrcInit: that.picSrcInit,
					items: JSON.stringify(items),
					pointStatus: that.pointStatus,
					small_width: that.small_width,
					small_height: that.small_height,
					phone_width: that.phone_width,
					set_height: that.set_height
				} 
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res.data)
					if(res.data.code){
						// console.log(res.data.data[0].status)
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '撤回成功'
						});
						// that.getSavedPoint();
						uni.redirectTo({
							url: 'PointSetting3'
						})
						that.por_obj = {}
						that.pointList.forEach(item => {
							item.display = 'none'
						})
					}else{
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '撤回失败！'
						});
					}		
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			radioChange: function (evt) {
				// console.log(this.pointStatus)
				if (this.pointStatus == '初测') {
					// console.log(this.pointData)
					for (let i = 0; i < this.pointData.length; i++) {
						if (this.pointData[i].number === evt.detail.value) {
							this.currentRadio = i;
							break;
						}						
					}
					let i = this.currentRadio;
					// console.log(i)
					this.pointDataPart = this.pointData[i];
					this.$refs.uniCollapse.onClick()
				}
				if (this.pointStatus == '复测') {
					// console.log(this.pointArr)
					for (let i = 0; i < this.pointArr.length; i++) {
						// console.log(this.pointArr)
						if (this.pointArr[i].measureType === evt.detail.value) {
							this.currentRadio = i;
							break;
						}
						
					}
					let i = this.currentRadio;
					// console.log(i)
					this.pointTypeArr = this.pointArr[i];
				}
			},
			checkboxChange: function (e) {
				const items = this.pointDataPart.sonNumber,
					  values = e.detail.value;
				for (let i = 0, lenI = items.length; i < lenI; ++i) {
					const item = items[i]
					if(values.includes(item.num)){
						this.$set(item,'checked',true)
					}else{
						this.$set(item,'checked',false)
					}
				}
			},
			//不合格值改变时触发判断不合格值是否在区间内
			unQualifiedChange1(e,item) {
			// if(this.preventTwice){
				// console.log(item.status)
				// this.preventTwice = 0
				let Arr = this.pointTypeArr.range.substr(1, this.pointTypeArr.range.length-2).split(',')
				if(item.retestVal >= parseInt(Arr[0]) && item.retestVal <= parseInt(Arr[1])){
					item.status = '合格'
					
				}else{
					item.status = '不合格'
				}
			},
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

	html,
	body {
		position: relative;
		height: 100%;
		width: 100%;
		overflow: hidden;
		margin: 0;
		padding: 0;
	}
	.box {
		width: 340rpx;
		height: 240rpx;
	}
	.container {
		width: 100%;
		height: 100%;
		overflow: hidden;
		background-color: #f7f7f7;
	}
	.headerTitle {
		width: 100%;
		height: 80upx;
		background-color: #007BFF;
		color: #f7f7f7;
		font-size: 120%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.typePoint {
		width: 100%;
		height: 100%;
		// border-bottom: solid #888888 1rpx;
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		position: relative;
		padding-left: 30upx;
	}
	.uni-list-cell-rd {
		display: flex;
		flex-direction: row;
		justify-content: flex-start;
		align-items: center;
		position: relative;
	}
	.input-name{
	    height: 80upx;
	    width: 100%;
		border-bottom: solid #888888 2rpx;
	    display: flex;
	    flex-direction: row;
	    // justify-content: center;
	    align-items: center;
	    position: relative;
	    padding-left: 30upx;
	    box-sizing: border-box;
	}
	.input-name1 {
	    height: 100%;
	    width: 100%;
		border-bottom: solid #888888 2rpx;
	    display: flex;
	    flex-direction: row;
	    // justify-content: center;
	    align-items: center;
	    position: relative;
	    padding-left: 30upx;
	    box-sizing: border-box;
	}
	.checkbox-name {
		height: 300upx;
		width: 50%;
		padding-left: 105upx;
		display: flex;
		flex-direction: column;
	}
	.uni-list-cell1 {
		display: flex;
		flex-direction: row;
		padding-left:90rpx;
	}
	.num{
		padding-left: 50rpx;
	}
	checkbox {
		margin: 5rpx;
	}
	radio {
		margin: 10rpx;
	}
	.botton-box {
		width: 50%;
		height: 80upx;
		margin: 30rpx;
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		position: relative;
		padding-left: 30upx;
	}
	.botton-box > input {
		margin: 20rpx;
		background-color: #007BFF;
		text-align: center;
		border-radius: 10rpx;
	}
	.collapse-content{
		background-color: #FFFFFF;
		font-size: 10px;
	}
	#font-green {
		color: green;
	}
	
	#font-red {
		color: red;
	}
	#myCanvas,
	#my_Canvas {		
		overflow: hidden;
		margin-top: 50px;
		margin-right: 20px;
		// border: 1px solid #d3d3d3;
		position: absolute;
		background-color: #f7f7f7;
	}

	.port {
		position: absolute;
		margin-top: 246px;
		background-color: #FFFFFF;
		padding: 5px;
		width: 90%;
		height: 40px;
		margin-left: 50%;
		left: -45%;
		border: 1px solid #000000;
		word-break: break-all;
	}

	.point {
		width: 100%;
		height: 40px;
	}
    .commonbar{
		width: 50rpx;
		height: 20px;
		border-radius: 50%;
		font-size: 10px;
		color: #000;
		line-height: 20px;
		text-align: center;
		background: yellow;
	}
	.bar {
		float: left;
		display: block;
		width: 50rpx;
	}

	.Bar {
		position: absolute;
		z-index: 99;
		// display: block;
	}

	.box {
		width: 80%;
		display: flex;
		flex-direction: row;
		margin-top: 590px;
		margin-left: 10%;
	}

	.mui-popover {
		height: 340px;
	}

	.textone {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		line-height: 35px;
		max-height: 40px;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
	}

	/*隐藏*/
	.myNone {
		display: none !important;
	}

	.yy {
		touch-action: none
	}
	
	.uni-popover.uni-active {
		    display: block;
		    opacity: 1;
		}
		
		.uni-popover {
		    height: 340px;
		}
		
		.uni-popover {
		    position: absolute;
		    z-index: 999;
		    display: none;
		    width: 280px;
		    -webkit-transition: opacity .3s;
		    transition: opacity .3s;
		    -webkit-transition-property: opacity;
		    transition-property: opacity;
		    -webkit-transform: none;
		    transform: none;
		    opacity: 0;
		    border-radius: 7px;
		    background-color: #f7f7f7;
		    -webkit-box-shadow: 0 0 15px rgba(0,0,0,.1);
		    box-shadow: 0 0 15px rgba(0,0,0,.1);
		}
		
		* {
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		    -webkit-user-select: none;
		    outline: 0;
		    -webkit-tap-highlight-color: transparent;
		    -webkit-tap-highlight-color: transparent;
		}
		
		.uni-popover .uni-popover-arrow.uni-bottom {
		    top: 100%;
		    left: -26px;
		    margin-top: -1px;
			transform:rotate(180deg)
		}
		
		.uni-popover .uni-popover-arrow {
		    position: absolute;
		    z-index: 1000;
		    top: -25px;
		    left: 0;
		    overflow: hidden;
		    width: 26px;
		    height: 26px;
		}
		
		.uni-popover .uni-popover-arrow:after {
		    position: absolute;
		    top: 19px;
		    left: 0;
		    width: 26px;
		    height: 26px;
		    content: ' ';
		    -webkit-transform: rotate(45deg);
		    transform: rotate(45deg);
		    border-radius: 3px;
		    background: #f7f7f7;
			
		}
		
		.uni-box {
		    width: 80%;
		    display: flex;
		    flex-direction: row;
		    margin-top: 550px;
		    margin-left: 10%;
		}
		
		.point {
			font-size: 14px;
		}
		
		.uni-table-view-cell {
		    position: relative;
		    overflow: hidden;
		    padding: 11px 15px;
		    -webkit-touch-callout: none;
		}
		
		.uni-table-view-cell:after {
		    position: absolute;
		    right: 0;
		    bottom: 0;
		    left: 0px;
		    height: 1px;
		    content: '';
		    -webkit-transform: scaleY(.5);
		    transform: scaleY(.5);
		    background-color: #c8c7cc;
		}
		
		ul {
			padding-inline-start: 0px;
		}
		
		.animation-enter,.animation-leave-to{
		    opacity: 0;
		}
		
		.animation-enter-to,.animation-leave{
		    opacity: 1;
		}
		
		.animation-enter-active,.animation-leave-active{
		    transition: all 0.5s;
		}
</style>
