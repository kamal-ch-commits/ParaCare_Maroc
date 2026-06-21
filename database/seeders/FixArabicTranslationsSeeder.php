<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FixArabicTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->fixCategories();
        $this->fixProducts();
    }

    private function fixCategories(): void
    {
        $categories = [
            'Soins visage' => ['name' => 'العناية بالبشرة', 'description' => 'ترطيب وروتين لطيف للبشرة.'],
            'Hygiene' => ['name' => 'العناية الصيدلية', 'description' => 'عناية موثوقة للاستخدام اليومي.'],
            'Bebe' => ['name' => 'العناية بالطفل', 'description' => 'أساسيات ناعمة للصغار.'],
            'Complements' => ['name' => 'المكملات', 'description' => 'توازن ودعم يومي.'],
            'Solaire' => ['name' => 'الحماية', 'description' => 'حماية يومية وواقي للشمس.'],
            'Produits bio & naturels' => ['name' => 'منتجات عضوية وطبيعية', 'description' => 'عناية نباتية وطبيعية مهدئة.'],
            'Materiel medical' => ['name' => 'المعدات الطبية', 'description' => 'أجهزة وملحقات لمتابعة الصحة في المنزل.'],
        ];

        foreach ($categories as $name => $arabic) {
            $category = Category::where('name', $name)->first();

            if (! $category) {
                continue;
            }

            $nameTranslations = $category->name_translations ?? [];
            $descriptionTranslations = $category->description_translations ?? [];
            $nameTranslations['ar'] = $arabic['name'];
            $descriptionTranslations['ar'] = $arabic['description'];

            $category->forceFill([
                'name_translations' => $nameTranslations,
                'description_translations' => $descriptionTranslations,
            ])->save();
        }
    }

    private function fixProducts(): void
    {
        $products = [
            '0001' => [
                'name' => 'لاروش بوزيه إيفاكلار جل رغوي منظف 400 مل',
                'description' => 'جل منظف منق يساعد على إزالة الشوائب والزيوت الزائدة للبشرة الدهنية والحساسة.',
            ],
            '0002' => [
                'name' => 'بيوديرما سنسيبيو H2O ماء ميسيلار 500 مل',
                'description' => 'ماء ميسيلار لإزالة المكياج مناسب للبشرة الحساسة وللاستخدام على الوجه والعينين.',
            ],
            '0003' => [
                'name' => 'بيوديرما أتوديرم كريم ألترا 500 مل',
                'description' => 'كريم فائق التغذية للبشرة الحساسة العادية إلى الجافة.',
            ],
            '0004' => [
                'name' => 'سيرافي لوشن مرطب 473 مل',
                'description' => 'لوشن مرطب للبشرة الجافة إلى شديدة الجفاف، مناسب للوجه والجسم.',
            ],
            '0005' => [
                'name' => 'دوكراي نيوبتيد إكسبرت سيروم مضاد لتساقط الشعر والنمو 2 × 50 مل',
                'description' => 'سيروم للشعر يساعد على تقليل تساقط الشعر ودعم نمو أكثر قوة.',
            ],
            '0006' => [
                'name' => 'كلوران سيروم مقو للشعر بالكينا وإديلويس العضوي 100 مل',
                'description' => 'سيروم مقو يترك على الشعر لتقوية الشعر الضعيف ودعم روتين مكافحة التساقط.',
            ],
            'BIO-001' => [
                'name' => 'فيليدا سكين فود عناية مغذية خفيفة عضوية 75 مل',
                'description' => 'عناية مغذية عضوية تساعد على ترطيب وتهدئة البشرة الجافة أو المجهدة.',
            ],
            'BIO-002' => [
                'name' => 'فيليدا جل أسنان نباتي عضوي 75 مل',
                'description' => 'جل أسنان عضوي ينظف بلطف ويساعد على تقليل حساسية اللثة.',
            ],
            'HYG-001' => [
                'name' => 'إلجيديوم معجون أسنان مضاد للبلاك 75 مل',
                'description' => 'معجون أسنان مضاد للبلاك للنظافة اليومية للفم والأسنان وحماية اللثة.',
            ],
            'HYG-002' => [
                'name' => 'ميريدول غسول فم لحماية اللثة 400 مل',
                'description' => 'غسول فم يومي يساعد على حماية اللثة والحد من تكون البلاك.',
            ],
            'BBM-001' => [
                'name' => 'موستيلا جل منظف لطيف للأطفال 500 مل',
                'description' => 'جل تنظيف لطيف للجسم والشعر عند الأطفال، مناسب للاستعمال اليومي.',
            ],
            'BBM-002' => [
                'name' => 'ميتوزيل مرهم واق 145 غ',
                'description' => 'مرهم واق يساعد على الوقاية من احمرار منطقة الحفاض وتهدئته لدى الطفل.',
            ],
            'COM-001' => [
                'name' => 'أركوفارما أزينك فيتاليتي غومي',
                'description' => 'مكمل غذائي على شكل غومي يساعد على تقليل التعب ودعم الطاقة.',
            ],
            'COM-002' => [
                'name' => 'فيتابايوتكس ويل وومن أوريجينال',
                'description' => 'مكمل غذائي مصمم لدعم الاحتياجات الغذائية اليومية للمرأة.',
            ],
            'DER-001' => [
                'name' => 'لاروش بوزيه سيكابلاست بلسم B5+ 100 مل',
                'description' => 'بلسم متعدد الإصلاح يهدئ البشرة الضعيفة ويحميها ويدعم تعافيها.',
            ],
            'DER-002' => [
                'name' => 'أفين كلينانس جل منظف 200 مل',
                'description' => 'جل منظف منق للبشرة الدهنية المعرضة للشوائب ومناسب للاستعمال اليومي.',
            ],
            'SOL-001' => [
                'name' => 'لاروش بوزيه أنثيليوس فلويد غير مرئي SPF50+ 50 مل',
                'description' => 'فلويد واقي شمسي للوجه بحماية عالية جدا للبشرة الحساسة المعرضة للشمس.',
            ],
            'SOL-002' => [
                'name' => 'أفين كريم بلمسة غير مرئية SPF50+ 50 مل',
                'description' => 'كريم واقي شمسي للوجه بحماية عالية للبشرة الحساسة مع لمسة غير مرئية.',
            ],
            'MED-001' => [
                'name' => 'أومرون جهاز قياس ضغط الدم M2 Basic',
                'description' => 'جهاز إلكتروني أوتوماتيكي لقياس ضغط الدم بسهولة في المنزل.',
            ],
            'MED-002' => [
                'name' => 'براون مقياس حرارة جبهي طبي BNT400B',
                'description' => 'مقياس حرارة جبهي بدون لمس لقياس سريع وصحي لدرجة الحرارة لجميع أفراد الأسرة.',
            ],
        ];

        foreach ($products as $code => $arabic) {
            $product = Product::where('code', $code)->first();

            if (! $product) {
                continue;
            }

            $nameTranslations = $product->name_translations ?? [];
            $descriptionTranslations = $product->description_translations ?? [];
            $nameTranslations['ar'] = $arabic['name'];
            $descriptionTranslations['ar'] = $arabic['description'];

            $product->forceFill([
                'name_translations' => $nameTranslations,
                'description_translations' => $descriptionTranslations,
            ])->save();
        }
    }
}
