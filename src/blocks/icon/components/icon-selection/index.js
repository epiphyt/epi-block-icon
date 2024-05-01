import { BlockControls } from '@wordpress/block-editor';
import { Dropdown, SearchControl, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { edit } from '@wordpress/icons';

import IconSearchResults from '../icon-search-results';

export default function IconSelection( { fetchedIcons, setAttributes, setFetchedIcons } ) {
	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const [ searchTerm, setSearchTerm ] = useState( '' );

	return (
		<Dropdown
			className={ 'epi-block-icon__selection-popover--inserter is-' + ( isModalOpen ? 'open' : 'closed' ) }
			onToggle={ () => setIsModalOpen( ! isModalOpen ) }
			popoverProps={ {
				className: 'epi-block-icon__selection-popover',
				placement: 'bottom',
			} }
			renderContent={ () => (
				<div className={ 'epi-block-icon__selection is-' + ( isModalOpen ? 'open' : 'closed' ) }>
					<div className="epi-block-icon__popover-content">
						<SearchControl
							onChange={ ( value ) => setSearchTerm( value ) }
							placeholder={ __( 'Search', 'epi-block-icon' ) }
							value={ searchTerm }
						/>

						<div className="epi-block-icon__selection-results">
							<IconSearchResults
								fetchedIcons={ fetchedIcons }
								searchTerm={ searchTerm }
								setAttributes={ setAttributes }
								setFetchedIcons={ setFetchedIcons }
							/>
						</div>
					</div>
				</div>
			) }
			renderToggle={ ( { onToggle } ) => (
				<BlockControls group="block">
					<ToolbarGroup>
						<ToolbarButton
							icon={ edit }
							label={ __( 'Change icon', 'epi-block-icon' ) }
							onClick={ onToggle }
						/>
					</ToolbarGroup>
				</BlockControls>
			) }
		/>
	);
}
