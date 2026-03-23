<div class="login-container"> 
    <form method="POST" action="/itdbadm-mp/coffee-backend/auth/login.php" id="login-form">
        <div class="login-content">
            <div class="email">
                <input type="text" name="username" placeholder="Username">
            </div>

            <div class="login">
                <input type="password" name="password" placeholder="Password">
            </div>

            <div id="login-error" style="display:none; color:red;"></div>
            
            <div class="login-footer">
                <input type="submit" value="Log In" class="login-btn">
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch('/itdbadm-mp/coffee-backend/auth/login.php', { method: 'POST', body: formData });
    const data = await res.json();
    // const text = await res.text(); // change json() to text() temporarily
    // console.log(text); // see raw response
    if (data.success) {
        if (data.role === 'ADMIN')       window.location.href = '/itdbadm-mp/coffee-backend/management/management.php';
        else if (data.role === 'STAFF') window.location.href = '/itdbadm-mp/coffee-backend/staff/staff.php';
        else                             window.location.href = '/itdbadm-mp/coffee-backend/index.php';
    } else {
        document.getElementById('login-error').style.display = 'block';
        document.getElementById('login-error').textContent   = data.error;
    }
});
</script>