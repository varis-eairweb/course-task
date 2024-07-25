$(document).ready(function () {
    // Initialize counters for dynamic field generation using letters
    let moduleCount = "a";
    let questionCount = "a";
    let materialCount = "a";

    /**
     * Increment the letter for generating unique IDs.
     * @param {string} letter - The current letter to increment.
     * @returns {string|null} - The next letter or null if at 'z'.
     */
    function incrementLetter(letter) {
        if (letter === "z") return null; // No next letter after 'z'

        // Get the char code of the next letter and return it
        let nextCharCode = letter.charCodeAt(0) + 1;
        return String.fromCharCode(nextCharCode);
    }

    /**
     * Event handler to add material fields to a module.
     */
    $(document).on("click", "#add-material", function (e) {
        e.preventDefault();

        // Get the ID of the module to which materials are being added
        const moduleId = $(this).data("id");

        // Append a new set of material input fields
        $(`#material-container-${moduleId}`).append(`
            <div class="card m-1">
                <div class="row m-2">
                    <div class="col-md-5">
                        <label for="material_type_${materialCount}" class="form-label">Type</label>
                        <input type="text" name="module[${moduleId}][materials][${materialCount}][type]" class="form-control" id="material_type_${materialCount}">
                    </div>
                    <div class="col-md-5">
                        <label for="material_link_${materialCount}" class="form-label">Link</label>
                        <input type="text" name="module[${moduleId}][materials][${materialCount}][link]" class="form-control" id="material_link_${materialCount}">
                    </div>
                    <div class="col-md-2 mt-auto">
                        <button class="btn btn-danger remove-module-materials">X</button>
                    </div>
                </div>
            </div>
        `);

        // Increment material count for unique IDs
        materialCount = incrementLetter(materialCount);
    });

    /**
     * Event handler to add question fields to a module's test.
     */
    $(document).on("click", "#add-question", function (e) {
        e.preventDefault();

        // Get the ID of the module to which questions are being added
        const moduleId = $(this).data("id");

        // Append a new set of question input fields
        $(`#question-container-${moduleId}`).append(`
            <div class="card m-3">
                <div class="row">
                    <div class="col-md-11">
                        <div class="row m-2">
                            <div class="col-md-9">
                                <label for="question_${questionCount}" class="form-label">Question*</label>
                                <input type="text" class="form-control" name="module[${moduleId}][test][question][${questionCount}][question]" id="question_${questionCount}">
                            </div>
                            <div class="col-md-3">
                                <label for="question_status_${questionCount}" class="form-label">Status</label>
                                <select id="question_status_${questionCount}" name="module[${moduleId}][test][question][${questionCount}][status]" class="form-select">
                                    <option selected value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="col-md-1">
                                <input class="form-check-input mt-auto" type="checkbox" name="module[${moduleId}][test][question][${questionCount}][answer]" value="1">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="module[${moduleId}][test][question][${questionCount}][option_1]" placeholder="Option 1">
                            </div>
                            <div class="col-md-1">
                                <input class="form-check-input mt-auto" type="checkbox" name="module[${moduleId}][test][question][${questionCount}][answer]" value="2">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="module[${moduleId}][test][question][${questionCount}][option_2]" placeholder="Option 2">
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="col-md-1">
                                <input class="form-check-input mt-auto" type="checkbox" name="module[${moduleId}][test][question][${questionCount}][answer]" value="3">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="module[${moduleId}][test][question][${questionCount}][option_3]" placeholder="Option 3">
                            </div>
                            <div class="col-md-1">
                                <input class="form-check-input mt-auto" type="checkbox" name="module[${moduleId}][test][question][${questionCount}][answer]" value="4">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="module[${moduleId}][test][question][${questionCount}][option_4]" placeholder="Option 4">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 pt-4">
                        <button class="btn btn-danger remove-module-question">X</button>
                    </div>
                </div>
            </div>
        `);

        // Increment question count for unique IDs
        questionCount = incrementLetter(questionCount);
    });

    /**
     * Event handler to add a new module to the course.
     */
    $(document).on("click", "#add-module", function (e) {
        e.preventDefault();

        // Append a new module with its fields
        $("#module-container").append(`
            <div class="card">
                <button class="btn btn-danger remove-module">X</button>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h3>Course Module</h3>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="module_title_${moduleCount}" class="form-label">Title</label>
                                <input type="text" name="module[${moduleCount}][title]" class="form-control" id="module_title_${moduleCount}">
                            </div>
                            <div class="col-md-4">
                                <label for="module_status_${moduleCount}" class="form-label">Status</label>
                                <select id="module_status_${moduleCount}" name="module[${moduleCount}][status]" class="form-select">
                                    <option selected value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="module_is_testable_${moduleCount}" class="form-label">Is Testable</label>
                                <select id="module_is_testable_${moduleCount}" name="module[${moduleCount}][is_testable]" class="form-select">
                                    <option selected value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="module_description_${moduleCount}" class="form-label">Description</label>
                            <textarea class="form-control" name="module[${moduleCount}][description]" id="module_description_${moduleCount}"></textarea>
                        </div>
                        <h5 class="m-2">Course Module Materials</h5>
                        <div id="material-container-${moduleCount}"></div>
                        <button class="btn btn-secondary m-2" type="button" id="add-material" data-id="${moduleCount}">Add Material</button>
                    </li>
                    <li class="list-group-item">
                        <h3>Course Test</h3>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="test_title_${moduleCount}" class="form-label">Title</label>
                                <input type="text" name="module[${moduleCount}][test][title]" class="form-control" id="test_title_${moduleCount}">
                            </div>
                            <div class="col-md-4">
                                <label for="test_duration_${moduleCount}" class="form-label">Duration</label>
                                <input type="text" name="module[${moduleCount}][test][duration]" class="form-control" id="test_duration_${moduleCount}">
                            </div>
                            <div class="col-12">
                                <label for="test_instructions_${moduleCount}" class="form-label">Instructions</label>
                                <textarea class="form-control" name="module[${moduleCount}][test][instructions]" id="test_instructions_${moduleCount}"></textarea>
                            </div>
                            <h4 class="mt-4">Questions</h4>
                            <div id="question-container-${moduleCount}"></div>
                        </div>
                        <button class="btn btn-secondary m-2" type="button" id="add-question" data-id="${moduleCount}">Add Question</button>
                    </li>
                </ul>
            </div>
        `);

        // Increment module count for unique IDs
        moduleCount = incrementLetter(moduleCount);
    });

    /**
     * Event handler to remove a material field.
     */
    $(document).on("click", ".remove-module-materials", function (e) {
        e.preventDefault();
        $(this).closest(".card").remove();
    });

    /**
     * Event handler to remove a question field.
     */
    $(document).on("click", ".remove-module-question", function (e) {
        e.preventDefault();
        $(this).closest(".card").remove();
    });

    /**
     * Event handler to remove an entire module.
     */
    $(document).on("click", ".remove-module", function (e) {
        e.preventDefault();
        $(this).closest(".card").remove();
    });
});
