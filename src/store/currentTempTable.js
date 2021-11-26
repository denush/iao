import fetchData from '@/lib/fetchData';
import processJSON from '@/lib/processJSON';

import catalogs from './currentTempTable__catalogs';

const ROOT_API = process.env.VUE_APP_ROOT_API;
const GET_TEMP_TABLE_TEMPLATE = ROOT_API + 'get_tmp_table_template.php';
const GET_TEMP_TABLE = ROOT_API + 'get_tmp_table.php';
const GET_TEMP_TABLE_ROW_COUNT = ROOT_API + 'get_tmp_table_row_count.php';
const VERIFY_TEMP_TABLE = ROOT_API + 'verify_temp_table.php';
const COPY_TMP_TO_MAIN = ROOT_API + 'current_temp_table/copy_tmp_to_main.php';

export default {
	namespaced: true,

	modules: {
		catalogs
	},

	state: {
		tempTableTemplate: null,
		tempTableTemplateIsLoading: false,

		tempTable: [],
		tempTableId: null,
		tempTableLoading: false,
		tempTableVerifying: false,

		tempTableCurrentPage: 1,
		tempTablePerPage: 100,
		tempTableRowCount: 0,
		tempTableRowCountTotal: 0,

		tempTableFilter: {},

		tempTableMovingToReestr: false,
	},

	getters: {
		tempTableFilterStr: (state) => {
			/**
				filter string example
				(forestry=''Аганское'' or forestry=''Агрызское'' ) and (localforestry=''Агрызское'' ) 
			 */

			const filter = state.tempTableFilter;

			const field_str_list = [];

			for (const field in filter) {

				const value_str_list = [];

				for (const value of filter[field]) {
					let value_str = field + "=''";
					value_str += value + "''";

					value_str_list.push(value_str);
				}

				let field_str = '(';
				field_str += value_str_list.join(' or ');
				field_str += ')';

				field_str_list.push(field_str);
			}

			const filter_str = field_str_list.join(' and ');

			return filter_str;
		}
	},

	mutations: {
		setTempTableTemplate(state, template) { state.tempTableTemplate = template; },
		setTempTableTemplateIsLoading(state, loading) { state.tempTableTemplateIsLoading = loading; },

		setTempTable(state, table) { state.tempTable = table; },
		setTempTableId(state, tableId) { state.tempTableId = tableId; },
		setTempTableLoading(state, loading) { state.tempTableLoading = loading; },
		setTempTableVerifying(state, verifying) { state.tempTableVerifying = verifying; },

		setTempTableCurrentPage(state, currentPage) { state.tempTableCurrentPage = currentPage; },
		setTempTableRowCount(state, payload) {
			state.tempTableRowCount = +payload.row_count;
			state.tempTableRowCountTotal = +payload.total_row_count;
		},

		setTempTableFilter(state, filter_obj) {
			for (const filter_field in filter_obj) {

				if (filter_obj[filter_field].length) {
					const filter_field_data = JSON.parse(JSON.stringify(filter_obj[filter_field]));
					state.tempTableFilter[filter_field] = filter_field_data;
				} else {
					delete state.tempTableFilter[filter_field];
				}

			} 
		},

		setTempTableMovingToReestr(state, moving) { state.tempTableMovingToReestr = moving; },
	},

	actions: {
		resetAllChecked(context) {
			const { state } = context;

			if (!state.tempTableId) {
				throw new Error('GET_TEMP_TABLE__NO_TABLE_ID');
			}

			const update_section = [];
			for (const row of state.tempTable) {
				const temp = {
					'tab_name': 'tmp_tabs.' + state.tempTableId,
					'f_v': {
						'id': row.id,
						'is_row_checked': false,
						'is_row_correct': false
					}
				};

				update_section.push(temp);
			}


			const json_obj = [
				{
					'u': update_section
				}
			];

			const json_str = JSON.stringify(json_obj);

			return processJSON(json_str);
		},

		getTempTableTemplate(context) {
			const { commit } = context;

			commit('setTempTableTemplateIsLoading', true);

			return fetchData(GET_TEMP_TABLE_TEMPLATE).then(res => {
				commit('setTempTableTemplate', res);
			}).finally(() => {
				commit('setTempTableTemplateIsLoading', false);
			})

		},

		getTempTable(context, table_id) {
			const { state, getters, commit } = context;

			if (table_id) {
				commit('setTempTableId', table_id);
			}

			if (!state.tempTableId) {
				throw new Error('GET_TEMP_TABLE__NO_TABLE_ID');
			}

			const form = new FormData();
			form.append('table_id', state.tempTableId);
			form.append('current_page', state.tempTableCurrentPage);
			form.append('per_page', state.tempTablePerPage);
			form.append('filter', getters.tempTableFilterStr);

			commit('setTempTableLoading', true);

			return fetchData(GET_TEMP_TABLE, form).then(res => {
				commit('setTempTable', res);
			}).then(res => {
				return fetchData(GET_TEMP_TABLE_ROW_COUNT, form).then(res => {
					if (res.length) {
						commit('setTempTableRowCount', res[0]);
					} else {
						throw new Error('CANT_GET_TEMP_TABLE_ROW_COUNT');
					}
				});
			}).finally(() => {
				commit('setTempTableLoading', false);
			});
		},

		saveTempTableData(context, json) {
			return processJSON(json);
		},

		verifyTempTable(context, is_global) {
			const { state, commit } = context;

			if (!state.tempTableId) {
				throw new Error('GET_TEMP_TABLE__NO_TABLE_ID');
			}

			if (!is_global) {
				is_global = false;
			}

			const form = new FormData();
			form.append('table_id', state.tempTableId);
			form.append('is_global', is_global);

			commit('setTempTableVerifying', true);

			return fetchData(VERIFY_TEMP_TABLE, form).then(res => {
				
				if (res?.[0]?._result === 't') {
					return 0; // success
				} else {
					throw new Error('VERIFY_TEMP_TABLE__VERIFY_FAILURE');
				}
				
			}).finally(() => {
				commit('setTempTableVerifying', false);
			});
		},

		MOVE_TEMP_TABLE_TO_REESTR(context) {
			const { state, commit } = context;

			const form = new FormData();
			form.append('table_id', state.tempTableId);

			commit('setTempTableMovingToReestr', true);

			return fetchData(COPY_TMP_TO_MAIN, form).then(res => {

				console.log(res);

			}).finally(() => {
				commit('setTempTableMovingToReestr', false);
			});
		}
	}
};