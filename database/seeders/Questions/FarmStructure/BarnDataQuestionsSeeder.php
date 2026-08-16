<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BarnDataQuestionsSeeder extends Seeder
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
                ->where('name', 'بيانات العنبر')
                ->first();

            if (! $subsection) {
                throw new RuntimeException(
                    'Questionnaire subsection "بيانات العنبر" was not found. Run the section seeders first.'
                );
            }

            $questions = [
                [
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف العنبر؟',
                    'help_text' => 'حدد البيانات الأساسية التي ترى ضرورة تسجيلها لكل عنبر.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'اسم العنبر', 'value' => 'name', 'sort_order' => 1],
                        ['label' => 'كود العنبر', 'value' => 'code', 'sort_order' => 2],
                        ['label' => 'المزرعة التابع لها', 'value' => 'farm', 'sort_order' => 3],
                        ['label' => 'نوع / استخدام العنبر', 'value' => 'usage', 'sort_order' => 4],
                        ['label' => 'السعة الاستيعابية', 'value' => 'capacity', 'sort_order' => 5],
                        ['label' => 'حالة العنبر', 'value' => 'status', 'sort_order' => 6],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at', 'sort_order' => 7],
                        ['label' => 'وصف الموقع داخل المزرعة', 'value' => 'location_description', 'sort_order' => 8],
                        ['label' => 'ملاحظات', 'value' => 'notes', 'sort_order' => 9],
                        ['label' => 'خصائص بيئية / إنشائية', 'value' => 'environmental_features', 'sort_order' => 10],
                    ],
                ],
                [
                    'title' => 'هل اسم العنبر يجب أن يكون من البيانات الإلزامية؟',
                    'help_text' => 'حدد ما إذا كان يمكن إنشاء عنبر بدون اسم.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'title' => 'كيف تريد التعامل مع كود العنبر؟',
                    'help_text' => 'حدد ما إذا كان كود العنبر غير مطلوب أو يتم إدخاله أو توليده تلقائيًا.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'field_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'لا نحتاج كودًا للعنبر', 'value' => 'not_required', 'sort_order' => 1],
                        ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual', 'sort_order' => 2],
                        ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic', 'sort_order' => 3],
                        ['label' => 'يحتاج الأمر إلى مراجعة قبل الاعتماد', 'value' => 'needs_review', 'sort_order' => 4],
                    ],
                ],
                [
                    'title' => 'ما الاستخدامات التي يمكن تخصيص العنبر لها؟',
                    'help_text' => 'حدد الاستخدامات الفعلية التي قد يعمل بها العنبر.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'إنتاج', 'value' => 'production', 'sort_order' => 1],
                        ['label' => 'فطام', 'value' => 'weaning', 'sort_order' => 2],
                        ['label' => 'تسمين', 'value' => 'fattening', 'sort_order' => 3],
                        ['label' => 'حجر / عزل', 'value' => 'quarantine', 'sort_order' => 4],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 5],
                    ],
                ],
                [
                    'title' => 'هل يمكن للعنبر الواحد أن يخدم أكثر من استخدام في نفس الوقت؟',
                    'help_text' => 'مثال: أن يعمل العنبر للإنتاج والفطام في نفس الفترة.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'title' => 'هل أنواع استخدام العنابر ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل ستظل قائمة الاستخدامات ثابتة داخل النظام أم يمكن للمدير إضافة أنواع جديدة وتعديلها.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'value_management',
                    'target_entity' => 'barn_usage',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
                    ],
                ],
                [
                    'title' => 'ما الحالات التشغيلية التي يمكن أن يكون عليها العنبر؟',
                    'help_text' => 'حدد الحالات التي تحتاج إلى تمثيلها في النظام.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'نشط', 'value' => 'active', 'sort_order' => 1],
                        ['label' => 'متوقف', 'value' => 'stopped', 'sort_order' => 2],
                        ['label' => 'تحت الصيانة', 'value' => 'maintenance', 'sort_order' => 3],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 4],
                    ],
                ],
                [
                    'title' => 'هل حالات العنبر ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل يتم تمثيل الحالات كقيم ثابتة أم كقائمة يمكن إدارتها من لوحة التحكم.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'value_management',
                    'target_entity' => 'barn_status',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed', 'sort_order' => 1],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed', 'sort_order' => 2],
                    ],
                ],
                [
                    'title' => 'كيف تريد تحديد السعة الاستيعابية للعنبر؟',
                    'help_text' => 'حدد هل السعة قيمة يكتبها المستخدم أم يتم احتسابها من البطاريات والأقفاص المرتبطة بالعنبر.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'لا نحتاج تسجيل السعة الاستيعابية', 'value' => 'not_required', 'sort_order' => 1],
                        ['label' => 'يتم إدخال السعة يدويًا', 'value' => 'manual', 'sort_order' => 2],
                        ['label' => 'يتم حسابها تلقائيًا من البطاريات والأقفاص', 'value' => 'calculated', 'sort_order' => 3],
                        ['label' => 'نحتفظ بسعة تصميمية منفصلة ونحسب السعة الفعلية تلقائيًا', 'value' => 'planned_and_calculated', 'sort_order' => 4],
                    ],
                ],
                [
                    'title' => 'ما الخصائص البيئية أو الإنشائية التي تحتاج إلى تسجيلها لكل عنبر؟',
                    'help_text' => 'اختر فقط الخصائص التي لها قيمة فعلية في تشغيل وإدارة العنبر.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => false,
                    'sort_order' => 10,
                    'report_category' => 'field',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'المساحة', 'value' => 'area', 'sort_order' => 1],
                        ['label' => 'نظام التهوية', 'value' => 'ventilation', 'sort_order' => 2],
                        ['label' => 'نظام التبريد', 'value' => 'cooling', 'sort_order' => 3],
                        ['label' => 'نظام التدفئة', 'value' => 'heating', 'sort_order' => 4],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 5],
                    ],
                ],
                [
                    'title' => 'كيف تريد التعامل مع خصائص العنبر الإضافية مستقبلًا؟',
                    'help_text' => 'هذا السؤال يساعد في تحديد ما إذا كانت خصائص العنبر أعمدة ثابتة أم خصائص قابلة للإضافة لاحقًا.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => false,
                    'sort_order' => 11,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'حقول ثابتة في ملف العنبر', 'value' => 'fixed_fields', 'sort_order' => 1],
                        ['label' => 'خصائص يمكن للإدارة إضافتها وإدارتها', 'value' => 'managed_properties', 'sort_order' => 2],
                        ['label' => 'لم يتحدد بعد', 'value' => 'undecided', 'sort_order' => 3],
                    ],
                ],
                [
                    'title' => 'كيف يتم تحديد عدد البطاريات الموجودة داخل العنبر؟',
                    'help_text' => 'المفضل ألا يتم تكرار قيمة يمكن للنظام استخراجها من البطاريات المرتبطة بالعنبر.',
                    'type' => QuestionType::from('single_choice'),
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'يُحسب تلقائيًا من البطاريات المرتبطة بالعنبر', 'value' => 'calculated', 'sort_order' => 1],
                        ['label' => 'يُدخل يدويًا', 'value' => 'manual', 'sort_order' => 2],
                        ['label' => 'عدد فعلي محسوب مع قيمة تخطيطية منفصلة', 'value' => 'planned_and_calculated', 'sort_order' => 3],
                    ],
                ],
                [
                    'title' => 'هل تريد أن يحسب النظام تلقائيًا مؤشرات الإشغال الحالية للعنبر؟',
                    'help_text' => 'مثل عدد الأقفاص، الأقفاص المشغولة، الأقفاص المتاحة، وعدد الأرانب الموجودة حاليًا.',
                    'type' => QuestionType::from('yes_no'),
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'title' => 'ما الأسباب التي قد تستدعي نقل الحيوان من عنبر إلى آخر؟',
                    'help_text' => 'حدد الأسباب التشغيلية التي يجب أن يستطيع النظام تسجيلها عند نقل الحيوانات بين العنابر.',
                    'type' => QuestionType::from('multi_choice'),
                    'is_required' => false,
                    'sort_order' => 14,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'barn_transfer',
                    'options' => [
                        ['label' => 'تغيير المرحلة الإنتاجية', 'value' => 'production_stage_change', 'sort_order' => 1],
                        ['label' => 'التسكين أو إعادة التوزيع', 'value' => 'rehousing', 'sort_order' => 2],
                        ['label' => 'الحجر / العزل', 'value' => 'quarantine', 'sort_order' => 3],
                        ['label' => 'صيانة العنبر', 'value' => 'maintenance', 'sort_order' => 4],
                        ['label' => 'ازدحام / إعادة توزيع السعة', 'value' => 'capacity_redistribution', 'sort_order' => 5],
                        ['label' => 'قرار إداري', 'value' => 'administrative', 'sort_order' => 6],
                        ['label' => 'أخرى', 'value' => 'other', 'sort_order' => 7],
                    ],
                ],
                [
                    'title' => 'هل توجد بيانات أو خصائص أو متطلبات أخرى تخص العنبر ولم نتطرق إليها؟',
                    'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يحتاج إلى مراجعة قبل اعتماد التصميم الفني.',
                    'type' => QuestionType::from('textarea'),
                    'is_required' => false,
                    'sort_order' => 15,
                    'report_category' => 'manual_review',
                    'target_entity' => 'barn',
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

                $optionValues = collect($options)->pluck('value')->all();

                if ($optionValues !== []) {
                    $question->options()->whereNotIn('value', $optionValues)->delete();
                } else {
                    $question->options()->delete();
                }

                foreach ($options as $optionData) {
                    $question->options()->updateOrCreate(
                        ['value' => $optionData['value']],
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
