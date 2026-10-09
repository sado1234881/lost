<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Claim;
use App\Models\Notification;
use App\Models\RecoveryHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (Admin, Staff, Student)
        $admin = User::create([
            'name' => 'Security Admin',
            'email' => 'admin@sjut.ac.tz',
            'registration_number' => 'ADMIN/SJUT/001',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '+255 700 000 001',
        ]);

        $staff = User::create([
            'name' => 'Dr. Emmanuel Mvungi',
            'email' => 'staff@sjut.ac.tz',
            'registration_number' => 'STAFF/SJUT/042',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone_number' => '+255 788 112 233',
        ]);

        $student1 = User::create([
            'name' => 'Erick Peter',
            'email' => 'student@sjut.ac.tz',
            'registration_number' => '2023/SJUT/0142',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone_number' => '+255 712 345 678',
        ]);

        $student2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@sjut.ac.tz',
            'registration_number' => '2023/SJUT/0188',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone_number' => '+255 754 987 654',
        ]);

        // 2. Create Categories
        $catPhones = Category::create([
            'category_name' => 'Phones',
            'icon' => 'fa-mobile-screen-button',
            'description' => 'Mobile smartphones, feature phones, and handheld accessories.',
        ]);

        $catLaptops = Category::create([
            'category_name' => 'Laptops',
            'icon' => 'fa-laptop',
            'description' => 'Notebook computers, MacBooks, tablets, and chargers.',
        ]);

        $catBags = Category::create([
            'category_name' => 'Bags',
            'icon' => 'fa-bag-shopping',
            'description' => 'Backpacks, handbags, briefcases, and luggage.',
        ]);

        $catBooks = Category::create([
            'category_name' => 'Books',
            'icon' => 'fa-book',
            'description' => 'Textbooks, research notebooks, library books, and binders.',
        ]);

        $catID = Category::create([
            'category_name' => 'ID Cards',
            'icon' => 'fa-id-card',
            'description' => 'University student ID cards, Staff ID cards, National IDs, and licenses.',
        ]);

        $catKeys = Category::create([
            'category_name' => 'Keys',
            'icon' => 'fa-key',
            'description' => 'Hostel room keys, office keys, vehicle keys, and keyrings.',
        ]);

        $catWallets = Category::create([
            'category_name' => 'Wallets',
            'icon' => 'fa-wallet',
            'description' => 'Wallets, purses, and money pouches.',
        ]);

        $catCalc = Category::create([
            'category_name' => 'Calculators',
            'icon' => 'fa-calculator',
            'description' => 'Scientific calculators (Casio, HP) and lab equipment.',
        ]);

        $catFlash = Category::create([
            'category_name' => 'Flash Drives',
            'icon' => 'fa-hard-drive',
            'description' => 'USB flash drives, external hard drives, and memory cards.',
        ]);

        // 3. Create Sample Lost Items
        $lost1 = LostItem::create([
            'user_id' => $staff->id,
            'category_id' => $catLaptops->id,
            'item_name' => 'Dell Latitude Staff Laptop (Black)',
            'color' => 'Black',
            'description' => 'Black Dell Latitude 14-inch laptop with SJUT Staff Asset Tag #ST-889.',
            'date_lost' => '2026-07-28',
            'location_lost' => 'Staff Common Room / Faculty Office Block B',
            'status' => 'active',
        ]);

        $lost2 = LostItem::create([
            'user_id' => $student2->id,
            'category_id' => $catID->id,
            'item_name' => 'Student Identity Card (Sarah Johnson)',
            'color' => 'Blue/White',
            'description' => 'Official SJUT Student ID card bearing registration 2023/SJUT/0188.',
            'date_lost' => '2026-07-29',
            'location_lost' => 'Cafeteria / Student Center Annex',
            'status' => 'active',
        ]);

        $lost3 = LostItem::create([
            'user_id' => $student1->id,
            'category_id' => $catCalc->id,
            'item_name' => 'Casio fx-991EX Scientific Calculator',
            'color' => 'Black',
            'description' => 'Black Casio scientific calculator with name initial E.P engraved on back.',
            'date_lost' => '2026-07-25',
            'location_lost' => 'Science Block LT-2',
            'status' => 'recovered',
        ]);

        // 4. Create Sample Found Items
        $found1 = FoundItem::create([
            'user_id' => $admin->id,
            'category_id' => $catPhones->id,
            'item_name' => 'Samsung Galaxy A32 (Black Case)',
            'color' => 'Black',
            'description' => 'Black Samsung smartphone with cracked screen protector in a clear rubber case.',
            'date_found' => '2026-07-29',
            'location_found' => 'Assembly Hall Main Entrance',
            'storage_location' => 'Security Desk Cabinet Shelf B-2',
            'status' => 'reported',
        ]);

        $found2 = FoundItem::create([
            'user_id' => $admin->id,
            'category_id' => $catID->id,
            'item_name' => 'Student ID Card - Sarah Johnson',
            'color' => 'Blue/White',
            'description' => 'Found on cafeteria table during noon cleanup.',
            'date_found' => '2026-07-29',
            'location_found' => 'Cafeteria Main Hall',
            'storage_location' => 'Security Admin Safe Box 1',
            'status' => 'claimed',
        ]);

        $found3 = FoundItem::create([
            'user_id' => $admin->id,
            'category_id' => $catKeys->id,
            'item_name' => 'Set of 3 Keys with Wooden Keyring',
            'color' => 'Brass/Silver',
            'description' => '3 keys on a round wooden keyring stamped SJUT Hostel Block C.',
            'date_found' => '2026-07-30',
            'location_found' => 'Hostel Block C Walkway',
            'storage_location' => 'Hostel Matron Office',
            'status' => 'reported',
        ]);

        // 5. Create Sample Claim
        $claim1 = Claim::create([
            'user_id' => $student2->id,
            'item_type' => 'found_item',
            'item_id' => $found2->id,
            'proof_of_ownership' => 'The ID card bears my full name Sarah Johnson and registration 2023/SJUT/0188. I also have my admission letter for photo verification.',
            'claim_date' => '2026-07-29',
            'claim_status' => 'approved',
            'qr_code_token' => 'CLAIM-' . Str::upper(Str::random(10)),
            'admin_notes' => 'Identity verified matching student registry.',
        ]);

        // 6. Create Notifications
        Notification::create([
            'user_id' => $student2->id,
            'title' => 'Ownership Claim Approved!',
            'message' => 'Your claim for Student ID Card - Sarah Johnson has been verified by Security Admin. Please view your QR release code.',
            'type' => 'claim',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $staff->id,
            'title' => 'Lost Item Report Registered',
            'message' => 'Your report for "Dell Latitude Staff Laptop (Black)" has been published to the Security Office index.',
            'type' => 'info',
            'is_read' => false,
        ]);
    }
}
