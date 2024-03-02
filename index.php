<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SchoolCode Africa</title>
	<link rel="stylesheet" href="style.css">
	<link rel="shortcut icon" href="images/logo.jpeg" type="image/x-icon">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-V/4PL2JXt9tMUApqDiTmmog4DnIf9q5OpiW1T2l5lRg2rZ2HBi4BNV7VQQ3yP3sKnEdR7wgAsZ9fbtTMbf3RVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
</head>
<body>

	<!----------This is for the header where you will fond the logo, ul, li, and the intros----------->
	<header>
	  <div class="head">
	   <a href="/">
		<div class="logo">
			<img src="images/logo.jpeg" alt="logo" srcset="">
		</div>
	  </a>
		<ul>
		  <a href="#home"><li>HOME</li></a>	
			<a href="#about"><li>ABOUT US</li></a>
			<a href="#services"><li>SERVICES</li></a>
			<a href=""><li>COURSES</li></a>
		</ul>
		<a href="contact.php">
		 <button>CONTACT US</button>
	    </a>
		<!---For the menu bar-->
			<svg onclick="toggleMenu()" class="menu" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M2 6a1 1 0 0 1 1-1h18a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1m0 6.032a1 1 0 0 1 1-1h18a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1m1 5.033a1 1 0 1 0 0 2h18a1 1 0 0 0 0-2z" /></svg>
	  </div>
	  
	  
	  <div class="code_text__home" id="home">
		<div class="text_page">
		   <div class="text_body">
			  <h1>Let solve <span class="solve_prop">Problems</span> through <span class="soft_ware">Software Building</span></h1>
		      <h4>All you need is to <span class="start_j">START</span></h4>
		   </div>
		   <a href="#services">
			<button>
				Start your Journey
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
					<path fill="currentColor" d="M8.6 3.4L14.2 9H2v2h12.2l-5.6 5.6L10 18l8-8l-8-8z"/>
				</svg>
			</button>			
		</a>
		 </div>
	  </div>
	  <!----For ANimation Circle-->
	  <div class="circle" id="circle1"></div>
	  <div class="circle" id="circle2"></div>
	  <div class="circle" id="circle3"></div>
	  <div class="circle" id="circle4"></div>
	  <div class="circle" id="circle5"></div>  

	  	<!---Side Menu-->
	<div class="side-menu">
		<div class="logo"></div>
		<ul>
			<a href="#home"><li>HOME</li></a>	
			<a href="#about"><li>ABOUT US</li></a>
			<a href="#services"><li>SERVICES</li></a>
			<a href=""><li>COURSES</li></a>
		</ul>
		<button>CONTACT US</button>
	</div>

	</header>
	<!-------For the About part-------->
	<div class="about" id="about">
		<div class="text_about">
			<h1>About Us</h1>
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In perspiciatis dignissimos itaque voluptatum vero voluptatibus, aspernatur cumque nostrum, consequatur deleniti enim quibusdam! Nostrum ex, magni voluptatem doloremque modi id unde! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse, iusto quae illo vel nisi dolorem nulla consectetur aut quod, vero dolore inventore nihil quidem rem repellat! Alias iste dolorem ab.</p>
			<button>Contact Us</button>
		</div>
		<div class="img_about">
		   <!---------I decide to give a bg img-->
		</div>
	</div>
	<div class="about_two_boxes">
		<div class="boxes_about">
			<h1 class="first_box">1000+</h1>
			<p>Students taughts</p>
		</div>
		<div class="boxes_about">
			<h1 class="sec_box">20+</h1>
			<p>Schools</p>
		</div>
		<div class="boxes_about">
			<h1 class="third_box">5+</h1>
			<p>Years Experience</p>
		</div>
		<div class="boxes_about">
			<h1 class="f_box">3+</h1>
			<p>Softwares</p>
		</div>
	</div>

	<!-----For the service----->
	<div class="services" id="services">
		<div class="service_head">
			<h1>SERVICES</h1>
			<p>Our Services are of different types</p>
		</div>
		<div class="cards_res">
			<div class="card">
				<img src="images/code.jpg" alt="for web dev courses">
				<h1>Web Development Courses</h1>
				<p>$19.99</p>
				<a href="contact.php">
				  <button>ENROL NOW</button>
				</a>
			  </div>
			  <div class="card">
				<img src="images/code.jpg" alt="for web dev courses">
				<h1>Building of Softwares</h1>
				<p>$19.99</p>
				<a href="contact.php">
				<button>CONTACT US</button>
			    </a>
			  </div>
			  <div class="card">
				<img src="images/code.jpg" alt="for web dev courses">
				<h1>Understanding Programming</h1>
				<p>$19.99</p>
				<a href="contact.php">
				<button>ENROL NOW</button>
				</a>
			  </div>
		</div>
	</div>

	<!----footer part---->
	<footer>
		<div class="footer">
		<div class="row">
		<a href="#"><i class="fa fa-facebook"></i></a>
		<a href="#"><i class="fa fa-instagram"></i></a>
		<a href="#"><i class="fa fa-youtube"></i></a>
		<a href="#"><i class="fa fa-twitter"></i></a>
		</div>
		
		<div class="row">
		<ul>
		<li><a href="contact.php">Contact us</a></li>
		<li><a href="#services">Our Services</a></li>
		<li><a href="#">Privacy Policy</a></li>
		<li><a href="#">Terms & Conditions</a></li>
		</ul>
		</div>
		
		<div class="row">
			SchoolCode Africa Copyright © 2024 SoftPen Tech - All rights reserved || Developed By: AfricTech 
		</div>
		</div>
		</footer>
	
	 <script src="script.js"></script>
</body>
</html>