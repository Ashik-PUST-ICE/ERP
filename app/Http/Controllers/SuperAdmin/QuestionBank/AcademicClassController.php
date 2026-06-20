<?php

namespace App\Http\Controllers\SuperAdmin\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class AcademicClassController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AcademicClass::orderBy('order');
            return datatables($data)
                ->addIndexColumn()
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
                                     <button type="button" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 editClassBtn" data-id="' . $item->id . '">
                                        <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                        <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                     </button>
                                  </li>
                                  <li>
                                     <button onclick="deleteItem(\'' . route('super-admin.question-bank.classes.destroy', $item->id) . '\', \'classesDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
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
        $data['title'] = __('Manage Classes');
        return view('sadmin.question_bank.classes.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Add New Class');
        return view('sadmin.question_bank.classes.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:classes,name',
            'order' => 'nullable|integer',
            'status' => 'required|in:1,2'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        AcademicClass::create([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Class Created Successfully'));
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Class');
        $data['class'] = AcademicClass::findOrFail($id);
        return view('sadmin.question_bank.classes.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:classes,name,' . $id,
            'order' => 'nullable|integer',
            'status' => 'required|in:1,2'
        ]);
        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first());
        }

        $class = AcademicClass::findOrFail($id);
        $class->update([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'status' => $request->status,
        ]);

        return $this->success([], __('Class Updated Successfully'));
    }

    public function destroy($id)
    {
        $class = AcademicClass::findOrFail($id);
        $class->delete();

        return $this->success([], __('Class Deleted Successfully'));
    }

    public function getInfo(Request $request)
    {
        $class = AcademicClass::find($request->id);
        if ($class) {
            return $this->success($class);
        }
        return $this->error([], __('Class Not Found'));
    }
}
