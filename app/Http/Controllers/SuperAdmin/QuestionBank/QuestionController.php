<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\AcademicClass;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\QuestionType;
use App\Models\FileManager;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class QuestionController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Question::with(['academicClass', 'subject', 'chapter', 'topic', 'questionType'])->orderBy('questions.id', 'desc');
            return datatables($data)
                ->addIndexColumn()
                ->addColumn('class_name', function ($item) {
                    return $item->academicClass ? $item->academicClass->name : 'N/A';
                })
                ->addColumn('subject_name', function ($item) {
                    return $item->subject ? $item->subject->name : 'N/A';
                })
                ->addColumn('type_name', function ($item) {
                    return $item->questionType ? $item->questionType->name : 'N/A';
                })
                ->addColumn('question_preview', function ($item) {
                    return strip_tags(substr($item->question_text, 0, 50)) . '...';
                })
                ->editColumn('status', function ($item) {
                    if ($item->status == 1) {
                        return '<div class="zBadge zBadge-active">' . __('Active') . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-inactive">' . __('Inactive') . '</div>';
                    }
                })
                ->addColumn('action', function ($item) {
                    return '<div class="dropdown dropdown-one">
                               <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                               <ul class="dropdown-menu dropdownItem-one">
                                  <li>
                                     <a href="' . route('super-admin.question-bank.questions.edit', $item->id) . '" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </a>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.questions.destroy', $item->id) . '\', \'questionsDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                        <div class="d-flex"><i class="fa-solid fa-trash text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Delete") . '</p>
                                     </button>
                                  </li>
                               </ul>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $data['title'] = __('Manage Questions');
        return view('sadmin.question_bank.questions.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Add New Question');
        $data['classes'] = AcademicClass::where('status', 1)->orderBy('order')->get();
        $data['questionTypes'] = getQuestionTypes();
        return view('sadmin.question_bank.questions.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'topic_id' => 'nullable|exists:topics,id',
            'question_type_id' => 'required|integer',
            'question_text' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'correct_answer' => 'nullable|string',
            'explanation' => 'nullable|string',
            'marks' => 'required|numeric',
            'difficulty' => 'required|in:' . QB_DIFFICULTY_EASY . ',' . QB_DIFFICULTY_MEDIUM . ',' . QB_DIFFICULTY_HARD,
            'board_id' => 'nullable|exists:education_boards,id',
            'stem_id' => 'nullable|exists:question_stems,id',
            'year' => 'nullable|integer',
            'status' => 'required|in:' . QB_QUESTION_STATUS_DRAFT . ',' . QB_QUESTION_STATUS_PUBLISHED . ',' . QB_QUESTION_STATUS_ARCHIVED,
            'source' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $question = new Question();
            $question->class_id = $request->class_id;
            $question->subject_id = $request->subject_id;
            $question->chapter_id = $request->chapter_id;
            $question->topic_id = $request->topic_id;
            $question->question_type_id = $request->question_type_id;
            $question->question_text = $request->question_text;
            // Determine correct_answer and options_json based on type
            $selectedType = (int) $request->question_type_id;
            $correctAnswer = null;
            $optionsJson = null;

            if ($selectedType === QB_QTYPE_MCQ) {
                // Handled via QuestionOption table
            } elseif ($selectedType === QB_QTYPE_TRUE_FALSE) {
                $correctAnswer = $request->tf_answer;
            } elseif ($selectedType === QB_QTYPE_FILL_BLANK) {
                if ($request->has('blanks') && is_array($request->blanks)) {
                    $optionsJson = json_encode(['blanks' => $request->blanks]);
                }
            } elseif ($selectedType === QB_QTYPE_MATCHING) {
                if ($request->has('match_left') && $request->has('match_right')) {
                    $matches = [];
                    foreach ($request->match_left as $idx => $left) {
                        if (!empty($left) && !empty($request->match_right[$idx])) {
                            $matches[] = ['left' => $left, 'right' => $request->match_right[$idx]];
                        }
                    }
                    $optionsJson = json_encode(['matches' => $matches]);
                }
            } elseif ($selectedType === QB_QTYPE_SHORT || $selectedType === QB_QTYPE_LONG) {
                $correctAnswer = $request->correct_answer;
            }

            $question->correct_answer = $correctAnswer;
            $question->options_json = $optionsJson;
            $question->explanation = $request->explanation;
            $question->marks = $request->marks;
            $question->difficulty = $request->difficulty;
            $question->board_id = $request->board_id;
            $question->stem_id = $request->stem_id;
            $question->year = $request->year;
            $question->status = $request->status;
            $question->source = $request->source;
            $question->created_by = auth()->id();

            if ($request->hasFile('image')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('Question', $request->image);
                if ($uploaded) {
                    $question->image = 'storage/' . $uploaded->path;
                }
            }

            $question->save();

            // Handle Dynamic Options
            if ($request->has('options') && is_array($request->options)) {
                $isCorrectIndex = $request->is_correct_option ?? -1;
                foreach ($request->options as $index => $optionText) {
                    if (!empty($optionText)) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionText,
                            'is_correct' => ($index == $isCorrectIndex) ? 1 : 0,
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();
            return $this->success([], __('Question Created Successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Question');
        $data['question'] = Question::with('options')->findOrFail($id);
        $data['classes'] = AcademicClass::where('status', 1)->orderBy('order')->get();
        $data['subjects'] = Subject::where('class_id', $data['question']->class_id)->where('status', 1)->orderBy('order')->get();
        $data['chapters'] = Chapter::where('subject_id', $data['question']->subject_id)->where('status', 1)->orderBy('order')->get();
        $data['topics'] = Topic::where('chapter_id', $data['question']->chapter_id)->where('status', 1)->orderBy('order')->get();
        $data['questionTypes'] = getQuestionTypes();
        return view('sadmin.question_bank.questions.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'topic_id' => 'nullable|exists:topics,id',
            'question_type_id' => 'required|integer',
            'question_text' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'correct_answer' => 'nullable|string',
            'explanation' => 'nullable|string',
            'marks' => 'required|numeric',
            'difficulty' => 'required|in:' . QB_DIFFICULTY_EASY . ',' . QB_DIFFICULTY_MEDIUM . ',' . QB_DIFFICULTY_HARD,
            'board_id' => 'nullable|exists:education_boards,id',
            'stem_id' => 'nullable|exists:question_stems,id',
            'year' => 'nullable|integer',
            'status' => 'required|in:' . QB_QUESTION_STATUS_DRAFT . ',' . QB_QUESTION_STATUS_PUBLISHED . ',' . QB_QUESTION_STATUS_ARCHIVED,
            'source' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $question = Question::findOrFail($id);
            $question->class_id = $request->class_id;
            $question->subject_id = $request->subject_id;
            $question->chapter_id = $request->chapter_id;
            $question->topic_id = $request->topic_id;
            $selectedType = (int) $request->question_type_id;
            $correctAnswer = null;
            $optionsJson = null;

            if ($selectedType === QB_QTYPE_MCQ) {
                // Options handled below
            } elseif ($selectedType === QB_QTYPE_TRUE_FALSE) {
                $correctAnswer = $request->tf_answer;
            } elseif ($selectedType === QB_QTYPE_FILL_BLANK) {
                if ($request->has('blanks') && is_array($request->blanks)) {
                    $optionsJson = json_encode(['blanks' => $request->blanks]);
                }
            } elseif ($selectedType === QB_QTYPE_MATCHING) {
                if ($request->has('match_left') && $request->has('match_right')) {
                    $matches = [];
                    foreach ($request->match_left as $idx => $left) {
                        if (!empty($left) && !empty($request->match_right[$idx])) {
                            $matches[] = ['left' => $left, 'right' => $request->match_right[$idx]];
                        }
                    }
                    $optionsJson = json_encode(['matches' => $matches]);
                }
            } elseif ($selectedType === QB_QTYPE_SHORT || $selectedType === QB_QTYPE_LONG) {
                $correctAnswer = $request->correct_answer;
            }

            $question->question_type_id = $selectedType;
            $question->question_text = $request->question_text;
            $question->correct_answer = $correctAnswer;
            $question->options_json = $optionsJson;
            $question->explanation = $request->explanation;
            $question->marks = $request->marks;
            $question->difficulty = $request->difficulty;
            $question->board_id = $request->board_id;
            $question->stem_id = $request->stem_id;
            $question->year = $request->year;
            $question->status = $request->status;
            $question->source = $request->source;

            if ($request->hasFile('image')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('Question', $request->image);
                if ($uploaded) {
                    $question->image = 'storage/' . $uploaded->path;
                }
            }

            $question->save();

            // Handle Dynamic Options (Re-create them)
            if ($request->has('options') && is_array($request->options)) {
                // Delete existing options
                QuestionOption::where('question_id', $question->id)->delete();
                
                $isCorrectIndex = $request->is_correct_option ?? -1;
                foreach ($request->options as $index => $optionText) {
                    if (!empty($optionText)) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionText,
                            'is_correct' => ($index == $isCorrectIndex) ? 1 : 0,
                            'order' => $index,
                        ]);
                    }
                }
            } else {
                // If the type changed to something without options, clear existing options
                $qTypes = getQuestionTypes();
                $qType = $qTypes[$request->question_type_id] ?? null;
                if ($qType && $qType['has_options'] == 0) {
                    QuestionOption::where('question_id', $question->id)->delete();
                }
            }

            DB::commit();
            return $this->success([], __('Question Updated Successfully'));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        // Automatically deletes options due to DB cascade or we can manually delete
        QuestionOption::where('question_id', $question->id)->delete();
        $question->delete();

        return $this->success([], __('Question Deleted Successfully'));
    }

    // --- API Endpoints for Cascading Dropdowns ---
    public function getSubjectsByClass(Request $request)
    {
        $subjects = Subject::where('class_id', $request->class_id)->where('status', 1)->orderBy('order')->get();
        return response()->json(['success' => true, 'data' => $subjects]);
    }

    public function getChaptersBySubject(Request $request)
    {
        $chapters = Chapter::where('subject_id', $request->subject_id)->where('status', 1)->orderBy('order')->get();
        return response()->json(['success' => true, 'data' => $chapters]);
    }

    public function getTopicsByChapter(Request $request)
    {
        $topics = Topic::where('chapter_id', $request->chapter_id)->where('status', 1)->orderBy('order')->get();
        return response()->json(['success' => true, 'data' => $topics]);
    }

    public function getQuestionTypeInfo(Request $request)
    {
        $types = getQuestionTypes();
        $type = $types[$request->type_id] ?? null;
        return response()->json(['success' => true, 'data' => $type]);
    }
}
