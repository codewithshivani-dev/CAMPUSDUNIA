@extends('layouts.homelayout')
@section('title', 'Mobile Development')
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
        <img src="public/images/mobile_banner_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/mobile_banner_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="public/images/mobile_banner_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="mobile-development" style="">Mobile Development</h1>
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

<!-- Portfolio Section Start here-->
<div id="mobile-development-all">
<section style="margin-top:40px;margin-bottom:20px;border:0px solid red;height:600px;padding-top:0px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="portfolio-text">
                    Our <span>Mobile Development Portfolio</span>
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
                    <div class="portfolio-one-heading">
                        Mobile Development
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
                    <div class="portfolio-one-heading">
                        Mobile Development
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
                    <div class="portfolio-one-heading">
                        Mobile Development
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
                    <div class="portfolio-one-heading">
                        Mobile Development
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
                    <div class="portfolio-one-heading">
                        Mobile Development
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
                     <div class="portfolio-one-heading">
                        Mobile Development
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#mobile-development-all .portfolio-text
{
    text-align:center;
    border:0px solid red;   
    font-size:40px;
    color:#000;
    font-weight:bold;
}
#mobile-development-all .portfolio-text span
{
    color:#fbb800;
}
#mobile-development-all .portfolio-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    perspective:300px;
    overflow:hidden;
    
}
#mobile-development-all .portfolio-imgone
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-imgtwo
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-imgthree
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-imgfour
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-imgfive
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-imgsix
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('public/images/portfolio_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#mobile-development-all .portfolio-two
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
#mobile-development-all .portfolio-one-heading
{
  height:20%;
  width:100%;
  background-color:#000;
  position: absolute;
  top:80%;
  font-weight:bold;
  color:#fff;
  padding-top:5px;
  text-align:center;
  transform:rotateX(0deg);
  transform-origin:bottom;
  transition:0.5s ease-in-out;
  opacity:0.9;
  font-size:18px;
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgone
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgtwo
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgthree
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgfour
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgfive
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-imgsix
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#mobile-development-all .portfolio-main:hover
.portfolio-two 
{
transform:rotate(0deg);
perspective:300px;
opacity: 0.9;
}
#mobile-development-all .portfolio-main:hover
.portfolio-one-heading 
{
transform:rotateX(90deg);
perspective:500px;
opacity: 0
}
</style>
<!--Portfolio Section End here-->
<!-- Why choose us Section start here-->
<div id="mobile-development-main">
<section class="why-choose-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            <div class="why-choose-text">Why Choose CodeNxt For Your <span>Mobile Development Solutions?</span></div>
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
                    <img src="public/images/scalability.png" alt="" width="50px;">
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
                    <img src="public/images/responsive.png" alt="" width="70px;">
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
                    <img src="public/images/loading.png" alt="" width="60px;">
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
                    <img src="public/images/cyber.png" alt="" width="70px;">
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
#mobile-development-main .why-choose-main
{
    margin-top:20px;
    margin-bottom:40px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#mobile-development-main .why-choose-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#mobile-development-main .why-choose-text span
{
    color:#fbb800;
}
#mobile-development-main .why-choose-line
{
    font-size:20px;
    transform:translateX(5px);
}
#mobile-development-main .choose-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    overflow:hidden;
}
#mobile-development-main .choose-onecolor
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
#mobile-development-main .choose-twocolor
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
#mobile-development-main .choose-two
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
#mobile-development-main .choose-main:hover
.choose-two
{
    top:0%;
}
</style>
<!--Why choose us Section end here-->
<!--Complete feature section start here-->
<div id="mobile-development-main">
<section class="mobile-main-section" style="border:0px solid red;padding-top:0px;">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
            <div class="why-choose-text" style="border:0px solid red;margin-top:0px;">A Complete Feature-Suite To <span>Build Your Custom Mobile Apps</span></div>
            <div class="why-choose-line">We offer end-to-end features to drive engagement & revenue for your brand</div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <div class="mobile-main">
          <div id="mobile1">
            
          </div>
          <div id="mobile2">
            
          </div>
          <div id="mobile3">
            
          </div>
          <div id="mobile4">
            
          </div>
          <div id="mobile5">
            
          </div>
          <div id="mobile6">
            
          </div>
          <div id="mobile7">
            
          </div>
          <div id="mobile8">
            
          </div>
          <div id="mobile9">
            
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="mobile-icons-main">
          <div class="row">
            <div class="col-sm-4">
              <div id="mobileicon1" onMouseover="mobileonein()">
                <img src="public/images/mobile-dev-1.png" alt="" width="80px">
                <span>Multiple Payment</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon2" onMouseover="mobiletwoin()">
              <img src="public/images/mobile-dev-2.png" alt="" width="80px">
                <span>Advanced Analytics</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon3" onMouseover="mobilethreein()">
              <img src="public/images/mobile-dev-3.png" alt="" width="80px">
               <span> Mobile Friendly</span>
              </div>
            </div>  
            <div class="col-sm-4">
              <div id="mobileicon4" onMouseover="mobilefourin()">
              <img src="public/images/mobile-dev-4.png" alt="" width="80px">
                <span>Multi-Language</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon5" onMouseover="mobilefivein()">
              <img src="public/images/mobile-dev-5.png" alt="" width="80px">
                <span>Discounts & Promo</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon6" onMouseover="mobilesixin()">
              <img src="public/images/mobile-dev-6.png" alt="" width="80px">
                <span>Reviews & Ratings</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon7" onMouseover="mobilefourin()">
              <img src="public/images/mobile-dev-7.png" alt="" width="80px">
                <span>Geo &nbsp; Location</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon8" onMouseover="mobileeightin()">
              <img src="public/images/mobile-dev-8.png" alt="" width="80px">
                <sapn>Push Notification</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div id="mobileicon9" onMouseover="mobileninein()">
              <img src="public/images/mobile-dev-9.png" alt="" width="80px">
               <span> Integrated Chat System</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
<style>
 #mobile-development-main  .mobile-main
  {
    border:0px solid red;
    position: relative;
    height:700px;
    width:350px;
    left:20px;
    top:50px;
  }
  #mobile-development-main  #mobile1
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_1.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:1;
  }
 
  #mobile-development-main #mobile2
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_2.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile3
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_3.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile4
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_4.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile5
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_5.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile6
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_6.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile7
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_7.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile8
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_8.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main #mobile9
  {
    position:absolute;
    border:0px solid green;
    height:100%;
    width:100%;
    background-image:url('public/images/mobile_9.png');
    background-size:contain;
    background-repeat:no-repeat;
    transition:0.5s ease-in-out;
    opacity:0;
  }
  #mobile-development-main .mobile-icons-main
  {
    position: relative;
    border:0px solid red;
    top:50px;
    height:550px;
    margin-top:80px;
  }
  #mobile-development-main .mobile-icons-main
  {
    padding:50px;

  }
  #mobile-development-main #mobileicon1
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: -40px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }

  #mobile-development-main #mobileicon2
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #fbb800;
    border-radius: 10px;
    margin-top: -40px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon3
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: -40px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon4
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #fbb800;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon5
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon6
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #fbb800;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon7
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon8
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #fbb800;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  #mobile-development-main #mobileicon9
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  
/*Media Query Start*/
@media screen and (max-width: 767px)
{
  #mobile-development-main .mobile-development
  {
    font-size: 34px;
    color:#001b5b;
    font-weight:bold;
  }
  .intro
  {
    margin-top: 400px;
  }
  .site-blocks-cover h1 
  {
    font-size: 2rem;
  }
  #mobile-development-main .portfolio-text 
  {
    text-align: center;
    border: 0px solid red;
    font-size: 25px;
    color: #000;
    font-weight: bold;
  } 
  #mobile-development-main .why-choose-main 
  {
    margin-top: 810px;
  }
  #mobile-development-main .why-choose-text
  {
    border: 0px solid red;
    font-size: 24px;
  }
  #mobile-development-main .mobile-main-section
  {
    margin-top: 680px;
  }
  .footer-v1 
  {
    background-color: #201D3C;
    padding: 115px 0 50px 0;
    color: #AEAACB;
    margin-top: 800px;
  }
  #mobile-development-main #mobileicon2 
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 72px;
  }
  #mobile-development-main #mobileicon2 
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #fbb800;
    border-radius: 10px;
    margin-top: 20px;
  }
  #mobile-development-main #mobileicon3 
  {
    border: 0px solid red;
    text-align: center;
    padding: 20px 10px 20px 10px;
    margin-bottom: 10px;
    background-color: #001b5b;
    border-radius: 10px;
    margin-top: 20px;
  }
}


</style>
<script>
function mobileonein()
{
  document.getElementById("mobile1").style.opacity="1";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobiletwoin()
{
  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="1";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobilethreein()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="1";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
  
}
function mobilefourin()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="1";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobilefivein()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="1";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobilesixin()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="1";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobilesevenin()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="1";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="0";
}
function mobileeightin()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="1";
  document.getElementById("mobile9").style.opacity="0";
}
function mobileninein()
{

  document.getElementById("mobile1").style.opacity="0";
  document.getElementById("mobile2").style.opacity="0";
  document.getElementById("mobile3").style.opacity="0";
  document.getElementById("mobile4").style.opacity="0";
  document.getElementById("mobile5").style.opacity="0";
  document.getElementById("mobile6").style.opacity="0";
  document.getElementById("mobile7").style.opacity="0";
  document.getElementById("mobile8").style.opacity="0";
  document.getElementById("mobile9").style.opacity="1";
}
</script>
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop