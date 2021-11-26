import CustomPSelect from '@/lib/customComponents/CustomPSelect';
import CustomPInput from '@/lib/customComponents/CustomPInput';
import CustomPCheckbox from '@/lib/customComponents/CustomPCheckbox';
import CustomPModal from '@/lib/customComponents/CustomPModal';

function registerCustomComponents(app) {
	app.component('CustomPSelect', CustomPSelect);
	app.component('CustomPInput', CustomPInput);
	app.component('CustomPCheckbox', CustomPCheckbox);
	app.component('CustomPModal', CustomPModal);
}

export default registerCustomComponents;