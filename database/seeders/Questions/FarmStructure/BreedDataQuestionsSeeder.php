<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BreedDataQuestionsSeeder extends Seeder {
  public function run(): void {
    DB::transaction(function (): void {
      $mainSection = QuestionnaireSection::query()
        ->whereNull('parent_id')
        ->where('name', 'إدخال البيانات الأساسية للمزرعة')
        ->first();

      if (!$mainSection) {
        throw new RuntimeException(
          'Main questionnaire section "إدخال البيانات الأساسية للمزرعة" was not found. Run the section seeders first.'
        );
      }

      $subsection = QuestionnaireSection::query()
        ->where('parent_id', $mainSection->id)
        ->where('name', 'بيانات السلالات')
        ->first();

      if (!$subsection) {
        throw new RuntimeException(
          'Questionnaire subsection "بيانات السلالات" was not found. Run the section seeders first.'
        );
      }

      $questions = [

        /*
        |--------------------------------------------------------------------------
        | 1. Breed fields
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف السلالة؟',
          'help_text' => 'حدد البيانات الأساسية التي ترى ضرورة تسجيلها لكل سلالة حتى يمكن استخدامها لاحقًا في المقارنات والتقارير.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 1,
          'report_category' => 'field',
          'target_entity' => 'breed',
          'options' => [
            ['label' => 'اسم السلالة', 'value' => 'name', 'sort_order' => 1],
            ['label' => 'كود مختصر', 'value' => 'code', 'sort_order' => 2],
            ['label' => 'الاسم الإنجليزي', 'value' => 'english_name', 'sort_order' => 3],
            ['label' => 'نوع السلالة', 'value' => 'breed_type', 'sort_order' => 4],
            ['label' => 'الغرض الإنتاجي', 'value' => 'production_purpose', 'sort_order' => 5],
            ['label' => 'الوصف / الملاحظات', 'value' => 'notes', 'sort_order' => 6],
            ['label' => 'الحالة', 'value' => 'status', 'sort_order' => 7],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 2. Breed name
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل اسم السلالة يجب أن يكون إلزاميًا وفريدًا داخل النظام؟',
          'help_text' => 'الاسم هو المرجع الرئيسي الذي سيظهر في ملفات الأرانب والتقارير والمقارنات.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 2,
          'report_category' => 'field_rule',
          'target_entity' => 'breed',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 3. Breed code
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد التعامل مع الكود المختصر للسلالة؟',
          'help_text' => 'حدد هل تحتاج السلالة إلى كود مختصر للاستخدام في الأكواد والتقارير أم يكفي الاسم.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 3,
          'report_category' => 'field_rule',
          'target_entity' => 'breed',
          'options' => [
            ['label' => 'لا نحتاج كودًا مختصرًا', 'value' => 'not_required', 'sort_order' => 1],
            ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual', 'sort_order' => 2],
            ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic', 'sort_order' => 3],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 4. English name
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل نحتاج إلى تسجيل الاسم الإنجليزي للسلالة؟',
          'help_text' => 'اختر نعم إذا كان الاسم الإنجليزي سيستخدم في التقارير أو التصدير أو التكاملات المستقبلية.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => false,
          'sort_order' => 4,
          'report_category' => 'field_rule',
          'target_entity' => 'breed',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 5. Breed types
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما أنواع السلالات التي يجب أن يدعمها النظام؟',
          'help_text' => 'حدد التصنيفات الأساسية التي تحتاج إلى تمييزها لكل سلالة.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 5,
          'report_category' => 'lookup_values',
          'target_entity' => 'breed_type',
          'options' => [
            ['label' => 'نقية', 'value' => 'pure', 'sort_order' => 1],
            ['label' => 'هجين', 'value' => 'hybrid', 'sort_order' => 2],
            ['label' => 'غير محددة', 'value' => 'unknown', 'sort_order' => 3],
            ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 6. Breed type management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل أنواع السلالات ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل يتم تمثيل نوع السلالة كقيم ثابتة أم كقائمة يستطيع المدير إضافتها وتعديلها.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 6,
          'report_category' => 'value_management',
          'target_entity' => 'breed_type',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 7. Production purposes
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الأغراض الإنتاجية التي يمكن ربطها بالسلالة؟',
          'help_text' => 'وجود الغرض الإنتاجي يساعد لاحقًا في مقارنة أداء السلالات حسب الهدف المستخدم من أجلها.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 7,
          'report_category' => 'lookup_values',
          'target_entity' => 'breed_purpose',
          'options' => [
            ['label' => 'إنتاج لحم', 'value' => 'meat', 'sort_order' => 1],
            ['label' => 'أمهات / إنتاج', 'value' => 'breeding_does', 'sort_order' => 2],
            ['label' => 'تحسين وراثي', 'value' => 'genetic_improvement', 'sort_order' => 3],
            ['label' => 'متعدد الأغراض', 'value' => 'multi_purpose', 'sort_order' => 4],
            ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 5],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 8. Multiple purposes
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل يمكن ربط السلالة بأكثر من غرض إنتاجي في نفس الوقت؟',
          'help_text' => 'هذا القرار يحدد هل الغرض الإنتاجي قيمة واحدة أم علاقة متعددة القيم.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 8,
          'report_category' => 'relationship_rule',
          'target_entity' => 'breed',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 9. Purpose management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل الأغراض الإنتاجية ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل قائمة الأغراض الإنتاجية ثابتة داخل النظام أم يستطيع المدير إضافة أغراض جديدة وتعديلها.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 9,
          'report_category' => 'value_management',
          'target_entity' => 'breed_purpose',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 10. Hybrid lineage
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'إذا كانت السلالة هجينة، هل نحتاج إلى تسجيل السلالات الأصلية أو مصدر التهجين؟',
          'help_text' => 'اختر نعم إذا كان أصل السلالة الهجينة مهمًا للتحليل الوراثي أو مقارنة الأداء لاحقًا.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => false,
          'sort_order' => 10,
          'report_category' => 'relationship_rule',
          'target_entity' => 'breed',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 11. Breed status
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الحالات التي يمكن أن تكون عليها السلالة داخل النظام؟',
          'help_text' => 'الحالة تحدد هل السلالة متاحة للاستخدام في السجلات الجديدة أم محفوظة فقط للتاريخ.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 11,
          'report_category' => 'lookup_values',
          'target_entity' => 'breed_status',
          'options' => [
            ['label' => 'نشطة', 'value' => 'active', 'sort_order' => 1],
            ['label' => 'غير نشطة', 'value' => 'inactive', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 12. Status management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل حالات السلالات ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل حالات السلالة قيم ثابتة أم قائمة يمكن إدارتها من لوحة التحكم.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 12,
          'report_category' => 'value_management',
          'target_entity' => 'breed_status',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 13. Historical preservation
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'عند إيقاف سلالة، هل يجب منع استخدامها في سجلات جديدة مع الاحتفاظ بها في السجلات التاريخية؟',
          'help_text' => 'هذا يمنع فقدان ارتباط الحيوانات القديمة بسلالتها عند إيقاف السلالة من الاستخدام الجديد.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 13,
          'report_category' => 'business_rule',
          'target_entity' => 'breed',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 14. Analytical usage
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما المؤشرات التي تريد مقارنة السلالات بناءً عليها لاحقًا؟',
          'help_text' => 'هذا السؤال لا ينشئ حقولًا جديدة في السلالة، لكنه يحدد التقارير والتحليلات التي يجب أن تدعم المقارنة بين السلالات.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => false,
          'sort_order' => 14,
          'report_category' => 'report_requirement',
          'target_entity' => 'breed',
          'options' => [
            ['label' => 'النمو', 'value' => 'growth', 'sort_order' => 1],
            ['label' => 'الخصوبة', 'value' => 'fertility', 'sort_order' => 2],
            ['label' => 'حجم البطون', 'value' => 'litter_size', 'sort_order' => 3],
            ['label' => 'النفوق', 'value' => 'mortality', 'sort_order' => 4],
            ['label' => 'أداء خطوط الإنتاج', 'value' => 'production_performance', 'sort_order' => 5],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 15. Additional requirements
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل توجد بيانات أو تصنيفات أو متطلبات أخرى تخص السلالات ولم نتطرق إليها؟',
          'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يحتاج إلى مراجعة قبل اعتماد التصميم الفني.',
          'type' => QuestionType::from('textarea'),
          'is_required' => false,
          'sort_order' => 15,
          'report_category' => 'manual_review',
          'target_entity' => 'breed',
          'options' => [],
        ],
      ];

      foreach ($questions as $questionData) {
        $options = $questionData['options'] ?? [];
        unset($questionData['options']);

        $question = QuestionnaireQuestion::query()->updateOrCreate(
          [
            'section_id' => $subsection->id,
            'title' => $questionData['title'],
          ],
          $questionData,
        );

        $optionValues = collect($options)
          ->pluck('value')
          ->all();

        if ($optionValues !== []) {
          $question->options()
            ->whereNotIn('value', $optionValues)
            ->delete();
        } else {
          $question->options()->delete();
        }

        foreach ($options as $optionData) {
          $question->options()->updateOrCreate(
            [
              'value' => $optionData['value'],
            ],
            [
              'label' => $optionData['label'],
              'sort_order' => $optionData['sort_order'],
            ],
          );
        }
      }
    });
  }
}
