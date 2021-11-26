<template>
	<div class='temp-tables'>
		<div class='temp-tables__content main-wrapper'>
			<!-- <TempTablesLoader/> -->
			<TempTablesList/>
		</div>
	</div>
</template>

<script>
	import { mapActions } from 'vuex';

	import TempTablesLoader from './TempTables__loader';
	import TempTablesList from './TempTables__list';

	export default {
		name: 'TempTables',

		components: {
			TempTablesLoader,
			TempTablesList
		},

		methods: {
			...mapActions('tempTables', [
				'getTempTableList'
			])
		},

		created() {
			this.getTempTableList();
		}

	};
</script>

<style scoped lang='scss' src='@/styles/main_wrapper.scss'></style>

<style scoped>
	.file-table {
		/*width: fit-content;*/
	}

	.file-table::v-deep .file-table__row > td {
		vertical-align: middle;
	}
</style>

<style scoped>
	.temp-tables {
		/*background-color: #eff2f5;
		display: flex;
		flex-direction: column;
		height: 100vh;*/
		height: 100%;
		overflow: auto;
	}

/*	.main-component__content {
		height: 100%;
		overflow-y: auto;
	}*/
</style>