<template>
	<view class="content">
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">工程名称：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.projectName" /></view>
			</view>
		</view>




		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">拆模部位:</view>
				<view class="uni-list-cell-db">
					<picker @change="bindPickerChange" :value="index" :range="removalPosition">
						<view class="uni-input">{{ removalPosition[index] }}</view>
					</picker>
				</view>
			</view>
		</view>
		
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">砼强度等级：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.concreteGrade" /></view>
			</view>
		</view>
		
		
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">浇筑日期：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="removal_Data.pouringDate" /></view>
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
			removal_Data: {
				flag:'create_removal',
				projectName: '',//工程名称
				company: '',//责任单位
				section:'',//区号
				build:'',//栋号
				floor:'',//楼层
				unitName:'',//单元
				pouringDate:'',
				concreteGrade:'',
				removalPosition:'',
				projectId:'',
				createrId:'',
				creater:''
			},
			index:0,
			removalPosition: ['选择拆模部位'],
		};
	},
	onLoad() {
		uni.getStorage({
			key:'userInfo',
			success:(res)=>{
				this.removal_Data.projectName = res.data.projectName
				this.removal_Data.company = res.data.companyName
				this.removal_Data.projectId = res.data.projectId
				//获取拆模部位
					uni.request({
						url: this.api + '/module_project/SpecialAdmin/removal.php',
						data: {
							flag: 'removalPosition',
							projectId:res.data.projectId
						},
						method: 'POST',
						sslVerify:false,
						dataType: 'json',
						header: {
							'content-type': 'application/x-www-form-urlencoded', //level头信息
						},
						success: (res) => {
							if(res.data.status='success'){
								let data = res.data.data
								let length = data.length
								for (let i=0;i<length;i++) {
									var pouringPosition = data[i].pouringPosition
									var removalPosition = pouringPosition.split("/");
									for(let k = 0;k<removalPosition.length;k++){
										this.removalPosition.push(removalPosition[k])
									}
								}
								
							}							
						},
						fail: (error) => {
							console.log(error);
						}
					});
			}
		})
		uni.getStorage({
			key:'userInfo',
			success:(res)=>{
				this.removal_Data.createrId = res.data.userId
				this.removal_Data.creater = res.data.username
			}
		})
		uni.getStorage({
			key:'buildInfo',
			success:(res)=>{
				this.removal_Data.section = res.data.section
				this.removal_Data.build = res.data.build
				this.removal_Data.floor = res.data.floor
				this.removal_Data.unitName = res.data.unit
			}
		})
	},
	methods: {
		//提交
		confirm() {
			if (this.index== "") {
				uni.showToast({
					icon: 'none',
					position: 'bottom',
					title: '未选择拆模部位'
				});
				return;
			}else{
				this.isconfirm = true
			}
			console.log(this.removal_Data);
			uni.request({
				url: this.api + '/module_project/SpecialAdmin/removal.php',
				data: this.removal_Data,
				method: 'POST',
				sslVerify:false,
				dataType: 'json',
				header: {
					'content-type': 'application/x-www-form-urlencoded', //level头信息
				},
				success: (res) => {
					console.log(res);
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
		// 选择拆模部位对应生成砼强度和浇筑时间
		bindPickerChange: function(e) {
			this.index = e.target.value
			this.removal_Data.removalPosition = this.removalPosition[this.index]
			uni.request({
				url: this.api + '/module_project/SpecialAdmin/removal.php',
				data: {
					flag: 'getConcreteTime',
					projectId:this.removal_Data.projectId,
					removalPosition:this.removal_Data.removalPosition
				},
				method: 'POST',
				sslVerify:false,
				dataType: 'json',
				header: {
					'content-type': 'application/x-www-form-urlencoded', //level头信息
				},
				success: (res) => {
					if(res.data.status='success'){
						let data = res.data.data
						let length = data.length
						this.removal_Data.pouringDate = data[0].pouringDate
						this.removal_Data.concreteGrade = data[0].concreteGrade		
					}							
				},
				fail: (error) => {
					console.log(error);
				}
			});
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
