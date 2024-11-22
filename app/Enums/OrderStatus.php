<?php

namespace App\Enums;

/**
 * Possible order statuses. 
 * Each value corresponds to a possible value in the `status` column of the `orders` table.
 */
enum OrderStatus: string
{
    case Pending = 'pending';
    case SelectingIngredients = 'selecting_ingredients';
    case Shaking = 'shaking';
    case AddingIceCubes = 'adding_ice_cubes';
    case Ready = 'ready';
}
