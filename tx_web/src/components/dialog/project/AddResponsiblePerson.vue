<template>
	<div class="AddPerson">
		<el-dialog title="新增责任人" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogNewResPerson.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
				<el-row v-for="(item,index) in leaderNodeList" :key="'leader'+index" >
					<el-col :span="15">
						<div class="grid-content bg-purple">
							<el-form-item label="栋号长" prop="leaderPerson" class="flex-1 px-2">
								<el-select v-model="item.selValue" filterable placeholder="请选择">
									<el-option v-for="(option,index1) in item.nodeValue" :key="index1" :label="option.label" :value="option.value">
									</el-option>
								</el-select>
							</el-form-item>
						</div>
					</el-col>
				</el-row>
				<el-row v-for="(item,index) in qualityOptions" :key="'quality'+index" >
					<el-col :span="15">
						<div class="grid-content bg-purple">
							<el-form-item label="质量员" prop="qualityPerson" class="flex-1 px-2">
								<el-select v-model="item.selValue" filterable placeholder="请选择">
									<el-option v-for="(option,index2) in item.nodeValue" :key="index2" :label="option.label" :value="option.value">
									</el-option>
								</el-select>
							</el-form-item>
						</div>
					</el-col>
					<el-col :span="3" :class="index===0?'hiddenButton':''">
						<div class="grid-content bg-purple">
							<el-button style="margin-left: 10px;" type="danger" icon="el-icon-delete" @click="deleteNode(qualityOptions,index)">删除</el-button>
						</div>
					</el-col>
					<el-col :span="3" :class="index===0?'':'hiddenButton'">
						<div class="grid-content bg-purple">
							<el-button style="margin-left: 10px;" @click="addPerson('qualityFlag')">新增</el-button>
						</div>
					</el-col>
				</el-row>
						
				<el-row v-for="(item,index) in constructionOptions" :key="'construction'+index" >
					<el-col :span="15">
						<div class="grid-content bg-purple">
							<el-form-item label="施工员" prop="constructionPerson" class="flex-1 px-2">
								<el-select v-model="item.selValue" filterable placeholder="请选择">
									<el-option v-for="(option,index3) in item.nodeValue" :key="index3" :label="option.label" :value="option.value" >
									</el-option>
								</el-select>
							</el-form-item>
						</div>
					</el-col>
					<el-col :span="3" :class="index===0?'hiddenButton':''">
						<div class="grid-content bg-purple">
							<el-button style="margin-left: 10px;" type="danger" icon="el-icon-delete" @click="deleteNode(constructionOptions,index)">删除</el-button>
						</div>
					</el-col>
					<el-col :span="3" :class="index===0?'':'hiddenButton'">
						<div class="grid-content bg-purple">
							<el-button style="margin-left: 10px;" @click="addPerson('constructionFlag')">新增</el-button>
						</div>
					</el-col>
				</el-row>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogAdd')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
import {$EventBus} from "./QualityInspection/event-bus.js";
	export default {
		name: 'AddNewBuild',
		props: {
			dialogNewResPerson: Object,
			registerBaseData: Object,
			sectionData: Array,
			currentRow: Object,
			buildPerson: Object
		},
		data() {
			return {
				nodeList: [],
				msgFormSon: [],
				formDate: {
					build: '',
					undergroundNumber: '',
					abovegroundNumer: '',
					unit: ''
				},
				value: '',
				formrules: {
					// 	build: [{ required: true, message: '栋号不能为空', trigger: 'blur' }]
				},
				leaderNodeList: [{
					// id: '',
					selValue: '',
					post: '栋号长',
					nodeValue: []
				}],
				qualityOptions: [{
					selValue: '',
					post: '质量员',
					nodeValue: []
				}],
				constructionOptions: [{
					selValue: '',
					post: '施工员',
					nodeValue: []
				}]
				// qualityOptions: [{value: '2',label: '双皮奶'}],
				// constructionOptions: [{value: '3',label: '双皮奶'}]
			};
		},
		mounted() {
			this.getAllPerson() //获取该工程下的管理人员
			let nodeList = sessionStorage.getItem('nodeList')
			this.nodeList = JSON.parse(nodeList)
			console.log(this.nodeList)
			console.log(this.buildPerson)

			this.$EventBus.$on("SubmittedManagement", (nodeList) => {
				this.nodeList = nodeList.nodeList
				console.log(this.nodeList.length)
				// for (var key in leaderNodeList){
				// 	var i = 0;
				// 	for(var a = 0;a<nodeList.length;a++){
				// 		if(){

				// 		}
				// 	}

				// }
            });

		},
		methods: {
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				let registerBaseData = sessionStorage.getItem('registerBaseData')
				registerBaseData = JSON.parse(registerBaseData)
				// let nodeList = sessionStorage.getItem('nodeList')
				// that.nodeList = JSON.parse(nodeList)
				// console.log(that.nodeList)
				console.log(that.currentRow)
				console.log(that.currentRow.id)
				fd.append('flag', 'addNewBuildPerson');
				fd.append('proTimeStamp', registerBaseData.timeStamp);
				fd.append('leaderNodeList', JSON.stringify(that.leaderNodeList));
				fd.append('qualityOptions', JSON.stringify(that.qualityOptions));
				fd.append('constructionOptions', JSON.stringify(that.constructionOptions));
				fd.append('buildId', that.currentRow.id);
				// console.log(that.qualityOptions)
				that.$refs.dialogAdd.validate((valid) => {
					if (valid) {
						that.$axios.post(that.$adminUrl + `/project/projectManagement.php`, fd).then((res) => {
							console.log(res.data)
							if (res.data.code) {
								this.$message({
									type: 'success',
									message: '栋号责任人已定义完成！'
								});
								that.dialogNewResPerson.show = false
								that.$emit('handleNewBuildPersonChild')
								// that.$emit('func',JSON.stringify(that.leaderNodeList))
								// that.$emit('func',JSON.stringify(that.qualityOptions))
								// that.$emit('func',JSON.stringify(that.constructionOptions))
								that.FormTabelEmpty();
								that.$emit('showCityName',);
							}
							else {
								this.$message.error('定义错误');
							}
						}).catch(() => {

						})
					} else {
						console.log('error submit!!');
					}
				});
				
				// this.getAllPerson();
			},

			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogNewResPerson.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
			addPerson(param) {
				console.log(param)
				if (param == "qualityFlag") {
					let obj = {
						selValue: '',
						post: '质量员',
						nodeValue: []
					}
					obj.nodeValue = this.qualityOptions[0].nodeValue
					this.qualityOptions.push(obj)

				} else if (param == "constructionFlag") {
					let obj = {
						selValue: '',
						post: '施工员',
						nodeValue: []
					}
					obj.nodeValue = this.constructionOptions[0].nodeValue
					this.constructionOptions.push(obj)
				}
				// this.getAllPerson()
				
			},
			deleteNode(obj,index) {
				// console.log(obj[index])
				let fd = new FormData()
				let personId = obj[index].selValue.split('/')[3]
				console.log(personId)
				fd.append('flag','deleteBuildPerson')
				fd.append('personId',personId)
				this.$axios.post(this.$adminUrl+`/project/projectManagement.php`,fd).then(res=>{
					this.$message.success('删除成功！')
					obj.splice(index, 1) // 点击调用删除方法，在nodeList数组里删除index这个下标项，逗号后面的1就代表删除当前的1项
				}).catch({
					
				})
			},
			//获取所有责任管理人员
			getAllPerson(){
				const that = this
				console.log(that.leaderNodeList.nodeValue)
				let fd = new FormData()
				fd.append('flag','getAllPerson')
				let sessionData = sessionStorage.getItem('registerBaseData')
				let registerBaseData = JSON.parse(sessionData)
				fd.append('proTimeStamp', registerBaseData.timeStamp)
				that.$axios.post(that.$adminUrl +`/project/projectManagement.php`,fd).then(res => {
					console.log(res.data.data)
					this.leaderNodeList[0].nodeValue = []
					console.log(this.nodeList);
					for(var i = 0;i<this.nodeList.length;i++){						
						for(var a = 0;a<this.qualityOptions.length;a++){
							this.qualityOptions[a].nodeValue = []
						}

						for(var b = 0;b<this.constructionOptions.length;b++){
							this.constructionOptions[b].nodeValue = []
						}
					}
			
					if(res.data.code){
						// console.log(res.data.data)
						var resdata = res.data.data
						for (var key in resdata){
							var personArr = resdata[key]
							console.log(personArr)
						    if(key=="栋号长"){
								for(var obj in personArr){
									// if(personArr[obj].selValue!=""){
									// 	this.leaderNodeList[0].selValue = personArr[obj].selValue
									// }
									this.leaderNodeList[0].nodeValue.push({
										value: personArr[obj].value,
										label: personArr[obj].label
									})
								}
							}else if(key=="质量员"){
								for(var obj in personArr){
									// console.log(personArr[obj].selValue)
									// if(personArr[obj].selValue!=""){
									// 	this.qualityOptions[0].selValue = personArr[obj].selValue
									// }
									let i = 0;									
									this.qualityOptions[i].nodeValue.push({
										value: personArr[obj].value,
										label: personArr[obj].label
									})
									i++;
									// console.log(this.qualityOptions[0].nodeValue)
								}
							}else if(key=="施工员"){
								for(var obj in personArr){
									let i = 0;
									// if(personArr[obj].selValue!=""){
									// 	this.constructionOptions[0].selValue = personArr[obj].selValue
									// }
									this.constructionOptions[i].nodeValue.push({
										value: personArr[obj].value,
										label: personArr[obj].label,										
									})
									console.log(this.constructionOptions[i].nodeValue)
									i++;
									
								}
							}
						}
						that.msgFormSon.push(
							that.leaderNodeList,
							that.qualityOptions,
							that.constructionOptions
						)
						// that.$emit('func',that.msgFormSon)
						// that.$emit('func',that.qualityOptions)
						// that.$emit('func',that.constructionOptions)
					}else{
					}
				}).catch({
					
				})
			},
		},
		watch: {

			nodeList:
				function() {
					this.getAllPerson()
				},

			"currentRow": {
				handler(newValue, oldValue) {
					// console.log(newValue)
				}
			},
			// "buildPerson": {
			// 	handler(newValue, oldValue) {
			// 		console.log(newValue)
			// 		for(var key in newValue){
			// 			var personArr = newValue[key]
			// 			if(key=="栋号长"){
			// 				for(var personKey in personArr){
			// 					let obj = {
			// 						selValue: personArr[personKey].selValue,
			// 						post: '栋号长',
			// 						nodeValue: []
			// 					}
			// 					// console.log(this.leaderNodeList[0].nodeValue)
			// 					obj.nodeValue = this.leaderNodeList[0].nodeValue
			// 					this.leaderNodeList = []
			// 					this.leaderNodeList.push(obj)
			// 				}
			// 			}else if(key=="质量员") {
			// 				if(personArr.length>1){
			// 					var saveNodeValue = this.qualityOptions[0].nodeValue
			// 					this.qualityOptions = []
			// 					for(var i=0;i<personArr.length;i++){
			// 						let obj = {
			// 							selValue: personArr[i].selValue,
			// 							post: '质量员',
			// 							nodeValue: []
			// 						}
			// 						obj.nodeValue = saveNodeValue
			// 						this.qualityOptions.push(obj)
			// 					}
			// 				}else{
			// 					for(var i=0;i<personArr.length;i++){
			// 						let obj = {
			// 							selValue: personArr[i].selValue,
			// 							post: '质量员',
			// 							nodeValue: []
			// 						}
			// 						// console.log(this.leaderNodeList[0].nodeValue)
			// 						obj.nodeValue = this.qualityOptions[0].nodeValue
			// 						this.qualityOptions = []
			// 						this.qualityOptions.push(obj)
			// 					}
			// 				}
			// 			}else if(key=="施工员") {
			// 				if(personArr.length>1){
			// 					var saveNodeValue = this.constructionOptions[0].nodeValue
			// 					this.constructionOptions = []
			// 					for(var i=0;i<personArr.length;i++){
			// 						let obj = {
			// 							selValue: personArr[i].selValue,
			// 							post: '施工员',
			// 							nodeValue: []
			// 						}
			// 						obj.nodeValue = saveNodeValue
			// 						this.constructionOptions.push(obj)
			// 					}
			// 				}else{
			// 					for(var i=0;i<personArr.length;i++){
			// 						let obj = {
			// 							selValue: personArr[i].selValue,
			// 							post: '施工员',
			// 							nodeValue: []
			// 						}
			// 						// console.log(this.leaderNodeList[0].nodeValue)
			// 						obj.nodeValue = this.constructionOptions[0].nodeValue
			// 						this.constructionOptions = []
			// 						this.constructionOptions.push(obj)
			// 					}
			// 				}
			// 			}
			// 		}
			// 	}
			// }
		}
	};
</script>
<style>
	.customClass {
		width: 30%;
	}
	.hiddenButton {
		display: none;
	}
</style>
