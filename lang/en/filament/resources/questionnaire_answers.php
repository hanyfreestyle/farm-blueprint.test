<?php

return [
    'navigation_group' => 'Questionnaire',
    'navigation_label' => 'Answers',
    'model_label' => 'Answer',
    'plural_model_label' => 'Answers',
    'sections' => [
        'basic' => 'Answer details',
    ],
    'fields' => [
        'question_id' => 'Question',
        'value' => 'Answer value',
        'readable_value' => 'Readable answer',
        'notes' => 'Notes',
        'needs_review' => 'Needs review',
        'review_status' => 'Review status',
        'reviewed_at' => 'Reviewed at',
        'updated_at' => 'Updated at',
        'main_section' => 'Main section',
        'subsection' => 'Subsection',
        'question' => 'Question',
        'question_type' => 'Question type',
    ],
    'actions' => [
        'mark_reviewed' => 'Mark reviewed',
        'mark_pending' => 'Return to review',
    ],
    'values' => [
        'yes' => 'Yes',
        'no' => 'No',
        'empty' => 'No answer',
    ],
];
