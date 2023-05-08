<?php

namespace WP_STATISTICS\Api\v2;

class CheckUserOnline extends \WP_STATISTICS\RestAPI
{
    /**
     * REST API Address for Checking Online Users
     *
     * @var string
     */
    const REST_ENDPOINT = 'online';

    /**
     * CheckUserOnline constructor.
     */
    public function __construct()
    {

        # Create REST API to Check Online User
        add_action('rest_api_init', array($this, 'register_online_user_rest_api'));
    }

    // Create REST API to Check Online Users
    public function register_online_user_rest_api()
    {
        register_rest_route(self::$namespace, '/' . self::REST_ENDPOINT, array(
            'methods' => 'GET',
            'callback' => [$this, 'onlineUserUpdateCallback'],
            'permission_callback' => function (\WP_REST_Request $request) {
                return true;
            }
        ));
    }

    public function onlineUserUpdateCallback()
    {
        $response = [
            'status' => true,
            'message' => 'User is online, Information recorded successfully.',
        ];
        return rest_ensure_response($response);
        \WP_STATISTICS\UserOnline::record();
    }
}

new CheckUserOnline();
