# Questionnaire Implementation Plan

## 1. Project Goal

Ù‡Ø°Ø§ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ø¹Ø¨Ø§Ø±Ø© Ø¹Ù† ØªØ·Ø¨ÙŠÙ‚ Ù…Ø®ØµØµ Ù„Ù…Ø±Ø§Ø¬Ø¹Ø© ÙˆØªØ­Ù„ÙŠÙ„ ØªØµÙˆØ± Ù†Ø¸Ø§Ù… Ø¥Ø¯Ø§Ø±Ø© Ù…Ø²Ø±Ø¹Ø© Ø§Ù„Ø£Ø±Ø§Ù†Ø¨ Ù…Ø¹ Ù…Ø®ØªØµ Domain Expert.

Ø§Ù„Ù‡Ø¯Ù Ù„ÙŠØ³ Ø¥Ù†Ø´Ø§Ø¡ Questionnaire Platform Ø¹Ø§Ù…Ø©.

Ø§Ù„Ù…Ø³Ø§Ø± Ø§Ù„Ø£Ø³Ø§Ø³ÙŠ Ù„Ù„Ù…Ø´Ø±ÙˆØ¹:

Domain Review
â†’ Questions
â†’ Specialist Answers
â†’ Optional Notes
â†’ Needs Review
â†’ Technical Specification

Ø§Ù„Ù…Ø®ØªØµ Ù„Ø§ ÙŠÙ‚ÙˆÙ… Ø¨ØªØµÙ…ÙŠÙ… Laravel Database Ø£Ùˆ Migrations Ù…Ø¨Ø§Ø´Ø±Ø©.

Ø§Ù„Ù…Ø®ØªØµ ÙŠØ¬ÙŠØ¨ Ø¹Ù† Ø£Ø³Ø¦Ù„Ø© ØªØ´ØºÙŠÙ„ÙŠØ© ÙˆÙÙ†ÙŠØ© ØªØ®Øµ Ø§Ù„ÙˆØ§Ù‚Ø¹ Ø§Ù„ÙØ¹Ù„ÙŠ Ù„Ù„Ù…Ø²Ø±Ø¹Ø©.

Ø¨Ø¹Ø¯ Ø§ÙƒØªÙ…Ø§Ù„ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§ØªØŒ ÙŠÙ‚ÙˆÙ… Ø§Ù„Ù†Ø¸Ø§Ù… Ø¨Ø¥Ù†ØªØ§Ø¬ Technical Specification Ù…Ù†Ø¸Ù… ÙŠÙ…ÙƒÙ† Ø¥Ø¹Ø·Ø§Ø¤Ù‡ Ù„Ø§Ø­Ù‚Ù‹Ø§ Ù„Ù…Ø·ÙˆØ± Ø£Ùˆ AI Developer Ù„Ø¨Ù†Ø§Ø¡ Ø§Ù„Ù†Ø¸Ø§Ù… Ø§Ù„Ø­Ù‚ÙŠÙ‚ÙŠ.

---

# 2. Core Development Reference

Ø§Ù„Ù…Ø±Ø¬Ø¹ Ø§Ù„Ø£Ø³Ø§Ø³ÙŠ Ù„Ù„ØªØ¹Ø§Ù…Ù„ Ù…Ø¹ Laravel Core Ù‡Ùˆ:

AGENTS.md

Ø§Ù„Ù…ÙˆØ¬ÙˆØ¯ ÙÙŠ Ø¬Ø°Ø± Ø§Ù„Ù…Ø´Ø±ÙˆØ¹.

Ù‚Ø¨Ù„ ØªÙ†ÙÙŠØ° Ø£ÙŠ Phase ÙŠØ¬Ø¨:

1. Ù‚Ø±Ø§Ø¡Ø© AGENTS.md Ø¨Ø§Ù„ÙƒØ§Ù…Ù„.
2. Ø§Ù„Ø§Ù„ØªØ²Ø§Ù… Ø¨Ø¥ØµØ¯Ø§Ø±Ø§Øª Laravel / Filament ÙˆØ§Ù„Ø­Ø²Ù… Ø§Ù„Ù…ÙˆØ¬ÙˆØ¯Ø© ÙØ¹Ù„ÙŠÙ‹Ø§.
3. Ø§ØªØ¨Ø§Ø¹ conventions Ø§Ù„Ù…ÙˆØ¬ÙˆØ¯Ø© ÙÙŠ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹.
4. Ø§Ø³ØªØ®Ø¯Ø§Ù… Ù†ÙØ³ Ø£Ø³Ù„ÙˆØ¨ Ø¨Ù†Ø§Ø¡ Filament Resources Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… ÙÙŠ Ø§Ù„Ù€Core.
5. Ø¹Ø¯Ù… Ø§ÙØªØ±Ø§Ø¶ APIs Ø£Ùˆ namespaces ØºÙŠØ± Ù…ÙˆØ¬ÙˆØ¯Ø© ÙÙŠ Ø§Ù„Ù†Ø³Ø®Ø© Ø§Ù„Ù…Ø«Ø¨ØªØ©.

Ù‡Ø°Ø§ Ø§Ù„Ù…Ù„Ù:

docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md

Ø®Ø§Øµ ÙÙ‚Ø· Ø¨Ø®Ø·Ø© ØªÙ†ÙÙŠØ° Ù…Ø´Ø±ÙˆØ¹ Ø§Ù„Ø§Ø³ØªØ¨ÙŠØ§Ù†.

AGENTS.md
Ø®Ø§Øµ Ø¨Ù‚ÙˆØ§Ø¹Ø¯ Ø§Ù„Ù€Core ÙˆØ§Ù„Ù…Ø´Ø±ÙˆØ¹ ÙƒÙƒÙ„.

---

# 3. Explicit Non-Goals

Ù‡Ø°Ø§ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ù„ÙŠØ³:

- Generic Questionnaire Builder
- Survey SaaS
- Multi Project Platform
- Multi Tenant Platform
- Blueprint Engine
- Questionnaire Versioning System
- Questionnaire Sessions System
- Source Mapping Engine
- Generic Decision Engine
- Rules DSL
- Generic Workflow Builder

Ù„Ø§ ÙŠØªÙ… Ø¥Ø¶Ø§ÙØ© Ø£ÙŠ abstraction Ø¹Ø§Ù…Ø© Ø¥Ù„Ø§ Ø¥Ø°Ø§ Ø¸Ù‡Ø±Øª Ù„Ù‡Ø§ Ø­Ø§Ø¬Ø© ÙØ¹Ù„ÙŠØ© ÙÙŠ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ø§Ù„Ø­Ø§Ù„ÙŠ ÙˆØªÙ… Ø§Ø¹ØªÙ…Ø§Ø¯Ù‡Ø§ ØµØ±Ø§Ø­Ø©.

---

# 4. Simplified Architecture

Ø§Ù„Ø¨Ù†ÙŠØ© Ø§Ù„Ø£Ø³Ø§Ø³ÙŠØ©:

Section
â†’ Subsection
â†’ Questions
â†’ Question Options
â†’ Answers
â†’ Technical Report

Ù„Ø¯ÙŠÙ†Ø§ Ø§Ø³ØªØ¨ÙŠØ§Ù† ÙˆØ§Ø­Ø¯ ÙÙ‚Ø·.

Ù„Ø§ ØªÙˆØ¬Ø¯ Questionnaire Sessions.

ÙƒÙ„ Question Ù„Ù‡Ø§ Answer Ø­Ø§Ù„ÙŠØ© ÙˆØ§Ø­Ø¯Ø© ÙÙ‚Ø·.

Ø¹Ù†Ø¯ ØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø© ÙŠØªÙ… ØªØ­Ø¯ÙŠØ« Ù†ÙØ³ Answer.

Ù‡ÙŠÙƒÙ„ Ø§Ù„Ø£Ù‚Ø³Ø§Ù… Ø§Ù„Ù…Ø¹ØªÙ…Ø¯ Ø§Ù„Ø¢Ù†:

Main Section
â†’ Subsection
â†’ Questions

ÙˆÙ„Ø§ ÙŠØªÙ… Ø§Ø¹ØªÙ…Ø§Ø¯ Ø´Ø¬Ø±Ø© Ø¹Ù…ÙŠÙ‚Ø© Ø¹Ø§Ù…Ø© ÙÙŠ Ù‡Ø°Ø§ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹.

---

# 5. Sections

Ø§Ù„Ø£Ù‚Ø³Ø§Ù… Ù‡ÙŠ Ø§Ù„Ù…Ø±Ø§Ø­Ù„ Ø§Ù„Ø±Ø¦ÙŠØ³ÙŠØ© Ù„Ù„Ø¯Ø±Ø§Ø³Ø©.

ÙˆÙ‡ÙŠ Ø§Ù„Ù…ØµØ¯Ø± Ø§Ù„Ø°ÙŠ ÙŠØªÙ… Ù…Ù†Ù‡ Ø¨Ù†Ø§Ø¡ Ø§Ù„Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø¬Ø§Ù†Ø¨ÙŠØ© ÙÙŠ ÙˆØ§Ø¬Ù‡Ø© Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù….

ÙŠØªÙ… Ø§Ø³ØªØ®Ø¯Ø§Ù… Ø¬Ø¯ÙˆÙ„ ÙˆØ§Ø­Ø¯ Ø°Ø§ØªÙŠ Ø§Ù„Ø±Ø¨Ø· Ù„Ø¯Ø¹Ù… Ù…Ø³ØªÙˆÙŠÙŠÙ† Ù…Ù†Ø·Ù‚ÙŠÙŠÙ† ÙÙ‚Ø·:

- Main Section
- Subsection

Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø¨Ø¯Ø¦ÙŠØ©:

- id
- parent_id nullable
- name
- description
- sort_order
- timestamps

description:

ÙŠØ­ØªÙˆÙŠ Ø¹Ù„Ù‰ Markdown.

Ø§Ù„ØªØ±ØªÙŠØ¨:

sort_order

ÙŠØ­Ø¯Ø¯ ØªØ±ØªÙŠØ¨ Ø§Ù„Ù‚Ø³Ù… ÙÙŠ:

- Filament
- Frontend Sidebar
- Technical Report

Ù…Ø¹Ù†Ù‰ parent_id:

parent_id = null
â†’ Main Section

parent_id != null
â†’ Subsection

Ø§Ù„Ø¹Ù„Ø§Ù‚Ø§Øª:

- Section belongsTo Parent
- Section hasMany Children
- Section hasMany Questions

Ù‚Ø§Ø¹Ø¯Ø© Ø§Ù„Ø¨Ù†ÙŠØ©:

- ÙŠØ¯Ø¹Ù… Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ù…Ø³ØªÙˆÙŠÙŠÙ† Ù…Ù†Ø·Ù‚ÙŠÙŠÙ† ÙÙ‚Ø· Ù„Ù„Ø£Ù‚Ø³Ø§Ù…
- Ø§Ù„Ø£Ø³Ø¦Ù„Ø© ØªÙ†ØªÙ…ÙŠ Ø¹Ø§Ø¯Ø© Ø¥Ù„Ù‰ Subsections
- Ù„Ø§ ÙŠØªÙ… Ø¨Ù†Ø§Ø¡ deep tree Ø¹Ø§Ù…

Ø³Ù„ÙˆÙƒ Ø§Ù„Ø­Ø°Ù Ø§Ù„Ø¢Ù…Ù†:

- Ø­Ø°Ù Main Section Ù…Ø¹ ÙˆØ¬ÙˆØ¯ Subsections ØªØ­ØªÙ‡ ÙŠØ¬Ø¨ Ø£Ù† ÙŠÙƒÙˆÙ† Ù…Ø±ÙÙˆØ¶Ø§Ù‹
- Ø­Ø°Ù Ø£ÙŠ Section Ù…Ø§ Ø¯Ø§Ù… Ù…Ø±ØªØ¨Ø·Ø§Ù‹ Ø¨Ù‡ Questions ÙŠØ¬Ø¨ Ø£Ù† ÙŠÙƒÙˆÙ† Ù…Ø±ÙÙˆØ¶Ø§Ù‹

---

# 6. Questions

ÙƒÙ„ Question ØªØªØ¨Ø¹ Section ÙˆØ§Ø­Ø¯Ø©.

Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø¨Ø¯Ø¦ÙŠØ©:

- id
- section_id
- title
- help_text
- type
- is_required
- sort_order
- depends_on_question_id nullable
- dependency_operator nullable
- dependency_value nullable
- report_category nullable
- target_entity nullable
- timestamps

Ù„Ø§ ÙŠØªÙ… Ø§Ø¹ØªÙ…Ø§Ø¯ Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù†Ù‡Ø§Ø¦ÙŠØ© Ù‚Ø¨Ù„ ØªÙ†ÙÙŠØ° Phase 1 ÙˆÙ…Ø±Ø§Ø¬Ø¹Ø© Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ ÙØ¹Ù„ÙŠÙ‹Ø§.

---

# 7. Question Types

ÙŠØªÙ… Ø§Ø³ØªØ®Ø¯Ø§Ù… PHP Backed Enum Ø¨Ø§Ø³Ù… Ù…Ù†Ø§Ø³Ø¨ Ù…Ø«Ù„:

QuestionType

Ø§Ù„Ù‚ÙŠÙ…:

text
textarea
number
date
yes_no
single_choice
multi_choice
select

Ù…Ø¹Ø§Ù†ÙŠÙ‡Ø§:

text
= Ù†Øµ Ù‚ØµÙŠØ±

textarea
= Ù†Øµ Ø·ÙˆÙŠÙ„

number
= Ø±Ù‚Ù…

date
= ØªØ§Ø±ÙŠØ®

yes_no
= Ù†Ø¹Ù… / Ù„Ø§

single_choice
= Ø§Ø®ØªÙŠØ§Ø± ÙˆØ§Ø­Ø¯

multi_choice
= Ø£ÙƒØ«Ø± Ù…Ù† Ø§Ø®ØªÙŠØ§Ø±

select
= Ù‚Ø§Ø¦Ù…Ø© Ù…Ù†Ø³Ø¯Ù„Ø©

---

# 8. Question Options

ØªØ³ØªØ®Ø¯Ù… ÙÙ‚Ø· Ù…Ø¹:

single_choice
multi_choice
select

Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø¨Ø¯Ø¦ÙŠØ©:

- id
- question_id
- label
- value
- sort_order
- timestamps

ÙÙŠ Filament ÙŠØªÙ… Ø¥Ø¯Ø§Ø±ØªÙ‡Ø§ Ø¨ÙˆØ§Ø³Ø·Ø© Repeater Ø¯Ø§Ø®Ù„ Question Resource.

Ø¹Ù†Ø¯ Ø§Ø®ØªÙŠØ§Ø± Question Type Ù„Ø§ ÙŠØ­ØªØ§Ø¬ Options:

- text
- textarea
- number
- date
- yes_no

Ù„Ø§ ÙŠØ¸Ù‡Ø± Repeater.

Ø¹Ù†Ø¯ Ø§Ø®ØªÙŠØ§Ø±:

- single_choice
- multi_choice
- select

ÙŠØ¸Ù‡Ø± Repeater ØªÙ„Ù‚Ø§Ø¦ÙŠÙ‹Ø§.

---

# 9. Simple Conditional Questions

Ø§Ù„Ù†Ø¸Ø§Ù… ÙŠØ¯Ø¹Ù… Ø´Ø±Ø· Ø¸Ù‡ÙˆØ± Ø¨Ø³ÙŠØ· ÙÙ‚Ø·.

Ø§Ù„Ø³Ø¤Ø§Ù„ Ø§Ù„Ø­Ø§Ù„ÙŠ ÙŠÙ…ÙƒÙ† Ø£Ù† ÙŠØ¹ØªÙ…Ø¯ Ø¹Ù„Ù‰ Ø¥Ø¬Ø§Ø¨Ø© Ø³Ø¤Ø§Ù„ Ø³Ø§Ø¨Ù‚.

Ø§Ù„Ù…Ù†Ø·Ù‚:

Parent Question
+
Operator
+
Expected Value

Ø§Ù„Ù€Operators Ø§Ù„Ù…Ø·Ù„ÙˆØ¨Ø© Ù…Ø¨Ø¯Ø¦ÙŠÙ‹Ø§:

equals
contains

Ù…Ø«Ø§Ù„:

Question A:
Ù‡Ù„ Ø§Ù„ØªØµÙˆØ± Ù…Ù†Ø§Ø³Ø¨ØŸ

Answer:
needs_changes

Question B:
Ù…Ø§ Ø§Ù„ØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ù…Ø·Ù„ÙˆØ¨ØŸ

Question B ÙŠØ¸Ù‡Ø± Ø¹Ù†Ø¯Ù…Ø§:

Question A
equals
needs_changes

Ù„Ø§ ÙŠØªÙ… Ø¨Ù†Ø§Ø¡:

- DSL
- Nested Rule Engine
- Complex Boolean Expressions

ÙÙŠ Ù‡Ø°Ù‡ Ø§Ù„Ù…Ø±Ø­Ù„Ø©.

---

# 10. Answers

Ù„Ø¯ÙŠÙ†Ø§ Ù†ØªÙŠØ¬Ø© ÙˆØ§Ø­Ø¯Ø© ÙÙ‚Ø· Ù„Ù„Ø§Ø³ØªØ¨ÙŠØ§Ù†.

ÙƒÙ„ Question Ù„Ù‡Ø§ Answer ÙˆØ§Ø­Ø¯Ø© ÙƒØ­Ø¯ Ø£Ù‚ØµÙ‰.

Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø¨Ø¯Ø¦ÙŠØ©:

- id
- question_id
- value
- notes nullable
- needs_review boolean
- review_status
- reviewed_at nullable
- timestamps

ÙŠØ¬Ø¨ ÙˆØ¬ÙˆØ¯ Unique Constraint Ø¹Ù„Ù‰:

question_id

Ø¨Ø­ÙŠØ« Ù„Ø§ ÙŠØªÙ… Ø¥Ù†Ø´Ø§Ø¡ Ø¹Ø¯Ø© Answers Ù„Ù†ÙØ³ Ø§Ù„Ø³Ø¤Ø§Ù„.

Ø¹Ù†Ø¯ ØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø© ÙŠØªÙ… Ø§Ø³ØªØ®Ø¯Ø§Ù…:

updateOrCreate

Ø£Ùˆ Ø§Ù„Ù…Ù†Ø·Ù‚ Ø§Ù„Ù…ÙƒØ§ÙØ¦.

---

# 11. Answer Storage

Ø§Ù„Ù‚ÙŠÙ… Ø§Ù„ÙØ±Ø¯ÙŠØ© Ù…Ø«Ù„:

text
textarea
number
date
yes_no
single_choice
select

ÙŠØªÙ… ØªØ®Ø²ÙŠÙ† Ù‚ÙŠÙ…Ø© ÙˆØ§Ø­Ø¯Ø©.

multi_choice:

ÙŠØªÙ… ØªØ®Ø²ÙŠÙ† Array Ø¨Ø§Ø³ØªØ®Ø¯Ø§Ù… JSON/Cast Ù…Ù†Ø§Ø³Ø¨.

Ø§Ù„ØªÙØ§ØµÙŠÙ„ Ø§Ù„Ù†Ù‡Ø§Ø¦ÙŠØ© ÙŠØªÙ… Ø§Ø¹ØªÙ…Ø§Ø¯Ù‡Ø§ ÙÙŠ Phase 1.

---

# 12. Notes

ÙƒÙ„ Ø³Ø¤Ø§Ù„ ÙÙŠ Frontend ÙŠØ­ØªÙˆÙŠ Ø¹Ù„Ù‰:

Ù…Ù„Ø§Ø­Ø¸Ø§Øª Ø§Ø®ØªÙŠØ§Ø±ÙŠØ©

Ø§Ù„Ù…Ø®ØªØµ ÙŠØ³ØªØ·ÙŠØ¹ ÙƒØªØ§Ø¨Ø© Ø£ÙŠ Ù…Ù„Ø§Ø­Ø¸Ø© Ø¥Ø¶Ø§ÙÙŠØ© ØªØ®Øµ Ø§Ù„Ø³Ø¤Ø§Ù„.

Ù…Ø«Ø§Ù„:

Ø§Ù„Ø³Ø¤Ø§Ù„:
Ù…Ø§ Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø·Ù„ÙˆØ¨Ø© Ù„Ø¨ÙŠØ§Ù†Ø§Øª Ø§Ù„Ù…Ø²Ø±Ø¹Ø©ØŸ

Ø§Ù„Ù…Ø®ØªØµ ÙŠØ®ØªØ§Ø±:

- Ø§Ù„Ø§Ø³Ù…
- Ø§Ù„Ù‡Ø§ØªÙ
- Ø§Ù„Ø­Ø§Ù„Ø©

Ø«Ù… ÙŠÙƒØªØ¨ ÙÙŠ Notes:

"Ù†Ø­ØªØ§Ø¬ Ø£ÙŠØ¶Ù‹Ø§ Ø±Ù‚Ù… Ø§Ù„Ø³Ø¬Ù„ Ø§Ù„ØªØ¬Ø§Ø±ÙŠ."

---

# 13. Needs Review

Ø¥Ø°Ø§ ÙƒØ§Ù†Øª:

notes

ØºÙŠØ± ÙØ§Ø±ØºØ©:

needs_review = true

ØªÙ„Ù‚Ø§Ø¦ÙŠÙ‹Ø§.

Ù„Ø§ ÙŠØ­Ø¯Ø¯ Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… needs_review Ø¨Ù†ÙØ³Ù‡.

Ø§Ù„Ù…Ø³Ø§Ø±:

Specialist writes note
â†’ Answer becomes Needs Review
â†’ Admin reviews note
â†’ Admin may update existing Question
or
â†’ Add new Question
or
â†’ Add new Option
â†’ Mark note Reviewed

---

# 14. Review Status

Ø§Ø³ØªØ®Ø¯Ù… Enum Ø¨Ø³ÙŠØ· Ø¥Ø°Ø§ ÙƒØ§Ù† Ù…Ù†Ø§Ø³Ø¨Ù‹Ø§:

pending
reviewed

Ù„Ø§ ÙŠØªÙ… Ø¨Ù†Ø§Ø¡ Workflow Ø£ÙƒØ¨Ø± Ù…Ù† Ø°Ù„Ùƒ.

Ø§Ù„Ù…Ø·Ù„ÙˆØ¨ ÙÙ‚Ø· Ù…Ø¹Ø±ÙØ©:

Ù‡Ù„ ØªÙ…Øª Ù…Ø±Ø§Ø¬Ø¹Ø© Ù…Ù„Ø§Ø­Ø¸Ø© Ø§Ù„Ù…Ø®ØªØµ Ø£Ù… Ù„Ø§ØŸ

---

# 15. Business Questions vs Technical Decisions

Ø§Ù„Ù…Ø®ØªØµ Ù„Ø§ ÙŠØªÙ… Ø³Ø¤Ø§Ù„Ù‡ Ø¨Ù„ØºØ© ØªÙ‚Ù†ÙŠØ©.

Ù…Ø«Ø§Ù„ ØºÙŠØ± ØµØ­ÙŠØ­:

"Ù‡Ù„ ØªØ±ÙŠØ¯ Farm Status ÙƒÙ€ Enum Ø£Ù… TableØŸ"

Ø§Ù„Ø³Ø¤Ø§Ù„ Ø§Ù„ØµØ­ÙŠØ­:

"Ù‡Ù„ Ø­Ø§Ù„Ø§Øª Ø§Ù„Ù…Ø²Ø±Ø¹Ø© Ù‚ÙŠÙ… Ø«Ø§Ø¨ØªØ© Ù„Ø§ ØªØªØºÙŠØ±ØŒ Ø£Ù… ÙŠØ¬Ø¨ Ø£Ù† ÙŠØ³ØªØ·ÙŠØ¹ Ù…Ø¯ÙŠØ± Ø§Ù„Ù†Ø¸Ø§Ù… Ø¥Ø¶Ø§ÙØ© ÙˆØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ø­Ø§Ù„Ø§ØªØŸ"

Ø¥Ø°Ø§ Ø§Ø®ØªØ§Ø±:

Ù‚ÙŠÙ… Ø«Ø§Ø¨ØªØ©

ÙŠÙ…ÙƒÙ† Ø£Ù† ÙŠÙ†ØªØ¬ Ø§Ù„ØªÙ‚Ø±ÙŠØ±:

Recommendation:
Enum

Ø¥Ø°Ø§ Ø§Ø®ØªØ§Ø±:

ÙŠÙ…ÙƒÙ† Ø¥Ø¶Ø§ÙØªÙ‡Ø§ ÙˆØªØ¹Ø¯ÙŠÙ„Ù‡Ø§

ÙŠÙ…ÙƒÙ† Ø£Ù† ÙŠÙ†ØªØ¬ Ø§Ù„ØªÙ‚Ø±ÙŠØ±:

Recommendation:
Managed Lookup Table

Ù…Ø«Ù„:

farm_statuses

---

# 16. Report Categories

ÙŠÙ…ÙƒÙ† Ø£Ù† ØªØ­ØªÙˆÙŠ Question Ø¹Ù„Ù‰:

report_category

Ø§Ù„Ø£Ù†ÙˆØ§Ø¹ Ø§Ù„Ù…Ø¨Ø¯Ø¦ÙŠØ©:

field
lookup
relationship
workflow
rule
alert
report
general

Ø§Ù„Ù‡Ø¯Ù Ù‡Ùˆ Ù…Ø³Ø§Ø¹Ø¯Ø© Technical Report Generator.

Ù„Ø§ ÙŠØ¹ØªØ¨Ø± Ù‡Ø°Ø§ Generic Rule Engine.

---

# 17. Target Entity

ÙŠÙ…ÙƒÙ† Ø£Ù† ØªØ­ØªÙˆÙŠ Question Ø¹Ù„Ù‰:

target_entity

Ù…Ø«Ø§Ù„:

farm
barn
battery
cage
rabbit
mating
birth

Ø§Ù„Ù‡Ø¯Ù:

Ø±Ø¨Ø· Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø© Ø¨Ø§Ù„Ø¬Ø²Ø¡ Ø§Ù„Ø°ÙŠ Ø³ØªØ¸Ù‡Ø± ØªØ­ØªÙ‡ ÙÙŠ Ø§Ù„ØªÙ‚Ø±ÙŠØ± Ø§Ù„ØªÙ‚Ù†ÙŠ.

Ù…Ø«Ø§Ù„:

target_entity:
farm

report_category:
field

---

# 18. Technical Specification Goal

Ø¨Ø¹Ø¯ Ø§Ù†ØªÙ‡Ø§Ø¡ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§ØªØŒ Ù„Ø§ Ù†Ø±ÙŠØ¯ ØªÙ‚Ø±ÙŠØ±Ù‹Ø§ Ù…Ù† Ù†ÙˆØ¹:

Question:
Ù…Ø§ Ø§Ù„Ø­Ù‚ÙˆÙ„ Ø§Ù„Ù…Ø·Ù„ÙˆØ¨Ø©ØŸ

Answer:
Ø§Ù„Ø§Ø³Ù…ØŒ Ø§Ù„Ù‡Ø§ØªÙØŒ Ø§Ù„Ø­Ø§Ù„Ø©.

Ø¨Ù„ Ù†Ø±ÙŠØ¯ Technical Specification Ù…Ù†Ø¸Ù….

Ù…Ø«Ø§Ù„:

# Farm

## Fields

name
- type: string

phone
- type: string

status
- source: Farm Status Requirement

## Managed Lists

Farm Status

If editable:
Recommend:
farm_statuses table

If fixed:
Recommend:
Enum

---

# 19. Technical Specification Sections

Ø§Ù„ØªÙ‚Ø±ÙŠØ± Ø§Ù„Ù†Ù‡Ø§Ø¦ÙŠ ÙŠÙ…ÙƒÙ† Ø£Ù† ÙŠØ­ØªÙˆÙŠ Ø¹Ù„Ù‰:

# Entities

# Fields

# Fixed Values / Enums

# Managed Lookup Tables

# Relationships

# Workflow Requirements

# Business Rules

# Alerts

# Reports

# Needs Review

Ù„Ø§ ÙŠØªÙ… Ø§Ø¹ØªÙ…Ø§Ø¯ Ø´ÙƒÙ„ Ø§Ù„ØªÙ‚Ø±ÙŠØ± Ø§Ù„Ù†Ù‡Ø§Ø¦ÙŠ Ø¨Ø§Ù„ÙƒØ§Ù…Ù„ Ù‚Ø¨Ù„ Phase 4.

---

# 20. Needs Review in Technical Report

Ø£ÙŠ Answer ØªØ­ØªÙˆÙŠ:

needs_review = true

ÙˆÙ„Ø§ ØªØ²Ø§Ù„:

review_status = pending

ØªØ¸Ù‡Ø± ÙÙŠ Ø§Ù„ØªÙ‚Ø±ÙŠØ± ØªØ­Øª:

# Needs Review

Ù…Ø¹:

- Section
- Question
- Current Answer
- Specialist Note

ÙˆÙ„Ø§ ÙŠØªÙ… ØªØ­ÙˆÙŠÙ„ Ø§Ù„Ù…Ù„Ø§Ø­Ø¸Ø© ØºÙŠØ± Ø§Ù„Ù…Ø¹ØªÙ…Ø¯Ø© ØªÙ„Ù‚Ø§Ø¦ÙŠÙ‹Ø§ Ø¥Ù„Ù‰ Migration Requirement.

---

# 21. Filament Administration Goal

Filament Admin ÙŠÙƒÙˆÙ† Ø¨Ø³ÙŠØ·Ù‹Ø§.

Ø§Ù„Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ù…Ø·Ù„ÙˆØ¨Ø© Ù…Ø¨Ø¯Ø¦ÙŠÙ‹Ø§:

Questionnaire

- Sections
- Questions
- Answers

Question Options Ù„Ø§ ØªØ­ØªØ§Ø¬ Resource Ù…Ø³ØªÙ‚Ù„Ø© Ø¨Ø§Ù„Ø¶Ø±ÙˆØ±Ø©.

ÙŠÙØ¶Ù„ Ø¥Ø¯Ø§Ø±ØªÙ‡Ø§ Ø¯Ø§Ø®Ù„ Question Resource Ø¨Ø§Ø³ØªØ®Ø¯Ø§Ù… Repeater.

---

# 22. Section Resource

Ø§Ù„Ù…Ø·Ù„ÙˆØ¨:

List
Create
Edit
Delete
Ordering

Form:

Name

Description
Markdown Editor Ø£Ùˆ textarea Ù…Ù†Ø§Ø³Ø¨ Ø­Ø³Ø¨ Core conventions.

Sort Order

---

# 23. Question Resource

Form Ù…Ø¨Ø¯Ø¦ÙŠ:

Section

Question Title

Help Text

Question Type

Required

Sort Order

Options Repeater
ÙŠØ¸Ù‡Ø± ÙÙ‚Ø· Ø¹Ù†Ø¯ Ø§Ù„Ø­Ø§Ø¬Ø©.

Conditional Question Settings
ØªØ¸Ù‡Ø± ÙÙ‚Ø· Ø¹Ù†Ø¯ ØªÙØ¹ÙŠÙ„ dependency.

Report Category

Target Entity

Metadata ÙÙ‚Ø· Ø¥Ø°Ø§ Ø¸Ù‡Ø±Øª Ù„Ù‡Ø§ Ø­Ø§Ø¬Ø© ÙØ¹Ù„ÙŠØ©.

---

# 24. Dynamic Question Form

Filament form ÙŠØ¬Ø¨ Ø£Ù† ÙŠØªØºÙŠØ± Ø­Ø³Ø¨:

QuestionType

TEXT:
Ù„Ø§ Options.

TEXTAREA:
Ù„Ø§ Options.

NUMBER:
ÙŠÙ…ÙƒÙ† Ù„Ø§Ø­Ù‚Ù‹Ø§ Ø¥Ø¶Ø§ÙØ©:
min
max
step
Ø¥Ø°Ø§ Ø¸Ù‡Ø±Øª Ø­Ø§Ø¬Ø© ÙØ¹Ù„ÙŠØ©.

DATE:
Ù„Ø§ Options.

YES_NO:
Ø§Ù„Ù‚ÙŠÙ… Ù…Ø¹Ø±ÙˆÙØ© Ø¯Ø§Ø®Ù„ÙŠÙ‹Ø§:
yes / no

SINGLE_CHOICE:
Options Repeater.

MULTI_CHOICE:
Options Repeater.

SELECT:
Options Repeater.

---

# 25. Answers Administration

Filament Answers page ØªØ¹Ø±Ø¶ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§Øª Ù…Ø¬Ù…Ø¹Ø© Ø­Ø³Ø¨ Section.

ÙŠØ¬Ø¨ Ø£Ù† ÙŠØ±Ù‰ Admin:

Question

Readable Answer

Notes

Needs Review

Review Status

Edit

Ù„Ø§ ÙŠØªÙ… Ø¹Ø±Ø¶:

Raw JSON

Internal Value

IDs

Ø¥Ù„Ø§ Ø¹Ù†Ø¯ Ø§Ù„Ø­Ø§Ø¬Ø© Ø§Ù„ØªÙ‚Ù†ÙŠØ©.

---

# 26. Frontend Goal

ÙˆØ§Ø¬Ù‡Ø© Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… ÙŠØ¬Ø¨ Ø£Ù† ØªÙƒÙˆÙ† Ø¨Ø³ÙŠØ·Ø© Ø¬Ø¯Ù‹Ø§.

Approved stack:

Bootstrap 5
Bootstrap RTL
Font Awesome 6
Tajawal
Blade
Lightweight JavaScript

Ù„Ø§ ÙŠØªÙ… Ø§Ø³ØªØ®Ø¯Ø§Ù… Filament UI ÙÙŠ ÙˆØ§Ø¬Ù‡Ø© Ø§Ù„Ù…Ø®ØªØµ.

---

# 27. Frontend Layout

Desktop:

Sidebar
+
Questions Area

Sidebar ÙŠØªÙ… ØªÙˆÙ„ÙŠØ¯Ù‡Ø§ Ù…Ù†:

Sections

Ø¨Ø§Ù„ØªØ±ØªÙŠØ¨:

sort_order

ÙŠØ³ØªØ·ÙŠØ¹ Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… Ø§Ù„ØªÙ†Ù‚Ù„ Ø¨Ø­Ø±ÙŠØ© Ø¨ÙŠÙ† Ø§Ù„Ø£Ù‚Ø³Ø§Ù….

Ù„Ø§ ÙŠÙˆØ¬Ø¯ Session Flow Ù…Ø¹Ù‚Ø¯.

---

# 28. Question Rendering

Frontend ÙŠÙ‚Ø±Ø£ QuestionType ÙˆÙŠÙˆÙ„Ø¯ Control Ù…Ù†Ø§Ø³Ø¨.

text:
input text

textarea:
textarea

number:
input number

date:
input date

yes_no:
Yes / No controls

single_choice:
radio/cards

multi_choice:
checkbox/cards

select:
select

---

# 29. Optional Notes on Every Question

ØªØ­Øª ÙƒÙ„ Ø³Ø¤Ø§Ù„ ÙŠÙˆØ¬Ø¯:

Ù…Ù„Ø§Ø­Ø¸Ø§Øª Ø¥Ø¶Ø§ÙÙŠØ©
Ø§Ø®ØªÙŠØ§Ø±ÙŠ

Textarea ØµØºÙŠØ±Ø©.

Ù„Ø§ ØªÙƒÙˆÙ† Ø¨Ø§Ø±Ø²Ø© Ø£ÙƒØ«Ø± Ù…Ù† Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø© Ø§Ù„Ø£Ø³Ø§Ø³ÙŠØ©.

Ø¥Ø°Ø§ ÙƒØªØ¨ Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… Ø¨Ù‡Ø§:

needs_review = true

---

# 30. Answer Persistence

Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§Øª ØªØ­ÙØ¸ ÙØ¹Ù„ÙŠÙ‹Ø§ ÙÙŠ Database.

Ù„Ø§ ØªØ¹ØªÙ…Ø¯ Ø¹Ù„Ù‰ Browser Session.

Ø¥Ø°Ø§ Ø£ØºÙ„Ù‚ Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù…:

Browser
Computer

Ø«Ù… Ø¹Ø§Ø¯ Ù„Ø§Ø­Ù‚Ù‹Ø§:

Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§Øª ØªØ¸Ù„ Ù…Ø­ÙÙˆØ¸Ø©.

Ù„Ø§ ÙŠÙˆØ¬Ø¯ QuestionnaireSession Model.

Ù„Ø¯ÙŠÙ†Ø§ Answer ÙˆØ§Ø­Ø¯Ø© Ù„ÙƒÙ„ Question.

---

# 31. Technical Report Frontend

Route Ù„Ø§Ø­Ù‚Ù‹Ø§ Ù…Ø«Ù„:

/technical-report

ØªØ¹Ø±Ø¶ Technical Specification Ø§Ù„Ù†Ù‡Ø§Ø¦ÙŠ.

ØªØ­ØªÙˆÙŠ Ø¹Ù„Ù‰:

Print

ÙˆÙŠØ³ØªØ®Ø¯Ù… Browser Print:

Save as PDF

Ù„Ø§ Ù†Ø¶ÙŠÙ PDF Package ÙÙŠ Ø§Ù„Ø¨Ø¯Ø§ÙŠØ©.

---

# 32. Repository Encoding Standard

Ù‡Ø°Ù‡ Ø§Ù„Ù‚ÙˆØ§Ø¹Ø¯ Ø«Ø§Ø¨ØªØ© Ù„Ù„Ù…Ø´Ø±ÙˆØ¹.

Encoding:

UTF-8 without BOM

Line Endings:

LF

Final Newline:

Yes

ÙŠØ¬Ø¨ Ø§Ù„Ø­ÙØ§Ø¸ Ø¹Ù„Ù‰ Ø§Ù„Ù„ØºØ© Ø§Ù„Ø¹Ø±Ø¨ÙŠØ© Ø¨Ø´ÙƒÙ„ ØµØ­ÙŠØ­.

---

# 33. File Editing Rules

Ù‚Ø¨Ù„ ØªØ¹Ø¯ÙŠÙ„ Ø£ÙŠ Ù…Ù„Ù:

Read it first.

Ø§Ø³ØªØ®Ø¯Ù… targeted edits.

Ù„Ø§ ØªØ¹ÙŠØ¯ ÙƒØªØ§Ø¨Ø© Ù…Ù„Ù ÙƒØ§Ù…Ù„ Ø¥Ø°Ø§ ÙƒØ§Ù† ØªØ¹Ø¯ÙŠÙ„ ØµØºÙŠØ± ÙƒØ§ÙÙŠÙ‹Ø§.

Ø¥Ø°Ø§ ÙØ´Ù„ Patch:

- Ù„Ø§ ØªÙƒØ±Ø± Ù†ÙØ³ Ø§Ù„Ù…Ø­Ø§ÙˆÙ„Ø©
- Ø§ÙØ­Øµ Ø§Ù„Ù…Ø­ØªÙˆÙ‰ Ø§Ù„Ø­Ø§Ù„ÙŠ
- Ø§ÙØ­Øµ encoding
- Ø§ÙØ­Øµ line endings
- Ø«Ù… Ø¹Ø¯Ù„

Ù„Ø§ ØªØ¹Ù…Ù„ normalization Ù„Ù…Ù„ÙØ§Øª ØºÙŠØ± Ù…Ø±ØªØ¨Ø·Ø© Ø¨Ø§Ù„Ù…Ù‡Ù…Ø©.

Ù„Ø§ ØªÙ†Ø´Ø¦ noisy diffs Ø¨Ø¯ÙˆÙ† Ø¯Ø§Ø¹Ù.

---

# 34. Filament Compatibility

ÙŠØ¬Ø¨ Ù‚Ø±Ø§Ø¡Ø©:

AGENTS.md

Ù‚Ø¨Ù„ ØªÙ†ÙÙŠØ° Ø£ÙŠ Filament Resource.

ÙˆÙŠØ¬Ø¨ Ø§Ù„ØªØ­Ù‚Ù‚ Ù…Ù†:

actual installed Filament version

Ù…Ù† Ø§Ù„Ù…Ø´Ø±ÙˆØ¹.

Ù„Ø§ ÙŠØªÙ… Ø§ÙØªØ±Ø§Ø¶ namespace Ø£Ùˆ API Ù…Ù† Ø§Ù„Ø°Ø§ÙƒØ±Ø©.

Existing working resources in the Laravel Core are the preferred implementation reference.

---

# 35. Testing Rules

Ù„Ø§ ÙŠØªÙ… Ø¨Ù†Ø§Ø¡ Browser Screenshot Testing.

Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… ÙŠÙ‚ÙˆÙ… Ø¨Ø§Ù„Ù…Ø±Ø§Ø¬Ø¹Ø© Ø§Ù„Ø¨ØµØ±ÙŠØ©.

Codex Ù…Ø³Ø¤ÙˆÙ„ Ø¹Ù†:

Automated Tests

Ù„Ù„Ø³Ù„ÙˆÙƒ Ø§Ù„Ù…Ù‡Ù….

---

# 36. Implementation Phases

## Phase 0 â€” Project Audit & Repository Hygiene

Goal:

ÙÙ‡Ù… Laravel Core Ù‚Ø¨Ù„ ØªÙ†ÙÙŠØ° Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ø§Ù„Ø¬Ø¯ÙŠØ¯.

Tasks:

- [x] Read AGENTS.md completely
- [x] Verify Laravel version
- [x] Verify PHP version
- [x] Verify Filament version
- [x] Verify database
- [x] Verify installed packages
- [x] Verify localization conventions
- [x] Verify Filament Resource conventions
- [x] Verify frontend conventions
- [x] Verify UTF-8
- [x] Verify BOM
- [x] Verify line endings
- [x] Review .editorconfig
- [x] Review .gitattributes
- [x] Verify text hygiene tooling if present
- [x] Document findings
- [x] Confirm Phase 1 architecture

Verified Core Findings:

- AGENTS.md matches the current Core stack in the audited areas and did not require correction during Phase 0.
- PHP CLI version verified: 8.2.12.
- Laravel version verified: 12.66.0.
- Filament version verified from composer.lock: 4.12.6.
- Database configuration supports multiple drivers, with MySQL defined and the default connection remaining environment-driven through `DB_CONNECTION`.
- The questionnaire plan file is valid UTF-8 without BOM and uses LF line endings; Arabic text rendering issues observed in terminal output are display-side mojibake, not a repository encoding defect.
- Audited directories `app/`, `resources/`, `lang/`, `database/`, and `docs/` currently contain no UTF-8 BOM files and no CRLF files.
- `.editorconfig` already enforces `charset = utf-8`, `end_of_line = lf`, and `insert_final_newline = true`.
- `.gitattributes` already enforces `* text=auto eol=lf`.
- No dedicated repository-wide text hygiene script or test exists at the moment; Phase 0 did not introduce one because no encoding or line-ending defect was found.
- Existing Filament resources confirm the current Core conventions: separate Resource / Schemas / Tables / Pages classes, translation-backed labels, session-persisted table state, modal filters with four columns when filters exist, `->iconButton()` record actions, and full-width admin pages.
- Localization conventions are aligned with AGENTS.md: admin content locales come from `config/core.php`, frontend localized routes come from `mcamara/laravel-localization`, and translatable content uses Spatie Translatable.
- Frontend baseline currently uses Blade + Vite + Tailwind CSS v4 in the starter project; the questionnaire-specific Bootstrap 5 / RTL / Font Awesome 6 / Tajawal stack remains a future approved direction for questionnaire frontend work rather than an existing Core-wide frontend implementation.

Status:
Completed

---

## Phase 1 â€” Core Data Model

Goal:

Ø¥Ù†Ø´Ø§Ø¡ Ù‚Ø§Ø¹Ø¯Ø© Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª Ø§Ù„Ø£Ø³Ø§Ø³ÙŠØ© ÙÙ‚Ø·.

Expected scope:

- Sections Migration
- Questions Migration
- Question Options Migration
- Answers Migration
- Enums
- Models
- Relationships
- Casts
- Constraints
- Indexes
- Tests

Tasks:

- [x] Finalize table structure
- [x] Create migrations
- [x] Create QuestionType enum
- [x] Create AnswerReviewStatus enum if approved
- [x] Create models
- [x] Add relationships
- [x] Add casts
- [x] Add constraints
- [x] Add indexes
- [x] Add database tests
- [x] Run migrations
- [x] Run tests
- [x] Update plan

Status:
Completed

---

## Phase 2 â€” Filament Administration

Goal:

Ø¥Ø¯Ø§Ø±Ø© Ù…Ø­ØªÙˆÙ‰ Ø§Ù„Ø§Ø³ØªØ¨ÙŠØ§Ù† ÙˆØ§Ù„Ø¥Ø¬Ø§Ø¨Ø§Øª Ù…Ù† Ù„ÙˆØ­Ø© Ø§Ù„ØªØ­ÙƒÙ….

Tasks:

- [x] Section Resource
- [x] Question Resource
- [x] Dynamic Question Type form
- [x] Options Repeater
- [x] Conditional Question settings
- [x] Report Category
- [x] Target Entity
- [x] Answers administration
- [x] Needs Review indicators
- [x] Review action
- [x] Filament permissions according to Core
- [x] Automated tests
- [x] Update plan

Status:
Completed

---

## Phase 3 â€” Questionnaire Frontend

Goal:

ÙˆØ§Ø¬Ù‡Ø© Ø¨Ø³ÙŠØ·Ø© Ù„Ù„Ù…Ø®ØªØµ Ù…Ø´Ø§Ø¨Ù‡Ø© ÙÙŠ Ø¨Ø³Ø§Ø·ØªÙ‡Ø§ Ù„Ù„Ù†Ù…ÙˆØ°Ø¬ Ø§Ù„Ù…Ø¹ØªÙ…Ø¯.

Tasks:

- [x] Questionnaire route
- [x] Bootstrap layout
- [x] RTL
- [x] Tajawal
- [x] Font Awesome
- [x] Sidebar from Sections
- [x] Section description rendering
- [x] Question renderer
- [x] Answer save/update
- [x] Notes save/update
- [x] Needs Review auto flag
- [x] Simple conditional questions
- [x] Restore existing answers
- [x] Responsive structure
- [x] Automated behavior tests
- [x] Update plan

Status:
Completed

Phase 3A note:

This frontend delivery was completed as the Farm Data Frontend Pilot using the existing seeded Pilot records.

Phase 3A-R1 note:

This UX revision updates the respondent flow to Arabic-only navigation, config-driven zero-group filtering, Main Section overview pages, and a one-question stepper while preserving the same one-answer-per-question persistence model.

---

## Phase 4 â€” Technical Specification Generator

Goal:

ØªØ­ÙˆÙŠÙ„ Ø§Ù„Ø¥Ø¬Ø§Ø¨Ø§Øª Ø¥Ù„Ù‰ Technical Specification ÙˆØ§Ø¶Ø­.

Tasks:

- [ ] Define structured report format
- [ ] Group by Target Entity
- [ ] Fields section
- [ ] Enum recommendations
- [ ] Managed Lookup recommendations
- [ ] Relationships
- [ ] Workflow requirements
- [ ] Business rules
- [ ] Alerts
- [ ] Reports
- [ ] Needs Review
- [ ] Technical report page
- [ ] Print stylesheet
- [ ] Automated report tests
- [ ] Update plan

Status:
Pending

---

## Phase 5 â€” Final Review & Polish

Goal:

Ù…Ø±Ø§Ø¬Ø¹Ø© Ø§Ù„ØªØ¬Ø±Ø¨Ø© Ø§Ù„ÙƒØ§Ù…Ù„Ø©.

Tasks:

- [ ] Review Admin UX
- [ ] Review questionnaire UX
- [ ] Review report output
- [ ] Fix confirmed usability problems
- [ ] Validate answer persistence
- [ ] Validate Needs Review process
- [ ] Validate Print output
- [ ] Final automated test run
- [ ] Update documentation

Status:
Pending

---

# 37. Architectural Decisions

## AD-001

One questionnaire only.

Status:
Approved

## AD-002

No Questionnaire Sessions.

Status:
Approved

## AD-003

One Answer per Question.

Status:
Approved

## AD-004

Question Options are managed in Question Resource through Repeater.

Status:
Approved

## AD-005

Every Question supports Optional Notes.

Status:
Approved

## AD-006

Notes automatically trigger Needs Review.

Status:
Approved

## AD-007

Technical decisions are derived from business answers.

Status:
Approved

## AD-008

Specialist is not asked Laravel/database terminology.

Status:
Approved

## AD-009

Frontend uses Bootstrap 5 + Bootstrap RTL + Font Awesome 6 + Tajawal.

Status:
Approved

## AD-010

Technical report initially uses browser Print instead of PDF library.

Status:
Approved

## AD-011

Questionnaire Sections use a two-level self-referencing hierarchy:

Main Section
â†’ Subsection

Status:
Approved

## AD-012

Questionnaire Answers may be created from Filament only for Questions that do not already have an Answer.

Status:
Approved

## AD-013

Changing a Question from an option-based type to a non-option type is blocked while Options still exist, to avoid silent data loss.

Status:
Approved

---

# 38. Deferred Decisions

The following remain deferred until the relevant Phase:

- Exact report metadata fields
- Exact Technical Specification transformation rules
- Whether number questions require min/max/step
- Whether Question metadata needs JSON
- Whether Answer edit history is required
- Whether report should later support direct PDF generation

---

# 39. Development Rule

Any feature that does not directly support:

Question
â†’ Answer
â†’ Optional Note
â†’ Needs Review
â†’ Technical Specification

must not be added without explicit approval.

---

# 40. Execution Log

Append entries only.

Do not delete previous execution history.

Format:

## YYYY-MM-DD â€” Phase X

### Planned

### Implemented

### Files Created

### Files Modified

### Tests

### Findings

### Decisions

### Issues

### Next Step

## 2026-08-15 - Phase 0

### Planned

- Audit the current Laravel Core before any questionnaire implementation.
- Verify AGENTS.md against the actual repository state.
- Confirm repository text hygiene and file editing safety conditions.
- Stop after documenting Phase 0 findings and Phase 1 recommendations.

### Implemented

- Read AGENTS.md and the questionnaire implementation plan completely.
- Verified PHP, Laravel, Filament, Node, npm, database configuration, and key installed package versions from the repository.
- Inspected current Filament resources, localization helpers, admin panel provider, user model, routes, and frontend package configuration to capture actual project conventions.
- Audited UTF-8 validity, BOM presence, and LF / CRLF status across `app/`, `resources/`, `lang/`, `database/`, and `docs/`.
- Reviewed `.editorconfig` and `.gitattributes`.
- Confirmed Phase 1 should retain the approved simplified questionnaire architecture with no questionnaire session layer.

### Files Created

- None.

### Files Modified

- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- `php -v`
- `php artisan --version`
- Read-only repository inspection of composer metadata, configuration, Filament resources, and localization helpers
- Read-only UTF-8 / BOM / line-ending audit across the target directories

### Findings

- AGENTS.md is materially aligned with the actual Core and required no factual correction in Phase 0.
- Verified versions: PHP 8.2.12, Laravel 12.66.0, Filament 4.12.6, Node 22.14.0, npm 10.9.2.
- MySQL remains part of the intended Core stack and is fully configured, while the active default connection stays environment-driven.
- Existing resource patterns are suitable as the direct reference for future questionnaire Resources.
- The repository is already stable on the target text format standard in the audited directories.
- No existing automated text hygiene tool was found.

### Decisions

- Phase 0 remains documentation and audit only; no questionnaire application code was created.
- The plan continues to use the approved simplified architecture: one questionnaire, no questionnaire sessions, one current answer per question, optional notes triggering review, and a later technical specification output.

### Issues

- `composer show` and `git status` are blocked in this execution environment by Git safe-directory ownership checks, so package verification relied on `composer.lock`, `composer.json`, and installed project code instead.

### Next Step

- Await approval for Phase 1 - Core Data Model.

## 2026-08-15 - Phase 1

### Planned

- Build the minimal questionnaire domain foundation only.
- Add the approved two-level section hierarchy using one self-referencing table.
- Create migrations, enums, models, relationships, casts, constraints, and focused database tests.
- Stop before any Filament, frontend, or report-generation work.

### Implemented

- Created questionnaire migrations for sections, questions, question options, and answers.
- Added `QuestionType`, `QuestionDependencyOperator`, and `AnswerReviewStatus` enums.
- Added questionnaire models with relationships, defaults, casts, and safe domain rules.
- Implemented automatic `notes` â†’ `needs_review` synchronization and `review_status` â†’ `reviewed_at` synchronization in the answer model.
- Added focused feature tests covering hierarchy, deletion safety, relationships, uniqueness constraints, JSON answer storage, notes review behavior, and reviewed-state timestamps.
- Updated the implementation plan to reflect the approved main-section / subsection hierarchy and Phase 1 completion.

### Files Created

- `app/Enums/Questionnaire/QuestionType.php`
- `app/Enums/Questionnaire/QuestionDependencyOperator.php`
- `app/Enums/Questionnaire/AnswerReviewStatus.php`
- `lang/en/enums/questionnaire.php`
- `lang/ar/enums/questionnaire.php`
- `tests/Feature/QuestionnaireCoreDataModelTest.php`

### Files Modified

- `database/migrations/2026_08_15_144817_create_questionnaire_sections_table.php`
- `database/migrations/2026_08_15_144818_create_questionnaire_questions_table.php`
- `database/migrations/2026_08_15_144819_create_questionnaire_question_options_table.php`
- `database/migrations/2026_08_15_144820_create_questionnaire_answers_table.php`
- `app/Models/QuestionnaireSection.php`
- `app/Models/QuestionnaireQuestion.php`
- `app/Models/QuestionnaireQuestionOption.php`
- `app/Models/QuestionnaireAnswer.php`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- Questionnaire core data model feature tests
- Migration execution against SQLite in-memory configuration

### Findings

- The two-level section hierarchy fits cleanly in a single self-referencing table without needing a tree package.
- Safe deletion is handled by FK restrictions for parent sections and section-owned questions.
- A single JSON answer value column can support scalar and array answers while keeping the model API simple.

### Decisions

- `report_category` and `target_entity` remain nullable strings in Phase 1.
- `metadata` remains deferred and was not added.
- Dependency parent deletion clears only `depends_on_question_id`; dependent questions remain intact.

### Issues

- None beyond the previously known terminal mojibake display issue for Arabic text in this environment.

### Next Step

- Await approval for Phase 2 - Filament Administration.

## 2026-08-15 - Phase 2

### Planned

- Verify Phase 1 questionnaire migrations against the actual MySQL development database.
- Build Filament Administration for sections, questions, and answers only.
- Keep question options inside the question form through a repeater.
- Add simple review workflow, translations, and focused tests without starting frontend or report generation.

### Implemented

- Verified the four questionnaire Phase 1 migrations on the local MySQL development database and ran them successfully.
- Added Filament resources, pages, schemas, and tables for questionnaire sections, questions, and answers.
- Added section hierarchy admin controls, main-section to subsection selection flow, dynamic question-type behavior, dependency settings, and answer review actions.
- Added translation files in Arabic and English for all three questionnaire resources.
- Added questionnaire policies following the existing Shield policy naming convention.
- Added focused tests for Filament-admin-supporting behavior and readable answer formatting.
- Updated the implementation plan to mark Phase 2 complete and record the implementation details discovered during execution.

### Files Created

- `app/Filament/Resources/QuestionnaireSections/QuestionnaireSectionResource.php`
- `app/Filament/Resources/QuestionnaireSections/Schemas/QuestionnaireSectionForm.php`
- `app/Filament/Resources/QuestionnaireSections/Tables/QuestionnaireSectionsTable.php`
- `app/Filament/Resources/QuestionnaireSections/Pages/ListQuestionnaireSections.php`
- `app/Filament/Resources/QuestionnaireSections/Pages/CreateQuestionnaireSection.php`
- `app/Filament/Resources/QuestionnaireSections/Pages/EditQuestionnaireSection.php`
- `app/Filament/Resources/QuestionnaireQuestions/QuestionnaireQuestionResource.php`
- `app/Filament/Resources/QuestionnaireQuestions/Schemas/QuestionnaireQuestionForm.php`
- `app/Filament/Resources/QuestionnaireQuestions/Tables/QuestionnaireQuestionsTable.php`
- `app/Filament/Resources/QuestionnaireQuestions/Pages/ListQuestionnaireQuestions.php`
- `app/Filament/Resources/QuestionnaireQuestions/Pages/CreateQuestionnaireQuestion.php`
- `app/Filament/Resources/QuestionnaireQuestions/Pages/EditQuestionnaireQuestion.php`
- `app/Filament/Resources/QuestionnaireAnswers/QuestionnaireAnswerResource.php`
- `app/Filament/Resources/QuestionnaireAnswers/Schemas/QuestionnaireAnswerForm.php`
- `app/Filament/Resources/QuestionnaireAnswers/Tables/QuestionnaireAnswersTable.php`
- `app/Filament/Resources/QuestionnaireAnswers/Pages/ListQuestionnaireAnswers.php`
- `app/Filament/Resources/QuestionnaireAnswers/Pages/CreateQuestionnaireAnswer.php`
- `app/Filament/Resources/QuestionnaireAnswers/Pages/EditQuestionnaireAnswer.php`
- `app/Policies/QuestionnaireSectionPolicy.php`
- `app/Policies/QuestionnaireQuestionPolicy.php`
- `app/Policies/QuestionnaireAnswerPolicy.php`
- `lang/en/filament/resources/questionnaire_sections.php`
- `lang/ar/filament/resources/questionnaire_sections.php`
- `lang/en/filament/resources/questionnaire_questions.php`
- `lang/ar/filament/resources/questionnaire_questions.php`
- `lang/en/filament/resources/questionnaire_answers.php`
- `lang/ar/filament/resources/questionnaire_answers.php`
- `tests/Feature/QuestionnaireFilamentAdminSupportTest.php`

### Files Modified

- `app/Models/QuestionnaireQuestion.php`
- `app/Models/QuestionnaireAnswer.php`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- MySQL migration verification through `php artisan migrate`
- Questionnaire core data model tests
- Questionnaire Filament admin support tests
- Resource route registration verification

### Findings

- The Phase 1 questionnaire schema works correctly on the local MySQL development database.
- The existing Filament Core split structure adapts cleanly to questionnaire sections, questions, and answers.
- Readable answer formatting is best centralized in the questionnaire model layer instead of duplicating mapping logic across tables and forms.

### Decisions

- Answers can be created from Filament only for unanswered questions.
- Type changes from option-based questions to non-option types are blocked while options still exist.
- Section delete UX now stops before the database exception and shows a readable admin message when child subsections or questions still exist.

### Issues

- Shield permission generation command was reviewed, but existing manually created questionnaire policies already follow the project Shield convention, so no extra generator write step was required during this phase.

### Next Step

- Await HUMAN frontend review and real answers before Phase 3B - Technical Report Pilot.

## 2026-08-15 - Phase 3A-R1

### Planned

- Revise respondent navigation and presentation without changing questionnaire data or persistence architecture.
- Remove respondent language switching and simplify public respondent routes to Arabic-only paths.
- Replace the single-page Subsection experience with a one-question stepper flow while preserving one Answer per Question.

### Implemented

- Removed respondent language switching from the questionnaire frontend and fixed the respondent shell to Arabic RTL.
- Added `config/questionnaire.php` with `show_zero_groups` defaulting to `false`.
- Updated respondent routes to use Arabic-only public paths for Home, Main Section, Subsection, Question steps, completion, and answer persistence.
- Added Main Section overview pages that list only visible Subsections and do not render Questions directly.
- Replaced the old all-questions Subsection page with a one-question stepper flow, including Previous navigation, Save & Continue behavior, and a completion state.
- Kept lightweight auto-save for the current Question without automatic navigation.
- Updated frontend tests to cover Arabic-only behavior, zero-group filtering, Main Section/Subsection navigation, stepper flow, required validation, conditional sequencing, and completion behavior.

### Files Created

- `config/questionnaire.php`
- `app/Http/Controllers/Questionnaire/QuestionnairePageController.php`
- `app/Http/Requests/Questionnaire/SubmitQuestionnaireAnswerStepRequest.php`
- `resources/views/questionnaire/main-section.blade.php`
- `resources/views/questionnaire/question.blade.php`
- `resources/views/questionnaire/completion.blade.php`
- `resources/views/components/questionnaire/question-form.blade.php`

### Files Modified

- `routes/web.php`
- `app/Http/Controllers/Questionnaire/QuestionnaireAnswerController.php`
- `app/Services/Questionnaire/QuestionnaireFrontendService.php`
- `resources/views/layouts/questionnaire.blade.php`
- `resources/views/questionnaire/home.blade.php`
- `resources/views/components/questionnaire/sidebar.blade.php`
- `public/js/questionnaire.js`
- `public/css/questionnaire.css`
- `tests/Feature/QuestionnaireFrontendPilotTest.php`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- Questionnaire core data model tests
- Questionnaire Filament admin support tests
- Questionnaire frontend Pilot tests updated for 3A-R1

### Findings

- The existing one-answer-per-question model supports step-based navigation cleanly without adding sessions or step tables.
- Zero-group filtering is safest when derived from real Question counts at runtime and controlled only by config.
- Conditional Questions fit naturally into the stepper as long as the applicable sequence is recalculated after each saved step.

### Decisions

- Respondent frontend is Arabic-only in this Pilot revision.
- Zero-question Main Sections and Subsections are hidden from respondent navigation by default through config.
- Save & Continue is now the primary respondent flow, while auto-save remains secondary protection for the current Question.

### Issues

- The implementation plan keeps the original Phase 3 heading and records this change explicitly as Phase 3A-R1 rather than rewriting earlier approved history.

### Next Step

- Await the next approved frontend or report instruction.

## 2026-08-15 - Phase 3A

### Planned

- Build the first real frontend Pilot for the Farm Data subsection only.
- Reuse the existing database sections, questions, options, and answers as the source of truth.
- Keep the UX compact, RTL, and database-persistent without introducing sessions or report generation.

### Implemented

- Added localized frontend routes for Home, Study, and answer save/update.
- Added a dedicated questionnaire frontend service for tree loading, conditional applicability, answer payload preparation, and derived progress.
- Added a compact Bootstrap RTL + Tajawal + Font Awesome frontend shell with a Main Section home page, hierarchical Sidebar, and a single-page Subsection study flow.
- Added lightweight JavaScript auto-save behavior and optional Notes UX while keeping backend Answer model rules authoritative.
- Added support for all approved QuestionTypes, conditional rendering, existing-answer restore behavior, and derived Main/Subsection progress.
- Added focused automated tests covering frontend rendering, persistence, conditional visibility, and progress behavior.
- Updated the implementation plan to record Phase 3A completion and the next gated step.

### Files Created

- `app/Services/Questionnaire/QuestionnaireFrontendService.php`
- `app/Http/Requests/Questionnaire/SaveQuestionnaireAnswerRequest.php`
- `app/Http/Controllers/Questionnaire/QuestionnaireController.php`
- `app/Http/Controllers/Questionnaire/QuestionnaireAnswerController.php`
- `resources/views/layouts/questionnaire.blade.php`
- `resources/views/components/questionnaire/sidebar.blade.php`
- `resources/views/components/questionnaire/question-block.blade.php`
- `resources/views/questionnaire/home.blade.php`
- `resources/views/questionnaire/study.blade.php`
- `public/css/questionnaire.css`
- `public/js/questionnaire.js`
- `tests/Feature/QuestionnaireFrontendPilotTest.php`

### Files Modified

- `routes/web.php`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- Questionnaire core data model tests
- Questionnaire Filament admin support tests
- Questionnaire frontend Pilot tests
- Frontend route registration verification

### Findings

- The current one-answer-per-question architecture supports persistent frontend behavior cleanly without sessions.
- Rendering all Pilot questions for one Subsection on a single page works well with a compact UI and lightweight auto-save.
- Progress is most reliable when derived from currently applicable questions only, while hidden conditional answers remain preserved.

### Decisions

- Frontend respondent routes now use explicit localized `{locale}` prefixes.
- Hidden conditional answers are preserved in the database during this Pilot instead of being auto-deleted.
- Zero-question Subsections render a safe placeholder state instead of failing.

### Issues

- Bootstrap RTL, Tajawal, and Font Awesome are loaded through CDN links in this Pilot frontend shell.

### Next Step

- Await HUMAN frontend review and real answers before Phase 3B - Technical Report Pilot.

## 2026-08-15 - Phase 3A-R2

### Planned

- Apply a very small Home-page-only revision without changing the question flow, persistence, or report generation.
- Separate zero Main Section visibility from zero Subsection visibility in config.
- Add a Home page PDF button as a visual placeholder only.

### Implemented

- Kept the frontend revision limited to Home visibility behavior and Home header actions.
- Split zero-group visibility into separate Main Section and Subsection config flags.
- Added a Home page PDF button placeholder with no backend PDF generation behavior.

### Files Created

- None.

### Files Modified

- `config/questionnaire.php`
- `app/Services/Questionnaire/QuestionnaireFrontendService.php`
- `resources/views/questionnaire/home.blade.php`
- `public/css/questionnaire.css`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- No automated test run was required for this small Home/config revision.

### Findings

- Home visibility is clearer when empty Main Sections and empty Subsections are controlled independently.
- The current Pilot setting keeps both visibility flags enabled: `show_zero_main_sections = true` and `show_zero_subsections = true`.
- The PDF button is intentionally present as a UI placeholder only; PDF generation is still not implemented in this phase.

### Decisions

- No questionnaire data, question rendering flow, save behavior, conditional behavior, or report routes were changed.
- Visual verification remains a human review responsibility for this small UI adjustment.

### Issues

- None.

### Next Step

- Await the next approved frontend or report instruction.

## 2026-08-15 - Phase 3A-R3

### Planned

- Correct Home-page Main Section aggregation without changing any questionnaire flow or persistence behavior.
- Reposition the Home-page PDF placeholder button inside a cleaner header action area only.

### Implemented

- Fixed Main Section totals so they are aggregated from all child Subsections instead of being distorted by visible-child filtering.
- Changed Main Section progress totals to use all child-subsection question counts while keeping answered counts based on answered applicable questions.
- Moved the PDF placeholder button into a dedicated Home header action area with compact RTL-friendly alignment.

### Files Created

- None.

### Files Modified

- `app/Services/Questionnaire/QuestionnaireFrontendService.php`
- `resources/views/questionnaire/home.blade.php`
- `public/css/questionnaire.css`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- No automated test run was performed for this Home-page-only correction.

### Findings

- Main Section counting was previously coupled to filtered visible Subsections and to applicable-question totals, which could understate the true child-question total on Home cards.

### Decisions

- `show_zero_main_sections` and `show_zero_subsections` remain visibility-only settings and do not alter Main Section question aggregation.
- The Home PDF button remains a placeholder with `href="#"` and no PDF behavior.

### Issues

- None.

### Next Step

- Await manual Home-page review.

---

# 41. Current Phase

Current Phase:

Phase 3A-R2 - Home Configuration Revision

Status:

Completed

Next Action:

Await the next approved frontend or report instruction.

