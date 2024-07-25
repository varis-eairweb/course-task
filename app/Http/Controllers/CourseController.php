<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\CourseModuleTest;
use App\Models\CourseModuleTestQuestion;
use App\Models\Module;
use App\Models\ModuleMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Builder $builder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Builder $builder)
    {
        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action'],
        ])->ajax([
            'url' => route('course.datatable.list'),
            'type' => 'POST',
            'headers' => ['X-CSRF-TOKEN' => csrf_token()],
            'data' => 'function(d) {
                d.search = $("#all_search").val();
            }'
        ])->parameters([
            'paging' => true,
            'bLengthChange' => false,
            'searching' => false,
            'info' => false,
            'order' => [0, 'desc'],
        ]);

        return view('course.list', compact('html'));
    }

    /**
     * Handle DataTables ajax request for courses.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataTableList(Request $request)
    {
        $courses = Course::with('module', 'module.courseModuleTest', 'module.courseModuleTest.courseModuleTestQuestion', 'module.moduleMaterial');

        if ($request->filled('search')) {
            $courses->where('title', 'LIKE', '%' . $request->search . '%');
        }

        return DataTables::of($courses)
            ->addColumn('action', function ($course) {
                return '
                    <a href="' . route('course.edit', $course->id) . '"><button class="btn btn-primary">Edit</button></a>
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="delete" data-url="' . route('course.destroy', $course->id) . '">
                        <button class="table-delete-button btn btn-primary">Delete</button>
                    </a>
                    &nbsp;&nbsp;
                ';
            })
            ->addColumn('status', function ($course) {
                return $course->status == 1 ? "Active" : "Inactive";
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('course.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourseRequest $request)
    {
        $data = $request->all();
        $course = Course::create($data);

        foreach ($request->module as $moduleValue) {
            $moduleValue['course_id'] = $course->id;
            $module = Module::create($moduleValue);

            if (isset($moduleValue['materials'])) {
                foreach ($moduleValue['materials'] as $materialData) {
                    $materialData['module_id'] = $module->id;
                    ModuleMaterial::create($materialData);
                }
            }

            $testData = $moduleValue['test'];
            $testData['module_id'] = $module->id;
            $test = CourseModuleTest::create($testData);

            foreach ($testData['question'] as $questionData) {
                $questionData['course_module_test_id'] = $test->id;
                CourseModuleTestQuestion::create($questionData);
            }
        }

        return redirect()->route('course.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     */
    public function show(string $id)
    {
        // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $id)
    {
        $course = Course::with('module', 'module.courseModuleTest', 'module.courseModuleTest.courseModuleTestQuestion', 'module.moduleMaterial')->findOrFail($id);
        return view('course.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $course = Course::findOrFail($id);
        $course->update($data);

        $moduleIds = [];
        $materialIds = [];
        $testIds = [];
        $questionIds = [];

        foreach ($data['module'] as $moduleId => $moduleData) {
            if (is_numeric($moduleId)) {
                $module = Module::findOrFail($moduleId);
                $module->update($moduleData);
            } else {
                $moduleData['course_id'] = $course->id;
                $module = Module::create($moduleData);
            }
            $moduleIds[] = $module->id;

            if (isset($moduleData['materials'])) {
                foreach ($moduleData['materials'] as $materialId => $materialData) {
                    if (is_numeric($materialId)) {
                        $material = ModuleMaterial::findOrFail($materialId);
                        $material->update($materialData);
                    } else {
                        $materialData['module_id'] = $module->id;
                        $material = ModuleMaterial::create($materialData);
                    }
                    $materialIds[] = $material->id;
                }
            }

            if (isset($moduleData['test'])) {
                $testData = $moduleData['test'];
                if ($module->courseModuleTest) {
                    $test = CourseModuleTest::findOrFail($module->courseModuleTest->id);
                    $test->update($testData);
                } else {
                    $testData['module_id'] = $module->id;
                    $test = CourseModuleTest::create($testData);
                }
                $testIds[] = $test->id;

                foreach ($testData['question'] as $questionId => $questionData) {
                    if (is_numeric($questionId)) {
                        $question = CourseModuleTestQuestion::findOrFail($questionId);
                        $question->update($questionData);
                    } else {
                        $questionData['course_module_test_id'] = $test->id;
                        $question = CourseModuleTestQuestion::create($questionData);
                    }
                    $questionIds[] = $question->id;
                }
            }
        }

        Module::whereNotIn('id', $moduleIds)->where('course_id', $course->id)->delete();
        ModuleMaterial::whereNotIn('id', $materialIds)->whereIn('module_id', $moduleIds)->delete();
        CourseModuleTest::whereNotIn('id', $testIds)->whereIn('module_id', $moduleIds)->delete();
        CourseModuleTestQuestion::whereNotIn('id', $questionIds)->whereIn('course_module_test_id', $testIds)->delete();

        return redirect()->route('course.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->route('course.index')->with('success', 'Course deleted successfully.');
    }
}
