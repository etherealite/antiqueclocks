/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';


import block from './block.json';


const TEMPLATE = [
	['curios/collectable-manufacturer', {}, []]
];

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(block.name, {
	/**
	 * @see ./edit.js
	 */
	edit() {
		const blockProps = useBlockProps();
		return (
			<div {...blockProps}>
				<InnerBlocks
					template={TEMPLATE}
				/>
			</div>
		);
	},

	save() {
		return null;
	}
});
