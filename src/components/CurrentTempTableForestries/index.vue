<template>

	<div class='temp-table-current'>

		<template v-if='!tempTableTemplate'>
			<div>
				<p-spinner v-if='tempTableTemplateIsLoading'/>
			</div>
		</template>

		<template v-else>

			<TableToolbar
				:edit-json='editJson'
				:address-at-start='addressAtStart'
				:filter-only-incorrect='filterOnlyIncorrect'
				@save-success='onSaveSuccess'
				@address-at-start-changed='addressAtStartChanged'
				@filter-only-incorrect-changed='filterOnlyIncorrectChanged'
			/>

			<div class='temp-table-current__table-container'>
				<TableComponent
					:edited-rows='editedRows'
					:address-at-start='addressAtStart'
					:filter-only-incorrect='filterOnlyIncorrect'
					@start-quick-edit='onStartQuickEdit'
					@edit-btn-pushed='onEditBtnPushed'
					@save-quick-edit='onSaveQuickEdit'
				/>
			</div>

			<p-paginator
				:rows='tempTablePerPage'
				:totalRecords='tempTableRowCount'
				@page='onPage'
			/>

			<QuickEditModal
				v-model:visible='quickEditModalOpen'
				:current-row='quickEditedRow'
				:current-column='quickEditedColumn'
				@applied='onQuickEditApplied'
				@hide='onQuickEditModalHide'
			/>

		</template>

	</div>

</template>

<script>
	import { mapState, mapMutations, mapActions } from 'vuex';

	import TableToolbar from './CurrentTempTableForestries__toolbar';
	import TableComponent from './CurrentTempTableForestries__table';
	import QuickEditModal from './CurrentTempTableForestries__quickEditModal';

	export default {
		name: 'TempTablesTempTable',

		components: {
			TableToolbar,
			TableComponent,
			QuickEditModal
		},

		data: () => ({
			editModalOpen: false,
			selectedRow: null,

			// editedRows: [],

			editJson: null,

			addressAtStart: false,
			filterOnlyIncorrect: false,


			// Редактирование всей строки //
			editRowModalOpen: false,
			editedRow: null,

			// Быстрое редактирование //
			quickEditModalOpen: false,
			quickEditedRow: null,
			quickEditedColumn: null,
		}),

		computed: {
			editedRows() {
				if (!this.editJson) {
					return [];
				}

				return JSON.parse(this.editJson)[0]['u'].map(item => item.f_v);
			},

			...mapState('currentTempTableForestries', {
				tempTableTemplate: state => state.tempTableTemplate,
				tempTableTemplateIsLoading: state => state.tempTableTemplateIsLoading,
				tempTablePerPage: state => state.tempTablePerPage,
				tempTableRowCount: state => state.tempTableRowCount
			})
		},

		watch: {
			filterOnlyIncorrect(filterOnlyIncorrect) {
				if (filterOnlyIncorrect) {
					const filter_obj = {
						is_row_correct: [ false ],
						is_row_checked: [ true ]
					};

					this.setTempTableFilter(filter_obj);
				} else {
					const filter_obj = {
						is_row_correct: [],
						is_row_checked: []
					};

					this.setTempTableFilter(filter_obj);
				}

				this.applyFilter();
			},
		},

		methods: {
			applyFilter() {
				this.setTempTableCurrentPage(1);
				this.s_getTempTable();
			},

			onSaveQuickEdit({ row, column, value }) {
				const table_id = this.$route.params.table_id;

				const json_obj = [
					{
						'u': null
					}
				];

				if (column.info_table_name) {
					const update_section = [
						{
							'tab_name': 'tmp_tabs_forestries.' + table_id,
							'f_v': {
								'id': row.id,
								[column.field]: value === null ? value : value[column.info_table_field],
								'is_row_checked': false,
								'is_row_correct': false
							}
						}
					];

					json_obj[0].u = update_section;
				} else {
					const update_section = [
						{
							'tab_name': 'tmp_tabs_forestries.' + table_id,
							'f_v': {
								'id': row.id,
								[column.field]: value,
								'is_row_checked': false,
								'is_row_correct': false
							}
						}
					];

					json_obj[0].u = update_section;
				}

				const json_str = JSON.stringify(json_obj);
				// console.log(json_str);
				// return;

				this.s_saveTempTableData(json_str).then(res => {
					return this.s_verifyTempTable();
				}).then(res => {
					return this.s_getTempTable();
				})
			},

			onStartQuickEdit({ row, column }) {
				this.quickEditedRow = row;
				this.quickEditedColumn = column;

				this.quickEditModalOpen = true;
			},

			onQuickEditApplied(fieldData) {
				console.log(fieldData);
			},

			onQuickEditModalHide() {
				this.quickEditedRow = null;
				this.quickEditedColumn = null;
			},

			addressAtStartChanged(value) {
				this.addressAtStart = value;
			},

			filterOnlyIncorrectChanged(value) {
				this.filterOnlyIncorrect = value;
			},

			onSaveSuccess() {
				this.editJson = null;
			},

			onPage(event) {
				const page = event.page + 1;

				this.setTempTableCurrentPage(page);
				this.getTempTable();
			},

			getTempTable() {
				const table_id = this.$route.params.table_id;

				if (!table_id) {
					throw new Error('TEMP_TABLE_NO_TABLE_ID');
				}

				this.s_getTempTable(table_id);
			},

			onEditBtnPushed(row) {
				this.editedRow = row;
				this.editRowModalOpen = true;
			},

			onEditRowApplied(json_obj) {
				// const index = this.editedRows.findIndex(row => row.id === json_obj.id);

				this.formJson(json_obj);

				/* если в списке отредактированных строк уже есть данная строка, удаляем ее оттуда */
				// if (index !== -1) {
				// 	this.editedRows.splice(index, 1);
				// }
				/* если отредактированы какие-либо данные, записываем строку в список отредактированных строк */
				/* length > 1   =   в вернувшейся строке есть отредактированные данные, а не только поле id */
				// if (Object.keys(json_obj).length > 1) {
				// 	this.editedRows.push(json_obj);
				// }
			},

			formJson(json_row) {
				// json_row - только что отредактированная строка

				const table_id = this.$route.params.table_id;
				let json_obj = null;

				if (this.editJson) {
					json_obj = JSON.parse(this.editJson);
				} else {
					json_obj = [{'u': []}];
				}
					
				/* получаем все отредактированные строки */
				const editedRows = JSON.parse(JSON.stringify( json_obj[0]['u'].map(item => item.f_v) ));
		
				/* есть в уже отредактированных строках только что отредактириванная */
				const findedIndex = editedRows.findIndex(row => row.id === json_row.id);

				if (Object.keys(json_row).length > 1) {
					if (findedIndex !== -1) {
						editedRows.splice(findedIndex, 1);
						editedRows.push(json_row);
					} else {
						editedRows.push(json_row);
					}
				} else {
					if (findedIndex !== -1) {
						editedRows.splice(findedIndex, 1);
					} 
				}

				if (editedRows.length) {
					const updateSection = [];

					for (const row of editedRows) {
						const temp = {
							'tab_name': 'tmp_tabs_forestries.' + table_id,
							'f_v': { ...row }
						};

						/* обнуление флагов состояния строки */
						temp.f_v.is_row_checked = false;
						temp.f_v.is_row_correct = false;

						updateSection.push(temp);
					}

					this.editJson = JSON.stringify( [ { 'u': updateSection } ] );
				} else {
					this.editJson = null;
				}
			},

			...mapMutations('currentTempTableForestries', [
				'setTempTableCurrentPage',
				'setTempTableFilter'
			]),

			...mapActions('currentTempTableForestries', {
				getTempTableTemplate: 'getTempTableTemplate',
				s_getTempTable: 'getTempTable',
				s_saveTempTableData: 'saveTempTableData',
				s_verifyTempTable: 'verifyTempTable',
			}),

		},

		created() {
			if (!this.tempTableTemplate) {
				this.getTempTableTemplate();
			}
			
			this.getTempTable();

			
		}
	};
</script>

<style scoped>
	.temp-table-current {
		height: 100%;
		display: flex;
		flex-direction: column;
	}

	.temp-table-current__table-container {
		flex: 1;
		overflow: auto;
	}

</style>