<template>
	<view>
		<!-- 基本示例 -->
		<uni-popup ref="popup" :type="type" :animation="false" @change="change">
			<view class="top">
				<button size="mini" @click="cancel()">取消</button>
				<view class="select">选择下达人</view>
				<button size="mini" @click="confirm()">确定</button>
			</view>
			<scroll-view scroll-y="true" style="background-color: #FFFFFF;" :show-scrollbar="true">
				<view class="popup-content">
					<view class="uni-list">
						<checkbox-group @change="checkboxChange">
							<label class="uni-list-cell uni-list-cell-pd" v-for="(item,index) in items" :key="index">
								<view>{{ item.name }}</view>
								<view><checkbox :value="item.personInfo" :checked="item.checked" /></view>
							</label>
						</checkbox-group>
					</view>
				</view>
			</scroll-view>
		</uni-popup>
	</view>
</template>

<script>
import uniPopupDialog from '@/components/uni-popup/uni-popup-dialog.vue';
export default {
	props:['flag','person'],
	components: {
		uniPopupDialog
	},
	data() {
		return {
			type: 'top',
			items: [],
			selectPerson:[],
			cardId:[]//批量下达卡片
		};
	},
	mounted(){
		let that = this
		uni.getStorage({
			key: 'userInfo',
			success: res => {
				that.getPerson(res.data.proTimeStamp)
			}
		});
		that.$on('cardId', (res) => {
			this.cardId.push(res)
		})
	},
	methods: {
		/**
		 * 打开基础内容
		 */
		toggle(type) {
			this.type = type;
			this.$refs.popup.open();
		},
		/**
		 * popup 状态发生变化触发
		 * @param {Object} e
		 */
		change(e) {
			//弹出层关闭状态清除卡片id
			if(e.show==false){
				this.cardId = []
			}
		},
		// 取消
		cancel(){
			this.$refs.popup.close();
		},
		//确定
		confirm(){
			var that = this;
			console.log(that.cardId);
			// // 循环遍历ID
			that.selectPerson = [];
			that.items.forEach(item => {
				if (item.checked === true) {
					delete item.checked;
					that.selectPerson.push(item.personInfo);
				}
			});
			
			var selectPerson = that.selectPerson.join(",");//选择下达人的userid和手机号码
			var length = that.cardId.length;
			console.log(selectPerson);
			for (var i = 0; i < length; i++) {
				let opts = {
					url: this.api+'/message_push/messageTonotice.php',
					method: 'POST'
				}
				let param = {
					flag: this.flag,
					selectPerson:selectPerson,
					cardId:that.cardId[i]
				}
				let isLoading = false//是否需要加载动画
				this.myRequest.httpRequest (opts, param,isLoading).then(res => {
					console.log(res)
					let data = res.data
					uni.hideLoading()//隐藏加载中转圈圈
					this.isloading = false//取消遮罩层
				}, error => {
					console.log(error);
				})
			// 	//推送手机通知栏消息
				let opt = {
					url: this.api+'/message_push/messageToperson.php',
					method: 'POST'
				}
				let params = {
					flag:'concrete',
					selectPerson:selectPerson,
					cardId:that.cardId[i]
				}
				this.myRequest.httpRequest (opt, params).then(res => {
					// let data = res.data
					console.log(res);
				}, error => {
					console.log(error);
				})
				
			}
			uni.showToast({
				icon: 'success',
				position: 'bottom',
				title: '已下达'
			});
			this.$refs.popup.close();
			if(this.flag=='side'){
				that.$emit('assignside', that.cardId[0]);
			}else{
				that.$emit('refresh');//刷新
			}
		},
		//下达人多选
		checkboxChange: function(e) {
			var items = this.items,
				values = e.detail.value;
			for (var i = 0, lenI = items.length; i < lenI; ++i) {
				const item = items[i];
				if (values.includes(item.personInfo)) {
					this.$set(item, 'checked', true);
				} else {
					this.$set(item, 'checked', false);
				}
			}
		},
		//获取下达人
		getPerson(proTimeStamp){
			let opts = {
				url: this.api+'/message_push/getPerson.php',
				method: 'POST'
			}
			let param = {
				flag: this.person,
				proTimeStamp:proTimeStamp
			}
			let isLoading = false//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				// console.log(res)
				let data = res.data
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				for(var key in data)
				{
					var obj = JSON.parse(data[key]);
					var name = Object.keys(obj)[0];
					var userId = obj[name];
					let items = {userId:userId,name:name,personInfo:name+'|'+userId}
					this.items.push(items)
				}
			}, error => {
				console.log(error);
			})
		}
	},
	/**
	 * 拦截应用返回事件，仅仅 app 端生效
	 */
	onBackPress() {}
};
</script>
<style lang="scss">
.page {
	background-color: #efeff4;
}
.top {
	padding: 6px;
	display: flex;
	align-items: center;
	background-color: #efeff4;
	.select {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 50%;
	}
}
.popup-content {
	// padding: 15px;
	height: 250px;
}
/*定义滚动条高宽及背景 高宽分别对应横竖滚动条的尺寸*/
::-webkit-scrollbar {
	width: 10upx !important;
	height: 10upx !important;
	background-color: #efeff4;
}

/*定义滚动条轨道 内阴影+圆角*/
::-webkit-scrollbar-track {
	// -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #efeff4;
}

/*定义滑块 内阴影+圆角*/
::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
	background-color: #efeff4;
}
//选择下达人样式
.uni-list-cell {
	margin-left: 10%;
	width: 80%;
	display: flex;
	justify-content: space-between;
}
</style>
