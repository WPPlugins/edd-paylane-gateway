<?php
/**
 * Helper functions
 *
 * @package         EDD\Gateway\PayLane\Functions
 * @since           1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Check if a given currency is supported by PayLane
 *
 * @since       1.0.0
 * @param       string $currency The currency to check
 * @return      bool $supported True if supported, false otherwise
 */
function edd_paylane_is_valid_currency( $currency ) {
    $types = array(
        'EUR',
        'GBP',
        'PLN',
        'USD'
    );

    if( in_array( $currency, $types ) ) {
        $supported = true;
    } else {
        $supported = false;
    }

    return $supported;
}


/**
 * Sanitize credit card month
 *
 * @since       1.0.0
 * @param       string $month The credit card month
 * @return      string $month The sanitized month
 */
function edd_paylane_sanitize_month( $month ) {
    if( strlen( $month ) == 1 ) {
        $month = '0' . $month;
    }

    return $month;
}


/**
 * Error handler
 *
 * @since       1.0.0
 * @param       object $error The error returned by the gateway
 * @return      string $message The error message to display to the user
 */
function edd_paylane_error_handler( $error ) {
    switch( $error->error_number ) {
        case '302':
            $message = __( 'Direct debit is not accessible for this country.', 'edd-paylane-gateway' );
            break;
        case '303':
            $message = __( 'Direct debit declined.', 'edd-paylane-gateway' );
            break;
        case '312':
            $message = __( 'Account holder name is not valid.', 'edd-paylane-gateway' );
            break;
        case '313':
            $message = __( 'Customer name is not valid.', 'edd-paylane-gateway' );
            break;
        case '314':
            $message = __( 'Customer email is not valid.', 'edd-paylane-gateway' );
            break;
        case '315':
            $message = __( 'Customer address is noti valid.', 'edd-paylane-gateway' );
            break;
        case '316':
            $message = __( 'Customer city is not valid.', 'edd-paylane-gateway' );
            break;
        case '317':
            $message = __( 'Customer zip code is not valid.', 'edd-paylane-gateway' );
            break;
        case '318':
            $message = __( 'Customer state is not valid.', 'edd-paylane-gateway' );
            break;
        case '319':
            $message = __( 'Customer country is not valid.', 'edd-paylane-gateway' );
            break;
        case '320':
            $message = __( 'Amount is not valid.', 'edd-paylane-gateway' );
            break;
        case '321':
            $message = __( 'Amount is below merchant threshold.', 'edd-paylane-gateway' );
            break;
        case '322':
            $message = __( 'Currency code is not valid.', 'edd-paylane-gateway' );
            break;
        case '323':
            $message = __( 'Customer IP address is not valid.', 'edd-paylane-gateway' );
            break;
        case '324':
            $message = __( 'Transaction description is not valid.', 'edd-paylane-gateway' );
            break;
        case '325':
            $message = __( 'Account country is not valid.', 'edd-paylane-gateway' );
            break;
        case '326':
            $message = __( 'Bank code is not valid.', 'edd-paylane-gateway' );
            break;
        case '327':
            $message = __( 'Account number is not valid.', 'edd-paylane-gateway' );
            break;
        case '401':
            $message = __( 'Multiple transaction lock triggered. Please try again in a moment.', 'edd-paylane-gateway' );
            break;
        case '402':
            $message = __( 'Payment gateway error. Please try again later.', 'edd-paylane-gateway' );
            break;
        case '403':
            $message = __( 'Card declined.', 'edd-paylane-gateway' );
            break;
        case '404':
            $message = __( 'Transaction in this currency is not allowed.', 'edd-paylane-gateway' );
            break;
        case '405':
            $message = __( 'Unknown payment method or method not set.', 'edd-paylane-gateway' );
            break;
        case '406':
            $message = __( 'More than one payment method provided.', 'edd-paylane-gateway' );
            break;
        case '407':
            $message = __( 'Capture later not possible with this payment method.', 'edd-paylane-gateway' );
            break;
        case '408':
            $message = __( 'Feature not available for this payment method.', 'edd-paylane-gateway' );
            break;
        case '409':
            $message = __( 'Overriding defaults not allowed for this merchant account.', 'edd-paylane-gateway' );
            break;
        case '410':
            $message = __( 'Unsupported payment method.', 'edd-paylane-gateway' );
            break;
        case '411':
            $message = __( 'Card number format is not valid.', 'edd-paylane-gateway' );
            break;
        case '412':
            $message = __( 'Card expiration year is not valid.', 'edd-paylane-gateway' );
            break;
        case '413':
            $message = __( 'Card expiration month is not valid.', 'edd-paylane-gateway' );
            break;
        case '414':
            $message = __( 'Card expiration year is in the past.', 'edd-paylane-gateway' );
            break;
        case '415':
            $message = __( 'Card has expired.', 'edd-paylane-gateway' );
            break;
        case '416':
            $message = __( 'Card code format is not valid.', 'edd-paylane-gateway' );
            break;
        case '417':
            $message = __( 'Name on card is not valid.', 'edd-paylane-gateway' );
            break;
        case '418':
            $message = __( 'Cardholder name is not valid.', 'edd-paylane-gateway' );
            break;
        case '419':
            $message = __( 'Cardholder email is not valid.', 'edd-paylane-gateway' );
            break;
        case '420':
            $message = __( 'Cardholder address is not valid.', 'edd-paylane-gateway' );
            break;
        case '421':
            $message = __( 'Cardholder city is not valid.', 'edd-paylane-gateway' );
            break;
        case '422':
            $message = __( 'Cardholder zip is not valid.', 'edd-paylane-gateway' );
            break;
        case '423':
            $message = __( 'Cardholder state is not valid.', 'edd-paylane-gateway' );
            break;
        case '424':
            $message = __( 'Cardholder country is not valid.', 'edd-paylane-gateway' );
            break;
        case '425':
            $message = __( 'Amount is not valid.', 'edd-paylane-gateway' );
            break;
        case '426':
            $message = __( 'Amount is below merchant threshold.', 'edd-paylane-gateway' );
            break;
        case '427':
            $message = __( 'Currency code is not valid.', 'edd-paylane-gateway' );
            break;
        case '428':
            $message = __( 'Client IP is not valid.', 'edd-paylane-gateway' );
            break;
        case '429':
            $message = __( 'Purchase description is not valid.', 'edd-paylane-gateway' );
            break;
        case '430':
            $message = __( 'Unknown card type or card number invalid.', 'edd-paylane-gateway' );
            break;
        case '431':
            $message = __( 'Card issue number is not valid.', 'edd-paylane-gateway' );
            break;
        case '432':
            $message = __( 'Fraud check on is not valid.', 'edd-paylane-gateway' );
            break;
        case '433':
            $message = __( 'AVS level is not valid.', 'edd-paylane-gateway' );
            break;
        case '441':
            $message = __( 'Sale authorization ID is not valid.', 'edd-paylane-gateway' );
            break;
        case '442':
            $message = __( 'Sale authorization ID not found or authorization has been closed.', 'edd-paylane-gateway' );
            break;
        case '443':
            $message = __( 'Capture sale amount greater than the authorization amount.', 'edd-paylane-gateway' );
            break;
        case '470':
            $message = __( 'Resale without card code is not allowed for this merchant account.', 'edd-paylane-gateway' );
            break;
        case '471':
            $message = __( 'Sale ID is not valid.', 'edd-paylane-gateway' );
            break;
        case '472':
            $message = __( 'Resale amount is not valid.', 'edd-paylane-gateway' );
            break;
        case '473':
            $message = __( 'Amount is below merchant account threshold.', 'edd-paylane-gateway' );
            break;
        case '474':
            $message = __( 'Resale currency code is not valid.', 'edd-paylane-gateway' );
            break;
        case '475':
            $message = __( 'Resale description is not valid.', 'edd-paylane-gateway' );
            break;
        case '476':
            $message = __( 'Sale ID not found.', 'edd-paylane-gateway' );
            break;
        case '477':
            $message = __( 'Cannot resale. Chargeback assigned to sale ID.', 'edd-paylane-gateway' );
            break;
        case '478':
            $message = __( 'Cannot resale this sale.', 'edd-paylane-gateway' );
            break;
        case '479':
            $message = __( 'Card has expired.', 'edd-paylane-gateway' );
            break;
        case '480':
            $message = __( 'Cannot resale. Reversal assigned to sale ID.', 'edd-paylane-gateway' );
            break;
        case '481':
            $message = __( 'Sale ID is not valid.', 'edd-paylane-gateway' );
            break;
        case '482':
            $message = __( 'Refund amount is not valid.', 'edd-paylane-gateway' );
            break;
        case '483':
            $message = __( 'Refund reason is not valid.', 'edd-paylane-gateway' );
            break;
        case '484':
            $message = __( 'Sale ID not found.', 'edd-paylane-gateway' );
            break;
        case '485':
            $message = __( 'Cannot refund. Chargeback assigned to sale ID.', 'edd-paylane-gateway' );
            break;
        case '486':
            $message = __( 'Cannot refund. Exceeded available refund amount.', 'edd-paylane-gateway' );
            break;
        case '487':
            $message = __( 'Cannot refund. Sale is already refunded.', 'edd-paylane-gateway' );
            break;
        case '488':
            $message = __( 'Cannot refund this sale.', 'edd-paylane-gateway' );
            break;
        case '491':
            $message = __( 'Sale ID is not set or empty.', 'edd-paylane-gateway' );
            break;
        case '492':
            $message = __( 'Sale ID is too large.', 'edd-paylane-gateway' );
            break;
        case '493':
            $message = __( 'Sale ID is not valid.', 'edd-paylane-gateway' );
            break;
        case '501':
            $message = __( 'Internal server error. Please try again later.', 'edd-paylane-gateway' );
            break;
        case '502':
            $message = __( 'Payment gateway error. Please try again later.', 'edd-paylane-gateway' );
            break;
        case '503':
            $message = __( 'Payment method not allowed for this merchant account.', 'edd-paylane-gateway' );
            break;
        case '505':
            $message = __( 'This merchant account is inactive.', 'edd-paylane-gateway' );
            break;
        case '601':
            $message = __( 'Fraud attempt detected.', 'edd-paylane-gateway' );
            break;
        case '611':
            $message = __( 'Blacklisted account number found.', 'edd-paylane-gateway' );
            break;
        case '612':
            $message = __( 'Blacklisted card country found.', 'edd-paylane-gateway' );
            break;
        case '613':
            $message = __( 'Blacklisted card number found.', 'edd-paylane-gateway' );
            break;
        case '614':
            $message = __( 'Blacklisted customer country found.', 'edd-paylane-gateway' );
            break;
        case '615':
            $message = __( 'Blacklisted customer email found.', 'edd-paylane-gateway' );
            break;
        case '616':
            $message = __( 'Blacklisted customer IP address found.', 'edd-paylane-gateway' );
            break;
        case '701':
            $message = __( '3-D Secure authentication server error. Please try again or use card not enrolled in 3-D Secure.', 'edd-paylane-gateway' );
            break;
        case '702':
            $message = __( '3-D Secure authentication server problem. Please try again or use card not enrolled in 3-D Secure.', 'edd-paylane-gateway' );
            break;
        case '703':
            $message = __( '3-D Secure authentication failed. Credit card cannot be accepted for payment.', 'edd-paylane-gateway' );
            break;
        case '704':
            $message = __( '3-D Secure authentication failed. Card declined.', 'edd-paylane-gateway' );
            break;
        case '711':
            $message = __( 'Card number format is not valid.', 'edd-paylane-gateway' );
            break;
        case '712':
            $message = __( 'Card expiration year is not valid.', 'edd-paylane-gateway' );
            break;
        case '713':
            $message = __( 'Card expiration month is not valid.', 'edd-paylane-gateway' );
            break;
        case '714':
            $message = __( 'Card has expired.', 'edd-paylane-gateway' );
            break;
        case '715':
            $message = __( 'Amount is not valid.', 'edd-paylane-gateway' );
            break;
        case '716':
            $message = __( 'Currency code is not valid.', 'edd-paylane-gateway' );
            break;
        case '717':
            $message = __( 'Back URL is not valid.', 'edd-paylane-gateway' );
            break;
        case '718':
            $message = __( 'Unknown card type or card number invalid.', 'edd-paylane-gateway' );
            break;
        case '719':
            $message = __( 'Card issue number is not valid.', 'edd-paylane-gateway' );
            break;
        case '720':
            $message = __( 'Unable to verify enrollment for 3-D Secure. You can perform a payment without 3-D Secure or decline the transaction.', 'edd-paylane-gateway' );
            break;
        case '731':
            $message = __( 'Completed authentication with this Secure3d ID not found.', 'edd-paylane-gateway' );
            break;
        case '732':
            $message = __( 'Sale and 3-D Secure card numbers are different.', 'edd-paylane-gateway' );
            break;
        case '733':
            $message = __( 'Sale and 3-D Secure card expiration years are different.', 'edd-paylane-gateway' );
            break;
        case '734':
            $message = __( 'Sale and 3-D Secure card expiration months are different.', 'edd-paylane-gateway' );
            break;
        case '735':
            $message = __( 'Sale and 3-D Secure amounts are different.', 'edd-paylane-gateway' );
            break;
        case '736':
            $message = __( 'Sale and 3-D Secure currency codes are different.', 'edd-paylane-gateway' );
            break;
        case '737':
            $message = __( 'Sale was performed for this Secure3d ID.', 'edd-paylane-gateway' );
            break;
        default:
            $message = $error->error_description;
            break;
    }


    return apply_filters( 'edd_paylane_gateway_error', $message, $error );
}
