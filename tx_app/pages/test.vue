<template>
	<div>aaaa
	<button type="default" @longpress= "test()" >长按</button>
	</div>
	
</template>

<script>
	export default {
		methods:{
			test () {
				console.log('长按成功')
			}
		}
	}
</script>

<style>
</style>
