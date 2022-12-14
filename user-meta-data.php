<?php
/*
* Plugin Name: PFS-Display Predefined User Meta Data
* Description: This plugin will display custom meta data for users on the User Profile
* Version: 1.0.5
* Author: Pink Fizz Social
* Author URI: http://pinkfizz.social
* License: GPL2
*/

/**
 * Add extra meta details to User Page
 * *
 * */
function mysite_custom_define() {
  $custom_meta_fields = array();
  $custom_meta_fields['planner-renewal'] = 'Planner Renewal Email Status';
  $custom_meta_fields['card-expiry'] = 'Card Expiry Email Status';
  $custom_meta_fields['academy_membership'] = 'Academy Membership:';	
  $custom_meta_fields['subscription_renewal_email_last_sent'] = 'Subscription Renewal Email Last Sent:';
  $custom_meta_fields['academy+_upgrade'] = 'Has Academy been upgraded?';  
  $custom_meta_fields['temporarily_block_automatewoo_emails'] = 'Temporarily Block AutomateWoo Emails:';
  return $custom_meta_fields;
}
function mysite_columns($defaults) {
  $meta_number = 0;
  $custom_meta_fields = mysite_custom_define();
  foreach ($custom_meta_fields as $meta_field_name => $meta_disp_name) {
    $meta_number++;
    $defaults[('mysite-usercolumn-' . $meta_number . '')] = __($meta_disp_name, 'user-column');
  }
  return $defaults;
}

function mysite_custom_columns($value, $column_name, $id) {
  $meta_number = 0;
  $custom_meta_fields = mysite_custom_define();
  foreach ($custom_meta_fields as $meta_field_name => $meta_disp_name) {
    $meta_number++;
    if( $column_name == ('mysite-usercolumn-' . $meta_number . '') ) {
      return get_the_author_meta($meta_field_name, $id );
    }
  }
}
function mysite_show_extra_profile_fields($user) {
  print('<h3>PFS-Display User Meta Data</h3>');

  print('<table class="form-table">');

  $meta_number = 0;
  $custom_meta_fields = mysite_custom_define();
  foreach ($custom_meta_fields as $meta_field_name => $meta_disp_name) {
    $meta_number++;
    print('<tr>');
    print('<th><label for="' . $meta_field_name . '">' . $meta_disp_name . '</label></th>');
    print('<td>');
    print('<input type="text" name="' . $meta_field_name . '" id="' . $meta_field_name . '" value="' . esc_attr( get_the_author_meta($meta_field_name, $user->ID ) ) . '" class="regular-text" /><br />');
    print('<span class="description"></span>');
    print('</td>');
    print('</tr>');
  }
  print('</table>');
}
function mysite_save_extra_profile_fields($user_id) {

  if (!current_user_can('edit_user', $user_id))
    return false;

  $meta_number = 0;
  $custom_meta_fields = mysite_custom_define();
  foreach ($custom_meta_fields as $meta_field_name => $meta_disp_name) {
    $meta_number++;
    update_user_meta( $user_id, $meta_field_name, $_POST[$meta_field_name] );
  }
}
add_action('show_user_profile', 'mysite_show_extra_profile_fields');
add_action('edit_user_profile', 'mysite_show_extra_profile_fields');
add_action('personal_options_update', 'mysite_save_extra_profile_fields');
add_action('edit_user_profile_update', 'mysite_save_extra_profile_fields');
add_action('manage_users_custom_column', 'mysite_custom_columns', 15, 3);
add_filter('manage_users_columns', 'mysite_columns', 15, 1);   

add_filter('mpcs_classroom_style_handles', function($allowed_handles) {
	global $wp_styles;
	
	foreach($wp_styles->queue as $style) {
		$allowed_handles[] = $wp_styles->registered[$style]->handle;
	}
	
	return $allowed_handles;
});
