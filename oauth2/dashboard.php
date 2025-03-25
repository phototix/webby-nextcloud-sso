<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebbyPage Member Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://private-gallery.brandon.my/_data/i/upload/2024/12/05/20241205151727-f14c8b6b-me.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }
        h2, h4 {
            color: #FFF;
        }
        .card {
            background-color: #121212;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.8);
            color: #FFF;
        }
        .btn-primary {
            background-color: #0078FF;
            border-color: #0078FF;
        }
        .btn-primary:hover {
            background-color: #005BB5;
        }
        .dashboard-header {
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center dashboard-header">
            <!-- Logo -->
            <img src="https://private-gallery.brandon.my/upload/2025/03/20/20250320164036-cc90d07f.png" 
                 alt="WebbyPage Logo" 
                 class="logo">
            <h2>Welcome to Your Dashboard</h2>
            <p>Manage your account and explore features below.</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Profile</h4>
                    <p>Update your personal information and preferences.</p>
                    <button class="btn btn-primary" onclick="navigateTo('profile')">Go to Profile</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Messages</h4>
                    <p>View your recent messages and notifications.</p>
                    <button class="btn btn-primary" onclick="navigateTo('messages')">Check Messages</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Settings</h4>
                    <p>Manage account settings and security options.</p>
                    <button class="btn btn-primary" onclick="navigateTo('settings')">Open Settings</button>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Analytics</h4>
                    <p>View your usage statistics and reports.</p>
                    <button class="btn btn-primary" onclick="navigateTo('analytics')">View Analytics</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Support</h4>
                    <p>Contact support for assistance and help.</p>
                    <button class="btn btn-primary" onclick="navigateTo('support')">Get Support</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h4>Logout</h4>
                    <p>Securely log out of your account.</p>
                    <button class="btn btn-primary" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function navigateTo(page) {
            window.location.href = `/?page=grant&api=<?=$api?>&cate=${page}`;
        }

        function logout() {
            window.location.href = '/logout.php';
        }
    </script>
</body>
</html>