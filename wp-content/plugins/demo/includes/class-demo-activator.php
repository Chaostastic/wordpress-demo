<?php
namespace Demo;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';

class Activator {
    public function activate(): void {
        $model = new OrganisationsService();
        $model->create_tables();
    }
}