@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <style>
      body {
        background-color: #f8f9fa;
      }

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
          font-size: 1.1rem;
      }

      .error {
          color: red;
          font-size: 0.875em;
      }

      .next {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
      }

      .btn-container {
        width: 100%;
        text-align: center;
        margin-top: 20px;
      }

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
      select{
        width: 100%;
        padding: 6px;
      }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div>
                    <!-- <img src="/image/institute.png" alt="Institute Image" style="width: 100%"> -->
                </div>
            </div>
        </div>
        <form class="form1" action="{{ route('fincap.merchant.update', $fincapMerchant->fincap_merchant_id) }}" method='POST' enctype="multipart/form-data">
            @csrf
            <h3>Institute Details</h3>
            @if ($errors->any())
            @endif
            <div class="row">
                <input type="hidden" name="institute_type_id" value="{{ request()->query('institute_type_id') }}">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="institute_name" value="{{$fincapMerchant->fincap_merchant_name}}" class="form-control" id="name" />
                    </div>
                    @if($errors->has('institute_name'))
                    <div class="error" style="color:red">{{ $errors->first('institute_name')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="institute_type_name" class="form-label">Affiliation</label>
                        <br>
                        <select id="institute_type_name" class="form-select" name="affiliation">
                            <option value="">--Select--</option>
                            <option value="CBSE" {{ old('affiliation') == 'CBSE' ? 'selected' : '' }}>CBSE</option>
                            <option value="ICSE" {{ old('affiliation') == 'ICSE' ? 'selected' : '' }}>ICSE</option>
                            <option value="State Board" {{ old('affiliation') == 'State Board' ? 'selected' : '' }}>State Board</option>
                        </select>
                    </div>
                    @if($errors->has('affiliation'))
                    <div class="error" style="color:red">{{ $errors->first('affiliation')}}</div>
                    @endif
                </div>
                <div class="col-sm-12 d-flex p-0">
                  <div class="mb-3 col-sm-6">
                  <label for="image" class="form-label">Image</label>
                  <div class="file-input w-100">
                          <br>
                          <label for="file-upload-image" id="file-drag-image">
                              <img id="file-image-image"
                    src="{{ $fincapMerchant->fincap_merchant_images
                        ? route('image', ['path' => $fincapMerchant->fincap_merchant_images])
                        : asset('images/default-logo.png') }}"
                    alt="Preview"
                    class="{{ $fincapMerchant->fincap_merchant_images ? '' : 'hidden' }}">

                              <div id="start-image" class="{{ $fincapMerchant->fincap_merchant_images}}">
                              <i class="fa fa-upload" aria-hidden="true"></i>
                                  <div>Select a file or drag here</div>
                                  <div id="notimage-image" class="hidden">Please select an image</div>
                                  <span id="file-upload-btn-image" class="btn">Select a file</span>
                              </div>
                              <div id="response-image" class="response hidden">
                                  <div id="messages-image"></div>
                                  <progress class="progress" id="file-progress-image" value="0">
                                      <span>0</span>%
                                  </progress>
                              </div>
                          </label>
                          <input type="file" id="file-upload-image"  name="fincap_merchant_images" accept="image/*" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])" />
                      </div>
                      @if($errors->has('fincap_merchant_images'))
                          <div class="error" style="color:red">{{ $errors->first('fincap_merchant_images')}}</div>
                      @endif
                  </div>
                  <div class="mb-3 col-sm-6">
                  <label for="logo" class="form-label">Logo</label>
                  <div class="file-input w-100">
                          <br>
                          <label for="file-upload-logo" id="file-drag-logo">
                              <img id="file-image-logo"
                    src="{{ $fincapMerchant->fincap_merchant_logo_image
                        ? route('image', ['path' => $fincapMerchant->fincap_merchant_logo_image])
                        : asset('images/default-logo.png') }}"
                    alt="Preview"
                    class="{{ $fincapMerchant->fincap_merchant_logo_image ? '' : 'hidden' }}">

                              <div id="start-logo">
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
                          <input type="file" id="file-upload-logo" value="{{$fincapMerchant->fincap_merchant_logo_image}}" name="fincap_merchant_logo_image" accept="image/*" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])" />
                      </div>
                      @if($errors->has('fincap_merchant_logo_image'))
                          <div class="error" style="color:red">{{ $errors->first('fincap_merchant_logo_image')}}</div>
                      @endif
                  </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" value="{{$fincapMerchant->fincap_merchant_email}}" name="fincap_merchant_email" class="form-control" id="email" />
                    </div>
                    @if($errors->has('fincap_merchant_email'))
                    <div class="error" style="color:red">{{ $errors->first('fincap_merchant_email')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone no.</label>
                        <input type="text" value="{{$fincapMerchant->fincap_merchant_contact_number}}" name="fincap_merchant_contact_number" class="form-control" id="phone_number" />
                    </div>
                    @if($errors->has('fincap_merchant_contact_number'))
                    <div class="error" style="color:red">{{ $errors->first('fincap_merchant_contact_number')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="Website" class="form-label">Website Url/page</label>
                        <input type="text" value="{{$fincapMerchant->fincap_merchant_website}}" name="fincap_merchant_website" class="form-control" id="Website" />
                    </div>
                    @if($errors->has('fincap_merchant_website'))
                    <div class="error" style="color:red">{{ $errors->first('instifincap_merchant_websitetute_website')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="authorized_person" class="form-label">Authorized Person</label>
                        <br>
                        <select id="authorized_person" value="{{$fincapMerchant->authorized_person}}" name="authorized_person" class="form-select">
                            <option value="Select" {{ old('authorized_person') == 'Select' ? 'selected' : '' }}>--Select--</option>
                            <option value="Director" {{ old('authorized_person') == 'Director' ? 'selected' : '' }}>Director</option>
                            <option value="Owner" {{ old('authorized_person') == 'Owner' ? 'selected' : '' }}>Owner</option>
                            <option value="Manager" {{ old('authorized_person') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    @if($errors->has('authorized_person'))
                    <div class="error" style="color:red">{{ $errors->first('authorized_person')}}</div>
                    @endif
                </div>
                <div class="col-sm-12">
                    <h3>Address</h3>
                </div>
                <div class="col-sm-12">
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <br>
                        <input type="text" value="{{$fincapMerchant->fincap_merchant_state}}" name="fincap_merchant_state" class="form-control" id="city" />
                    </div>
                    @if($errors->has('fincap_merchant_state'))
                    <div class="error" style="color:red">{{ $errors->first('fincap_merchant_state')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" value="{{$fincapMerchant->fincap_merchant_city}}" name="fincap_merchant_city" class="form-control" id="city" />
                    </div>
                    @if($errors->has('fincap_merchant_city'))
                    <div class="error" style="color:red">{{ $errors->first('fincap_merchant_city')}}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="pin_code" class="form-label">Pin code</label>
                        <input type="text" value="{{$fincapMerchant->fincap_merchant_pincode}}" name="fincap_merchant_pincode" class="form-control" id="pin_code" />
                    </div>
                    @if($errors->has('fincap_merchant_pincode'))
                    <div class="error" style="color:red">{{ $errors->first('fincap_merchant_pincode')}}</div>
                    @endif
                </div>
                <div class="btn-container col-sm-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
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

            fileSelectImage.addEventListener('change', fileSelectHandlerImage, false);
            fileDragImage.addEventListener('dragover', fileDragHoverImage, false);
            fileDragImage.addEventListener('dragleave', fileDragHoverImage, false);
            fileDragImage.addEventListener('drop', fileSelectHandlerImage, false);
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

            var isGood = /\.(?=gif|jpg|png|jpeg)/gi.test(file.name);
            if (isGood) {
                document.getElementById('start-' + target).classList.add("hidden");
                document.getElementById('response-' + target).classList.remove("hidden");
                document.getElementById('notimage-' + target).classList.add("hidden");
                document.getElementById('file-image-' + target).classList.remove("hidden");
                document.getElementById('file-image-' + target).src = URL.createObjectURL(file);
            } else {
                document.getElementById('file-image-' + target).classList.add("hidden");
                document.getElementById('notimage-' + target).classList.remove("hidden");
                document.getElementById('start-' + target).classList.remove("hidden");
                document.getElementById('response-' + target).classList.add("hidden");
                document.getElementById("file-upload-form").reset();
            }
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

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
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
  </body>
</html>
@endsection
