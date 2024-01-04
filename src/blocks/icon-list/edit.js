import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function IconListEdit( { attributes, className } ) {
	const { layout = {} } = attributes;
	const blockProps = useBlockProps( {
		className,
	} );
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'epi/icon' ],
		defaultBlock: {
			name: 'epi/icon',
		},
		directInsert: true,
		template: [ [ 'epi/icon' ] ],
		layout,
		templateInsertUpdatesSelection: true,
	} );

	return <div { ...innerBlocksProps } />;
}
