<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CageDataQuestionsSeeder extends Seeder {
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
        ->where('name', 'بيانات القفص / العين')
        ->first();

      if (!$subsection) {
        throw new RuntimeException(
          'Questionnaire subsection "بيانات القفص / العين" was not found. Run the section seeders first.'
        );
      }

      $questions = [

        /*
        |--------------------------------------------------------------------------
        | 1. Cage fields
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف القفص / العين؟',
          'help_text' => 'حدد البيانات الأساسية التي ترى ضرورة تسجيلها لكل قفص أو عين.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 1,
          'report_category' => 'field',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'كود القفص / العين', 'value' => 'code', 'sort_order' => 1],
            ['label' => 'البطارية التابع لها', 'value' => 'battery', 'sort_order' => 2],
            ['label' => 'رقم الدور / الصف', 'value' => 'row_number', 'sort_order' => 3],
            ['label' => 'ترتيب العين داخل الدور / الصف', 'value' => 'position_in_row', 'sort_order' => 4],
            ['label' => 'نوع القفص الفعلي', 'value' => 'physical_type', 'sort_order' => 5],
            ['label' => 'الاستخدام الحالي للقفص', 'value' => 'current_usage', 'sort_order' => 6],
            ['label' => 'السعة القصوى', 'value' => 'capacity', 'sort_order' => 7],
            ['label' => 'الحالة التشغيلية', 'value' => 'status', 'sort_order' => 8],
            ['label' => 'تاريخ بدء الاستخدام', 'value' => 'started_at', 'sort_order' => 9],
            ['label' => 'الأبعاد / المواصفات', 'value' => 'dimensions', 'sort_order' => 10],
            ['label' => 'ملاحظات', 'value' => 'notes', 'sort_order' => 11],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 2. Cage code
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد إنشاء كود القفص / العين؟',
          'help_text' => 'الكود يجب أن يكون واضحًا وفريدًا ويمكن للعامل استخدامه ميدانيًا عند التسكين أو النقل.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 2,
          'report_category' => 'numbering_rule',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'يتم توليده تلقائيًا من كود البطارية والترتيب', 'value' => 'automatic_from_battery', 'sort_order' => 1],
            ['label' => 'يتم إدخاله يدويًا', 'value' => 'manual', 'sort_order' => 2],
            ['label' => 'يدعم الطريقتين مع ضمان عدم التكرار', 'value' => 'both', 'sort_order' => 3],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 3. Physical cage types
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل نحتاج إلى فصل نوع القفص الفعلي عن استخدامه الحالي؟',
          'help_text' => 'نوع القفص يصف التصميم أو المقاس والتجهيزات، بينما الاستخدام الحالي يصف الغرض التشغيلي مثل أنثى أو ذكر أو تسمين.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 3,
          'report_category' => 'architecture_rule',
          'target_entity' => 'cage',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 4. Cage physical types
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما أنواع القفص الفعلية التي يجب أن يدعمها النظام؟',
          'help_text' => 'اختر الأنواع التي تختلف فعليًا في التصميم أو المقاس أو التجهيز، وليس مجرد الاستخدام التشغيلي.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => false,
          'sort_order' => 4,
          'report_category' => 'lookup_values',
          'target_entity' => 'cage_type',
          'options' => [
            ['label' => 'قفص فردي', 'value' => 'individual', 'sort_order' => 1],
            ['label' => 'قفص جماعي', 'value' => 'group', 'sort_order' => 2],
            ['label' => 'قفص مجهز لبيت ولادة', 'value' => 'maternity_ready', 'sort_order' => 3],
            ['label' => 'تصميم آخر', 'value' => 'other', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 5. Cage type management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل أنواع القفص الفعلية ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل يتم تمثيل أنواع القفص كقيم ثابتة أم كقائمة يستطيع المدير إضافتها وتعديلها.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => false,
          'sort_order' => 5,
          'report_category' => 'value_management',
          'target_entity' => 'cage_type',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 6. Cage usages
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الاستخدامات التشغيلية التي يمكن تخصيص القفص لها؟',
          'help_text' => 'حدد الاستخدامات الحالية التي يجب أن يستطيع النظام تمييزها لكل قفص.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 6,
          'report_category' => 'lookup_values',
          'target_entity' => 'cage_usage',
          'options' => [
            ['label' => 'أنثى إنتاج', 'value' => 'production_female', 'sort_order' => 1],
            ['label' => 'ذكر', 'value' => 'male', 'sort_order' => 2],
            ['label' => 'فطام', 'value' => 'weaning', 'sort_order' => 3],
            ['label' => 'تسمين', 'value' => 'fattening', 'sort_order' => 4],
            ['label' => 'عزل / حجر', 'value' => 'quarantine', 'sort_order' => 5],
            ['label' => 'استخدام آخر', 'value' => 'other', 'sort_order' => 6],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 7. Cage usage management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل استخدامات الأقفاص ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل قائمة الاستخدامات تظل ثابتة أم يستطيع المدير إضافة استخدامات جديدة وتعديلها.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 7,
          'report_category' => 'value_management',
          'target_entity' => 'cage_usage',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 8. Usage change/history
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل يمكن تغيير استخدام القفص بمرور الوقت؟',
          'help_text' => 'هذا القرار يحدد هل يكفي حفظ الاستخدام الحالي أم يجب الاحتفاظ بتاريخ تغير استخدام القفص.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 8,
          'report_category' => 'workflow_rule',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'الاستخدام ثابت عادة ولا يتغير', 'value' => 'fixed_usage', 'sort_order' => 1],
            ['label' => 'يمكن تغييره مع حفظ الاستخدام الحالي فقط', 'value' => 'changeable_current_only', 'sort_order' => 2],
            ['label' => 'يمكن تغييره ويجب الاحتفاظ بتاريخ التغييرات', 'value' => 'changeable_with_history', 'sort_order' => 3],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 9. Capacity
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد تحديد السعة القصوى للقفص؟',
          'help_text' => 'ليست كل الأقفاص فردية؛ أقفاص الفطام أو التسمين قد تحتوي مجموعة من الأرانب.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 9,
          'report_category' => 'field_rule',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'تُحدد يدويًا لكل قفص', 'value' => 'manual_per_cage', 'sort_order' => 1],
            ['label' => 'تأتي كقيمة افتراضية من نوع / استخدام القفص مع إمكانية تعديلها', 'value' => 'default_by_type_editable', 'sort_order' => 2],
            ['label' => 'جميع الأقفاص فردية والسعة دائمًا 1', 'value' => 'always_one', 'sort_order' => 3],
            ['label' => 'تحتاج إلى قاعدة أخرى حسب التشغيل', 'value' => 'other_rule', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 10. Occupancy calculation
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل يجب أن يحسب النظام عدد الأرانب الحالي والأماكن المتاحة في القفص تلقائيًا؟',
          'help_text' => 'المقترح أن يتم حساب الإشغال من سجلات التسكين والحركات بدل إدخال العدد الحالي يدويًا.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 10,
          'report_category' => 'calculation_rule',
          'target_entity' => 'cage',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 11. Cage statuses
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الحالات التشغيلية التي يمكن أن يكون عليها القفص؟',
          'help_text' => 'حالة القفص منفصلة عن نوعه واستخدامه الحالي.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 11,
          'report_category' => 'lookup_values',
          'target_entity' => 'cage_status',
          'options' => [
            ['label' => 'متاح', 'value' => 'available', 'sort_order' => 1],
            ['label' => 'مشغول', 'value' => 'occupied', 'sort_order' => 2],
            ['label' => 'محجوز', 'value' => 'reserved', 'sort_order' => 3],
            ['label' => 'تحت الصيانة', 'value' => 'maintenance', 'sort_order' => 4],
            ['label' => 'معطل', 'value' => 'disabled', 'sort_order' => 5],
            ['label' => 'خارج الخدمة', 'value' => 'out_of_service', 'sort_order' => 6],
            ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 7],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 12. Status management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل حالات القفص ثابتة أم قابلة للإدارة؟',
          'help_text' => 'حدد هل يتم تمثيل الحالات كقيم ثابتة أم كقائمة يمكن إدارتها من لوحة التحكم.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 12,
          'report_category' => 'value_management',
          'target_entity' => 'cage_status',
          'options' => [
            ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 13. Group housing rules
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل يجب أن يطبق النظام قواعد على عدد ونوع الأرانب المسموح بتسكينها معًا داخل القفص؟',
          'help_text' => 'مثل منع تجاوز السعة أو منع جمع حالات أو فئات لا يسمح التشغيل بجمعها معًا.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 13,
          'report_category' => 'business_rule',
          'target_entity' => 'cage_occupancy',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 14. Adult animal occupancy
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد التعامل مع تسكين الذكور والإناث البالغة؟',
          'help_text' => 'حدد القاعدة التشغيلية العامة، ويمكن لاحقًا إضافة استثناءات إذا احتاجها الواقع.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 14,
          'report_category' => 'business_rule',
          'target_entity' => 'cage_occupancy',
          'options' => [
            ['label' => 'كل ذكر أو أنثى بالغة في قفص منفرد', 'value' => 'single_adult_per_cage', 'sort_order' => 1],
            ['label' => 'قد يسمح بأكثر من حيوان بالغ حسب نوع / استخدام القفص', 'value' => 'depends_on_cage_usage', 'sort_order' => 2],
            ['label' => 'تحتاج القاعدة إلى مراجعة قبل الاعتماد', 'value' => 'needs_review', 'sort_order' => 3],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 15. Nest box handling
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف يجب التعامل مع بيت الولادة بالنسبة لقفص الأنثى؟',
          'help_text' => 'المصدر يفرق بين كون بيت الولادة جزءًا ثابتًا من القفص وبين تركيبه كجزء من تجهيز الأنثى قبل الولادة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 15,
          'report_category' => 'workflow_rule',
          'target_entity' => 'nest_box',
          'options' => [
            ['label' => 'جزء ثابت من القفص', 'value' => 'fixed_part_of_cage', 'sort_order' => 1],
            ['label' => 'يتم تركيبه وإزالته حسب مرحلة الحمل / الولادة', 'value' => 'workflow_installed', 'sort_order' => 2],
            ['label' => 'لا نحتاج إلى تتبعه في النظام', 'value' => 'not_tracked', 'sort_order' => 3],
            ['label' => 'يحتاج الأمر إلى مراجعة', 'value' => 'needs_review', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 16. Dimensions/specifications
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل توجد مواصفات للقفص تستحق التسجيل مثل الأبعاد أو التجهيزات؟',
          'help_text' => 'اختر نعم إذا كانت هذه المواصفات تؤثر فعليًا على التشغيل أو السعة أو نوع الاستخدام.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => false,
          'sort_order' => 16,
          'report_category' => 'field_rule',
          'target_entity' => 'cage',
          'options' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | 17. Cleaning / disinfection workflow
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ماذا يحدث للقفص بعد خروجه من الإشغال وقبل استخدامه مرة أخرى؟',
          'help_text' => 'حدد هل يصبح القفص متاحًا مباشرة أم توجد عملية تنظيف / تعقيم يجب تسجيلها أو تنفيذها أولًا.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 17,
          'report_category' => 'workflow_rule',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'يصبح متاحًا مباشرة', 'value' => 'available_immediately', 'sort_order' => 1],
            ['label' => 'التنظيف / التعقيم إجراء اختياري يمكن تسجيله', 'value' => 'optional_cleaning', 'sort_order' => 2],
            ['label' => 'يجب تنفيذ التنظيف / التعقيم قبل أن يصبح القفص متاحًا', 'value' => 'required_before_available', 'sort_order' => 3],
            ['label' => 'تحتاج العملية إلى مراجعة', 'value' => 'needs_review', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 18. QR / Barcode
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل تحتاج الأقفاص مستقبلًا إلى كود مرئي للوصول السريع إلى بياناتها؟',
          'help_text' => 'مثل وضع QR Code أو Barcode على القفص ليستخدمه العامل للوصول إلى سجله مباشرة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => false,
          'sort_order' => 18,
          'report_category' => 'ui_requirement',
          'target_entity' => 'cage',
          'options' => [
            ['label' => 'لا نحتاج ذلك حاليًا', 'value' => 'none', 'sort_order' => 1],
            ['label' => 'QR Code', 'value' => 'qr_code', 'sort_order' => 2],
            ['label' => 'Barcode', 'value' => 'barcode', 'sort_order' => 3],
            ['label' => 'دعم الطريقتين', 'value' => 'both', 'sort_order' => 4],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 19. Additional requirements
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل توجد بيانات أو قواعد أو متطلبات أخرى تخص القفص / العين ولم نتطرق إليها؟',
          'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يحتاج إلى مراجعة قبل اعتماد التصميم الفني.',
          'type' => QuestionType::from('textarea'),
          'is_required' => false,
          'sort_order' => 19,
          'report_category' => 'manual_review',
          'target_entity' => 'cage',
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
