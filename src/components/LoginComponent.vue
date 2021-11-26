<template>
	<div class='login-component'>
		<div class='login-form'>
			<div class='login-form__field'>
				<div class='login-form__field-title'>Имя пользователя</div>
				<div class='login-form__field-input-container'>
					<custom-p-input v-model='username' placeholder=''/>
				</div>
			</div>
			<div class='login-form__field'>
				<div class='login-form__field-title'>Пароль</div>
				<div class='login-form__field-input-container'>
					<custom-p-input v-model='password' placeholder=''/>
				</div>
			</div>
<!-- 			<div class='login-form__field'>
				<custom-p-checkbox v-model='remember' label='запомнить меня'/>
			</div> -->
			<div class='login-form__field'>
				<p-button label='Войти' @click='login' class='login-form__submit'/>
			</div>
		</div>
	</div>
</template>

<script>
	import { ref } from 'vue';
	import { useStore } from 'vuex';
	import { useRouter } from 'vue-router';

	export default {
		name: 'LoginCompnent',

		setup() {
			const store = useStore();
			const router = useRouter();

			const username = ref('');
			const password = ref('');
			const remember = ref(true);

			const login = () => {
				return store.dispatch('auth/LOGIN', {
					username: username.value,
					password: password.value,
					remember: remember.value
				}).then(res => {
					if (store.getters['auth/logged']) {
						router.push({ name: 'main' });
					}
				});
			};

			return {
				username,
				password,
				remember,
				login
			};
		}

	};
</script>

<style scoped>
	.login-component {
		flex-direction: column;
		gap: 1rem;
		height: 100vh;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.login-form {
		display: flex;
		flex-direction: column;
		gap: 1rem;
		width: 300px;
	}

	.login-form__field-title {
		margin-bottom: 0.5rem;
	}

	.login-form__submit {
		width: 100%;
	}
</style>