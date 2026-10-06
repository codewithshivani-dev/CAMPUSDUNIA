@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CampusDunia Smart Card</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
<style>
    @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

    body{
      background-color: black;
      color: white;
    }

   .student-card-text
   {
    font-size:55px;
    color:#fff;
    text-align:center;
    width:100%;
    border:0px solid #fff;
    font-weight:600;
    letter-spacing:1.5px;
   }
   .student-card-main
   {
    height:auto;
    border:0px solid white;
    padding-top:50px;
    padding-bottom:50px;

   }
  .span-td
   {
      background: linear-gradient(90deg, #f78da7, #fdd835, #81c784, #64b5f6, #ba68c8);
       -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      color: transparent;
      font-weight:600 !important;
   }
   .para
   {
    color:#fff;
    font-size:18px;
    width:100%;
    text-align:center;
    letter-spacing:1px;

   }
   .image-banner-smart-card
   {
    margin-block:40px;
    display:flex;
    justify-content: space-around;
   }
   .one-card-one-platform
   {
    font-size:40px;
    color:#fff;
    text-align:center;
    width:100%;
    border:0px solid #fff;
    font-weight:600;
    letter-spacing:1.5px;
   }
   .one-card-para
   {
    color:lightgray !important;
    font-size:16px;
    width:90%;
    text-align:center;
    letter-spacing:1px;
    margin:auto;
    margin-top:10px;
   }
   .small-text
  {
    font-size:15px;
    font-weight:600;
    padding-top:55px;
  }
  .para-td-campus
  {
    font-size:40px;
    font-weight:600;
  }
  .para-td-campus-para
  {
    font-size:20px !important;
    font-weight:200;
    margin-top:20px;
  }
  .campus-id-access
  {
    margin-top:80px;
  }
  .main-campus-id
  {
    background-color:#000;
  }
       /* MEDIA QUERIES START */
      @media screen and (max-width: 768px) 
      {
        .student-card-main {
          padding-top:10px;
        }
        .student-card-text
        {
          font-size: 25px;
          padding-top:10px;
        }
        .image-banner-smart-card img 
        {
          margin-left:20px!important;
        }
        .one-card-one-platform 
        {
          font-size: 25px;
        }
        .para-td-campus 
        {
          font-size: 25px;
        }
      }
    </style>
</head>

<body>
  <section style="padding-top:130px;"></section>
  <section class="student-card-main">
    <div class="container">
      <div class="row">
          <div class="col-sm-12">
            <div class="student-card-text">
                CampusDunia Smart Card. <span class="span-td">Reimagined.<span>
             </div>
             <div class="para">
                Built for modern campuses to deliver seamless, secure, and elevated student experiences.
             </div>
             <div style="display: flex;justify-content: space-around;margin-top:20px;">
              <button class="btn btn-warning" style="font-weight:600;color:#fff;">
                Apply Now
              </button>
             </div>
          </div>
          <div class="col-sm-12"> 
              <div class="image-banner-smart-card">
                  <img src="image/smart-card-banner.png" alt="" width="80%" style="margin-left:230px;">
              </div>
          </div>
      </div>
    </div>
  </section>
  <!--ONE CARD AND ONE PLATFORM-->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="one-card-one-platform">
              One Card. One Platform.<br/><span class="span-td">Endless Possibilities.</span>
          </div>
          <div class="one-card-para">
            Across campuses today, students juggle between plastic ID cards, parent-funded debit cards, metro passes, and more. It’s fragmented, outdated, and forgettable. CampusDunia changes that. It unifies campus identity and access, transit, payments, and privileges into one smart, elegant card. With CampusDunia, institutions deliver exceptional student experiences, streamline operations, and activate campus engagement like never before.
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon-1.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Unified campus experience
            </h6>
          </div>
        </div>
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon- 2.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Enhanced campus security
            </h6>
          </div>
        </div>
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon-3.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Digital-first campus image
            </h6>
          </div>
        </div>
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon-4.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Operational efficiency at scale
            </h6>
          </div>
        </div>
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon-5.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Branded campus identity
            </h6>
          </div>
        </div>
        <div class="col-sm-2 mt-4">
          <div style="text-align:center;">
            <img src="image/icon-6.png" alt="" width="40%">
            <h6 style="margin-top:10px;">
              Superior student experience
            </h6>
          </div>
        </div>
      </div>
      </div>
    </div>
  </section>
  <!--One Card. One Platform. Endless Possibilities. END-->
  <section class="main-campus-id">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
            <div class="small-text mt-4">
              CAMPUS ID & ACCESS
            </div>
            <div class="para-td-campus"> 
              A smarter, safer ID <br>Card
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Issue dynamic, digital-first student IDs with built-in access control</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Unique & dynamic QR code, scannable via any mobile camera or QR reader</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Dual Interface (DI) Chip enables smooth passage through boom barriers and flap gates</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Create and issue temporary cards with a click</p>
            </div>
        </div>
        <div class="col-sm-6">
          <img src="image/campus-id-access.png" alt="" width="100%" class="campus-id-access">
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-sm-6">
          <img src="image/campus-td-two.png" alt="" width="100%" class="campus-id-access">
        </div>
        <div class="col-sm-6">
            <div class="small-text mt-4">
              PAYMENTS
            </div>
            <div class="para-td-campus">
              Fast, secure payments in a tap
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Secure, numberless card protects student information</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Virtual card accessible via the CampusDunia</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Tap & go payments for books, snacks, shopping, travel, and more</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Works seamlessly across all online platforms</p>
            </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-sm-6">
            <div class="small-text mt-4">
              CampusDunia App
            </div>
            <div class="para-td-campus">
              Students stay in control of their spends
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Paired with the Campusdunia app, CampusDunia keeps students in the loop with real-time transaction updates</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Check wallet balance, top up funds instantly, and track transactions</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Lock and unlock the card with just a tap</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Set payment limits across each medium for better budget control</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Lock and unlock the card with just a tap</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Set payment limits across each medium for better budget control</p>
            </div>
        </div>
         <div class="col-sm-6">
          <img src="image/real-time-image-phone.png" alt="" width="70%" class="campus-id-access" style="margin-left:70px;">
        </div>
      </div>
      <div class="row mt-4">
         <div class="col-sm-6">
          <img src="image/gifts-campusdunia.png" alt="" width="100%" class="campus-id-access">
        </div>
        <div class="col-sm-6">
            <div class="small-text mt-4">
              WELCOME BENEFITS
            </div>
            <div class="para-td-campus">
              CampusDunia Smart Card Perks
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Students enjoy welcome benefits worth ₹15,000 from day one</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Exclusive deals on travel, food, wellness, upskilling, and learning platforms</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Institutions can run their own exclusive schemes and privileges</p>
                
            </div>
        </div>
       
      </div>
      <div class="row mt-4">
        <div class="col-sm-6">
          <img src="image/doctor-campusdunia.png" alt="" width="100%" class="campus-id-access">
        </div>
        <div class="col-sm-6">
            <div class="small-text mt-4">
              WELLNESS BOOST
            </div>
            <div class="para-td-campus">
              Extend institutional care beyond the campus
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Access wellness perks that are designed for everyday student life, and go beyond campus boundaries</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Students get worldwide personal accident coverage worth INR 100,000</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Free 24×7 doctor consultations anytime, anywhere</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Enjoy up to 40% off on health checkups and more</p>
            </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-sm-6">
            <div class="small-text mt-4">
              CAMPUS ID & ACCESS
            </div>
            <div class="para-td-campus">
              A smarter, safer ID <br>Card
            </div>
            <div>
              <p class="para-td-campus-para">
                <i class="fa-solid fa-arrow-right-long"></i> Issue dynamic, digital-first student IDs with built-in access control</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Unique & dynamic QR code, scannable via any mobile camera or QR reader</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Dual Interface (DI) Chip enables smooth passage through boom barriers and flap gates</p>
                <p class="para-td-campus-para"><i class="fa-solid fa-arrow-right-long"></i> Create and issue temporary cards with a click</p>
            </div>
        </div>
        <div class="col-sm-6">
          <img src="image/campusduniua-rewards.png" alt="" width="70%" class="campus-id-access">
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
    // document.addEventListener("DOMContentLoaded", function () {
    //   function revealImages() {
    //     const section = document.querySelector(".secureImg");
    //     const position = section.getBoundingClientRect().top;
    //     const windowHeight = window.innerHeight;

    //     if (position < windowHeight - 200) {
    //       section.classList.add("animate");
    //     }
    //   }

    //   window.addEventListener("scroll", revealImages);
    // });

    // document.addEventListener("DOMContentLoaded", function () {
    //   let items = document.querySelectorAll(".timeline li");

    //   function isElementInViewport(el) {
    //     let rect = el.getBoundingClientRect();
    //     return (
    //       rect.top >= 0 &&
    //       rect.left >= 0 &&
    //       rect.bottom <=
    //         (window.innerHeight || document.documentElement.clientHeight) &&
    //       rect.right <=
    //         (window.innerWidth || document.documentElement.clientWidth)
    //     );
    //   }

    //   function callbackFunc() {
    //     for (let i = 0; i < items.length; i++) {
    //       if (isElementInViewport(items[i])) {
    //         items[i].classList.add("in-view");
    //       } else {
    //         items[i].classList.remove("in-view"); 
    //       }
    //     }
    //   }

    //   window.addEventListener("scroll", callbackFunc);
    //   window.addEventListener("resize", callbackFunc);

    //   callbackFunc();
    // });
    </script>
</body>
</html>
