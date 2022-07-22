<template>
  <p-dialog
    :visible='isOpen'
    @update:visible='$emit("toggle-open", $event)'
    header='Выгрузка реестра'
    modal
    dismissableMask
    :draggable='false'
    @show='fetchRegions'
    :style='{ "minWidth": "30vw" }'
  >
    <div class='region-picker'>
      <div v-if='regions.length' class='region-picker__option region-picker__all-option'>
        <label>
          <p-radio-button v-model='selectedRegion' :value='preselectedRegion' name='region' />
          <span>Все регионы</span>
        </label>
      </div>

      <div v-for='region in regions' :key='region.id' class='region-picker__option'>
        <label>
          <p-radio-button v-model='selectedRegion' :value='region' name='region' />
          <span>{{ region.name }}</span>
        </label>
      </div>
    </div>

    <div class='button-block'>
      <p-button label='Отмена' @click='$emit("toggle-open", false)' icon='fa fa-times' class='p-button-sm p-button-outlined p-button-secondary'/>
      <p-button label='Выгрузить' @click='formReestr' :disabled='!selectedRegion' icon='fa fa-file-download' class='p-button-sm' />
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

  emits: [ 'toggle-open', 'start-download', 'finish-download' ],

  setup(props, { emit }) {
    const selectedRegion = ref(null);
    const preselectedRegion = ref({
      id: 0,
      name: 'Все регионы'
    });

    selectedRegion.value = preselectedRegion.value;

    const regions = ref([]);

    const fetchRegions = () => {
      return fetch(FETCH_REGIONS, { credentials: 'include' })
      .then(res => res.json())
      .then(res => {
        // for (let i = 1; i <= 50; ++i) {
        //   const temp = {
        //     id: i,
        //     name: 'test ' + i
        //   };
        //   regions.value.push(temp);
        // }

        regions.value = res;
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
      emit('start-download');
      emit('toggle-open', false);

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
        emit('finish-download');
      });
    };

    return {
      selectedRegion,
      preselectedRegion,
      regions,
      fetchRegions,
      formReestr
    };
  },
};
</script>

<style scoped>
.region-picker {
  max-height: 50vh;
  overflow: auto;
}

.region-picker__option {
  margin-bottom: 1rem;
}

.region-picker__option > label {
  cursor: pointer;
  display: flex;
  gap: 1rem;
  align-items: center;
}

.region-picker__all-option {
  padding-bottom: 1rem;
  border-bottom: 1px solid #bbb;
}

.button-block {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  margin-top: 2rem;
}

</style>