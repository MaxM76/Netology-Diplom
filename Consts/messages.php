<?php

// REQUIREMENTS

    const LOGIN_REQUIREMENTS = 'Логин пользователя должен состоять более чем из 3 символов';
    const PASSWORD_REQUIREMENTS = 'Пароль пользователя должен состоять более чем из 3 символов';

// ERRORS

    const QUESTION_ID_DB_ERR_MSG = 'Нет информации об идентификаторе вопроса. Ошибка базы данных: ';
    const QUESTION_ID_ERR_MSG = 'Ошибка при получении идентификатора вопроса';
    const TOPIC_ID_DB_ERR_MSG = 'Нет информации об идентификаторе категории вопросов. Ошибка базы данных: ';
    const TOPIC_ID_ERR_MSG = 'Ошибка при получении идентификатора категории вопросов';
    const QUESTION_ID_OF_ANSWER_ERR_MSG =
        'Ошибка при получении идентификатора вопроса, к которому запрашиваются ответы';
    const GETTING_VALUE_ERR_MSG = 'Ошибка в получении введенных данных. Поле - ';
    const ITEM_DELETE_DB_ERR_MSG = 'Не удалось удалить запись. Ошибка базы данных: ';
    const ITEM_ADD_DB_ERR_MSG = 'Не удалось добавить запись. Ошибка базы данных: ';
    const ITEM_UPDATE_DB_ERR_MSG = 'Не удалось обновить запись. Ошибка базы данных: ';
    const TOPIC_ID_OF_QUESTION_ERR_MSG =
        'Ошибка при получении идентификатора категории вопросов, к которой запрашивается список вопросов';
    const USER_LOGIN_ERR_MSG =
        'Ошибка ввода логина пользователя. Поле ввода было пустым или введенные данные не удовлетворяют требованиям. ';
    const USER_EXIST_ERR_MSG = 'Пользователь с введенным логином уже существует в системе';
    const USER_EMAIL_ERR_MSG =
        'Ошибка ввода электронной почты. Поле ввода было пустым или введенные данные не удовлетворяют требованиям.';
    const USER_PASSWORD_ERR_MSG =
        'Ошибка ввода пароля пользователя. Поле ввода было пустым или введенные данные не удовлетворяют требованиям. ';
    const USER_PASSWORDS_NOT_EQUAL_ERR_MSG = 'Введенные пароли не идентичны';
    const WRONG_PASSWORD_ERR_MSG = 'Введенный пароль не соответствует паролю зарегистрированного пользователя';
    const LACK_DATA_FOR_LOGIN_ERR_MSG = 'Введенных данных недостаточно для авторизации';
    const USER_REGISTER_DB_ERR_MSG = 'Пользователь не зарегистрирован. Ошибка базы данных: ';

// MESSAGES

    const ANSWERS_LIST_FAILURE_MSG = 'Невозможно получить список ответов';
    const ITEM_DEFAULT_VALUE_MSG = 'Используется значение по умолчанию для переменной ';
    const ITEM_DELETE_SUCCESS_MSG = 'Запись удалена';
    const ITEM_DELETE_FAILURE_MSG = 'Запись не удалена';
    const ITEM_ADD_SUCCESS_MSG = 'Запись добавлена';
    const ITEM_ADD_FAILURE_MSG = 'Запись не добавлена';
    const ITEM_UPDATE_SUCCESS_MSG = 'Запись обновлена';
    const ITEM_UPDATE_FAILURE_MSG = 'Запись не обновлена';
    const QUESTIONS_LIST_FAILURE_MSG = 'Список вопросов не получен';
    const USER_DEFAULT_TYPE_MSG = 'Используется тип пользователя по умолчанию (гость)';
    const USER_REGISTER_SUCCESS_MSG = 'Пользователь зарегистрирован';
    const USER_REGISTER_FAILURE_MSG = 'Не удалось зарегистрировать пользователя';
    const USER_LOGIN_SUCCESS_MSG = 'Авторизация пользователя успешна';
    const USER_LOGIN_FAILURE_MSG = 'Авторизация не произведена';
