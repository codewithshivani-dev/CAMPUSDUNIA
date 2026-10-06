@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CampusDunia | How It Works</title>
    <link
      href="https://fonts.googleapis.com/css?family=Comfortaa"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
      integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn"
      crossorigin="anonymous"
    />
    <link
      rel="shortcut icon"
      href="https://campusdunia.co.in/image/icon.png"
      type="image/x-icon"
    />
    <link href="https://campusdunia.co.in/css/style.css" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://campusdunia.co.in/css/royal-preload.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
      crossorigin="anonymous"
    ></script>
    <style>
      body {
        background: #000 !important;
        color: white;
      }
      @media screen and (min-width: 768px) {
        .how-it-works-main {
          background: url(image/hiw.png) no-repeat;
          border: 0px solid red;
          width: 100%;
          margin-top: 40px;
          background-size: contain;
        }
        .get-started {
          margin-top: 40px;
          font-size: 20px;
          font-weight: 600;
          margin-left: 250px;
        }
        .get-started-para {
          margin-left: 250px;
          width: 50%;
          font-size: 14px;
        }
        .get-started-para span {
          color: #00a0ff;
        }
        .verfication {
          margin-top: 150px;
          margin-left: 350px;
          font-size: 20px;
          font-weight: 600;
        }
        .verfication-para {
          margin-left: 350px;
          width: 50%;
          font-size: 14px;
        }
        .upload-records {
          margin-top: 120px;
          margin-left: 250px;
          font-size: 20px;
          font-weight: 600;
        }
        .upload-records-para {
          margin-left: 250px;
          width: 45%;
          font-size: 14px;
        }
        .send-fee {
          margin-top: 87px;
          margin-left: 350px;
          font-size: 20px;
          font-weight: 600;
        }
        .send-fee-para {
          margin-left: 350px;
          width: 40%;
          font-size: 14px;
        }
        .transaction-tracking {
          margin-top: 100px;
          margin-left: 250px;
          font-size: 20px;
          font-weight: 600;
        }
        .transaction-tracking-para {
          margin-left: 250px;
          width: 40%;
          font-size: 14px;
        }
        .customize-reports {
          margin-top: 114px;
          margin-left: 350px;
          font-size: 20px;
          font-weight: 600;
        }
        .customize-reports-para {
          margin-left: 350px;
          width: 40%;
          font-size: 14px;
        }
        .reconcilitions {
          margin-top: 94px;
          margin-left: 250px;
          font-size: 20px;
          font-weight: 600;
        }
        .reconcilitions-para {
          margin-left: 250px;
          width: 30%;
          font-size: 14px;
        }
        .monitoring {
          margin-top: 118px;
          margin-left: 350px;
          font-size: 20px;
          font-weight: 600;
        }
        .monitoring-para {
          margin-left: 350px;
          width: 50%;
          font-size: 14px;
        }
        .endless-possibilities {
          margin-top: 136px;
          margin-left: 250px;
          font-size: 20px;
          font-weight: 600;
        }
        .endless-possibilities-para {
          margin-left: 250px;
          width: 35%;
          font-size: 14px;
          margin-bottom: 150px;
        }
      }
      @media screen and (max-width: 768px) {
        .how-it-works-main {
          background: none;
          margin-top: 50px;
          margin-bottom: 50px;
        }
        .get-started {
          text-align: center;
          margin-left: 0px;
          margin-top: 0px;
          font-size: 20px;
          font-weight: 600;
        }
        .get-started-para {
          margin-left: 0px;
          width: 100%;
          text-align: center;
          margin-top: 20px;
        }
        .td-responsive-for-all {
          margin-top: 70px;
          margin-left: 0px;
          text-align: center;
          font-size: 20px;
          font-weight: 600;
        }
        .td-responsive-for-all-para {
          width: 100%;
          margin-left: 0px;
          text-align: center;
          margin-top: 20px;
        }
      }
      @media screen and (min-width: 768px) {
        .how-it-works-main-two {
          background: url(image/hiw-two.png) no-repeat;
          border: 0px solid red;
          width: 100%;
          margin-top: 40px;
          background-size: 100% 100%;
          margin-bottom: 50px;
        }
        .search-school {
          margin-top: 0px;
          font-size: 20px;
          font-weight: 600;
          margin-left: 370px;
        }
        .search-school-para {
          margin-left: 370px;
          width: 40%;
          font-size: 14px;
        }
        .login-account {
          margin-top: 270px;
          font-size: 20px;
          font-weight: 600;
          width: 37%;
          margin-left: 40px;
        }
        .login-account-para {
          font-size: 14px;
          width: 40%;
          margin-left: 40px;
        }
        .Campusdunia-text {
          margin-top: 90px;
          font-size: 20px;
          font-weight: 600;
          margin-left: 370px;
        }
        .Campusdunia-text-para {
          margin-left: 370px;
          width: 32%;
          font-size: 14px;
          margin-bottom: 40px;
        }
        .header-image {
          /* background: url(image/how-it-works.jpg) no-repeat; */
          width: 100%;
          height: 500px;
          position: relative;
          background-size: cover;
        }
        .tab-button {
          position: absolute;
          bottom: 0;
          border: 0px solid red;
        }
        .nav-tdlink.active {
          border: 2px solid #f39c12 !important;
          background-color: #ffff !important;
        }

        .nav-tdlink:hover{
          color: #f39c12 !important;
        }
        .nav-tdlink {
          background-color: #000;
          color: white;
        }
      }
      @media screen and (max-width: 768px) {
        .how-it-works-main-two {
          background: none;
          margin-bottom: 40px;
        }
        .navbar-td ul li{
          border: 2px solid #f3f3f3 !important;
        }
        .navbar-td
        {
          display: flex;
          text-align: center;
          flex-direction: column-reverse;
        }

        .header-image
        {
          margin-top: 100px;
        }
      }
      .footer{
      border-top: none !important;
      }
      section{
        overflow: hidden;
      }
      .nav-tabs{
        border: none;
      }
      .highlight{
        color: #f39c12;
      }
      .header-image
        {
          margin-top: 100px;
        }
        .nav-tdlink.active { border: 2px solid #f39c12 !important;background-color: #ffff !important;color: #f39c12 !important;}.nav-tdlink:hover{ color: #f39c12 !important; } .nav-tdlink { background-color: #000;  color: white !important; }
    </style>
  </head>

  <body
    data-aos-easing="ease"
    data-aos-duration="3000"
    data-aos-delay="0"
    cz-shortcut-listen="true"
  >
  <section class="header-image">
    <img src="image/how-it-works.jpg" alt="how" width="100%">
      <div class="container">
        <div class="row">
          <div class="tab-button">
          <ul class="nav nav-tabs navbar-td" id="myTab" role="tablist" style="border-bottom: none;">
              <li class="nav-item">
                <a
                  class="nav-link active nav-tdlink activetd"
                  id="home-tab"
                  data-toggle="tab"
                  href="#home"
                  role="tab"
                  aria-controls="home"
                  aria-selected="true"
                  style="
                    padding-left: 30px;
                    padding-right: 30px;
                  "
                  >INSTITUTIONS</a
                >
              </li>
              <li class="nav-item" style="margin-left: 3px">
                <a
                  class="nav-link nav-tdlink"
                  id="profile-tab"
                  data-toggle="tab"
                  href="#profile"
                  role="tab"
                  aria-controls="profile"
                  aria-selected="false"
                  style="
                    padding-left: 30px;
                    padding-right: 30px;
                    border: 2px solid #f3f3f3;
                  "
                  >PARENTS/STUDENTS</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    
    <section style="margin-top: 40px">
      <div class="container">
        <div class="tab-content" id="myTabContent">
          <div
            class="tab-pane fade show active"
            id="home"
            role="tabpanel"
            aria-labelledby="home-tab"
          >
            <section>
              <div class="container">
                <div class="row">
                  <div class="how-it-works-main">
                    <div class="col-sm-12">
                      <div class="get-started"><span class="highlight">GET STARTED</span></div>
                      <div class="get-started-para">
                        Go to Institute Registration page & complete
                        documentation formalities. Once we are through the
                        documentation and agreement, your educational institute
                        web profile page link will be generated.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="verfication td-responsive-for-all">
                      <span class="highlight">VERIFICATION</span>
                      </div>
                      <div class="verfication-para td-responsive-for-all-para">
                        Once you upload/provide your educational institution<br />
                        documents like business/institute registration, address,
                        location, website & bank documents, it goes for
                        verification.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="upload-records td-responsive-for-all">
                      <span class="highlight">UPLOAD RECORDS</span>
                      </div>
                      <div
                        class="upload-records-para td-responsive-for-all-para"
                      >
                        The one-time process requires you to upload the
                        students’ records and create fee.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="send-fee td-responsive-for-all">
                      <span class="highlight">SEND FEE DEPOSIT REQUEST</span>
                      </div>
                      <div class="send-fee-para td-responsive-for-all-para">
                        Generate fee deposit request and send a notification to
                        the parents/students using the integrated email/SMS
                        service.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="transaction-tracking td-responsive-for-all">
                      <span class="highlight">TRANSACTIONS TRACKING</span>
                      </div>
                      <div
                        class="transaction-tracking-para td-responsive-for-all-para"
                      >
                        Track all the transactions until they are settled in
                        your bank account.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="customize-reports td-responsive-for-all">
                      <span class="highlight">CUSTOMIZED REPORTS AS PER DEMAND</span>
                      </div>
                      <div
                        class="customize-reports-para td-responsive-for-all-para"
                      >
                        Use the filters and generate customized class-wise or
                        section-wise reports or track individual students.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="reconcilitions td-responsive-for-all">
                      <span class="highlight">RECONCILIATIONS</span>
                      </div>
                      <div
                        class="reconcilitions-para td-responsive-for-all-para"
                      >
                        Easily reconcile both online and offline payments.
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="monitoring td-responsive-for-all">
                      <span class="highlight">MONITORING</span>
                      </div>
                      <div class="monitoring-para td-responsive-for-all-para">
                        It’s easy to monitor the late fee payments.
                      </div>
                    </div>
                    <div class="col-sm-12">

                      <div class="endless-possibilities td-responsive-for-all">
                      <span class="highlight">ENDLESS POSSIBILITIES</span>
                      </div>
                      <div
                        class="endless-possibilities-para td-responsive-for-all-para"
                      > ing (but not
                        limited to) annual fee, recurring fee (monthly,
                        quarterly or annual basis or as per your semesters),
                        hostel fee, late fee & transport fee. Even generating
                        event tickets and collecting payment for the same is
                        possible.
                      </div>
                    </div>
                  </div> 
                </div>
              </div>
            </section>
          </div>
          <div
            class="tab-pane fade"
            id="profile"
            role="tabpanel"
            aria-labelledby="profile-tab"
          >
            <section>
              <div class="container">
                <div class="row">
                  <div class="how-it-works-main-two">
                    <div class="col-sm-12">
                      <div class="search-school td-responsive-for-all">
                      <span class="highlight">SEARCH INSTITUTE OR COURSE</span>
                      </div>
                      <div class="search-school-para td-responsive-for-all-para">
                        Search & select your institute/course and proceed to the
                        institute/course page where you can log into your
                        account and pay the fee.<br /><br />
                        If you can’t find your institute/course name, click on
                        ‘Recommend your institute/course and we will approach
                        your institute for this facility.
                      </div>
                    </div>
                    <di4v class="col-sm-12">
                      <div class="login-account td-responsive-for-all">
                      <span class="highlight">LOGIN YOUR ACCOUNT/ DOWNLOAD MOBILE APP</span>
                      </div>
                      <div class="login-account-para td-responsive-for-all-para">
                        Log into your account using the username and password
                        sent at your registered email.<br /><br />
                        Alternatively, you may “Download Campusdunia Mobile App”
                        and start making hassle free payments for every new fee
                        request. If you face any difficulty while logging in,
                        Reach to us at support@Campusdunia.co.in
                      </div>
                    </di4v>
                    <div class="col-sm-12">
                      <div class="Campusdunia-text td-responsive-for-all"> 

                        <span class="highlight">Campusdunia</span>
                      </div>
                      <div
                        class="Campusdunia-text-para td-responsive-for-all-para"
                      >
                        You’ll receive email/ SMS/ App notification along with a
                        payment link. Simply click on the link to pay the fee
                        online using your Debit Card/ Credit Card/ Netbanking
                        (all banks).<br /><br />
                        In case your educational institution is not registered
                        on Campusdunia, you can login and start using free
                        facilities. You’ll receive email/ SMS/ App notification
                        along with a payment link. Simply click on the link to
                        pay the fee online using your Debit Card/ Credit Card/
                        Netbanking (all banks).
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>
  </body>
  </html>
  <!-- <script src="https://campusdunia.co.in/js/royal_preloader.min.js"></script> -->
<!-- <script type="text/javascript">
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
  /****Active link */
  let switchNavMenuItem = (menuItems) => {
    var current = location.pathname;

    $.each(menuItems, (index, item) => {
      $(item).removeClass("active");

      if (
        (current.includes($(item).attr("href")) &&
          $(item).attr("href") !== "/") ||
        ($(item).attr("href") === "/" && current === "/")
      ) {
        $(item).addClass("active");
      }
    });
  };

  $(document).ready(() => {
    switchNavMenuItem($(".nav-link"));
  });

  /****lazy load */
  window.jQuery = window.$ = jQuery;
  (function ($) {
    "use strict";
    //Preloader
    Royal_Preloader.config({
      mode: "logo",
      logo: "/image/logo.png",
      logo_size: [220, 75],
      showProgress: true,
      showPercentage: true,
      text_colour: "#000000",
      background: "#ffffff",
    });
  })(jQuery);
</script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
        $(document).ready(() => {       
$.ajax({
  url: "https://geolocation-db.com/jsonp",
  jsonpCallback: "callback",
  dataType: "jsonp",
  success: function(location) {
    alert(location);
    $('#country').html(location.country_name);
    $('#state').html(location.state);
    $('#city').html(location.city);
    $('#latitude').html(location.latitude);
    $('#longitude').html(location.longitude);
    $('#ip').html(location.IPv4);
  }
});
});
</script> -->
