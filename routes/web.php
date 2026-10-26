<?php

use Illuminate\Support\Facades\Route;

// Open the game when someone visits the site root.
Route::redirect('/', '/game/');
