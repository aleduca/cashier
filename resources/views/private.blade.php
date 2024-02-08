<h2>Olá {{ auth()->user()->name }}</h2>

@if (auth()->user()->subscribed('premium'))
<p>You are subscribed.</p>
@endif
