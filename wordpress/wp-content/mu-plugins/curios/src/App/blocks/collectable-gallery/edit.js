/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */

import {
	store as blockEditorStore,
	MediaPlaceholder,
	InspectorControls,
	useBlockProps,
	BlockControls,
	MediaReplaceFlow,
	useInnerBlocksProps
} from '@wordpress/block-editor';



 import { withNotices } from '@wordpress/components';

 import { useState, useMemo } from '@wordpress/element';

 import { useSelect, useDispatch } from '@wordpress/data';

 import { useEntityProp } from '@wordpress/core-data';

 import Select  from 'react-select';


 import Datetime from 'react-datetime';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

const ALLOWED_MEDIA_TYPES = ['image'];
const allowedBlocks = ['curios/collectable-image']

const Gallery = (props) => {

	const {
		blockProps,
		isSelected,
		mediaPlaceholder,
	} = props;

	const { children, ...innerBlocksProps } = useInnerBlocksProps( blockProps, {
		allowedBlocks,
		templateLock: false,
		orientation: 'horizontal',
		renderAppender: false,
		__experimentalLayout: { type: 'default', alignments: [] },
	} );

	return (
		<figure { ...innerBlocksProps }>
			{ children }				
			{ isSelected && ! children && (
			<div
				className="blocks-gallery-media-placeholder-wrapper"
			>
				{ mediaPlaceholder }
			</div>
			) }
		</figure>
	);
}

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
	const {
		attributes,
		setAttributes,
		isSelected,
		className,
		noticeUI,
		insertBlocksAfter,
		noticeOperations,
		onReplace,
		context,
		clientId,
	} = props;

	const {
		__unstableMarkNextChangeAsNotPersistent,
		replaceInnerBlocks,
		updateBlockAttributes,
		selectBlock,
		clearSelectedBlock,
	} = useDispatch( blockEditorStore );

	const innerBlockImages = useSelect(
		( select ) => {
			return select( blockEditorStore ).getBlock( clientId )?.innerBlocks;
		},
		[ clientId ]
	);

	const images = useMemo(
		() =>
			innerBlockImages?.map( ( block ) => ( {
				clientId: block.clientId,
				id: block.attributes.id,
				url: block.attributes.url,
				attributes: block.attributes,
				fromSavedContent: Boolean( block.originalContent ),
			} ) ),
		[ innerBlockImages ]
	);

	const imagesUploading = images.some(
		( img ) => ! img.id && img.url?.indexOf( 'blob:' ) === 0
	);

	const mediaPlaceholderProps = {
			addToGallery: false,
			disableMediaButtons: false,
			value: {},
	};

	const mediaPlaceholder = (
		<MediaPlaceholder
			handleUpload={ false }
			labels={ {
				title: 'Gallery' ,
				instructions: 'give me an image',
			} }
			// onSelect={ updateImages }
			accept="image/*"
			allowedTypes={ ALLOWED_MEDIA_TYPES }
			multiple
			{ ...mediaPlaceholderProps }
		/>
	);


	const blockProps = useBlockProps();

	return (
		<Gallery
			{ ...props }
			blockProps={blockProps}
			mediaPlaceholder={mediaPlaceholder}
		/>
	)
}