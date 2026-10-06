<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
/>
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
<style>
  @import url("https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Comfortaa:wght@300..700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");
  /* Header Styling */
  * {
    font-family: "DM Sans", sans-serif;
  }
  .header {
    font-family: "DM Sans", sans-serif;
    /* font-family: system-ui, -apple-system, "Segoe UI", Roboto,
     "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif,
     "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
     "Noto Color Emoji" !important; */
    background: #000;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    /* padding: 10px 0; */
    backdrop-filter: blur(10px);
    min-height: 120px;
    transition: transform 0.3s ease-in-out, background 0.3s ease-in-out;
  }
  .navbar-nav .nav-link.active-link {
    color: #f39c12 !important;
  }

  /* Hide header when scrolling down */
  .header.hidden {
    transform: translateY(-100%);
  }

  /* Transparent effect when scrollin-g up */
  .header.transparent {
    background: rgba(51, 51, 51, 0.8); /* Dark with transparency */
    backdrop-filter: blur(5px);
  }

  .nav-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .logo h2 {
    font-size: 24px;
    font-weight: 700;
    color: white;
  }

  .nav-links {
    display: flex;
    gap: 25px;
  }

  .nav-links a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 1.5px;
    padding: 0px 10px;
  }

  .nav-links a:hover {
    color: #f39c12;
  }

  /* Call-to-Action Button */
  .cta-btn-nav {
    background-color: #f39c12;
    color: black;
    font-size: 16px;
    font-weight: 700;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .cta-btn-nav:hover {
    background-color: #e67e22;
  }

  /* navbar Nav links */
  .nav-link-nav {
    color: white !important;
    text-decoration: none !important;
    font-size: 18px !important;
    font-weight: 400 !important;
    letter-spacing: 1.5px;
    padding: 0px 15px !important;
  }

  .dropdown-menu {
    background-color: #222 !important;
  }

  .dropdown-item {
    color: #ffff !important;
    background-color: #222 !important;
  }

  .header-navbar-td {
    margin-top: 100px;
  }

  @media (min-width: 992px) {
    .navbar .dropdown:hover .dropdown-menu {
      display: block;
      margin-top: 0;
    }
  }

  /* Path to success */
  .step img {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    margin-top: 35px;
  }

  .step {
    transition: transform 0.5s ease-in-out;
  }

  .step:hover {
    cursor: pointer;
    transform: scale(1.04);
  }

  .step p {
    font-size: 16px;
  }

  /* hero-Text */
  /* / Container styles / */
  .content {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .slider-wrapper {
    font-size: 50px;
  }

  .slider {
    height: 65px;
    overflow: hidden;
    margin-left: 15px;
  }

  .slider div {
    height: 50px;
    padding: 2px 0px;
    color: #f39c12;
    text-align: left;
    margin-bottom: 50px;
    box-sizing: border-box;
  }

  .slider-text-1 {
    animation: slide 7s linear infinite;
  }

  @keyframes slide {
    0% {
      margin-top: -300px;
    }
    5% {
      margin-top: -200px;
    }
    33% {
      margin-top: -200px;
    }
    38% {
      margin-top: -100px;
    }
    66% {
      margin-top: -100px;
    }
    71% {
      margin-top: 0px;
    }
    100% {
      margin-top: 0px;
    }
  }

  @media screen and (max-width: 768px) {
    section {
      padding-left: 10px;
      overflow: hidden;
    }
    .header {
      padding: 0px 0;
    }
    .partners {
      padding-left: 0;
    }
    .logo h1 {
      font-size: 40px;
    }
    h2 {
      font-size: 32px;
    }
    .slider-wrapper {
      font-size: 32px;
    }
    .bttn {
      font-size: 12px;
      padding: 6px 10px;
    }
    .credit-line-benefits {
      height: auto;
    }
    .heading-two {
      font-size: 32px;
    }
    .credit-line-card .number-two {
      font-size: 25px;
    }
    .heading {
      font-size: 38px;
    }
    .card-container {
      position: relative;
      height: 350px;
      width: 200px;
      rotate: 10deg;
    }
    .intro-card {
      position: absolute;
      left: 36px;
      top: 25px;
    }
    .cashback-content-all {
      margin-left: 0px;
    }
    .rewardsGift img {
      width: 35px;
    }
    .trusted-partners {
      justify-content: space-evenly;
    }
    .space-top {
      margin-top: 20px;
    }
  }

  header .nav-item a {
    color: #fff !important;
    margin-right: 10px;
    letter-spacing: 1px;
  }
  h2,
  h1,
  h3,
  h4,
  h5,
  h6,
  p,
  span {
    font-weight: 300 !important;
  }
  h1,
  h2 {
    font-size: 40px !important;
  }
  p {
    font-size: 20px !important;
  }
  .bttn {
    font-weight: 500 !important;
  }
  .nav-link {
    font-weight: 300 !important;
    font-size: 20px !important;
  }
  /* Button css */
  .bttn {
    width: fit-content;
    cursor: pointer;
    --c: #f39c12;
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
</style>
<header class="header" style="color: white !important; background: #000">
  <!-- Top Navbar: Logo and CTA Button -->
  <nav class="navbar navbar-light">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand" href="index.php">
        <img src="image/logo.png" alt="" width="180px" />
      </a>
     
    </div>
  </nav>

  <!-- Bottom Navbar: Navigation Links -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="border-bottom: 1px solid #646464">
    <div class="container">
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        class="collapse navbar-collapse"
        id="navbarNav"
        style="justify-content: flex-end"
      >
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="about.php">About Us</a>
          </li>
          <!-- <li class="nav-item">
                <a class="nav-link" href="pay-fee.php">Students</a>
              </li> -->
          <li class="nav-item">
            <a class="nav-link" href="student.php">Students</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="institute.php">Institutes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: false,
  });

  let lastScrollTop = 0;
  const header = document.querySelector(".header");
  window.addEventListener("scroll", () => {
    let currentScroll = window.scrollY;
    if (currentScroll > lastScrollTop && currentScroll > 50) {
      // Scrolling down - Hide header
      header.classList.add("hidden");
      header.classList.remove("transparent");
    } else {
      // Scrolling up - Show header & make it transparent
      header.classList.remove("hidden");
      if (currentScroll > 50) {
        header.classList.add("transparent");
      } else {
        header.classList.remove("transparent");
      }
    }
    lastScrollTop = currentScroll;
  });
  document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
    navLinks.forEach((link) => {
      const href = link.getAttribute("href");
      if (
        href === currentPath ||
        (href === "index.php" && currentPath === "")
      ) {
        link.classList.add("active-link");
      }
    });
  });
</script>
