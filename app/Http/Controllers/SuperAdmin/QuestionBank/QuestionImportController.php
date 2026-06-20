<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Exports\QuestionsTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use App\Models\AcademicClass;
use App\Models\AiAgentSetting;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\UploadedBook;
use App\Services\Ai\AiServiceFactory;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class QuestionImportController extends Controller
{
    use ResponseTrait;

    /**
     * Import / AI Generate page.
     */
    public function index()
    {
        $data['title']    = __('Import & AI Generate Questions');
        $data['classes']  = AcademicClass::where('status', 1)->orderBy('order')->get();
        $data['books']    = UploadedBook::with(['academicClass', 'subject'])->latest()->get();

        // AI provider info
        $settings = AiAgentSetting::forUser(auth()->id());
        $data['aiProvider']    = $settings->ai_provider;
        $data['aiModel']       = $settings->ai_model;
        $data['aiConfigured']  = $settings->isProviderConfigured();

        return view('sadmin.question_bank.import.index', $data);
    }

    /**
     * Download the Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new QuestionsTemplateExport(), 'questions_import_template.xlsx');
    }

    /**
     * Handle Excel bulk import.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new QuestionsImport(auth()->id());
        Excel::import($import, $request->file('excel_file'));

        $msg = "Imported {$import->imported} question(s) successfully.";
        if (count($import->errors)) {
            $msg .= ' Errors: ' . implode(' | ', $import->errors);
        }

        return $this->success(['errors' => $import->errors, 'imported' => $import->imported], $msg);
    }

    /**
     * Upload a book/PDF/DOCX for AI generation.
     * Extracts text and saves to uploaded_books.
     */
    public function uploadBook(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'book_file'  => 'required|file|mimes:pdf,docx,txt|max:20480',
            'class_id'   => 'nullable|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $file     = $request->file('book_file');
        $ext      = strtolower($file->getClientOriginalExtension());
        $path     = $file->store('uploaded_books', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Extract text based on file type
        $extractedText = '';
        try {
            if ($ext === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($fullPath);
                $extractedText = $pdf->getText();
            } elseif ($ext === 'docx') {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $extractedText .= $element->getText() . "\n";
                        }
                    }
                }
            } elseif ($ext === 'txt') {
                $extractedText = file_get_contents($fullPath);
            }
        } catch (\Exception $e) {
            $extractedText = '';
        }

        $book = UploadedBook::create([
            'title'          => $request->title,
            'file_path'      => $path,
            'file_type'      => $ext,
            'extracted_text' => $extractedText,
            'class_id'       => $request->class_id,
            'subject_id'     => $request->subject_id,
            'status'         => 1,
            'uploaded_by'    => auth()->id(),
        ]);

        $textPreview = mb_substr($extractedText, 0, 300);
        return $this->success([
            'book_id'      => $book->id,
            'text_preview' => $textPreview,
            'text_length'  => mb_strlen($extractedText),
        ], __('Book uploaded and text extracted successfully.'));
    }

    /**
     * Generate questions from a book using AI.
     */
    public function generateFromBook(Request $request)
    {
        $request->validate([
            'book_id'       => 'required|exists:uploaded_books,id',
            'class_id'      => 'required|exists:classes,id',
            'subject_id'    => 'required|exists:subjects,id',
            'chapter_id'    => 'nullable|exists:chapters,id',
            'topic_id'      => 'nullable|exists:topics,id',
            'question_type' => 'required|integer',
            'count'         => 'required|integer|min:1|max:30',
            'difficulty'    => 'required|in:' . QB_DIFFICULTY_EASY . ',' . QB_DIFFICULTY_MEDIUM . ',' . QB_DIFFICULTY_HARD,
        ]);

        $book     = UploadedBook::findOrFail($request->book_id);
        $text     = $book->extracted_text;

        if (empty(trim($text))) {
            return $this->error([], __('No text extracted from this book. Cannot generate questions.'));
        }

        // Truncate text to avoid token limit (~6000 chars ≈ ~1500 tokens)
        $text = mb_substr($text, 0, 6000);

        // Resolve AI service using existing admin AI system
        try {
            $settings = AiAgentSetting::forUser(auth()->id());
            $aiService = AiServiceFactory::make($settings);
        } catch (\Exception $e) {
            return $this->error([], __('AI not configured. Please set up your AI API key in AI Agent Settings.'));
        }

        $types    = getQuestionTypes();
        $typeName = $types[$request->question_type]['name'] ?? 'MCQ';
        $diffNames = [
            QB_DIFFICULTY_EASY   => 'Easy',
            QB_DIFFICULTY_MEDIUM => 'Medium',
            QB_DIFFICULTY_HARD   => 'Hard',
        ];
        $diffName = $diffNames[$request->difficulty] ?? 'Medium';
        $count    = (int) $request->count;

        // Build MCQ vs non-MCQ prompt
        if ((int)$request->question_type === QB_QTYPE_MCQ) {
            $formatInstructions = <<<EOT
Return ONLY a valid JSON array. Each item must have:
- "question_text": string
- "option_a": string
- "option_b": string
- "option_c": string
- "option_d": string
- "correct_answer": one of "a", "b", "c", "d"
- "explanation": string (optional, can be empty)

Example: [{"question_text":"What is...","option_a":"A","option_b":"B","option_c":"C","option_d":"D","correct_answer":"a","explanation":"..."}]
EOT;
        } else {
            $formatInstructions = <<<EOT
Return ONLY a valid JSON array. Each item must have:
- "question_text": string
- "correct_answer": string (the model/correct answer)
- "explanation": string (optional, can be empty)

Example: [{"question_text":"What is...","correct_answer":"The answer is...","explanation":""}]
EOT;
        }

        $prompt = <<<EOT
You are an expert question maker for Bangladeshi school/college education.

Based on the following text content from a book, generate exactly {$count} {$typeName} questions at {$diffName} difficulty level.

TEXT:
{$text}

INSTRUCTIONS:
1. Generate exactly {$count} questions.
2. Questions must be based STRICTLY on the provided text.
3. {$formatInstructions}
4. Do NOT include any explanation text outside the JSON. Return ONLY the JSON array.
EOT;

        try {
            $rawResponse = $aiService->chat(
                [['role' => 'user', 'content' => $prompt]],
                'You are a helpful educational question generator. Always respond with valid JSON only.',
                ['max_tokens' => 3000]
            );

            // Extract JSON from response
            preg_match('/\[.*\]/s', $rawResponse, $matches);
            $jsonStr = $matches[0] ?? $rawResponse;
            $questions = json_decode($jsonStr, true);

            if (!is_array($questions)) {
                return $this->error([], __('AI returned invalid format. Please try again.'));
            }

            return $this->success([
                'questions'     => $questions,
                'question_type' => (int) $request->question_type,
                'class_id'      => $request->class_id,
                'subject_id'    => $request->subject_id,
                'chapter_id'    => $request->chapter_id,
                'topic_id'      => $request->topic_id,
                'difficulty'    => $request->difficulty,
            ], __('Questions generated successfully!'));
        } catch (\Exception $e) {
            return $this->error([], __('AI generation failed: ') . $e->getMessage());
        }
    }

    /**
     * Save AI-generated questions (after review).
     */
    public function saveGenerated(Request $request)
    {
        $request->validate([
            'questions'     => 'required|array|min:1',
            'class_id'      => 'required|exists:classes,id',
            'subject_id'    => 'required|exists:subjects,id',
            'chapter_id'    => 'nullable|exists:chapters,id',
            'topic_id'      => 'nullable|exists:topics,id',
            'question_type' => 'required|integer',
            'difficulty'    => 'required|integer',
        ]);

        $saved  = 0;
        $typeId = (int) $request->question_type;

        DB::beginTransaction();
        try {
            foreach ($request->questions as $qData) {
                $questionText = trim($qData['question_text'] ?? '');
                if (empty($questionText)) continue;

                $correctAnswer = null;
                $optionsJson   = null;

                if ($typeId === QB_QTYPE_MCQ) {
                    // correct_answer is a letter (a/b/c/d), we store it for reference
                    $correctAnswer = $qData['correct_answer'] ?? 'a';
                } else {
                    $correctAnswer = $qData['correct_answer'] ?? '';
                }

                $question = Question::create([
                    'class_id'         => $request->class_id,
                    'subject_id'       => $request->subject_id,
                    'chapter_id'       => $request->chapter_id,
                    'topic_id'         => $request->topic_id,
                    'question_type_id' => $typeId,
                    'question_text'    => $questionText,
                    'correct_answer'   => $correctAnswer,
                    'explanation'      => trim($qData['explanation'] ?? ''),
                    'marks'            => 1,
                    'difficulty'       => $request->difficulty,
                    'status'           => QB_QUESTION_STATUS_PUBLISHED,
                    'source_type'      => 'ai_generated',
                    'created_by'       => auth()->id(),
                ]);

                // Save MCQ options
                if ($typeId === QB_QTYPE_MCQ) {
                    $optionLetters = ['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'];
                    foreach ($optionLetters as $letter => $key) {
                        $optText = trim($qData[$key] ?? '');
                        if (empty($optText)) continue;
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optText,
                            'is_correct'  => ($letter === strtolower($correctAnswer)) ? 1 : 0,
                            'order'       => ord($letter) - 97,
                        ]);
                    }
                }

                $saved++;
            }

            DB::commit();
            return $this->success(['saved' => $saved], __("{$saved} question(s) saved to Question Bank successfully!"));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Delete an uploaded book.
     */
    public function deleteBook($id)
    {
        $book = UploadedBook::findOrFail($id);
        // Delete file from storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($book->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
        }
        $book->delete();
        return $this->success([], __('Book deleted successfully.'));
    }
}
