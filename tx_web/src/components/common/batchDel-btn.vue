<template>
     <el-button type="danger" title="批量删除" size icon="el-icon-delete" @click="handleDel()">批量删除</el-button>
</template>
<script>
export default {
     data(){
          return{

          }
     },
     methods:{
          handleDel(){
               this.$emit('handleDelChild')
          }
     }
}
</script>
<style>

</style>