@extends('layouts.homelayout')
@section('title', 'Cloud Computing')
@section('content')
<div class="container card-0 justify-content-center ">
        <div class="card-body px-sm-4 px-0">
            <div class="row justify-content-center mb-5">
                <div class="col-md-10 col"><h3 class="font-weight-bold ml-md-0 mx-auto text-center text-sm-left"> Request a Quote </h3><!--<p class="mt-md-4  ml-md-0 ml-2 text-center text-sm-left"> Entrust with heigh professionalism we are offering pixel perfect web and mobile application development third party integration and solution to our.</p>--></div>
            </div>
            <div class="row justify-content-center round">
                <div class="col-lg-10 col-md-12 ">
                    <div class="card shadow-lg card-1">
                        <div class="card-body inner-card">
                            <div class="row justify-content-center">
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <div class="form-group"><label for="first-name">First Name</label><input type="text" class="form-control" id="first-name" placeholder="Type your Name"> </div>
                                    <div class="form-group"> <label for="Mobile-Number">Mobile Number</label> <input type="text" class="form-control" id="Mobile-Number" placeholder=""> </div>
                                    <div class="form-group"> <label for="inputEmail4">Project Type</label> <select class="form-control"><option>Web Design</option><option>Blockchain</option> <option>ML</option></select> </div>
                                    <div class="form-group"> <label for="time">Maximum time for the project</label> <input type="text" class="form-control" id="time" placeholder="">  </div>
                                    <div class="form-group"><label for="skill">Required Skills</label> <input type="text" class="form-control" id="skill" placeholder=""> </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <div class="form-group"> <label for="last-name">Last Name</label> <input type="text" class="form-control" id="last-name" placeholder=""> </div>
                                    <div class="form-group"> <label for="phone">Email</label>  <input type="email" class="form-control" id="email" placeholder=""> </div>
                                    <div class="form-group"> <label for="Evaluate Budget">Evaluate Budget</label> <input type="text" class="form-control" id="Evaluate Budget" placeholder=""> </div>
                                    <div class="form-group"> <label for="Company-Name">Company Name</label> <input type="text" class="form-control" id="Company-Name" placeholder=""> </div>
                                    <div class="form-group"> <label for="inputEmail4">Country</label> <select class="form-control"> <option>India</option><option>China</option> <option>UK</option> </select></div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-12 col-lg-10 col-12"> <div class="form-group files"><label class="my-auto">Upload Your File </label> <input id="file" type="file" class="form-control" /></div></div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-12 col-lg-10 col-12"><div class="form-group"> <label for="exampleFormControlTextarea2">Message</label> <textarea class="form-control rounded-0" id="exampleFormControlTextarea2" rows="5"></textarea></div>
                                    <div class="row justify-content-end mb-5">
                                        <div class="col-lg-4 col-auto "><button type="button" class="btn btn-warning btn-block"><small class="font-weight-bold">Request a Quote</small></button> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>


p {
    font-size: 15px;
    line-height: 25px !important;
    font-weight: 500;


}
   

.btn {
    letter-spacing: 1px;
}

select:active{
    box-shadow: none !important;
    outline-width: 0 !important;

}
select:after{
    box-shadow: none !important;
    outline-width: 0 !important;

}
input,
textarea {
    padding: 10px 12px 10px 12px;
    border: 1px solid lightgrey;
    border-radius: 0px !important;
    margin-bottom: 5px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px;
    resize: none;

}

select:focus,
input:focus {
    box-shadow: none !important;
    border: 1px solid #2196F3 !important;
    outline-width: 0 !important;
    font-weight: 400
}

label {
    margin-bottom: 2px;
    font-weight: bolder;
    font-size: 14px;
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #304FFE;
    outline-width: 0
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

.form-control {
    height: calc(2em + .75rem + 3px);
}

.inner-card {
    margin: 79px 0px 70px 0px;
}

.card-0 {
    margin-top: 100px;
    margin-bottom: 100px;
}

.card-1 {
    border-radius: 17px;
    color: black;
    box-shadow: 2px 4px 15px 0px rgb(0, 0, 0 , 0.5) !important;
}

#file {
    border: 2px dashed #92b0b3 !important;

}

.color input {
    background-color: #f1f1f1;
}

.files:before {
    position: absolute;
    bottom: 60px;
    left: 0;
    width: 100%;
    content: attr(data-before);
    color: #000;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}


#file {
    display: inline-block;
    width: 100%;
    padding: 95px 0 0 100%;
    background: url('https://i.imgur.com/VXWKoBD.png') top center no-repeat #fff;
    background-size: 55px 55px;
}

</style>
<script>
  $(document).ready(function(){
        $(".files").attr('data-before',"Drag file here or click the above button");
        $('input[type="file"]').change(function(e){
            var fileName = e.target.files[0].name; 
            $(".files").attr('data-before',fileName);
        
        });
    });
</script>
@stop