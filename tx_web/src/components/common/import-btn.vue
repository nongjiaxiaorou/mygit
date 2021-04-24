<template>
	<el-button type="primary" title="导入学生信息" size icon="el-icon-folder-add" @click="handleImport()">批量导入
	</el-button>
</template>

<script>
	export default {
		data(){
			return{
				
			}
		},
		methods:{
			handleImport(){
				this.$emit('handleImportChaild')
			}
		}
	}
</script>

<style>
</style>