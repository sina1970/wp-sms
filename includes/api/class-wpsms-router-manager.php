<?php

namespace WP_SMS\Api;

use BsBlockCore\Core\Rest\Rest;
use WP_SMS\RestApi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Router_Manager {

	public static $registeredRoutes;

	public function __construct() {
		// Register routes
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}

	/**
	 * Register routes
	 */
	public function registerRoutes() {
		$this->registerRouteEndpoints( '/send' );
		$this->registerRouteEndpoints( '/credit' );
		$this->registerRouteEndpoints( '/newsletter' );
		$this->registerRouteEndpoints( '/subscribers' );
	}

	/**
	 * Register API class endpoints
	 *
	 * @param string $route
	 */
	public function registerRouteEndpoints( $route ) {

		// Set class name to include
		$className = str_replace( '/', '', $route );

		include( RestApi::$version . '/class-wpsms-api-' . $className . '.php' );

		$className = '\\WP_SMS\\Api\\' . RestApi::$version . '\\' . ucfirst( $className );
		$className::registerRoute( $route );
	}
}

new Router_Manager();