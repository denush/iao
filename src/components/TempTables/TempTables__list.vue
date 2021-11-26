<template>
<!-- 	<p-table
		:value='formedTempTableList'
		row-hover
	
		:row-class='() => "file-table__row"'
		class='file-table p-datatable-sm'
	>
		<template #empty>
			Таблицы не загружены
		</template>

		<p-column field='file_name' header='Название файла'/>
		<p-column field='create_date' header='Дата загрузки'/>
		<p-column field='name' header='Дата последнего редактирования'/>

		<p-column>
			<template #body='slotScope'>
				<p-button @click='goToTable(slotScope)' icon='fa fa-sign-in-alt' class='p-button-rounded p-button-sm p-mr-2'/>
				<p-button @click='removeTable(slotScope)' icon='fa fa-trash' class='p-button-rounded p-button-sm p-button-danger'/>
			</template>
		</p-column>
	</p-table> -->

	<table class='table-temp-list'>
		<thead>
			<tr>
				<th></th>
				<th>Название файла</th>
				<th>Дата загрузки</th>
				<th>Дата последнего редактирования</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr v-for='(table, table_i) in formedTableList' :key='table.tmp_tab_id'>
				<td class='table-temp-list__table-num-column'>{{ table_i + 1 }}</td>
				<td>{{ table.file_name }}</td>
				<td>{{ formatDate(table.create_date) }}</td>
				<td>{{ table.last_edit_date }}</td>
				<td>
					<p-button @click='goToTable(table)' icon='fa fa-sign-in-alt' class='p-button-rounded p-button-sm p-mr-2'/>
					<p-button @click='removeTable(table)' icon='fa fa-trash' class='p-button-rounded p-button-sm p-button-danger'/>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
	import { computed } from 'vue';
	import { useStore } from 'vuex';
	import { useRouter } from 'vue-router';
	import { mapState, mapActions } from 'vuex';

	export default {
		name: 'TempTablesList',

		setup() {
			const store = useStore();
			const router = useRouter();

			const tableList = computed(() => store.state.tempTables.tempTableList);
			const formedTableList = computed(() => {
				return tableList.value.filter(table => {
					return table.status_id !== '3';
				});
			});

			const goToTable = table => {
				const route_obj = {
					name: 'temp-table',
					params: {
						table_id: table.tmp_tab_id
					}
				};
				router.push(route_obj);
			};

			const removeTable = table => {
				return store.dispatch('tempTables/removeTempTable', table.tmp_tab_id).then(res => {
					return store.dispatch('tempTables/getTempTableList');
				});
			};

			const formatDate = unformatted => {
				const date = new Date(unformatted);
				return date.toLocaleString();
			};

			return {
				tableList,
				formedTableList,
				goToTable,
				removeTable,
				formatDate
			};
		}

	};
</script>

<style>
	.table-temp-list {
		border-collapse: separate;
		border-spacing: 0 0.5rem;
		width: 100%;
	}

	.table-temp-list thead tr th {
		font-weight: bold;
		text-align: left;
		padding: 0 1rem;
		padding-bottom: 1rem;
		color: #3e4750;
	}

	.table-temp-list tbody tr {
		/*border: 1px solid gray;*/
	}

	.table-temp-list tbody tr td {
		background-color: white;
		padding: 0.5rem 1rem;
		vertical-align: middle;
		color: #3e4750;
	}

	.table-temp-list__table-num-column {
		background-color: #142739 !important;
		color: #dee7f2 !important;
		font-weight: bold;
		text-align: center;
	}
</style>