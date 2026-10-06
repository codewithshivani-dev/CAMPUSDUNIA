@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Funding Education Landing Page</title>
    <style>
      body{
        background-color: black;
        color: white;
        font-family: "DM Sans", sans-serif;
      }
      h1,
      h2,
      h3 {
        font-weight: 800;
      }
      section {
        padding: 60px 0;
      }
      /* .why-we-exist-main {
      } */
      .card-custom {
        /* background-color: #1a1a1a; */
        border-radius: 16px;
        padding: 20px;
        height: auto;
        text-align: left;
        padding-left: 0px;
      }
      .step-box {
        background-color: #111;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
      }
      .section-title {
            font-size: 40px;
    font-weight: 600;
        text-align: left;
        margin-bottom: 40px;
      }
      .faqs li {
        margin-bottom: 10px;
      }

      .text-left-td {
        text-align: left;
      }

      .hero-section-heading {
        font-size: 45px !important;
        font-weight: 600 !important;
      }
      .para-heading {              
        line-height: 35px;
      }
      .card-custom-one {
        padding: 15px 25px;
        border: 1px solid #fff;
        border-radius: 20px;
        min-height: 138px;
      }
      .card-custom-one p {
        margin-bottom: 0px;
      }
      .why-we-exist-responsive {
        text-align: left !important;
        margin-left: 50px;
      }
      .card-custom-responsive {
        text-align: right;
        margin-top: 100px;
      }

      /* MEDIA QUERIES START */
      @media screen and (max-width: 768px) {
        .hero-section-heading {
          margin-top: 70px;
          font-size: 35px !important;
          font-weight: 600 !important;
        }
        .section-title {
          font-size: 35px;
        }
        .why-we-exist-responsive {
          text-align: left !important;
          margin-left: 00px;
        }
        .card-custom-responsive {
          text-align: left;
          margin-top: 0px;
        }
        .poor-credit-history-resposnive {
          margin-top: 20px;
        }
        section {
          padding: 20px 0px;
        }
        .mission-card {
          flex-wrap: wrap;
          margin-bottom: 15px;
          align-items: center;
        }
        .icon-box {
          height: 90px;
        }
        .icon-box-one {
          width: 35%;
        }
        .icon-box-two {
          width: 50%;
        }
        .icon-box-three {
          width: 70%;
        }
        .process-title {
          line-height: 50px !important;
          text-align: left !important;
        }
        .step-details p {
          margin: 0 !important;
        }
        #why_choose_us .chosse-heading-one-td {
          min-height: 0px !important;
        }
         .card-custom-one
        {
            margin-bottom:20px;
        }
      }
    </style>
  </head>
  <body>
    <!-- Hero -->
    <section style="padding: 50px 0px 0px 0px; min-height: 1px"></section>
    <section class="text-center" style="padding: 25px 0px !important">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="text-left-td">
              <h1 class="hero-section-heading">
                "Funding education to help you unlock your full potential"
              </h1>
              <p class="mt-4 para-heading">
                Empowering ambitious students with affordable, stress-free loans
                to break down financial barriers and pursue their dreams.
              </p>
              <div
                class="bttn bttn-two mt-2 mb-4"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal"
              >
                Apply
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="text-align: center">
              <img
                src="image/for-parents-1.jpg"
                alt=""
                width="80%"
                style="
                  -webkit-mask-image: linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 1) 90%,
                    rgba(0, 0, 0, 0) 100%
                  );
                  mask-image: linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 1) 90%,
                    rgba(0, 0, 0, 0) 100%
                  );
                "
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Why We Exist -->
    <section class="why-we-exist-main">
      <div class="container">
        <div
          style="border: 4px solid #626262; padding: 30px; border-radius: 20px"
        >
          <div class="section-title why-we-exist-responsive">
            <!--Why We Exist-->
            Empowering Students, Breaking Barriers
          </div>
          <div class="row text-center">
            <!--<div class="col-md-4">-->
            <!--  <div-->
            <!--    class="card-custom card-custom-responsive"-->
            <!--    style=""-->
            <!--  >-->
            <!--    <h3 style="font-weight: 600 !important">Financial Barriers</h3>-->
            <!--    <p-->
            <!--      style="-->
            <!--        font-size: 18px !important;-->
            <!--        font-weight: 400 !important;-->
            <!--      "-->
            <!--    >-->
            <!--      Many deserving students can’t access quality education due to-->
            <!--      financial constraints.-->
            <!--    </p>-->
            <!--  </div>-->
            <!--</div>-->
            <div class="col-md-6">
              <div>
                <img
                  src="image/for-parents.png"
                  class="img-fluid"
                  alt=""
                  style="
                    margin-top: -30px;
                    -webkit-mask-image: linear-gradient(
                      to bottom,
                      rgba(0, 0, 0, 1) 90%,
                      rgba(0, 0, 0, 0) 100%
                    );
                    mask-image: linear-gradient(
                      to bottom,
                      rgba(0, 0, 0, 1) 90%,
                      rgba(0, 0, 0, 0) 100%
                    );
                  "
                />
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-custom">
                <h3 style="font-weight: 600 !important">
                  Overcoming Financial Barriers
                </h3>
                <p
                  style="
                    font-size: 18px !important;
                    font-weight: 400 !important;
                  "
                >
                  Many talented students can't access quality education or
                  skilling programs due to lack of funds.
                </p>
              </div>
              <div class="card-custom">
                <h3 style="font-weight: 600 !important">
                  Connecting Opportunities
                </h3>
                <p
                  style="
                    font-size: 18px !important;
                    font-weight: 400 !important;
                  "
                >
                  We offer affordable funding solutions to turn aspirations into
                  reality.
                </p>
              </div>
              <div class="card-custom">
                <h3 style="font-weight: 600 !important">Empowering Growth</h3>
                <p
                  style="
                    font-size: 18px !important;
                    font-weight: 400 !important;
                  "
                >
                  Our mission is to empower students to overcome obstacles,
                  enabling them to succeed academically and develop their skills
                  for a brighter future.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Common Barriers -->
     <section>
    <section class="container">
      <div class="section-title" style="text-align: left !important">
        Common Barriers to Education & Growth
      </div>
      <div class="row">
        <div class="col-md-6">
          <div
            class="card-custom-one"
            style="border: 2px solid #f39c12 !important"
          >
            <h5 style="font-size: 25px; font-weight: 600 !important">
              Lack of Funds
            </h5>
            <p
              style="
                line-height: 2;
                margin-top: 10px;
                font-size: 16px !important;
              "
            >
              Many talented students can't afford quality education programs and
              essential learning resources.
            </p>
          </div>
        </div>
        <div class="col-md-6 poor-credit-history-resposnive">
          <div class="card-custom-one" style="border: 2px solid #f39c12">
            <h5 style="font-size: 25px; font-weight: 600 !important">
              Poor Credit History
            </h5>
            <p
              style="
                line-height: 2;
                margin-top: 10px;
                font-size: 16px !important;
              "
            >
              Limited financial history makes it difficult to qualify for
              traditional education loans.
            </p>
          </div>
        </div>
        <div class="col-md-6 mt-4">
          <div class="card-custom-one" style="border: 2px solid #f39c12">
            <h5 style="font-size: 25px; font-weight: 600 !important">
              Limited Access
            </h5>
            <p
              style="
                line-height: 2;
                margin-top: 10px;
                font-size: 16px !important;
              "
            >
              Very few lenders offer affordable options to students despite
              their potential.
            </p>
          </div>
        </div>
        <div class="col-md-6 mt-4">
          <div class="card-custom-one" style="border: 2px solid #f39c12">
            <h5 style="font-size: 25px; font-weight: 600 !important">
              Complex Loan application process
            </h5>
            <p
              style="
                line-height: 2;
                margin-top: 10px;
                font-size: 16px !important;
              "
            >
              Students lack support and guidance through complex financial
              application processes.
            </p>
          </div>
        </div>
      </div>
    </section>
    </section>

    <!-- Mission -->
    <style>
      /* <!-- Our Mission Section CSS Start --> */

      h2.section-title-mission {
        font-weight: 600 !important;
        font-size: 48px !important;
        margin-bottom: 40px;
      }

      .mission-card {
        display: flex;
        margin-bottom: 15px;
        align-items: center;
      }

      .icon-box {
        height: 115px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 20px;
      }

      .icon-box i {
        color: #fff;
      }

      .icon-box-one {
        border: 2px solid #f39c12;
      }
      .icon-box-two {
        border: 2px solid #f39c12;
      }
      .icon-box-three {
        border: 2px solid #f39c12;
      }
      .border-svg.color-1 svg {
        border: 2px solid #f39c12;
        padding: 12px 12px;
        border-radius: 50%;
        width: 66px !important;
        height: 66px;
        margin-right: 20px;
      }
      .border-svg.color-2 svg {
        border: 2px solid #f39c12;
        padding: 12px 12px;
        border-radius: 50%;
        width: 66px !important;
        height: 66px;
        margin-right: 20px;
      }
      .border-svg.color-3 svg {
        border: 2px solid #f39c12;
        padding: 12px 12px;
        border-radius: 50%;
        width: 66px !important;
        height: 66px;
        margin-right: 20px;
      }
      .border-svg.color-4 svg {
        border: 2px solid #f39c12;
        padding: 12px 12px;
        border-radius: 50%;
        width: 66px !important;
        height: 66px;
        margin-right: 20px;
      }
      .mission-text h5 {
        font-weight: 600;
        font-size: 25px;
        margin: 20px 0;
      }

      .mission-text p {
        font-size: 18px;
        margin-bottom: 16px;
        color: #ccc;
      }

      .underline-one {
        border-bottom: 2px solid #f39c12;
      }
      .underline-two {
        border-bottom: 2px solid #f39c12;
      }
      .underline-three {
        border-bottom: 2px solid #f39c12;
      }
      .img {
        width: 35px;
      }
      /* <!-- Our Mission Section CSS End --> */
    </style>

    <!-- Our Mission Section Start -->
    <!--<section class="mission-main">-->
    <!--  <div class="container">-->
    <!--    <h2 class="section-title-mission">Our Mission</h2>-->
    <!--    <div class="mission-card">-->
    <!--      <div class="col-md-2 icon-box icon-box-one">-->
    <!--        <div class="img" style="width: 28px;">-->
    <!--          <svg-->
    <!--            class="colorable-icon"-->
    <!--            viewBox="0 0 384 512"-->
    <!--            xmlns="http://www.w3.org/2000/svg"-->
    <!--            data-icon="lightbulb"-->
    <!--            data-prefix="fal"-->
    <!--            aria-hidden="true"-->
    <!--          >-->
    <!--            <path-->
    <!--              d="M310.3 258.1C326.5 234.8 336 206.6 336 176c0-79.5-64.5-144-144-144S48 96.5 48 176c0 30.6 9.5 58.8 25.7 82.1c4.1 5.9 8.8 12.3 13.6 19l0 0c12.7 17.5 27.1 37.2 38 57.1c8.9 16.2 13.7 33.3 16.2 49.9H109c-2.2-12-5.9-23.7-11.8-34.5c-9.9-18-22.2-34.9-34.5-51.8l0 0 0 0 0 0c-5.2-7.1-10.4-14.2-15.4-21.4C27.6 247.9 16 213.3 16 176C16 78.8 94.8 0 192 0s176 78.8 176 176c0 37.3-11.6 71.9-31.4 100.3c-5 7.2-10.2 14.3-15.4 21.4l0 0 0 0c-12.3 16.8-24.6 33.7-34.5 51.8c-5.9 10.8-9.6 22.5-11.8 34.5H242.5c2.5-16.6 7.3-33.7 16.2-49.9c10.9-20 25.3-39.7 38-57.1c4.9-6.7 9.5-13 13.6-19zM192 96c-44.2 0-80 35.8-80 80c0 8.8-7.2 16-16 16s-16-7.2-16-16c0-61.9 50.1-112 112-112c8.8 0 16 7.2 16 16s-7.2 16-16 16zM146.7 448c6.6 18.6 24.4 32 45.3 32s38.7-13.4 45.3-32H146.7zM112 432v-5.3c0-5.9 4.8-10.7 10.7-10.7H261.3c5.9 0 10.7 4.8 10.7 10.7V432c0 44.2-35.8 80-80 80s-80-35.8-80-80z"-->
    <!--              fill="currentColor"-->
    <!--            ></path>-->
    <!--          </svg>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-10 mission-text">-->
    <!--        <h5>Identify Talent</h5>-->
    <!--        <p>We recognize potential where others don't look.</p>-->
    <!--        <div class="underline-one"></div>-->
    <!--      </div>-->
    <!--    </div>-->

    <!--    <div class="mission-card">-->
    <!--      <div class="col-md-3 icon-box icon-box-two">-->
    <!--        <div class="img">-->
    <!--          <svg-->
    <!--            class="colorable-icon"-->
    <!--            viewBox="0 0 576 512"-->
    <!--            xmlns="http://www.w3.org/2000/svg"-->
    <!--            data-icon="money-bill"-->
    <!--            data-prefix="fal"-->
    <!--            aria-hidden="true"-->
    <!--          >-->
    <!--            <path-->
    <!--              d="M480 96c0 35.3 28.7 64 64 64V128c0-17.7-14.3-32-32-32H480zm-32 0H128c0 53-43 96-96 96V320c53 0 96 43 96 96H448c0-53 43-96 96-96V192c-53 0-96-43-96-96zM32 384c0 17.7 14.3 32 32 32H96c0-35.3-28.7-64-64-64v32zm512-32c-35.3 0-64 28.7-64 64h32c17.7 0 32-14.3 32-32V352zM64 96c-17.7 0-32 14.3-32 32v32c35.3 0 64-28.7 64-64H64zM0 128C0 92.7 28.7 64 64 64H512c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM352 256a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zm-160 0a96 96 0 1 1 192 0 96 96 0 1 1 -192 0z"-->
    <!--              fill="currentColor"-->
    <!--            ></path>-->
    <!--          </svg>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-9 mission-text">-->
    <!--        <h5>Provide Resources</h5>-->
    <!--        <p>-->
    <!--          We offer accessible financial solutions tailored to your needs.-->
    <!--        </p>-->
    <!--        <div class="underline-two"></div>-->
    <!--      </div>-->
    <!--    </div>-->

    <!--    <div class="mission-card">-->
    <!--      <div class="col-md-4 icon-box icon-box-three">-->
    <!--        <div class="img">-->
    <!--          <svg-->
    <!--            class="colorable-icon"-->
    <!--            viewBox="0 0 640 512"-->
    <!--            xmlns="http://www.w3.org/2000/svg"-->
    <!--            data-icon="graduation-cap"-->
    <!--            data-prefix="fal"-->
    <!--            aria-hidden="true"-->
    <!--          >-->
    <!--            <path-->
    <!--              d="M307.2 66.2L47.6 160l74 26.7c10.3-6.9 21.5-12.6 33.4-17.1l159.4-59.8c8.3-3.1 17.5 1.1 20.6 9.4s-1.1 17.5-9.4 20.6L166.2 199.6c-1.5 .5-2.9 1.1-4.3 1.7l145.3 52.5c4.1 1.5 8.4 2.2 12.8 2.2s8.7-.8 12.8-2.2L592.4 160 332.8 66.2c-4.1-1.5-8.4-2.2-12.8-2.2s-8.7 .8-12.8 2.2zM296.3 283.9L126.9 222.7C99.4 246 82.1 279.9 80.2 316.9c5.9 13.2 10.2 27.5 13.4 41.5c6.4 27.6 10.7 65.9 2.1 108.7c-.9 4.3-3.4 8-7.1 10.4s-8.2 3.1-12.4 2l-64-16c-5.2-1.3-9.4-5.1-11.2-10.2s-.9-10.7 2.3-14.9c8.6-11.7 16-24.6 22.5-37.6C37.2 377.8 48 348.4 48 320c0-.6 0-1.2 .1-1.8c1.4-41 18-79.1 45.1-107.7L15.8 182.6C6.3 179.1 0 170.1 0 160s6.3-19.1 15.8-22.6L296.3 36.1c7.6-2.7 15.6-4.1 23.7-4.1s16.1 1.4 23.7 4.1L624.2 137.4c9.5 3.4 15.8 12.5 15.8 22.6s-6.3 19.1-15.8 22.6L343.7 283.9c-7.6 2.7-15.6 4.1-23.7 4.1s-16.1-1.4-23.7-4.1zm-122-10L160.4 406.3c.7 .8 1.8 2.1 3.7 3.7c6 5.2 16.5 11.5 31.9 17.5C226.4 439.4 270.3 448 320 448s93.6-8.6 124.1-20.6c15.4-6 25.8-12.3 31.9-17.5c1.9-1.6 3-2.8 3.7-3.7L465.7 273.8l31-11.2L512 408c0 35.3-86 72-192 72s-192-36.7-192-72l15.3-145.4 31 11.2zM480.5 405a.2 .2 0 1 0 -.3-.1 .2 .2 0 1 0 .3 .1zm-321 0a.1 .1 0 1 0 .2 0 .1 .1 0 1 0 -.2 0zM67 444.2c2.5-20.7 1.7-40-.5-56.7c-3.8 10-8 19.3-12.1 27.6c-3.8 7.6-7.9 15.2-12.5 22.8L67 444.2z"-->
    <!--              fill="currentColor"-->
    <!--            ></path>-->
    <!--          </svg>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-8 mission-text">-->
    <!--        <h5>Empower Success</h5>-->
    <!--        <p>We support your journey from enrollment to graduation.</p>-->
    <!--        <div class="underline-three"></div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</section>-->
    <!-- Our Mission Section End -->

    <!-- Why Choose Us -->
    <style>
      .choose-section-image {
        width: 100%;
      }
      .choose-section-image img {
        width: 100%;
        height: 100%;
        border-radius: 20px;
        object-fit: cover;
      }
      .choose-section-image-two {
        width: 100%;
      }
      .choose-section-image-two img {
        width: 100%;
        height: 100%;
        border-radius: 20px;
        object-fit: cover;
      }
      #why_choose_us .chosse-heading-one-td {
        min-height: 90px;
      }
      .chosse-heading-one-td {
        font-size: 24px;
        font-weight: 600;
        line-height: 30px;
        margin-top: 10px;
      }

      .choose-para-one-td {
        font-size: 17px;
        line-height: 30px;
        margin-top: 10px;
        color: #ccc;
      }
      #Layer_1 {
        width: 36px;
      }
      .st0,
      .cls-1 {
        fill-rule: evenodd;
        fill-rule: evenodd;
        fill: black;
        stroke: white;
        stroke-width: 5px;
      }

      #svg-section svg {
        width: 36px !important;
        height: 36px !important;
      }
      /* .choose-section-image {
          background-image: url(image/choose-section-image.avif);
          width: 100%;
          height: 100%;
          background-size: cover;
          border-radius: 20px;
          background-position: center center;
          background-repeat: no-repeat;
        } */
      /* .choose-section-image-two {
          background-image: url(image/choose-section-image-two.avif);
          width: 100%;
          height: 100%;
          background-size: cover;
          border-radius: 20px;
          background-position: center center;
          background-repeat: no-repeat;
        } */
    </style>
    <section id="why_choose_us">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-12">
              <div class="section-title">Why Choose Us?</div>
            </div>
            <div class="row" id="svg-section">
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    style="width: 34px"
                    class="colorable-icon"
                    viewBox="0 0 576 512"
                    xmlns="http://www.w3.org/2000/svg"
                    data-icon="piggy-bank"
                    data-prefix="fal"
                    aria-hidden="true"
                  >
                    <path
                      d="M272 32c34.8 0 64.5 22.2 75.5 53.3c2.9 8.3 12.1 12.7 20.4 9.8s12.7-12.1 9.8-20.4C362.3 31.2 320.8 0 272 0C216.5 0 170.4 40.4 161.5 93.4c-1.5 8.7 4.4 17 13.1 18.4s17-4.4 18.4-13.1C199.5 60.8 232.4 32 272 32zM55.2 190.3c7.9-4 11.1-13.6 7.2-21.5s-13.6-11.1-21.5-7.2L36.2 164C14 175.1 0 197.8 0 222.6c0 35.7 28.5 64.7 64 65.4c0 0 0 0 0 0c0 52.4 25.2 98.8 64 128v48c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V448h64v16c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V434.7c25.5-11.1 47.5-28.7 64-50.7h32c17.7 0 32-14.3 32-32V256c0-17.7-14.3-32-32-32H530.7c-10.7-24.6-27.4-45.9-48.3-62.2l7.2-25C495.4 116.3 480 96 458.8 96H456c-30.5 0-58.2 12.2-78.4 32H224c-77.4 0-142 55-156.8 128H65.4C47 256 32 241 32 222.6c0-12.7 7.2-24.2 18.5-29.9l4.7-2.3zM424 288a24 24 0 1 0 0-48 24 24 0 1 0 0 48zM396.4 154.7C411 138.3 432.3 128 456 128h2.8l-10.2 35.8c-1.9 6.7 .7 13.8 6.5 17.7c22.8 15.2 40.4 37.6 49.7 63.8c2.3 6.4 8.3 10.7 15.1 10.7H544v96H503.8c-5.3 0-10.3 2.7-13.3 7.1c-15.2 22.8-37.6 40.4-63.8 49.6C420.3 411 416 417 416 423.8V464c0 8.8-7.2 16-16 16H368c-8.8 0-16-7.2-16-16V432c0-8.8-7.2-16-16-16H240c-8.8 0-16 7.2-16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V407.8c0-5.3-2.7-10.3-7.1-13.3C118.6 371.5 96 332.4 96 288c0-70.7 57.3-128 128-128H384h0l.4 0c4.6 0 8.9-1.9 11.9-5.3z"
                      fill="currentColor"
                    ></path>
                  </svg>
                  <div class="chosse-heading-one-td">
                    Affordable Interest Rates, Peace of Mind
                  </div>
                  <div class="choose-para-one-td">
                    We understand how important your dreams are. That’s why we
                    offer competitive interest rates and flexible repayment
                    options designed with your unique situation in mind.
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    style="width: 30px"
                    class="colorable-icon"
                    viewBox="0 0 512 512"
                    xmlns="http://www.w3.org/2000/svg"
                    data-icon="clock"
                    data-prefix="fal"
                    aria-hidden="true"
                  >
                    <path
                      d="M480 256A224 224 0 1 1 32 256a224 224 0 1 1 448 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM240 112V256c0 5.3 2.7 10.3 7.1 13.3l96 64c7.4 4.9 17.3 2.9 22.2-4.4s2.9-17.3-4.4-22.2L272 247.4V112c0-8.8-7.2-16-16-16s-16 7.2-16 16z"
                      fill="currentColor"
                    ></path>
                  </svg>
                  <div class="chosse-heading-one-td">
                    Fast, Stress-Free Approval
                  </div>
                  <div class="choose-para-one-td">
                    Your time and energy matter. Our simple, digital process
                    evaluates your potential—not just your credit history—so you
                    can access funds quickly and without unnecessary stress.
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    style="width: 30px"
                    class="colorable-icon"
                    viewBox="0 0 512 512"
                    xmlns="http://www.w3.org/2000/svg"
                    data-icon="headset"
                    data-prefix="fal"
                    aria-hidden="true"
                  >
                    <path
                      d="M32 256C32 132.3 132.3 32 256 32s224 100.3 224 224V400.1c0 26.5-21.5 48-48 48l-82.7-.1c-6.6-18.6-24.4-32-45.3-32H240c-26.5 0-48 21.5-48 48s21.5 48 48 48h64c20.9 0 38.7-13.4 45.3-32l82.7 .1c44.2 0 80.1-35.8 80.1-80V256C512 114.6 397.4 0 256 0S0 114.6 0 256v48c0 8.8 7.2 16 16 16s16-7.2 16-16V256zM320 464c0 8.8-7.2 16-16 16H240c-8.8 0-16-7.2-16-16s7.2-16 16-16h64c8.8 0 16 7.2 16 16M144 224h16V352H144c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zM64 272v32c0 44.2 35.8 80 80 80h16c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32H144c-44.2 0-80 35.8-80 80zm288-48h16c26.5 0 48 21.5 48 48v32c0 26.5-21.5 48-48 48H352V224zm16-32H352c-17.7 0-32 14.3-32 32V352c0 17.7 14.3 32 32 32h16c44.2 0 80-35.8 80-80V272c0-44.2-35.8-80-80-80z"
                      fill="currentColor"
                    ></path>
                  </svg>
                  <div class="chosse-heading-one-td">Always Here for You</div>
                  <div class="choose-para-one-td">
                    Our caring advisors are available 24/7 to support you
                    through every step—whether you're applying, facing
                    challenges, or preparing for graduation. You're never alone
                    on this journey.
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    style="width: 30px; height: 25px"
                    class="colorable-icon"
                    viewBox="0 0 640 512"
                    xmlns="http://www.w3.org/2000/svg"
                    data-icon="diploma"
                    data-prefix="fal"
                    aria-hidden="true"
                  >
                    <path
                      d="M253.2 80.8C245 70.2 232.4 64 219 64h-3c-22.1 0-40 17.9-40 40s17.9 40 40 40h40 45.8L253.2 80.8zm-97 63.2c-7.7-11.4-12.1-25.2-12.1-40c0-39.8 32.2-72 72-72h3c23.3 0 45.3 10.8 59.5 29.3L320 115.2l41.5-53.9C375.7 42.8 397.7 32 421 32h3c39.8 0 72 32.2 72 72c0 14.8-4.5 28.6-12.1 40H544c65.1 .3 96 71.5 96 128c0 56.9-24.1 119.2-81 140.7c-10.5 4-22.5 4.2-33.4 1.8L400 386.6V464c0 5.5-2.9 10.7-7.6 13.6s-10.6 3.2-15.6 .7L320 449.9l-56.8 28.4c-5 2.5-10.9 2.2-15.6-.7s-7.6-8.1-7.6-13.6V386.6L114.4 414.5l-6.9-31.2L240 353.8V176H216 96.1c-18.9 .1-33.9 9.9-45.5 28.1C38.5 223.1 32 248.8 32 272c0 50.8 21.4 96.1 60.3 110.8c3.5 1.3 9.1 1.8 15.1 .5l6.9 31.2c-10.9 2.4-22.9 2.2-33.4-1.8C24.1 391.2 0 328.9 0 272c0-56.5 30.9-127.7 96-128h60.1zM400 176V353.8l132.6 29.5c6 1.3 11.6 .9 15.1-.5c38.9-14.7 60.3-60 60.3-110.8c0-23.2-6.5-48.9-18.6-67.9c-11.6-18.2-26.6-28-45.5-28.1H424 400zm24-32c22.1 0 40-17.9 40-40s-17.9-40-40-40h-3c-13.4 0-26 6.2-34.1 16.8L338.2 144H384h40zm-88 32H304 272V438.1l40.8-20.4c4.5-2.3 9.8-2.3 14.3 0L368 438.1V176H336z"
                      fill="currentColor"
                    ></path>
                  </svg>
                  <div class="chosse-heading-one-td">
                    Funds Disbursed Directly
                  </div>
                  <div class="choose-para-one-td">
                    We simplify the process by depositing your funds straight
                    into your institution’s account—so you can focus on what
                    truly matters: your education and your future.
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 122.88 108.47"
                  >
                    <defs>
                      <style>
                        .a {
                          fill-rule: evenodd;
                          fill: black;
                          stroke: white;
                          stroke-width: 5px;
                        }
                      </style>
                    </defs>
                    <title>personal-loan</title>
                    <path
                      class="a stroke-td"
                      d="M40,72.91c5.38,15.82,27.83,16.4,32.87,0a16.37,16.37,0,0,0,5,2.83,32.39,32.39,0,0,0-2.21,4.07,31.09,31.09,0,0,0,.46,26.43H0C2,96.44.75,88.13,9.92,83,16.47,79.34,34,78.26,40,72.91Zm82.06,23a5.66,5.66,0,0,1,.84,2.9c0,5.35-8.28,9.69-18.48,9.69s-18.47-4.34-18.47-9.69a5.72,5.72,0,0,1,.84-2.9c2.35,3.94,9.36,6.79,17.63,6.79s15.29-2.85,17.64-6.79Zm-11-24.4-.22,2.41-9.07-.79a8.29,8.29,0,0,1,1.56,3.26l-2.19-.2a7.06,7.06,0,0,0-1-2.13,4.33,4.33,0,0,0-1.88-1.69l.17-2,12.61,1.09Zm-6.68-3.76c8.39,0,15.19,2.67,15.19,6s-6.8,6-15.19,6-15.18-2.68-15.18-6,6.8-6,15.18-6Zm0-2c10.2,0,18.48,4.34,18.48,9.69s-8.28,9.68-18.48,9.68-18.47-4.34-18.47-9.68,8.27-9.69,18.47-9.69Zm17.7,18.78a5.56,5.56,0,0,1,.78,2.78c0,5.34-8.28,9.68-18.48,9.68S85.93,92.64,85.93,87.3a5.34,5.34,0,0,1,.78-2.78c2.27,4,9.33,6.9,17.69,6.9s15.42-2.92,17.7-6.9ZM35.87,40.15a5,5,0,0,0-2.56.67,2,2,0,0,0-.73.85A2.88,2.88,0,0,0,32.34,43c0,1.54.85,3.56,2.42,5.89l0,0h0L39.86,57c2,3.24,4.18,6.55,6.83,9a13.88,13.88,0,0,0,9.74,3.92,14.36,14.36,0,0,0,10.31-4.09c2.74-2.56,4.91-6.08,7-9.59l5.72-9.43c1.07-2.44,1.46-4.07,1.21-5-.14-.57-.77-.85-1.85-.9-.22,0-.45,0-.69,0l-.8,0a1.58,1.58,0,0,1-.44,0,7.59,7.59,0,0,1-1.57-.08l2-8.68C62.77,34.44,51.89,23.63,36.5,30l1.12,10.23a8.32,8.32,0,0,1-1.75-.07Zm44.95-1.82A3.66,3.66,0,0,1,83.5,41.1c.41,1.6,0,3.85-1.4,6.94h0l-.08.17-5.79,9.54c-2.23,3.67-4.5,7.36-7.52,10.19A17,17,0,0,1,56.44,72.8a16.65,16.65,0,0,1-11.68-4.67,45.54,45.54,0,0,1-7.33-9.56l-5.09-8.08a14.85,14.85,0,0,1-2.88-7.38A5.9,5.9,0,0,1,30,40.47a5,5,0,0,1,1.75-2,5.67,5.67,0,0,1,1.23-.62,129.51,129.51,0,0,1-.24-14.53A19,19,0,0,1,33.32,20,19.43,19.43,0,0,1,41.9,9.05a27.17,27.17,0,0,1,7.2-3.2c1.61-.46-1.38-5.62.3-5.79C57.47-.76,70.52,6.6,76.16,12.7c2.82,3.06,4.58,7.09,5,12.45l-.31,13.18Z"
                    />
                  </svg>
                  <div class="chosse-heading-one-td">No Collateral Needed</div>
                  <div class="choose-para-one-td">
                    You shouldn’t have to risk assets or property to pursue your
                    goals. Our loans are accessible without collateral, opening
                    doors for all determined students.
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    width="64"
                    height="64"
                    viewBox="0 0 64 64"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    stroke="white"
                    stroke-width="5"
                  >
                    <!-- Tag icon -->
                    <path
                      d="M39 3H20C17.8 3 16 4.8 16 7V26L37 47L60 24L39 3Z"
                    />
                    <circle cx="24" cy="12" r="2.5" />

                    <!-- Briefcase icon -->
                    <rect x="4" y="36" width="40" height="24" rx="2" />
                    <path
                      d="M16 36V30C16 28.9 16.9 28 18 28H30C31.1 28 32 28.9 32 30V36"
                    />
                    <line x1="4" y1="48" x2="44" y2="48" />
                  </svg>

                  <div class="chosse-heading-one-td">
                    Special Discounts & Work-Study Opportunities
                  </div>
                  <div class="choose-para-one-td">
                    <div class="choose-para-one-td">
                      Benefit from exclusive discounts and gain valuable
                      experience through work-study programs that let you earn
                      while you learn—empowering your growth both academically
                      and financially.
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    id="Layer_1"
                    data-name="Layer 1"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 122.88 107.29"
                  >
                    <defs>
                      <style>
                        .cls-1 {
                          fill-rule: evenodd;
                        }
                      </style>
                    </defs>
                    <title>workplace</title>
                    <path
                      class="cls-1 stroke-td"
                      d="M28,18.22a1,1,0,0,0-.36.42,1.54,1.54,0,0,0-.12.67,5.83,5.83,0,0,0,1.2,2.91h0l2.51,4a21.61,21.61,0,0,0,3.37,4.45,6.91,6.91,0,0,0,4.82,1.94,7.12,7.12,0,0,0,5.1-2A22.16,22.16,0,0,0,48,25.85l2.83-4.66a10.13,10.13,0,0,0,.53-1.48c.45-1.8-.72-1.4-2.08-1.43a4.23,4.23,0,0,1-.5-.05l1-3.69c-7.2,1.14-12.58-4.21-20.18-1.07l.55,4.46c-.77,0-1.41-.21-2.13.29ZM74.68,10H114.3a3.25,3.25,0,0,1,3.23,3.23V39.48a3.25,3.25,0,0,1-3.23,3.23H99.54l2.52,6.35H118a4.89,4.89,0,0,1,4.88,4.88V65.08A4.91,4.91,0,0,1,118,70h-2.47V103.6a3.69,3.69,0,0,1-3.69,3.69H11.59a3.73,3.73,0,0,1-3.71-3.72V70h-3A4.9,4.9,0,0,1,0,65.07V53.93a4.88,4.88,0,0,1,4.88-4.87h7.59c0-1.35,0-2.11,0-3.46,0-8.5,14.26-7.89,19.18-11.1a27.65,27.65,0,0,0,1.29-3.38L33,31A26.54,26.54,0,0,1,30,27l-2.51-4a7.27,7.27,0,0,1-1.43-3.64,3,3,0,0,1,.25-1.32,2.42,2.42,0,0,1,.86-1,3.07,3.07,0,0,1,.6-.31,55.24,55.24,0,0,1-.12-6.59A10.62,10.62,0,0,1,28,8.52a10.53,10.53,0,0,1,7-6.76c1.57-.54,1-1.83,2.56-1.76,3.74.21,9.53,2.63,11.76,5.18C52.4,8.78,51.6,12.6,51.49,17h0a1.78,1.78,0,0,1,1.32,1.36,5.73,5.73,0,0,1-.68,3.43h0a.14.14,0,0,1-.05.08l-2.86,4.71a23,23,0,0,1-3.73,5l-.06.09.46.67c.5.73,1.06,1.56,1.59,2.21,3.94,2.45,17.81,3,18.05,9.13l.21,5.35H87l3-6.35H74.68a3.25,3.25,0,0,1-3.23-3.23V13.2A3.24,3.24,0,0,1,74.68,10Zm-70,43.76a.3.3,0,0,0-.08.2V65.07a.27.27,0,0,0,.27.29H118a.27.27,0,0,0,.28-.29V53.93a.27.27,0,0,0-.09-.2.29.29,0,0,0-.2-.09c-10.48,0-113-.14-113.28.09ZM12.47,70v32.73H111V70ZM34.92,39a3.1,3.1,0,0,1,0-4.55,10.66,10.66,0,0,1,3.78,2,2.21,2.21,0,0,1,1-.16,24.37,24.37,0,0,1,4.13-2.12c1.85,1.8,1.66,3.47-.16,5a8.27,8.27,0,0,1-2.32-1.07,3.27,3.27,0,0,1-.2.79l2,7.81c1.54-3.24,3.14-6.6,3.54-10.79a30.66,30.66,0,0,1-2-2.71c-.15-.21-.28-.4-.4-.59A8.33,8.33,0,0,1,39.44,34a8.08,8.08,0,0,1-5.29-1.9,16.7,16.7,0,0,1-1.53,3.59,1.07,1.07,0,0,1-.14.16A38.16,38.16,0,0,0,36.26,46.7l2-7.81a2.4,2.4,0,0,1-.4-1.29,8.88,8.88,0,0,1-3,1.36Z"
                    />
                  </svg>
                  <div class="chosse-heading-one-td">
                    Supporting Your Financial Wellness
                  </div>
                  <div class="choose-para-one-td">
                    <div class="choose-para-one-td">
                      We’re here to help you build good financial habits. Our
                      financial literacy programs equip you with the knowledge
                      to manage credit and wealth responsibly—setting you up for
                      success beyond graduation.
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card-custom">
                  <svg
                    style="width: 36px"
                    version="1.1"
                    id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px"
                    y="0px"
                    viewBox="0 0 122.88 91.12"
                    style="enable-background: new 0 0 122.88 91.12"
                    xml:space="preserve"
                  >
                    <g>
                      <path
                        class="st0 stroke-td"
                        d="M33.32,70.81l5.07,14.9l2.55-8.84l-1.25-1.36c-0.57-0.82-0.69-1.54-0.38-2.16c0.68-1.33,2.08-1.09,3.38-1.09 c1.37,0,3.06-0.26,3.49,1.45c0.15,0.58-0.04,1.17-0.44,1.79l-1.25,1.36l2.55,8.84l4.59-14.9c3.31,2.98,13.1,3.58,16.75,5.61 c1.15,0.64,2.19,1.46,3.03,2.57c1.27,1.67,2.05,3.87,2.26,6.65l0.76,4.8c-0.02,0.24-0.06,0.47-0.11,0.69h-63.7 c-0.05-0.22-0.09-0.45-0.11-0.69l0.76-4.8c0.21-2.78,0.99-4.97,2.26-6.65c0.84-1.11,1.88-1.92,3.04-2.57 C20.22,74.38,30.01,73.79,33.32,70.81L33.32,70.81L33.32,70.81z M70.12,0.02H57.68c-1.09,0-2.12,0.2-3.09,0.59 C53.63,1,52.76,1.59,51.98,2.37c-0.78,0.78-1.37,1.65-1.76,2.61c-0.39,0.95-0.59,1.98-0.59,3.09v12.15c0,1.11,0.2,2.14,0.59,3.09 c0.39,0.95,0.98,1.83,1.76,2.61c0.78,0.78,1.65,1.36,2.61,1.76c0.95,0.39,1.98,0.59,3.09,0.59h4.03c0.7,0.89,1.47,1.73,2.28,2.5 c0.97,0.91,2,1.72,3.11,2.44c1.11,0.73,2.3,1.37,3.56,1.92c1.25,0.55,2.59,1,4,1.37c0.41,0.11,0.84-0.02,1.14-0.34 c0.42-0.47,0.38-1.19-0.09-1.61c-0.66-0.58-1.22-1.17-1.67-1.76c-0.44-0.58-0.8-1.17-1.06-1.78v-0.02c-0.3-0.66-0.56-1.33-0.8-2 c-0.08-0.23-0.16-0.48-0.23-0.73l3.84,0c1.09,0,2.12-0.2,3.09-0.59c0.95-0.39,1.83-0.98,2.61-1.76c0.78-0.78,1.37-1.65,1.76-2.61 c0.39-0.97,0.59-2,0.59-3.09V8.06c0-1.09-0.2-2.12-0.59-3.09c-0.39-0.95-0.98-1.83-1.76-2.61c-0.78-0.78-1.66-1.37-2.61-1.76 C77.91,0.2,76.88,0,75.77,0h-5.64L70.12,0.02L70.12,0.02L70.12,0.02z M69.48,9c0-0.01,0.05-0.04,0.05-0.05 c0.66-0.48,1.6-0.35,2.1,0.3c0.5,0.66,0.38,1.6-0.27,2.11l-2.48,2.63h-0.01c0.94,0.84,1.94,1.73,3.15,2.86 c0.01-0.01,0.04,0.04,0.04,0.05c0.49,0.66,0.36,1.59-0.3,2.1c-0.65,0.5-1.59,0.38-2.1-0.27l-2.68-2.56l-2.49,2.87 c-0.45,0.7-1.38,0.9-2.08,0.45c-0.7-0.44-0.92-1.36-0.49-2.06c0.01-0.01,0.04-0.06,0.04-0.06c1.1-1.22,1.98-2.21,2.81-3.15 l-2.8-2.36c-0.7-0.45-0.9-1.38-0.45-2.08c0.44-0.7,1.36-0.91,2.06-0.48c0.01,0.01,0.06,0.04,0.06,0.04 c1.18,1.06,2.14,1.92,3.05,2.72C67.51,11.15,68.39,10.19,69.48,9L69.48,9L69.48,9z M63.31,2.3h12.44c0.81,0,1.56,0.14,2.23,0.42 c0.67,0.28,1.29,0.7,1.86,1.26s0.98,1.19,1.26,1.86s0.42,1.42,0.42,2.23v12.15c0,0.81-0.14,1.56-0.42,2.23 c-0.28,0.67-0.7,1.3-1.26,1.86c-0.58,0.56-1.2,1-1.87,1.26C77.3,25.86,76.57,26,75.76,26h-5.34c-0.63,0-1.14,0.52-1.14,1.14 c0,0.14,0.03,0.27,0.06,0.39c0.2,0.75,0.42,1.5,0.67,2.22c0.27,0.76,0.55,1.48,0.86,2.19c0.16,0.36,0.34,0.7,0.55,1.06 c-1.09-0.48-2.12-1.05-3.08-1.67c-1-0.66-1.94-1.39-2.79-2.19c-0.87-0.81-1.65-1.7-2.37-2.65c-0.22-0.3-0.56-0.45-0.91-0.45h-4.59 c-0.81,0-1.55-0.14-2.22-0.42c-0.67-0.28-1.3-0.7-1.87-1.27c-0.56-0.56-0.98-1.19-1.26-1.86s-0.42-1.42-0.42-2.23V8.1 c0-0.81,0.14-1.56,0.42-2.23c0.28-0.67,0.7-1.3,1.26-1.86c0.56-0.56,1.19-0.98,1.86-1.26c0.67-0.28,1.42-0.42,2.23-0.42h5.64V2.3 L63.31,2.3L63.31,2.3z M12.66,10.1h11.5c0.75,0,1.44,0.13,2.06,0.39c0.62,0.26,1.2,0.65,1.72,1.17c0.52,0.52,0.91,1.1,1.17,1.72 c0.26,0.62,0.39,1.31,0.39,2.06v11.23c0,0.75-0.13,1.44-0.39,2.06c-0.26,0.62-0.65,1.2-1.17,1.72c-0.53,0.52-1.11,0.92-1.73,1.17 c-0.62,0.26-1.3,0.39-2.05,0.39h-4.94c-0.58,0-1.05,0.48-1.05,1.05c0,0.13,0.03,0.25,0.06,0.36c0.19,0.69,0.39,1.39,0.62,2.05 c0.25,0.71,0.5,1.37,0.79,2.02c0.14,0.33,0.32,0.65,0.5,0.98c-1.01-0.45-1.96-0.97-2.84-1.54c-0.92-0.61-1.79-1.28-2.58-2.02 c-0.81-0.75-1.53-1.57-2.19-2.45c-0.2-0.27-0.52-0.42-0.84-0.42H7.45c-0.75,0-1.43-0.13-2.05-0.39C4.78,31.39,4.2,31,3.67,30.48 c-0.52-0.52-0.91-1.1-1.17-1.72s-0.39-1.31-0.39-2.06V15.47c0-0.75,0.13-1.44,0.39-2.06c0.26-0.62,0.65-1.2,1.17-1.72 c0.52-0.52,1.1-0.91,1.72-1.17C6,10.26,6.7,10.13,7.45,10.13h5.21V10.1L12.66,10.1L12.66,10.1z M9.71,23.68 c-0.35-0.33-0.53-0.78-0.54-1.23C9.17,22,9.33,21.55,9.67,21.2c0.33-0.35,0.78-0.53,1.22-0.54c0.45-0.02,0.91,0.15,1.25,0.48 l2.15,2.06l5.15-6.35c0-0.01,0.05-0.05,0.06-0.05c0.72-0.63,1.81-0.56,2.45,0.15c0.64,0.72,0.59,1.81-0.11,2.46l-6.35,7.5 c0,0-0.04,0.04-0.05,0.05c-0.34,0.29-0.76,0.44-1.19,0.43c-0.43-0.01-0.85-0.17-1.19-0.48L9.71,23.68L9.71,23.68L9.71,23.68z M18.95,8H7.45C6.44,8,5.48,8.19,4.59,8.55C3.71,8.91,2.9,9.46,2.18,10.18s-1.27,1.53-1.63,2.41C0.19,13.47,0,14.42,0,15.45v11.23 c0,1.02,0.19,1.98,0.55,2.86c0.36,0.88,0.91,1.69,1.63,2.41c0.72,0.72,1.53,1.26,2.41,1.63c0.88,0.36,1.83,0.55,2.86,0.55h3.72 c0.65,0.82,1.36,1.6,2.11,2.31c0.9,0.84,1.85,1.59,2.87,2.25c1.02,0.68,2.12,1.27,3.29,1.78c1.15,0.5,2.4,0.92,3.69,1.27 c0.38,0.1,0.78-0.01,1.05-0.32c0.39-0.43,0.35-1.1-0.09-1.49c-0.61-0.53-1.13-1.08-1.54-1.63c-0.4-0.53-0.74-1.08-0.98-1.65v-0.01 c-0.27-0.61-0.52-1.23-0.74-1.85c-0.07-0.22-0.14-0.45-0.22-0.68h3.55c1.01,0,1.96-0.19,2.86-0.55c0.88-0.36,1.69-0.91,2.41-1.63 c0.72-0.72,1.27-1.53,1.63-2.41c0.36-0.89,0.55-1.85,0.55-2.86V15.43c0-1.01-0.19-1.96-0.55-2.86c-0.36-0.88-0.91-1.69-1.63-2.41 c-0.72-0.72-1.53-1.27-2.41-1.63c-0.88-0.36-1.83-0.55-2.86-0.55h-5.21L18.95,8L18.95,8L18.95,8z M79.02,59.3l2.88-0.07l2.4-0.06 c-2.8-8.6-1.86-16.51,4.86-23.22c1.14,3.69,3.7,6.73,8.05,8.98c2.08,1.55,4.1,3.41,6.04,5.56c0.35-1.42-0.97-3.14-2.57-4.93 c1.48,0.73,2.83,1.75,3.8,3.72c1.12,2.28,1.11,4.21,0.73,6.68c-0.17,1.15-0.46,2.22-0.85,3.21h3.98c4.2-8.99,1.54-22.33-7.05-27.98 c-2.63-1.73-4.53-1.66-7.62-1.66c-3.54,0-5.35,0.11-8.38,2.11c-4.47,2.95-7.21,8.07-8.37,15.17C76.7,50.34,76.54,56.46,79.02,59.3 L79.02,59.3L79.02,59.3z M89.97,75.66l2.15-7.46l-1.05-1.15c-0.48-0.7-0.58-1.3-0.31-1.82c0.57-1.13,1.76-0.91,2.85-0.91 c1.15,0,2.58-0.22,2.94,1.22c0.12,0.49-0.03,0.99-0.37,1.51l-1.05,1.15l2.15,7.46l-3.66,2.89L89.97,75.66L89.97,75.66L89.97,75.66z M109.2,73.08c-1.63-3.26-3.69-6.04-6.1-8.64c4.53,1.76,9.15,3.48,12.58,5.63c2.18,1.36,3.31,2.4,4.19,4.05 c1.87,3.52,2.08,6.68,2.36,10.49l0.65,6.51H79.3c-0.17-4.04,0-7.05-1.34-10.94c-0.57-1.64-1.34-3.1-2.31-4.39 c-0.65-0.86-1.41-1.63-2.25-2.33c-0.79-0.66-1.61-1.21-2.44-1.67c-0.35-0.19-0.71-0.38-1.1-0.54c0.49-0.38,1.06-0.77,1.72-1.18 c3.43-2.15,8.05-3.88,12.58-5.63c-2.41,2.61-4.47,5.38-6.1,8.64l4.72-0.12l10.85,9.44l10.85-9.44L109.2,73.08L109.2,73.08 L109.2,73.08z M30.73,49.64c-0.46,0.04-0.8,0.15-1.06,0.33c-0.16,0.11-0.28,0.25-0.37,0.42c-0.09,0.19-0.13,0.43-0.12,0.69 c0.03,0.83,0.47,1.94,1.33,3.21l0.02,0.02l0,0l2.83,4.5c1.13,1.8,2.31,3.63,3.79,4.97c1.4,1.28,3.1,2.15,5.34,2.15 c2.43,0.01,4.21-0.9,5.65-2.25c1.51-1.42,2.72-3.37,3.9-5.31l3.18-5.24c0.65-1.47,0.84-2.38,0.63-2.81 c-0.12-0.25-0.56-0.34-1.27-0.3c-0.49,0.15-1.07,0.13-1.75-0.05l1.29-4.95c-5.83-0.07-9.82-1.09-14.54-4.11 c-1.55-0.99-2.02-2.12-3.57-2.01c-1.17,0.23-2.16,0.75-2.94,1.59c-0.75,0.8-1.32,1.91-1.68,3.32l0.94,5.85 C31.7,49.8,31.17,49.78,30.73,49.64L30.73,49.64L30.73,49.64z M56.07,48.33c0.67,0.2,1.15,0.59,1.44,1.18 c0.48,0.96,0.28,2.39-0.61,4.43l0,0c-0.02,0.04-0.04,0.07-0.05,0.11l-3.23,5.31c-1.25,2.06-2.51,4.12-4.22,5.71 c-1.76,1.65-3.95,2.76-6.93,2.75c-2.78-0.01-4.88-1.07-6.59-2.64c-1.65-1.51-2.91-3.45-4.11-5.35l-2.83-4.5 c-1.05-1.56-1.59-3-1.63-4.18c-0.02-0.57,0.08-1.08,0.29-1.54c0.22-0.48,0.56-0.88,1.02-1.19c0.22-0.15,0.48-0.27,0.75-0.38 c-0.17-2.42-0.24-5.43-0.12-7.96c0.06-0.61,0.17-1.23,0.35-1.84c0.72-2.59,2.54-4.66,4.78-6.1c0.8-0.5,1.65-0.92,2.58-1.25 c5.44-1.97,12.65-0.9,16.51,3.28c1.57,1.7,2.56,3.96,2.77,6.94L56.07,48.33L56.07,48.33L56.07,48.33z"
                      />
                    </g>
                  </svg>
                  <!--<img src="image/negotiation.png">-->
                  <div class="chosse-heading-one-td">
                    Building Confidence Inside and Out
                  </div>
                  <div class="choose-para-one-td">
                    <div class="choose-para-one-td">
                      Facing interviews? We understand the nerves. Our tailored
                      training programs turn anxiety into confidence, helping
                      you shine in every opportunity that comes your way.
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-4">
                <div class="card-custom">
                  <svg
                    style="width: 36px"
                    version="1.1"
                    id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px"
                    y="0px"
                    viewBox="0 0 122.88 91.12"
                    style="enable-background: new 0 0 122.88 91.12"
                    xml:space="preserve"
                  >
                    <g>
                      <path
                        class="st0 stroke-td"
                        d="M33.32,70.81l5.07,14.9l2.55-8.84l-1.25-1.36c-0.57-0.82-0.69-1.54-0.38-2.16c0.68-1.33,2.08-1.09,3.38-1.09 c1.37,0,3.06-0.26,3.49,1.45c0.15,0.58-0.04,1.17-0.44,1.79l-1.25,1.36l2.55,8.84l4.59-14.9c3.31,2.98,13.1,3.58,16.75,5.61 c1.15,0.64,2.19,1.46,3.03,2.57c1.27,1.67,2.05,3.87,2.26,6.65l0.76,4.8c-0.02,0.24-0.06,0.47-0.11,0.69h-63.7 c-0.05-0.22-0.09-0.45-0.11-0.69l0.76-4.8c0.21-2.78,0.99-4.97,2.26-6.65c0.84-1.11,1.88-1.92,3.04-2.57 C20.22,74.38,30.01,73.79,33.32,70.81L33.32,70.81L33.32,70.81z M70.12,0.02H57.68c-1.09,0-2.12,0.2-3.09,0.59 C53.63,1,52.76,1.59,51.98,2.37c-0.78,0.78-1.37,1.65-1.76,2.61c-0.39,0.95-0.59,1.98-0.59,3.09v12.15c0,1.11,0.2,2.14,0.59,3.09 c0.39,0.95,0.98,1.83,1.76,2.61c0.78,0.78,1.65,1.36,2.61,1.76c0.95,0.39,1.98,0.59,3.09,0.59h4.03c0.7,0.89,1.47,1.73,2.28,2.5 c0.97,0.91,2,1.72,3.11,2.44c1.11,0.73,2.3,1.37,3.56,1.92c1.25,0.55,2.59,1,4,1.37c0.41,0.11,0.84-0.02,1.14-0.34 c0.42-0.47,0.38-1.19-0.09-1.61c-0.66-0.58-1.22-1.17-1.67-1.76c-0.44-0.58-0.8-1.17-1.06-1.78v-0.02c-0.3-0.66-0.56-1.33-0.8-2 c-0.08-0.23-0.16-0.48-0.23-0.73l3.84,0c1.09,0,2.12-0.2,3.09-0.59c0.95-0.39,1.83-0.98,2.61-1.76c0.78-0.78,1.37-1.65,1.76-2.61 c0.39-0.97,0.59-2,0.59-3.09V8.06c0-1.09-0.2-2.12-0.59-3.09c-0.39-0.95-0.98-1.83-1.76-2.61c-0.78-0.78-1.66-1.37-2.61-1.76 C77.91,0.2,76.88,0,75.77,0h-5.64L70.12,0.02L70.12,0.02L70.12,0.02z M69.48,9c0-0.01,0.05-0.04,0.05-0.05 c0.66-0.48,1.6-0.35,2.1,0.3c0.5,0.66,0.38,1.6-0.27,2.11l-2.48,2.63h-0.01c0.94,0.84,1.94,1.73,3.15,2.86 c0.01-0.01,0.04,0.04,0.04,0.05c0.49,0.66,0.36,1.59-0.3,2.1c-0.65,0.5-1.59,0.38-2.1-0.27l-2.68-2.56l-2.49,2.87 c-0.45,0.7-1.38,0.9-2.08,0.45c-0.7-0.44-0.92-1.36-0.49-2.06c0.01-0.01,0.04-0.06,0.04-0.06c1.1-1.22,1.98-2.21,2.81-3.15 l-2.8-2.36c-0.7-0.45-0.9-1.38-0.45-2.08c0.44-0.7,1.36-0.91,2.06-0.48c0.01,0.01,0.06,0.04,0.06,0.04 c1.18,1.06,2.14,1.92,3.05,2.72C67.51,11.15,68.39,10.19,69.48,9L69.48,9L69.48,9z M63.31,2.3h12.44c0.81,0,1.56,0.14,2.23,0.42 c0.67,0.28,1.29,0.7,1.86,1.26s0.98,1.19,1.26,1.86s0.42,1.42,0.42,2.23v12.15c0,0.81-0.14,1.56-0.42,2.23 c-0.28,0.67-0.7,1.3-1.26,1.86c-0.58,0.56-1.2,1-1.87,1.26C77.3,25.86,76.57,26,75.76,26h-5.34c-0.63,0-1.14,0.52-1.14,1.14 c0,0.14,0.03,0.27,0.06,0.39c0.2,0.75,0.42,1.5,0.67,2.22c0.27,0.76,0.55,1.48,0.86,2.19c0.16,0.36,0.34,0.7,0.55,1.06 c-1.09-0.48-2.12-1.05-3.08-1.67c-1-0.66-1.94-1.39-2.79-2.19c-0.87-0.81-1.65-1.7-2.37-2.65c-0.22-0.3-0.56-0.45-0.91-0.45h-4.59 c-0.81,0-1.55-0.14-2.22-0.42c-0.67-0.28-1.3-0.7-1.87-1.27c-0.56-0.56-0.98-1.19-1.26-1.86s-0.42-1.42-0.42-2.23V8.1 c0-0.81,0.14-1.56,0.42-2.23c0.28-0.67,0.7-1.3,1.26-1.86c0.56-0.56,1.19-0.98,1.86-1.26c0.67-0.28,1.42-0.42,2.23-0.42h5.64V2.3 L63.31,2.3L63.31,2.3z M12.66,10.1h11.5c0.75,0,1.44,0.13,2.06,0.39c0.62,0.26,1.2,0.65,1.72,1.17c0.52,0.52,0.91,1.1,1.17,1.72 c0.26,0.62,0.39,1.31,0.39,2.06v11.23c0,0.75-0.13,1.44-0.39,2.06c-0.26,0.62-0.65,1.2-1.17,1.72c-0.53,0.52-1.11,0.92-1.73,1.17 c-0.62,0.26-1.3,0.39-2.05,0.39h-4.94c-0.58,0-1.05,0.48-1.05,1.05c0,0.13,0.03,0.25,0.06,0.36c0.19,0.69,0.39,1.39,0.62,2.05 c0.25,0.71,0.5,1.37,0.79,2.02c0.14,0.33,0.32,0.65,0.5,0.98c-1.01-0.45-1.96-0.97-2.84-1.54c-0.92-0.61-1.79-1.28-2.58-2.02 c-0.81-0.75-1.53-1.57-2.19-2.45c-0.2-0.27-0.52-0.42-0.84-0.42H7.45c-0.75,0-1.43-0.13-2.05-0.39C4.78,31.39,4.2,31,3.67,30.48 c-0.52-0.52-0.91-1.1-1.17-1.72s-0.39-1.31-0.39-2.06V15.47c0-0.75,0.13-1.44,0.39-2.06c0.26-0.62,0.65-1.2,1.17-1.72 c0.52-0.52,1.1-0.91,1.72-1.17C6,10.26,6.7,10.13,7.45,10.13h5.21V10.1L12.66,10.1L12.66,10.1z M9.71,23.68 c-0.35-0.33-0.53-0.78-0.54-1.23C9.17,22,9.33,21.55,9.67,21.2c0.33-0.35,0.78-0.53,1.22-0.54c0.45-0.02,0.91,0.15,1.25,0.48 l2.15,2.06l5.15-6.35c0-0.01,0.05-0.05,0.06-0.05c0.72-0.63,1.81-0.56,2.45,0.15c0.64,0.72,0.59,1.81-0.11,2.46l-6.35,7.5 c0,0-0.04,0.04-0.05,0.05c-0.34,0.29-0.76,0.44-1.19,0.43c-0.43-0.01-0.85-0.17-1.19-0.48L9.71,23.68L9.71,23.68L9.71,23.68z M18.95,8H7.45C6.44,8,5.48,8.19,4.59,8.55C3.71,8.91,2.9,9.46,2.18,10.18s-1.27,1.53-1.63,2.41C0.19,13.47,0,14.42,0,15.45v11.23 c0,1.02,0.19,1.98,0.55,2.86c0.36,0.88,0.91,1.69,1.63,2.41c0.72,0.72,1.53,1.26,2.41,1.63c0.88,0.36,1.83,0.55,2.86,0.55h3.72 c0.65,0.82,1.36,1.6,2.11,2.31c0.9,0.84,1.85,1.59,2.87,2.25c1.02,0.68,2.12,1.27,3.29,1.78c1.15,0.5,2.4,0.92,3.69,1.27 c0.38,0.1,0.78-0.01,1.05-0.32c0.39-0.43,0.35-1.1-0.09-1.49c-0.61-0.53-1.13-1.08-1.54-1.63c-0.4-0.53-0.74-1.08-0.98-1.65v-0.01 c-0.27-0.61-0.52-1.23-0.74-1.85c-0.07-0.22-0.14-0.45-0.22-0.68h3.55c1.01,0,1.96-0.19,2.86-0.55c0.88-0.36,1.69-0.91,2.41-1.63 c0.72-0.72,1.27-1.53,1.63-2.41c0.36-0.89,0.55-1.85,0.55-2.86V15.43c0-1.01-0.19-1.96-0.55-2.86c-0.36-0.88-0.91-1.69-1.63-2.41 c-0.72-0.72-1.53-1.27-2.41-1.63c-0.88-0.36-1.83-0.55-2.86-0.55h-5.21L18.95,8L18.95,8L18.95,8z M79.02,59.3l2.88-0.07l2.4-0.06 c-2.8-8.6-1.86-16.51,4.86-23.22c1.14,3.69,3.7,6.73,8.05,8.98c2.08,1.55,4.1,3.41,6.04,5.56c0.35-1.42-0.97-3.14-2.57-4.93 c1.48,0.73,2.83,1.75,3.8,3.72c1.12,2.28,1.11,4.21,0.73,6.68c-0.17,1.15-0.46,2.22-0.85,3.21h3.98c4.2-8.99,1.54-22.33-7.05-27.98 c-2.63-1.73-4.53-1.66-7.62-1.66c-3.54,0-5.35,0.11-8.38,2.11c-4.47,2.95-7.21,8.07-8.37,15.17C76.7,50.34,76.54,56.46,79.02,59.3 L79.02,59.3L79.02,59.3z M89.97,75.66l2.15-7.46l-1.05-1.15c-0.48-0.7-0.58-1.3-0.31-1.82c0.57-1.13,1.76-0.91,2.85-0.91 c1.15,0,2.58-0.22,2.94,1.22c0.12,0.49-0.03,0.99-0.37,1.51l-1.05,1.15l2.15,7.46l-3.66,2.89L89.97,75.66L89.97,75.66L89.97,75.66z M109.2,73.08c-1.63-3.26-3.69-6.04-6.1-8.64c4.53,1.76,9.15,3.48,12.58,5.63c2.18,1.36,3.31,2.4,4.19,4.05 c1.87,3.52,2.08,6.68,2.36,10.49l0.65,6.51H79.3c-0.17-4.04,0-7.05-1.34-10.94c-0.57-1.64-1.34-3.1-2.31-4.39 c-0.65-0.86-1.41-1.63-2.25-2.33c-0.79-0.66-1.61-1.21-2.44-1.67c-0.35-0.19-0.71-0.38-1.1-0.54c0.49-0.38,1.06-0.77,1.72-1.18 c3.43-2.15,8.05-3.88,12.58-5.63c-2.41,2.61-4.47,5.38-6.1,8.64l4.72-0.12l10.85,9.44l10.85-9.44L109.2,73.08L109.2,73.08 L109.2,73.08z M30.73,49.64c-0.46,0.04-0.8,0.15-1.06,0.33c-0.16,0.11-0.28,0.25-0.37,0.42c-0.09,0.19-0.13,0.43-0.12,0.69 c0.03,0.83,0.47,1.94,1.33,3.21l0.02,0.02l0,0l2.83,4.5c1.13,1.8,2.31,3.63,3.79,4.97c1.4,1.28,3.1,2.15,5.34,2.15 c2.43,0.01,4.21-0.9,5.65-2.25c1.51-1.42,2.72-3.37,3.9-5.31l3.18-5.24c0.65-1.47,0.84-2.38,0.63-2.81 c-0.12-0.25-0.56-0.34-1.27-0.3c-0.49,0.15-1.07,0.13-1.75-0.05l1.29-4.95c-5.83-0.07-9.82-1.09-14.54-4.11 c-1.55-0.99-2.02-2.12-3.57-2.01c-1.17,0.23-2.16,0.75-2.94,1.59c-0.75,0.8-1.32,1.91-1.68,3.32l0.94,5.85 C31.7,49.8,31.17,49.78,30.73,49.64L30.73,49.64L30.73,49.64z M56.07,48.33c0.67,0.2,1.15,0.59,1.44,1.18 c0.48,0.96,0.28,2.39-0.61,4.43l0,0c-0.02,0.04-0.04,0.07-0.05,0.11l-3.23,5.31c-1.25,2.06-2.51,4.12-4.22,5.71 c-1.76,1.65-3.95,2.76-6.93,2.75c-2.78-0.01-4.88-1.07-6.59-2.64c-1.65-1.51-2.91-3.45-4.11-5.35l-2.83-4.5 c-1.05-1.56-1.59-3-1.63-4.18c-0.02-0.57,0.08-1.08,0.29-1.54c0.22-0.48,0.56-0.88,1.02-1.19c0.22-0.15,0.48-0.27,0.75-0.38 c-0.17-2.42-0.24-5.43-0.12-7.96c0.06-0.61,0.17-1.23,0.35-1.84c0.72-2.59,2.54-4.66,4.78-6.1c0.8-0.5,1.65-0.92,2.58-1.25 c5.44-1.97,12.65-0.9,16.51,3.28c1.57,1.7,2.56,3.96,2.77,6.94L56.07,48.33L56.07,48.33L56.07,48.33z"
                      />
                    </g>
                  </svg>
                  <div class="chosse-heading-one-td">
                    Confidence Starts Here
                  </div>
                  <div class="choose-para-one-td">
                    <div class="choose-para-one-td">
                      Transform interview anxiety into confidence with our
                      expert-led training program tailored for students.
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
          <!-- <div class="col-md-4">
            <div class="choose-section-image">
              <img
                src="image/why-we-choose-us.png"
                alt="Image One Choose Section"
              />
            </div>
          </div> -->
        </div>
      </div>
    </section>
    <!--are you eligible section start-->
    <section>
      <div class="container" style="margin-top: 50px">
        <div class="row">
          <div class="col-md-7">
            <div class="col-md-12">
              <div class="section-title">Check your Eligibility</div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card-custom d-flex">
                  <div class="border-svg color-1">
                    <svg
                      style="width: 30px"
                      class="colorable-icon"
                      viewBox="0 0 384 512"
                      xmlns="http://www.w3.org/2000/svg"
                      data-icon="location-dot"
                      data-prefix="fal"
                      aria-hidden="true"
                    >
                      <path
                        d="M352 192c0-88.4-71.6-160-160-160S32 103.6 32 192c0 15.6 5.4 37 16.6 63.4c10.9 25.9 26.2 54 43.6 82.1c34.1 55.3 74.4 108.2 99.9 140c25.4-31.8 65.8-84.7 99.9-140c17.3-28.1 32.7-56.3 43.6-82.1C346.6 229 352 207.6 352 192zm32 0c0 87.4-117 243-168.3 307.2c-12.3 15.3-35.1 15.3-47.4 0C117 435 0 279.4 0 192C0 86 86 0 192 0S384 86 384 192zm-240 0a48 48 0 1 0 96 0 48 48 0 1 0 -96 0zm48 80a80 80 0 1 1 0-160 80 80 0 1 1 0 160z"
                        fill="currentColor"
                      ></path>
                    </svg>
                  </div>
                  <div class="border-des">
                    <div class="chosse-heading-one-td">Chosen Institutes</div>
                    <div class="choose-para-one-td">
                      You're from or studying in a chosen college from your
                      city.
                    </div>
                    <div class="choose-para-two-td">
                      Find out if your institute qualifies or not…..
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card-custom d-flex">
                  <div class="border-svg color-2">
                    <svg
                      style="width: 30px"
                      class="colorable-icon"
                      viewBox="0 0 512 512"
                      xmlns="http://www.w3.org/2000/svg"
                      data-icon="certificate"
                      data-prefix="fal"
                      aria-hidden="true"
                    >
                      <path
                        d="M170.7 108.3c7.5-4.3 12.9-11.5 15-19.8l12.3-48.6 35 36c6 6.2 14.3 9.7 22.9 9.7s16.9-3.5 22.9-9.7l35-36 12.3 48.6c2.1 8.4 7.5 15.5 15 19.8s16.4 5.4 24.7 3.1l48.3-13.6L400.6 146c-2.3 8.3-1.2 17.2 3.1 24.7s11.5 12.9 19.8 15l48.6 12.3-36 35c-6.2 6-9.7 14.3-9.7 22.9s3.5 16.9 9.7 22.9l36 35-48.6 12.3c-8.4 2.1-15.5 7.5-19.8 15s-5.4 16.4-3.1 24.7l13.6 48.3L366 400.6c-8.3-2.3-17.2-1.2-24.7 3.1s-12.9 11.5-15 19.8l-12.3 48.6-35-36c-6-6.2-14.3-9.7-22.9-9.7s-16.9 3.5-22.9 9.7l-35 36-12.3-48.6c-2.1-8.4-7.5-15.5-15-19.8s-16.4-5.4-24.7-3.1L97.8 414.2 111.4 366c2.3-8.3 1.2-17.2-3.1-24.7s-11.5-12.9-19.8-15L39.8 313.9l36-35c6.2-6 9.7-14.3 9.7-22.9s-3.5-16.9-9.7-22.9l-36-35 48.6-12.3c8.4-2.1 15.5-7.5 19.8-15s5.4-16.4 3.1-24.7L97.8 97.8 146 111.4c8.3 2.3 17.2 1.2 24.7-3.1zM49.6 162.6l-31.5 8c-8.4 2.1-15 8.7-17.3 17.1S1 205 7.3 211l23.3 22.6L53.5 256 30.5 278.3 7.3 301C1 307-1.4 316 .8 324.4s8.9 14.9 17.3 17.1l31.5 8 31 7.9-8.7 30.8-8.8 31.2c-2.4 8.4 0 17.4 6.1 23.5s15.1 8.5 23.5 6.1l31.2-8.8 30.8-8.7 7.9 31 8 31.5c2.1 8.4 8.7 15 17.1 17.3s17.3-.2 23.4-6.4l22.6-23.3L256 458.5l22.3 22.9L301 504.7c6.1 6.2 15 8.7 23.4 6.4s14.9-8.9 17.1-17.3l8-31.5 7.9-31 30.8 8.7 31.2 8.8c8.4 2.4 17.4 0 23.5-6.1s8.5-15.1 6.1-23.5l-8.8-31.2-8.7-30.8 31-7.9 31.5-8c8.4-2.1 15-8.7 17.3-17.1s-.2-17.4-6.4-23.4l-23.3-22.6L458.5 256l22.9-22.3L504.7 211c6.2-6.1 8.7-15 6.4-23.4s-8.9-14.9-17.3-17.1l-31.5-8-31-7.9 8.7-30.8 8.8-31.2c2.4-8.4 0-17.4-6.1-23.5s-15.1-8.5-23.5-6.1l-31.2 8.8-30.8 8.7-7.9-31-8-31.5c-2.1-8.4-8.7-15-17.1-17.3S307 1 301 7.3L278.3 30.5 256 53.5 233.7 30.5 211 7.3C205 1 196-1.4 187.6 .8s-14.9 8.9-17.1 17.3l-8 31.5-7.9 31-30.8-8.7L92.7 63.1c-8.4-2.4-17.4 0-23.5 6.1s-8.5 15.1-6.1 23.5l8.8 31.2 8.7 30.8-31 7.9z"
                        fill="currentColor"
                      ></path>
                    </svg>
                  </div>
                  <div class="border-des">
                    <div class="chosse-heading-one-td">Recognized Program</div>
                    <div class="choose-para-one-td">
                      You're enrolled in an accredited educational course or
                      program.
                    </div>
                    <div class="choose-para-two-td">
                      Find out if your course is recognized by campusdunia…..
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card-custom d-flex">
                  <div class="border-svg color-3">
                    <svg
                      style="width: 30px"
                      class="colorable-icon"
                      viewBox="0 0 512 512"
                      xmlns="http://www.w3.org/2000/svg"
                      data-icon="stars"
                      data-prefix="fal"
                      aria-hidden="true"
                    >
                      <path
                        d="M352 16V80h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H352v64c0 8.8-7.2 16-16 16s-16-7.2-16-16V112H256c-8.8 0-16-7.2-16-16s7.2-16 16-16h64V16c0-8.8 7.2-16 16-16s16 7.2 16 16zM152.8 265.5c-4.7 9.5-13.7 16-24.1 17.5L41.2 295.8l63.4 61.9c7.5 7.3 11 17.9 9.2 28.3l-15 87.3L177 432.1c9.3-4.9 20.5-4.9 29.8 0L285 473.3l-15-87.3c-1.8-10.4 1.7-20.9 9.2-28.3l63.4-61.9-87.5-12.7c-10.4-1.5-19.4-8.1-24.1-17.5l-39.1-79.4-39.1 79.4zm17.6-108.1c8.8-17.9 34.3-17.9 43.1 0l46.3 94 103.5 15.1c19.7 2.9 27.5 27 13.3 40.9l-74.9 73.2 17.7 103.3c3.4 19.6-17.2 34.6-34.8 25.3l-92.6-48.8L99.3 509.2c-17.6 9.3-38.2-5.7-34.8-25.3L82.2 380.6 7.2 307.4C-7 293.5 .9 269.3 20.5 266.5l103.5-15.1 46.3-94zM448 160c8.8 0 16 7.2 16 16v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H464v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V240H400c-8.8 0-16-7.2-16-16s7.2-16 16-16h32V176c0-8.8 7.2-16 16-16z"
                        fill="currentColor"
                      ></path>
                    </svg>
                  </div>
                  <div class="border-des">
                    <div class="chosse-heading-one-td">
                      Demonstrated Potential
                    </div>
                    <div class="choose-para-one-td">
                      You show academic promise or skill aptitude in your field.
                    </div>
                    <div class="choose-para-one-td">
                      Upload your documents like ….
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card-custom d-flex">
                  <div class="border-svg color-4">
                    <svg
                      style="width: 30px"
                      class="colorable-icon"
                      viewBox="0 0 576 512"
                      xmlns="http://www.w3.org/2000/svg"
                      data-icon="hand-holding-heart"
                      data-prefix="fal"
                      aria-hidden="true"
                    >
                      <path
                        d="M163.9 28.9C191.7 .7 235.8-.8 265.3 24.3c5.8 4.9 11 10.4 16.3 15.9c2.1 2.2 4.2 4.5 6.4 6.6c2.1-2.2 4.3-4.4 6.4-6.6c5.2-5.5 10.5-11 16.3-15.9C340.2-.8 384.3 .7 412.1 28.9c29.4 29.8 29.4 78.2 0 108L310.5 240.1c-6.2 6.3-14.3 9.4-22.5 9.4s-16.3-3.1-22.5-9.4L163.9 136.9c-29.4-29.8-29.4-78.2 0-108zm83.6 22.5c-16.8-17.1-44-17.1-60.8 0c-17.1 17.4-17.1 45.7 0 63.1L288 217.3 389.3 114.4c17.1-17.4 17.1-45.7 0-63.1c-16.8-17.1-44-17.1-60.8 0L299.4 80.9c-6.3 6.4-16.5 6.4-22.8 0L247.5 51.4zM151 317.4c13.1-8.8 28.6-13.4 44.4-13.4H344c30.9 0 56 25.1 56 56c0 8.6-1.9 16.7-5.4 24h5.6l94.7-56.4c8.3-4.9 17.8-7.6 27.5-7.6h1.3c28.9 0 52.3 23.4 52.3 52.3c0 17.7-9 34.2-23.8 43.8L432.6 493.9c-18.2 11.8-39.4 18.1-61 18.1H16c-8.8 0-16-7.2-16-16s7.2-16 16-16H371.5c15.5 0 30.6-4.5 43.6-12.9l119.6-77.8c5.8-3.7 9.2-10.2 9.2-17c0-11.2-9.1-20.3-20.3-20.3h-1.3c-3.9 0-7.7 1.1-11.1 3l-98.5 58.7c-2.5 1.5-5.3 2.3-8.2 2.3H344 320 256c-8.8 0-16-7.2-16-16s7.2-16 16-16h64 24c13.3 0 24-10.7 24-24s-10.7-24-24-24H195.4c-9.5 0-18.7 2.8-26.6 8.1L88.9 397.3c-2.6 1.8-5.7 2.7-8.9 2.7H16c-8.8 0-16-7.2-16-16s7.2-16 16-16H75.2L151 317.4z"
                        fill="currentColor"
                      ></path>
                    </svg>
                  </div>
                  <div class="border-des">
                    <div class="chosse-heading-one-td">Financial Need</div>
                    <div class="choose-para-one-td">
                      You face financial constraints that limit your educational
                      opportunities.
                    </div>
                    <div class="choose-para-one-td">
                      Upload your documents like ….
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="choose-section-image-two">
              <img
                src="image/eligible-student-page.jpeg"
                alt="choose-section-image-two"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--simple four step process section start-->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <style>
      /* <!-- Step Process Section CSS Start --> */
      .process-title {
        font-weight: 800;
        font-size: 55px;
        line-height: 85px;
        letter-spacing: 5%;
        text-align: center;
        margin-bottom: 60px;
      }
      .step-wrapper {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        /* gap: 20px; */
      }
      .themed-shape-text-over-background {
        position: relative;
        width: 260px;
        height: 81px;
      }
      .css-1b6tcxl {
        width: 100%;
        height: 100%;
      }
      .css-1j8r2w0 {
        transition: fill 0.3s;
      }
      .arrow-step1 {
        stroke: #f39c12;
        stroke-width: 2;
      }
      .arrow-step2 {
        stroke: #f39c12;
        stroke-width: 2;
      }
      .arrow-step3 {
        stroke: #f39c12;
        stroke-width: 2;
      }
      .arrow-step4 {
        stroke: #f39c12;
        stroke-width: 2;
      }
      .themed-heading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 700;
        text-align: center;
        color: white;
      }
      .themed-heading i {
        font-size: 20px;
      }
      .step-details {
        padding: 20px;
        width: 100%;
      }
      .step-details h5 {
        font-weight: 700;
        font-size: 25px;
        margin-bottom: 10px;
        /* padding-left: 20px; */
      }
      .step-details p {
        font-size: 18px;
        color: #ccc;
        max-width: 220px;
        margin: 0 auto;
      }
      /* <!-- Step Process Section CSS End --> */
    </style>
    <!-- Step Process Section Start -->
    <style>
      .steps {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 40px;
        max-width: 1200px;
        margin: auto;
      }

      .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 8px;
        position: relative;
        padding-right: 20px;
      }

      .step .line {
        width: 2px;
        height: 80px;
        background-color: white;
        /* margin-bottom: 12px; */
      }

      .step .circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #f39c12;
        border: 2px solid white;
        /* margin-bottom: 12px; */
      }

      .title {
        font-weight: 700;
        font-size: 25px;
        color: #f39c12;
        margin-bottom: 20px;
      }

      .desc {
        font-size: 17px;
        color: #ccc;
        /* margin-bottom: 16px; */
      }

      .arrow {
        width: 180px;
        height: 70px;
        clip-path: polygon(0% 0%, 80% 0%, 100% 50%, 80% 100%, 0% 100%, 20% 50%);
        background-color: gray;
        margin-block: 15px;
      }

      .blue {
        background-color: #f39c12;
      }
      .green {
        background-color: #f39c12;
      }
      .lime {
        background-color: #f39c12;
      }

      @media (max-width: 992px) {
        .title {
          margin-bottom: 10px;
        }
        .arrows-row {
          display: none;
        }
      }
      @media (max-width: 768px) {
        .arrows-row {
          display: none;
        }
        .step-Apply {
          margin-block: 15px;
          margin-inline: 5px;
        }
      }

      .step-Apply {
        display: flex;
        max-width: 100%;
      }
      .empty-space {
        max-width: 100%;
      }
    </style>
    <section>
      <!--    <div class="container">-->
      <!--  <h2 class="process-title">Our Simple 4-Step Process</h2>-->

      <!--  <div class="row step-wrapper">-->
      <!-- Step 1 -->
      <!--    <div class="col-md-3">-->
      <!--      <div-->
      <!--        contenteditable="false"-->
      <!--        class="themed-shape-text-over-background"-->
      <!--      >-->
      <!--        <svg class="css-1b6tcxl arrow-step1">-->
      <!--          <path-->
      <!--            d="M 238.5 0 L 258.75 40.5 L 238.5 81 L 0 81 L 20.25 40.5 L 0 0 Z"-->
      <!--            class="css-1j8r2w0"-->
      <!--          ></path>-->
      <!--        </svg>-->
      <!--        <div class="themed-heading"><i class="bi bi-pencil"></i></div>-->
      <!--      </div>-->
      <!--      <div class="step-details">-->
      <!--        <h5>Apply Online</h5>-->
      <!--        <p>-->
      <!--          Complete our simple application form in <br />-->
      <!--          <b style="color: #F39C12"-->
      <!--            ><span id="timer">10:00</span> minutes.</b-->
      <!--          >-->
      <!--        </p>-->
      <!--      </div>-->
      <!--    </div>-->

      <!-- Step 2 -->
      <!--    <div class="col-md-3">-->
      <!--      <div-->
      <!--        contenteditable="false"-->
      <!--        class="themed-shape-text-over-background"-->
      <!--      >-->
      <!--        <svg class="css-1b6tcxl arrow-step2">-->
      <!--          <path-->
      <!--            d="M 238.5 0 L 258.75 40.5 L 238.5 81 L 0 81 L 20.25 40.5 L 0 0 Z"-->
      <!--            class="css-1j8r2w0"-->
      <!--          ></path>-->
      <!--        </svg>-->
      <!--        <div class="themed-heading">-->
      <!--          <i class="bi bi-check2-square"></i>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="step-details">-->
      <!--        <h5>Get Approval</h5>-->
      <!--        <p>-->
      <!--          Receive a fast decision based on your potential, not just-->
      <!--          finances.-->
      <!--        </p>-->
      <!--      </div>-->
      <!--    </div>-->

      <!-- Step 3 -->
      <!--    <div class="col-md-3">-->
      <!--      <div-->
      <!--        contenteditable="false"-->
      <!--        class="themed-shape-text-over-background"-->
      <!--      >-->
      <!--        <svg class="css-1b6tcxl arrow-step3">-->
      <!--          <path-->
      <!--            d="M 238.5 0 L 258.75 40.5 L 238.5 81 L 0 81 L 20.25 40.5 L 0 0 Z"-->
      <!--            class="css-1j8r2w0"-->
      <!--          ></path>-->
      <!--        </svg>-->
      <!--        <div class="themed-heading"><i class="bi bi-wallet2"></i></div>-->
      <!--      </div>-->
      <!--      <div class="step-details">-->
      <!--        <h5>Disbursement</h5>-->
      <!--        <p>-->
      <!--          Funds are sent directly to your institution or personal account.-->
      <!--        </p>-->
      <!--      </div>-->
      <!--    </div>-->

      <!-- Step 4 -->
      <!--    <div class="col-md-3">-->
      <!--      <div-->
      <!--        contenteditable="false"-->
      <!--        class="themed-shape-text-over-background inactive"-->
      <!--      >-->
      <!--        <svg class="css-1b6tcxl arrow-step4">-->
      <!--          <path-->
      <!--            d="M 238.5 0 L 258.75 40.5 L 238.5 81 L 0 81 L 20.25 40.5 L 0 0 Z"-->
      <!--            class="css-1j8r2w0"-->
      <!--          ></path>-->
      <!--        </svg>-->
      <!--        <div class="themed-heading">-->
      <!--          <i class="bi bi-rocket-takeoff"></i>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="step-details">-->
      <!--        <h5>Achieve Dreams</h5>-->
      <!--        <p>Focus on learning while we handle the financial support.</p>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--</div>-->
      <div class="container">
        <div class="section-title">Your Journey to Financial Support</div>
        <div class="row">
          <div style="display: flex; flex-wrap: wrap">
            <div class="col-md-3 step-Apply">
              <div class="step">
                <div class="circle"></div>
                <div class="line"></div>
                <div class="circle"></div>
              </div>
              <div class="col-md-12" style="display: flex; text-align: left">
                <div>
                  <div class="title">Apply Online</div>
                  <div class="desc">Select Course</div>
                </div>
              </div>
            </div>
            <div class="col-md-3 empty-space"></div>

            <div class="col-md-3 step-Apply">
              <div class="step">
                <div class="circle"></div>
                <div class="line"></div>
                <div class="circle"></div>
              </div>
              <div class="col-md-12" style="display: flex; text-align: left">
                <div>
                  <div class="title">Disbursement</div>
                  <div class="desc">Pay Fee By CampusDunia Credit Limit</div>
                </div>
              </div>
            </div>
            <div class="col-md-3 empty-space"></div>
          </div>
        </div>
        <div class="row arrows-row">
          <div class="arrows" style="display: flex; flex-wrap: wrap">
            <div class="col-md-3">
              <div class="arrow blue"></div>
            </div>
            <div class="col-md-3">
              <div class="arrow blue"></div>
            </div>
            <div class="col-md-3">
              <div class="arrow blue"></div>
            </div>
            <div class="col-md-3">
              <div class="arrow blue"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div style="display: flex; flex-wrap: wrap">
            <div class="col-md-3 empty-space"></div>
            <div class="col-md-3 step-Apply">
              <div class="step">
                <div class="circle"></div>
                <div class="line"></div>
                <div class="circle"></div>
              </div>
              <div class="col-md-12" style="display: flex; text-align: left">
                <div>
                  <div class="title">Get Approval</div>
                  <div class="desc">
                    Avail CampusDunia Credit Limit
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 empty-space"></div>
            <div class="col-md-3 step-Apply">
              <div class="step">
                <div class="circle"></div>
                <div class="line"></div>
                <div class="circle"></div>
              </div>
              <div class="col-md-12" style="display: flex; text-align: left">
                <div>
                  <div class="title">Achieve Dreams</div>
                  <div class="desc">
                    Realize your goals with financial support
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Step Process Section End -->
    <!--simple four step process section end-->
    <!---Testimonial section start--->
    <style>
      /* <!-- Testimonial Section CSS Start --> */
      .testimonial-section {
        padding: 60px 20px;
      }
      .testimonial-section h2 {
        font-weight: 800;
        font-size: 42px;
        margin-bottom: 40px;
      }
      .testimonial-card {
        border-radius: 20px;
        /* padding: 20px; */
        height: 100%;
      }
      .testimonial-img-container:hover {
        transform: translateY(-5px);
      }
      .testimonial-img-container {
        background-color: #2e2e2e;
        border-radius: 20px;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
      }
      /* .testimonial-img {
        width: 100%;
        border-radius: 15px;
        margin-bottom: 15px;
        object-fit: cover;
      } */
      .testimonial-name {
        font-weight: 600;
        font-size: 25px;
        margin-top: 10px;
      }
      .testimonial-text {
        font-weight: 400;
        font-size: 18px;
        color: #ccc;
      }
      .testimonial-img {
        max-width: 100%;
        border-radius: 15px;
      }
      /* <!-- Testimonial Section CSS End --> */
    </style>
    <!-- Testimonial Section Start -->
    <section class="testimonial-section">
      <div class="container">
        <div class="section-title text-center">Real Students, Real Success</div>
        <div class="row g-4">
          <!-- Priya -->
          <div class="col-md-4">
            <div class="testimonial-card">
              <div class="testimonial-img-container">
                <img
                  src="image/testimonial-one.png"
                  alt="Priya"
                  class="testimonial-img"
                />
              </div>
              <div class="testimonial-name">Priya from Jhansi</div>
              <p class="testimonial-text">
                "The loan process was so simple. Now I'm completing my
                engineering degree without financial stress."
              </p>
            </div>
          </div>

          <!-- Rahul -->
          <div class="col-md-4">
            <div class="testimonial-card">
              <div class="testimonial-img-container">
                <img
                  src="image/testimonial-two.png"
                  alt="Rahul"
                  class="testimonial-img"
                />
              </div>
              <div class="testimonial-name">Rahul from Dehradun</div>
              <p class="testimonial-text">
                "Their belief in my potential changed everything. I'm the first
                doctor in my family."
              </p>
            </div>
          </div>

          <!-- Ananya -->
          <div class="col-md-4">
            <div class="testimonial-card">
              <div class="testimonial-img-container">
                <img
                  src="image/testimonial-three.png"
                  alt="Ananya"
                  class="testimonial-img"
                />
              </div>
              <div class="testimonial-name">Ananya from Vizag</div>
              <p class="testimonial-text">
                "Their support helped me focus on learning rather than worrying
                about finances."
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonial Section End -->

    <!-- Eligibility -->
    <!-- <section class="container">
      <div class="section-title">Are You Eligible?</div>
      <ul>
        <li>Chosen Institute</li>
        <li>Recognized Program</li>
        <li>Demonstrated Potential</li>
        <li>Financial Need</li>
      </ul>
    </section> -->

    <!-- Simple 4-Step Process -->
    <!-- <section class="container">
      <div class="section-title">Our Simple 4-Step Process</div>
      <div class="row">
        <div class="col-md-3"><div class="step-box">1. Apply Online</div></div>
        <div class="col-md-3"><div class="step-box">2. Get Approval</div></div>
        <div class="col-md-3"><div class="step-box">3. Disbursement</div></div>
        <div class="col-md-3">
          <div class="step-box">4. Achieve Dreams</div>
        </div>
      </div>
    </section> -->

    <!-- Success Stories -->
    <!-- <section class="container">
      <div class="section-title">Real Students, Real Success</div>
      <div class="row">
        <div class="col-md-4">
          <div class="card-custom">Priya from Jhansi</div>
        </div>
        <div class="col-md-4">
          <div class="card-custom">Rahul from Dehradun</div>
        </div>
        <div class="col-md-4">
          <div class="card-custom">Ananya from Vizag</div>
        </div>
      </div>
    </section> -->

    <!-- FAQs -->
    <!-- <section class="container">
      <div class="section-title">Frequently Asked Questions</div>
      <ul class="faqs">
        <li>How do I qualify for a loan?</li>
        <li>What is the average processing time?</li>
        <li>How soon can I get funds?</li>
        <li>Who can I contact for support?</li>
      </ul>
    </section> -->

    <!-- Final CTA -->
    <!-- <section class="container text-center">
      <div class="section-title">Ready to Turn Dreams Into Reality?</div>
      <h3>5 min</h3>
      <p>Application Time</p>
      <h3>7 days</h3>
      <p>Average Approval</p>
      <h3>1000+</h3>
      <p>Students Helped</p>
      <p>Contact: support@campusdunia.com</p>
    </section> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      let timeLeft = 10 * 60;
      const timerDisplay = document.getElementById("timer");

      function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        timerDisplay.textContent = `${minutes}:${
          seconds < 10 ? "0" : ""
        }${seconds}`;

        timeLeft--;

        if (timeLeft < 0) {
          timeLeft = 10 * 60;
        }

        setTimeout(updateTimer, 1000);
      }

      updateTimer();
    </script>
  </body>
</html>

