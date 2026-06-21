<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::query()
            ->where('email', 'admin@example.com')
            ->orWhere('username', 'admin')
            ->firstOrNew();

        $admin->fill([
            'name' => 'Administrateur',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ])->save();

        collect([
            ['name' => 'Sara Client', 'email' => 'sara@example.com'],
            ['name' => 'Youssef Client', 'email' => 'youssef@example.com'],
        ])->each(function (array $customer): void {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'username' => User::generateUsernameFromEmail($customer['email']),
                    'role' => 'customer',
                    'password' => Hash::make('password'),
                ]
            );
        });

        $categories = collect([
            ['name' => 'Soins visage', 'description' => 'Cremes, nettoyants et serums.'],
            ['name' => 'Hygiene', 'description' => 'Produits d hygiene quotidienne.'],
            ['name' => 'Bebe', 'description' => 'Produits pour nourrissons et enfants.'],
            ['name' => 'Complements', 'description' => 'Vitamines et complements alimentaires.'],
            ['name' => 'Solaire', 'description' => 'Protection et soins apres soleil.'],
        ])->mapWithKeys(fn (array $data) => [$data['name'] => Category::updateOrCreate(['name' => $data['name']], $data)]);

        $suppliers = collect([
            ['name' => 'Para Distribution', 'phone' => '0522000001', 'email' => 'contact@para-distribution.local', 'address' => 'Casablanca'],
            ['name' => 'Sante Plus Maroc', 'phone' => '0522000002', 'email' => 'vente@santeplus.local', 'address' => 'Rabat'],
            ['name' => 'Bio Care Supply', 'phone' => '0522000003', 'email' => 'commande@biocare.local', 'address' => 'Marrakech'],
        ])->mapWithKeys(fn (array $data) => [$data['name'] => Supplier::updateOrCreate(['email' => $data['email']], $data)]);

        $productsData = [
            ['code' => 'PV-002', 'name' => 'Gel nettoyant doux', 'category' => 'Soins visage', 'supplier' => 'Para Distribution', 'purchase_price' => 38, 'sale_price' => 55, 'minimum_threshold' => 10, 'expiration_date' => now()->addDays(20), 'description' => 'Nettoyant visage quotidien.', 'stock' => 7],
            ['code' => 'HY-001', 'name' => 'Shampooing dermatologique', 'category' => 'Hygiene', 'supplier' => 'Sante Plus Maroc', 'purchase_price' => 52, 'sale_price' => 78, 'minimum_threshold' => 6, 'expiration_date' => now()->addYear(), 'description' => 'Shampooing cuir chevelu sensible.', 'stock' => 18],
            ['code' => 'HY-002', 'name' => 'Gel hydroalcoolique 500ml', 'category' => 'Hygiene', 'supplier' => 'Bio Care Supply', 'purchase_price' => 18, 'sale_price' => 29, 'minimum_threshold' => 12, 'expiration_date' => now()->subDays(10), 'description' => 'Solution hydroalcoolique.', 'stock' => 0],
            ['code' => 'BB-001', 'name' => 'Lingettes bebe hypoallergeniques', 'category' => 'Bebe', 'supplier' => 'Sante Plus Maroc', 'purchase_price' => 22, 'sale_price' => 35, 'minimum_threshold' => 15, 'expiration_date' => now()->addMonths(6), 'description' => 'Paquet de lingettes bebe.', 'stock' => 30],
            ['code' => 'CO-001', 'name' => 'Vitamine C 1000mg', 'category' => 'Complements', 'supplier' => 'Bio Care Supply', 'purchase_price' => 60, 'sale_price' => 95, 'minimum_threshold' => 8, 'expiration_date' => now()->addDays(12), 'description' => 'Boite de 20 comprimes.', 'stock' => 5],
        ];

        foreach ($productsData as $data) {
            $stock = $data['stock'];
            unset($data['stock']);

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'category_id' => $categories[$data['category']]->id,
                    'supplier_id' => $suppliers[$data['supplier']]->id,
                    'purchase_price' => $data['purchase_price'],
                    'sale_price' => $data['sale_price'],
                    'stock_quantity' => $stock,
                    'minimum_threshold' => $data['minimum_threshold'],
                    'expiration_date' => $data['expiration_date'],
                    'description' => $data['description'],
                    'main_image' => null,
                ]
            );

            StockEntry::updateOrCreate(
                ['product_id' => $product->id, 'entry_date' => now()->subDays(12)->toDateString()],
                [
                    'supplier_id' => $product->supplier_id,
                    'quantity' => max($stock, 10),
                    'purchase_price' => $product->purchase_price,
                ]
            );
        }

        $this->call([
            CatalogTranslationsSeeder::class,
            FixArabicTranslationsSeeder::class,
            AdminDashboardDemoDataSeeder::class,
        ]);
    }
}
