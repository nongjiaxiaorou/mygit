<template>
     <el-button type="primary" :title="title" size icon="el-icon-plus" @click="handleAdd()">{{title==null?'新增':title}}</el-button>
</template>
<script>
export default {
	props:{
		buttonTitle:String
	},
    data(){
        return{
			title:this.buttonTitle
        }
     },
     methods:{
          handleAdd(){
               this.$emit('handleAddChild')
          }
     }
}
</script>
<style>

</style>