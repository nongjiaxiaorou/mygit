<template>
    <view >
		
		<view class="uni-list">
            <view class="uni-list-cell">
                <view class="uni-list-cell-left">
                    {{datename}}
                </view>
                <view class="uni-list-cell-db">
                    <picker mode="date" :value="date" :start="startDate" :end="endDate" @change="bindDateChange">
                        <view class="uni-input">{{date}}</view>
                    </picker>
                </view>
            </view>
        </view>
 
    </view>
</template>
 
<script>
    export default {
		props:['datename','dateAppoint'],
        data() {
			const currentDate = this.getDate('currentDate')
            return {
				date: currentDate,
				time: '12:01'
            }
        },
        onLoad(option) {},
		mounted() {
			this.$emit('date', this.date)
		},
		computed: {
			startDate() {
				return this.getDate('start');
			},
			endDate() {
				return this.getDate('end');
			}
		},
        methods: {
			//日期选择器
			bindDateChange: function(e) {
				this.date = e.target.value
				this.$emit('date', this.date)
			},
			//获取N天后的日期
			GetDateStr(AddDayCount) {
				var dd = new Date();
				dd.setDate(dd.getDate() + AddDayCount); 
				var y = dd.getFullYear();
				var m = (dd.getMonth() + 1) < 10 ? "0" + (dd.getMonth() + 1) : (dd.getMonth() + 1); 
				var d = dd.getDate() < 10 ? "0" + dd.getDate() : dd.getDate(); 
				return y + "-" + m + "-" + d;
			},
			//获取指定日期
			GetDateAppoint(dateAppoint) {
				var dd = new Date(dateAppoint);
				dd.setDate(dd.getDate()); 
				var y = dd.getFullYear();
				var m = (dd.getMonth() + 1) < 10 ? "0" + (dd.getMonth() + 1) : (dd.getMonth() + 1); 
				var d = dd.getDate() < 10 ? "0" + dd.getDate() : dd.getDate(); 
				return y + "-" + m + "-" + d;
			},
			getDate(type) {
				var type = (typeof type=='string')?type:''
				// console.log(type+'--->'+ typeof type)
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let day = date.getDate(); 
				if(this.datename=='截止日期：'){
					return this.GetDateStr(3);
				}
				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year + 2;
				}else if(type.indexOf('-')>0){
					return this.GetDateAppoint(type);
				}
				month = month > 9 ? month : '0' + month;;
				day = day > 9 ? day : '0' + day;
				return `${year}-${month}-${day}`;
			},
        },
		watch: {
			dateAppoint(newValue, oldValue) {
				this.date = this.getDate(this.dateAppoint)
				this.$emit('date', this.date)
			}
		},
    }
</script>
 
<style lang="scss">
</style>
 