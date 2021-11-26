<template>
  <div class='load-file'>

  	<div class='load-file__content main-wrapper'>

  		<p-toolbar class='p-mb-4'>
  			<template #left>
		    	<input
		    		type='file'
		    		accept='.csv'
		    		@input='onFileSelected'
		    		ref='file-input'
		    		style='display: none'
		    	>

			    <p-button
			    	:label='fileList.length ? "Добавить файл" : "Выбрать файл для загрузки"'
			    	icon='fa fa-plus'
			    	@click='openFilePicker'
			    	class='p-button-sm p-mr-2'
			    />

			     <p-button
			    	label='Загрузить на сервер'
			    	icon='fa fa-file-upload'
			    	:disabled='!fileList.length'
			    	@click='send'
			    	class='p-button-sm'
			    />

			  </template>

			  <template #right>
			  	<div v-if='fileIsLoading' class='loading-indicator'>
				  	<p-spinner/>
				  	<span class='load-file-text'>Файл загружается...</span>
			  	</div>
			  </template>

<!-- 			  <template #right v-if='fileList.length'>
			    <p-button
			    	label='Очистить все'
			    	icon='fa fa-times'
			    	:disabled='!fileList.length'
			    	@click='removeAll'
			    	class='p-button-sm p-button-warning p-mr-2'
			    />

			     <p-button
			    	label='Очистить выбранное'
			    	icon='fa fa-times'
			    	:disabled='!selectedFileList.length'
			    	@click='removeSelected'
			    	class='p-button-sm p-button-warning'
			    />
			  </template> -->
  		</p-toolbar>

<!-- 		  <div v-for='item in fileList'>
		  	{{ item.file.name }}
		  	<button @click='removeItem(item)'>remove</button>
		  </div> -->


		  <div>
		  	<p-table
		  		v-if='fileList.length'
		  		:value='fileList'
		  		:selection.sync='selectedFileList'
		  		row-hover
		  		auto-layout
		  		:row-class='() => "file-table__row"'
		  		class='file-table p-datatable-sm'
				>
					<template #empty>
						Файлы не добавлены
					</template>

					<p-column field='name' header='Название файла'/>
					<p-column>
						<template #body='slotScope'>
							<p-button @click='showFile(slotScope)' icon='fa fa-sign-in-alt' class='p-button-rounded p-mr-2'/>
							<p-button @click='removeItem(slotScope)' icon='fa fa-times' class='p-button-rounded p-button-warning'/>
						</template>
					</p-column>
					<p-column selectionMode='multiple'/>
				</p-table>
		  </div>

<!-- 		  <p-button
		  	v-if='fileList.length'
	    	label='Отправить'
	    	@click='send'
	    	class='p-button-sm'
	    /> -->

	  </div>

	  <!-- <LoadFilePreview/> -->

  </div>
</template>

<script>
	// import LoadFilePreview from './LoadFile__preview';

	const LOAD_CSV = process.env.VUE_APP_ROOT_API + 'load_csv.php';

	export default {
	  name: 'FileLoader',

	  data: () => ({
	  	fileList: [],

	  	selectedFileList: [],

	  	fileIsLoading: false,
	  }),

	  components: {
			// LoadFilePreview
		},

		computed: {
			currentFile() {
				return this.fileList.length ? this.fileList[0] : null;
			}
		},

	  methods: {
	  	rowClass() {
	  		return 'file-table-row';
	  	},

	  	send() {
	  		// const file = this.fileList[0].file;

	  		const user_id = this.$store.state.auth.user_id;

	  		if (!user_id) {
	  			throw new Error('LOAD_FILE_NO_USER_ID');
	  			return;
	  		}

	  		const form = new FormData();
				form.append('user_id', user_id);

				for (let item of this.fileList) {
					// console.log(item.file);
					form.append('files[]', item.file);
				}

				this.fileIsLoading = true;

				fetch(LOAD_CSV, {
					credentials: 'include',
					method: 'post',
					body: form,
				})
				.then(res => res.json())
				.then(res => {
					this.$router.push({ name: 'temp-tables' });
				})
				.catch(err => { throw err; })
				.finally(() => { this.fileIsLoading = false; });
	  	},

	  	showFile(item) {

	  		const file = item.data.file;

	  		// console.log(file);

	  		const reader = new FileReader();

	  		reader.readAsText(file);

	  		reader.onload = function() {
	  			console.log(reader.result);
	  		};

	  		reader.onerror = function() {
	  			console.log(reader.error);
	  		};
	  	},

	  	removeItem(item) {
	  		const index = this.fileList.findIndex(i => i === item);
	  		this.fileList.splice(index, 1);
	  	},
	  	removeAll() {
	  		this.fileList = [];
	  	},
	  	removeSelected() {
	  		for (let file of this.selectedFileList) {
	  			this.removeItem(file);
	  		}

	  		this.selectedFileList = [];
	  	},

	  	openFilePicker() {
	  		const input = this.$refs['file-input'];
	  		input.click();
	  	},

	  	onFileSelected() {
	  		const input = this.$refs['file-input'];
	  		const file = this.$refs['file-input'].files[0];

	  		/* Проверка на уникальность файла */
	  		const finded = this.fileList.find(item => {
	  			return item.file.name === file.name
	  		});

	  		if (finded) {
	  			return;
	  		}
	  		/*********************************/

	  		const temp = {
	  			name: file.name,
	  			file: file
	  		};

	  		this.fileList.push(temp);

	  		input.value = null;
	  	}
	  }
	};
</script>

<style scoped lang='scss' src='@/styles/main_wrapper.scss'></style>

<style scoped>
	.file-table {
		/*width: fit-content;*/
	}

	.file-table::v-deep .file-table__row > td {
		vertical-align: middle;
	}

	.p-progress-spinner {
		height: 24px;
		width: 24px;
	}

	.load-file-text {
		display: inline-block;
		color: #888;
		margin-right: 1rem;
		margin-left: 1rem;
	}

	.loading-indicator {
		display: flex;
		align-items: center;
	}
</style>