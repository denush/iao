import PrimeVue from 'primevue/config';

import 'primevue/resources/themes/saga-blue/theme.css';		//theme
import 'primevue/resources/primevue.min.css';							//core css
import 'primeicons/primeicons.css';												//icons
import 'primeflex/primeflex.css';

/* ИСПОЛЬЗУЕМЫЕ КОМПОНЕНТЫ */
import PButton from 'primevue/button';
import PCheckbox from 'primevue/checkbox';
import PColumn from 'primevue/column';
import PDialog from 'primevue/dialog';
import PDivider from 'primevue/divider';
import PDropdown from 'primevue/dropdown';
import PInputText from 'primevue/inputtext';
import PInputNumber from 'primevue/inputnumber';
import PListbox from 'primevue/listbox';
import PSwitch from 'primevue/inputswitch';
import PMenu from 'primevue/menu';
import PMessage from 'primevue/message';
import PMultiselect from 'primevue/multiselect';
import POverlayPanel from 'primevue/overlaypanel';
import PPaginator from 'primevue/paginator';
import PPanel from 'primevue/panel';
// import PSidebar from 'primevue/sidebar';
import PSpinner from 'primevue/progressspinner';
import PSplitButton from 'primevue/splitbutton';
import PTable from 'primevue/datatable';
import PTabmenu from 'primevue/tabmenu';
import PToolbar from 'primevue/toolbar';

function registerPrimeVue(app) {
	app.use(PrimeVue);

	app.component('PButton', PButton);
	app.component('PCheckbox', PCheckbox);
	app.component('PColumn', PColumn);
	app.component('PDialog', PDialog);
	app.component('PDivider', PDivider);
	app.component('PDropdown', PDropdown);
	app.component('PInputText', PInputText);
	app.component('PInputNumber', PInputNumber);
	app.component('PListbox', PListbox);
	app.component('PSwitch', PSwitch);
	app.component('PMenu', PMenu);
	app.component('PMessage', PMessage);
	app.component('PMultiselect', PMultiselect);
	app.component('POverlayPanel', POverlayPanel);
	app.component('PPaginator', PPaginator);
	// app.component('PSidebar', PSidebar);
	app.component('PPanel', PPanel);
	app.component('PSpinner', PSpinner);
	app.component('PSplitButton', PSplitButton);
	app.component('PTable', PTable);
	app.component('PTabmenu', PTabmenu);
	app.component('PToolbar', PToolbar);
}

export default registerPrimeVue;