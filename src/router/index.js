import { createRouter, createWebHashHistory } from 'vue-router';
import store from '@/store';

import MainComponent from '@/components/MainComponent';
import LoginComponent from '@/components/LoginComponent';

import DataReestr from '@/components/DataReestr';
import LoadFile from '@/components/LoadFile';
import TempTables from '@/components/TempTables';
import CurrentTempTable from '@/components/CurrentTempTable';
import CurrentTempTableForestries from '@/components/CurrentTempTableForestries';

const routes = [
  {
    name: 'main',
    path: '/',
    redirect: { name: 'temp-tables' },
    component: MainComponent,
    meta: { requiresAuth: true },
    children: [
      { name: 'journal', path: 'journal', component: DataReestr },
      { name: 'load', path: 'load', component: LoadFile },
      { name: 'temp-tables', path: 'temp-tables', component: TempTables },
      { name: 'temp-table', path: 'temp-table/:table_id', component: CurrentTempTable },
      { name: 'temp-table-forestries', path: 'temp-table-forestries/:table_id', component: CurrentTempTableForestries }
    ]
  },

  { name: 'login', path: '/login', component: LoginComponent },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (await store.dispatch('auth/checkLogin')) {
      if (!store.state.auth.user) {
        await store.dispatch('auth/getUserInfo');
      }
      next();
    } else {
      next({ name: 'login' });
    }
  } else {
    next();
  }
});

export default router;