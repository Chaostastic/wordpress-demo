<?php
namespace Demo;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';

class Activator {
    public function activate(): void {
        $model = new OrganisationsService();
        $model->createTables();

        $id = wp_insert_post(array(
            'post_title'    => wp_strip_all_tags( 'Form' ),
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
        ));
    }
}