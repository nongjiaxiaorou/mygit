<template>
	<div id="DataTable" class="basic1 container">
		<el-form :model="formDate" label-width="120px">
			<div class="flex" style="width: 80%;">
				<el-form-item label="工程名称：" prop="projectName" class="flex-1 px-2">
					<el-input v-model="formDate.projectName"></el-input>
				</el-form-item>
				<el-form-item label="楼层信息：" prop="inspectPosition" class="flex-1 px-2">
					<el-input v-model="formDate.inspectPosition"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="验收人：" prop="acceptPerson" class="flex-1 px-2">
					<el-input v-model="formDate.acceptPerson"></el-input>
				</el-form-item>
				<el-form-item label="检查项目：" prop="inspectItem" class="flex-1 px-2">
					<el-input v-model="formDate.inspectItem"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="验收流程：" prop="acceptProcess" class="flex-1 px-2">
					<el-input v-model="formDate.acceptProcess"></el-input>
				</el-form-item>
				<el-form-item label="结构部位：" prop="structurePosition" class="flex-1 px-2">
					<el-input v-model="formDate.structurePosition"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="验收工序：" prop="acceptProcedure" class="flex-1 px-2">
					<el-input v-model="formDate.acceptProcedure"></el-input>
				</el-form-item>
				<el-form-item label="检查日期：" prop="inspectDate" class="flex-1 px-2">
					<el-input v-model="formDate.inspectDate"></el-input>
				</el-form-item>
			</div>
		</el-form>
		<div class="tableTitle" v-show="imgAcceptList.length>0"><span class="midText">项目验收图片</span></div>
		<div style="width: 100%;">
			<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
				<div class="flex box-style">
					<el-card class="box-card" v-for="(item,index) in imgAcceptList" :key="index" >
					  <div slot="header" class="clearfix">
					    <span class="title-style">{{item.title}}</span>
					    <!-- <el-button class="button-style" type="text" @click="openView(item.id)">查看回复图片</el-button> -->
					  </div>
					  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
					</el-card>
				</div>
			</el-form>
		</div>
		<div class="tableTitle" v-show="perSignInfoList.length>0"><span class="midText">验收人签名：</span></div>
		<div style="width: 100%;">
			<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
				<!-- <div class="flex box-style">
					<el-card class="box-card" v-for="(item,index) in perSignInfoList" :key="index" >
					  <div slot="header" class="clearfix">
					    <span class="title-style">{{item.title}}</span>
					  </div>
					  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
					</el-card>
				</div> -->
				<div class="containerSign">
					<el-card class="box-card card" v-for="(item,index) in perSignInfoList" :key="index" >
						<div class="img">
							<el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						</div>
						<div class="top-text">
							<div class="name">
								验收签名
							</div>
							
						</div>
						<div class="bottom-text">
							<div class="text">
								{{item.content}}
							</div>
							<div class="textSign">
								签名：2020-12-20
							</div>
						</div>
					</el-card>
				</div>
			</el-form>
		</div>
		
	</div>
	
</template>

<script>
	import VmCard from '@/components/common/vm-card.vue'
	export default {
		components: {
		  VmCard
		},
		data() {
			return {
				formDate:{
					projectName:'',
					inspectPosition:'',
					inspectPerson:'',
					inspectItem:'',
					acceptProcess:'',
					structurePosition: '',
					acceptProcedure:'',
					inspectDate: '',
					acceptTimeStamp: ''
				},
				inspectAdminData:{},
				taskTimeStamp:'',
				showViewer: false,
				imgAcceptList: [],
				perSignInfoList: [],
				showViewerStr: ''
			}
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
			//第一次进入
			let sessionData = sessionStorage.getItem('itemAccepttData')
			sessionData = JSON.parse(sessionData)
			// console.log(sessionData)
			this.formDate.projectName = sessionData.projectName
			this.formDate.inspectPosition = sessionData.inspectPosition
			this.formDate.acceptPerson = sessionData.acceptPerson
			this.formDate.inspectItem = sessionData.inspectItem
			this.formDate.acceptProcess = sessionData.acceptProcess
			this.formDate.structurePosition = sessionData.structurePosition
			this.formDate.acceptProcedure = sessionData.acceptProcedure
			this.formDate.inspectDate = sessionData.inspectDate
			this.formDate.acceptTimeStamp = sessionData.acceptTimeStamp
			this.getAcceptDetail()
			this.getAcceptPersonSign()
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
				let sessionData = sessionStorage.getItem('itemAccepttData')
				sessionData = JSON.parse(sessionData)
				this.formDate.projectName = sessionData.projectName
				this.formDate.inspectPosition = sessionData.inspectPosition
				this.formDate.acceptPerson = sessionData.acceptPerson
				this.formDate.inspectItem = sessionData.inspectItem
				this.formDate.acceptProcess = sessionData.acceptProcess
				this.formDate.structurePosition = sessionData.structurePosition
				this.formDate.acceptProcedure = sessionData.acceptProcedure
				this.formDate.inspectDate = sessionData.inspectDate
				this.formDate.acceptTimeStamp = sessionData.acceptTimeStamp
				this.getAcceptDetail()
				this.getAcceptPersonSign()
			},
			//获取验收通知单详情
			getAcceptDetail() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getAcceptDetail')
				fd.append('acceptTimeStamp', this.formDate.acceptTimeStamp)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/datacheck/ItemAccept/ItemAccept.php`, fd).then(res => {
					console.log(res.data)
					if(res.data.code){
						for(var i=0;i<res.data.data.length;i++){
							this.imgAcceptList.push({
								id: res.data.data[i].id,
								title: res.data.data[i].acceptPicDescribe,
								imgUrl: this.$adminImgUrl + '/app_pic/acceptPic/'+res.data.data[i].acceptPic,
								imgArr: [this.$adminImgUrl + '/app_pic/acceptPic/'+res.data.data[i].acceptPic]
							})
						}
					}
					loading.close();
				}).catch(() => {
					console.error("获取失败")
				})
			},
			//获取验收人签名
			getAcceptPersonSign() {
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getAcceptPersonSign')
				fd.append('acceptTimeStamp', this.formDate.acceptTimeStamp)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/datacheck/ItemAccept/ItemAccept.php`, fd).then(res => {
					// console.log(res.data)
					if(res.data.code){
						for(var i=0;i<res.data.data.length;i++){
							console.log(res.data.data[i].acceptSignPic)
							this.perSignInfoList.push({
								id: res.data.data[i].id,
								imgUrl: res.data.data[i].acceptSignPic,
								imgArr: [res.data.data[i].acceptSignPic],
								content: '关于'+res.data.data[i].inspectPosition+'-'+res.data.data[i].inspectItem+'-'+res.data.data[i].acceptProcedure+'工序的验收签名'
							})
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
		overflow-x: scroll
		// text-overflow: ellipsis;
	}
	
	.button-style {
		float: right;
		padding: 3px 0
	}
	
	
	::-webkit-scrollbar-track-piece {
	  -webkit-border-radius: 0
	}
	
	::-webkit-scrollbar {
	  width: 5px;
	  height: 10px
	}
	
	::-webkit-scrollbar-thumb {
	  height: 50px;
	  background-color: #b8b8b8;
	  -webkit-border-radius: 6px;
	  outline-offset: -2px;
	  filter: alpha(opacity = 50);
	  -moz-opacity: 0.5;
	  -khtml-opacity: 0.5;
	  opacity: 0.5
	}
	
	::-webkit-scrollbar-thumb:hover {
	  height: 50px;
	  background-color: #878987;
	  -webkit-border-radius: 6px
	}
	
	.containerSign{
		width: 80%;
		display: flex;/*弹性布局*/
		align-items: center;/*纵轴方向居中对齐*/
		justify-content: left;/*X轴方向左对齐*/
		flex-wrap: wrap;
	}
	
	.card{
		height: 270px;
		width: 30%;
		margin: 10px;
		background-color: white;
		transition: 0.4s;
		box-shadow: 2px 2px 5px rgba(0,0,0,0.2);/*设置阴影效果*/
	}
	
	.card:hover{
		height: 360px;
		box-shadow:5px 5px 10px rgba(0,0,0,0.2);
	}
	
	.card .img{
		height: 200px;
		width: 100%;
		display: flex;
		align-items: center;
	}
	
	.card .img img{
		height: 100%;
		width: 100%;
		object-fit: cover;/*切割图片，保留图片原比例大小*/
	}
	
	.card .top-text{
		padding-top: 5px;
	}
	
	.card .top-text .name{
		font-size: 16px;
		font-weight:600;
		color: #202020;
		text-align: center;
	}
	
	.card .top-text p{
		font-size: 20px;
		font-weight:600;/*字体粗细*/
		color: #e74c3c;
		line-height: 20px;
	}
	
	.card .bottom-text{
		padding: 0 20px 10px 20px;
		margin-top: 5px;
		 background-color: white;
		 visibility: hidden;
		 transition: 0.5s;
	}
	
	.card:hover .bottom-text{
		opacity: 1;
		visibility: visible;
	}
	
	.card .bottom-text .text{
		text-align: justify;
		border-top: 1px solid #d4d4d4;
		}
	
	.container{
		border: none;
	}
	
	.content{
		background-color: white;
		margin: 2%;
		padding: 2%;
		padding-left: 10%;
		border: 1px solid #ddd;
	}
	
	.textSign{
		font-size: 5px;
		text-align: right;
		margin-top: 10px;
	}
</style>
