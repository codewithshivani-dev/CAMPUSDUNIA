@extends('layouts.homelayout')
@section('title', 'Cloud Computing')
@section('content')
<!-- Slider Start here-->
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.1.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Style -->
<link rel="stylesheet" href="style.css{{ asset('css/style.css') }}">

<div id="cloud-computing">

    <div class="site-blocks-cover">
    <div class="img-wrap">
        <div class="owl-carousel slide-one-item hero-slider">
        <div class="slide">
            <img src="images/hero_4.jpg" alt="Free Website Template by Free-Template.co">  
        </div>
        <div class="slide">
            <img src="images/hero_5.jpg" alt="Free Website Template by Free-Template.co">  
        </div>
        <div class="slide">
            <img src="images/hero_6.jpg" alt="Free Website Template by Free-Template.co">  
        </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
        <div class="col-md-6 ml-auto align-self-center">
            <div class="intro">
            <div class="heading">
                <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">Cloud Computing</h1>
            </div>
            <div class="text sub-text">
                <p style="color:#000;font-weight:bold;">We Develop Customized Websites For Your Precise Business Needs</p>
                <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div> <!-- END .site-blocks-cover -->
    <section class="en-common-page cloud-btmpad-0">
        <div class="container">
            <div class="entr__heading text-center">
                <span>// Cloud Computing Information</span>
                <h2>Cloud Computing</h2>
                <hr class="under-line-hd">
            </div>
            <div class="cloud-centre-part">
                <h1 class="cld-hd-text">Secure Centralized Solutions to Enhance Mobility and Productivity</h1>
                <p>We develop cloud-native, industry-specific business platforms to enhance productivity, inclusivity, and innovativeness of the workforce and our partners. Our cloud computing services enables customers to scale up rapidly while reducing service usage. Behind the scenes, our services leverage virtualization, automation, and other cloud management technologies to facilitate service agility.</p>
            </div>
        </div>
            <div class="cloud-bg-c-img"><img src="images/cloud-computing-banner-1.png" alt="" width="100%"></div>
            <h3 class="text-center cld-text-hd">What we available</h3>
        <div class="container">
            <div class="cloud-box" style="margin-top: 60px;">
                <div class="row">
                    <div class="col-sm-3">
                    <div class="cld-inner-box">
                    <p>
                    <img src="images/cloud-1.png">
                    </p>
                    <h5>Build Cloud</h5>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    </div>
                    <div class="col-sm-3">
                    <div class="cld-inner-box">
                    <p>
                    <img src="images/cloud-2.png">
                    </p>
                    <h5>Manage Cloud</h5>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                    </div>
                    </div>
                    <div class="col-sm-3">
                    <div class="cld-inner-box">
                    <p>
                    <img src="images/cloud-3.png">
                    </p>
                    <h5>Use Cloud</h5>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    </div>
                    <div class="col-sm-3">
                    <div class="cld-inner-box">
                    <p>
                    <img src="images/cloud-4.png"></p>
                    <p>
                    <h5>Cloud Consulting Services</h5>
                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cloud-block-section">
            <h3 class="cld-type-hd">Types of Cloud</h3>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                    <div class="cld-inner-block">
                    <div class="cld-inn-img">
                    <img src="images/banner-3.png">
                    <h5>CLOUD</h5>
                    </div>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    </div> 
                    <div class="col-sm-4">
                    <div class="cld-inner-block">
                    <div class="cld-inn-img">
                    <img src="images/banner-2.png">
                    <h5>PRIVATE CLOUD</h5>
                    </div>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    </div> 
                    <div class="col-sm-4">
                    <div class="cld-inner-block">
                    <div class="cld-inn-img">
                    <img src="images/banner-3.png">
                    <h5>HYBRID CLOUD</h5>
                    </div>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    </div> 
                </div>   
            </div>   
        </div>
    </section>
</div>
<style type="text/css">
#cloud-computing .center-display {
    display: flex;
    align-items: center;
    justify-content: center;
}
#cloud-computing .cld-hd-text{
    font-size: 18px;
    font-weight: 600;
}
#cloud-computing .under-line-hd{
    width: 15%;
    margin: 0 auto;
    padding-top: 15px;
    border: 0;
    border-bottom: 2px solid #7141b1;
}
#cloud-computing .en-common-hd {
    width: 100%;
    min-height: 350px;
    color: #43baff;
    font-weight: 500;
    background: #262051 center center no-repeat;
    background-image: url(images/cloud.jpg);
    background-size: cover;
}
#cloud-computing .en-common-hd .cont-title {
    color: #fff;
    margin-bottom: 0;
    flex: 1;
    padding: 10px 20px 10px 0;
}
#cloud-computing h1 {
    font-size: 48px;
}
#cloud-computing .none-style {
    list-style: none;
    padding-left: 0;
}
#cloud-computing .en-common-hd .breadcrumbs {
    margin-bottom: 0;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 800;
}
#cloud-computing .en-common-hd .breadcrumbs li {
    display: inline-block;
    color: #fff;
}
#cloud-computing .en-common-hd .breadcrumbs li a {
    color: #fff;
}
#cloud-computing section {
    padding-top: 120px;
    padding-bottom: 130px;
    position: relative;
}
#cloud-computing .entr-en-common-pg {
    margin-right: 95px;
}
#cloud-computing .entr__heading {
    margin-bottom: 15px;
}
#cloud-computing .entr__heading > span {
    font-size: 14px;
    font-weight: 800;
    color: #7141b1;
    position: relative;
    display: inline-block;
    margin-bottom: 2px;
    text-transform: uppercase;
}
#cloud-computing .entr__heading h2 {
    margin-bottom: 0;
    line-height: 48px;
}
#cloud-computing .cloud-centre-part{
    padding-top: 50px;
}
#cloud-computing .cld-text-hd{
    position: relative;
    top: 40px;
}
#cloud-computing .cld-inner-box h5{font-weight: 600;}
#cloud-computing .cld-inner-box{
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    min-height: 200px;
    padding: 20px 40px 30px;
    background: #f5f5f5;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    -webkit-transition: all .4s ease-in-out;
    -moz-transition: all .4s ease-in-out;
    -o-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;-webkit-box-shadow: 0 15px 55px -5px rgba(9,31,67,.1);
    -moz-box-shadow: 0 5px 10px -10px rgba(9,31,67,.1);
    box-shadow: 0 5px 10px -10px rgba(9,31,67,.1);
}
#cloud-computing .cld-inner-box:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: #7141b1;
    transform-origin: bottom center;
    -moz-transform: scaleY(0);
    -ms-transform: scaleY(0);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transition: all .3s linear 0s;
    -moz-transition: all .3s linear 0s;
    -ms-transition: all .3s linear 0s;
    -o-transition: all .3s linear 0s;
    transition: all .3s linear 0s;
}
#cloud-computing .cld-inner-box:hover {
    -moz-transform: translateY(-5px);
    -o-transform: translateY(-5px);
    -ms-transform: translateY(-5px);
    -webkit-transform: translateY(-5px);
    transform: translateY(-5px);
}
#cloud-computing .cld-inner-box:hover:after {
    -moz-transform: scaleY(1);
    -ms-transform: scaleY(1);
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
}
#cloud-computing .cloud-block-section{
    margin-top: 30px;
    background: #f5f5f5;
}
#cloud-computing .cld-inner-block{
    box-shadow: 0px 0px 27px 0px rgba(0,0,0,0.09);
    transition: 400ms all;
    background: #fff;
}
#cloud-computing .cld-inner-block:hover {
    transform: scale(1.04);
}
#cloud-computing .cld-inn-img{position: relative;}
#cloud-computing .cld-inn-img img{
   height: 270px;
}
#cloud-computing .cld-inn-img h5{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-weight: 600;
    display: none;
}
#cloud-computing .cld-inner-block:hover .cld-inn-img h5{
    display:block;
}
#cloud-computing .cld-inner-block p{
    padding: 20px;
}
#cloud-computing .cld-type-hd{
   text-align: center;
    padding: 50px 0;
}
#cloud-computing .cloud-block-section{padding-bottom: 60px;}
#cloud-computing .cloud-btmpad-0{padding-bottom: 0px !important;}
</style>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop