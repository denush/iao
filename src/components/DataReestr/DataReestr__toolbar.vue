<template>
	<div class='toolbar'>
		<div class='toolbar-left'>
			<p-button
				label='Выгрузить реестр'
				:loading='isReestrDownloading'
				@click='downloadReestr'
				class='p-button-sm'
			/>
		</div>

		<FormReestrModal
			:isOpen='formReestrModalIsOpen'
			@toggle-open='toggleFormReestrModal'
			@start-download='isReestrDownloading = true'
			@finish-download='isReestrDownloading = false'
		/>

	</div>
</template>

<script>
	import { ref } from 'vue';
	import FormReestrModal from './DataReestr__formReestrModal.vue';

	const ROOT_API = process.env.VUE_APP_ROOT_API;
	const FORM_REESTR_UPP = ROOT_API + 'form_reestr/upp.php';

	export default {
		name: 'DataReestrToolbar',

		components: {
			FormReestrModal
		},

		setup() {
			const isReestrDownloading = ref(false);
			const formReestrModalIsOpen = ref(false);

			const toggleFormReestrModal = (event) => {
				formReestrModalIsOpen.value = event;
			};

			const downloadReestr = () => {
				toggleFormReestrModal(true);
				return;


				const form = new FormData();

				isReestrDownloading.value = true;

				fetch(FORM_REESTR_UPP, {
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
	  			isReestrDownloading.value = false;
	  		});
			};

			return {
				isReestrDownloading,
				formReestrModalIsOpen,
				downloadReestr,
				toggleFormReestrModal
			};
		}
	};
</script>

<style scoped>
	.toolbar {
		padding: 1rem;
	}
</style>