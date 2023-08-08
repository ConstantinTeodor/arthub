<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artisticGenres = [
            'Abstract',
            'Realism',
            'Impressionism',
            'Expressionism',
            'Surrealism',
            'Minimalism',
            'Pop Art',
            'Cubism',
            'Fauvism',
            'Romanticism',
            'Baroque',
            'Renaissance',
            'Gothic',
            'Contemporary',
            'Classical',
            'Neo-Classical',
            'Symbolism',
            'Dadaism',
            'Constructivism',
            'Art Nouveau',
            'Art Deco',
            'Abstract Expressionism',
            'Post-Impressionism',
            'Landscape',
            'Portrait',
            'Still Life',
            'Cityscape',
            'Figurative',
            'Fantasy',
            'Sci-Fi',
            'Historical',
            'Mythological',
            'Religious',
            'Nature',
            'Wildlife',
            'Floral',
            'Seascape',
            'Street',
            'Architecture',
            'Photorealism',
            'Illustration',
            'Conceptual',
            'Kinetic',
            'Installation',
            'Mixed Media',
            'Digital',
            'Photography',
            'Performance',
            'Textile',
            'Sculpture',
            'Ceramics',
            'Glass',
            'Metalwork',
            'Woodwork',
            'Jewelry',
            'Fashion',
            'Film',
            'Animation',
            'Video Art',
            'Sound Art',
            'Land Art',
            'Light Art',
        ];

        foreach ($artisticGenres as $artworkGenre) {
            $genre = new Genre();
            $genre->name = $artworkGenre;
            $genre->save();
        }
    }
}
