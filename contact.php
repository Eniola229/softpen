<?php
 session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SchoolCode Africa</title>
	<link rel="stylesheet" type="text/css" href="contact.css">
</head>
<body>
	<div class="contact_body">
    <div class="data_secured">
        <img src="images/logo.jpeg">
        <p>Tell us how we may be of service to you...</p>
        <p style="color:red;">
             <?php 
                        if(isset($_SESSION['status']))
                        {
                            echo "<h4>".$_SESSION['status']."</h4>";
                            unset($_SESSION['status']);
                        }
                        ?>
        </p>
    </div>
    <div class="contact_form">
        <form method="POST" action="includes/contact_code.php">
            <div class="form_group">
                <label for="name">Name<span>*</span></label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form_group">
                <label for="email">E-Mail<span>*</span></label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form_group">
                <label for="phone">Phone Number<span>*</span></label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form_group">
                <label for="want_to">Want To?<span>*</span></label>
                <select id="want_to" name="want_to" required>
                    <option value="Learn Programming">Learn Programming</option>
                    <option value="Build Software/Website">Build Software/Website</option>
                </select>
            </div>
            <div class="form_group">
                <label for="message">Message<span>*</span></label>
                <textarea id="message" placeholder="Message" name="message" required></textarea>
            </div>
            <button type="submit" name="submit_btn">Submit</button>
        </form>
        <div class="go_back">
            <a href="index.php">Go Back</a>
        </div>
    </div>
</div>

</body>
</html>