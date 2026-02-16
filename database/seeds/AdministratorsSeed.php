<?php
/**
 * Administrators Seed
 * 
 * Seeds the trongate_administrators table with test data.
 */
class AdministratorsSeed extends Seed {
    
    public function run(): void {
        echo "Seeding administrators...\n";
        
        // Clear existing data (keep id 1 for admin)
        $this->db->query("DELETE FROM trongate_administrators WHERE id > 1");
        
        // Hash for password 'admin123'
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        
        // Insert test admin
        $this->insert('trongate_administrators', [
            'user_level_id' => 1,
            'username' => 'superadmin',
            'password' => $password,
            'email' => 'superadmin@example.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'active' => 1
        ]);
        
        echo "Created superadmin user (password: admin123)\n";
    }
}
