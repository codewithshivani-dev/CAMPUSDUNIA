@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    /* Common Styles */
    .file-input {
        display: block;
        clear: both;
        margin: 0 auto;
        width: 100%;
        max-width: 600px;
    }

    .file-input label {
        float: left;
        clear: both;
        width: 100%;
        padding: 2rem 1.5rem;
        text-align: center;
        background: #fff;
        border-radius: 7px;
        border: 3px solid #eee;
        transition: all .2s ease;
        user-select: none;
    }

    .file-input label:hover {
        border-color: #007bff;
    }

    .file-input label.hover {
        border: 3px solid #007bff;
        box-shadow: inset 0 0 0 6px #eee;
    }

    .file-input #start-logo,
    .file-input #start-image {
        float: left;
        clear: both;
        width: 100%;
    }

    .file-input #start-logo.hidden,
    .file-input #start-image.hidden {
        display: none;
    }

    .file-input i.fa {
        font-size: 50px;
        margin-bottom: 1rem;
        transition: all .2s ease-in-out;
    }

    .file-input .response {
        float: left;
        clear: both;
        width: 100%;
    }

    .file-input .response.hidden {
        display: none;
    }

    .file-input #file-image-logo,
    .file-input #file-image-image {
        display: inline;
        margin: 0 auto .5rem auto;
        width: auto;
        height: 150px;
        max-width: 180px;
    }

    .file-input #file-image-logo.hidden,
    .file-input #file-image-image.hidden {
        display: none;
    }

    .file-input #notimage-logo,
    .file-input #notimage-image {
        display: block;
        float: left;
        clear: both;
        width: 100%;
    }

    .file-input #notimage-logo.hidden,
    .file-input #notimage-image.hidden {
        display: none;
    }

    .file-input progress,
    .file-input .progress {
        display: inline;
        clear: both;
        margin: 0 auto;
        width: 100%;
        max-width: 180px;
        height: 8px;
        border: 0;
        border-radius: 4px;
        background-color: #eee;
        overflow: hidden;
    }

    .file-input .progress[value]::-webkit-progress-bar {
        border-radius: 4px;
        background-color: #eee;
    }

    .file-input .progress[value]::-webkit-progress-value {
        background: linear-gradient(to right, darken(#007bff, 8%) 0%, #007bff 50%);
        border-radius: 4px;
    }

    .file-input .progress[value]::-moz-progress-bar {
        background: linear-gradient(to right, darken(#007bff, 8%) 0%, #007bff 50%);
        border-radius: 4px;
    }

    .file-input input[type="file"] {
        display: none;
    }

    .file-input .btn {
        display: inline-block;
        margin: .5rem .5rem 1rem .5rem;
        clear: both;
        font-family: inherit;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        text-transform: initial;
        border: none;
        border-radius: .2rem;
        outline: none;
        padding: 0 1rem;
        height: 36px;
        line-height: 36px;
        color: #fff;
        transition: all 0.2s ease-in-out;
        box-sizing: border-box;
        background: #007bff;
        border-color: #007bff;
        cursor: pointer;
    }

    /* Additional Styles */
    .container {
        margin-top: 50px;
    }

    .form1 {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .form-label {
        font-weight: bold;
        font-size: larger;
    }

    .btn-container {
        text-align: center;
        margin-top: 20px;
    }

    .error {
        color: red;
        font-size: 0.875em;
    }

    .preview-image {
        max-width: 100px;
        margin-bottom: 10px;
    }

    .fileDiv{
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
</style>
<div class="container">
    <div class="form1">
    <form id="file-upload-form" action="{{ route('institute-course.update', $course_data->finacp_merchant_sub_category_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <h3>Update Course</h3>
    <hr>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-3">
        <label for="course" class="form-label">Course</label>
        <br>
        <mark style="background-color:#fff1a6;">(*Hold down the control (ctrl) button to select multiple options)</mark>
        @php
            $selectedTypes = is_array($course_data->finacp_merchant_sub_category_type) 
                ? $course_data->finacp_merchant_sub_category_type 
                : explode(', ', $course_data->finacp_merchant_sub_category_type);
        @endphp
        <select name="finacp_merchant_sub_category_type[]" id="course" multiple style="width: 100%; padding: 15px;">
            @foreach($allsubCategories as $type)
                <option value="{{ $type }}" {{ in_array($type, $selectedTypes) ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        @if($errors->has('finacp_merchant_sub_category_type'))
            <div class="error" style="color:red">{{ $errors->first('finacp_merchant_sub_category_type') }}</div>
        @endif
    </div>

    <div class="choose col-sm-12 d-flex fileDiv" style="flex-wrap: wrap;">
        <div class="mb-3 w-100 fileDiv">
            <label for="logo" class="form-label">Course Logo</label>
            <br>
            <div class="file-input">
                <label for="file-upload-logo" id="file-drag-logo">
                <img id="file-image-logo" 
                    src="{{ $course_data->finacp_merchant_sub_category_logo 
                        ? route('image', ['path' => $course_data->finacp_merchant_sub_category_logo]) 
                        : asset('images/default-logo.png') }}" 
                    alt="Preview" 
                    class="{{ $course_data->finacp_merchant_sub_category_logo ? '' : 'hidden' }}">

                    <div id="start-logo" class="{{ $course_data->finacp_merchant_sub_category_logo ? 'hidden' : '' }}">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        <div>Select a file or drag here</div>
                        <div id="notimage-logo" class="hidden">Please select an image</div>
                        <span id="file-upload-btn-logo" class="btn">Select a file</span>
                    </div>
                    <div id="response-logo" class="response hidden">
                        <div id="messages-logo"></div>
                        <progress class="progress" id="file-progress-logo" value="0">
                            <span>0</span>%
                        </progress>
                    </div>
                </label>
                <input id="file-upload-logo" type="file" name="finacp_merchant_sub_category_logo" accept="image/*" onchange="document.getElementById('file-image-logo').src = window.URL.createObjectURL(this.files[0]); document.getElementById('file-image-logo').classList.remove('hidden'); document.getElementById('start-logo').classList.add('hidden');">
               
            </div>
        </div>
    </div>

    <div class="btn-container">
        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
    </div>
</form>



    </div>
</div>
<script>
    function imgUpload() {
        function init() {
            var fileSelectLogo = document.getElementById('file-upload-logo'),
                fileDragLogo = document.getElementById('file-drag-logo'),
                fileSelectImage = document.getElementById('file-upload-image'),
                fileDragImage = document.getElementById('file-drag-image');

            fileSelectLogo.addEventListener('change', fileSelectHandlerLogo, false);
            fileDragLogo.addEventListener('dragover', fileDragHoverLogo, false);
            fileDragLogo.addEventListener('dragleave', fileDragHoverLogo, false);
            fileDragLogo.addEventListener('drop', fileSelectHandlerLogo, false);

        }

        function fileDragHoverLogo(e) {
            e.stopPropagation();
            e.preventDefault();
            e.target.className = (e.type === 'dragover' ? 'hover' : 'file-input');
        }

        function fileSelectHandlerLogo(e) {
            var files = e.target.files || e.dataTransfer.files;
            fileDragHoverLogo(e);
            parseFile(files[0], 'logo');
            uploadFile(files[0], 'logo');
        }

        function fileDragHoverImage(e) {
            e.stopPropagation();
            e.preventDefault();
            e.target.className = (e.type === 'dragover' ? 'hover' : 'file-input');
        }

        function fileSelectHandlerImage(e) {
            var files = e.target.files || e.dataTransfer.files;
            fileDragHoverImage(e);
            parseFile(files[0], 'image');
            uploadFile(files[0], 'image');
        }

        function output(msg, target) {
            var messages = target === 'logo' ? 'messages-logo' : 'messages-image';
            document.getElementById(messages).innerHTML = msg;
        }

        function parseFile(file, target) {
            console.log(file.name);
            output('<strong>' + encodeURI(file.name) + '</strong>', target);

        }

        function setProgressMaxValue(e, target) {
            var progressBar = target === 'logo' ? 'file-progress-logo' : 'file-progress-image';
            if (e.lengthComputable) {
                document.getElementById(progressBar).max = e.total;
            }
        }

        function updateFileProgress(e, target) {
            var progressBar = target === 'logo' ? 'file-progress-logo' : 'file-progress-image';
            if (e.lengthComputable) {
                document.getElementById(progressBar).value = e.loaded;
            }
        }

        function uploadFile(file, target) {
            var xhr = new XMLHttpRequest(),
                fileSizeLimit = 1024; // 1MB

            if (xhr.upload) {
                if (file.size <= fileSizeLimit * 1024 * 1024) {
                    var progressBar = target === 'logo' ? 'file-progress-logo' : 'file-progress-image';
                    document.getElementById(progressBar).style.display = 'inline';
                    xhr.upload.addEventListener('loadstart', function(e) { setProgressMaxValue(e, target); }, false);
                    xhr.upload.addEventListener('progress', function(e) { updateFileProgress(e, target); }, false);

                    xhr.onreadystatechange = function(e) {
                        if (xhr.readyState == 4) {
                            // Handle response if needed
                        }
                    };

                    xhr.open('POST', document.getElementById('file-upload-form').action, true);
                    xhr.setRequestHeader('X-File-Name', file.name);
                    xhr.setRequestHeader('X-File-Size', file.size);
                    xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                    xhr.send(file);
                } else {
                    output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).', target);
                }
            }
        }

        if (window.File && window.FileList && window.FileReader) {
            init();
        } else {
            document.getElementById('file-drag-logo').style.display = 'none';
            document.getElementById('file-drag-image').style.display = 'none';
        }
    }

    imgUpload();
</script>
@endsection
