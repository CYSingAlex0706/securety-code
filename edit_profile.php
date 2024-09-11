<?php
require_once 'session_control.php';
check_session_timeout();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        .error {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
<div>
            <a href="profile.php">Profile</a>
</div>

<h2>Please enter a valid Hong Kong identity card number - e.g A123456(7)</h2>

<form id="editForm" action="edit.php" method="post">
    <label for="hkid">HKID:</label>
    <input type="text" id="hkid" name="hkid" required>
    <span id="idError" class="error">In valid format, please enter a valid Hong Kong identity card number - e.g A123456(7)</span><br><br>
    <input type="submit" value="Submit">
</form>

<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    function validateForm() {
        const hkid = document.getElementById('hkid').value.toUpperCase();

        if (!/^[A-Z]{1}[0-9]{6}\([0-9]\)$/.test(hkid)) {
            document.getElementById('idError').style.display = 'inline';
            return false;
        } else {
            document.getElementById('idError').style.display = 'none';
            return true;
        }
    }

    // For debugging
    console.log("Form script loaded");
</script>
</body>
</html>