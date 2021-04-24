<template>
	<view class="boxa">
		<view class="top_kbox">
			<block v-for="(item, i) in newlist" :key="i">
				<view class="ibox" @tap="alertnum(i)" :class="[i == i1 ? 'actives' : '']">
					<text class="uni_14">{{ item }}</text>
					<image v-if="i != i1" class="ii" src="/static/images/choose-Cadex.png" mode=""></image>
					<image v-else class="ii" src="/static/images/choose-Cades.png" mode=""></image>
				</view>
			</block>
		</view>
		<view :class="[show ? 'list_boxs2' : 'list_boxs']">
			<view class="lione" v-if="i1 != 0">
				<block v-for="(item, i) in listchild" :key="i">
					<view class="mli" @tap="chooseOne(i)">
						<text :class="[i == i2 ? 'actives' : '']" class="uni_14">{{ item }}</text>
						<image v-if="i == i2" class="ii" src="/static/images/choose-Cadecc.png" mode=""></image>
					</view>
				</block>
			</view>
			<view class="pickdate" v-else>
				<block >
					<view class="datepicker-header">
						<view class="datepicker-header__result">
							<picker mode="date" :value="date" :start="startDate" :end="endDate" @change="bindDateChange">
								<view :class="'datepicker-header__result-text ' + (current === 0 ? 'highlight' : 'default-border')">{{date}}</view>
							</picker>
							<view class="datepicker-header__result-separator">至</view>
							<picker mode="date" :value="date" :start="startDate" :end="endDate" @change="bindDateChange1">
								<view :class="'datepicker-header__result-text ' + (current === 0 ? 'highlight' : 'default-border')">{{date1}}</view>
							</picker>
						</view>
					</view>
				</block>
			</view>
			<view class="hideA" @tap="hide"></view>
		</view>
	</view>
</template>

<script>
export default {
	props: ['list', 'arr'], //数组  arr
	data() {
		const currentDate = this.getDate({
			format: true
		});
		return {
			current: 0, //0为开始时间，1为结束时间
			i1: null,
			i2: null,
			show: false,
			listchild: [],
			newlist: this.list,
			date: '起始日期',
			date1:'截止日期',
		};
	},
	computed: {
		startDate() {
			return this.getDate('start');
		},
		endDate() {
			return this.getDate('end');
		}
	},
	methods: {
		getDate(type) {
			const date = new Date();
			let year = date.getFullYear();
			let month = date.getMonth() + 1;
			let day = date.getDate();

			if (type === 'start') {
				year = year - 60;
			} else if (type === 'end') {
				year = year + 2;
			}
			month = month > 9 ? month : '0' + month;
			day = day > 9 ? day : '0' + day;
			return `${year}-${month}-${day}`;
		},
		bindDateChange: function(e) {
			this.date = e.target.value;
			this.$emit("dateChange", e.target.value,'startTime')
		},
		bindDateChange1: function(e) {
			this.date1 = e.target.value;
			this.$emit("dateChange", e.target.value,'endTime')
		},
		alertnum(i) {
			// console.log(this.i1,i)
			// if (this.i1 != i) {
			this.listchild = [];
			this.i1 = i;
			this.listchild = this.arr[i];
			this.i2 = null;
			// console.log(this.show)
			if (this.show == true) {
				this.show = !this.show;
				this.i1 = -1;
			} else {
				this.show = !this.show;
			}
			let ins = this.listchild.indexOf(this.newlist[i]);
			if (ins > -1) {
				this.i2 = ins;
			}
			// }else{
			// 	this.show = false
			// }
		},
		chooseOne(i) {
			this.show = false;
			this.i2 = i;
			this.newlist[this.i1] = this.listchild[i];
			this.$emit('chooseLike', [this.i1, this.i2]);
			this.show = false;
			this.i1 = null;
			this.i2 = null;
		},
		hide() {
			this.show = false;
			this.i1 = null;
			this.i2 = null;
		}
	}
};
</script>

<style lang="scss" scoped>
.hideA {
	height: calc(100% - 310upx);
}

.mli {
	/* border: 1upx solid red; */
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 22upx 0;
}

.lione {
	background-color: #fff;
	height: 260upx;
	padding: 10upx 40upx;
	overflow-y: auto;
}
.pickdate{
	background-color: #fff;
	padding: 10upx 40upx;
}
.list_boxs {
	background-color: rgba(84, 80, 80, 0.48);
	position: fixed;
	height: calc(100%);
	width: 100%;
	z-index: 88;
	transition: all 0.5s;
	transform: translateY(-120%);
}
.list_boxs2 {
	background-color: rgba(84, 80, 80, 0.48);
	position: fixed;
	height: calc(100%);
	width: 100%;
	z-index: 88;
	transform: translateY(0);
	transition: all 0.5s;
}

.ii {
	width: 30upx;
	height: 30upx;
	display: block;
	margin-left: 12upx;
}

.actives {
	color: #f0ad4e;
}

.ibox {
	display: flex;
	align-items: center;
}

.top_kbox {
	display: flex;
	justify-content: space-between;
	align-items: center;
	background-color: #ffffff;
	padding: 14upx 5%;
	position: fixed;
	top: 88upx;
	width: 90%;
	z-index: 99;
	/* #ifdef APP-PLUS */
	top: 0;
	/* #endif */
}
.boxa {
	padding-top: 84upx;
	position: relative;
}
.datepicker {
	&-header {
		display: flex;
		flex-direction: column;
		padding: 25upx 50upx;
		box-sizing: border-box;
		justify-content: center;
		width: 100%;
		&__result {
			width: 100%;
			height: 65upx;
			color: #00c456;
			font-size: $uni-font-size-lg;
			display: flex;
			flex-direction: row;
			justify-content: center;
			&-text {
				flex: 1;
				text-align: center;
			}
			&-separator {
				width: 100upx;
				color: rgba(0,0,0,.6);
				font-weight: 1000;
				font-size: $uni-font-size-base;
				text-align: center;
			}
		}
	}
}
.highlight {
	color: #000;
	border-bottom: 1px solid #efeff4;
}
</style>
