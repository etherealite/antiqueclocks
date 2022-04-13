/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Import core wordpress components
 * 
 */
 import { TextControl } from '@wordpress/components';

 import { useState } from '@wordpress/element';

 import { useSelect, useDispatch } from '@wordpress/data';

 import { useEntityProp } from '@wordpress/core-data';

 import CreatableSelect  from 'react-select/creatable';


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {

	
	// const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

	


	// const metaFieldValue = meta['collectable_sale']['realized_price'] || '';


	// const updateMetaValue = (newValue) => {

	// 	setMeta({...meta, collectable_sale: {realized_price: newValue}});
	// };

	
	// return (
	// 	<div {...useBlockProps()}>
	// 		<TextControl
	// 			label='Price'
	// 			value={metaFieldValue}
	// 			onChange={updateMetaValue}
	// 		/>
	// 	</div>
	// );

	

	const post = useSelect(
		(select) => select('core/editor').getCurrentPost(),
	);

	const currentManuId = useSelect(
		select => select('core/editor')
			.getCurrentPostAttribute('manufacturer')
			?.[0]
	);

	const postType = useSelect(
		(select) => select('core/editor').getCurrentPostType(),
		[]
	);
	const postId = useSelect(
		(select) => select('core/editor').getCurrentPostId(),
		[]
	)

	const { editPost } = useDispatch('core/editor');


	const {isLoading, postTerm} = useSelect(
		select => {
			const termId = currentManuId;
			const { getEntityRecord, isResolving } = select('core');
			const termArgs = ['taxonomy', 'manufacturer', termId];
			const term = getEntityRecord(...termArgs);
			const _isLoading = isResolving('getEntityRecord', termArgs);
			return {
				isLoading: _isLoading,
				postTerm: term,
			}
		},
		[currentManuId]
	);


	// const oldVal = oldTerm?.map(([term]) => ({label: term?.name, value: term?.id}));



	const [search, setSearch] = useState(0);

	const searchResults = useSelect(
        select =>
            select('core').getEntityRecords('taxonomy', 'manufacturer', {
				per_page: 20,
				orderby: 'count',
				order: 'desc',
				_fields: 'id,name,count',
				search: search,
			})?.map(t => {
				return {label: t.name, value: t.id};
			}),
        []
    );

	const currentValue = postTerm ? {label: postTerm.name, value: postTerm.id} : null;

	const [newVal, setNewVal] = useState(null);

	const handleChange = (value) => {
		console.log(value);
		console.log('setting manu id to ', value.value);
		editPost({manufacturer: [value.value]});
		setNewVal(value);
	};
	return (
		<div {...useBlockProps()}>
			<CreatableSelect
				value={newVal ?? currentValue}
				isLoading={isLoading}
				placeholder={'Select a Manufacturer...'}
				options={searchResults}
				onChange={handleChange}
				onInputChange={newValue => setSearch(newValue)}
			/>
		</div>
	);

}