import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { dom } from '@fortawesome/fontawesome-svg-core';

dom.watch(); // This will kick of the initial replacement of i to svg tags and configure a MutationObserver
						 // Преобразование <i class='fa fa-...'> к font-awesome svg иконкам

/* ИСПОЛЬЗУЕМЫЕ ИКОНКИ */
import {
	faAngleDoubleDown,
	faCamera,
	faCaretDown,
	faCaretUp,
	faCaretLeft,
	faCheck,
	faClipboardCheck,
	faDatabase,
	faFileDownload,
	faFileUpload,
	faFilter,
	faHome,
	faEdit,
	faEye,
	faLayerGroup,
	faLock,
	faMap,
	faPlus,
	faSignInAlt,
	faSignOutAlt,
	faSyncAlt,
	faTimes,
	faTrash,
	faUndo,
	faUpload,
	faVectorSquare
} from '@fortawesome/free-solid-svg-icons';

library.add(
	faAngleDoubleDown,
	faCamera,
	faCaretDown,
	faCaretUp,
	faCaretLeft,
	faCheck,
	faClipboardCheck,
	faDatabase,
	faFileDownload,
	faFileUpload,
	faFilter,
	faHome,
	faEdit,
	faEye,
	faLayerGroup,
	faLock,
	faMap,
	faPlus,
	faSignInAlt,
	faSyncAlt,
	faSignOutAlt,
	faTimes,
	faTrash,
	faUndo,
	faUpload,
	faVectorSquare
);

function registerFontAwesomeIcon(app) {
	app.component('fa-icon', FontAwesomeIcon);
}

export default registerFontAwesomeIcon;