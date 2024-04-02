<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Rental System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header img {
            max-width: 100px;
            margin-right: 20px;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        .container-wrapper {
            display: flex;
            justify-content: space-around;
            overflow-x: hidden; /* Hide overflow */
            margin-bottom: 30px;
            padding: 20px;
            position: relative; /* Added position relative */
        }
        .container {
            width: 80%; /* Adjust width */
            background-color: #fff; /* Updated container color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out; /* Added opacity transition */
            position: relative; /* Position relative */
            height: auto; /* Adjusted height */
            min-height: 300px; /* Minimum height */
        }
        .container.active {
            opacity: 1; /* Show active container */
            transform: translateX(0); /* Move to left */
        }
        .container.hidden {
            opacity: 0; /* Hide inactive container */
            transform: translateX(100%); /* Move to right */
        }
        .container:hover {
            transform: scale(1.05);
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropbtn:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px; /* Decreased padding */
            text-align: center;
            position: fixed; /* Fixed position */
            width: 100%; /* Full width */
            bottom: 0; /* Stick to the bottom */
            height: auto; /* Adjusted height */
        }
        .contact-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 5px; /* Adjusted margin */
        }
        .contact-info i {
            margin-right: 5px; /* Adjusted margin */
        }
    </style>
</head>
<body>
    <header>
        <img src="qr.jpeg" alt="QR Code Logo">
        <h1 style="font-size: 24px;">Welcome to Pazuri QR Code Rental Payment System</h1>
        <nav>
            <ul>
                <!-- Add navigation links if needed -->
            </ul>
        </nav>
        <div class="dropdown">
            <button class="dropbtn">Login <i class="fas fa-caret-down"></i></button>
            <div class="dropdown-content">
                <a href="user_login.php">Tenant Login</a>
                <a href="login.php">Admin Login</a>
            </div>
        </div>
        <button onclick="location.href='user_registration.php';">Create Account</button>
    </header>
    
    <main>
        <div class="container-wrapper">
            <div class="container active" id="about">
                <h2>About QR Code Rental System</h2>
                <p>  No more waiting in lines or dealing with paperwork. With just a simple scan, you can securely submit your rental payments anytime, anywhere.</p>
                <div class="qr-code">
                    
                    <img src="qr.jpeg" alt="">
                </div>
            </div>
            
            <div class="container hidden" id="advantages">
    <h2>why Pazuri</h2>
    <ul>
        <li> Enjoy the convenience of paying your rent anytime, anywhere using your smartphone with our secure QR code payment system.</li>
        <li>Rent from Pazuri and experience well-maintained properties with excellent amenities.</li>
        <li>Say goodbye to delays in updating your payment status with our instant payment processing system.</li>
        <li> Rest assured that your payment information is protected with our encrypted and secure QR codes.</li>
        <li> Our team provides responsive and friendly support for all your rental needs.</li>
    </ul>
</div>

            
            <div class="container hidden" id="how-it-works">
                <h2>How QR Code Payments Work</h2>
                <ol>
                    
                    <li>Open your mobile payment app and scan the QR code.</li>
                    <li>Confirm the payment details and submit your payment.</li>
                    <li>Receive instant confirmation of your payment.</li>
                </ol>
            </div>
        </div>
        
        <!-- Other content goes here -->
    </main>
    
    <footer>
        <div class="contact-info">
            <i class="fas fa-map-marker-alt"></i>
            <p>125 Mayor road, Rongai, Keenya</p>
        </div>
        <div class="contact-info">
            <i class="fas fa-phone"></i>
            <p>+254000000000</p>
        </div>
        <div class="contact-info">
            <i class="far fa-envelope"></i>
            <p>info@pazuri.com</p>
        </div>
        <p><a href="#">pazuri.com</a></p>
    </footer>

    <script>
        // JavaScript to handle motion-switching effect
        const containers = document.querySelectorAll('.container');
        let currentContainerIndex = 0;

        function showNextContainer() {
            const previousContainerIndex = currentContainerIndex;
            currentContainerIndex = (currentContainerIndex + 1) % containers.length;

            containers.forEach(container => {
                container.classList.remove('active');
                container.classList.add('hidden');
            });

            containers[currentContainerIndex].classList.remove('hidden');
            containers[currentContainerIndex].classList.add('active');
        }

        setInterval(showNextContainer, 4000); // Switch containers every 11 seconds (increase motion speed)
    </script>
</body>
</html>
