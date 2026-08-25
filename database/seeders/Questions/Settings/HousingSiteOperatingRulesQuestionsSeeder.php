<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingSiteOperatingRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'housing_site_rules.operational_availability_components',
                    'title' => 'ما الشروط التي يجب أن تدخل في تحديد الإتاحة التشغيلية الفعلية لموقع الإيواء؟',
                    'help_text' => 'الإتاحة التشغيلية ليست مجرد Status يدوي؛ يجب أن تعكس حالة الموقع وما فوقه من هيكل، والصيانة، والتطهير، وفترة الانتظار عند اعتمادها. توافق الموقع مع حيوان معين وقواعد الجمع والفصل تراجع في 6.4.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'availability_rule',
                    'target_entity' => 'housing_site_availability',
                    'options' => [
                        ['label' => 'الحالة التشغيلية المحلية تسمح بالاستخدام', 'value' => 'local_operational_status_allows_use'],
                        ['label' => 'الموقع الأب متاح تشغيليًا', 'value' => 'parent_site_effectively_available'],
                        ['label' => 'لا توجد صيانة فعالة تمنع الاستخدام', 'value' => 'no_blocking_active_maintenance'],
                        ['label' => 'تم استيفاء التطهير إذا كان مطلوبًا', 'value' => 'required_sanitation_completed'],
                        ['label' => 'انتهت فترة الانتظار إذا كانت مطلوبة', 'value' => 'required_waiting_period_elapsed'],
                        ['label' => 'تم التفعيل التشغيلي إذا كان المستوى يتطلب تفعيلًا', 'value' => 'required_activation_completed'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.parent_unavailability_effect',
                    'title' => 'إذا أصبح العنبر أو البطارية غير متاح تشغيليًا، كيف يجب أن يؤثر ذلك على المواقع التابعة؟',
                    'help_text' => '4.16 يحفظ الحدث الفعلي على الموقع الذي حدثت له الصيانة أو الإيقاف. هنا نحسم قاعدة الإتاحة المشتقة للمواقع التابعة دون تغيير هويتها أو إنشاء Status يدوي متكرر لكل قفص.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'availability_rule',
                    'target_entity' => 'housing_site_availability',
                    'options' => [
                        ['label' => 'عدم إتاحة الموقع الأب تجعل كل المواقع التابعة غير متاحة تلقائيًا حتى زوال السبب', 'value' => 'parent_unavailability_blocks_descendants'],
                        ['label' => 'يطبق الأثر فقط على المستويات التي تحدد القاعدة أنها تتأثر بالموقع الأب', 'value' => 'propagation_by_site_rule'],
                        ['label' => 'لا تنتقل عدم الإتاحة تلقائيًا ويجب تغيير كل موقع تابع بصورة مستقلة', 'value' => 'no_automatic_parent_propagation'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.operational_capacity_model',
                    'title' => 'كيف يجب تمثيل السعة التشغيلية المستخدمة في قرارات الإتاحة دون تغيير السعة الهيكلية أو الفيزيائية للموقع؟',
                    'help_text' => 'Farm Structure يحسم مصدر السعة الأساسية للقفص، لكن المراجعة المعمارية تسمح بوجود سعة تشغيلية قابلة للضبط. المطلوب تحديد هل يوجد حد تشغيلي منفصل أم تستخدم السعة الأساسية نفسها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'capacity_rule',
                    'target_entity' => 'housing_site_capacity',
                    'options' => [
                        ['label' => 'لا توجد سعة تشغيلية منفصلة؛ تستخدم السعة الأساسية المسجلة للموقع', 'value' => 'use_structural_capacity_only'],
                        ['label' => 'يمكن تحديد حد تشغيل أقل من أو يساوي السعة الأساسية دون تجاوزها', 'value' => 'configurable_operational_limit_within_structural_capacity'],
                        ['label' => 'تشتق السعة التشغيلية من الاستخدام / Profile التشغيلي مع بقاء السعة الأساسية سقفًا أعلى', 'value' => 'derive_operational_capacity_from_usage_or_profile'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.capacity_change_effect',
                    'title' => 'إذا خُفضت السعة التشغيلية وأصبح الإشغال الحالي أعلى من الحد الجديد، كيف يجب أن يتعامل النظام مع الحالة؟',
                    'help_text' => 'الإشغال الحالي مشتق من الحركات ولا يجوز للنظام نقل الحيوانات تلقائيًا أو إعادة كتابة الماضي بسبب تعديل Setting. المطلوب تحديد أثر التغيير على الوضع القائم والعمليات الجديدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'change_effect_rule',
                    'target_entity' => 'housing_site_capacity',
                    'options' => [
                        ['label' => 'يمنع خفض الحد إلى أقل من الإشغال الحالي حتى يتم الإخلاء / النقل فعليًا', 'value' => 'prevent_limit_below_current_occupancy'],
                        ['label' => 'يسمح بالتغيير ويظهر الموقع كتجاوز قائم يمنع إضافة إشغال جديد حتى يعود داخل الحد', 'value' => 'allow_change_flag_existing_overcapacity'],
                        ['label' => 'يسمح بالتغيير ويولد مراجعة / إجراء مطلوب لمعالجة التجاوز دون نقل تلقائي', 'value' => 'allow_change_create_capacity_review'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.sanitation_requirement_model',
                    'title' => 'متى يجب أن يكون تنظيف / تطهير القفص شرطًا إلزاميًا قبل إعادة استخدامه؟',
                    'help_text' => 'التصور يترك إلزام التنظيف والتعقيم بين دورات الإشغال كنقطة تحتاج حسمًا، ويذكر أن القفص قد ينتقل بعد الإخلاء إلى انتظار التطهير بدل أن يصبح متاحًا فورًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'sanitation_rule',
                    'target_entity' => 'housing_site_sanitation',
                    'options' => [
                        ['label' => 'إلزامي بعد كل دورة إشغال قبل دخول إشغال جديد', 'value' => 'required_after_every_occupancy_cycle'],
                        ['label' => 'إلزامي فقط عندما تنطبق قاعدة حسب الاستخدام أو نوع الحالة التشغيلية', 'value' => 'required_conditionally_by_operational_rule'],
                        ['label' => 'ليس شرطًا عامًا؛ يسجل التطهير فقط عند الحاجة التشغيلية', 'value' => 'not_globally_required'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.sanitation_trigger_scope',
                    'title' => 'ما الحالات التي يجب أن تستطيع قواعد التشغيل ربطها بمتطلب تطهير قبل إعادة الاستخدام؟',
                    'help_text' => '4.16 يحسم كيف يبدأ وينفذ سجل التطهير عند انطباق القاعدة. هنا نحسم الحالات التي يمكن للسياسة أن تعتبرها Trigger لمتطلب التطهير دون تنفيذ العملية من Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'sanitation_rule',
                    'target_entity' => 'housing_site_sanitation',
                    'options' => [
                        ['label' => 'انتهاء دورة إشغال وإخلاء القفص', 'value' => 'post_occupancy_vacate'],
                        ['label' => 'اكتمال صيانة عندما تقرر السياسة أن التطهير مطلوب بعدها', 'value' => 'post_maintenance_completion'],
                        ['label' => 'تغيير الاستخدام التشغيلي عندما تقرر السياسة أن الانتقال بين الاستخدامات يتطلب تجهيزًا', 'value' => 'operational_usage_change'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.post_maintenance_sanitation_model',
                    'title' => 'كيف يجب تحديد ما إذا كان الموقع يحتاج تطهيرًا بعد انتهاء الصيانة وقبل العودة للاستخدام؟',
                    'help_text' => '4.16 يحتوي التكامل بعد اكتمال الصيانة إذا كانت Settings تشترط التطهير. المطلوب هنا تعريف القاعدة التي تقرر متى يكون هذا الشرط مطلوبًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'sanitation_rule',
                    'target_entity' => 'housing_site_maintenance',
                    'options' => [
                        ['label' => 'التطهير إلزامي بعد كل صيانة قبل إعادة الاستخدام', 'value' => 'always_required_after_maintenance'],
                        ['label' => 'يحدد حسب نوع الموقع أو نوع / أثر الصيانة عندما تتوفر هذه المعلومة', 'value' => 'conditional_after_maintenance'],
                        ['label' => 'اكتمال الصيانة لا يولد شرط تطهير إضافيًا إلا إذا كان هناك Trigger آخر مستقل', 'value' => 'maintenance_does_not_add_sanitation_requirement'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.waiting_period_model',
                    'title' => 'هل نحتاج فترة انتظار تشغيلية بعد التطهير أو التجهيز قبل اعتبار الموقع جاهزًا لإعادة الاستخدام؟',
                    'help_text' => 'المراجعة المعمارية تضع فترة الانتظار كSetting محتمل ولا تحدد رقمًا ثابتًا. إذا اعتمدت، يجب أن تكون القيمة قابلة للضبط في النطاق المناسب بدل Hardcode.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'waiting_period_rule',
                    'target_entity' => 'housing_site_readiness',
                    'options' => [
                        ['label' => 'لا توجد فترة انتظار؛ اكتمال المتطلبات يسمح بإعادة الاستخدام فورًا', 'value' => 'no_waiting_period'],
                        ['label' => 'توجد فترة انتظار واحدة قابلة للضبط بعد التجهيز المطلوب', 'value' => 'single_configurable_waiting_period'],
                        ['label' => 'قد تختلف فترة الانتظار حسب الاستخدام أو نوع Trigger / التجهيز', 'value' => 'waiting_period_by_usage_or_trigger'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.waiting_period_start_model',
                    'title' => 'إذا كانت هناك فترة انتظار، من أي نقطة يجب أن يبدأ حسابها؟',
                    'help_text' => 'المطلوب ربط المدة بحدث Canonical واضح حتى لا تختلف الجاهزية بين الشاشات. التنفيذ الفعلي للتطهير أو الصيانة محفوظ في 4.16.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'waiting_period_rule',
                    'target_entity' => 'housing_site_readiness',
                    'options' => [
                        ['label' => 'من وقت اكتمال آخر إجراء تجهيز مطلوب مثل التطهير', 'value' => 'from_last_required_preparation_completion'],
                        ['label' => 'من وقت إخلاء الموقع وانتهاء الإشغال السابق', 'value' => 'from_vacate_time'],
                        ['label' => 'تحدد نقطة البداية حسب نوع القاعدة / Trigger الذي أوجب الانتظار', 'value' => 'start_point_by_trigger_rule'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.reuse_requirements',
                    'title' => 'ما المتطلبات التي يجب استيفاؤها قبل اعتبار موقع الإيواء جاهزًا لإعادة الاستخدام؟',
                    'help_text' => 'هذا السؤال يحدد شروط Site Readiness نفسها. أهلية حيوان محدد لهذا الموقع مثل الجنس والمرحلة والجمع ستراجع في 6.4.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'readiness_rule',
                    'target_entity' => 'housing_site_readiness',
                    'options' => [
                        ['label' => 'انتهاء أي صيانة مانعة وعودة الموقع للحالة التشغيلية المسموح بها', 'value' => 'maintenance_completed_and_status_allows_use'],
                        ['label' => 'إتمام التطهير المطلوب', 'value' => 'required_sanitation_completed'],
                        ['label' => 'انتهاء فترة الانتظار المطلوبة', 'value' => 'required_waiting_period_elapsed'],
                        ['label' => 'إتاحة الموقع الأب', 'value' => 'parent_site_available'],
                        ['label' => 'وجود تفعيل تشغيلي ساري عند الحاجة', 'value' => 'site_activation_effective'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.usage_change_preparation_policy',
                    'title' => 'إذا كان Farm Structure يسمح بتغيير الاستخدام التشغيلي للقفص، كيف يجب أن تؤثر قواعد التجهيز على هذا التغيير؟',
                    'help_text' => 'قرار السماح بتغيير الاستخدام وطريقة تنفيذه موجود في تعريف القفص. هنا نحسم فقط هل الانتقال بين استخدامين يحتاج متطلبات تطهير / انتظار إضافية قبل بدء الاستخدام الجديد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'usage_change_rule',
                    'target_entity' => 'cage_usage_change',
                    'options' => [
                        ['label' => 'لا توجد متطلبات إضافية غير شروط الجاهزية العامة للموقع', 'value' => 'general_readiness_rules_only'],
                        ['label' => 'يطبق نفس متطلب التطهير / الانتظار المستخدم بعد دورة الإشغال السابقة', 'value' => 'reuse_standard_post_occupancy_preparation'],
                        ['label' => 'يمكن تعريف متطلبات تجهيز حسب الاستخدام السابق والاستخدام الجديد', 'value' => 'preparation_rules_by_usage_transition'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.rule_variation_dimensions',
                    'title' => 'على أي خصائص يجب أن يسمح النظام باختلاف قواعد السعة والتطهير والانتظار داخل نفس نطاق الإعدادات؟',
                    'help_text' => '6.1 يحسم Scope مثل المزرعة والعنبر والـProfile. هذا السؤال يحدد الاختلاف داخل نفس النطاق وفق خصائص الموقع نفسه دون تحويل كل خاصية إلى Module مستقل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'rule_scope',
                    'target_entity' => 'housing_site_operational_rule',
                    'options' => [
                        ['label' => 'نفس القاعدة لكل المواقع داخل النطاق ولا تختلف حسب خصائص الموقع', 'value' => 'uniform_within_scope'],
                        ['label' => 'حسب مستوى الموقع: عنبر / بطارية / قفص', 'value' => 'by_site_level'],
                        ['label' => 'حسب الاستخدام التشغيلي للقفص عند وجوده', 'value' => 'by_operational_usage'],
                        ['label' => 'حسب النوع / الملف الفيزيائي عندما تكون له دلالة تشغيلية', 'value' => 'by_physical_type_or_profile'],
                    ],
                ],
                [
                    'seed_key' => 'housing_site_rules.effective_availability_source_model',
                    'title' => 'كيف يجب أن ينتج النظام قيمة «متاح تشغيليًا الآن» للموقع؟',
                    'help_text' => 'الهدف منع وجود Flag يدوي مستقل يناقض الحالة أو الصيانة أو التطهير. يمكن تخزين Snapshot للأداء، لكن يجب أن يظل قابلًا لإعادة الاشتقاق من المصادر والقواعد المعتمدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'housing_site_availability',
                    'options' => [
                        ['label' => 'تشتق الإتاحة دائمًا من الحالة والأحداث والقواعد الفعالة دون قيمة يدوية مستقلة', 'value' => 'derive_from_canonical_state_and_rules'],
                        ['label' => 'تشتق الإتاحة مع السماح بتخزين Snapshot محسوب لتحسين الأداء دون تعديله يدويًا', 'value' => 'derived_with_noneditable_cached_snapshot'],
                        ['label' => 'تخزن قيمة إتاحة مستقلة يمكن تعديلها يدويًا بجانب السجلات الأخرى', 'value' => 'independent_manual_availability_flag'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد تشغيل هيكل المزرعة ومواقع الإيواء',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
