🧠 INSURANCE APP – FEATURE MIGRATION SCHEMA (ONLY)
1️⃣ Insurance Categories (Life / Health / General)
insurance_categories
id BIGINT PK
slug VARCHAR(50) UNIQUE          -- life, health, general
order_no INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP
updated_at TIMESTAMP

insurance_category_translations
id BIGINT PK
insurance_category_id BIGINT FK
language_code VARCHAR(10)
name VARCHAR(255)
description TEXT NULL
UNIQUE (insurance_category_id, language_code)

2️⃣ Exam Configuration (IC-38, IC-39 future)
exams
id BIGINT PK
code VARCHAR(20) UNIQUE           -- IC-38
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

exam_translations
id BIGINT PK
exam_id BIGINT FK
language_code VARCHAR(10)
name VARCHAR(255)
description TEXT
UNIQUE (exam_id, language_code)

3️⃣ Chapters (Insurance Syllabus)
chapters
id BIGINT PK
exam_id BIGINT FK
insurance_category_id BIGINT FK
order_no INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

chapter_translations
id BIGINT PK
chapter_id BIGINT FK
language_code VARCHAR(10)
title VARCHAR(255)
description TEXT NULL
UNIQUE (chapter_id, language_code)

4️⃣ Concepts (Detailed Theory)
concepts
id BIGINT PK
chapter_id BIGINT FK
order_no INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

concept_translations
id BIGINT PK
concept_id BIGINT FK
language_code VARCHAR(10)
title VARCHAR(255)
content_html LONGTEXT
UNIQUE (concept_id, language_code)

5️⃣ One-Liners (Exam Quick Points)
one_liners
id BIGINT PK
chapter_id BIGINT FK
order_no INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

one_liner_translations
id BIGINT PK
one_liner_id BIGINT FK
language_code VARCHAR(10)
content TEXT
UNIQUE (one_liner_id, language_code)

6️⃣ Short & Simple (Simplified Explanation)
short_simples
id BIGINT PK
chapter_id BIGINT FK
order_no INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

short_simple_translations
id BIGINT PK
short_simple_id BIGINT FK
language_code VARCHAR(10)
title VARCHAR(255)
content TEXT
UNIQUE (short_simple_id, language_code)

7️⃣ Terminology (A–Z Insurance Terms)
terminologies
id BIGINT PK
exam_id BIGINT FK
category VARCHAR(50) NULL
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

terminology_translations
id BIGINT PK
terminology_id BIGINT FK
language_code VARCHAR(10)
term VARCHAR(255)
definition TEXT
UNIQUE (terminology_id, language_code)

8️⃣ Study Materials (E-Notes / Posters)
materials
id BIGINT PK
exam_id BIGINT FK
type ENUM('pdf','poster','note')
file_size INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

material_translations
id BIGINT PK
material_id BIGINT FK
language_code VARCHAR(10)
title VARCHAR(255)
file_path VARCHAR(255)
UNIQUE (material_id, language_code)

9️⃣ Test Engine (Mock / Practice / Live)
tests
id BIGINT PK
exam_id BIGINT FK
chapter_id BIGINT NULL
type ENUM('mock','practice','live','chapter')
total_questions INT
duration_minutes INT
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

test_translations
id BIGINT PK
test_id BIGINT FK
language_code VARCHAR(10)
title VARCHAR(255)
description TEXT NULL
UNIQUE (test_id, language_code)

🔟 Questions (Multilingual)
questions
id BIGINT PK
difficulty ENUM('easy','medium','hard')
correct_option CHAR(1)
is_active BOOLEAN DEFAULT 1
created_at TIMESTAMP

question_translations
id BIGINT PK
question_id BIGINT FK
language_code VARCHAR(10)
question_text TEXT
option_a TEXT
option_b TEXT
option_c TEXT
option_d TEXT
UNIQUE (question_id, language_code)

test_questions
test_id BIGINT FK
question_id BIGINT FK
PRIMARY KEY (test_id, question_id)

1️⃣1️⃣ Test Attempts
test_attempts
id BIGINT PK
user_id BIGINT FK
test_id BIGINT FK
score INT
started_at TIMESTAMP
submitted_at TIMESTAMP

1️⃣2️⃣ Performance Summary
performance_stats
user_id BIGINT PK
exam_id BIGINT FK
total_tests INT DEFAULT 0
avg_score DECIMAL(5,2)
accuracy DECIMAL(5,2)
last_test_at TIMESTAMP

1️⃣3️⃣ User Tracking (Bookmarks / Pins)
user_saved_items
id BIGINT PK
user_id BIGINT FK
entity_type ENUM(
 'chapter',
 'concept',
 'one_liner',
 'short_simple',
 'terminology',
 'material',
 'question'
)
entity_id BIGINT
action ENUM('bookmark','pin')
created_at TIMESTAMP

1️⃣4️⃣ Ads Tracking (Mobile-Only)
ad_events
id BIGINT PK
user_id BIGINT FK
ad_type ENUM('banner','interstitial','rewarded')
event ENUM('shown','clicked','completed')
platform ENUM('android','ios')
created_at TIMESTAMP
