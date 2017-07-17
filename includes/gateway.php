<?php
/**
 * Gateway Functions
 *
 * @package         EDD\Gateway\PayLane\Gateway
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Add settings section
 *
 * @since       1.0.3
 * @param       array $sections The existing extensions sections
 * @return      array The modified extensions settings
 */
function edd_paylane_add_settings_section( $sections ) {
    $sections['paylane'] = __( 'PayLane', 'edd-paylane' );

    return $sections;
}
add_filter( 'edd_settings_sections_gateways', 'edd_paylane_add_settings_section' );


/**
 * Register settings
 *
 * @since        1.0.0
 * @param        array $settings The existing plugin settings
 * @param        array The modified plugin settings array
 */
function edd_paylane_register_settings( $settings ) {
    $new_settings = array(
        'paylane' => array(
            array(
                'id'   => 'edd_paylane_gateway_settings',
                'name' => '<strong>' . __( 'PayLane Gateway Settings', 'edd-paylane-gateway' ) . '</strong>',
                'desc' => '',
                'type' => 'header'
            ),
            array(
                'id'   => 'edd_paylane_gateway_username',
                'name' => __( 'Username', 'edd-paylane-gateway' ),
                'desc' => __( 'Enter your PayLane API username.', 'edd-paylane-gateway' ),
                'type' => 'text'
            ),
            array(
                'id'   => 'edd_paylane_gateway_password',
                'name' => __( 'Password', 'edd-paylane-gateway' ),
                'desc' => __( 'Enter your PayLane API password.', 'edd-paylane-gateway' ),
                'type' => 'text'
            )
        )
    );

    return array_merge( $settings, $new_settings );
}
add_filter( 'edd_settings_gateways', 'edd_paylane_register_settings', 1 );


/**
 * Register our new gateway
 *
 * @since        1.0.0
 * @param        array $gateways The current gateway list
 * @return        array $gateways The updated gateway list
 */
function edd_paylane_register_gateway( $gateways ) {
    $gateways['paylane'] = array(
        'admin_label'    => 'PayLane',
        'checkout_label' => __( 'Credit Card', 'edd-paylane-gateway' )
    );

    return $gateways;
}
add_filter( 'edd_payment_gateways', 'edd_paylane_register_gateway' );


/**
 * Process payment submission
 *
 * @since        1.0.0
 * @param        array $purchase_data The data for a specific purchase
 * @return        void
 */
function edd_paylane_process_payment( $purchase_data ) {
    $errors = edd_get_errors();

    if( ! $errors ) {
        $username = edd_get_option( 'edd_paylane_gateway_username', '' );
        $password = edd_get_option( 'edd_paylane_gateway_password', '' );
        $currency = edd_get_currency();

        try{
            // Handle errors
            $err = false;

            $required = array(
                'card_name'      => __( 'Card name is required.', 'edd-paylane-gateway' ),
                'card_number'    => __( 'Card number is required.', 'edd-paylane-gateway' ),
                'card_exp_month' => __( 'Card expiration month is required.', 'edd-paylane-gateway' ),
                'card_exp_year'  => __( 'Card expiration year is required.', 'edd-paylane-gateway' ),
                'card_cvc'       => __( 'Card CVC is required.', 'edd-paylane-gateway' )
            );

            foreach( $required as $field => $error ) {
                if( ! $purchase_data['card_info'][$field] ) {
                    edd_set_error( 'authorize_error', $error );
                    $err = true;
                }
            }

            if( ! edd_paylane_is_valid_currency( $currency ) ) {
                edd_set_error( 'authorize_error', __( 'The specified currency is not supported by PayLane at this time.', 'edd-paylane-gateway' ) );
                $err = true;
            }

            if( $err ) {
                edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
            }

            $params = array(
                'sale' => array(
                    'amount'      => $purchase_data['price'],
                    'currency'    => $currency,
                    'description' => $purchase_data['purchase_key'],
                ),
                'customer' => array(
                    'name'    => $purchase_data['card_info']['card_name'],
                    'email'   => $purchase_data['user_email'],
                    'ip'      => edd_get_ip(),
                    'address' => array(
                        'street_house' => $purchase_data['card_info']['card_address'],
                        'city'         => $purchase_data['card_info']['card_city'],
                        'state'        => $purchase_data['card_info']['card_state'],
                        'zip'          => $purchase_data['card_info']['card_zip'],
                        'country_code' => $purchase_data['card_info']['card_country']
                    ),
                ),
                'card' => array(
                    'card_number'      => $purchase_data['card_info']['card_number'],
                    'expiration_month' => edd_paylane_sanitize_month( $purchase_data['card_info']['card_exp_month'] ),
                    'expiration_year'  => $purchase_data['card_info']['card_exp_year'],
                    'name_on_card'     => $purchase_data['card_info']['card_name'],
                    'card_code'        => $purchase_data['card_info']['card_cvc']
                )
            );

            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
                ),
                'body' => json_encode( $params )
            );

            $response = wp_remote_retrieve_body( wp_remote_post( 'https://direct.paylane.com/rest/cards/sale', $args ) );
            $response = json_decode( $response );

            if( ! $response->success ) {
                $error = edd_paylane_error_handler( $response->error );

                edd_set_error( 'authorize_error', $error );
                edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
            } else {
                $payment_data = array(
                    'price'        => $purchase_data['price'],
                    'date'         => $purchase_data['date'],
                    'user_email'   => $purchase_data['user_email'],
                    'purchase_key' => $purchase_data['purchase_key'],
                    'currency'     => $currency,
                    'downloads'    => $purchase_data['downloads'],
                    'cart_details' => $purchase_data['cart_details'],
                    'user_info'    => $purchase_data['user_info'],
                    'status'       => 'pending'
                );

                $payment = edd_insert_payment( $payment_data );

                if( $payment ) {
                    edd_insert_payment_note( $payment, sprintf( __( 'PayLane Gateway Transaction ID: %s', 'edd-paylane-gateway' ), $response->id_sale ) );
                    if( function_exists( 'edd_set_payment_transaction_id' ) ) {
                        edd_set_payment_transaction_id( $payment, $response->id_sale );
                    }
                    edd_update_payment_status( $payment, 'publish' );
                    edd_send_to_success_page();
                } else {
                    edd_set_error( 'authorize_error', __( 'Your payment could not be recorded. Please try again.', 'edd-paylane-gateway' ) );
                    edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
                }
            }
        } catch( Exception $e ) {
            edd_record_gateway_error( __( 'PayLane Gateway Error', 'edd-paylane-gateway' ), print_r( $e, true ), 0 );
            edd_set_error( 'card_declined', __( 'Your card was declined.', 'edd-paylane-gateway' ) );
            edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
        }
    } else {
        edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
    }
}
add_action( 'edd_gateway_paylane', 'edd_paylane_process_payment' );


/**
 * Output form errors
 *
 * @since        1.0.0
 * @return        void
 */
function edd_paylane_errors_div() {
    echo '<div id="edd-paylane-errors"></div>';
}
add_action( 'edd_after_cc_fields', 'edd_paylane_errors_div', 999 );