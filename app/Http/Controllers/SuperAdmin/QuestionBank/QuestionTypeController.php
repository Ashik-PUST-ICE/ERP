<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class QuestionTypeController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = QuestionType::orderBy('id');
            return datatables($data)
                ->addIndexColumn()
                ->editColumn('has_options', function ($item) {
                    if ($item->has_options == 1) {
                        return '<div class="zBadge zBadge-active">' . __('Yes') . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-inactive">' . __('No') . '</div>';
                    }
                })
                ->addColumn('action', function ($item) {
                    return '<div class="dropdown dropdown-one">
                               <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                               <ul class="dropdown-menu dropdownItem-one">
                                  <li>
                                     <button type="button" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 editQuestionTypeBtn" data-id="' . $item->id . '">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </button>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.question-types.destroy', $item->id) . '\', \'questionTypesDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                        <div class="d-flex"><i class="fa-solid fa-trash text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Delete") . '</p>
                                     </button>
                                  </li>
                               </ul>
                            </div>';
                })
                ->rawColumns(['has_options', 'action'])
                ->make(true);
        }
        $data['title'] = __('Manage Question Types');
        return view('sadmin.question_bank.question_types.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:question_types,name',
            'has_options' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        QuestionType::create([
            'name' => $request->name,
            'has_options' => $request->has_options,
        ]);

        return $this->success([], __('Question Type Created Successfully'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:question_types,name,' . $id,
            'has_options' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        $questionType = QuestionType::findOrFail($id);
        $questionType->update([
            'name' => $request->name,
            'has_options' => $request->has_options,
        ]);

        return $this->success([], __('Question Type Updated Successfully'));
    }

    public function destroy($id)
    {
        $questionType = QuestionType::findOrFail($id);
        $questionType->delete();

        return $this->success([], __('Question Type Deleted Successfully'));
    }

    public function getInfo(Request $request)
    {
        $questionType = QuestionType::find($request->id);
        if ($questionType) {
            return $this->success($questionType);
        }
        return $this->error([], __('Question Type Not Found'));
    }
}
