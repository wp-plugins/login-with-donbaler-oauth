<?php

	/*
		Plugin Name: Login with Donbaler OAuth
		Plugin URI: https://wordpress.org/plugins/login-with-donbaler-oauth/
		Description: افزونه ورود به وردپرس توسط حساب کاربری دنبالر ...
		Version: 1.1.1
		Author: Nima Saberi
		Author URI: http://ideyeno.ir
	*/
	 
	//ini_set( 'error_reporting', E_ALL | E_STRICT	);
	//ini_set( 'display_errors',			1	); 
	
	$acc_default_settings = array(
		'acc_login_address' => 'wp-login.php',
		'acc_oauth_key' => '',
		'acc_oauth_secret' => '',
		'acc_css_style' => '',
	);
	if ( ! is_array(get_option('donbaler_oauth') ) ) {
		add_option('donbaler_oauth', $acc_default_settings);
	}
	$options = get_option('donbaler_oauth');
	
	// پنل تنظیمات مدیریتی
	add_action('admin_menu', 'acc_plugin_setup_menu');
	function acc_plugin_setup_menu(){
		add_menu_page( 'ورود توسط حساب دنبالر', 'Donbaler OAuth', 'manage_options', 'donbaler-oauth', 'acc_plugin_setup_menu_content', 'dashicons-lock' );
	}
	function acc_plugin_setup_menu_content() {
		$options = get_option('donbaler_oauth');
		$html = '';
		if ( isset($_POST['form_save']) ) {
			$options["acc_login_address"] = (isset($_POST['acc_login_address']) && !empty($_POST['acc_login_address']) ? esc_attr($_POST['acc_login_address']) : 'wp-login.php');
			$options["acc_oauth_key"] = (isset($_POST['acc_oauth_key']) ? esc_attr($_POST['acc_oauth_key']) : '');
			$options["acc_oauth_secret"] = (isset($_POST['acc_oauth_secret']) ? esc_attr($_POST['acc_oauth_secret']) : '');
			$options["acc_css_style"] = (isset($_POST['acc_css_style']) && !empty($_POST['acc_css_style']) ? esc_attr($_POST['acc_css_style']) : '.acc_login_form a {text-decoration: none;  color: #7E7E7E;} .acc_login_form {text-align:center; padding:15px;margin: 5px 0px; color: #ffffff; text-decoration: none;background: #E4E4E4;} .acc_login_form:hover{ background: #D8D8D8;}');
			update_option('donbaler_oauth', $options);
			$html .= '<div class="updated notice is-dismissible" id="message">';
			$html .= '<p>تغییرات به درستی ذخیره شد.</p>';
			$html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">بستن این اعلان.</span></button>';
			$html .= '</div>';
		}
		$html .= '<div class="wrap"><h2>ورود توسط حساب دنبالر</h2></div>';
		$html .= '<div>توسط <a href="mailto:nima@ideyeno.ir" target="_blank" style="text-decoration: none;">نیما صابری</a> در <a href="http://ideyeno.ir/" target="_blank" style="text-decoration: none;">آزمایشگاه ایده نو</a></div>';
		$html .= '<hr>';
		$html .= '<form action="" method="post">';
		$html .= '<div class="card pressthis" style="float: right;">';
		$html .= '<b>تنظیمات »</b><br><br>';
		$html .= 'CONSUMER KEY :<br>';
		$html .= '<input value="'.$options["acc_oauth_key"].'" name="acc_oauth_key" type="text" class="regular-text ltr" style="text-align:center;padding: 10px;" /><br><br>';
		$html .= 'CONSUMER SECRET :<br>';
		$html .= '<input value="'.$options["acc_oauth_secret"].'" name="acc_oauth_secret" type="text" class="regular-text ltr" style="text-align:center;padding: 10px;" /><br><br>';
		$html .= 'LOGIN SLUG :<br>';
		$html .= '<input value="'.$options["acc_login_address"].'" name="acc_login_address" type="text" placeholder="wp-login.php" class="regular-text ltr" style="text-align:center;padding: 10px;" />';
		$html .= '<br><small>* به صورت پیشفرض wp-login.php می‌باشد</small><br><br>';
		$html .= 'SHORTCODE STYLE :<br>';
		$html .= '<textarea name="acc_css_style" class="regular-text ltr" style="padding: 10px;resize: none; height: 140px;">'.$options["acc_css_style"].'</textarea><br><br>';
		$html .= '<input value="ذخیره تغییرات" type="submit" name="form_save" class="button button-secondary" style="padding: 6px 15px; height: auto;"/>';
		$html .= '</div>';
		$html .= '</form>';
		$html .= '<div class="card pressthis" style="float: right; margin-right: 25px;">';
		$html .= '<b>راهنما »</b><br><br>';
		$html .= '<small>به منظور ایجاد کلید و کد احراز هویت مراحل زیر را دنبال کنید :</small><hr>';
		$html .= '- به donbaler.com رجوع کرده و لاگین نمایید : <a href="http://donbaler.com/signin/?reference=api" target="_blank" style="text-decoration: none;">[لینک]</a><br>';
		$html .= '- به <a href="http://donbaler.com/api" target="_blank" style="text-decoration: none;">صفحه API</a> رفته و بر روی <a href="http://donbaler.com/api/app:new" target="_blank" style="text-decoration: none;">«افزودن یک برنامه جدید»</a> کلیک کنید<br>';
		$html .= '- عنوان و توضیح برنامه را درج کنید<br>';
		$html .= '- آدرس برنامه را بنویسید : <em style="background: #ededed;">'.home_url().'</em><br>';
		$html .= '- در فیلد طراحان برنامه نام خود را درج کرده و نوع برنامه را Browser مشخص کنید<br>';
		$html .= '- آدرس بازگشت اطلاعات را بنویسید : <em dir="ltr" style="background: #ededed;">'.home_url().'/</em><br>';
		$html .= '- سطح دسترسی را بر روی «فقط خواندن» تنظیم کرده و فرم را ذخیره کنید<br>';
		$html .= '- بر روی نام برنامه ایجاد شده کلیک کرده تا وارد صفحه مربوطه شوید<br>';
		$html .= '- کلید مصرف کننده را کپی کرده و در فیلد CONSUMER KEY درج کنید<br>';
		$html .= '- راز مصرف‌کننده را کپی کرده و در فیلد CONSUMER SECRET درج کنید<br>';
		$html .= '- تنظیمات افزونه وردپرس را ذخیره کنید.<br><br>';
		$html .= '<hr><br>';
		$html .= '<b>کد کوتاه »</b><br><br>';
		$html .= '<center><em dir="ltr" style="background: #ededed;text-align:left;">[donbaler-oauth]</em><br><br>';
		$html .= '<em dir="ltr" style="background: #ededed;text-align:left;">&#60;?= do_shortcode( "[donbaler-oauth]" ); ?&#62;</em></center><br>';
		$html .= '</div>';
		echo $html;
	}
	
	date_default_timezone_set('Asia/Tehran');
	define( 'ACC_URL', 'http://donbaler.com/' );
	define( 'ACC_REQ_URL', ACC_URL.'oauth/request_token' );
	define( 'ACC_ACS_URL', ACC_URL.'oauth/access_token' );
	define( 'ACC_OAUTH_URL', ACC_URL.'oauth/authenticate' );
	define( 'ACC_LOGIN_SLUG', $options["acc_login_address"] );
	define( 'ACC_TIME', time() );
	define( 'ACC_NONCE', md5(rand().time().rand()) );
	define( 'ACC_OAUTH_KEY', $options["acc_oauth_key"] );
	define( 'ACC_OAUTH_SECRECT', $options["acc_oauth_secret"] );
	define( 'ACC_CSS_STYLE', $options["acc_css_style"] );
	
	// انتقال به پنل تنظیمات پس از نصب
	register_activation_hook(__FILE__, 'acc_oauth_activate');
	add_action('admin_init', 'acc_plugin_redirect');
	function acc_oauth_activate() {
		add_option('acc_plugin_do_activation_redirect', true);
	}
	function acc_plugin_redirect() {
		if (get_option('acc_plugin_do_activation_redirect', false)) {
			delete_option('acc_plugin_do_activation_redirect');
			wp_redirect(admin_url('admin.php?page=donbaler-oauth'));
		}
	}
	
	// احراز هویت
	$acc_mail = ( isset($_GET['email']) ? strip_tags(trim(strtolower($_GET['email']))) : '');
	$acc_type = ( isset($_GET['type']) ? strip_tags(trim($_GET['type'])) : '');
	$acc_oauth_token = ( isset($_GET['oauth_token']) ? strip_tags(trim($_GET['oauth_token'])) : '');
	$acc_oauth_verifier = ( isset($_GET['oauth_verifier']) ? strip_tags(trim($_GET['oauth_verifier'])) : '');
	
	function acc_login_form() {
		 $style = '<style>.acc_login_form{text-align:center; padding:15px;margin: 5px 0px; color: #ffffff; text-decoration: none;background: #1BA0D8;} .acc_login_form:hover{ background: #0875A3;}</style>';
		 $text = '<a href="'.home_url().'/'.ACC_LOGIN_SLUG.'?type=authorization" style="text-decoration: none;"><div class="acc_login_form">ورود توسط دنبالر</div></a>';
		 echo $style.$text;
	}
	
	function acc_login_shortcode() {
		$text = '<div class="acc_login_form"><a href="'.home_url().'/'.ACC_LOGIN_SLUG.'?type=authorization&redirect_to='.$_SERVER["REQUEST_URI"].'">';
		$text .= 'ورود توسط حساب کاربری دنبالر</a></div>';
		return '<style>'.ACC_CSS_STYLE.'</style>'.$text; 
	}
	
	if ( !empty($options["acc_oauth_key"]) && !empty($options["acc_oauth_secret"]) ) {
		add_filter('login_message', 'acc_login_form', 999);
		//add_action( 'login_form', 'acc_login_form', 999 );
		add_shortcode( 'donbaler-oauth', 'acc_login_shortcode' );
	}
	
	if ( $acc_type === 'authorization' ) {
		ob_start();
		session_start();
		$parameters = "oauth_consumer_key=".urlencode(utf8_encode(ACC_OAUTH_KEY));
		$parameters .= "&oauth_nonce=".urlencode(utf8_encode(ACC_NONCE));
		$parameters .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
		$parameters .= "&oauth_timestamp=".urlencode(utf8_encode(ACC_TIME));
		$parameters .= "&oauth_version=".urlencode(utf8_encode("1.0"));
		$resource_string = "GET&".urlencode(utf8_encode(ACC_REQ_URL))."&".urlencode(utf8_encode($parameters));
		$oauth_signature = base64_encode(hash_hmac("sha1", $resource_string, ACC_OAUTH_SECRECT."&", true));
		$request_body = ACC_REQ_URL."?oauth_nonce=".ACC_NONCE."&oauth_timestamp=".ACC_TIME;
		$request_body .="&oauth_consumer_key=".ACC_OAUTH_KEY;
		$request_body .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
		$request_body .="&oauth_signature=".$oauth_signature."&oauth_version=1.0";
		$my_request = curl_init();
		curl_setopt($my_request, CURLOPT_URL, $request_body);
		curl_setopt($my_request, CURLOPT_RETURNTRANSFER, TRUE);
		$request_result = curl_exec($my_request);
		curl_close($my_request);
		parse_str($request_result);
		$return = explode("=", str_replace("&", "=", $request_result));
		$_SESSION['api']['oauth_token_secret'] = $return[1];
		$_SESSION['api']['oauth_token'] = $return[3]; 
		$_SESSION['api']['redirect_to'] = (isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : ''); 
		header( 'Location: '.ACC_OAUTH_URL.'?'.$request_result );
		ob_flush(); 
		//echo $request_result;
		//exit;
	}
	
	if ( !empty($acc_oauth_token) && !empty($acc_oauth_verifier) ) {
		ob_start();
		session_start();
		$t_secret = $_SESSION['api']['oauth_token_secret'];
		$redirect_to = (isset($_SESSION['api']['redirect_to']) ? '&redirect_to='.$_SESSION['api']['redirect_to'] : '');
		$parameters = 'oauth_consumer_key='.urlencode(utf8_encode(ACC_OAUTH_KEY));
		$parameters .= '&oauth_nonce='.urlencode(utf8_encode(ACC_NONCE));
		$parameters .= '&oauth_signature_method='.urlencode(utf8_encode("HMAC-SHA1"));
		$parameters .= '&oauth_timestamp='.urlencode(utf8_encode(ACC_TIME));
		$parameters .= '&oauth_token='.urlencode(utf8_encode($acc_oauth_token));
		$parameters .= '&oauth_verifier='.urlencode(utf8_encode($acc_oauth_verifier));
		$parameters .= '&oauth_version='.urlencode(utf8_encode('1.0'));
		$resource_string = 'POST&'.urlencode(utf8_encode(ACC_ACS_URL)).'&'.urlencode(utf8_encode($parameters));
		$oauth_signature = base64_encode(hash_hmac('sha1', $resource_string, urlencode(ACC_OAUTH_SECRECT).'&'.urlencode($t_secret), true)); 
		$request_body = "oauth_nonce=".ACC_NONCE."&oauth_timestamp=".ACC_TIME;
		$request_body .="&oauth_consumer_key=".ACC_OAUTH_KEY;
		$request_body .= "&oauth_signature_method=".urlencode(utf8_encode("HMAC-SHA1"));
		$request_body .="&oauth_signature=".$oauth_signature."&oauth_version=1.0";
		$request_body .= "&oauth_verifier=".urlencode(utf8_encode($acc_oauth_verifier));
		$request_body .="&oauth_token=".urlencode(utf8_encode($acc_oauth_token));
		$my_request = curl_init();
		curl_setopt($my_request, CURLOPT_URL, ACC_ACS_URL);
		curl_setopt($my_request, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($my_request, CURLOPT_POST, 1);
		curl_setopt($my_request, CURLOPT_POSTFIELDS, $request_body);
		$access_request_result = curl_exec($my_request);
		curl_close($my_request);
		parse_str($access_request_result);
		$return = explode("=", str_replace("&", "=", $access_request_result));
		$_SESSION['api']['oauth_token_secret'] = $return[1];
		$_SESSION['api']['oauth_token'] = $return[3]; 
		$_SESSION['api']['user_id'] = $return[5];
		$_SESSION['api']['email'] = $return[7];
		$_SESSION['api']['username'] = $return[13];
		header( 'Location: '.home_url().'/'.ACC_LOGIN_SLUG.'?type=login'.$redirect_to );
		ob_flush();
		exit; 
	}
	
	if ( $acc_type === 'login' ) {
		ob_start();
		session_start();
		require_once(ABSPATH.'wp-includes/pluggable.php');
		$acc_mail = $_SESSION['api']['email'];		
		$acc_name = $_SESSION['api']['username'];		
		
		function wpa_valid_account( $account, $acc_name ){
			if ( is_user_logged_in() ) {
				wp_logout();
			}
			// اعتبار سنجی ایمیل
			if ( ! is_email($account) ) {
				return 'ER:ایمیل بازگشتی از ساختار صحیح برخوردار نیست.';
			}
			// بررسی وجود داشتن ایمیل در دیتابیس
			if( ! email_exists( $account ) ){
				$registration = get_option( 'users_can_register' );
				if ( ! $registration ) {
					return 'ER:امکان ثبت نام کاربر جدید غیرفعال شده است.';
				}
				$new_username = strtolower($acc_name).'_'.rand(10000, 99999);
				if ( username_exists( $new_username ) ) {
					$new_username = strtolower($acc_name).'_'.rand(10000000, 999999999);
				}
				$errors = register_new_user($new_username, $account);
				if ( is_wp_error($errors) ) {
					return 'ER:ثبت نام به درستی صورت نپذیرفت.';
				}
				$sent_mail = @wp_mail( $account, get_bloginfo('name'), "با سلام و احترام ؛\nثبت نام شما از طریق Donbaler OAuth صورت پذیرفت.\n\n".home_url());
				if ( !$sent_mail ){
					//return "ER:ارسال ایمیل با خطا مواجه شد ؛ با مدیر سیستم در میان بگذارید.";
				}
			}
			$user = get_user_by( 'email', $account );
			if ( ! $user ) {
				return "ER:حساب کاربری وجود ندارد.";
			}
			global $pagenow;
			if ( 'wp-login.php' != $pagenow ) {
				return "ER:صفحه ورود معتبر نیست ؛ با مدیر سیستم در میان بگذارید.";
			}
			$nonce = wp_create_nonce( 'acc_login:'.$user->ID );
			wp_clear_auth_cookie();
			do_action( 'wp_login', $user->user_login );
			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie($user->ID, true); 
			if ( is_user_logged_in() ) {
				//$current_user = wp_get_current_user();
				//return 'OK:'. $current_user->user_login;
				return 'OK';
			} else {
				return 'ER:ورود با خطا مواجه شد ؛ مجدداً تلاش کنید!';
			}
		}
		
		if ( !empty($acc_mail) ) {  
			//echo $email_account;
			$return = wpa_valid_account( $acc_mail, $acc_name );
			if ( $return === 'OK' ) {
				//echo $return;
				if( isset($_REQUEST['redirect_to']) ){
					wp_safe_redirect( home_url().$_REQUEST['redirect_to']);
				} else {
					if ( is_admin() ) {
						wp_safe_redirect( home_url().'/wp-admin');
					} else {
						wp_safe_redirect( home_url().'/'.ACC_LOGIN_SLUG);
					}
				}
				exit;
			} else {
				$explode = explode(':', $return);
				$error_msg = '';
				$error_msg .= '<div style="direction:rtl; text-align:right; font: 12px tahoma;">';
				$error_msg .= '<b>خطا!</b><br>';
				$error_msg .= $explode[1];
				$error_msg .= ' <a href="'.home_url().'/'.ACC_LOGIN_SLUG.'">[ بازگشت ]</a>';
				$error_msg .= '</div>';
				wp_die($error_msg);
				exit;
			}
		}
		ob_flush();
		
	}
		
?>