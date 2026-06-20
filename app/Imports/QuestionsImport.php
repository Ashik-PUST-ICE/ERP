<?php

namespace App\Imports;

use App\Models\AcademicClass;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $imported = 0;
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
        $typeMap = [
            'mcq'           => QB_QTYPE_MCQ,
            'true/false'    => QB_QTYPE_TRUE_FALSE,
            'true false'    => QB_QTYPE_TRUE_FALSE,
            'fill in blank' => QB_QTYPE_FILL_BLANK,
            'fill blank'    => QB_QTYPE_FILL_BLANK,
            'short'         => QB_QTYPE_SHORT,
            'long'          => QB_QTYPE_LONG,
            'matching'      => QB_QTYPE_MATCHING,
        ];

        $difficultyMap = [
            'easy'   => QB_DIFFICULTY_EASY,
            'medium' => QB_DIFFICULTY_MEDIUM,
            'hard'   => QB_DIFFICULTY_HARD,
        ];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because row 1 is heading

            try {
                // Resolve Class
                $class = AcademicClass::where('name', trim($row['class'] ?? ''))->first();
                if (!$class) {
                    $this->errors[] = "Row {$rowNum}: Class '{$row['class']}' not found.";
                    continue;
                }

                // Resolve Subject
                $subject = Subject::where('name', trim($row['subject'] ?? ''))
                    ->where('class_id', $class->id)->first();
                if (!$subject) {
                    $this->errors[] = "Row {$rowNum}: Subject '{$row['subject']}' not found for Class '{$row['class']}'.";
                    continue;
                }

                // Resolve Chapter (optional)
                $chapter = null;
                if (!empty($row['chapter'])) {
                    $chapter = Chapter::where('name', trim($row['chapter']))
                        ->where('subject_id', $subject->id)->first();
                }

                // Resolve Topic (optional)
                $topic = null;
                if (!empty($row['topic']) && $chapter) {
                    $topic = Topic::where('name', trim($row['topic']))
                        ->where('chapter_id', $chapter->id)->first();
                }

                // Resolve question type
                $typeKey = strtolower(trim($row['question_type'] ?? 'mcq'));
                $questionTypeId = $typeMap[$typeKey] ?? QB_QTYPE_MCQ;

                // Resolve difficulty
                $diffKey = strtolower(trim($row['difficulty'] ?? 'medium'));
                $difficulty = $difficultyMap[$diffKey] ?? QB_DIFFICULTY_MEDIUM;

                $questionText = trim($row['question_text'] ?? '');
                if (empty($questionText)) {
                    $this->errors[] = "Row {$rowNum}: Question text is empty.";
                    continue;
                }

                DB::beginTransaction();

                // Create the question
                $question = Question::create([
                    'class_id'         => $class->id,
                    'subject_id'       => $subject->id,
                    'chapter_id'       => $chapter?->id,
                    'topic_id'         => $topic?->id,
                    'question_type_id' => $questionTypeId,
                    'question_text'    => $questionText,
                    'correct_answer'   => trim($row['correct_answer'] ?? ''),
                    'explanation'      => trim($row['explanation'] ?? ''),
                    'marks'            => is_numeric($row['marks'] ?? '') ? $row['marks'] : 1,
                    'difficulty'       => $difficulty,
                    'year'             => is_numeric($row['year'] ?? '') ? $row['year'] : null,
                    'status'           => QB_QUESTION_STATUS_PUBLISHED,
                    'source_type'      => 'excel_import',
                    'created_by'       => $this->userId,
                ]);

                // Handle MCQ options (A, B, C, D)
                if ($questionTypeId === QB_QTYPE_MCQ) {
                    $optionLetters = ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'];
                    $correctLetter = strtolower(trim($row['correct_answer'] ?? 'a'));

                    foreach ($optionLetters as $i => $key) {
                        $optText = trim($row[$key] ?? '');
                        if (empty($optText)) continue;

                        $letterChar = chr(97 + $i); // a, b, c, d, e
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optText,
                            'is_correct'  => ($correctLetter === $letterChar) ? 1 : 0,
                            'order'       => $i,
                        ]);
                    }
                }

                DB::commit();
                $this->imported++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }
    }
}
