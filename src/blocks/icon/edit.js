import {
	__experimentalLinkControl as LinkControl,
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	__experimentalUnitControl as UnitControl,
	Icon,
	PanelBody,
	Popover,
	ToolbarButton,
	TextControl,
} from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { fullscreen, link, linkOff } from '@wordpress/icons';
import SVG from 'react-inlinesvg';

import fetchIcon, { updateIcons } from './components/fetch-icon';
import { NEW_TAB_TARGET, NOFOLLOW_REL } from './constants';
import { getUpdatedLinkAttributes } from './get-updated-link-attributes';
import IconSelection from './components/icon-selection';
import Loading from './components/loading';

const LINK_SETTINGS = [
	...LinkControl.DEFAULT_LINK_SETTINGS,
	{
		id: 'nofollow',
		title: __( 'Mark as nofollow', 'epi-block-icon' ),
	},
];

export default function IconEdit( props ) {
	const [ fetchedIcons, setFetchedIcons ] = useState( {} );
	const [ isEditingURL, setIsEditingURL ] = useState( false );
	const [ isLoading, setIsLoading ] = useState( false );
	const {
		attributes: { icon, linkTarget, rel, size, title, url },
		className,
		isSelected,
		setAttributes,
	} = props;
	const blockProps = useBlockProps( {
		className,
	} );
	const isURLSet = !! url;
	const nofollow = !! rel?.includes( NOFOLLOW_REL );
	const opensInNewTab = linkTarget === NEW_TAB_TARGET;
	const style = {
		height: size,
		width: size,
	};

	function unlink() {
		setAttributes( {
			url: undefined,
			linkTarget: undefined,
			rel: undefined,
		} );
		setIsEditingURL( false );
	}

	useEffect( () => {
		if ( ! icon || fetchedIcons[ icon ] ) {
			return;
		}

		try {
			setIsLoading( true );
			fetchIcon( icon ).then( ( svg ) => updateIcons( icon, svg, setFetchedIcons, setIsLoading ) );
		} catch ( error ) {
			setIsLoading( false );
			console.error( error );
		}
	}, [ icon ] );

	// Memoize link value to avoid overriding the LinkControl's internal state.
	// This is a temporary fix. See https://github.com/WordPress/gutenberg/issues/51256.
	const linkValue = useMemo( () => ( { url, opensInNewTab, nofollow } ), [ url, opensInNewTab, nofollow ] );

	return (
		<div { ...blockProps }>
			{ isSelected ? (
				<IconSelection
					fetchedIcons={ fetchedIcons }
					setAttributes={ setAttributes }
					setFetchedIcons={ setFetchedIcons }
				/>
			) : null }

			<BlockControls group="block">
				<ToolbarButton
					name="link"
					icon={ isURLSet ? linkOff : link }
					title={ isURLSet ? __( 'Unlink', 'epi-block-icon' ) : __( 'Link', 'epi-block-icon' ) }
					onClick={ () => {
						if ( isURLSet ) {
							unlink();
						} else {
							setIsEditingURL( true );
						}
					} }
					isActive={ isURLSet || isEditingURL }
				/>
			</BlockControls>

			<InspectorControls>
				<PanelBody>
					<TextControl
						help={ __( 'A title for accessibility purposes, e.g. if the icon acts as an action, link or similar.', 'epi-block-icon' ) }
						label={ __( 'Title', 'epi-block-icon' ) }
						onChange={ ( title ) => setAttributes( { title } ) }
						value={ title }
					/>
					<UnitControl
						label={ __( 'Size', 'epi-block-icon' ) }
						labelPosition="side"
						onChange={ ( size ) => setAttributes( { size } ) }
						value={ size }
					/>
				</PanelBody>
			</InspectorControls>

			{ isSelected && ( isEditingURL || isURLSet ) ? (
				<Popover
					placement="bottom"
					onClose={ () => setIsEditingURL( false ) }
					focusOnMount={ isEditingURL ? 'firstElement' : false }
					shift
				>
					<LinkControl
						value={ linkValue }
						onChange={ ( { url: newURL, opensInNewTab: newOpensInNewTab, nofollow: newNofollow } ) =>
							setAttributes(
								getUpdatedLinkAttributes( {
									rel,
									url: newURL,
									opensInNewTab: newOpensInNewTab,
									nofollow: newNofollow,
								} )
							)
						}
						onRemove={ unlink }
						forceIsEditingLink={ isEditingURL }
						settings={ LINK_SETTINGS }
					/>
				</Popover>
			) : null }

			{ icon ? (
				isLoading ? (
					<Loading style={ style } />
				) : (
					<SVG src={ fetchedIcons[ icon ] } style={ style } />
				)
			) : (
				<Icon icon={ fullscreen } style={ style } />
			) }
		</div>
	);
}
