<?php
namespace Hedonist\Actions\UserTaste;

use Illuminate\Database\Eloquent\Collection;

class GetUserTastesResponse
{
    private $tastes;
    
    public function __construct(Collection $tastes)
    {
        $this->tastes = $tastes;
    }
    
    public function getTastes(): array
    {
        return $this->tastes->toArray();
    }
}