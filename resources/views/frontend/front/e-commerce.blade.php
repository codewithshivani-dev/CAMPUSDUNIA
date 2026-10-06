@extends('layouts.homelayout')
@section('title', 'E-Commerce')
@section('content')
<!-- Slider Start here-->
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.1.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Style -->
<link rel="stylesheet" href="style.css{{ asset('css/style.css') }}">


<div class="site-blocks-cover">
  <div class="img-wrap">
    <div class="owl-carousel slide-one-item hero-slider">
      <div class="slide">
        <img src="public/images/hero_4.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/hero_5.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/hero_6.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">E-Commerce</h1>
          </div>
          <div class="text sub-text">
            <p style="color:#000;font-weight:bold;">Take your business to the next level</p>
            <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- END .site-blocks-cover -->

<!--slider section end here-->
<div id="e-commerce-main">
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="readymade-stock">
                Readymade Stack
                </div>
                <div class="readymade-para">
                Get a comprehensive suite of e-commerce platforms with mobile compatibility and software integration of third party applications
                </div>
                <div class="btn-main">
                  <button class="btn btn-warning btn-main-text">Know More</button>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="readystackimage">

                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
    #e-commerce-main .readymade-stock
    {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
    }
    #e-commerce-main .readymade-para
    {
        font-size:18px;
        color:gray;
        line-height:40px;
        width:80%;
        padding:10px;
    }
    #e-commerce-main .readystackimage
    {
        background-image:url('public/images/readymade-stack.png');
        height:400px;
        width:100%;
        background-size:contain;
        border:0px solid red;
        background-repeat:no-repeat;
    }
</style>
<div id="e-commerce-main">
<section class="customized-main">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="customized-img">

        </div>
      </div>
      <div class="col-sm-6">
        <div class="customized-text">
        Customized Product
        </div>
        <div class="customized-para">
        Customize your website with additional features and flexible payment options to provide a user-friendly experience for your customers
        </div>
        <div class="btn-main">
          <button class="btn btn-warning btn-main-text">Know More</button>
        </div>
      </div>
    </div>
  </div>
</section>
  </div>
<style>
  #e-commerce-main .customized-img
  {
    height:400px;
    width:100%;
    border:0px solid red;
    background-image:url('public/images/custmized-img.png');
    background-size:contain;
    background-repeat:no-repeat;
  }
  #e-commerce-main .customized-main
  {
    margin-top:-50px;
    padding-top:0px;
    border:0px solid red;
  }
  #e-commerce-main .customized-text
  {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
  }
  #e-commerce-main .customized-para
  {
    font-size:18px;
    color:gray;
    line-height:40px;
    width:80%;
    padding:10px;
  }
  #e-commerce-main .btn-main
  {
    padding:10px;
    font-weight:bold;
  }
  #e-commerce-main .btn-main-text
  {
    font-weight:bold;
    color:#fff;
  }

</style>
<div id="e-commerce-main">
<section style="border:0px solid red;padding-top:0px;">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      <div class="manage-text">
          Track & Manage
        </div>
        <div class="manage-para">
        Manage your business on-the-go and create products, execute orders, and monitor data in real-time
        </div>
        <div class="btn-main">
          <button class="btn btn-warning btn-main-text">Know More</button>
        </div>
      </div>
      <div class="col-sm-6">
      <div class="manage-img">

      </div>
      </div>
    </div>
  </div>
</section>
</div>
<style>
  #e-commerce-main .manage-text
  {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
  }
  #e-commerce-main .manage-para
  {
    font-size:18px;
    color:gray;
    line-height:40px;
    width:80%;
    padding:10px;
  }
  #e-commerce-main .manage-img
  {
    height:400px;
    width:100%;
    border:0px solid green;
    background-image:url('public/images/manage-img.png');
    background-size:contain;
    background-repeat:no-repeat;
  }
</style>
<!-- Portfolio Section Start here-->
<div id="e-commerce-main">
<section class="ecommerce-portfolio" style="">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="portfolio-text">
                    Our <span>E-Commerce Portfolio</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgone">
                       
                    </div>
                    <div class="portfolio-two">
                    Highlight your store and restaurant chain and give your customers online ordering service for your business
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgtwo">

                    </div>
                    <div class="portfolio-two">
                    Lead the generation by giving your customers a virtual store experience and showcase your offering with style
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgthree">

                    </div>
                    <div class="portfolio-two">
                    Give customers an easy way<br/>  to order their healthcare<br/> products with our fast and user-friendly interface
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfour">
                       
                    </div>
                    <div class="portfolio-two">
                    Increase your consumer base with our built-in search engine optimization tool in this competitive market
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfive">

                    </div>
                    <div class="portfolio-two">
                    With our mobile-adaptive web platform service, provide your clients with a wide range of household products
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgsix">

                    </div>
                    <div class="portfolio-two">
                    Offer customers the residential or commercial property of their choice at desired location at an affordable price
                        <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#e-commerce-main .ecommerce-portfolio
  {
    margin-top:-30px;
    margin-bottom:20px;
    border:0px solid red;
    height:600px;
    padding-top:0px;
  }
  #e-commerce-main .portfolio-text
{
    text-align:center;
    border:0px solid red;   
    font-size:40px;
    color:#000;
    font-weight:bold;
}
#e-commerce-main .portfolio-text span
{
    color:#fbb800;
}
#e-commerce-main .portfolio-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    perspective:300px;
    overflow:hidden;
    
}
#e-commerce-main .portfolio-imgone
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-imgtwo
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-imgthree
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-imgfour
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-4.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-imgfive
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-5.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-imgsix
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/e-commerce-6.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#e-commerce-main .portfolio-two
{
    position: absolute;
    height:100%;
    width:60%;
    background-color:#000;
    transform:rotateY(90deg);
    transform-origin:left;
    transition:0.5s ease-in-out;
    opacity: 0;
    color:#fff;
    font-size:16px;
    text-align:center;
    padding-top:7px;
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgone
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgtwo
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgthree
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgfour
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgfive
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-imgsix
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#e-commerce-main .portfolio-main:hover
.portfolio-two 
{
transform:rotate(0deg);
perspective:300px;
opacity: 0.9;
}
</style>
<!--Portfolio Section End here-->
<style>

/*Media Query Start*/
@media screen and (max-width: 767px)
{
  #e-commerce-main .readymade-stock
  {
    margin-top: -100px;
  }
  #e-commerce-main .customized-text
  {
    margin-top: -820px;
  }
  #e-commerce-main .customized-img
  {
    margin-top:254px;
  }
  #e-commerce-main .manage-text
  {
    margin-top:-100px
  }
  #e-commerce-main .portfolio-text
  {
    font-size:25px;
  }
  #e-commerce-main .ecommerce-portfolio 
  {
    margin-top: -200px;
  }
  .footer-v1 
  {
    background-color: #201D3C;
    padding: 115px 0 50px 0;
    color: #AEAACB;
    margin-top: 808px;
  }
  .site-blocks-cover h1 {
    font-size: 25px;
}
.site-blocks-cover .intro .text {
    padding-left: 0;
    font-size: 20px;
}
.site-blocks-cover .intro .heading {
    margin-left: 0;
    margin-top: 390px;
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