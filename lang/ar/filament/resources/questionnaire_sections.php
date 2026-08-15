<?php

return [
    'navigation_group' => 'الاستبيان',
    'navigation_label' => 'الأقسام',
    'model_label' => 'قسم',
    'plural_model_label' => 'الأقسام',
    'sections' => [
        'basic' => 'بيانات القسم',
    ],
    'fields' => [
        'name' => 'الاسم',
        'parent_id' => 'القسم الرئيسي',
        'parent_display' => 'القسم الرئيسي',
        'description' => 'الوصف',
        'sort_order' => 'الترتيب',
        'section_type' => 'النوع',
        'children_count' => 'عدد الأقسام الفرعية',
        'questions_count' => 'عدد الأسئلة',
        'created_at' => 'تاريخ الإنشاء',
    ],
    'types' => [
        'main' => 'قسم رئيسي',
        'subsection' => 'قسم فرعي',
    ],
    'hints' => [
        'parent_id' => 'اتركه فارغاً إذا كان هذا قسماً رئيسياً.',
    ],
    'messages' => [
        'delete_has_children' => 'لا يمكن حذف هذا القسم لأنه يحتوي على أقسام فرعية.',
        'delete_has_questions' => 'لا يمكن حذف هذا القسم لأنه يحتوي على أسئلة.',
    ],
];
