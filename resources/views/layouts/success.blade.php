{{-- Success flash. aria-live=polite so it is announced without interrupting. --}}
<div class="rv-flash" role="status" aria-live="polite" aria-atomic="true">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="rv-alert-title">Sukces</span>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij komunikat"></button>
        </div>
    @endif
</div>
