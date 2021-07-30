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
			<view @touchmove="touchmove" @touchstart="touchmove">
			<!-- <view @click="touchmove" class='box1'> -->
				<img ref="pic" :src="picSrc" hidden="hidden" id="pic"/>				
				<canvas type="webgl" canvas-id="myCanvas"  disable-scroll="true" id="myCanvas" ref="myCanvas" class="my-canvas" :style="{width:canvasWidth +'px',height:canvasHeight +'px',margin:'0 0 0 50% ',left:'-170px'}"
				 width="100px" height="100px" ></canvas>
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
								<li v-for="(item,index) in pointArr" :key="index" class="uni-table-view-cell" @click="selectType(item)" v-show="item.missItem == null"><p>{{item.measureType}}</p></li>
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
				phone_width: '342',
				set_height: '242',
				img_w: '1282',
				img_h: '906',
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
				smallTop: '',
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
			// console.log(this.myCanvas)
			// console.log(this.my_Canvas)
			
			this.getFirst()   // this.pointList = []
			
			
			// var url_load = "../../../static/images/projectPic.jpg";
			// myCanvas.drawImage(url_load, 0, 0, 1282, 906, 0, 0, 342, 243);
		},
		onLoad(option) {
			// that = this
			// this.currentData = JSON.parse(option.currentData)
			// this.cardParam = JSON.parse(option.cardParam)
			// this.projectId = option.projectId
			// this.picSrc = option.measureSrc
			// uni.$on('changeStatus', (point)=>{
			// 	console.log(point)
			// 	console.log(this.pointList)						
			// 	for(let i = 0; i < point.length; i++) {
			// 		if ( point[i].status == '合格') {
			// 			this.pointList.display = 'block';
			// 			console.log(this.pointList)
			// 		}
			// 	}
				
			// }) 
			// console.log(this.picSrc)
			
		}, 
		mounted() {
			let that = this
			that.getStorageInfo(); // 获取缓存
			uni.getSystemInfo({
			    success: (res) => {
					// console.log(res.pixelRatio);
			            that.plus = res.pixelRatio // 获取像素比
			    }	
		   })
		   // console.log(that.plus);  
		   var plus = that.plus
			var img = this.$refs["pic"];
			this.set_height = this.phone_width * (this.img_h / this.img_w)
			this.small_width = this.phone_width * 0.2 //smalldiv的宽度
			this.small_height = this.set_height * 0.2 //samlldiv的高度

			this.smallWidth = this.small_width
            this.smallHeight = this.small_height   
			
			this.canvasWidth = this.phone_width * plus;
			this.canvasHeight = this.set_height * plus;
			// console.log(this.canvasWidth);
			// let myCanvas = uni.createCanvasContext('myCanvas')
			
			
			// var url_load = "../../../static/images/projectPic.jpg";
			// myCanvas.drawImage(url_load, 0, 0, 1282, 906, 0, 0, 342, 243);
			
			// this.getFirst()
			// this.getSavedPoint()
		},
		methods: {
			// aaa(){
			// 	uni.startSoterAuthentication({
			//     success: (res) => {
			// 		// console.log(res);
			//     }	
			// 	})
			// },
			getStorageInfo() {
				uni.getStorage({
					key:'cardParam',
					success:(res) =>{
						// console.log(res)
						let temp = res.data
						this.cardParam = temp
						uni.getStorage({
							key: 'PicSrc',
							success: (res) => {
								// console.log(res)
								let temp = res.data
								this.picSrc = res.data
								uni.getStorage({
									key: 'userInfo',
									success:(res) =>{
										// console.log(res)
										let temp = res.data
										this.currentData = res.data
										this.projectId = this.currentData.projectId
										this.getPointStatus();
									}
								})
							}
						})
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
			//窗口坐标转换为canvas坐标
			windowTocanvas(flag, x, y) {
				// console.log(111)
					let view = uni.createSelectorQuery().in(this).select("#myCanvas");
					view.boundingClientRect(data => {
						// console.log(data);
						// console.log(x,y);
						this.tomyCanvas = {
							x: x - data.left,//当前小紫色方框与canvas左内边距
							y: y - data.top,
							mar_t: data.top,
							mar_l: data.left,//canvas左边距
						};
					// console.log(this.tomyCanvas);
					}).exec();
			},
			//窗口坐标转换为canvas坐标
			// windowTocanvas1(flag, x, y) {
					
			// 		let view = uni.createSelectorQuery().in(this).select("#my_Canvas");
			// 		view.boundingClientRect(data => {
			// 			// console.log(data);
			// 			// this.tomyCanvas = {
			// 			// 	x: x - data.left,//当前小紫色方框与canvas左内边距
			// 			// 	y: y - data.top,
			// 			// 	mar_t: data.top,
			// 			// 	mar_l: data.left,//canvas左边距
			// 			// };
					
			// 		}).exec();
			// },
			//移动
			touchmove(e){
				let that = this;
				that.tempE = e;
				// console.log(e)
				// console.log(that.tempE)
				//pageX 当前点击点X左边
				if (that.smallDivShow == 'none') return ; // 处于布点状态无法移动小紫框放大
				that.windowTocanvas(that.myCanvas, that.tempE.touches[0].pageX, that.tempE.touches[0].pageY); // 计算当前小方框相对于canvas的XY左边
				// console.log(that.windowTocanvas)
				that.buttonShow = true //显示下方按钮
				let loc = that.tomyCanvas  // 小紫框到canvas的边距
				var x = parseInt(loc.x);
				var y = parseInt(loc.y);
				// console.log(loc); 
				var LimitHeight = that.set_height
				if (y < LimitHeight) {
					that.mar_top = loc.mar_t;  // canvas的上边距
					that.mar_left = loc.mar_l;  // mar_left = divX
					if (x < 0 && y < that.set_height) { //当鼠标的X坐标小于图片与div遮罩层的x坐标和是ｄｉｖｘ＝０；
						that.divX = loc.mar_l;
					} else if (x >= 0 && x < that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标在图片内部并且小于图片最右边的X坐标
						that.divX = x + that.mar_left;
					} else if (x >= that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标大于图片的最右边的X坐标 （Y轴同理）
						// that.divX = that.divX
					}
				
					if (y < 0) {
						that.divY = that.mar_top;
					} else if (y >= 0 && y < that.set_height - that.smallHeight) {
						that.divY = that.mar_top + y;
					} else if (y > that.set_height - that.smallHeight && y <= that.set_height) {
						that.divY = that.mar_top + that.set_height - that.smallHeight;
					} else if (y > that.set_height) {
				
					}
					that.smallTop = that.divY
					that.smallLeft = that.divX
					// 获取图纸大小信息
					 uni.getImageInfo({
						src: that.picSrc,
						success: function (image) {
							// console.log(image.width);
							// console.log(image.height);
							that.img_w = image.width;
							that.img_h = image.height;
						}
					});
					that.X = (that.divX - that.mar_left) * (that.img_w * 0.5 / that.phone_width); // 放大图的坐标
					that.Y = (that.divY - that.mar_top) * (that.img_w * 0.5 / that.phone_width);
					that.M = that.small_width * (that.img_w * 0.5 / that.phone_width);  // 放大图的截取大小
					that.N = that.small_height * (that.img_w * 0.5 / that.phone_width);
					// console.log(X+"@"+Y+"@"+M+"@"+N)
					if (that.biao == "move") {
						that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
						that.my_Canvas.drawImage(that.picSrc, that.X*2, that.Y*2 ,that. M*2 , that.N*2 , 0, 0, 342, 242); //绘制图片
						that.my_Canvas.draw(); //绘制图片 
					} 
					that.box_X = that.divX - that.mar_left; // 小紫框相对于canvas的X坐标, 小紫框的坐标 - canvas的坐标
					that.box_Y = that.divY - that.mar_top;
				}
			},
			//绘制移到的框和放大图
			// creat(length, X_zb, Y_zb, X_father, Y_father, data, point_id) {
			// 	const that = this;
			// 	document.body.ontouchmove = function(e) {
			// 		var loc = that.windowTocanvas(myCanvas, e.touches[0].pageX, e.touches[0].pageY);
			// 		var x = parseInt(loc.x);
			// 		var y = parseInt(loc.y);
			// 		// console.log('3232'+x+y)
			// 		var LimitHeight = that.set_height + 44;
			// 		if (y < LimitHeight) {
			// 			that.mar_top = parseInt(loc.mar_t);
			// 			that.mar_left = parseInt(loc.mar_l);
						
   //                       // console.log(that.smallWidth);
			// 			if (x < 0 && y < that.set_height) { //当鼠标的X坐标小于图片与div遮罩层的x坐标和是ｄｉｖｘ＝０；
			// 				that.divX = that.mar_left;
			// 			} else if (x >= 0 && x < that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标在图片内部并且小于图片最右边的X坐标
			// 				that.divX = x + that.mar_left;
			// 			} else if (x >= that.phone_width - that.smallWidth && y < that.set_height) { //鼠标的X坐标大于图片的最右边的X坐标 （Y轴同理）
			// 				// that.divX = that.divX
			// 			}

			// 			if (y < 0) {
			// 				that.divY = that.mar_top;
			// 			} else if (y >= 0 && y < that.set_height - that.smallHeight) {
			// 				that.divY = that.mar_top + y;
			// 			} else if (y > that.set_height - that.smallHeight && y <= that.set_height) {
			// 				that.divY = that.mar_top + that.set_height - that.smallHeight;
			// 			} else if (y > that.set_height) {

			// 			}

			// 			// console.log('top:' + that.divY + " " + 'left:' + that.divX)
			// 			that.smallTop = that.divY
			// 			that.smallLeft = that.divX

			// 			var X = (that.divX - that.mar_left) * (that.img_w * 0.5 / that.phone_width);
			// 			var Y = (that.divY - that.mar_top) * (that.img_w * 0.5 / that.phone_width);
			// 			// console.log("Y:"+Y)
			// 			// console.log("X:"+X)
			// 			var M = that.small_width * (that.img_w * 0.5 / that.phone_width);
			// 			var N = that.small_height * (that.img_w * 0.5 / that.phone_width);
			// 			// console.log(X+"@"+Y+"@"+M+"@"+N)
			// 			let img_load = new Image;
			// 			img_load.src = that.picSrc;
			// 			if (that.biao == "move") {
			// 				// let ctx = uni.createCanvasContext('my_Canvas');
			// 				this.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
			// 				this.my_Canvas.drawImage('../../../static/images/projectPic.jpg', X * 2, Y * 2, M * 2, N * 2, 0, 0, 342, 242); //绘制图片
			// 				this.my_Canvas.draw(); //绘制图片
			// 			}
			// 			// 	// var zb = document.getElementById("zb");

			// 			that.box_X = that.divX - that.mar_left;
			// 			that.box_Y = that.divY - that.mar_top;

			// 			// 	//alert(box_X+"#"+box_Y)

			// 			// 	// for (var j = 0; j < length - 1; j++) {
			// 			// 	// 	var X_zb = data[j].X; //x比例
			// 			// 	// 	var Y_zb = data[j].Y; //y比例
			// 			// 	// 	var X_father = data[j].X_father; //Y坐标
			// 			// 	// 	var Y_father = data[j].Y_father; //Y坐标
			// 			// 	// 	var d_x = X_zb * phone_width;
			// 			// 	// 	var d_y = Y_zb * that.set_height;
			// 			// 	// 	var port = document.getElementById("port");
			// 			// 	// 	var mainWidth = port.offsetWidth;
			// 			// 	// 	var port_obj = port.getBoundingClientRect(); //用于获取点相对于视窗的位置集合
			// 			// 	// 	var bbox = my_Canvas.getBoundingClientRect();
			// 			// 	// 	var mar_top = bbox.top;
			// 			// 	// 	var mar_left = bbox.left;
			// 			// 	// 	var span_Id = data[j].测点类型 + "|" + point_id + "|" + data[j].num;
			// 			// 	// 	//			console.log(span_Id)
			// 			// 	// 	var y_y = document.getElementById(span_Id);
			// 			// 	// 	var classname = y_y.getAttribute('class');
			// 			// 	// 	classVal = classname.replace("bar", "Bar");
			// 			// 	// 	y_y.setAttribute("class", classVal);
			// 			// 	// 	var dian_left = (X_father * 4.95 + d_x + mar_left - port_obj.left - mainWidth / 2) - X * 2.65;
			// 			// 	// 	var dian_top = (Y_father * 4.95 + d_y + mar_top - port_obj.top - mainWidth / 2) - Y * 2.65;
			// 			// 	// 	//		    alert(dian_left+"@"+dian_top)
			// 			// 	// 	y_y.style.marginLeft = dian_left + "px";
			// 			// 	// 	y_y.style.marginTop = dian_top + "px";

			// 			// 	// 	var diana = document.getElementById("dian");
			// 			// 	// 	var dian_style = diana.getBoundingClientRect(); //用于获取点相对于视窗的位置集合
			// 			// 	// 	var ca_mar_T = mar_top - dian_style.top;
			// 			// 	// 	var ca_ma_L = mar_left - dian_style.left;
			// 			// 	// 	if (dian_top < ca_mar_T || dian_top > (ca_mar_T + that.set_height)) {
			// 			// 	// 		y_y.style.display = "none";
			// 			// 	// 	} else {
			// 			// 	// 		y_y.style.display = "block";
			// 			// 	// 	}
			// 			// 	// }
			// 		}



			// 	};
			// },
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
				// if (this.name != "save") {
				// 	biao = "stay";

				// var port = this.$refs['port']
				// var mainWidth = port.offsetWidth;
				// var mainWidth = '20';  				 
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
				// console.log(that.select_point);
				// console.log(that.pointList)
                that.pointList.forEach(item => { 
                	if (item.title == 'y' && item.id==that.select_point.id) {
						// console.log(11)
                		item.display = 'none';
                	}
                });
				// console.log(that.pointList)
			},
			touchstart(e) { //布点
				const that = this 
				that.flage = 1;
				let por = new Array(); //存点坐标
				let point_name = that.poiotType; 
				that.pointList.forEach(item => {				
					if (item.title == 'y' && item.id==that.select_point.id) {
						item.display = 'block'
						item.left = (e.touches[0].x - that.port_obj.left )
						// item.left = (e.touches[0].x - that.port_obj.left - 10.00000000000000)
						item.top = (e.touches[0].y + that.port_obj.height )
						// console.log(item.top + "子" + item.left)						
						por = [item.left / 342, item.top / 242, that.box_X, that.box_Y, that.num]; // 点相对于canvas的坐标
						that.por_obj[point_name + "|" + that.num] = por; // 将点及对应的坐标存入对象						
						// that.my_Canvas.arc(item.left+20, item.top-25, 10, 0, 2*Math.PI,false);
						// that.my_Canvas.setFillStyle('yellow');	
						// that.my_Canvas.fill();
						// that.my_Canvas.draw();						
					}  					 
				});  				 
				// console.log(that.por_obj)
				// console.log(point_name);
				// console.log(that.pointList)  
				// console.log(that.num)  
				
				 
				
				
				// that.windowTocanvas1(that.my_Canvas, e.touches[0].x, e.touches[0].y);
				// that.windowTocanvas(that.my_Canvas, e.touches[0].pageX, e.touches[0].pageY);
				// let loc = that.tomyCanvas 
				// var point_x = parseInt(loc.x);
				// var point_y = parseInt(loc.y);
				// var mar_top = parseInt(loc.mar_t) + 44; 
				// var mar_left = parseInt(loc.mar_l);
				// por = [point_x / that.phone_width, point_y / that.set_height, that.box_X, that.box_Y, this.num]; //X坐标
				// por = [point_x / that.phone_width, item.top / that.set_height, that.box_X, that.box_Y, this.num]; //X坐标
				// that.por_obj[point_name + "|" + this.num] = por; //将点及对应的坐标存入对象
				// console.log(that.por_obj)
			// 	por = [point_x / that.phone_width, point_y / that.set_height, box_X, box_Y, num]; //X坐标
			// 	por_obj[point_name + "|" + num] = por; //将点及对应的坐标存入对象
			
			}, 
			
			
			//展示测点类型函数
			openPopover() {
				this.animationShow = !this.animationShow
				// console.log(this.pointArr)
				// console.log(this.pointData)
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
						this.pointStatus = res.data.data[0].status
						// console.log(res.data.data[0].status)
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为'+res.data.data[0].status+'状态！'
						});
						this.getSavedPoint()
						// this.getPointType()
						this.getMeasureType()
					}else{
						this.pointStatus = '初测'
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '当前为初测状态！'
						});
						// this.getPointType()
						this.getSavedPoint()
						this.getMeasureType()
					}
					
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			},
			// // 获取测点类型
			// getPointType() {
			// 	let that = this
			// 	let opts = {
			// 		url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
			// 		method: 'POST',
					
			// 	}
			// 	let param = {
			// 		flag: 'getPointType',
			// 		measureId: that.cardParam.id,
			// 		projectId: that.projectId,
			// 		pointStatus: that.pointStatus
			// 	}  
			// 	let isLoading = false//是否需要加载动画
			// 	that.myRequest.httpRequest (opts, param,isLoading).then(res => {
			// 		console.log(res.data)
			// 		if(res.data.code){
			// 			for(var i=0;i<res.data.data.length;i++){
			// 				if(res.data.data[i].measurePointName!="null"){
			// 					that.pointTypeArr.push({
			// 						poiotType: res.data.data[i].measurePointName,
			// 						pointNumber: res.data.data[i].number
			// 					})
			// 				} 
			// 			}
			// 		}
			// 		uni.hideLoading()//隐藏加载中转圈圈
			// 		that.isloading = false//取消遮罩层
			// 	}, error => {
			// 		console.log(error);
			// 	})
			// },
			//获取测点类型
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
				// console.log(that.pointStatus)
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					if(res.data.code){
						this.pointArr = res.data.data
						let i = that.currentRadio;
						that.pointTypeArr = that.pointArr[i];
						// console.log(this.pointArr)
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
			// 选择测点类型
			selectType(item) {
				let that = this
				that.smallDivShow = 'block'
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
					// console.log(res.data)
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
			savePointFunc() {
				// console.log(JSON.stringify(this.por_obj))
				let that = this
				that.smallDivShow = 'block'
				that.animationShow = false
				let opts = {
					url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				let param = {
					flag: 'savePoint', 
					measureId: that.cardParam.id,
					por_obj: JSON.stringify(that.por_obj)
				} 
				let isLoading = false//是否需要加载动画
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					if(res.data.code){
						that.getSavedPoint()
						uni.showToast({
							icon: 'none',
							position: 'bottom',
							title: '测点布置成功！'
						});
						that.por_obj = {}
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
				// 创建最上面的画布
				// let url_load = "../../../static/images/projectPic.jpg";
				 // url_load = that.picSrc;
				// that.myCanvas.drawImage(url_load, 0, 0, 1282, 90	6, 0, 0, 342, 243);、
				// let img_load = new Image; 	// 创建图片对象
				// img_load.src = that.picSrc; // 本栋的图纸地址
				// console.log(img_load.width,'|',img_load.height)
				// that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
				that.myCanvas.drawImage(that.picSrc,0, 0, 342, 243);
				let opts = {
					url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
					method: 'POST'
				}
				console.log(that.pointStatus)
				let param = {
					flag: 'getSavedPoint',
					measureId: this.cardParam.id,
					pointStatus: that.pointStatus
				} 
				let isLoading = false//是否需要加载动画
				// console.log(this.cardParam.id)
				that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// console.log(res.data)
					// console.log(that.cardParam.id)
					that.putPointNum = res.data.putPointNum
					that.notPointNum = res.data.notPointNum
					if(res.data.code){
						that.pointData = res.data.data
						// console.log(res.data.notPointNum)
						let i = that.currentRadio;
						that.pointDataPart = that.pointData[i];
						// console.log(that.pointDataPart)
						that.drawPoint()
					}else{ 
						// that.myCanvas.draw();
						// that.my_Canvas.draw();
						that.pointData = [];
						that.pointDataPart  = [];
						that.drawPoint()
						// var c=document.getElementById("myCanvas"); 
						// save_pic(c);
					} 
					uni.hideLoading()//隐藏加载中转圈圈
					that.isloading = false//取消遮罩层
				}, error => {
				})
			}, 
			// 将已保存的点画到画布上
			drawPoint(X_father,Y_father,X_zb,Y_zb,length,i,num) {
				const that = this;
					// let myCanvas = document.querySelector('#myCanvas > canvas')
					// let myCanvas = uni.createSelectorQuery().in(this).select("#myCanvas");
					// let ctx = myCanvas.getContext('2d')
					// var plus = window.devicePixelRatio; //获取设备像素比
					// var c=document.getElementById("myCanvas");
					// var phone_width = $(window).width()*0.95;//canvas的宽度
			// 		var img = this.$refs["pic"];
			// 		var img_w = img.naturalWidth;
			// 		var img_h = img.naturalHeight;
			// //		alert(img_w+"||"+img_h)
			// 		var set_height = this.phone_width*(img_h/img_w);
			// 		var bbox = myCanvas.getBoundingClientRect();
					//适应ios
					var phone_width=342;
					var set_height=242;
					let data = that.pointData
					// console.log(data); 
					
					// const myCanvas = uni.createCanvasContext('myCanvas')
					// console.log(data)
					for(let i=0;i<data.length;i++){
						for (let j = 0; j < data[i].sonNumber.length; j++) {
							var X_zb = data[i].sonNumber[j].Xcoordinate;  // 点相对于canvas的坐标
							var Y_zb = data[i].sonNumber[j].Ycoordinate;
							var X_father = data[i].sonNumber[j].parXcoordinate; // 小紫框相对于canvas的坐标
							var Y_father = data[i].sonNumber[j].parYcoordinate; 
							// console.log('X_father'+X_father);
							// console.log('Y_father'+Y_father);
							var num = data[i].sonNumber[j].num; 
							var point_x = (parseFloat(X_father))
							// var point_x = (parseFloat(X_father)+parseFloat(phone_width*X_zb*0.2))*(300/phone_width);
							var point_y = (parseFloat(Y_father)) 
							// var point_y = (parseFloat(Y_father)+parseFloat(set_height*Y_zb*0.2))*(150/set_height); 
							// console.log(this.small_width+'aaa')
							// console.log(point_x+parseFloat(X_zb*342));  
							that.savePointX = point_x+parseFloat(X_zb*that.small_width)+4 // 点的坐标
							that.savePointY = point_y+parseFloat(Y_zb*that.small_height)-6
							// console.log(savePointX+'++++
							// that.touchmove(that.tempE);
							that.myCanvas.beginPath();   
							that.myCanvas.strokeStyle ="#ff0000"; 
							that.myCanvas.arc(that.savePointX,that.savePointY,1,Math.PI*2,0,true);
							// this.myCanvas.arc(point_x+parseFloat(X_zb*342),point_y+parseFloat(Y_zb*242),3,Math.PI*2,0,true);
							that.myCanvas.fillStyle ="#ff0000";
							that.myCanvas.fill();  
							that.myCanvas.font="7px Arial";  
							that.myCanvas.fillText(num,that.savePointX+1,that.savePointY);   
						}
						
					} 
					that.myCanvas.stroke();		
					that.myCanvas.draw(true, (() => {
						 setTimeout(()=>{
							uni.canvasToTempFilePath({
							  x: 0,
							  y: 0,
							  width: 342,
							  height: 242,
							  destWidth: 3420,
							  destHeight: 2420,
							  fileType:'jpg',
							  canvasId: 'myCanvas',
							  success: function(res) {
								  uni.compressImage({
									  src: res.tempFilePath,
									  quality: 100,
									  success: res => {
										  // console.log(res.tempFilePath)
										  that.picSrc = res.tempFilePath
										  that.my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
										  that.my_Canvas.drawImage(that.picSrc, that.X*2, that.Y*2 , that.M *2, that.N*2, 0, 0, 342, 242); //绘制图片
										  // console.log(that.picSrc,'---',that.X*2, '--',that.Y*2,'--',that.M *2, '--',that.N*2,)
										  that.my_Canvas.draw()
										  that.savePicSrc()
										  
										}
									})
								} 
							})
						},500)
					}));
					// console.log(this.$refs.myCanvas)
					// this.$refs.myCanvas.toBlob(function(blob) {
					// 	let url = URL.createObjectURL(blob);
					// 	console.log(url)
					// 	console.log(blob)
					// });
					// console.log(ctx)
					// ctx.toBlob(function(blob) {
					// 	let url = URL.createObjectURL(blob);
					// 	console.log(url)
					// 	console.log(blob)
					// });
					// myCanvas.setStrokeStyle("#00ff00")
					//         myCanvas.setLineWidth(5)
					//         myCanvas.rect(0, 0, 200, 200)
					//         myCanvas.stroke()
					//         myCanvas.draw()
					// if(i==(length-2)){
					// 	save_pic(c);
					// }
				},
				savePicSrc () {
					const that = this;
					const opts = {
							url: that.api+'/module_project/ActualMeasure/MeasurePoint.php',
							method: 'POST' ,
						}
					const param = {
						flag: 'savePrimaryPicSrc',
						measureId:that.cardParam.id,
						picSrc: that.picSrc,
					}
					let isLoading = false;
					that.myRequest.httpRequest(opts, param, isLoading).then(res => {
						if (res.data.code) {
							// console.log(that.cardParam.id)
							console.log(res.data)
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
					// console.log(this.$refs.showLeft);
					// this.$refs.showLeft.open()
					// const showLeft = document.getElementById("showLeft");
					// showLeft.open();
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
						measureType: this.pointTypeArr.measureType,
					} 
					// console.log(param)
					let isLoading = true//是否需要加载动画
					this.myRequest.httpRequest (opts, param,isLoading).then(res => {
						// console.log(res.data)
						if(res.data.code){
							// that.getSavedPoint()
							uni.redirectTo({
								url: 'PointSetting'
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
					// this.isCorrect = 'false'
				},
				// 撤销布点
				clearPoint (items,length,measureType) {
					const that = this
					// console.log(items)
					// console.log(length)
					const opts = {
						url: this.api+'/module_project/ActualMeasure/MeasurePoint.php',
						method: 'POST'
					}
					let isLoading = false//是否需要加载动画
					const param = {
						flag: 'clearPoint',
						measureId:that.cardParam.id,
						measureType: measureType,
						items: JSON.stringify(items) 
					} 
					that.myRequest.httpRequest (opts, param,isLoading).then(res => {
						// console.log(res.data)
						if(res.data.code){
							// console.log(res.data.data[0].status)
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: '撤回成功'
							});
							// that.getSavedPoint();
							uni.redirectTo({
								url: 'PointSetting'
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
					// for (let i = 0; i < length; i++) {
					// 	const item = items[i];
					// 	console.log(item)
					// 	if (item.checked == true) { // 筛选出被勾选的
					// 		const param = {
					// 			flag: 'clearPoint',
					// 			measureId:that.cardParam.id,
					// 			measureType: measureType,
					// 			number: item.num
					// 		} 
					// 		console.log(param)
					// 		that.myRequest.httpRequest (opts, param,isLoading).then(res => {
					// 			console.log(res.data)
					// 			if(res.data.code){
					// 				// console.log(res.data.data[0].status)
					// 				uni.showToast({
					// 					icon: 'none',
					// 					position: 'bottom',
					// 					title: '已撤回测点'+item.num
					// 				});
					// 				that.getSavedPoint();
					// 				that.por_obj = {}
					// 				that.pointList.forEach(item => {
					// 					// if (item.title == 'y'&&item.id==that.select_point.id) {
					// 						item.display = 'none'
					// 					// }
					// 				})
					// 			}else{
					// 				uni.showToast({
					// 					icon: 'none',
					// 					position: 'bottom',
					// 					title: '撤回失败！'
					// 				});
					// 			}		
					// 			uni.hideLoading()//隐藏加载中转圈圈
					// 			that.isloading = false//取消遮罩层
					// 		}, error => {
					// 			console.log(error);
					// 		})
					// 	}
					// }
					// that.getSavedPoint()
					// console.log('clearPoint')
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
						// this.$refs.uniCollapse1.onClick()
					}
				},
				// changePointType () {
				// 	this.index = e.target.value
				// },
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
						// return
					// }
					// this.preventTwice++
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
		width: 20px;
		height: 20px;
		border-radius: 50%;
		font-size: 15px;
		color: #000;
		line-height: 20px;
		text-align: center;
		background: yellow;
	}
	.bar {
		float: left;
		display: block;
		width: 30px;
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
