<template>
	<div id="DataTable" class="basic1 container">
        <!-- 砼浇筑模块 -->
        <div v-show="noticeState=='浇筑'||noticeState=='旁站'||noticeState=='完成旁站'">
            <el-form :model="formDate" label-width="120px">
			<div class="flex" style="width: 80%;">
				<el-form-item label="工程名称" prop="projectName" class="flex-1 px-2">
					<el-input v-model="formDate.projectName"></el-input>
				</el-form-item>
				<el-form-item label="浇筑部位" prop="pouringPosition" class="flex-1 px-2">
					<el-input v-model="formDate.pouringPosition"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="混凝土强度" prop="concreteGrade" class="flex-1 px-2">
					<el-input v-model="formDate.concreteGrade"></el-input>
				</el-form-item>
				<el-form-item label="浇筑时间" prop="pouringDate" class="flex-1 px-2">
					<el-input v-model="formDate.pouringDate"></el-input>
				</el-form-item>
			</div>
            <div class="flex" style="width: 80%;" v-show="noticeState=='旁站'||noticeState=='完成旁站'">
				<el-form-item label="旁站时间" prop="sideDate" class="flex-1 px-2">
					<el-input v-model="formDate.sideDate"></el-input>
				</el-form-item>
				<el-form-item label="旁站人" prop="bystander" class="flex-1 px-2">
					<el-input v-model="formDate.bystander"></el-input>
				</el-form-item>
			</div>
		</el-form>
		<div>
			<div class="tableTitle"><span class="midText">意见和签名</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
					<div class="flex box-style">
                        <el-card class="box-card " style="width:300px;"  >
						  <div slot="header" class="clearfix">
						    <span class="title-style">质量员意见:{{sign.idea1}}</span>
                             <span class="title-style">签名时间:{{sign.datetime1}}</span>
						  </div>
						  <el-image :src="sign.sign1"></el-image>

						</el-card>

                        <el-card class="box-card " style="width:300px;"  >
						  <div slot="header" class="clearfix">
						    <span class="title-style">生产经理意见:{{sign.idea2}}</span>
                             <span class="title-style">签名时间:{{sign.datetime2}}</span>
						  </div>
						  <el-image src="sign.sign2"></el-image>
						</el-card>
					</div>
                    
				</el-form>
			</div>
		</div>

    <div v-show="noticeState=='旁站'||noticeState=='完成旁站'">
		<div class="tableTitle"><span class="midText">浇筑前</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
                    <span class="title-style">浇筑前描述:{{formDate.poured_describe}}</span>
					<div class="flex box-style">
						<el-card class="box-card" v-for="(item,index) in pouredlist" :key="index" >
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						</el-card>
					</div>
				</el-form>
			</div>
            <div class="tableTitle"><span class="midText">浇筑中</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
                    <span class="title-style">浇筑中描述:{{formDate.pouring_describe}}</span>
					<div class="flex box-style">
						<el-card class="box-card" v-for="(item,index) in pouringlist" :key="index" >
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						</el-card>
					</div>
				</el-form>
			</div>
            <div class="tableTitle"><span class="midText">浇筑后</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
                    <span class="title-style">浇筑中描述:{{formDate.pour_describe}}</span>
					<div class="flex box-style">
                        <el-card class="box-card" v-for="(item,index) in pourlist" :key="index" >
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						</el-card>
					</div>
				</el-form>
			</div>
        </div>
        </div>
        <!-- 砼拆模模块 -->
        <div>
            <div v-show="noticeState=='拆模'||noticeState=='完工'">
            <el-form :model="formDate" label-width="120px">
			<div class="flex" style="width: 80%;">
				<el-form-item label="工程名称" prop="projectName" class="flex-1 px-2">
					<el-input v-model="formDate1.projectName"></el-input>
				</el-form-item>
				<el-form-item label="拆模部位" prop="pouringPosition" class="flex-1 px-2">
					<el-input v-model="formDate1.removalPosition"></el-input>
				</el-form-item>
			</div>
			<div class="flex" style="width: 80%;">
				<el-form-item label="混凝土强度" prop="concreteGrade" class="flex-1 px-2">
					<el-input v-model="formDate1.concreteGrade"></el-input>
				</el-form-item>
				<el-form-item label="浇筑日期" prop="pouringDate" class="flex-1 px-2">
					<el-input v-model="formDate1.pouringDate"></el-input>
				</el-form-item>
			</div>
            <div class="flex" style="width: 80%;">
				<el-form-item label="拆模日期" prop="createDate" class="flex-1 px-2">
					<el-input v-model="formDate1.createDate"></el-input>
				</el-form-item>
                <el-form-item label="完成日期" prop="completeDate" class="flex-1 px-2">
					<el-input v-model="formDate1.completeDate"></el-input>
				</el-form-item>
			</div>
		</el-form>

		<div class="tableTitle"><span class="midText">拆模报告</span></div>
			<div style="width: 100%;">
				<el-form :model="formDate" label-width="120px" style="margin-left: 4%;margin-top: 2%;">
                    <span class="title-style">拆模描述:{{formDate1.removal_describe}}</span>
					<div class="flex box-style">
						<el-card class="box-card" v-for="(item,index) in removallist" :key="index" >
						  <el-image :src="item.imgUrl" :preview-src-list="item.imgArr"></el-image>
						</el-card>
					</div>
				</el-form>
			</div>
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
				noticeState:'',
				formDate:{
                    id:'',
					projectName:'',
					concreteGrade:'',
					pouringPosition:'',
					pouringDate:'',
					sideDate:'',
                    bystander: '',
                    
                    poured_describe:'',
					poured_img:'',
					pouring_describe:'',
					pouring_img:'',
					pour_describe:'',
					pour_img:''
                },
               sign:{
                   sign1:'',//质量员签名
                   sign2:'', //生产经理签名
                   idea1:'',//质量员意见
                   idea2:'',//生产经理意见
                   datetime1:'',//质量员签名时间
                   datetime2:''//生产经理签名时间
                },
                pouredlist: [],
                pouringlist: [],
				pourlist: [],
				removallist:[],
                formDate1:{
                    id:'',
					projectName:'',
					concreteGrade:'',
					removalPosition:'',
					pouringDate:'',
					completeDate:'',
                    removal_describe: '',
                    createDate:'',
                },
				url1: this.$adminImgUrl + '/null.jpg',
				srcList1: [this.$adminImgUrl + '/null.jpg',this.$adminImgUrl + '/pc_pic/floorPic/16029028410000.jpg'],
			}
		},
		watch: {
			'$route.params': 'getTabelValueReset'
		},
		mounted() {
            //第一次进入
			let sessionData = sessionStorage.getItem('concreteData')
            sessionData = JSON.parse(sessionData)
			console.log(sessionData)
			this.noticeState = sessionData.proState
            if(this.noticeState=='拆模'||this.noticeState=='完工'){
				this.formDate1.projectName = sessionData.projectName
                this.formDate1.concreteGrade = sessionData.concreteGrade
                this.formDate1.removalPosition = sessionData.removalPosition
                this.formDate1.pouringDate = sessionData.pouringDate
				this.formDate1.completeDate = sessionData.completeDate
				this.formDate1.createDate = sessionData.createDate
				this.formDate1.completeDate = sessionData.completeDate
				this.formDate1.id = sessionData.id
				this.formDate1.removal_describe = sessionData.removal_describe
				this.removal_img(sessionData.removal_img)
            }else{
                this.formDate.projectName = sessionData.projectName
                this.formDate.concreteGrade = sessionData.concreteGrade
                this.formDate.pouringPosition = sessionData.pouringPosition
                this.formDate.pouringDate = sessionData.pouringDate
                this.formDate.sideDate = sessionData.sideDate
                this.formDate.bystander = sessionData.bystander
                this.formDate.id = sessionData.id
                // console.log(sessionData);
                this.formDate.poured_describe = sessionData.poured_describe
                this.formDate.pouring_describe = sessionData.pouring_describe
                this.formDate.pour_describe = sessionData.pour_describe
                this.getPourDetail(sessionData)
            }
		},
		methods: {
			//监听路由传参变化
			getTabelValueReset() {
			let sessionData = sessionStorage.getItem('concreteData')
            sessionData = JSON.parse(sessionData)
			console.log(sessionData)
			this.noticeState = sessionData.proState
            if(this.noticeState=='拆模'||this.noticeState=='完工'){
				this.formDate1.projectName = sessionData.projectName
                this.formDate1.concreteGrade = sessionData.concreteGrade
                this.formDate1.removalPosition = sessionData.removalPosition
                this.formDate1.pouringDate = sessionData.pouringDate
				this.formDate1.completeDate = sessionData.completeDate
				this.formDate1.createDate = sessionData.createDate
				this.formDate1.completeDate = sessionData.completeDate
				this.formDate1.id = sessionData.id
				this.formDate1.removal_describe = sessionData.removal_describe
				this.removal_img(sessionData.removal_img)
            }else{
                this.formDate.projectName = sessionData.projectName
                this.formDate.concreteGrade = sessionData.concreteGrade
                this.formDate.pouringPosition = sessionData.pouringPosition
                this.formDate.pouringDate = sessionData.pouringDate
                this.formDate.sideDate = sessionData.sideDate
                this.formDate.bystander = sessionData.bystander
                this.formDate.id = sessionData.id
                // console.log(sessionData);
                this.formDate.poured_describe = sessionData.poured_describe
                this.formDate.pouring_describe = sessionData.pouring_describe
                this.formDate.pour_describe = sessionData.pour_describe
                this.getPourDetail(sessionData)
            }
			},
			// 渲染拆模图片
			removal_img(e){
				console.log(e)
                    let removal_img = new Array();
                    removal_img = e.split("||");
                    for(let i = 0;i<removal_img.length-1;i++){
                        this.removallist.push({
                        imgUrl: this.$adminImgUrl + '/app_pic/concrete_image/'+removal_img[i],
                        imgArr: [this.$adminImgUrl + '/app_pic/concrete_image/'+removal_img[i]],
                    })
                    }
			},
			//获取浇筑旁站通知单签名
			getPourDetail(sessionData) {
                // console.log(sessionData);
                if(sessionData.proState=='旁站'||sessionData.proState=='完成旁站'){
                     //浇筑前
                    let poured_img = new Array();
                    poured_img = sessionData.poured_img.split("||");
                    for(let i = 0;i<poured_img.length-1;i++){
                        this.pouredlist.push({
                        imgUrl: this.$adminImgUrl + '/app_pic/concrete_image/'+poured_img[i],
                        imgArr: [this.$adminImgUrl + '/app_pic/concrete_image/'+poured_img[i]],
                    })
                    }

                    //浇筑中
                    let pouring_img = new Array();
                    pouring_img = sessionData.pouring_img.split("||");
                    for(let i = 0;i<pouring_img.length-1;i++){
                        this.pouringlist.push({
                        imgUrl: this.$adminImgUrl + '/app_pic/concrete_image/'+pouring_img[i],
                        imgArr: [this.$adminImgUrl + '/app_pic/concrete_image/'+pouring_img[i]],
                    })
                    }

                    //浇筑后
                    let pour_img = new Array();
                    pour_img = sessionData.pour_img.split("||");
                    for(let i = 0;i<pour_img.length-1;i++){
                        this.pourlist.push({
                        imgUrl: this.$adminImgUrl + '/app_pic/concrete_image/'+pour_img[i],
                        imgArr: [this.$adminImgUrl + '/app_pic/concrete_image/'+pour_img[i]],
                    })
                    }
                }
				const that = this
				let fd = new FormData()
				fd.append('flag', 'getnoticeDetail')
                fd.append('id', that.formDate.id)
                console.log(that.formDate.id)
				const loading = that.$loading({
					lock: true,
					text: 'Loading',
					target: document.querySelector('#DataTable'),
					spinner: 'el-icon-loading'
				});
				that.$axios.post(that.$adminUrl + `/datacheck/ConcreteManage/ConcreteManage.php`, fd).then(res => {
					console.log(res.data)
					if(res.data.code){
                        this.sign = res.data.data[0]
                         console.log(sessionData.pouring_img);
					}
					loading.close();
				}).catch(() => {
                    console.error("获取失败")
                    loading.close();
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
        font-size: 15px;
	}
	
	.button-style {
		float: right;
		padding: 3px 0
	}
</style>
