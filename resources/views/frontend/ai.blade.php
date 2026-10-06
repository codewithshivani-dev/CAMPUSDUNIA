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



<div class="site-blocks-cover">
  <div class="img-wrap">
    <div class="owl-carousel slide-one-item hero-slider">
      <div class="slide">
        <img src="images/ai_banner_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/ai_banner_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/ai_banner_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="ai-text" style="">ARTIFICIAL INTELLIGENCE</h1>
          </div>
          <div class="text sub-text">
            <p style="color:#000;font-weight:bold;">Solve your biggest AI problems with us.</p>
            <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- END .site-blocks-cover -->
</div>
<!--slider section end here-->
<div id="ai-main">
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="readymade-stock">
                Data Science
                </div>
                <div class="readymade-para">
                Integrate end-to-end Big Data solutions with your system using Data Sciences technologies such as Artificial Intelligence, Machine Learning, and Deep Learning
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
    #ai-main .readymade-stock
    {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
    }
    #ai-main .readymade-para
    {
        font-size:18px;
        color:gray;
        line-height:40px;
        width:80%;
        padding:10px;
    }
    #ai-main .readystackimage
    {
        background-image:url('images/data-science.png');
        height:400px;
        width:100%;
        background-size:contain;
        border:0px solid red;
        background-repeat:no-repeat;
    }
    #ai-main .ai-text
    {
      color:#001b5b;
      font-weight:bold;
      font-size:70px;
    }
</style>
<div id="ai-main">
<section class="customized-main">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="customized-img">

        </div>
      </div>
      <div class="col-sm-6">
        <div class="customized-text">
        Data Analytics
        </div>
        <div class="customized-para">
        Boost your revenue, enhance operational efficiency, and adapt swiftly to emerging market trends using AI technology
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
  #ai-main .customized-img
  {
    height:400px;
    width:100%;
    border:0px solid red;
    background-image:url('images/data-analytics.png');
    background-size:contain;
    background-repeat:no-repeat;
  }
  #ai-main .customized-main
  {
    margin-top:-50px;
    padding-top:0px;
    border:0px solid red;
  }
  #ai-main .customized-text
  {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
  }
  #ai-main .customized-para
  {
    font-size:18px;
    color:gray;
    line-height:40px;
    width:80%;
    padding:10px;
  }
  #ai-main .btn-main
  {
    padding:10px;
    font-weight:bold;
  }
  #ai-main .btn-main-text
  {
    font-weight:bold;
    color:#fff;
  }

</style>
<div id="ai-main">
<section style="border:0px solid red;padding-top:0px;">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      <div class="manage-text">
            Data Engineering
        </div>
        <div class="manage-para">
        Take informed and efficient strategic decisions by incorporating the company's information into insights
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
  #ai-main .manage-text
  {
        padding:10px;
        border:0px solid red;
        font-size:35px;
        font-weight:bold;
        color:#000;
        margin-top:60px;
  }
  #ai-main .manage-para
  {
    font-size:18px;
    color:gray;
    line-height:40px;
    width:80%;
    padding:10px;
  }
  #ai-main .manage-img
  {
    height:400px;
    width:100%;
    border:0px solid green;
    background-image:url('images/data-engineering.png');
    background-size:contain;
    background-repeat:no-repeat;
  }
</style>
<!-- Portfolio Section Start here-->
<div id="ai-main">
<section class="ai-portfolio-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="portfolio-text">
                    Our <span>AI Solutions</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgone">
                       
                    </div>
                    <div class="portfolio-two">
                    Augment human capabilities and abilities to enhance your customers, workers, and other stakeholders<br/>
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    AI Consulting
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgtwo">

                    </div>
                    <div class="portfolio-two">
                    Improve your customers experiences with AI and Cognitive Care and quickly deploy AI assistants<br/>
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    Conversational AI Services
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgthree">

                    </div>
                    <div class="portfolio-two">
                    Develop a framework to create and implement AI applications and manage the process from pilot to production<br/>
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    AI at Scale
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfour">
                       
                    </div>
                    <div class="portfolio-two">
                    Using end-to-end risk management, make more intelligent decisions and stay current on ever-changing rules<br/>
                         <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    AI for Risk Management
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgfive">

                    </div>
                    <div class="portfolio-two">
                    Transform complex <br/>documents to extract value from unstructured data and improve accuracy using AI<br/>
                    <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    Market Intelligence
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="portfolio-main">
                    <div class="portfolio-imgsix">

                    </div>
                    <div class="portfolio-two">
                    Test and regulate the ethical aspects of artificial intelligence using our experience and frameworks<br/>
                        <button class="btn" style="margin-top:20px;font-weight:bold;background-color:#fbb800;color:#fff;">Know More</button>
                    </div>
                    <div class="portfolio-one-heading">
                    Ethical AI
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
  #ai-main .ai-portfolio-main
  {
    margin-top:-30px;
    margin-bottom:20px;
    border:0px solid red;
    height:600px;
    padding-top:0px;
  }
  #ai-main .portfolio-text
{
    text-align:center;
    border:0px solid red;   
    font-size:40px;
    color:#000;
    font-weight:bold;
}
#ai-main .portfolio-text span
{
    color:#fbb800;
}
#ai-main .portfolio-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    perspective:300px;
    overflow:hidden;
    
}
#ai-main .portfolio-imgone
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-imgtwo
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-imgthree
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-imgfour
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-4.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-imgfive
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-5.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-imgsix
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/e-commerce-6.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#ai-main .portfolio-two
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
#ai-main .portfolio-one-heading
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
#ai-main .portfolio-main:hover
.portfolio-imgone
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-imgtwo
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-imgthree
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-imgfour
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-imgfive
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-imgsix
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#ai-main .portfolio-main:hover
.portfolio-two 
{
transform:rotate(0deg);
perspective:300px;
opacity: 0.9;
}
#ai-main .portfolio-main:hover
.portfolio-one-heading 
{
transform:rotateX(90deg);
perspective:500px;
opacity: 0
}
/*Media Query Start*/
@media screen and (max-width: 767px)
{
  .site-blocks-cover .intro .heading 
  {
    margin-left: 0;
    margin-top: 400px;
  }
  #ai-main .ai-text
  {
    font-size:20px;
  }
  .site-blocks-cover h1
   {
    font-size: 25px;
   }
   #ai-main .readymade-stock 
   {
    margin-top: -100px;
   }
   #ai-main .readymade-para 
   {
    width: 100%;
   }
   #ai-main .customized-text {
   
    margin-top: -700px;
  }
  #ai-main .customized-para 
  {
    width: 100%;
  }
  #ai-main .customized-img {

    margin-top: 120px;
}
#ai-main .manage-text {
    margin-top: -240px;
}
#ai-main .manage-para 
  {
    width: 100%;
  }
  #ai-main .portfolio-text {
    text-align: center;
    border: 0px solid red;
    font-size: 25px;
    color: #000;
    font-weight: bold;
}
#ai-main .ai-portfolio-main {
    margin-top: -250px;
    margin-bottom: 20px;
    border: 0px solid red;
    height: 600px;
    padding-top: 0px;
}
.footer-v1 
{
    margin-top: 800px;
}
}
</style>
<!--Portfolio Section End here-->

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop