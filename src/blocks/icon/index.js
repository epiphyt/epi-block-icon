import { registerBlockType } from '@wordpress/blocks';

import meta from './block.json';
import IconEdit from './edit';

import './editor.scss';
import './style.scss';

registerBlockType( meta, {
	edit: IconEdit,
	save: () => null,
} );
