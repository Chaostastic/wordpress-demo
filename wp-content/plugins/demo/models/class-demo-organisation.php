<?php
namespace Demo\Model;

class Organisation {
    private string $org_name;
    private int $org_id;

    public function __construct($org_name, $service) {
        $this->org_name = $org_name;
        $this->org_id = $service->get_org_id($this->org_name);
    }

    public function get_org_name(): string {
        return $this->org_name;
    }

    public function get_org_id(): int {
        return $this->org_id;
    }

    public function set_org_id(int $org_id): void {
        $this->org_id = $org_id;
    }
}