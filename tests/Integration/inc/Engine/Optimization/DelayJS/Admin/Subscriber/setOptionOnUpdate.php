<?php

namespace WP_Rocket\Tests\Integration\inc\Engine\Optimization\DelayJS\Admin\Subscriber;

use WP_Rocket\Tests\Integration\TestCase;

/**
 * @covers \WP_Rocket\Engine\Optimization\DelayJS\Admin\Subscriber::set_option_on_update
 *
 * @group  DelayJS
 * @group  AdminOnly
 */
class Test_SetOptionOnUpdate extends TestCase{
	public function set_up() {
		parent::set_up();

		$this->setUpSettings();
		$this->unregisterAllCallbacksExcept( 'wp_rocket_upgrade', 'set_option_on_update', 13 );
	}

	public function tear_down() {
		parent::tear_down();

		$this->tearDownSettings();
		$this->restoreWpFilter( 'wp_rocket_upgrade' );
	}

	/**
	 * @dataProvider configTestData
	 */
	public function testShouldDoExpected( $options, $old_version, $expected ) {
		$this->mergeExistingSettingsAndUpdate( $options );

		do_action( 'wp_rocket_upgrade', '', $old_version );

		$updated = get_option( 'wp_rocket_settings' );

		$this->assertSame(
			(bool) $expected['delay_js'],
			isset( $updated['delay_js'] ) && $updated['delay_js']
		);

		if ( isset( $updated['delay_js'] ) && $updated['delay_js'] ) {
			$this->assertSame(
				$expected['delay_js_exclusions'],
				$updated['delay_js_exclusions']
			);
		}
	}
}
