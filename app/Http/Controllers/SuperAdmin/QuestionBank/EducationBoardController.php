<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\EducationBoard;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class EducationBoardController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EducationBoard::query();
            return datatables($data)
                ->addIndexColumn()
                ->editColumn('status', function ($item) {
                    if ($item->status == QB_STATUS_ACTIVE) {
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
                                     <button type="button" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 editBoardBtn" data-id="' . $item->id . '">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </button>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.education-boards.destroy', $item->id) . '\', \'boardsDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
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
        $data['title'] = __('Education Boards');
        $data['updateBaseUrl'] = route('super-admin.question-bank.education-boards.update', ':id');
        return view('sadmin.question_bank.education_boards.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:education_boards,name',
            'status' => 'required|in:' . QB_STATUS_ACTIVE . ',' . QB_STATUS_INACTIVE,
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        EducationBoard::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return $this->success([], __('Education Board Created Successfully'));
    }

    public function edit($id)
    {
        $board = EducationBoard::findOrFail($id);
        return $this->success($board);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:education_boards,name,' . $id,
            'status' => 'required|in:' . QB_STATUS_ACTIVE . ',' . QB_STATUS_INACTIVE,
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        $board = EducationBoard::findOrFail($id);
        $board->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return $this->success([], __('Education Board Updated Successfully'));
    }

    public function destroy($id)
    {
        EducationBoard::findOrFail($id)->delete();
        return $this->success([], __('Education Board Deleted Successfully'));
    }
}
