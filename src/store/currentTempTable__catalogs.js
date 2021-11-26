import fetchData from '@/lib/fetchData';

const ROOT_API = process.env.VUE_APP_ROOT_API;
const GET_REGIONS = ROOT_API + 'catalogs/get_regions.php';
	
export default {
	state: {


		region_list: [],
		forestry_list: [],
		localforestry_list: [],
		subforestry_list: [],

	},

	mutations: {
		setCatalog(state, payload) {
			const { catalogName, catalogData } = payload;

			state[catalogName] = catalogData;
		}
	},

	actions: {
		loadCatalogs(context) {
			console.log('lalalal');
		},

		getAddressLists(context, payload) {

		},

		getRegionList(context, payload) {
			const { commit } = context;

			const fo_id = '5';

			console.log('get region id');

			const form = new FormData();
			form.append('fo_id', fo_id);

			return fetchData(GET_REGIONS, form).then(res => {
				const resObj = {
					catalogName: 'region_list',
					catalogData: res
				};
				commit('setCatalog', resObj);
			});
		},

		getForestryList(context, payload) {

		}
	}

};