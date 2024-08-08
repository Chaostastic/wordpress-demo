<?php
namespace Demo\Service;

class FormsService {
    private mixed $wpdb;
    private string $forms_table;

    public function __construct() {
        $this->wpdb = $GLOBALS['wpdb'];
        $this->forms_table = $this->wpdb->prefix . 'demo_forms';
    }

    public function addFrom($form) {
        $this->wpdb->insert($this->forms_table, $form);
        return $this->wpdb->insert_id;
    }

    public function createTable(): void {
        $this->wpdb->query("
            CREATE TABLE $this->forms_table (
                id int NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                email varchar(255) NOT NULL,
                phone int NOT NULL,
                created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )
        ");
    }

    public function dropTable(): void {
        $this->wpdb->query("DROP TABLE $this->forms_table");
    }
}