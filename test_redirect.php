<!DOCTYPE html>
<html>
<head>
    <title>Test Redirect</title>
</head>
<body>
    <h1>Test Redirect Page</h1>
    <p>Click the link below to test if redirection to register.php works:</p>
    
    <p><a href="register.php">Regular Link to register.php</a></p>
    
    <p><a href="javascript:void(0);" onclick="window.location.href='register.php';">JavaScript Link to register.php</a></p>
    
    <p>
        <button onclick="window.location.href='register.php';">Button Link to register.php</button>
    </p>
    
    <script>
        // Add a console log to check if JavaScript is running
        console.log("Test redirect page loaded");
    </script>
</body>
</html> 