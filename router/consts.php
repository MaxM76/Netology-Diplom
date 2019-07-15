<?php

    const USER_TYPES = ['Администратор' => 0, 'Пользователь' => 1, 'Гость' => 2];
    const INV_USER_TYPES = [0 => 'Администратор', 1 => 'Пользователь', 2 => 'Гость'];


    const USERS_METHODS = [
        'welcome' => 'welcome', // all
        'gotologin' => 'gotoLogin', // quest
        'login' => 'login', // quest
        'logout' => 'logout', // admin, user
        'gotoregister' => 'gotoRegister', // guest
        'register' => 'register', // guest
        'list' => 'renderResultPage', // admin
        'item' => 'getItem', // admin
        'delete' => 'deleteItem', // admin
        'add' => 'addItem', // admin
        'update' => 'updateItem', // admin
        'default' => 'welcome', // all
    ];    
    
    /*
    const TOPICS_METHODS = [
        'list' => 'renderResultPage', // all
        'item' => 'getItem', // all
        'delete' => 'deleteItem', // admin
        'add' => 'addItem', // admin
        'update' => 'updateItem', // admin
        'default' => 'renderResultPage', // all
    ];
*/

    const TOPICS_METHODS = [
        'list' => ['renderResultPage', 'renderResultPage', 'renderResultPage'], // all
        'item' => ['getItem', 'renderResultPage', 'renderResultPage'], // all
        'delete' => ['deleteItem', 'renderResultPage', 'renderResultPage'], // admin
        'add' => ['addItem', 'renderResultPage', 'renderResultPage'], // admin
        'update' => ['updateItem', 'renderResultPage', 'renderResultPage'], // admin
        'default' => ['renderResultPage', 'renderResultPage', 'renderResultPage'], // all
    ];
    
    const QUESTIONS_METHODS = [
        'list' => ['renderResultPage', 'renderResultPage', 'renderResultPage'], // all
        'item' => ['getItem', 'getItem', 'getItem'], // all
        'delete' => ['deleteItem', 'renderResultPage', 'renderResultPage'], // admin
        'add' => ['addItem', 'addItem', 'addItem'],// all (guest - with email provided)
        'update' => ['updateItem', 'updateItem', 'updateItem'],// admin or author (?)
        'default' => ['renderResultPage', 'renderResultPage', 'renderResultPage'],// all
    ];

    const ANSWERS_METHODS = [
        'list' => ['renderResultPage', 'renderResultPage', 'renderResultPage'],// all
        'item' => ['getItem', 'getItem', 'getItem'],// all
        'delete' => ['deleteItem', 'renderResultPage', 'renderResultPage'], // admin
        'add' => ['addItem', 'renderResultPage', 'renderResultPage'], // admin
        'update' => ['updateItem', 'renderResultPage', 'renderResultPage'], // admin
        'default' => ['renderResultPage', 'renderResultPage', 'renderResultPage'], // all
    ];

    const MENU_BUTTONS = [
        'gotoStartPage' => ['caption' => 'Стартовая страница', 'href' => '/'],
        'login' => ['caption' => 'Авторизироваться', 'href' => '?c=users&a=gotologin'],
        'logout' => ['caption' => 'Выйти', 'href' => '?c=users&a=logout'],
        'register' => ['caption' => 'Зарегистрироваться', 'href' => '?c=users&a=gotoregister'],
        'manageUsers' => ['caption' => 'Управление пользователями', 'href' => '?c=users&a=list'],
        'manageContent' => ['caption' => 'Управление контентом', 'href' => '?c=topics&a=list'],
        'explore' => ['caption' => 'Просмотр контента', 'href' => '?c=topics&a=list']
    ];
    

