<template>
	<view class="content">
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">工程名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.projectName" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">责任单位：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.company" /></view>
			</view>
		</view>

		<!-- <view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">目前状态：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="Pouring_Data.state" /></view>
			</view>
		</view> -->

		<!-- 浇筑时间  -->
		<pick-date class="mt-1" :datename="datename" @date="getdate"></pick-date>


		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑部位数:</view>
				<view class="uni-list-cell-db">
					<picker @change="bindPickerChange" :value="index" :range="pournum">
						<view class="uni-input">{{ pournum[index] }}</view>
					</picker>
				</view>
			</view>
		</view>
		
		<view v-for="(item,index) of pournum_select" :key="index">
			
			<view class="uni-list mt-1">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">砼等级{{index+1}}：</view>
					<view class="uni-list-cell-db">
						<picker :id="index" @change="Picker" :value="index2" :range="concrete_grade">
							<view class="uni-input">{{ concrete_select[index] }}</view>
						</picker>
					</view>
				</view>
			</view>
			
			
			<view class="uni-list mt-1">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">浇筑部位{{index+1}}：</view>
					<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="pouring_position[index]" /></view>
				</view>
			</view>
			
			<view class="uni-list mt-1">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">浇筑方量{{index+1}}：</view>
					<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="pouring_volume[index]" placeholder="请输入浇筑方量" /></view>
				</view>
			</view>
			
		</view>

		
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">主管施工员:</view>
				<view class="uni-list-cell-db">
					<picker @change="bindPicker" :value="index1" :range="builder">
						<view class="uni-input">{{ builder[index1] }}</view>
					</picker>
				</view>
			</view>
		</view>

		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="confirm()">确定</button>
	</view>
</template>

<script>
import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
export default {
	components: {
		'pick-date': pickdate
	},
	data() {
		return {
			isconfirm:false,
			Pouring_Data: {
				flag:'create_pour',
				section:'',//区号
				build:'',//栋号
				floor:'',//楼层
				unit:'',//单元
				projectName: '',//工程名称
				company: '',//责任单位
				state: '',//目前状态
				pouringDate: '',//浇筑时间
				concreteGrade:'',//砼强度等级拼接
				pouringPosition:'',//浇筑部位拼接
				pouringVolume:'',//浇筑方量拼接
				builder:'',
				projectId:'',
				createrId:'',
				creater:''
			},
			buildSelData: {},
			datename: '浇筑时间：',
			pournum_select:[''],//选择的浇筑部位数
			concrete_select:[],//砼等级
			pouring_position:[],//浇筑部位
			pouring_volume:[],//浇筑方量
			index:0,
			pournum: ['1', '2', '3', '4', '5', '6', '7', '9', '10'],
			index1:'0',
			builder: [''],//主管施工员
			index2:'0',
			concrete_grade: ['C15','C20','C25','C30','C35','C40','C45','C50','C55','C60'],//混凝土强度等级
		};
	},
	onLoad() {
		uni.getStorage({
			key:'userInfo',
			success:(res)=>{
				// console.log(proTimeStamp);
				this.Pouring_Data.createrId = res.data.userId
				this.Pouring_Data.creater = res.data.username
				this.Pouring_Data.projectName = res.data.projectName
				this.Pouring_Data.company = res.data.companyName
				this.Pouring_Data.projectId = res.data.projectId
				this.getbuilder(res.data.proTimeStamp)
			}
		})
		uni.getStorage({
			key:'buildInfo',
			success:(res)=>{
				this.Pouring_Data.section = res.data.section
				this.Pouring_Data.build = res.data.build
				this.Pouring_Data.floor = res.data.floor
				this.Pouring_Data.unitName = res.data.unit
				this.pouring_position[0] = res.data.build+res.data.floor+res.data.unit
			}
		})
	},
	methods: {
		//获取施工员
		getbuilder(proTimeStamp){
			let opts = {
				url: this.api+'/module_project/SpecialAdmin/pour.php',
				method: 'POST'
			}
			let param = {
				flag: 'getbuilder',
				proTimeStamp:proTimeStamp
			}
			let isLoading = true//是否需要加载动画
			this.myRequest.httpRequest (opts, param,isLoading).then(res => {
				console.log(res)
				let data = res.data
				uni.hideLoading()//隐藏加载中转圈圈
				this.isloading = false//取消遮罩层
				for(var key in data)
				{
					var obj = JSON.parse(data[key]);
					var name = Object.keys(obj)[0];
					console.log(name);
					this.builder.push(name)
				}
			}, error => {
				console.log(error);
			})
		},
		//选择时间子组件传时间
		getdate(data){
			this.Pouring_Data.pouringDate = data
		},
		//提交
		confirm() {
			this.Pouring_Data.concreteGrade = this.concrete_select.join('/')
			this.Pouring_Data.pouringPosition = this.pouring_position.join('/')
			this.Pouring_Data.pouringVolume = this.pouring_volume.join('/')
			if (this.Pouring_Data.concreteGrade== ""||this.Pouring_Data.pouringPosition==''||this.Pouring_Data.pouringVolume=='') {
				uni.showToast({
					icon: 'none',
					position: 'bottom',
					title: '信息未填完整'
				});
				return;
			}else{
				this.isconfirm = true
			}
			uni.request({
				url: this.api + '/module_project/SpecialAdmin/pour.php',
				data: this.Pouring_Data,
				method: 'POST',
				sslVerify:false,
				dataType: 'json',
				header: {
					'content-type': 'application/x-www-form-urlencoded', //level头信息
				},
				success: (res) => {
					if(res.data.status='success'){
						uni.showToast({
							icon: 'success',
							position: 'bottom',
							title: '保存成功'
						});
						setTimeout(function() {
							uni.navigateBack()
						}, 1000);
					}							
				},
				fail: (error) => {
					console.log(error);
				}
			});
		},
		//选择浇筑部位数对应生成多部位
		bindPickerChange: function(e) {
			let position = this.Pouring_Data.build+this.Pouring_Data.floor+this.Pouring_Data.unitName
			this.index = e.target.value
			var num = this.pournum[this.index]
			this.pournum_select = []
			this.pouring_position = []
			for (var i=1;i<=num;i++) {
				this.pournum_select.push('')
				this.pouring_position.push(position)
			}
		},
		//主管施工员
		bindPicker: function(e) {
			this.index1 = e.target.value
			this.Pouring_Data.builder = this.builder[this.index1]
		},
		//砼强度等级
		Picker: function(e) {
			let num = e.target.id;//表示砼强度几
			this.concrete_select.splice(num,1,this.concrete_grade[e.target.value]);
		},
		
	}
};
</script>

<style lang="scss">
page {
	padding-top: 10rpx;
	background-color: #efeff4;
}
</style>
