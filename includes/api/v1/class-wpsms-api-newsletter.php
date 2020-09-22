<?php

namespace WP_SMS\Api\V1;

use WP_SMS\RestApi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @category   class
 * @package    WP_SMS_Api
 * @version    1.0
 */
class Newsletter {

	/**
	 * Register routes
	 *
	 * @param $route
	 */
	public static function registerRoute( $route ) {

		// SMS Newsletter
		register_rest_route( RestApi::$namespace, $route, array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( self::class, 'subscribe_callback' ),
				'args'                => array(
					'name'     => array(
						'required' => true,
					),
					'mobile'   => array(
						'required' => true,
					),
					'group_id' => array(
						'required' => false,
					),
				),
				'permission_callback' => '__return_true'
			),
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( self::class, 'unsubscribe_callback' ),
				'args'                => array(
					'name'   => array(
						'required' => true,
					),
					'mobile' => array(
						'required' => true,
					),
				),
				'permission_callback' => '__return_true'
			),
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( self::class, 'verify_subscriber_callback' ),
				'args'                => array(
					'name'       => array(
						'required' => true,
					),
					'mobile'     => array(
						'required' => true,
					),
					'activation' => array(
						'required' => true,
					),
				),
				'permission_callback' => '__return_true'
			)
		) );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function subscribe_callback( \WP_REST_Request $request ) {
		// Get parameters from request
		$params = $request->get_params();
		$number = RestApi::convertNumber( $params['mobile'] );

		$group_id = isset ( $params['group_id'] ) ? $params['group_id'] : 1;
		$result   = RestApi::subscribe( $params['name'], $number, $group_id );

		if ( is_wp_error( $result ) ) {
			return RestApi::response( $result->get_error_message(), 400 );
		}

		return RestApi::response( $result );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function unsubscribe_callback( \WP_REST_Request $request ) {
		// Get parameters from request
		$params = $request->get_params();
		$number = RestApi::convertNumber( $params['mobile'] );

		$group_id = isset ( $params['group_id'] ) ? $params['group_id'] : 1;
		$result   = RestApi::unSubscribe( $params['name'], $number, $group_id );

		if ( is_wp_error( $result ) ) {
			return RestApi::response( $result->get_error_message(), 400 );
		}

		return RestApi::response( __( 'Your number has been successfully unsubscribed.', 'wp-sms' ) );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function verify_subscriber_callback( \WP_REST_Request $request ) {
		// Get parameters from request
		$params = $request->get_params();
		$number = RestApi::convertNumber( $params['mobile'] );

		$group_id = isset ( $params['group_id'] ) ? $params['group_id'] : 1;
		$result   = RestApi::verifySubscriber( $params['name'], $number, $params['activation'], $group_id );

		if ( is_wp_error( $result ) ) {
			return RestApi::response( $result->get_error_message(), 400 );
		}

		return RestApi::response( __( 'Your number has been successfully subscribed.', 'wp-sms' ) );
	}
}

new Newsletter();