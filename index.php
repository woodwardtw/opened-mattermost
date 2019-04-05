<?php 
/*
Plugin Name: OpenEd to Mattermost
Plugin URI:  https://github.com/
Description: For sending gravity forms messages to Mattermost - reliant on a mattermost hook and the ID of the form defined in the code.
Version:     1.0
Author:      Tom Woodward
Author URI:  http://bionicteaching.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


//only form #2 bc of _2
add_action( 'gform_after_submission_2', 'send_mattermost_message', 10, 2 );

function send_mattermost_message($entry, $form){
	$help_name = rgar( $entry, '1' );
	$help_email = rgar( $entry, '2' );
	$help_message = rgar( $entry, '3' );
	$help_url = rgar( $entry, '4' );
//getting fancy with a table
	$content = json_encode('| Submitted Data| Responses  |
| :------------ |:---------------|
| **From:**     | '.$help_name.'|
| **Email:**    | ' . $help_email . '|
| **Message:**  | ' . $help_message . '|
| **URL:**      | [' . $help_url . '](' . $help_url . ')|');
	var_dump($content);
	$data_string = '{"text": '. $content . ' }';
	$ch = curl_init(MM_CHANNEL);// see variable in wp-config.php
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',                                              
	    'Content-Length: ' . strlen($data_string))                                          
	);                                                                                                                                                                                                        
	$result = curl_exec($ch);
	//var_dump($result);
}