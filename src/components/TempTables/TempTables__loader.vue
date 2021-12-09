<template>
	<div class='loader'>

		<div>
			<input
				type='file'
				accept='.csv'
				@input='onFileSelected'
				ref='fileInput'
				style='display: none'
			>

			<p-button
				label='Загрузить общую таблицу'
				icon='fa fa-upload'
				@click='openFilePicker'
				class='p-button-sm p-mr-2'
			/>
		</div>

		<div>
			<input
				type='file'
				accept='.csv'
				@input='onFileSelectedForestries'
				ref='fileInputForestries'
				style='display: none'
			>

			<p-button
				label='Загрузить таблицу лесничества'
				icon='fa fa-upload'
				@click='openFilePickerForestries'
				class='p-button-sm p-button-success'
			/>
		</div>

	</div>
</template>

<script>
	import { ref } from 'vue';
	import { useStore } from 'vuex';

	const LOAD_CSV = process.env.VUE_APP_ROOT_API + 'load_csv.php';
	const LOAD_CSV_FORESTRY = process.env.VUE_APP_ROOT_API + 'load_csv_forestries.php';

	export default {
		name: 'TempTablesLoader',

		setup() {
			const store = useStore();

			/* FORESTRIES */
			const fileInputForestries = ref(null);
			let fileIsLoadingForestries = ref(false);

			const openFilePickerForestries = function() {
				const input = fileInputForestries.value;
	  		input.click();
			};

			const onFileSelectedForestries = function() {
				const file = fileInputForestries.value.files[0];
				const user_id = store.state.auth.user_id;

				if (!file) {
					throw new Error('NO_LOADED_FILE');
				}

	  		if (!user_id) {
	  			throw new Error('LOAD_FILE_NO_USER_ID');
	  		}

				const form = new FormData();
				form.append('user_id', user_id);
				form.append('file', file);

				fileIsLoadingForestries.value = true;

				fetch(LOAD_CSV_FORESTRY, {
					credentials: 'include',
					method: 'post',
					body: form
				})
				.then(res => res.json())
				.then(res => {
					return store.dispatch('tempTables/getTempTableListForestries');
				})
				.catch(err => { throw err; })
				.finally(() => { fileIsLoadingForestries.value = false; });
			}

			/* COMMON */
			const fileInput = ref(null);
			let fileIsLoading = ref(false);

			const openFilePicker = function() {
				const input = fileInput.value;
	  		input.click();
			};

			const onFileSelected = function() {
				const file = fileInput.value.files[0];
				const user_id = store.state.auth.user_id;

				if (!file) {
					throw new Error('NO_LOADED_FILE');
				}

	  		if (!user_id) {
	  			throw new Error('LOAD_FILE_NO_USER_ID');
	  		}

				const form = new FormData();
				form.append('user_id', user_id);
				form.append('file', file);

				fileIsLoading.value = true;

				fetch(LOAD_CSV, {
					credentials: 'include',
					method: 'post',
					body: form
				})
				.then(res => res.json())
				.then(res => {
					return store.dispatch('tempTables/getTempTableList');
				})
				.catch(err => { throw err; })
				.finally(() => { fileIsLoading.value = false; });
			}

			return {
				fileInput,
				openFilePicker,
				onFileSelected,

				fileInputForestries,
				openFilePickerForestries,
				onFileSelectedForestries
			};
		}
	};
</script>

<style scoped>
	.loader {
		display: flex;
		justify-content: flex-end;
		flex-wrap: wrap;
		margin-bottom: 2rem;
	}
</style>