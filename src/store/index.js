import { createStore } from 'vuex';

import auth from './auth';
import tempTables from './tempTables';
import currentTempTable from './currentTempTable';
import dataReestr from './dataReestr';


export default createStore({
	
	modules: {
  	auth,
  	tempTables,
    currentTempTable,
    dataReestr
  },

  state: {
  },
  mutations: {
  },
  actions: {
  }

});

