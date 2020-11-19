<?php
/**

 * Dragonpay WooCommerce Shopping Cart Plugin

 * 

 * @author Sean Paulo Bautista

 * @version 1.2.0

 * @example For callback : http://shoppingcarturl/?wc-api=WC_Dragonpay_Gateway

 * 

 */


/**

 * Plugin Name: Dragonpay

 * Plugin URI: http://leentechsystems.com/

 * Description: Dragonpay | The leading payment gateway in the Philippines with dragonpay payment solutions & free features: Physical Payment at 7-Eleven, Seamless Checkout, Tokenization, Loyalty Program and more for WooCommerce v2.3

 * Author: Leentech

 * Author URI: http://leentechsystems.com/

 * Version: 2.0

 * For callback : http://dev.dima.ph/cart/?wc-api=WC_Dragonpay_Gateway
 * For postback : http://dev.dima.ph/cart/?wc-api=WC_Dragonpay_Gateway

 */

 //If WooCommerce plugin is not available
function wcdragonpay_woocommerce_fallback_notice() {

    $message = '<div class="error">';
    $message .= '<p>' . __( 'WooCommerce Dragonpay Gateway depends on the last version of <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> to work!' , 'wcdragonpay' ) . '</p>';
    $message .= '</div>';
    echo $message;
}



//Load the function
add_action( 'plugins_loaded', 'wcdragonpay_gateway_load', 0 );

/**

 * Load dragonpay gateway plugin function

 * 

 * @return mixed

 */

function wcdragonpay_gateway_load() {

    if ( !class_exists( 'WC_Payment_Gateway' ) ) {
        add_action( 'admin_notices', 'wcdragonpay_woocommerce_fallback_notice' );
        return;
    }

    //Load language

    load_plugin_textdomain( 'wcdragonpay', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    add_filter( 'woocommerce_payment_gateways', 'wcdragonpay_add_gateway' );
    /**

     * Add dragonpay gateway to ensure WooCommerce can load it

     * 

     * @param array $methods

     * @return array

     */

    function wcdragonpay_add_gateway( $methods ) {
        $methods[] = 'WC_Dragonpay_Gateway';
        return $methods;
    }



    /**

     * Define the dragonpay gateway

     * 

     */

    class WC_Dragonpay_Gateway extends WC_Payment_Gateway {
        /**

         * Construct the dragonpay gateway class

         * 

         * @global mixed $woocommerce

         */

        public function __construct() {
            global $woocommerce;
            $this->id = 'dragonpay';
            $this->icon = plugins_url( 'images/dragonpay.png', __FILE__ );
            $this->has_fields = false;
            $this->method_title = __( 'Dragonpay', 'wcdragonpay' );
            // Load the form fields.
            $this->init_form_fields();
            // Load the settings.
            $this->init_settings();
            // Define user setting variables.
            $this->title = $this->settings['title'];
            $this->description = $this->settings['description'];
            $this->merchant_id = $this->settings['merchant_id'];
            $this->password = $this->settings['password'];
            $this->testmode  = $this->settings['testmode'];
            //Checking if testmode is enabled
            if ( $this->testmode == 'yes' ) {
               $this->pay_url = 'http://test.dragonpay.ph/Pay.aspx?';
            } else {
               $this->pay_url = 'https://gw.dragonpay.ph/Pay.aspx?';
            }
            // Actions.

            add_action( 'valid_dragonpay_request_callback', array( &$this, 'check_dragonpay_response_callback' ) );
            add_action( 'valid_dragonpay_request_postback', array( &$this, 'check_dragonpay_response_postback' ) );
            add_action( 'woocommerce_receipt_dragonpay', array( &$this, 'receipt_page' ) );
            //save setting configuration
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );		
            // Payment listener/API hook
            add_action( 'woocommerce_api_wc_dragonpay_gateway', array( $this, 'check_response' ));
            // Checking if merchant_id is not empty.
            $this->merchant_id == '' ? add_action( 'admin_notices', array( &$this, 'merchant_id_missing_message' ) ) : '';
            // Checking if verify_key is not empty.
            $this->password == '' ? add_action( 'admin_notices', array( &$this, 'password_missing_message' ) ) : '';

        }

        /**

         * Checking if this gateway is enabled and available in the user's country.

         *

         * @return bool

         */

        public function is_valid_for_use() {
            if ( !in_array( get_woocommerce_currency() , array( 'PHP' ) ) ) {
                return false;
            }
            return true;
        }



        /**

         * Admin Panel Options

         * - Options for bits like 'title' and availability on a country-by-country basis.

         *

         */

        public function admin_options() {

            ?>
            <h3><?php _e( 'Dragonpay Online Payment', 'wcdragonpay' ); ?></h3>
            <p><?php _e( 'Dragonpay Online Payment works by sending the user to dragonpay to enter their payment information.', 'wcdragonpay' ); ?></p>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table><!--/.form-table-->
            <?php

        }



        /**

         * Gateway Settings Form Fields.

         * 

         */

        public function init_form_fields() {

            $this->form_fields = array(

                'enabled' => array(
                    'title' => __( 'Enable/Disable', 'wcdragonpay' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable Dragonpay', 'wcdragonpay' ),
                    'default' => 'yes'
                ),

                'title' => array(
                    'title' => __( 'Title', 'wcdragonpay' ),
                    'type' => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', 'wcdragonpay' ),
                    'default' => __( 'Dragonpay Online Payment', 'wcdragonpay' )
                ),

                'description' => array(

                    'title' => __( 'Description', 'wcdragonpay' ),
                    'type' => 'textarea',
                    'description' => __( 'This controls the description which the user sees during checkout.', 'wcdragonpay' ),
                    'default' => __( 'Pay with Dragonpay Online Payment', 'wcdragonpay' )

                ),

                'merchant_id' => array(
                    'title' => __( 'Merchant ID', 'wcdragonpay' ),
                    'type' => 'text',
                    'description' => __( 'Please enter your Dragonpay Merchant ID.', 'wcdragonpay' ) . ' ' . sprintf( __( 'You can to get this information in: %sdragonpay Account%s.', 'wcdragonpay' ), '<a href="https://www.dragonpay.ph/" target="_blank">', '</a>' ),
                    'default' => ''

                ),

                'password' => array(
                    'title' => __( 'Password', 'wcdragonpay' ),
                    'type' => 'text',
                    'description' => __( 'Please enter your Dragonpay password.', 'wcdragonpay' ) . ' ' . sprintf( __( 'You can to get this information in: %sdragonpay Account%s.', 'wcdragonpay' ), '<a href="https://www.dragonpay.ph/" target="_blank">', '</a>' ),
                    'default' => ''

                ),

                 'testmode' => array(
                    'title' => __( 'Enable/Disable', 'wcdragonpay' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable Test Mode', 'wcdragonpay' ),
                    'default' => 'yes'
                ),

            );

        }



        /**

         * Generate the form.

         *

         * @param mixed $order_id

         * @return string

         */

        public function generate_form( $order_id ) {
            $order = new WC_Order( $order_id );	
            $pay_url = $this->pay_url;
            $total = $order->order_total;

            if ( sizeof( $order->get_items() ) > 0 ) 
                foreach ( $order->get_items() as $item )

                    if ( $item['qty'] )
                        $item_names[] = $item['name'] . ' x ' . $item['qty'];
                        $desc = sprintf( __( 'Order %s' , 'woocommerce'), $order->get_order_number() ) . " - " . implode( ', ', $item_names );
                        $merchantid = $this->merchant_id;
                        $txnid = $order->id;
                        $amount = $order->order_total;
                        $ccy = get_woocommerce_currency();
                        $description = $desc;
                        $email = $order->billing_email;
                        $password = $this->password;
                        $digest_str = "$merchantid:$txnid:$amount:$ccy:$description:$email:$password";  
                        $digest = sha1($digest_str);  
                        $dragonpay_args = array(
                            'merchantid' => $this->merchant_id,
                            'txnid' => $order->id,
                            'amount' => $total,
                            'ccy' => get_woocommerce_currency(),
                            'description' => $desc,
                            'email' => $order->billing_email,
                            'digest' => $digest
                        );
            $dragonpay_args_array = array();
            foreach ($dragonpay_args as $key => $value) {
                $dragonpay_args_array[] = "<input type='hidden' name='".$key."' value='". $value ."' />";

            }
            return "<form action='".$pay_url."' method='GET' id='dragonpay_payment_form' name='dragonpay_payment_form'>"
                    . implode('', $dragonpay_args_array)
                    . "<input type='submit' class='button-alt' id='submit_dragonpay_payment_form' value='" . __('Pay via dragonpay', 'woothemes') . "' /> "
                    . "<a class='button cancel' href='" . $order->get_cancel_order_url() . "'>".__('Cancel order &amp; restore cart', 'woothemes')."</a>"
                    . "<script>document.dragonpay_payment_form.submit();</script>"
                    . "</form>";
        }



        /**

         * Order error button.

         *

         * @param  object $order Order data.

         * @return string Error message and cancel button.

         */

        protected function dragonpay_order_error( $order ) {
            $html = '<p>' . __( 'An error has occurred while processing your payment, please try again. Or contact us for assistance.', 'wcdragonpay' ) . '</p>';
            $html .='<a class="button cancel" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Click to try again', 'wcdragonpay' ) . '</a>';
            return $html;
        }



        /**

         * Process the payment and return the result.

         *

         * @param int $order_id

         * @return array

         */

        public function process_payment( $order_id ) {
            global $woocommerce;
            $order = new WC_Order( $order_id );
            $woocommerce->cart->empty_cart();
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url( true )
            );
        }



        /**

         * Output for the order received page.

         * 

         */

        public function receipt_page( $order ) {
            echo $this->generate_form( $order );
        }



        /**

         * Check for dragonpay Response

         *

         * @access public

         * @return void

         */

    
        /**

         * This part is returnurl function for dragonpay

         * 

         * @global mixed $woocommerce

         */
        function check_response()
        {
            global $woocommerce;
            if($_GET['txnid'] && $_GET['status']) {
                do_action( "valid_dragonpay_request_callback", $_GET );
            } elseif($_POST['txnid'] && $_POST['status']) {
                do_action( "valid_dragonpay_request_postback", $_POST );
            }
        }

         /**

         * Get parameters response from Dragonpay - GET

         * 

         */

        function check_dragonpay_response_callback() {
            global $woocommerce;
            $order_id = $_GET['txnid'];
            $status = $_GET['status'];
            $order = new WC_Order( $order_id );
            if ($status == 'S') {
                $order->add_order_note('Dragonpay Payment Status: SUCCESSFUL');                             
                $order->payment_complete();
                wp_redirect($order->get_checkout_order_received_url());
                exit;
            }
            else if ($status == "P") { 
                $order->add_order_note('Dragonpay Payment Status: PENDING');
                $order->update_status('pending', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
                wp_redirect($order->get_checkout_order_received_url());
                exit;
            }
            else if ($status == "F") { 
                $order->add_order_note('Dragonpay Payment Status: FAILED');
                $order->update_status('failed', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
                wp_redirect($order->get_cancel_order_url());
                exit;
            } 
            else  {
                $order->add_order_note('dragonpay Payment Status: Invalid Transaction');
                $order->update_status('on-hold', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
                wp_redirect($order->get_cancel_order_url());
                exit;
            }   
        }

   /**

         * Get parameters response from Dragonpay - POST

         * 

         */


        function check_dragonpay_response_postback() {
            global $woocommerce;
            $order_id = $_POST['txnid'];
            $status = $_POST['status'];
            $order = new WC_Order( $order_id );
            if ($status == 'S') {
                $order->add_order_note('Dragonpay Payment Status: SUCCESSFUL');                             
                $order->payment_complete();
            }
            else if ($status == "P") { 
                $order->add_order_note('Dragonpay Payment Status: PENDING');
                $order->update_status('pending', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
            }
            else if ($status == "F") { 
                $order->add_order_note('Dragonpay Payment Status: FAILED');
                $order->update_status('failed', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
            } 
            else  {
                $order->add_order_note('dragonpay Payment Status: Invalid Transaction');
                $order->update_status('on-hold', sprintf(__('Payment %s via dragonpay.', 'woocommerce'), $order_id ) );
            }   
        }

		

	

        /**

         * Adds error message when not configured the app_key.

         * 

         */

        public function merchant_id_missing_message() {
            $message = '<div class="error">';
            $message .= '<p>' . sprintf( __( '<strong>Gateway Disabled</strong> You should inform your Merchant ID in Dragonpay. %sClick here to configure!%s' , 'wcdragonpay' ), '<a href="' . get_admin_url() . 'admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_Dragonpay_Gateway">', '</a>' ) . '</p>';
            $message .= '</div>';
            echo $message;
        }



        /**

         * Adds error message when not configured the app_secret.

         * 

         */

        public function password_missing_message() {
            $message = '<div class="error">';
            $message .= '<p>' . sprintf( __( '<strong>Gateway Disabled</strong> You should inform your Password in Dragonpay. %sClick here to configure!%s' , 'wcdragonpay' ), '<a href="' . get_admin_url() . 'admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_Dragonpay_Gateway">', '</a>' ) . '</p>';
            $message .= '</div>';
            echo $message;
        }

    }
}