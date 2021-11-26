<template>
	<div class='toolbar'>

		<div class='toolbar__left'>
			<!-- <p-button label='Сохранить изменения' @click='saveEditedRows' :disabled='!editJson' class='p-button-sm'/> -->
			<p-button label='Проверить все данные' @click='checkTable' :loading='tempTableVerifying' class='p-button-sm p-mr-2'/>

			<p-button label='Выгрузить строки с ошибками' @click='downloadErrorRows' :loading='isErrorsDownloading' class='p-button-sm p-mr-2'/>

			<p-button label='Выгрузить корректные строки в реестр' @click='moveTempTableToReestr' :loading='tempTableMovingToReestr' class='p-button-sm'/>
			<!-- <p-button label='get checked rows' @click='getCheckedRowList' class='p-button-sm p-ml-2'/> -->
			<!-- <p-button label='get incorrect rows' @click='getIncorrectRowList' class='p-button-sm p-ml-2'/> -->
			<!-- <p-button label='show json' @click='showJson' class='p-button-sm p-ml-2'/> -->
			<!-- <p-button label='log filter' @click='logFilter' class='p-button-sm p-ml-2'/> -->
			<!-- <p-button label='reset checked' @click='resetAllChecked' class='p-button-sm p-ml-2'/> -->
		</div>

		<div class='toolbar__right'>
			<div class='toolbar__adress-switch-block'>
				<p-switch :modelValue='addressAtStart' @update:modelValue='addressAtStartChanged'/>
				<span class='toolbar__label'>Адрес в начале</span>
			</div>
			<div class='toolbar__incorrect-filter-block'>
				<p-switch :modelValue='filterOnlyIncorrect' @update:modelValue='filterOnlyIncorrectChanged'/>
				<span class='toolbar__label'>Только некорректные</span>
			</div>
		</div>

	</div>
</template>

<script>
	import { ref, computed } from 'vue';
	import { useStore } from 'vuex';
	import { mapState, mapGetters, mapActions } from 'vuex';

	import tempTableTemplate from '@/lib/tempTableTemplate';

	const FORM_ERRORS = process.env.VUE_APP_ROOT_API + 'download_error_rows/index.php';

	export default {
		name: 'CurrentTempTableToolbar',

		setup() {
			const store = useStore();

			const isErrorsDownloading = ref(false);
			
			const tempTableMovingToReestr = computed(() => store.state.currentTempTable.tempTableMovingToReestr);

			const moveTempTableToReestr = () => {
				return store.dispatch('currentTempTable/MOVE_TEMP_TABLE_TO_REESTR').finally(() => {
					return store.dispatch('currentTempTable/getTempTable');
				});
			};

			return {
				isErrorsDownloading,
				moveTempTableToReestr,
				tempTableMovingToReestr
			};
		},

		props: {
			editJson: String,
			addressAtStart: Boolean,
			filterOnlyIncorrect: Boolean
		},

		computed: {
			...mapState('currentTempTable', {
				tempTableId:  state => state.tempTableId,
				tempTableTemplateIsLoading: state => state.tempTableTemplateIsLoading,
				tempTableVerifying: state => state.tempTableVerifying,
			}),

			...mapGetters('currentTempTable', [
				'tempTableFilterStr'
			]),

		},

		methods: {
			// moveRowsToReestr() {
			// 	console.log(this.isErrorDownloading);

			// 	this.isErrorDownloading = true;

			// 	setTimeout(() => {
			// 		console.log(this.isErrorDownloading);
			// 		this.isErrorDownloading = false;
			// 	}, 2000);
			// },

			downloadErrorRows() {
				console.log(this.isErrorsDownloading)

				const form = new FormData();
				form.append('table_id', this.tempTableId);

				this.isErrorsDownloading = true;

				fetch(FORM_ERRORS, {
	  			credentials: 'include',
	  			method: 'post',
	  			body: form
	  		})
	  		.then(res => res.json())
	  		.then(res => {

					const a = document.createElement('a');

					a.setAttribute('href', res.file);
					a.setAttribute("download", res.name);

					document.body.appendChild(a);
					a.click();
					a.remove();

	  		}).finally(() => {
	  			this.isErrorsDownloading = false;
	  		});
			},

			logFilter() {
				console.log(this.tempTableFilterStr);
				// console.log(this.tempTableTemplateIsLoading);
				// console.log(this.$store)
			},

			showJson() {
				console.log(this.editJson);
			},

			saveEditedRows() {
				this.saveTempTableData(this.editJson).then(res => {
					return this.getTempTable();
				}).then(res => {
					this.$emit('save-success');
				});
			},

			checkTable() {
				this.verifyTempTable(true).then(res => {
					return this.getTempTable();
				}).then(res => {
					this.$emit('check-completed');
				});
			},

			getCheckedRowList() {
				const result = this.$store.state.currentTempTable.tempTable.filter(item => {
					return item.is_row_checked === 't';
				});

				console.log(result);
			},

			getIncorrectRowList() {

				const json = JSON.stringify(tempTableTemplate);
				console.log(json);

				const result = this.$store.state.currentTempTable.tempTable.filter(item => {
					return item.is_row_checked === 't' && item.is_row_correct === 'f';
				});

				console.log(result);
			},

			addressAtStartChanged(modelValue) {
				this.$emit('address-at-start-changed', modelValue);
			},

			filterOnlyIncorrectChanged(modelValue) {
				this.$emit('filter-only-incorrect-changed', modelValue);
			},

			
			...mapActions('currentTempTable', [
				'saveTempTableData',
				'getTempTable',
				'verifyTempTable',
				'resetAllChecked'
			])
		}
	};
</script>

<style scoped>
	.toolbar {
		display: flex;
		justify-content: space-between;

		padding: 1rem;
	}

	.toolbar__right {
		display: flex;
		gap: 2rem;
	}

	.toolbar__adress-switch-block,
	.toolbar__incorrect-filter-block {
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.toolbar__label {
		color: #7b8fa3;
	}
</style>