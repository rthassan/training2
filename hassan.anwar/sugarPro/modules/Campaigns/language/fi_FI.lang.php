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
  'ERR_FIX_MESSAGES' => 'Korjaa seuraavat virheet ennen kuin jatkat',
  'ERR_INT_ONLY_EMAIL_PER_RUN' => 'Lähetettyjen sähköpostien lukumäärä erää kohti voi olla vain kokonaisluku.',
  'ERR_MESS_DUPLICATE_FOR_LIST' => 'Tälle kohdeluettelolle löytyi useita sähköpostimarkkinointiviestejä',
  'ERR_MESS_NOT_FOUND_FOR_LIST' => 'Sähköpostimarkkinointiviestiä ei löytynyt tälle kohdeluettelolle',
  'ERR_NO_EMAIL_MARKETING' => 'On oltava vähintään yksi aktiivinen sähköpostimarkkinointiviesti liitettynä kampanjaan.',
  'ERR_NO_MAILBOX' => 'Seuraavia markkinointiviestejä ei ole liitetty viestitiliin.<br />Korjaa tämä ennen jatkamista.',
  'ERR_NO_OPTS_SAVED' => 'Optimeita ei tallennettu sisääntulevan sähköpostin tilin kanssa.',
  'ERR_NO_TARGET_LISTS' => 'On oltava vähintään yksi kohdeluettelo liitettynä kampanjan.',
  'ERR_NO_TEST_TARGET_LISTS' => 'On oltava vähintään yksi kohdeluettelo jonka tyyppinä on Test liitettynä kampanjan.',
  'ERR_REVIEW_EMAIL_SETTINGS' => 'Tarkista sisääntulevan sähköpostin asetukset.',
  'ERR_SENDING_NOW' => 'Viestejä toimitetaan, kokeile tätä myöhemmin.',
  'LBL_ACCOUNTS' => 'Asiakkaat',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Asiakkaat',
  'LBL_ADD_TARGET' => 'Lisää',
  'LBL_ADD_TRACKER' => 'Luo seuraaja',
  'LBL_ALERT' => 'Huomautus',
  'LBL_ALL_PROSPECT_LISTS' => 'Kaikki tavoitelistat kampanjassa.',
  'LBL_ALREADY_SUBSCRIBED_HEADER' => 'Uutiskirjeet tilannut',
  'LBL_AMOUNT_IN' => 'Määrä',
  'LBL_ASSIGNED_TO' => 'Vastuuhenkilö:',
  'LBL_ASSIGNED_TO_ID' => 'Vastuuhenkilö:',
  'LBL_ASSIGNED_TO_NAME' => 'Vastuuhenkilö',
  'LBL_AVALAIBLE_FIELDS_HEADER' => 'Käytettävissä olevat kentät',
  'LBL_BACK_TO_CAMPAIGNS' => 'Takaisin kampanioihin',
  'LBL_BLOCKED_SUBPANEL_TITLE' => 'Estetty',
  'LBL_CAMPAIGN' => 'Kampanja:',
  'LBL_CAMPAIGN_ACCOUNTS_SUBPANEL_TITLE' => 'Asiakkaat',
  'LBL_CAMPAIGN_ACTUAL_COST' => 'Todelliset kulut:',
  'LBL_CAMPAIGN_BUDGET' => 'Budjetti:',
  'LBL_CAMPAIGN_CONTENT' => 'Kuvaus:',
  'LBL_CAMPAIGN_COST_PER_CLICK_THROUGH' => 'Hinta per klikkaus:',
  'LBL_CAMPAIGN_COST_PER_IMPRESSION' => 'Hinta per vaikutus:',
  'LBL_CAMPAIGN_DAYS_REMAIN' => 'Päiviä jäljellä',
  'LBL_CAMPAIGN_DIAGNOSTICS' => 'Kampanjadiagnostiikka',
  'LBL_CAMPAIGN_END_DATE' => 'Päättymispäivä:',
  'LBL_CAMPAIGN_EXPECTED_COST' => 'Odotetut kulut:',
  'LBL_CAMPAIGN_EXPECTED_REVENUE' => 'Odotetut tulot:',
  'LBL_CAMPAIGN_FREQUENCY' => 'Taajuus:',
  'LBL_CAMPAIGN_IMPRESSIONS' => 'Vaikutukset:',
  'LBL_CAMPAIGN_INACTIVE_SCHEDULE' => 'Kampanja ‘{0}’ on epäaktiivinen. Joudut asettamaan kampanjan aktiiviseksi.',
  'LBL_CAMPAIGN_INFORMATION' => 'Yleiskatsaus',
  'LBL_CAMPAIGN_LEAD_SUBPANEL_TITLE' => 'Liidit',
  'LBL_CAMPAIGN_NAME' => 'Nimi:',
  'LBL_CAMPAIGN_NOT_SELECTED' => 'Valitse ja assosioi kampanja:',
  'LBL_CAMPAIGN_OBJECTIVE' => 'Tavoite:',
  'LBL_CAMPAIGN_OPPORTUNITIES_WON' => 'Voitetut mahdollisuudet:',
  'LBL_CAMPAIGN_RESPONSE_BY_RECIPIENT_ACTIVITY' => 'Kampanjan vastaus vastaanottajan toiminnan mukaan',
  'LBL_CAMPAIGN_RETURN_ON_INVESTMENT' => 'Kampanjan Return On Investment',
  'LBL_CAMPAIGN_START_DATE' => 'Aloituspäivä:',
  'LBL_CAMPAIGN_STATUS' => 'Status:',
  'LBL_CAMPAIGN_TYPE' => 'Tyyppi:',
  'LBL_CAMPAIGN_WIZARD' => 'Kampanjatyökalu',
  'LBL_CAMPAIGN_WIZARD_START_TITLE' => 'Muokkaa kampanjaa:',
  'LBL_CAMP_MESSAGE_COPY' => 'Pidä kopiot kampanjaviesteistä:',
  'LBL_CAMP_MESSAGE_COPY_DESC' => 'Haluatko tallentaa kopion <strong>JOKAISESTA</strong> kampanjaviestistä? <strong>Oletuksena ja suosituksena on ei.</strong> Valitsemalla ei tallennetaan vain viestin malli ja tarvittavat muuttujat yksittäisten viestien luomiseen.',
  'LBL_CHARSET_NOTICE' => 'HUOMAUTUS: Varmista, että sivu, joka sisältää netistä liidiksi -lomakkeen sisältää seuraavat rivit &lt;head&gt;-osiossa:',
  'LBL_CHOOSE_CAMPAIGN_TYPE' => 'Kampanjan tyyppi',
  'LBL_CHOOSE_NEXT_STEP' => 'Valitse seuraava askel',
  'LBL_CONFIRM' => 'Aloita',
  'LBL_CONFIRM_CAMPAIGN_SAVE_CONTINUE' => 'Tallenna työt ja jatka markkinointisähköpostiin.',
  'LBL_CONFIRM_CAMPAIGN_SAVE_EXIT' => 'Haluatko tallentaa tiedot ja poistua?',
  'LBL_CONFIRM_CAMPAIGN_SAVE_OPTIONS' => 'Tallenna asetukset',
  'LBL_CONFIRM_SEND_SAVE' => 'Aiot lähteä ja jatkaa kampanjaviestien lähetys -sivulle. Tallennetaanko ja jatketaanko?',
  'LBL_CONTACTS' => 'Kontaktit',
  'LBL_CONTACT_SUBPANEL_TITLE' => 'Kontaktit',
  'LBL_COPY_AND_PASTE_CODE' => 'Tai kopioi ja liitä alla oleva HTML osaksi olemassa sivua',
  'LBL_COPY_OF' => 'Kopio',
  'LBL_CREATED' => 'Luonut',
  'LBL_CREATED_BY' => 'Luonut:',
  'LBL_CREATED_USER' => 'Luoja',
  'LBL_CREATE_EMAIL' => 'Luo sähköposti',
  'LBL_CREATE_EMAIL_TEMPLATE' => 'Luo',
  'LBL_CREATE_MAILBOX' => 'Luo uusi sähköpostitili',
  'LBL_CREATE_NEWSLETTER' => 'Luo uutiskirje',
  'LBL_CREATE_NEW_MARKETING_EMAIL' => 'Luo uusi markkinointisähköposti',
  'LBL_CREATE_TARGET' => 'Luo',
  'LBL_CREATE_WEB_TO_LEAD_FORM' => 'Luo webistä liidiksi -lomake',
  'LBL_CURRENCY' => 'Valuutta:',
  'LBL_CURRENCY_ID' => 'Valuutta ID',
  'LBL_CURRENCY_RATE' => 'Valuuttakurssi',
  'LBL_CUSTOM_LOCATION' => 'Käyttäjän määrittämä',
  'LBL_DATE_CREATED' => 'Luotu:',
  'LBL_DATE_ENTERED' => 'Luontipäivä',
  'LBL_DATE_LAST_MODIFIED' => 'Muokattu:',
  'LBL_DATE_MODIFIED' => 'Edellinen muokkaus:',
  'LBL_DATE_START' => 'Aloituspäivä',
  'LBL_DAY' => 'Päivä',
  'LBL_DEFAULT' => 'Kaikki kohdeluettelot',
  'LBL_DEFAULT_FROM_ADDR' => 'Oletus:',
  'LBL_DEFAULT_LEAD_SUBMIT' => 'Lähetä',
  'LBL_DEFAULT_LIST_ENTRIES_NOT_FOUND' => 'Merkintöjä ei löytynyt',
  'LBL_DEFAULT_LIST_ENTRIES_WERE_PROCESSED' => 'Merkinnät käsiteltiin',
  'LBL_DEFAULT_LIST_NOT_FOUND' => 'Ei löydetty kohdelistaa jonka tyyppinä olisi default',
  'LBL_DEFAULT_LOCATION' => 'Oletus',
  'LBL_DEFAULT_SUBPANEL_TITLE' => 'Kampanjat',
  'LBL_DEFINE_LEAD_HEADER' => 'Lomakkeen ylätunniste:',
  'LBL_DEFINE_LEAD_POST_URL' => 'Postin URL:',
  'LBL_DEFINE_LEAD_REDIRECT_URL' => 'Uudelleenohjaus-URL:',
  'LBL_DEFINE_LEAD_SUBMIT' => 'Lähetä-painikkeen selite:',
  'LBL_DELETE' => 'Poista',
  'LBL_DELETE_INLINE' => 'Poista',
  'LBL_DESCRIPTION_LEAD_FORM' => 'Lomakkeen kuvaus:',
  'LBL_DESCRIPTION_TEXT_LEAD_FORM' => 'Tämän lomakkeen lähtettäminen luo liidin ja linkin kampanjaan',
  'LBL_DIAGNOSTIC' => 'Diagnostiikka',
  'LBL_DIAGNOSTIC_WIZARD' => 'Näytä diagnostiikka',
  'LBL_DOWNLOAD_TEXT_WEB_TO_LEAD_FORM' => 'Lataa webistä liidiksi -lomakkeesi',
  'LBL_DOWNLOAD_WEB_TO_LEAD_FORM' => 'Webistä liidiksi -lomake',
  'LBL_DRAG_DROP_COLUMNS' => 'Vedä ja pudota liidin kentät sarakkeessa 1 & 2',
  'LBL_EDIT_EMAIL_TEMPLATE' => 'Muokkaa',
  'LBL_EDIT_EXISTING' => 'Muokkaa kampanjaa',
  'LBL_EDIT_INLINE' => 'Muokkaa',
  'LBL_EDIT_LAYOUT' => 'Muokkaa asettelua',
  'LBL_EDIT_LEAD_POST_URL' => 'Muokkaa postin URL:ää?',
  'LBL_EDIT_OPT_OUT' => 'Opt-out -linkki:',
  'LBL_EDIT_OPT_OUT_' => 'Opt-out -linkki?',
  'LBL_EDIT_TARGET_LIST' => 'Muokkaa kohdeluetteloa',
  'LBL_EDIT_TRACKER_NAME' => 'Seuraajan nimi:',
  'LBL_EDIT_TRACKER_URL' => 'Seuraajan URL:',
  'LBL_ELECTED_TO_OPTOUT' => 'Olet päättänyt kieltäytyä sähköpostien vastaanottamisesta.',
  'LBL_EMAIL' => 'Sähköposti',
  'LBL_EMAILMARKETING_SUBPANEL_TITLE' => 'Sähköpostimarkkinointi',
  'LBL_EMAILS_PER_RUN' => 'Lähetettyjen sähköpostien lukumäärä erää kohti:',
  'LBL_EMAILS_SCHEDULED' => 'Ajastetut sähköpostiviestit',
  'LBL_EMAIL_CAMPAIGNS_TITLE' => 'Sähköpostikampanjat',
  'LBL_EMAIL_COMPONENTS' => 'Sähköpostikomponentit',
  'LBL_EMAIL_MARKETING_SUBPANEL_TITLE' => 'Sähköpostimarkkinointi',
  'LBL_EMAIL_SETUP_DESC' => 'Täytä alla oleva lomake järjestelmäasetusten muokkaukseksi niin, että kampanjaviestejä voi lähettää.',
  'LBL_EMAIL_SETUP_WIZ' => 'Käynnistä sähköpostin konfigurointi',
  'LBL_EMAIL_SETUP_WIZARD' => 'Määritä sähköposti',
  'LBL_EMAIL_SETUP_WIZARD_TITLE' => 'Kampanjasähköpostin konfigurointi',
  'LBL_EMAIL_TITLE' => 'Kampanjat: sähköpostit',
  'LBL_END_DATE' => 'Päättymispäivä',
  'LBL_FILTER_CHART_BY' => 'Suodata kaavio:',
  'LBL_FINISH' => 'Lopeta',
  'LBL_FROM_ADDR' => 'Lähettäjän osoite',
  'LBL_FROM_MAILBOX_NAME' => 'Käytä sähköpostitiliä:',
  'LBL_FROM_NAME' => 'Lähettäjän nimi:',
  'LBL_HOME_START_MESSAGE' => 'Valitse minkä tyyppisen kampanjan haluat luoda.',
  'LBL_IMPORT_PROSPECTS' => 'Tuo tavoitteet',
  'LBL_INVALID' => 'Virheellinen',
  'LBL_INVALID EMAIL_SUBPANEL_TITLE' => 'Virheellinen sähköposti',
  'LBL_INVITEE' => 'Kontaktit',
  'LBL_LEADS' => 'Liidit',
  'LBL_LEADS_DELETED_SINCE_CREATED' => '{0} tämän kampanjan kautta luotua liidiä on poistettu kampanjan luonnista lähtien.',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Liidit',
  'LBL_LEAD_DEFAULT_HEADER' => 'Webistä liidiksi -lomake kampanjalle',
  'LBL_LEAD_FOOTER' => 'Lomakkeen alatunniste:',
  'LBL_LEAD_FORM_FIRST_HEADER' => 'Liidilomake (ensimmäinen sarake)',
  'LBL_LEAD_FORM_SECOND_HEADER' => 'Liidilomake (toinen sarake)',
  'LBL_LEAD_FORM_WIZARD' => 'Liidilomaketyökalu',
  'LBL_LEAD_MODULE' => 'Liidit',
  'LBL_LEAD_NOTIFY_CAMPAIGN' => 'Liittyvä kampanja:',
  'LBL_LEAD_SUBPANEL_TITLE' => 'Liidi',
  'LBL_LINK_SUBPANEL_TITLE' => 'Linkki',
  'LBL_LIST_CAMPAIGN_NAME' => 'Kampanja',
  'LBL_LIST_END_DATE' => 'Päättymispäivä',
  'LBL_LIST_FORM_TITLE' => 'Kampanjaluettelo',
  'LBL_LIST_NAME' => 'Nimi',
  'LBL_LIST_START_DATE' => 'Aloituspäivä',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_LIST_TO_ACTIVITY' => 'Näytä status',
  'LBL_LIST_TYPE' => 'Tyyppi',
  'LBL_LOCATION_TRACK' => 'Kampanjanseurantatiedostojen (kuten campaign_tracker.php) sijainti:',
  'LBL_LOGIN' => 'Käyttäjänimi',
  'LBL_LOG_ENTRIES' => 'Lokimerkinnät',
  'LBL_LOG_ENTRIES_BLOCKEDD_TITLE' => 'Estetty domainin tai osoitteen takia',
  'LBL_LOG_ENTRIES_CONTACT_TITLE' => 'Kontaktit luotu',
  'LBL_LOG_ENTRIES_INVALID_EMAIL_TITLE' => 'Palautetut viestit, virheellinen sähköpostiosoite',
  'LBL_LOG_ENTRIES_LEAD_TITLE' => 'Liidit luotu',
  'LBL_LOG_ENTRIES_LINK_TITLE' => 'Klikkasi linkkiä',
  'LBL_LOG_ENTRIES_REMOVED_TITLE' => 'Jättäytyneet',
  'LBL_LOG_ENTRIES_SEND_ERROR_TITLE' => 'Palautetut viestit, muut',
  'LBL_LOG_ENTRIES_TARGETED_TITLE' => 'Viesti lähetetty/yritetty',
  'LBL_LOG_ENTRIES_TITLE' => 'Vastaukset',
  'LBL_LOG_ENTRIES_VIEWED_TITLE' => 'Avasi viestin',
  'LBL_MAILBOX' => 'Valvotut -kansio',
  'LBL_MAILBOX_CHECK1_BAD' => 'Ei havaittu sähköpostitilejä joissa olisi bounce-käsittely.',
  'LBL_MAILBOX_CHECK1_GOOD' => 'Sähköpostitilit joissa havaittiin bounce-käsittely:',
  'LBL_MAILBOX_CHECK2_BAD' => 'Määritä järjestelmän sähköpostiosoite. Sähköpostiasetuksia ei ole määritetty tai niissä on virheitä.',
  'LBL_MAILBOX_CHECK2_GOOD' => 'Sähköpostiasetukset on määritetty:',
  'LBL_MAILBOX_CHECK_WIZ_BAD' => 'Ei havaittu sähköpostitilejä joissa olisi bounce-käsittely. Luo uusi alla.',
  'LBL_MAILBOX_CHECK_WIZ_GOOD' => 'Havaittiin sähköpostitili jossa on bounce-käsittely. Sinun ei tarvitse luoda uutta tiliä, mutta niin voi tehdä alla.',
  'LBL_MAILBOX_DEFAULT' => 'INBOX',
  'LBL_MAILBOX_NAME' => 'Postitilin nimi:',
  'LBL_MAIL_SENDTYPE' => 'Mail Transfer Agent:',
  'LBL_MAIL_SMTPAUTH_REQ' => 'Käytä SMTP-todennusta?',
  'LBL_MAIL_SMTPPASS' => 'SMTP Salasana:',
  'LBL_MAIL_SMTPPORT' => 'SMTP-portti',
  'LBL_MAIL_SMTPSERVER' => 'SMTP-palvelin',
  'LBL_MAIL_SMTPUSER' => 'SMTP-käyttäjänimi',
  'LBL_MANAGE_SUBSCRIPTIONS_TITLE' => 'Hallitse tilauksia',
  'LBL_MARKETING_CHECK1_BAD' => 'Ei havaittu sähköpostimarkkinointikomponentteja. Sinun pitää luoda yksi kampanjan postittamista varten.',
  'LBL_MARKETING_CHECK1_GOOD' => 'Sähköpostimarkkinointikomponentteja havaittu.',
  'LBL_MARKETING_CHECK2_BAD' => 'Ei havaittu kohdeluetteloja. Sinun pitää luoda yksi haluamaltasi kampanjan näytöltä.',
  'LBL_MARKETING_CHECK2_GOOD' => 'Kohdeluetteloja havaittu.',
  'LBL_MARK_AS_SENT' => 'Merkitse lähetetyiksi',
  'LBL_MASS_MAILING_TITLE' => 'Massapostituksen valinnat',
  'LBL_MESSAGE_FOR' => 'Lähetä tämä viesti:',
  'LBL_MESSAGE_QUEUE_TITLE' => 'Viestijono',
  'LBL_MODIFIED' => 'Muokannut',
  'LBL_MODIFIED_BY' => 'Muokkaaja:',
  'LBL_MODIFIED_USER' => 'Käyttäjä muokannut',
  'LBL_MODULE_NAME' => 'Kampanjat',
  'LBL_MODULE_NAME_SINGULAR' => 'Kampanja',
  'LBL_MODULE_TITLE' => 'Kampanjat: Etusivu',
  'LBL_MONTH' => 'Kuukausi',
  'LBL_MORE_DETAILS' => 'Lisätietoja',
  'LBL_MRKT_NAME' => 'Nimi',
  'LBL_NAME' => 'Nimi',
  'LBL_NAVIGATION_MENU_GEN1' => 'Kampanjan ylätunniste',
  'LBL_NAVIGATION_MENU_GEN2' => 'Budjetti',
  'LBL_NAVIGATION_MENU_MARKETING' => 'Markkinointi',
  'LBL_NAVIGATION_MENU_NEW_MAILBOX' => 'Uusi sähköpostitili',
  'LBL_NAVIGATION_MENU_SEND_EMAIL' => 'Lähetä sähköpostia',
  'LBL_NAVIGATION_MENU_SETUP' => 'Konfiguroi sähköposti',
  'LBL_NAVIGATION_MENU_SUBSCRIPTIONS' => 'Tilaukset',
  'LBL_NAVIGATION_MENU_SUMMARY' => 'Yhteenveto',
  'LBL_NAVIGATION_MENU_TRACKERS' => 'Seuraajat',
  'LBL_NEWSLETTER' => 'Uutiskirje',
  'LBL_NEWSLETTER WIZARD_TITLE' => 'Uutiskirje:',
  'LBL_NEWSLETTERS' => 'Näytä uutiskirjeet',
  'LBL_NEWSLETTER_FORENTRY' => 'Uutiskirje',
  'LBL_NEWSLETTER_LIST_FORM_TITLE' => 'Uutiskirjeluettelo',
  'LBL_NEWSLETTER_TITLE' => 'Kampanjat: Uutiskirjeet',
  'LBL_NEWSLETTER_WIZARD_START_TITLE' => 'Muokkaa uutiskirjettä:',
  'LBL_NEW_FORM_TITLE' => 'Uusi kampanja',
  'LBL_NO' => 'Ei',
  'LBL_NONE' => 'tyhjä',
  'LBL_NON_ADMIN_ERROR_MSG' => 'Ilmoitathan järjestelmänvalvojallesi, jotta he voivat korjata tämän ongelman.',
  'LBL_NOTIFY_TITLE' => 'Sähköpostitiedottamisen asetukset',
  'LBL_NOT_VALID_EMAIL_ADDRESS' => 'Kelvoton sähköpostiosoite',
  'LBL_NO_SUBS_ENTRIES_WARNING' => 'Et voi lähettää markkinointisähköpostia kunnes tilauslistassa on ainakin yksi merkintä.  Voit täyttää listan jälkeenpäin.',
  'LBL_NO_TARGETS_WARNING' => 'Et voi lähettää markkinointisähköpostia ellei kampanjalla ole ainakin yhtä kohdeluetteloa.',
  'LBL_NO_TARGET_ENTRIES_WARNING' => 'Et voi lähettää markkinointisähköpostia kunnes kohdeluettelossa on ainakin yksi merkintä.  Voit täyttää luettelon jälkeenpäin.',
  'LBL_OPPORTUNITIES' => 'Myyntimahdollisuudet',
  'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => 'Myyntimahdollisuudet',
  'LBL_OPPORTUNITY_SUBPANEL_TITLE' => 'Myyntimahdollisuudet',
  'LBL_OTHER_TYPE_CAMPAIGN' => 'Sähköpostiin perustumaton kampanja',
  'LBL_PASSWORD' => 'Salasana',
  'LBL_PORT' => 'Postipalvelimen portti',
  'LBL_PROSPECTLISTS_SUBPANEL_TITLE' => 'Prospektilista',
  'LBL_PROSPECT_LIST_SUBPANEL_TITLE' => 'Kohdeluettelo',
  'LBL_PROVIDE_WEB_TO_LEAD_FORM_FIELDS' => 'Täyttäkää kaikki vaaditut kentät',
  'LBL_QUEUE_BUTTON_KEY' => 'u',
  'LBL_QUEUE_BUTTON_LABEL' => 'Lähetä sähköposteja',
  'LBL_QUEUE_BUTTON_TITLE' => 'Lähetä sähköposteja',
  'LBL_RECHECK_BTN' => 'Tarkista uudelleen',
  'LBL_REFER_URL' => 'Seuraajan uudelleenohjauksen URL:',
  'LBL_REMOVE' => 'Poista',
  'LBL_REMOVED_SUBPANEL_TITLE' => 'Poistettu',
  'LBL_REPLY_ADDR' => 'Vastausosoite:',
  'LBL_REPLY_NAME' => 'Vastauksen saajan nimi:',
  'LBL_ROI_CHART_BUDGET' => 'Budjetti',
  'LBL_ROI_CHART_EXPECTED_REVENUE' => 'Odotetut tulot',
  'LBL_ROI_CHART_INVESTMENT' => 'Investoinnit',
  'LBL_ROI_CHART_REVENUE' => 'Tulot',
  'LBL_ROLLOVER_VIEW' => 'Vieritä hiiri rivin yli nähdäksesi lisätietoja.',
  'LBL_SAVED_SEARCH' => 'Tallennettiin haku ja asettelu',
  'LBL_SAVE_CONTINUE_BUTTON_LABEL' => 'Tallenna ja jatka',
  'LBL_SAVE_EXIT_BUTTON_LABEL' => 'Lopeta',
  'LBL_SCHEDULER_CHECK1_BAD' => 'Ajastuspalvelua ei ole konfiguroitu käsittelemään palautettuja kampanjaviestejä',
  'LBL_SCHEDULER_CHECK2_BAD' => 'Ajastuspalvelua ei ole konfiguroitu käsittelemään kampanjaviestejä.',
  'LBL_SCHEDULER_CHECK_BAD' => 'Ei havaittu ajastuspalveluja',
  'LBL_SCHEDULER_CHECK_GOOD' => 'Havaittiin ajastuspalvelu',
  'LBL_SCHEDULER_COMPONENTS' => 'Ajastuspalvelun komponentit',
  'LBL_SCHEDULER_LINK' => 'siirry ajastuspalvelun admin-näytölle.',
  'LBL_SCHEDULER_NAME' => 'Ajastuspalvelu',
  'LBL_SCHEDULER_STATUS' => 'Status',
  'LBL_SEARCH_FORM_TITLE' => 'Kampanjahaku',
  'LBL_SELECT_LEAD_FIELDS' => 'Ole hyvä ja valitse kentät',
  'LBL_SELECT_REQUIRED_LEAD_FIELDS' => 'Valitse vaaditut kentät:',
  'LBL_SELECT_TARGET' => 'Käytä olemassa olevaa kohdeluetteloa.',
  'LBL_SEND ERROR_SUBPANEL_TITLE' => 'Lähetysvirhe',
  'LBL_SEND_AS_TEST' => 'Lähetä markkinointiviesti testinä:',
  'LBL_SEND_EMAIL' => 'Ajasta sähköposti',
  'LBL_SERVER_TYPE' => 'Viestipalvelimen protokolla',
  'LBL_SERVER_URL' => 'Postipalvelimen osoite',
  'LBL_SSL' => 'Käytä SSL:ää',
  'LBL_SSL_DESC' => 'Jos postipalvelin tukee Secure Socket yhteyksiä, tämän päälle laittaminen pakottaa käyttämään SSL-yhteyttä kun tuodaan sähköposteja.',
  'LBL_START' => 'Aloita',
  'LBL_START_DATE' => 'Aloituspäivä',
  'LBL_START_DATE_TIME' => 'Aloituspäivä ja -aika:',
  'LBL_STATUS' => 'Status',
  'LBL_STATUS_TEXT' => 'Status:',
  'LBL_SUBSCRIPTION_LIST' => 'Tilauslista',
  'LBL_SUBSCRIPTION_LIST_NAME' => 'Tilauslistan nimi:',
  'LBL_SUBSCRIPTION_TARGET_WIZARD_DESC' => 'Tämä määrittelee kohdeluettelon tyypille Tilaus tälle kampanjalle. <br /> Tätä kohdeluetteloa käytetään kampanjaviestien lähetystä varten. <br />Jos sinulla ei ole valmiina kohdeluetteloa, tyhjä luettelo luodaan sinulle.',
  'LBL_SUBSCRIPTION_TYPE_NAME' => 'Tilaus',
  'LBL_TARGETED' => 'Kohdennettu',
  'LBL_TARGETED_SUBPANEL_TITLE' => 'Kohdennettu',
  'LBL_TARGET_LIST' => 'Kohdeluettelo',
  'LBL_TARGET_LISTS' => 'Kohdeluettelot',
  'LBL_TARGET_NAME' => 'Kohdeluettelon nimi',
  'LBL_TARGET_TYPE' => 'Kohdeluettelon tyyppi',
  'LBL_TEAM' => 'Ryhmä:',
  'LBL_TEMPLATE' => 'Sähköpostimalli:',
  'LBL_TEST_BUTTON_KEY' => 'e',
  'LBL_TEST_BUTTON_LABEL' => 'Lähetä testi',
  'LBL_TEST_BUTTON_TITLE' => 'Lähetä testi',
  'LBL_TEST_EMAILS_SENT' => 'Testiviestejä lähetetty',
  'LBL_TEST_LIST' => 'Testilista',
  'LBL_TEST_LIST_NAME' => 'Testilistan nimi:',
  'LBL_TEST_TARGET_WIZARD_DESC' => 'Tämä määrittelee kohdeluettelon tyypille Testi tälle kampanjalle. <br />Tätä luetteloa käytetään kampanjan testiviestien lähetykseen. <br />Jos sinulla ei ole valmiina kohdeluetteloa, tyhjä luettelo luodaan sinulle.',
  'LBL_TEST_TYPE_NAME' => 'Testi',
  'LBL_TIME_START' => 'Aloitusaika',
  'LBL_TODETAIL_BUTTON_KEY' => 'T',
  'LBL_TODETAIL_BUTTON_LABEL' => 'Näytä tiedot',
  'LBL_TODETAIL_BUTTON_TITLE' => 'Näytä tiedot',
  'LBL_TOP_CAMPAIGNS' => 'Top kampanjat',
  'LBL_TOP_CAMPAIGNS_DESCRIPTION' => 'Parhaiten toimivat kampanjat liikevaihdon mukaan.',
  'LBL_TOP_CAMPAIGNS_NAME' => 'Kampanjan nimi',
  'LBL_TOP_CAMPAIGNS_REVENUE' => 'Tulot',
  'LBL_TOTAL_ENTRIES' => 'Merkinnät',
  'LBL_TOTAL_TARGETED' => 'Kohdennettuja yhteensä',
  'LBL_TO_WIZARD' => 'käynnistä',
  'LBL_TO_WIZARD_TITLE' => 'Käynnistystyökalu',
  'LBL_TRACKED_URLS' => 'Seuraajien URL:ät',
  'LBL_TRACKED_URLS_SUBPANEL_TITLE' => 'Seuraajan URL',
  'LBL_TRACKERS' => 'Seuraajat',
  'LBL_TRACKER_COUNT' => 'Seuraajien määrä:',
  'LBL_TRACKER_KEY' => 'Seuraaja:',
  'LBL_TRACKER_TEXT' => 'Seuraajan linkkiteksti:',
  'LBL_TRACKER_URL' => 'Seuraajan URL:',
  'LBL_TRACK_BUTTON_KEY' => 'T',
  'LBL_TRACK_BUTTON_LABEL' => 'Näytä status',
  'LBL_TRACK_BUTTON_TITLE' => 'Näytä status',
  'LBL_TRACK_DELETE_BUTTON_KEY' => 'D',
  'LBL_TRACK_DELETE_BUTTON_LABEL' => 'Poista testimerkinnät',
  'LBL_TRACK_DELETE_BUTTON_TITLE' => 'Poista testimerkinnät',
  'LBL_TRACK_DELETE_CONFIRM' => 'Tämä valinta poistaa testiajon luomat lokitedostot. Jatketaanko?',
  'LBL_TRACK_QUEUE_SUBPANEL_TITLE' => 'Seuraa jonoa',
  'LBL_TRACK_ROI_BUTTON_LABEL' => 'Näytä ROI',
  'LBL_TYPE' => 'Tyyppi',
  'LBL_UNSUBSCRIBED_HEADER' => 'Saatavilla olevat uutiskirjeet/Uutiskirjeen tilauksen peruutus',
  'LBL_UNSUBSCRIBED_HEADER_EXPL' => 'Siirtämällä uutiskirjeen ‘Saatavilla olevat uutiskirjeet’- tai ‘Uutiskirjeen tilauksen peruutus’-listaan lisää yhteystiedon tämän uutiskirjeen tilauksen peruutuslistaan. Se ei poista kontaktia alkuperäisestä tilauslistasta tai kohdelistasta.',
  'LBL_UNSUBSCRIPTION_LIST' => 'Tilauksen peruutuslista',
  'LBL_UNSUBSCRIPTION_LIST_NAME' => 'Tilauksen peruutuslistan nimi:',
  'LBL_UNSUBSCRIPTION_TARGET_WIZARD_DESC' => 'Tämä määrittelee kohdeluettelon tyypille Tilauksen peruutus tälle kampanjalle. <br />Tässä luettelossa on niiden ihmisten nimiä, jotka ovat vetäytyneet kampanjaste. Heille ei pidä lähettää sähköpostia. <br />Jos sinulla ei ole valmiina kohdeluetteloa, tyhjä luettelo luodaan sinulle.',
  'LBL_UNSUBSCRIPTION_TYPE_NAME' => 'Tilauksen peruutus',
  'LBL_USERS_CANNOT_OPTOUT' => 'Järjestelmän käyttäjät eivät voi kieltäytyä vastaanottamasta sähköposteja.',
  'LBL_USE_EXISTING' => 'Käytä olemassaolevia',
  'LBL_VALID' => 'Virheetön',
  'LBL_VIEWED_SUBPANEL_TITLE' => 'Katsottu',
  'LBL_VIEW_INLINE' => 'Näytä',
  'LBL_WEB_TO_LEAD' => 'Luo webistä liidiksi -lomake',
  'LBL_WEB_TO_LEAD_FORM_TITLE1' => 'Luo liidilomake: valitse kentät',
  'LBL_WEB_TO_LEAD_FORM_TITLE2' => 'Luo liidilomake: lomakkeen ominaisuudet',
  'LBL_WIZARD_BUDGET_MESSAGE' => 'Syötä budjetti ROI:n laskua varten.',
  'LBL_WIZARD_FIRST_STEP_MESSAGE' => 'Olet jo ensimmäisessä vaiheessa.',
  'LBL_WIZARD_HEADER_MESSAGE' => 'Täytä tarvittavat kentät kampanjan tunnistusta varten.',
  'LBL_WIZARD_LAST_STEP_MESSAGE' => 'Olet jo viimeisessä vaiheessa.',
  'LBL_WIZARD_MARKETING_MESSAGE' => 'Täytä alla oleva lomake luodaksesi sähköposti-instanssi uutiskirjeellesi.  Näin voit määrittää tietoja milloin ja miten uutiskirje tulisi jakaa.',
  'LBL_WIZARD_SENDMAIL_MESSAGE' => 'Tämä on viimeinen vaihe.  Valitse, haluatko lähettää testisähköpostin, ajoittaa uutiskirjeen jakelun vai tallentaa muutokset ja siirtyä yhteenvetosivulle.',
  'LBL_WIZARD_SUBSCRIPTION_MESSAGE' => 'Jokaisella uutiskirjeellä on oltava kolme kohdeluetteloa (tilaukset, tilauksen poistot, ja testi). Voit liittää olemassa olevan kohdeluettelon. Jos et halua tehdä näin, luodaan tyhjä kohdeluettelo kun tallennat uutiskirjeen.',
  'LBL_WIZARD_TARGET_MESSAGE1' => 'Valitse tai luo kohdeluettelo kampanjaasi varten. Tätä luetteloa käytetään, kun lähetetään markkinointisähköposteja.',
  'LBL_WIZARD_TARGET_MESSAGE2' => 'Tai luo uusi alla olevalla lomakkeella:',
  'LBL_WIZARD_TRACKER_MESSAGE' => 'Määritä seuraajan URL tätä kampanjaa varten. Sinun pitää syöttää sekä nimi että URL seuraajan luomista varten.',
  'LBL_WIZ_FROM_ADDRESS' => 'Lähettäjän osoite:',
  'LBL_WIZ_FROM_NAME' => 'Lähettäjän nimi:',
  'LBL_WIZ_MARKETING_TITLE' => 'Markkinointisähköposti',
  'LBL_WIZ_NEWSLETTER_TITLE_STEP1' => 'Kampanjan ylätunniste',
  'LBL_WIZ_NEWSLETTER_TITLE_STEP2' => 'Kampanjan budjetti',
  'LBL_WIZ_NEWSLETTER_TITLE_STEP3' => 'Kampanjan seuraajien URL:ät',
  'LBL_WIZ_NEWSLETTER_TITLE_STEP4' => 'Tilaustiedot',
  'LBL_WIZ_NEWSLETTER_TITLE_SUMMARY' => 'Yhteenveto',
  'LBL_WIZ_SENDMAIL_TITLE' => 'Lähetä sähköpostia',
  'LBL_WIZ_TEST_EMAIL_TITLE' => 'Testisähköposti',
  'LBL_YEAR' => 'Vuosi',
  'LBL_YES' => 'Kyllä',
  'LNK_CAMPAIGN_DIGNOSTIC_LINK' => 'Kampanja ei välttämättä toimi halutulla tavalla eikä viestejä ehkä tule lähetettyä seuraavista syistä:',
  'LNK_CAMPAIGN_LIST' => 'Näytä kampanjat',
  'LNK_EMAIL_TEMPLATE_LIST' => 'Näytä sähköpostimallit',
  'LNK_NEW_CAMPAIGN' => 'Luo kampanja (Classic)',
  'LNK_NEW_EMAIL_TEMPLATE' => 'Luo sähköpostimalli',
  'LNK_NEW_PROSPECT' => 'Luo tavoite',
  'LNK_NEW_PROSPECT_LIST' => 'Luo kohdeluettelo',
  'LNK_PROSPECT_LIST' => 'Näytä tavoitteet',
  'LNK_PROSPECT_LIST_LIST' => 'Näytä kohdeluettelot',
  'LNL_NEW_CAMPAIGN_WIZARD' => 'Luo kampanja (Wizard)',
  'NTC_DELETE_CONFIRMATION' => 'Haluatko poistaa tämän tietueen?',
  'NTC_NO_LEGENDS' => 'Ei mitään',
  'TRACKING_ENTRIES_LOCATION_DEFAULT_VALUE' => '<code>config.php</code>:n <code>site_url</code> -asetuksen arvo',
);

