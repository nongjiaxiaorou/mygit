<template>
	<div id="DataTable" class="basic1 container">
		<el-form :model="formDate" label-width="120px">
			<div class="flex" style="width: 80%;">
				<el-form-item label="通知单编号：" prop="noticeNumber" class="flex-1 px-2">
					<el-input v-model="formDate.noticeNumber"></el-input>
				</el-form-item>
				<el-form-item label="整改对象：" prop="rectificationObj" class="flex-1 px-2">
					<el-input v-model="formDate.rectificationObj"></el-input>
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
				<el-form-item label="检查班组：" prop="inspectTeam" class="flex-1 px-2">
					<el-input v-model="formDate.inspectTeam"></el-input>
				</el-form-item>
				<el-form-item label="组长名称：" prop="teamLeader" class="flex-1 px-2">
					<el-input v-model="formDate.teamLeader"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;" v-show="noticeState=='整改中'||noticeState=='已完成'||noticeState=='已关闭'">
				<el-form-item label="检查下发人：" prop="inspectSendPerson" class="flex-1 px-2">
					<el-input v-model="formDate.inspectSendPerson"></el-input>
				</el-form-item>
				<el-form-item label="下发时间：" prop="inspectSendDate" class="flex-1 px-2">
					<el-input v-model="formDate.inspectSendDate"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="检查日期：" prop="inspectDate" class="flex-1 px-2">
					<el-input v-model="formDate.inspectDate"></el-input>
				</el-form-item>
				<el-form-item label="截止日期：" prop="endDate" class="flex-1 px-2">
					<el-input v-model="formDate.endDate"></el-input>
				</el-form-item>
			</div>
		</el-form>
		<div v-show="noticeState=='整改中'||noticeState=='已完成'||noticeState=='已关闭'">
			<div class="tableTitle"><span class="midText">一般问题</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
					<div class="flex box-style">
						<el-card class="box-card" v-for="(item,index) in imgCommonInfoList" :key="index" >
						  <div slot="header" class="clearfix">
						    <span class="title-style">{{item.title}}</span>
						    <el-button class="button-style" type="text" @click="openView(item.id)">查看回复图片</el-button>
						  </div>
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						  <ElImageViewer v-show="showViewerStr=='showViewer'+item.id" :on-close="()=>{showViewerStr=''}" :url-list="item.replyPicFile" />
						</el-card>
						
						<!-- <el-form-item label="质量隐患期限整改通知单：" ref="inspectLevel1" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url1" :preview-src-list="srcList1">
							</el-image>
						</el-form-item>
						<el-form-item label="质量隐患期限整改通知单回复：" ref="inspectLevel2" class="flex-1 px-2">
							<el-image style="width: 100px; height: 100px;margin-left: 2%;" :src="url2" :preview-src-list="srcList2">
							</el-image>
						</el-form-item> -->
					</div>
				</el-form>
			</div>
			<div class="tableTitle"><span class="midText">重大问题</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
					<div class="flex box-style">
						<el-card class="box-card" v-for="(item,index) in imgMajorInfoList" :key="index" >
						  <div slot="header" class="clearfix">
						    <span class="title-style">{{item.title}}</span>
						    <el-button class="button-style" type="text" @click="openView(item.id)">查看回复图片</el-button>
						  </div>
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						  <ElImageViewer v-show="showViewerStr=='showViewer'+item.id" :on-close="()=>{showViewerStr=''}" :url-list="item.replyPicFile" />
						</el-card>
					</div>
				</el-form>
			</div>
		</div>
		
		
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
				formDate:{
					noticeNumber:'',
					rectificationObj:'',
					inspectLevel:'',
					inspectTeam:'',
					teamLeader:'',
					inspectPosition: '',
					inspectSendPerson:'',
					inspectSendDate:'',
					inspectDate:'',
					endDate:'',
					noticeTimeStamp: ''
				},
				inspectAdminData:{},
				taskTimeStamp:'',
				url1: this.$adminImgUrl + '/null.jpg',
				srcList1: [this.$adminImgUrl + '/null.jpg',this.$adminImgUrl + '/pc_pic/floorPic/16029028410000.jpg'],
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
				imgCommonInfoList: [],
				imgMajorInfoList: [],
				showViewerStr: ''
			}
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('itemInspectData')
			sessionData = JSON.parse(sessionData)
			this.noticeState = sessionData.state
			this.formDate.noticeNumber = sessionData.noticeNumber
			this.formDate.rectificationObj = sessionData.inspectPersonName
			this.formDate.inspectLevel = sessionData.inspectLevel
			this.formDate.inspectTeam = sessionData.inspectTeam
			this.formDate.teamLeader = sessionData.leaderName
			this.formDate.inspectSendPerson = sessionData.inspectSendPerson
			this.formDate.inspectSendDate = sessionData.rectificationDate
			this.formDate.inspectDate = sessionData.inspectDate
			this.formDate.endDate = sessionData.endDate
			this.formDate.inspectPosition = sessionData.inspectPosition
			this.formDate.noticeTimeStamp = sessionData.noticeTimeStamp
			this.getNoticeDetail()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('itemInspectData')
				sessionData = JSON.parse(sessionData)
				this.noticeState = sessionData.state
				this.formDate.noticeNumber = sessionData.noticeNumber
				this.formDate.rectificationObj = sessionData.inspectPersonName
				this.formDate.inspectLevel = sessionData.inspectLevel
				this.formDate.inspectTeam = sessionData.inspectTeam
				this.formDate.teamLeader = sessionData.leaderName
				this.formDate.inspectSendPerson = sessionData.inspectSendPerson
				this.formDate.inspectSendDate = sessionData.rectificationDate
				this.formDate.inspectDate = sessionData.inspectDate
				this.formDate.endDate = sessionData.endDate
				this.formDate.inspectPosition = sessionData.inspectPosition
				this.formDate.noticeTimeStamp = sessionData.noticeTimeStamp
				this.getNoticeDetail()
			},
			//获取检查通知单详情
			getNoticeDetail() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getNoticeDetail')
				fd.append('noticeTimeStamp', that.formDate.noticeTimeStamp)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/datacheck/ItemInspect/ItemInspect.php`, fd).then(res => {
					// console.log(res.data)
					if(res.data.code){
						for(var i=0;i<res.data.data.length;i++){
							var Arr = []
							if(res.data.data[i].itemType=='一般问题'){
								var temArr = res.data.data[i].replyPicFile.split('||')
								var isHasReplyPic = false
								for(var j=0;j<temArr.length-1;j++){
									Arr.push(this.$adminImgUrl + '/app_pic/inspectReplyPic/'+temArr[j].split('->')[0])
									isHasReplyPic = true
								}
								if(isHasReplyPic){
									this.imgCommonInfoList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].violationContent,
										imgUrl: this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile,
										imgArr: [this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile],
										replyPicFile: Arr
									})
								}else{
									this.imgCommonInfoList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].violationContent,
										imgUrl: this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile,
										imgArr: [this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile],
										replyPicFile: [this.$adminImgUrl + '/null.jpg']
									})
								}
							}else if(res.data.data[i].itemType=='重大问题'){
								var temArr = res.data.data[i].replyPicFile.split('||')
								var isHasReplyPic = false
								for(var j=0;j<temArr.length-1;j++){
									Arr.push(this.$adminImgUrl + '/app_pic/inspectReplyPic/'+temArr[j].split('->')[0])
									isHasReplyPic = true
								}
								if(isHasReplyPic){
									this.imgMajorInfoList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].violationContent,
										imgUrl: this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile,
										imgArr: [this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile],
										replyPicFile: Arr
									})
								}else{
									this.imgMajorInfoList.push({
										id: res.data.data[i].id,
										title: res.data.data[i].violationContent,
										imgUrl: this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile,
										imgArr: [this.$adminImgUrl + '/app_pic/inspectPic/'+res.data.data[i].picFile],
										replyPicFile: [this.$adminImgUrl + '/null.jpg']
									})
								}
							}
						}
					}
					loading.close();
				}).catch(() => {
					console.error("获取失败")
				})
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
