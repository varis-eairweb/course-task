@extends('layout')

@section('content')
    <form action="{{ route('course.update', $course) }}" method="POST" class="row g-3" id="course_form">
        @csrf
        @method('put')
        <h2>Edit Course</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="course_title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="course_title"
                                value="{{ $course->title ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="course_status" class="form-label">Status</label>
                            <select id="course_status" name="status" class="form-select">
                                <option selected value="1">Active</option>
                                <option value="2" {{ $course->status == 2 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="course_description" class="form-label">Description</label>
                        <div id="course_description">{!! $course->description !!}</div>
                        <textarea name="description" class="form-control" id="og_description" style="position: absolute;left: -9999px;"> {{ $course->description }}</textarea>
                    </div>
                </li>
            </ul>
        </div>
        <div class="card m-1">
            <h2>Modules</h2>
            <div id="module-container">
                @foreach ($course->module as $module)
                    <div class="card">
                        <button class="btn btn-danger remove-module">X</button>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h3>Course Module</h3>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label for="module_title_{{ $module->id }}" class="form-label">Title</label>
                                        <input type="text" name="module[{{ $module->id }}][title]" class="form-control"
                                            id="module_title_{{ $module->id }}" value="{{ $module->title }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="module_status_{{ $module->id }}" class="form-label">Status</label>
                                        <select id="module_status_{{ $module->id }}"
                                            name="module[{{ $module->id }}][status]" class="form-select">
                                            <option selected value="1">Active</option>
                                            <option value="2" {{ $module->status == 2 ?? false ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="module_is_testable_{{ $module->id }}" class="form-label">Is
                                            Testable</label>
                                        <select id="module_is_testable_{{ $module->id }}"
                                            name="module[{{ $module->id }}][is_testable]" class="form-select">
                                            <option selected value="1">Yes</option>
                                            <option value="2"
                                                {{ $module->is_testable == 2 ?? false ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="module_description_{{ $module->id }}"
                                        class="form-label">Description</label>
                                    <textarea class="form-control" name="module[{{ $module->id }}][description]"
                                        id="module_description_{{ $module->id }}">{{ $module->description ?? '' }}</textarea>
                                </div>
                                <h5 class="m-2">Course Module Materials</h5>
                                <div id="material-container-{{ $module->id }}">
                                    @foreach ($module->moduleMaterial as $material)
                                        <div class="card m-1">
                                            <div class="row m-2">
                                                <div class="col-md-5">
                                                    <label for="material_type_{{ $material->id }}"
                                                        class="form-label">Type</label>
                                                    <input type="text"
                                                        name="module[{{ $module->id }}][materials][{{ $material->id }}][type]"
                                                        class="form-control" id="material_type_{{ $material->id }}"
                                                        value="{{ $material->type ?? '' }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="material_link_{{ $material->id }}"
                                                        class="form-label">Link</label>
                                                    <input type="text"
                                                        name="module[{{ $module->id }}][materials][{{ $material->id }}][link]"
                                                        class="form-control" id="material_link_{{ $material->id }}"
                                                        value="{{ $material->link ?? '' }}">
                                                </div>
                                                <div class="col-md-2 mt-auto">
                                                    <button class="btn btn-danger remove-module-materials">X</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                <button class="btn btn-secondary m-2" type="button" id="add-material"
                                    data-id="{{ $module->id }}">Add Material</button>
                            </li>
                            <li class="list-group-item">
                                <h3>Course Test</h3>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="test_title_{{ $module->id }}" class="form-label">Title</label>
                                        <input type="text" name="module[{{ $module->id }}][test][title]"
                                            class="form-control" id="test_title_{{ $module->id }}"
                                            value="{{ $module->courseModuleTest->title ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="test_duration_{{ $module->id }}"
                                            class="form-label">Duration</label>
                                        <input type="text" name="module[{{ $module->id }}][test][duration]"
                                            class="form-control" id="test_duration_{{ $module->id }}"
                                            value="{{ $module->courseModuleTest->duration ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="test_instructions_{{ $module->id }}"
                                            class="form-label">Instructions</label>
                                        <textarea class="form-control" name="module[{{ $module->id }}][test][instructions]"
                                            id="test_instructions_{{ $module->id }}">{{ $module->courseModuleTest->instructions ?? '' }}</textarea>
                                    </div>
                                    <h4 class="mt-4">Questions</h4>
                                    <div id="question-container-{{ $module->id }}">
                                        @foreach ($module->courseModuleTest->courseModuleTestQuestion ?? [] as $question)
                                            <div class="card m-3">
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <div class="row m-2">
                                                            <div class="col-md-9">
                                                                <label for="question_{{ $question->id }}"
                                                                    class="form-label">Question*</label>
                                                                <input type="text" class="form-control"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][question]"
                                                                    id="question_{{ $question->id }}"
                                                                    value="{{ $question->question ?? '' }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="question_status_{{ $question->id }}"
                                                                    class="form-label">Status</label>
                                                                <select id="question_status_{{ $question->id }}"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][status]"
                                                                    class="form-select">
                                                                    <option selected value="1">Active</option>
                                                                    <option value="2"
                                                                        {{ $question->status == 2 ?? false ? 'selected' : '' }}>
                                                                        Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row m-2">
                                                            <div class="col-md-1">
                                                                <input class="form-check-input mt-auto" type="checkbox"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][answer]"
                                                                    value="1"
                                                                    {{ $question->answer == 1 ?? false ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][option_1]"
                                                                    placeholder="Option 1"
                                                                    value="{{ $question->option_1 ?? '' }}">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input class="form-check-input mt-auto" type="checkbox"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][answer]"
                                                                    value="2"
                                                                    {{ $question->answer == 2 ?? false ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][option_2]"
                                                                    placeholder="Option 2"
                                                                    value="{{ $question->option_2 ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row m-2">
                                                            <div class="col-md-1">
                                                                <input class="form-check-input mt-auto" type="checkbox"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][answer]"
                                                                    value="3"
                                                                    {{ $question->answer == 3 ?? false ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][option_3]"
                                                                    placeholder="Option 3"
                                                                    value="{{ $question->option_3 ?? '' }}">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input class="form-check-input mt-auto" type="checkbox"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][answer]"
                                                                    value="4"
                                                                    {{ $question->answer == 4 ?? false ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control"
                                                                    name="module[{{ $module->id }}][test][question][{{ $question->id }}][option_4]"
                                                                    placeholder="Option 4"
                                                                    value="{{ $question->option_4 ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pt-4">
                                                        <button class="btn btn-danger remove-module-question">X</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="btn btn-secondary m-2" type="button" id="add-question"
                                    data-id="{{ $module->id }}">Add Question</button>
                            </li>
                        </ul>
                    </div>
                @endforeach

            </div>
            <div class="col-12 m-2">
                <button type="button" class="btn btn-outline-secondary" id="add-module">Add Module</button>
            </div>
            <div class="col-12 m-2">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font
        } from 'ckeditor5';

        ClassicEditor
            .create(document.querySelector('#course_description'), {
                plugins: [Essentials, Paragraph, Bold, Italic, Font],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ]
            }).then(editor => {
                // Listen for changes in the editor
                editor.model.document.on('change:data', () => {
                    document.getElementById('og_description').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    </script>

    <!-- JavaScript Requirements -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Laravel JavaScript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CourseRequest', '#course_form') !!}

    <script src="{{ asset('assets/js/course/edit.js') }}"></script>
@endsection
