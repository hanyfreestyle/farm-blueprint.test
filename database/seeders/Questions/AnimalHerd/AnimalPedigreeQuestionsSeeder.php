<?php

namespace Database\Seeders\Questions\AnimalHerd;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalPedigreeQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'animal.pedigree_relationships',
                    'title' => 'ما علاقات النسب المباشرة التي يجب أن يحتفظ بها سجل الحيوان؟',
                    'help_text' => 'حدد العلاقات الأساسية التي تمثل الأصل الفعلي للحيوان. السلالة تعريف مرجعي مستقل في Master Data، أما النسب فيمثل علاقات الحيوان الفعلية بالأب والأم والبطن التي خرج منها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'animal_pedigree',
                    'options' => [
                        ['label' => 'الأب البيولوجي', 'value' => 'biological_father'],
                        ['label' => 'الأم البيولوجية', 'value' => 'biological_mother'],
                        ['label' => 'البطن التي خرج منها الحيوان', 'value' => 'birth_litter'],
                    ],
                ],
                [
                    'seed_key' => 'animal.internal_pedigree_derivation',
                    'title' => 'كيف يجب إنشاء نسب الحيوان الناتج داخل النظام؟',
                    'help_text' => 'الحيوان الناتج داخل المزرعة قد تكون علاقات الأب والأم والبطن معروفة بالفعل من سجلات التلقيح والولادة. المطلوب تحديد هل يعاد إدخالها يدويًا أم يستفيد النظام من السجلات الموجودة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'animal_pedigree',
                    'options' => [
                        ['label' => 'يستنتج النسب تلقائيًا من سجلات الولادة ودورة الإنتاج', 'value' => 'automatic_from_birth_and_reproduction_records'],
                        ['label' => 'يستنتج ما هو متاح تلقائيًا ويسمح باستكمال البيانات الناقصة يدويًا', 'value' => 'automatic_when_available_manual_completion'],
                        ['label' => 'يتم إدخال النسب يدويًا', 'value' => 'manual_entry'],
                    ],
                ],
                [
                    'seed_key' => 'animal.pedigree_completeness_states',
                    'title' => 'ما حالات اكتمال النسب التي يجب أن يدعمها النظام؟',
                    'help_text' => 'خصوصًا للحيوانات القادمة من خارج المزرعة قد يكون الأب والأم معروفين بالكامل أو تكون بعض العلاقات فقط معروفة أو يكون النسب غير معروف. لا يجب اختراع بيانات لاستكمال الشجرة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'animal_pedigree',
                    'options' => [
                        ['label' => 'نسب كامل', 'value' => 'complete'],
                        ['label' => 'نسب جزئي', 'value' => 'partial'],
                        ['label' => 'نسب غير معروف', 'value' => 'unknown'],
                    ],
                ],
                [
                    'seed_key' => 'animal.external_ancestor_strategy',
                    'title' => 'كيف يجب تمثيل والد أو سلف معروف لكنه غير موجود كحيوان داخل النظام؟',
                    'help_text' => 'قد تصل بيانات الأب أو الأم من شهادة نسب أو سجل مربي دون أن يكون هذا السلف قد دخل المزرعة أو أصبح حيوانًا تشغيليًا داخل النظام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'relationship_strategy',
                    'target_entity' => 'animal_pedigree',
                    'options' => [
                        ['label' => 'إنشاء سجل مرجعي لسلف خارجي يظهر في شجرة النسب دون اعتباره حيوانًا تشغيليًا', 'value' => 'external_ancestor_reference'],
                        ['label' => 'تسجيله كمعلومة نصية فقط داخل النسب', 'value' => 'unstructured_text_only'],
                        ['label' => 'لا يقبل إلا إذا تم إنشاء سجل حيوان كامل له داخل النظام', 'value' => 'require_full_animal_record'],
                    ],
                ],
                [
                    'seed_key' => 'animal.external_ancestor_fields',
                    'title' => 'ما البيانات التي يجب أن يدعمها سجل السلف الخارجي؟',
                    'help_text' => 'يظهر هذا السؤال إذا تم اعتماد سجل مرجعي مستقل للسلف الخارجي. المطلوب تحديد الحد المناسب من البيانات المتاحة دون تحويل السلف الخارجي إلى حيوان موجود فعليًا في المزرعة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'field',
                    'target_entity' => 'external_ancestor',
                    'options' => [
                        ['label' => 'الاسم أو الكود الخارجي', 'value' => 'external_name_or_code'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'المربي أو المصدر', 'value' => 'source_or_breeder'],
                        ['label' => 'معلومات أو ملاحظات إضافية', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'animal.pedigree_evidence_types',
                    'title' => 'ما مصادر توثيق النسب التي يجب أن يستطيع النظام تمييزها؟',
                    'help_text' => 'الهدف التفرقة بين نسب ناتج تلقائيًا من سجلات النظام ونسب تم إدخاله من مصدر خارجي بدرجات مختلفة من الموثوقية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'provenance_rule',
                    'target_entity' => 'pedigree_evidence',
                    'options' => [
                        ['label' => 'موثق تلقائيًا من سجلات النظام', 'value' => 'system_record'],
                        ['label' => 'شهادة نسب', 'value' => 'pedigree_certificate'],
                        ['label' => 'سجل من المربي أو المصدر', 'value' => 'breeder_record'],
                        ['label' => 'إفادة بدون مستند', 'value' => 'unverified_statement'],
                        ['label' => 'غير معروف', 'value' => 'unknown'],
                    ],
                ],
                [
                    'seed_key' => 'animal.family_tree_build_strategy',
                    'title' => 'كيف يجب بناء شجرة العائلة للحيوان؟',
                    'help_text' => 'الأجداد يمكن الوصول إليهم من علاقات الأب والأم المسجلة عبر الأجيال. المطلوب حسم هل الشجرة مشتقة من هذه العلاقات أم تتم صيانتها يدويًا كبيانات منفصلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'animal_family_tree',
                    'options' => [
                        ['label' => 'يبنيها النظام تلقائيًا من علاقات الأب والأم والسلف الخارجي المسجلة', 'value' => 'automatic_from_parent_relationships'],
                        ['label' => 'تتم صيانة شجرة العائلة يدويًا بصورة مستقلة', 'value' => 'manual_tree_maintenance'],
                    ],
                ],
                [
                    'seed_key' => 'animal.biological_vs_foster_mother',
                    'title' => 'عند نقل مولود للرضاعة عند أنثى أخرى، هل يجب أن يبقى النسب مرتبطًا بالأم البيولوجية وتُسجل الأم الحاضنة / المرضعة بصورة منفصلة؟',
                    'help_text' => 'نقل المولود بين الأمهات حدث تشغيلي للرعاية ولا يجب أن يغير الأصل الوراثي إذا كان المطلوب الحفاظ على شجرة النسب الصحيحة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'animal_parentage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.genetic_line_usage',
                    'title' => 'هل يحتاج النظام إلى مفهوم «الخط الوراثي» كتصنيف مستقل بجانب السلالة والنسب المباشر؟',
                    'help_text' => 'السلالة تصف الانتماء الوراثي العام، والنسب يصف الأب والأم والأجداد. هذا السؤال يحسم فقط هل يوجد احتياج لمفهوم إضافي باسم الخط الوراثي؛ تفاصيله لا تُفترض من هذا السؤال وحده.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'data_model_rule',
                    'target_entity' => 'genetic_line',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.offspring_breed_derivation',
                    'title' => 'كيف يجب التعامل مع تصنيف سلالة نسل أبوين من سلالات مختلفة؟',
                    'help_text' => 'يحتفظ النظام دائمًا بحقيقة سلالة الأب وسلالة الأم من خلال النسب. المطلوب هنا فقط حسم هل سلالة النسل تختار يدويًا من تعريفات السلالات أم يقترحها أو يحددها النظام وفق قاعدة معتمدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'animal_breed',
                    'options' => [
                        ['label' => 'لا يتم الاستنتاج تلقائيًا وتُحدد سلالة النسل من تعريفات السلالات المعتمدة', 'value' => 'manual_from_master_data'],
                        ['label' => 'يقترح النظام التصنيف بناءً على سلالة الأبوين ويحتاج تأكيد المستخدم', 'value' => 'suggest_from_parents_require_confirmation'],
                        ['label' => 'يحدد النظام سلالة النسل تلقائيًا وفق قاعدة تصنيف قابلة للإعداد', 'value' => 'automatic_by_configured_rule'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'بيانات الحيوان وتكوين القطيع',
                sectionName: 'النسب وشجرة العائلة',
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

        $externalAncestorStrategy = $questions->get('animal.external_ancestor_strategy');
        $externalAncestorFields = $questions->get('animal.external_ancestor_fields');

        if ($externalAncestorStrategy && $externalAncestorFields) {
            $externalAncestorFields->forceFill([
                'depends_on_question_id' => $externalAncestorStrategy->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => 'external_ancestor_reference',
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'بيانات الحيوان وتكوين القطيع')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'النسب وشجرة العائلة')
            ->first();
    }
}
