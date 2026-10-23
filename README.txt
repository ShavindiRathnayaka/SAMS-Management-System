SAMS — login / forgot password / welcome dashboard connection

Included: existing home page (index.php), login design (login.php), forgot-password page and reset flow, the uploaded SAMS HTML dashboard as dashboard.php, and logout.php. The dashboard's existing HTML/CSS/design and demo modules are preserved; the only dashboard connection changes are login-session protection, logged-in user's name/role, and logout. Other dashboard content remains as in the original HTML prototype.

LOCAL SETUP
1. Back up your current C:\xampp\htdocs\SAMS folder.
2. Copy these files into C:\xampp\htdocs\SAMS.
3. Keep your existing SAMS database and confirm db.php has your correct local DB settings.
4. Start Apache and MySQL in XAMPP.
5. Open http://localhost:8080/SAMS/index.php (use your current Apache port if different).
6. Click Login. Successful login opens dashboard.php. The logout icon at the top right logs out and returns you to login.

Forgot password: local reset link is shown on the forgot-password page for development/testing; no email is sent. The reset flow creates the password_resets table automatically.

SECURITY: Do not upload db.php or real database credentials to a public GitHub repository.
