<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParapharmacyDemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $bebeCategoryName = "B\u{00E9}b\u{00E9} & maman";
            $bebeCategoryDescription = "Produits essentiels pour accompagner les soins quotidiens des b\u{00E9}b\u{00E9}s et des mamans.";
            $complementsCategoryName = "Compl\u{00E9}ments alimentaires";
            $complementsCategoryDescription = "Compl\u{00E9}ments pour soutenir la vitalit\u{00E9}, l'\u{00E9}quilibre nutritionnel et le bien-\u{00EA}tre quotidien.";
            $hygieneCategoryName = "Hygi\u{00E8}ne & sant\u{00E9} quotidienne";
            $hygieneCategoryDescription = "Produits essentiels pour l'hygi\u{00E8}ne quotidienne et le maintien du bien-\u{00EA}tre jour apr\u{00E8}s jour.";
            $dermoCategoryName = "Dermocosm\u{00E9}tique";
            $dermoCategoryDescription = "Soins dermatologiques cibl\u{00E9}s pour les peaux sensibles, r\u{00E9}actives ou \u{00E0} imperfections.";
            $bioCategoryName = "Produits bio & naturels";
            $bioCategoryDescription = "S\u{00E9}lection de soins \u{00E0} base d'ingr\u{00E9}dients d'origine naturelle ou certifi\u{00E9}s bio.";
            $medicalCategoryName = "Mat\u{00E9}riel m\u{00E9}dical";
            $medicalCategoryDescription = "Appareils et accessoires pratiques pour le suivi de la sant\u{00E9} \u{00E0} domicile.";
            $sunCategoryName = "Protection solaire";
            $sunCategoryDescription = "Protections solaires haute tol\u{00E9}rance pour pr\u{00E9}server la peau au quotidien.";

            $categories = collect([
                [
                    'name' => $bebeCategoryName,
                    'description' => $bebeCategoryDescription,
                    'aliases' => ['Bebe', $bebeCategoryName],
                ],
                [
                    'name' => $complementsCategoryName,
                    'description' => $complementsCategoryDescription,
                    'aliases' => ['Complements', $complementsCategoryName],
                ],
                [
                    'name' => $hygieneCategoryName,
                    'description' => $hygieneCategoryDescription,
                    'aliases' => ['Hygiene', $hygieneCategoryName],
                ],
                [
                    'name' => $dermoCategoryName,
                    'description' => $dermoCategoryDescription,
                    'aliases' => [$dermoCategoryName],
                ],
                [
                    'name' => $bioCategoryName,
                    'description' => $bioCategoryDescription,
                    'aliases' => [$bioCategoryName],
                ],
                [
                    'name' => $medicalCategoryName,
                    'description' => $medicalCategoryDescription,
                    'aliases' => [$medicalCategoryName],
                ],
                [
                    'name' => $sunCategoryName,
                    'description' => $sunCategoryDescription,
                    'aliases' => ['Solaire', $sunCategoryName],
                ],
            ])->mapWithKeys(function (array $category): array {
                $record = Category::query()->whereIn('name', $category['aliases'])->first();

                if ($record) {
                    $record->update([
                        'name' => $category['name'],
                        'description' => $category['description'],
                    ]);
                } else {
                    $record = Category::create([
                        'name' => $category['name'],
                        'description' => $category['description'],
                    ]);
                }

                return [$category['name'] => $record];
            });

            $frontPlaceholder = 'images/product-placeholder.svg';
            $packagingPlaceholder = 'images/product-placeholder.svg';

            $products = [
                [
                    'code' => 'BBM-001',
                    'category' => $bebeCategoryName,
                    'name' => "Mustela Gel Lavant Doux B\u{00E9}b\u{00E9} 500ml",
                    'description' => "Gel lavant doux pour le corps et les cheveux des b\u{00E9}b\u{00E9}s, adapt\u{00E9} \u{00E0} un usage quotidien.",
                    'purchase_price' => 85,
                    'sale_price' => 120,
                    'stock_quantity' => 20,
                    'minimum_threshold' => 5,
                    'expiration_date' => '2027-12-31',
                ],
                [
                    'code' => 'BBM-002',
                    'category' => $bebeCategoryName,
                    'name' => 'Mitosyl Pommade Protectrice 145g',
                    'description' => "Pommade protectrice pour aider \u{00E0} pr\u{00E9}venir et apaiser les rougeurs du si\u{00E8}ge chez b\u{00E9}b\u{00E9}.",
                    'purchase_price' => 75,
                    'sale_price' => 105,
                    'stock_quantity' => 18,
                    'minimum_threshold' => 5,
                    'expiration_date' => '2027-12-31',
                ],
                [
                    'code' => 'COM-001',
                    'category' => $complementsCategoryName,
                    'name' => "Arkopharma Azinc Vitalit\u{00E9} Gummies",
                    'description' => "Compl\u{00E9}ment alimentaire sous forme de gummies pour aider \u{00E0} r\u{00E9}duire la fatigue et soutenir l'\u{00E9}nergie.",
                    'purchase_price' => 90,
                    'sale_price' => 135,
                    'stock_quantity' => 15,
                    'minimum_threshold' => 4,
                    'expiration_date' => '2027-12-31',
                ],
                [
                    'code' => 'COM-002',
                    'category' => $complementsCategoryName,
                    'name' => 'Vitabiotics Wellwoman Original',
                    'description' => "Compl\u{00E9}ment alimentaire formul\u{00E9} pour soutenir les besoins nutritionnels quotidiens de la femme.",
                    'purchase_price' => 95,
                    'sale_price' => 145,
                    'stock_quantity' => 12,
                    'minimum_threshold' => 4,
                    'expiration_date' => '2027-12-31',
                ],
                [
                    'code' => 'HYG-001',
                    'category' => $hygieneCategoryName,
                    'name' => 'Elgydium Dentifrice Anti-Plaque 75ml',
                    'description' => "Dentifrice anti-plaque pour l'hygi\u{00E8}ne bucco-dentaire quotidienne et la protection des gencives.",
                    'purchase_price' => 28,
                    'sale_price' => 42,
                    'stock_quantity' => 30,
                    'minimum_threshold' => 8,
                    'expiration_date' => '2028-06-30',
                ],
                [
                    'code' => 'HYG-002',
                    'category' => $hygieneCategoryName,
                    'name' => 'Meridol Bain de Bouche Protection Gencives 400ml',
                    'description' => "Bain de bouche quotidien pour aider \u{00E0} prot\u{00E9}ger les gencives et limiter la plaque dentaire.",
                    'purchase_price' => 55,
                    'sale_price' => 79,
                    'stock_quantity' => 22,
                    'minimum_threshold' => 6,
                    'expiration_date' => '2028-06-30',
                ],
                [
                    'code' => 'DER-001',
                    'category' => $dermoCategoryName,
                    'name' => 'La Roche-Posay Cicaplast Baume B5+ 100ml',
                    'description' => "Baume multi-r\u{00E9}parateur pour apaiser, prot\u{00E9}ger et favoriser la r\u{00E9}cup\u{00E9}ration des peaux fragilis\u{00E9}es.",
                    'purchase_price' => 125,
                    'sale_price' => 175,
                    'stock_quantity' => 16,
                    'minimum_threshold' => 5,
                    'expiration_date' => '2028-12-31',
                ],
                [
                    'code' => 'DER-002',
                    'category' => $dermoCategoryName,
                    'name' => "Av\u{00E8}ne Cleanance Gel Nettoyant 200ml",
                    'description' => "Gel nettoyant purifiant pour peaux grasses \u{00E0} imperfections, formul\u{00E9} pour un usage quotidien.",
                    'purchase_price' => 110,
                    'sale_price' => 155,
                    'stock_quantity' => 18,
                    'minimum_threshold' => 5,
                    'expiration_date' => '2028-12-31',
                ],
                [
                    'code' => 'BIO-001',
                    'category' => $bioCategoryName,
                    'name' => "Weleda Skin Food Soin Nourrissant Texture L\u{00E9}g\u{00E8}re Bio 75ml",
                    'description' => "Soin nourrissant bio pour hydrater et apaiser les peaux s\u{00E8}ches ou agress\u{00E9}es.",
                    'purchase_price' => 120,
                    'sale_price' => 168,
                    'stock_quantity' => 14,
                    'minimum_threshold' => 4,
                    'expiration_date' => '2028-12-31',
                ],
                [
                    'code' => 'BIO-002',
                    'category' => $bioCategoryName,
                    'name' => "Weleda Gel Dentifrice V\u{00E9}g\u{00E9}tal Bio 75ml",
                    'description' => "Gel dentifrice bio pour nettoyer en douceur et aider \u{00E0} r\u{00E9}duire la sensibilit\u{00E9} gingivale.",
                    'purchase_price' => 42,
                    'sale_price' => 62,
                    'stock_quantity' => 20,
                    'minimum_threshold' => 5,
                    'expiration_date' => '2028-12-31',
                ],
                [
                    'code' => 'MED-001',
                    'category' => $medicalCategoryName,
                    'name' => "Omron Tensiom\u{00E8}tre Brassard M2 Basic",
                    'description' => "Tensiom\u{00E8}tre \u{00E9}lectronique automatique pour mesurer la tension art\u{00E9}rielle \u{00E0} domicile avec simplicit\u{00E9}.",
                    'purchase_price' => 340,
                    'sale_price' => 460,
                    'stock_quantity' => 8,
                    'minimum_threshold' => 2,
                    'expiration_date' => null,
                ],
                [
                    'code' => 'MED-002',
                    'category' => $medicalCategoryName,
                    'name' => "Braun Thermom\u{00E8}tre Frontal M\u{00E9}dical BNT400B",
                    'description' => "Thermom\u{00E8}tre frontal sans contact pour une prise de temp\u{00E9}rature rapide et hygi\u{00E9}nique pour toute la famille.",
                    'purchase_price' => 650,
                    'sale_price' => 850,
                    'stock_quantity' => 6,
                    'minimum_threshold' => 2,
                    'expiration_date' => null,
                ],
                [
                    'code' => 'SOL-001',
                    'category' => $sunCategoryName,
                    'name' => 'La Roche-Posay Anthelios UVMune 400 Fluide Invisible SPF50+ 50ml',
                    'description' => "Fluide solaire visage \u{00E0} tr\u{00E8}s haute protection pour les peaux sensibles expos\u{00E9}es au soleil.",
                    'purchase_price' => 140,
                    'sale_price' => 179,
                    'stock_quantity' => 24,
                    'minimum_threshold' => 6,
                    'expiration_date' => '2028-12-31',
                ],
                [
                    'code' => 'SOL-002',
                    'category' => $sunCategoryName,
                    'name' => "Av\u{00E8}ne Cr\u{00E8}me Fini Invisible SPF50+ 50ml",
                    'description' => "Cr\u{00E8}me solaire visage \u{00E0} haute protection formul\u{00E9}e pour les peaux sensibles avec fini invisible.",
                    'purchase_price' => 145,
                    'sale_price' => 184,
                    'stock_quantity' => 20,
                    'minimum_threshold' => 6,
                    'expiration_date' => '2028-12-31',
                ],
            ];

            foreach ($products as $data) {
                $product = Product::query()->where('name', $data['name'])->firstOrNew();
                $product->fill([
                    'code' => $data['code'],
                    'name' => $data['name'],
                    'category_id' => $categories[$data['category']]->id,
                    'supplier_id' => null,
                    'purchase_price' => $data['purchase_price'],
                    'sale_price' => $data['sale_price'],
                    'stock_quantity' => $data['stock_quantity'],
                    'minimum_threshold' => $data['minimum_threshold'],
                    'expiration_date' => $data['expiration_date'],
                    'description' => $data['description'],
                    'main_image' => $frontPlaceholder,
                ])->save();

                // Keep placeholder images in sync with the public catalog before using this seeder.
                $product->images()->delete();
                $product->images()->createMany([
                    [
                        'image_path' => $frontPlaceholder,
                        'is_main' => true,
                    ],
                    [
                        'image_path' => $packagingPlaceholder,
                        'is_main' => false,
                    ],
                ]);
            }
        });

        $this->call(CatalogTranslationsSeeder::class);
    }
}
