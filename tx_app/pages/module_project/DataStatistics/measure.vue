<template>
	<view class="content">
		<block>
			<ChooseLits :list="list" :arr="arr" @chooseLike="chooseLike"></ChooseLits>
			<view class="main-box">
				<t-table borderColor="#494F54">
					<t-tr fontSize="12">
						<t-th>检查部位</t-th>
						<t-th>分项工程</t-th>
						<t-th>类型</t-th>
						<t-th>合格率</t-th>
					</t-tr>
					<t-tr  fontSize="12">
						<t-td>11栋一层一单元</t-td>
						<t-td>前提工程(阿萨)</t-td>
						<t-td>重大问题</t-td>
						<t-td>重大问题</t-td>
					</t-tr>
				</t-table>
			</view>
		</block>
		<mpvue-picker
			:themeColor="themeColor"
			ref="mpvuePicker"
			:mode="mode"
			:deepLength="deepLength"
			:pickerValueDefault="pickerValueDefault"
			@onConfirm="onConfirm"
			@onCancel="onCancel"
			:pickerValueArray="pickerValueArray"
		></mpvue-picker>
	</view>
	
</template>

<script>
	import mpvuePicker from '../../../components/common/mpvue-picker/mpvuePicker.vue';
	import ChooseLits from '../../../components/common/choose-Cade/choose-Cade.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	export default {
		components: {
			ChooseLits,
			tTable,
			tTh,
			tTr,
			tTd,
			mpvuePicker
		},
		data() {
			return {
				list: ['时间段', '分项分部', '类型'],
				arr: [
					['起始时间'],
					['A公司', 'B公司', 'C公司'],
					['A工程', '一般问题', '重大问题'],
				],
				i2: [ 0, 0, 0],
				pickerValueArray: [
					{
						label: '问题隐患'
					},
					{
						label: '砼强度'
					}
				],
				themeColor: '#007AFF',
				mode: '',
				deepLength: 1,
				pickerValueDefault: [0]
			}
		},
		onBackPress() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
				return true;
			}
		},
		onUnload() {
			if (this.$refs.mpvuePicker.showPicker) {
				this.$refs.mpvuePicker.pickerCancel();
			}
		},
		onNavigationBarButtonTap(e) {
			if (e.index === 0) {
				this.showSinglePicker();
			}
		},
		methods: {
			//////////////////选择问题类型
			onCancel(e) {
				console.log(e);
			},
			// 单列
			showSinglePicker() {
				this.mode = 'selector';
				this.deepLength = 1;
				this.pickerValueDefault = [0];
				this.$refs.mpvuePicker.show();
			},
			onConfirm(e) {
				console.log(e.label);
				switch (e.label) {
					case '问题隐患': //问题隐患
						uni.redirectTo({
							url:'problems'
						})
						break;
					case '砼强度': //砼强度
					uni.redirectTo({
						url:'concrete'
					})
						break;
				}
				// this.setStyle(0, e.label);
			},
			//下拉筛选
			chooseLike(i1) {
				console.log(i1)
				// console.log(this.i2)
				if (this.i2[i1[0]] != i1[1]) {
					this.i2[i1[0]] = i1[1];
				}
				console.log(this.i2)
			},
		}
	}
</script>

<style  lang="scss">
	.main-box {
		position: absolute;
		top: 55px;
		width: 100%;
	}
</style>
