<template>

	<table class='temp-table'>
		<thead class='temp-table__head'>
			<tr class='temp-table__head-row'>
				<th class='temp-table__head-column temp-table__id-head-column'>№</th>
				<th v-for='column in tempTableColumnList' class='temp-table__head-column'>
					<div class='temp-table__head-column-content'>
						<div class='temp-table__head-column-label'>{{ column.name }}</div>
						<div class='temp-table__head-column-filter-icon'><i class='fa fa-filter'></i></div>
					</div>
				</th>
			</tr>

			<tr>
				<th class='temp-table__head-column temp-table__id-head-column'></th>
				<th v-for='column in tempTableColumnList' class='temp-table__head-column'>{{ column.field_num }}</th>
			</tr>
		</thead>

		<tbody>
			<tr
				v-for='row in tempTable'
				class='temp-table__row'
				:class='{ 
					"temp-table__row--edited": isRowEdited(row),
					"temp-table__row--selected": isRowSelected(row),
					"temp-table__row--incorrect": isRowIncorrect(row)
				}'
				@click='selectRow(row)'
			>
				<td class='temp-table__column temp-table__id-column'>{{ row.id }}</td>
				<td
					v-for='column in tempTableColumnList'
					class='temp-table__column temp-table__data-column'
					:class='{
						"temp-table__column--edited": isColumnEdited(row, column),
						"temp-table__column--incorrect": isColumnIncorrect(row, column),
					}'
					@click='startQuickEdit(row, column, $event)'
				>
					{{ getColumnValue(row, column) }}
				</td>
			</tr>
		</tbody>

		<p-overlay-panel ref='op' class='edit-overlay-panel p-shadow-24'>
			<div class='edit-overlay-panel__content'>

				<div class='edit-overlay-panel__current-value-block'>
					<div class='edit-overlay-panel__label'>Текущее значение</div>
					<div class='p-d-flex p-ai-center'>
						<div class='edit-overlay-panel__current-value p-mt-1'>{{ editedValuePlaceholder }}</div>
						<p-button
							v-if='!this.editedColumn.info_table_name && !this.editedColumn.function'
							icon='fa fa-angle-double-down'
							@click='copyCurrentToEdited'
							class='p-button-rounded p-button-outlined'
						/>
					</div>
				</div>

				<div class='edit-overlay-panel__new-value-block p-mt-3'>

					<div class='edit-overlay-panel__label'>Новое значение</div>

					<div v-if='this.editedColumn.info_table_name' class='edit-overlay-panel__input-container p-mt-1'>

						<p-dropdown
							v-model='editedValue'
							:options='editedInfoCatalog'
							:optionLabel='editedColumn.info_table_field'
		
							:placeholder='emptyOptionText'
							showClear
							emptyMessage='Нет элементов'
							filter
							emptyFilterMessage='Не найдено'
							class='edit-overlay-panel__input'

						/>

					</div>

					<div v-if='!this.editedColumn.info_table_name && !this.editedColumn.function' class='p-mt-1'>
						<p-input-text v-model='editedValue' :placeholder='emptyOptionText' class='edit-overlay-panel__input' autofocus/>
					</div>
				</div>

				<div class='edit-overlay-panel__footer p-mt-4 p-d-flex p-jc-end'>
					<div>
						<p-button label='Отмена' @click='cancelQuickEdit' icon='fa fa-times' class='p-mr-2 p-button-sm p-button-secondary p-button-text'/>
						<p-button label='Сохранить' @click='saveQuickEdit' icon='fa fa-check' class='p-button-sm p-button-success'/>
					</div>
				</div>

			</div>

		</p-overlay-panel>

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

				// return tempTableTemplate;

				const result = JSON.parse(JSON.stringify(this.tempTableTemplate));
				return result;
			},

			...mapState('currentTempTable', {
				tempTableTemplate: state => state.tempTableTemplate,
				tempTable: state => state.tempTable,
				tempTableLoading: state => state.tempTableLoading,
				tempTablePerPage: state => state.tempTablePerPage,
				tempTableRowCount: state => state.tempTableRowCount,
			})
		},

		watch: {
			// filterOnlyIncorrect(filterOnlyIncorrect) {
			// 	if (filterOnlyIncorrect) {

			// 		const filter_obj = {
			// 			is_row_correct: [ false ]
			// 		};

			// 		this.setTempTableFilter(filter_obj);

			// 	} else {
			// 		const filter_obj = {
			// 			is_row_correct: []
			// 		};

			// 		this.setTempTableFilter(filter_obj);
			// 	}
			// }
		},

		methods: {

			copyCurrentToEdited() {
				this.editedValue = this.editedValuePlaceholder;
			},

			loadCatalogFunction(functionStr) {
				// const index = functionStr.indexOf('(');

				let [ functionName, functionParamsStr ] = functionStr.split('(');

				if (functionParamsStr.indexOf(')') !== -1) {
					functionParamsStr = functionParamsStr.slice(0, functionParamsStr.indexOf(')'));
				}

				const functionParamsArr = functionParamsStr.split(',');

			
				for (let i = 0; i < functionParamsArr.length; ++i) {
					const param = functionParamsArr[i];

					if (param in this.editedRow) {
						functionParamsArr[i] = String(this.editedRow[param]);
						if (functionParamsArr[i] !== 'null') {
							functionParamsArr[i] = "'" + functionParamsArr[i] + "'";
						}
					}
				}

				functionParamsStr = functionParamsArr.join(',');

				console.log(functionName),
				console.log(functionParamsStr);

				// return;

				const form = new FormData();
				form.append('function_name', functionName);
				form.append('function_params', functionParamsStr);

				this.catalogIsLoading = true;

				return fetch(GET_FUNCTION_CATALOG, {
					credentials: 'include',
					method: 'post',
					body: form
				})
				.then(res => res.json())
				.then(res => {
					const temp = {
						isEmptyOption: true,
						[this.editedColumn.info_table_field]: EMPTY_OPTION_TEXT
					};

					res.unshift(temp);
					this.editedInfoCatalog = res;
				})
				.finally(() => {
					this.catalogIsLoading = false;
				});
			},

			loadInfoCatalog(catalogName) {
				const form = new FormData();
				form.append('catalog_name', catalogName);

				this.catalogIsLoading = true;

				return fetch(GET_INFO_CATALOG, {
					credentials: 'include',
					method: 'post',
					body: form
				})
				.then(res => res.json())
				.then(res => {
					const temp = {
						isEmptyOption: true,
						[this.editedColumn.info_table_field]: EMPTY_OPTION_TEXT
					};

					res.unshift(temp);
					this.editedInfoCatalog = res;
				})
				.finally(() => {
					this.catalogIsLoading = false;
				});
			},

			selectRow(row) {
				// console.log(row);
				// this.selectedRow = row;
				// console.log(this.tempTable);
			},

			saveQuickEdit() {
				if (this.editedValue?.isEmptyOption) {
					this.editedValue[this.editedColumn.info_table_field] = null;
				}

				// console.log(value);

				this.$emit('save-quick-edit', {
					row: this.editedRow,
					column: this.editedColumn,
					value: this.editedValue
				});

				if (this.$refs['op'].visible) {
					this.$refs['op'].toggle();
				}
			},

			cancelQuickEdit() {
				this.$refs.op.toggle(event);
			},

			startQuickEdit(row, column, event) {
				// console.log(row);
				// console.log(column);

				this.editedRow = row;
				this.editedColumn = column;
				this.editedValue = null;

				if (this.editedColumn.function) {
					this.loadCatalogFunction(this.editedColumn.function);
				} else if (this.editedColumn.info_table_name) {
					this.loadInfoCatalog(this.editedColumn.info_table_name);
				}

				this.$refs.op.toggle(event);
			},

			isRowSelected(row) {
				// return this.selectedRow?.id === row.id;
			},

			isRowEdited(row) {
				return this.editedRows.find(editedRow => editedRow.id === row.id);
			},

			isRowIncorrect(row) {
				return row.is_row_checked === 't' && row.is_row_correct === 'f';
			},

			isColumnEdited(row, column) {
				const finded = this.editedRows.find(editedRow => editedRow.id === row.id);

				if (finded) {
					return (column.field in finded);
				}
			},

			isColumnIncorrect(row, column) {
				if (!this.isRowIncorrect(row)) {
					return false;
				}

				if (!('field_check' in column)) {
					return false;
				}

				if (row[column.field_check] !== '-1') {
					return false;
				}

				return true;
			},

			getColumnValue(row, column) {
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

			editRow(row) {
				this.$emit('edit-btn-pushed', row);
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
	.temp-table {
		border-collapse: separate;
		font-size: 0.875rem;
	}

	/* header */
	.temp-table__head {
		z-index: 2;
		position: sticky;
		top: 0;
	}

	.temp-table__head-column,
	.temp-table__column {
		border-right: 1px solid #bbb;
		border-bottom: 1px solid #bbb;
	}

	.temp-table__head-row:last-child .temp-table__head-column {
		border-bottom: 1px solid #bbb;
	}

	.temp-table__head-column {
		background-color: #061625;
		color: #fff;
		font-weight: bold;
		min-width: 180px;
		padding: 0.1rem 0.5rem;
		vertical-align: middle;
	}

	.temp-table__head-column-content {
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 0.8rem;
	}

	.temp-table__head-column-label {
		flex: 1;
	}

	.temp-table__head-column-filter-icon {
		border-radius: 50%;
		border: 1px solid dodgerblue;
		padding: 0.4rem;

		width: 26px;
		height: 26px;
	}

	.temp-table__row:hover .temp-table__column {
		background-color: #D9EEFE;
	}

/*	.temp-table__head-column-content:hover .temp-table__head-column-filter-icon {
		background-color: dodgerblue;
	}*/

	.temp-table__column {
		background-color: #fff;
		padding: 0.25rem 0.5rem;
		vertical-align: middle;
	}

	.temp-table__column--edited {
		background-color: dodgerblue !important;
	}

	.temp-table__row--incorrect .temp-table__column {
		background-color: #FFA27D;
	}
	.temp-table__row--incorrect .temp-table__column--incorrect {
		background-color: #E74242;
	}

	.temp-table__row:hover.temp-table__row--incorrect .temp-table__column {
		background-color: #FFA27D;
	}
	.temp-table__row:hover.temp-table__row--incorrect .temp-table__column--incorrect {
		background-color: #E74242;
	}

	.temp-table__row--edited .temp-table__column {
		background-color: lightblue;
	}

	.temp-table__data-column {
		position: relative;
	}

	.temp-table__row:hover .temp-table__column {
		background-color: #D9EEFE;
	}

	.temp-table__id-head-column,
	.temp-table__id-column {
		position: sticky;
		left: 0;
		z-index: 1;
	}

	.temp-table__id-head-column {
		min-width: 0;
	}

	.temp-table__id-column {
		background-color: #061625 !important;
		color: white;
		font-weight: bold;
		text-align: center;
	}

	.temp-table__btn-column {
		position: sticky;
		right: 0;
		min-width: 0;
	}

	.temp-table__btn-column-header {
		border-left: 1px solid #bbb;
		z-index: -1;
	}

</style>