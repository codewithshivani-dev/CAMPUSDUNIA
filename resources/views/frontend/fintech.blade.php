@extends('layouts.homelayout')
@section('title', 'Fintech')
@section('content')
<!-- Slider Start here-->
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.1.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Style -->
<link rel="stylesheet" href="style.css{{ asset('css/style.css') }}">


<body>



<div class="site-blocks-cover">
  <div class="img-wrap">
    <div class="owl-carousel slide-one-item hero-slider">
      <div class="slide">
        <img src="images/fintech_banner_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/fintech_banner_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/fintech_banner_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">Empowering Fintechs</h1>
          </div>
          <div class="text sub-text">
            <p style="color:#000;font-weight:bold;">Connecting fintech, NBFCs, banks, and businesses with tailored solutions to empower payment platforms</p>
            <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- END .site-blocks-cover -->

<!--slider section end here-->
<div id="fintech-main">
<section class="fintech-portfolio-main">

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="solutions-text">
                    SOLUTIONS
                </div>
                <div class="enhance-text">
                Enhance your product portfolio
                </div>
                <div class="enhance-para">
                Leverage our services to provide a diverse <br/>range of financial services and innovative<br/> products to your users
                </div>
            </div>
            <div class="col-sm-6">
                <div class="enhance-img">
                    <img src="images/fintech.png" alt="" width="500px">
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
    #fintech-main .solutions-text
    {
        margin-top:80px;
    }
    #fintech-main .enhance-text
    {
        font-size:45px;
        color:#000;
        font-weight:bold;
        border:0px solid red;
        line-height:60px;
    }
    #fintech-main .enhance-para
    {
        font-size:20px;
        color:#000;
        border:0px solid red;
        margin-top:20px;
    }

</style>
<!---->
<div id="fintech-main">
<section class="development-main">
    <div class="container">

        <div class="row">
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-one.png" alt="">
                    <h3>Credit Card</h3>
                    <h5>With our entire API stack for credit card issuance, built-in security and end-to-end assistance from design to dispatch, issue credit cards within seconds</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-two.png" alt="">
                    <h3>Prepaid Card</h3>
                    <h5>Issue multi-purpose utility cards  such as corporate cards, gift cards, travel cards and meal cards with customization options and better security</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-three.png" alt="">
                    <h3>BNPL</h3>
                    <h5>With our BNPL solution, merchants can provide real-time credit line access to their consumer base, and money lenders can adjust credit limits based on customers' payback patterns</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-four.png" alt="">
                    <h3>Onboarding and KYC</h3>
                    <h5>Reduce your customers’ time for onboarding and KYC process with our user-friendly client onboarding process using AI & ML technology
</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-five.png" alt="">
                    <h3>Neobaking</h3>
                    <h5>Use our customized APIs for saving accounts, credit line, card generation, and other services to maximize your Neobanking ability</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/fintech-six.png" alt="">
                    <h3>Underwriting</h3>
                    <h5>Offer credit lines by leveraging AI and ML technology to evaluate your customer's creditworthiness</h5>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
    #fintech-main .development-main
{
    margin-top:-60px;
    margin-bottom:400px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#fintech-main .development-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#fintech-main .development-text span
{
    color:#fbb800;
}
#fintech-main .development-line
{
    font-size:20px;
    transform:translateX(5px);
}
#fintech-main .one-dev
{
    height: 300px;
    background-color:#f2f2f2;
    padding: 20px;
    margin-top:20px;
    box-shadow:0px opx 0px #001b5b;
    border-radius:20px 0px 20px 0px;
}
#fintech-main .one-dev h3
{
    font-size:20px;
    margin-top:20px;
}
#fintech-main .one-dev h5
{
    font-size:16px;
    font-weight:300;
}
/*Media Query Start*/
@media screen and (max-width: 767px)
{
    .site-blocks-cover h1 {
    font-size: 25px;
    margin-top:390px;
}
.site-blocks-cover .intro .text {
    padding-left: 0;
    font-size: 18px;
}
#fintech-main .enhance-text {
    font-size: 24px;
    color: #000;
    font-weight: bold;
    border: 0px solid red;
    line-height: 60px;
}
#fintech-main .fintech-portfolio-main
{
    margin-top: -150px;
}
.footer-v1 {
    background-color: #201D3C;
    padding: 115px 0 50px 0;
    color: #AEAACB;
    margin-top: 1600px;
}
}
</style>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop