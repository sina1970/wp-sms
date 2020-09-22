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
class Subscribers {

	/**
	 * Register API class route
	 *
	 * @param $route
	 */
	public static function registerRoute( $route ) {

		// SMS Newsletter
		register_rest_route( RestApi::$namespace, $route, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( self::class, 'subscribers_callback' ),
				'args'                => array(
					'page'     => array(
						'required' => false,
					),
					'group_id' => array(
						'required' => false,
					),
					'number'   => array(
						'required' => false,
					),
					'search'   => array(
						'required' => false,
					)
				),
				'permission_callback' => array( self::class, 'get_item_permissions_check' ),
			)
		) );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function subscribers_callback( \WP_REST_Request $request ) {
		// Get parameters from request
		$params = $request->get_params();

		$page     = isset ( $params['page'] ) ? $params['page'] : '';
		$group_id = isset ( $params['group_id'] ) ? $params['group_id'] : '';
		$mobile   = isset ( $params['mobile'] ) ? $params['mobile'] : '';
		$search   = isset ( $params['search'] ) ? $params['search'] : '';
		$result   = RestApi::getSubscribers( $page, $group_id, $mobile, $search );

		return RestApi::response( $result );
	}

	/**
	 * Check user permission
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function get_item_permissions_check( $request ) {
		return current_user_can( 'wpsms_subscribers' );
	}
}

new Subscribers();