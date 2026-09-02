{{--
    Validation error summary.

    Each message links to the field it belongs to, so a keyboard or screen
    reader user can jump straight to the input that needs fixing. The
    heading states the number of problems in words, never colour alone.
--}}
@if ($errors->any())
    <div class="rv-flash" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="alert alert-danger" tabindex="-1" id="rv-error-summary">
            <span class="rv-alert-title">
                @if ($errors->count() === 1)
                    Popraw 1 błąd w formularzu:
                @else
                    Popraw {{ $errors->count() }} błędy w formularzu:
                @endif
            </span>
            <ul>
                @foreach ($errors->keys() as $rvField)
                    @foreach ($errors->get($rvField) as $rvMessage)
                        <li><a href="#{{ $rvField }}">{{ $rvMessage }}</a></li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
@endif
