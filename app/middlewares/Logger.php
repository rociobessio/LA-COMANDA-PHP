<?php


class Logger{
    public function logOperacion($request,$response,$next){
        return $next($request,$response);
    }
}