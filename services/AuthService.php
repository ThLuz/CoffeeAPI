<?php

class AuthService {

    public static function generateToken(){
        return bin2hex(random_bytes(32));
    }

}