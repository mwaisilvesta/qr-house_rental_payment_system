<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Request Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        /* Basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Fit the background image within the container without stretching */
            background-repeat: no-repeat; /* Prevent the background image from repeating */
            background-position: center; /* Center the background image */
        }
        .container {
            display: flex;
            justify-content: space-between;
            max-width: 800px; /* Increased max-width for better readability of FAQs */
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9); /* Adjusted background opacity for better readability */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            flex: 1;
            padding-right: 20px;
            max-width: 600px; /* Adjusted max-width to maintain readability */
        }
        .faq-container {
            flex: 1;
            padding-left: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            resize: vertical;
            height: 300px; /* Increased height to triple the original size */
        }
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            margin-top: 5px;
            text-align: center;
        }
        .faq {
            margin-bottom: 20px;
        }
        .faq h3 {
            cursor: pointer;
        }
        .faq p {
            display: none;
        }
        .faq.open p {
            display: block;
        }
        .faq i {
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Maintenance Request Form</h2>
            <form id="maintenanceForm" method="post" action="process_request.php">
                <div class="form-group">
                    <label for="requestText">Maintenance Request:</label>
                    <textarea id="requestText" name="requestText" rows="15" required></textarea> <!-- Increased rows to triple the original size -->
                </div>
                <div class="form-group">
                    <label for="requestDate">Date:</label>
                    <input type="date" id="requestDate" name="requestDate" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit Request">
                </div>
            </form>
            <p id="successMessage" class="success-message" style="display: none;">Maintenance request submitted successfully!</p>
        </div>
        <!-- FAQs -->
        <div class="faq-container">
            <div class="faq">
                <h3><i class="fas fa-question"></i> What is a maintenance request?</h3>
                <p>A maintenance request is a formal submission made by a tenant to report a problem or request repairs or maintenance in their rental property.</p>
            </div>
            <div class="faq">
                <h3><i class="fas fa-question"></i> How long does it take to process a maintenance request?</h3>
                <p>The time taken to process a maintenance request varies depending on the nature and urgency of the issue. Typically, property managers aim to address non-emergency requests within a reasonable timeframe, while emergency issues are dealt with immediately.</p>
            </div>
            <div class="faq">
                <h3><i class="fas fa-question"></i> Can I track the status of my maintenance request?</h3>
                <p>Yes, many property management systems allow tenants to track the status of their maintenance requests online. You may receive notifications or updates on the progress of your request.</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script> <!-- Font Awesome JS -->
    <script>
        // FAQ toggle functionality
        document.querySelectorAll('.faq h3').forEach(item => {
            item.addEventListener('click', event => {
                const faq = event.target.parentElement;
                faq.classList.toggle('open');
            });
        });

        // Form submission handling (for demonstration purposes)
        document.getElementById("maintenanceForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission (for demonstration purposes)
            var requestData = {
                requestText: document.getElementById("requestText").value,
                requestDate: document.getElementById("requestDate").value
            };
            fetch('process_request.php', {
                method: 'POST',
                body: JSON.stringify(requestData),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("successMessage").style.display = "block";
                document.getElementById("maintenanceForm").reset();
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
