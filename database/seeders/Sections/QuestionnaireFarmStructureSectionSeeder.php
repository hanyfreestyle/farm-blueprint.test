<?php

namespace Database\Seeders\Sections;

use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;

class QuestionnaireFarmStructureSectionSeeder extends Seeder {
  public function run(): void {
    $mainSection = QuestionnaireSection::query()->updateOrCreate(
      [
        'parent_id' => null,
        'name' => 'إدخال البيانات الأساسية للمزرعة',
      ],
      [
        'description' => <<<'MD'
تبدأ عملية إعداد النظام بتعريف الهيكل الفعلي للمزرعة، بحيث يتم ربط كل حيوان وكل عملية تشغيلية بمكان محدد يمكن تتبعه تاريخيًا.
MD,
        'sort_order' => 1,
      ],
    );

    $subsections = [
      [
        'name' => 'بيانات المزرعة',
        'description' => <<<'MD'
تمثل المزرعة أعلى مستوى تنظيمي في النظام، بحيث يمكن للنظام مستقبلًا إدارة أكثر من مزرعة مع فصل بيانات وتشغيل كل مزرعة عن الأخرى.
MD,
        'sort_order' => 1,
      ],
      [
        'name' => 'بيانات العنبر',
        'description' => <<<'MD'
يمثل العنبر وحدة تنظيمية داخل المزرعة تحتوي على البطاريات والأقفاص، ويسمح بفصل الحيوانات والعمليات حسب الموقع الفعلي داخل المزرعة.
MD,
        'sort_order' => 2,
      ],
      [
        'name' => 'بيانات البطارية',
        'description' => <<<'MD'
تمثل البطارية وحدة داخل العنبر تضم مجموعة من العيون أو الأقفاص، وتساعد في تنظيم أماكن التسكين والترقيم والإشغال.
MD,
        'sort_order' => 3,
      ],
      [
        'name' => 'بيانات القفص / العين',
        'description' => <<<'MD'
القفص هو الموقع الفعلي للأرنب داخل المزرعة، وترتبط به أغلب حركات التسكين والنقل، لذلك يجب أن يكون واضح الهوية وقابلًا للتتبع.
MD,
        'sort_order' => 4,
      ],
      [
        'name' => 'بيانات السلالات',
        'description' => <<<'MD'
وجود السلالة لا يقتصر على تسجيل اسمها، بل يساعد لاحقًا في مقارنة النمو والخصوبة وحجم البطون والنفوق وأداء خطوط الإنتاج.
MD,
        'sort_order' => 5,
      ],
    ];

    foreach ($subsections as $subsection) {
      QuestionnaireSection::query()->updateOrCreate(
        [
          'parent_id' => $mainSection->id,
          'name' => $subsection['name'],
        ],
        [
          'description' => $subsection['description'],
          'sort_order' => $subsection['sort_order'],
        ],
      );
    }
  }
}
