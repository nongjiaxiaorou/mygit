<template>
	<view class="content">
		<view class="uni-list">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">质量缺陷：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.violationItem" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">发现时间：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.inspectDate" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">整改期限：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.endTime" /></view>
			</view>
		</view>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">问题性质：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.majorCate" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查对象：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.object" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">缺陷图片：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" @click="postPicture('defect')" disabled="true" v-model="formData.defectPicNum" /></view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">检查部位：</view>
				<view class="uni-list-cell-db">
					<ld-select :multiple="true" :list="options1" :isAllSelect='isAllSelect' list-key="label" value-key="value" placeholder="请选择" clearable
					 v-model="formData.inspectPosition" @change="selectInsPos"></ld-select>
				</view>
			</view>
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">回复意见：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.responses" /></view>
			</view>
		</view>
		<pick-date class="mt-1" datename="回复日期：" @date="getReplyDate"></pick-date>

		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">回复图片：</view>
				<view class="uni-list-cell-db"><input class="uni-input" type="text" @click="postPicture('reply')" disabled="true" v-model="formData.replyPicNum" /></view>
			</view>
		</view>
		<view class="uni-list mt-1">
			<view class="uni-list-cell">
				<view class="uni-list-cell-left">整改部位：</view>
				<!-- <view class="uni-list-cell-db"><input class="uni-input" type="text" v-model="formData.rectifyPosition" /></view> -->
				<view class="uni-list-cell-db">
					<ld-select2 :multiple="true" :list="options2" :isAllSelect='isAllSelect' list-key="label" value-key="value" placeholder="请选择" clearable
					 v-model="formData.rectifyPosition" @change="selectRecPos"></ld-select2>
				</view>
			</view>
		</view>
		<button :disabled="isconfirm" type="primary" class="flex align-center justify-center button" @click="submit()">确定</button>
	</view>
</template>

<script>
	import pickdate from '@/components/common/pick-date/pick-date.vue'; //日期选择器
	import ldSelect from '@/components/common/ld-select/ld-select.vue'
	import ldSelect2 from '@/components/common/ld-select/ld-select2.vue'
	export default {
		props: {},
		components: {
			'pick-date': pickdate,
			ldSelect,
			ldSelect2
		},
		data() {
			return {
				isconfirm: false,
				formData: {
					violationItem: '基底未达到设计土层',
					inspectDate: '', //发现时间
					endTime: '', //截止日期
					majorCate: '', //问题性质
					object: '', //检查对象
					defectPicNum: '选中了0张图片', //缺陷图片数量
					inspectPosition: [], //检查部位
					responses: '该质量缺陷已完成整改', //回复意见
					getReplyDate: '', //回复日期
					replyPicNum: '选中了0张图片', //回复图片数量
					rectifyPosition: [], //整改部位
					id: '',
					timeStamp:''
				},
				currentData: [],
				pournum: ['1', '2', '3', '4', '5', '6', '7', '9', '10'],
				options1: [],
				options2: [],
				imageList: [],
				defectPicList: [],
				replyPicList: [],
				uploadFlag: '',
				isAllSelect:false
			};
		},
		onLoad: function(option) {
			// console.log(JSON.parse(option.data))
			this.currentData = JSON.parse(option.data)
			this.formData.endTime = this.currentData.endTime + " 00:00:00"
			this.formData.violationItem = this.currentData.defect
			this.formData.inspectDate = this.currentData.createTime
			this.formData.majorCate = this.currentData.majorCate
			this.formData.id = this.currentData.id
			this.formData.object = this.currentData.object
			this.formData.timeStamp = this.currentData.timeStamp
			// console.log(this.formData.id)
		},
		onShow() {
			uni.$on('updateDefect',res=>{
				this.getImageList()
				uni.$off('updateDefect')
			})
		},
		watch:{
			options1:'changeOption2'
		},
		mounted() {
			this.getImageList()
			this.getDefectDetail()
		},
		methods: {
			//改变整改部位
			changeOption2(e){
				this.options2 = e
				// console.log(this.options2)
			},
			//提交
			submit() {
				// console.log(this.formData.id)
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/DefectDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'saveDefectDetail',
					formData: JSON.stringify(this.formData),
					id: this.formData.id
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res)
					uni.$emit('updateNotice')
					uni.navigateBack()
				}, error => {

				})
			},
			//获取缺陷详情
			getDefectDetail(){
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/DefectDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getDefectDetail',
					id: this.formData.id
				}
				let isLoading = false
				this.myRequest.httpRequest(opts,param,isLoading).then(res=>{
					// console.log(res.data.inspectPosition[0])
					if(res.data.inspectPosition[0] !=''){
						let inspectArr = res.data.inspectPosition[0].split("||")
						this.options1 = []
						this.isAllSelect = true
						for(let i=0;i<inspectArr.length;i++){
							this.options1.push({
								value:inspectArr[i],
								label:inspectArr[i]
							})
						}
					}else{
						this.getInspectPos()
					}
					if(res.data.rectifyPosition[0] !=null){
						let replyArr = res.data.rectifyPosition[0].split("||")
						this.options2 = []
						this.isAllSelect = true
						for(let i=0;i<replyArr.length;i++){
							this.options2.push({
								value:replyArr[i],
								label:replyArr[i]
							})
						}
					}
				},error=>{
					
				})
			},
			//获取检查部位
			getInspectPos() {
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/DefectDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getInspectPos',
					projectName: this.formData.object
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data.build)
					var length = res.data.id.length
					var dyLength = res.data.id.length
					// console.log(length)
					var dh_num = new Array()
					var dx_num = new Array()
					var ds_num = new Array()
					var dy_num = new Array()
					this.options1 = []
					if (length >= 1) {
						for (var i = 0; i < length; i++) {
							dh_num[i] = res.data.build[i]
							dx_num[i] = res.data.undergroundNumber[i]
							ds_num[i] = res.data.abovegroundNumber[i]
							dy_num[i] = res.data.unitName[i].split('||')
						}
						// console.log(" 栋号："+a+" 地下层数："+b+" 地上层数："+c+" 单元："+d)
						for (var i = 0; i < length; i++) {
							// console.log(dy_num[i][0])
							var new_arr = this.commonFunc.toChinesNum_floor(dx_num[i], ds_num[i])
							var num = Number(dx_num[i]) + Number(ds_num[i])
							// console.log(num)
							for (var k = 0; k < dy_num[i].length; k++) {
								var dy = dy_num[i][k]
								for (var j = 0; j < num; j++) {
									var jcbw = dh_num[i] + "-" + new_arr[j] + "-" + dy
									this.options1.push({
										value: jcbw,
										label: jcbw
									})
								}
							}
						}
					}

				}, error => {

				})
			},
			//获取图片
			getImageList() {
				let opts = {
					url: this.api + '/module_project/WeeklyInspect/DefectDetail.php',
					method: 'POST'
				}
				let param = {
					flag: 'getImageList',
					id: this.formData.id
				}
				let isLoading = false
				this.myRequest.httpRequest(opts, param, isLoading).then(res => {
					// console.log(res.data.replyPicture.length)
					this.defectPicList = []
					this.replyPicList = []
					if (res.data.defectPicture != '') {
						let defectArr = res.data.defectPicture[0].split('||')
						let defectLen = defectArr.length-1
						this.formData.defectPicNum = '选中了' + defectLen + '张图片'
						for (let i = 0; i < defectLen; i++) {
							defectArr[i] = this.imageUrl+'/defectPic/'+defectArr[i]
							this.defectPicList.push(defectArr[i])
						}
					}
					if (res.data.replyPicture != '') {
						let replyArr = res.data.replyPicture[0].split('||')
						let replyLen = replyArr.length-1
						this.formData.replyPicNum = '选中了' + replyLen + '张图片'
						for (let i = 0; i < replyLen; i++) {
							replyArr[i] = this.imageUrl+'/replyPic/'+replyArr[i]
							this.replyPicList.push(replyArr[i])
						}
					}


				}, error => {

				})
			},
			//上传图片
			postPicture(flag) {
				if (flag == 'defect') {
					this.imageList = JSON.stringify(this.defectPicList)
					this.uploadFlag = 'defect'
				} else {
					this.imageList = JSON.stringify(this.replyPicList)
					this.uploadFlag = 'reply'
				}
				// console.log(this.imageList)
				uni.navigateTo({
					url: `UploadPicture?id=${this.formData.id}&imageList=${this.imageList}&flag=${this.uploadFlag}&timeStamp=${this.formData.timeStamp}`
				})
			},
			//获取检查部位
			selectInsPos(e) {
				// console.log(e)
			},
			//回复日期
			getReplyDate() {

			},
			//整改部位
			selectRecPos(e) {
				// console.log(e)
			},
		}
	};
</script>

<style lang="scss">
	page {
		background-color: #f8f8f8;
	}

	.row {
		display: flex;
		align-items: center;
		position: relative;
		padding: 0 30upx;
		height: 90upx;
		background: #fff;

		.tit {
			flex-shrink: 0;
			// width: 120upx;
			font-size: 30upx;
		}

		.input {
			flex: 1;
			font-size: 30upx;
		}
	}
</style>
