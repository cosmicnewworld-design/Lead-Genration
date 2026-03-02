<h1>Welcome Manager!</h1>
<p>This is the management dashboard.</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
