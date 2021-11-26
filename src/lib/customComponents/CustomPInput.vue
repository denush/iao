<template>
	<div class='p-custom-input-container'>
		<p-input-text
	
			:modelValue='modelValue'
			:placeholder='placeholder'
			:disabled='disabled'
			@update:modelValue='onUpdateModelValue'
			:type='type'
		/>

<!-- 		<p-input-number
			v-else-if='type === "number"'
			:value='value'
			:placeholder='placeholder'
			:disabled='disabled'
			@input='onUpdateModelValue'
			:type='numberMode'

		/> -->
	</div>
</template>

<script>
	export default {
		name: 'CustomPInput',

		props: {
			modelValue: {
				type: null, /* null - любой тип */
				required: true
			},

			placeholder: {
				type: String,
				default: 'Введите значение'
			},

			disabled: {
				type: Boolean,
				default: false
			},

			type: {
				type: String,
				default: null
			}
		},

		emits: {
			'update:modelValue': null
		},

		methods: {
			onUpdateModelValue(event) {
				if (event === '') {
					event = null;
				}

				this.$emit('update:modelValue', event);
			},
		}
	};
</script>

<style scoped>
	.p-inputtext,
	.p-inputnumber {
		width: 100%;
	}
</style>