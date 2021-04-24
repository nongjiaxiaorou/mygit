<template>
	<div id="DataTable" class="basic1 container">
		<el-tabs v-model="activeName" type="card" @tab-click="handleClick">
			<el-tab-pane label="巡查信息" name="first">
				<el-form :model="formDate" label-width="120px">
					<div class="flex" style="width: 80%;">
						<el-form-item label="通知单编号：" prop="noticeNumber" class="flex-1 px-2">
							<el-input v-model="formDate.inspectCode"></el-input>
						</el-form-item>
						<el-form-item label="工程名称：" prop="rectificationObj" class="flex-1 px-2">
							<el-input v-model="formDate.projectName"></el-input>
						</el-form-item>
					</div>
					<div class="flex" style="width: 80%;">
						<el-form-item label="检查层级：" prop="inspectLevel" class="flex-1 px-2">
							<el-input v-model="formDate.inspectLevel"></el-input>
						</el-form-item>
						<el-form-item label="楼层信息：" prop="inspectPosition" class="flex-1 px-2">
							<el-input v-model="formDate.inspectPosition"></el-input>
						</el-form-item>
					</div>
					<div class="flex" style="width: 80%;">
						<el-form-item label="检查类型：" prop="inspectTeam" class="flex-1 px-2">
							<el-input v-model="formDate.inspectCate"></el-input>
						</el-form-item>
						<el-form-item label="检查单位：" prop="teamLeader" class="flex-1 px-2">
							<el-input v-model="formDate.inspectUnit"></el-input>
						</el-form-item>
					</div>
					<div class="flex" style="width: 80%;">
						<el-form-item label="违章大类：" prop="inspectSendPerson" class="flex-1 px-2">
							<el-input v-model="formDate.majorCate"></el-input>
						</el-form-item>
						<el-form-item label="违章状态：" prop="inspectSendDate" class="flex-1 px-2">
							<el-input v-model="formDate.noticeState"></el-input>
						</el-form-item>
					</div>
					<div class="flex" style="width: 80%;">
						<el-form-item label="检查日期：" prop="inspectDate" class="flex-1 px-2">
							<el-input v-model="formDate.inspectDate"></el-input>
						</el-form-item>
						<el-form-item label="截止日期：" prop="endDate" class="flex-1 px-2">
							<el-input v-model="formDate.endTime"></el-input>
						</el-form-item>
					</div>
				</el-form>
				<div>
					<div style="width: 100%;">
						<el-form label-width="120px" style="margin-right: 4%;margin-top: 2%;">
							<div class="flex align-center justify-center">
								<div class="flex-1" style="text-align: center;">缺陷内容</div>
								<div class="flex-1" style="text-align: center;">缺陷图片</div>
								<div class="flex-1" style="text-align: center;">回复图片</div>
							</div>
							<div class="flex align-center justify-center" v-for="(item,index) in defectList" :key="index">
								<div class="flex-1" style="text-align: center;">{{item.defect}}</div>
								<div class="flex-1" style="text-align: center;"><el-image style="width: 100px; height: 100px;" :src="item.defectUrl1" :preview-src-list="item.defectPic">
								</el-image></div>
								<div class="flex-1" style="text-align: center;"><el-image style="width: 100px; height: 100px;" :src="item.replyUrl1" :preview-src-list="item.replyPic">
								</el-image></div>
							</div>
						</el-form>
					</div>
				</div>
			</el-tab-pane>
			<el-tab-pane label="资料照片" name="second">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
					<div class="flex">
						<el-form-item label="现场照片：" ref="inspectLevel1" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url1" :preview-src-list="srcList1">
							</el-image>
						</el-form-item>
						<el-form-item label="会议照片：" ref="inspectLevel2" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url2" :preview-src-list="srcList2">
							</el-image>
						</el-form-item>
					</div>
					<div class="flex">
						<el-form-item label="会议纪要：" ref="inspectLevel3" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url3" :preview-src-list="srcList3">
							</el-image>
						</el-form-item>
						<el-form-item label="其它照片：" ref="inspectLevel4" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url4" :preview-src-list="srcList4">
							</el-image>
						</el-form-item>
					</div>
				</el-form>
			</el-tab-pane>
		</el-tabs>
		
	</div>
	
</template>

<script>
	import VmCard from '@/components/common/vm-card.vue'
	import ElImageViewer from 'element-ui/packages/image/src/image-viewer'
	export default {
		components: {
		  VmCard,
		  ElImageViewer
		},
		data() {
			return {
				formDate:{},
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
				noticeState: '',
				showViewer: false,
				defectList: [],
				showViewerStr: '',
				noticeTimeStamp: '',
				activeName:'first'
			}
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('sceneInsData')
			sessionData = JSON.parse(sessionData)
			this.noticeTimeStamp = sessionData.noticeTimeStamp
			this.getNoticeDetail()
			this.getFilePicture()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('sceneInsData')
				sessionData = JSON.parse(sessionData)
				this.noticeTimeStamp = sessionData.noticeTimeStamp
				this.getNoticeDetail()
				this.getFilePicture()
			},
			//获取检查通知单详情
			getNoticeDetail() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getNoticeDetail')
				fd.append('noticeTimeStamp', that.noticeTimeStamp)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/datacheck/WeeklyInspect/WeeklySceneInspect.php`, fd).then(res => {
					// console.log(res.data.data)
					if(res.data.code){
						that.formDate = res.data.data
						that.defectList = []
						if(res.data.data.defect != undefined){
							let oldArr = res.data.data.defect
							for(var i=0;i<oldArr.length;i++){
								let defectPic = []
								let defectUrl1 = ''
								let replyPic = []
								let replyUrl1 = ''
								if(res.data.data.defectPicture[i] != ''){
									for(let j =0;j<res.data.data.defectPicture[i].split('||').length-1;j++){
										defectPic.push(that.$adminImgUrl +'/app_pic/defectPic/'+res.data.data.defectPicture[i].split('||')[j])
									}
									defectUrl1 = defectPic[0]
								}else{
									defectUrl1 = this.$adminImgUrl + '/null.jpg'
								}
								if(res.data.data.replyPicture[i] != null){
									for(let j =0;j<res.data.data.replyPicture[i].split('||').length-1;j++){
										replyPic.push(that.$adminImgUrl +'/app_pic/replyPic/'+res.data.data.replyPicture[i].split('||')[j])
									}
									replyUrl1 = replyPic[0]
								}else{
									replyUrl1 = this.$adminImgUrl + '/null.jpg'
								}
								that.defectList.push({
									defect:oldArr[i],
									defectPic:defectPic,
									defectUrl1:defectUrl1,
									replyPic:replyPic,
									replyUrl1:replyUrl1
								})
							}
						}
					}
					loading.close();
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//获取资料照片
			getFilePicture(){
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getFilePicture')
				fd.append('noticeTimeStamp', that.noticeTimeStamp)
				that.$axios.post(that.$adminUrl+ `/datacheck/WeeklyInspect/WeeklySceneInspect.php`,fd).then(res=>{
					// console.log(res)
					if(res.data.code){
						if(res.data.data.scenePic != ''){
							for(let i =0;i<res.data.data.scenePic.split('||').length-1;i++){
								this.srcList1.push(that.$adminImgUrl +'/app_pic/filePic/'+res.data.data.scenePic.split('||')[i])
							}
							that.url1 = this.srcList1[0]
						}
						if(res.data.data.meetingPic != ''){
							for(let i =0;i<res.data.data.meetingPic.split('||').length-1;i++){
								this.srcList2.push(that.$adminImgUrl +'/app_pic/filePic/'+res.data.data.meetingPic.split('||')[i])
							}
							that.url2 = this.srcList2[0]
						}
						if(res.data.data.summaryPic != ''){
							for(let i =0;i<res.data.data.summaryPic.split('||').length-1;i++){
								this.srcList3.push(that.$adminImgUrl +'/app_pic/filePic/'+res.data.data.summaryPic.split('||')[i])
							}
							that.url3 = this.srcList3[0]
						}
						if(res.data.data.otherPic != ''){
							for(let i =0;i<res.data.data.otherPic.split('||').length-1;i++){
								this.srcList4.push(that.$adminImgUrl +'/app_pic/filePic/'+res.data.data.otherPic.split('||')[i])
							}
							that.url4 = this.srcList4[0]
						}
					}
				}).catch(() =>{
					
				})
			},
			handleClick(tab, event) { //表格tab切换触发的操作函数
				// console.log(tab, event);
			},
			openView(id) {
				this.showViewerStr = 'showViewer'+id
			}
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
	
	.tableTitle {
	    position: relative;
	    margin: 30px 0px 20px 0px;
	    width: 80%;
	    height: 1px;
	    background-color: #d4d4d4;
	    text-align: center;
	    font-size: 16px;
	    color: rgba(101, 101, 101, 1);
		left: 20px;
	}
	
	.midText {
	    position: absolute;
	    left: 50%;
	    background-color: #ffffff;
	    padding: 0 15px;
	    transform: translateX(-50%) translateY(-50%);
	}
	
	.box-style {
		width: 80%;
		// justify-content: space-between;
		flex-wrap: wrap;
	}
	
	.el-card {
		margin: 10px;
		width: 30%;
	}
	
	.title-style {
		display: block;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	
	.button-style {
		float: right;
		padding: 3px 0
	}
</style>
