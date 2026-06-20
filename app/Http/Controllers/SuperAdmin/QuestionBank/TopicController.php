<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Topic::with('chapter.subject.academicClass')->orderBy('topics.id', 'desc');
            return datatables($data)
                ->addIndexColumn()
                ->addColumn('chapter_name', function ($item) {
                    return $item->chapter ? $item->chapter->name : 'N/A';
                })
                ->addColumn('subject_name', function ($item) {
                    return ($item->chapter && $item->chapter->subject) ? $item->chapter->subject->name : 'N/A';
                })
                ->addColumn('class_name', function ($item) {
                    return ($item->chapter && $item->chapter->subject && $item->chapter->subject->academicClass) ? $item->chapter->subject->academicClass->name : 'N/A';
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
                                     <button type="button" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 editTopicBtn" data-id="' . $item->id . '">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </button>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.topics.destroy', $item->id) . '\', \'topicsDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
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
        $data['title'] = __('Manage Topics');
        // Fetch chapters with their subjects and classes for the dropdown
        $data['chapters'] = Chapter::with('subject.academicClass')->where('status', 1)->orderBy('order')->get();
        return view('sadmin.question_bank.topics.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chapter_id' => 'required|exists:chapters,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|in:1,2'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        Topic::create([
            'chapter_id' => $request->chapter_id,
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Topic Created Successfully'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'chapter_id' => 'required|exists:chapters,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|in:1,2'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        $topic = Topic::findOrFail($id);
        $topic->update([
            'chapter_id' => $request->chapter_id,
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Topic Updated Successfully'));
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();

        return $this->success([], __('Topic Deleted Successfully'));
    }

    public function getInfo(Request $request)
    {
        $topic = Topic::find($request->id);
        if ($topic) {
            return $this->success($topic);
        }
        return $this->error([], __('Topic Not Found'));
    }
}
