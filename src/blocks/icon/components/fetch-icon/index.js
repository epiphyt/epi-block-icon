export default async function fetchIcon( iconName ) {
	const response = await fetch(
		epiBlockIcon.assetUrl + epiBlockIcon.iconSet + '/' + epiBlockIcon.defaultVariant + '/' + iconName + '.svg'
	);

	return response.text();
}

export const updateIcons = ( name, svg, setFetchedIcons, setIsLoading ) => {
	let newIcons = {};
	newIcons[ name ] = svg;

	setFetchedIcons( ( prevState ) => ( { ...prevState, ...newIcons } ) );
	setIsLoading( false );
};
