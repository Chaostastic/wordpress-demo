<?php
namespace Demo\Service;

class OrganisationsService {
    private mixed $wpdb;
    private string $orgs_table;
    private string $relations_table;

    function __construct() {
        $this->wpdb = $GLOBALS['wpdb'];
        $this->orgs_table = $this->wpdb->prefix . 'demo_organisations';
        $this->relations_table = $this->wpdb->prefix . 'demo_relations';
    }

    function add_org($org_name) {
        $this->wpdb->insert($this->orgs_table, array('orgname' => $org_name));
        return $this->wpdb->insert_id;
    }

    function remove_org($org_id): void {
        $this->wpdb->delete($this->orgs_table, array('id' => $org_id));
    }

    function add_relation($parent_id, $org_id): void {
        $this->wpdb->insert($this->relations_table, array('parent' => $parent_id, 'child' => $org_id));
    }

    function remove_relations($org_id): void {
        $this->wpdb->query("
            DELETE FROM $this->relations_table
            WHERE parent = $org_id OR child = $org_id
        ");
    }

    function get_org_id($org_name) {
        return $this->wpdb->get_var("SELECT id FROM $this->orgs_table WHERE orgname = '$org_name'");
    }

    function get_parents($org_id) {
        return $this->wpdb->get_col("
            SELECT $this->orgs_table.orgname
            FROM $this->relations_table
            INNER JOIN $this->orgs_table ON $this->relations_table.parent=$this->orgs_table.id
            WHERE child = '$org_id'
        ");
    }

    function get_children($org_id) {
        return $this->wpdb->get_col("
            SELECT $this->orgs_table.orgname
            FROM $this->relations_table
            INNER JOIN $this->orgs_table ON $this->relations_table.child=$this->orgs_table.id
            WHERE parent = '$org_id'
        ");
    }

    function get_sisters($org_id) {
        return $this->wpdb->get_col("
            SELECT DISTINCT $this->orgs_table.orgname
            FROM $this->relations_table
            INNER JOIN $this->orgs_table ON $this->relations_table.child=$this->orgs_table.id
            WHERE parent IN (SELECT parent FROM $this->relations_table WHERE child = '$org_id') AND NOT child = '$org_id'; 
        ");
    }
    function create_tables(): void {
        $this->wpdb->query("
            CREATE TABLE $this->orgs_table (
                id int NOT NULL AUTO_INCREMENT,
                orgname varchar(255) NOT NULL,
                UNIQUE (orgname),
                PRIMARY KEY (id)
            )
        ");
        $this->wpdb->query("
            CREATE TABLE $this->relations_table (
                id int NOT NULL AUTO_INCREMENT,
                parent int NOT NULL,
                child  int NOT NULL,
                FOREIGN KEY (parent) REFERENCES $this->orgs_table(id) ON DELETE CASCADE,
                FOREIGN KEY (child) REFERENCES $this->orgs_table(id) ON DELETE CASCADE,
                PRIMARY KEY (id)
            )
        ");
    }

    function drop_tables(): void {
        $this->wpdb->query("DROP TABLE $this->relations_table");
        $this->wpdb->query("DROP TABLE $this->orgs_table");
    }
}