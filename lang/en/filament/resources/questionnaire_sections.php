<?php

return [
    'navigation_group' => 'Questionnaire',
    'navigation_label' => 'Sections',
    'model_label' => 'Section',
    'plural_model_label' => 'Sections',
    'sections' => [
        'basic' => 'Section information',
    ],
    'fields' => [
        'name' => 'Name',
        'parent_id' => 'Parent section',
        'parent_display' => 'Main section',
        'description' => 'Description',
        'sort_order' => 'Sort order',
        'section_type' => 'Type',
        'children_count' => 'Subsections count',
        'questions_count' => 'Questions count',
        'created_at' => 'Created at',
    ],
    'types' => [
        'main' => 'Main section',
        'subsection' => 'Subsection',
    ],
    'hints' => [
        'parent_id' => 'Leave empty to create a main section.',
    ],
    'messages' => [
        'delete_has_children' => 'This section cannot be deleted because it still contains subsections.',
        'delete_has_questions' => 'This section cannot be deleted because it still contains questions.',
    ],
];
