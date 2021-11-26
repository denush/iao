import fetchData from '@/lib/fetchData';

const ROOT_API = process.env.VUE_APP_ROOT_API;
const GET_TEMP_TABLE_LIST = ROOT_API + 'get_journal_tmp_tabs.php';
const REMOVE_TEMP_TABLE = ROOT_API + 'hide_tmp_table.php';

export default {
	namespaced: true,

	state: {
		tempTableList: [],
		tempTableListLoading: false
	},

	mutations: {
		setTempTableList(state, list) { state.tempTableList = list; },
		setTempTableListLoading(state, loading) { state.tempTableListLoading = loading; }
	},

	actions: {
		getTempTableList(context) {
			const { rootState, commit } = context;

			const user_id = rootState.auth.user_id;

			const form = new FormData();
			form.append('user_id', user_id);

			commit('setTempTableListLoading', true);

			return fetchData(GET_TEMP_TABLE_LIST, form).then(res => {
				commit('setTempTableList', res);
			}).finally(() => {
				commit('setTempTableListLoading', false);
			});
		},

		removeTempTable(context, table_id) {
			// console.log('rmeove', table_id);
			// return;

			const form = new FormData();
			form.append('table_id', table_id);

			return fetchData(REMOVE_TEMP_TABLE, form).then(res => {
				// console.log('removing done: ', res);
			});
		}

	}
};