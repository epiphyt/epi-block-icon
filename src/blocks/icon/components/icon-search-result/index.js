import { useEffect, useState } from '@wordpress/element';
import SVG from 'react-inlinesvg';

import fetchIcon, { updateIcons } from '../fetch-icon';
import Loading from '../loading';

export default function IconSearchResult( { fetchedIcons, iconName, setAttributes, setFetchedIcons } ) {
	const [ isLoading, setIsLoading ] = useState( false );

	useEffect( () => {
		if ( fetchedIcons[ iconName ] ) {
			return;
		}

		try {
			setIsLoading( true );
			fetchIcon( iconName ).then( ( svg ) => updateIcons( iconName, svg, setFetchedIcons, setIsLoading ) );
		} catch ( error ) {
			setIsLoading( false );
			console.error( error );
		}
	}, [ iconName ] );

	return (
		<div className="block-editor-block-types-list__list-item" draggable="false">
			<button
				className="components-button block-editor-block-types-list__item editor-block-list-item-paragraph"
				onClick={ () => setAttributes( { icon: iconName } ) }
				role="option"
				tabIndex="0"
				type="button"
			>
				<span className="block-editor-block-types-list__item-icon">
					<span className="block-editor-block-icon">
						{ isLoading || ! fetchedIcons[ iconName ] ? (
							<Loading style={ { height: '24px', width: '24px' } } />
						) : (
							<SVG src={ fetchedIcons[ iconName ] } />
						) }
					</span>
				</span>
				<span className="block-editor-block-types-list__item-title">{ iconName }</span>
			</button>
		</div>
	);
}
