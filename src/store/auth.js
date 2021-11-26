const ROOT_API = process.env.VUE_APP_ROOT_API;

const LOGIN = ROOT_API + 'auth/login.php';
const LOGOUT = ROOT_API + 'auth/logout.php';
const CHECK_LOGIN = ROOT_API + 'auth/check_login.php';
const GET_USER_INFO = ROOT_API + 'auth/get_user_info.php';

export default {
	namespaced: true,

	state: {
		user_id: null,
		user: null,
	},

	getters: {
		logged(state) { return state.user_id && state.user_id !== '-1'; }
	},

	mutations: {
		setUserId(state, user_id) {
			state.user_id = user_id;
		},

		setUserInfo(state, user) {
      state.user = user;
    },
	},

	actions: {
		LOGIN(context, creds) {
      const { commit } = context;

      const form = new FormData();
      form.append('username', creds.username);
      form.append('password', creds.password);
      form.append('remember', creds.remember);

      return fetch(LOGIN, {
        method: 'post',
        body: form,
        credentials: 'include'
      })
      .then(res => res.text())
      .then(res => {
        if (!isNaN(+res)) {
          commit('setUserId', res);
          return res;
        } else {
          return '-1';
        }
      })
      .catch(err => {
        commit('setUserId', '-1');
        throw err;
      });
    },

    logout(context) {
      const { state, commit } = context;

      return fetch(LOGOUT, {
        credentials: 'include',
        method: 'post'
      })
      .then(res => {
        state.user_id = null;
        state.user = null;
      })
      .catch(err => {
        throw err;
      })
      .finally(() => {
        commit('setUserId', '-1');
      });
    },

		checkLogin(context, payload) {
			const { getters, commit } = context;

      if (getters.logged) {
        return true;
      }

      return fetch(CHECK_LOGIN, {
        credentials: 'include',
        method: 'post'
      })
      .then(res => res.text())
      .then(res => {
        if (!isNaN(+res)) {
          commit('setUserId', res);
        }
        else {
          commit('setUserId', '-1');
        }
        return getters.logged;
      })
      .catch(err => {
        commit('setUserId', '-1');
        throw err;
      });
		},

		getUserInfo(context) {
      const { state, getters, commit } = context;

      if (!getters.logged) {
        return;
      }

      const form = new FormData();
      form.append('user_id', state.user_id);

      return fetch(GET_USER_INFO, {
        credentials: 'include',
        method: 'post',
        body: form
      })
      .then(res => res.json())
      .then(res => {
        commit('setUserInfo', res);
      })
    }
	}
};