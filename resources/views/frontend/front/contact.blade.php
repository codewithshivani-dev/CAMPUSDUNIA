@extends('layouts.homelayout')
@section('title', 'Contact')
@section('content')
<div id="cotact-page">
<div class="contact-hd center-display">
    <div class="container">
        <div class="inner center-display">
            <h1 class="cont-title">Contact</h1>
            <ul id="breadcrumbs" class="breadcrumbs none-style">
                <li><a href="index.html">Home</a></li>
                <li class="active">Contact</li>
            </ul>    
        </div>
    </div>
</div>
<section class="contact-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="entr-contact-pg">
                    <div class="entr__heading">
                        <h2>Contact us</h2>
                    </div>
                    <p>Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.</p>
                    <div class="entri-info-c entri-styl-box">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>                   
                        <div class="entr-info-data">
                            <h6>Our Address:</h6>
                            <p> GR Tower, 3rd floor,Plot D-258, Phase 8-A, Industrial Area, Mohali, Punjab 160070.</p>
                        </div>
                    </div>
                    <div class="entri-info-c entri-styl-box">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                        <div class="entr-info-data">
                            <h6>Our Mailbox:</h6>
                            <p>entritt@gmail.com</p>
                        </div>
                    </div>
                    <div class="entri-info-c entri-styl-box">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                        <div class="entr-info-data">
                            <h6>Our Phone:</h6>
                            <p>+0-172-403-7935</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="contact.php" method="post" class="contact-form-en">
                    <div class="contact_main_form">
                        <h2>Ready to Get Started?</h2>
                        <p class="font14">Your email address will not be published. Required fields are marked *</p>
                        <p>
                            <input type="text" name="name" value="" size="40" class="" aria-required="true" aria-invalid="false" placeholder="Your Name *" required>
                        </p>
                        <p>
                            <input type="email" name="email" value="" size="40" class="" aria-required="true" aria-invalid="false" placeholder="Your Email *" required>
                        </p>
                        <p>
                            <textarea name="message" cols="40" rows="10" class="" aria-invalid="false" placeholder="Message..." required></textarea>
                        </p>
                        <p><button type="submit" class="contact-btn contact-btn-light">Send Message</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="map-allen" style="width: 100%"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3430.752355703698!2d76.6880253151306!3d30.697243081649628!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fee5655555555%3A0xa5b767dd5e719df!2sEntritt%20Solutions%20Pvt.%20Ltd!5e0!3m2!1sen!2sin!4v1592822587289!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe></div>
<style type="text/css">
.center-display {
    display: flex;
    align-items: center;
    justify-content: center;
}
.contact-hd {
    width: 100%;
    min-height: 350px;
    color: #43baff;
    font-weight: 500;
    background: #262051 center center no-repeat;
    background-size: cover;
}
.contact-hd .cont-title {
    color: #fff;
    margin-bottom: 0;
    flex: 1;
    padding: 10px 20px 10px 0;
}
h1 {
    font-size: 48px;
}
.none-style {
    list-style: none;
    padding-left: 0;
}
.contact-hd .breadcrumbs {
    margin-bottom: 0;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 800;
}
.contact-hd .breadcrumbs li {
    display: inline-block;
    color: #fff;
}
.contact-hd .breadcrumbs li a {
    color: #aeaacb;
}
.contact-hd .breadcrumbs li:before {
    content: "";
    font-family: "FontAwesome";
    font-size: 7px;
    color: #43baff;
    margin: -3px 8px 0;
    display: inline-block;
    vertical-align: middle;
}
section {
    padding-top: 120px;
    padding-bottom: 130px;
    position: relative;
}
.entr-contact-pg {
    margin-right: 95px;
}
.entr__heading {
    margin-bottom: 15px;
}
.entr__heading > span {
    font-size: 14px;
    font-weight: 800;
    color: #7141b1;
    position: relative;
    display: inline-block;
    margin-bottom: 2px;
    text-transform: uppercase;
}
.entr__heading h2 {
    margin-bottom: 0;
    line-height: 48px;
}
.entri-info-c i {
    font-size: 30px;
    margin-top: 2px;
    line-height: 1;
    float: left;
    color: #43baff;
}
.entri-info-c.entri-styl-box:hover {
    box-shadow: 15px 15px 38px 0 rgba(0, 0, 0, 0.1);
    -webkit-box-shadow: 15px 15px 38px 0 rgba(0, 0, 0, 0.1);
    -moz-box-shadow: 15px 15px 38px 0 rgba(0, 0, 0, 0.1);
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
.entri-info-c.entri-styl-box {
    padding: 30px 30px 25px;
}
.entri-info-c .entr-info-data {
    padding-left: 50px;
    font-weight: 500;
}
.entri-info-c i:before {
    font-size: 30px;
}
.entri-info-c h6 {
    font-size: 16px;
    margin-bottom: 5px;
}
.entri-info-c p {
    margin-bottom: 0;
}
.contact-form-en .contact_main_form {
    padding: 60px 48px;
    background-image: linear-gradient(90deg, #00deff 0%, #7141b1 100%);
    color: #fff;
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
.font14 {
    font-size: 14px;
}
.contact-form-en .contact_main_form input, .contact-form-en .contact_main_form textarea {
    width: 100%;
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
}
.contact-btn.contact-btn-light {
    background: #fff;
    color: #1b1d21;
}
textarea {
    width: 100%;
    height: 150px;
    vertical-align: top;
}
.contact-form-en .contact_main_form input, .contact-form-en .contact_main_form textarea {
    width: 100%;
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
}
.contact-btn {
    transition: all 0.3s linear;
    -webkit-transition: all 0.3s linear;
    -moz-transition: all 0.3s linear;
    -o-transition: all 0.3s linear;
    -ms-transition: all 0.3s linear;
    font-size: 14px;
    padding: 14px 30px 14px 30px;
    line-height: 1.42857143;
    display: inline-block;
    margin-bottom: 0;
    text-decoration: none;
    text-transform: uppercase;
    white-space: nowrap;
    vertical-align: middle;
    font-weight: bold;
    text-align: center;
    background: #43baff;
    cursor: pointer;
    border: 1px solid transparent;
    color: #fff;
    outline: none;
}
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: #fff;
}
::-moz-placeholder { /* Firefox 19+ */
  color: #fff;
}
:-ms-input-placeholder { /* IE 10+ */
  color: #fff;
}
:-moz-placeholder { /* Firefox 18- */
  color: #fff;
}
</style>
@stop