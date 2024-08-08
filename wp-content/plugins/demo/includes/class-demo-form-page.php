<?php
namespace Demo;

class FormPage {
    public function load(): void {
        $id = 18;
        if (is_page($id)) {
            echo 'Hello World!';
        }
    }
}