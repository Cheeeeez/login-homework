<?php
function checkEmail($str)
{
    $regex = '/^[A-Za-z0-9]+[A-Za-z0-9.]*@[A-Za-z0-9]+(\.[A-Za-z0-9]+)$/';
    if (preg_match($regex, $str)) {
        return true;
    } else {
        return false;
    }
}

function checkPhoneNumber($str, $network)
{
    $regex = '/^[0-9]{10}$/';
    if (preg_match($regex, $str) && checkVietNamPhoneNumber($str, $network)) {
        return true;
    } else {
        return false;
    }
}

function checkVietNamPhoneNumber($string, $network)
{
    for ($i = 0; $i < strlen($string); $i++) {
        $firstThreeNumber = substr($string, FIRST_NUMBER_INDEX, THIRD_NUMBER);
        for ($j = 0; $j < count($network); $j++) {
            if ($firstThreeNumber == $network[$j]) {
                return true;
            }
        }
    }
    return false;
}

function checkPassword($str)
{
    $regex = '/^\S*(?=\S{8,})(?=\S*[A-Z])(?=\S*[0-9])(?=\S*[@!%\^\-\$])\S*$/';

    if (preg_match($regex, $str)) {
        return true;
    } else {
        return false;
    }

}

function isUsedEmail($email, $memberList)
{
    for ($i = 0; $i < count($memberList); $i++) {
        if ($email == $memberList[$i]->email) {
            return true;
        }
    }
    return false;
}