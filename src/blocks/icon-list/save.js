import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function IconListSave( { attributes, className } ) {
	const { layout } = attributes;
	const classNames = [];

	if ( className ) {
		classNames.push( className );
	}

	if ( layout?.flexWrap ) {
		classNames.push( 'is-flex-wrap-' + layout.flexWrap );
	}

	if ( layout?.justifyContent ) {
		classNames.push( 'is-content-justification-' + layout.justifyContent );
	}

	if ( layout?.orientation ) {
		classNames.push( 'is-orientation-' + layout.orientation );
	}

	const blockProps = useBlockProps.save( {
		className: classNames.join( ' ' ),
	} );
	const innerBlocksProps = useInnerBlocksProps.save( blockProps );

	return <div { ...innerBlocksProps } />;
}
