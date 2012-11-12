<?php
/*
Plugin Name: BNS Bio List
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An extension plugin included with BNS Bio to output the layout in an unordered list
Version: 0.1
Text Domain: bns-bio-list
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio List
 * An extension plugin included with BNS Bio to output the layout in an
 * unordered list.
 *
 * @package     BNS_Bio
 * @subpackage  BNS_Bio_List
 * @link        http://buynowshop.com/plugins/bns-bio/
 * @link        https://github.com/Cais/bns-bio/
 * @link        http://wordpress.org/extend/plugins/bns-bio/
 * @version     0.1
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2012, Edward Caissie
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
 */

/**
 * Enqueue Plugin Scripts and Styles
 * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
 *
 * @subpackage  BNS_Bio_List
 * @since       0.1
 *
 * @uses        plugin_dir_path
 * @uses        plugin_dir_url
 * @uses        wp_enqueue_style
 */
function BNS_Bio_List_Scripts_and_Styles() {

    /** Get the plugin data */
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $bns_bio_list_data = get_plugin_data( __FILE__ );

    /** Enqueue Styles */
    wp_enqueue_style( 'BNS-Bio-List-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-list-style.css', array(), $bns_bio_list_data['Version'], 'screen' );
    /** Check if custom stylesheet is readable (exists) */
    if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-bio-list-custom-style.css' ) ) {
        wp_enqueue_style( 'BNS-Bio-List-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-list-custom-style.css', array(), $bns_bio_list_data['Version'], 'screen' );
    }

}
add_action( 'wp_enqueue_scripts', 'BNS_Bio_List_Scripts_and_Styles' );

/** @var $bns_bio_plugin_directory - define plugin directory name dynamically */
$bns_bio_plugin_directory = basename( dirname ( __FILE__ ) );
/** Sanity check - is the plugin active? */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( $bns_bio_plugin_directory . '/bns-bio.php' ) ) {

    /**
     * Add additional actions to change the layout
     * Set priority higher than default (read: action fires later than default)
     */
    add_action( 'bns_bio_before_all', function(){ echo '<ul class="bns-bio-list">'; }, 20 );
    /** Set priority to fire earlier than 'BNS-Bio-Box' (at default 10) to insure output will validate */
    add_action( 'bns_bio_after_all', function(){ echo '</ul><!-- .bns-bio-list -->'; }, 9 );

    /** Open an `li` tag */
    function bns_bio_list_item() {
        echo '<li class="bns-bio-list-item">';
    }

    /**
     * Change author details to use `li` tag rather than `span`
     * @internal NOTE: HTML5 automatically closes the `li` tag before starting
     * a new one ... make use of this here
     * @todo Review this at a later date
     */
    add_action( 'bns_bio_before_author_name', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_url', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_email', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_desc', 'bns_bio_list_item' );

} else {

    /** @var $exit_message string - Message to display if 'BNS Bio' is not activated */
    $exit_message = __( 'BNS Bio List requires the BNS Bio Plugin to be activated first.', 'bns-bio-list' );
    exit ( $exit_message );

}