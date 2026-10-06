@extends('layouts.campusdunialayout')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fee</title>
</head>
<style>
     body{
        background-color: black;
        color: white;
      }
      h1,
      h2,
      h3 {
        font-weight: 800;
      }
      
  .payfee-main
  {
    background-color: #f3f3f3;
  }
  .payfee-text
  {
    font-size: 40px;
    font-weight: 600;
    margin-top: 150px;
  }
  .payfee-text span
  {
    color: darkorange;
    font-weight:600!important;
  }
  .payfee-para
  {
    margin-top: 20px;
    font-weight: 600;
    font-size: 21px;
    line-height:40px;
  }
  .features-main
  {
    background-color: #f7f6f3;
  }
  .features-td
  {
    padding: 50px;
    border:0px solid red;
    text-align: center;
    margin-top: 20px;
    margin-bottom: 20px;
    border-radius: 3px;
    background-color: #f7f6f3;
    box-shadow: 0px 0px 3px gray;
  }
  .features-text-td
  {
    text-align: center;
    font-size: 40px;
    font-weight: 600;
    margin-bottom: 20px;
    margin-top: 20px;
  }
  .all-para-size
  {
    font-size: 16px;
  }
  .tdone
  {
    transition: 0.5s ease-in-out !important;
    width: 45%;
    background-color:#f39c12 !important;
    font-weight: 600 !important;
    font-size: 14px;
  }
 .tdone:hover
  {
      transform: scale(1.1, 1.1);
  }
  .round-shape-box
  {
    border-radius:50px ;
  }
  
.pay-fee-hero
{
        margin-top: 40px;
        margin-left: 120px;
}
</style>
<body>
    <section class="payfee-main" style="padding:160px 0px 50px 0px">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="payfee-text">
        <span style="font-weight:700!important;"> Payfee </span>By CampusDunia 
        </div>
        <!-- <div class="payfee-para">
        Most convenient way designed for parents/students & learners to pay their educational fees.
        </div> -->
        <div>
        <form class="needs-validation">
          <div class="row mt-4">
            <div class="col-sm-6">
              <input type="text" class="form-control round-shape-box" placeholder=" Registration Number">
            </div>
            <div class="col-sm-6">
                <select id="inputState" class="form-control round-shape-box">
                  <option selected>+91</option>
                </select>
            </div>
          </div>
          <div class="row mt-4" style="display:none;">
            <div class="col">
              <input type="text" class="form-control" placeholder="First Name">
            </div>
            <div class="col">
              <input type="text" class="form-control" placeholder="Last Name">
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-sm-6">
                <select id="inputState" class="form-control round-shape-box">
                  <option selected>Institute Name</option>
                  <option>...</option>
                </select>
            </div>
            <div class="col-sm-6">
                <select id="inputState" class="form-control round-shape-box">
                  <option selected>Course Name</option>
                  <option>...</option>
                </select>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-sm-6">
                <select id="inputState" class="form-control round-shape-box">
                  <option selected>Fee Type</option>
                  <option>...</option>
                </select>
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control round-shape-box" placeholder=" Fee Amount">
            </div>
          </div>
        </form>
        </div>
         <button
                class="bttn bttn-two mt-2 mb-4" style="background:transparent;"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal"
              >
                Pay Fee
                <span></span><span></span><span></span><span></span>
              </button>
      </div>
      <div class="col-sm-6">
        <div class="pay-fee-hero">
            <img src="image/pay-fee.png" alt="" width="80%">
        </div>
      </div>
    </div>
  </div>
</section>
<!--FEATURES SECTION START-->
<section>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
              <div class="features-text-td">Features</div>
          </div>
      </div>
  </div>
</section>
<section style="padding-bottom:50px;">
<div class="container">
  <div class="row">
    <div class="col-sm-6">
        <img src="image/smartphones-marble-table.png" alt="" style="margin-top:40px;width:100%;">
    </div>
    <div class="col-sm-6 ml-4">
      <div class="payfee-text" style="margin-top:180px!important;margin-left:20px;">
      <span>Customize Fee</span> Plans
      </div>
      <div class="payfee-para all-para-size" style="margin-left:20px;">
       <h5>Designed especially for Learners smooth fee collection process</h5>
      </div>
      <div style="margin-top: 20px;margin-left:20px;display:flex;">
        <!-- <div class="btn tdone">One Time Payment</div> 
        <div class="btn tdone" style="margin-left:25px;">Easy Monthly Installments</div> -->
        <div class="bttn">One Time Payment
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div> 
        <div class="bttn" style="margin-left:25px;">Easy Monthly Installments
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
<!--FEATURES SECTION END-->
<!--rewards section start-->
<style>
    .feature-btn {
        display: flex;
        align-items: center;
        text-align: start;
        background-color: #1e1e1e;
        color: #fff;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        margin: 10px 0;
        border-radius: 5px;
        width: 100%;
        cursor: pointer;
        outline: 1px solid #424242;
      }
      .feature-btn:hover {
        background-color: #444;
      }
      .feature-btn img {
        margin-right: 10px;
      }

</style>
<section style="padding-bottom:100px;">
<div class="container">
  <div class="row">
    <div class="col-sm-6">
      <div class="payfee-text ">
      <span>Get Rewards </span>on Payfee 
      </div>
      <div class="payfee-para all-para-size">
        <h5>
            Experience the most convenint, flexible & rewarding way to pay your education fees 
        </h5>
      </div>
      <div class="features">
                <button
                  class="feature-btn"
                  
                >
                  <img src="image/rupee.png" alt="Cashback" /> Get instant
                  cashback of upto 1% on each transaction
                </button>
                <button
                  class="feature-btn"
                  
                >
                  <img src="image/party-popper.png" alt="Reward Points" /> Earn
                  50 Reward points by inviting your amazing friends
                </button>
                <button
                  class="feature-btn"
                 
                >
                  <img src="image/gift.png" alt="Rewards" /> Get instant
                  rewards & discounts with CampusDunia Smart Card.
                </button>
              </div>
    </div> 
    <div class="col-sm-6">
        <div style="max-width:300;text-align:center;margin-left: 100px;">
            <img src="image/reward.png" alt="" style="margin-top:70px;width:100%;">
        </div>
    </div> 
  </div>
</div>
</section>
<!--rewards section end-->
</body>
</html>