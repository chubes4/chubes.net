/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useInstanceId } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import './style.css';
import metadata from './block.json';

function Edit() {
	const blockProps = useBlockProps();
	const id = useInstanceId( Edit, 'chubes-contact-preview' );

	return (
		<div { ...blockProps }>
			<div className="contact-form-section">
				<div className="container">
					<div className="contact-form">
						<h2>{ __( 'Get in Touch', 'chubes-contact' ) }</h2>
						<label htmlFor={ `${ id }-name` }>{ __( 'Name', 'chubes-contact' ) }</label>
						<input id={ `${ id }-name` } type="text" disabled placeholder={ __( 'Name', 'chubes-contact' ) } />
						<label htmlFor={ `${ id }-email` }>{ __( 'Email', 'chubes-contact' ) }</label>
						<input id={ `${ id }-email` } type="email" disabled placeholder={ __( 'Email', 'chubes-contact' ) } />
						<label htmlFor={ `${ id }-website` }>{ __( 'Website (Optional)', 'chubes-contact' ) }</label>
						<input id={ `${ id }-website` } type="text" disabled placeholder="https://example.com" />
						<label htmlFor={ `${ id }-message` }>{ __( 'Message', 'chubes-contact' ) }</label>
						<textarea id={ `${ id }-message` } disabled />
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
