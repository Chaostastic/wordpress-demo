<?php
namespace Demo;
use Demo\Routes\OrganisationsRoutes;
use Demo\Routes\FormRoutes;
require_once DEMO_PLUGIN_DIR . 'routes/class-demo-organisations-routes.php';
require_once DEMO_PLUGIN_DIR . 'routes/class-demo-form-routes.php';

class Routes {
    function registerRoutes(): void {
        new OrganisationsRoutes();
        new FormRoutes();
    }
}