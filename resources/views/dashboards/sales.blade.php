<h1>Welcome Sales Rep!</h1>
<p>This is the sales dashboard.</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
