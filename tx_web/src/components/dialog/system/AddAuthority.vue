<template>
	<div class="AddPower">
	    <el-dialog title="授予权限" :modal-append-to-body="false" customClass="customClass" :visible.sync="authorityDialog.show">
			<el-card class="box-card flex flex_class">
				<el-transfer
					:titles="['未赋予的权限', '已赋予的权限']"
					v-model="value"
					:data="data"
					@change="saveChange">
				</el-transfer>
			</el-card>
		</el-dialog>
	</div>
</template>
 
<script>
export default {
    name: 'AddAuthority',
    props: {
        authorityDialog: Object,
		form: Object
    },
    data() {
		return {
			data: [],
			value: []
			
		};
    },
    mounted(){
    },
    methods: {
		saveChange(e){ //将变化的权限值存储到数据库
			// console.log(e)
			// console.log(e.join('|'))
			var that = this;
			var fd = new FormData()
			fd.append('flag','updateAuthorityFlag')
			fd.append('account',that.form.account)
			fd.append('authority',e.join('|'))
			// console.log(that.form.account)
			console.log(e.join('|'))
			this.$axios.post(that.$adminUrl+`/system/User_manage.php`,fd).then(res =>{
			    // console.log(res.data)
			    if(res.data.code){
					this.$emit('handleAddAuthorityChild')
					this.$message.success('您已更新了账号权限！')
			    }else{
					this.$message.error('当前网络不稳定,请更换网络后重试!')
				}
			})
		},
		getArrDifference(arr1, arr2) { //获取两个数组中不同的元素
			return arr1.concat(arr2).filter(function(v, i, arr) {
				return arr.indexOf(v) === arr.lastIndexOf(v);
			});
		},
		getArrEqual(arr1, arr2) {//获取两个数组中相同的元素
			let newArr = [];
			for (let i = 0; i < arr2.length; i++) {
				for (let j = 0; j < arr1.length; j++) {
					if(arr1[j] === arr2[i]){
						newArr.push(arr1[j]);
					}
				}
			}
			return newArr;
		}
    },
	watch:{
		form:{
			handler(newName, oldName) {//获取当前权限情况
			// console.log(newName)
				const data = [];
				const authority = newName.authority;
				const authorityArr = ['用户管理','公司集团定义','违章数据库管理','实测实量标准','工程类别定义','系统操作日志'];
				var arr = authority.split('|')
				// console.log(this.getArrDifference(authorityArr,arr))
				var differenceArr = this.getArrDifference(authorityArr,arr)
				var equalArr = this.getArrEqual(authorityArr,arr)
				for (let i = 0; i < authorityArr.length; i++) {
					data.push({
						key: authorityArr[i],
						label: authorityArr[i]
					});
				}
				this.data = data
				
				this.value = equalArr
				// this.data = this.getArrDifference(authorityArr,arr)
				// console.log(typeof this.getArrDifference(authorityArr,arr));
				// for (let i = 0; i < arr.length; i++) {
				//   data.push({
				// 	key: arr[i],
				// 	label: arr[i]
				//   });
				// }
				// this.data = data
			},
		}
	}
};
</script>
<style>
    .customClass{
        width: 50%;
    }
	.flex_class{
		/* flex-direction: column; */
		/* justify-content: space-around; */
		/* height: 100%; */
		justify-content: center;
	}
</style>