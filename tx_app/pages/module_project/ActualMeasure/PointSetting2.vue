
<template>
	<view @touchmove="touchmove">
		<div class="container">  
			<div>
				<img ref="pic" src="../../../static/images/projectPic.jpg" hidden="hidden" />
				<canvas type="webgl" canvas-id="myCanvas" id="myCanvas" ref="myCanvas" class="my-canvas" :style="{width:canvasWidth +'px',height:canvasHeight +'px',margin:'0 0 0 50% ',left:'-170px'}"
				 width="100px" height="100px"></canvas>
			</div>
			<div class="port" id="dian">
				<span id="port" class="bar" style="position: absolute;z-index: -1;">0</span>
				<block v-for="item in pointList">
					<!-- Object.keys(select_point).length== 0 -->
					<!-- :style="{'display':Object.keys(select_point).length== 0 ? 'block':'none'}" -->
					<span :style="{marginTop:item.top +'px',marginLeft:item.left +'px',display:item.display}" :class="[item.title=='x'? 'bar' : 'Bar']" class="commonbar"  :ref="item.id"  @click="setPoint(item,$event)">{{item.value}}</span>
				</block>
			</div>
			<div class="">
				<img ref="pic_biger" src="../../../static/images/projectPic.jpg" width="1602.5px" height="1132.5" hidden="hidden" />
				<!-- <canvas id="my_Canvas" style="margin-top: 340px;"></canvas> -->
				<canvas @touchstart="touchstart" :style="{width:canvasWidth1 +'px',height:canvasHeight1 +'px',margin:'300px 0 0 50% ',left:'-170px'}"
				 canvas-id="my_Canvas" id="my_Canvas"></canvas>
			</div>
			<div ref="smalldiv" :style="{position:'absolute',width:smallWidth+'px',height:smallHeight+'px',backgroundColor:'rgba(63,10,217,0.5)',border:'1px solid gray',top:smallTop+'px',left:smallLeft+'px'}"></div>
		</div>
	</view>
</template>

<script>
	let that = null
	const myCanvas = uni.createCanvasContext('myCanvas')
	const my_Canvas = uni.createCanvasContext('my_Canvas')
	export default {
		components: {},
		data() {
			return {
				src: '../../../static/images/projectPic.jpg',
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
			};
		},
		onReady() {

		},
		onLoad: function(option) {
			that = this
			// that.cardId = option.id
		},
		mounted() {
			uni.getSystemInfo({
			    success: (res) => {
			            var plus = res.pixelRatio
			    }	
			   })
			var img = this.$refs["pic"];
			this.set_height = this.phone_width * (this.img_h / this.img_w)
			this.small_width = this.phone_width * 0.2 //smalldiv的宽度
			this.small_height = this.set_height * 0.2 //samlldiv的高度

			this.smallWidth = this.small_width
            this.smallHeight = this.small_height
			
			this.canvasWidth = this.phone_width * plus;
			this.canvasHeight = this.set_height * plus;
            
			
			var url_load = "../../../static/images/projectPic.jpg";
			myCanvas.drawImage(url_load, 0, 0, 1282, 906, 0, 0, 342, 243);
			myCanvas.draw()

			this.getFirst()
		},
		methods: {
			getFirst() {
				// this.creat(1, 1, 2, 3, 4, 5, 6, 7); //将点布置到相应位置
				this.pointList = []
				this.budian(1, 2, 3, 4, 5, 6, 7); //生成点
				this.budian(11, 22, 33, 44, 55, 66, 77); //生成点
			},
			//窗口坐标转换为canvas坐标
			windowTocanvas(flag, x, y) {
					let view = uni.createSelectorQuery().in(this).select("#myCanvas");
					view.boundingClientRect(data => {
		
						this.tomyCanvas = {
							x: x - data.left,
							y: y - data.top,
							mar_t: data.top,
							mar_l: data.left,
						};
					
					}).exec();
			},
			//移动
			touchmove(e){
				that.windowTocanvas(myCanvas, e.touches[0].pageX, e.touches[0].pageY);
	
				let loc = that.tomyCanvas
				var x = parseInt(loc.x);
				var y = parseInt(loc.y);
				// console.log(loc);
				var LimitHeight = that.set_height
				if (y < LimitHeight) {

					that.mar_top = parseInt(loc.mar_t);
					that.mar_left = parseInt(loc.mar_l);

					if (that.biao == "move") {
						// console.log(that)
					}

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
				
					// console.log('top:' + that.divY + " " + 'left:' + that.divX)
					that.smallTop = that.divY
					that.smallLeft = that.divX
				
					var X = (that.divX - that.mar_left) * (that.img_w * 0.5 / that.phone_width);
					var Y = (that.divY - that.mar_top) * (that.img_w * 0.5 / that.phone_width);
					// console.log("Y:"+Y)
					// console.log("X:"+X)
					var M = that.small_width * (that.img_w * 0.5 / that.phone_width);
					var N = that.small_height * (that.img_w * 0.5 / that.phone_width);
					// console.log(X+"@"+Y+"@"+M+"@"+N)
					if (that.biao == "move") {
						my_Canvas.clearRect(0, 0, 1000, 1000); //清除画布
						my_Canvas.drawImage('../../../static/images/projectPic.jpg', X * 2, Y * 2, M * 2, N * 2, 0, 0, 342, 242); //绘制图片
						my_Canvas.draw(); //绘制图片
					}

				
					that.box_X = that.divX - that.mar_left;
					that.box_Y = that.divY - that.mar_top;
				}
			},
			//绘制移到的框和放大图
			creat(length, X_zb, Y_zb, X_father, Y_father, data, point_id) {
				document.body.ontouchmove = function(e) {
					var loc = that.windowTocanvas(myCanvas, e.touches[0].pageX, e.touches[0].pageY);
					var x = parseInt(loc.x);
					var y = parseInt(loc.y);

					var LimitHeight = that.set_height + 44;
					if (y < LimitHeight) {
						that.mar_top = parseInt(loc.mar_t);
						that.mar_left = parseInt(loc.mar_l);
						
                         console.log(that.smallWidth);
						if (x < 0 && y < that.set_height) { //当鼠标的X坐标小于图片与div遮罩层的x坐标和是ｄｉｖｘ＝０；
							that.divX = that.mar_left;
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

						// console.log('top:' + that.divY + " " + 'left:' + that.divX)
						that.smallTop = that.divY
						that.smallLeft = that.divX

						var X = (that.divX - that.mar_left) * (that.img_w * 0.5 / that.phone_width);
						var Y = (that.divY - that.mar_top) * (that.img_w * 0.5 / that.phone_width);
						// console.log("Y:"+Y)
						// console.log("X:"+X)
						var M = that.small_width * (that.img_w * 0.5 / that.phone_width);
						var N = that.small_height * (that.img_w * 0.5 / that.phone_width);
						// console.log(X+"@"+Y+"@"+M+"@"+N)
						if (that.biao == "move") {
							let ctx = uni.createCanvasContext('my_Canvas');
							ctx.clearRect(0, 0, 1000, 1000); //清除画布
							ctx.drawImage('../../../static/images/projectPic.jpg', X * 2, Y * 2, M * 2, N * 2, 0, 0, 342, 242); //绘制图片
							ctx.draw(); //绘制图片
						}
						// 	// var zb = document.getElementById("zb");

						that.box_X = that.divX - that.mar_left;
						that.box_Y = that.divY - that.mar_top;

						// 	//alert(box_X+"#"+box_Y)

						// 	// for (var j = 0; j < length - 1; j++) {
						// 	// 	var X_zb = data[j].X; //x比例
						// 	// 	var Y_zb = data[j].Y; //y比例
						// 	// 	var X_father = data[j].X_father; //Y坐标
						// 	// 	var Y_father = data[j].Y_father; //Y坐标
						// 	// 	var d_x = X_zb * phone_width;
						// 	// 	var d_y = Y_zb * that.set_height;
						// 	// 	var port = document.getElementById("port");
						// 	// 	var mainWidth = port.offsetWidth;
						// 	// 	var port_obj = port.getBoundingClientRect(); //用于获取点相对于视窗的位置集合
						// 	// 	var bbox = my_Canvas.getBoundingClientRect();
						// 	// 	var mar_top = bbox.top;
						// 	// 	var mar_left = bbox.left;
						// 	// 	var span_Id = data[j].测点类型 + "|" + point_id + "|" + data[j].num;
						// 	// 	//			console.log(span_Id)
						// 	// 	var y_y = document.getElementById(span_Id);
						// 	// 	var classname = y_y.getAttribute('class');
						// 	// 	classVal = classname.replace("bar", "Bar");
						// 	// 	y_y.setAttribute("class", classVal);
						// 	// 	var dian_left = (X_father * 4.95 + d_x + mar_left - port_obj.left - mainWidth / 2) - X * 2.65;
						// 	// 	var dian_top = (Y_father * 4.95 + d_y + mar_top - port_obj.top - mainWidth / 2) - Y * 2.65;
						// 	// 	//		    alert(dian_left+"@"+dian_top)
						// 	// 	y_y.style.marginLeft = dian_left + "px";
						// 	// 	y_y.style.marginTop = dian_top + "px";

						// 	// 	var diana = document.getElementById("dian");
						// 	// 	var dian_style = diana.getBoundingClientRect(); //用于获取点相对于视窗的位置集合
						// 	// 	var ca_mar_T = mar_top - dian_style.top;
						// 	// 	var ca_ma_L = mar_left - dian_style.left;
						// 	// 	if (dian_top < ca_mar_T || dian_top > (ca_mar_T + that.set_height)) {
						// 	// 		y_y.style.display = "none";
						// 	// 	} else {
						// 	// 		y_y.style.display = "block";
						// 	// 	}
						// 	// }
					}



				};
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
			},
			//点击布点
			setPoint(val, event) {
				console.log(val)
				//布点点击
				var por = new Array(); //存点坐标
				var num = val.value //编号
				var id_arr = val.id.split("|");
				var point_name = id_arr[0];
				var carid = id_arr[1]; //实测卡片id
				// if (this.name != "save") {
				// 	biao = "stay";

				// var port = this.$refs['port']
				// var mainWidth = port.offsetWidth;
				var mainWidth = '20';
				
				let port_obj = uni.createSelectorQuery().in(this).select("#port");//用于获取点相对于视窗的位置集合
				port_obj.boundingClientRect(data => {
						this.port_obj = data
						console.log(this.port_obj);
				}).exec();
                if (val.title == "x") { //判断是否为待点区的点
                	that.Name[that.j] = point_name + "|" + num;
                	that.flage = 0;
                	if (that.flage == 0) {
                		val.title = "y";
                	}
                }
				that.select_point = val
                that.pointList.forEach(item => {
                	if (item.title == 'y'&&item.id==that.select_point.id) {
                		item.display = 'none'
						// item.zindex = '-1'
                		// that.spanWidth = (point_x + mar_left - that.port_obj.left - 20 / 2)
                		// that.spanHeight = (point_y + mar_top - that.port_obj.top - 20 / 2)
                	}
                });
                // this.touchstart(val)
				// my_Canvas.ontouchstart = function(e) { //布点
				// 	that.flage = 1;
				// 	var loc = that.windowTocanvas(my_Canvas, e.touches[0].pageX, e.touches[0].pageY);
				// 	var point_x = parseInt(loc.x);
				// 	var point_y = parseInt(loc.y);
				// 	var mar_top = parseInt(loc.mar_t) + 44;
				// 	var mar_left = parseInt(loc.mar_l);
				// 	console.log(point_x + "子1" + point_y)
				// 	console.log(mar_top + "子2" + mar_left)

				// 	that.$refs[val.id][0].style.display = "block";
				// 	that.$refs[val.id][0].style.zIndex = 99
				// 	that.$refs[val.id][0].style.position = 'absolute'
				// 	that.$refs[val.id][0].style.marginLeft = (point_x + mar_left - port_obj.left - mainWidth / 2) + "px";
				// 	that.$refs[val.id][0].style.marginTop = (point_y + mar_top - port_obj.top - mainWidth / 2) + "px";

				// 	por = [point_x / that.phone_width, point_y / that.set_height, box_X, box_Y, num]; //X坐标
				// 	por_obj[point_name + "|" + num] = por; //将点及对应的坐标存入对象

				// };
			},
			touchstart(e) { //布点
				that.flage = 1;
				console.log(e);
				that.pointList.forEach(item => {
					if (item.title == 'y'&&item.id==that.select_point.id) {
						item.display = 'block'
						item.left = (e.touches[0].x - that.port_obj.left - 10)
						item.top = (e.touches[0].y + that.port_obj.height  )
						console.log(item.top + "子3" + item.left)
					}
				});;
			// 	por = [point_x / that.phone_width, point_y / that.set_height, box_X, box_Y, num]; //X坐标
			// 	por_obj[point_name + "|" + num] = por; //将点及对应的坐标存入对象
			
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

	html,
	body {
		position: relative;
		height: 100%;
		width: 100%;
		overflow: hidden;
		margin: 0;
		padding: 0;
	}

	.container {
		width: 100%;
		height: 100%;
		overflow: hidden;
		background-color: #F0FFFF;
	}

	#myCanvas,
	#my_Canvas {
		overflow: hidden;
		margin-top: 50px;
		border: 1px solid #d3d3d3;
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
</style>
