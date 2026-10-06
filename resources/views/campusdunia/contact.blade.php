@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - CampusDunia</title>
    <!-- <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
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
    <link rel="stylesheet" href="style.css" />
    <style>
      body {
        
        background: #000;
        color: #fff;
      }

      h2 {
        font-size: 44px;
        font-weight: 700;
        margin-top: -10px;
        margin-bottom: 25px;
       }

      .bg_contact_image {
        -webkit-mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 80%,
          rgba(0, 0, 0, 0) 100%
        );
        mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 80%,
          rgba(0, 0, 0, 0) 100%
        );
        opacity: 0.7 !important;
        background-repeat: no-repeat;
        background-size: cover;
        /* background-position: center center; */
        height: 500px;
        width: 100%;
        color: #fff;
      }

      .contact-head-content {
        position: absolute;
        bottom: 25px;
      }

      .contact-page {
        position: relative;
      }

      .contact_main_form h2 {
        font-size: 41px;
      }

      .entr-contact-pg {
        margin-right: 95px;
      }

      .entr__heading {
        margin-bottom: 15px;
      }

      .entri-info-c.entri-styl-box {
        padding: 30px 15px 25px 0px;
        display: flex;
        align-items: center;
      }

      .entri-info-c {
        font-size: 16px;
        overflow: hidden;
        transition: all 0.3s linear;
        -webkit-transition: all 0.3s linear;
        -moz-transition: all 0.3s linear;
        -o-transition: all 0.3s linear;
        -ms-transition: all 0.3s linear;
      }

      .entri-info-c i {
        font-size: 30px;
        margin-top: 2px;
        line-height: 1;
        float: left;
        color: #f39c12;
      }

      .entri-info-c .entr-info-data {
        padding-left: 30px;
        font-weight: 500;
      }

      .entri-info-c h6 {
        font-size: 18px;
        margin-bottom: 5px;
        color: #f39c12;
        font-weight: 800;
      }

      .entri-info-c p {
        margin-bottom: 0;
      }

      .contact-form-en .contact_main_form {
        padding: 48px 40px;
        background-image: linear-gradient(90deg, #b1b1b1 0%, #f39c12 100%);
        color: #fff;
        border-radius: 10px;
      }

      .contact-form-en .contact_main_form h2 {
        color: #fff;
        margin-bottom: 10px;
      }

      .contact-form-en .contact_main_form .font14 {
        margin-bottom: 30px;
      }

      .contact-form-en .contact_main_form p {
        color: #fff;
      }

      .contact-form-en .contact_main_form input,
      .contact-form-en .contact_main_form textarea {
        width: 100%;
        background: rgb(76 76 76 / 40%);
        color: #fff;
        border: 0;
        padding: 10px;
        border-radius: 5px;
      }

      .contact-form-en .contact_main_form input,
      .contact-form-en .contact_main_form textarea {
        width: 100%;
        background: rgb(76 76 76 / 40%);
        color: #fff;
        border: 0;
        padding: 10px;
        border-radius: 5px;
      }

      .contact-form-en .contact_main_form input,
      .contact-form-en .contact_main_form textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.3);
        color: #fff;
      }

      textarea {
        width: 100%;
        height: 150px;
        vertical-align: top;
      }

      .contact-form-en .contact_main_form input,
      .contact-form-en .contact_main_form textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.3);
        color: #000000;
      }

      .contact-form-en .contact_main_form p button {
        background: #000 !important;
      }

      .contact-page{
        padding: 50px 0px;
      }
      /* MEDIA QUERY START FROM HERE*/
@media only screen and (max-width: 768px)
{
  .responsive-text
  {
    display:none;
  }
  .contact-head-content {
    position: absolute;
    bottom: 0px;
    top: 500px;
}
}
    </style>
  </head>
  <body>
    <section style="height: 120px;"></section>

    <section>
      <div id="cotact-page">
        <div
          class="bg_contact_image"
          style="background-image: url('image/customer-service-agent.jpg')"
        ></div>
        <div class="container">
          <div class="contact-head-content">
            <h2>Always Here to Help!</h2>
            <p class="responsive-text">
              Got questions about CampusDunia, your account, Smart Card features,
              or payments? We’re here for you!
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="contact-page">
          <div class="container">
            <div class="row">
              <div class="col-lg-6">
                <div class="entr-contact-pg">
                  <div
                    class="entr__heading"
                    data-aos="fade-down"
                    data-aos-duration="1000"
                    data-aos-delay="100"
                    data-aos-offset="100"
                  >
                    <h2>Contact us</h2>
                  </div>
                  <p
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="100"
                    data-aos-offset="100"
                  >
                    Give us a call or drop by anytime, we endeavour to answer all
                    enquiries within 24 hours on business days. We will be happy
                    to answer your questions.
                  </p>
                  <div
                    class="entri-info-c entri-styl-box"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="100"
                    data-aos-offset="100"
                  >
                    <i class="fa-regular fa-address-book" aria-hidden="true"></i>
                    <div class="entr-info-data">
                      <h6>Our Address:</h6>
                      <p>
                        GR Tower, 3rd floor,Plot D-258, Phase 8-A, Industrial
                        Area, Mohali, Punjab 160070.
                      </p>
                    </div>
                  </div>
                  <div
                    class="entri-info-c entri-styl-box"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="100"
                    data-aos-offset="100"
                  >
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                    <div class="entr-info-data">
                      <h6>Our Mailbox:</h6>
                      <p>support@campusdunia.co.in</p>
                    </div>
                  </div>
                  <div
                    class="entri-info-c entri-styl-box"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="100"
                    data-aos-offset="100"
                  >
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <div class="entr-info-data">
                      <h6>Our Phone:</h6>
                      <p>+0-172-403-7935</p>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="col-lg-6"
                data-aos="fade-down"
                data-aos-duration="1000"
                data-aos-delay="100"
                data-aos-offset="100"
              >
                <form
                  action="https://campusdunia.co.in/post-enquiry"
                  method="post"
                  class="contact-form-en"
                >
                  <input
                    type="hidden"
                    name="_token"
                    value="M3PfLjFyO8qDRxEdKFJ9GTXnQbaoY4wcfCyFNlI1"
                  />
                  <div class="contact_main_form">
                    <h2>Ready to Get Started?</h2>
                    <p class="font14">
                      Your email address will not be published. Required fields
                      are marked *
                    </p>
                    <p>
                      <input
                        type="text"
                        name="first_name"
                        value=""
                        size="40"
                        class=""
                        aria-required="true"
                        aria-invalid="false"
                        placeholder="Your Name *"
                      />
                    </p>
                    <p>
                      <input
                        type="email"
                        name="email"
                        value=""
                        size="40"
                        class=""
                        aria-required="true"
                        aria-invalid="false"
                        placeholder="Your Email *"
                      />
                    </p>
                    <p>
                      <textarea
                        name="message"
                        cols="40"
                        rows="10"
                        class=""
                        aria-invalid="false"
                        placeholder="Message..."
                      ></textarea>
                    </p>
                    <p>
                      <button type="submit" class="bttn bttn-two btn-contact">
                        Send Message
                        <span></span><span></span><span></span><span></span>
                      </button>
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>

    <!-- Map Section -->
    <div class="map-allen" style="width: 100%">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3430.752355703698!2d76.6880253151306!3d30.697243081649628!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fee5655555555%3A0xa5b767dd5e719df!2sEntritt%20Solutions%20Pvt.%20Ltd!5e0!3m2!1sen!2sin!4v1592822587289!5m2!1sen!2sin"
        width="100%"
        height="450"
        frameborder="0"
        style="border: 0"
        allowfullscreen=""
        aria-hidden="false"
        tabindex="0"
      ></iframe>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: false,
      });
    </script>
  </body>
</html>
