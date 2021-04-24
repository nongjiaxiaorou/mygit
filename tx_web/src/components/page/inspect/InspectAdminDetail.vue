<template>
	<div id="ProTable" class="basic1 container">
		<el-form :model="formDate" label-width="120px">
			<div class="flex" style="width: 80%;">
				<el-form-item label="检查对象：" prop="inspectLevel" class="flex-1 px-2">
					<el-input v-model="formDate.inspectObj"></el-input>
				</el-form-item>
				<el-form-item label="检查层级：" prop="inspectLevel" class="flex-1 px-2">
					<el-input v-model="formDate.inspectLevel"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="检查班组：" prop="inspectLevel" class="flex-1 px-2">
					<el-input v-model="formDate.groupName"></el-input>
				</el-form-item>
				<el-form-item label="组长名称：" prop="inspectLevel" class="flex-1 px-2">
					<el-input v-model="formDate.groupLeader"></el-input>
				</el-form-item>
			</div>
		</el-form>
		<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
			<div class="flex">
				<el-form-item label="质量隐患期限整改通知单：" ref="inspectLevel1" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url1" :preview-src-list="srcList1">
					</el-image>
				</el-form-item>
				<el-form-item label="质量隐患期限整改通知单回复：" ref="inspectLevel2" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url2" :preview-src-list="srcList2">
					</el-image>
				</el-form-item>
			</div>
			<div class="flex">
				<el-form-item label="施工方案及现场问题整改通知单：" ref="inspectLevel3" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url3" :preview-src-list="srcList3">
					</el-image>
				</el-form-item>
				<el-form-item label="施工方案及现场问题整改通知单回复：" ref="inspectLevel4" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url4" :preview-src-list="srcList4">
					</el-image>
				</el-form-item>
			</div>
			<div class="flex">
				<el-form-item label="资料问题通知单：" ref="inspectLevel5" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url5" :preview-src-list="srcList5">
					</el-image>
				</el-form-item>
				<el-form-item label="资料问题通知单回复：" ref="inspectLevel6" class="flex-1 px-2">
					<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url6" :preview-src-list="srcList6">
					</el-image>
				</el-form-item>
			</div>
		</el-form>
	</div>
	
</template>

<script>
	export default {
		data() {
			return {
				formDate:{
					inspectObj:'',
					groupName:'',
					inspectLevel:'',
					groupLeader:''
				},
				inspectAdminData:{},
				taskTimeStamp:'',
				url1: this.$adminImgUrl + '/null.jpg',
				srcList1: [],
				url2: this.$adminImgUrl + '/null.jpg',
				srcList2: [],
				url3: this.$adminImgUrl + '/null.jpg',
				srcList3: [],
				url4: this.$adminImgUrl + '/null.jpg',
				srcList4: [],
				url5: this.$adminImgUrl + '/null.jpg',
				srcList5: [],
				url6: this.$adminImgUrl + '/null.jpg',
				srcList6: [],
			}
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('inspectAdminData')
			this.formDate.inspectObj = JSON.parse(sessionData).inspectObj
			this.formDate.groupName = JSON.parse(sessionData).groupName
			this.inspectAdminData = JSON.parse(sessionData)
			this.taskTimeStamp = JSON.parse(sessionData).taskTimeStamp
			this.getAdminData()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('inspectAdminData')
				this.formDate.inspectObj = JSON.parse(sessionData).inspectObj
				this.formDate.groupName = JSON.parse(sessionData).groupName
				this.inspectAdminData = JSON.parse(sessionData)
				this.taskTimeStamp = JSON.parse(sessionData).taskTimeStamp
				this.getAdminData()
			},
			//获取巡查管理信息
			getAdminData(isLoading) {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getAdminData')
				fd.append('taskTimeStamp', that.taskTimeStamp)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#ProTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/inspect/InspectAdminDetail.php`, fd).then(res => {
					// console.log(res.data.data)
					if(res.data.data != ''){
						this.formDate.inspectLevel = res.data.data.companyRank
						this.formDate.groupLeader = res.data.data.groupLeader
					}else{
						loading.close();
						return
					}
					if(res.data.data.qualityPic != ''){
						let qualityArr = res.data.data.qualityPic.split('||')
						let qualityLen = qualityArr.length-1
						for(let i=0;i<qualityLen;i++){
							this.srcList1.push(this.$adminImgUrl +'/app_pic/scorePic/'+qualityArr[i])
						}
						this.url1 = this.$adminImgUrl +'/app_pic/scorePic/'+ qualityArr[0]
					}
					
					if(res.data.data.qualityReplyPic != ''){
						let qualityReplyArr = res.data.data.qualityReplyPic.split('||')
						let qualityReplyLen = qualityReplyArr.length-1
						for(let i=0;i<qualityReplyLen;i++){
							this.srcList2.push(this.$adminImgUrl +'/app_pic/scorePic/'+qualityReplyArr[i])
						}
						this.url2 = this.$adminImgUrl +'/app_pic/scorePic/'+ qualityReplyArr[0]
					}
					
					if(res.data.data.constructPic != ''){
						let constructArr = res.data.data.constructPic.split('||')
						let constructLen = constructArr.length-1
						for(let i=0;i<constructLen;i++){
							this.srcList3.push(this.$adminImgUrl +'/app_pic/scorePic/'+constructArr[i])
						}
						this.url3 = this.$adminImgUrl +'/app_pic/scorePic/'+ constructArr[0]
					}
					
					if(res.data.data.constructReplyPic != ''){
						let constructReplyArr = res.data.data.constructReplyPic.split('||')
						let constructReplyLen = constructReplyArr.length-1
						for(let i=0;i<constructReplyLen;i++){
							this.srcList4.push(this.$adminImgUrl +'/app_pic/scorePic/'+constructReplyArr[i])
						}
						this.url4 = this.$adminImgUrl +'/app_pic/scorePic/'+ constructReplyArr[0]
					}
					
					if(res.data.data.filePic != ''){
						let fileArr = res.data.data.filePic.split('||')
						let fileLen = fileArr.length-1
						for(let i=0;i<fileLen;i++){
							this.srcList5.push(this.$adminImgUrl +'/app_pic/scorePic/'+fileArr[i])
						}
						this.url5 = this.$adminImgUrl +'/app_pic/scorePic/'+ fileArr[0]
					}
					
					if(res.data.data.fileReplyPic != ''){
						let fileReplyArr = res.data.data.fileReplyPic.split('||')
						let fileReplyLen = fileReplyArr.length-1
						for(let i=0;i<fileReplyLen;i++){
							this.srcList6.push(this.$adminImgUrl +'/app_pic/scorePic/'+fileReplyArr[i])
						}
						this.url6 = this.$adminImgUrl +'/app_pic/scorePic/'+ fileReplyArr[0]
					}
					
					loading.close();
				}).catch(() => {
					console.error("获取失败")
				})
			},
		}
	}
</script>

<style lang="scss">
	.basic1{
		margin: 2%;
		padding: 2%;
		padding-left: 10%;
		.el-image-viewer__close {
			top: 40px;
			right: 40px;
			width: 40px;
			height: 40px;
			font-size: 40px;
			background-color: aliceblue;
		}
	}
	
</style>
