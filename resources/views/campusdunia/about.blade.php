@extends('layouts.campusdunialayout')
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - CampusDunia</title>
    <!-- <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

      body {
        /* font-family: "Comfortaa", sans-serif; */
        color: white !important;
        background: #000 !important;
      }

      .highlight {
        color: #f39c12;
      }

      .aboutUs-section {
        background: #000;
        padding: 60px 0px;
      }

      .vision-section {
        /* background: #1e1e1e; */
        padding: 60px 0px;
      }

      .mission-section {
        background: #000;
        padding: 60px 0px;
      }

      /* {
        max-width: 100%;
        width: 85%;
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
      } */

      .visionImage img,
      .aboutImage img,
      .missionImage img {
        max-width: 100%;
    width: 85%;
    /* -webkit-mask-image:
    linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%),
    linear-gradient(to right, transparent 0%, black 20%, black 80%, transparent 100%);
  -webkit-mask-composite: intersect;
  mask-image:
    linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%),
    linear-gradient(to right, transparent 0%, black 20%, black 80%, transparent 100%);
  mask-composite: intersect; */
  -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 65%, rgba(0, 0, 0, 0) 100%);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 65%, rgba(0, 0, 0, 0) 100%);
}

      .about-content {
        place-content: center;
      }
      p {
        font-size: 18px !important;
        text-align:justify;
      }
      img
      {
     
      }
    </style>
  </head>
  <body>
    <section style="height: 120px"></section>
    <section class="aboutUs-section mt-4">
      <div class="container">
        <div class="row">
          <div class="col-md-6 about-content">
            <h2>About Us</h2>
            <p>
              CampusDunia
offers India’s Most Unified Platform  for Learners & Educational Institute. We
offer Solutions like CampusDunia Credit Limit, Smart Card which Empowers Learners
for their Education need and enabling them with ease of payments without any hassle.
With CampusDunia Credit Limit one can pay for their educational course with
multiple options like BNPL, No Cost & Easy EMI with rewards on each
transaction.
            </p>
          </div>
          <div class="col-md-6 aboutImage">
            <img src="image/about-removebg-preview.png" alt="AboutUs Image" />
          </div>
        </div>
      </div>
    </section>

    <section class="vision-section">
      <div class="container">
        <div class="row">
          <div class="col-md-6 visionImage">
            <img src="image/affordability-removebg-preview.png" alt="Vision Image" style=" "/>
          </div>
          <div class="col-md-6 about-content">
            <h2>Vision</h2>
            <p style="text-align:unset!important;">
              <span class="highlight">Making Education Affordable</span> and
              accessible for all, transforming lives through endless
              opportunities. burden
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="mission-section">
      <div class="container">
        <div class="row">
          <div class="col-md-6 about-content">
            <h2>Our Philosophy</h2>
            <p>
              We believe education is the cornerstone of India's growth. By
              removing financial barriers and providing opportunities, we aim to
              empower students with the skills and confidence to contribute to a
              thriving, inclusive nation. Our commitment is to nurture talent
              and fuel India’s future through accessible, quality education.
            </p>
          </div>
          <div class="col-md-6 missionImage">
            <img src="image/vision.jpg" alt="Mission Image" />
          </div>
        </div>
      </div>
    </section>

    <section class="mission-section">
      <div class="container">
        <div class="row">
          <!-- <div class="col-md-6 missionImage">
            <img src="image/vision.jpg" alt="Mission Image" />
          </div> -->
          <div class="col-md-12">
            <h2 class="text-center">Introducing Suchaksh</h2>
          </div>
          <div class="col-md-6 about-content mt-4">
            
            <p style="text-align:justify;">
              In a nation brimming with talent, many bright young minds are
              thwarted by financial hurdles, unable to access the education they
              deserve. Suchaksh stands as a beacon of hope for these students,
              committed to transforming lives through personalized support and
              funding on a case-by-case basis. We believe every child has the
              right to learn, grow, and dream beyond limitations.
            </p> 
            <p style="text-align:justify;">
              Beyond directly supporting students, Suchaksh also partners with
              educational institutions to help them meet their goals and uphold
              their commitment to quality education. As a Government of
              India-registered NGO, we operate with complete transparency,
              integrity, and adherence to all regulatory standards. Our mission
              is clear: to empower underprivileged students to pursue their
              educational aspirations and to strengthen our education ecosystem,
              fostering a brighter, more inclusive future for our nation.
            </p>
            <div class="bttn" style=" ">
              Learn More
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
          <div class="col-md-6">
              <div class="suchaksh-img" style="margin-top:30px">
                <img src="image/suchaksh-img.png" alt="" width="100%">
              </div>
          </div>
          
        </div>
      </div>
    </section>

    <!-- <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: false,
      });
    </script>
  </body>
</html>

