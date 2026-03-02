<h1>Welcome Admin!</h1>
<p>This is the admin dashboard.</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
