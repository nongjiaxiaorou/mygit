<template>
	<view v-if="visibleSync" class="cat-signature" :class="{ visible: show }" @touchmove.stop.prevent="moveHandle">
		<view class="mask" @tap="close" />
		<view class="content">
			
			<view >
				<view class="uni-title " style="padding-left: 10rpx;">意见栏:</view>
				<view class="uni-textarea"><textarea @blur="bindTextAreaBlur" auto-height /></view>
				<view class="mt-1">
					<view class="uni-title " style="padding-left: 10rpx;">签名:</view>
					<canvas
						class="firstCanvas"
						:canvas-id="canvasId"
						@touchmove="move"
						@touchstart="start($event)"
						@touchend="end"
						@touchcancel="cancel"
						@longtap="tap"
						disable-scroll="true"
						@error="error"
					/>
					<view class="btns">
						<button type="primary" @tap="clear" size="mini">清除</button>
						<button type="primary" @tap="save" size="mini">保存</button>
					</view>
				</view>
			</view>
			
			</view>
		</view>
	</view>
</template>

<script>
var content = null;
var touchs = [];
var canvasw = 0;
var canvash = 0;
let that = null
//获取系统信息
uni.getSystemInfo({
	success: function(res) {
		canvasw = res.windowWidth;
		canvash = (canvasw * 9) / 16;
	}
});
export default {
	name: 'cat-signature',
	props: {
		visible: {
			type: Boolean,
			default: false
		},
		canvasId: {
			type: String,
			default: 'firstCanvas'
		},
		cardid: String,//卡片id
		userid: String//用户id
	},
	data() {
		return {
			Inv: 0,
			show: false,
			visibleSync: false,
			signImage: '',
			hasDh: false,
			img:'',
			idea:'',//意见栏1
		};
	},
	watch: {
		visible(val) {
			this.visibleSync = val;
			this.show = val;
			this.getInfo();
		}
	},

	created(options) {
		this.visibleSync = this.visible;
		this.getInfo();
		setTimeout(() => {
			this.show = this.visible;
		}, 100);
	},
	methods: {
		//切换列表
		changeTab(Inv) {
			that.navIdx = Inv;
		},
		getInfo() {
			//获得Canvas的上下文
			content = uni.createCanvasContext(this.canvasId, this);
			//设置线的颜色
			content.setStrokeStyle('#000');
			//设置线的宽度
			content.setLineWidth(5);
			//设置线两端端点样式更加圆润
			content.setLineCap('round');
			//设置两条线连接处更加圆润
			content.setLineJoin('round');
		},
		//
		close() {
			this.show = false;
			this.hasDh = false;
			this.$emit('close');
		},
		moveHandle() {},
		// 画布的触摸移动开始手势响应
		start(e) {
			let point = {
				x: e.touches[0].x,
				y: e.touches[0].y
			};
			touchs.push(point);
			this.hasDh = true;
		},
		// 画布的触摸移动手势响应
		move: function(e) {
			let point = {
				x: e.touches[0].x,
				y: e.touches[0].y
			};
			touchs.push(point);
			if (touchs.length >= 2) {
				this.draw(touchs);
			}
		},

		// 画布的触摸移动结束手势响应
		end: function(e) {
			//清空轨迹数组
			for (let i = 0; i < touchs.length; i++) {
				touchs.pop();
			}
		},

		// 画布的触摸取消响应
		cancel: function(e) {
			// console.log("触摸取消" + e)
		},

		// 画布的长按手势响应
		tap: function(e) {
			// console.log("长按手势" + e)
		},

		error: function(e) {
			// console.log("画布触摸错误" + e)
		},

		//绘制
		draw: function(touchs) {
			let point1 = touchs[0];
			let point2 = touchs[1];
			// console.log(JSON.stringify(touchs))
			content.moveTo(point1.x, point1.y);
			content.lineTo(point2.x, point2.y);
			content.stroke();
			content.draw(true);
			touchs.shift();
		},
		//清除操作
		clear: function() {
			//清除画布
			content.clearRect(0, 0, canvasw, canvash);
			content.draw(true);
			// this.close()
			this.hasDh = false;
			this.$emit('clear');
			// console.log(this.userid);
		},
		save() {
			var that = this;
			if (!this.hasDh) {
				uni.showToast({ title: '请先签字', icon: 'none' });
				return;
			}
			uni.showLoading({ title: '生成中...', mask: true });
			setTimeout(() => {
				uni.canvasToTempFilePath(
					{
						canvasId: that.canvasId,
						success: function(res) {
							that.signImage = res.tempFilePath;//base64格式
							
							uni.setStorage({
								key:'signImage',
								data:{
									signImage:that.signImage,
									idea:that.idea
								}
							})
							uni.hideLoading();
							that.hasDh = false;
							that.show = false;
						},
						fail: function(err) {
							console.log(err);
							uni.hideLoading();
						}
					},
					this
				);
			}, 100);
			this.$emit('save');
		},
		bindTextAreaBlur: function (e) {
			this.idea = e.detail.value
		}
	}
};
</script>

<style lang="scss">
	.inv-h-w {
		background-color: #ffffff;
		height: 80upx;
		display: flex;
	}
	.inv-h {
		font-size: 30upx;
		flex: 1;
		text-align: center;
		color: #c9c9c9;
		height: 80upx;
		line-height: 80upx;
	}
	.inv-h-se {
		color: #5ba7ff;
		border-bottom: 4upx solid #5ba7ff;
	}
.cat-signature.visible {
	visibility: visible;
}
.cat-signature {
	display: block;
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	overflow: hidden;
	z-index: 11;
	height: 100vh;
	visibility: hidden;
	.mask {
		display: block;
		opacity: 0;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.4);
		transition: opacity 0.3s;
	}
	.content {
		display: block;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		margin: auto;
		width: 96%;
		height: 750upx;
		background: #efeff4;
		overflow: hidden;
		border-radius: 30upx;
		box-shadow: 0px 3px 3px #333;
		// canvas
		.firstCanvas {
			background-color: #ffffff;
			width: 100%;
			height: 400upx;
		}
		// canvas

		.btns {
			margin-top: 20rpx;
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
			.button{
				border-radius: 50rpx;
			}
		}
	}
}

.visible .mask {
	display: block;
	opacity: 1;
}
.imgs{width: 500upx;height: 500upx;display: flex;margin: 0 auto;flex-wrap: wrap;}
.imgs img{width: 100%; height: 100%;}
</style>
