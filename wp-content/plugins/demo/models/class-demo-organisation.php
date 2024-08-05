<?php
namespace Demo\Model;

class Organisation {
    private string $org_name;
    private int|null $org_id;

    public function __construct($org_name, $service) {
        $this->org_name = $org_name;
        $this->org_id = $service->getOrgId($this->org_name);
    }

    public function getOrgName(): string {
        return $this->org_name;
    }

    public function getOrgId(): int|null {
        return $this->org_id;
    }

    public function setOrgId(int $org_id): void {
        $this->org_id = $org_id;
    }
}