<?php
/**
 * Members Seed
 * 
 * Seeds the members table with test data.
 */
class MembersSeed extends Seed {
    
    public function run(): void {
        echo "Seeding members...\n";
        
        // Clear existing data
        $this->truncate('members');
        
        // Hash for password 'password123'
        $password = password_hash('password123', PASSWORD_BCRYPT);
        
        // Insert test members
        $members = [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'email_address' => 'john@example.com',
                'password' => $password
            ],
            [
                'username' => 'jane_smith', 
                'email' => 'jane@example.com',
                'email_address' => 'jane@example.com',
                'password' => $password
            ],
            [
                'username' => 'bob_wilson',
                'email' => 'bob@example.com',
                'email_address' => 'bob@example.com',
                'password' => $password
            ]
        ];
        
        $this->insert_batch('members', $members);
        echo "Created " . count($members) . " members\n";
    }
}
