@extends('layouts.homelayout')
@section('title', 'Blockchain')
@section('content')
<!-- Slider Start here-->
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.1.css') }}">

<link rel="stylesheet" href="{{ asset('css/animate.css') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Style -->
<link rel="stylesheet" href="style.css{{ asset('css/style.css') }}">


<div id="blockchain">


<div class="site-blocks-cover">
  <div class="img-wrap">
    <div class="owl-carousel slide-one-item hero-slider">
      <div class="slide">
        <img src="images/blockchain_1.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/blockchain_2.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
      <div class="slide">
        <img src="images/blockchain_3.jpg" alt="Free Website Template by Free-Template.co">  
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6 ml-auto align-self-center">
        <div class="intro">
          <div class="heading">
            <h1 class="font-weight-bold" style="color:#001b5b;font-weight:bold;">Blockchain</h1>
          </div>
          <div class="text sub-text">
            <p style="color:#000;font-weight:bold;">Create a decentralised environment for your brand to improve security and transparency</p>
            <p><a href="" target="_blank" class="btn btn-outline-primary btn-md btn-pill" style="color:#fff;background-color:#112a66;font-weight:bold;">Start a project</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- END .site-blocks-cover -->
</div>
<!--slider section ends here-->
<!-- Portfolio Section Start here-->
<div id="blockchain">
<section style="margin-top:40px;margin-bottom:20px;border:0px solid red;height:600px;padding-top:0px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="portfolio-text">
                    Our <span>Blockchain Portfolio</span>
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
#blockchain .portfolio-text
{
    text-align:center;
    border:0px solid red;   
    font-size:40px;
    color:#000;
    font-weight:bold;
}
#blockchain .portfolio-text span
{
    color:#fbb800;
}
#blockchain .portfolio-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    perspective:300px;
    overflow:hidden;
    
}
#blockchain .portfolio-imgone
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-imgtwo
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-imgthree
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-imgfour
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_2.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-imgfive
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_3.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-imgsix
{
    position: absolute;
    height:100%;
    width:100%;
    background-image:url('images/block_1.jpg');
    background-size:cover;
    transition:0.5s ease-in-out;

}
#blockchain .portfolio-two
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
#blockchain .portfolio-main:hover
 .portfolio-imgone
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-imgtwo
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-imgthree
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-imgfour
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-imgfive
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-imgsix
{
    transform:rotate(10deg) scale(1.2, 1.4);
}
#blockchain .portfolio-main:hover
 .portfolio-two 
{
transform:rotate(0deg);
perspective:300px;
opacity: 0.9;
}
</style>
<!--Portfolio Section End here-->
<!-- Why choose us Section start here-->
<div id="blockchain">


<section class="why-choose-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            <div class="why-choose-text">Why Choose CodeNxt For Your <span>Blockchain Solutions?</span></div>
            <div class="why-choose-line">We ensure web solutions that work flawlessly across multiple devices</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-onecolor">
                    Enhanced Security
                    </div>
                    <div class="choose-two">
                    Prevent fraud and unlawful behaviour by producing a record that cannot be altered and is encrypted end-to-end. 
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-twocolor">
                    Greater Transparency
                    </div>
                    <div class="choose-two">
                    Transactions are immutably documented and time and date stamped, allowing members to see the whole transaction history.
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-onecolor">
                    Instant Traceability
                    </div>
                    <div class="choose-two">
                    Track and exchange data regarding provenance directly with customers using blockchain.
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="choose-main">
                    <div class="choose-twocolor">
                    Increased Efficiency
                    </div>
                    <div class="choose-two">
                    Transactions are conducted faster and more efficiently by automating the process with blockchain.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#blockchain .why-choose-main
{
    margin-top:20px;
    margin-bottom:40px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#blockchain .why-choose-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#blockchain .why-choose-text span
{
    color:#fbb800;
}
#blockchain .why-choose-line
{
    font-size:20px;
    transform:translateX(5px);
}
#blockchain .choose-main
{
    position: relative;
    height:200px;
    border:0px solid red;
    margin-top:20px;
    overflow:hidden;
}
#blockchain .choose-onecolor
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
#blockchain .choose-twocolor
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
#blockchain .choose-two
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
#blockchain .choose-main:hover
 .choose-two
{
    top:0%;
}
</style>
<div id="blockchain">


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
                    <img src="images/block_chain_one.png" alt="">
                    <h3>Financial Service</h3>
                    <h5>Reduce friction and delays by enhancing operational efficiency in global commerce, trade finance, consumer banking, lending, and other activities.</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/block_chain_two.png" alt="">
                    <h3>Supply & Food Chain</h3>
                    <h5>Build confidence among trade partners by offering end-to-end visibility, optimising operations, and resolving difficulties more quickly.</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/block_chain_three.png" alt="">
                    <h3>Real Estate</h3>
                    <h5>Reduce transaction costs and time by implementing smart contracts and track purchase payments with greater transparency.</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/block_chain_four.png" alt="">
                    <h3>Travel & Transportation</h3>
                    <h5>Reduce check-in time, track payments, and track luggage movement by utilising blockchain's decentralised database system.</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/block_chain_five.png" alt="">
                    <h3>Healthcare</h3>
                    <h5>Improve patient data security by making it simpler to communicate records across physicians, payers, and researchers.</h5>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="one-dev">
                    <img src="images/block_chain_six.png" alt="">
                    <h3>Insurance</h3>
                    <h5>Increase speed and efficiency by automating manual and paper-intensive procedures like underwriting and claims settlement.</h5>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<style>
#blockchain .development-main
{
    margin-top:20px;
    margin-bottom:400px;
    border:0px solid red;
    height:350px;
    padding-top:0px;
}
#blockchain .development-text
{
   
    border:0px solid red;   
    font-size:35px;
    color:#000;
    font-weight:bold;
}
#blockchain .development-text span
{
    color:#fbb800;
}
#blockchain .development-line
{
    font-size:20px;
    transform:translateX(5px);
}
#blockchain .one-dev
{
    height:250px;
    background-color:#f2f2f2;
    padding: 20px;
    margin-top:20px;
    box-shadow:0px opx 0px #001b5b;
    border-radius:20px 0px 20px 0px;
}
#blockchain .one-dev h3
{
    font-size:20px;
    margin-top:20px;
}
#blockchain .one-dev h5
{
    font-size:16px;
    font-weight:300;
}
/*Media Query Start*/
@media screen and (max-width: 767px)
{
    .site-blocks-cover h1 {
    font-size: 2rem;
    margin-top:380px;
}
#blockchain .portfolio-text {
    text-align: center;
    border: 0px solid red;
    font-size: 25px;
    color: #000;
    font-weight: bold;
}
#blockchain .why-choose-main {
    margin-top: 770px;
    margin-bottom: 40px;
    border: 0px solid red;
    height: 350px;
    padding-top: 0px;
}
#blockchain .why-choose-text {
    border: 0px solid red;
    font-size: 25px;
    color: #000;
    font-weight: bold;
}
#blockchain .development-main {
    margin-top: 680px;
    margin-bottom: 400px;
    border: 0px solid red;
    height: 350px;
    padding-top: 0px;
}
#blockchain .development-text {
    border: 0px solid red;
    font-size: 24px;
    color: #000;
    font-weight: bold;
}
.footer-v1 {
    margin-top: 1475px;
}

}
</style>
<!--Why choose us Section start here-->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<!-- Slider end here-->
@stop