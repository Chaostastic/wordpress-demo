<?php
namespace Demo;

class DeActivator {
    static function de_activate() {
        global $wpdb;
        $orgs_table = $wpdb->prefix . 'demo_organisations';
        $relations_table = $wpdb->prefix . 'demo_relations';
        $wpdb->query("DROP TABLE $relations_table");
        $wpdb->query("DROP TABLE $orgs_table");
    }
}