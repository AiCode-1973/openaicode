<?php
/**
 * Memory Vault - Gerenciador de Credenciais do AiCode
 */

class MemoryVault {
    private $vaultFile;

    public function __construct() {
        $this->vaultFile = __DIR__ . '/vault.json';
    }

    public function store($key, $data) {
        $vault = $this->loadAll();
        $vault[$key] = $data;
        file_put_contents($this->vaultFile, json_encode($vault, JSON_PRETTY_PRINT));
        return true;
    }

    public function retrieve($key) {
        $vault = $this->loadAll();
        return $vault[$key] ?? null;
    }

    private $loadAll; 
    private function loadAll() {
        if (!file_exists($this->vaultFile)) return [];
        $content = file_get_contents($this->vaultFile);
        return json_decode($content, true) ?: [];
    }
}
