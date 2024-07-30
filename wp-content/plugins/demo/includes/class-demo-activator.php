<?php
namespace Demo;

class Activator {
    function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $orgs_name = $wpdb->prefix . 'demo_organisations';
        $orgs_sql = "CREATE TABLE $orgs_name (
              id int NOT NULL AUTO_INCREMENT,
              orgname varchar(255) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

        $relations_name = $wpdb->prefix . 'demo_relations';
        $relations_sql = "CREATE TABLE $relations_name (
              id int NOT NULL AUTO_INCREMENT,
              parent varchar(255) NOT NULL,
              child  varchar(255) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($orgs_sql);
        dbDelta($relations_sql);
    }
}