<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PosSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insert([
            'username' => 'admin',
            'full_name' => 'System Administrator',
            'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'avatar' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->db->table('products')->insertBatch([
            [
                'name' => 'Mechanical Keyboard',
                'price' => 2499.00,
                'stock_quantity' => 15,
                'image' => null,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Wireless Mouse',
                'price' => 899.00,
                'stock_quantity' => 25,
                'image' => null,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'USB-C Hub',
                'price' => 1299.00,
                'stock_quantity' => 10,
                'image' => null,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->db->table('customers')->insertBatch([
            [
                'full_name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'phone' => '09171234567',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'phone' => '09181234567',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
