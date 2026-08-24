<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingMovementQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'housing_movement.event_types',
                    'title' => 'ما أنواع حركات الموقع التي يجب أن يسجلها هذا المسار التشغيلي للحيوان؟',
                    'help_text' => 'حدد الأحداث التي تغير إشغال الحيوان لموقع الإيواء. الخروج من المزرعة نفسه يعالج في مسار الخروج، وتجهيز القفص بعد الإخلاء يعالج في Workflow تشغيل وتجهيز مواقع الإيواء.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'housing_movement',
                    'options' => [
                        ['label' => 'التسكين الأول للحيوان في موقع إيواء', 'value' => 'initial_housing'],
                        ['label' => 'النقل من قفص / عين إلى قفص / عين أخرى', 'value' => 'cage_transfer'],
                        ['label' => 'إخلاء الموقع الحالي دون تحديد قفص بديل في نفس الحركة', 'value' => 'explicit_vacate'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل حركة التسكين أو النقل أو الإخلاء؟',
                    'help_text' => 'المطلوب تحديد بيانات الواقعة الفعلية. الموقع الحالي والإشغال لا يدخلا يدويًا؛ بل ينتجان من الحركات المسجلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'housing_movement',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'نوع الحركة', 'value' => 'movement_type'],
                        ['label' => 'القفص / العين السابق عند وجوده', 'value' => 'source_cage'],
                        ['label' => 'القفص / العين الجديد عند وجوده', 'value' => 'destination_cage'],
                        ['label' => 'تاريخ / وقت حدوث الحركة فعليًا', 'value' => 'occurred_at'],
                        ['label' => 'تاريخ / وقت تسجيل الحركة في النظام', 'value' => 'recorded_at'],
                        ['label' => 'سبب الحركة عند انطباقه', 'value' => 'reason'],
                        ['label' => 'المستخدم / المنفذ', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.location_reference_model',
                    'title' => 'هل يجب أن يختار المستخدم القفص / العين فقط كموقع فعلي للحيوان ويستنتج النظام البطارية والعنبر والمزرعة تلقائيًا؟',
                    'help_text' => 'القفص يتبع بطارية ثم عنبرًا ثم مزرعة، لذلك تخزين المستويات الأربعة بصورة مستقلة داخل كل حركة قد يؤدي إلى تناقضات غير ضرورية.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'animal_location',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_movement.transfer_atomicity',
                    'title' => 'عند نقل الحيوان بين قفصين، كيف يجب تنفيذ إغلاق الإشغال السابق وفتح الإشغال الجديد؟',
                    'help_text' => 'الهدف ألا توجد لحظة منطقية يظهر فيها الحيوان في قفصين بسبب تنفيذ خطوتين مستقلتين غير مترابطتين، مع الاحتفاظ بتاريخ القفص السابق والجديد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'housing_movement',
                    'options' => [
                        ['label' => 'حركة نقل واحدة تغلق الإشغال السابق وتفتح الجديد كوحدة واحدة', 'value' => 'atomic_transfer'],
                        ['label' => 'حدث إخلاء ثم حدث تسكين منفصلان لكن يجب ربطهما كعملية نقل واحدة', 'value' => 'linked_vacate_and_housing_events'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.single_active_occupancy',
                    'title' => 'هل يجب منع وجود أكثر من سجل تسكين نشط لنفس الحيوان في نفس اللحظة؟',
                    'help_text' => 'هذا يحافظ على أن الموقع الحالي للحيوان ينتج من سجل إشغال واحد واضح، بينما يظل كل تاريخ المواقع السابقة محفوظًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'animal_occupancy',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_movement.batch_transfer_support',
                    'title' => 'هل يجب أن يدعم النظام تنفيذ نقل جماعي لعدة حيوانات في عملية واحدة؟',
                    'help_text' => 'المرجع يذكر الحاجة لذلك خصوصًا في الفطام والتسمين وإعادة توزيع القطيع، مع الحفاظ على التتبع الفردي لكل حيوان.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'housing_batch_movement',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_movement.batch_transfer_scopes',
                    'title' => 'ما أشكال النقل الجماعي التي يجب أن يدعمها النظام؟',
                    'help_text' => 'حدد نطاق الاختيار داخل عملية النقل الجماعي. لا يغير ذلك شرط وجود سجل حركة مستقل لكل حيوان إذا تم اعتماد التتبع الفردي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'housing_batch_movement',
                    'options' => [
                        ['label' => 'نقل كل الحيوانات الموجودة في القفص / العين المصدر', 'value' => 'all_source_occupants'],
                        ['label' => 'اختيار جزء محدد من الحيوانات الموجودة في القفص / العين المصدر', 'value' => 'selected_source_animals'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.batch_individual_history',
                    'title' => 'عند تنفيذ نقل جماعي، هل يجب إنشاء سجل حركة مستقل لكل حيوان مع ربط السجلات بعملية النقل الجماعية؟',
                    'help_text' => 'هذا يحقق سهولة تنفيذ العملية مرة واحدة مع بقاء Timeline كل حيوان دقيقًا وقابلًا للتحليل.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'history_rule',
                    'target_entity' => 'housing_movement',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_movement.reason_requirement_scope',
                    'title' => 'في أي أنواع حركة يجب تسجيل سبب صريح للحركة؟',
                    'help_text' => 'قائمة «أسباب النقل» موجودة بالفعل في Master Data ولا تعاد هنا. هذا السؤال يحدد فقط متى يكون وجود السبب جزءًا من سجل الحركة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'housing_movement',
                    'options' => [
                        ['label' => 'التسكين الأول', 'value' => 'initial_housing'],
                        ['label' => 'النقل بين مواقع الإيواء', 'value' => 'transfer'],
                        ['label' => 'الإخلاء الصريح دون وجهة جديدة في نفس الحركة', 'value' => 'explicit_vacate'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.transfer_reason_reference',
                    'title' => 'هل يجب أن يرتبط سبب حركة النقل بقائمة «أسباب النقل» المعتمدة في Master Data بدل إدخاله كنص حر؟',
                    'help_text' => 'الهدف الحفاظ على تحليل موحد لأسباب النقل مع إمكانية استخدام الملاحظات لشرح التفاصيل الخاصة بكل واقعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'transfer_reason',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_movement.time_tracking_model',
                    'title' => 'ما مستوى التوقيت الذي يجب الاحتفاظ به لحركة الموقع؟',
                    'help_text' => 'قد يتم تنفيذ الحركة فعليًا ثم تسجيلها في النظام لاحقًا، لذلك يجب حسم الفرق بين وقت حدوث الحركة ووقت إدخالها لأغراض التاريخ والتدقيق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'audit_rule',
                    'target_entity' => 'housing_movement',
                    'options' => [
                        ['label' => 'تاريخ الحركة فقط', 'value' => 'event_date_only'],
                        ['label' => 'تاريخ ووقت حدوث الحركة فعليًا', 'value' => 'event_datetime'],
                        ['label' => 'تاريخ ووقت حدوث الحركة فعليًا + تاريخ ووقت تسجيلها في النظام', 'value' => 'event_datetime_and_recorded_at'],
                    ],
                ],
                [
                    'seed_key' => 'housing_movement.occupied_structure_relocation_support',
                    'title' => 'هل يجب دعم عملية نقل جماعي مرتبطة بإخلاء موقع إيواء مشغول بسبب إيقاف أو صيانة القفص أو البطارية أو العنبر؟',
                    'help_text' => 'هذا السؤال يخص تنفيذ النقل الفعلي للحيوانات وإنتاج سجلات حركة لهم. قواعد منع إيقاف موقع مشغول أو السماح بالتجاوز تظل في Settings، وإجراء الصيانة نفسه ينتمي إلى Workflow تشغيل وصيانة مواقع الإيواء.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'housing_relocation_operation',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'التسكين والنقل والإخلاء وإدارة الإشغال',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );

            $this->configureDependencies();
        });
    }

    private function configureDependencies(): void
    {
        $section = $this->resolveSection();

        if (! $section) {
            return;
        }

        $questions = QuestionnaireQuestion::query()
            ->where('section_id', $section->id)
            ->whereNotNull('seed_key')
            ->get()
            ->keyBy('seed_key');

        $eventTypes = $questions->get('housing_movement.event_types');
        $transferAtomicity = $questions->get('housing_movement.transfer_atomicity');

        if ($eventTypes && $transferAtomicity) {
            $transferAtomicity->forceFill([
                'depends_on_question_id' => $eventTypes->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'cage_transfer',
            ])->save();
        }

        $batchTransferSupport = $questions->get('housing_movement.batch_transfer_support');

        if ($batchTransferSupport) {
            foreach ([
                'housing_movement.batch_transfer_scopes',
                'housing_movement.batch_individual_history',
            ] as $dependentSeedKey) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $batchTransferSupport->id,
                    'dependency_operator' => QuestionDependencyOperator::EQUALS,
                    'dependency_value' => '1',
                ])->save();
            }
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الحركات ودورة التشغيل الفعلية')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'التسكين والنقل والإخلاء وإدارة الإشغال')
            ->first();
    }
}
