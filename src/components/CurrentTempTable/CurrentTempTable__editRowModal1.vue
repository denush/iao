<template>
	<custom-p-modal :value='isOpen' :dismissable='false' @input='onModalInput' @show='onModalShow' @hide='onModalHide'>
		<template v-if='selectedRow && editedRow'>

			<div class='edit-component'>

				<div class='edit-component__header'>
					header
				</div>

				<div class='edit-component__field-container'>
					<table class='edit-component__table-field-list'>
						<tbody>
							<tr v-for='column in tempTableColumnList' :class='{ edited: isEdited(column) }'>
								<td class='edit-component__field-column edit-component__field-name-column'>{{ column.name }}</td>
								<td class='edit-component__field-column edit-component__field-input-column'>
									<template v-if='column.catalog'>
										<custom-p-select v-model='editedRow[column.field]' :options='[column.catalog]'/>
									</template>
									<template v-else>
										<custom-p-input v-model='editedRow[column.field]'/>
									</template>
								</td>
								<td class='edit-component__field-column edit-component__field-reset-btn-column'><p-button v-if='isEdited(column)' @click='resetValue(column)' icon='fa fa-undo'/></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class='edit-component__btn-block'>
					<div>
						<p-button label='Восстановить значения' :disabled='!editedColumns.length' class='p-button-secondary p-mr-2' @click='resetEdit'/>
						<p-button label='Применить' :disabled='isApplyBtnDisabled' class='p-button-success' @click='applyEdit'/>
					</div>
				</div>

			</div>

		</template>
	</custom-p-modal>
</template>

<script>
	import { mapState, mapActions } from 'vuex';

	import tempTableTemplate from '@/lib/tempTableTemplate';

	export default {
		name: 'CurrentTempTableEditRowModal',

		model: {
			prop: 'isOpen'
		},

		props: {
			isOpen: {
				type: Boolean,
				default: false
			},

			addressAtStart: Boolean,

			selectedRow: Object,

			editedRows: Array
		},

		data: () => ({
			editedRow: null,
			prevEditedRow: null,
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

			editedColumns() {
				const result = [];

				for (const column of this.tempTableColumnList) {
					if (this.editedRow[column.field] !== this.selectedRow[column.field]) {
						result.push(column);
					}
				}

				return result;
			},

			isApplyBtnDisabled() {
				/* если строка редактировалась раньше */
				if (this.prevEditedRow) {
					/* если хоть одно ранее редактированное значение не совпадает с текущим, нужна возможность сохранить */
					for (const field of Object.keys(this.prevEditedRow)) {
						if (this.prevEditedRow[field] !== this.editedRow[field]) {
							return false;
						}
					}

					/* если изменных значений больше, чем ранее редактированных, значит есть новые данные, которые нужно сохранить */
					if (this.editedColumns.length > (Object.keys(this.prevEditedRow).length - 1) ) {
						return false;
					}

					return true;
				}

				/* если строка ранее не редактировалась, смотрем, есть ли вообще измененные столбцы */
				return !this.editedColumns.length;
			},

			...mapState('currentTempTable', {
				region_list: state => state.catalogs.region_list,
			})
		},

		methods: {
			onModalInput(event) {
				this.$emit('input', event);
			},
			onModalShow(event) {
				this.getRegionList();

				this.editedRow = JSON.parse(JSON.stringify(this.selectedRow));
				this.prevEditedRow = this.editedRows.find(row => row.id === this.editedRow.id);

				if (this.prevEditedRow) {
					Object.assign(this.editedRow, this.prevEditedRow);
				}

			},
			onModalHide(event) {
				this.editedRow = null;
				this.prevEditedRow = null;
			},

			isEdited(column) {
				const finded = this.editedColumns.find(editedColumn => editedColumn.field === column.field);
				return finded;
			},

			resetValue(column) {
				this.editedRow[column.field] = this.selectedRow[column.field];
			},

			applyEdit() {
				const json_obj = {
					id: this.selectedRow.id
				};

				for (const column of this.editedColumns) {
					json_obj[column.field] = this.editedRow[column.field];
				}

				this.$emit('edit-applied', json_obj);
				this.$emit('input', false);
			},

			resetEdit() {
				for (const column of this.editedColumns) {
					this.resetValue(column);
				}
			},

			...mapActions('currentTempTable', [
				'getRegionList'
			])
		}
	};
</script>

<style scoped>
	
	.edit-component {
		display: flex;
		flex-direction: column;
		/*max-height: 70vh;*/
	}

	.edit-component__field-container {

		border-top: 1px solid #bbb;
		border-bottom: 1px solid #bbb;

		padding: 1rem 0;

		max-height: 60vh;
		overflow-y: scroll;
	}

	.edit-component__field-column {
		padding: 0.4rem 0.8rem;
		vertical-align: middle;

	}

	.edit-component__field-name-column {
		font-weight: bold;
		max-width: 300px;
		text-align: right;
	}

	.edit-component__field-input-column {
		width: 300px;
	}

	.edit-component__field-reset-btn-column {
		text-align: center;
		width: 60px;
	}


	.edit-component__table-field-list tr.edited td {
		background-color: orange;
	}

	.edit-component__btn-block {
		display: flex;
		justify-content: flex-end;
		margin-top: 2rem;
	}
</style>