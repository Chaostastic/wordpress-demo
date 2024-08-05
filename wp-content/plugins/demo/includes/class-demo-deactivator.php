<?php
namespace Demo;
use Demo\Service\OrganisationsService;
require_once DEMO_PLUGIN_DIR . 'services/class-demo-organisations-service.php';

class DeActivator {
    public function deactivate(): void {
        $model = new OrganisationsService();
        $model->dropTables();
    }
}