@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-canceled {
            color: red;
        }
        .status-complete {
            color: green;
        }
        .mainDiv{
            position: relative;
        }
        .childContent{
            position: absolute;
            color: #000;
            top: 65%;
            left: 7%;
        }
        .child1 {
            background: linear-gradient(90deg, #4B3F72, #F6C667);
            padding: 40px 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            color: white;
            /* height: 200px; */
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: -50px;
            border: 5px solid white;
        }
    </style>
</head>
<body>
    
<div class="mainDiv1">
        <div class="mainDiv" style="height: 270px;">
            <div class="child1" style="height: 200px;">
                <div class="childContent">
                    <img src="/images/allen-logo.webp" alt="Profile Image" class="profile-image">
                    <h3>{{$fincapMerchants->fincap_merchant_name}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Users</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User Hash ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>                       
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody id="CourseDisplayTable">
                @foreach($users_data as $users_data_array)
                    <tr>
                       <td>{{ $users_data_array->fincap_partner_user_id }}</td>
                        <td>{{ $users_data_array->first_name }} {{ $users_data_array->middle_name }} {{ $users_data_array->last_name }}</td>
                        <td>{{ $users_data_array->email }}</td>
                        <td>{{ $users_data_array->mobile_number }}</td>
                        <td>{{ $users_data_array->gender }}</td>
                        <td>{{ $users_data_array->date_of_birth }}</td>                 
                        <td>{{ $users_data_array->father_name }}</td>
                        <td>{{ $users_data_array->mother_name }}</td>
                        <td>{{ $users_data_array->status}}</td>
                        <td>{{ $users_data_array->created_at }}</td>
                        <td>{{ $users_data_array->updated_at }}</td>             
                    </tr>
                @endforeach  
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>
@endsection