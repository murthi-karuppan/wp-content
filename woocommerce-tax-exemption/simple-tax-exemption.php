<?php
   /*
   Plugin Name: Woocommerce Tax Exemption
   Description: A plugin to add simple tax exemption to the Woocommerce checkout page. Records select and deselect the checkbox to the order meta.
   */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/*Tax Exempt Checkout for Woocommerce */



add_action('woocommerce_before_order_notes', 'taxexempt_before_order_notes' );

function taxexempt_before_order_notes( $checkout ) {



        echo '<div style="clear: both"></div>



        <h3>Tax Exempt Details</h3>';

        woocommerce_form_field( 'tax_exempt_checkbox', array(

            'type'          => 'checkbox',

            'class'         => array('tax-exempt-checkbox', 'update_totals_on_change'),

            'label'         => __('Tax Exempt'),

            ), $checkout->get_value( 'tax_exempt_checkbox' ));


}

add_action( 'woocommerce_checkout_update_order_review', 'taxexempt_checkout_update_order_review');

function taxexempt_checkout_update_order_review( $post_data ) {

        global $woocommerce;
        $woocommerce->customer->set_is_vat_exempt(FALSE);
        parse_str($post_data);
        if ( isset($tax_exempt_checkbox) && $tax_exempt_checkbox == '1')
            $woocommerce->customer->set_is_vat_exempt(true);
    }

/**

 * Update the order meta with field value

 **/

add_action('woocommerce_checkout_update_order_meta', 'tax_exempt_field_update_order_meta');

function tax_exempt_field_update_order_meta( $order_id ) {

    if ($_POST['tax_exempt_checkbox']) update_post_meta( $order_id, 'Tax Exempt ID', esc_attr($_POST['tax_exempt_checkbox']));
}

/**

 * Display field value on the order edition page

 **/

add_action( 'woocommerce_admin_order_data_after_billing_address', 'tax_exempt_field_display_admin_order_meta', 10, 1 );
function tax_exempt_field_display_admin_order_meta($order){

    echo '<p><strong>'.__('Tax Exempt ID').':</strong> ' . get_post_meta( $order->id, 'Tax Exempt ID', true ) . '</p>';
}

/*Enqueue the tax exempt trigger script*/

function woocommerce_tax_exempt_script() {

    wp_enqueue_style('tax-exempt-css', plugins_url('/css/tax-exempt.css', __FILE__ ));

    wp_enqueue_script('tax_exempt', plugins_url('/js/tax-exempt.js', __FILE__ ), array('jquery'), '1.0', true );

}

add_action('init', 'woocommerce_tax_exempt_script', 100);

}

?>
