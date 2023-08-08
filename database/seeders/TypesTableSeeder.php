<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artworkTypes = [
            'Oil Paintings',
            'Acrylic Paintings',
            'Watercolor Paintings',
            'Abstract Art',
            'Portraits',
            'Landscape Art',
            'Still Life',
            'Impressionism',
            'Surrealism',
            'Cubism',
            'Realism',
            'Expressionism',
            'Minimalism',
            'Pop Art',
            'Mixed Media',
            'Digital Art',
            'Sculpture',
            'Ceramic Art',
            'Pottery',
            'Glass Art',
            'Collage',
            'Engraving',
            'Etching',
            'Lithography',
            'Printmaking',
            'Mosaic Art',
            'Calligraphy',
            'Installation Art',
            'Kinetic Art',
            'Street Art',
            'Graffiti Art',
            'Textile Art',
            'Fiber Art',
            'Woodwork',
            'Metalwork',
            'Jewelry Art',
            'Performance Art',
            'Conceptual Art',
            'Land Art',
            'Light Art',
            'Sound Art',
            'Digital Photography',
            'Film and Video Art',
            'Contemporary Art',
            'Classical Art',
            'Neo-Classical Art',
            'Baroque Art',
            'Renaissance Art',
            'Gothic Art',
            'Pre-Raphaelite Art',
            'Romanticism',
            'Symbolism',
            'Fauvism',
            'Dadaism',
            'Constructivism',
            'Bauhaus',
            'Art Nouveau',
            'Art Deco',
            'Abstract Expressionism',
            'Post-Impressionism',
        ];

        foreach ($artworkTypes as $artworkType) {
            $type = new Type();
            $type->name = $artworkType;
            $type->save();
        }
    }
}
