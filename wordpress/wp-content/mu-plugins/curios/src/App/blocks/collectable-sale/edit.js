
import moment from 'moment';

import { useBlockProps } from '@wordpress/block-editor';

import { useSelect, useDispatch } from '@wordpress/data';


import CurrencyInput from 'react-currency-input-field';

import Select from 'react-select';

import Datetime from 'react-datetime';


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
	
	const defaults = {
		kind: 'auction',
		realizedPrice: null,
		date: new Date().toISOString(),
	};

	const kindOptions = {
		auction: {label: 'Auction', value: 'auction'},
		other: {label: 'Other', value: 'other'},
	};

	const sale = useSelect(
		select => ({ ...defaults, ...select('core/editor' ).getEditedPostAttribute('meta')
			?.['collectable_sale'] ?? {} })
	);

	const meta = useSelect(
		select => select('core/editor').getEditedPostAttribute('meta')
	);

	const { editPost } = useDispatch('core/editor');

	const onPriceChange = (value) => {
		console.log('price change', value);
		const newSale = { ...sale, realizedPrice: value };
		const newMeta = { ...meta, collectable_sale: {...newSale} };
		editPost({meta: newMeta});
	};
	const onTypeChange = (option) => {
		console.log('type change val:', option);
		const newSale = { ...sale, kind: option.value };
		const newMeta = { ...meta, collectable_sale: { ...newSale } };
		editPost({meta: newMeta});
	};
	const onEstimateChange = (value) => {
		console.log('estimate chnage val:', value);
		const newSale = { ...sale, estimate: value };
		const newMeta = { ...meta, collectable_sale: { ...newSale } };
		editPost({meta: newMeta});
	};
	const onDateChange = (value) => {
		console.log('date change value:', value);
		const date = value.toISOString();
		const newSale = { ...sale, date: date };
		const newMeta = { ...meta, collectable_sale: { ...newSale } };
		editPost({meta: newMeta});
	};

	return (
		<div {...useBlockProps()}>
			<h4>Price</h4>
			<CurrencyInput
				prefix='$'
				value={sale.realizedPrice}
				placeholder='$0.00'
				decimalsLimit={2}
				decimalScale={2}
				onValueChange={onPriceChange}
			/>
			<h4>Price Type</h4>
			<Select
				defaultValue={kindOptions.auction}
				value={kindOptions[sale.kind]}
				placeholder='Select a price type...'
				options={Object.values(kindOptions)}
				onChange={onTypeChange}
			/>
			<div className={sale.kind === 'auction' ? '' : 'disabled'}>
				<h4>
					Pre-Auction Estimate
				</h4>
				<CurrencyInput
					prefix='$'
					value={sale.estimate}
					placeholder='$0.00'
					decimalsLimit={2}
					decimalScale={2}
					onValueChange={onEstimateChange}
					disabled={sale.kind !== 'auction'}
				/>
			</div>
			<h4>Date of Sale</h4>
			<Datetime
				value={moment(sale.date)}
				utc={true}
				timeFormat='hh:mm A'
				onChange={onDateChange}
			/>
		</div>
	);
}