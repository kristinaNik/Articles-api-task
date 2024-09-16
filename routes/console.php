<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('articles:fetch-and-store')->everyFiveMinutes();