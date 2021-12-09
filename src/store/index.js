import { createStore } from 'vuex';

import auth from './auth';
import tempTables from './tempTables';
import currentTempTable from './currentTempTable';
import currentTempTableForestries from './currentTempTableForestries';
import dataReestr from './dataReestr';


export default createStore({
	
	modules: {
  	auth,
  	tempTables,
    currentTempTable,
    currentTempTableForestries,
    dataReestr
  },

  state: {
  },
  mutations: {
  },
  actions: {
  }

});

