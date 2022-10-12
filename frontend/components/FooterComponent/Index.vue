<template>
	<v-footer :absolute="false" class="d-flex justify-center" app>
		<span>&copy; {{ new Date().getFullYear() }}</span>
	</v-footer>
</template>

<script>
	export default {
    name: 'FooterComponent',
	}
</script>

<style scoped>

</style>