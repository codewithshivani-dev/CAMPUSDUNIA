@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
        <style>
            body {
                background-color: #f8f9fa;
            }

            .container {
                margin-top: 50px;
                margin-bottom: 50px;
            }

            .form1 {
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background-color: #f9f9f9;
            }

            .form-label {
                font-weight: bold;
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
            #userDefined{
                padding-left: 0px;
            }
            .field-container{
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                margin-top: 10px;
                margin-bottom: 10px;
            }
            .common-input1,
            .common-input{
                width: max-content;
            }
            .addFieldButton,
            .removeFieldButton{
                margin-left: 5px;
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
            <form class="form1"  action="{{ route('product.update', $product_details->id) }}" method='POST' enctype="multipart/form-data">
                @csrf
                <h3>Update Course Details</h3>
                    @if ($errors->any())
                    @endif
                <div class="row">
                    <input type="hidden" name="institute_type_id" value="{{ request()->query('institute_type_id') }}">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="course_type" class="form-label">Course type</label>
                            
                            <select name="course_type" id="course_type" class="form-control">
                                <option value="">Course Name</option>
                                @if(!empty($subCategories))
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory }}" {{ $product_details->course_type == $subCategory ? 'selected' : '' }}>{{ $subCategory }}</option>
                    @endforeach
                @endif
                            </select>
                            
                        </div>
                        @if($errors->has('institute_name'))
                            <div class="error" style="color:red">{{ $errors->first('institute_name')}}</div>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="institute_type_name" class="form-label">Sub type</label>
                            <br>
                            <input type="text" value="{{ $product_details->sub_type }}" class="form-control" name="sub_type" id="yearlyfee" />
                        </div>
                        @if($errors->has('affiliation'))
                        <div class="error" style="color:red">{{ $errors->first('affiliation')}}</div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"  value="{{ $product_details->institute_email }}" name="institute_email" class="form-control" id="email" />
                        </div>
                        @if($errors->has('institute_email'))
                        <div class="error" style="color:red">{{ $errors->first('institute_email')}}</div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone no.</label>
                            <input type="text"  value="{{ $product_details->institute_phone_number }}" name="institute_phone_number" class="form-control" id="phone_number" />
                        </div>
                        @if($errors->has('institute_phone_number'))
                        <div class="error" style="color:red">{{ $errors->first('institute_phone_number')}}</div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="Website" class="form-label">Website Url/page</label>
                            <input type="text" value="{{ $product_details->institute_website }}" name="institute_website" class="form-control" id="Website" />
                        </div>
                        @if($errors->has('institute_website'))
                        <div class="error" style="color:red">{{ $errors->first('institute_website')}}</div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="authorized_person" class="form-label">Authorized Person</label>
                            <br>
                            <select id="authorized_person" name="authorized_person" class="form-select">
                                <option value="Select" {{ $product_details->authorized_person == 'Select'? 'selected' : '' }}>--Select--</option>
                                <option value="Director" {{ $product_details->authorized_person == 'Director'? 'selected' : '' }}>Director</option>
                                <option value="Owner" {{ $product_details->authorized_person == 'Owner'? 'selected' : '' }}>Owner</option>
                                <option value="Manager" {{ $product_details->authorized_person == 'Manager'? 'selected' : '' }}>Manager</option>
                            </select>
                        </div>
                        @if($errors->has('authorized_person'))
                            <div class="error" style="color:red">{{ $errors->first('authorized_person')}}</div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Mode of education</label>
                            <select id="course_name" name="mode_of_education" class="form-select">
                                <option value="">--Select--</option>
                                <option value="Online" {{ $product_details->mode_of_education == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Offline" {{ $product_details->mode_of_education == 'Offline' ? 'selected' : '' }}>Offline</option>
                                <option value="Hybrid" {{ $product_details->mode_of_education == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                        @if($errors->has('mode_of_education'))
                            <div class="error" style="color:red">{{ $errors->first('mode_of_education')}}</div>
                        @endif
                    </div>
                    
                    <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="duration" class="form-label">Fee Duration</label>
                        <select id="duration" name="fee_duration" class="form-select">
                            <option value="Select" {{ $product_details->fee_duration == 'Select' ? 'selected' : '' }} name="fee_duration" vale="">--Select--</option>
                            <option value="Hourly" {{ $product_details->fee_duration == 'Hourly' ? 'selected' : '' }} name="fee_duration" value="Hourly">Hourly</option>
                            <option value="Weekly" {{ $product_details->fee_duration == 'Weekly' ? 'selected' : '' }} name="fee_duration" value="Weekly">Weekly</option>
                            <option value="Monthly" {{ $product_details->fee_duration == 'Monthly' ? 'selected' : '' }} name="fee_duration" value="Monthly">Monthly</option>
                            <option value="Quarterly" {{ $product_details->fee_duration == 'Quarterly' ? 'selected' : '' }} name="fee_duration" value="Quarterly">Quarterly</option>
                            <option value="Half yearly" {{ $product_details->fee_duration == 'Half yearly' ? 'selected' : '' }} name="fee_duration" value="Half-yearly">Half yearly</option>
                            <option value="Yearly" {{ $product_details->fee_duration == 'Yearly' ? 'selected' : '' }} name="fee_duration" value="Yearly">Yearly</option>
                        </select>
                    </div>  
                </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="Duration_type" class="form-label">Course Duration</label>
                            <input
                            type="number"
                            class="form-control"
                            value="{{ $product_details->course_duration }}"
                            name="course_duration"
                            id="Duration_type"
                            oninput="createInputFields()"
                            />
                            <div id="input-container"></div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="feeTypes" class="form-label">Fee category</label>
                        <div class="mb-3" id="feeTypesContainer">
                            <div class="form-check" id="hostelFee">
                                <input
                            
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="hostelFeeCheckbox"
                                />
                                <label class="form-check-label" for="hostelFeeCheckbox">
                                Hostel fee
                                <span id="hostelFeeCheckbox-duration"></span>
                                </label>
                                <input
                                name="hostel_fee"
                                type="text"
                                value="{{ $product_details->hostel_fee }}"
                                class="form-control fee-input"
                                id="hostelFeeInput"
                                style="display: none"
                                />
                            </div>
                            <div class="form-check" id="transportationFee">
                                <input               
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="transportationFeeCheckbox"
                                />
                                <label class="form-check-label" for="transportationFeeCheckbox">
                                Transportation fee
                                <span id="transportationFeeCheckbox-duration"></span>
                                </label>
                                <input
                                name="transportation_fee"
                                type="text"
                                value= "{{ $product_details->hostel_fee }}";
                                class="form-control fee-input"
                                id="transportationFeeInput"
                                style="display: none"
                                />
                            </div>
                            <div class="form-check" id="registrationFee">
                                <input
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="registrationFeeCheckbox"
                                />
                                <label class="form-check-label" for="registrationFeeCheckbox">
                                Registration fee
                                <!-- <span id="registrationFeeCheckbox-duration"></span> -->
                                </label>
                                <input                
                                name="registration_fee"
                                type="text"
                                value= "{{ $product_details->registration_fee }}";
                                class="form-control fee-input"
                                id="registrationFeeInput"
                                style="display: none"
                                />
                            </div>
                            <div class="form-check" id="miscellaneousFee">
                                <input
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="miscellaneousFeeCheckbox"
                                />
                                <label class="form-check-label" for="miscellaneousFeeCheckbox">
                                Miscellaneous fee
                                <span id="miscellaneousFeeCheckbox-duration"></span>
                                </label>
                                <input
                                name="miscellaneous_fee"
                                value= "{{ $product_details->miscellaneous_fee }}";
                                type="text"
                                class="form-control fee-input"
                                id="miscellaneousFeeInput"
                                style="display: none"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="total_amount_fees" class="form-label">Total fee</label>
                            <input type="text"  name="total_fee"  value= "{{ $product_details->total_fee }}" class="form-control" id="total_amount_fees" />
                        </div>
                    </div>

                    <div class="col-sm-12" id="additionalFieldsContainer">
                        <div class="form-check" id="userDefined">
                            <b>User-Defined Field</b>
                            <form id="dynamicForm">
                                <div id="fieldsContainer">
                                    <!-- Fields will be added here -->
                                </div>
                                <!-- <button type="submit">Submit</button> -->
                            </form>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <label
                                    for="course_start_date"
                                    class="form-label"
                                >Course Start Date</label
                                >
                                <input
                                type="date"
                                name="course_start_date"
                                value= "{{ $product_details->course_start_date }}"
                                class="form-control"
                                id="course_start_date"
                                />
                            </div>
                            <div>
                                <label
                                    for="course_end_date"
                                    class="form-label"
                                >Course End Date</label
                                >
                                <input
                                type="date"
                                name="course_end_date"
                                value= "{{ $product_details->course_end_date }}"
                                class="form-control"
                                id="course_end_date"
                                />
                            </div>
                        </div>
                    </div>

                
                    <div class="btn-container col-sm-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script>
            let fieldCount = 0;
            const maxFields = 5;
            let formData = [];

            function initialize() {
                const backendData = {!! json_encode($product_details->userdefined_column_fee) !!};
                let parsedData;
                try {
                    parsedData = JSON.parse(backendData);
                } catch (error) {
                    console.error('Error parsing backend data:', error);
                    return;
                }
                
                if (typeof parsedData === 'object' && Object.keys(parsedData).length > 0) {
                    populateFields(parsedData);
                    Object.keys(parsedData).forEach(key => {
                        console.log(`${key}: ${parsedData[key]}`);
                    });
                } else {
                    fieldCount = 1;
                    addField(fieldCount);
                }
                calculateTotalFee();
            }

            function populateFields(data) {
                Object.keys(data).forEach((key, index) => {
                    if (index < maxFields) {
                        addField(index + 1);
                        document.getElementById('key' + (index + 1)).value = key;
                        document.getElementById('value' + (index + 1)).value = data[key];
                        updateValueLabel(index + 1); // Update valueLabel after setting key and value
                        updateFormData(index + 1);
                    }
                });

                const fieldsContainer = document.getElementById("fieldsContainer");
                const additionalFieldsContainer = document.getElementById("additionalFieldsContainer");
                additionalFieldsContainer.appendChild(fieldsContainer);
            }

            function addField(index) {
                if (fieldCount < maxFields) {
                    const fieldsContainer = document.getElementById("fieldsContainer");
                    const fieldContainer = document.createElement("div");
                    fieldContainer.className = "field-container";
                    fieldContainer.id = 'fieldContainer' + index;

                    // Create key input
                    const keyInput = document.createElement("input");
                    keyInput.type = "text";
                    keyInput.id = 'key' + index;
                    keyInput.name = 'key' + index;
                    keyInput.placeholder = "Enter key";
                    keyInput.className = "form-control common-input1";
                    keyInput.addEventListener("input", function() {
                        console.log('Key input changed:', keyInput.value);
                        updateValueLabel(index);
                        updateFormData(index);
                    });


                    // Create value label
                    const valueLabel = document.createElement("label");
                    valueLabel.htmlFor = 'value' + index;
                    valueLabel.id = 'valueLabel' + index;
                    valueLabel.innerText = "Value:";
                    valueLabel.className = "common-label";

                    // Create value input
                    const valueInput = document.createElement("input");
                    valueInput.type = "text";
                    valueInput.id = 'value' + index;
                    valueInput.name = 'value' + index;
                    valueInput.placeholder = "Enter value";
                    valueInput.className = "form-control common-input";
                    valueInput.addEventListener("input", function() {
                        updateFormData(index);
                        calculateTotalFee();
                    });
                    console.log('keyInput id:', 'key' + index);
                    console.log('valueLabel id:', 'valueLabel' + index);


                    // Create button group with remove and add buttons
                    const buttonGroup = document.createElement("div");
                    buttonGroup.className = "button-group";
                    const removeButton = document.createElement("button");
                    removeButton.type = "button";
                    removeButton.className = "btn btn-outline-danger removeFieldButton";
                    removeButton.innerHTML = '<i class="fas fa-trash-alt"></i> Delete';
                    removeButton.addEventListener("click", function() {
                        removeField(index);
                    });

                    const addFieldButton = document.createElement("button");
                    addFieldButton.type = "button";
                    addFieldButton.innerHTML = '<i class="fas fa-plus"></i> Add';
                    addFieldButton.id = 'addFieldButton' + index;
                    addFieldButton.className = "btn btn-outline-primary addFieldButton";
                    addFieldButton.addEventListener("click", function() {
                        if (fieldCount < maxFields) {
                            addField(++fieldCount);
                        } else {
                            console.log("Max fields reached. Cannot add more.");
                        }
                    });

                    buttonGroup.appendChild(addFieldButton);
                    buttonGroup.appendChild(removeButton);
                    fieldContainer.appendChild(keyInput);
                    fieldContainer.appendChild(valueLabel);
                    fieldContainer.appendChild(valueInput);
                    fieldContainer.appendChild(buttonGroup);
                    fieldsContainer.appendChild(fieldContainer);

                    fieldCount++;

                    // Initialize form data for the new field
                    formData.push({
                        key: "",
                        value: ""
                    });
                } else {
                    console.log("Max fields reached. Cannot add more.");
                }
            }

            function updateValueLabel(index) {
                const keyInput = document.getElementById('key' + index);
                const valueInput = document.getElementById('value' + index);
                const valueLabel = document.getElementById('valueLabel' + index);

                if (keyInput && valueLabel) {
                    valueLabel.innerText = keyInput.value ? keyInput.value + ":" : "Value:";
                }
            }

            function updateFormData(index) {
                const keyInput = document.getElementById('key' + index);
                const valueInput = document.getElementById('value' + index);
                if (keyInput && valueInput) {
                    formData[index - 1] = {
                        key: keyInput.value,
                        value: valueInput.value
                    };
                }
            }

            function removeField(index) {
                if (fieldCount > 1) {
                    const fieldContainer = document.getElementById('fieldContainer' + index);
                    if (fieldContainer) {
                        fieldContainer.remove();
                        formData.splice(index - 1, 1);
                        fieldCount--;
                    }
                } else {
                    Toastify({
                        text: "At least one field must be present.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                }
            }

            function calculateTotalFee() {
                let total = 0;
                let hostelFee = document.getElementById("hostelFeeInput").value;
                let transportationFee = document.getElementById("transportationFeeInput").value;
                let registrationFee = document.getElementById("registrationFeeInput").value;
                let miscellaneousFee = document.getElementById("miscellaneousFeeInput").value;

                if (hostelFee !== "") {
                    total += parseFloat(hostelFee);
                }
                if (transportationFee !== "") {
                    total += parseFloat(transportationFee);
                }
                if (registrationFee !== "") {
                    total += parseFloat(registrationFee);
                }
                if (miscellaneousFee !== "") {
                    total += parseFloat(miscellaneousFee);
                }

                let courseFeeInputs = document.querySelectorAll("#input-container input[type='text']");
                courseFeeInputs.forEach(function (input) {
                    if (input.value !== "") {
                        total += parseFloat(input.value);
                    }
                });

                document.getElementById("total_amount_fees").value = total.toFixed(2);
            }

            // Initialize the fields on page load
            window.onload = initialize;

            document.addEventListener("DOMContentLoaded", function () {
                var feeTypes = [
                    {
                        id: "hostelFeeCheckbox",
                        inputId: "hostelFeeInput",
                        durationId: "hostelFeeCheckbox-duration",
                    },
                    {
                        id: "transportationFeeCheckbox",
                        inputId: "transportationFeeInput",
                        durationId: "transportationFeeCheckbox-duration",
                    },
                    {
                        id: "registrationFeeCheckbox",
                        inputId: "registrationFeeInput",
                        durationId: "registrationFeeCheckbox-duration",
                    },
                    {
                        id: "miscellaneousFeeCheckbox",
                        inputId: "miscellaneousFeeInput",
                        durationId: "miscellaneousFeeCheckbox-duration",
                    },
                ];

                var durationSelect = document.getElementById("duration");
                var durationType = document.getElementById("Duration_type");

                durationSelect.addEventListener("change", function () {
                    var selectedDuration = durationSelect.value;
                    feeTypes.forEach(function (feeType) {
                        var durationSpan = document.getElementById(feeType.durationId);
                        if (durationSpan) {
                            durationSpan.textContent = "";
                        }
                        var input = document.getElementById(feeType.inputId);
                        input.disabled = selectedDuration === "Hourly";
                        if (selectedDuration === "Hourly") {
                            input.value = "";
                        }
                    });
                    if (selectedDuration === "Yearly") {
                        durationType.value = "Yearly (" + selectedDuration + ")";
                    } else if (!durationType.getAttribute("data-user-set")) {
                        durationType.value = "";
                    }
                });

                feeTypes.forEach(function (feeType) {
                    var checkbox = document.getElementById(feeType.id);
                    var input = document.getElementById(feeType.inputId);
                    checkbox.addEventListener("change", function () {
                        input.style.display = checkbox.checked ? "block" : "none";
                        calculateTotalFee();
                        if (checkbox.id === "courseFeeCheckbox" && checkbox.checked) {
                            durationType.value = "Yearly";
                            durationType.setAttribute("data-user-set", "true");
                        }
                    });

                    input.addEventListener("input", function () {
                        calculateTotalFee();
                    });
                });

                durationType.addEventListener("input", function () {
                    durationType.setAttribute("data-user-set", "true");
                });
            });

            function createInputFields() {
                let container = document.getElementById("input-container");
                container.innerHTML = "";
                let Duration = document.getElementById("duration").value;
                let Duration_type = document.getElementById("Duration_type").value;
                if (!Duration || !Duration_type) {
                    return;
                }

                let totalFees = parseInt(document.getElementById("total_fees").value, 10);
                let numFields = Math.ceil(totalFees / Duration);

                for (let i = 0; i < numFields; i++) {
                    let label = document.createElement("label");
                    let input = document.createElement("input");
                    input.type = "text";
                    input.className = "form-control";
                    input.placeholder = "Enter fee for " + (Duration * (i + 1)) + " " + Duration_type;
                    input.id = Duration_type + '-' + (i + 1);
                    input.name = Duration_type + '-' + (i + 1);
                    input.addEventListener("input", function () {
                        calculateTotalFee();
                    });
                    label.htmlFor = Duration_type + '-' + (i + 1);
                    label.textContent = (Duration * (i + 1)) + " " + Duration_type;
                    container.appendChild(label);
                    container.appendChild(input);
                }
            }

            function setSameFee() {
                let isChecked = document.getElementById("sameFeeCheckbox").checked;
                let container = document.getElementById("input-container");
                let inputs = container.querySelectorAll("input[type='text']");
                if (isChecked && inputs.length > 0) {
                    let firstValue = inputs[0].value;
                    inputs.forEach(function (input) {
                        input.value = firstValue;
                        input.disabled = true;
                    });
                } else {
                    inputs.forEach(function (input) {
                        input.disabled = false;
                    });
                }
                calculateTotalFee();
            }
        </script>
    </body>
</html>
@endsection