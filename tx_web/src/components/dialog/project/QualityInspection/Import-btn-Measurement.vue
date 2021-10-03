<template>
	<el-button type="primary" class="Import-btn-Measurement" title="Excel表导入测点数据" size icon="el-icon-folder-add" @click="handleImport()">Excel表导入测点数据
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
.Import-btn-Measurement{
        margin-top: 10px;
        margin-bottom: 50px;
    }
</style>