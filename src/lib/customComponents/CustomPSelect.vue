<template>
	<div class='p-custom-select-container'>

		<!-- MULTIPLE SELECT -->
		<template v-if='multiple'>
			<p-multiselect
				:value='value'
				:options="options"
				:optionLabel="optionLabel"
				:dataKey='dataKey'
				placeholder="Выберите значения"
				filter
				filterPlaceholder='Поиск...'
				emptyFilterMessage='Не найдено элементов'
				:disabled='disabled'
				@input='onInput'
				:appendTo='appendTo'
			/>
		</template>

		<!-- SINGLE SELECT -->
		<template v-else>
			<p-dropdown
				:value='value'
				:options='options'
				:optionLabel='optionLabel'
				:dataKey='dataKey'
				placeholder="Выберите значение"
				showClear
				filter
				emptyFilterMessage='Не найдено элементов'
				:disabled='disabled'
				@input='onInput'
				:appendTo='appendTo'
			/>
		</template>

		<p-spinner v-if='loading' stroke-width='6'/>

	</div>
</template>

<script>
	export default {
		name: 'CustomPSelect',

		props: {
			value: {
				type: null, /* null - любой тип */
				required: true
			},

			options: {
				type: Array,
				required: true,
				default: () => [],
			},

			optionLabel: {
				type: String,
				default: 'name',
			},

			dataKey: {
				type: String,
				default: 'id',
			},

			multiple: {
				type: Boolean,
				default: false
			},

			disabled: {
				type: Boolean,
				default: false
			},

			loading: {
				type: Boolean,
				default: false
			},

			appendTo: {
				type: String,
				default: null
			}
		},

		methods: {
			onInput(value) {
				this.$emit('input', value);
			},
		}

	};
</script>

<style scoped>
	.p-custom-select-container {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.p-progress-spinner {
		height: 24px;
		width: 24px;
	}

	.p-dropdown,
	.p-multiselect {
		width: 100%;
	}

	.p-dropdown >>> .p-dropdown-label,
	.p-multiselect >>> .p-multiselect-label {
		overflow: visible;
		white-space: normal;
	}

	.p-dropdown >>> .p-dropdown-panel,
	.p-multiselect >>> .p-multiselect-panel {
		width: 100%;
	}

	.p-dropdown >>> .p-dropdown-items-wrapper,
	.p-multiselect >>> .p-multiselect-items-wrapper {
		width: 100%;
	}

	.p-dropdown >>> .p-dropdown-item,
	.p-multiselect >>> .p-multiselect-item {
		white-space: normal;
	}
</style>