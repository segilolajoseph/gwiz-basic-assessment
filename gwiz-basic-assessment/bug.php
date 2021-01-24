<?php
/**
 * Plugin Name: GP Nested Forms
 * Description: Create forms within forms for better management of complex forms. Formception!
 * Plugin URI: https://gravitywiz.com/documentation/gravity-forms-nested-forms/
 * Version: 1.0-beta-9.5
 * Author: Gravity Wiz
 * Author URI: https://gravitywiz.com
 * License: GPL2
 * Perk: True
 * Text Domain: gp-nested-forms
 * Domain Path: /languages
 */


add_action('gform_after_submission_1', 'remove_form_entry', 10, 2);
function remove_form_entry($entry, $form){
    global $wpdb;
 
    $lead_id = $entry['id'];
    $lead_table = RGFormsModel::get_lead_table_name();
    $lead_notes_table = RGFormsModel::get_lead_notes_table_name();
    $lead_detail_table = RGFormsModel::get_lead_details_table_name();
    $lead_detail_long_table = RGFormsModel::get_lead_details_long_table_name();
 
    //Delete from detail long
    $sql = $wpdb->prepare(" DELETE FROM $lead_detail_long_table
                            WHERE lead_detail_id IN(
                                SELECT id FROM $lead_detail_table WHERE lead_id=%d
                            )", $lead_id);
    $wpdb->query($sql);
 
    //Delete from lead details
    $sql = $wpdb->prepare("DELETE FROM $lead_detail_table WHERE lead_id=%d", $lead_id);
    $wpdb->query($sql);
 
    //Delete from lead notes
    $sql = $wpdb->prepare("DELETE FROM $lead_notes_table WHERE lead_id=%d", $lead_id);
    $wpdb->query($sql);
 
    //Delete from lead
    $sql = $wpdb->prepare("DELETE FROM $lead_table WHERE id=%d", $lead_id);
    $wpdb->query($sql);
 
}

