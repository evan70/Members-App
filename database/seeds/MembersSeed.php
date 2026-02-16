<?php
/**
 * Members Seed
 * 
 * Seeds the members table with test data.
 */
class MembersSeed extends Seed {
    
    public function run(): void {
        echo "Seeding members...\n";
        
        // Clear only members, keep trongate_users
        $this->truncate('members');
        
        // Hash for password 'password123'
        $password = password_hash('password123', PASSWORD_BCRYPT);
        
        // Get the current max trongate_user_id to continue from there
        $result = $this->db->query("SELECT MAX(id) as max_id FROM trongate_users", 'array');
        $current_max = !empty($result) && isset($result[0]['max_id']) ? (int)$result[0]['max_id'] : 0;
        
        // Create members data
        $members = [
            [
                'username' => 'evan70',
                'email' => 'evan@example.com',
                'email_address' => 'evan@example.com',
                'password' => $password,
                'confirmed' => 1,
                'num_logins' => 0,
                'user_level_id' => 2
            ],
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'email_address' => 'john@example.com',
                'password' => $password,
                'confirmed' => 1,
                'num_logins' => 0,
                'user_level_id' => 2
            ],
            [
                'username' => 'jane_smith', 
                'email' => 'jane@example.com',
                'email_address' => 'jane@example.com',
                'password' => $password,
                'confirmed' => 1,
                'num_logins' => 0,
                'user_level_id' => 2
            ],
            [
                'username' => 'bob_wilson',
                'email' => 'bob@example.com',
                'email_address' => 'bob@example.com',
                'password' => $password,
                'confirmed' => 1,
                'num_logins' => 0,
                'user_level_id' => 2
            ]
        ];
        
        // Insert trongate_users first and track their IDs
        $trongate_user_ids = [];
        foreach ($members as $m) {
            $tg_id = $this->insert('trongate_users', [
                'code' => $this->generate_code(),
                'user_level_id' => $m['user_level_id'],
                'username' => $m['username'],
                'password' => $m['password'],
                'email' => $m['email'],
                'active' => 1
            ]);
            $trongate_user_ids[] = $tg_id;
        }
        
        // Then insert members with correct trongate_user_id
        $member_data = [];
        foreach ($members as $index => $m) {
            $member_data[] = [
                'username' => $m['username'],
                'email' => $m['email'],
                'email_address' => $m['email_address'],
                'password' => $m['password'],
                'confirmed' => $m['confirmed'],
                'num_logins' => $m['num_logins'],
                'trongate_user_id' => $trongate_user_ids[$index]
            ];
        }
        
        $this->insert_batch('members', $member_data);
        echo "Created " . count($members) . " members\n";
    }
    
    private function generate_code(): string {
        return bin2hex(random_bytes(16));
    }
}
