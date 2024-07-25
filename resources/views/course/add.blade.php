@extends('layout')

@section('content')
    <form action="{{ route('course.store') }}" method="POST" class="row g-3" id="course_form">
        @csrf
        <h2>Add Course</h2>
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
                            <input type="text" name="title" class="form-control" id="course_title">
                        </div>
                        <div class="col-md-6">
                            <label for="course_status" class="form-label">Status</label>
                            <select id="course_status" name="status" class="form-select">
                                <option selected value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="course_description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="course_description"></textarea>
                    </div>
                </li>
            </ul>
        </div>
        <div class="card m-1">
            <h2>Modules</h2>
            <div id="module-container"></div>
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

    <script src="{{ asset('assets/js/course/add.js') }}"></script>
@endsection
