<template>
	<p-dialog
		modal
		:dismissableMask='true'
		:visible='visible'
		@update:visible='$emit("update:visible", $event)'
		@show='onModalShow'
		@hide='onModalHide'
	>

		<div v-if='!currentColumn.info_table_name && !currentColumn.function'>
			<div>
				{{ currentFieldValue }}
				<p-button icon='fa fa-angle-double-down' @click='setCurrentValueAsNew'/>
			</div>

			<p-input-text
				:model-value='newFieldValue'
				@update:model-value='newFieldValue = ($event === "" ? null : $event)'
				ref='new-value-input'
			/>

			<transition name="p-message" tag="div">
      	<p-message
      		v-if='warningMessageIsShown'
      		severity='warn'
      		@close='warningMessageIsShown = false'
      	>
      		Введено текущее значение
      	</p-message>
      </transition>

			<div>
				<p-button label='Отмена' @click='cancel' class='p-button-sm'/>
				<p-button label='Принять' @click='apply' class='p-button-sm'/>
			</div>
		</div>

		<div v-else>
			no
		</div>

	</p-dialog>
</template>

<script>
	import { mapState } from 'vuex';

	export default {
		name: 'CurrentTempTableQuickEditModal',

		props: {
			visible: Boolean,
			currentRow: Object,
			currentColumn: Object,
		},

		emits: {
			'update:visible': null,
			applied: null,
			hide: null
		},

		data: () => ({
			newFieldValue: null,
			warningMessageIsShown: false,
		}),

		computed: {
			currentFieldValue() {
				return this.currentRow[this.currentColumn.field];
			}
		},

		methods: {
			setCurrentValueAsNew() {
				this.newFieldValue = this.currentFieldValue;
				this.focusInput();
			},

			onModalShow() {
				this.focusInput();
			},

			onModalHide() {
				this.newFieldValue = null,
				this.$emit('hide');
			},

			focusInput() {
				this.$refs['new-value-input'].$el.focus();
			},

			apply() {
				this.warningMessageIsShown = false;

				if (this.newFieldValue === this.currentFieldValue) {
					this.warningMessageIsShown = true;
					return;
				}

				const result = {
					id: this.currentRow.id,
					[this.currentColumn.field]: this.newFieldValue
				};

				this.$emit('applied', result);
			},

			cancel() {
				this.$emit('update:visible', false);
			},
		}

	};
</script>