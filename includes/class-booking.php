<?php

namespace minibookcalc\admin\includes;
use minibookcalc\includes\minibookcalc_utils;

class minibookcalc_booking extends minibookcalc_utils{
	public function __construct()
	{
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
		add_shortcode('booking_quote', [$this, 'register_booking_form']);
		add_action('wp_ajax_send_mini_bookin_calc', [$this, 'send_booking']);
		add_action('wp_ajax_nopriv_send_mini_bookin_calc', [$this, 'send_booking']);
	}

	public function load_assets()
	{
		wp_register_script('minibookcalc-booking-js', MINIBOOKCAL_PLUGIN_URL.'assets/mini-booking.js', [], uniqid());
        wp_enqueue_script('minibookcalc-booking-js');
        wp_enqueue_style('minibookcalc-booking-css', MINIBOOKCAL_PLUGIN_URL.'assets/style.css' , [], uniqid());
		$cauto_variables = [
            'ajaxurl'   => admin_url( 'admin-ajax.php' ), 
            'nonce'     => wp_create_nonce( $this->nonce_key )
        ];
        wp_localize_script('minibookcalc-booking-js', 'minibooking', $cauto_variables);
	}

	public function register_booking_form()
	{
		ob_start();
		$this->get_view('mini-form', ['rates' => [
			'distance'	=> $this->default_km_rate,
			'base'		=> $this->default_base_fee,
			'room'		=> $this->default_room_rate,
			'currency'	=> $this->default_currency
		], 'rooms' => $this->default_no_rooms]);
		return ob_get_clean();
	}

	public function send_booking() {

		if ( !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), $this->nonce_key ) ) {
            wp_send_json(
                [
                    'status'    => 'failed',
                    'message'   => esc_html(__('Invalid nonce please contact developer or clear your cache', 'minibookcalc'))
                ]
            );
            exit();
        }

		$payload = isset($_POST['payload'])? $_POST['payload'] : null; 

		if (!$payload) {
			wp_send_json(
                [
                    'status'    => 'failed',
                    'message'   => esc_html(__('No payload found', 'minibookcalc'))
                ]
            );
            exit();
		}

		$payload = (array) json_decode(wp_unslash($payload));

		if (is_array($payload)) {
			foreach ($payload as $index => $load) {
				$payload[$index] = sanitize_text_field($load); //sanitize
			}
		}

		$res = $this->send_to_db($payload);
		$this->send_to_email($payload);
		wp_send_json($res);
		
	}

	public function send_to_db($payload)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . MINIBOOKCAL_TABLE_NAME;
		$res = $wpdb->insert(
			$table_name,
			$payload
		);

		if ($res) {
			return [
				'status' 	=> 'succes',
				'message'	=> __('Data is successfully sent', 'minibookcalc'),
			];
		} else {
			return[	
				'status' 	=> 'failed',
				'error'	=> __('Failed to insert data. Error: '.$wpdb->last_error),
			];
		}
	}

	public function send_to_email($payload)
	{	
		$to = get_option('admin_email');
		
		if (!$to) return;

		$subject = __('NOTICE: New booking is made', 'minibookcalc');

		$distance_total = $this->default_km_rate * (float) $payload['distance'];
		$rooms_rate	    = $this->default_room_rate * (int) $payload['number_of_rooms'];
		$total			= $this->default_base_fee + $distance_total + $rooms_rate;
		$total			= $this->default_currency.$total;

		$body = sprintf('
			<div>
				<p>Dear Admin,</p>
				<p>New booking is being sent, please find the details below</p>
				<p>Name: %s</p>
				<p>Address: %s</p>
				<p>Distance: %s</p>
				<p>Number of rooms: %s</p>
				<p>Total: %s</p>
			</div>
		', $payload['name'], $payload['address'], $payload['distance'], $payload['number_of_rooms'], $total);
		$headers = array('Content-Type: text/html; charset=UTF-8');

		wp_mail( $to, $subject, $body, $headers );
	}
	
}

new minibookcalc_booking();