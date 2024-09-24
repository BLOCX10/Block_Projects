<?php

class MasalaAccountSettings {
	public function __construct() {
		$this->sendResetPasswordLink();
	}

	private function sendResetPasswordLink(): void {
		$callback = function () {

			$email = esc_html($_POST['email']);
			if(is_email($email)){
				if(email_exists($email)){
					$userData = get_user_by( 'email', $email );
					$userName = $userData->user_login;
					$userId = $userData->ID;
					$randomKey = self::generateRandomKey();
					update_user_meta( $userId, 'user_pass_key', $randomKey);
					update_user_meta( $userId, 'user_pas_time_to_reset', strtotime("now"));
					$userLink =  get_the_permalink(get_field('checkLink','option'));
					if($userLink){
						$resetPasswordLink = $userLink.'?user='.$userName.'&key='.$randomKey;
						$title = 'Password Reset';

						$message = '
						<div class="email-box">
						    <div class="email-box-top">
						        <img src="" alt="">
						    </div>
						    <div class="email-box-main">
						        <div class="email-box-main-title">
						            <span>Text</span>
						            <img src="" alt="">
						        </div>
						        <div class="email-box-main-desc">
						       		Description
						        </div>
						        <a href="'.$resetPasswordLink.'" class="email-box-main-btn">
						            Button
						        </a>
						    </div>
						</div>';
						$headers = array('Content-Type: text/html; charset=UTF-8');
						wp_mail( $email, $title, $message,$headers);
					}
					echo 'true';
				}else{
					echo 'false';
				}
			}
			else{
				echo 'false';
			}
			die();
		};

		add_action( 'wp_ajax_send_reset_link', $callback );
		add_action( 'wp_ajax_nopriv_send_reset_link', $callback );
	}

	private static function generateRandomKey( $length = 32 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$randomString .= $characters[ random_int( 0, $charactersLength - 1 ) ];
		}
		return $randomString;

	}
}
