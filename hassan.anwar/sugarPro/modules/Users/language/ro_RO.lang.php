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
  'ERR_DELETE_RECORD' => 'Trebuie sa specifici un numar de inregistrare pentru a sterge contul',
  'ERR_EMAIL_INCORRECT' => 'Furnizaţi o adresă de email validă, în scopul de a crea şi trimite parola.',
  'ERR_EMAIL_NOT_SENT_ADMIN' => 'Sistemul este în imposibilitatea de a procesa solicitarea dumneavoastră. Vă rugăm să verificaţi:',
  'ERR_EMAIL_NO_OPTS' => 'Nu a putut găsi setările optime pentru e-mail Inbound.',
  'ERR_ENTER_CONFIRMATION_PASSWORD' => 'va rugam sa introduceti confirmarea parolei',
  'ERR_ENTER_NEW_PASSWORD' => 'Va rugam sa introduceti noua parola',
  'ERR_ENTER_OLD_PASSWORD' => 'Vă rugăm să introduceţi parola dvs. curentă.',
  'ERR_IE_FAILURE1' => 'Clic aici pt intoarcere',
  'ERR_IE_FAILURE2' => 'Este o problema cu conectarea la adresa de mail.Va rugam verificati setarile si incercati din nou.',
  'ERR_IE_MISSING_REQUIRED' => 'In setările de email Inbound lipsesc informaţiile solicitate. Vă rugăm să verificaţi setările şi încercaţi din nou. Dacă nu setati Email Inbound, vă rugăm să stergeti toate câmpurile din această secţiune.',
  'ERR_INVALID_PASSWORD' => 'Trebuie sa specificati un nume de utilizator valid si parola corespunzatoare.',
  'ERR_LAST_ADMIN_1' => 'Numele angajatului "',
  'ERR_LAST_ADMIN_2' => '" este ultimul angajat cu acces de administrator. Cel putin un angajat trebuie sa fie administrator',
  'ERR_NO_LOGIN_MOBILE' => 'Prima dvs. conectare la această aplicatie trebuie să fie completata cu un browser non-mobil sau în modul normal. Vă rugăm să reveniţi cu un browser complet sau faceţi clic pe link-ul normal de mai jos. Ne cerem scuze pentru orice neplăcere.',
  'ERR_PASSWORD_CHANGE_FAILED_1' => 'Schimbare parola de utilizator a eşuat pentru',
  'ERR_PASSWORD_CHANGE_FAILED_2' => 'a eşuat. Noua parolă trebuie stabilita.',
  'ERR_PASSWORD_CHANGE_FAILED_3' => 'Parola noua nu este buna',
  'ERR_PASSWORD_INCORRECT_OLD_1' => 'Parola incorecta curentă pentru utilizator',
  'ERR_PASSWORD_INCORRECT_OLD_2' => 'Reintroduceti parola',
  'ERR_PASSWORD_LINK_EXPIRED' => 'link-ul dvs. a expirat, vă rugăm a generati unul nou',
  'ERR_PASSWORD_MISMATCH' => 'Parolele nu se potrivesc.',
  'ERR_PASSWORD_USERNAME_MISSMATCH' => 'Trebuie să specificaţi un nume de utilizator şi adresa de email.',
  'ERR_REASS_DIFF_USERS' => 'Vă rugăm să selectaţi  optiunea Catre utilizator care este diferit de la acest utilizator.',
  'ERR_REASS_SELECT_MODULE' => 'Vă rugăm să mergeţi înapoi şi selectaţi cel puţin un singur modul.',
  'ERR_RECIPIENT_EMAIL' => 'Adresa de email destinatar',
  'ERR_REENTER_PASSWORDS' => 'Parola nouă şi parola de confirmare nu se potrivesc.',
  'ERR_REPORT_LOOP' => 'Sistemul a detectat o buclă de raportare. Un utilizator nu se pot raporta la ei înşişi, nici managerii nu-si pot raporta lor.',
  'ERR_RULES_NOT_MET' => 'Parola introdusa nu a îndeplinit cerinţele parolei. Vă rugăm să încercaţi din nou.',
  'ERR_SERVER_SMTP_EMPTY' => 'Sistemul este în imposibilitatea de a trimite un e-mail pentru utilizator. Vă rugăm să verificaţi Outgoing Mail Configuration în Setări e-mail.',
  'ERR_SERVER_STATUS' => 'Server-ul dvs. de stare',
  'ERR_SMTP_URL_SMTP_PORT' => 'URL-ul SMTP Server şi Port',
  'ERR_SMTP_USERNAME_SMTP_PASSWORD' => 'SMTP Nume utilizator şi parola SMTP',
  'ERR_USER_INFO_NOT_FOUND' => 'Informaţii utilizator nu au fost găsite',
  'ERR_USER_IS_LOCKED_OUT' => 'Acest utilizator este blocat din aplicatia Sugar şi nu se poate autentifica folosind  parola lui / ei existent.',
  'ERR_USER_NAME_EXISTS_1' => 'Nume de utilizator',
  'ERR_USER_NAME_EXISTS_2' => 'există deja. Duplicat nume de utilizator nu sunt permise. Schimba numele de utilizator să fie unic.',
  'LBL_ACCOUNT_NAME' => 'Numele Contului',
  'LBL_ADDRESS' => 'Adresa',
  'LBL_ADDRESS_CITY' => 'Orasul adresa',
  'LBL_ADDRESS_COUNTRY' => 'Tara adresa',
  'LBL_ADDRESS_INFORMATION' => 'Informatii Adresa',
  'LBL_ADDRESS_POSTALCODE' => 'Adresa cod posta',
  'LBL_ADDRESS_STATE' => 'Statul adresa',
  'LBL_ADDRESS_STREET' => 'Strada adresa',
  'LBL_ADDRESS_STREET_2' => 'Adresa 2',
  'LBL_ADDRESS_STREET_3' => 'Adresa 3',
  'LBL_ADMIN' => 'Administrator sistem:',
  'LBL_ADMIN_DESC' => 'Utilizatorul poate accesa pagina de administrare toate înregistrările, indiferent de echipa de securitate.',
  'LBL_ADMIN_USER' => 'Utilizator adiministrator sistem',
  'LBL_ADVANCED' => 'Avansat',
  'LBL_AFFECTED' => 'afectat',
  'LBL_ANY_ADDRESS' => 'Oricare Adresa:',
  'LBL_ANY_EMAIL' => 'Oricare Email:',
  'LBL_ANY_PHONE' => 'Oricare Telefon:',
  'LBL_APPLY_OPTIMUMS' => 'Aplica Optime',
  'LBL_ASSIGN_PRIVATE_TEAM' => '(echipa privata in curs de salvare)',
  'LBL_ASSIGN_TEAM' => 'Alocare la Echipe',
  'LBL_ASSIGN_TO_USER' => 'Aloca la Utilizator',
  'LBL_AUTHENTICATE_ID' => 'Id-ul de autentificare',
  'LBL_BASIC' => 'Instaleaza Inbound',
  'LBL_BUTTON_CREATE' => 'Creaza',
  'LBL_BUTTON_EDIT' => 'Editeaza',
  'LBL_CALENDAR_OPTIONS' => 'Opţiuni Calendar',
  'LBL_CANCEL' => 'Anulare',
  'LBL_CANNOT_SEND_PASSWORD' => 'Nu se poate trimite parola',
  'LBL_CERT' => 'Valideaza Certificat',
  'LBL_CERT_DESC' => 'Validare fortata a Certificatului de Securitate a serverului de mail -  a nu se utiliza daca se foloseste auto-semnatura',
  'LBL_CHANGE_PASSWORD' => 'Schimba Parola generata',
  'LBL_CHANGE_PASSWORD_TITLE' => 'Parola:',
  'LBL_CHANGE_SYSTEM_PASSWORD' => 'Vă rugăm să furnizaţi o parolă nouă.',
  'LBL_CHECKMARK' => 'Checkmark',
  'LBL_CHOOSE_A_KEY' => 'Alegeţi o cheie pentru a preveni publicarea neautorizată a calendarului dvs.',
  'LBL_CHOOSE_EMAIL_PROVIDER' => 'Alegeti furnizorul dumneavoastra de Email',
  'LBL_CHOOSE_WHICH' => 'Alege tab-uri care sunt afişate',
  'LBL_CITY' => 'Oras',
  'LBL_CLEAR_BUTTON_TITLE' => 'Sterge',
  'LBL_CONFIRM_PASSWORD' => 'Confirma parola',
  'LBL_CONFIRM_REGULAR_USER' => 'Aţi schimbat tipul de utilizator de la System Administrator utilizator la utilizator obişnuit. După salvarea acestei schimbari, utilizatorul nu va mai avea privilegii de administrator de sistem \\ n \\ nFaceţi clic pe OK pentru a continua.. \\ NFaceţi clic pe Anulare pentru a reveni la înregistrare.',
  'LBL_COUNTRY' => 'Tara:',
  'LBL_CREATED_BY_NAME' => 'Creeata de',
  'LBL_CURRENCY' => 'Valuta',
  'LBL_CURRENCY_EXAMPLE' => 'Exemplu Valută Display',
  'LBL_CURRENCY_SHOW_PREFERRED' => 'Afişează moneda preferată',
  'LBL_CURRENCY_SHOW_PREFERRED_TEXT' => 'Schimbă moneda de bază în cea preferată de utilizator în ferestrele de vizualizare liste şi înregistrări',
  'LBL_CURRENCY_SIG_DIGITS' => 'Cifre semnificative a valutei',
  'LBL_CURRENCY_SIG_DIGITS_DESC' => 'Numărul de zecimale pentru a arăta moneda',
  'LBL_CURRENCY_TEXT' => 'Selectaţi moneda in care vor fi afişate în mod implicit atunci când creaţi noi inregistrari. Aceasta este, de asemenea, moneda care va fi afişata în coloanele Suma în Vedere Lista Oportunităţi.',
  'LBL_DATE_ENTERED' => 'Data intrarii',
  'LBL_DATE_FORMAT' => 'Formatul datei',
  'LBL_DATE_FORMAT_TEXT' => 'Setaţi formatul de afişare pentru ştampile cu data',
  'LBL_DATE_MODIFIED' => 'Data Modificarii',
  'LBL_DECIMAL_SEP' => 'Simbol zecimal',
  'LBL_DECIMAL_SEP_TEXT' => 'Caractere folosite pentru a separa partea zecimală',
  'LBL_DEFAULT_PRIMARY_TEAM' => 'Echipa Implicita',
  'LBL_DEFAULT_SUBPANEL_TITLE' => 'Utilizatori',
  'LBL_DEFAULT_TEAM' => 'Echipe implicita',
  'LBL_DEFAULT_TEAM_TEXT' => 'Echipe disponibil să apară în mod implicit în înregistrările sunt cele de care sunteţi membru.',
  'LBL_DELETED' => 'Stearsa',
  'LBL_DELETE_GROUP_CONFIRM' => 'Sunteţi sigur că doriţi să ştergeţi acest Grup de utilizatori? Faceţi clic pe OK pentru a şterge înregistrarea utilizatorului.<br />După ce faceţi clic pe OK, vi se va da posibilitatea de a realoca înregistrări atribuite User Group la un alt utilizator.',
  'LBL_DELETE_PORTAL_CONFIRM' => 'Sunteţi sigur că doriţi să ştergeţi acest Portal API utilizator? Faceţi clic pe OK pentru a şterge înregistrarea utilizatorului.',
  'LBL_DELETE_USER' => 'Ştergeţi utilizator',
  'LBL_DELETE_USER_CONFIRM' => 'Atunci când utilizatorul record se elimină, înregistrarea angajaţilor corespunzătoare vor fi şterse de asemenea. După ce utilizatorul este şters, nici definiţii flux de lucru şi rapoartele care implică utilizatorul ar putea avea nevoie să fie actualizate.<br /><br />Faceţi clic pe OK pentru a şterge înregistrarea utilizatorului. După ce faceţi clic pe OK, vi se va da posibilitatea de a realoca înregistrări atribuite utilizator la un alt utilizator.',
  'LBL_DEPARTMENT' => 'Departament',
  'LBL_DESCRIPTION' => 'Descriere',
  'LBL_DISPLAY_TABS' => 'Arata foi',
  'LBL_DOWNLOADS' => 'Descarcari',
  'LBL_DST_INSTRUCTIONS' => '(+ DST) indică respectarea orei de vara',
  'LBL_EAPM_SUBPANEL_TITLE' => 'Conturi externe',
  'LBL_EDIT' => 'Editeaza',
  'LBL_EDITLAYOUT' => 'Editeaza Plan General',
  'LBL_EDIT_TABS' => 'Editeaza fila',
  'LBL_EMAIL' => 'Adresa Email:',
  'LBL_EMAILS' => 'Email-uri',
  'LBL_EMAIL_ADDRESS' => 'Adresă Email',
  'LBL_EMAIL_CHARSET' => 'Outbound Setul de caractere',
  'LBL_EMAIL_EDITOR_OPTION' => 'Compuneţi format',
  'LBL_EMAIL_GMAIL_DEFAULTS' => 'Preincarca setariel implicite ale GmailTM',
  'LBL_EMAIL_INBOUND_TITLE' => 'Setări Email Inbound',
  'LBL_EMAIL_LINK_TYPE' => 'Client Email',
  'LBL_EMAIL_LINK_TYPE_HELP' => 'Client Email Sugar: Expediaza email-uri utilizand clientul de email din cadrul aplicatiei Sugar.<br />Client Email Extern: Expediaza email-uri utilizand un client de email din afara aplicatie Sugar, precum Microsoft Outlook.',
  'LBL_EMAIL_NOT_SENT' => 'Sistemul este în imposibilitatea de a procesa solicitarea dumneavoastră. Vă rugăm să contactaţi administratorul de sistem.',
  'LBL_EMAIL_OTHER' => 'Mail 2',
  'LBL_EMAIL_OUTBOUND_TITLE' => 'Setări E mail Outbound',
  'LBL_EMAIL_PROVIDER' => 'Provider Email',
  'LBL_EMAIL_SHOW_COUNTS' => 'Arata conturi email ?',
  'LBL_EMAIL_SIGNATURE_ERROR1' => 'Această semnătură necesită un nume.',
  'LBL_EMAIL_SMTP_SSL' => 'Activate SMTP pe SSL?',
  'LBL_EMAIL_TEMPLATE_MISSING' => 'Nicun sablon de e-mail nu este selectat pentru e-mailul care conţine parola pe care va fi trimisa utilizatorului. Vă rugăm să selectaţi un şablon de e-mail în pagina Management Parole.',
  'LBL_EMPLOYEE_INFORMATION' => 'Informatii angajat',
  'LBL_EMPLOYEE_STATUS' => 'Statut Angajat:',
  'LBL_ERROR' => 'Eroare',
  'LBL_EXCHANGE_SMTPPASS' => 'Schimba Parola',
  'LBL_EXCHANGE_SMTPPORT' => 'Schimba portul serverului',
  'LBL_EXCHANGE_SMTPSERVER' => 'Schimba Server',
  'LBL_EXCHANGE_SMTPUSER' => 'Schimba Numele de Utilizator',
  'LBL_EXPORT_CHARSET' => 'Import / Export Set de caractere',
  'LBL_EXPORT_CHARSET_DESC' => 'Alegeţi setul de caractere utilizat în localizarea dumneavoastră. Aceasta proprietate va fi utilizata pentru importul de date,. Csv exporturile şi pentru generarea vCard.',
  'LBL_EXPORT_CREATED_BY' => 'Creat de ID',
  'LBL_EXPORT_DELIMITER' => 'Delimitator de export',
  'LBL_EXPORT_DELIMITER_DESC' => 'Specificaţi caracter (e) utilizat pentru a delimita datele exportate.',
  'LBL_EXTERNAL_AUTH_ONLY' => 'Autentifica acest utilizator doar prin intermediul',
  'LBL_EXT_AUTHENTICATE' => 'Autentificare Externa',
  'LBL_FAX' => 'Fax:',
  'LBL_FAX_PHONE' => 'Fax:',
  'LBL_FDOW' => 'Prima zi din saptamana',
  'LBL_FDOW_TEXT' => 'Prima zi din saptamana,luna,an',
  'LBL_FILTER_USERS_REPORTS' => 'Rapoarte utilizator',
  'LBL_FIND_OPTIMUM_KEY' => 'f',
  'LBL_FIND_OPTIMUM_MSG' => 'Cautare variabile de conexiune optima.',
  'LBL_FIND_OPTIMUM_TITLE' => 'Cauta Configurare Optima',
  'LBL_FIRST_NAME' => 'Nume:',
  'LBL_FORCE' => 'Fortare Negativ',
  'LBL_FORCE_DESC' => 'Unele servere IMAP/POP3 necesita comutatoare speciale. Verificati daca fortati un comutator negativ atunci cand va conectati (adica, /notls)',
  'LBL_FORECASTS' => 'Previziuni',
  'LBL_FORGOTPASSORD_NOT_ENABLED' => 'Momentan această funcţie nu este activată. Vă rugăm să contactaţi administratorul.',
  'LBL_FOUND_OPTIMUM_MSG' => 'S-au gasit setarile optime. Apasati butonul de mai jos pentru a le aplica la contul dumneavoastra de email.',
  'LBL_GENERATE_PASSWORD' => 'Resetare parolă',
  'LBL_GENERATE_PASSWORD_BUTTON_KEY' => 'G',
  'LBL_GENERATE_PASSWORD_BUTTON_LABEL' => 'Resetare parolă',
  'LBL_GENERATE_PASSWORD_BUTTON_TITLE' => 'Reset [Alt + G] Parola',
  'LBL_GMAIL_SMTPPASS' => 'Parola Gmail',
  'LBL_GMAIL_SMTPUSER' => 'Adresa Gmail de Email',
  'LBL_GROUP_DESC' => 'Folosiţi pentru atribuirea produsului pentru un grup (de exemplu: pentru e-mail Inbound). Acest tip nu se poate loga prin intermediul interfeţei web Sugar.',
  'LBL_GROUP_USER' => 'Grup Utilizatori',
  'LBL_GROUP_USER_STATUS' => 'Grup Utilizatori',
  'LBL_HELP' => 'Ajutor',
  'LBL_HIDEOPTIONS' => 'Ascunde optiuni',
  'LBL_HIDE_TABS' => 'Ascunde foi',
  'LBL_HOME_PHONE' => 'Telefon Acasa:',
  'LBL_ICAL_PUB_URL' => 'iCal integration URL',
  'LBL_ICAL_PUB_URL_HELP' => 'Folosiţi acest URL-ul să se aboneze la calendarul de Sugar cu iCal.',
  'LBL_INBOUND_TITLE' => 'Informatii cont',
  'LBL_IS_ADMIN' => 'Este Administrator',
  'LBL_IS_GROUP' => 'grup',
  'LBL_LANGUAGE' => 'Limba',
  'LBL_LAST_ADMIN_NOTICE' => 'V-aţi selectat. Nu puteţi schimba tipul de utilizator sau Stare ta.',
  'LBL_LAST_NAME' => 'Prenume',
  'LBL_LAST_NAME_SLASH_NAME' => 'Prenume',
  'LBL_LAYOUT_OPTIONS' => 'Setari Aspect',
  'LBL_LDAP' => 'LDAP',
  'LBL_LDAP_AUTHENTICATION' => 'Autentificare LDAP',
  'LBL_LDAP_ERROR' => 'LDAP Eroare: Vă rugăm să contactaţi un Admin',
  'LBL_LDAP_EXTENSION_ERROR' => 'LDAP Eroare: Extensii nu pot fi încărcate',
  'LBL_LIST_ACCEPT_STATUS' => 'Accept Statut',
  'LBL_LIST_ADMIN' => 'Admin',
  'LBL_LIST_DEPARTMENT' => 'Departament',
  'LBL_LIST_DESCRIPTION' => 'Descriere',
  'LBL_LIST_EMAIL' => 'E-mail',
  'LBL_LIST_FORM_TITLE' => 'Utilizatori',
  'LBL_LIST_GROUP' => 'Grup',
  'LBL_LIST_LAST_NAME' => 'Prenume',
  'LBL_LIST_MEMBERSHIP' => 'Membru',
  'LBL_LIST_NAME' => 'Nume',
  'LBL_LIST_PASSWORD' => 'Parola:',
  'LBL_LIST_PRIMARY_PHONE' => 'Telefon Primar',
  'LBL_LIST_STATUS' => 'Statut',
  'LBL_LIST_TITLE' => 'Titlu:',
  'LBL_LIST_USER_NAME' => 'Nume Utilizator',
  'LBL_LOCALE_DEFAULT_NAME_FORMAT' => 'Nume Format Ecran',
  'LBL_LOCALE_DESC_FIRST' => '[Primul]',
  'LBL_LOCALE_DESC_LAST' => '[Ultimul]',
  'LBL_LOCALE_DESC_SALUTATION' => '[Formula de adresare]',
  'LBL_LOCALE_DESC_TITLE' => '[Titlu]',
  'LBL_LOCALE_EXAMPLE_NAME_FORMAT' => 'Exemplu',
  'LBL_LOCALE_NAME_FORMAT_DESC' => '"S" Formula de adresare, "f" Prenume, "L" Nume',
  'LBL_LOCALE_NAME_FORMAT_DESC_2' => '"S" Formula de adresare<br />"F" Prenume<br />"L" Nume',
  'LBL_LOGGED_OUT_1' => 'Aţi fost deconectat. Pentru a va loga din nou vă rugăm să faceţi clic pe',
  'LBL_LOGGED_OUT_2' => 'aici',
  'LBL_LOGGED_OUT_3' => '.',
  'LBL_LOGIN' => 'Nume Utilizator',
  'LBL_LOGIN_ADMIN_CALL' => 'Vă rugăm să contactaţi administratorul de sistem.',
  'LBL_LOGIN_ATTEMPTS_OVERRUN' => 'Prea multe incercari esuate de autentificare.',
  'LBL_LOGIN_BUTTON_KEY' => 'L',
  'LBL_LOGIN_BUTTON_LABEL' => 'Logare',
  'LBL_LOGIN_BUTTON_TITLE' => 'Log In [Alt + L]',
  'LBL_LOGIN_FORGOT_PASSWORD' => 'Ai uitat parola?',
  'LBL_LOGIN_LOGIN_TIME_ALLOWED' => 'Aveţi posibilitatea să încercaţi să vă conectaţi din nou în',
  'LBL_LOGIN_LOGIN_TIME_DAYS' => 'zile.',
  'LBL_LOGIN_LOGIN_TIME_HOURS' => 'h.',
  'LBL_LOGIN_LOGIN_TIME_MINUTES' => 'min.',
  'LBL_LOGIN_LOGIN_TIME_SECONDS' => 'sec.',
  'LBL_LOGIN_OPTIONS' => 'Optiuni',
  'LBL_LOGIN_SUBMIT' => 'Incarca',
  'LBL_LOGIN_WELCOME_TO' => 'Bun venit in',
  'LBL_MAILBOX' => 'Foldere Monitorizate',
  'LBL_MAILBOX_DEFAULT' => 'INBOX',
  'LBL_MAILBOX_SSL_DESC' => 'Utilizati SSL atunci cand va conectati. Daca aceasta nu functioneaza, verificati daca instalarea PHP dumneavoastra include "--with-imap-ssl" in configuratie.',
  'LBL_MAILBOX_TYPE' => 'Actiuni Posibile',
  'LBL_MAILMERGE' => 'Îmbinare corespondenţă',
  'LBL_MAILMERGE_TEXT' => 'Activează Îmbinare corespondenţă (Îmbinare corespondenţă trebuie să fie, de asemenea, activat de către administratorul de sistem în configura setările)',
  'LBL_MAIL_FROMADDRESS' => 'Adresa pentru răspuns',
  'LBL_MAIL_FROMNAME' => 'Raspunde la numele',
  'LBL_MAIL_OPTIONS_TITLE' => 'Setari Email',
  'LBL_MAIL_SENDTYPE' => 'Agent Transfer Email:',
  'LBL_MAIL_SMTPAUTH_REQ' => 'Utilizeaza Autentificare SMTP:',
  'LBL_MAIL_SMTPPASS' => 'Parola SMTP:',
  'LBL_MAIL_SMTPPORT' => 'Port SMTP:',
  'LBL_MAIL_SMTPSERVER' => 'Server SMTP',
  'LBL_MAIL_SMTPTYPE' => 'SMTP Server Tip:',
  'LBL_MAIL_SMTPUSER' => 'Nume Utilizator SMTP:',
  'LBL_MAIL_SMTP_SETTINGS' => 'SMTP Server Specificaţii',
  'LBL_MARK_READ' => 'Lasa Mesaje pe Server',
  'LBL_MARK_READ_DESC' => 'Marcheaza mesajele citite pe serverul de email la importare; a nu se sterge.',
  'LBL_MARK_READ_NO' => 'Emailurile marcate sters dupa importare',
  'LBL_MARK_READ_YES' => 'Emailuri lasate pe server dupa importare',
  'LBL_MAX_SUBTAB' => 'Numărul de subfile',
  'LBL_MAX_SUBTAB_DESCRIPTION' => 'Număr de subfile afişate pe tab-ul înainte de a apare un meniu de prea plin.',
  'LBL_MAX_TAB' => 'Număr de foi',
  'LBL_MAX_TAB_DESCRIPTION' => 'Numărul de file în partea de sus a paginii înainte de un meniu apare overflow.',
  'LBL_MESSENGER_ID' => 'IM Nume:',
  'LBL_MESSENGER_TYPE' => 'IM Tip:',
  'LBL_MISSING_DEFAULT_OUTBOUND_SMTP_SETTINGS' => 'Administator nu a configurat încă contul implicit de ieşire. Imposibil pentru a trimite e-mail de testare.',
  'LBL_MOBILE_PHONE' => 'Mobil:',
  'LBL_MODIFIED_BY' => 'Modificata de',
  'LBL_MODIFIED_BY_ID' => 'Modificat de ID-ul',
  'LBL_MODIFIED_USER_ID' => 'Modificare Utilizator ID',
  'LBL_MODULE_NAME' => 'Utilizatori',
  'LBL_MODULE_NAME_SINGULAR' => 'Utilizator',
  'LBL_MODULE_TITLE' => 'Utilizatori: Acasa',
  'LBL_MY_TEAMS' => 'Echiplele mele',
  'LBL_NAME' => 'Nume intreg:',
  'LBL_NAVIGATION_PARADIGM' => 'Navigare',
  'LBL_NEW_FORM_TITLE' => 'Utilizator nou',
  'LBL_NEW_PASSWORD' => 'Parola noua',
  'LBL_NEW_PASSWORD1' => 'Parola:',
  'LBL_NEW_PASSWORD2' => 'Confirma parola',
  'LBL_NEW_USER_BUTTON_KEY' => 'N',
  'LBL_NEW_USER_BUTTON_LABEL' => 'Utilizator nou',
  'LBL_NEW_USER_BUTTON_TITLE' => 'Noi [Alt + N] Utilizator',
  'LBL_NEW_USER_PASSWORD_1' => 'Parola a fost schimbata cu succes.',
  'LBL_NEW_USER_PASSWORD_2' => 'Un e-mail a fost trimis la utilizator care conţine o parolă generata de sistem.',
  'LBL_NEW_USER_PASSWORD_3' => 'Parola a fost creata cu succes.',
  'LBL_NORMAL_LOGIN' => 'Comutaţi în vizualizarea normală',
  'LBL_NOTES' => 'Note',
  'LBL_NO_KEY' => 'Cheia nu este setata. Vă rugăm să setați cheia pentru a permite publicare.',
  'LBL_NUMBER_GROUPING_SEP' => '1000 separator',
  'LBL_NUMBER_GROUPING_SEP_TEXT' => 'Caractere utilizate pentru separarea miilor',
  'LBL_OAUTH_TOKENS' => 'OAuth Tokens',
  'LBL_OAUTH_TOKENS_SUBPANEL_TITLE' => 'OAuth Access Tokens',
  'LBL_OFFICE_PHONE' => 'Telefon Birou',
  'LBL_OK' => 'OK',
  'LBL_OLD_PASSWORD' => 'parola curenta',
  'LBL_ONLY' => 'Numai',
  'LBL_ONLY_SINCE' => 'Importa Numai de la Ultima Verificare:',
  'LBL_ONLY_SINCE_DESC' => 'Cand se utilizeaza POP3, PHP nu poate filtra mesajele Noi/Necitite. Acest steag permite cererea de verificare a mesajelor incepand cu ultima data cand contul de mail a fost verificat. Aceasta va imbunatati in mod semnificativ performanta daca serverul dumneavoastra de email nu suporta IMAP.',
  'LBL_ONLY_SINCE_NO' => 'Nu. Verifica in opozitie cu toate emailurile de pe serverul de mail.',
  'LBL_ONLY_SINCE_YES' => 'Da.',
  'LBL_OTHER' => 'Altul',
  'LBL_OTHER_EMAIL' => 'Alta adresa Email',
  'LBL_OTHER_PHONE' => 'Alt Telefon:',
  'LBL_OWN_OPPS' => 'Nu sunt Oportunităţi',
  'LBL_OWN_OPPS_DESC' => 'Selectaţi în cazul în care utilizatorul nu va fi atribuit la oportunităţi. Utilizaţi această setare pentru utilizatorii care sunt manageri care nu sunt implicati în activităţile de vânzare. Setarea este utilizata pentru modulul de prognoză.',
  'LBL_PASSWORD' => 'Parola:',
  'LBL_PASSWORD_EXPIRATION_GENERATED' => 'Parola dumneavoastră este generata de sistem',
  'LBL_PASSWORD_EXPIRATION_LOGIN' => 'Parola dvs. a expirat. Vă rugăm să furnizaţi o parolă nouă.',
  'LBL_PASSWORD_EXPIRATION_TIME' => 'Parola dvs. a expirat. Vă rugăm să furnizaţi o parolă nouă.',
  'LBL_PASSWORD_GENERATED' => 'parola noua generata',
  'LBL_PASSWORD_SENT' => 'Parola actualizata',
  'LBL_PDF_FONT_NAME_DATA' => 'Font pentru subsol',
  'LBL_PDF_FONT_NAME_DATA_TEXT' => 'Fonturile selectate vor fi aplicate la textul în notele de subsol ale documentului PDF',
  'LBL_PDF_FONT_NAME_MAIN' => 'Font pentru antet şi corp',
  'LBL_PDF_FONT_NAME_MAIN_TEXT' => 'Fonturile selectate vor fi aplicate la textul în antet şi corpul Document PDF',
  'LBL_PDF_FONT_SIZE_DATA' => 'Dimensiune Font Date',
  'LBL_PDF_FONT_SIZE_DATA_TEXT' => '',
  'LBL_PDF_FONT_SIZE_MAIN' => 'Dimensiune font Principal',
  'LBL_PDF_FONT_SIZE_MAIN_TEXT' => '',
  'LBL_PDF_MARGIN_BOTTOM' => 'Marginea de jos',
  'LBL_PDF_MARGIN_BOTTOM_TEXT' => '',
  'LBL_PDF_MARGIN_FOOTER' => 'Marginea de subsol',
  'LBL_PDF_MARGIN_FOOTER_TEXT' => '',
  'LBL_PDF_MARGIN_HEADER' => 'Marginea antet',
  'LBL_PDF_MARGIN_HEADER_TEXT' => '',
  'LBL_PDF_MARGIN_LEFT' => 'Marginea de stanga',
  'LBL_PDF_MARGIN_LEFT_TEXT' => '',
  'LBL_PDF_MARGIN_RIGHT' => 'Marginea de dreapta',
  'LBL_PDF_MARGIN_RIGHT_TEXT' => '',
  'LBL_PDF_MARGIN_TOP' => 'Marginea de top',
  'LBL_PDF_MARGIN_TOP_TEXT' => '',
  'LBL_PDF_PAGE_FORMAT' => 'Formatul de pagină',
  'LBL_PDF_PAGE_FORMAT_TEXT' => 'Formatul utilizat pentru paginile',
  'LBL_PDF_PAGE_ORIENTATION' => 'Orientare Pagina',
  'LBL_PDF_PAGE_ORIENTATION_L' => 'Peisaj',
  'LBL_PDF_PAGE_ORIENTATION_P' => 'Portret',
  'LBL_PDF_PAGE_ORIENTATION_TEXT' => '',
  'LBL_PDF_SETTINGS' => 'Setări PDF',
  'LBL_PHONE' => 'Telefon',
  'LBL_PHONE_FAX' => 'Telefon fax',
  'LBL_PHONE_HOME' => 'Telefon acasa',
  'LBL_PHONE_MOBILE' => 'Telefon mobil',
  'LBL_PHONE_OTHER' => 'Alt telefon',
  'LBL_PHONE_WORK' => 'Telefon munca',
  'LBL_PICK_TZ_DESCRIPTION' => 'Înainte de a continua, vă rugăm să confirmaţi fusul orar. Selectaţi fusul orar corespunzătoare din lista de mai jos, şi faceţi clic pe Salvare pentru a continua. Zona de timp poate fi schimbată în orice moment în setările dvs. de utilizator.',
  'LBL_PICK_TZ_WELCOME' => 'Bun venit la Sugar.',
  'LBL_PICTURE_FILE' => 'Poza',
  'LBL_PORT' => 'Portul Server de Mail',
  'LBL_PORTAL_ONLY' => 'Portal Utilizator',
  'LBL_PORTAL_ONLY_DESC' => 'Utilizaţi pentru API Portal. Acest tip nu se poate loga prin intermediul interfeţei web Sugar.',
  'LBL_PORTAL_ONLY_USER' => 'Portal API utilizator',
  'LBL_POSTAL_CODE' => 'Cod Postal:',
  'LBL_PRIMARY_ADDRESS' => 'Adresa Primara:',
  'LBL_PRIVATE_TEAM_FOR' => 'Echipa privata pentru:',
  'LBL_PROCESSING' => 'Precesare...',
  'LBL_PROMPT_TIMEZONE' => 'Expertul utilizator Prompt',
  'LBL_PROMPT_TIMEZONE_TEXT' => 'Selectaţi pentru a avea noi utilizatori treceti prin utilizator New Wizard, la prima logare',
  'LBL_PROSPECT_LIST' => 'Lista Prospect',
  'LBL_PROVIDE_USERNAME_AND_EMAIL' => 'Ofera atât un nume de utilizator şi o adresă de email.',
  'LBL_PSW_MODIFIED' => 'parola a fost schimbata',
  'LBL_PUBLISH_KEY' => 'Publica cheie',
  'LBL_QUOTAS' => 'Contingente',
  'LBL_REASS_ASSESSING' => 'Evaluarea',
  'LBL_REASS_BUTTON_CLEAR' => 'Sterge',
  'LBL_REASS_BUTTON_CONTINUE' => 'Urmatorul>',
  'LBL_REASS_BUTTON_GO_BACK' => '<innapoi',
  'LBL_REASS_BUTTON_REASSIGN' => 'Realoca',
  'LBL_REASS_BUTTON_RESTART' => 'Restart',
  'LBL_REASS_BUTTON_RETURN' => 'Intoarce-te',
  'LBL_REASS_BUTTON_SUBMIT' => 'Incarca',
  'LBL_REASS_CANNOT_PROCESS' => 'nu pot fi procesate',
  'LBL_REASS_CONFIRM_REASSIGN' => 'Doriţi să realocaţi toate înregistrările acestui utilizator?',
  'LBL_REASS_CONFIRM_REASSIGN_NO' => 'Nu',
  'LBL_REASS_CONFIRM_REASSIGN_TITLE' => 'Atribuie din nou',
  'LBL_REASS_CONFIRM_REASSIGN_YES' => 'Da',
  'LBL_REASS_DESC_PART1' => 'Selectaţi modulele care conţine înregistrările de realocare de la un anumit utilizator la un alt utilizator.<br /><br />Faceţi clic pe Următorul pentru a vizualiza numărul de înregistrări care vor fi actualizate în fiecare modulul selectat. Faceţi clic pe Revocare pentru a ieşi din pagina fără nici o mutare de înregistrări.',
  'LBL_REASS_DESC_PART2' => 'Selecta care module în raport cu care să ruleze fluxurilor de lucru, trimite notificări de atribuire, şi nu de urmărire de audit în timpul mutării. Faceţi clic pe Următorul pentru a continua şi realocaţi înregistrări. Faceţi clic pe Restart pentru a reîncepe.',
  'LBL_REASS_FAILED' => 'Esuat',
  'LBL_REASS_FAILED_SAVE' => 'Imposibilitatea de a salva de înregistrarea',
  'LBL_REASS_FILTERS' => 'Filtre',
  'LBL_REASS_FROM' => 'de la',
  'LBL_REASS_HAVE_BEEN_UPDATED' => 'au fost actualizate:',
  'LBL_REASS_MOD_REASSIGN' => 'Module să includă în Realocare:',
  'LBL_REASS_NONE' => 'Niciunul',
  'LBL_REASS_NOTES_ONE' => 'Rularea fluxului de lucru ar determina procesul de mutare să fie semnificativ mai lent.',
  'LBL_REASS_NOTES_THREE' => 'Alocarea înregistrărilor pentru tine nu vor declanşa notificări alocate.',
  'LBL_REASS_NOTES_TITLE' => 'Note:',
  'LBL_REASS_NOTES_TWO' => 'Chiar dacă nu selectaţi pentru a face urmărirea de audit, Data modificării şi Modificată prin câmp ,în înregistrări va fi în continuare actualizată în consecinţă.',
  'LBL_REASS_NOT_PROCESSED' => 'nu a putut fi prelucrate:',
  'LBL_REASS_RECORDS_FROM' => 'inregistrari de la',
  'LBL_REASS_SCRIPT_TITLE' => 'Selectaţi modulele care conţine înregistrările de realocare de la un anumit utilizator la un alt utilizator.',
  'LBL_REASS_STEP2_DESC' => 'Echipele enumerate mai jos au fost disponibile în echipa de la utilizator, dar nu în a echipei utilizatorului. Toate înregistrările în Din echipa utilizatorului nu vor fi vizibile în echipa de Utilizator excepţia cazului în care valorile echipei sunt mapate.',
  'LBL_REASS_STEP2_TITLE' => 'Realocarea Echipei',
  'LBL_REASS_SUCCESSFUL' => 'Succes',
  'LBL_REASS_SUCCESS_ASSIGN' => 'Cu succes alocate',
  'LBL_REASS_TEAMS_GOOD_MSG' => 'Utilizatorul are acces la toate de la echipele de utilizatorului. Nici o cartografiere necesara. Redirectionarea la pagina următoare în 5 secunde.',
  'LBL_REASS_TEAM_NO_CHANGE' => '- Nici o schimbare -',
  'LBL_REASS_TEAM_SET_TO' => 'şi echipele au fost stabilite la',
  'LBL_REASS_TEAM_TO' => 'Stabileste echipe la:',
  'LBL_REASS_THE_FOLLOWING' => 'Urmatoarele',
  'LBL_REASS_TO' => 'pana la',
  'LBL_REASS_UPDATE_COMPLETE' => 'Actualizare completa',
  'LBL_REASS_USER_FROM' => 'de la utilizator:',
  'LBL_REASS_USER_FROM_TEAM' => 'Echipa de la utilizatorului:',
  'LBL_REASS_USER_TO' => 'Catre utilizator:',
  'LBL_REASS_USER_TO_TEAM' => 'catre echipa utilizatorului',
  'LBL_REASS_VERBOSE_HELP' => 'Selectaţi această opţiune pentru a vizualiza informaţii detaliate despre sarcinile reatribuire care implica fluxuri de lucru.',
  'LBL_REASS_VERBOSE_OUTPUT' => 'Ieşire Verbose',
  'LBL_REASS_WILL_BE_UPDATED' => 'va fi actualizat',
  'LBL_REASS_WORK_NOTIF_AUDIT' => 'Includeţi flux de lucru / Anunturi / Audit (în mod semnificativ mai lent)',
  'LBL_RECAPTCHA_FILL_FIELD' => 'Introduceţi textul care apare în imagine.',
  'LBL_RECAPTCHA_IMAGE' => 'Schimba la imagine',
  'LBL_RECAPTCHA_INSTRUCTION' => 'Introduceţi două cuvinte de mai jos',
  'LBL_RECAPTCHA_INSTRUCTION_OPPOSITE' => 'Introduceţi două cuvinte la dreapta',
  'LBL_RECAPTCHA_INVALID_PRIVATE_KEY' => 'Invalid cheie privată reCAPTCHA',
  'LBL_RECAPTCHA_INVALID_REQUEST_COOKIE' => 'Parametrul provocare a script-ului reCAPTCHA reverificat a fost incorectă.',
  'LBL_RECAPTCHA_NEW_CAPTCHA' => 'Ia un alt CAPTCHA',
  'LBL_RECAPTCHA_SOUND' => 'Schimba la sunet',
  'LBL_RECAPTCHA_UNKNOWN' => 'Eroare necunoscută reCAPTCHA',
  'LBL_RECEIVE_NOTIFICATIONS' => 'Notifica in sarcina',
  'LBL_RECEIVE_NOTIFICATIONS_TEXT' => 'Vei primi o notificare prin e-mail atunci când o înregistrare este alocat pentru tine.',
  'LBL_REGISTER' => 'Utilizator nou?Va rugam inregistrati-va',
  'LBL_REGULAR_DESC' => 'Utilizatorul poate accesa modulele şi înregistrările de securitate bazate pe echipă şi roluri.',
  'LBL_REGULAR_USER' => 'Utilizator normal',
  'LBL_REMINDER' => 'Memento',
  'LBL_REMINDER_EMAIL' => 'Email',
  'LBL_REMINDER_EMAIL_ALL_INVITEES' => 'Trimite email tuturor invitatilor',
  'LBL_REMINDER_POPUP' => 'Popup',
  'LBL_REMINDER_TEXT' => 'Setaţi implicit pentru memento-uri pentru apeluri şi întâlniri.',
  'LBL_REMOVED_TABS' => 'Admin Eliminaţi Tabs',
  'LBL_REPORTS_TO' => 'Raporteaza catre',
  'LBL_REPORTS_TO_ID' => 'Raporteaza la ID:',
  'LBL_REPORTS_TO_NAME' => 'Raporteaza catre',
  'LBL_REQUEST_SUBMIT' => 'Cererea dvs. a fost înaintată.',
  'LBL_RESET_DASHBOARD' => 'Resetare Tabloul de bord',
  'LBL_RESET_PREFERENCES' => 'Revenire la setarile initiale',
  'LBL_RESET_PREFERENCES_WARNING' => 'Esti sigur ca vrei resetaţi toate preferinţele dumneavoastră de utilizator? Avertisment: Acest jurnal, de asemenea, va va scoate din cererii.',
  'LBL_RESET_PREFERENCES_WARNING_USER' => 'Esti sigur ca vrei resetaţi toate preferinţele pentru acest utilizator?',
  'LBL_RESET_TO_DEFAULT' => 'Intoarce-te la initial',
  'LBL_RESOURCE_NAME' => 'Nume',
  'LBL_RESOURCE_TYPE' => 'Tip',
  'LBL_ROLES_SUBPANEL_TITLE' => 'Roluri',
  'LBL_SALUTATION' => 'Formula de salut',
  'LBL_SAVED_SEARCH' => 'Cautari salvate & Aspect',
  'LBL_SEARCH_FORM_TITLE' => 'Cautare user',
  'LBL_SEARCH_URL' => 'Căutare locaţie',
  'LBL_SELECT_CHECKED_BUTTON_LABEL' => 'Selecteaza Utilizatorii Marcati',
  'LBL_SELECT_CHECKED_BUTTON_TITLE' => 'Selecteaza Utilizatorii Marcati',
  'LBL_SERVER_OPTIONS' => 'Setari Avansate',
  'LBL_SERVER_TYPE' => 'Protocol Server de Mail',
  'LBL_SERVER_URL' => 'Adresa Server de Mail',
  'LBL_SESSION_EXPIRED' => 'Aţi fost deconectat pentru că sesiunea dvs. a expirat.',
  'LBL_SETTINGS_URL' => 'URL',
  'LBL_SETTINGS_URL_DESC' => 'Folosiţi acest URL atunci când se stabilesc setările de autentificare pentru Sugar Plug-in pentru Microsoft ® Outlook ® şi Sugar Plug-in pentru Microsoft ® Word ®.',
  'LBL_SHOWOPTIONS' => 'Arata optiuni',
  'LBL_SHOW_ON_EMPLOYEES' => 'Afişare Înregistrarea angajaţilor',
  'LBL_SIGNATURE' => 'Semnatura',
  'LBL_SIGNATURES' => 'Semnaturi',
  'LBL_SIGNATURE_DEFAULT' => 'Utilizare semnătură?',
  'LBL_SIGNATURE_HTML' => 'Semnatura HTML',
  'LBL_SIGNATURE_NAME' => 'Nume',
  'LBL_SIGNATURE_PREPEND' => 'Semnatura deasupra raspunsului',
  'LBL_SMTP_SERVER_HELP' => 'Acest SMTP Mail Server poate fi folosit pentru expedierea mesajelor. Furnizaţi un nume de utilizator şi parola pentru contul de e-mail pentru a utiliza serverul de poştă electronică.',
  'LBL_SSL' => 'Utilizati SSL',
  'LBL_SSL_DESC' => 'Utilizaţi Secure Socket Layer la conectarea la serverul de mail.',
  'LBL_STATE' => 'Stat:',
  'LBL_STATUS' => 'Statut',
  'LBL_SUBPANEL_LINKS' => 'Link-uri Subpanou',
  'LBL_SUBPANEL_LINKS_DESCRIPTION' => 'În Vizualizări Detaliu, afişeaza un rând de link-uri de comenzi rapide subpanou.',
  'LBL_SUGAR_LOGIN' => 'Este Utilizator Sugar',
  'LBL_SUPPORTED_THEME_ONLY' => 'afectează numai teme care acceptă această opţiune.',
  'LBL_SWAP_LAST_VIEWED_DESCRIPTION' => 'Afişează bara Ultimele afişate pe laterale, în cazul verificarii. Altfel, se pune deasupra.',
  'LBL_SWAP_LAST_VIEWED_POSITION' => 'Ultimele afişate pe o parte',
  'LBL_SWAP_SHORTCUT_DESCRIPTION' => 'Afişează bara de comenzi rapide în partea de sus, dacă au fost verificate. În caz contrar, se merge pe o parte.',
  'LBL_SWAP_SHORTCUT_POSITION' => 'Comenzi rapide în partea de sus',
  'LBL_SYSTEM_GENERATED_PASSWORD' => 'Parola generata de sistem',
  'LBL_SYSTEM_SIG_DIGITS' => 'Cifre semnificative pentru sistem',
  'LBL_SYSTEM_SIG_DIGITS_DESC' => 'Numărul de zecimale de afişat pentru zecimale şi numere float în sistem, precum moneda şi media în Rapoarte.',
  'LBL_TAB_TITLE_EMAIL' => 'Setari Email',
  'LBL_TAB_TITLE_USER' => 'Setarile utilizatorului',
  'LBL_TEAMS' => 'Echipe',
  'LBL_TEAM_MEMBERSHIP' => 'Membru de echipa',
  'LBL_TEAM_SET' => 'Setare echipa',
  'LBL_TEAM_UPLINE' => 'Rapoartele membrilor',
  'LBL_TEAM_UPLINE_EXPLICIT' => 'Membru',
  'LBL_TEST_BUTTON_KEY' => 't',
  'LBL_TEST_BUTTON_TITLE' => 'Test [Alt+T]',
  'LBL_TEST_SETTINGS' => 'Testare Setari',
  'LBL_TEST_SUCCESSFUL' => 'Conexiunea s-a realizat cu succes.',
  'LBL_THEME' => 'Tema',
  'LBL_THEMEPREVIEW' => 'Previzualizare',
  'LBL_THEME_COLOR' => 'culoare',
  'LBL_THEME_FONT' => 'tip scris',
  'LBL_TIMEZONE' => 'Timpul curent',
  'LBL_TIMEZONE_DST' => 'Economii Daylight',
  'LBL_TIMEZONE_DST_TEXT' => 'Respectaţi Economii Daylight',
  'LBL_TIMEZONE_TEXT' => 'Setaţi fusul orar curent',
  'LBL_TIME_FORMAT' => 'Formatul timpului',
  'LBL_TIME_FORMAT_TEXT' => 'Setaţi formatul de afişare pentru timbre timp',
  'LBL_TITLE' => 'Titlu:',
  'LBL_TLS' => 'Utilizeaza TLS',
  'LBL_TLS_DESC' => 'Utilizeaza Stratul de Transport Securizat atunci cand va conectati la serverul de mail - a se utiliza numai daca serverul dumneavoastra de mail suporta acest protocol.',
  'LBL_TOGGLE_ADV' => 'Arata optiunile avansate',
  'LBL_TOO_MANY_CONCURRENT' => 'Aceasta sesiune sa încheiat, deoarece o altă sesiune a fost început sub acelaşi nume de utilizator.',
  'LBL_UPDATE_FINISH' => 'Actualizare completa',
  'LBL_USER' => 'Utilizatori',
  'LBL_USER_ACCESS' => 'Acces',
  'LBL_USER_HASH' => 'Parola:',
  'LBL_USER_HOLIDAY_SUBPANEL_TITLE' => 'Sarbatori Utilizator',
  'LBL_USER_INFORMATION' => 'Profil utilizator',
  'LBL_USER_LOCALE' => 'Setări Locale',
  'LBL_USER_NAME' => 'Nume Utilizator',
  'LBL_USER_NAME_FOR_ROLE' => 'Utilizatori/Echipe/Roluri',
  'LBL_USER_PREFERENCES' => 'Preferinţe utilizator',
  'LBL_USER_SETTINGS' => 'Setarile utilizatorului',
  'LBL_USER_TYPE' => 'Tipul Utilizatorului',
  'LBL_USE_GROUP_TABS' => 'Module Grupate',
  'LBL_USE_REAL_NAMES' => 'Arată Nume complet',
  'LBL_USE_REAL_NAMES_DESC' => 'Afişează utilizatori "numele şi prenumele în loc de nume de utilizator lor în domenii de atribuire.',
  'LBL_WIZARD_BACK_BUTTON' => 'Inapoi',
  'LBL_WIZARD_FINISH' => 'Faceţi clic pe Finish de mai jos pentru a salva setările şi a începe să utilizaţi Sugar. Pentru mai multe informaţii despre utilizarea zahărului:<br /><br />Universitatea Sugar<br />Utilizatorului final şi administrator de sistem de formare şi Resurse<br />Documentaţia<br />Ghiduri de produse şi Note de lansare<br />Baza de cunoştinţe<br />Sfaturi de la SugarCRM de sprijin pentru îndeplinirea sarcinilor comune şi procese în Sugar<br />Wiki<br />Sfaturi şi răspunsuri din partea Comunităţii Sugar pentru întrebări frecvente<br />Forumuri<br />Forum dedicat comunităţii Sugar pentru a discuta subiecte de interes reciproc şi cu SugarCRM dezvoltatori',
  'LBL_WIZARD_FINISH1' => 'Ce doriţi să faceţi în continuare?',
  'LBL_WIZARD_FINISH10' => 'Utilizaţi Studio pentru a crea şi a gestiona domenii de aplicare şi machete.',
  'LBL_WIZARD_FINISH11' => 'Viziteaza Universitatea Sugar',
  'LBL_WIZARD_FINISH12' => 'Găsiţi materiale de instruire şi de clase, care va vor ajuta sa a început ca un administrator de sistem sau un utilizator final a cererii.',
  'LBL_WIZARD_FINISH14' => 'Documentatie',
  'LBL_WIZARD_FINISH15' => 'Ghidul de produse şi Note de lansare',
  'LBL_WIZARD_FINISH16' => 'Baza de cunoştinţe',
  'LBL_WIZARD_FINISH17' => 'Sfaturi de la Asistenţă SugarCRM pentru efectuarea sarcinilor comune şi procesele din Sugar',
  'LBL_WIZARD_FINISH18' => 'Forum',
  'LBL_WIZARD_FINISH19' => 'Forum dedicat comunităţii Sugar pentru a discuta subiecte de interes reciproc şi cu dezvoltator SugarCRM',
  'LBL_WIZARD_FINISH2' => 'Start utilizare Sugar',
  'LBL_WIZARD_FINISH2DESC' => 'Du-te direct la pagina de start',
  'LBL_WIZARD_FINISH3' => 'Import Data',
  'LBL_WIZARD_FINISH4' => 'mportul datelor din surse externe în aplicare.',
  'LBL_WIZARD_FINISH5' => 'Creaza Utlizatori',
  'LBL_WIZARD_FINISH6' => 'Creaza noi conturi de utilizator pentru oameni pentru a accesa aplicaţia.',
  'LBL_WIZARD_FINISH7' => 'Vezi si manageriaza setarile aplicatiei',
  'LBL_WIZARD_FINISH8' => 'Gestionaţi setări avansate, inclusiv setările aplicaţiei implicite.',
  'LBL_WIZARD_FINISH9' => 'Configurati Aplicatia',
  'LBL_WIZARD_FINISH_BUTTON' => 'Sfarsit',
  'LBL_WIZARD_FINISH_TAB' => 'Sfarsit',
  'LBL_WIZARD_FINISH_TITLE' => 'Sunteti pregatit sa folositi Sugar!',
  'LBL_WIZARD_LOCALE' => 'Locatia Dumneavoastră',
  'LBL_WIZARD_LOCALE_DESC' => 'Specifica fusul orar şi modul în care doriţi date, monedele şi numele să apară în Sugar.',
  'LBL_WIZARD_NEXT_BUTTON' => 'Urmatorul>',
  'LBL_WIZARD_PERSONALINFO' => 'Informaţiile dvs.',
  'LBL_WIZARD_PERSONALINFO_DESC' => 'Furnizarea de informaţii despre tine. Informaţiile pe care le furnizati despre dumneavoastra vor fi vizibile pentru alţi utilizatori de Sugar.<br />Campurile marcate cu * sunt obligatorii',
  'LBL_WIZARD_SKIP_BUTTON' => 'sari peste',
  'LBL_WIZARD_SMTP' => 'Contul dumneavoastra de Email-ul dvs.',
  'LBL_WIZARD_SMTP_DESC' => 'Furnizaţi numele dvs. de cont de email şi parola pentru serverul de e-mail implicit outbound.',
  'LBL_WIZARD_TITLE' => 'Utilizator Vrajitor',
  'LBL_WIZARD_WELCOME' => 'Faceţi clic pe Următorul pentru a configura câteva setări de bază pentru utilizarea Sugar.',
  'LBL_WIZARD_WELCOME_NOSMTP' => 'Faceţi clic pe Următorul pentru a configura câteva setări de bază pentru utilizarea Sugar.',
  'LBL_WIZARD_WELCOME_TAB' => 'Bun venit',
  'LBL_WIZARD_WELCOME_TITLE' => 'Bine aţi venit la Sugar!',
  'LBL_WORKSHEETS' => 'Foi de lucru',
  'LBL_WORK_PHONE' => 'Telefon Serviciu:',
  'LBL_YAHOOMAIL_SMTPPASS' => 'Parola Yahoo!Mail',
  'LBL_YAHOOMAIL_SMTPUSER' => 'ID Yahoo!Mail',
  'LBL_YOUR_PUBLISH_URL' => 'Publică la locaţia mea',
  'LBL_YOUR_QUERY_URL' => 'URL-ul de interogare',
  'LNK_EDIT_TABS' => 'Editeaza fila',
  'LNK_IMPORT_USERS' => 'Import utilizatori',
  'LNK_NEW_GROUP_USER' => 'Creaţi User Group',
  'LNK_NEW_PORTAL_USER' => 'Creaţi utilizator API Portal',
  'LNK_NEW_USER' => 'Creaza utilizator nou',
  'LNK_REASSIGN_RECORDS' => 'Realocaţi inregistrari',
  'LNK_USER_LIST' => 'Vezi utilizatori',
  'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Esti sigura ca vrei sa stergi acest angajat \\ membru?',
);

