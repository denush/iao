<template>
	<p-dialog
		:visible='value'
		
		modal
		:dismissableMask='dismissable'

		:header='title'

		:contentStyle='contentStyle'

		@update:visible='$emit("update:visible", $event)'
		@show='$emit("show", $event)'
		@hide='$emit("hide", $event)'
	>
		<slot name='default'></slot>
	</p-dialog>
</template>

<script>
	export default {
		name: 'CustomPModal',

		props: {
			value: {
				type: Boolean,
				default: false
			},

			title: {
				type: String,
				default: ' '
			},

			dismissable: {
				type: Boolean,
				default: true
			},

			contentStyle: {
				type: Object,
				default: () => ({})
			}
		},

		emits: {
			'update:visible': null,
			show: null,
			hide: null,
		},

		// methods: {
		// 	onInput(event) { this.$emit('input', event); },

		// 	onShow(event) { this.$emit('show', event); },

		// 	onHide(event) { this.$emit('hide', event); }
		// }

	};
</script>

<style scoped>
</style>