import { registerBlockType } from '@wordpress/blocks';

import meta from './block.json';
import IconListEdit from './edit';
import IconListSave from './save';

registerBlockType( meta, {
	edit: IconListEdit,
	save: IconListSave,
} );
