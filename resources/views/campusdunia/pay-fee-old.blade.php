@extends('layouts.campusdunialayout')
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For Parents</title>
    <!-- <link rel="stylesheet" href="css/style.css" /> -->
    <!-- <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

      body {
        background: #000;
        /* font-family: "Comfortaa", sans-serif; */
        color: white;
      }

      .hero-section-forparents {
        margin-top: 60px;
        padding: 80px 0px 0px 0px;
        background-color: #000;
        height: auto;
      }
      .hero-title-forparents {
        text-align: center;
        font-size: 55px;
      }
      .highlight-for-parents {
        color: #f39c12;
      }
      .hero-heading-parents {
        width: 80%;
        margin: auto;
      }
      .hero-title {
        color: #fff;
        text-align: center;
        margin-top: 0px;
      }
      .btn-td {
        margin: auto;
      }
      .dashboard-main {
        margin-top: 60px;
      }
      .dashboard-main img {
        -webkit-mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 65%,
          rgba(0, 0, 0, 0) 100%
        );
        mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 65%,
          rgba(0, 0, 0, 0) 100%
        );
      }
      .for-parent-two-main {
        height: auto;
        padding: 80px 20px;
        background-color: #000;
      }
      .streamline-payment-td {
        color: #fff;
        margin: auto;
        width: 80%;
        text-align: center;
        font-size: 35px;
      }
      .text-highlight-td {
        color: #f39c12;
      }
      .para-streamline-payment {
        color: #fff;
        text-align: center;
        margin-top: 25px !important;
        font-size: 18px;
        width: 80%;
        margin: auto;
      }
      .img-for-parents-style {
        margin-top: 100px;
      }
      .customize-fee-plans {
        font-size: 45px;
        margin-top: 180px;
      }
      .customize-fee-plan-para {
        font-size: 20px;
        margin-top: 10px;
      }
      .typewriter {
        font-size: 20px;
        font-weight: 600;
        margin-top:10px;
      }
      .typewrite {
        list-style: none;
        color: #ffa000;
        text-decoration: none;
      }
      .auto-debit-fee-subscription {
        font-size: 40px;
        margin-top: 140px;
      }
      .rewards-for-parents {
        font-size: 40px;
        margin-top: 120px;
      }
      .three-points-for-parents-main {
        padding: 80px 20px;
        background-color: #000;
      }
      .three-points-heading {
        font-size: 25px;
        margin-top: 40px;
      }
      .three-points-para {
        font-size: 16px;
        margin-top: 20px;
      }
      .three-points-icon {
        width: 70px;
      }
      .for-student-hero-content
      {
        line-height:50px;
      }

      .bttn {
        width: fit-content;
        cursor: pointer;
        background:transparent;
        --c: #f39c12;
        color: var(--c);
        font-size: 16px;
        border: 3px solid #f39c12;
        border-radius: 0.5em;
        padding: 8px 30px;
        /* height: 3em; */
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 1px;
        text-align: center;
        /* line-height: 3em; */
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: 0.5s;
        /* margin: 1em; */
      }

      .bttn span {
        position: absolute;
        width: 25%;
        height: 100%;
        background-color: var(--c);
        transform: translateY(150%);
        border-radius: 50%;
        left: calc((var(--n) - 1) * 25%);
        transition: 0.5s;
        transition-delay: calc((var(--n) - 1) * 0.1s);
        z-index: -1;
      }

      .bttn:hover {
        color: black;
        cursor: pointer;
      }

      .bttn:hover span {
        transform: translateY(0) scale(2);
      }

      .bttn span:nth-child(1) {
        --n: 1;
      }

      .bttn span:nth-child(2) {
        --n: 2;
      }

      .bttn span:nth-child(3) {
        --n: 3;
      }

      .bttn span:nth-child(4) {
        --n: 4;
      }
      /* MEDIA QUERY START FROM HERE*/
@media only screen and (max-width: 768px)
{
  .hero-title-forparents {
    text-align: center;
    font-size: 40px;
}
  .hero-heading-parents {
    width: 100%;
    margin: auto;
}
.dashboard-main {
    margin-top: 60px;
    width: 347px;
}
.streamline-payment-td {
    color: #fff;
    margin: auto;
    width: 100%;
    text-align: center;
    font-size: 40px;
}
.para-streamline-payment {
    color: #fff;
    text-align: center;
    margin-top: 25px !important;
    font-size: 16px;
    width: 100%;
    margin: auto;
}
.customize-fee-plans {
    font-size: 40px;
    margin-top: 20px;
}
.customize-fee-plan-para {
    font-size: 16px;
    margin-top: 10px;
}
.typewriter {
    font-size: 32px;
    font-weight: 600;
}
.auto-debit-fee-subscription {
    font-size: 40px;
    margin-top: 90px;
}
.img-for-parents-style {
    margin-top: 50px;
}
.rewards-for-parents {
    font-size: 40px;
    margin-top: 20px;
}
.three-points-icon {
    width: 70px;
    margin-top: 40px;
}
}
.heading-text-institute
{
  text-align:left;
  margin-top:100px;
}
.heading-text-institute span
{
  color:#f39c12;
}
.journey-step-section
{
  padding: 40px 0px 40px 0px;
  background:#000;
}
    </style>
  </head>
  <body>
      <!-- Hero Section -->
    <section class="hero-section-institute" style="padding-top:120px;">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="heading-text-institute">
              <h2 class="for-student-hero-content">
              Empowering Education
Through Smart Financing <br/><span style="font-size:25px;">- Pay Your Fees With Ease!</span>
              </h2>
              <div
                class="bttn bttn-two mt-4 mb-4 btn-hero-section"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal".
              >
                Apply
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
                <section class="journey-step-section text-white text-center">
      <div
        class="container"
        data-aos="fade-up"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
       style="padding:0px;">
        <h2 class="heading" style="margin-bottom: 8px;text-align:left;margin-top:15px;">
          <span>3 Easy</span> steps
        </h2>
        <h5 style="font-size:25px;text-align:left;"><span style="color:#f39c12">To recieve fund for your dream career</span> </h5>
        <div class="row" style="text-align:left;">
          <div
            class="col-md-3 step"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/icon-apply.png" alt="apply" style="margin-bottom: 10px;
    margin-top: 20px;"/>
            <p style="padding-left: 14px;">Apply</p>
          </div>
          <div
            class="col-md-3 step"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/icon-approved.png" alt="approved" style="margin-bottom: 10px;
    margin-top: 20px;">
            <p style="padding-left: 14px;">Approval</p>
          </div>
          <div
            class="col-md-3 step"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/icon-access-funds.png" alt="access-funds" style="margin-bottom: 10px;
    margin-top: 20px;">
            <p style="padding-left: 14px;">Access</p>
          </div>
        </div>
        <!-- <div style="display: flex; justify-content: center; margin-top: 30px">
          <div class="bttn bttn-two">
            Apply Now
            <span></span><span></span><span></span><span></span>
          </div>
        </div> -->
      </div>
    </section>
          </div>
          <div class="col-sm-6">
            <div style="    text-align: center;">
              <img src="image/for-parents-1.jpg" alt="" width="80%" style="-webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 90%, rgba(0, 0, 0, 0) 100%);
mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 90%, rgba(0, 0, 0, 0) 100%);">
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Journey Section -->


<!--BENEFITS SECTION START-->

<style>
  .benefits-section-main
  {
    background:#fff;
    padding: 100px 0px 100px 0px;
  }
 .card-benefits{
    position: relative;
    width: 320px;
    height: 190px;
    margin: 0 auto;
    background: #fff;
    box-shadow: 0 15px 60px rgba(0,0,0, .5);
    border-radius: 15px;
  }
  
  .card-benefits .face{
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background:#000;
    border-radius:15px;
  }
  
  .card-benefits .face.face1{
    box-sizing: border-box;
    padding: 20px;
    color:#000;
  }
  
  .card-benefits .face.face1 h2{
    margin: 0;
    padding: 0;
  }
  
  .card-benefits .face.face1 .content{
    font-size:14px;
    margin:0;
    padding:0 0 1em 0;
    font-weight:500;
    color:#fff;
    text-align:center;
  }
  
  .card-benefits .face.face2{
    background: #111;
    transition: 0.5s;
  }
  
  .card-benefits:nth-child(1) .face.face2{
    background: linear-gradient(90deg, #ff891c,rgb(236, 75, 0));
    border-radius: 15px;
  }
  .card-benefits:hover .face.face2{
    height: 60px;
    border-radius: 15px 15px;
  }
  
  .card-benefits .face.face2:before{
    content:'';
    position: absolute;
    top:0;
    left:0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255, 0.1);
    border-radius: 15px 15px;
  }
  
  .card-benefits .face.face2 h2{
    margin: 0;
    padding: 0;
    font-size: 10em;
    color: #fff;
    transition: 0.5s;
    text-shadow: 0 2px 5px rgba(0,0,0, .2);
  }
  
  .card-benefits:hover .face.face2 h2{
    font-size: 2em;
  }
</style>
<section class="benefits-section-main">
    <h2 class="heading" style="margin-bottom: 8px;text-align:left;margin-top:15px;">
      <span></span>Why consider Campusdunia?
    </h2>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div class="card-benefits">
          <div class="face face1">
            <div class="content"> 
              <p>Save more with affordable rates designed to ease your financial burden. </p>
            </div>
          </div>
          <div class="face face2">
            <h4 style="margin-bottom:0px;">Low Interest Rate</h4>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card-benefits">
          <div class="face face1">
            <div class="content"> 
              <p>Get the funds you need without pledging any assets or security. </p>
            </div>
          </div>
          <div class="face face2">
            <h4 style="margin-bottom:0px;">No Collateral Required</h4>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card-benefits">
          <div class="face face1">
            <div class="content"> 
              <p>Unlock special deals and savings tailored just for students. </p>
            </div>
          </div>
          <div class="face face2">
            <h4 style="margin-bottom:0px;">Exclusive Discounts</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- Hero Section -->
    <!-- <section class="hero-section-forparents">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="hero-heading-parents">
              <h2 class="hero-title">
                <span class="hero-title-forparents">
                  No Financial Barriers, Just Brighter Futures
                  <span class="highlight-for-parents"
                    >– Pay Your Fees with Ease!</span>
                </span>
              </h2>
              <div class="btn-main-td">
                <div class="bttn bttn-two mt-2 mb-4 btn-td">
                  Join Waitlist
                  <span></span><span></span><span></span><span></span>
                </div>
              </div>
              <div class="dashboard-main">
                <img src="image/for-parents.png" alt=""  width="100%"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <section class="for-parent-two-main">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="streamline-payment-td">
              Hassle-Free Payments for Your 
              <span class="text-highlight-td">Dream Education</span>
            </div>
            <div class="para-streamline-payment">
              Financial barriers should never hinder your child's growth and
              education
            </div>
          </div>

            <div class="col-sm-6">
              <div class="auto-debit-fee-subscription">
              <span class="text-highlight-td">Auto-debit</span> subscriptions
              </div>
              <div class="customize-fee-plan-para">
                Never miss a fee payment deadline with our<span
                  style="font-weight: 800"
                >
                  auto-debit facility</span
                >.
              </div>
            <!-- <div class="typewriter">
                    Repay in
                    <span class="theme-color-code">
                        <a href="" class="typewrite theme-color-code" data-period="2000" data-type="[&quot;3,6,9,12&quot;]"><span class="wrap">3,6,9,12</span></a>
                        EMIs
                      </span>
                    </div> -->
          </div>
          <div class="col-sm-6">
            <div class="img-for-parents-style">
              <img src="image/auto-debit-for-parent.png" alt="" width="80%" />
            </div>
          </div>

<div class="col-sm-6">
            <div class="img-for-parents-style">
              <img src="image/rewards-for-parents.png" alt="" width="80%" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="rewards-for-parents"><span class="text-highlight-td">Get Rewards</span> on Payfee</div>
            <div class="customize-fee-plan-para">
              Never miss a fee payment deadline with our<span
                style="font-weight: 800"
              >
                auto-debit facility</span
              >.
            </div>
            <!-- <div class="typewriter">
                    Repay in
                    <span class="theme-color-code">
                        <a href="" class="typewrite theme-color-code" data-period="2000" data-type="[&quot;3,6,9,12&quot;]"><span class="wrap">3,6,9,12</span></a>
                        EMIs
                      </span>
                    </div> -->
          </div>


          
          <div class="col-sm-6">
            <div class="customize-fee-plans"><span class="text-highlight-td">Customize Fee </span> Plans</div>
            <div class="customize-fee-plan-para">
              Designed especially for educational institutions smooth fee
              collection process
            </div>
            <div class="typewriter">
              Repay in
              <span class="theme-color-code">
                <a
                  href=""
                  class="typewrite theme-color-code"
                  data-period="2000"
                  data-type='["3,6,9,12"]'
                  ><span class="wrap">3,6,9,12</span></a
                >
                EMIs
              </span>
            </div>
          </div>
<div class="col-sm-6">
            <div class="img-for-parents-style">
              <img src="image/img-one-for-parents-2.png" alt="" width="80%"/>
            </div>
          </div>
          

          
        </div>
      </div>
    </section>
<!--TESTIMONIAL SLIDER --->
<style>
  .testimonial-main
  {
    padding:100px 0px 100px 0px;
  }
  .content-wrapper {
	height: auto;
	width: 100%;
	max-width: 100rem;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	padding-bottom: 5rem;
}

h1 {
	margin-bottom: calc(0.7rem + 0.5vmin);
	font-size: calc(2.3rem + 1vmin);
}

.blue-line {
	height: 0.3rem;
	width: 6rem;
	background-color: #fc9321;
	margin-bottom: calc(3rem + 2vmin);
}

.wrapper-for-arrows {
	position: relative;
	width: 100%;
	border-radius: 2rem;
	box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
	overflow: hidden;
	display: grid;
	place-items: center;
}

.review-wrap {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	padding-top: calc(2rem + 1vmin);
	width: 100%;
}

#imgDiv {
	border-radius: 50%;
	width: calc(6rem + 4vmin);
	height: calc(6rem + 4vmin);
	position: relative;
	box-shadow: 5px -3px #fc9321;
	background-size: cover;
	margin-bottom: calc(0.7rem + 0.5vmin);
}

.chicken {
	background-image: url("https://media0.giphy.com/media/A8Cdznswn5vnG/200w.gif?cid=790b7611e8c5980ee7141bc18ec12c49962b871eb404ba5b&rid=200w.gif&ct=s");
	width: 200px;
	height: 250px;
	position: absolute;
	top: 12%;
}

#imgDiv::after {
	content: "''";
	font-size: calc(2rem + 2vmin);
	/* font-family: sans-serif; */
	line-height: 150%;
	color: #fff;
	display: grid;
	place-items: center;
	border-radius: 50%;
	background-color: #fc9321;
	position: absolute;
	top: 10%;
	left: -10%;
	width: calc(2rem + 2vmin);
	height: calc(2rem + 2vmin);
}

#personName {
	margin-bottom: calc(0.7rem + 0.5vmin);
	font-size: calc(1rem + 0.5vmin);
	letter-spacing: calc(0.1rem + 0.1vmin);
	font-weight: bold;
}

#profession {
	font-size: calc(0.8rem + 0.3vmin);
	margin-bottom: calc(0.7rem + 0.5vmin);
	color: #fc9321;
}

#description {
	font-size: calc(0.8rem + 0.3vmin);
	width: 90%;
	max-width: 58rem;
	text-align: center;
	margin-bottom: calc(1.4rem + 1vmin);
	color: rgb(221, 221, 221);
	line-height: 2rem;
}

.arrow-wrap {
	position: absolute;
	top: 50%;
}

.arrow {
	width: calc(1.4rem + 0.6vmin);
	height: calc(1.4rem + 0.6vmin);
	border: solid #fc9321;
	border-width: 0 calc(0.2rem + 0.2vmin) calc(0.2rem + 0.2vmin) 0;
	cursor: pointer;
	transition: transform 0.3s;
}

.arrow:hover {
	transition: 0.3s;
	transform: scale(1.15);
}

.left-arrow-wrap {
	left: 5%;
	transform: rotate(135deg);
	-webkit-transform: rotate(135deg);
}

.right-arrow-wrap {
	transform: rotate(-45deg);
	-webkit-transform: rotate(-45deg);
	right: 5%;
}

.surprise-me-btn {
	border: 2px solid #fc9321;
	background-color: rgb(224, 238, 255);
	color: #fc9321;
	border-radius: 2rem;
	padding: calc(0.5rem + 0.2vmin) 0;
	width: calc(7rem + 5vmin);
	text-align: center;
	transition: background-color 0.3s, transform 0.3s;
	cursor: pointer;
	margin-bottom: calc(1.4rem + 1vmin);
}

.surprise-me-btn:hover {
	transition: background-color 0.3s, transform 0.3s;
	background-color: rgb(255, 255, 255);
	transform: rotate(5deg);
}

.move-head {
	animation: moveHead 1.55s infinite;
	animation-delay: -0.8s;
}

.hide-chicken-btn {
	border: 2px solid rgb(226, 89, 79);
	background-color: rgb(255, 224, 224);
	color: rgb(226, 79, 79);
	border-radius: 2rem;
	padding: calc(0.5rem + 0.2vmin) 0;
	width: calc(10rem + 5vmin);
	text-align: center;
	transition: background-color 0.3s, transform 0.3s;
	cursor: pointer;
	margin-bottom: calc(1.4rem + 1vmin);
}

.hide-chicken-btn:hover {
	transition: background-color 0.3s, transform 0.3s;
	background-color: rgb(255, 255, 255);
	transform: rotate(5deg);
}

@keyframes moveHead {
	0% {
	}
	25% {
		transform: translate(0.5rem, 1rem) rotate(5deg);
	}
	100% {
		transform: translate(0, 0) rotate(-5deg);
	}
}

@media screen and (max-width: 900px) {
	.content-wrapper {
		width: 100%;
	}
}

</style>
<section class="testimonial-main">
  <div class="container">
<div class="content-wrapper">
		<h1>Testimonials</h1>
		
		<div class="wrapper-for-arrows">
			<div style="opacity: 0;" class="chicken"></div>
			<div id="reviewWrap" class="review-wrap">
				<div id="imgDiv" class="">
				</div>
				<div id="personName"></div>
				<div id="profession"></div>
				<div id="description">
				</div>
			</div>
			
			<div class="left-arrow-wrap arrow-wrap">
				<div class="arrow" id="leftArrow"></div>
			</div>
			<div class="right-arrow-wrap arrow-wrap">
				<div class="arrow" id="rightArrow"></div>
			</div>
		</div>
	</div>
  </div>
  </section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
	<script src="main.js"></script>

  <script>
    const reviewWrap = document.getElementById("reviewWrap");
const leftArrow = document.getElementById("leftArrow");
const rightArrow = document.getElementById("rightArrow");
const imgDiv = document.getElementById("imgDiv");
const personName = document.getElementById("personName");
const profession = document.getElementById("profession");
const description = document.getElementById("description");
const surpriseMeBtn = document.getElementById("surpriseMeBtn");
const chicken = document.querySelector(".chicken");

let isChickenVisible;

let people = [
	{
		photo:
			'url("https://cdn.pixabay.com/photo/2018/03/06/22/57/portrait-3204843_960_720.jpg")',
		name: "Susan Smith",
		profession: "WEB DEVELOPER",
		description:
			"Cheese and biscuits chalk and cheese fromage frais. Cheeseburger caerphilly cheese slices chalk and cheese cheeseburger mascarpone danish fontina rubber cheese. Squirty cheese say cheese manchego jarlsberg lancashire taleggio cheese and wine squirty cheese. Babybel pecorino feta macaroni cheese brie queso everyone loves gouda. Cheese and biscuits camembert de normandie fromage fromage macaroni cheese"
	},

	{
		photo:
			"url('https://cdn.pixabay.com/photo/2019/02/11/20/20/woman-3990680_960_720.jpg')",
		name: "Anna Grey",
		profession: "UFC FIGHTER",
		description:
			"I'm baby migas cornhole hell of etsy tofu, pickled af cardigan pabst. Man braid deep v pour-over, blue bottle art party thundercats vape. Yr waistcoat whatever yuccie, farm-to-table next level PBR&B. Banh mi pinterest palo santo, aesthetic chambray leggings activated charcoal cred hammock kitsch humblebrag typewriter neutra knausgaard. Pabst succulents lo-fi microdosing portland gastropub Banh mi pinterest palo santo"
	},

	{
		photo:
			"url('https://cdn.pixabay.com/photo/2016/11/21/12/42/beard-1845166_960_720.jpg')",
		name: "Branson Cook",
		profession: "ACTOR",
		description:
			"Radio telescope something incredible is waiting to be known billions upon billions Jean-François Champollion hearts of the stars tingling of the spine. Encyclopaedia galactica not a sunrise but a galaxyrise concept of the number one encyclopaedia galactica from which we spring bits of moving fluff. Vastness is bearable only through love paroxysm of global death concept"
	},

	{
		photo:
			"url('https://cdn.pixabay.com/photo/2014/10/30/17/32/boy-509488_960_720.jpg')",
		name: "Julius Grohn",
		profession: "PROFESSIONAL CHILD",
		description:
			"Biscuit chocolate pastry topping lollipop pie. Sugar plum brownie halvah dessert tiramisu tiramisu gummi bears icing cookie. Gummies gummi bears pie apple pie sugar plum jujubes. Oat cake croissant bear claw tootsie roll caramels. Powder ice cream caramels candy tiramisu shortbread macaroon chocolate bar. Sugar plum jelly-o chocolate dragée tart chocolate marzipan cupcake gingerbread."
	}
];

imgDiv.style.backgroundImage = people[0].photo;
personName.innerText = people[0].name;
profession.innerText = people[0].profession;
description.innerText = people[0].description;
let currentPerson = 0;

//Select the side where you want to slide
function slide(whichSide, personNumber) {
	let reviewWrapWidth = reviewWrap.offsetWidth + "px";
	let descriptionHeight = description.offsetHeight + "px";
	//(+ or -)
	let side1symbol = whichSide === "left" ? "" : "-";
	let side2symbol = whichSide === "left" ? "-" : "";

	let tl = gsap.timeline();

	if (isChickenVisible) {
		tl.to(chicken, {
			duration: 0.4,
			opacity: 0
		});
	}

	tl.to(reviewWrap, {
		duration: 0.4,
		opacity: 0,
		translateX: `${side1symbol + reviewWrapWidth}`
	});

	tl.to(reviewWrap, {
		duration: 0,
		translateX: `${side2symbol + reviewWrapWidth}`
	});

	setTimeout(() => {
		imgDiv.style.backgroundImage = people[personNumber].photo;
	}, 400);
	setTimeout(() => {
		description.style.height = descriptionHeight;
	}, 400);
	setTimeout(() => {
		personName.innerText = people[personNumber].name;
	}, 400);
	setTimeout(() => {
		profession.innerText = people[personNumber].profession;
	}, 400);
	setTimeout(() => {
		description.innerText = people[personNumber].description;
	}, 400);

	tl.to(reviewWrap, {
		duration: 0.4,
		opacity: 1,
		translateX: 0
	});

	if (isChickenVisible) {
		tl.to(chicken, {
			duration: 0.4,
			opacity: 1
		});
	}
}

function setNextCardLeft() {
	if (currentPerson === 3) {
		currentPerson = 0;
		slide("left", currentPerson);
	} else {
		currentPerson++;
	}

	slide("left", currentPerson);
}

function setNextCardRight() {
	if (currentPerson === 0) {
		currentPerson = 3;
		slide("right", currentPerson);
	} else {
		currentPerson--;
	}

	slide("right", currentPerson);
}

leftArrow.addEventListener("click", setNextCardLeft);
rightArrow.addEventListener("click", setNextCardRight);

surpriseMeBtn.addEventListener("click", () => {
	if (chicken.style.opacity === "0") {
		chicken.style.opacity = "1";
		imgDiv.classList.add("move-head");
		surpriseMeBtn.innerText = "Remove the chicken";
		surpriseMeBtn.classList.remove("surprise-me-btn");
		surpriseMeBtn.classList.add("hide-chicken-btn");
		isChickenVisible = true;
	} else if (chicken.style.opacity === "1") {
		chicken.style.opacity = "0";
		imgDiv.classList.remove("move-head");
		surpriseMeBtn.innerText = "Surprise me";
		surpriseMeBtn.classList.add("surprise-me-btn");
		surpriseMeBtn.classList.remove("hide-chicken-btn");
		isChickenVisible = false;
	}
});

window.addEventListener("resize", () => {
	description.style.height = "100%";
});

  </script>
<!--TESTIMONIAL SLIDER end --->
    <section class="three-points-for-parents-main">
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <div class="three-points-icon">
              <img src="image/payment-method-icon.png" alt="" width="100%" />
            </div>
            <div class="three-points-heading">8+ methods to pay</div>
            <div class="three-points-para">
              Pay with cards, wallets, UPI, Netbanking, NEFTs, Cash or Zero Cost
              EMIs.
            </div>
          </div>
          <div class="col-sm-4">
            <div class="three-points-icon">
              <img
                src="image/payment-method-icon-two.png"
                alt=""
                width="100%"
              />
            </div>
            <div class="three-points-heading">Pay anytime, anywhere</div>
            <div class="three-points-para">
              Pay digitally from the comfort of your home or on campus via QR
              code.
            </div>
          </div>
          <div class="col-sm-4">
            <div class="three-points-icon">
              <img
                src="image/payment-method-icon-three.png"
                alt=""
                width="100%"
              />
            </div>
            <div class="three-points-heading">Priority Support</div>
            <div class="three-points-para">
              Get instant support via call or email and solve any pressing fee
              payment issues.
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
  <script>
    var TxtType = function (el, toRotate, period) {
      this.toRotate = toRotate;
      this.el = el;
      this.loopNum = 0;
      this.period = parseInt(period, 10) || 2000;
      this.txt = "";
      this.tick();
      this.isDeleting = false;
    };

    TxtType.prototype.tick = function () {
      var i = this.loopNum % this.toRotate.length;
      var fullTxt = this.toRotate[i];

      if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
      } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
      }

      this.el.innerHTML = '<span class="wrap">' + this.txt + "</span>";

      var that = this;
      var delta = 200 - Math.random() * 100;

      if (this.isDeleting) {
        delta /= 2;
      }

      if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
      } else if (this.isDeleting && this.txt === "") {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
      }

      setTimeout(function () {
        that.tick();
      }, delta);
    };

    window.onload = function () {
      var elements = document.getElementsByClassName("typewrite");
      for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute("data-type");
        var period = elements[i].getAttribute("data-period");
        if (toRotate) {
          new TxtType(elements[i], JSON.parse(toRotate), period);
        }
      }
      // INJECT CSS
      var css = document.createElement("style");
      css.type = "text/css";
      css.innerHTML = ".typewrite > .wrap { border-right: 0.1em solid #ffa000}";
      document.body.appendChild(css);
    };
  </script>
</html>
