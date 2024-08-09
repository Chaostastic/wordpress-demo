<?php
namespace Demo;
use Demo\Service\OrganisationsService;
use Demo\Service\FormsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';
require_once DEMO_PLUGIN_DIR . 'services/class-demo-forms-service.php';

class DeActivator {
    public function deactivate(): void {
        (new OrganisationsService())->dropTables();
        (new FormsService())->dropTable();
    }
}