
<?php
/**
 * Plugin Name: REST API Access Protection
 * Plugin URI: https://github.com/ariffmy/wp-rest-api-access-protection
 * Description: Restricts public REST API discovery, namespace indexes and user enumeration.
 * Version: 2.2.0
 * Author: ariff.my
 * Author URI: https://ariff.my/
 * License: GPL-2.0-or-later
 * Text Domain: ariffmy-rest-security
 */

defined('ABSPATH') || exit;

add_filter('rest_pre_dispatch', static function ($result, $server, $request) {

    // Preserve existing REST responses.
    if (null !== $result) {
        return $result;
    }

    // Allow authenticated users.
    if (is_user_logged_in()) {
        return $result;
    }

    // Normalize REST API route.
    $route = '/' . trim($request->get_route(), '/');

    // Block main REST API index.
    $blocked = ($route === '/');

    // Block all registered namespace indexes.
    foreach ($server->get_namespaces() as $namespace) {
        if (strcasecmp($route, '/' . trim($namespace, '/')) === 0) {
            $blocked = true;
            break;
        }
    }

    // Block public user enumeration.
    if (preg_match('#^/wp/v2/users(?:/|$)#i', $route)) {
        $blocked = true;
    }

    // Allow other REST API endpoints.
    if (!$blocked) {
        return $result;
    }

    // Return custom security response.
    return new WP_Error(
        'ariff_access_denied',
        'Access Denied | Protected by ariff.my',
        ['status' => 403]
    );

}, 10, 3);
