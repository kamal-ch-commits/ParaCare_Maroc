<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategoryTranslations();
        $this->seedProductTranslations();
        $this->call(FixArabicTranslationsSeeder::class);
    }

    private function seedCategoryTranslations(): void
    {
        $translations = [
            'Soins visage' => [
                'name' => ['fr' => 'Soins visage', 'en' => 'Face care', 'ar' => 'ا�"ع�?ا�Sة با�"بشرة'],
                'description' => ['fr' => 'Cremes, nettoyants et serums.', 'en' => 'Creams, cleansers, and serums.', 'ar' => 'ترط�Sب �^ر�^ت�S�? �"ط�Sف �"�"بشرة.'],
            ],
            'Hygiene' => [
                'name' => ['fr' => 'Hygiene', 'en' => 'Hygiene', 'ar' => 'ا�"ع�?ا�Sة ا�"ص�Sد�"�Sة'],
                'description' => ['fr' => 'Produits d hygiene quotidienne.', 'en' => 'Daily hygiene products.', 'ar' => 'ع�?ا�Sة �.�^ث�^�,ة �"�"استخدا�. ا�"�S�^�.�S.'],
            ],
            'Bebe' => [
                'name' => ['fr' => 'Bebe', 'en' => 'Baby', 'ar' => 'ا�"ع�?ا�Sة با�"طف�"'],
                'description' => ['fr' => 'Produits pour nourrissons et enfants.', 'en' => 'Products for babies and children.', 'ar' => 'أساس�Sات �?اع�.ة �"�"صغار.'],
            ],
            'Complements' => [
                'name' => ['fr' => 'Complements', 'en' => 'Supplements', 'ar' => 'ا�"�.�f�.�"ات'],
                'description' => ['fr' => 'Vitamines et complements alimentaires.', 'en' => 'Vitamins and dietary supplements.', 'ar' => 'ت�^از�? �^دع�. �S�^�.�S.'],
            ],
            'Solaire' => [
                'name' => ['fr' => 'Solaire', 'en' => 'Sun care', 'ar' => 'ا�"ح�.ا�Sة'],
                'description' => ['fr' => 'Protection et soins apres soleil.', 'en' => 'Sun protection and after-sun care.', 'ar' => 'ح�.ا�Sة �S�^�.�Sة �^�^ا�,�S �"�"ش�.س.'],
            ],
            'Produits bio & naturels' => [
                'name' => ['fr' => 'Produits bio & naturels', 'en' => 'Organic & natural products', 'ar' => '�.�?تجات عض�^�Sة �^طب�Sع�Sة'],
                'description' => ['fr' => "Selection de soins a base d'ingredients naturels ou certifies bio.", 'en' => 'Care products made with natural or certified organic ingredients.', 'ar' => 'ع�?ا�Sة �?بات�Sة �^طب�Sع�Sة �.�?دئة.'],
            ],
            'Materiel medical' => [
                'name' => ['fr' => 'Materiel medical', 'en' => 'Medical equipment', 'ar' => 'ا�"�.عدات ا�"طب�Sة'],
                'description' => ['fr' => 'Appareils et accessoires pour le suivi de la sante.', 'en' => 'Devices and accessories for health monitoring.', 'ar' => 'أج�?زة �^�.�"ح�,ات �"�.تابعة ا�"صحة ف�S ا�"�.�?ز�".'],
            ],
        ];

        foreach ($translations as $name => $translationSet) {
            $translationSet = $this->repairTranslations($translationSet);

            Category::query()
                ->where('name', $name)
                ->update([
                    'name_translations' => $this->encodeTranslations($translationSet['name']),
                    'description_translations' => $this->encodeTranslations($translationSet['description']),
                ]);
        }
    }

    private function seedProductTranslations(): void
    {
        $translations = [
            '0001' => [
                'name' => ['fr' => 'La Roche-Posay Effaclar Gel Moussant Purifiant 400ml', 'en' => 'La Roche-Posay Effaclar Purifying Foaming Gel 400ml', 'ar' => '�"ار�^ش ب�^ز�S�? إ�Sفا�f�"ار ج�" رغ�^�S �.�?ظف 400 �.�"'],
                'description' => ['fr' => "Gel nettoyant purifiant pour peaux grasses et sensibles.", 'en' => 'Purifying cleansing gel for oily and sensitive skin.', 'ar' => 'ج�" �.�?ظف �.�?�, �Sساعد ع�"�? إزا�"ة ا�"ش�^ائب �^ا�"ز�S�^ت ا�"زائدة �"�"بشرة ا�"د�?�?�Sة �^ا�"حساسة.'],
            ],
            '0002' => [
                'name' => ['fr' => 'Bioderma Sensibio H2O Eau Micellaire 500ml', 'en' => 'Bioderma Sensibio H2O Micellar Water 500ml', 'ar' => 'ب�S�^د�Sر�.ا س�?س�Sب�S�^ H2O �.اء �.�Sس�S�"ار 500 �.�"'],
                'description' => ['fr' => 'Eau micellaire demaquillante pour peaux sensibles.', 'en' => 'Micellar cleansing water for sensitive skin.', 'ar' => '�.اء �.�Sس�S�"ار �"إزا�"ة ا�"�.�f�Sاج �.�?اسب �"�"بشرة ا�"حساسة �^�"�"استخدا�. ع�"�? ا�"�^ج�? �^ا�"ع�S�?�S�?.'],
            ],
            '0003' => [
                'name' => ['fr' => 'Bioderma Atoderm Creme Ultra 500ml', 'en' => 'Bioderma Atoderm Ultra Cream 500ml', 'ar' => 'ب�S�^د�Sر�.ا أت�^د�Sر�. �fر�S�. أ�"ترا 500 �.�"'],
                'description' => ['fr' => 'Creme nourrissante pour peaux sensibles normales a seches.', 'en' => 'Nourishing cream for normal to dry sensitive skin.', 'ar' => '�fر�S�. فائ�, ا�"تغذ�Sة �"�"بشرة ا�"حساسة ا�"عاد�Sة إ�"�? ا�"جافة.'],
            ],
            '0004' => [
                'name' => ['fr' => 'CeraVe Lait Hydratant 473ml', 'en' => 'CeraVe Moisturising Lotion 473ml', 'ar' => 'س�Sراف�S �"�^ش�? �.رطب 473 �.�"'],
                'description' => ['fr' => 'Lait hydratant pour peaux seches a tres seches.', 'en' => 'Moisturising lotion for dry to very dry skin.', 'ar' => '�"�^ش�? �.رطب �"�"بشرة ا�"جافة إ�"�? شد�Sدة ا�"جفاف�O �.�?اسب �"�"�^ج�? �^ا�"جس�..'],
            ],
            '0005' => [
                'name' => ['fr' => 'Ducray Neoptide Expert Serum Anti-Chute & Croissance 2 x 50ml', 'en' => 'Ducray Neoptide Expert Anti-Hair Loss & Growth Serum 2 x 50ml', 'ar' => 'د�^�fرا�S �?�S�^بت�Sد إ�fسبرت س�Sر�^�. �.ضاد �"تسا�,ط ا�"شعر �^ا�"�?�.�^ 2 �- 50 �.�"'],
                'description' => ['fr' => 'Serum capillaire anti-chute pour soutenir la croissance.', 'en' => 'Anti-hair loss serum to support healthier hair growth.', 'ar' => 'س�Sر�^�. �"�"شعر �Sساعد ع�"�? ت�,�"�S�" تسا�,ط ا�"شعر �^دع�. �?�.�^ أ�fثر �,�^ة.'],
            ],
            '0006' => [
                'name' => ['fr' => 'Klorane Serum Fortifiant a la Quinine & Edelweiss BIO 100ml', 'en' => 'Klorane Strengthening Serum with Quinine & Organic Edelweiss 100ml', 'ar' => '�f�"�^را�? س�Sر�^�. �.�,�^ �"�"شعر با�"�f�S�?�S�? �^ا�"إد�S�"�^�Sس ا�"عض�^�S 100 �.�"'],
                'description' => ['fr' => 'Serum fortifiant sans rincage pour cheveux fragilises.', 'en' => 'Leave-in strengthening serum for weakened hair.', 'ar' => 'س�Sر�^�. �.�,�^ �Sتر�f ع�"�? ا�"شعر �"ت�,�^�Sة ا�"شعر ا�"ضع�Sف �^دع�. ر�^ت�S�? �.�fافحة ا�"تسا�,ط.'],
            ],
            'BBM-001' => [
                'name' => ['fr' => 'Mustela Gel Lavant Doux Bebe 500ml', 'en' => 'Mustela Gentle Cleansing Gel Baby 500ml', 'ar' => '�.�^ست�S�"ا ج�" �.�?ظف �"ط�Sف �"�"أطفا�" 500 �.�"'],
                'description' => ['fr' => 'Gel lavant doux pour le corps et les cheveux des bebes.', 'en' => 'Gentle cleansing gel for baby body and hair.', 'ar' => 'ج�" ت�?ظ�Sف �"ط�Sف �"�"جس�. �^ا�"شعر ع�?د ا�"أطفا�"�O �.�?اسب �"�"استع�.ا�" ا�"�S�^�.�S.'],
            ],
            'BBM-002' => [
                'name' => ['fr' => 'Mitosyl Pommade Protectrice 145g', 'en' => 'Mitosyl Protective Ointment 145g', 'ar' => '�.�Sت�^ز�S�" �.ر�?�. �^ا�, 145 غ'],
                'description' => ['fr' => 'Pommade protectrice pour apaiser les rougeurs chez bebe.', 'en' => 'Protective ointment to help soothe baby diaper rash.', 'ar' => '�.ر�?�. �^ا�, �Sساعد ع�"�? ا�"�^�,ا�Sة �.�? اح�.رار �.�?ط�,ة ا�"حفاض �^ت�?دئت�? �"د�? ا�"طف�".'],
            ],
            'COM-001' => [
                'name' => ['fr' => 'Arkopharma Azinc Vitalite Gummies', 'en' => 'Arkopharma Azinc Vitality Gummies', 'ar' => 'أر�f�^فار�.ا أز�S�?�f ف�Sتا�"�Sت�S غ�^�.�S'],
                'description' => ['fr' => 'Complement alimentaire en gummies pour soutenir l energie.', 'en' => 'Dietary supplement gummies to support energy.', 'ar' => '�.�f�.�" غذائ�S ع�"�? ش�f�" غ�^�.�S �Sساعد ع�"�? ت�,�"�S�" ا�"تعب �^دع�. ا�"طا�,ة.'],
            ],
            'COM-002' => [
                'name' => ['fr' => 'Vitabiotics Wellwoman Original', 'en' => 'Vitabiotics Wellwoman Original', 'ar' => 'ف�Sتابا�S�^ت�fس �^�S�" �^�^�.�? أ�^ر�Sج�S�?ا�"'],
                'description' => ['fr' => 'Complement alimentaire pour les besoins nutritionnels de la femme.', 'en' => 'Dietary supplement for women daily nutritional needs.', 'ar' => '�.�f�.�" غذائ�S �.ص�.�. �"دع�. ا�"احت�Sاجات ا�"غذائ�Sة ا�"�S�^�.�Sة �"�"�.رأة.'],
            ],
            'HYG-001' => [
                'name' => ['fr' => 'Elgydium Dentifrice Anti-Plaque 75ml', 'en' => 'Elgydium Anti-Plaque Toothpaste 75ml', 'ar' => 'إ�"ج�Sد�S�^�. �.عج�^�? أس�?ا�? �.ضاد �"�"ب�"ا�f 75 �.�"'],
                'description' => ['fr' => 'Dentifrice anti-plaque pour l hygiene bucco-dentaire.', 'en' => 'Anti-plaque toothpaste for daily oral hygiene.', 'ar' => '�.عج�^�? أس�?ا�? �.ضاد �"�"ب�"ا�f �"�"�?ظافة ا�"�S�^�.�Sة �"�"ف�. �^ا�"أس�?ا�? �^ح�.ا�Sة ا�"�"ثة.'],
            ],
            'HYG-002' => [
                'name' => ['fr' => 'Meridol Bain de Bouche Protection Gencives 400ml', 'en' => 'Meridol Gum Protection Mouthwash 400ml', 'ar' => '�.�Sر�Sد�^�" غس�^�" ف�. �"ح�.ا�Sة ا�"�"ثة 400 �.�"'],
                'description' => ['fr' => 'Bain de bouche quotidien pour proteger les gencives.', 'en' => 'Daily mouthwash that helps protect gums.', 'ar' => 'غس�^�" ف�. �S�^�.�S �Sساعد ع�"�? ح�.ا�Sة ا�"�"ثة �^ا�"حد �.�? ت�f�^�? ا�"ب�"ا�f.'],
            ],
            'DER-001' => [
                'name' => ['fr' => 'La Roche-Posay Cicaplast Baume B5+ 100ml', 'en' => 'La Roche-Posay Cicaplast Balm B5+ 100ml', 'ar' => '�"ار�^ش ب�^ز�S�? س�S�fاب�"است ب�"س�. B5+ 100 �.�"'],
                'description' => ['fr' => 'Baume reparateur pour apaiser et proteger la peau.', 'en' => 'Repair balm that soothes and protects weakened skin.', 'ar' => 'ب�"س�. �.تعدد ا�"إص�"اح �S�?دئ ا�"بشرة ا�"ضع�Sفة �^�Sح�.�S�?ا �^�Sدع�. تعاف�S�?ا.'],
            ],
            'DER-002' => [
                'name' => ['fr' => 'Avene Cleanance Gel Nettoyant 200ml', 'en' => 'Avene Cleanance Cleansing Gel 200ml', 'ar' => 'أف�S�? �f�"�S�?ا�?س ج�" �.�?ظف 200 �.�"'],
                'description' => ['fr' => 'Gel nettoyant purifiant pour peaux grasses.', 'en' => 'Purifying cleansing gel for oily skin.', 'ar' => 'ج�" �.�?ظف �.�?�, �"�"بشرة ا�"د�?�?�Sة ا�"�.عرضة �"�"ش�^ائب �^�.�?اسب �"�"استع�.ا�" ا�"�S�^�.�S.'],
            ],
            'BIO-001' => [
                'name' => ['fr' => 'Weleda Skin Food Soin Nourrissant Texture Legere Bio 75ml', 'en' => 'Weleda Skin Food Light Nourishing Care Organic 75ml', 'ar' => 'ف�S�"�Sدا س�f�S�? ف�^د ع�?ا�Sة �.غذ�Sة خف�Sفة عض�^�Sة 75 �.�"'],
                'description' => ['fr' => 'Soin nourrissant bio pour hydrater et apaiser les peaux seches.', 'en' => 'Organic nourishing care that hydrates and soothes dry skin.', 'ar' => 'ع�?ا�Sة �.غذ�Sة عض�^�Sة تساعد ع�"�? ترط�Sب �^ت�?دئة ا�"بشرة ا�"جافة أ�^ ا�"�.ج�?دة.'],
            ],
            'BIO-002' => [
                'name' => ['fr' => 'Weleda Gel Dentifrice Vegetal Bio 75ml', 'en' => 'Weleda Organic Herbal Tooth Gel 75ml', 'ar' => 'ف�S�"�Sدا ج�" أس�?ا�? �?بات�S عض�^�S 75 �.�"'],
                'description' => ['fr' => 'Gel dentifrice bio pour nettoyer en douceur.', 'en' => 'Organic tooth gel that cleans gently.', 'ar' => 'ج�" أس�?ا�? عض�^�S �S�?ظف ب�"طف �^�Sساعد ع�"�? ت�,�"�S�" حساس�Sة ا�"�"ثة.'],
            ],
            'MED-001' => [
                'name' => ['fr' => 'Omron Tensiometre Brassard M2 Basic', 'en' => 'Omron M2 Basic Blood Pressure Monitor', 'ar' => 'أ�^�.ر�^�? ج�?از �,�Sاس ضغط ا�"د�. M2 Basic'],
                'description' => ['fr' => 'Tensiometre automatique pour mesurer la tension a domicile.', 'en' => 'Automatic blood pressure monitor for home use.', 'ar' => 'ج�?از إ�"�fتر�^�?�S أ�^ت�^�.ات�S�f�S �"�,�Sاس ضغط ا�"د�. بس�?�^�"ة ف�S ا�"�.�?ز�".'],
            ],
            'MED-002' => [
                'name' => ['fr' => 'Braun Thermometre Frontal Medical BNT400B', 'en' => 'Braun Medical Forehead Thermometer BNT400B', 'ar' => 'برا�^�? �.�,�Sاس حرارة جب�?�S طب�S BNT400B'],
                'description' => ['fr' => 'Thermometre frontal sans contact pour toute la famille.', 'en' => 'No-touch forehead thermometer for the whole family.', 'ar' => '�.�,�Sاس حرارة جب�?�S بد�^�? �"�.س �"�,�Sاس سر�Sع �^صح�S �"درجة ا�"حرارة �"ج�.�Sع أفراد ا�"أسرة.'],
            ],
            'SOL-001' => [
                'name' => ['fr' => 'La Roche-Posay Anthelios UVMune 400 Fluide Invisible SPF50+ 50ml', 'en' => 'La Roche-Posay Anthelios UVMune 400 Invisible Fluid SPF50+ 50ml', 'ar' => '�"ار�^ش ب�^ز�S�? أ�?ث�S�"�S�^س UVMune 400 ف�"�^�Sد غ�Sر �.رئ�S SPF50+ 50 �.�"'],
                'description' => ['fr' => 'Fluide solaire visage tres haute protection.', 'en' => 'Very high-protection facial sunscreen fluid.', 'ar' => 'ف�"�^�Sد �^ا�,�S ش�.س�S �"�"�^ج�? بح�.ا�Sة عا�"�Sة جدا �"�"بشرة ا�"حساسة ا�"�.عرضة �"�"ش�.س.'],
            ],
            'SOL-002' => [
                'name' => ['fr' => 'Avene Creme Fini Invisible SPF50+ 50ml', 'en' => 'Avene Invisible Finish Cream SPF50+ 50ml', 'ar' => 'أف�S�? �fر�S�. ب�"�.سة غ�Sر �.رئ�Sة SPF50+ 50 �.�"'],
                'description' => ['fr' => 'Creme solaire visage haute protection.', 'en' => 'High-protection facial sunscreen cream.', 'ar' => '�fر�S�. �^ا�,�S ش�.س�S �"�"�^ج�? بح�.ا�Sة عا�"�Sة �"�"بشرة ا�"حساسة �.ع �"�.سة غ�Sر �.رئ�Sة.'],
            ],
        ];

        foreach ($translations as $code => $translationSet) {
            $translationSet = $this->repairTranslations($translationSet);

            Product::query()
                ->where('code', $code)
                ->update([
                    'name_translations' => $this->encodeTranslations($translationSet['name']),
                    'description_translations' => $this->encodeTranslations($translationSet['description']),
                ]);
        }
    }

    private function repairTranslations(array $translations): array
    {
        foreach ($translations as $field => $values) {
            foreach ($values as $locale => $value) {
                $translations[$field][$locale] = $this->repairText($value);
            }
        }

        return $translations;
    }

    private function repairText(string $value): string
    {
        if (! preg_match('/[���]/u', $value)) {
            return $value;
        }

        $decoded = @iconv('UTF-8', 'ISO-8859-1//IGNORE', $value);

        return is_string($decoded) && $decoded !== '' ? $decoded : $value;
    }

    private function encodeTranslations(array $translations): string
    {
        return json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
