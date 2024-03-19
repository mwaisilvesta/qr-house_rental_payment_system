<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Request Form</title>
    <style>
        /* Basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Maintenance Request Form</h2>
        <form id="maintenanceForm" method="post" action="process_request.php">
            <div class="form-group">
                <label for="requestText">Maintenance Request:</label>
                <textarea id="requestText" name="requestText" rows="5" required></textarea>
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

    <script>
        document.getElementById("maintenanceForm").addEventListener("submit", function(event) {
            // Prevent form submission
            event.preventDefault();
            
            // Get form data
            var requestData = {
                requestText: document.getElementById("requestText").value,
                requestDate: document.getElementById("requestDate").value
            };

            // Simulate form submission (replace with actual AJAX request or form submission)
            fetch('process_request.php', {
                method: 'POST',
                body: JSON.stringify(requestData),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Display success message
                document.getElementById("successMessage").style.display = "block";
                // Reset form
                document.getElementById("maintenanceForm").reset();
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
