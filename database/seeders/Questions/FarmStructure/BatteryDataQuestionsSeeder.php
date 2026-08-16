<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BatteryDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $mainSection = QuestionnaireSection::query()
                ->whereNull('parent_id')
                ->where('name', 'إدخال البيانات الأساسية للمزرعة')
                ->first();

            if (! $mainSection) {
                throw new RuntimeException(
                    'Main questionnaire section "إدخال البيانات الأساسية للمزرعة" was not found. Run the section seeders first.'
                );
            }

            $subsection = QuestionnaireSection::query()
                ->where('parent_id', $mainSection->id)
                ->where('name', 'بيانات البطارية')
                ->first();

            if (! $subsection) {
                throw new RuntimeException(
                    'Questionnaire subsection "بيانات البطارية" was not found. Run the section seeders first.'
                );
            }

            $questions = [

                /*
                |--------------------------------------------------------------------------
                | 1. Battery fields
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف البطارية؟',
                    'help_text' => 'حدد البيانات الأساسية التي ترى ضرورة تسجيلها لكل بطارية.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'اسم البطارية', 'value' => 'name', 'sort_order' => 1],
                        ['label' => 'كود البطارية', 'value' => 'code', 'sort_order' => 2],
                        ['label' => 'العنبر التابعة له', 'value' => 'barn', 'sort_order' => 3],
                        ['label' => 'نوع / استخدام البطارية', 'value' => 'type', 'sort_order' => 4],
                        ['label' => 'عدد الأدوار أو الصفوف', 'value' => 'rows_count', 'sort_order' => 5],
                        ['label' => 'عدد العيون في كل دور أو صف', 'value' => 'eyes_per_row', 'sort_order' => 6],
                        ['label' => 'إجمالي عدد العيون', 'value' => 'eyes_total', 'sort_order' => 7],
                        ['label' => 'نمط ترقيم العيون', 'value' => 'numbering_pattern', 'sort_order' => 8],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status', 'sort_order' => 9],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at', 'sort_order' => 10],
                        ['label' => 'الشركة أو النوع المصنع', 'value' => 'manufacturer', 'sort_order' => 11],
                        ['label' => 'ملاحظات', 'value' => 'notes', 'sort_order' => 12],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 2. Battery identity
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'كيف تريد تعريف البطارية داخل النظام؟',
                    'help_text' => 'حدد الهوية الأساسية التي سيستخدمها العامل والإدارة للتمييز بين البطاريات.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'اسم فقط', 'value' => 'name_only', 'sort_order' => 1],
                        ['label' => 'كود فقط', 'value' => 'code_only', 'sort_order' => 2],
                        ['label' => 'اسم وكود', 'value' => 'name_and_code', 'sort_order' => 3],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 3. Battery code behavior
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'كيف تريد التعامل مع كود البطارية؟',
                    'help_text' => 'حدد ما إذا كان الكود يتم إدخاله يدويًا أو توليده تلقائيًا بواسطة النظام.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'لا نحتاج كودًا مستقلًا', 'value' => 'not_required', 'sort_order' => 1],
                        ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual', 'sort_order' => 2],
                        ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic', 'sort_order' => 3],
                        ['label' => 'يحتاج الأمر إلى مراجعة قبل الاعتماد', 'value' => 'needs_review', 'sort_order' => 4],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 4. Battery usages/types
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'ما أنواع أو استخدامات البطاريات التي يجب أن يدعمها النظام؟',
                    'help_text' => 'حدد الاستخدامات الفعلية التي قد تختلف بسبب تصميم البطارية أو الغرض التشغيلي منها.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'إناث / أمهات', 'value' => 'female', 'sort_order' => 1],
                        ['label' => 'ذكور', 'value' => 'male', 'sort_order' => 2],
                        ['label' => 'تسمين', 'value' => 'fattening', 'sort_order' => 3],
                        ['label' => 'استخدام عام / متعدد', 'value' => 'general', 'sort_order' => 4],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 5],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 5. Battery type management
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل أنواع البطاريات ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل تظل أنواع البطاريات قيمًا ثابتة داخل النظام أم يستطيع المدير إضافة أنواع جديدة وتعديلها.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'value_management',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 6. Regular row structure
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل يمكن وصف جميع البطاريات بنظام منتظم من أدوار أو صفوف وعدد عيون ثابت داخل كل دور؟',
                    'help_text' => 'هذا القرار يحدد هل يكفي تخزين عدد الأدوار وعدد العيون في كل دور أم نحتاج بنية أكثر مرونة.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],

                /*
                |--------------------------------------------------------------------------
                | 7. Rows and eyes as stored structure
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل عدد الأدوار وعدد العيون في كل دور بيانات أساسية يجب تسجيلها لكل بطارية؟',
                    'help_text' => 'إذا كانت بنية البطاريات منتظمة، يمكن الاعتماد على هذه القيم لإنشاء العيون وحساب السعة.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],

                /*
                |--------------------------------------------------------------------------
                | 8. Total eyes calculation
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'كيف تريد تحديد إجمالي عدد العيون في البطارية؟',
                    'help_text' => 'إذا كان عدد الأدوار وعدد العيون في كل دور معروفًا، يفضل عدم تكرار إجمالي يمكن حسابه تلقائيًا.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'يُحسب تلقائيًا من عدد الأدوار × عدد العيون في الدور', 'value' => 'calculated', 'sort_order' => 1],
                        ['label' => 'يتم إدخاله يدويًا', 'value' => 'manual', 'sort_order' => 2],
                        ['label' => 'يختلف حسب تصميم البطارية ويحتاج طريقة مرنة', 'value' => 'flexible_structure', 'sort_order' => 3],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 9. Automatic cage/eye generation
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل يجب أن ينشئ النظام العيون / الأقفاص تلقائيًا عند إنشاء البطارية؟',
                    'help_text' => 'مثال: بطارية من 3 أدوار × 4 عيون يمكن للنظام أن ينشئ لها 12 عينًا تلقائيًا بدل إدخال كل عين يدويًا.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],

                /*
                |--------------------------------------------------------------------------
                | 10. Eye numbering pattern
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'ما نمط ترقيم العيون الأنسب داخل البطارية؟',
                    'help_text' => 'حدد الطريقة التي تطابق الترقيم الميداني الفعلي وتكون واضحة للعامل عند التسكين أو النقل.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'numbering_rule',
                    'target_entity' => 'cage',
                    'options' => [
                        ['label' => 'ترقيم تسلسلي داخل البطارية مثل B01-01، B01-02', 'value' => 'sequential', 'sort_order' => 1],
                        ['label' => 'ترقيم مرتبط بالدور / الصف مثل B01-R1-01', 'value' => 'row_based', 'sort_order' => 2],
                        ['label' => 'طريقة أخرى يحددها التشغيل الفعلي', 'value' => 'custom', 'sort_order' => 3],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 11. Battery statuses
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'ما الحالات التشغيلية التي يمكن أن تكون عليها البطارية؟',
                    'help_text' => 'حدد الحالات التي يحتاج النظام إلى تمثيلها للبطارية.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'battery_status',
                    'options' => [
                        ['label' => 'نشطة', 'value' => 'active', 'sort_order' => 1],
                        ['label' => 'متوقفة', 'value' => 'stopped', 'sort_order' => 2],
                        ['label' => 'تحت الصيانة', 'value' => 'maintenance', 'sort_order' => 3],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 4],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 12. Status management
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل حالات البطارية ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل يتم تمثيل الحالات كقيم ثابتة أم كقائمة يمكن إضافتها وتعديلها من لوحة التحكم.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'value_management',
                    'target_entity' => 'battery_status',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 13. Manufacturer/type information
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل نحتاج إلى تسجيل الشركة المصنعة أو النوع / الموديل للبطارية؟',
                    'help_text' => 'اختر نعم فقط إذا كانت هذه المعلومة لها قيمة تشغيلية أو تحليلية فعلية.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => false,
                    'sort_order' => 13,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],

                /*
                |--------------------------------------------------------------------------
                | 14. Usage flexibility
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل استخدام البطارية ثابت أم يمكن أن يتغير بمرور الوقت؟',
                    'help_text' => 'هذا القرار يحدد هل يكفي حفظ الاستخدام الحالي فقط أم نحتاج إلى تتبع تغييرات الاستخدام تاريخيًا.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'استخدام ثابت لا يتغير عادة', 'value' => 'fixed_usage', 'sort_order' => 1],
                        ['label' => 'يمكن تغيير الاستخدام مع الاحتفاظ بالحالة الحالية فقط', 'value' => 'changeable_current_only', 'sort_order' => 2],
                        ['label' => 'يمكن تغيير الاستخدام ويجب الاحتفاظ بتاريخ التغييرات', 'value' => 'changeable_with_history', 'sort_order' => 3],
                    ],
                ],

                /*
                |--------------------------------------------------------------------------
                | 15. Disable / maintenance rule
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'عند إيقاف البطارية أو وضعها تحت الصيانة، هل يجب منع التسكين الجديد في جميع العيون التابعة لها مع الاحتفاظ بالتاريخ السابق؟',
                    'help_text' => 'هذا يحدد أثر الحالة التشغيلية للبطارية على العيون التابعة لها دون حذف سجلات التسكين والحركات السابقة.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'business_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],

                /*
                |--------------------------------------------------------------------------
                | 16. Additional requirements
                |--------------------------------------------------------------------------
                */
                [
                    'title' => 'هل توجد بيانات أو خصائص أو متطلبات أخرى تخص البطارية ولم نتطرق إليها؟',
                    'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يحتاج إلى مراجعة قبل اعتماد التصميم الفني.',
                    'type' => QuestionType::from('textarea'),
                    'is_required' => false,
                    'sort_order' => 16,
                    'report_category' => 'manual_review',
                    'target_entity' => 'battery',
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
