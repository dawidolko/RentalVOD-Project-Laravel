<footer class="{{ isset($fixedBottom) && $fixedBottom ? 'fixed-bottom' : '' }}">
    <div class="rv-page" style="padding-block: 0;">
        <div class="row align-items-center g-3">
            <div class="col-md-4 text-center text-md-start">
                <p>&copy; {{ __('name_page') }} &ndash; <time datetime="2024">2024</time></p>
            </div>

            <div class="col-md-4">
                <nav aria-label="Media społecznościowe">
                    <ul class="rv-footer-social list-unstyled mb-0">
                        <li>
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-facebook" aria-hidden="true"></i>
                                <span class="visually-hidden">Facebook (otwiera się w nowej karcie)</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-twitter" aria-hidden="true"></i>
                                <span class="visually-hidden">Twitter (otwiera się w nowej karcie)</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-instagram" aria-hidden="true"></i>
                                <span class="visually-hidden">Instagram (otwiera się w nowej karcie)</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-linkedin" aria-hidden="true"></i>
                                <span class="visually-hidden">LinkedIn (otwiera się w nowej karcie)</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-4 text-center text-md-end">
                <a href="mailto:rentalVOD@contact.com" class="text-decoration-none">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    rentalVOD@contact.com
                </a>
                <span class="d-block mt-2">
                    <a href="{{ route('regulamin') }}" class="text-decoration-none">Regulamin</a>
                </span>
            </div>
        </div>
    </div>
</footer>

<script>
    window.addEventListener('load', function () {
        // Bootstrap toasts are opt-in; show any that the server rendered.
        if (typeof bootstrap === 'undefined') {
            return;
        }
        document.querySelectorAll('.toast').forEach(function (toastEl) {
            new bootstrap.Toast(toastEl).show();
        });
    });
</script>
