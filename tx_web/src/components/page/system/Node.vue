<template>
  <div class="main">
    <!-- <div class="table"> -->
    <table width="650" height="680"  cellspacing="0">
		  <caption color:black align="center" style="padding-top: 11px;">
        <strong class="title">课堂教学和实验课程请假条
        </strong>
      </caption>
      </br>
      </br>

			<p  class="div1 font-style" colspan="7">尊敬的<u>孟庆元</u>老师：</p>
      <p  class="div2 font-style" colspan="7">我因<u>进口高档打开看和打击很后大大</u>，不能参加<u>10</u>月<u>23</u>日<u>1234</u>节</p>
      <p  class="div3 font-style" colspan="7">的<u>单片机接口原理大大大大</u>课程，特向你请假，请予批准。</p>
      <p  class="div2 font-style" colspan="7">此致</p>
      <br>
      <br>
      <p  class="div1 font-style" colspan="7">敬礼！</p>
      <p  class="div2 font-style" colspan="7">请假人：<u>陈晓滔</u> 学 号:<u>2017010302113</u> 班 级：<u>17电信B班</u></p>
      <p  class="div4 font-style" colspan="7">201<u>9</u>年<u>10</u>月<u>23</u>日</p>
      <p  class="div2 font-style" colspan="7">批假老师签名：</p>
      <br>
      <p  class="div2 font-style" colspan="7">201<u>9</u>年<u>10</u>月<u>31</u>日</p>
      <p  class="div4 font-style" colspan="7">*此联任课老师存根</p>
      <p  class="div4 font-style" style="font-style:oblique;" colspan="7">沿&nbsp;&nbsp;此&nbsp;&nbsp;处&nbsp;&nbsp;剪&nbsp;&nbsp;开</p>
      <p style="border-bottom:dotted 1px #000;margin-top:-15px;"></p>
      <br>
      </br>
      <strong class="title" style="margin-left:200px;">课堂教学和实验课程请假条</strong>
      </br>
      </br>
      </br>
			<p  class="div1 font-style" colspan="7">尊敬的<u>孟庆元</u>老师：</p>
      <p  class="div2 font-style" colspan="7">我因<u>进口高档打开看和打击很后大大</u>，不能参加<u>10</u>月<u>23</u>日<u>1234</u>节</p>
      <p  class="div3 font-style" colspan="7">的<u>单片机接口原理大大大大</u>课程，特向你请假，请予批准。</p>
      <p  class="div2 font-style" colspan="7">此致</p>
      <br>
      <br>
      <p  class="div1 font-style" colspan="7">敬礼！</p>
      <p  class="div2 font-style" colspan="7">请假人：<u>陈晓滔</u> 学 号:<u>2017010302113</u> 班 级：<u>17电信B班</u></p>
      <p  class="div4 font-style" colspan="7">201<u>9</u>年<u>10</u>月<u>23</u>日</p>
      <p  class="div2 font-style" colspan="7">批假老师签名：</p>
      <br>
      <p  class="div2 font-style" colspan="7">201<u>9</u>年<u>10</u>月<u>31</u>日</p>
      <p  class="div4 font-style" colspan="7">*此联班委存根</p>
	  </table>
    <!-- </caption> -->
  </div>
</template>

<script>
export default {
  name: "printTable",
  data () {
    return {
      systemDate:"",
      timer:"",
      classroom:''
    }
  },
  mounted () {
    
   
  },
  methods:{
    getTime(){
      let nowDate = new Date();
      let date = {
        year: nowDate.getFullYear(),
        month: nowDate.getMonth() + 1,
        date: nowDate.getDate(),
      }
      this.systemDate = date.year + '-'  + date.month + '-' + date.date;         
		}
  },
  created(){
    
    this.getTime();
    clearTimeout(this.timer);  //清除延迟执行 
    this.timer = setTimeout(()=>{   //设置延迟执行
      window.print();
      window.close();
    },100)
	}
};
</script>

<style  media="print" scoped>
        td{
             width: 80px;
          }
        .f3 {
            font-family: serif;          
        }
        .title{
            font-size:21px;
            font-family:宋体;
            font-weight:bold;
        }
        .font-style {
            font-size:16px;
            font-family:宋体;
            margin:15px;
            margin-left:80px;   
        }
        .div2{
            padding-left: 7%;
            /* padding-top:  1%; */
        }
        .div4{
            padding-left: 55%;
            /* padding-top: 40px; */
        }
        .div5{
            padding-left: 66%;
        }
        .div6 {
          width: 300px;
          border-bottom: 1px solid black;
        }    
</style>
