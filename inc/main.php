<?php
add_filter( 'https_ssl_verify', '__return_false' );
add_action( 'admin_enqueue_scripts', 'TiengViet_admin_style');
add_action( 'after_wp_tiny_mce', 'TiengViet_custom_script_load',200);
add_action( 'admin_footer', 'TiengViet_custom_script_load');
add_action( 'add_meta_boxes', 'TiengViet_add_meta_box',200);
add_action( 'wp_ajax_TiengViet_getLSIKeyword', 'TiengViet_getLSIKeyword' );
add_action( 'wp_ajax_nopriv_TiengViet_getLSIKeyword', 'TiengViet_getLSIKeyword' );
add_action( 'wp_ajax_TiengViet_getAudit', 'TiengViet_getAudit' );
add_action( 'wp_ajax_nopriv_TiengViet_getAudit', 'TiengViet_getAudit' );
add_action( 'wp_ajax_TiengViet_Deposit', 'TiengViet_Deposit' );
add_action( 'wp_ajax_nopriv_TiengViet_Deposit', 'TiengViet_Deposit' );
add_action( 'wp_ajax_TiengViet_Coupon', 'TiengViet_Coupon' );
add_action( 'wp_ajax_nopriv_TiengViet_Coupon', 'TiengViet_Coupon' );
add_action( 'product_cat_edit_form', 'TiengViet_meta_box_custom',100);
add_action( 'category_edit_form', 'TiengViet_meta_box_custom',100);
add_action( 'admin_menu', 'TiengViet_adminPanel_settings'); 
?>