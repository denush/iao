<template>
	<div>
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
				<th class='btn-column btn-column-header temp-table__head-column'></th>
			</tr>

			<tr>
				<th class='temp-table__head-column temp-table__id-head-column'></th>
				<th v-for='(column, column_i) in tempTableColumnList' class='temp-table__head-column'>{{ column_i + 1 }}</th>
				<th class='btn-column btn-column-header temp-table__head-column'></th>
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
					@dblclick='beginQuickEditColumn(row, column)'
					@mouseenter='onColumnMouseEnter($event, row, column)'
					@mouseleave='onColumnMouseLeave($event, row, column)'
				>
					{{ getColumnValue(row, column) }}
				</td>

				<td class='temp-table__column btn-column'>
					<p-button @click='editRow(row)' icon='fa fa-edit' class='p-button-sm p-button-rounded'/>
				</td>
			</tr>
		</tbody>

	</table>
	<p-overlay-panel ref='op'>
			lalalal faslfasjf
		</p-overlay-panel>
</div>
</template>

<script>
	import { mapState, mapMutations, mapActions } from 'vuex';

	import tempTableTemplate from '@/lib/tempTableTemplate';

	export default {
		name: 'TempTablesTempTable',

		props: {
			editedRows: Array,
			addressAtStart: Boolean
		},

		data: () => ({
			selectedRow: null,
		}),

		computed: {
			tempTableColumnList() {
				if (this.addressAtStart) {
					const result = JSON.parse(JSON.stringify(tempTableTemplate));

					const addressFields = result.splice(19, 6); // forestry, ..., section_lp

					result.splice(1, 0, ...addressFields);

					return result;
				}

				return tempTableTemplate;
			},

			isVisible() {
				// return this.$refs['op'][0].visible
			},

			...mapState('currentTempTable', {
				tempTable: state => state.tempTable,
				tempTableLoading: state => state.tempTableLoading,
				tempTablePerPage: state => state.tempTablePerPage,
				tempTableRowCount: state => state.tempTableRowCount,
			})
		},

		methods: {

			onColumnMouseEnter(event, row, column) {

				const columnElement = event.target;
				const editButton = document.createElement('button');

				const editIcon = document.createElement('icon');
				editIcon.className = 'fa fa-edit';
				editButton.append(editIcon);

				editButton.addEventListener('click', (event) => {
					console.log(row);
					console.log(column);
				})

				editButton.className= 'temp-table__column-edit-btn';
				// editButton.style.backgroundColor = 'red';

				columnElement.append(editButton);
			},

			onColumnMouseLeave(event) {
				const existedEditButtons = document.querySelector('.temp-table__column-edit-btn');

				// for (let btn of existedEditButtons) {
				// 	btn.remove();
				// }

				existedEditButtons.remove();
			},

			toggleOp(event, column_i) {
				// const overlayPanel = this.$refs['op'].find(item => item.$attrs['data-key'] === column_i);

				// const op = this.$refs['op'];

				// op.toggle(event);
			},

			selectRow(row) {
				// console.log(row);
				this.selectedRow = row;
			},

			beginQuickEditColumn(row, column) {
				console.log(column);
			},

			isRowSelected(row) {
				return this.selectedRow?.id === row.id;
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

				if (!('check_field' in column)) {
					return false;
				}

				if (row[column.check_field] !== '-1') {
					return false;
				}

				return true;
			},

			getColumnValue(row, column) {
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

			...mapMutations('tempTables', [
				'setCurrentPage'
			]),

			...mapActions('tempTables', [
				'getTempTable'
			])
		},

		created() {
			const table_id = this.$route.params.table_id;

			if (!table_id) {
				throw new Error('TEMP_TABLE_NO_TABLE_ID');
			}

			// this.getTempTable(table_id);
		}
	};
</script>

<style>

	.temp-table__column-edit-btn {
		/*display: none;*/

		background-color: #2196F3;
		border: none;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;

		cursor: pointer;

		color: #fff;

		position: absolute;
		top: 0;

		right: -40px;
		z-index: 1;
		height: 100%;
		width: 40px;

	}

</style>

<style scoped>
	
	.temp-table {
		border-collapse: separate;
		font-size: 0.875rem;

		position: relative;
	}

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
		/*display: flex;*/
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

	.temp-table__head-column-content:hover .temp-table__head-column-filter-icon {
		background-color: dodgerblue;
	}

	.temp-table__column {
		background-color: #fff;
		padding: 0.2rem 0.5rem;
		vertical-align: middle;

		
	}

	.temp-table__column:hover {
		outline: 2px solid #2196F3;
		z-index: 1;
	}

	.temp-table__column--edited {
		background-color: dodgerblue !important;
	}


	.temp-table__row--incorrect .temp-table__column {
		background-color: orange;
	}
	.temp-table__row--incorrect .temp-table__column--incorrect {
		background-color: red;
	}

	.temp-table__row--edited .temp-table__column {
		background-color: lightblue;
	}

	.temp-table__row--selected .temp-table__column {
		/*background-color: red;*/
		/*outline: 3px solid red;*/
	}

	.temp-table__data-column {
		position: relative;
	}

	.temp-table__column-edit-btn {
		/*display: none;*/

		background-color: blue;
		width: 100px;
/*
		position: absolute;
		top: 0;

		left: 0;
		z-index: 100;
		height: 100%;
		width: 100px;*/
	}

/*	.temp-table__column:hover .temp-table__column-edit-btn {
		display: block;
	}
*/
	.temp-table__row--selected {
		/*outline: 2px solid blue;*/
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

	.btn-column {
		/*background-color: transparent;*/
		position: sticky;
		right: 0;
	}

	.btn-column-header {
		/*background-color: transparent;*/
		/*z-index: 1;*/
		min-width: 0;
		border-left: 1px solid #bbb;
	}

	

</style>