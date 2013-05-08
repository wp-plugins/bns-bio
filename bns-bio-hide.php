<?php
/*
Plugin Name: BNS Bio Hide
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An extension plugin included with BNS Bio to hide the email address
Version: 0.3.1
Text Domain: bns-bio-hide
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio List
 * An extension plugin included with BNS Bio to hide the email address.
 *
 * @package     BNS_Bio
 * @subpackage  BNS_Bio_Hide
 * @link        http://buynowshop.com/plugins/bns-bio/
 * @link        https://github.com/Cais/bns-bio/
 * @link        http://wordpress.org/extend/plugins/bns-bio/
 * @version     0.2
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2012-2013, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @version 0.2
 * @date    November 19, 2012
 * Change sanity check to self-deactivate if 'BNS Bio' is not active
 *
 * @version 0.3
 * @date    February 11, 2013
 * Version matching to BNS-Bio
 */

/**
 * Enqueue Plugin Scripts and Styles
 * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
 *
 * @subpackage  BNS_Bio_Hide
 * @since       0.1
 *
 * @uses        plugin_dir_path
 * @uses        plugin_dir_url
 * @uses        wp_enqueue_style
 */
function BNS_Bio_Hide_Scripts_and_Styles() {

    /** Get the plugin data */
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $bns_bio_hide_data = get_plugin_data( __FILE__ );

    /** Enqueue Styles */
    wp_enqueue_style( 'BNS-Bio-Hide-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-hide-style.css', array(), $bns_bio_hide_data['Version'], 'screen' );
    /** Check if custom stylesheet is readable (exists) */
    if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-bio-hide-custom-style.css' ) ) {
        wp_enqueue_style( 'BNS-Bio-Hide-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-hide-custom-style.css', array(), $bns_bio_hide_data['Version'], 'screen' );
    }

}
add_action( 'wp_enqueue_scripts', 'BNS_Bio_Hide_Scripts_and_Styles' );

/** @var $bns_bio_plugin_directory - define plugin directory name dynamically */
$bns_bio_plugin_directory = basename( dirname ( __FILE__ ) );
/** Sanity check - is the plugin active? */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( $bns_bio_plugin_directory . '/bns-bio.php' ) ) {

    /** Remove the list item (if it exists) */
    function bns_bio_remove_list_item() {
        remove_action( 'bns_bio_before_author_email', 'bns_bio_list_item' );
    }
    add_action( 'init', 'bns_bio_remove_list_item' );

    /** Return null strings to the filters clearing existing content */
    add_filter( 'bns_bio_author_email_text', '__return_null', 100 );
    add_filter( 'bns_bio_author_email', '__return_null', 100 );

} else {

    /** If 'BNS Bio' is not active then self-deactivate 'BNS Bio Hide' */
    deactivate_plugins( $bns_bio_plugin_directory . '/bns-bio-hide.php' );

}