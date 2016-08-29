<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


$mod_strings = array (
  'ERR_IMPORT_SYSTEM_ADMININSTRATOR' => 'Вы не можете импортировать системного администратора',
  'ERR_MISSING_MAP_NAME' => 'Отсутствует название кастомизированного сопоставления',
  'ERR_MISSING_REQUIRED_FIELDS' => 'Missing required fields:',
  'ERR_MULTIPLE' => 'Несколько колонок имеют одно и то же название поля.',
  'ERR_MULTIPLE_PARENTS' => 'Только один родительский ID может быть определен',
  'ERR_SELECT_FILE' => 'Выбрать файл для загрузки.',
  'ERR_SELECT_FULL_NAME' => 'Вы не можете выбрать полное имя, когда выбраны имя и фамилия.',
  'LBL_' => '',
  'LBL_ACCOUNTS_NOTE_1' => 'Поля, заканчивающиеся на Улица 2 и Улица 3, объединены вместе с полем Основная улица при переносе в базу данных.',
  'LBL_ACT' => 'Act!',
  'LBL_ACT_NUM_1' => 'Запустить <b>ACT!</b>',
  'LBL_ACT_NUM_10' => 'Выберите <b>Все записи</b> и затем нажмите кнопку <b>Готово</b>',
  'LBL_ACT_NUM_2' => 'Выберите меню <b>Файл</b>, опцию меню <b>Обмен данными</b>, затем опцию меню <b>Экспорт...</b>',
  'LBL_ACT_NUM_3' => 'Выберите тип файла <b>Text-Delimited</b>',
  'LBL_ACT_NUM_4' => 'Выберите название файла и место сохранения для экспортированных данных и нажмите <b>Далее</b>',
  'LBL_ACT_NUM_5' => 'Выберите <b>Только записи Контактов</b>',
  'LBL_ACT_NUM_6' => 'Нажмите на кнопку <b>Опции...</b>',
  'LBL_ACT_NUM_7' => 'Выберите <b>Запятую</b> как символ разделения полей',
  'LBL_ACT_NUM_8' => 'Проверьте, отмечен ли флажок <b>Да, экспортировать названия полей</b> и нажмите <b>OK</b>',
  'LBL_ACT_NUM_9' => 'Нажмите <b>Далее</b>',
  'LBL_ADD_FIELD_HELP' => 'Используйте эту опцию, чтобы добавить значение в поле всех созданных или обновленных записей. Выберите поле и затем введите или выберите значение для этого поля в колонке Значение по умолчанию.',
  'LBL_ADD_ROW' => 'Добавить поле',
  'LBL_ARE_YOU_SURE' => 'Вы уверены? Это удалит все данные в данном модуле.',
  'LBL_ASSIGNED_USER' => 'Если пользователь не существует - используйте текущего пользователя',
  'LBL_AUTO_DETECT_ERROR' => 'Не удалось обнаружить разделитель полей и классификатор в файле для импорта. Пожалуйста, проверьте настройки в свойствах файла для импорта.',
  'LBL_BACK' => '< Назад',
  'LBL_CANCEL' => 'Отмена',
  'LBL_CANNOT_OPEN' => 'Невозможно отрыть для чтения импортированный файл',
  'LBL_CHARSET' => 'Кодирование файла:',
  'LBL_CONFIRM_EXT_TITLE' => 'Шаг {0}: Подтвердить настройки внешнего источника',
  'LBL_CONFIRM_IMPORT' => 'Вы выбрали обновление записей в процессе импорта. Обновления, внесенные в существующих записях не могут быть отменены. Тем не менее, записи, созданные в процессе импорта могут быть отменены (удалены), если это необходимо. Нажмите кнопку Отмена для создания только новых записей, или нажмите кнопку OK, чтобы продолжить.',
  'LBL_CONFIRM_MAP_OVERRIDE' => 'Предупреждение: Вы уже выбрали кастомизированное сопоставление для данного импорта, хотите продолжить?',
  'LBL_CONFIRM_TITLE' => 'Шаг {0}: Подтвердить настройки файла для импорта',
  'LBL_CONTACTS_NOTE_1' => 'Или фамилия, или полное имя должны быть отображены.',
  'LBL_CONTACTS_NOTE_2' => 'Если полное имя отображено, то имя и фамилия игнорируются.',
  'LBL_CONTACTS_NOTE_3' => 'Если отображено полное имя, то данные в полном имени будут разделены на имя и фамилию при внесении в базу данных.',
  'LBL_CONTACTS_NOTE_4' => 'Поля, заканчивающиеся на Улица 2 и Улица 3, объединены вместе с полем Основная улица при переносе в базу данных.',
  'LBL_CREATED_TAB' => 'Созданные записи',
  'LBL_CREATE_BUTTON_HELP' => 'Используйте эту опцию для создания новых записей. Внимание: Строки в файле импорта, которые содержат значения, совпадающие с ID существующих записей, не будет импортированы, если значения соответствуют полю ID.',
  'LBL_CSV' => 'файл на моем компьютере',
  'LBL_CURRENCY' => 'Валюта:',
  'LBL_CURRENCY_SIG_DIGITS' => 'Значимые знаки валюты',
  'LBL_CUSTOM' => 'Кастомизированный',
  'LBL_CUSTOM_CSV' => 'Кастомизированный файл с разделителями-запятыми',
  'LBL_CUSTOM_DELIMITED' => 'Кастомизированный файл с разделителями',
  'LBL_CUSTOM_DELIMITER' => 'Поля, разделенные:',
  'LBL_CUSTOM_ENCLOSURE' => 'Разделитель:',
  'LBL_CUSTOM_NUM_1' => 'Запустить приложение и открыть файл данных',
  'LBL_CUSTOM_NUM_2' => 'Выберите опцию меню <b>Сохранить как...</b> или <b>Экспорт...</b>',
  'LBL_CUSTOM_NUM_3' => 'Сохранить файл в формате <b>CSV</b>',
  'LBL_CUSTOM_TAB' => 'Кастомизированный файл с разделителями табуляции',
  'LBL_DATABASE_FIELD' => 'Поле модуля',
  'LBL_DATABASE_FIELD_HELP' => 'Данная колонка отображает все поля в модуле. Выберите поле для отображения данных в строках файла для импорта.',
  'LBL_DATE_FORMAT' => 'Формат даты',
  'LBL_DEBUG_MODE' => 'Включить режим отладки',
  'LBL_DECIMAL_SEP' => 'Десятичный разделитель',
  'LBL_DEFAULT_VALUE' => 'Значение по умолчанию',
  'LBL_DEFAULT_VALUE_HELP' => 'Укажите значение для поля в созданной или обновленной записи, если поле в файле для импорта не содержит данных.',
  'LBL_DELETE' => 'Удалить',
  'LBL_DELETE_MAP_CONFIRMATION' => 'Вы действительно хотите удалить этот сохраненный набор параметров импорта?',
  'LBL_DELIMITER_COMMA_HELP' => 'Используйте данную опцию для выбора файла в формате электронной таблицы, содержащего данные, которые Вы хотели бы импортировать. Примеры: файл .csv  или файл для экспорта из Microsoft Outlook.',
  'LBL_DELIMITER_CUSTOM_HELP' => 'Выберите данную опцию, если символ, разделяющий поля в файле для импорта - ни запятая, ни TAB, и введите символ в смежное поле.',
  'LBL_DELIMITER_TAB_HELP' => 'Выберите данную опцию, если символ, разделяющий поля в файле для импорта - <b>TAB</b>, и расширение файла - .txt.',
  'LBL_DESELECT' => 'Отменить выбор',
  'LBL_DONT_MAP' => '-- Не отображать данное поле --',
  'LBL_DUPLICATES' => 'Найдены дубли',
  'LBL_DUPLICATE_TAB' => 'Дубли',
  'LBL_DUP_HELP' => 'Здесь представлены строки в файле для импорта, которые не были импортированы, поскольку они содержат данные, которые совпадают со значениями в существующих записях, выявленных на основе проверки записей на наличие дублей. Данные, которые совпадают, выделены. Для повторного импорта этих строк, загрузите список, внесите изменения и нажмите <b>Импортировать еще раз</b>.',
  'LBL_ENCLOSURE_HELP' => '<p><b>Определитель символа</b> используется для заключения предполагаемого содержимого поля, включая любые символы, которые используются как разделители.<br><br>Пример: Если разделителем является запятая (,) и определителем является знак кавычек ("),<br><b>"Купертино, Калифорния"</b> импортируется в одно поле в приложении и представляется как <b>Купертино, Калифорния</b>.<br>Если нет определяющих символов, или если другой символ является определителем,<br><b>"Купертино, Калифорния"</b> импортируется в два смежных поля, как <b>"Купертино</b> и <b>"Калифорния</b>.<br><br>Примечание: Файл для импорта не может содержать определяющих символов.<br>Определитель символа по умолчанию для файлов в формате с разделителями-запятыми и файлов, разделенных знаком табуляции, созданных в Excel - знак кавычек.</p>',
  'LBL_ERROR' => 'Ошибка:',
  'LBL_ERROR_DELETING_RECORD' => 'Ошибка при удалении записи:',
  'LBL_ERROR_HELP' => 'Здесь представлены строки в файле для импорта, которые не были импортированы вследствие ошибок. Для повторного импорта строк, загрузите список, внесите правки и нажмите <b>Импортировать еще раз</b>',
  'LBL_ERROR_IMPORTS_NOT_SET_UP' => 'Импорт не настроен для данного типа модуля',
  'LBL_ERROR_IMPORT_CACHE_NOT_WRITABLE' => 'Директория кэша импорта не доступна для записи.',
  'LBL_ERROR_INVALID_ACCOUNT' => 'Неверное название Контрагента или ID',
  'LBL_ERROR_INVALID_BOOL' => 'Неверное логическое значение',
  'LBL_ERROR_INVALID_CURRENCY' => 'Неверно указанная валюта',
  'LBL_ERROR_INVALID_DATE' => 'Неверная строка даты',
  'LBL_ERROR_INVALID_DATETIME' => 'Неверные время и дата',
  'LBL_ERROR_INVALID_DATETIMECOMBO' => 'Неверные время и дата',
  'LBL_ERROR_INVALID_EMAIL' => 'Неверный адрес электронной почты',
  'LBL_ERROR_INVALID_FLOAT' => 'Неверное значение с дробной частью',
  'LBL_ERROR_INVALID_ID' => 'Данный ID слишком длинный, чтобы поместиться в поле (максимальная длина - 36 символов)',
  'LBL_ERROR_INVALID_INT' => 'Неверное целое значение',
  'LBL_ERROR_INVALID_NAME' => 'Слишком длинная строка, чтобы поместиться в поле',
  'LBL_ERROR_INVALID_NUM' => 'Неверное числовое значение',
  'LBL_ERROR_INVALID_PHONE' => 'Неправильный номер телефона',
  'LBL_ERROR_INVALID_RELATE' => 'Неверное реляционное поле',
  'LBL_ERROR_INVALID_TEAM' => 'Неверное название команды или ID',
  'LBL_ERROR_INVALID_TIME' => 'Неверное время',
  'LBL_ERROR_INVALID_USER' => 'Неверное имя пользователяя или ID',
  'LBL_ERROR_INVALID_VARCHAR' => 'Слишком длинная строка, чтобы поместиться в поле',
  'LBL_ERROR_NOT_IN_ENUM' => 'Значение находится не в выпадающем списке. Допустимые значения:',
  'LBL_ERROR_SELECTING_RECORD' => 'Ошибка при выборе записи:',
  'LBL_ERROR_SYNC_USERS' => 'Недействительное значение для синхронизирования с Outlook:',
  'LBL_ERROR_TAB' => 'Ошибки',
  'LBL_ERROR_UNABLE_TO_PUBLISH' => 'Нельзя опубликовать схему. Уже существует другая схема импорта с аналогичным названием.',
  'LBL_ERROR_UNABLE_TO_UNPUBLISH' => 'Нельзя отменить публикацию схемы импорта, принадлежащую другому пользователю. Необходимо чтобы схема импорта с аналогичным названием принадлежала Вам.',
  'LBL_EXAMPLE_FILE' => 'Загрузить шаблон файла для импорта',
  'LBL_EXTERNAL_ASSIGNED_TOOLTIP' => 'Чтобы назначить пользователя, другое лицо, ответственным за новые записи, используйте колонку Значение по умолчанию для выбора другого пользователя.',
  'LBL_EXTERNAL_DEFAULT_TOOPLTIP' => 'Укажите значение для поля в созданной записи, если поле во внешнем источнике не содержит никаких данных.',
  'LBL_EXTERNAL_ERROR_FEED_CORRUPTED' => 'Внешний ресурс не доступен, попробуйте ещё раз позже.',
  'LBL_EXTERNAL_ERROR_NO_SOURCE' => 'Невозможно восстановить адаптер источника данных, пожалуйста, попробуйте позже.',
  'LBL_EXTERNAL_FIELD' => 'Внешнее поле',
  'LBL_EXTERNAL_FIELD_TOOLTIP' => 'Данная колонка отображает поля во внешнем источнике, содержащем данные для создания новых записей.',
  'LBL_EXTERNAL_MAP_HELP' => 'Таблица ниже содержит поля во внешнем источнике и поля модуля, которому они сопоставляются. Проверьте сопоставления, чтобы убедиться, что они являются тем, что Вы ожидаете, и, при необходимости, внесите изменения. Обязательно сопоставьте со всеми обязательными полями (отмеченными звездочкой).',
  'LBL_EXTERNAL_MAP_NOTE' => 'Импорт будет произведен для контактов всех групп контактов Google.',
  'LBL_EXTERNAL_MAP_NOTE_SUB' => 'Имена пользователей недавно созданных пользователей по умолчанию будут Полными именами контактов Google. Имена пользователей могут быть изменены после того, как будут созданы записи пользователей.',
  'LBL_EXTERNAL_MAP_SUB_HELP' => 'Нажмите <b>Импортировать сейчас</b> для начала импорта. Записи будут созданы только для данных, содержащих фамилии. Записи не будут созданы для данных, установленных как дубли на основе имен и/или адресов электронной почты, соответствующие существующим записям.',
  'LBL_EXTERNAL_SOURCE' => 'внешнее приложение или внешние услуги',
  'LBL_EXTERNAL_SOURCE_HELP' => 'Используйте эту опцию для импорта данных непосредственно из внешнего приложения или сервиса, например, Gmail.',
  'LBL_EXTERNAL_TEAM_TOOLTIP' => 'Чтобы назначить команды, которые не являются командой (-ами) по умолчанию, ответственными за новые записи, используйте колонку Значение по умолчанию для выбора других команд.',
  'LBL_EXT_SOURCE_SIGN_IN' => 'Войти',
  'LBL_FAIL' => 'Не удалось:',
  'LBL_FAILURE' => 'Импорт не удался:',
  'LBL_FIELD_DELIMETED_HELP' => 'Разделитель полей определяет символ, который используется для разделения колонок полей.',
  'LBL_FIELD_NAME' => 'Поле',
  'LBL_FILE_ALREADY_BEEN_OR' => 'Файл для импорта уже был обработан или не существует',
  'LBL_FILE_OPTIONS' => 'Опции файла',
  'LBL_FILE_UPLOAD_WIDGET_HELP' => 'Выберите файл, содержащий данные с разделителем, как например, разделённый запятой или табуляцией файл. Рекомендуются файлы типа .csv.',
  'LBL_FINISHED' => 'Готово',
  'LBL_GOOD_FILE' => 'Файл импорта успешно прочитан',
  'LBL_HAS_HEADER' => 'Строка заголовка:',
  'LBL_HEADER_ROW' => 'Строка заголовка',
  'LBL_HEADER_ROW_HELP' => 'Эта колонка отображает надписи в строке заголовка файла для импорта.',
  'LBL_HEADER_ROW_OPTION_HELP' => 'Выберите, если верхняя строка файла для импорта является заголовком строки, содержащим названия полей.',
  'LBL_HIDE_ADVANCED_OPTIONS' => 'Скрыть свойства файла для импорта',
  'LBL_HIDE_NOTES' => 'Скрыть заметки',
  'LBL_HIDE_PREVIEW_COLUMNS' => 'Спрятать колонки предварительного просмотра',
  'LBL_IDS_EXISTED_OR_LONGER' => 'Записи пропущены, поскольку ID либо уже существует, либо длиннее 36 символов',
  'LBL_ID_EXISTS_ALREADY' => 'Данный код уже присутствует в этой таблице',
  'LBL_IMPORT_ACT_TITLE' => 'Act! может экспортировать данные в формате <b>CSV</b>, который может быть использован при импорте данных в систему. При экспорте данных из Act!, выполните следующие действия:',
  'LBL_IMPORT_BUTTON' => 'Создать только новые записи',
  'LBL_IMPORT_COMPLETE' => 'Выход',
  'LBL_IMPORT_COMPLETED' => 'Импорт завершен',
  'LBL_IMPORT_CUSTOM_TITLE' => 'Многие приложения позволяют экспортировать данные в текстовом файле <b>(.csv)</b>, выполняя следующие шаги:',
  'LBL_IMPORT_ERROR' => 'Ошибка импорта',
  'LBL_IMPORT_ERROR_MAX_REC_LIMIT_REACHED' => 'Файл для импорта содержит {0} строк.Оптимальное количество строк - {1}. Большее количество строк может замедлить процесс импорта. Нажмите OK для продолжения импорта. Нажмите Отмена для изменения и повторной загрузки файла для импорта.',
  'LBL_IMPORT_FIELDDEF_ASSIGNED_USER_NAME' => 'Имя пользователя или ID',
  'LBL_IMPORT_FIELDDEF_BOOL' => '&#39;0&#39; или &#39;1&#39;',
  'LBL_IMPORT_FIELDDEF_CURRENCY' => 'Числовое (десятые разрешены)',
  'LBL_IMPORT_FIELDDEF_DATE' => 'Дата',
  'LBL_IMPORT_FIELDDEF_DATETIME' => 'Дата/время',
  'LBL_IMPORT_FIELDDEF_DOUBLE' => 'Числовое (без десятых)',
  'LBL_IMPORT_FIELDDEF_EMAIL' => 'Адрес электронной почты',
  'LBL_IMPORT_FIELDDEF_ENUM' => 'Список',
  'LBL_IMPORT_FIELDDEF_FLOAT' => 'Числовое (десятые разрешены)',
  'LBL_IMPORT_FIELDDEF_ID' => 'Индивидуальный ID номер',
  'LBL_IMPORT_FIELDDEF_INT' => 'Числовое (без десятых)',
  'LBL_IMPORT_FIELDDEF_NAME' => 'Любой текст',
  'LBL_IMPORT_FIELDDEF_NUM' => 'Числовое (без десятых)',
  'LBL_IMPORT_FIELDDEF_PHONE' => 'Номер телефона',
  'LBL_IMPORT_FIELDDEF_RELATE' => 'Название или ID',
  'LBL_IMPORT_FIELDDEF_TEAM_LIST' => 'Название Команды или ID',
  'LBL_IMPORT_FIELDDEF_TEXT' => 'Любой текст',
  'LBL_IMPORT_FIELDDEF_TIME' => 'Время',
  'LBL_IMPORT_FIELDDEF_VARCHAR' => 'Любой текст',
  'LBL_IMPORT_FILE_SETTINGS' => 'Настройки файла для импорта',
  'LBL_IMPORT_FILE_SETTINGS_HELP' => 'Во время загрузки файла для импорта, некоторые свойства файла могли быть автоматически определены. При необходимости, Вы можете просматривать и управлять этими свойствами. Примечание: Данные настройки относятся к данному импорту и не заменят все пользовательские настройки.',
  'LBL_IMPORT_MODULE_ERROR_LARGE_FILE' => 'Слишком большой файл. Макс:',
  'LBL_IMPORT_MODULE_ERROR_LARGE_FILE_END' => 'Байты. Измените $sugar_config[&#39;upload_maxsize&#39;] в config.php',
  'LBL_IMPORT_MODULE_ERROR_NO_MOVE' => 'Файл не был успешно загружен. Проверьте доступ к файлу в установочной кэш директории Sugar.',
  'LBL_IMPORT_MODULE_ERROR_NO_UPLOAD' => 'Файл не был загружен успешно. Возможно, что задано небольшое число в параметре &#39;upload_max_filesize&#39; в файле php.ini',
  'LBL_IMPORT_MODULE_MAP_ERROR' => 'Не удается опубликовать. Есть еще одно опубликованное сопоставление импорта с тем же названием.',
  'LBL_IMPORT_MODULE_MAP_ERROR2' => 'Не удалось снять с публикации сопоставление, принадлежащее другому пользователю. Ваше сопоставление импорта носит такое же название.',
  'LBL_IMPORT_MODULE_NO_DIRECTORY' => 'Директория',
  'LBL_IMPORT_MODULE_NO_DIRECTORY_END' => 'не существует или недоступен для записи',
  'LBL_IMPORT_MODULE_NO_TYPE' => 'Импорт не настроен для данного типа модуля',
  'LBL_IMPORT_MODULE_NO_USERS' => 'ПРЕДУПРЕЖДЕНИЕ: В системе не указаны пользователи. Если при импорте данных не указаны ответственные пользователи, все записи будут привязаны к Администратору.',
  'LBL_IMPORT_MORE' => 'Импортировать еще раз',
  'LBL_IMPORT_NOW' => 'Импортировать сейчас',
  'LBL_IMPORT_OUTLOOK_TITLE' => 'Microsoft Outlook 98 и 2000 может экспортировать данные в формате <b>CSV</b>, который может быть использован при импорте данных в систему. При экспорте данных из Outlook, выполните следующие действия:',
  'LBL_IMPORT_RECORDS' => 'Импорт записей',
  'LBL_IMPORT_RECORDS_OF' => 'из',
  'LBL_IMPORT_RECORDS_TO' => 'кому',
  'LBL_IMPORT_SF_TITLE' => 'Salesforce.com может экспортировать данные в формате <b>CSV</b>, который может быть использован при импорте данных в систему. При экспорте данных из Salesforce.com, выполните следующие действия:',
  'LBL_IMPORT_STARTED' => 'Импорт начат:',
  'LBL_IMPORT_TAB_TITLE' => 'Многие приложения позволяют экспортировать данные в текстовом файле <b>(.tsv или .tab)</b>, выполняя следующие шаги:',
  'LBL_IMPORT_TYPE' => 'Что Вы хотите сделать с импортированными данными?',
  'LBL_INDEX_NOT_USED' => 'Доступные поля:',
  'LBL_INDEX_USED' => 'Поля для проверки:',
  'LBL_LAST_IMPORTED' => 'Создано',
  'LBL_LAST_IMPORT_UNDONE' => 'Импорт был отменен.',
  'LBL_LOCALE_DEFAULT_NAME_FORMAT' => 'Формат отображения имени',
  'LBL_LOCALE_EXAMPLE_NAME_FORMAT' => 'Пример',
  'LBL_LOCALE_NAME_FORMAT_DESC' => '<i>"s" Обращение, "f" Имя, "l" Фамилия</i>',
  'LBL_MICROSOFT_OUTLOOK' => 'Microsoft Outlook',
  'LBL_MICROSOFT_OUTLOOK_HELP' => 'Кастомизированные сопоставления для Microsoft Outlook основываются на файле для импорта, который является файлом типа .csv. Если файл для импорта - разделенный табуляцией файл, - сопоставления не будут применены в соответствии с ожидаемым.',
  'LBL_MIME_TYPE_ERROR_1' => 'Выбранный файл, по-видимому, не содержит список, разделенный запятыми. Пожалуйста, проверьте тип файла. Мы рекомендуем файлы .csv.',
  'LBL_MIME_TYPE_ERROR_2' => 'Чтобы продолжить импорт выбранного файла, нажмите кнопку OK. Чтобы загрузить новый файл, нажмите, нажмите Попробуйте еще раз',
  'LBL_MISSING_HEADER_ROW' => 'Строка заголовка не найдена',
  'LBL_MODULE_NAME' => 'Импорт',
  'LBL_MODULE_NAME_SINGULAR' => 'Импорт',
  'LBL_MY_PUBLISHED_HELP' => 'Используйте эту опцию, чтобы применить предварительно установленные параметры импорта, в том числе свойства импорта, сопоставление, а также любые свойства проверки записей на наличие дубликатов для данного импорта.',
  'LBL_MY_SAVED' => 'Для использования сохраненных настроек, выберите ниже:',
  'LBL_MY_SAVED_ADMIN_HELP' => 'Используйте эту опцию, чтобы применить предварительно установленные параметры импорта, в том числе свойства импорта, сопоставления, а также любые свойства проверки записей на наличие дублей для данного импорта.<br><br>Нажмите<b>Опубликовать</b> для того, чтобы сделать сопоставление доступным для других пользователей.<br>Нажмите <b>Отменить публикацию</b> для того, чтобы сделать сопоставление недоступным для других пользователей.<br>Нажмите <b>Удалить</b> для удаления сопоставления для всех пользователей.',
  'LBL_MY_SAVED_HELP' => 'Используйте эту опцию, чтобы применить предварительно установленные параметры импорта, в том числе свойства импорта, сопоставление, а также любые свойства проверки записей на наличие дубликатов для данного импорта.<br><br>Нажмите <b>Удалить</b> для удаления сопоставления для всех пользователей.',
  'LBL_NEXT' => 'Далее >',
  'LBL_NOLOCALE_NEEDED' => 'Перекодировка не требуется',
  'LBL_NONE' => 'Нет',
  'LBL_NOTES' => 'Заметки:',
  'LBL_NOT_MULTIENUM' => 'Не является элементом списка множественных значений',
  'LBL_NOT_SAME_NUMBER' => 'Не такое же количество полей в строке было представлено в файле',
  'LBL_NOT_SET_UP' => 'Импорт не настроен для данного типа модуля',
  'LBL_NOT_SET_UP_FOR_IMPORTS' => 'Импорт не настроен для данного типа модуля',
  'LBL_NOW_CHOOSE' => 'Теперь выберите тот файл для импорта:',
  'LBL_NO_DATECHECK' => 'Пропустить проверку даты (это ускорит выполнение операции, но приведет к ошибке, если будет обнаружена хотя бы одна неверная дата)$#39;',
  'LBL_NO_EMAILS' => 'Не отправлять уведомления по электронной почте во время этого импорта',
  'LBL_NO_EMAIL_DEFS_IN_MODULE' => 'Попытка обработки электронных адресов в Bean, который не поддерживает это.',
  'LBL_NO_ID' => 'Требуется код',
  'LBL_NO_IMPORT_TO_UNDO' => 'Не было импорта для отмены.',
  'LBL_NO_LINES' => 'В файле для импорта не были обнаружены строки. Пожалуйста, убедитесь, что нет никаких пустых строк в файле и повторите попытку.',
  'LBL_NO_PRECHECK' => 'Режим исходного формата',
  'LBL_NO_RECORD' => 'Нет записи с таким ID для обновления',
  'LBL_NO_WORKFLOW' => 'Не запускайте рабочий процесс в течение этого импорта',
  'LBL_NUMBER_GROUPING_SEP' => 'Разделитель тысяч',
  'LBL_NUM_1' => '1.',
  'LBL_NUM_10' => '10.',
  'LBL_NUM_11' => '11.',
  'LBL_NUM_12' => '12.',
  'LBL_NUM_2' => '2.',
  'LBL_NUM_3' => '3.',
  'LBL_NUM_4' => '4.',
  'LBL_NUM_5' => '5.',
  'LBL_NUM_6' => '6.',
  'LBL_NUM_7' => '7.',
  'LBL_NUM_8' => '8.',
  'LBL_NUM_9' => '9.',
  'LBL_OK' => 'OK',
  'LBL_OPTION_ENCLOSURE_DOUBLEQUOTE' => 'Двойная кавычка (")',
  'LBL_OPTION_ENCLOSURE_NONE' => 'Нет',
  'LBL_OPTION_ENCLOSURE_OTHER' => 'Остальные:',
  'LBL_OPTION_ENCLOSURE_QUOTE' => 'Одинарная кавычка (&#39;)',
  'LBL_OUTLOOK_NUM_1' => 'Запустить <b>Outlook</b>',
  'LBL_OUTLOOK_NUM_2' => 'Выберите меню <b>Файл</b>, затем опцию меню <b>Импорт и Экспорт ...</b>',
  'LBL_OUTLOOK_NUM_3' => 'Выберите <b>Экспорт в файл</b> и нажмите кнопку Далее',
  'LBL_OUTLOOK_NUM_4' => 'Выберите <b>CSV (Windows)</b> и нажмите <b>Далее</b>.<br>    Примечание: Вам может быть предложено установить компонент для экспорта',
  'LBL_OUTLOOK_NUM_5' => 'Выберите папку <b>Контакты</b> и нажмите кнопку <b>Далее</b>. Вы можете выбрать различные папки контактов, если контакты хранятся в нескольких папках',
  'LBL_OUTLOOK_NUM_6' => 'Выберите название файла и нажмите кнопку  <b>Далее</b>',
  'LBL_OUTLOOK_NUM_7' => 'Нажмите <b>Готово</b>',
  'LBL_PRE_CHECK_SKIPPED' => 'Предварительная проверка пропущена',
  'LBL_PUBLISH' => 'Опубликовать',
  'LBL_PUBLISHED_SOURCES' => 'Для использования предварительно установленных параметров настройки импорта, выберите ниже:',
  'LBL_RECORDS_SKIPPED' => 'Записи пропущены, поскольку у них отсутствует одно или более необходимых полей',
  'LBL_RECORDS_SKIPPED_DUE_TO_ERROR' => 'Записи пропущены из-за возникшей ошибки',
  'LBL_RECORD_CANNOT_BE_UPDATED' => 'Запись не может быть обновлена из-за проблем доступа',
  'LBL_RELATED_ACCOUNTS' => 'Не создавать связанных контрагентов',
  'LBL_REMOVE_ROW' => 'Удалить поле',
  'LBL_REQUIRED_NOTE' => 'Обязательное поле(я):',
  'LBL_REQUIRED_VALUE' => 'Требуемое значение отсутствует',
  'LBL_RESULTS' => 'Результаты',
  'LBL_ROW' => 'Строка',
  'LBL_ROW_HELP' => 'Эта колонка отображает данные в первой строке без заголовка файла для импорта. Если надписи строки заголовка появляются в этой колонке, нажмите Назад, чтобы задать строку заголовка в свойствах файла для импорта.',
  'LBL_ROW_NUMBER' => 'Номер строки',
  'LBL_SALESFORCE' => 'Salesforce.com',
  'LBL_SAMPLE_URL_HELP' => 'Загрузите образец файла для импорта, который содержит строку заголовка полей модуля. Файл может использоваться как шаблон для создания файла для импорта, содержащего данные, которые Вы бы хотели импортировать.',
  'LBL_SAVE_AS_CUSTOM' => 'Сохранить как кастомизированное сопоставление:',
  'LBL_SAVE_AS_CUSTOM_NAME' => 'Название кастомизированного сопоставления:',
  'LBL_SAVE_MAPPING_AS' => 'Чтобы сохранить настройки импорта, укажите название для сохраненных настроек:',
  'LBL_SAVE_MAPPING_HELP' => 'Введите название для сохранения настроек импорта, включая сопоставление полей и показателей, используемых при проверке записей на наличие дубликатов. Сохраненные настройки импорта могут быть использованы для будущих импортов.',
  'LBL_SELECT_DS_INSTRUCTION' => 'Готовы начать импорт? Выберите источник данных, которые Вы хотели бы импортировать.',
  'LBL_SELECT_DUPLICATE_INSTRUCTION' => 'Чтобы избежать создания дублей записей, выберите, какое из сопоставленных полей Вы бы хотели использовать для проверки записей на наличие дублей в процессе импорта данных. Значения существующих записей в выбранных файлах будут проверены на соответствия с данными файла для импорта. Если найдены совпадающие данные, будут отображены строки в файле для импорта, содержащие эти данные наряду с результатами импорта (следующая страница). Затем Вы сможете выбрать, какие из этих строк подлежат дальнейшему импорту.',
  'LBL_SELECT_FIELDS_TO_MAP' => 'Из представленного ниже списка, выберите поля в файле для импорта, которые должны быть импортированы в каждое поле системы. По завершении, нажмите <b>Далее</b>:',
  'LBL_SELECT_FILE' => 'Выбрать файл:',
  'LBL_SELECT_MAPPING_INSTRUCTION' => 'В таблице ниже представлены все поля в модуле, которые могут быть сопоставлены с данными файла для импорта. Если файл содержит строку заголовка, колонки в файле были сопоставлены с соответствующими полями. Если данные для импорта содержат даты, год должен быть в формате ГГГГ. Проверьте сопоставления, чтобы убедиться, что они являются тем, что Вы ожидаете, и внесите изменения, если это необходимо. Чтобы помочь Вам проверить сопоставления, Строка 1 отображает данные в файле. Обязательно сопоставьте со всеми обязательными полями (отмеченными звездочкой).',
  'LBL_SELECT_PROPERTY_INSTRUCTION' => 'Вот как представлены несколько первых строк файла для импорта с определенными свойствами файла. Если была определена строка заголовка, она отображается в верхней строке таблицы. Просмотрите свойства файла для импорта, чтобы внести изменения в определенные свойства и для установки дополнительных свойств. Обновление параметров обновит данные, указанные в таблице.',
  'LBL_SELECT_UPLOAD_INSTRUCTION' => 'Выберите файл на Вашем компьютере, содержащий данные, которые Вы хотели бы импортировать или загрузите шаблон для начала создания файла для импорта.',
  'LBL_SF_NUM_1' => 'Откройте Ваш браузер, перейдите на http://www.salesforce.com, и войдите под Вашим электронным адресом и паролем',
  'LBL_SF_NUM_10' => 'При <b>Экспорте отчета:</b>, для <b>Формата файла для экспорта:</b>, выберите <b>.csv</b>. Нажмите <b>Экспортировать</b>.',
  'LBL_SF_NUM_11' => 'Появится диалоговое окно для сохранения экспортированного файла на Ваш компьютер.',
  'LBL_SF_NUM_2' => 'Нажмите на вкладку <b>Отчеты</b> в верхнем меню',
  'LBL_SF_NUM_3' => '<b>Для экспорта Контрагентов:</b> Нажмите на ссылку <b>Active Accounts</b> <br><b>Для экспорта Контактов:</b> Нажмите на ссылку <b>Список для рассылки</b>',
  'LBL_SF_NUM_4' => '<b>Шаг 1: Выберите тип отчета</b>, выберите <b>Табличный отчет</b> нажмите <b>Далее</b>',
  'LBL_SF_NUM_5' => '<b>Шаг 2: Выберите колонки отчета</b>, выберите колонки, которые Вы хотите экспортировать и нажмите <b>Далее</b>',
  'LBL_SF_NUM_6' => '<b>Шаг 3: Выберите информацию для обобщения </b>, просто нажмите <b>Далее</b>',
  'LBL_SF_NUM_7' => '<b>Шаг 4: Расположите по порядку колонки отчета</b>, просто нажмите <b>Далее</b>',
  'LBL_SF_NUM_8' => '<b>Шаг 5: Выберите критерии отчета</b>, под <b>Дата начала</b>, выберите достаточно давнюю дату, чтобы включить все Контрагенты. Вы также можете экспортировать ряд характеристик Контрагентов, используя более расширенные критерии. После завершения, нажмите <b>Запустить отчет</b>',
  'LBL_SF_NUM_9' => 'Отчет будет сформирован и на странице будет отображен <b>Статус формирования отчета: Завершено.</b> Теперь нажмите <b>Экспорт в Excel</b>',
  'LBL_SHOW_ADVANCED_OPTIONS' => 'Просмотреть свойства файла для импорта',
  'LBL_SHOW_HIDDEN' => 'Показать поля, которые не могут быть корректно импортированы',
  'LBL_SHOW_NOTES' => 'Просмотреть заметки',
  'LBL_SHOW_PREVIEW_COLUMNS' => 'Показать колонки предварительного просмотра',
  'LBL_SIGN_IN_HELP' => 'Для активации данной услуги, пожалуйста, войдите в систему под вкладкой Внешние Контрагенты со страницы пользовательских настроек.',
  'LBL_START_OVER' => 'Начать сначала',
  'LBL_STEP_1_TITLE' => 'Шаг 1: Выбрать источник данных',
  'LBL_STEP_2_TITLE' => 'Шаг {0}: Загрузить файл для импорта',
  'LBL_STEP_3_TITLE' => 'Шаг {0}: Подтвердить сопоставление полей',
  'LBL_STEP_4_TITLE' => 'Шаг 4: Импорт файла',
  'LBL_STEP_5_TITLE' => 'Шаг 5: Просмотр результатов',
  'LBL_STEP_DUP_TITLE' => 'Шаг {0}: Проверить наличие возможных дублей',
  'LBL_STEP_MODULE' => 'В какой модуль Вы хотите импортировать данные?',
  'LBL_STRICT_CHECKS' => 'Используйте строгий набор правил (Проверьте адреса электронной почты, а также номера телефонов)',
  'LBL_SUCCESS' => 'Готово',
  'LBL_SUCCESSFULLY' => 'Успешно импортировано',
  'LBL_SUCCESSFULLY_IMPORTED' => 'Записи успешно созданы',
  'LBL_SUMMARY' => 'Резюме',
  'LBL_SYSTEM_SIG_DIGITS' => 'Значащие цифры системы',
  'LBL_TAB' => 'Файл данных с разделителями на основе табуляции',
  'LBL_TAB_NUM_1' => 'Запустить приложение и открыть файл данных',
  'LBL_TAB_NUM_2' => 'Выберите опцию меню <b>Сохранить как...</b> или <b>Экспорт...</b>',
  'LBL_TAB_NUM_3' => 'Сохранить файл в формате <b>TSV</b>',
  'LBL_TEST' => 'Тестирование импорта (не добавляйте данные и не вносите изменения)',
  'LBL_THIRD_PARTY_CSV_SOURCES' => 'Если данные файла для импорта были экспортированы из любого из следующих источников, выберите, из какого именно.',
  'LBL_THIRD_PARTY_CSV_SOURCES_HELP' => 'Выберите источник для автоматического применения кастомизированного сопоставления, чтобы упростить процесс сопоставления (следующий шаг).',
  'LBL_TIMEZONE' => 'Часовой пояс:',
  'LBL_TIME_FORMAT' => 'Формат времени:',
  'LBL_TRUNCATE_TABLE' => 'Очистить таблицу перед импортом (удаление всех записей)',
  'LBL_TRY_AGAIN' => 'Попробуйте еще раз',
  'LBL_UNDO_LAST_IMPORT' => 'Отменить импорт',
  'LBL_UNIQUE_INDEX' => 'Выберите показатель для сравнения дублей',
  'LBL_UNPUBLISH' => 'Отменить публикацию',
  'LBL_UPDATE_BUTTON' => 'Создать новые записи и обновить существующие записи',
  'LBL_UPDATE_BUTTON_HELP' => 'Используйте эту опцию для обновления существующих записей. Данные в файле импорта будут соотнесены с существующими записями на основе ID записи в файле импорта.',
  'LBL_UPDATE_RECORDS' => 'Обновление существующих записей (операцию нельзя отменить)',
  'LBL_UPDATE_SUCCESSFULLY' => 'Записи успешно обновлены',
  'LBL_VALUE' => 'Значение',
  'LBL_VERIFY_DUPLCATES_HELP' => 'Найти существующие записи в системе, которые могут считаться дублями записей, готовых к импорту, проверяя записи на наличие дублей соответствующих данных. Поля, которые попали в колонку "Проверка данных", будут использоваться для проверки записей на наличие дублей. Строки в файле для импорта, содержащие соответствующие данные, будут указаны на следующей странице, и Вы сможете выбрать, какие строки импортировать',
  'LBL_VERIFY_DUPS' => 'Для проверки соответствующих данных существующих записей  в файле для импорта, выберите поля для проверки.',
  'LBL_WHAT_IS' => 'Мои данные находятся в:',
  'LNK_DUPLICATE_LIST' => 'Загрузить список дублей',
  'LNK_ERROR_LIST' => 'Загрузить список ошибок',
  'LNK_RECORDS_SKIPPED_DUE_TO_ERROR' => 'Загрузить список строк, которые не были импортированы',
);

