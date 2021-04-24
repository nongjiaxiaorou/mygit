<template>
	<div id="ProDetail" class="basic container flex align-center justify-center">
		<div class="flex-1 px-5" style="" id="leftBox">
			<el-tabs :tab-position="tabPosition">
				<el-tab-pane label="现场质量巡检" style="margin-left: 6%;">
					<WeeklySceneInspect></WeeklySceneInspect>
				</el-tab-pane>

				<el-tab-pane label="方案资料巡查" style="margin-left: 6%;">
					<WeeklyFileInspect></WeeklyFileInspect>
				</el-tab-pane>
			</el-tabs>
		</div>
	</div>
</template>
<script>
	import WeeklySceneInspect from './WeeklySceneInspect.vue'
	import WeeklyFileInspect from './WeeklyFileInspect.vue'
	export default {
		name: 'basetable',
		components: {
			WeeklySceneInspect,
			WeeklyFileInspect
		},
		data() {
			return {
				loading: false,
				tabPosition: 'left',
				activeName: '',
				registerBaseData: {},
				framework: '',

			}
		},
		computed: {

		},
		mounted() {
			
		},
		methods: {
			
		},
	};
</script>
<style scoped>
	.el-row {
		margin-bottom: 20px;

		&:last-child {
			margin-bottom: 0;
		}
	}

	.el-col {
		border-radius: 4px;
	}

	.bg-purple-dark {
		background: #99a9bf;
	}

	.bg-purple {
		background: #d3dce6;
	}

	.bg-purple-light {
		background: #e5e9f2;
	}

	.grid-content {
		border-radius: 4px;
		min-height: 32px;
		margin: 0.625rem;
	}

	.title-text {
		color: #31708f;
		line-height: 32px;
	}

	.title-box {
		background-color: #d9edf7;
	}

	.info-label {
		color: #31708f;
		line-height: 32px;
		text-align: center;
	}

	.info-title {
		color: inherit;
		line-height: 32px;
		text-align: center;
	}

	.info-title-box {
		width: 10%;
		background-color: #5bc0de;
		height: 32px;
		line-height: 32px;
		text-align: center;
	}

	.row-bg {
		padding: 10px 0;
		background-color: #f9fafc;
	}
</style>
