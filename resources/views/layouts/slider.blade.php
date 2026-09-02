{{--
    Hero carousel.

    The stylesheets and scripts this block needs are loaded here rather than
    in a nested <head> element (which was invalid inside <body> and pulled in
    a css/strip.css file that does not exist in the project).
--}}
<link rel="stylesheet" href="{{ asset('css/slide.css') }}">
<link rel="stylesheet" href="{{ asset('css/topfive.css') }}">
<script defer src="{{ asset('js/slide.js') }}"></script>
<script defer src="{{ asset('js/topfive.js') }}"></script>

<section aria-labelledby="hero-heading" class="rv-section" style="margin-top: var(--rv-space-4);">
    <h2 id="hero-heading" class="visually-hidden">Polecane produkcje</h2>

    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" aria-roledescription="karuzela" aria-label="Polecane produkcje">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="10000" role="group" aria-roledescription="slajd" aria-label="1 z 4: Legends">
                <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/F6CDB6C0EB2D77EB19BCADA31F85066E001A1F61FA68F4AC3356A73FE076477F/scale?width=1440&aspectRatio=1.78&format=jpeg" class="d-block w-100 rounded" alt="Kadr promocyjny z serii Legends">
                <div class="title-overlay" style="background-image: url('https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/DDFF0FDF457E092EE53149CE7DB5BD14CB97E27B92D2D087E7C687B4E3073DE2/scale?width=1440&aspectRatio=1.78');"></div>
            </div>
            <div class="carousel-item" data-bs-interval="10000" role="group" aria-roledescription="slajd" aria-label="2 z 4: Burrow">
                <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/2A509165105A09F9F533E2008B143BCF38D6A5859D0EBB40CCA388772005CD94/scale?width=1440&aspectRatio=1.78&format=jpeg" class="d-block w-100 rounded" alt="Kadr promocyjny z filmu Burrow">
                <div class="title-overlay" style="background-image: url('https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/DD8BBA864E290FBC03A244A488FFC8DC8365FBF2F95A122B1D57BF3772D717FD/scale?width=1440&aspectRatio=1.78');"></div>
            </div>
            <div class="carousel-item" data-bs-interval="10000" role="group" aria-roledescription="slajd" aria-label="3 z 4: Animacje">
                <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/09DAD6A5DAECB6BA9E329FFA896B18978FE4AD5540C070D7973EE53357DD4D24/scale?width=1440&aspectRatio=1.78&format=jpeg" class="d-block w-100 rounded" alt="Kadr promocyjny z kolekcji animacji">
                <div class="title-overlay" style="background-image: url('https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/A31BE6502DC7A3A01DAF58DF7E91AB6FF598A078C8FA88A1DB2D7B7E25439464/scale?width=1440&aspectRatio=1.78');"></div>
            </div>
            <div class="carousel-item" data-bs-interval="10000" role="group" aria-roledescription="slajd" aria-label="4 z 4: The Simpsons">
                <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/223DAE104BE1175F374C4AACAC0EB5B8B0DB9C49839AA2E10085533DDFE07A8E/scale?width=1440&aspectRatio=1.78&format=jpeg" class="d-block w-100 rounded" alt="Kadr promocyjny z serialu The Simpsons">
                <div class="title-overlay" style="background-image: url('https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/47A6FB38D95B3A5EF5583C9EED0B698ED2992CBA4AC7222DD3269DC92DFA03A6/scale?width=1440&aspectRatio=1.78');"></div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Poprzedni slajd</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Następny slajd</span>
        </button>
    </div>
</section>
