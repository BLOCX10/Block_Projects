<?php
private function connectToApiCheckExist(): void {
	$callback = function () {
		$email = $_POST['email'];
		$id = $_POST['url'];

		$link = 'empty';

		if(!empty($id)){
			$link = wp_get_attachment_url($id);
		}

		if ( !empty( $email) ) {
			if ( class_exists( \MailPoet\API\API::class ) ) {
				$mailpoet_api = \MailPoet\API\API::MP( 'v1' );
				try {
					$mailpoet_api->getSubscriber( $email );
					$emailStatus = 'exist';

				} catch ( Exception $e ) {
					$emailStatus = 'add';
				}
				echo json_encode(array('status' => $emailStatus, 'url' => $link));

			} else {
				echo json_encode(array('status' => 'error'));
			}
		} else {
			echo json_encode(array('status' => 'error'));
		}

		die();
	};

	add_action( 'wp_ajax_add_user_to_newsletter_media', $callback );
	add_action( 'wp_ajax_nopriv_add_user_to_newsletter_media', $callback );
}