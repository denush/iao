import fetchData from '@/lib/fetchData';

const ROOT_API = process.env.VUE_APP_ROOT_API;
const API_GET_TABLE_TEMPLATE = ROOT_API + 'get_tmp_table_template.php';
const API_GET_REESTR_DATA_TABLE = ROOT_API + 'get_reestr_data_table.php';
const API_GET_REESTR_DATA_TABLE_ROW_COUNT = ROOT_API + 'get_reestr_data_table_row_count.php';

export default {
	namespaced: true,

	state: {
		tableTemplate: null,
		tableTemplateLoading: false,

		reestrDataTable: [],
		reestrDataTableLoading: false,

		reestrDataTableCurrentPage: 1,
		reestrDataTablePerPage: 100,
		reestrDataTableRowCount: 0,
		// dataReestrTableRowCountTotal: 0,
	},

	mutations: {
		setTableTemplate(state, template) { state.tableTemplate = template; },
		setTableTemplateLoading(state, loading) { state.tableTemplateLoading = loading; },
		setReestrDataTable(state, table) { state.dataReestrTable = table; },
		setReestrDataTableLoading(state, loading) { state.dataReestrTableLoading = loading; },
		setReestrDataTableRowCount(state, payload) { state.reestrDataTableRowCount = +payload.row_count; },
		setReestrDataTableCurrentPage(state, page) { state.reestrDataTableCurrentPage = page; }
	},

	actions: {
		GET_TABLE_TEMPLATE({ commit }) {
			commit('setTableTemplateLoading', true);

			return fetchData(API_GET_TABLE_TEMPLATE).then(res => {
				commit('setTableTemplate', res);
			}).finally(() => {
				commit('setTableTemplateLoading', false);
			});
		},

		GET_REESTR_DATA_TABLE({ state, commit }) {
			commit('setReestrDataTableLoading', true);

			const form = new FormData();
			form.append('current_page', state.reestrDataTableCurrentPage);
			form.append('per_page', state.reestrDataTablePerPage)
			form.append('filter', '');

			return fetchData(API_GET_REESTR_DATA_TABLE, form).then(res => {
				commit('setReestrDataTable', res);
			}).then(res => {
				return fetchData(API_GET_REESTR_DATA_TABLE_ROW_COUNT, form).then(res => {
					if (res.length) {
						commit('setReestrDataTableRowCount', res[0]);
					} else {
						throw new Error('CANT_GET_TEMP_TABLE_ROW_COUNT');
					}
				});
			}).finally(() => {
				commit('setReestrDataTableLoading', false);
			});
		},

		CHANGE_PAGE({ commit, dispatch }, page) {
			commit('setReestrDataTableCurrentPage', page);
			dispatch('GET_REESTR_DATA_TABLE');
		}
	}
};