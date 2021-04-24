<template>
	<div class="AddPro">
		<el-dialog title="新建小组" :modal-append-to-body="false" customClass="customClass" :visible.sync="dialogAdd.show">
			<el-form :model="formDate" ref="dialogAdd" label-width="80px" :rules="formrules">
				<el-form-item label="小组名称" prop="groupName" class="px-2">
					<el-input v-model="formDate.groupName"></el-input>
				</el-form-item>
				<div class="flex">
					<el-form-item label="组长" prop="groupLeader" class="flex-1 px-2">
						<el-input v-model="formDate.groupLeader"></el-input>
					</el-form-item>
					<el-form-item label="副组长" prop="groupDeputy" class="flex-1 px-2">
						<el-select multiple collapse-tags v-model="formDate.groupDeputy" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item, index) in groupDeputyList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
				<div class="flex">
					<el-form-item label="组员" prop="groupMember" class="flex-1 px-2">
						<el-select multiple collapse-tags v-model="formDate.groupMember" placeholder="请选择" style="width: 100%;">
							<el-option v-for="(item,index) in groupMemberList" :key="item.value" :label="item.label" :value="item.value">
							</el-option>
						</el-select>
					</el-form-item>
				</div>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormClose('dialogAdd')">取 消</el-button>
				<el-button type="primary" @click="dialogFormAdd()">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'AddUser',
		props: {
			dialogAdd: Object
		},
		data() {
			return {
				formDate: {
					groupName: '',//小组名称
					groupLeader:'',//组长
					groupDeputy:[],//副组长
					groupMember:[],//组员
				},
				groupDeputyList: [],
				groupMemberList:[],
				userInfo:[],
				groupLeaderValue:'',
				formrules: {
					// username: [{ required: true, message: '班级信息不能为空', trigger: 'blur' }]
				}
			};
		},
		mounted() {
			this.getUser()
			this.userInfo = JSON.parse(localStorage.getItem('userInfo'))
			this.formDate.groupLeader = this.userInfo.userName+'/'+this.userInfo.phone+'/'+this.userInfo.company
			this.groupLeaderValue = this.userInfo.userName+'/'+this.userInfo.phone+'/'+this.userInfo.company+'/'+this.userInfo.userId+'/'+this.userInfo.account
		},
		methods: {
			//获取所有用户
			getUser(){
				const that = this
				let fd = new FormData()
				fd.append('flag','getUser')
				that.$axios.post(that.$adminUrl +`/project/detail.php`,fd).then(res => {
					console.log(res)
					let length = res.data.data.length
					for(let i=0;i<length;i++){
						this.groupMemberList.push({
							value:res.data.data[i].name+"/"+res.data.data[i].phone+"/"+res.data.data[i].companyName+"/"+res.data.data[i].id+"/"+res.data.data[i].account,
							label:res.data.data[i].name+"/"+res.data.data[i].phone+"/"+res.data.data[i].companyName,
							userId:res.data.data[i].id
						})
						console.log(this.groupMemberList);
						this.groupDeputyList = this.groupMemberList
					}
				}).catch({
					
				})
			},
			//模态框的新增
			dialogFormAdd() {
				const that = this
				var fd = new FormData();
				fd.append('flag', 'addGroup');
				fd.append('formDate', JSON.stringify(this.formDate));
				fd.append('groupLeaderValue', this.groupLeaderValue);
				
				that.$axios.post(that.$adminUrl + `/inspect/AddInsGroup.php`, fd).then((res) => {
					console.log(res)
				}).catch(() => {
				
				})
			},
			
			//关闭模态框
			dialogFormClose(dialogAdd) {
				this.FormTabelEmpty();
				this.dialogAdd.show = false;
			},
			//清空表单
			FormTabelEmpty() {
				for (var i in this.formDate) {
					this.formDate[i] = '';
				}
				this.formDate.rge_time = this.commonFunc.getNowDate();
			},
		}
	};
</script>
<style>
	.customClass {
		width: 40%;
	}
</style>
