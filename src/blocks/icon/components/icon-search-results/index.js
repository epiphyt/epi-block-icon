import { __ } from '@wordpress/i18n';

import icons from '../../../../icons';
import IconSearchResult from '../icon-search-result';

import './editor.scss';

const MAX_RESULTS = 30;

export default function IconSearchResults( { fetchedIcons, searchTerm, setAttributes, setFetchedIcons } ) {
	let searchedIcons = structuredClone( icons[ epiBlockIcon.iconSet ] );

	if ( searchTerm ) {
		searchedIcons = icons[ epiBlockIcon.iconSet ].filter( ( name ) => name.includes( searchTerm.toLowerCase() ) );
	}

	if ( searchedIcons.length > MAX_RESULTS ) {
		searchedIcons.length = MAX_RESULTS;
	}

	return (
		<div className="block-editor-inserter__block-list epi-block-icon-list">
			<div className="block-editor-inserter__panel-content">
				<div
					role="listbox"
					className="block-editor-block-types-list"
					aria-label={ __( 'Icons', 'epi-block-icon' ) }
				>
					<div role="presentation">
						{ searchedIcons.map( ( iconName, index ) => {
							return (
								<IconSearchResult
									fetchedIcons={ fetchedIcons }
									iconName={ iconName }
									key={ index }
									setAttributes={ setAttributes }
									setFetchedIcons={ setFetchedIcons }
								/>
							);
						} ) }
					</div>
				</div>
			</div>
		</div>
	);
}
