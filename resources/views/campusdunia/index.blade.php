@extends('layouts.campusdunialayout')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CampusDunia | Credit Limit for Learners</title>
    <style> 
      @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");
      /* @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYBLACKITALIC.OTF")
          format("opentype");
        font-weight: 900;
        font-style: italic;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYBOLD.OTF") format("opentype");
        font-weight: 700;
        font-style: normal;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYHEAVYITALIC.OTF")
          format("opentype");
        font-weight: 900;
        font-style: italic;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYLIGHTITALIC.OTF")
          format("opentype");
        font-weight: 300;
        font-style: italic;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYMEDIUM.OTF")
          format("opentype");
        font-weight: 500;
        font-style: normal;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYREGULAR.OTF")
          format("opentype");
        font-weight: 400;
        font-style: normal;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYSEMIBOLDITALIC.OTF")
          format("opentype");
        font-weight: 600;
        font-style: italic;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYTHINITALIC.OTF")
          format("opentype");
        font-weight: 100;
        font-style: italic;
      }

      @font-face {
        font-family: "SF Pro Display";
        src: url("/font/sf-pro-display/SFPRODISPLAYULTRALIGHTITALIC.OTF")
          format("opentype");
        font-weight: 200;
        font-style: italic;
      } */

      /* Applying the font */
      body {
        /* font-family:system-ui, -apple-system, "Segoe UI", Roboto,
     "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif,
     "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
     "Noto Color Emoji";
        font-family: "SF Pro Display", sans-serif; */
        background: #000 !important;
      }
      section {
        place-content: center;
        justify-content: center;
        padding: 100px 0px 100px 0px !important;
      }

      /* Hero Section Styling */
      .hero-section {
        position: fixed;
        top: 100px;
        left: 0;
        width: 100vw;
        height: calc(100vh - 100px);
        /* z-index: 1; */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #000;
        padding: 100px 0px 100px 0px;
      }

      .credit-line-benefits {
        /* background: linear-gradient(to bottom, #333333 25%, #333333 100%); */
        /* background-image: url(/image/Rectangle\ 2.png); */
        /* height: 425px; */
        position: relative;
        z-index: 1;
        margin-top: calc(100vh - 80px);
        background-color: #1e1e1e;
        padding: 100px 0px 100px 0px;
      }

      .logo h1 {
        font-size: 60px;
        font-weight: 800;
      }

      .campus {
        color: #fff;
      }

      .powered {
        font-size: 18px;
        color: #ccc;
        align-items: center;
      }

      .smHighlight {
        color: #f39c12; /* Match the orange theme */
      }

      h2 {
        font-size: 44px;
        font-weight: 400;
        margin-top: -10px;
        margin-bottom: 25px;
      }

      .highlight {
        color: #f39c12;
        font-weight: 600;
      }

      .highlightWrapper .highlight {
        /* display: inline-block; */
        overflow: hidden;
        position: relative;
        height: 1em;
        vertical-align: bottom;
      }

      .highlightWrapper .highlight span {
        display: inline-block;
        position: absolute;
        width: 100%;
        text-align: left;
        animation: slideUp 1.5s ease-in-out infinite;
      }

      @keyframes slideUp {
        0% {
          transform: translateY(100%);
          opacity: 0;
        }
        50% {
          opacity: 1;
        }
        100% {
          transform: translateY(-100%);
          opacity: 0;
        }
      }

      .fade-out {
        opacity: 0;
      }

      /* Button Styling */
      .btn-warning {
        background-color: #f39c12;
        border: none;
        font-size: 18px;
        padding: 10px 20px;
      }

      /* Mobile App Mockup */
      .hero-Img {
        width: 100%;
        height: auto;
        -webkit-mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 65%,
          rgba(0, 0, 0, 0) 100%
        );
        mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 65%,
          rgba(0, 0, 0, 0) 100%
        );
      }

      /* Journey Step Section */
      .journey-step-section {
        /* background: linear-gradient(to bottom, #333333 70%, #000 100%); Dark theme */
        /* background-image: url(/image/Rectangle\ 1.png); */
        position: relative;
        z-index: 1;
        background-color: #000;
        padding: 100px 0px 100px 0px;
      }

      /* Features List */
      .features-list {
        list-style: none;
        padding: 0;
      }

      .features-list li {
        font-size: 16px;
        /*  margin-top: 15px;
        margin-bottom: 15px; */
        display: flex;
        align-items: center;
        letter-spacing: 1px;
      }

      .features-list li img {
        /* filter: brightness(0) invert(1); */
        margin-right: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
        width: 42px;
      }

      /* QR Code Section */
      /* .qr-code-container {
        margin-top: 50px;
      }

      .qr-box {
        background: #222;
        padding: 15px;
        display: inline-block;
        border-radius: 10px;
        text-align: center;
      }

      .qr-box img {
        width: 100px;
      }

      .qr-box p {
        color: #fff;
        font-size: 14px;
        margin-top: 5px;
      } */

      /* Account Section */
      .smart-card-section {
        /* background: linear-gradient(to bottom, #333333 75%, #000 100%); */
        /* background-image: url(/image/Rectangle\ 1.png); */
        position: relative;
        z-index: 1;
        background-color: #000;
        padding: 100px 0px 120px !important;
      }

      .heading {
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 50px;
      }
      .heading-two {
        font-size: 35px;
      }

      /* Features List */
      .features-list {
        list-style: none;
        padding: 0;
      }
      /*
      .features-list li {
        font-size: 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
      } */

      /* Brand Logo */
      .intro-card {
        width: 100%;
      }

      /* UPI Section */
      .pay-fee-section {
        /* background: linear-gradient(to bottom, #000 25%, #333333 100%); Dark Gray */
        /* background-image: url(/image/Rectangle\ 2.png); */
        position: relative;
        z-index: 1;
        background-color: #1e1e1e;
        padding: 60px 0px;
        color: #fff;
      }

      /* Features List */
      .features-list {
        list-style: none;
        padding: 0;
      }

      /* .features-list li {
        font-size: 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
      } */

      /* CTA Button */
      .cta-btn {
        background-color: #f39c12;
        color: #000;
        font-size: 18px;
        font-weight: 700;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
      }

      .cta-btn:hover {
        background-color: #d68910;
        color: #ffff;
      }

      /* UPI Icons */
      .upi-icons {
        width: 75%;
      }

      /* Reward Section */
      .reward-section {
        /* background: linear-gradient(
          to bottom,
          #000,
          #333333
        );  */
        /* background-image: url(/image/Rectangle\ 2.png); */
        position: relative;
        z-index: 1;
        background-color: #1e1e1e;
        padding: 100px 0px 100px 0px;
        color: #fff;
        display: flex;
        align-items: center;
        position: relative;
      }

      /* Left Side Gradient & Image */
      .left-side {
        position: relative;
      }

      .product-img {
        width: 55%;
        position: relative;
        top: 60px;
        -webkit-mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 60%,
          rgba(0, 0, 0, 0) 100%
        );
        mask-image: linear-gradient(
          to bottom,
          rgba(0, 0, 0, 1) 80%,
          rgba(0, 0, 0, 0) 100%
        );
      }

      /* Feature Buttons */
      .features {
        margin-top: 20px;
      }

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

      /* Phone Mockup */
      .phone-mockup {
        margin-top: 20px;
        width: 60%;
      }

      .membership-section {
        /* background-image: url(/image/Rectangle\ 1.png); */
        position: relative;
        z-index: 1;
        background-color: #000;
        padding: 100px 0px 100px 0px;
        color: white;
      }
      .support-section {
        /* background-image: url(/image/Rectangle\ 1.png); */
        position: relative;
        z-index: 1;
        background-color: #1e1e1e;
        padding: 100px 0px 100px 0px;
        color: white;
      }
      /* campusDunia Card Section */
      .campusDunia-card {
        width: 150px;
        margin-bottom: 20px;
      }

      .brand-name {
        font-size: 30px;
        font-weight: 700;
      }

      .features-list {
        list-style: none;
        padding: 0;
      }

      /* .features-list li {
        font-size: 18px;
        margin: 5px 0;
      } */

      /* Membership Section */
      .membership-card {
        background: linear-gradient(135deg, #333, #444);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        color: white;
      }

      .membership-card h3 {
        font-size: 22px;
        text-transform: capitalize;
      }

      .credit-line-card {
        position: relative;
        background: linear-gradient(
          135deg,
          #333,
          #444
        ); /* Keep the background */
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        color: white;
        overflow: hidden;
        -webkit-border-left: 5px solid #f39c12;
        border-left: 5px solid #f39c12;
        transition: color 0.4s ease-in-out;
        cursor: pointer;
      }

      /* Create the color fill effect */
      .credit-line-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: -100%; /* Start outside from the left */
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #ff891c, #ffc53b, #ffd643);
        transition: left 0.6s ease-in-out; /* Animate from left to right */
        z-index: 0; /* Place below text */
      }

      /* Expand the color fill when hovering */
      .credit-line-card:hover::after {
        left: 0; /* Moves to cover the card */
      }

      .credit-line-card .number-two {
        position: relative;
        font-size: 35px;
        font-weight: 600;
        display: inline-block;
        transition: color 0.4s ease-in-out;
        z-index: 1; /* Keep text above */
      }

      .credit-line-card:hover .number-two {
        color: black; /* Change text color when background fills */
      }

      .registUsers {
        -webkit-border-left: 5px solid #d35400;
        border-left: 5px solid #d35400;
      }
      .learners {
        -webkit-border-left: 5px solid #e67e22;
        border-left: 5px solid #e67e22;
      }
      .service {
        -webkit-border-left: 5px solid #e67e22;
        border-left: 5px solid #e67e22;
      }

      .finance {
        -webkit-border-left: 5px solid #d35400;
        border-left: 5px solid #d35400;
      }

      /* Support Section */
      .contact-btn {
        background-color: #f39c12;
        color: black;
        border: none;
        padding: 10px 20px;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
      }

      .contact-btn:hover {
        background-color: #e67e22;
      }

      /* Footer Styling */
    

      /* Social Links  */
      .social-links {
        display: flex;
        gap: 15px;
      }

      .social-icon {
        color: white;
        font-size: 16px;
        text-decoration: none;
      }

      .social-icon:hover {
        color: #f39c12;
      }

      /* Trusted By Section */
      .trusted-by-section {
        position: relative;
        z-index: 1;
        /* background-image: url(/image/Rectangle\ 2.png); */
        background-color: #000;
        color: #fff;
        padding: 100px 0px 100px 0px;
      }

      .trusted-by-section .highlight {
        color: #f89b29;
      }

      .trusted-partners {
        display: flex;
        flex-wrap: wrap;
        /* margin-top: 20px; */
      }

      .trusted-partners img {
        width: 130px;
      }

      .partners {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 50px;
        margin-top: 20px;
      }

      .partners img {
        max-height: 150px;
        width: 150px;
        /* filter: brightness(0) invert(0); */
      }

      /* Already Onboard Section */
      .already-onboard-section {
        position: relative;
        z-index: 1;
        /* background-image: url(/image/Rectangle\ 2.png); */
        background-color: #1e1e1e;
        color: #fff;
        padding: 100px 0px 100px 0px;
      }

      .already-onboard-section .highlight {
        color: #f89b29;
      }

      .stats {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin-top: 20px;
      }

      .stat-box {
        background: #1a1a1a;
        padding: 20px;
        border-radius: 10px;
        width: 200px;
        text-align: center;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
      }

      .stat-box .number {
        font-size: 24px;
        font-weight: bold;
        color: #f89b29;
      }

      .stat-box .label {
        font-size: 12px;
      }

      .number {
        font-size: 55px; /* Adjust as needed */
        font-weight: 800;
        background: linear-gradient(90deg, #ff891c, #ffc53b, #ffd643);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
      }

      .btn-td-hassle {
        margin-bottom: 70px;
      }

      /* .new-font-size {
        font-size: 70px;
      } */

      .cashback-content-all {
        margin-left: 100px;
      }

      .highlight-container {
        height: 70px;
        overflow: hidden;
        margin-left: 10px;
      }

      /* .highlight-list {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        transition: transform 1.1s ease-in-out;
      }

      .highlight-list .highlight {
        font-weight: bold;
        font-size: 52px;
        text-align: left;
        padding: 0;
        margin: 0;
        line-height: 72px;
      } */

      /* Rewards Section Images css */
      .rewardsImg {
        position: relative;
      }

      .rewardsGift {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 25%;
        left: 25%;
      }

      .rewardsGift img {
        width: 50px;
        opacity: 0; /* Initially hidden */
        transform: translateY(50px) scale(0.5); /* Start from below and smaller */
        animation-fill-mode: forwards;
      }

      /* Infinite Reveal and Pulse */
      .rewardsGift img:nth-child(1) {
        animation: revealGift 4s ease-in-out infinite 1s;
      }

      .rewardsGift img:nth-child(2) {
        animation: revealGift 4s ease-in-out infinite 1.2s;
      }

      .rewardsGift img:nth-child(3) {
        animation: revealGift 4s ease-in-out infinite 1.4s;
      }

      /* Keyframes for Smooth Reveal */
      @keyframes revealGift {
        0% {
          opacity: 0;
          transform: translateY(50px) scale(0.5);
        }
        20% {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
        80% {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
        100% {
          opacity: 0;
          transform: translateY(-20px) scale(1.1);
        }
      }

      /* Keyframes for Pulsing Effect */
      @keyframes pulse {
        0% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.1);
        }
        100% {
          transform: scale(1);
        }
      }

      /* SmartCard Section Card CSS */
      .card-container {
        position: relative;
        height: 310px;
        width: 205px;
        rotate: 17deg;
      }

      .back-card {
        margin-left: 80px;
        width: 205px !important;
        margin-top: 10px;
      }

      .intro-card {
        width: 100%;
        position: absolute;
        left: 60px;
        top: 0px;
      }

      .pulse {
        animation: pulse 3s infinite ease-in-out alternate;
      }

      @keyframes pulse {
        from {
          transform: scale(1);
        }
        to {
          transform: scale(1.2);
        }
      }

      .roll-animation {
        animation: roll-cycle 4s ease infinite alternate;
      }

      @keyframes roll-cycle {
        0% {
          opacity: 1;
          transform: translateX(-30%) rotate(-0deg);
        }
        50% {
          opacity: 1;
          transform: translateX(0px) rotate(0deg);
        }
        100% {
          opacity: 1;
          transform: translateX(50%) rotate(30deg);
        }
      }

    

      /* Path to success */
      
      /* Button css */
      .bttn {
        width: fit-content;
        cursor: pointer;
        --c: goldenrod;
        color: var(--c);
        font-size: 16px;
        border: 3px solid var(--c);
        border-radius: 0.5em;
        padding: 6px 10px;
        /* height: 3em; */
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 1px;
        text-align: center;
        /* line-height: 3em; */
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: 0.5s;
        /* margin: 1em; */
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
      /* .back-Img {
        background: url(/image/rewards-background-main.png);
        background-size: contain;
        background-repeat: no-repeat;
        position: absolute;
        right: 0;
        height: 100%;
        width: 36%;
        opacity: 0.3;
      } */
    
      /* 
      .hero-section {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 10;
      } */

      .hero-bottom {
        width: 100%;
        /* max-width: 1349px; */
        background: #1e1e1e;
        text-align: center;
        /* left: -111px; */
        position: absolute;
        margin-top: -20px;
        bottom: 0;
      }

      /* .hero-bottom {
        width: 100%;
        background: #1e1e1e;
        text-align: center;
      } */
      .hero-bottom div{
        place-content: center;
      }
      .hero-bottom div img{
        width: 130px;
      }

      .hero-bottom .br-right {
        position: relative;
      }

      .hero-bottom .br-right::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 35px;
        border-right: 1px solid #fff;
      }

      /* Modal CSS */
      .modal-theme{
background: #1e1e1e !important;
    color: white !important;
      }
      .modal-theme form input{
        background: #000;
        color: white;
      }
      .modal-theme form input.form-control::placeholder {
        color:rgb(233, 233, 233);
      }

      .modal-theme form input:focus{ 
        background: #000;
        color: white;
        outline: none;
        border: 1px solid white;
        box-shadow: 0 0 0 .25rem rgb(253 201 13 / 25%);
      }
      .modalBtn{
        width: 100%;
        justify-content: center;
        display: flex;
      }
      .btn-close-custom{
        filter: invert(1);
        opacity: 1;
        width: 1.25rem;
        height: 1.25rem;
      }
      .modal-header .btn-close {
        transition: transform 0.4s ease-in-out;
      }

      .modal-header .btn-close:hover {
        transform: rotate(90deg);
      }
      .modal-title{
        width: 100%;
        text-align: center;
      }


      /*hero-section-new-start*/
        .hero {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding: 4rem 2rem;
      }
      .left,
      .right {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
      }
      .right {
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .image-slider-container {
        height: 300px;
        overflow: hidden;
        position: relative;
        width: 400px;
        border-radius: 12px;
      }
      .image-slider-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        transition: transform 1s ease-in-out;
      }
      .image-slide {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .image-slide img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
      }
      h1 {
        font-size: 3rem;
        font-weight: bold;
        line-height: 1.2;
        margin: 1rem 0;
      }
      .word-rotator {
        height: 60px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
      }
      .word-slider {
        display: flex;
        flex-direction: column;
        transform: translateY(0);
        transition: transform 1s ease-in-out;
      }
      .word {
        height: 60px;
        display: flex;
        align-items: center;
        font-size: 2rem;
        font-weight: 800;
        color: #f39c12;
      }
      .tagline {
        font-weight: bold;
        color: #f39c12;
        font-size: 1rem;
        margin-bottom: 0.5rem;
      }
      .buttons {
        margin-top: 2rem;
      }
      .demo,
      .products {
        font-size: 1rem;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
      }
      .demo {
        background: white;
        color: #20285B;
        border: none;
        margin-right: 1rem; 
      }
      .products {
        background: transparent;
        color: white;
        border: 2px solid white;
      }
      @media (max-width: 768px) {
        .left,
        .right {
          max-width: 100%;
          text-align: center;
        }
        .hero {
          flex-direction: column;
        }
        .buttons {
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .demo,
        .products {
          margin: 0.5rem 0;
        }
        .image-slider-container {
          width: 100%;
        }
      }
      /*hero-section-new-end*/
      .most-unified-text
      {
        font-size:50px !important;
        font-weight:700 !important;
      }
      .introducing-campusdunia
      {
        text-align:center;
        width:100%;
        font-size:40px;
        font-weight:700;
        margin-bottom:20px;
      }
   /*MEDIA QUERIES START*/
   @media only screen and (max-width: 768px)
   {
    
.hero-section {
    position: fixed;
    top: -60px;
    left: 0;
    width: 100vw;
    /* height: calc(100vh - -100px); */
    /* z-index: 1; */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000;
    padding: 100px 0px 100px 0px;
    height: 890px;
}
.most-unified-text
      {
        font-size:35px !important;`
        font-weight:700 !important;
      }
.word {
    height: 60px;
    display: flex;
    align-items: center;
    font-size: 1.3rem;
    font-weight: 800 !important;
    color: #f39c12;
}
.button-main-apply
{
  width:100%;
  display:flex;
  justify-content: space-around;
}
   }
   /*MEDIA QUERIES END*/
</style>
  </head>
  <body>
    <!-- Hero Section -->
    <section style="min-height: 1px"></section>
    <section class="hero-section text-white">
      <section class="hero">
        <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <div class="left">
                <p class="tagline"><img
                  src="image/highlight.png"
                  alt="Highlight"
                  class="me-1"
                  style="width: 25px !important; height: 25px !important"
                />FEES MADE FANTASTIC!</p>
                <h1 class="most-unified-text" style="">
                  India’s most unified
                  <span class="word-rotator">
                    <span class="word-slider" id="wordSlider">
                      <span class="word">Fee Collection Platform for Institute</span>
                      <span class="word">Credit Limit Platform for Learners</span>
                      <span class="word">Fee Collection Platform for Institute</span>
                      <!-- clone for loop -->
                    </span>
                  </span>
                </h1>
                <div class="button-main-apply">
                  <div class="bttn bttn-two mt-2 mb-4"data-bs-toggle="modal" data-bs-target="#waitlistModal">
                    Apply
                    <span></span><span></span><span></span><span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="right">
                <div class="image-slider-container">
                  <div class="image-slider-wrapper" id="imageSlider">

                    <div class="image-slide">
                      <div class="hero-img-position">
                        <img src="image/institute-hero.png" alt="Hero Image" class="hero-Img" />
                      </div>
                    </div>
                    
                    <div class="image-slide">
                      <div class="hero-img-position">
                        <img src="image/final-hero-shade-new.png" alt="Hero Image" class="hero-Img" width="100%" />
                      </div>
                    </div>
                    <div class="image-slide">
                      <div class="hero-img-position">
                        <img src="image/institute-hero.png" alt="Hero Image" class="hero-Img" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </section>
      <script>
      const wordSlider = document.getElementById("wordSlider");
      const imageSlider = document.getElementById("imageSlider");
      const wordHeight = 60;
      const imageHeight = 300;
      let index = 0;
      function nextSlide() {
        index++;
        wordSlider.style.transform = `translateY(-${index * wordHeight}px)`;
        imageSlider.style.transform = `translateY(-${index * imageHeight}px)`;
        if (index === 2) {
          setTimeout(() => {
            wordSlider.style.transition = "none";
            imageSlider.style.transition = "none";
            index = 0;
            wordSlider.style.transform = `translateY(0)`;
            imageSlider.style.transform = `translateY(0)`;
            void wordSlider.offsetWidth;
            void imageSlider.offsetWidth;
            wordSlider.style.transition = "transform 1s ease-in-out";
            imageSlider.style.transition = "transform 1s ease-in-out";
          }, 1000);
        }
      }
      setInterval(nextSlide, 3000);
    </script>
      <div class="hero-bottom">
          <div class="row"> 
            <div class="col-md-3 br-right">powered by</div>
            <div class="col-md-3"><img src="image/iciciLogo.png" alt="ICICI Icon"></div>
            <div class="col-md-3"><img src="image/visaIcon.png" alt="Visa Logo"></div>
            <div class="col-md-3"><img src="image/rupay-logo.png" alt="Rupay Logo" style="width:110px;"></div>
          </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="waitlistModal" tabindex="-1" aria-labelledby="waitlistModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content rounded-4 modal-theme">
          <div class="modal-header">
            <h5 class="modal-title" id="waitlistModalLabel">Join the Waitlist</h5>
            <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="userName" class="form-label">Name</label>
                <input type="text" class="form-control" id="userName" placeholder="Enter your name">
              </div>
              <div class="mb-3">
                <label for="userNumber" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="userNumber" placeholder="Enter your phone number">
              </div>
              <div class="mb-3">
                <label for="userEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="userEmail" placeholder="Enter your email">
              </div>
              <div class="modalBtn">
                <div class="bttn bttn-two mt-2">
                  Submit
                  <span></span><span></span><span></span><span></span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <section class="credit-line-benefits text-white">
   <div
        class="container"
        data-aos="fade-up"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <div class="row align-items-center">
          <div class="col-sm-12">
            <div class="introducing-campusdunia">
              Introducing <span class="highlight" style="font-weight:700!important;">CampusDunia</span]>
            </div>
          </div>
          <!-- Left Content -->
          <div
            class="col-lg-6" 
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="hero-content-all">
              <div class="logo">
                <!-- <h1 class="text-uppercase">
                  <span class="campus">CampusDunia</span>
                </h1> -->
              </div>
              <h2 class="mt-4 mb-0 highlightWrapper hero-heading" style="font-weight:700!important;">
                Empowering Learners with <span class="highlight" style="font-weight:700!important;">Easy Financial Access</span>
                <!-- India's First Credit Line
                <div class="d-flex text-align-center">
                  for
                  <div class="content">
                    <div class="slider-wrapper">
                      <div class="slider">
                        <div class="sli der-text-1">Young Millennials</div>
                        <div class="slider-text-2">&</div>
                        <div class="slider-text-3">Gen Z</div>
                      </div>
                    </div>
                  </div> -->
                <!-- </div> -->
              </h2>
              <p class="powered my-4 text-white d-flex">
                <!-- <i
                  class="fa-solid fa-star"
                  style="color: #f39c12; width: 25px"
                ></i> -->
                <img
                  src="image/highlight.png"
                  alt="Highlight"
                  class="me-1"
                  style="width: 25px !important; height: 25px !important"
                />
                <span class="number" style="font-size: 18px"
                  >LEARN MORE & DREAM BIGGER</span
                >
              </p>
              <div
                class="bttn bttn-two mt-2 mb-4"
                data-bs-toggle="modal"
                data-bs-target="#waitlistModal"
              >
                Join Waitlist
                <span></span><span></span><span></span><span></span>
              </div>
            </div>
          </div>
          <!-- Right Image -->
          <div
            class="col-lg-6 text-center"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="hero-img-td">
              <div class="hero-img-position">
                <img
                  src="image/final-hero-shade-2.png"
                  alt="Hero Image"
                  class="hero-Img"
                />
              </div>
            </div>
          </div>
        </div>
      </div>



          <div class="container partners text-center">
        <div class="row w-100">
          <h2
            class="heading"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
            style="font-size: 42px"
          >
            <!-- Get CampusDunia
            <span class="highlight">Credit line @ 0% interest rate</span> -->
            <span class="theme-color-code" style="font-weight:700!important;" >Get CampusDunia
              <a
                href=""
                class="highlight typewrite theme-color-code"
                style="text-decoration: none;font-weight:700!important;"
                data-period="2000"
                data-type='["Credit limit @ 0% interest rate"]'
              >
                <span class="wrap"></span>
              </a>
            </span>
          </h2>

          <div
            class="col-md-4"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="credit-line-card registeredUsers">
              <h3 class="number-two">
                No Joining <br />
                Fees
              </h3>
              <!-- <p class="label">Registered Users</p> -->
            </div>
          </div>
          <div
            class="col-md-4"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="credit-line-card registeredUsers">
              <h3 class="number-two">
                No Annual <br />
                Charges
              </h3>
              <!-- <p class="label">Registered Users</p> -->
            </div>
          </div>
          <div
            class="col-md-4"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="credit-line-card registeredUsers">
              <h3 class="number-two">
                No Hidden <br />
                Cost
              </h3>
              <!-- <p class="label">Registered Users</p> -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Smart Card Section -->
    <section class="smart-card-section text-white">
      <div
        class="container"
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <h2 class="heading text-center" style="font-weight:600!important;">CampusDunia <span class="highlight" style="font-weight:600!important;"> Smart Card</span></h2>
        <div class="row align-items-center">
          <!-- Left Content -->
          <div
            class="col-lg-6"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <h2 class="heading-two">
              <span class="highlight" style="font-weight:600!important;">3 in 1 Smart Card</span>
            </h2>
            <h5>A Hassle-Free Smart Card for Payments, Identity & Security</h5>
            <ul class="features-list">
              <li>
                <img src="image/credit-card.png" alt="Zero Balance" /> Prepaid card
                for seamless payments
              </li>
              <li>
                <img src="image/idAccessCard.png" alt="Id Access" /> Student ID and
                campus access card
              </li>
              <li>
                <img src="image/spendTracking.png" alt="Tracking" />
                Parental spend tracking for better expense management
              </li>
              <!-- <li>
                <img src="/image/bank.png" alt="Bank" /> No Need for a Bank
                Account
              </li> -->
            </ul>
          </div>

          <!-- Right Image -->
          <div
            class="col-lg-6 text-center"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="card-container">
              <img
                src="image/prepaid-card-back.png"
                alt=""
                class="intro-card back-card roll-animation"
              />
              <img
                src="image/PREAPID-CARD.png"
                alt="campusDunia "
                class="intro-card front-card pulse"
              />
            </div>
          </div>
        </div>
      </div>
      <style>
        .bigText {
          font-weight: 600;
          font-size: 100px;
          letter-spacing: -0.02em;
          margin-bottom: 0px;
          /* font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; */
          color: transparent;
          -webkit-text-stroke: 1px rgb(255, 255, 255);
          transition: 0.4s;
          cursor: pointer;
        }

        .bigText span:hover {
          color: white !important;
          -webkit-text-stroke: 1px rgb(66, 66, 66);
        }

        .bigText span,
        button {
          color: inherit;
          outline: none;
          border: none;
          background: transparent;
          text-decoration: none;
        }

        .bigText-item {
          position: relative;
          text-align: center;
        }

        .drop-effect-img {
          position: absolute;
          bottom: 50%;
          left: 0;
          right: 0;
          margin: 0 auto;
          width: max-content;
          opacity: 0;
          visibility: hidden;
          z-index: -1;
          transition: 0.4s;
        }

        .bigText-item:hover .drop-effect-img {
          opacity: 1;
          visibility: visible;
          z-index: -1;
          bottom: 35%;
        }

        .drop-effect-img img {
          transform: scale(0.9);
          transition: transform 0.3s ease;
        }

        .bigText-item:hover .drop-effect-img img {
          transform: scale(1);
        }

        .bigText span {
          transition: 0.3s ease-out;
        }
        .drop-effect-img img {
          width: 120px;
        }
      </style>
      <div class="container" style="margin-top: 130px;">
        <div class="row">
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
            <div class="bigText-item">
              <div class="drop-effect-img">
                <img src="image/spend.png" alt="Earn" />
              </div>
              <h3 class="bigText"><span>Spend.</span></h3>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
            <div class="bigText-item">
              <div class="drop-effect-img">
                <img src="image/access.png" alt="Save" />
              </div>
              <h3 class="bigText"><span>Access.</span></h3>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
            <div class="bigText-item">
              <div class="drop-effect-img">
                <img src="image/track.png" alt="Spend" />
              </div>
              <h3 class="bigText"><span>Track.</span></h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pay Fee Section -->
    <section class="pay-fee-section">
      <div
        class="container"
        data-aos="fade-up"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <h2 class="heading text-center" style="font-weight:600!important;">For Institute</h2>
        <div class="row align-items-center">
          <!-- Left Side: Gradient Background with Products -->
          <div
            class="col-lg-6 left-side"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img
              src="image/fee-collection.png"
              alt="UPI Features"
              class="upi-icons"
            />
            <!-- <img
              src="/image/wifi.gif"
              alt=""
              style="
                position: absolute;
                top: 25%;
                left: 45%;
                width: 10%;
                rotate: 90deg;
              "
            /> -->
          </div>

          <!-- Right Side: Text and Features -->
          <div
            class="col-lg-6"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
            >
            <h2 class="heading-two" style="font-weight:600!important;">
              <span class="highlight" style="font-weight:600!important;"> Hassle-Free </span>Fee Collection
            </h2>
            <h5>
              Set up auto-debit for easy EMI payments.
              <!-- Say goodbye to payment follow-ups <br />
              -Enable auto-debit for seamless transactions -->
            </h5>
            <ul class="features-list">
              <li>
                <img src="image/payment.png" alt="Automatic Payment" />
                Automate fee collection with scheduled payments
              </li>
              <li>
                <img src="image/swipe.png" alt="Online" /> Accept UPI, cards,
                and net banking seamlessly
              </li>
              <li>
                <img src="image/business.png" alt="Real time payment" />
                Real-time payment tracking and instant settlements
              </li>
              <!-- <li>✅ Secure and transparent transactions for institutes</li> -->
            </ul>
            <a href="institute.php" style="text-decoration:none;">
            <div class="bttn">
              <!-- <button class="cta-btn btn-td-hassle"> -->
              Learn More
              <span></span><span></span><span></span><span></span>
              <!-- </button> -->
            </div>
      </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Journey Section -->
    <!-- <section class="journey-step-section text-white text-center">
      <div
        class="container"
        data-aos="fade-up"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <h2 class="heading" style="margin-bottom: 25px">
          Your <span class="highlight">Path to Success</span>
        </h2>
        <h5>Four simple steps to find your perfect education.</h5>
        <div class="row">
          <div
            class="col-md-3 step"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/success-1.png" alt="Select Institute" />
            <p>Select your desired institute and course.</p>
          </div>
          <div
            class="col-md-3 step"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/success-2.png" alt="Apply" />
            <p>Provide and verify your details and documents online.</p>
          </div>
          <div
            class="col-md-3 step"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/success-3.png" alt="Approval" />
            <p>On-the-spot approval and instant support from our team.</p>
          </div>
          <div
            class="col-md-3 step"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/success-4.png" alt="Repay" />
            <p>
              Loan is disbursed, and you repay in flexible EMIs.
              
            </p>
          </div>
        </div>
        <div style="display: flex; justify-content: center; margin-top: 30px">
          <div class="bttn bttn-two">
           
            Apply Now
            <span></span><span></span><span></span><span></span>
            
          </div>
        </div>
      </div>
    </section> -->

      <!----FOR STUDENTS SECTION START>-->
<!-- Payment Option 3 -->
    <section class="journey-step-section multiple-payment-options text-center" style="color:#fff;">
      <div class="container">
        <h2 class="heading text-center" style="font-weight:600!important;">For Students</h2>
        <div class="row">
          
          <div
            class="col-md-6"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
            >
            <h2 class="heading-two" style="text-align:left;margin-top:50px;font-weight:600!important;">
              <span class="highlight" style="font-weight:600!important;"> Empowering Ambition, </span>not just Financing needs
            </h2>
            <h5 style="text-align:left">
              
              <!-- Say goodbye to payment follow-ups <br />
              -Enable auto-debit for seamless transactions -->
            </h5>
            <ul class="features-list">
              <li>
                <img src="image/payment.png" alt="Automatic Payment" />
                Auto-debit subscriptions
              </li>
              <li>
                <img src="image/swipe.png" alt="Online"  style="width:36px"/> Get Rewards on Payfee
              </li>
              <li>
                <img src="image/business.png" alt="Real time payment" />
                Customize Fee Plans
              </li>
              <!-- <li>✅ Secure and transparent transactions for institutes</li> -->
            </ul>
            <a href="pay-fee.php" style="text-decoration:none;">
            <div class="bttn">
              <!-- <button class="cta-btn btn-td-hassle"> -->
              Learn More
              <span></span><span></span><span></span><span></span>
              <!-- </button> -->
            </div>
        </a>
          </div>
          <div class="col-md-6">
            <img
              src="image/for-students-all-home.png"
              class="img-fluid"
              alt="Online Payment"
              style="width: 100%"
            />
          </div>
          
        </div>
      </div>
    </section>
      <!--FOR STUDENTS SECTION END>-->
    <!-- Rewarding Spending Account Section -->
    <section class="reward-section">
      <div
        class="container-fluid"
        data-aos="fade-up"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <!-- <div class="back-Img"></div> -->
        <h2 class="heading text-center" style="font-weight:600!important;">Benefits</h2>
        <div class="row align-items-center">
          <!-- Left Side: Gradient Background with Products -->
          <div
            class="col-lg-6 left-side"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="cashback-content-all">
              <h2 class="heading-two" style="font-weight:600!important;">
                Earn Rewards &
                <span class="highlight" style="font-weight:600!important;">Cashback Upto 1%</span>
              </h2>
              <h5>Perks of CampusDunia</h5>
              <div class="features">
                <button
                  class="feature-btn"
                  data-aos="fade-right"
                  data-aos-duration="4000"
                  data-aos-delay="50"
                  data-aos-offset="100"
                >
                  <img src="image/rupee.png" alt="Cashback" /> Get instant
                  cashback of upto 1% on each transaction
                </button>
                <button
                  class="feature-btn"
                  data-aos="fade-up"
                  data-aos-duration="500"
                  data-aos-delay="50"
                  data-aos-offset="100"
                >
                  <img src="image/party-popper.png" alt="Reward Points" /> Earn
                  50 Reward points by inviting your amazing friends
                </button>
                <button
                  class="feature-btn"
                  data-aos="fade-up"
                  data-aos-duration="500"
                  data-aos-delay="50"
                  data-aos-offset="100"
                >
                  <img src="image/gift.png" alt="Rewards" /> Get instant
                  rewards & discounts with CampusDunia Smart Card.
                </button>
              </div>
            </div>

            <!-- <img src="iphone-mockup.png" alt="App UI" class="phone-mockup" /> -->
          </div>

          <!-- Right Side: Text and Features -->
          <div class="col-lg-6 text-center">
            <div
              class="rewardsImg"
              data-aos="fade-up"
              data-aos-duration="500"
              data-aos-delay="50"
              data-aos-offset="100"
            >
              <img
                src="image/rewards.png"
                alt="Product Cards"
                class="product-img"
              />
              <div
                class="rewardsGift"
                data-aos="fade-up"
                data-aos-duration="500"
                data-aos-delay="50"
                data-aos-offset="100"
              >
                <img src="image/gift-1.png" alt="Gift" class="mb-2" />
                <img src="image/gift-2.png" alt="Gift" class="mb-2" />
                <img src="image/gift-3.png" alt="Gift" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Already Onboard Section -->
    <!-- <section class="already-onboard-section">
      <div class="container text-center">
        <div class="stats">
          <div class="stat-box"></div>
          <div class="stat-box"></div>
          <div class="stat-box"></div>
          <div class="stat-box"></div>
        </div>
      </div>
    </section> -->

    <!-- Membership Plans -->
    <section class="membership-section text-center">
      <h2
        class="heading new-font-size" style="font-weight:600!important;"
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        Already <span class="highlight" style="font-weight:600!important;">Onboard</span>
      </h2>
      <div class="container">
        <div class="row">
          <div
            class="col-md-3"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="membership-card registeredUsers registUsers">
              <h3 class="number">6,00,000+</h3>
              <p class="label">Registered Users</p>
            </div>
          </div>
          <div
            class="col-md-3"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="membership-card learners">
              <h3 class="number">25,000+</h3>
              <p class="label">Learners Served</p>
            </div>
          </div>
          <div
            class="col-md-3"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="membership-card finance">
              <h3 class="number">128 Cr+</h3>
              <p class="label">Got Fee Finance</p>
            </div>
          </div>
          <div
            class="col-md-3"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <div class="membership-card service">
              <h3 class="number">25+ Cities</h3>
              <p class="label">Servicing Across</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 24/7 Support Section -->
    <section class="support-section text-center">
      <div class="container">
        <h2
          class="heading-two new-font-size" style="font-weight:600!important;"
          data-aos="fade-down"
          data-aos-duration="500"
          data-aos-delay="50"
          data-aos-offset="100"
          style="align-items: center"
        >
          Our support team is
          <span class="highlight" style="font-weight:600!important;"
            >active
            <img src="image/support3.gif" alt="" style="width: 10%" />x7</span
          >
        </h2>
        <h5
          data-aos="fade-up"
          data-aos-duration="500"
          data-aos-delay="50"
          data-aos-offset="100"
        >
          Feel free to chat with our support team whenever you need more
          clarity.
        </h5>

        <div
          style="display: flex; justify-content: center; margin-top: 30px"
          data-aos="fade-up"
          data-aos-duration="500"
          data-aos-delay="50"
          data-aos-offset="100"
        >
          <div class="bttn bttn-two">
            <!-- <button class="cta-btn contact-btn"> -->
            Contact Us
            <span></span><span></span><span></span><span></span>
            <!-- </button> -->
          </div>
        </div>
      </div>
    </section>

    <!-- Trusted By Section -->
    <section class="trusted-by-section">
      <div
        class="container text-center"
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="50"
        data-aos-offset="100"
      >
        <h2 class="heading-two new-font-size" style="font-weight:600!important;">
          We are <span class="highlight" style="font-weight:600!important;">trusted</span> by
        </h2>
        <h5
          data-aos="fade-left"
          data-aos-duration="500"
          data-aos-delay="50"
          data-aos-offset="100"
        >
          We are here to simplify your education journey
        </h5>
        <div class="trusted-partners">
          <div
            class="col-md-3"
            data-aos="fade-up"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/punjabGovt.png" alt="Elevation" />
          </div>
          <div
            class="col-md-3"
            data-aos="fade-right"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/startup-india.png" alt="Sequoia" />
          </div>
          <div
            class="col-md-3"
            data-aos="fade-left"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/elcot.png" alt="Venture Highway" />
          </div>
          <div
            class="col-md-3"
            data-aos="fade-down"
            data-aos-duration="500"
            data-aos-delay="50"
            data-aos-offset="100"
          >
            <img src="image/inc42.png" alt="General Catalyst" />
          </div>
          <!-- <img src="ycombinator-logo.png" alt="Y Combinator" />
          <img src="gfc-logo.png" alt="GFC" />
          <img src="rocketship-logo.png" alt="Rocketship VC" />
          <img src="greenoaks-logo.png" alt="Greenoaks Capital" /> -->
        </div>
      </div>
    </section>
  </body>
  <script>
    // scroll animation

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
  </script>
</html>

