@extends('layouts.campusdunialayout')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Line</title>
    
    <!-- <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<style>
         @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

body {
  background: #000;
  /* font-family: "Comfortaa", sans-serif; */
  color: white;
}

.highlight {
  color: #f39c12;
}
.upi-icons {
    width: 45%;
    /* transform: skewX(10deg); */
  }
.upi-section {
    /* background: linear-gradient(to bottom, #000, #333333, #000); */
    background-color: #1e1e1e;
    background-size: cover;
    color: white;
  }
    .credit-hero-main-td
    {
        background-color: black;
        /* padding: 50px; */
    }
    .main-content-td
    {
        font-size: 43px;
    color: #fff;
    line-height: 75px;
    font-weight: 700;
    margin-top: 80px;
    }
    .main-content-td span
    {
        color: #f39c12;
    }
    .hero-credit-points
    {
        
        list-style:none;
        margin-top: 20px;
        font-size: 20px;
        letter-spacing: 1.5px;
        font-weight: lighter;
        background: linear-gradient(90deg, #ff891c, #ffc53b, #ffd643);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }
    .credit-img
    {
        max-width: 100%;width: 90%;
        margin-left: 50px;
    }   
    .credit-line-para
    {
        width:90%
    }
    .features-heading-main
    {
        font-size: 50px;
        /* margin-bottom: 70px; */
    }
    .features-heading
    {
        font-size:46px;
    }
    .typewriter{
        font-size: 40px;
        font-weight: 600;
    }
    .typewrite
    {
        list-style: none;
        color: #f39c12;
        text-decoration: none;
    }
    .heading-buy-now
    {
        font-size:50px;
    }
    .para-buy-now                                                           
    {
        font-size:18px;
        letter-spacing: 1.5px;
}
.bnpl-one
{
    text-align: left;
}
.bnpl-one img{
    width: 125px;
}
.bnpl-text-one
{
    text-align: left;
    margin-left: 20px;
}
.accordion-button {
        background: #333333 !important;
        color: white !important;
      }

      .accordion-item {
        color: #ffffff;
        background-color: #818181;
      }
      .accordion-button::after {
        filter: brightness(0) invert(1);
      }

      .accordion-button:not(.collapsed)::after {
        color: white !important;
      }

      .accordion-button:focus {
        box-shadow: none;
        /* / border-color: #f39c12; / */
        outline: none;
      }
      .faq-section
      {
        background-color: #000;
      }
      .accordion-item h2
      {
        margin-top:0 ;
      }
       /* Button css */
  .bttn {
    width: fit-content;
    cursor: pointer;
    --c: goldenrod;
    color: var(--c);
    font-size: 16px;
    border: 3px solid var(--c);
    border-radius: 0.5em;
    padding: 10px 16px;
    /* height: 3em; */
    text-transform: uppercase;
    font-weight: bold;
    /* font-family: sans-serif; */
    letter-spacing: 1px;
    text-align: center;
    /* line-height: 3em; */
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: 0.5s;
    /* margin: 1em; */
  }

  .bttn-two {
    background: transparent;
    /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2),
      0 6px 20px 0 rgba(0, 0, 0, 0.19); */
      margin-top: 40px;
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

  section{
    padding: 80px 0px;
  }

  .membership-section {
 background: #000;
    /* background: linear-gradient(to bottom, #333333 75%, #000 100%); */
    /* padding: 100px 0; */
    color: white;
  }

  .membership-card {
    background: transparent !important;
    /* background: linear-gradient(135deg, #333, #444); */
    padding: 20px;
    display: flex
;
    border-radius: 8px;
    margin-top: 20px;
    color: white;
}
/* MEDIA QUERY START FROM HERE*/
@media only screen and (max-width: 768px)
{
  .main-content-td {
    font-size: 44px;
    margin-top:20px;
  }
  .credit-img {
    max-width: 100%;
    width: 90%;
    margin-left: 25px;
}
.upi-icons {
    width: 55%;
    margin-top: 30px;
}
.features-heading-main 
{
  font-size: 44px;
}
.features-heading {
    font-size: 40px;
    margin-top: 20px;
}
.typewriter {
    font-size: 32px;
    font-weight: 600;
}
.heading-buy-now {
    font-size: 44px;
}
.para-buy-now {
    font-size: 16px;
    letter-spacing: 1.5px;
    padding: 3px;
}
}
</style>
<body>
      <!--hero section start-->
      <section class="credit-hero-main-td mt-4">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" style="
                place-content: center;
            ">
                    <div class="hero-main-content">
                        <div class="main-content-td">
                            CampusDunia Instant <span style="font-weight: 600!important;">Credit Limit</span>
                        </div>
                        <div class="credit-line-para">
                            <p class="">
                                Spend smart with 0% interest, EMIs, and credit growth
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="hero-credit-img">
                        <img class="credit-img" src="image/credit-line-fulfil.png" alt="">
                    </div>
                </div>
            </div>
        </div>
      </section>
     <section
      class="upi-section"
    >
      <div class="container">
        <div class="row align-items-center mt-3">
          <h2 class="features-heading-main text-center" style="font-weight: 600!important;">
           <span style="color:#f39c12;font-weight: 600!important;">Pay Fee</span> By CampusDunia Credit Limit
          </h2>
          <div class="col-lg-6 text-center">
            <img
              src="image/emi-plan-fulfil.png"
              alt="UPI Features"
              class="upi-icons" style="margin-top:50px"
            />
          </div>

          <div class="col-lg-6 features-list">
            <h2 class="features-heading" style="font-weight:600!important;">Hey Learners!!</h2>
            <h2 style="font-size:30px!important;">Avail any Educational Course with CampusDunia<span class="highlight"> Credit Limit</span>
            <span class="highlight" style="font-weight: 700!important;"> Upto ₹ 5L</span></h2>
            <div class="typewriter">
            Repay in
            <span class="theme-color-code">
                <a href="" class="typewrite theme-color-code" data-period="2000" data-type="[&quot;3,6,9,12&quot;]"><span class="wrap">3,6,9,12</span></a>
                EMIs
              </span>
            </div>
              <!-- <li>
                <img src="image/upi.png" alt="UPI" /> Flexible repayment option begins at 3 months & upto 12 months
              </li>
              <li>
                <img src="image/qr.png" alt="QR" /> Immediate access to Credit
              </li>
              <li>
                <img src="image/payment-reconciliation.png" alt="Payment" />
                Borrow as per your need
              </li> -->
            </ul>
            <div class="bttn bttn-two">
                <!-- <button class="cta-btn contact-btn"> -->
                Get Started
                <span></span><span></span><span></span><span></span>
                <!-- </button> -->
              </div>
          </div>
        </div>
      </div>
    </section>
    <section class="membership-section text-center">
        <h2
        class="heading-buy-now new-font-size" style="font-size: 40px;font-weight: 600!important;"
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="20"
      >
  
      <span class="highlight" style="font-weight: 600!important;">Buy Now Pay Later</span>  as per your Convenience
  
    </h2>

      <p class="para-buy-now">Instant credit limit to meet all the expenses with flexible repayment option</p>
      <div class="container">
        <div class="row mt-4" style="margin-top: 50px !important;">
          <div
            class="col-md-6"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
            <div class="membership-card registeredUsers">
                <div class="bnpl-one">
                    <img src="image/cash-icon-td.png" alt=""   style=" max-width: 100%;width:160px;">
                </div>
                <div class="bnpl-text-one">
                  <h3 class="number">Immediate access to Credit</h3>
                  <p class="label">Get instant credit to buy courses or pay bills and services from the comfort of your home</p>
                </div>
            </div>
          </div>
          <div
            class="col-md-6"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
            <div class="membership-card">
                <div class="bnpl-one">
                    <img src="image/borrow-icon-td.png" alt=""   style=" max-width: 100%;width:145px;">
                </div>
                <div class="bnpl-text-one">
                    <h3 class="number">Borrow as per your need</h3>
                    <p class="label">Avail amount as per your need & repay only what you use with an Easy EMI Option</p>
                </div>
            </div>
          </div>
          <div
            class="col-md-6 mt-3"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
            <div class="membership-card" style="margin-top: 10px;">
                <div class="bnpl-one">
                    <img src="image/borrow-amt-icon-td.png" alt="" style=" max-width: 100%;">
                </div>
                <div class="bnpl-text-one">
                    <h3 class="number">Flexipay option</h3>
                    <p class="label">Flexible repayment option begins at 3 months that lasts upto 12 months</p>
                </div>
            </div>
          </div>
          <div
            class="col-md-6 mt-3"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
            <div class="membership-card" style="margin-top: 10px;">
                <div class="bnpl-one">
                    <img src="image/rewaards-icon-td.png" alt=""   style=" max-width: 100%;width:167px;">
                </div>
                <div class="bnpl-text-one">
                    <h3 class="number">Rewards and Benefits</h3>
                    <p class="label"> Pay for services and bills and earn exciting rewards and free vouchers from our merchant partners</p>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section> 
    <!--CREDIT LINE FULFILL YOUR WISHES START-->
    <style>
      .credit-line-fulfil
      {
 background-color: #1e1e1e;
      }
          .cardBox {
  width: auto;
  height: 350px;
  position: relative;
  display: grid;
  place-items: center;
  overflow: hidden;
  border-radius: 20px;
  box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 10px 0px,
    rgba(0, 0, 0, 0.5) 0px 2px 25px 0px;
}
.card-credit-line-fulfil {
  position: absolute;
  width: 95%;
  height: 95%;
  background: #000;
  border-radius: 20px;
  z-index: 5;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
  color: #ffffff;
  overflow: hidden;
  padding: 20px;
  cursor: pointer;
  box-shadow: rgba(0, 0, 0, 0.4) 0px 30px 60px -12px inset,
    rgba(0, 0, 0, 0.5) 0px 18px 36px -18px inset;
}


 .para-credit-line {
    font-size: 13px;
    line-height: 18px;
  font-weight: 300;
  margin-top: 15px;
}

.heading-fulfil-credit-line
{
  color: white;text-align: center;margin-bottom: 20px;margin-bottom: 60px;
}
@keyframes glowing {
  0% {
    transform: rotate(0);
  }
  100% {
    transform: rotate(360deg);
  }
}
    </style>
    <section class="credit-line-fulfil">
      <div class="container">
        <h2
            class="heading-buy-now new-font-size heading-fulfil-credit-line" style="font-weight: 600!important;"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
      
          A Credit Limit that <span class="highlight" style="font-weight: 600!important;">fulfills</span> all your <span class="highlight" style="font-weight: 600!important;">wishes</span> 
        </h2>
        
          <div class="row">
            
              <div class="col-sm-3">
                  <div class="cardBox">
                      <div class="card-credit-line-fulfil">
                          <img src="image/credit-icon-one.png" alt="" width="100px">
                          <h3 class="mt-5" style="font-size: 20px;margin-top: 15px;">No Credit History Required</h3>
                          <p class="para-credit-line">Our alternative data credit score approves customers without credit histories.</p>
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="cardBox">
                      <div class="card-credit-line-fulfil">
                          <img src="image/credit-icon-two-td.png" alt="" width="100px">
                          <h3 class="mt-5" style="font-size: 20px;margin-top: 15px;">Wide range of Credit line</h3>
                          <p class="para-credit-line">Credit line from ₹2,000 to ₹5,00,000 for all your expenses and needs.</p>
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="cardBox">
                      <div class="card-credit-line-fulfil">
                          <img src="image/credit-icon-three-td.png" alt="" width="100px">
                          <h3 class="mt-5" style="font-size: 20px;margin-top: 15px;">Fuel up your <br> career</h3>
                          <p class="para-credit-line">CampusDunia credit line helps GenZ and millennials upskill and grow.</p>
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="cardBox">
                      <div class="card-credit-line-fulfil">
                          <img src="image/credit-icon-four-td.png" alt="" width="100px">
                          <h3 class="mt-5" style="font-size: 20px;margin-top: 15px;">Afford quality Education</h3>
                          <p class="para-credit-line">Breaks financial barriers to education, building a productive and secure nation.</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
    <!--CREDIT LINE FULFILL YOUR WISHES END-->
    <!--faq section-->
    <section class="faq-section">
        <div
          class="container"
          data-aos="fade-up"
          data-aos-duration="500"
          data-aos-delay="50"
          data-aos-offset="20"
        >
          <h2
            class="heading"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20" style="color:#fff;text-align: center;font-weight: 600!important;"
          >
            Frequently Asked<span class="highlight" style="font-weight:600!important;"> Questions</span>
          </h2>
          <div
            class="accordion mt-3"
            id="faqAccordion"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="20"
          >
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq1"
                >
                When & how will I receive the fees paid by my students?
                </button>
              </h2>
              <div
                id="faq1"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion"
              >
                <div class="accordion-body">
                    We follow a 'T + 1' settlement cycle, meaning the payment will be settled into your bank account in 2 working days from the successful transaction date. This is the same bank account details of which were provided in your KYC documents.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq2"
                >
                What are KYC documents and why is it required to be submitted?
                </button>
              </h2>
              <div
                id="faq2"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion"
              >
                <div class="accordion-body">
                    Generally an identity proof with photograph and an address proof are the two basic mandatory KYC documents that are required to establish one's identity.<br><br>
                    For KYC, one needs to upload copies of PAN Card, Aadhar Card & a Cancelled Cheque (without signature).
                    <br><br>
                    The objective of KYC guidelines is to prevent businesses from being used by criminal elements for money laundering activities. It also enables businesses to understand their customers, their financial dealings so as to serve them better and manage its risks prudently.
                                
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq3"
                >
                What if I do not submit KYC documents?
                </button>
              </h2>
              <div
                id="faq3"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion"
              >
                <div class="accordion-body">
                    For KYC, one needs to upload copies of PAN Card, Aadhar Card & a Cancelled Cheque (without signature). If someone does not upload the KYC documents, settlements to the partner Institute will not happen & shall be withheld. To start settlements to your bank account, we need your bank account details & your PAN details.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#faq4"
                >
                How to add Students?
                </button>
              </h2>
              <div
                id="faq4"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion"
              >
                <div class="accordion-body">
                    Students can be added one-by-one or imported from an Excel file. Format of the Excel file can be found in the panel itself.
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq5"
                  >
                  How many students can be added?
                  </button>
                </h2>
                <div
                  id="faq5"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Unlimited. There is no limit on the number of students you can add or import.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq6"
                  >
                  Will I get an approval even if I apply on a holiday or beyond banking hours?
                  </button>
                </h2>
                <div
                  id="faq6"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Yes! However, the KYC verification process might be impacted on holidays.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq7"
                  >
                  How will my students know about their login details?
                  </button>
                </h2>
                <div
                  id="faq7"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Students will receive an SMS with their login details on their mobile phones immediately after their account is created in the system - either when you import student details in to the system or when you create their account individually.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq8"
                  >
                  How many courses & programs can I add?
                  </button>
                </h2>
                <div
                  id="faq8"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Unlimited. There is no limit on the number of Courses, Programs or Batches you can create.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq9"
                  >
                  Two of my Courses have the same fee structure. Do I need to enter the same data twice?
                  </button>
                </h2>
                <div
                  id="faq9"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    No. You can copy the fees structure & rename it as per your needs. You can also modify, add or remove fee heads if needed in the copied fees structure.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq10"
                  >
                  What are the different types of payment methods in which my students can pay?
                  </button>
                </h2>
                <div
                  id="faq10"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Campusdunia supports & accepts payments from all major Credit & Debit Cards (VISA, MasterCard, RuPay, AMEX, Diners), Internet Banking (All major Indian Banks), Mobile Wallets* (Paytm, Mobikwik, JioMoney, etc.), UPI & Prepaid Cards. Campusdunia also supports acceptance of International payments.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq11"
                  >
                  Who is eligible to apply for CampusDunia?
                  </button>
                </h2>
                <div
                  id="faq11"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Anyone who meets the following criteria can apply:
                            Resident of PAN India
                            Salaried employees and self-employed professionals like doctors, lawyers, shop owners, business owners etc with a minimum monthly salary of Rs. 20,000
                            * 21 years of age and above
                            Before you open the CampusDunia app, please have the following information handy:
                            Aadhaar Card
                            Pan Card
                            Bank statement of last statement
                  </div>
                </div>
              </div>
          </div>
        </div>
      </section>

</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: false,
    });

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
      css.innerHTML = ".typewrite > .wrap { border-right: 0.1em solid #f39c12}";
      document.body.appendChild(css);
    };
  </script>
</html>
