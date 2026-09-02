{{-- Session error flash (same contract as layouts.error, kept for the
     views that already include this partial name). --}}
<div class="rv-flash" role="alert" aria-live="assertive" aria-atomic="true">
    @if (session('error'))
        <div class="alert alert-danger">
            <span class="rv-alert-title">Błąd</span>
            {{ session('error') }}
        </div>
    @endif
</div>
