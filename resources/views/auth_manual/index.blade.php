<form method="POST" action="{{ route('login') }}">
  @csrf
  <input type="email" name="email" placeholder="Email">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Login</button>
</form>
@if(session()->has('failed'))
  <div>{{ session('failed') }}</div>
@endif
