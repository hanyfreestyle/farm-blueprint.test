<?php

namespace Database\Seeders;

use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;

class QuestionnaireSectionSeeder extends Seeder {
  public function run(): void {
    $sections = [

      /*
      |--------------------------------------------------------------------------
      | 1. إدخال البيانات الأساسية للمزرعة
      |--------------------------------------------------------------------------
      */

      [
        'name' => 'إدخال البيانات الأساسية للمزرعة',
        'description' => <<<'MD'
تبدأ عملية إعداد النظام بتعريف الهيكل الفعلي للمزرعة، بحيث يتم ربط كل حيوان وكل عملية تشغيلية بمكان محدد يمكن تتبعه تاريخيًا.
MD,
        'sort_order' => 1,
        'children' => [
          [
            'name' => 'بيانات المزرعة',
            'description' => null,
            'sort_order' => 1,
          ],
          [
            'name' => 'بيانات العنبر',
            'description' => null,
            'sort_order' => 2,
          ],
          [
            'name' => 'بيانات البطارية',
            'description' => null,
            'sort_order' => 3,
          ],
          [
            'name' => 'بيانات القفص / العين',
            'description' => null,
            'sort_order' => 4,
          ],
          [
            'name' => 'بيانات السلالات',
            'description' => null,
            'sort_order' => 5,
          ],
        ],
      ],

      /*
      |--------------------------------------------------------------------------
      | 2. إعدادات التشغيل ودورة الإنتاج
      |--------------------------------------------------------------------------
      */

      [
        'name' => 'إعدادات التشغيل ودورة الإنتاج',
        'description' => null,
        'sort_order' => 2,
        'children' => [
          [
            'name' => 'إعدادات التلقيح',
            'description' => null,
            'sort_order' => 1,
          ],
          [
            'name' => 'إعدادات فحص الحمل والجس والحمل المتوقع',
            'description' => null,
            'sort_order' => 2,
          ],
          [
            'name' => 'إعدادات الولادة والرضاعة وإعادة التلقيح',
            'description' => null,
            'sort_order' => 3,
          ],
          [
            'name' => 'إعدادات الفطام والوزن والانتقال للتتبع الفردي',
            'description' => null,
            'sort_order' => 4,
          ],
          [
            'name' => 'إعدادات مراحل النمو والفرز وتحديد المصير',
            'description' => null,
            'sort_order' => 5,
          ],
          [
            'name' => 'إعدادات التسمين والوصول إلى البيع والخروج',
            'description' => null,
            'sort_order' => 6,
          ],
          [
            'name' => 'إعدادات النفوق والحالات الصحية والاستبعاد',
            'description' => null,
            'sort_order' => 7,
          ],
          [
            'name' => 'إعدادات المهام والتنبيهات والمواعيد',
            'description' => null,
            'sort_order' => 8,
          ],
          [
            'name' => 'إعدادات الترقيم والأكواد وقواعد الانتقال بين الحالات',
            'description' => null,
            'sort_order' => 9,
          ],
        ],
      ],

      /*
      |--------------------------------------------------------------------------
      | 3. تكوين وإدخال القطيع
      |--------------------------------------------------------------------------
      */

      [
        'name' => 'تكوين وإدخال القطيع',
        'description' => null,
        'sort_order' => 3,
        'children' => [
          [
            'name' => 'مصادر تكوين القطيع',
            'description' => null,
            'sort_order' => 1,
          ],
          [
            'name' => 'بيانات ملف الأرنب الأساسية',
            'description' => null,
            'sort_order' => 2,
          ],
          [
            'name' => 'النسب وشجرة العائلة',
            'description' => null,
            'sort_order' => 3,
          ],
          [
            'name' => 'الدخول الأول للمزرعة والتقييم الأولي',
            'description' => null,
            'sort_order' => 4,
          ],
          [
            'name' => 'تسكين القطيع وإدارة الإشغال',
            'description' => null,
            'sort_order' => 5,
          ],
          [
            'name' => 'تنظيم الذكور والإناث داخل القطيع',
            'description' => null,
            'sort_order' => 6,
          ],
          [
            'name' => 'تحويل إنتاج المزرعة إلى القطيع والإحلال الداخلي',
            'description' => null,
            'sort_order' => 7,
          ],
          [
            'name' => 'جاهزية القطيع لبدء دورة التشغيل والإنتاج',
            'description' => null,
            'sort_order' => 8,
          ],
        ],
      ],

      /*
      |--------------------------------------------------------------------------
      | 4. الحركات ودورة التشغيل الفعلية
      |--------------------------------------------------------------------------
      */

      [
        'name' => 'الحركات ودورة التشغيل الفعلية',
        'description' => null,
        'sort_order' => 4,
        'children' => [
          [
            'name' => 'بدء دورة الأنثى وعملية التلقيح',
            'description' => null,
            'sort_order' => 1,
          ],
          [
            'name' => 'تكرار التلقيح وإدارة محاولات التلقيح',
            'description' => null,
            'sort_order' => 2,
          ],
          [
            'name' => 'الجس وفحص الحمل وتحديد نتيجة المحاولة',
            'description' => null,
            'sort_order' => 3,
          ],
          [
            'name' => 'متابعة الحمل وتجهيز الولادة',
            'description' => null,
            'sort_order' => 4,
          ],
          [
            'name' => 'تسجيل الولادة وإنشاء البطن',
            'description' => null,
            'sort_order' => 5,
          ],
          [
            'name' => 'مرحلة الرضاعة ومتابعة البطن',
            'description' => null,
            'sort_order' => 6,
          ],
          [
            'name' => 'إعادة تلقيح الأم أثناء الرضاعة وتداخل الدورات الإنتاجية',
            'description' => null,
            'sort_order' => 7,
          ],
          [
            'name' => 'تنفيذ الفطام وتحويل البطن إلى أفراد مستقلة',
            'description' => null,
            'sort_order' => 8,
          ],
          [
            'name' => 'متابعة النمو والوزن والفرز بعد الفطام',
            'description' => null,
            'sort_order' => 9,
          ],
          [
            'name' => 'تنفيذ قرار المصير: الإحلال أو التسمين',
            'description' => null,
            'sort_order' => 10,
          ],
          [
            'name' => 'الحالات الاستثنائية وتغيير مسار دورة الإنتاج',
            'description' => null,
            'sort_order' => 11,
          ],
          [
            'name' => 'الخروج النهائي من المزرعة وإغلاق دورة حياة الأرنب',
            'description' => null,
            'sort_order' => 12,
          ],
        ],
      ],

      /*
      |--------------------------------------------------------------------------
      | 5. التقارير والإشعارات ومؤشرات الأداء
      |--------------------------------------------------------------------------
      */

      [
        'name' => 'التقارير والإشعارات ومؤشرات الأداء',
        'description' => null,
        'sort_order' => 5,
        'children' => [
          [
            'name' => 'لوحة التحكم ومؤشرات اليوم',
            'description' => null,
            'sort_order' => 1,
          ],
          [
            'name' => 'المهام والتنبيهات اليومية',
            'description' => null,
            'sort_order' => 2,
          ],
          [
            'name' => 'تقارير القطيع',
            'description' => null,
            'sort_order' => 3,
          ],
          [
            'name' => 'تقارير الخصوبة والتلقيح',
            'description' => null,
            'sort_order' => 4,
          ],
          [
            'name' => 'تقارير الولادة والرضاعة والفطام',
            'description' => null,
            'sort_order' => 5,
          ],
          [
            'name' => 'تقارير النمو والأوزان والتسمين',
            'description' => null,
            'sort_order' => 6,
          ],
          [
            'name' => 'تقارير النفوق والصحة',
            'description' => null,
            'sort_order' => 7,
          ],
          [
            'name' => 'تقييم أداء الإناث والذكور',
            'description' => null,
            'sort_order' => 8,
          ],
          [
            'name' => 'تقارير النسب والإحلال',
            'description' => null,
            'sort_order' => 9,
          ],
          [
            'name' => 'تقارير الإشغال والسعة والمواقع',
            'description' => null,
            'sort_order' => 10,
          ],
          [
            'name' => 'الإنذار المبكر واكتشاف الحالات غير الطبيعية',
            'description' => null,
            'sort_order' => 11,
          ],
          [
            'name' => 'التقارير المقارنة والاتجاهات الزمنية',
            'description' => null,
            'sort_order' => 12,
          ],
          [
            'name' => 'المقارنة بين أجزاء المزرعة',
            'description' => null,
            'sort_order' => 13,
          ],
          [
            'name' => 'صفحة الحيوان التحليلية',
            'description' => null,
            'sort_order' => 14,
          ],
          [
            'name' => 'صفحة البطن التحليلية',
            'description' => null,
            'sort_order' => 15,
          ],
          [
            'name' => 'سجل التنبيهات',
            'description' => null,
            'sort_order' => 16,
          ],
          [
            'name' => 'مستويات التنبيه',
            'description' => null,
            'sort_order' => 17,
          ],
          [
            'name' => 'التقارير القابلة للتصفية والتصدير',
            'description' => null,
            'sort_order' => 18,
          ],
          [
            'name' => 'جودة البيانات والتنبيهات الإدارية',
            'description' => null,
            'sort_order' => 19,
          ],
          [
            'name' => 'المؤشرات الرئيسية للمزرعة KPIs',
            'description' => null,
            'sort_order' => 20,
          ],
          [
            'name' => 'مبدأ التقرير → التنبيه → القرار → الإجراء',
            'description' => null,
            'sort_order' => 21,
          ],
        ],
      ],
    ];

    foreach ($sections as $sectionData) {
      $mainSection = QuestionnaireSection::query()->updateOrCreate(
        [
          'parent_id' => null,
          'name' => $sectionData['name'],
        ],
        [
          'description' => $sectionData['description'],
          'sort_order' => $sectionData['sort_order'],
        ],
      );

      foreach ($sectionData['children'] as $childData) {
        QuestionnaireSection::query()->updateOrCreate(
          [
            'parent_id' => $mainSection->id,
            'name' => $childData['name'],
          ],
          [
            'description' => $childData['description'],
            'sort_order' => $childData['sort_order'],
          ],
        );
      }
    }
  }
}