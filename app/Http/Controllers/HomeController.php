<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use App\Services\RecommendationService;

class HomeController extends Controller
{
    protected $movieService;
    protected $recommendationService;

    public function __construct(MovieService $movieService, RecommendationService $recommendationService)
    {
        $this->movieService = $movieService;
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $this->movieService->updatePrices();
    
        $movies = Movie::where('available', 'dostępny')
                       ->inRandomOrder()
                       ->limit(6)
                       ->with('category')
                       ->get();
    
        foreach ($movies as $movie) {
            $movie->promo_price = $this->movieService->calculatePromoPrice($movie->price_day);
        }
    
        $topMovies = Movie::select('movies.id', 'movies.title', 'movies.img_path', 'movies.available', 'movies.description', 'movies.category_id', 'movies.director', 'movies.release_year', 'movies.duration', 'movies.rate', 'movies.video_path', 'movies.price_day', \DB::raw('count(loan_movie.id) as loans_count'))
            ->join('loan_movie', 'loan_movie.movie_id', '=', 'movies.id')
            ->groupBy('movies.id', 'movies.title', 'movies.img_path', 'movies.available', 'movies.description', 'movies.category_id', 'movies.director', 'movies.release_year', 'movies.duration', 'movies.rate', 'movies.video_path', 'movies.price_day')
            ->orderByDesc('loans_count')
            ->limit(10)
            ->get();
    
        foreach ($topMovies as $movie) {
            $movie->promo_price = $this->movieService->calculatePromoPrice($movie->price_day);
        }

        $topRecommendedMovies = $this->recommendationService->getTopRecommendedMovies();
        $promotionsEnabled = $this->movieService->arePromotionsEnabled();
    
        return view('home', compact('movies', 'topMovies', 'topRecommendedMovies', 'promotionsEnabled'));
    }

    public function regulamin()
    {
        return view('regulamin'); 
    }

    /**
     * XML sitemap listing the publicly indexable pages and every movie.
     */
    public function sitemap()
    {
        $movies = Movie::select('id', 'updated_at')->get();

        return response()
            ->view('sitemap.index', compact('movies'))
            ->header('Content-Type', 'application/xml');
    }
}
