<template>
	<div class='p-custom-checkbox-container' :class='{ "custom-checkbox--disabled": disabled }'>

		<label v-if='label && labelLeft' :for='checkboxId || innerId' class='label'>
			{{ label }}
		</label>

		<p-checkbox
			:modelValue='modelValue'
			:disabled='disabled'
			:binary='!multiple'
			@update:modelValue='onInput'
			:id='checkboxId || innerId'
			:value='multipleValue'
		/>

		<label v-if='label && !labelLeft' :for='checkboxId || innerId' class='label'>
			{{ label }}
		</label>

	</div>
</template>

<script>
	export default {
		name: 'CustomPCheckbox',

		props: {
			modelValue: {
				type: null, /* null - любой тип */
				required: true
			},

			multiple: {
				type: Boolean,
				default: false
			},

			multipleValue: {
				type: null,
				default: null
			},

			disabled: {
				type: Boolean,
				default: false
			},

			checkboxId: {
				type: String,
				default: null
			},

			label: {
				type: String,
				default: null
			},

			labelLeft: {
				type: Boolean,
				default: false
			}
		},

		data: () => ({
			innerId: null,
		}),

		methods: {
			onInput(newModelValue) {
				console.log(newModelValue);
				this.$emit('update:modelValue', newModelValue);
			},

			_generateUniqueId() {
				function chr4(){
			    return Math.random().toString(16).slice(-4);
			  }

			  return chr4() + chr4() +
		    '-' + chr4() +
		    '-' + chr4() +
		    '-' + chr4() +
		    '-' + chr4() + chr4() + chr4();
			}
		},

		mounted() {
			if (this.label && !this.checkboxId) {
				this.innerId = this._generateUniqueId();
			}
		}
	};
</script>

<style scoped>
	.p-custom-checkbox-container {
		display: flex;
		align-items: center;
		gap: 0.8rem;
	}

	.p-checkbox >>> .p-hidden-accessible {
		display: none;
	}

	.label {
		cursor: pointer;
	}

	.custom-checkbox--disabled .label {
		color: #bbb;
	}
</style>