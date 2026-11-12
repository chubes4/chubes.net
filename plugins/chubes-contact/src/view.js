/* global chubesContactParams */
document.addEventListener( 'DOMContentLoaded', function () {
	const form = document.getElementById( 'contactForm' );
	if ( ! form ) {
		return;
	}

	if ( typeof chubesContactParams === 'undefined' ) {
		// eslint-disable-next-line no-console
		console.error( 'Chubes Contact: Params not found' );
		return;
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();

		const formEl = this;
		const field = ( name ) => formEl.querySelector( `[name="${ name }"]` ).value;
		const payload = {
			contactName: field( 'contactName' ),
			contactEmail: field( 'contactEmail' ),
			contactWebsite: field( 'contactWebsite' ),
			contactMessage: field( 'contactMessage' ),
			contact_honeypot: field( 'contact_honeypot' ),
			contact_timestamp: field( 'contact_timestamp' ),
			nonce: chubesContactParams.nonce,
		};

		const errorEl = formEl.querySelector( '.contact-error' );
		const successEl = formEl.querySelector( '.contact-success' );
		const genericError = 'An error occurred. Please try again later.';

		errorEl.style.display = 'none';
		errorEl.textContent = '';
		successEl.style.display = 'none';
		successEl.textContent = '';

		fetch( chubesContactParams.rest_url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify( payload ),
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				if ( data.success ) {
					successEl.textContent = data.message;
					successEl.style.display = 'block';
					formEl.reset();
					const timestamp = formEl.querySelector( '[name="contact_timestamp"]' );
					if ( timestamp ) {
						timestamp.value = Math.floor( Date.now() / 1000 );
					}
				} else {
					errorEl.textContent = data.message || genericError;
					errorEl.style.display = 'block';
				}
			} )
			.catch( () => {
				errorEl.textContent = genericError;
				errorEl.style.display = 'block';
			} );
	} );
} );
