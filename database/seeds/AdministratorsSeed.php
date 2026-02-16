<?php
/**
 * Administrators Seed
 * 
 * Seeds the trongate_administrators table with test data.
 */
class AdministratorsSeed extends Seed {
    
    public function run(): void {
        echo "Seeding administrators...\n";
        
        // Hash for password 'admin123'
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        
        // Create trongate_users first
        $tg_ids = [];
        
        // Admin user
        $tg_ids[] = $this->insert('trongate_users', [
            'code' => $this->generate_code(),
            'user_level_id' => 1,
            'username' => 'admin',
            'password' => $password,
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'active' => 1
        ]);
        
        // Superadmin user
        $tg_ids[] = $this->insert('trongate_users', [
            'code' => $this->generate_code(),
            'user_level_id' => 1,
            'username' => 'superadmin',
            'password' => $password,
            'email' => 'superadmin@example.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'active' => 1
        ]);
        
        // Clear existing and insert administrators with links
        $this->truncate('trongate_administrators');
        
        // Insert admin
        $this->insert('trongate_administrators', [
            'user_level_id' => 1,
            'trongate_user_id' => $tg_ids[0],
            'username' => 'admin',
            'password' => $password,
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'active' => 1
        ]);
        
        // Insert superadmin
        $this->insert('trongate_administrators', [
            'user_level_id' => 1,
            'trongate_user_id' => $tg_ids[1],
            'username' => 'superadmin',
            'password' => $password,
            'email' => 'superadmin@example.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'active' => 1
        ]);
        
        echo "Created admin and superadmin users (password: admin123)\n";
    }
    
    private function generate_code(): string {
        return bin2hex(random_bytes(16));
    }
}
