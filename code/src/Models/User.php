<?php

namespace Geekbrains\Application1\Models;

class User
{

    private ?string $userName;

    private ?string $userBirthday;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(string $name = null, string $birthday = null)
    {
        $this->userName = $name;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): string|null
    {
        return $this->userName;
    }

    public function getUserBirthday(): string|null
    {
        return $this->userBirthday;
    }

    public static function getAllUsersFromStorage(): array|false
    {

        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;

        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "r");

            $users = [];

            while (!feof($file)) {
                $userString = fgets($file);
                $userArray = explode(", ", $userString);

                $user = new User(
                    $userArray[0],
                    $userArray[1]
                );

                $users[] = $user;
            }

            fclose($file);

            return $users;
        } else {
            return false;
        }
    }

    public static function saveUserInStorage(string $userName, string $date): bool
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        if (User::validateName($userName) && User::validateDate($date)) {
            $newUser = $userName . ", " . $date . PHP_EOL;

            $fileHandler = fopen($address, 'a');

            if (fwrite($fileHandler, $newUser)) {
                fclose($fileHandler);
                return true;
            }
            fclose($fileHandler);
            return false;
        }
        return false;
    }

    public static function validateName(string $name): bool
    {
        $name = trim($name);
        if (mb_strlen($name) < 2 || (mb_strlen($name)) > 50) {
            return false;
        }
        return preg_match('/^[a-zа-я\s]+$/iu', $name);
    }

    public static function validateDate(string $date): bool
    {
        $dateBlocks = explode("-", $date);

        if (count($dateBlocks) < 3) {
            return false;
        }

        foreach ($dateBlocks as $num) {
            if (!is_numeric($num) || str_contains($num, '.')) {
                return false;
            }
        }

        if ($dateBlocks[0] < 1 || $dateBlocks[0] > 31) {
            return false;
        }
        if ($dateBlocks[1] < 1 || $dateBlocks[1] > 12) {
            return false;
        }
        if ($dateBlocks[2] < 1900 || $dateBlocks[2] > date('Y')) {
            return false;
        }

        return true;
    }
}



