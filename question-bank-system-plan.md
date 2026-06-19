# e-Question Bank (প্রশ্ন ব্যাংক) System — SaaS Build Plan

## 1. What this system is
A SaaS platform where teachers/institutions log in, **select Class → Subject → Chapter/Topic**, and **create, store, tag, and organize exam questions**. Over time this builds a searchable, reusable "question bank" that can be used to generate question papers, share with other teachers, or sell as content.

This is NOT a live exam-taking system (no timers, no proctoring, no student exam portal) — it's a **content/question management SaaS**, closer to a "question repository + paper generator" than to ViserExam/Quick Quiz style exam platforms. Exam-delivery can be added later as a module if needed.

---

## 2. User Roles

| Role | What they can do |
|---|---|
| **Super Admin** (you) | Manage all tenants/institutions, plans, billing, global settings, content moderation |
| **Institution Admin** (school/coaching owner) | Manage their own teachers, classes, subjects, branding |
| **Teacher / Content Creator** | Select class & subject, create/edit/delete questions, organize into chapters |
| **Moderator/Reviewer** (optional) | Approve/reject questions before they go live in shared bank |
| **Viewer/Student** (optional, future) | Browse public questions, practice mode |

---

## 3. Core Workflow (the heart of the product)

```
Login → Select Class (e.g. Class 9, HSC, JSC)
      → Select Subject (e.g. Physics, Bangla, English)
      → Select Chapter/Topic (e.g. Chapter 3: Motion)
      → Click "Add Question"
      → Choose Question Type (MCQ / True-False / Fill in Blank / Short / Long / Matching)
      → Enter question text (supports Bangla + English + math symbols + image upload)
      → Enter options/answer + correct answer + explanation
      → Set: Marks, Difficulty (Easy/Medium/Hard), Tags, Board/Year reference
      → Save → Question goes into the Bank
```

Everything downstream (search, filter, export, paper generation) is built on top of this structure.

---

## 4. Feature List

### A. Must-Have (MVP)
- Multi-tenant SaaS architecture (each institution = isolated workspace)
- Class / Subject / Chapter / Topic hierarchy (fully admin-configurable, not hardcoded)
- Question creation with types: **MCQ (single/multi-answer), True/False, Fill in the blank, Short answer, Long/Descriptive, Matching**
- Bangla + English (Unicode) input support, rich text editor (bold/italic/equation/image)
- Image/diagram upload inside questions and options
- Bulk import via Excel/CSV (huge time-saver for teachers migrating old banks)
- Search & filter (by class, subject, chapter, difficulty, tag, type, year/board)
- Edit/duplicate/delete/version history of questions
- Export selected questions to **PDF/Word as a formatted question paper** (with logo/letterhead)
- Role-based access (Admin / Teacher / Moderator)
- User authentication (email/phone OTP, password reset)
- Dashboard with stats (total questions, by subject, by class, recent activity)

### A.1 — PDF Export / Print Feature (MUST HAVE — short list)
- Question create করার পর **PDF বানানোর option**
- PDF **save** করা যাবে (server-এ + download)
- PDF **direct print** করা যাবে (browser print button)
- PDF-এর উপরে (header/letterhead) থাকবে:
  - Institution **Logo**
  - Institution **Name**
  - Institution Address (optional)
  - **Exam Name** (e.g. "Half Yearly Exam 2026")
  - **Class** name
  - **Subject** name
  - Full Marks / Time (optional fields)
- Body-তে **selected questions auto-format** হয়ে আসবে (numbered, marks shown)
- নিচে **Answer Key** আলাদা পেজে (option: include/exclude)
- প্রতিটা institution তাদের own logo/header **settings থেকে customize** করতে পারবে (settings table → already planned)
- Multiple **paper templates** (1-column / 2-column layout) — pick before export
- Re-print/re-download পুরোনো generated paper (saved file history)

### A.2 — Excel Import/Export + Admin Upload + AI Question Create (MUST HAVE)

**Excel Export (template দিয়ে)**
- User চাইলে blank **Excel template download** করতে পারবে (column: Class, Subject, Chapter, Question Type, Question, Option A-D, Correct Answer, Marks, Difficulty)
- Excel-এ বসে নিজের মত **question fill up করে নিতে পারবে** (offline-এও কাজ করা যাবে)

**Excel Import (Admin/Teacher upload)**
- Fill করা Excel **upload** করলে system automatically সব question বানিয়ে বানিয়ে **bank-এ add** করে দেবে
- Upload করার আগে **preview screen** (kotogula question thik ase, kotogula error ase dekhabe)
- Duplicate/invalid row হলে **error report** দেখাবে, বাকিগুলো ঠিকমতো save হবে
- Bulk upload **history log** রাখবে (কে কখন কত question upload করলো)

**Admin Question Upload (manual single/bulk)**
- Admin/Teacher সরাসরি UI থেকে এক এক করে question add করতে পারবে (already covered)
- চাইলে **Word/PDF থেকে copy-paste** করে দ্রুত বসাতে পারবে (rich text editor handles formatting)
- Image/diagram attach করে upload করতে পারবে প্রতিটা question/option-এ

**AI দিয়ে Question Create (AI-Assisted)**
- কোনো **paragraph/text/chapter content paste** করলে AI স্বয়ংক্রিয়ভাবে MCQ/Short/Fill-in-blank question **generate** করে দেবে
- Generated question গুলো user **review/edit করে তারপর bank-এ save** করবে (auto-save না, approval লাগবে)
- AI দিয়ে **difficulty level auto-suggest** করবে
- (Optional future) AI দিয়ে **answer explanation auto-generate**

---

### A.3 — OMR + Negative Marking + Reseller + Cloud Limit + Student Mode (MUST HAVE — eproshnobank-style)

**OMR Sheet Generate + Evaluate**
- Question paper বানানোর সাথে সাথে matching **OMR sheet auto-generate** হবে (printable)
- Exam শেষে OMR sheet **scan/upload** করলে system **auto-evaluate** করবে (image processing/bubble detection)
- Result auto-generate: কে কত পেলো, কোনটা ভুল হলো — সব দেখানো

**Negative Marking**
- প্রতিটা question paper/exam তৈরির সময় **negative marking option** সেট করা যাবে (-০.২৫ / -০.৫ / -১ / None)
- Result calculation negative marking সহ automatically হিসাব হবে

**Secure Shareable Exam Link (White-label)**
- Question paper/exam থেকে এক ক্লিকে **secure link generate** হবে, শেয়ার করার জন্য
- লিংকে কোনো platform branding থাকবে না — পুরোটাই institution-এর own নামে (white-label)

**Cloud Limit by Plan**
- প্রতি plan-এ **কতগুলো question-set cloud-এ ব্যাক-আপ থাকবে** তার লিমিট থাকবে (e.g. Free=50, Pro=1000)
- প্রতি question-set-এ কতগুলো প্রশ্ন থাকবে তার limit (e.g. 10–150)
- Question তৈরিতে কোনো hard limit নেই, কিন্তু cloud storage/set সংখ্যা plan অনুযায়ী

**Reseller / Business Account**
- একটা institution চাইলে **Business Account** নিয়ে নিজে question pack/paper বানিয়ে অন্য user/institution-এর কাছে **বিক্রি** করতে পারবে
- Reseller-এর জন্য আলাদা dashboard: কত বিক্রি হলো, income history

**Student Practice Mode**
- Student login করে শুধু **practice mode**-এ approved question গুলো দেখতে/practice করতে পারবে (read-only, exam না)
- ভবিষ্যতে: Model Test, Doubt Solve, Student-side Question Bank — এগুলোর জন্য জায়গা রাখা (placeholder module)

---

### B. Should-Have (v2 — makes it a real SaaS product)
- Multi-tenant billing/subscription (per-institution plans: Free/Pro/Unlimited)
- Question approval workflow (Teacher submits → Moderator approves → goes to shared bank)
- Auto question-paper generator (pick rules: "20 MCQ + 5 short from Chapter 1-3, mixed difficulty" → auto-select & format)
- Duplicate question detection (avoid same question re-entered)
- Tagging system (board, year, exam type, learning outcome)
- Shareable/public question bank marketplace (teachers can publish & even sell packs)
- Print-ready templates (multiple paper layouts, watermark, answer key separate sheet)
- Activity log / audit trail (who created/edited what)
- Notifications (email/SMS when question approved/rejected)

### C. Nice-to-Have (v3 — competitive edge, AI-powered)
- **AI question generator**: paste a paragraph/chapter text → AI suggests MCQs/short questions (like ViserExam's AI feature — strong selling point on CodeCanyon)
- AI-based difficulty auto-tagging
- OCR: scan a printed question paper image → auto-extract questions into the bank
- Plagiarism/duplicate-similarity check using AI
- Multi-language question bank (Bangla/English/Arabic etc.)
- Mobile app (teacher can add questions on the go)
- API access for institutions to integrate with their own LMS/exam system
- Analytics: most-used chapters, question quality ratings, peer review/comments

---

## 5. Full Detailed Database Design (Table-by-Table)

Below is a production-ready table list with column names. Use this directly as your migration plan (Laravel migration files = 1 file per table, in this order).

### 5.1 `institutions` (tenants — each school/coaching = one row)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| name | varchar | institution name |
| slug | varchar unique | subdomain or URL slug |
| logo | varchar | path to logo |
| address, phone, email | varchar | |
| status | enum(active, suspended) | |
| created_at, updated_at | timestamp | |

### 5.2 `users`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK → institutions | null for super admin |
| name, email, phone | varchar | |
| password | varchar | hashed |
| role | enum(super_admin, institution_admin, teacher, moderator, viewer) | or use separate `roles`/`role_user` table if you want flexible permissions |
| avatar | varchar | |
| status | enum(active, inactive) | |
| email_verified_at | timestamp | |
| created_at, updated_at | timestamp | |

### 5.3 `classes`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| name | varchar | e.g. "Class 9", "HSC 1st Year" |
| order | int | for sorting |
| status | enum(active, inactive) | |

### 5.4 `subjects`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| class_id | bigint FK → classes | |
| name | varchar | e.g. "Physics", "Bangla 1st Paper" |
| code | varchar | optional subject code |
| order | int | |

### 5.5 `chapters`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| subject_id | bigint FK → subjects | |
| name | varchar | e.g. "Chapter 3: Motion" |
| order | int | |

### 5.6 `topics` (optional sub-level under chapter)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| chapter_id | bigint FK → chapters | |
| name | varchar | |
| order | int | |

### 5.7 `question_types` (lookup table — keeps types configurable, not hardcoded)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| name | varchar | MCQ, True/False, Fill in Blank, Short Answer, Long Answer, Matching |
| has_options | boolean | true for MCQ/True-False |

### 5.8 `questions` (the core table)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| class_id, subject_id, chapter_id, topic_id | bigint FK | denormalized for fast filtering |
| question_type_id | bigint FK → question_types | |
| question_text | text | supports HTML (rich text/Bangla/math) |
| image | varchar nullable | question image/diagram |
| correct_answer | text | for non-MCQ types, or index for MCQ |
| explanation | text nullable | answer explanation |
| marks | decimal | |
| difficulty | enum(easy, medium, hard) | |
| board | varchar nullable | e.g. Dhaka Board, NCTB |
| year | year nullable | |
| status | enum(draft, pending, approved, rejected) | |
| created_by | bigint FK → users | |
| approved_by | bigint FK → users, nullable | |
| created_at, updated_at | timestamp | |

### 5.9 `question_options` (for MCQ/True-False/Matching — one row per option)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| question_id | bigint FK → questions | |
| option_text | text | |
| image | varchar nullable | |
| is_correct | boolean | |
| order | int | A, B, C, D order |

### 5.10 `tags`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| name | varchar | e.g. "important", "previous-board-question" |

### 5.11 `question_tag` (pivot table — many-to-many)
| Column | Type | Note |
|---|---|---|
| question_id | bigint FK | |
| tag_id | bigint FK | |

### 5.12 `question_papers`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| title | varchar | internal title, e.g. "Mid-Term Physics Exam 2026" |
| exam_name | varchar | shown on printed paper header, e.g. "Half Yearly Exam 2026" |
| class_id, subject_id | bigint FK | shown on printed paper header |
| full_marks | decimal | shown on printed paper header |
| time_duration | varchar nullable | e.g. "3 Hours" |
| negative_marking | decimal nullable | e.g. -0.25, -0.5, -1, null = none |
| share_link_token | varchar nullable | unique token for secure white-label exam link |
| created_by | bigint FK → users | |
| file_path | varchar nullable | generated PDF/Word path |
| created_at | timestamp | |

### 5.13 `question_paper_items` (pivot — which questions in which paper, with order)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| question_paper_id | bigint FK | |
| question_id | bigint FK | |
| order | int | |
| marks_override | decimal nullable | if marks differ for this paper |

### 5.14 `plans` (SaaS subscription plans)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| name | varchar | Free, Pro, Unlimited |
| price | decimal | |
| billing_cycle | enum(monthly, yearly) | |
| max_questions | int nullable | null = unlimited |
| max_teachers | int nullable | |
| max_question_sets | int nullable | e.g. 50 (Free), 1000 (Pro) — cloud backup limit |
| max_questions_per_set | int nullable | e.g. 150 |
| features | json | feature flags |

### 5.15 `subscriptions`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| plan_id | bigint FK | |
| starts_at, ends_at | date | |
| status | enum(active, expired, cancelled) | |

### 5.16 `payments`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| subscription_id | bigint FK | |
| amount | decimal | |
| method | varchar | bKash, Nagad, SSLCommerz, Stripe |
| transaction_id | varchar | |
| status | enum(pending, success, failed) | |
| created_at | timestamp | |

### 5.17 `activity_logs` (audit trail)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK | |
| action | varchar | "created question", "approved question" |
| model_type, model_id | varchar/bigint | polymorphic reference |
| created_at | timestamp | |

### 5.18 `notifications`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK | |
| title, message | varchar/text | |
| is_read | boolean | |
| created_at | timestamp | |

### 5.19 `settings` (per-institution config)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| key | varchar | e.g. "paper_template", "letterhead_logo" |
| value | text | |

### 5.20 `bulk_uploads` (Excel import history log)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| uploaded_by | bigint FK → users | |
| file_path | varchar | original excel file |
| total_rows | int | |
| success_count | int | |
| failed_count | int | |
| error_report | json nullable | row-wise error detail |
| created_at | timestamp | |

### 5.21 `omr_sheets` (auto-generated per question paper)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| question_paper_id | bigint FK → question_papers | |
| total_questions | int | |
| file_path | varchar | generated printable OMR PDF |
| created_at | timestamp | |

### 5.22 `omr_results` (after scan/upload evaluation)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| omr_sheet_id | bigint FK → omr_sheets | |
| student_name | varchar nullable | if known |
| scanned_image | varchar | uploaded OMR scan |
| answers_detected | json | bubble detection result |
| score | decimal | calculated with negative marking |
| evaluated_at | timestamp | |

### 5.23 `reseller_accounts`
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| institution_id | bigint FK | |
| status | enum(pending, approved, suspended) | |
| total_sales | decimal | |
| created_at | timestamp | |

### 5.24 `marketplace_items` (question packs/papers listed for sale)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| reseller_account_id | bigint FK | |
| question_paper_id | bigint FK nullable | or a bundle of question_ids |
| title | varchar | |
| price | decimal | |
| sales_count | int | |
| status | enum(active, inactive) | |

---
**Relationship summary:**
```
institutions 1—N users
institutions 1—N classes 1—N subjects 1—N chapters 1—N topics
topics 1—N questions
questions 1—N question_options
questions N—N tags (via question_tag)
question_papers N—N questions (via question_paper_items)
institutions 1—N subscriptions —1 plans
subscriptions 1—N payments
```

---

## 6. Recommended Tech Stack
Since you mentioned CodeCanyon-style reference products, the common stack for this category is:

- **Backend**: Laravel (PHP) — most CodeCanyon question-bank/exam scripts use this, huge plugin ecosystem, easy multi-tenant packages (e.g. `stancl/tenancy`)
- **Frontend**: Blade + Livewire/Alpine.js (fast, simple) **or** Laravel + React/Vue if you want a more app-like SPA feel
- **Database**: MySQL
- **Rich text editor**: TinyMCE or CKEditor (with math/equation plugin — MathType or MathJax)
- **PDF/Word export**: DomPDF / Snappy(wkhtmltopdf) for PDF, PHPWord for Word
- **Excel import/export**: Laravel Excel (Maatwebsite)
- **Payments (for SaaS billing)**: bKash/Nagad/SSLCommerz (for BD market) + Stripe (international)
- **AI features (if added)**: Anthropic Claude API / OpenAI API for question generation & OCR

*(If you'd rather go Node.js/React or Django, the same module structure applies — stack choice doesn't change the feature plan.)*

---

## 7. Competitor Reference (what's already selling on CodeCanyon)
For positioning/feature-parity check:
- **ViserExam** – AI-powered exam SaaS with customizable question banks, AI question generation, category-based organization
- **Quiz App & Online Exam System (CBT)** – App + Web + Question Bank combo
- **Quick Quiz (Laravel)** – Quiz/exam system with question bank module
- **iTest** – Quiz & online examination system

Your differentiator: focus purely on **question bank creation & management** (less common as standalone — most bundle it inside a full exam system), Bangla-first UX, and an AI question generator tuned for the local curriculum (NCTB, board-wise).

---

## 8. Suggested Development Phases

| Phase | Scope | Rough effort |
|---|---|---|
| Phase 1 | Auth, multi-tenant setup, Class/Subject/Chapter CRUD, question CRUD (all types), basic dashboard | 3–4 weeks |
| Phase 2 | Search/filter, bulk import/export, PDF/Word paper generator, role-based approval flow | 2–3 weeks |
| Phase 3 | Subscription/billing, public question bank marketplace, notifications | 2–3 weeks |
| Phase 4 | AI question generation, OCR import, mobile app, analytics | 4+ weeks |

---

## 9. Monetization (SaaS angle)
- **Free tier**: limited questions (e.g. 100), 1 class
- **Pro tier (monthly/yearly per institution)**: unlimited questions, PDF export, branding
- **Enterprise**: multiple branches, API access, priority support
- Optional: sell pre-made question packs (by subject/board) as add-on revenue

---

### Next steps
Tell me which phase you want to start with, and I can help you:
1. Design the database schema in detail, or
2. Build a clickable UI mockup/wireframe of the question-creation screen, or
3. Write out the Laravel project folder structure to start coding.
