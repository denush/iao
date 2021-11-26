import { createApp } from 'vue';
import App from './App';
import router from './router';
import store from './store';

import registerPrimeVue from '@/lib/primevue';
import registerCustomComponents from '@/lib/customComponents';
import registerFontAwesomeIcon from '@/lib/fontawesome';

const app = createApp(App);

app.use(store);
app.use(router);
app.mount('#app');

registerPrimeVue(app);
registerCustomComponents(app);
registerFontAwesomeIcon(app);

