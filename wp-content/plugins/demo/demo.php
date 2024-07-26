<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DemoPlugin')) {
	class DemoPlugin {
		public function __construct() {
			echo 'Hello World!';
		}
	}

	new DemoPlugin();
}