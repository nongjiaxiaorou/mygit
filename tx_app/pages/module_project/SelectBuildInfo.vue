<template>
	<view class="content">
		<view>
			<view class="uni-title uni-common-pl"></view>
			<view class="uni-list">
				<view class="uni-list-cell">
					<view class="uni-list-cell-left">
						工程名称：
					</view>
					<view class="uni-list-cell-db">
						<view class="uni-input"><input v-model="projectName" /></view>
					</view>
				</view>
			</view>
		</view>

		<view class="uni-title uni-common-pl"></view>
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">
					区段栋号：
				</view>
				<view class="uni-list-cell-db">
					<view class="list-item" @tap="handleTap('picker2')">
						<view class="item-content">
							<view class="item-value">
								<!-- <text class="item-label">{{ label2 }}</text> -->
								<view v-if="!label2" class="uni-input">请选择楼层单元</view>
								<view v-if="label2" class="uni-input">{{label2}}</view>
							</view>
						</view>
						<lb-picker ref="picker2" v-model="value2" mode="multiSelector" :list="list2" :level="2" :dataset="{ name: 'label2' }"
						 :formatter="formatter" @change="handleChange2" @confirm="handleConfirm" @cancel="handleCancel">
						</lb-picker>
					</view>
				</view>
			</view>
		</view>

		<view class="uni-title uni-common-pl"></view>
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">
					区段栋号：
				</view>
				<view class="uni-list-cell-db">
					<view class="list-item" @tap="handleTap('picker3')">
						<view class="item-content">
							<view class="item-value">
								<!-- <text class="item-label">{{ label2 }}</text> -->
								<view v-if="!label3" class="uni-input">请选择区段栋号</view>
								<view v-if="label3" class="uni-input">{{label3}}</view>
							</view>
						</view>
						<lb-picker ref="picker3" v-model="value3" mode="multiSelector" :list="list3" :level="2" :dataset="{ name: 'label3' }"
						 :formatter="formatter" @change="handleChange3" @confirm="handleConfirm" @cancel="handleCancel">
						</lb-picker>
					</view>
				</view>
			</view>
		</view>

		<button type="primary" class="flex align-center justify-center button" @click="submint()">确定</button>

	</view>
</template>

<script>
	import LbPicker from '../../components/common/lb-picker'
	export default {
		components: {
			LbPicker
		},
		data() {
			return {
				value2: [],
				label2: '',
				list2: [],
				value3: [],
				label3: '',
				list3: [],
				level: '',
				userInfo: [],
				proTimeStamp: '',
				projectName: '',
				modulePath: ''
			}
		},
		mounted() {

		},
		onReady() {
			this.$nextTick(() => {
				// 此处可以调用getColumnsInfo方法获取默认值的选项信息
				const info2 = this.$refs.picker2.getColumnsInfo(this.value2)
				const info3 = this.$refs.picker3.getColumnsInfo(this.value3)
				this.label2 = info2.item.map(m => m.label).join('-')
				this.label3 = info3.item.map(m => m.label).join('-')
			})
		},
		onLoad: function(option) {
			this.modulePath = option.modulePath
			uni.getStorage({
				key: 'buildInfo',
				success: (res) => { //有缓存 直接跳转到对应模块路径
					console.log(res)
				}
			})
			uni.getStorage({
				key: 'userInfo',
				success: (res) => {
					// console.log(res.data)
					this.userInfo = res.data
					this.level = res.data.level
					this.projectName = res.data.projectName
					this.proTimeStamp = res.data.proTimeStamp

					//异步获取楼层信息
					let opts = {
						url: this.api + '/module_project/SelectBuildInfo.php',
						method: 'POST'
					}
					let param = {
						flag: 'getSectionBuild',
						proTimeStamp: this.proTimeStamp
					}
					let isLoading = false //是否需要加载动画       
					this.myRequest.httpRequest(opts, param, isLoading).then(res => {
						console.log(res)
						let sectionArr = res.data.section
						let buildArr = res.data.build
						let child_arry = []
						for (let i = 0; i < sectionArr.length; i++) {
							child_arry = []
							for (let j = 0; j < buildArr[i].split(',').length; j++) {
								child_arry.push({
									value: buildArr[i].split(',')[j],
									label: buildArr[i].split(',')[j],
								})
							}
							this.list2.push({
								value: sectionArr[i],
								label: sectionArr[i],
								children: child_arry
							})
						}
					}, error => {
						console.log(error);
					})
				}
			})
		},
		methods: {
			handleTap(picker) {
				this.$refs[picker].show()
			},
			formatter({
				item,
				rowIndex,
				columnIndex
			}) {
				return `${item.value}`
			},
			handleChange2(e) {
				this.label2 = e.value[0] + "-" + e.value[1]
				//根据栋号异步获取楼层单元
				let opts = {
					url: this.api + '/module_project/SelectBuildInfo.php',
					method: 'POST'
				}
				let param = {
					flag: 'getFloorUnit',
					proTimeStamp: this.proTimeStamp,
					build: e.value[1],
				}
				let isLoading = false //是否需要加载动画       
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					let unitName = res.data.unitName[0].split('||')
					let undergroundNumber = res.data.undergroundNumber
					let abovegroundNumber = res.data.abovegroundNumber
					// console.log(undergroundNumber+" "+abovegroundNumber+" "+unitName.split('||'))
					let floorArr = this.commonFunc.toChinesNum_floor(undergroundNumber, abovegroundNumber)
					let unitArr = unitName
					let child_arry = []
					for (let i = 0; i < floorArr.length; i++) {
						child_arry = []
						for (let j = 0; j < unitArr.length; j++) {
							child_arry.push({
								value: unitArr[j],
								label: unitArr[j]
							})
						}
						this.list3.push({
							value: floorArr[i],
							label: floorArr[i],
							children: child_arry,
						})
					}
				}, error => {
					console.log(error);
				})
			},
			handleChange3(e) {
				this.label3 = e.value[0] + "-" + e.value[1]
				console.log('change::', e.value)
			},
			handleConfirm(e) {},
			handleCancel(e) {
				console.log('cancel::', e)
			},
			//确定提交
			submint() {
				// console.log(this.label2.split('-')[0])
				let data = {
					section: this.label2.split('-')[0],
					build: this.label2.split('-')[1],
					floor: this.label3.split('-')[0],
					unit: this.label3.split('-')[1]
				}
				uni.setStorage({
					key: 'buildInfo',
					data: data
				})
				uni.navigateTo({
					url: this.modulePath
				})
			},
		}
	}
</script>

<style>
	page {
		background-color: #F5F4F2;
	}

	uni-picker-view {
		display: block;
	}

	uni-picker-view .uni-picker-view-wrapper {
		display: flex;
		position: relative;
		overflow: hidden;
		height: 100%;
		background-color: white;
	}

	uni-picker-view[hidden] {
		display: none;
	}

	picker-view {
		width: 100%;
		height: 600upx;
		margin-top: 20upx;
	}
</style>
