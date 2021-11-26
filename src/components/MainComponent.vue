<template>
  <div class='main-component'>
  	
  	<MainHeader/>

  	<div class='main-component__content'>
			<router-view/>
	  </div>

  </div>
</template>

<script>
	import MainHeader from './MainHeader';

  export default {
    name: 'MainComponent',

    components: {
    	MainHeader
    }
  }
</script>

<style scoped>
	.main-component {
		background-color: #eff2f5;
		display: flex;
		flex-direction: column;
		height: 100vh;
	}

	.main-component__content {
		height: 100%;
		overflow-y: auto;
	}
</style>