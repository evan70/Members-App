<?php
/**
 * Routes Seed
 * 
 * Seeds the trongate_routes table with default routes.
 */
class RoutesSeed extends Seed {
    
    public function run(): void {
        echo "Seeding routes...\n";
        
        // Check if table exists
        if (!$this->db->table_exists('trongate_routes')) {
            echo "Table trongate_routes does not exist, skipping...\n";
            return;
        }
        
        // Clear existing routes
        $this->truncate('trongate_routes');
        
        // Default routes
        $routes = [
            [
                'route_pattern' => 'members-login',
                'destination' => 'members-login',
                'method' => 'GET',
                'priority' => 10,
                'active' => 1
            ],
            [
                'route_pattern' => 'join',
                'destination' => 'join', 
                'method' => 'GET',
                'priority' => 10,
                'active' => 1
            ],
            [
                'route_pattern' => 'dashboard',
                'destination' => 'dashboard',
                'method' => 'GET', 
                'priority' => 10,
                'active' => 1
            ],
            [
                'route_pattern' => 'trongate-administrators',
                'destination' => 'trongate_administrators',
                'method' => 'GET',
                'priority' => 10,
                'active' => 1
            ]
        ];
        
        $this->insert_batch('trongate_routes', $routes);
        echo "Created " . count($routes) . " routes\n";
    }
}
