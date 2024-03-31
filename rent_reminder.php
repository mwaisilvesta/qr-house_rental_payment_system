<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            margin: 0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 2px solid #3498db;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #message {
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
        }

        #customFrequencyInput {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rent Reminder</h1>
        <form id="reminderForm">
            <label for="dueDate">Due Date:</label>
            <input type="date" id="dueDate" name="dueDate" required>
            <label for="frequency">Frequency:</label>
            <select id="frequency" name="frequency" required>
                <option value="monthly">Monthly</option>
                <option value="weekly">Weekly</option>
                <option value="custom">Custom</option>
            </select>
            <div id="customFrequencyInput">
                <label for="customFrequency">Custom Frequency:</label>
                <input type="text" id="customFrequency" name="customFrequency">
            </div>
            <label for="notificationMessage">Notification Message:</label>
            <textarea id="notificationMessage" name="notificationMessage" rows="4" cols="30" required></textarea>
            <label for="selectedTenants">Select Tenants:</label>
            <select id="selectedTenants" name="selectedTenants[]" required multiple>
                <!-- Tenants will be fetched dynamically using JavaScript -->
            </select>
            <button type="submit">Set Reminder</button>
        </form>
        <div id="message"></div>
    </div>

    <script>
        document.getElementById('frequency').addEventListener('change', function() {
            var customFrequencyInput = document.getElementById('customFrequencyInput');
            if (this.value === 'custom') {
                customFrequencyInput.style.display = 'block';
            } else {
                customFrequencyInput.style.display = 'none';
            }
        });

        document.getElementById('reminderForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'insert_reminder.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('message').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('message').innerHTML = "<span style='color: red;'>Error: Failed to save reminder.</span>";
                }
            };
            xhr.send(formData);
        });

        window.onload = function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_tenants.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var tenantsDropdown = document.getElementById('selectedTenants');
                    var tenants = JSON.parse(xhr.responseText);
                    if (tenants.length === 0) {
                        tenantsDropdown.innerHTML = "<option value='' disabled>No tenants found</option>";
                        tenantsDropdown.disabled = true;
                    } else {
                        tenants.forEach(function(tenant) {
                            var option = document.createElement('option');
                            option.value = tenant.id;
                            option.textContent = tenant.name;
                            tenantsDropdown.appendChild(option);
                        });
                    }
                }
            };
            xhr.send();
        };
    </script>
</body>
</html>
