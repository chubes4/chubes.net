import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './style.css';
import metadata from './block.json';

function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<div className="contact-form-section">
				<div className="container">
					<div className="contact-form">
						<h2>{ __( 'Get in Touch', 'chubes-contact' ) }</h2>
						<label>{ __( 'Name', 'chubes-contact' ) }</label>
						<input type="text" disabled placeholder={ __( 'Name', 'chubes-contact' ) } />
						<label>{ __( 'Email', 'chubes-contact' ) }</label>
						<input type="email" disabled placeholder={ __( 'Email', 'chubes-contact' ) } />
						<label>{ __( 'Website (Optional)', 'chubes-contact' ) }</label>
						<input type="text" disabled placeholder="https://example.com" />
						<label>{ __( 'Message', 'chubes-contact' ) }</label>
						<textarea disabled />
						<button type="button" className="btn" disabled>
							{ __( 'Send Message', 'chubes-contact' ) }
						</button>
					</div>
				</div>
			</div>
		</div>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
} );
