<template>
	<view class="content">
		<block>
			<ChooseLits :list="list" :arr="arr" @chooseLike="chooseLike"></ChooseLits>
			<view class="main-box">
				<t-table borderColor="#494F54">
					<t-tr fontSize="12">
						<t-th>检查部位</t-th>
						<t-th>问题类型</t-th>
						<t-th>问题描述</t-th>
						<t-th>累计数</t-th>
					</t-tr>
					<t-tr  fontSize="12">
						<t-td>1栋一层一单元</t-td>
						<t-td>一般问题</t-td>
						<t-td>节点部位、加密区箍筋漏设</t-td>
						<t-td>16</t-td>
					</t-tr>
					<t-tr  fontSize="12">
						<t-td>1栋一层一单元</t-td>
						<t-td>重大问题</t-td>
						<t-td>节点部位、加密区箍筋漏设</t-td>
						<t-td>16</t-td>
					</t-tr>
				</t-table>
			</view>
		</block>
		<lb-picker ref="picker1" v-model="selectObj" mode="selector" :list="tabBarArray" :dataset="{ name: 'label3' }" @confirm="handleConfirm" @cancel="handleCancel">
		</lb-picker>
	</view>
	
</template>

<script>
	import ChooseLits from '@/components/common/choose-Cade/choose-Cade.vue'
	import tTable from '@/components/common/t-table/t-table.vue'
	import tTh from '@/components/common/t-table/t-th.vue'
	import tTr from '@/components/common/t-table/t-tr.vue'
	import tTd from '@/components/common/t-table/t-td.vue'
	import LbPicker from '@/components/common/lb-picker/index.vue'
	export default {
		components: {
			ChooseLits,
			tTable,
			tTh,
			tTr,
			tTd,
			LbPicker
		},
		data() {
			return {
				list: ['时间', '栋号', '楼层' , '单元'],
				arr: [
					['起始时间', '截止时间'],
					['1栋', '2栋', '3栋'],
					['负一层', '一层', '二层', '三层'],
					['东单元', '西单元']
				],
				i2: [0, 0, 0],
				themeColor: '#007AFF',
				selectObj: '',
				tabBarArray: ['整改闭合率','非正常验收通过率','实测合格率','隐患总数','一般隐患','重大隐患'],
			}
		},
		onNavigationBarButtonTap() {
			this.$refs.picker1.show()
		},
		methods: {
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
