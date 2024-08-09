<?php
namespace Demo;
use Demo\Service\OrganisationsService;
use Demo\Service\FormsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';
require_once DEMO_PLUGIN_DIR . 'services/class-demo-forms-service.php';

class Activator {
    public function activate(): void {
        (new OrganisationsService())->createTables();
        (new FormsService())->createTable();
    }
}