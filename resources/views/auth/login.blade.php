@include('layouts.html')

@include('layouts.head', ['pageTitle' => 'RentalVOD - logowanie'])

<head>
    <link rel="stylesheet" href="{{ asset('css/styleLogin.css') }}">
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5 mb-5 marginbig">

        <div class="row mt-4 mb-4 text-center">
            <div class="col-12">
                <img src="{{ asset('storage/img/logo.webp') }}" alt="Logo" class="img-fluid" style="max-width: 150px; margin-bottom: 20px; border-radius: 50">
                <h1>Zaloguj się</h1>
            </div>
        </div>

        @include('layouts.success')

        @include('layouts.errors')

        <div class="row d-flex justify-content-center">
            <div class="col-10 col-sm-10 col-md-6 col-lg-4">
                <form method="POST" action="{{ route('login.authenticate') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Hasło</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
                    </div>

                    <div class="text-center mt-4 mb-4">
                        <button class="btn custom-btn" type="submit">Zaloguj się</button>
                    </div>
                    <p>Nie masz konta? <a href="{{ route('register') }}">Zarejestruj się</a></p>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
    <script src="{{ asset('js/login.js') }}"></script>
    </script>

</body>

</html>
