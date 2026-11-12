<?php
/**
 * Tests for the contact form REST endpoint.
 *
 * @package ChubesContact\Tests
 */

declare( strict_types = 1 );

if ( ! class_exists( 'WP_UnitTestCase' ) ) {
	return;
}

/**
 * @group chubes-contact
 */
class ContactEndpointTest extends WP_UnitTestCase {

	/**
	 * Mail captured through pre_wp_mail.
	 *
	 * @var array<int, array<string, mixed>>
	 */
	private array $sent = array();

	public function set_up(): void {
		parent::set_up();

		$this->sent = array();
		add_filter( 'pre_wp_mail', array( $this, 'capture_mail' ), 10, 2 );

		do_action( 'rest_api_init', rest_get_server() );
	}

	public function tear_down(): void {
		remove_filter( 'pre_wp_mail', array( $this, 'capture_mail' ), 10 );
		parent::tear_down();
	}

	/**
	 * Records the message and short-circuits delivery as a success.
	 *
	 * @param null|bool            $short_circuit Pre-filter value.
	 * @param array<string, mixed> $atts          wp_mail() arguments.
	 */
	public function capture_mail( $short_circuit, array $atts ): bool {
		$this->sent[] = $atts;
		return true;
	}

	/**
	 * Builds a submission that passes every check.
	 *
	 * @param array<string, mixed> $overrides Params to replace.
	 */
	private function submit( array $overrides = array() ): WP_REST_Response {
		$request = new WP_REST_Request( 'POST', '/chubes/v1/contact' );
		$params  = array_merge(
			array(
				'contactName'       => 'Ada Lovelace',
				'contactEmail'      => 'ada@example.com',
				'contactWebsite'    => 'https://example.com',
				'contactMessage'    => 'Hello there.',
				'contact_honeypot'  => '',
				'contact_timestamp' => time() - 30,
				'nonce'             => wp_create_nonce( 'contact_nonce' ),
			),
			$overrides
		);
		$request->set_body_params( $params );

		return rest_get_server()->dispatch( $request );
	}

	public function test_block_name_is_the_one_published_pages_use(): void {
		// Pages store <!-- wp:chubes/contact-form -->. CI does not build the
		// block, so assert the source metadata rather than the registry.
		$json = file_get_contents( dirname( __DIR__ ) . '/src/block.json' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
		$this->assertIsString( $json );

		$this->assertSame( 'chubes/contact-form', json_decode( $json, true )['name'] );
	}

	public function test_block_registration_is_hooked_to_init(): void {
		$this->assertNotFalse( has_action( 'init', 'chubes_contact_init' ) );
	}

	public function test_route_is_registered(): void {
		$this->assertArrayHasKey( '/chubes/v1/contact', rest_get_server()->get_routes() );
	}

	public function test_valid_submission_mails_admin_and_sender(): void {
		$response = $this->submit();

		$this->assertSame( 200, $response->get_status() );
		$this->assertTrue( $response->get_data()['success'] );
		$this->assertCount( 2, $this->sent );

		$admin = $this->sent[0];
		$this->assertSame( get_option( 'admin_email' ), $admin['to'] );
		$this->assertStringContainsString( 'Name: Ada Lovelace', $admin['message'] );
		$this->assertStringContainsString( 'Website: https://example.com', $admin['message'] );
		$this->assertContains( 'Reply-To: ada@example.com', $admin['headers'] );

		$this->assertSame( 'ada@example.com', $this->sent[1]['to'] );
	}

	public function test_invalid_nonce_is_rejected(): void {
		$response = $this->submit( array( 'nonce' => 'nope' ) );

		$this->assertSame( 403, $response->get_status() );
		$this->assertSame( 'invalid_nonce', $response->get_data()['code'] );
		$this->assertCount( 0, $this->sent );
	}

	public function test_filled_honeypot_is_rejected(): void {
		$response = $this->submit( array( 'contact_honeypot' => 'http://spam.example' ) );

		$this->assertSame( 400, $response->get_status() );
		$this->assertSame( 'spam_detected', $response->get_data()['code'] );
		$this->assertCount( 0, $this->sent );
	}

	public function test_too_fast_submission_is_rejected(): void {
		$response = $this->submit( array( 'contact_timestamp' => time() ) );

		$this->assertSame( 429, $response->get_status() );
		$this->assertSame( 'too_fast', $response->get_data()['code'] );
		$this->assertCount( 0, $this->sent );
	}

	public function test_invalid_email_is_rejected_by_schema(): void {
		$response = $this->submit( array( 'contactEmail' => 'not-an-email' ) );

		$this->assertSame( 400, $response->get_status() );
		$this->assertSame( 'rest_invalid_param', $response->get_data()['code'] );
		$this->assertCount( 0, $this->sent );
	}
}
