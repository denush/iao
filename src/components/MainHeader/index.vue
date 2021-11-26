<template>
	<div class='main-header'>

		<div class='main-header__top-content main-wrapper'>
			<div class='top-content__left'>
				<div class='top-content__title'>
					Информационно-Аналитический Отдел
				</div>
			</div>

			<div class='top-content__right' @click='toggleUserMenu'>
				<div class='main-header__user'>
					<span>{{ user.name + ' '}}</span>
					<fa-icon icon='caret-down'/>
				</div>
			</div>
		</div>

		<HeaderNavbar/>

		<p-menu ref='user-menu' :model='userMenu' popup/>

	</div>
</template>

<script>
	import { mapState, mapActions } from 'vuex';
	import HeaderNavbar from './MainHeader__navbar';

	export default {
		name: 'MainHeader',
		components: {
			HeaderNavbar
		},

		data() {
			return {
				userMenu: [
					{
						label: 'Выйти',
						icon: 'fa fa-sign-out-alt',
						command: () => this.logout()
					}
				]
			};
		},

		computed: {
			...mapState('auth', {
				user: state => state.user
			})
		},

		methods: {
			toggleUserMenu() {
				this.$refs['user-menu'].toggle(event);
			},

			logout() {
				this.store_logout().then(res => {
					this.$router.push({ name: 'login' });
				});
			},

			...mapActions('auth', {
				store_logout: 'logout'
			})
		}
	};
</script>

<style lang='scss' scoped src='@/styles/main_wrapper.scss'></style>
<style lang='scss' scoped src='@/styles/constants.scss'></style>

<style scoped>
	.main-header {
		background-color: #eff2f5;
		box-shadow: 0 0.2rem 0.7rem #333;
		color: #7b8fa3;
	}

	.main-header__top-content {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		padding-top: 1rem;
		padding-bottom: 1rem;
	}

	.main-header__user {
		cursor: pointer;
	}

	.top-content__title {
		font-size: 1.2rem;
	}
</style>