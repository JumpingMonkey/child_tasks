<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public static $mainLanguage = 'en';

    public static $languages = ['en', 'ru', 'uk'];

    /*
     * Проверяет наличие корректной метки языка в текущем URL
     * Возвращает метку или значеие null, если нет метки
     */
    public static function getLocaleByRequest()
    {

            if (request()->lang != self::$mainLanguage){
                return request()->lang;
            }

        return null;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (request()->filled('lang') && in_array(request()->lang, self::$languages)){
            
            $locale = self::getLocaleByRequest();

            if ($locale){
                App::setLocale($locale);
            } else { //если метки нет - устанавливаем основной язык $mainLanguage
                App::setLocale(self::$mainLanguage);
            }

        } else {
            //Todo remove lazyload here
            $locale = Auth::user()?->accountSettings?->language;
            if (\Auth::check() && $locale) {
                \App::setLocale($locale);
            }
        }

        return $next($request); //пропускаем дальше - передаем в следующий 
    }
}
