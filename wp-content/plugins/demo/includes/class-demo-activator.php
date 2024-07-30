<?php
namespace Demo;

class Activator {
    static function activate() {
        global $wpdb;

        $orgs_table = $wpdb->prefix . 'demo_organisations';
        $wpdb->query("
            CREATE TABLE $orgs_table (
                id int NOT NULL AUTO_INCREMENT,
                orgname varchar(255) NOT NULL,
                UNIQUE (orgname),
                PRIMARY KEY (id)
            )
        ");

        $relations_table = $wpdb->prefix . 'demo_relations';
        $wpdb->query("
            CREATE TABLE $relations_table (
                id int NOT NULL AUTO_INCREMENT,
                parent int NOT NULL,
                child  int NOT NULL,
                FOREIGN KEY (parent) REFERENCES $orgs_table(id),
                FOREIGN KEY (child) REFERENCES $orgs_table(id),
                PRIMARY KEY (id)
            )
        ");
    }
}