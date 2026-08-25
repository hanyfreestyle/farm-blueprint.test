<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingSiteOperationsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'housing_site_maintenance.target_scopes',
                    'title' => 'على أي مستويات من هيكل الإيواء يجب أن يستطيع النظام تسجيل عملية صيانة فعلية؟',
                    'help_text' => 'تعريف العنبر والبطارية والقفص موجود في Farm Structure، بينما هذا السؤال يحدد المواقع التي يمكن أن تكون هدفًا لحدث صيانة تشغيلي فعلي دون تكرار تعريف الكيانات نفسها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_scope',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'القفص / العين', 'value' => 'cage'],
                        ['label' => 'البطارية', 'value' => 'battery'],
                        ['label' => 'العنبر', 'value' => 'barn'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_maintenance.record_model',
                    'title' => 'كيف يجب تمثيل سجل الصيانة عبر مستويات الإيواء المختلفة؟',
                    'help_text' => 'المطلوب حسم النموذج التاريخي للحدث نفسه بحيث يمكن معرفة ماذا حدث للموقع ومتى، مع عدم تحويل الحالة الحالية إلى بديل عن سجل الصيانة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'سجل صيانة موحد يمكن ربطه بقفص أو بطارية أو عنبر حسب نوع الموقع', 'value' => 'unified_maintenance_record_polymorphic_target'],
                        ['label' => 'يعتمد كل مستوى على Action History الخاص به دون سجل صيانة موحد', 'value' => 'entity_action_history_only'],
                        ['label' => 'سجلات صيانة منفصلة حسب نوع الموقع', 'value' => 'separate_records_per_site_type'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_maintenance.lifecycle_model',
                    'title' => 'كيف يجب تمثيل دورة الصيانة من بدايتها حتى اكتمالها؟',
                    'help_text' => 'المصدر يميز بين دخول الموقع تحت الصيانة وعودته لاحقًا إلى التشغيل. المطلوب الحفاظ على تاريخ البداية والنهاية بدل الاكتفاء بآخر Status.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'دورة صيانة واحدة لها بداية واكتمال وتحتوي الأحداث المرتبطة بها', 'value' => 'maintenance_cycle_with_start_and_completion'],
                        ['label' => 'سجل واحد يحتوي تاريخ البداية وتاريخ النهاية دون أحداث فرعية', 'value' => 'single_record_with_start_and_end'],
                        ['label' => 'أحداث مستقلة لبدء الصيانة وإنهائها مع ربطها تاريخيًا', 'value' => 'linked_start_and_completion_events'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_maintenance.occupied_target_integration',
                    'title' => 'عند بدء صيانة موقع يحتوي على حيوانات، كيف يجب أن يتكامل الحدث مع مسار التسكين والنقل في 4.2؟',
                    'help_text' => 'قواعد المنع أو السماح أو Override مكانها Settings. هذا السؤال يحسم فقط طريقة الربط التشغيلي مع الإخلاء الفعلي للحيوانات عندما تكون الصيانة مرتبطة بموقع مشغول.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'يجب إتمام حركات الإخلاء / النقل الفعلية في 4.2 قبل اعتبار الصيانة قد بدأت', 'value' => 'vacate_before_maintenance_start'],
                        ['label' => 'يسجل طلب / قرار الصيانة أولًا وتظل البداية معلقة حتى اكتمال الإخلاء في 4.2', 'value' => 'maintenance_pending_until_vacated'],
                        ['label' => 'تبدأ الصيانة وتُنشأ إجراءات إخلاء مرتبطة يجب تنفيذها فورًا', 'value' => 'maintenance_start_with_linked_vacate_actions'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_sanitation.post_vacate_trigger_model',
                    'title' => 'إذا أصبح القفص فارغًا وكانت سياسة التشغيل تشترط التطهير قبل إعادة الاستخدام، كيف يجب بدء مسار التجهيز؟',
                    'help_text' => 'المصدر يوضح أن القفص قد يصبح فارغًا ثم يمر بمرحلة انتظار التنظيف / التعقيم قبل أن يصبح متاحًا. إلزام التطهير نفسه وفترات الانتظار تأتي من Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'housing_site_sanitation',
                    'options' => [
                        ['label' => 'ينشئ النظام متطلب تطهير تلقائيًا بمجرد انتهاء دورة الإشغال عند انطباق السياسة', 'value' => 'auto_create_sanitation_requirement'],
                        ['label' => 'ينشئ النظام مهمة تطهير لكن لا يعتبر عملية التطهير بدأت إلا عند تنفيذها', 'value' => 'create_sanitation_task_only'],
                        ['label' => 'لا ينشأ شيء تلقائيًا ويبدأ المستخدم عملية التطهير يدويًا عند الحاجة', 'value' => 'manual_sanitation_start'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_operation.parent_child_history_model',
                    'title' => 'عند تنفيذ إجراء تشغيلي على بطارية أو عنبر، كيف يجب الحفاظ على أثره التاريخي على المواقع التابعة؟',
                    'help_text' => 'قد يجعل إجراء على Parent Site الأقفاص أو البطاريات التابعة غير متاحة فعليًا دون تغيير هويتها أو محو حالتها المحلية. المطلوب حسم نموذج التاريخ ومنع تكرار أحداث متنافسة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'history_rule',
                    'target_entity' => 'housing_site_operation',
                    'options' => [
                        ['label' => 'يحفظ الحدث على الموقع الأب فقط ويشتق النظام أثر عدم الإتاحة على المواقع التابعة', 'value' => 'parent_event_only_derive_child_effect'],
                        ['label' => 'يحفظ الحدث على الموقع الأب وينشئ سجلات أثر مرتبطة للمواقع التابعة دون اعتبارها أحداثًا مستقلة', 'value' => 'parent_event_with_linked_child_effect_records'],
                        ['label' => 'ينشئ Action مستقلًا لكل موقع تابع متأثر', 'value' => 'independent_child_actions'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_maintenance.post_completion_sanitation_model',
                    'title' => 'إذا كانت إعدادات التشغيل تشترط التطهير بعد انتهاء الصيانة، ماذا يجب أن يحدث عند تسجيل اكتمال الصيانة؟',
                    'help_text' => 'هذا السؤال لا يقرر هل التطهير مطلوب؛ ذلك من Settings. المطلوب فقط حسم التكامل عندما تكون القاعدة المطبقة تشترط التطهير قبل إعادة الاستخدام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'ينتقل الموقع تلقائيًا إلى انتظار التطهير ولا يعود متاحًا للتسكين', 'value' => 'transition_to_sanitation_required'],
                        ['label' => 'ينشئ النظام مهمة / إجراء تطهير مرتبطًا وتظل الجاهزية غير مكتملة حتى تنفيذه', 'value' => 'create_linked_sanitation_action'],
                        ['label' => 'تسجل نهاية الصيانة فقط ويبدأ التطهير لاحقًا كعملية مستقلة يدويًا', 'value' => 'maintenance_complete_manual_sanitation_followup'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_operation.return_to_service_model',
                    'title' => 'كيف يجب أن يعود موقع الإيواء إلى الخدمة بعد انتهاء الصيانة أو التجهيز المطلوب؟',
                    'help_text' => 'العودة للخدمة يجب ألا تمحو الصيانة أو التطهير السابقين. قواعد الجاهزية والفترات المطلوبة تأتي من Settings، بينما هذا السؤال يحسم حدث العودة التشغيلي نفسه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'housing_site_operation',
                    'options' => [
                        ['label' => 'Action صريح لإعادة الموقع إلى الخدمة بعد تحقق المتطلبات', 'value' => 'explicit_return_to_service_action'],
                        ['label' => 'يعود الموقع تلقائيًا إلى الخدمة بمجرد تحقق جميع المتطلبات', 'value' => 'automatic_return_when_requirements_met'],
                        ['label' => 'طريقة العودة إلى الخدمة تحدد من إعدادات التشغيل', 'value' => 'return_model_from_settings'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_sanitation.readiness_after_completion_model',
                    'title' => 'كيف يجب تحديد جاهزية الموقع للتسكين بعد تسجيل اكتمال التطهير؟',
                    'help_text' => 'إتمام التطهير لا يعني بالضرورة أن الموقع متاح فورًا إذا كانت هناك فترة انتظار أو اعتماد مطلوب. المطلوب فصل اكتمال العملية عن Housing Eligibility النهائية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'housing_site_readiness',
                    'options' => [
                        ['label' => 'تشتق الجاهزية تلقائيًا من اكتمال التطهير وفترة الانتظار والاعتماد وبقية شروط الإتاحة', 'value' => 'derive_readiness_from_completed_requirements'],
                        ['label' => 'يلزم Action مستقل لتأكيد الجاهزية بعد اكتمال التطهير', 'value' => 'explicit_readiness_confirmation_action'],
                        ['label' => 'التطهير يثبت فقط كسجل تاريخي وتحدد الإتاحة يدويًا بصورة مستقلة', 'value' => 'sanitation_history_only_manual_availability'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'تشغيل وصيانة وتجهيز مواقع الإيواء',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
