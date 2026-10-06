@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Education | ERP Software</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
      :root {
  --clr-primary: #ffa000;
  --clr-primary-hover: #ffa000;
  --clr-secondary: #ffffff;
  --transition: 0.5s ease;
}

body {
  background-color: #000 !important;
}

.clr {
  color: var(--clr-primary);
}

/* header {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  background-color: #ff7614;
  padding: 10px;
}

header ul {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  list-style-type: none;
  margin-top: 0px !important;
  margin-bottom: 0px !important;
}

header a,
li {
  color: #fff !important;
  text-decoration: none;
}

.head-contact {
  width: 40%;
}

.navbar-custom {
  background-color: var(--clr-secondary);
}

.navbar-custom .navbar-brand,
.navbar-custom .nav-link,
.navbar-custom .navbar-toggler-icon {
  color: #fff !important;
}

.navbar-toggler {
  border: 1px solid white;
}

.head-nav {
  width: 40%;
}

#my-nav {
  transition: background-color 0.5s ease-in-out;
}

#my-nav.scrolled {
  background-color: #ffa000;
}

.fa-envelope,
.fa-mobile {
  margin-right: 5px;
} */

.product {
  min-height: 400px;
}

.product-bg {
  /*background-image: url(image/product.webp);*/
}

.product-content {
  padding: 20px 60px;
}

.product-content h1 {
  color:#000;
  border-bottom: 0;
  border-left: 0;
  font-size: 38px;
  padding: 65px 0px 5px 0px;
  line-height: 40px;
}

.product-content h5 {
  color: #fff;
  font-weight: 400;
  padding: 25px 0px;
  margin-top: 8px;
}

.erp-modules {
  color: white;
}

.institute-banner img {
  width: 100%;
}

.banner-btn {
  border-radius: 5px !important;
  color: #fff;
  font-size: 17px;
  padding: 10px 8px;
  border: 0;
  min-width: 150px;
  transition: all 0.35s;
  font-weight: 600;
  letter-spacing: 0.8px;
}

.erp-management .contain {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 0.5rem;
}

.erp-management .hoverbox {
  position: relative;
  background: hsla(220, 10%, 20%, 0.9);
  padding: 32px 28px;
  overflow: hidden;
  border-radius: 0px;
  transition: 0.35s ease-in;
}

.erp-management .hoverbox:after {
  content: "";
  position: absolute;
  top: 0;
  left: auto;
  right: 0;
  width: 0%;
  height: 0.35rem;
  background: var(--clr-primary);
  transition: var(--transition);
}

.erp-management .hoverbox img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  transition: var(--transition);
  filter: blur(1px) saturate(0);
}

.erp-management .hoverbox h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #fff;
  margin-bottom: 1rem;
}

.erp-management .hoverbox p {
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 1.125rem;
  font-weight: 300;
}

.erp-management .hoverbox a {
  position: relative;
  color: #fff;
  text-decoration: unset;
  text-transform: uppercase;
  font-size: 0.875rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  transition: color 0.35s;
}

.erp-management .hoverbox a:after {
  content: ">";
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-left: 1rem;
  border: 2px solid #fff5;
  border-radius: 50%;
  width: 1.5rem;
  height: 1.5rem;
  transition: all 0.35s, color 0s;
}

.erp-management .hoverbox a:before {
  content: "";
  position: absolute;
  width: 0rem;
  height: 0.125rem;
  background: #fff;
  right: 0.75rem;
  top: calc(50% - 0.025rem);
  transition: 0.35s;
}

.erp-management .hoverbox:hover {
  background: hsla(220, 20%, 20%, 0.75);
  box-shadow: 0px 16px 24px rgba(0, 15, 0, 0.1);
}

.erp-management .hoverbox:hover:after {
  width: 100%;
  left: 0;
  right: auto;
}

.erp-management .hoverbox:hover a:after {
  border-color: transparent;
  margin-left: 2rem;
  transition: all 0.35s, color 0s;
}

.erp-management .hoverbox:hover a:before {
  width: 1.5rem;
  background: var(--clr-primary);
}

.erp-management .hoverbox:hover a {
  color: var(--clr-primary);
}

.erp-management .hoverbox:hover a:hover {
  color: var(--clr-primary-hover);
}

.erp-management .hoverbox:hover a:hover:before {
  background: var(--clr-primary-hover);
}

.erp-management .hoverbox:hover img {
  transform: scale(1.2) rotate(-5deg);
  filter: blur(8px) saturate(0);
}

.module-head img,
.benefits-head img {
  height: 50px;
}

.manage-heading {
  color: var(--clr-secondary) !important;
}

.manage-items li {
  position: relative;
  padding: 7px 10px;
  border-left: 3px solid #ffa000;
  border-right: 1px solid #ffa0009c;
  border-top: 1px solid #ffa0009c;
  border-bottom: 1px solid #ffa0009c;
  color: var(--clr-secondary) !important;
  margin-bottom: 15px;
  font-weight: 400;
  min-height: 65px;
  display: flex;
  align-items: center;
  overflow: hidden;
  z-index: 1;
  cursor: pointer;
}

.manage-items li::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0%;
  background-color: #ffa000;
  transition: width 1s ease-in-out;
  z-index: -1;
}

.manage-items li:hover::before {
  width: 100%;
  color: #fff;
}

.manage-items li:hover {
  color: #ffffff !important;

  transition: 0.7s ease-in-out;
}

.manage-img img {
  max-width: 100%;
}

.manage-items {
  padding: 0px;
  padding-right: 15px !important;
}

.banner-top {
  padding: 0px !important;
}

.benefitsOfErp {
  color: white;
}
.benefitsOfErp .benefit-contain {
  display: flex;
  flex-wrap: wrap;
  padding: 0;
  align-items: center;
  justify-content: center;
}

.benefitsOfErp .container .card {
  position: relative;
  min-width: 320px;
  height: 440px;
  box-shadow: inset 5px 5px 5px rgba(0, 0, 0, 0.2),
    inset -5px -5px 15px rgba(255, 255, 255, 0.1),
    5px 5px 15px rgba(0, 0, 0, 0.3), -5px -5px 15px rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  margin-right: 15px;
  margin-bottom: 15px;
  transition: 0.5s;
}

.benefitsOfErp .container .card .box .content a {
  background: var(--clr-primary) !important;
}

.benefitsOfErp .container .card .box {
  position: absolute;
  top: 20px;
  left: 20px;
  right: 20px;
  bottom: 20px;
  background: #2a2b2f;
  border-radius: 15px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  transition: 0.5s;
}

.benefitsOfErp .container .card .box:hover {
  transform: translateY(-15px);
}

.benefitsOfErp .container .card .box:before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 50%;
  height: 100%;
  background: rgba(255, 255, 255, 0.03);
}

.benefitsOfErp .container .card .box .content {
  padding: 20px;
  text-align: center;
}

.benefitsOfErp .container .card .box .content h2 {
  position: absolute;
  top: -20px;
  right: 0px;
  font-size: 6rem;
  color: rgba(255, 255, 255, 0.1);
}

.benefitsOfErp .container .card .box .content h3 {
  font-size: 1.2rem;
  z-index: 1;
  transition: 0.5s;
  margin-bottom: 15px;
}

.benefitsOfErp .container .card .box .content ul li {
  font-size: 0.9rem;
  line-height: 1.5em;
  font-weight: 300;
  color: rgba(255, 255, 255, 0.9);
  z-index: 1;
  transition: 0.5s;
}

.benefitsOfErp .container .card .box .content ul {
  text-align: start;
}

.benefitsOfErp .container .card .box .content a {
  position: relative;
  display: inline-block;
  padding: 8px 20px;
  background: black;
  border-radius: 5px;
  text-decoration: none;
  color: white;
  margin-top: 20px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  transition: 0.5s;
}

.benefitsOfErp .container .card .box .content a:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.6);
  background: #fff !important;
  color: #000;
}



.copy-right-text {
  color: #fff;
}

.copy-right-text a {
  color: #ffb606;
}

.terms-privacy li + li {
  margin-left: 30px;
}

.terms-privacy li a {
  color: #fff;
  position: relative;
}

.terms-privacy li a:after {
  position: absolute;
  content: "-";
  color: #fff;
  display: inline-block;
  top: 0;
  right: -18px;
}

.terms-privacy li + li a:after {
  display: none;
}

.banner-section {
  position: relative;
  background: black;
  background-image: linear-gradient(
      90deg,
      rgba(255, 160, 0, 0.45) 0%,
      white 20%,
      white 80%,
      rgba(255, 160, 0, 0.45) 100%
    ),
    url("image/Mediamodifier-Design-Template (1).png");
  background-repeat: repeat;
  background-size: cover;
}

.background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url(image/manual.gif);
  background-size: 33.33% 100%;
  /* background-size: 100% 50%; */
  z-index: 0;
  opacity: 0.1;
}

.banner-sec {
  width: 65% !important;
  padding-bottom: 50px;
}

.banner-section-child img {
  height: 100px;
  /* mix-blend-mode: darken; */
}

.banner-sec-head {
  color: var(--clr-secondary);
  font-weight: 700;
}

/* --------------------    Animation CSS    ------------------ */

/* Initial hidden state for animations */
.banner,
.banner-top {
  opacity: 0;
  transform: translateX(0);
  transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

/* Slide in from left */
.slide-in-left {
  opacity: 1;
  transform: translateX(0) !important;
}

/* Slide in from right */
.slide-in-right {
  opacity: 1;
  transform: translateX(0) !important;
}

/* Specific initial state for banner and banner-top */
.banner {
  transform: translateX(-100%); /* Start off-screen left */
}

.banner-top {
  transform: translateX(100%); /* Start off-screen right */
}

.image-wrapper {
  display: flex;
  align-items: center;
  /* width: 100%; */
  max-width: 90%;
  margin: 0 auto;
}

.hr-left,
.hr-right {
  flex: 1;
  border: 0;
  border-top: 5px solid #0000 !important;
}

.hr-left {
  transform: translateX(-100%);
}

.hr-right {
  transform: translateX(100%);
}

@keyframes slideInLeft {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}

/* Apply the animation */
.hr-left.animate {
  animation: slideInLeft 1s forwards;
}

.hr-right.animate {
  animation: slideInRight 1s forwards;
}

.col-left,
.col-right {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left {
  transform: translateX(-100%);
}

.col-right {
  transform: translateX(100%);
}

.col-left.animate,
.col-right.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left2,
.col-right2 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left2 {
  transform: translateX(-100%);
}

.col-right2 {
  transform: translateX(100%);
}

.col-left2.animate,
.col-right2.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left3,
.col-right3 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left3 {
  transform: translateX(-100%);
}

.col-right3 {
  transform: translateX(100%);
}

.col-left3.animate,
.col-right3.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left4,
.col-right4 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left4 {
  transform: translateX(-100%);
}

.col-right4 {
  transform: translateX(100%);
}

.col-left4.animate,
.col-right4.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left5,
.col-right5 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left5 {
  transform: translateX(-100%);
}

.col-right5 {
  transform: translateX(100%);
}

.col-left5.animate,
.col-right5.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left6,
.col-right6 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left6 {
  transform: translateX(-100%);
}

.col-right6 {
  transform: translateX(100%);
}

.col-left6.animate,
.col-right6.animate {
  opacity: 1;
  transform: translateX(0);
}
.col-left7,
.col-right7 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left7 {
  transform: translateX(-100%);
}

.col-right7 {
  transform: translateX(100%);
}

.col-left7.animate,
.col-right7.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left8,
.col-right8 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left8 {
  transform: translateX(-100%);
}

.col-right8 {
  transform: translateX(100%);
}

.col-left8.animate,
.col-right8.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left9,
.col-right9 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left9 {
  transform: translateX(-100%);
}

.col-right9 {
  transform: translateX(100%);
}

.col-left9.animate,
.col-right9.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left10,
.col-right10 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left10 {
  transform: translateX(-100%);
}

.col-right10 {
  transform: translateX(100%);
}

.col-left10.animate,
.col-right10.animate {
  opacity: 1;
  transform: translateX(0);
}

.col-left11,
.col-right11 {
  opacity: 0;
  transition: all 1.2s ease-in-out;
}

.col-left11 {
  transform: translateX(-100%);
}

.col-right11 {
  transform: translateX(100%);
}

.col-left11.animate,
.col-right11.animate {
  opacity: 1;
  transform: translateX(0);
}
.hidden {
  overflow-x: hidden;
}

/* The container for the progress bar */
.progress-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px; /* Adjust the thickness of the progress bar */
  background-color: #f3f3f3;
  z-index: 9999; /* Ensure it's always on top */
}

/* The actual progress bar */
.progress-bar1 {
  height: 100%;
  width: 0;
  background-color: #21255c; /* Color of the progress bar */
}
   .header
      {
        min-height:85px !important;
        height:85px !important;
      }
    </style>
  </head>
  <body class="hidden">
 <section style="padding: 50px 0px 0px 0px; min-height: 1px"></section>
    <!-- <nav
      class="navbar navbar-expand-lg navbar-custom navbar-dark sticky-top"
      id="my-nav"
    >
      <div class="container-fluid">
        <a class="navbar-brand" href="#"
          ><img
            src="image/logo-of-campusdunia.png"
            alt=""
            style="width: 100%; height: 50px"
        /></a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fa-solid fa-envelope"></i> entrit@gmail.com
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fa fa-mobile"></i> +91-98745-63218
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">|</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">+0-172-403-7935</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">Partnership</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Careers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Calculate ROI</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="progress-container">
      <div class="progress-bar1" id="progressBar"></div>
    </div> -->
    <section style="position: relative;
    background: #000 !important;
    color: white;margin-top: 35px;">
      <main class="product">
        <div class="container-fluid product-bg">
          <div class="row">
            <div class="col-md-6 banner">
              <div class="product-content">
                <h1 style="color:#fff;">
                  Streamline and optimize your campus operations with
                  <span class="clr">Campusdunia ERP</span>.
                </h1>
                <h5>
                  Experience a comprehensive and adaptable ERP solution designed
                  to enhance campus efficiency, boost productivity, and provide
                  greater oversight and management.
                </h5>
                <div class="btn btn-warning mt-2" style="font-weight:600">
                  Request a Demo
                </div>
              </div>
            </div>
            <div class="col-md-6 banner-top">
              <div class="institute-banner">
                <img
                  src="image/institute-Banner.webp"
                  alt="Campusdunia ERP Banner"
                  class="img-fluid"
                />
              </div>
            </div>
          </div>
        </div>
      </main>
    </section>
    <section data-aos="fade-right" class="erp-management my-5">
      <div class="container-fluid contain">
        <div class="hoverbox">
          <img
            class="hoverbox__image"
            src="https://images.unsplash.com/photo-1511447333015-45b65e60f6d5?crop=entropy&cs=tinysrgb&fm=jpg&ixid=MnwzMjM4NDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE2Njk2OTQxOTc&ixlib=rb-4.0.3&q=80"
            alt="Centralized Management"
          />
          <h3>Centralized Management</h3>
          <p>
            Centralizes all campus processes and operations within a unified
            data repository, enhancing efficiency across single or multiple
            institutions.
          </p>
          <a href="#">Learn More</a>
        </div>
        <div class="hoverbox">
          <img
            class="hoverbox__image"
            src="https://images.unsplash.com/photo-1579567761406-4684ee0c75b6?crop=entropy&cs=tinysrgb&fm=jpg&ixid=MnwzMjM4NDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE2Njk2OTQxOTc&ixlib=rb-4.0.3&q=80"
            alt="Higher ROI"
          />
          <h3>Accelerated ROI</h3>
          <p>
            Implement quickly, streamline operations, and reduce paper-based
            tasks to achieve a faster return on investment.
          </p>
          <a href="#">Learn More</a>
        </div>
        <div class="hoverbox">
          <img
            class="hoverbox__image"
            src="https://images.unsplash.com/photo-1462556791646-c201b8241a94?crop=entropy&cs=tinysrgb&fm=jpg&ixid=MnwzMjM4NDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE2Njk2OTQyNTA&ixlib=rb-4.0.3&q=80"
            alt="Data Security"
          />
          <h3>Enhanced Data Security</h3>
          <p>
            Protect your data with role-based access controls, end-to-end
            encryption, multi-factor authentication, and advanced security
            measures.
          </p>
          <a href="#">Learn More</a>
        </div>
        <div class="hoverbox">
          <img
            class="hoverbox__image"
            src="https://images.unsplash.com/photo-1462556791646-c201b8241a94?crop=entropy&cs=tinysrgb&fm=jpg&ixlib=rb-4.0.3&q=80"
            alt="Resource Planning"
          />
          <h3>Optimal Resource Planning</h3>
          <p>
            Digitally align your available resources with institutional needs to
            ensure optimal utilization and effective management.
          </p>
          <a href="#">Learn More</a>
        </div>
      </div>
    </section>

    <section class="erp-modules">
      <div class="text-center module-head">
        <h2 class="clr">Campusdunia Education ERP Modules</h2>
        <div class="image-wrapper">
          <hr class="hr-left col-left10" />
          <img class="my-img" src="image/bachelor.png" alt="Graduation Cap" />
          <hr class="hr-right col-right10" />
        </div>
      </div>
      <div class="container my-4">
        <div class="row">
          <div class="col-md-6 col-left">
            <div class="ad-management manage-img mt-3">
              <img
                src="image/Admission-management-screenshot.png"
                alt="Admission Management Module"
              />
            </div>
          </div>
          <div class="col-md-6 col-right">
            <div class="ad-manage-head my-3">
              <h3 class="manage-heading">Admission Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Merit List Generation</li>
                  <li>Online Student Registration</li>
                  <li>Course Selection</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Online Fee Payment</li>
                  <li>Live Admission Status Tracking</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left2">
            <div class="sr-manage-head my-3">
              <h3 class="manage-heading">Student Records Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Centralized Data Storage</li>
                  <li>Attendance Tracking</li>
                  <li>Performance Records</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Departmental Communication</li>
                  <li>Quick Data Search and Retrieval</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-right2">
            <div class="sr-management manage-img mt-3">
              <img
                src="image/Student-Information-System-Screenshot.webp"
                alt="Student Records Management"
              />
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left3">
            <div class="at-management manage-img mt-3">
              <img
                src="image/Student-Attendance-System.webp"
                alt="Attendance Management"
              />
            </div>
          </div>
          <div class="col-md-6 col-right3">
            <div class="at-manage-head my-3">
              <h3 class="manage-heading">Attendance Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Biometric and RFID Integration</li>
                  <li>Automated Attendance Recording</li>
                  <li>Mobile and Laptop Accessibility</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Email/SMS Notifications</li>
                  <li>Easy Report Generation</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left4">
            <div class="fee-manage-head my-3">
              <h3 class="manage-heading">Fees Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Secure Transactions</li>
                  <li>Fee Structure Allocation</li>
                  <li>E-receipt Generation</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>UPI, Credit/Debit Payments</li>
                  <li>Pending Fee Notifications</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-right4">
            <div class="fee-management manage-img mt-3">
              <img
                src="image/Fees-Collection-System.webp"
                alt="Fees Management"
              />
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left5">
            <div class="hrms-management manage-img mt-3">
              <img
                src="image/Human-Resource-Information-System.webp"
                alt="HRMS"
              />
            </div>
          </div>
          <div class="col-md-6 col-right5">
            <div class="hrms-manage-head my-3">
              <h3 class="manage-heading">HRMS</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Leave Management</li>
                  <li>Faculty Profile Maintenance</li>
                  <li>Salary Calculation</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Recruitment Management</li>
                  <li>Service Records Maintenance</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left6">
            <div class="pay-manage-head my-3">
              <h3 class="manage-heading">Payroll Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>MIS Report Generation</li>
                  <li>Leave and Incentive Calculations</li>
                  <li>Automated Salary Processing</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>HRMS Integration</li>
                  <li>Increment Cycle Tracking</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-right6">
            <div class="fee-management manage-img mt-3">
              <img
                src="image/Fees-Collection-System.webp"
                alt="Payroll Management"
              />
            </div>
          </div>
        </div>
        <div class="row my-5">
          <div class="col-md-6 col-left7">
            <div class="hrms-management manage-img mt-3">
              <img
                src="image/Library-Management-System.webp"
                alt="Library Management"
              />
            </div>
          </div>
          <div class="col-md-6 col-right7">
            <div class="lib-manage-head my-3">
              <h3 class="manage-heading">Library Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Email/SMS Reminders</li>
                  <li>Vendor Information Management</li>
                  <li>Book Tracking and Issuance</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Late Fee Calculation</li>
                  <li>Keyword-based Book Search</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Gate Management Section -->
        <div class="row my-5">
          
          <div class="col-md-6 col-right8">
            <div class="gate-manage-head my-3">
              <h3 class="manage-heading">Gate Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Visitor and Vehicle Management</li>
                  <li>Access Control</li>
                  <li>Alerts and Notifications</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Material and Vehicle Movement Tracking</li>
                  <li>OTP Verification</li>
                  <li>Purchase Order Integration</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-left8">
            <div class="gate-management manage-img mt-3">
               <img
                src="image/gate-management-2.png"
                alt="Payroll Management" style="margin-top:-45px"
              />
            </div>
          </div>
        </div>
        
        <!-- Front Desk Management Section -->
        <div class="row my-5">
          <div class="col-md-6 col-right9">
            <div class="frontdesk-management manage-img mt-3">
               <img
                src="image/front-desk.png"
                alt="Payroll Management" style="margin-top:-55px"
              />
            </div>
          </div>
          <div class="col-md-6 col-left9">
            <div class="frontdesk-manage-head my-3">
              <h3 class="manage-heading">Front Desk Management</h3>
            </div>
            <div class="col-md-12 d-flex">
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Guest Services</li>
                  <li>Visitor Management</li>
                  <li>Appointment Scheduling</li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="manage-items">
                  <li>Security and ID Badge Generation</li>
                  <li>Communication and Emergency Alerts</li>
                  <li>Information Management and Reporting</li>
                </ul>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </section>

    <section class="benefitsOfErp">
      <div class="text-center benefits-head">
        <h2 class="clr">Benefits of an ERP System</h2>
        <div class="image-wrapper">
          <hr class="hr-left col-left11" />
          <img class="my-img" src="image/bachelor.png" alt="Graduation Cap" />
          <hr class="hr-right col-right11" />
        </div>
        <p class="text-center">
          To streamline administrative procedures effectively, institutes can
          leverage proficient
          <br />
          Institute Management Software and enjoy the following benefits.
        </p>
      </div>

      
    </section>

    <section class="banner-section my-5 p-2">
      <div class="background"></div>
      <div class="container">
        <div class="banner-section-head text-center my-5">
          <h2>
            Comprehensive Integration Options to Elevate Management Efficiency
          </h2>
        </div>
        <div class="banner-section-content text-center my-5">
          <h5 style="font-weight: 500">
            Campusdunia ERP is equipped with all the features your institution
            will ever need, offering up to 20 integrations that significantly
            boost the platform's capabilities and functionality.
          </h5>
        </div>
      </div>
      <div class="container-fluid banner-sec">
        <div class="row banner-section-child">
          <div class="col-md-3 text-center">
            <div class="bio-img">
              <img src="image/fingerprint.gif" alt="Biometric Integration" />
            </div>
            <div class="banner-sec-head">Biometric Integration</div>
          </div>
          <div class="col-md-3 text-center">
            <div class="pin-img">
              <img src="image/location-pin.gif" alt="Location Tracking" />
            </div>
            <div class="banner-sec-head">Location Tracking</div>
          </div>
          <div class="col-md-3 text-center">
            <div class="gate-img">
              <img src="image/tap-to-pay.gif" alt="Payment Gateway" />
            </div>
            <div class="banner-sec-head">Payment Gateway</div>
          </div>
          <div class="col-md-3 text-center">
            <div class="class-img">
              <img src="image/presentation.gif" alt="Online Learning" />
            </div>
            <div class="banner-sec-head">Online Learning</div>
          </div>
        </div>
      </div>
    </section>

    <script>
      window.onload = function () {
  let controller = new ScrollMagic.Controller();

  let bannerScene = new ScrollMagic.Scene({
    triggerElement: ".banner",
    triggerHook: 0.8,
    reverse: false,
  })
    .setClassToggle(".banner", "slide-in-left")
    .addTo(controller);

  let bannerTopScene = new ScrollMagic.Scene({
    triggerElement: ".banner-top",
    triggerHook: 0.8,
    reverse: false,
  })
    .setClassToggle(".banner-top", "slide-in-right")
    .addTo(controller);
};

window.addEventListener("scroll", function () {
  const navbar = document.getElementById("my-nav");
  if (window.scrollY > 200) {
    navbar.classList.add("scrolled");
    console.log("Scrolled class added");
  } else {
    navbar.classList.remove("scrolled");
    console.log("Scrolled class removed");
  }
});
document.addEventListener("DOMContentLoaded", () => {
  const hrLeft = document.querySelector(".hr-left");
  const hrRight = document.querySelector(".hr-right");
  const animateOnScroll = () => {
    const triggerPoint = window.innerHeight * 0.7;
    if (hrLeft.getBoundingClientRect().top < triggerPoint) {
      hrLeft.classList.add("animate");
    }
    if (hrRight.getBoundingClientRect().top < triggerPoint) {
      hrRight.classList.add("animate");
    }
  };
  window.addEventListener("scroll", animateOnScroll);
  animateOnScroll();
});
document.addEventListener("DOMContentLoaded", function () {
  const leftColumn = document.querySelector(".col-left");
  const rightColumn = document.querySelector(".col-right");
  function checkPosition() {
    const leftPosition = leftColumn.getBoundingClientRect().top;
    const rightPosition = rightColumn.getBoundingClientRect().top;
    const screenPosition = window.innerHeight;
    if (leftPosition < screenPosition) {
      leftColumn.classList.add("animate");
    }
    if (rightPosition < screenPosition) {
      rightColumn.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition);
  checkPosition();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn2 = document.querySelector(".col-left2");
  const rightColumn2 = document.querySelector(".col-right2");
  function checkPosition2() {
    const leftPosition2 = leftColumn2.getBoundingClientRect().top;
    const rightPosition2 = rightColumn2.getBoundingClientRect().top;
    const screenPosition2 = window.innerHeight;
    if (leftPosition2 < screenPosition2) {
      leftColumn2.classList.add("animate");
    }
    if (rightPosition2 < screenPosition2) {
      rightColumn2.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition2);
  checkPosition2();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn3 = document.querySelector(".col-left3");
  const rightColumn3 = document.querySelector(".col-right3");
  function checkPosition3() {
    const leftPosition3 = leftColumn3.getBoundingClientRect().top;
    const rightPosition3 = rightColumn3.getBoundingClientRect().top;
    const screenPosition3 = window.innerHeight;
    if (leftPosition3 < screenPosition3) {
      leftColumn3.classList.add("animate");
    }
    if (rightPosition3 < screenPosition3) {
      rightColumn3.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition3);
  checkPosition3();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn4 = document.querySelector(".col-left4");
  const rightColumn4 = document.querySelector(".col-right4");
  function checkPosition4() {
    const leftPosition4 = leftColumn4.getBoundingClientRect().top;
    const rightPosition4 = rightColumn4.getBoundingClientRect().top;
    const screenPosition4 = window.innerHeight;
    if (leftPosition4 < screenPosition4) {
      leftColumn4.classList.add("animate");
    }
    if (rightPosition4 < screenPosition4) {
      rightColumn4.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition4);
  checkPosition4();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn5 = document.querySelector(".col-left5");
  const rightColumn5 = document.querySelector(".col-right5");
  function checkPosition5() {
    const leftPosition5 = leftColumn5.getBoundingClientRect().top;
    const rightPosition5 = rightColumn5.getBoundingClientRect().top;
    const screenPosition5 = window.innerHeight;
    if (leftPosition5 < screenPosition5) {
      leftColumn5.classList.add("animate");
    }
    if (rightPosition5 < screenPosition5) {
      rightColumn5.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition5);
  checkPosition5();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn6 = document.querySelector(".col-left6");
  const rightColumn6 = document.querySelector(".col-right6");
  function checkPosition6() {
    const leftPosition6 = leftColumn6.getBoundingClientRect().top;
    const rightPosition6 = rightColumn6.getBoundingClientRect().top;
    const screenPosition6 = window.innerHeight;
    if (leftPosition6 < screenPosition6) {
      leftColumn6.classList.add("animate");
    }
    if (rightPosition6 < screenPosition6) {
      rightColumn6.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition6);
  checkPosition6();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn7 = document.querySelector(".col-left7");
  const rightColumn7 = document.querySelector(".col-right7");
  function checkPosition7() {
    const leftPosition7 = leftColumn7.getBoundingClientRect().top;
    const rightPosition7 = rightColumn7.getBoundingClientRect().top;
    const screenPosition7 = window.innerHeight;
    if (leftPosition7 < screenPosition7) {
      leftColumn7.classList.add("animate");
    }
    if (rightPosition7 < screenPosition7) {
      rightColumn7.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition7);
  checkPosition7();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn8 = document.querySelector(".col-left8");
  const rightColumn8 = document.querySelector(".col-right8");
  function checkPosition8() {
    const leftPosition8 = leftColumn8.getBoundingClientRect().top;
    const rightPosition8 = rightColumn8.getBoundingClientRect().top;
    const screenPosition8 = window.innerHeight;
    if (leftPosition8 < screenPosition8) {
      leftColumn8.classList.add("animate");
    }
    if (rightPosition8 < screenPosition8) {
      rightColumn8.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition8);
  checkPosition8();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn9 = document.querySelector(".col-left9");
  const rightColumn9 = document.querySelector(".col-right9");
  function checkPosition9() {
    const leftPosition9 = leftColumn9.getBoundingClientRect().top;
    const rightPosition9 = rightColumn9.getBoundingClientRect().top;
    const screenPosition9 = window.innerHeight;
    if (leftPosition9 < screenPosition9) {
      leftColumn9.classList.add("animate");
    }
    if (rightPosition9 < screenPosition9) {
      rightColumn9.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition9);
  checkPosition9();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn10 = document.querySelector(".col-left10");
  const rightColumn10 = document.querySelector(".col-right10");
  function checkPosition10() {
    const leftPosition10 = leftColumn10.getBoundingClientRect().top;
    const rightPosition10 = rightColumn10.getBoundingClientRect().top;
    const screenPosition10 = window.innerHeight;
    if (leftPosition10 < screenPosition10) {
      leftColumn10.classList.add("animate");
    }
    if (rightPosition10 < screenPosition10) {
      rightColumn10.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition10);
  checkPosition10();
});

document.addEventListener("DOMContentLoaded", function () {
  const leftColumn11 = document.querySelector(".col-left11");
  const rightColumn11 = document.querySelector(".col-right11");
  function checkPosition11() {
    const leftPosition11 = leftColumn11.getBoundingClientRect().top;
    const rightPosition11 = rightColumn11.getBoundingClientRect().top;
    const screenPosition11 = window.innerHeight;
    if (leftPosition11 < screenPosition11) {
      leftColumn11.classList.add("animate");
    }
    if (rightPosition11 < screenPosition11) {
      rightColumn11.classList.add("animate");
    }
  }
  window.addEventListener("scroll", checkPosition11);
  checkPosition11();
});
AOS.init({
  offset: 100,
  duration: 700,
  easing: "ease-out-cubic",
  delay: 150,
});

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
    <script>
      window.onscroll = function () {
        scrollProgress();
      };

      function scrollProgress() {
        var winScroll =
          document.body.scrollTop || document.documentElement.scrollTop;
        var height =
          document.documentElement.scrollHeight -
          document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById("progressBar").style.width = scrolled + "%";
      }
    </script>
  </body>
</html>