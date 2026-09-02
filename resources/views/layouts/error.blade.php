{{-- Error flash. aria-live=assertive because the user must act on it. --}}
<div class="rv-flash" role="alert" aria-live="assertive" aria-atomic="true">
    @if (session('error'))
        <div class="alert alert-danger">
            <span class="rv-alert-title">Błąd</span>
            {{ session('error') }}
        </div>
    @endif
</div>
