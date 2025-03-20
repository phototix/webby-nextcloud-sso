<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebbyPage Member Connector Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://private-gallery.brandon.my/_data/i/upload/2024/12/05/20241205151727-f14c8b6b-me.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }
        h2 {
            color:#FFF;
        }
        p {
            color:#FFF;
        }
        .card {
            width: 60%;
            margin: 0 auto;
            background-color: #121212;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.8);
        }
        .btn-primary {
            background-color: #0078FF;
            border-color: #0078FF;
        }
        .btn-primary:hover {
            background-color: #005BB5;
        }
        .security-warning {
            background-color: #3d3d29;
            color: #ffd700;
            padding: 10px;
            border-radius: 5px;
        }
        .logo {
            max-width: 350px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <!-- Logo -->
            <img src="https://private-gallery.brandon.my/upload/2025/03/20/20250320164036-cc90d07f.png" 
                 alt="WebbyPage Logo" 
                 class="logo">
            
            <!-- Card Container -->
            <div class="card text-center">
                <h2>Connect to your account</h2>
                <p>Please log in before granting <strong>WebbyCMS</strong> access to your WebbyCloud account.</p>
                
                <!-- Security Warning -->
                <div class="security-warning mb-4">
                    <strong>Security warning</strong><br>
                    If you are not trying to set up a new device or app, someone is trying to trick you into granting them access to your data. In this case, do not proceed and instead contact your system administrator.
                </div>
                
                <!-- Login Button -->
                <a href="logout.php" class="btn btn-primary btn-lg">
                    Log in <span class="ms-2">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
