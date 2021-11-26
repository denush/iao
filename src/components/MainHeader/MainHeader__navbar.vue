<template>
	<div class='navbar'>

		<div class='navbar__content main-wrapper'>
			<div
				@click='linkTo(link_journal)'
				class='navbar__item'
				:class='{ "navbar__item--selected": isSelected(link_journal) }'
			>
				Журнал
			</div>
			<div
				@click='linkTo(link_load)'
				class='navbar__item'
				:class='{ "navbar__item--selected": isSelected(link_load) }'
			>
				Загрузка
			</div>

			<div
				@click='linkTo(link_tempTables)'
				class='navbar__item'
				:class='{ "navbar__item--selected": isSelected(link_tempTables) }'
			>
				Таблицы
			</div>

		</div>

	</div>
</template>

<script>
	export default {
		data: () => ({
			link_journal: { name: 'journal' },
			link_load: { name: 'load' },
			link_tempTables: { name: 'temp-tables' },
		}),

		methods: {
			linkTo(link_obj) {
				if (link_obj.name === this.$route.name) {
					return;
				}

				this.$router.push(link_obj);
			},

			isSelected(link_obj) {
				return link_obj.name === this.$route.name;
			},
		}
	};
</script>

<style scoped lang='scss' src='@/styles/main_wrapper.scss'></style>

<style scoped>
	.navbar {
		background-color: #142739;
		font-size: 0.9rem;
	}
	.navbar__content {
		display: flex;
		flex-wrap: wrap;
		padding-top: 0;
		padding-bottom: 0;
	}

	.navbar__item {
		border-bottom: 4px solid #142739;
		color: #dee7f2;
		cursor: pointer;
		padding: 0.5rem 3rem;
	}

	.navbar__item:hover {
		border-bottom: 4px solid #dee7f2;
		background-color: #dee7f2;
		color: #142739;
	}

	.navbar__item--selected {
		border-bottom-color: #1385f6;
		color: #f4f7fb;
	}
	.navbar__item--selected:hover {
		border-bottom-color: #1385f6;
		background-color: #dee7f2;
		color: #142739;
	}
</style>