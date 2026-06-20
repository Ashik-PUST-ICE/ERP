# e-Question Bank — Core System Spec

## Introduction
এটি একটি SaaS প্রশ্নব্যাংক সিস্টেম। **Super Admin** পুরো Master Question Bank (সব Class/Subject/Chapter-এর প্রশ্ন) তৈরি ও আপলোড করে রাখে। **Institution/Teacher (SaaS গ্রাহক)** নিজে প্রশ্ন লেখে না — তারা Master Bank থেকে Class → Subject → Chapter সিলেক্ট করে দরকারি প্রশ্ন বেছে নেয়, Draft Sheet বানায়, এবং সবশেষে Institution Logo/Exam Name সহ PDF বানিয়ে Save/Print করে। Teacher নতুন প্রশ্নের জন্য Suggestion দিতে পারে, যা Super Admin approve করলে Master Bank-এ যুক্ত হয়। Bulk question add-এর জন্য Excel import/export এবং AI-assisted question generation আছে। Exam-এর জন্য OMR sheet generate, scan-evaluate ও negative marking সাপোর্ট করে।

## Roles
- **Super Admin** — Master question bank manage, suggestion approve, plans/billing manage
- **Institution Admin** — তাদের teacher manage, branding, subscription
- **Teacher** — question select, draft sheet, PDF export, suggestion submit
- **Student** (future) — practice mode (read-only)

## Core Workflow
```
[SUPER ADMIN] Add/Upload Question (manual or Excel) → status=2 (published) → Master Bank
[TEACHER] Select Class→Subject→Chapter → Browse Master Bank → Tick Question+Answer
       → Save as Draft Sheet → Finalize → Generate PDF (with Logo/Exam Name/Class)
       → Save / Print / Download
       → (optional) Suggest New Question → Super Admin reviews → approved → joins Master Bank
```

## Core Features (MUST HAVE only)
1. Class / Subject / Chapter hierarchy (sadmin-configurable)
2. Question types: MCQ, True/False, Fill in blank, Short, Long, Matching
3. Master Question Bank — Super Admin only can publish
4. Teacher: browse/filter/select questions by class, subject, chapter, difficulty, tag
5. Draft Sheet (save selection in progress, resume later, multiple drafts)
6. Question Suggestion by teacher → Super Admin approve/reject
7. Excel template download + bulk Excel upload (with preview + error report)
8. AI-assisted question generation (paste text → auto MCQ/short questions), goes through review before publish
9. PDF generate with header: Logo, Institution Name, Exam Name, Class, Subject, Full Marks, Time
10. Save / Print / Download PDF, with separate Answer Key page
11. OMR sheet auto-generate per paper; scan/upload → auto-evaluate
12. Negative marking option per paper (-0.25 / -0.5 / -1 / none)
13. Secure shareable white-label exam link (no platform branding)
14. Subscription plans with cloud question-set limits
15. Question filter by **Board** (Dhaka/Rajshahi/Chittagong/Khulna/Sylhet/Comilla/Dinajpur/Madrasah)
16. PDF layout customize: column count (1/2-column), font size, font style
17. Super Admin question source — **not manual-only**:
    - Manual single add
    - Excel bulk upload
    - AI text-paste → auto question generate
    - **Textbook PDF upload** → no ready questions inside, AI **generates new** questions from the content (OCR + AI), Super Admin reviews & publishes
    - **Question Paper PDF upload** → PDF already has ready questions/answers (e.g. old exam papers, existing question banks) → OCR+AI **directly extracts** Q/Options/Answer and auto-fills into the system, Super Admin just reviews & publishes (no generation needed, much faster/more accurate)

## Database Tables (status fields = `tinyint`, mapping given below each table)

### institutions
id, name, slug, logo, address, phone, email, status(tinyint), created_at, updated_at
- status: 1=active, 2=suspended

### users
id, institution_id(FK, nullable), name, email, phone, password, role(tinyint), avatar, status(tinyint), email_verified_at, created_at, updated_at
- role: 1=super_admin, 2=institution_admin, 3=teacher, 4=moderator, 5=student
- status: 1=active, 2=inactive

### classes
id, institution_id(FK, nullable — null if global/master class), name, order, status(tinyint)
- status: 1=active, 2=inactive

### subjects
id, class_id(FK), name, code, order

### chapters
id, subject_id(FK), name, order

### topics
id, chapter_id(FK), name, order

### question_types
id, name, has_options(tinyint: 0=no,1=yes)

### questions  (Master Bank)
id, class_id(FK), subject_id(FK), chapter_id(FK), topic_id(FK), question_type_id(FK),
question_text, image, correct_answer, explanation, marks, difficulty(tinyint),
board, year, status(tinyint), source(tinyint), book_upload_id(FK, nullable), created_by(FK users), created_at, updated_at
- difficulty: 1=easy, 2=medium, 3=hard
- status: 1=draft, 2=published, 3=archived
- source: 1=super_admin, 2=suggestion_approved, 3=textbook_pdf_generated, 4=question_paper_pdf_extracted

### question_options
id, question_id(FK), option_text, image, is_correct(tinyint: 0=no,1=yes), order

### tags
id, name

### question_tag
question_id(FK), tag_id(FK)

### question_suggestions
id, institution_id(FK), suggested_by(FK users), class_id(FK), subject_id(FK), chapter_id(FK),
question_type_id(FK), question_text, suggested_options(json), suggested_answer,
status(tinyint), reviewed_by(FK users, nullable), review_note, created_at, reviewed_at
- status: 1=pending, 2=approved, 3=rejected

### question_papers  (Draft Sheet + Final Paper, same table)
id, institution_id(FK), title, exam_name, class_id(FK), subject_id(FK), full_marks,
time_duration, negative_marking, share_link_token, status(tinyint), column_layout(tinyint),
font_size, font_style, created_by(FK users), file_path, created_at
- status: 1=draft, 2=finalized
- column_layout: 1=single_column, 2=two_column

### question_paper_items
id, question_paper_id(FK), question_id(FK), order, marks_override

### book_uploads (Super Admin uploads a PDF → system auto-generates or extracts questions)
id, class_id(FK), subject_id(FK), chapter_id(FK, nullable), upload_type(tinyint), file_path,
total_pages, extracted_questions_count, status(tinyint), uploaded_by(FK users), created_at, processed_at
- upload_type: 1=textbook_pdf (AI generates new questions), 2=question_paper_pdf (OCR+AI extracts ready questions directly)
- status: 1=uploaded, 2=processing, 3=completed, 4=failed

### bulk_uploads
id, institution_id(FK, nullable — null if super admin upload), uploaded_by(FK users),
file_path, total_rows, success_count, failed_count, error_report(json), created_at

### omr_sheets
id, question_paper_id(FK), total_questions, file_path, created_at

### omr_results
id, omr_sheet_id(FK), student_name, scanned_image, answers_detected(json), score, evaluated_at



### activity_logs
id, user_id(FK), action, model_type, model_id, created_at

### notifications
id, user_id(FK), title, message, is_read(tinyint: 0=no,1=yes), created_at



## Relationships
```
institutions 1—N users
classes 1—N subjects 1—N chapters 1—N topics
topics 1—N questions
questions 1—N question_options
questions N—N tags
question_papers N—N questions (via question_paper_items)
institutions 1—N subscriptions —1 plans
subscriptions 1—N payments
institutions 1—N question_suggestions
question_papers 1—1 omr_sheets 1—N omr_results
```