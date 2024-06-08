<?php

use Random\RandomException;

class GenerateRandom
{
    public function loginToken()
    {
        $digits = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
        $length = 128;

        $digitsLength = strlen($digits);
        $random = '';

        for ($i = 0; $i < $length; $i++) {
            try {
                $random .= $digits[random_int(0, $digitsLength - 1)];
            } catch (RandomException $e) {
                xtSBack('function-loginToken-RandomException',$e);
            }
        }
        return $random;
    }
}