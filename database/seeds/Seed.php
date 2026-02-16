<?php
/**
 * Base Seed Class
 * 
 * All database seeds should extend this class.
 */
class Seed {
    protected $db;
    
    public function __construct() {
        $this->db = new Db();
    }
    
    /**
     * Run the seed
     * Override this in child classes
     */
    public function run(): void {
        // Override in child class
    }
    
    /**
     * Truncate a table before seeding
     */
    protected function truncate(string $table): void {
        $this->db->query("DELETE FROM $table");
    }
    
    /**
     * Insert a single record
     */
    protected function insert(string $table, array $data): int {
        return $this->db->insert($data, $table);
    }
    
    /**
     * Insert multiple records
     */
    protected function insert_batch(string $table, array $records): int {
        return $this->db->insert_batch($records, $table);
    }
}
