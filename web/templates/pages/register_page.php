<h2>Register</h2>
<form action="/register" method="POST">
    <label>
        Username:
        <br />
        <input required type="text" name="username" placeholder="johndoe123" />
    </label>
    <label>
        Password:
        <br />
        <input required type="password" name="password" placeholder="Password" />
    </label>
    <label>
        Confirm Password:
        <br />
        <input required type="password" name="confirm_password" placeholder="Password" />
    </label>
    <button type="submit">Register</button>
</form>
<p>Already Registered? <a href="/login">Login</a></p>
