<template>

	<table class='reestr-table'>
		<thead class='reestr-table__head'>
			<tr class='reestr-table__head-row'>
				<th class='reestr-table__head-column reestr-table__id-head-column'>№</th>
				<th v-for='column in tempTableColumnList' class='reestr-table__head-column'>
					<div class='reestr-table__head-column-content'>
						<div class='reestr-table__head-column-label'>{{ column.name }}</div>
						<div class='reestr-table__head-column-filter-icon'><i class='fa fa-filter'></i></div>
					</div>
				</th>
				<!-- <th class='reestr-table__btn-column reestr-table__btn-column-header reestr-table__head-column'></th> -->
			</tr>

			<tr>
				<th class='reestr-table__head-column reestr-table__id-head-column'></th>
				<th v-for='column in tempTableColumnList' class='reestr-table__head-column'>{{ column.field_num }}</th>
				<!-- <th class='reestr-table__btn-column reestr-table__btn-column-header reestr-table__head-column'></th> -->
			</tr>

		</thead>

		<tbody>
			<tr
				v-for='row in tempTable'
				class='reestr-table__row'
			>
				<td class='reestr-table__column reestr-table__id-column'>{{ row.id }}</td>

				<td
					v-for='column in tempTableColumnList'
					class='reestr-table__column reestr-table__data-column'
				>
					{{ row[column.field] }}
					<!-- {{ getColumnValue(row, column) }} -->
					
				</td>

			</tr>
		</tbody>

	</table>

</template>

<script>
	const ROOT_API = process.env.VUE_APP_ROOT_API;
	const GET_INFO_CATALOG = ROOT_API + 'catalogs/get_info_catalog.php';
	const GET_FUNCTION_CATALOG = ROOT_API + 'catalogs/get_info_catalog_function.php';

	const EMPTY_OPTION_TEXT = '( пусто )';

	import { mapState, mapMutations, mapActions } from 'vuex';

	export default {
		name: 'TempTablesTempTable',

		props: {
			editedRows: Array,
			addressAtStart: Boolean,
			filterOnlyIncorrect: Boolean
		},

		setup() {

		},

		data: () => ({
			selectedRow: null,

			info_catalog: [],
			editedRow: null,
			editedColumn: null,
			editedValue: null,
			editedInfoCatalog: [],
			catalogIsLoading: false
		}),

		computed: {
			emptyOptionText() {
				return EMPTY_OPTION_TEXT;
			},

			editedValuePlaceholder() {
				const row = this.editedRow;
				const column = this.editedColumn;

				if (!row || !column) {
					return null;
				}

				const finded = this.editedRows.find(editedRow => editedRow.id === row.id);

				if (finded && column.field in finded) {
					return finded[column.field];
				} else {
					return row[column.field];
				}
			},

			tempTableColumnList() {
				if (this.addressAtStart) {
					const result = JSON.parse(JSON.stringify(this.tempTableTemplate));

					const addressFields = result.splice(20, 6); // forestry, ..., section_lp

					result.splice(1, 0, ...addressFields);

					return result;
				}

				const result = JSON.parse(JSON.stringify(this.tempTableTemplate));
				return result;
			},

			...mapState('dataReestr', {
				tempTableTemplate: state => state.tableTemplate,
				tempTable: state => state.dataReestrTable,
				tempTableLoading: state => state.dataReestrTableLoading,
				tempTablePerPage: state => state.dataReestrTablePerPage,
				tempTableRowCount: state => state.dataReestrTableRowCount,
			})
		},



		methods: {

			getColumnValue(row, column) {
				return 2;

				if (!row || !column) {
					return null;
				}

				const finded = this.editedRows.find(editedRow => editedRow.id === row.id);

				if (finded && column.field in finded) {
					return finded[column.field];
				} else {
					return row[column.field];
				}
			},


			onPage(event) {
				const currentPage = event.page + 1;
				const table_id = this.$route.params.table_id;

				this.setCurrentPage(currentPage);
				this.getTempTable(table_id);
			},

			...mapMutations('currentTempTable', [
				'setTempTableFilter'
			]),

			...mapMutations('tempTables', [
				'setCurrentPage'
			]),

			...mapActions('tempTables', [
				'getTempTable'
			])
		}

	};
</script>

<style scoped>
	.edit-overlay-panel__content {
		width: 224px;
	}

	.edit-overlay-panel__label {
		color: #888;
		font-size: 0.9rem;
	}

	.edit-overlay-panel__current-value {
		flex: 1;
		font-size: 1rem;
	}

/*	.edit-overlay-panel__input-container {
		display: flex;
		width: 100%;
	}*/

	.edit-overlay-panel__input {
		width: 100%;
		/*flex: 1;*/
	}

</style>


<style scoped>
	.reestr-table {
		border-collapse: separate;
		font-size: 0.875rem;
	}

	/* header */
	.reestr-table__head {
		z-index: 2;
		position: sticky;
		top: 0;
	}

	.reestr-table__head-column,
	.reestr-table__column {
		border-right: 1px solid #bbb;
		border-bottom: 1px solid #bbb;
	}

	.reestr-table__head-row:last-child .reestr-table__head-column {
		border-bottom: 1px solid #bbb;
	}

	.reestr-table__head-column {
		background-color: #061625;
		color: #fff;
		font-weight: bold;
		min-width: 180px;
		padding: 0.1rem 0.5rem;
		vertical-align: middle;
	}

	.reestr-table__head-column-content {
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 0.8rem;
	}

	.reestr-table__head-column-label {
		flex: 1;
	}

	.reestr-table__head-column-filter-icon {
		border-radius: 50%;
		border: 1px solid dodgerblue;
		padding: 0.4rem;

		width: 26px;
		height: 26px;
	}

	.reestr-table__row:hover .reestr-table__column {
		background-color: #D9EEFE;
	}

/*	.reestr-table__head-column-content:hover .reestr-table__head-column-filter-icon {
		background-color: dodgerblue;
	}*/

	.reestr-table__column {
		background-color: #fff;
		padding: 0.25rem 0.5rem;
		vertical-align: middle;
	}

	.reestr-table__column--edited {
		background-color: dodgerblue !important;
	}

	.reestr-table__row--incorrect .reestr-table__column {
		background-color: #FFA27D;
	}
	.reestr-table__row--incorrect .reestr-table__column--incorrect {
		background-color: #E74242;
	}

	.reestr-table__row:hover.reestr-table__row--incorrect .reestr-table__column {
		background-color: #FFA27D;
	}
	.reestr-table__row:hover.reestr-table__row--incorrect .reestr-table__column--incorrect {
		background-color: #E74242;
	}

	.reestr-table__row--edited .reestr-table__column {
		background-color: lightblue;
	}

	.reestr-table__data-column {
		position: relative;
	}

	.reestr-table__row:hover .reestr-table__column {
		background-color: #D9EEFE;
	}

	.reestr-table__id-head-column,
	.reestr-table__id-column {
		position: sticky;
		left: 0;
		z-index: 1;
	}

	.reestr-table__id-head-column {
		min-width: 0;
	}

	.reestr-table__id-column {
		background-color: #061625 !important;
		color: white;
		font-weight: bold;
		text-align: center;
	}

	.reestr-table__btn-column {
		position: sticky;
		right: 0;
		min-width: 0;
	}

	.reestr-table__btn-column-header {
		border-left: 1px solid #bbb;
		z-index: -1;
	}

</style>