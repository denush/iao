<template>
	<div class='reestr-data'>
		<DataReestrToolbar/>

		<div class='reestr-data__flex-container'>
			<DataReestrTable/>
		</div>
		<p-paginator
			:rows='perPage'
			:totalRecords='rowCount'
			@page='onPage'
		/>

	</div>
</template>


<script>
	import { computed } from 'vue';
	import { useStore } from 'vuex';

	import DataReestrToolbar from './DataReestr__toolbar';
	import DataReestrTable from './DataReestr__table';

	export default {
	  name: 'FileLoader',

	  components: {
	  	DataReestrToolbar,
	  	DataReestrTable
	  },

	  setup() {
	  	const store = useStore();

	  	const perPage = computed(() => store.state.dataReestr.reestrDataTablePerPage);
	  	const rowCount = computed(() => store.state.dataReestr.reestrDataTableRowCount);

	  	const onPage = (event) => {
	  		console.log(event.page);
	  		store.dispatch('dataReestr/CHANGE_PAGE', event.page + 1);
	  	};

	  	store.dispatch('dataReestr/GET_TABLE_TEMPLATE');
	  	store.dispatch('dataReestr/GET_REESTR_DATA_TABLE');

	  	return { perPage, rowCount, onPage };
	  }
	};
</script>

<style scoped lang='scss' src='@/styles/main_wrapper.scss'></style>

<style scoped>
	.reestr-data {
		display: flex;
		flex-direction: column;
		height: 100%;
	}

	.reestr-data__flex-container {
		flex: 1;
		overflow: auto;
	}
</style>
