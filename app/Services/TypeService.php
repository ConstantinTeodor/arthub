<?php

namespace App\Services;

use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class TypeService
{
    public function __construct() {}

    /**
     * @return Collection
     */
    public function getTypes(): Collection
    {
        return Type::all();
    }
}
