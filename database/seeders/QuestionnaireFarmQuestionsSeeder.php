<?php

namespace Database\Seeders;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QuestionnaireFarmQuestionsSeeder extends Seeder {
  public function run(): void {
    DB::transaction(function (): void {
      $section = QuestionnaireSection::query()
        ->where('name', 'بيانات المزرعة')
        ->whereNotNull('parent_id')
        ->first();

      if (!$section) {
        throw new RuntimeException(
          'Subsection "بيانات المزرعة" was not found. Run QuestionnaireSectionSeeder first.'
        );
      }

      $questions = [

        /*
        |--------------------------------------------------------------------------
        | Q01 — Farm Fields
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟',
          'help_text' => 'حدد البيانات الأساسية التي ترى ضرورة تسجيلها لكل مزرعة.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 1,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'اسم المزرعة', 'value' => 'name'],
            ['label' => 'كود المزرعة', 'value' => 'code'],
            ['label' => 'رقم الهاتف', 'value' => 'phone'],
            ['label' => 'المحافظة', 'value' => 'governorate'],
            ['label' => 'المنطقة', 'value' => 'area'],
            ['label' => 'العنوان التفصيلي', 'value' => 'address'],
            ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
            ['label' => 'حالة المزرعة', 'value' => 'status'],
            ['label' => 'ملاحظات عامة', 'value' => 'notes'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q02 — Farm Name Requirement
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل اسم المزرعة يجب أن يكون من البيانات الإلزامية؟',
          'help_text' => 'حدد ما إذا كان يمكن إنشاء مزرعة في النظام بدون تسجيل اسم لها.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 2,
          'report_category' => 'field',
          'target_entity' => 'farm',
        ],

        /*
        |--------------------------------------------------------------------------
        | Q03 — Farm Code
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'بالنسبة لكود المزرعة، كيف تفضل التعامل معه؟',
          'help_text' => 'الكود هو معرف مختصر يمكن استخدامه داخل النظام بجانب اسم المزرعة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 3,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'لا نحتاج كودًا للمزرعة', 'value' => 'not_required'],
            ['label' => 'المستخدم يكتب الكود يدويًا', 'value' => 'manual'],
            ['label' => 'النظام يولد الكود تلقائيًا', 'value' => 'automatic'],
            ['label' => 'يحتاج مراجعة قبل الاعتماد', 'value' => 'needs_review'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q04 — Farm Activities
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الأنشطة التي يمكن أن تعمل بها المزرعة؟',
          'help_text' => 'يمكن اختيار أكثر من نشاط إذا كانت المزرعة تجمع بين أكثر من نشاط.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 4,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'إنتاج', 'value' => 'production'],
            ['label' => 'تسمين', 'value' => 'fattening'],
            ['label' => 'تربية سلالات', 'value' => 'breeding'],
            ['label' => 'أكثر من نشاط في نفس المزرعة', 'value' => 'multiple'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q05 — Farm Activity Management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل قائمة أنشطة المزرعة ثابتة أم يجب أن يستطيع المدير إضافة وتعديل الأنشطة؟',
          'help_text' => 'السؤال يحدد هل الأنشطة تظل مجموعة محددة مسبقًا أم تحتاج إلى إدارة مرنة من لوحة التحكم.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 5,
          'report_category' => 'lookup',
          'target_entity' => 'farm_activity',
          'options' => [
            ['label' => 'قائمة ثابتة لا تتغير', 'value' => 'fixed'],
            ['label' => 'يمكن للمدير إضافة وتعديل الأنشطة', 'value' => 'managed'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q06 — Owner / Manager
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد التعامل مع مالك المزرعة والمسؤول عن التشغيل اليومي؟',
          'help_text' => 'نريد تحديد ما إذا كانت بيانات المالك والمسؤول تمثل شخصًا واحدًا أم أدوارًا منفصلة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 6,
          'report_category' => 'relationship',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'يكفي تسجيل شخص واحد', 'value' => 'single_person'],
            ['label' => 'يجب فصل المالك عن المسؤول عن التشغيل', 'value' => 'separate_owner_manager'],
            ['label' => 'قد يوجد أكثر من مسؤول عن التشغيل', 'value' => 'multiple_managers'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q07 — Contact Information
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما بيانات الاتصال التي ترى ضرورة تسجيلها للمزرعة؟',
          'help_text' => 'اختر بيانات الاتصال المطلوبة فعليًا في ملف المزرعة.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => false,
          'sort_order' => 7,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'رقم هاتف واحد', 'value' => 'phone'],
            ['label' => 'أكثر من رقم هاتف', 'value' => 'multiple_phones'],
            ['label' => 'بريد إلكتروني', 'value' => 'email'],
            ['label' => 'واتساب', 'value' => 'whatsapp'],
            ['label' => 'لا نحتاج بيانات اتصال مستقلة للمزرعة', 'value' => 'none'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q08 — Location Fields
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما مستوى تفاصيل الموقع والعنوان الذي نحتاج إلى تسجيله لكل مزرعة؟',
          'help_text' => 'حدد مكونات الموقع التي يجب أن تكون متاحة في بيانات المزرعة.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 8,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'المحافظة', 'value' => 'governorate'],
            ['label' => 'المركز / المدينة', 'value' => 'city'],
            ['label' => 'المنطقة / القرية', 'value' => 'area'],
            ['label' => 'العنوان التفصيلي', 'value' => 'address'],
            ['label' => 'الموقع الجغرافي على الخريطة', 'value' => 'geolocation'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q09 — Geolocation Method
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'إذا احتجنا تسجيل الموقع الجغرافي للمزرعة، كيف تريد إدخاله؟',
          'help_text' => 'هذا السؤال يظهر فقط عند اختيار الموقع الجغرافي على الخريطة في السؤال السابق.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => false,
          'sort_order' => 9,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'depends_on_sort_order' => 8,
          'dependency_operator' => QuestionDependencyOperator::from('contains'),
          'dependency_value' => 'geolocation',
          'options' => [
            ['label' => 'اختيار نقطة على الخريطة', 'value' => 'map'],
            ['label' => 'إدخال Latitude / Longitude', 'value' => 'coordinates'],
            ['label' => 'دعم الطريقتين', 'value' => 'both'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q10 — Location Management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'كيف تريد إدارة المحافظات والمناطق داخل النظام؟',
          'help_text' => 'حدد هل بيانات المواقع تعتمد على قوائم منظمة أم نصوص حرة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 10,
          'report_category' => 'lookup',
          'target_entity' => 'location',
          'options' => [
            ['label' => 'قائمة ثابتة جاهزة', 'value' => 'fixed'],
            ['label' => 'قائمة يمكن للمدير إضافتها وتعديلها', 'value' => 'managed'],
            ['label' => 'مجرد نص يكتبه المستخدم', 'value' => 'text'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q11 — Farm Status Values
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'ما الحالات التشغيلية التي يمكن أن تكون عليها المزرعة؟',
          'help_text' => 'التصور الحالي يتضمن نشطة ومتوقفة، ويمكنك تحديد الحالات التي تراها مطلوبة.',
          'type' => QuestionType::from('multi_choice'),
          'is_required' => true,
          'sort_order' => 11,
          'report_category' => 'field',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'نشطة', 'value' => 'active'],
            ['label' => 'متوقفة', 'value' => 'stopped'],
            ['label' => 'تحت الصيانة', 'value' => 'maintenance'],
            ['label' => 'أخرى', 'value' => 'other'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q12 — Farm Status Management
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل حالات المزرعة ثابتة أم يجب أن يستطيع المدير إضافتها وتعديلها؟',
          'help_text' => 'الإجابة ستساعد لاحقًا في تحديد هل الحالات قيم ثابتة أم قائمة تتم إدارتها من لوحة التحكم.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 12,
          'report_category' => 'lookup',
          'target_entity' => 'farm_status',
          'options' => [
            ['label' => 'ثابتة لا تتغير', 'value' => 'fixed'],
            ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q13 — Multi Farm Support
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل النظام يجب أن يدعم إدارة أكثر من مزرعة؟',
          'help_text' => 'التصور الحالي يسمح بالتوسع لأكثر من مزرعة مع فصل بيانات كل مزرعة.',
          'type' => QuestionType::from('single_choice'),
          'is_required' => true,
          'sort_order' => 13,
          'report_category' => 'relationship',
          'target_entity' => 'farm',
          'options' => [
            ['label' => 'مزرعة واحدة فقط', 'value' => 'single_farm'],
            ['label' => 'أكثر من مزرعة من البداية', 'value' => 'multi_farm'],
            ['label' => 'مزرعة واحدة حاليًا مع دعم التوسع مستقبلًا', 'value' => 'future_multi_farm'],
          ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Q14 — Per-Farm Settings
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'عند وجود أكثر من مزرعة، هل يجب أن تكون لكل مزرعة إعدادات تشغيل وإنتاج مستقلة؟',
          'help_text' => 'مثال: إعدادات التلقيح والفطام والتنبيهات قد تختلف من مزرعة إلى أخرى.',
          'type' => QuestionType::from('yes_no'),
          'is_required' => true,
          'sort_order' => 14,
          'report_category' => 'rule',
          'target_entity' => 'farm',
        ],

        /*
        |--------------------------------------------------------------------------
        | Q15 — Missing Requirements
        |--------------------------------------------------------------------------
        */
        [
          'title' => 'هل توجد بيانات أو متطلبات أخرى تخص المزرعة غير موجودة في التصور الحالي؟',
          'help_text' => 'اكتب أي نقطة ترى أنها مهمة ولم يتم تغطيتها في الأسئلة السابقة.',
          'type' => QuestionType::from('textarea'),
          'is_required' => false,
          'sort_order' => 15,
          'report_category' => 'general',
          'target_entity' => 'farm',
        ],
      ];

      $createdQuestions = [];

      foreach ($questions as $questionData) {
        $dependsOnSortOrder = $questionData['depends_on_sort_order'] ?? null;
        $options = $questionData['options'] ?? [];

        unset(
          $questionData['depends_on_sort_order'],
          $questionData['options'],
        );

        if ($dependsOnSortOrder !== null) {
          if (!isset($createdQuestions[$dependsOnSortOrder])) {
            throw new RuntimeException(
              "Dependency question with sort order {$dependsOnSortOrder} was not created."
            );
          }

          $questionData['depends_on_question_id'] =
            $createdQuestions[$dependsOnSortOrder]->id;
        } else {
          $questionData['depends_on_question_id'] = null;
          $questionData['dependency_operator'] = null;
          $questionData['dependency_value'] = null;
        }

        $question = QuestionnaireQuestion::query()->updateOrCreate(
          [
            'section_id' => $section->id,
            'sort_order' => $questionData['sort_order'],
          ],
          $questionData,
        );

        /*
         * Pilot synchronization:
         * Keep options exactly aligned with this Seeder.
         *
         * Do not continue using this destructive option sync once
         * real questionnaire answers depend on these option values.
         */
        $question->options()->delete();

        foreach ($options as $index => $option) {
          $question->options()->create([
            'label' => $option['label'],
            'value' => $option['value'],
            'sort_order' => $index + 1,
          ]);
        }

        $createdQuestions[$question->sort_order] = $question;
      }
    });
  }
}