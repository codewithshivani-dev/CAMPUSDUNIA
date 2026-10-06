@extends('layouts.homelayout')
@section('title', 'Web Development')
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
        <img src="images/web_banner_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/hero_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/web_banner_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">Web Development</h1>
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

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->

<!-- Portfolio Section Start here-->
<div id="web-development-main">
<section style="margin-top:40px;margin-bottom:20px;border:0px solid red;height:600px;padding-top:0px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="portfolio-text">
                    Our <span>Web Development Portfolio</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgone">
                       
                    </div>
                    <div class="portfolio-two">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgtwo">

                    </div>
                    <div class="portfolio-two">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgthree">

                    </div>
                    <div class="portfolio-two">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfour">
                       
                    </div>
                    <div class="portfolio-two">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfive">

                    </div>
                    <div class="portfolio-two">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgsix">

                    </div>
                    <div class="portfolio-two">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, dignissimos.
                        <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#web-development-main .portfolio-text
{
    text-align:center;
    border:0px solid red;   
    font-size:40px;
    color:#000;
    font-weight:bold;
}
#web-development-main .portfolio-text span
{
    color:#fbb800;
}
#web-development-main .portfolio-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    perspective:300px;
    overflow:hidden;
    
}
#web-development-main .portfolio-imgone
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-imgtwo
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-imgthree
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-imgfour
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-imgfive
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-imgsix
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/hero_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#web-development-main .portfolio-two
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
    padding-top:20px;
}
#web-development-main .portfolio-main:hover
.portfolio-imgone
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-imgtwo
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-imgthree
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-imgfour
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-imgfive
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-imgsix
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#web-development-main .portfolio-main:hover
.portfolio-two 
{
transform:rotate(0deg);
perspective:300px;
opacity: 0.9;
}
</style>
<!--Portfolio Section End here-->

<!-- Why choose us Section start here-->
<div id="web-development-main">
<section class="why-choose-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            <div class="why-choose-text">Why Choose CodeNxt For Your <span>Web Development Solutions?</span></div>
            <div class="why-choose-line">We ensure web solutions that work flawlessly across multiple devices</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="choose-main">
                    
                    <div class="choose-onecolor">
                        Robust & Scalable
                    </div>
                    <div style="position:absolute;top:30%;left:48%;transform:translate(-50%, -50%);">
                    <img src="images/scalability.png" alt="" width="50px;">
                    </div>
                    <div class="choose-two">
                    Fully functional and scalable<br> solution that grows with your business
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-twocolor">
                        Highly Responsive
                    </div>
                    <div style="position:absolute;top:30%;left:48%;transform:translate(-50%, -50%);">
                    <img src="images/responsive.png" alt="" width="70px;">
                    </div>
                    <div class="choose-two">
                    Web solutions that work well on mobile, tablet, and desktop devices
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-onecolor">
                        Quick Loading
                    </div>
                    <div style="position:absolute;top:30%;left:48%;transform:translate(-50%, -50%);">
                    <img src="images/loading.png" alt="" width="60px;">
                    </div>
                    <div class="choose-two">
                    We offer a minimalistic setup to ensure your website load faster
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-twocolor">
                        Secure Solutions
                    </div>
                    <div style="position:absolute;top:30%;left:48%;transform:translate(-50%, -50%);">
                    <img src="images/cyber.png" alt="" width="70px;">
                    </div>
                    <div class="choose-two">
                    Highly secure websites to with <br>stand high traffic without any glitches
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#web-development-main .why-choose-main
{
    margin-top:20px;
    margin-bottom:40px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#web-development-main .why-choose-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#web-development-main .why-choose-text span
{
    color:#fbb800;
}
#web-development-main .why-choose-line
{
    font-size:20px;
    transform:translateX(5px);
}
#web-development-main .choose-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    overflow:hidden;
}
#web-development-main .choose-onecolor
{
    position: absolute;
    height:100%;
    width:100%;
    background-color:#001b5b;
    color:white;
    font-size:18px;
    text-align:center;
    padding-top:100px;
    font-weight:bold;
}
#web-development-main .choose-twocolor
{
    position: absolute;
    height:100%;
    width:100%;
    background-color:#fbb800;
    color:white;
    font-size:18px;
    text-align:center;
    padding-top:100px;
    font-weight:bold;
}
#web-development-main .choose-two
{
    position: absolute;
    height:100%;
    width:100%;
    top:100%;
    background-color:#000;
    transition:0.5s ease-in-out;
    color:white;
    font-size:16px;
    text-align:center;
    padding-top:50px;
}
#web-development-main .choose-main:hover
.choose-two
{
    top:0%;
}
</style>
<div id="web-development-main">
<section class="development-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="development-text">Website Development To Take Your <span>Business To The Next Level</span></span></div>
                <div class="development-line">We provide a complete range of services to turn your great ideas into profitable business solutions</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_one.png" alt="">
                    <h3>Web Portal Development</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_two.png" alt="">
                    <h3>Custom Web Development</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_three.png" alt="">
                    <h3>E-Commerce Development</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_four.png" alt="">
                    <h3>CMS Web Development</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_five.png" alt="">
                    <h3>Enterprise Web Development</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/web_six.png" alt="">
                    <h3>Support & Maintaince</h3>
                    <h5>Customer-focused web portals with all the necessary functionalities to help expand your business digitally</h5>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
    #web-development-main .development-main
{
    margin-top:20px;
    margin-bottom:400px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#web-development-main .development-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#web-development-main .development-text span
{
    color:#fbb800;
}
#web-development-main .development-line
{
    font-size:20px;
    transform:translateX(5px);
}
#web-development-main .one-dev
{
    height:250px;
    background-color:#f2f2f2;
    padding: 20px;
    margin-top:20px;
    box-shadow:0px opx 0px #001b5b;
    border-radius:20px 0px 20px 0px;
}
#web-development-main .one-dev h3
{
    font-size:20px;
    margin-top:20px;
}
#web-development-main .one-dev h5
{
    font-size:16px;
    font-weight:300;
}
</style>
<style>
/*Media Query Start*/
@media screen and (max-width: 767px)
{
    #web-development-main .heading h1
    {
        margin-top:200px;
    }
    #web-development-main .why-choose-text
    {
        margin-top: 40px;
        font-size: 25px;
    }
    .footer-v1
    {
        margin-top: 1600px;
    }
    #web-development-main .why-choose-main
    {
        margin-top: 950px;
    }
    #web-development-main .portfolio-text
    {
        font-size: 25px;
    }
    #web-development-main .why-choose-text
    {
        margin-top: -90px;
        font-size: 25px;
    }
    #web-development-main .development-main 
    {
    margin-top: 680px;
    }
    #web-development-main .development-text
    {
        font-size:25px;
    }
    .site-blocks-cover h1 
    {
    font-size: 2rem;
    margin-top: 390px;
    }

}
</style>
<!-- Why choose us Section End here-->
@stop