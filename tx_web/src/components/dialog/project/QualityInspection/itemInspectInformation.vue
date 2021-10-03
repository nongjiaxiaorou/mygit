<template>
	<div id="DataTable" class="basic1 container">
		<el-form :model="formDate" label-width="120px">
            <div class="grid-content title-text title-box">项目实测信息/{{formDate.inspectItem}}</div>
            <el-tabs v-model="activeName" type="card" @tab-click.once="handleClick">
                 <el-tab-pane label="基本信息" name="first">
                    <div class="flex" style="width: 80%;">
                        <el-form-item label="标题名称：" prop="inspectItem" class="flex-1 px-2">
                            <el-input v-model="formDate.inspectItem"></el-input>
                        </el-form-item>
                        <el-form-item label="检查部位：" prop="inspectPosition" class="flex-1 px-2">
                            <el-input v-model="formDate.inspectPosition"></el-input>
                        </el-form-item>
                    </div>
                    <div class="flex" style="width: 80%;">
                        <el-form-item label="检查日期：" prop="inspectDate" class="flex-1 px-2">
                            <el-input v-model="formDate.inspectDate"></el-input>
                        </el-form-item>
                        <el-form-item label="检查人员：" prop="inspectPerson" class="flex-1 px-2">
                            <el-input v-model="formDate.inspectPerson"></el-input>
                        </el-form-item>
                    </div>
                    <div class="flex" style="width: 80%;">
                        <el-form-item label="施工班组：" prop="" class="flex-1 px-2">
                            <el-input v-model="formDate.constructionTeam"></el-input>
                        </el-form-item>
                        <el-form-item label="组长姓名：" prop="" class="flex-1 px-2">
                            <el-input v-model="formDate.teamLeaderName"></el-input>
                        </el-form-item>
                    </div>
                    <div class="flex" style="width: 80%;">
                        <el-form-item label="联系方式：" prop="" class="flex-1 px-2">
                            <el-input v-model="formDate.contactMode"></el-input>
                        </el-form-item>
                        <el-form-item label="施工日期：" prop="" class="flex-1 px-2">
                            <el-input v-model="formDate.constructionDate"></el-input>
                        </el-form-item>
                    </div>
                    <el-row>
                        <el-button type="primary" @click="handleClickModify">修改</el-button>
                        <el-button type="danger" @click="handleClickDelete">删除</el-button>
                        <el-button type="success" @click="handleClickReturn">返回</el-button>
                        
                        <!-- @click="handleClickelete" -->
                    </el-row>
                </el-tab-pane>

                <el-tab-pane label="实测实量数据" name="second">
                    <importBtnMeasurement @handleImportChaild='handleImport'></importBtnMeasurement>
                    <ImportMeasurement :dialogImport="dialogImport" ></ImportMeasurement>
                    <div  class = "text-box">
                            爆点总数：{{UnqualifiedNum}} <br> 已布测点：{{MeasuredPoints}}
                    </div>
                    <el-collapse v-model="activeNames" @change="handleChange" accordion v-for="(item, index) in tableData" :key="index">            
                        <el-collapse-item :title="item.measurePointName" :name="item.measurePointNumber" >
                            <el-collapse v-model="activeNames0" @change="handleChange">
                                <el-collapse-item title="合格" name="1">
                                    <el-table
                                    :data="item.dataSon1"
                                    height="100%"
                                    border
                                    style="width: 100%">
                                        <el-table-column
                                        prop="measurePointNumber"
                                        label="编号"
                                        width="180">
                                        </el-table-column>
                                        <el-table-column
                                        prop="pointInitialValue"
                                        label="初测值"
                                        width="180">
                                        </el-table-column>
                                        <el-table-column
                                        prop="pointRetestValue"
                                        label="复测值">
                                        </el-table-column>
                                        <el-table-column
                                        prop="pointFinaltestValue"
                                        label="终测值">
                                        </el-table-column>
                                    </el-table>
                                </el-collapse-item>

                                <el-collapse-item title="不合格" name="2">
                                    <el-table
                                    :data="item.dataSon2"
                                    height="100%"
                                    border
                                    style="width: 100%">
                                        <el-table-column
                                            prop="measurePointNumber"
                                            label="编号"
                                            width="180">
                                            </el-table-column>
                                            <el-table-column
                                            prop="pointInitialValue"
                                            label="初测值"
                                            width="180">
                                            </el-table-column>
                                            <el-table-column
                                            prop="pointRetestValue"
                                            label="复测值">
                                            </el-table-column>
                                            <el-table-column
                                            prop="pointFinaltestValue"
                                            label="终测值">
                                        </el-table-column>
                                    </el-table>
                                </el-collapse-item>
                            </el-collapse>
                           
                        </el-collapse-item>
                        <!-- <el-collapse-item title="座厕坑距偏差" name="2">
                            <div></div>
                            <div></div>
                        </el-collapse-item>
                        <el-collapse-item title="地面表面平整度毛坯交付（有找平层）" name="3">
                            <div></div>
                            <div></div>
                            <div></div>
                        </el-collapse-item>
                        <el-collapse-item title="地面表面平整度（装修房直铺地板交付）" name="4">
                            <div></div>
                            <div></div>
                        </el-collapse-item> -->
                    </el-collapse>    
                </el-tab-pane>
                    
                <el-tab-pane label="统计分析" name="third">
                    <el-table
                            :data="tableData"
                            height="100%"
                            border
                            style="width: 100%">
                                <el-table-column
                                prop="number"
                                label="编号"
                                width="180">
                                </el-table-column>
                                <el-table-column
                                prop="measurePointName"
                                label="测点类型"
                                width="180">
                                </el-table-column>
                                <el-table-column
                                prop="qualityInspectionValue"
                                label="合格率">
                                </el-table-column>
                                <el-table-column
                                prop="qualityInspectionStatus"
                                label="预警状态">
                                </el-table-column>
                            </el-table>
                </el-tab-pane>
                    
                <el-tab-pane label="实测测点图纸" name="fourth">

                    <div>初测测点图片：{{this.primaryMeasurePic}}</div>                                    
                    <img :src="primaryMeasurePicSrc" alt="" style="height:400px">
                 
                    <div>复测测点图片：{{this.measurePointPic}}</div>
                    <img :src="measurePointPicSrc" alt="" style="height:400px">

                    <div  class = "button-box">
                        <el-button type="primary" size="medium" @click="printClick1">打印测点图片</el-button>
                    </div>

                </el-tab-pane>

                <el-tab-pane label="手绘布点图" name="five">                
                    <div class="el-upload-box">
                        <el-upload                        
                        ref="upload"
                        :auto-upload="false"
                        :on-success="success"
                        :http-request="httpRequest"
                        :on-exceed="fileExceed"
                        :on-change="onchangeFunc"
                        :limit="1"                       
                        :action="storage"
                        class="el-upload">
                            <i class="el-icon-upload"></i>
                            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                        </el-upload>                   
                        <div class="el-upload__tip0">图片支持如下格式:</div>
                        <div class="el-upload__tip">JPEG、PNG格式</div>
                        <div class="el-upload__tip">像素为宽x高 （1200x840）</div>
                        <el-row class = "button-box5">
                            <el-button @click="abort()">取 消</el-button>
                            <el-button type="primary" @click="uploadpreliminaryPic()">导入初测图片</el-button>
                            <el-button type="primary" @click="uploadRtestPic()">导入复测图片</el-button>
                        </el-row>
                    </div>
                    <div>初测手绘布点图片：{{this.PreliminaryPainted}}</div>                                    
                    <img :src="preliminarySrc" alt="" id="PreliminarySurvey" style="height:300px">
                 
                    <div>复测手绘布点图片：{{this.RetestPainted}}</div>
                    <img :src="retestSrc" alt="" style="height:300px">
                    
                    <div  class = "button-box">
                        <el-button type="primary" size="medium" @click="printClick2">打印测点图片</el-button>
                    </div>
                </el-tab-pane>

            </el-tabs>	
		</el-form>
        	
	</div>	
</template>

<script>
import importBtnMeasurement from '../QualityInspection/Import-btn-Measurement'
import ImportMeasurement from "../QualityInspection/ImportMeasurement"
	export default {
        name: 'itemInspectInformation',
        props: {
			registerBaseData: Object,
			
		},
		components: {
            importBtnMeasurement,
            ImportMeasurement,
            

		},
		data() {
			return {    
                tableData: [],     
                tableData1: [],   
                tableData2: [],              
                activeNames: ['1'],
                activeNames0: [''],
                activeName: 'first',
				formDate:{ //缓存里的数据
					inspectDate:'',
					inspectItem:'',
					inspectPerson:'',
                    inspectPosition:'',
                    id:'',
                    proTimeStamp: '',
                },
                dialogImport: {
					show: false
                },
                preliminarySrc: "",//手绘初测图路径
                retestSrc: '',  //手绘复测图路径
                PreliminaryPainted: '', //手绘初测图片名
                RetestPainted: '', //手绘复测图片名
                isLoading:true,
                fileList: [],              
                storage: '',
                itemInspectInformation: '',
                UnqualifiedNum: '', //爆点总数
                MeasuredPoints: '', //已布测点
                importStatus: '', //导入图片判断实测状态
                status: '', //实测状态
                measurePointPic: '',//项目实测测点图片
                primaryMeasurePic: '',//初测图片
                measurePointPicSrc: '',//项目实测测点图片路径
                primaryMeasurePicSrc: '',//初测图片路径
                // a: '',
                // b: '',
                // c: '',
                // d: '',
                // f: '',
                // Aarray: [],
                // Barray: [],
                // Carray: [],
                // Darray: [],
                // Earray: [],
			}
		},
		watch: {
            '$route.params': 'getTabelValueReset'
        },

        created() {
            
        },

		mounted() {                       
            //第一次进入 
            this.handleAccess()           
            this.measuredRealData()
            this.HandPlot()            
            this.MeasuringPoint()
            this.accessPoint()

        },
        
		methods: {
            


            //监听路由传参变化
			getTabelValueReset() {
                let sessionData = sessionStorage.getItem('itemInspectInformation')
                sessionData = JSON.parse(sessionData)
                this.formDate.id = sessionData.id
                this.formDate.inspectDate = sessionData.inspectDate
                this.formDate.inspectItem = sessionData.inspectItem
                this.formDate.inspectPerson = sessionData.inspectPerson
                this.formDate.inspectPosition = sessionData.inspectPosition
                this.formDate.proTimeStamp = sessionData.proTimeStamp
                this.handleAccess()
			},

            handleAccess() {
                let sessionData = sessionStorage.getItem('itemInspectInformation')
                sessionData = JSON.parse(sessionData)
                this.formDate.id = sessionData.id
                this.formDate.inspectDate = sessionData.inspectDate
                this.formDate.inspectItem = sessionData.inspectItem
                this.formDate.inspectPerson = sessionData.inspectPerson
                this.formDate.inspectPosition = sessionData.inspectPosition
                this.formDate.proTimeStamp = sessionData.proTimeStamp
                console.log(this.formDate.id)
            },
            
            //获取点数量
            accessPoint() {
                const that = this
				let fd = new FormData()
                fd.append('flag', 'accessPoint')
                fd.append('id',this.formDate.id)
                that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
                    console.log(res.data.data)                                       
                    console.log(that.tableData)
                    that.UnqualifiedNum = res.data.data.UnqualifiedNum;
                    that.MeasuredPoints = res.data.data.MeasuredPoints;
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
                    }
				}).catch(() => {
                    console.error("获取失败")
                })        
            },

			handleClick(tab, event) {
                console.log(tab, event);
                if(this.PreliminaryPainted == '') {                   
                    this.preliminarySrc =  this.$adminImgUrl + "/null.jpg" 
                    console.log(this.PreliminaryPainted)                 
                }
                else {                  
                    this.preliminarySrc = this.$adminImgUrl + "/pc_pic/floorPic/manualPic/" + this.PreliminaryPainted                                                       
                    console.log(this.PreliminaryPainted)
                }

                if(this.RetestPainted == '') {                    
                    this.retestSrc =  this.$adminImgUrl + "/null.jpg"                                
                }
                else {
                    this.retestSrc = this.$adminImgUrl + "/pc_pic/floorPic/manualPic/" + this.RetestPainted 
                }


                if(this.primaryMeasurePic == '') {                   
                    this.primaryMeasurePicSrc =  this.$adminImgUrl + "/null.jpg"
                    console.log(this.primaryMeasurePic)
                }
                else {                  
                    this.primaryMeasurePicSrc = this.$adminImgUrl + "/app_pic/inspectPic/upload/" + this.primaryMeasurePic                                                         
                    console.log(this.primaryMeasurePic)
                }

                if(this.measurePointPic == '') {                    
                    this.measurePointPicSrc =  this.$adminImgUrl + "/null.jpg"                                 
                }
                else {
                    this.measurePointPicSrc = this.$adminImgUrl + "/app_pic/inspectPic/upload/" + this.measurePointPic 
                }

                // for (var i = 0 ;i < this.tableData.length;i++) {
                //     for(var a = 0;a < this.tableData[i].dataSon.length;a++){
                //         if (this.tableData[i].dataSon[a].pointInitialValue == '合格') {
                //             this.tableData1.push(this.tableData[i].dataSon[a])
                            
                //         }
                //         else {
                //             this.tableData2.push(this.tableData[i].dataSon1[a])            
                //         }
                //     }
                // }
                // console.log(this.tableData[i].dataSon2)

                // const that = this
                // console.log(typeof that.tableData.length)
                // var i = 0;
                // console.log(that.tableData.length)
                // that.a = that.tableData[i].measurePointName
                // for (i = 0; i <= that.tableData.length; i++) {                                             
                //     if(that.a == that.tableData[i].measurePointName) {                           
                //         that.Aarray.push(that.tableData[i].measurePointNumber + that.tableData[i].pointInitialValue + '(' + that.tableData[i].pointInitialStatus + ')' + that.tableData[i].pointRetestValue + '(' + that.tableData[i].pointRetestStatus + ')' + that.tableData[i].pointFinaltestValue + '(' + that.tableData[i].finaltestStatus + ')'); 
                //     }
                //     else {
                //         if(that.b == '') {
                //             that.b = that.tableData[i].measurePointName
                //         }
                //         else {}                           
                //         if(that.b == that.tableData[i].measurePointName) {       
                //             that.Barray.push(that.tableData[i].measurePointNumber + that.tableData[i].pointInitialValue + '(' + that.tableData[i].pointInitialStatus + ')' + that.tableData[i].pointRetestValue + '(' + that.tableData[i].pointRetestStatus + ')' + that.tableData[i].pointFinaltestValue + '(' + that.tableData[i].finaltestStatus + ')');
                //         }
                //         else{
                //             if(that.c == '') {
                //             that.c = that.tableData[i].measurePointName
                //             }
                //             else {}
                //             if(that.c == that.tableData[i].measurePointName) {          
                //                 that.Carray.push(that.tableData[i].measurePointNumber + that.tableData[i].pointInitialValue + '(' + that.tableData[i].pointInitialStatus + ')' + that.tableData[i].pointRetestValue + '(' + that.tableData[i].pointRetestStatus + ')' + that.tableData[i].pointFinaltestValue + '(' + that.tableData[i].finaltestStatus + ')');
                //             }
                //             else{
                //                 if(that.d == '') {
                //                     that.d = that.tableData[i].measurePointName
                //                 }
                //                 else {}
                //                 if(that.d == that.tableData[i].measurePointName) {             
                //                     that.Darray.push(that.tableData[i].measurePointNumber + that.tableData[i].pointInitialValue + '(' + that.tableData[i].pointInitialStatus + ')' + that.tableData[i].pointRetestValue + '(' + that.tableData[i].pointRetestStatus + ')' + that.tableData[i].pointFinaltestValue + '(' + that.tableData[i].finaltestStatus + ')');
                //                 }
                //                 else {
                //                     if(that.f == '') {
                //                         that.f = that.tableData[i].measurePointName
                //                     }
                //                     else {}
                //                     if(that.f == that.tableData[i].measurePointName) { 
                //                         that.Earray.push(that.tableData[i].measurePointNumber + that.tableData[i].pointInitialValue + '(' + that.tableData[i].pointInitialStatus + ')' + that.tableData[i].pointRetestValue + '(' + that.tableData[i].pointRetestStatus + ')' + that.tableData[i].pointFinaltestValue + '(' + that.tableData[i].finaltestStatus + ')');
                //                     }
                //                     else {
                //                         // console.log(错误)
                //                     }
                //                 }
                //             }
                //         }
                //     }                    
                // }
            },

            // 获取实测实量数据
            measuredRealData() {
                const that = this
				let fd = new FormData()
                fd.append('flag', 'measuredRealData')
                fd.append('id',this.formDate.id)
                that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
                    console.log(res.data.data)                                       
                    that.tableData = res.data.data
                    console.log(that.tableData)

					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
						loading.close();
                    }
				}).catch(() => {
                    console.error("获取失败")
                })
                  
            },
            
            // 打印测点图片按钮
            printClick1() {
                if(this.status == '初测') {
                    this.$router.push({
                        name: 'printMeasurementPic',
                        params: {PicSrc1:this.primaryMeasurePicSrc,text1:'初测测点图片:' + this.primaryMeasurePic,PicSrc2:this.$adminImgUrl + "/null.jpg",text2:'复测测点图片:' + this.measurePointPic}
				    })
                }
                else {
                        this.$router.push({
                        name: 'printMeasurementPic',
                        params: {PicSrc1:this.primaryMeasurePicSrc,text1:'初测测点图片:' + this.primaryMeasurePic,PicSrc2:this.measurePointPicSrc,text2:'复测测点图片:' + this.measurePointPic}
				    })
                }              
            },

            // 打印手绘布点图按钮
            printClick2() {
                if(this.status == '初测') {
                    this.$router.push({
                        name: 'PrintHandPainted',
                        params: {PicSrc:this.preliminarySrc,text:'初测测点图片:' + this.PreliminaryPainted}
				    })
                }
                else {
                        this.$router.push({
                        name: 'PrintHandPainted',
                        params: {PicSrc:this.retestSrc,text:'复测测点图片:' + this.RetestPainted}
				    })
                }              
            },

            // 获取手绘图
            HandPlot() {                
                const that = this              
                let fd = new FormData() 
                fd.append('flag', 'HandPlot')
                fd.append('id',that.formDate.id)
                that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
                    that.PreliminaryPainted = res.data.data.picName1;
                    that.RetestPainted = res.data.data.picName2;
                    console.log(that.PreliminaryPainted);
                }).catch(() => {
                    console.error("获取失败")
                })          
            },

            // 实测测点图纸
            MeasuringPoint() {
                const that = this              
                let fd = new FormData() 
                fd.append('flag', 'MeasuringPoint')
                fd.append('id',that.formDate.id)
                that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
                    that.measurePointPic = res.data.data.measurePointPic;
                    that.primaryMeasurePic = res.data.data.primaryMeasurePic;
                    that.status = res.data.data.status;
                }).catch(() => {
                    console.error("获取失败")
                })    
            },

            //导入
            handleImport() {
				this.dialogImport.show = true;
			},

            handleChange(val) {                
                console.log(val);
            },

            //选择文件时触发
			onchangeFunc(file, fileList) {
				this.fileList = fileList;
            },
            
            abort(){
               this.$refs.upload.clearFiles();
            },

            //导入初测图片按钮
			uploadpreliminaryPic() {
				this.importStatus = '初测';
				this.fileList = ""
				// console.log(this.$refs.upload.uploadFiles)
				if (this.$refs.upload.uploadFiles) {
                    this.$refs.upload.submit(); // 此函数执行后会调用httpRequest函数
                    this.$refs.upload.clearFiles();
                    
				} else {
					this.$message({
						type: 'warning',
						message: '您未添加任何文件！'
					});
				}
            },
            
             //导入复测图片按钮
			uploadRtestPic() {				
				this.fileList = ""
                // console.log(this.$refs.upload.uploadFiles)
                this.importStatus = '复测';
				if (this.$refs.upload.uploadFiles) {
                    this.$refs.upload.submit(); // 此函数执行后会调用httpRequest函数
                    this.$refs.upload.clearFiles();
				} else {
					this.$message({
						type: 'warning',
						message: '您未添加任何文件！'
					});
				}
			},

            // 超出上传限制提示框
			fileExceed(e) {
				let options = {
					message: '已添加文件！',
					type: 'warning',
					duration: 2000
				};
				// Message(options);
			},

            // 上传成功回调
			success(res) {
				console.log(res);
				this.$emit('updateImp');
			},

            httpRequest(param) {
				const that =this
				let fileObj = param.file // 相当于input里取得的files
				let fd = new FormData() // FormData 对象
				fd.append('file', fileObj) // 文件对象
				
				fd.append('flag', 'saveUploadPic')
				fd.append('proTimeStamp', that.formDate.proTimeStamp)
                fd.append('id', that.formDate.id)
                fd.append('importStatus', that.importStatus)
				let config = {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/handPaintedPointPicture.php`, fd, config).then(res => {
                    console.log(res.data)
                    if(that.importStatus == '初测'){
                        that.PreliminaryPainted = res.data.picName
                        console.log(res.data.picName)
                    }
                    else{
                        that.RetestPainted = res.data.picName
                        console.log(res.data.picName)
                    }
                    // sessionStorage.setItem('HandPaintedCache',JSON.stringify(that.PreliminaryPainted))//存入储存，每次打开都能获取                    
					setTimeout(() => {
						that.$emit('handleImportFloorChild'); //初始化webscoket刷新视图
                        that.$message.success('手绘布点图上传成功');
                        that.handleClick();
					}, 500);
				})
            },

            
 
            // 修改
            handleClickModify(isLoading) {
                sessionStorage.setItem('itemInspectInformation',JSON.stringify(this.formDate))
                const that = this
				let fd = new FormData()
				fd.append('flag', 'Modify')
				fd.append('inspectDate',this.formDate.inspectDate)
                fd.append('inspectItem',this.formDate.inspectItem)
                fd.append('inspectPerson',this.formDate.inspectPerson)
                fd.append('inspectPosition',this.formDate.inspectPosition)
                fd.append('constructionTeam',this.formDate.constructionTeam)
                fd.append('teamLeaderName',this.formDate.teamLeaderName)
                fd.append('contactMode',this.formDate.contactMode)
                fd.append('constructionDate',this.formDate.constructionDate)
                fd.append('id',this.formDate.id)
				console.log(fd)
				// console.log(fd.flag)
				// console.log(this.$loading())				
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
                        loading.close();                       
                        this.$alert('修改成功', '', {
                            confirmButtonText: '确定',
                        });
					}
				}).catch(() => {
					console.error("获取失败")
                })
                this.handleAccess()
			},
            
            // 删除
            handleClickDelete(isLoading) {
                sessionStorage.setItem('itemInspectInformation',JSON.stringify(this.formDate))
                const that = this
				let fd = new FormData()
				fd.append('flag', 'Delete')
                fd.append('id',this.formDate.id)
                console.log(fd)
                console.log(1)
				// console.log(fd.flag)
				// console.log(this.$loading())				
				that.$axios.post(that.$adminUrl + `/project/DetailedInspectionv/projectMeasured .php`, fd).then(res => {
					if(that.isLoading){
						const loading = that.$loading({
							lock: true,
							text: 'Loading',
							target: document.querySelector('#ProTable'),
							spinner: 'el-icon-loading'
						});
                        loading.close();
                        // this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                        //     confirmButtonText: '确定',
                        //     cancelButtonText: '取消',
                        //     type: 'warning'
                        //     }).then(() => {
                        //     this.$message({
                        //         type: 'success',
                        //         message: '删除成功!'
                        //     });
                        //     }).catch(() => {
                        //     this.$message({
                        //         type: 'info',
                        //         message: '已取消删除'
                        //     });          
                        // });
					}
				}).catch(() => {
					console.error("获取失败")
                })
                this.handleAccess()
			},

            handleClickReturn(row) {
                this.$router.push({
					name: 'ProjectMeasurement',
					params:row
				})
            },
        },
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

	.grid-content {
		border-radius: 4px;
		min-height: 32px;
		margin: 0.625rem;
	}

    .title-text {
		color: #31708f;
		line-height: 32px;
	}

    .title-box {
		background-color: #d9edf7;
	}

    .button-box {
        display: flex;
        justify-content:flex-end;
        margin-right: 50px;

    }

    .el-upload {
        
		// display: block !important;
		margin: 10px 0;
	}

    .el-upload-box {
        text-align:center
    }

    .button-box5 {
        margin: 30px 0;
    }

    .text-box {
        margin: 0px 30px 30px;
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
    }
</style>
