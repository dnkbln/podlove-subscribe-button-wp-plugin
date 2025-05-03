<?php

namespace PodloveSubscribeButton\Settings;

class Buttons {

	public static function page() {

		?>
		<div data-client="podlove-subscribe-button">
      		<subscribe-button-overview></subscribe-button-overview>
			<subscribe-button-list></subscribe-button-list>
		</div>
		<?php
	}

	public static function process_form() {
	}

}
