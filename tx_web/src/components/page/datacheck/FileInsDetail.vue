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
						<el-form-item label="资料名称：" prop="inspectLevel" class="flex-1 px-2">
							<el-input v-model="formDate.fileName"></el-input>
						</el-form-item>
						<el-form-item label="楼层信息：" prop="inspectPosition" class="flex-1 px-2">
							<el-input v-model="formDate.inspectPosition"></el-input>
						</el-form-item>
					</div>
					<div class="flex" style="width: 80%;">
						<el-form-item label="检查人：" prop="inspectTeam" class="flex-1 px-2">
							<el-input v-model="formDate.inspectPerson"></el-input>
						</el-form-item>
						<el-form-item label="检查日期：" prop="teamLeader" class="flex-1 px-2">
							<el-input v-model="formDate.inspectDate"></el-input>
						</el-form-item>
					</div>
				</el-form>
			</el-tab-pane>
			<el-tab-pane label="资料照片" name="second">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
					<div class="flex">
						<el-form-item label="方案资料汇总：" ref="inspectLevel1" class="flex-1 px-2">
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
			let sessionData = sessionStorage.getItem('fileInsData')
			sessionData = JSON.parse(sessionData)
			this.noticeTimeStamp = sessionData.noticeTimeStamp
			this.getNoticeDetail()
			this.getprogrammePicture()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('fileInsData')
				sessionData = JSON.parse(sessionData)
				this.noticeTimeStamp = sessionData.noticeTimeStamp
				this.getNoticeDetail()
				this.getprogrammePicture()
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
				that.$axios.post(that.$adminUrl + `/datacheck/WeeklyInspect/WeeklyFileInspect.php`, fd).then(res => {
					// console.log(res.data.data)
					if(res.data.code){
						that.formDate = res.data.data
					}
					loading.close();
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//获取资料照片
			getprogrammePicture(){
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getprogrammePicture')
				fd.append('noticeTimeStamp', that.noticeTimeStamp)
				that.$axios.post(that.$adminUrl+ `/datacheck/WeeklyInspect/WeeklyFileInspect.php`,fd).then(res=>{
					// console.log(res)
					if(res.data.code){
						if(res.data.data.problemPic != ''){
							for(let i =0;i<res.data.data.problemPic.split('||').length-1;i++){
								this.srcList1.push(that.$adminImgUrl +'/app_pic/programmePic/'+res.data.data.problemPic.split('||')[i])
							}
							that.url1 = this.srcList1[0]
						}
						if(res.data.data.meetingPic != ''){
							for(let i =0;i<res.data.data.meetingPic.split('||').length-1;i++){
								this.srcList2.push(that.$adminImgUrl +'/app_pic/programmePic/'+res.data.data.meetingPic.split('||')[i])
							}
							that.url2 = this.srcList2[0]
						}
						if(res.data.data.summaryPic != ''){
							for(let i =0;i<res.data.data.summaryPic.split('||').length-1;i++){
								this.srcList3.push(that.$adminImgUrl +'/app_pic/programmePic/'+res.data.data.summaryPic.split('||')[i])
							}
							that.url3 = this.srcList3[0]
						}
						if(res.data.data.otherPic != ''){
							for(let i =0;i<res.data.data.otherPic.split('||').length-1;i++){
								this.srcList4.push(that.$adminImgUrl +'/app_pic/programmePic/'+res.data.data.otherPic.split('||')[i])
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
