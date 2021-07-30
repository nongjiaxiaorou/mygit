<template>
	<view style="background-color: #f5f5f5;height: 100vh;">
		<!-- <search v-if="searchIf" ref="sea" @confirm="confirmSearch" /> -->
		<view class="all">
			<view class="sheng">
				<view class="title">
					<scroll-view scroll-x="true" style="width: 100%;overflow: hidden;white-space: nowrap;" scroll-left="100">
						<view v-for="(item,index) in parent" style="display:inline-block" :key="index">
							<view style='display:inline-block' v-if="index==0" @click="backTree(item,-1)" :class="index==parent.length-1&&!isre?'none':'active'">全部</view>
							<!-- <view style='display:inline-block' v-if="index==0&&isre" @click="backTree(item,-2)" :class="index==parent.length-1&&isre?'none':'active'">
								<text style='display:inline-block;margin: 0 6px;color: #D0D4DB;' class="iconfont icon-arrow-right"></text>
								搜索结果
							</view> -->
							<view style='display:inline-block' @click="backTree(item,index)">
								<text style='display:inline-block;margin: 0 6px;color: #D0D4DB;' v-if="index!=0" class="iconfont icon-right"></text>
								<text style='display:inline-block' :class="index==parent.length-1?'none':'active'">
									{{item[props.label]}}
								</text>
							</view>
						</view>
					</scroll-view>
				</view>
				<view class="common" v-for="(item, index) in tree" @click="toChildren(item)" :key="index">
					<label class="content">
						<!-- <view class="checkbox" v-if="isCheck" @click.stop="checkboxChange(item,index)">
							<image v-if="item.checked" src="./icon/check.png" class="img"></image>
							<image v-else src="./icon/nocheck.png" class="img"></image>
						</view> -->
						<view class="person" v-if="item.user">
							{{item[props.label].substring(item[props.label].length-2)}}
						</view>
						<view :class="{ 'word': true, 'label-color': item.isSel }">{{item[props.label]}}</view>
					</label>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import search from '@/components/common/build-tree/build-search.vue'
	import uniNavBar from "@/components/uni-app/uni-nav-bar/uni-nav-bar.vue"
	// import url("./css/icon.css");
	export default {
		props: {
			trees: { 
				type: Array,
				default: () => []
			},
			isCheck: {
				type: Boolean,
				default: () => false
			},
			checkList: {
				type: Array,
				default: () => []
			},
			searchIf:{
				type:Boolean,
				default:()=>true
			},
			props: {
				type: Object,
				default: () => {
					return {
						label: 'name',
						children: 'children'
					}
				}
			}
		},
		data() {
			return {
				isre: false,
				tree: this.trees,
				allData: this.trees,
				parent: [1],
				searchResult: [],
				buildSelData: {}
			}
		},
		components: {
			search
		},
		mounted() {
			let that = this
			uni.getStorage({
				key: 'buildInfo',
				success: function (res) {
					that.buildSelData = res.data
				}
			})
		},
		methods: {
			checkboxChange: function(item, index) {
				var that = this;
				let status = !that.tree[index].checked
				that.$set(that.tree[index], 'checked', status)
				if (that.checkList.length <= 0) {
					that.checkList.push(that.tree[index])
				} else {
					let status = false
					for (var i = 0, len = that.checkList.length; i < len; i++) {
						if (that.checkList[i].id === that.tree[index].id) {
							that.checkList.splice(i, 1)
							status = true
							break
						}
					}
					if (!status) {
						that.checkList.push(that.tree[index])
					}
				}
				that.$emit('sendValue', that.checkList)
			},
			toChildren(item) {
				var that = this;
				let children = that.props.children
				if(that.parent.length<4){
					that.tree = item[children]
					that.parent.push(item)
					// console.log(that.parent)
				}else if(that.parent.length<5){
					item.isSel = true
					that.parent.push(item)
					// console.log(that.tree)
				} else if (that.parent.length == 5){
					for(let i=0; i<2; i++)
						that.tree[i].isSel = false;
					item.isSel = true
					// console.log(that.parent[4])
					that.parent[4] = item
					// console.log(that.tree)
				}
				this.$emit('sendChildData',that.parent);
				// console.log(that.parent)
				
			},
			confirmSearch(val) {
				this.searchResult = []
				this.search(this.allData, val)
				this.isre = true
				this.parent.splice(1, 6666)
				uni.showLoading({
					title: '正在查找'
				})
				setTimeout(() => {
					this.tree = this.searchResult
					uni.hideLoading()
				}, 300)
			},
			search(data, keyword) {
				var that = this
				let children = that.props.children
				for (var i = 0, len = data.length; i < len; i++) {
					if (data[i].name.indexOf(keyword) >= 0) {
						that.searchResult.push(data[i])
					}
					if (!data[i].user && data[i][children].length > 0) {
						that.search(data[i][children], keyword)
					}
				}
			},
			//选中变色清空函数
			selClearFunc(data) {
				var that = this
				let children = that.props.children
				for (var i = 0, len = data.length; i < len; i++) {
					if(data[i].hasOwnProperty("isSel")){
						data[i].isSel = false
					}
					if (!data[i].user && data[i][children].length > 0) {
						that.selClearFunc(data[i][children])
					}
				}
			},
			backTree(item, index) {
				if (index == -1) {
					this.tree = this.allData
					this.parent.splice(1, 6666)
					this.isre = false
					this.selClearFunc(this.tree)
					// this.$refs.sea.clears()
				} else if (index == -2) {
					this.tree = this.searchResult
					this.selClearFunc(this.tree)
					this.parent.splice(1, 6666)
				} else {
					var t = this
					this.selClearFunc(this.tree)
					if (this.parent.length - index > 2) {
						this.parent.forEach((item, i) => {
							if (i > index) {
								this.parent.splice(i, 6666)
							}
						})
					} else if (index == this.parent.length - 1) {
						console.log('不操作')
					} else {
						this.parent.splice(this.parent.length - 1, 1)
					}
					this.tree = item[this.props.children]
				}
			},
			//获取当前已选中的楼栋
			getSeletedBuilding() {
				var that = this;
				// console.log(item)
				let children = that.props.children
				// that.buildSelData
			    console.log(this.tree)
				for(var i=0;i<this.tree.length;i++){
					console.log(this.tree[i].name)
					if(this.tree[i].name==that.buildSelData.section){
						that.parent.push(this.tree[i])
						var build = this.tree[i][children]
						for(var j=0;j<build.length;j++){
							// console.log(build[j].name+"=="+that.buildSelData.build)
							if(build[j].name==that.buildSelData.build){
								that.parent.push(build[j])
								var floor = this.tree[i].children[j][children]
								for(var k=0;k<floor.length;k++){
									if(floor[k].name==that.buildSelData.floor){
										that.parent.push(floor[k])
										var unit = this.tree[i].children[j][children][k][children]
										that.tree = unit
										for(var l=0;l<unit.length;l++){
											if(unit[l].name==that.buildSelData.unit){
												// this.allData[i].children[j][children][k][children].isSel = true
												unit[l].isSel = true
												that.parent.push(unit[l])
											}
										}
									}
								}
							}
						}
					}
					// that.tree = item[children]
					// that.parent.push(item)
					console.log(that.parent)
				}
			}
		},
		watch: {
			"trees": {
				handler(newValue, oldValue) {
					let obj = this.commonFunc.Es5duplicate(newValue,'name')
					console.log(obj)
					this.tree = JSON.parse(JSON.stringify(obj))
					// this.tree = this.commonFunc.Es5duplicate(newValue,'name')
					this.allData = JSON.parse(JSON.stringify(obj))
					console.log(newValue);
					this.getSeletedBuilding()
				}
		    }
		}
		
	}
</script>

<style lang="scss" scoped>
	.flex_between_center {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.checkbox {
		position: relative;
		height: 18px;
		margin-left: 5px;
		margin-right: 5px;
		width: 18px;

		.img {
			width: 100%;
			height: 100%;
			display: flex;
			// justify-content: center;
		}
	}

	.checkBorder {
		border: 1px solid #ecdee4;
	}

	// .checkbox:hover{
	// 	border: 1px solid #7f65ff;
	// }
	.all {
		padding-bottom: 68px;
		background-color: #f5f5f5;

		.sheng {
			margin-bottom: 10px;

			.title {
				height: 45px;
				padding: 0 16px;
				line-height: 45px;
				font-size: 15px;
				color: #606064;
			}


		}
	}

	.common {
		background-color: #fff;

		.content {
			display: flex;
			align-items: center;
			padding-left: 5px;
			border-bottom: 1px solid #fafafa;
			height: 50px;
			line-height: 50px;
			font-size: 16px;

			.word {
				margin-left: 10px;
			}
		}
	}

	.person {
		height: 32px;
		width: 32px;
		border-radius: 50%;
		border: 1px solid #ff9f44;
		background-color: #fff9f4;
		margin-left: 7px;
		color: #f57a00;
		line-height: 32px;
		font-size: 11px;
		text-align: center;
	}

	.active {
		color: #4297ED;
	}

	.none {

		color: #666666;

	}
	
	.label-color {
		color: #4297ED;
	}
</style>
