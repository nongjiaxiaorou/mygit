<template>
    <div class="demo-image__preview">
		<div class="img-block">
			<div v-for="(img,index) in imgs" :key="index" style="width: 200px" class="image">
				<el-image 
					style="width: 180px; height: 180px"
					:src="img.url" 
					:preview-src-list="img.srcList">
				</el-image>
				<p>{{img.title}}</p>
			</div>
		</div>
    </div>
</template>
<script>

	export default {
		name: 'bigProImg',
		props: {
			registerBaseData: Object
		},
		components: {

		},
		data() {
			return {
				imgs: [
					{
						title: '1',
						url:'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg']
					},
					{
						title: '2',
						url:'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg'	]				
					},
					{
						title: '3',
						url:'https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg'	]				
					},
					{
						title: '1',
						url:'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg']
					},
					{
						title: '2',
						url:'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg'	]				
					},
					{
						title: '2',
						url:'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg'	]				
					},
					{
						title: '3',
						url:'https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg'	]				
					},
					{
						title: '1',
						url:'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg']
					},
					{
						title: '2',
						url:'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
						srcList: ['https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg'	]				
					},
				],
            }
		},
		computed: {

		},
		mounted() {
			this.getSession()
			console.log(this.registerBaseData);
			this.getImage()
			
		},
		methods: {
			getImage () {
				const that = this;
				const fd = new FormData();
				let imgs = [];
				const projectId = that.registerBaseData.projectId;
				const buildInfo = that.registerBaseData1.section + "||" + that.registerBaseData1.build;
				fd.append('flag','getBigProImg')
				fd.append('projectId',projectId)
				fd.append('buildInfo',buildInfo)
				that.$axios.post (that.$adminUrl + `/project/projectStatistics.php` , fd).then (res => {
                    // console.log(res.data);
					for (var i = 0; i < res.data.data.length; i++) {
						console.log(imgs);
                        imgs.push({
                            title: res.data.data[i].violation,
                            url: that.$adminImgUrl + '/app_pic/inspectPic/' + res.data.data[i].picFile,
                            srcList: [that.$adminImgUrl + '/app_pic/inspectPic/' + res.data.data[i].picFile]
                        })
					}
					// console.log(222);
                    that.imgs = imgs;
					// console.log(imgs);
					
				}) 	
			},
			// 获取本地缓存
			getSession () {
				let registerBaseData = sessionStorage.getItem('registerBaseData')
				this.registerBaseData = JSON.parse(registerBaseData)
				let registerBaseData1 = sessionStorage.getItem('registerBaseData1')
				this.registerBaseData1 = JSON.parse(registerBaseData1)
			},
		},
	}
</script>
<style scoped>
.demo-image__preview {
	width: 100%;
}
.img-block {
	display: flex;
	flex-wrap: wrap;
}
.image {
	padding: 15px;
}
p {
	text-align: center;
}
</style>
