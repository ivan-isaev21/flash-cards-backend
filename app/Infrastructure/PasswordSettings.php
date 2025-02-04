<?php

namespace App\Infrastructure;

use Illuminate\Validation\Rules\Password;

class PasswordSettings
{
    public static function make():Password
    {
        return Password::min(8)                  // Минимальная длина
            ->letters()             // Должны быть буквы
            //->mixedCase()          // Должны быть буквы в разном регистре
            ->numbers();            // Должны присутствовать цифры
           // ->symbols()            // Должны присутствовать символы
            //->uncompromised();     // Проверка на утечки (haveibeenpwned);
    }
}
