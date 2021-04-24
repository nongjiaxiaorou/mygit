<template>
    <el-input placeholder="请输入搜索条件" class="input" v-model="search_world" suffix-icon="el-icon-search">
    </el-input>
</template>

<script>
export default {
    data(){
        return{
            search_world:''
        }
    },
    watch:{
        search_world:function(val){
            this.$emit('search_key',val)
        }
    },
    computed:{

    },
}
</script>

<style lang="scss" scoped>
    .input /deep/input.el-input__inner{
        width:365px;
        height:38px;
        float: right;
        border:1px solid rgba(153,153,153,1);
      }
      .input /deep/ i.el-icon-search:before{
          color: #999999;
          font-size: large;
      }
</style>