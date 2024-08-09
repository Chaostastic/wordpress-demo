<?php
namespace Demo;
require_once DEMO_PLUGIN_DIR . 'view/class-demo-form-page.php';

class Pages {
    public function load(): void {
        if (is_page(18)) {
            (new Pages\FormPage())->loadHtml();
        }
    }
}