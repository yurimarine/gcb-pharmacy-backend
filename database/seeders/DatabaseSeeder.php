<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Generic;
use App\Models\Manufacturer;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Supplier;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'role' => 'admin'
            ]
        );

        Generic::firstOrCreate(
            ['name' => 'Paracetamol'],
            [
                'description' => 'Pain reliever and fever reducer'
            ]
        );

        Generic::firstOrCreate(
            ['name' => 'Ibuprofen'],
            [
                'description' => 'NSAID used to reduce pain, fever, and inflammation'
            ]
        );

        Generic::firstOrCreate(
            ['name' => 'Amoxicillin'],
            [
                'description' => 'Antibiotic used to treat bacterial infections'
            ]
        );

        Manufacturer::firstOrCreate(
            ['name' => 'Pfizer'],
            [
            'description' => 'Global biopharmaceutical company producing various medicines.'
            ]
        );

        Manufacturer::firstOrCreate(
            ['name' => 'Unilab'],
            [
            'description' => 'Leading pharmaceutical company in the Philippines.'
            ]
        );

        Manufacturer::firstOrCreate(
            ['name' => 'Johnson & Johnson'],
            [
            'description' => 'Multinational manufacturer of healthcare products and medicines.'
            ]
        );

        Pharmacy::firstOrCreate(
            ['email' => 'manager1@pharmacy.com'],
            [
                'name' => 'HealthCare Pharmacy',
                'manager' => 'Juan Dela Cruz',
                'phone' => '09171234567',
                'address' => '123 Main Street, Calamba, Laguna',
                'description' => 'Primary branch offering essential medicines and healthcare products.'
            ]
        );

        Pharmacy::firstOrCreate(
            ['email' => 'manager2@pharmacy.com'],
            [
                'name' => 'Wellness Drugstore',
                'manager' => 'Maria Santos',
                'phone' => '09281234567',
                'address' => '456 Market Road, San Pedro, Laguna',
                'description' => 'Drugstore focused on providing generic and branded medicines.'
            ]
        );

        Supplier::firstOrCreate(
            ['email' => 'contact@medisupply.com'],
            [
                'name' => 'MediSupply Co.',
                'contact_person' => 'Anna Cruz',
                'phone' => '09181234567',
                'address' => '12 Health St., Calamba, Laguna'
            ]
        );

        Supplier::firstOrCreate(
            ['email' => 'info@pharmasource.com'],
            [
                'name' => 'PharmaSource Inc.',
                'contact_person' => 'Ben Reyes',
                'phone' => '09291234567',
                'address' => '34 Wellness Rd., San Pedro, Laguna'
            ]
        );

        Supplier::firstOrCreate(
            ['email' => 'sales@globalmed.com'],
            [
                'name' => 'GlobalMed Suppliers',
                'contact_person' => 'Clara Santos',
                'phone' => '09381234567',
                'address' => '56 Medical Ave., Santa Rosa, Laguna'
            ]
        );


        Product::firstOrCreate(
            ['barcode' => '1234567890123'],
            [
                'generic_id' => 1, // Paracetamol
                'supplier_id' => 1,
                'manufacturer_id' => 1, // Pfizer
                'brand_name' => 'Paracetamol',
                'dosage_form' => 'Tablet',
                'dosage_amount' => '500mg',
                'packaging_type' => 'Box',
                'volume_amount' => 10,
                'volume_unit' => 'pcs',
                'unit_cost' => 5.50,
                'description' => 'Paracetamol tablets for fever and pain relief'
            ]
        );

        Product::firstOrCreate(
            ['barcode' => '2345678901234'],
            [
                'generic_id' => 2, // Ibuprofen
                'supplier_id' => 2,
                'manufacturer_id' => 2, // Unilab
                'brand_name' => 'Ibuprofen',
                'dosage_form' => 'Capsule',
                'dosage_amount' => '200mg',
                'packaging_type' => 'Box',
                'volume_amount' => 20,
                'volume_unit' => 'pcs',
                'unit_cost' => 8.75,
                'description' => 'Ibuprofen capsules for inflammation and pain relief'
            ]
        );

        Product::firstOrCreate(
            ['barcode' => '3456789012345'],
            [
                'generic_id' => 3, // Amoxicillin
                'supplier_id' => 3,
                'manufacturer_id' => 3, // Johnson & Johnson
                'brand_name' => 'Amoxicillin',
                'dosage_form' => 'Capsule',
                'dosage_amount' => '500mg',
                'packaging_type' => 'Bottle',
                'volume_amount' => 30,
                'volume_unit' => 'pcs',
                'unit_cost' => 15.00,
                'description' => 'Amoxicillin capsules for bacterial infections'
            ]
        );

        Inventory::firstOrCreate(
            ['product_id' => 1, 'pharmacy_id' => 1],
            [
                'stock_quantity' => 100,
                'reorder_quantity' => 20,
                'expiry_date' => '2026-12-31',
                'markup_percentage' => 20,
                'selling_price' => 6.60
            ]
        );

        Inventory::firstOrCreate(
            ['product_id' => 2, 'pharmacy_id' => 1],
            [
                'stock_quantity' => 80,
                'reorder_quantity' => 15,
                'expiry_date' => '2026-08-15',
                'markup_percentage' => 25,
                'selling_price' => 10.94
            ]
        );

        Inventory::firstOrCreate(
            ['product_id' => 3, 'pharmacy_id' => 2],
            [
                'stock_quantity' => 50,
                'reorder_quantity' => 10,
                'expiry_date' => '2026-05-30',
                'markup_percentage' => 30,
                'selling_price' => 19.50
            ]
        );

    }
}