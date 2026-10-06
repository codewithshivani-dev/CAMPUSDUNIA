@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Institutes - CampusDunia</title>
    <link rel="stylesheet" href="style.css" />
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
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

      body {
        /* font-family: "Comfortaa", sans-serif; */
        color: white;
      }
      .section-flexible {
        background-color: #1e1e1e;
        height: auto;
      }
      section {
        padding: 80px 0px;
      }
      .hero-section-institute {
        background-color: #000;
        text-align: center;
        padding: 160px 0px 20px 0px;
      }
      .hero-title {
        font-size: 55px;
        font-weight: 800;
      }
      .hero-subtitle {
        font-size: 21px;
        font-weight: 500;
      }
      .btn-primary {
        background-color: #f39c12;
        border: none;
        padding: 12px 30px;
        font-size: 21px;
        font-weight: 800;
      }
      .text-highlight {
        color: #f39c12;
        font-weight: 600!important;
      }
      .section-auto-payments {
        background-color: #1e1e1e;
        background-size: cover;
      }
      .section-smart-payments {
        background-color: #000;
        background-size: cover;
      }
      .multiple-payment-options {
        background-color: #1e1e1e;
        height: auto;
        background-size: cover;
      }
      .hero-title {
        color: #fff;
        text-align: center;
        margin-top: 0px;
      }
      .pay-fee-screen-image {
        width: 100%;
        margin-top: -40px;
      }
      .hero-heading {
        width: 80%;
        margin: auto;
      }
      .btn-main-td {
        margin-top: 20px;
      }
      .btn-td {
        margin: auto;
        margin-top: 20px;
      }
      .dashboard-main img {
        width: 100%;
      }
      .img-color-mixture {
        -webkit-mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 60%,
          rgba(0, 0, 0, 0) 100%
        );
        mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 60%,
          rgba(0, 0, 0, 0) 100%
        );
      }
      .fw-bold {
        font-size: 40px;
        color: #fff;
        margin-top: 50px;
        text-align: left;
        font-weight: 600 !important;
      }
      .para-bold {
        text-align: left;
        color: #fff;
        font-size: 18px;
        margin-top: 20px !important;
      }
      .automated-payments {
        text-align: left;
        font-size: 40px;
        margin-top: 120px;
      }
      .auto-debit-para {
        color: #fff;
        text-align: left;
        font-size: 18px;
        margin-top: 20px !important;
      }
      .text-highlight {
        color: #f39c12;
      }
      .img-fluid {
        width: 70%;
      }
      .list-style-td {
        color: #fff;
        font-size: 18px;
        margin-left: -20px;
      }

      /* ZERO COST EMI SLIDER */
      .slider-wrapper {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }
      .slides .slide {
        display: none;
      }
      .slides .slide.active {
        display: block;
      }
      .slides .slide h2 {
        font-size: 24px;
        color: #333;
      }
      .slides .slide p {
        font-size: 16px;
        color: #555;
      }

      .pagination .dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ccc;
        cursor: pointer;
        transition: background 0.3s;
      }
      .pagination .dot.active {
        background: #333;
      }

      .heading-text-institute {
        text-align: left;
        margin-top: 60px;
      }
      .heading-text-institute span {
        color: #fc9321;
      }
      .btn-hero-section {
        margin-top: 20px !important;
      }

      /* MEDIA QUERY START FROM HERE*/
      @media only screen and (max-width: 768px) {
        .hero-title {
          font-size: 45px;
          font-weight: 800;
        }

        .fw-bold {
          font-size: 40px;
          color: #fff;
          margin-top: 0px;
          text-align: left;
          font-weight: 400 !important;
        }
        .img-fluid {
          width: 50% !important;
        }
      }
    </style>
  </head>
  <body>
    <!-- Hero Section -->
    <section class="hero-section-institute">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="heading-text-institute">
              <h2 style="font-weight: 600!important;">
                Boost Your Working Capital By Getting
                <span class="text-highlight">Full Year fees Upfront</span>
              </h2>
              <div
                class="bttn bttn-two mt-3 mb-4 btn-hero-section"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal"
              >
                Apply
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div style="max-width: 546px">
              <img src="image/dashboard-main.png" alt="" width="100%" />
            </div>
          </div>
          <!-- <div class="col-md-6">
              <div class="pay-fee-screen-image">
                <img src="/image/pay-fee-screen.png" class="img-fluid" alt="Hero Image" />
              </div>
            </div> -->
        </div>
      </div>
    </section>
    <!--ON BOARD YEARS INSTITUTE START-->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div style="max-width: 456px">
              <img src="image/onboard.png" alt="" width="100%" />
            </div>
          </div>
          <div class="col-sm-6">
             <div class="heading-text-institute">
              <h2 style="font-size:40px !important;font-weight:600!important;">
                Onboard your institute
              </h2>
              <h2 style="font-size:35px !important;">
                Get upto <span>50 Lakhs</span> working capital 
                <span>@ 0% interest for 45 days</span>
              </h2>                                                                                                                                                                                                                                    
              <div
                class="bttn bttn-two mt-4 mb-4 btn-hero-section"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal"
              >

                Apply
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--ON BOARD YEARS INSTITUTE END-->

    <!-- Payment Option 1 -->
    <!-- <section class="section-flexible">
      <div class="container">
        <div class="row">
    
          <div class="col-md-6">
            <h5 class="fw-bold">Flexible EMI Plans</h5>
            <h2 class="para-bold">
              Pay Fees <span class="text-highlight">in Easy Installments</span>
            </h2>
            <ul class="list-style-td" style="list-style: none; color: #fff">
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Pay in Easy EMI's
                rather than paying all at once
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Spread out costs to
                reduce stress on your budget
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Choose a plan that
                works best for you
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Spread out costs to
                reduce stress on your budget
              </li>
            </ul>
          </div>

          <div class="col-md-6 text-center">
            <img
              src="image/pay-fee-screen.png"
              class="img-fluid img-color-mixture"
              alt="EMI Payment"
            />
          </div>
        </div>
      </div>
    </section> -->

    <!-- Zero‑Cost EMI Slider -->
    <!-- <section class="section-flexible">
      <div class="container">
        <div class="slider-wrapper d-flex align-items-center justify-content-between bg-white rounded p-4 my-5">
          
          <div class="slides flex-grow-1 pe-4">
            <div class="slide active" data-index="0">
              <h2>Zero Cost <strong>EMI</strong></h2>
              <p>Offer parents the convenience of paying fees in affordable monthly instalments with a quick 2‑min sign‑up!</p>
            </div>
            <div class="slide" data-index="1">
              <h2>Get the full year’s fee, <strong>upfront</strong></h2>
              <p>Boost your institute’s cashflow and let parents spread out payments.</p>
            </div>
            <div class="slide" data-index="2">
              <h2>Digital and <strong>secure</strong> payments</h2>
              <p>Paper‑free, PCI‑compliant gateway with bank‑grade security.</p>
            </div>

            
            <div class="pagination mt-4 text-center">
              <span class="dot active mx-1" data-index="0"></span>
              <span class="dot mx-1" data-index="1"></span>
              <span class="dot mx-1" data-index="2"></span>
            </div>
          </div>

          
          <div class="phone flex-shrink-0" style="width: 300px;">
            <img id="phone-image" src="image/pay-fee-screen.png" class="img-fluid" alt="Phone screenshot">
          </div>
        </div>
      </div>
    </section> -->
    <!-- <script>
  const images = [
    'image/pay-fee-screen.png',
    'image/dashboard-main.png',
    'image/auto-debit.png'
  ];
  const slides = document.querySelectorAll('.slide');
  const dots   = document.querySelectorAll('.dot');
  const phone  = document.getElementById('phone-image');
  function goToSlide(idx) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[idx].classList.add('active');
    dots[idx].classList.add('active');
    phone.src = images[idx];
  }
  dots.forEach(d => d.addEventListener('click', e => {
    goToSlide(+e.currentTarget.dataset.index);
  }));
</script> -->
    <!-- Zero‑Cost EMI Slider End -->

    <!-- ZERO COST EMI SECTION START -->
    <style>
      .zero-cost-emi-section {
        background-color: #1e1e1e;
      }
      .zero-cost-emi {
        color: #fff;
        font-size: 20px;
      }
      .margin-td-one {
        margin-left: 60px;
        width: 65%;
      }
      .margin-td {
        margin-left: 60px;
        width: 70%;
      }
    </style>
    <!-- <section class="zero-cost-emi-section">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="" style="border: 0px solid red">
              <img
                src="image/payment-second.png"
                class="d-block margin-td"
                alt="..."
              />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="zero-cost-emi" style="margin-top: 100px">
              <h5 class="fw-bold highlight">Simplify Fee Reconciliation</h5>
              <p class="para-bold">
                Simplify the complex process of fee reconciliation, saving time
                and reducing errors<span class="text-highlight">
                  with automated solutions.</span
                >
              </p>
              <ul class="list-style-td" style="list-style: none; color: #fff">
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Automated Matching –
                  Auto-match fee records
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Real-Time Insights –
                  Instant transaction tracking+
                </li>

                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Error Reduction –
                  Fewer manual errors
                </li>
                 <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Fee Reconciliation, Simplified
              </li> 
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- ZERO COST EMI SECTION END -->

    <!--SMART CARD FEATURES-->
    <style>
      .cerebox-services {
        background: #000;
        display: flex;
        justify-content: center;
        padding: 60px 0px;
      }

      .cerebox-services .cerebox-service-wrap {
        display: flex;
        width: 100%;
        gap: 20px;
        padding-left: 0;
        padding-right: 0;
        border-radius: 30px;
        justify-content: space-between;
      }

      .cerebox-services .cerebox-service-wrap .left-panel {
        /* flex-basis: 45%; */
        position: sticky;
        top: 80px;
        height: fit-content;
      }

      .cerebox-services .cerebox-service-wrap .left-panel .top-section {
        text-align: left;
      }

      .cerebox-services .cerebox-service-wrap .left-panel h2,
      .cerebox-services .cerebox-service-wrap .left-panel p {
        z-index: 2;
        position: relative;
      }

      .cerebox-services .cerebox-service-wrap .left-panel .ser-button {
        margin: 40px 0 0;
        background: #fafafa;
        border: 1px solid #eaeaea;
        padding: 30px;
        border-radius: 10px;
        min-height: 310px;
        position: relative;
      }

      .cerebox-services .cerebox-service-wrap .left-panel .ser-button h2 {
        font-size: 32px;
        line-height: 40px;
        margin: 0 0 10px;
        color: #05164d;
      }

      .cerebox-services
        .cerebox-service-wrap
        .left-panel
        .ser-button
        h2
        strong {
        color: #016be3;
      }

      .cerebox-services .cerebox-service-wrap .left-panel .ser-button:after {
        content: "";
        background: url() no-repeat center;
        width: 189px;
        height: 224px;
        background-size: 100%;
        position: absolute;
        right: 0;
        bottom: 0;
        z-index: 1;
      }

      .cerebox-services .cerebox-service-wrap .right-panel {
        /* flex-basis: 50%; */
        display: flex;
        flex-direction: column;
        gap: 15px;
      }

      .cerebox-services .cerebox-service-wrap .right-panel .content-box {
        display: flex;
        /* flex-direction: column; */
        /* background: #000000; */
        /* border: 1px solid #f3f1f1; */
        border-radius: 5px;
        padding: 10px;
        position: relative;
        align-items: center !important;
        /* border-bottom: 1px solid #2e2e2e; */
        /* outline: 1px solid #363636; */
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .img-sec {
        /* width: 100%; */
        margin: 0px 10px 0px 0px;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .img-sec
        img {
        width: 40px;
        height: 100%;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        h3 {
        color: #ffffff;
        position: relative;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        h3
        a {
        color: #ffffff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        h3
        a:hover {
        text-decoration: none;
        text-decoration: underline;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        p {
        color: #ffffff;
        margin: 0px !important;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        ul {
        margin: 20px 0 0;
        display: flex;
        flex-wrap: wrap;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        ul
        li {
        /* flex-basis: 48%; */
        margin-bottom: 10px;
        font-weight: 500;
        padding-left: 30px;
        position: relative;
        font-weight: 400;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        ul
        li:before {
        content: "";
        background: url() no-repeat center;
        width: 20px;
        height: 20px;
        background-size: 100%;
        position: absolute;
        left: 0;
        top: 4px;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        a {
        color: #656565;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .text-box
        a:hover {
        text-decoration: none;
        text-decoration: underline;
      }

      .cerebox-services .cerebox-service-wrap .right-panel .content-box .move {
        width: 40px;
        height: 46px;
        border: 2px solid #858585;
        background: transparent;
        border-radius: 18px;
        position: absolute;
        top: 30px;
        right: 30px;
        transition: all 0.3s ease-in-out;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .move:after {
        content: "";
        background: url(image/icons/move-grey.svg) no-repeat;
        position: absolute;
        right: 0;
        left: 0;
        margin: 0 auto;
        top: 15px;
        background-size: 100%;
        width: 14px;
        height: 14px;
        transform: rotate(-8deg);
        transition: all 0.3s ease-in-out;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .move:hover {
        width: 35px;
        height: 35px;
        border-radius: 14px;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box
        .move:hover:after {
        transform: rotate(0deg);
        top: 8px;
      }

      /* .cerebox-services .cerebox-service-wrap .right-panel .content-box:nth-child(2n) {
        background: #181818;
        border-bottom: 1px solid #2e2e2e;
        background: linear-gradient(270deg, #0b147c 21.77%, #4b1bac 100%);
    } */

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        h3 {
        color: #fff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        h3
        a {
        color: #fff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        h3
        a:hover {
        text-decoration: underline;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        p,
      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        li {
        color: #fff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        ul
        li:before {
        background: url() no-repeat center;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        a {
        color: #fff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .text-box
        a:hover {
        text-decoration: underline;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .move {
        border: 2px solid #fff;
        background: #fff;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .move:after {
        background: url(image/icons/move-blue.svg) no-repeat;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .move:hover {
        width: 35px;
        height: 35px;
        border-radius: 14px;
      }

      .cerebox-services
        .cerebox-service-wrap
        .right-panel
        .content-box:nth-child(2n)
        .move:hover:after {
        transform: rotate(0deg);
        top: 8px;
      }

      @media (max-width: 1024px) {
        .cerebox-services .cerebox-service-wrap {
          justify-content: space-between;
        }

        .cerebox-services .cerebox-service-wrap .left-panel .top-section {
          text-align: left;
        }

        .cerebox-services .cerebox-service-wrap .left-panel .ser-button {
          margin: 30px 0 0;
          padding: 25px;
          min-height: initial h2;
          /* min-height-font-size: 26px;
          min-height-line-height: 35px; */
        }

        .cerebox-services
          .cerebox-service-wrap
          .left-panel
          .ser-button
          .btn-container
          .cta-button {
          display: inline-flex;
          align-items: center;
        }

        .cerebox-services .cerebox-service-wrap .right-panel .content-box {
          padding: 20px;
        }

        .cerebox-services
          .cerebox-service-wrap
          .right-panel
          .content-box
          .text-box
          ul
          li {
          /* flex-basis: 100%; */
          padding-left: 28px;
        }

        .cerebox-services
          .cerebox-service-wrap
          .right-panel
          .content-box
          .text-box
          ul
          li:before {
          width: 18px;
          height: 18px;
          top: 3px;
        }

        .cerebox-services
          .cerebox-service-wrap
          .right-panel
          .content-box
          .move {
          width: 32px;
          height: 40px;
          top: 20px;
          right: 20px;
        }

        .cerebox-services
          .cerebox-service-wrap
          .right-panel
          .content-box
          .move:after {
          top: 12px;
        }

        .cerebox-services
          .cerebox-service-wrap
          .right-panel
          .content-box
          .move:hover {
          width: 32px;
          height: 35px;
          border-radius: 14px;
        }
      }

      /* Parent wrapper to apply 3D perspective */
      /* Parent wrapper to apply 3D perspective */
      .card-container {
        width: 205px; /* Adjust based on your card size */
        height: 310px;
        perspective: 1000px;
      }
      /* .card-wrapper {
        perspective: 1000px;
        display: flex;
        align-items: center;
        justify-content: center;
      } */

      .card-inner {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        rotate: 20deg;
        animation: rotateCard 8s infinite;
      }

      .card-front,
      .card-back {
        width: 100%;
        height: 100%;
        position: absolute;
        backface-visibility: hidden;
      }

      .card-back {
        transform: rotateY(180deg);
      }

      @keyframes rotateCard {
        0% {
          transform: rotateY(0deg);
        }
        50% {
          transform: rotateY(180deg);
        }
        100% {
          transform: rotateY(360deg);
        }
      }

      .highlight {
        color: #f39c12;
      }

      .heading {
        font-size: 52px;
        font-weight: 700;
        margin-top: 10px;
        margin-bottom: 25px;
      }

      .secureImg {
        position: relative;
        display: flex;
        padding: 30px;
      }

      /* First Image - Starts Off-Screen */
      .secureImg .imgOne {
        position: absolute;
        z-index: 1;
        transform: translateX(255px);
        opacity: 0;
        transition: transform 2.5s ease-in-out, opacity 2.5s ease-in-out;
      }

      /* Second Image - Stays in Place */
      .secureImg .imgTwo {
        position: relative;
        z-index: 2;
        left: 55% !important;
      }

      /* When Section is in View */
      .secureImg.animate .imgOne {
        transform: translateX(0);
        opacity: 1;
        transition: transform 2.5s ease-in-out, opacity 2.5s ease-in-out;
        animation: zoomVibrate 0.8s ease-in-out infinite alternate;
        animation-delay: 2.5s;
      }

      /* Zoom + Vibrate Animation */
      @keyframes zoomVibrate {
        0% {
          transform: translateX(0) scale(1);
        }

        25% {
          transform: translateX(-2px) scale(1.02);
        }

        50% {
          transform: translateX(2px) scale(1.03);
        }

        75% {
          transform: translateX(-1px) scale(1.02);
        }

        100% {
          transform: translateX(1px) scale(1.03);
        }
      }

      .feature-card-list li {
        text-align: start;
        place-content: center;
        list-style-type: none;
        align-items: center;
        /* background: linear-gradient(135deg, #333, #444); */
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        /* outline: 1px solid; */
        min-height: 80px;
      }

      .feature-card-list {
        padding-left: 0px;
      }

      .feature-card img,
      .card-col img {
        max-width: 300px;
        max-height: 430px;
      }

      .card-col {
        margin-left: 25%;
            margin-top: 70px;
      }
      .other-benefits
      {
        font-size:45px;
        font-weight:700;
        text-align:center;
        width:100%;
        margin-bottom:60px !important;
      }
    </style>
    <!-- <section class="cerebox-services padding-t-120 padding-b-120" id="serv">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
              <div class="other-benefits">
                Other <span style="font-weight: 700 !important;color: #f39c12;">Benefits</span>
              </div>
          </div>
        </div>
        <div class="cerebox-service-wrap">
          <div class="col-sm-6">
            <div class="zero-cost-emi">
              <h5 class="fw-bold">
                <span class="text-highlight">All-in-One</span> Smart Card
              </h5>
              <p class="para-bold">
                Enjoy a complimentary smart card with integrated access,
                identity, and payment features —
                <span class="text-highlight"> all in one.</span>
              </p>
              <ul class="list-style-td" style="list-style: none; color: #fff">
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Seamless Access –
                  Entry to campus facilities
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Digital ID – Acts as
                  your student identity
                </li>

                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Easy Payments –
                  Tap-to-pay for services and fees
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Identification & Branding
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Cashless Payments
                </li>
                 
                
              </ul>
            </div>
          </div>

          <div class="left-panel col-md-6">
            <div class="card-col">
              <div class="card-container">
                <div class="card-inner">
                  <img
                    src="image/PREAPID-CARD.png"
                    alt="SmartCard"
                    class="card-front"
                  />
                  <img
                    src="image/prepaid-card-back.png"
                    alt="SmartCard Back"
                    class="card-back"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->

    <!--SMART CARD FEATURES END-->

<section class="cerebox-services padding-t-120 padding-b-120" id="serv">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
              <div class="other-benefits">
                Other <span style="font-weight: 700 !important;color: #f39c12;">Benefits</span>
              </div>
          </div>
        </div>
        <div class="cerebox-service-wrap">
          <div class="col-sm-6">
            <div class="zero-cost-emi">
              <h5 class="fw-bold">
                <span class="text-highlight" style="font-weight:700!important;">CampusDunia Smart Card</span> Offering & It's Benefits
              </h5>
              <p class="para-bold">
                Unlock smarter campus living with the <span class="text-highlight">CampusDunia Smart Card
                 all in one.</span>
              </p>
              <ul class="list-style-td" style="list-style: none; color: #fff">
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Identification & Branding
                </li>

                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Cashless Payments
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Parental Oversight
                </li>
                <li class="mt-4">
                  <i class="fa-solid fa-circle-check"></i> Convenience for Students
                </li>
                 
                <!-- <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Fee Reconciliation, Simplified
              </li> -->
              </ul>
            </div>
          </div>

          <!-- <div class="left-panel col-md-6">
            <div class="card-col" style="padding: 20px">
              <div
                class="card-wrapper"
                data-aos="fade-up"
                data-aos-duration="500"
                data-aos-delay="10"
                data-aos-offset="20"
              >
                <img
                  src="image/PREAPID-CARD.png"
                  alt="SmartCard"
                  class="animated-card"
                />
                <img src="image/prepaid-card-back.png" alt="" />
              </div>
            </div>
          </div> -->

          <div class="left-panel col-md-6">
            <div class="card-col">
              <div class="card-container">
                <div class="card-inner">
                  <img
                    src="image/PREAPID-CARD.png"
                    alt="SmartCard"
                    class="card-front"
                  />
                  <img
                    src="image/prepaid-card-back.png"
                    alt="SmartCard Back"
                    class="card-back"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Payment Option 2 -->
    <section class="section-auto-payments text-center">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="" style="border: 0px solid red">
              <img
                src="image/payment-one.png"
                class="d-block margin-td-one"
                alt="..."
              />
            </div>
          </div>
          <div class="col-md-6">
            <h5 class="fw-bold automated-payments">
              Automate Offer EMIs,
              <span class="text-highlight">Drive Admissions</span>
            </h5>
            <p class="auto-debit-para">
              <span class="text-highlight"
                >Boost admissions and fee collections
              </span>
              by enabling quick, hassle-free EMI options — sign-up takes just 2
              minutes!
            </p>
            <ul
              class="list-style-td"
              style="list-style: none; color: #fff; text-align: left"
            >
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Get the full year’s
                fee, upfront
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Boost institute’s
                cashflow
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Digital and secure
                payments
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Payment Option 2 -->
    <!-- <section class="section-smart-payments text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h5 class="fw-bold automated-payments" style="margin-top: 120px">
              Because <span class="text-highlight">Smart Payment Wins</span>
            </h5>
            <p class="auto-debit-para">
              Forget post-dated cheques. Automate fee collection directly from
              the payer’s account,
              <span class="text-highlight">right on time </span>
            </p>
            <ul
              class="list-style-td"
              style="list-style: none; color: #fff; text-align: left"
            >
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> 50 sec sign up for
                auto-debit
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Send fee-related alerts
                and reminders
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Eliminate fee
                follow-ups
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Keep finances on track
                effortlessly
              </li>
            </ul>
          </div>
          <div class="col-sm-6">
            <div class="" style="border: 0px solid red">
              <img
                src="image/payment-one.png"
                class="d-block margin-td-one"
                alt="..."
              />
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- Payment Option 3 -->
    <section class="multiple-payment-options text-center">
      <div class="container">
        <div class="row">
          
          <div class="col-md-6">
            <h5 class="fw-bold" style="margin-top: 120px">
              <span class="text-highlight">Multiple</span> Payment Options
            </h5>
            <p class="para-bold auto-debit-para mt-3">
              A variety of secure online payment methods for
              <span class="text-highlight"
                >simple and timely fee payments.</span
              >
            </p>
            <ul
              class="list-style-td"
              style="list-style: none; color: #fff; text-align: left"
            >
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Flexible fee-payment
                options
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Auto-reconciliation of
                payments
              </li>
              <li class="mt-4">
                <i class="fa-solid fa-circle-check"></i> Digital and secure
                payments
              </li>
            </ul>
          </div>
          <div class="col-md-6">
            <img
              src="image/payment-options.png"
              class="img-fluid"
              alt="Online Payment"
              style="width: 75%"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: false,
      });
    </script>
  </body>
</html>

