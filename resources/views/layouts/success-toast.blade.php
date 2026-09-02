@if (Session::has('successToast'))
    <div id="themeToast" class="toast text-bg-success position-fixed top-0 end-0 m-3" role="status" aria-live="polite" aria-atomic="true" style="z-index: 1100;">
        <div class="d-flex">
            <div class="toast-body">{{ Session::get('successToast') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Zamknij powiadomienie"></button>
        </div>
    </div>
@endif
