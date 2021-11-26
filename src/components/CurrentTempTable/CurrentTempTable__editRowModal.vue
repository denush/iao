<template>
	<p-dialog
		:visible='visible'
		modal
		@update:visible='$emit("update:visible", $event)'
	>
		<!-- {{ tempTableTemplate }} -->
		<!-- {{ editedRow }} -->

		<div class='edit-block'>

			<div class='edit-block__field-list'>

				<div
					v-for='item in tempTableTemplate'
					:key='item.id'
					@click='select($event, item)'
					class='edit-block__field-item'
					:class='{ "edit-block__field-item--selected": isSelected(item) }'
				>
					<div class='edit-block__field-item-label'>{{ item.field_num + '. ' + item.name }}</div>

					<div class='edit-block__field-item-value'>
						<!-- <template v-if='isSelected(item)'>

							<p-input-text

							/>
						</template>
						<template v-else>
							{{ editedRow[item.field] ?? '(не указано)'}}
						</template> -->

						{{ editedRow[item.field] ?? '(не указано)'}}

						
					</div>

					<!-- <div :ref='"test-" + item.id'>lala</div> -->

					<p-overlay-panel :ref='"op-" + item.id'>
						<p-input-text/>
					</p-overlay-panel>
				</div>

			

			</div>

			

			<div class='edit-block__button-block'>
				<p-button label='Отмена'/>
				<p-button label='Сохранить'/>
			</div>

		</div>


	</p-dialog>
</template>

<script>
	import { mapState } from 'vuex';

	export default {
		name: 'CurrentTempTableEditRowModal',

		props: {
			visible: Boolean,
			editedRow: Object,
		},

		emits: {
			'update:visible': null
		},

		data: () => ({
			selectedFieldItem: null,
			selectedValue: null
		}),

		computed: {
			...mapState('currentTempTable', {
				tempTableTemplate: state => state.tempTableTemplate,
			})
		},

		methods: {
			select(event, fieldItem) {
				// console.log(this.$refs);
				
				// return;


				this.selectedFieldItem = fieldItem;

				// this.$refs['op'].toggle(event);
				this.$refs['op-' + fieldItem.id].toggle(event);

				// setTimeout(() => {
				// 	console.log(event);
				// 	this.$refs['op'].show(event);
				// }, 500);
				

				
			},

			isSelected(fieldItem) {
				return this.selectedFieldItem?.id === fieldItem.id;
			},

			setValue() {

			}
		}
	};
</script>

<style scoped>
	.edit-block__field-list {
		/*display: flex;*/
		/*flex-wrap: wrap;*/


	}

	.edit-block__field-item {
		padding: 1rem;
	}

	.edit-block__field-item:hover {

		/*border: 2px solid dodgerblue;*/
		box-shadow: 0px 0px 10px dodgerblue;
		cursor: pointer;

		/*transform: scale(1.01);*/
		/*transition: all 0.2s ease-out;*/
	}

	.edit-block__field-item--selected {
		background-color: lightblue;
	}

	.edit-block__field-item-label {
		color: #999;
	}

	.edit-block__field-item-value {
		font-weight: bold;
	}

	.edit-block__button-block {
		position: sticky;
		bottom: 0;
	}
</style>