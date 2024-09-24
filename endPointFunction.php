<?php

class endpointLoc {

	public function __construct() {
		$this->createLocationEndpoint();
	}

	public function createLocationEndpoint(): void {
		add_action( 'rest_api_init', function () {
			register_rest_route( '/loc/v1', '/response', [
				'methods'             => 'GET',
				'callback'            => self::endpointDataResponse(),
				'permission_callback' => '__return_true',
			] );
		} );
	}

	private static function endpointDataResponse(): object {
		$callback = function () {
			$id = $_GET['id'];
			$user = $_GET['user'];
			if((int)get_field('id',$id) === (int)$user){
				$address = get_field('street',$id);
				$city = get_field('city',$id);
				update_user_meta( $user, 'user', $id);
				update_user_meta( $user, 'address', $address);
				update_user_meta( $user, 'city', $city);


				$number = get_field( 'number', $id );
				$access = get_field( 'access', $id );
				$floor = get_field( 'floor', $id );
				$code = get_field( 'code', $id );
				$extra = get_field( 'extrat', $id );
				$company = get_field( 'company', $id );

				return array('1' => $number, '2' => $access, '3' => $floor, '4' => $code, '5' => $extra, '6' => $company);
			}
			return array('status' => 'false');
		};

		return $callback;
	}

}
