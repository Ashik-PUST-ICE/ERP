<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subject::with('academicClass')->orderBy('subjects.id', 'desc');
            return datatables($data)
                ->addIndexColumn()
                ->addColumn('class_name', function ($item) {
                    return $item->academicClass ? $item->academicClass->name : 'N/A';
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
                                     <button type="button" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 editSubjectBtn" data-id="' . $item->id . '">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </button>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.subjects.destroy', $item->id) . '\', \'subjectsDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
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
        $data['title'] = __('Manage Subjects');
        $data['classes'] = AcademicClass::where('status', 1)->orderBy('order')->get();
        return view('sadmin.question_bank.subjects.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|in:' . QB_STATUS_ACTIVE . ',' . QB_STATUS_INACTIVE
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        Subject::create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Subject Created Successfully'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|in:' . QB_STATUS_ACTIVE . ',' . QB_STATUS_INACTIVE
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        $subject = Subject::findOrFail($id);
        $subject->update([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Subject Updated Successfully'));
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return $this->success([], __('Subject Deleted Successfully'));
    }

    public function getInfo(Request $request)
    {
        $subject = Subject::find($request->id);
        if ($subject) {
            return $this->success($subject);
        }
        return $this->error([], __('Subject Not Found'));
    }
}
