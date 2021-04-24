const screeHeight= document.body.clientHeight/2;
export default {
	screeHeight,
	a: function(index) {
		console.log("调用公共方法成功")
	},
	//获取当前时间
	getNowDate: function() {
		this.Date = new Date();
		// 获取当前年
		this.year = this.Date.getFullYear();
		// 获取当前月
		this.month = this.Date.getMonth() + 1;
		// 获取当前日
		this.date = this.Date.getDate();
		return this.year + '-' + this.month + '-' + this.date + ' ' + ' ';
	},
	//格式化时间精确到秒
	dateFormat: function(dateData) {
		var date = new Date(dateData)
		var y = date.getFullYear()
		var m = date.getMonth() + 1
		m = m < 10 ? ('0' + m) : m
		var d = date.getDate()
		d = d < 10 ? ('0' + d) : d
		var hh = date.getHours()
		hh = hh < 10 ? ('0' + hh) : hh
		var mm = date.getMinutes()
		mm = mm < 10 ? ('0' + mm) : mm
		var ss = date.getSeconds()
		ss = ss < 10 ? ('0' + ss) : ss
		const time = y + '-' + m + '-' + d + ' ' + hh + ':' + mm + ':' + ss
		return time
	},
	//对象数组去重ES5写法
	Es5duplicate(arr, type) {
		var newArr = [];
		var tArr = [];
		if (arr.length == 0) {
			return arr;
		} else {
			if (type) {
				for (var i = 0; i < arr.length; i++) {
					if (!tArr[arr[i][type]]) {
						newArr.push(arr[i]);
						tArr[arr[i][type]] = true;
					}
				}
				return newArr;
			} else {
				for (var i = 0; i < arr.length; i++) {
					if (!tArr[arr[i]]) {
						newArr.push(arr[i]);
						tArr[arr[i]] = true;
					}
				}
				return newArr;
			}
		}
	},
	//删除数组对象指定值
	/*
	 * 根据数组对象属性删除对应项
	 * @param {Array} arr - 数组对象
	 * @param {String} attr - 属性
	 * @param {} value - 属性值
	 * @return void
	 */
	removeByValue(arr, attr, value) {
		var index = 0;
		for (var i in arr) {
			if (arr[i][attr] == value) {
				index = i;
				break;
			}
		}
		arr.splice(index, 1);
	}
};
