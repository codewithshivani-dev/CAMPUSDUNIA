@extends('layouts.homelayout')
@section('title', 'Artificial Intelligence')
@section('content')
<!-- Slider Start here-->
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.1.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Style -->
<link rel="stylesheet" href="style.css{{ asset('css/style.css') }}">

<title>Carousel #10</title>
</head>
<body>


<div class="content">

<div class="container">
  
</div>

<div class="site-blocks-cover">
  <div class="img-wrap">
    <div class="owl-carousel slide-one-item hero-slider">
      <div class="slide">
        <img src="public/images/hero_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/hero_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/hero_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">About Us</h1>
          </div>
          <div class="text sub-text">
            <p style="color:#000;font-weight:bold;">We Develop Customized Websites For Your Precise Business Needs</p>
            <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 
</div>
<!-- END .site-blocks-cover -->
<!--about us section start-->
<section class="about-us-main">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      <div class="about-us-heading">
            About Us
          </div>
        <div class="about-us-text">
         
        Codenxt is a next level IT Service Company which offers web design, mobile app development, digital marketing along with IT support on cloud computing, Data security & maintenance. We utilize the latest technology like Artificial Intelligence, Machine Learning, and Block Chain to deliver high quality and result-oriented IT solutions for our domestic & global clientele. We believe in doing the creative things in innovative way and helps in shaping your Ideas in reality through technology
        </div>
      </div>
      <div class="col-sm-6">
        <div class="about-us-img">

        </div>
      </div>      
    </div>
  </div>
</section>
<!--about us section end-->
<!--Our Mission section start here-->
<section class="about-us-main">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      <div class="mission-img">

      </div>
      </div>
      <div class="col-sm-6">
      <div class="about-us-heading">
            Our Mission
          </div>
        <div class="about-us-text">
         
        We are committed to offer 360 degree IT service and support to all our highly valued clients and boosting their business growth through implementing best technology .
        </div>
      </div>      
    </div>
  </div>
</section>
<!--Our Mission section ends-->

<style>
  .about-us-heading
  {
    font-size:35px;
    color:#000;
    font-weight:bold;
    margin-top:20px;
  }
  .about-us-text
  {
    height:auto;
    width:100%;
    border:0px solid red;
    margin-top:20px;  
  }
  .about-us-img
  {
    background-image:url('public/images/about-image.png');
    height:400px;
    background-size:cover;
  }
  .mission-img
  {
    background-image:url('public/images/mission.png');
    height:400px;
    background-size:cover;
    border:0px solid red;
  }
  /*Media Query Start*/
@media screen and (max-width: 767px)
{
  .site-blocks-cover h1 {
    font-size: 25px;
}
.site-blocks-cover .intro .text {
    padding-left: 0;
    font-size:20px;
}
.site-blocks-cover .intro .heading {
    margin-left: 0;
    margin-top: 400px;
}
.about-us-main
{
  margin-top:-100px;
}
.about-us-heading {
    font-size: 25px;
}
.about-us-img {
    background-image: url(public/images/about-image.png);
    height: 292px;
    width: 381px;
    background-size: contain;
    background-repeat: no-repeat;
}
.mission-img {
    background-image: url(public/images/mission.png);
    height: 292px;
    width: 381px;
    background-size: contain;
    background-repeat: no-repeat;
    margin-top: 250px;
}
}
</style>
<!--about us section start-->
<section class="about-us-main">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      <div class="about-us-heading">
            Our Vision
          </div>
        <div class="about-us-text">
         
        To provide highly efficient technology services to our domestic and Global clients for their IT need with best solutions to achieve their business goals. We are strived to provide our clients with services that build value and deliver the all-important competitive advantage.
        </div>
      </div>
      <div class="col-sm-6">
        <div class="about-us-img">

        </div>
      </div>      
    </div>
  </div>
</section>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop