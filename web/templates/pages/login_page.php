<h2>Login</h2>
<form action="/login" method="POST">
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
    <button type="submit">Login</button>
</form>
<p>Not yet Registered? <a href="/register">Register</a></p>
