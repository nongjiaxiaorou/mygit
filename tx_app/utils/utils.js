export default {
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
	//获取当前时间串
	getNowDateStr: function() {
		this.Date = new Date();
		// 获取当前年
		this.year = this.Date.getFullYear();
		// 获取当前月
		this.month = this.Date.getMonth() + 1;
		// 获取当前日
		this.date = this.Date.getDate();
		return this.year + '' + this.month + '' + this.date;
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
		const time = y + '-' + m + '-' + d + '-' + hh + '-' + mm + '-' + ss
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
	//base64转ArrayBuffer
	b64oblobTourl(base64Data) {
		//console.log(base64Data);//data:image/png;base64,
		var byteString;
		if (base64Data.split(',')[0].indexOf('base64') >= 0){
			byteString = atob(base64Data.split(',')[1].replace(/%0A|\s/g,'')); //base64 解码
		}else {
			byteString = unescape(base64Data.split(',')[1]);
		}
		var mimeString = base64Data.split(',')[0].split(':')[1].split(';')[0]; //mime类型 -- image/png
		// var arrayBuffer = new ArrayBuffer(byteString.length); //创建缓冲数组
		// var ia = new Uint8Array(arrayBuffer);//创建视图
		var ia = new Uint8Array(byteString.length); //创建视图
		// console.log(byteString.length)
		for (var i = 0; i < byteString.length; i++) {
			ia[i] = byteString.charCodeAt(i);
		}
		var blob = new Blob([ia], {type: mimeString});
		let url = window.URL.createObjectURL(blob)
		return url;
	},
	//数字转中文
	toChinesNum_floor(dx_num, ds_num) {
		var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
		var unit = ["", "十", "百", "千", "万"];
		dx_num = parseInt(dx_num);
		ds_num = parseInt(ds_num);
		let dx_arr = []
		let ds_arr = []
		var getWan = function(temp) {
			var strArr = temp.toString().split("").reverse();
			var newNum = "";
			for (var i = 0; i < strArr.length; i++) {
				newNum = (i == 0 && strArr[i] == 0 ? "" : (i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? "" : changeNum[strArr[
					i]] + (strArr[i] == 0 ? unit[0] : unit[i]))) + newNum;
			}
			return newNum;
		}
		for (var i = 0; i < dx_num; i++) {
			if(i==0){
				dx_arr[i] = '基础层'
			}else{
				dx_arr[i] = "负" + getWan(i) + "层"
			}
		}
		for (var i = 0; i < ds_num; i++) {
			if(i==ds_num-1){
				ds_arr[i] = "屋面层"
			}else{
				ds_arr[i] = getWan(i + 1) + "层"
			}
		}
		//	console.log(dx_arr.concat(ds_arr))
		return dx_arr.concat(ds_arr);
	},
	toChinesNum_unit(num) {
		var changeNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九']; //changeNum[0] = "零"
		var unit = ["", "十", "百", "千", "万"];
		num = parseInt(num);
		let dx_arr = []
		let ds_arr = []
		var getWan = function(temp) {
			var strArr = temp.toString().split("").reverse();
			var newNum = "";
			for (var i = 0; i < strArr.length; i++) {
				newNum = (i == 0 && strArr[i] == 0 ? "" : (i > 0 && strArr[i] == 0 && strArr[i - 1] == 0 ? "" : changeNum[strArr[
					i]] + (strArr[i] == 0 ? unit[0] : unit[i]))) + newNum;
			}
			return newNum;
		}
		return getWan(num);
	},
	//BlobUrl转blob数据
	objectURLToBlob(blodurl) {
		uni.showLoading({
			title: '压缩中...'
		});
		return new Promise((resolve, reject) => {
			var http = new XMLHttpRequest();
			http.open('GET', blodurl, true);
			http.responseType = 'blob';
			http.onload = function(e) {
			    if (this.status == 200 || this.status === 0) {
				console.log('blod数据',this.response);
				// 在将blod数据转为file
				let files = new window.File([this.response], 'file.name', { type: 'image' });
				console.log('blod数据转换file',files);
				resolve(files);
				uni.hideLoading();
		            }
			};
			http.send();
		});
	}
};
