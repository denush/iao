<template>
  <p-dialog
    :visible='isOpen'
    @update:visible='$emit("toggle-open", $event)'
    header='Выгрузка реестра в csv'
    modal
    dismissableMask
    @show='fetchRegions'
    :style='{ "minWidth": "30vw" }'
  >
    <div>
      <div v-for='region in regions' :key='region.id'>
        <label>
          <p-radio-button v-model='selectedRegion' :value='region' name='region' />
          <span>{{ region.name }}</span>
        </label>
      </div>
    </div>

    <div>
      <p-button label='Отмена' @click='$emit("toggle-open", false)' />
      <p-button label='Выгрузить' @click='formReestr' :disabled='!selectedRegion' />
    </div>
  </p-dialog>
</template>

<script>
import { ref } from 'vue';

const ROOT_API = process.env.VUE_APP_ROOT_API;
const FETCH_REGIONS = ROOT_API + 'get_regions_from_main_iao.php';
const FORM_REESTR_UPP = ROOT_API + 'form_reestr/upp.php';

export default {
  name: 'DataReestr__formReestrModal',

  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },

  setup() {
    const selectedRegion = ref(null);

    const regions = ref([]);

    const fetchRegions = () => {
      return fetch(FETCH_REGIONS, { credentials: 'include' }).then(res => res.json()).then(res => {
        regions.value = res;
        // console.log('*** FETCHED REGIONS ***');
        // console.log(res);
      });
    };

    const formReestr = () => {
      if (!selectedRegion.value) {
        console.log('NO_REGION_SELECTED');
        throw new Error('NO_REGION_SELECTED');
      }

      const form = new FormData();
      form.append('region_id', selectedRegion.value.id);

      // isReestrDownloading.value = true;

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
        // isReestrDownloading.value = false;
      });
    };

    return {
      selectedRegion,
      regions,
      fetchRegions,
      formReestr
    };
  },
};
</script>
