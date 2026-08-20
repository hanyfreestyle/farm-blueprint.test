<?php

namespace Database\Seeders\Sections;

use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;

class QuestionnaireMasterDataSectionSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Reuse the existing first main section when upgrading from the old
         * "إدخال البيانات الأساسية للمزرعة" structure so existing child
         * sections, questions and answers keep the same parent identity.
         */
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'إدارة البيانات الأساسية')
            ->first();

        if (! $mainSection) {
            $mainSection = QuestionnaireSection::query()
                ->whereNull('parent_id')
                ->where('name', 'إدخال البيانات الأساسية للمزرعة')
                ->first();
        }

        if (! $mainSection) {
            $mainSection = new QuestionnaireSection();
            $mainSection->parent_id = null;
        }

        $mainSection->fill([
            'name' => 'إدارة البيانات الأساسية',
            'description' => <<<'MD'
يضم هذا القسم البيانات والكيانات المرجعية التي يجب تأسيسها مبكرًا حتى تعتمد عليها بقية أجزاء النظام بصورة موحدة، مثل هيكل المزرعة والأنشطة التشغيلية وملفات إعدادات التشغيل والسلالات وفريق التشغيل وقوائم الأسباب التشغيلية وبيانات الموقع.
MD,
            'sort_order' => 1,
        ]);
        $mainSection->save();

        $subsections = [
            [
                'name' => 'بيانات المزرعة',
                'description' => <<<'MD'
تمثل المزرعة أعلى مستوى تنظيمي في النظام، وترتبط بها بيانات الموقع والاتصال والهيكل التشغيلي والقطيع والحركات.
MD,
                'sort_order' => 1,
            ],
            [
                'name' => 'أنشطة المزرعة',
                'description' => <<<'MD'
قائمة مرجعية مستقلة للأنشطة التشغيلية التي يمكن إسنادها للعنابر، ويمكن استنتاج أنشطة المزرعة الفعلية من أنشطة العنابر التابعة لها وتوحيد مسميات الأنشطة في الإدخال والتقارير.
MD,
                'sort_order' => 2,
            ],
            [
                'name' => 'ملفات إعدادات التشغيل',
                'description' => <<<'MD'
تمثل ملفات إعدادات التشغيل مجموعات إعدادات مستقلة قابلة لإعادة الاستخدام، بحيث يتم اختيار ملف إعدادات مناسب للعنبر وتحديد قواعد التشغيل والإنتاج التي يعمل وفقًا لها.
MD,
                'sort_order' => 3,
            ],
            [
                'name' => 'بيانات العنبر',
                'description' => <<<'MD'
يمثل العنبر وحدة تنظيمية داخل المزرعة تحتوي على البطاريات والأقفاص، ويسمح بفصل الحيوانات والعمليات حسب الموقع الفعلي داخل المزرعة.
MD,
                'sort_order' => 4,
            ],
            [
                'name' => 'بيانات البطارية',
                'description' => <<<'MD'
تمثل البطارية وحدة داخل العنبر تضم مجموعة من العيون أو الأقفاص، وتساعد في تنظيم أماكن التسكين والترقيم والإشغال.
MD,
                'sort_order' => 5,
            ],
            [
                'name' => 'بيانات القفص / العين',
                'description' => <<<'MD'
القفص هو الموقع الفعلي للأرنب داخل المزرعة، وترتبط به أغلب حركات التسكين والنقل، لذلك يجب أن يكون واضح الهوية وقابلًا للتتبع.
MD,
                'sort_order' => 6,
            ],
            [
                'name' => 'بيانات السلالات',
                'description' => <<<'MD'
السلالات بيانات مرجعية أساسية تستخدم لاحقًا في ملفات الحيوانات وتحليل النمو والخصوبة وحجم البطون والنفوق وأداء خطوط الإنتاج.
MD,
                'sort_order' => 7,
            ],
            [
                'name' => 'المستخدمون وفريق التشغيل',
                'description' => <<<'MD'
يحدد هذا القسم أعضاء فريق التشغيل الذين يمكنهم تسجيل الدخول إلى لوحة التحكم، وربطهم بالمزارع والأدوار والصلاحيات والورديات ونطاق العمل التشغيلي.
MD,
                'sort_order' => 8,
            ],
            [
                'name' => 'أسباب النقل',
                'description' => <<<'MD'
قائمة مرجعية مستقلة لأسباب نقل الحيوانات أو تغيير مواقعها، لتوحيد التسجيل وتمكين التحليل والتقارير لاحقًا.
MD,
                'sort_order' => 9,
            ],
            [
                'name' => 'أسباب النفوق',
                'description' => <<<'MD'
قائمة مرجعية مستقلة لأسباب النفوق، تستخدم بدل الاعتماد على النص الحر فقط حتى يمكن تصنيف حالات النفوق وتحليلها بدقة.
MD,
                'sort_order' => 10,
            ],
            [
                'name' => 'أسباب الاستبعاد',
                'description' => <<<'MD'
قائمة مرجعية مستقلة لأسباب استبعاد الحيوانات من الإنتاج أو من مسار معين، مع الحفاظ على السبب في السجل التاريخي.
MD,
                'sort_order' => 11,
            ],
            [
                'name' => 'أسباب الخروج',
                'description' => <<<'MD'
قائمة مرجعية مستقلة لأسباب خروج الحيوان من المزرعة أو من القطيع، وتستخدم في الحركات والتقارير والسجل التاريخي.
MD,
                'sort_order' => 12,
            ],
            [
                'name' => 'أسباب تغيير الذكر',
                'description' => <<<'MD'
قائمة مرجعية مستقلة لأسباب تغيير الذكر في العمليات التناسلية، لتوحيد القرار وتحليله لاحقًا بدل الاعتماد على وصف حر فقط.
MD,
                'sort_order' => 13,
            ],
            [
                'name' => 'المحافظات',
                'description' => <<<'MD'
قائمة مرجعية مستقلة للمحافظات تستخدم عند تحديد موقع المزرعة وربط المدن بالمحافظة التابعة لها.
MD,
                'sort_order' => 14,
            ],
            [
                'name' => 'المدن',
                'description' => <<<'MD'
قائمة مرجعية مستقلة للمدن، وترتبط كل مدينة بمحافظة لضمان سلامة بيانات الموقع واستخدامها بصورة موحدة في النظام.
MD,
                'sort_order' => 15,
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
