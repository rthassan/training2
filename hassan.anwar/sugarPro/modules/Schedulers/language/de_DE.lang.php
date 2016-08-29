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
  'ERR_CRON_SYNTAX' => 'Ungültige Cron Syntax',
  'ERR_DELETE_RECORD' => 'Zum Löschen des Plans muss eine Datensatznummer angegeben werden.',
  'LBL_ADV_OPTIONS' => 'Erw. Optionen',
  'LBL_ALL' => 'Jeden Tag',
  'LBL_ALWAYS' => 'Immer',
  'LBL_AND' => 'und',
  'LBL_ASYNCMASSUPDATE' => 'Asynchrone Massen Aktualisierungen',
  'LBL_AT' => 'um',
  'LBL_AT_THE' => 'Am',
  'LBL_BASIC_OPTIONS' => 'Basis Setup',
  'LBL_CATCH_UP' => 'Ausführen wenn versäumt',
  'LBL_CATCH_UP_WARNING' => 'Deaktivieren, wenn der Lauf dieses Jobs mehr als einen Moment dauert.',
  'LBL_CLEANJOBQUEUE' => 'Cleanup Job Queue',
  'LBL_CLEANOLDRECORDLISTS' => 'Alte Datensatzlisten bereinigen',
  'LBL_CRONTAB_EXAMPLES' => 'Das oben stehende verwendet standard Crontab Notation.',
  'LBL_CRONTAB_SERVER_TIME_POST' => '). Bitte die Zeitplaner Ausführungszeit definieren.',
  'LBL_CRONTAB_SERVER_TIME_PRE' => 'Die cron Spezifikationen laufen über die Server Zeitzone (',
  'LBL_CRON_INSTRUCTIONS_LINUX' => 'Konfiguration eines crontab Eintrages',
  'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Konfiguration des Windows Terminplaners',
  'LBL_CRON_LINUX_DESC' => 'Fügen Sie diese Zeile Ihrem Crontab hinzu:',
  'LBL_CRON_WINDOWS_DESC' => 'Erstellen Sie eine Batch-Datei mt den folgenden Befehlen:',
  'LBL_DATE_TIME_END' => 'Enddatum &  Zeit',
  'LBL_DATE_TIME_START' => 'Startdatum & Zeit',
  'LBL_DAY_OF_MONTH' => 'Datum',
  'LBL_DAY_OF_WEEK' => 'Tag',
  'LBL_EVERY' => 'alle',
  'LBL_EVERY_DAY' => 'Jeden Tag',
  'LBL_EXECUTE_TIME' => 'Ausführungszeit',
  'LBL_FRI' => 'Freitag',
  'LBL_FROM' => 'Von',
  'LBL_HOUR' => 'Stunden',
  'LBL_HOURS' => 'h',
  'LBL_HOUR_SING' => 'Stunde',
  'LBL_IN' => 'in',
  'LBL_INTERVAL' => 'Intervall',
  'LBL_JOB' => 'Job',
  'LBL_JOBS_SUBPANEL_TITLE' => 'Aktive Jobs',
  'LBL_JOB_URL' => 'Job URL',
  'LBL_LAST_RUN' => 'Letzte erfolgreiche Durchführung',
  'LBL_LIST_EXECUTE_TIME' => 'Wird gestartet am:',
  'LBL_LIST_JOB_INTERVAL' => 'Intervall:',
  'LBL_LIST_LIST_ORDER' => 'Geplante Aufgaben:',
  'LBL_LIST_NAME' => 'Geplante Aufgabe:',
  'LBL_LIST_RANGE' => 'Bereich:',
  'LBL_LIST_REMOVE' => 'Entfernen:',
  'LBL_LIST_STATUS' => 'Status:',
  'LBL_LIST_TITLE' => 'Aufgaben Liste:',
  'LBL_MINS' => 'min',
  'LBL_MINUTES' => 'Minuten',
  'LBL_MIN_MARK' => 'Minuten nach',
  'LBL_MODULE_NAME' => 'Schedulers',
  'LBL_MODULE_NAME_SINGULAR' => 'Sugar Planer',
  'LBL_MODULE_TITLE' => 'Geplante Aufgaben',
  'LBL_MON' => 'Montag',
  'LBL_MONTH' => 'Monat',
  'LBL_MONTHS' => 'Monat',
  'LBL_NAME' => 'Job Name',
  'LBL_NEVER' => 'Nie',
  'LBL_NEW_FORM_TITLE' => 'Neuer Termin',
  'LBL_NO_PHP_CLI' => 'Wenn Ihr Host keine PHP-Binary zur Verfügung stellt, können Sie Ihre Jobs mittels wget oder curl starten:<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose http://translate.sugarcrm.com/soon/latest/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent http://translate.sugarcrm.com/soon/latest/cron.php > /dev/null 2>&1',
  'LBL_OFTEN' => 'So oft wie möglich.',
  'LBL_ON_THE' => 'Um',
  'LBL_OOTB_BOUNCE' => 'Unzustellbare Kampagnen E-Mails verarbeiten (Nacht)',
  'LBL_OOTB_CAMPAIGN' => 'Kampagnen-Massenmails versenden (Nacht)',
  'LBL_OOTB_CLEANUP_QUEUE' => 'Clean Jobs Queue',
  'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Zukünftige Zeitspanen anlegen',
  'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
  'LBL_OOTB_IE' => 'Eingehende Mailkonten überprüfen',
  'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Process Author geplanter Job',
  'LBL_OOTB_PRUNE' => 'Datenbank am 1. des Monats säubern',
  'LBL_OOTB_PRUNE_RECORDLISTS' => 'Alte Datensatzlisten kürzen',
  'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Diagnose-Tool-Dateien entfernen',
  'LBL_OOTB_REMOVE_PDF_FILES' => 'Temporäre PDF-Dateien entfernen',
  'LBL_OOTB_REMOVE_TMP_FILES' => 'Temporäre Dateien entfernen',
  'LBL_OOTB_REPORTS' => 'Berichte Aufgaben verarbeiten',
  'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'E-Mail Erinnerungsbenachrichtigungen ausführen',
  'LBL_OOTB_TRACKER' => 'Userhistorie am 1. des Monats säubern',
  'LBL_OOTB_WORKFLOW' => 'Workflow Aufgaben verarbeiten',
  'LBL_PERENNIAL' => 'andauernd',
  'LBL_PERFORMFULLFTSINDEX' => 'Volltext Suche Index System',
  'LBL_PMSEENGINECRON' => 'Prozessautor-Planer',
  'LBL_POLLMONITOREDINBOXES' => 'Checke einlaufende Mail Accounts',
  'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Run Nightly Process Bounced Campaign Emails',
  'LBL_PROCESSQUEUE' => 'Run Report Generation Scheduled Tasks',
  'LBL_PROCESSWORKFLOW' => 'Process Workflow Tasks',
  'LBL_PRUNEDATABASE' => 'Prune Database on 1st of Month',
  'LBL_RANGE' => 'an',
  'LBL_REFRESHJOBS' => 'Aktualisiere Jobs',
  'LBL_RUNMASSEMAILCAMPAIGN' => 'Run Nightly Mass Email Campaigns',
  'LBL_SAT' => 'Samstag',
  'LBL_SCHEDULER' => 'Geplante Aufgabe:',
  'LBL_SEARCH_FORM_TITLE' => 'Geplante Aufgabe Suche',
  'LBL_SENDEMAILREMINDERS' => 'E-Mail Erinnerungsbenachrichtigungen ausführen',
  'LBL_STATUS' => 'Status',
  'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Zukünftige Zeitspanen anlegen',
  'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
  'LBL_SUN' => 'Sonntag',
  'LBL_THU' => 'Donnerstag',
  'LBL_TIME_FROM' => 'Aktiv von',
  'LBL_TIME_TO' => 'Aktiv bis',
  'LBL_TOGGLE_ADV' => 'Erw. Optionen',
  'LBL_TOGGLE_BASIC' => 'Basisoptionen',
  'LBL_TRIMTRACKER' => 'Prune Tracker Tables',
  'LBL_TUE' => 'Dienstag',
  'LBL_UPDATETRACKERSESSIONS' => 'Update Tracker Session Tables',
  'LBL_UPDATE_TRACKER_SESSIONS' => 'Update tracker_sessions Tabelle',
  'LBL_WARN_CURL' => 'Warnung:',
  'LBL_WARN_CURL_TITLE' => 'cURL Warnung:',
  'LBL_WARN_NO_CURL' => 'In diesem System sind die cURL Bibliotheken im PHP Modul nicht aktiviert (--with-curl=/pfad/zu/curl_library). Bitte kontaktieren Sie den Administrator zur Lösung dieses Problems. Ohne diese Funktionalität kann der Zeitplaner die Jobs nicht einreihen.',
  'LBL_WED' => 'Mittwoch',
  'LNK_LIST_SCHEDULED' => 'Geplante Jobs',
  'LNK_LIST_SCHEDULER' => 'Geplante Aufgaben',
  'LNK_NEW_SCHEDULER' => 'Neue Aufgabe',
  'NTC_DELETE_CONFIRMATION' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen wollen?',
  'NTC_LIST_ORDER' => 'Setzen Sie die Reihenfolge, in der dieser Plan in der Terminplaner Auswahlliste erscheinen soll.',
  'NTC_STATUS' => 'Zum Entfernen dieses Plans von der Terminplaner Auswahlliste setzen Sie den Status auf inaktiv',
  'SOCK_GREETING' => 'Dies ist die Oberfläche für die Services des Sugar Zeitplaners. <br />[ Verfügbare daemon Kommandos: start|restart|shutdown|status ]<br />Um zu verlassen, tippen Sie  &#39;quit&#39;. Um das Service herunterzufahren &#39;shutdown&#39;.',
);

