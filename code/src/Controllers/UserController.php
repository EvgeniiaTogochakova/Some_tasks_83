<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

class UserController
{
    public function actionSave(): string
    {
        $userName = $_GET['name']??null;
        $date = $_GET['birthday']??null;
        $render = new Render();
        if (isset($userName) && isset($date)) {
            if (User::saveUserInStorage($userName, $date)) {
                return $render->renderPage('add-user-success.twig', ['userName' => $userName]);
            } else {
                return $render->renderPage('add-user-failure.twig', ['userName' => $userName]);
            }
        }
        return $render->renderPage('add-user-failure.twig');
    }


    public function actionIndex(): string
    {
        $users = User::getAllUsersFromStorage();

        $render = new Render();

        if (!$users) {
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]);
        } else {
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]);
        }
    }
}