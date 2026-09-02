{{--
    Per-field validation message.

    Usage:
      <input id="email" name="email"
             @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
      @include('layouts.field-error', ['field' => 'email'])

    The message is text (never colour alone) and carries the id that the
    input references through aria-describedby.
--}}
@error($field)
    <p class="rv-field-error" id="{{ $field }}-error">
        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
        <span class="visually-hidden">Błąd:</span>
        {{ $message }}
    </p>
@enderror
