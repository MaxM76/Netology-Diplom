<?php

    //namespace lh\router;

    const ADMIN_STR = 'Администратор';
    const USER_STR = 'Пользователь';
    const QUEST_STR = 'Гость';

    const ADMIN_CODE = 0;
    const USER_CODE = 1;
    const QUEST_CODE = 2;

    const USER_TYPES = [ADMIN_STR => ADMIN_CODE, USER_STR => USER_CODE, QUEST_STR => QUEST_CODE];
    const INV_USER_TYPES = [ADMIN_CODE => ADMIN_STR, USER_CODE => USER_STR, QUEST_CODE => QUEST_STR];

// question status;
    const QUESTION_PUBLISHED = true; // 1
    const QUESTION_NOT_PUBLISHED = false; // 0
//question answered;
    const QUESTION_ANSWERED = true; // 1
    const QUESTION_NOT_ANSWERED = false; // 0

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
        'error' => 'welcome', // all
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

    const MENU_BUTTONS = [
        'gotoStartPage' => ['caption' => 'Стартовая страница', 'href' => '/'],
        'login' => ['caption' => 'Авторизироваться', 'href' => '?c=users&a=gotologin'],
        'logout' => ['caption' => 'Выйти', 'href' => '?c=users&a=logout'],
        'register' => ['caption' => 'Зарегистрироваться', 'href' => '?c=users&a=gotoregister'],
        'manageUsers' => ['caption' => 'Управление пользователями', 'href' => '?c=users&a=list'],
        'manageContent' => ['caption' => 'Управление контентом', 'href' => '?c=topics&a=list'],
        'explore' => ['caption' => 'Просмотр контента', 'href' => '?c=topics&a=list']
    ];

/*    const TOPIC_LIST_BUTTONS = [
        'addTopic' => ['caption' => 'Добавить категорию', 'href' => '?c=topics&a=item&id=-1'],
        'deleteTopic' => ['caption' => 'Удалить', 'href' => '?c=topics&a=delete&id='],
        'updateTopic' => ['caption' => 'Изменить', 'href' => '?c=topics&a=item&id='],
        'showQuestions' => ['caption' => 'Показать вопросы', 'href' => '?c=questions&a=list&topic_id='],
        'hideQuestions' => ['caption' => 'Скрыть вопросы', 'href' => '?c=topics&a=list']
    ];
*/