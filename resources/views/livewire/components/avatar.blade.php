<img class="rounded-circle shadow-sm me-1"
     src="{{ URL::route("user.image") }}?ts={{ Auth::user()->updated_at->timestamp }}"
     alt="{{ Auth::user()->name }}"
     height="40">
