<?php

    const UNKNOWN_ITEM_ID = -1;

// user type constants

    const ADMIN_STR = 'Администратор';
    const USER_STR = 'Пользователь';
    const QUEST_STR = 'Гость';

    const ADMIN_CODE = 0;
    const USER_CODE = 1;
    const QUEST_CODE = 2;

    const USER_TYPES = [ADMIN_STR => ADMIN_CODE, USER_STR => USER_CODE, QUEST_STR => QUEST_CODE];
    const INV_USER_TYPES = [ADMIN_CODE => ADMIN_STR, USER_CODE => USER_STR, QUEST_CODE => QUEST_STR];

// filters
    const ALL_QUESTIONS = 'all';
    const UNANSWERED_QUESTIONS = 'unanswered';
    const PUBLISHED_QUESTIONS = 'published';
    const UNPUBLISHED_QUESTIONS = 'unpublished';
    const FILTERS = [
        ALL_QUESTIONS => 'Все',
        UNANSWERED_QUESTIONS => 'Неотвеченные',
        PUBLISHED_QUESTIONS => 'Опубликованные',
        UNPUBLISHED_QUESTIONS => 'Неопубликованные'
    ];

// PDO::errorInfo() array indexes

    const PDO_ERROR_INFO_SQLSTATE_INDEX  = 0;
    const PDO_ERROR_INFO_CODE_INDEX = 1;
    const PDO_ERROR_INFO_MSG_INDEX = 2;

    const PDO_ERROR_INFO_NO_ERROR_CODE = 0;

// login, password and mail patterns
    const LOGIN_REGEXP = '/.{3,}/is';
    const PASSWORD_REGEXP = '/.{3,}/is';
    const EMAIL_REGEXP ='/.+@.+\..+/is';

// question status
    const QUESTION_PUBLISHED = 1;
    const QUESTION_NOT_PUBLISHED = 0;

    const QUESTION_ANSWERED = 1;
    const QUESTION_NOT_ANSWERED = 0;

// methods
    const USERS_METHODS = [
        'welcome' => ['welcome', 'welcome', 'welcome'],
        'gotologin' => ['renderResultPage', 'welcome', 'gotoLogin'],// quest
        'login' => ['login', 'login', 'login'],// quest
        'logout' => ['logout', 'logout', 'logout'],// admin, user
        'gotoregister' => ['renderResultPage', 'welcome', 'gotoRegister'],// guest
        'register' => ['renderResultPage', 'welcome', 'register'],// guest
        'list' => ['renderResultPage', 'welcome', 'welcome'], // admin
        'item' => ['getItem', 'welcome', 'welcome'],// admin
        'delete' => ['deleteItem', 'welcome', 'welcome'],// admin
        'add' => ['addItem', 'welcome', 'welcome'],// admin
        'update' => ['updateItem', 'welcome', 'welcome'],// admin
        'default' => ['welcome', 'welcome', 'welcome'],// all
        'error' => ['welcome', 'welcome', 'welcome'],// all
    ];

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

// menu buttons
    const MENU_BUTTONS = [
        'gotoStartPage' => ['caption' => 'Стартовая страница', 'href' => '/'],
        'login' => ['caption' => 'Авторизироваться', 'href' => 'index.php?c=users&a=gotologin'],
        'logout' => ['caption' => 'Выйти', 'href' => 'index.php?c=users&a=logout'],
        'register' => ['caption' => 'Зарегистрироваться', 'href' => 'index.php?c=users&a=gotoregister'],
        'manageUsers' => ['caption' => 'Управление пользователями', 'href' => 'index.php?c=users&a=list'],
        'manageContent' => ['caption' => 'Управление контентом', 'href' => 'index.php?c=topics&a=list'],
        'explore' => ['caption' => 'Просмотр контента', 'href' => 'index.php?c=topics&a=list']
    ];
