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
  'ERR_FORECAST_AMOUNT' => 'Commit Amount is required and must be a number.',
  'LBL_ACTIONS' => 'פעולות',
  'LBL_AMOUNT' => 'סכום',
  'LBL_ASSIGNING_QUOTA' => 'מקצה הצעת מחיר',
  'LBL_ASSIGN_QUOTA_BUTTON' => 'הקצה הצעת מחיר',
  'LBL_BASE_RATE' => 'שער בסיס',
  'LBL_CAMPAIGN' => 'קמפיין',
  'LBL_CANCEL' => 'בטל',
  'LBL_CATEGORY' => 'קטגוריה',
  'LBL_CHANGES_BY' => 'שינויים על ידי  {0}',
  'LBL_CHART_ADJUSTED' => '(התאם)',
  'LBL_CHART_AMOUNT' => 'כמות',
  'LBL_CHART_FOOTER' => 'Forecast History<br/>Quota vs Forecasted Amount vs Closed Opportunity Value',
  'LBL_CHART_FORECAST_FOR' => 'עבור {0}',
  'LBL_CHART_INCLUDED' => 'כלול',
  'LBL_CHART_NOT_INCLUDED' => 'לא כלול',
  'LBL_CHART_OPTIONS' => 'אפשרויות תרשים',
  'LBL_CHART_TITLE' => 'Quota vs. Committed vs. Actual',
  'LBL_CHART_TYPE' => 'סוג',
  'LBL_CLOSED' => 'נסגר בהצלחה',
  'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
  'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
  'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}, and {{{third}}}',
  'LBL_COMMITTED_HISTORY_BEST_CHANGED' => 'עדיף {{{direction}}} {{{from}}} ל- {{{to}}}',
  'LBL_COMMITTED_HISTORY_BEST_SAME' => 'הכי טוב להשאיר כך',
  'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => 'בסבירות גבוהה {{{direction}}} {{{from}}} ל- {{{to}}}',
  'LBL_COMMITTED_HISTORY_LIKELY_SAME' => 'סביר להניח שנשאר אותו הדבר',
  'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => 'הגדר תחזית',
  'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => 'תחזית מעודכנת',
  'LBL_COMMITTED_HISTORY_WORST_CHANGED' => 'הכי גרוע {{{direction}}} {{{from}}} ל- {{{to}}}',
  'LBL_COMMITTED_HISTORY_WORST_SAME' => 'הכי גרוע להשאיר כך',
  'LBL_COMMITTED_MONTHS_AGO' => 'לפני {0} חודשים ב {1}',
  'LBL_COMMITTED_THIS_MONTH' => 'חודש זה ב {0}',
  'LBL_COMMIT_AMOUNT' => 'סיכום של ערכים שהוקצו.',
  'LBL_COMMIT_HEADER' => 'תחזית לביצוע',
  'LBL_COMMIT_MESSAGE' => 'Do you want to commit these amounts?',
  'LBL_COMMIT_NOTE' => 'Enter amounts that you would like to commit for the selected Time Period:',
  'LBL_COMMIT_STAGE' => 'שלב חיוב',
  'LBL_COMMIT_TOOLTIP' => 'על מנת לאפשר חיוב: שנה ערך בגליון העבודה',
  'LBL_COPY' => 'העתק ערכים',
  'LBL_COPY_AMOUNT' => 'סך-הכל סכום',
  'LBL_COPY_FROM' => 'העתק ערך מתוך:',
  'LBL_COPY_WEIGH_AMOUNT' => 'סך הכל סכום משוקלל',
  'LBL_COST_PRICE' => 'מחיר עלות',
  'LBL_CREATED_BY' => 'נוצר על ידי',
  'LBL_CUMULATIVE_TOTAL' => 'סך הכל מצטבר',
  'LBL_CURRENCY' => 'מטבע',
  'LBL_CURRENCY_ID' => 'מטבע ID',
  'LBL_CURRENCY_RATE' => 'שער מטבע',
  'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => 'הקלק כאן על מנת להגדיר את מודול תחזית',
  'LBL_DASHLET_FORECAST_NOT_SETUP' => 'Forecasts has not been configured and needs to be setup in order to use this widget. Please contact your system administrator.',
  'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => 'יש להגדיר תחזית על מנת להשתמש ביישומון זה',
  'LBL_DASHLET_MY_FORECAST' => 'התחזית שלי',
  'LBL_DASHLET_MY_PIPELINE' => 'הצינור שלי',
  'LBL_DASHLET_MY_TEAMS_FORECAST' => 'התחזית של הצוות שלי',
  'LBL_DASHLET_MY_TEAMS_PIPELINE' => 'הצינור של הצוות שלי',
  'LBL_DASHLET_PIPELINE_CHART_DESC' => 'הצג תרשים צינור נוכחי',
  'LBL_DASHLET_PIPELINE_CHART_NAME' => 'תחזית תרשים צינור',
  'LBL_DATA_SET' => 'אוסף נתונים',
  'LBL_DATE_CLOSED' => 'תאריך סגירה צפוי',
  'LBL_DATE_COMMITTED' => 'בוצע בתאריך',
  'LBL_DATE_ENTERED' => 'הוזן בתאריך',
  'LBL_DATE_MODIFIED' => 'שונה בתאריך',
  'LBL_DELETED' => 'נמחק',
  'LBL_DISCOUNT' => 'הנחה',
  'LBL_DISPLAYED_TOTAL' => 'סך הכל מוצג',
  'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => 'הכי טוב מעל סגירה',
  'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => 'הכי טוב מעל הצעת מחיר',
  'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => 'סביר להניח מעל הצעת מחיר',
  'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => 'סביר להניח מעל הצעת מחיר',
  'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => 'הכי גרוע מעל סגירה',
  'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => 'הכי גרוע מעל הצעת מחיר',
  'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => 'הכי טוב תחת סגירה',
  'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => 'הכי טוב תחת הצעת מחיר',
  'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => 'סביר להניח תחת הצעת מחיר',
  'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => 'סביר להניח תחת הצעת מחיר',
  'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => 'הכי גרוע תחת סגירה',
  'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => 'הכי גרוע תחת הצעת מחיר',
  'LBL_DOWN' => 'למטה',
  'LBL_DV_FORECAST_OPPORTUNITY' => 'הזדמנויות לתחזית',
  'LBL_DV_FORECAST_PERIOD' => 'משך זמן לתחזית',
  'LBL_DV_FORECAST_ROLLUP' => 'Forecast Rollup',
  'LBL_DV_HEADER' => 'תחזיות:גיליון עבודה',
  'LBL_DV_LAST_COMMIT_AMOUNT' => 'סכום אחרון לביצוע:',
  'LBL_DV_LAST_COMMIT_DATE' => 'תאריך ביצוע אחרון:',
  'LBL_DV_MY_FORECASTS' => 'התחזיות שלי',
  'LBL_DV_MY_TEAM' => 'התחזיות של הצוות שלי',
  'LBL_DV_TIMEPERIOD' => 'משך זמן:',
  'LBL_DV_TIMEPERIODS' => 'משכי זמן:',
  'LBL_DV_TIMPERIOD_DATES' => 'טווח תאריכים:',
  'LBL_EDITABLE_INVALID' => 'ערך שגוי עבור {0}',
  'LBL_EDITABLE_INVALID_RANGE' => 'על הערך להיות בין {0} ל {1}',
  'LBL_ERROR_NOT_MANAGER' => 'שגיאה: למתשמש {0} אין גישה',
  'LBL_EXPECTED_OPPORTUNITIES' => 'הזדמנויות מצופות',
  'LBL_EXPORT_CSV' => 'ייצא CSV',
  'LBL_FC_START_DATE' => 'תאריך התחלה',
  'LBL_FC_USER' => 'מתוזמן עבור',
  'LBL_FDR_ADJ_AMOUNT' => 'סכום מתואם',
  'LBL_FDR_COMMIT' => 'סכום לביצוע',
  'LBL_FDR_C_BEST_CASE' => 'במקרה הטוב',
  'LBL_FDR_C_LIKELY_CASE' => 'במקרה הסביר',
  'LBL_FDR_C_WORST_CASE' => 'במקרה הגרוע',
  'LBL_FDR_DATE_COMMIT' => 'תאריך לביצוע',
  'LBL_FDR_OPPORTUNITIES' => 'בזדמנויות בתחזית:',
  'LBL_FDR_USER_NAME' => 'דוח ישיר',
  'LBL_FDR_WEIGH' => 'סכום משוקלל של התחזיות:',
  'LBL_FDR_WK_BEST_CASE' => 'במקרה הטוב הערכה',
  'LBL_FDR_WK_LIKELY_CASE' => 'המקרה הסביר הערכה',
  'LBL_FDR_WK_WORST_CASE' => 'במקרה הגרוע הערכה',
  'LBL_FILTERS' => 'פילטרים',
  'LBL_FISCAL_YEAR' => 'שנת כספים',
  'LBL_FMT_DIRECT_FORECAST' => '(ישיר)',
  'LBL_FMT_ROLLUP_FORECAST' => '(כלפי מעלה)',
  'LBL_FORECAST' => 'תחזית',
  'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => 'אין לך גישה למודול תחזית',
  'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => 'שגיאה בגישה לתחזית',
  'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => 'טווחים',
  'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => 'תרחישים',
  'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => 'תקופות זמן',
  'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => 'משתנים',
  'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => 'פריסת גליון עבודה',
  'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => 'הזדמנויות',
  'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => 'שורות פרטי הכנסה',
  'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => 'בחר כיצד לאכלס את גליון העבודה תחזית',
  'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => 'אני מציין מקום עבור תחזית על ידי תוכן how-to',
  'LBL_FORECASTS_CONFIG_HELP_RANGES' => 'Configure how you would like to categorize {{forecastByModule}}. <br><br>Please note that the Range settings cannot be changed after the first commit. For upgraded instances, the Range setting is locked in with existing Forecast data.<br><br>You may select two or more categories based on probability ranges or create categories which are not based on probability. <br><br>There are check-boxes to the left of your custom categories; use these to decide which ranges will be included within the Forecast amount committed and reported to managers. <br><br>A user may change the include/exclude status and category of {{forecastByModule}} manually from their worksheet.',
  'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => 'Select the columns you would like the user to fill out for their Forecasts of each {{forecastByModuleSingular}}. Please note the Likely amount is tied to the amount shown in {{forecastByModule}}; for this reason the Likely column cannot be hidden.',
  'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => 'Conﬁgure the Time Period that will be used in the Forecasts module. <br><br>Please note that Time Period settings cannot be changed after initial setup.<br><br>Start by choosing the Start Date of your ﬁscal year. Then choose the type of Time Period for the Forecast. The date range for the Time Periods will be automatically calculated based on your selections. The Sub Time Period is the base for the Forecast worksheet. <br><br>The viewable future and past Time Periods will determine the number of visible sub-periods in the Forecasts module. The users are able to view and edit the Forecast numbers in the visible sub-periods.',
  'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => 'Select which columns you would like to view in the Forecast module. The list of fields will combine the worksheet and allow the user to choose how to configure its view.',
  'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => 'תחזית על ידי',
  'LBL_FORECASTS_CONFIG_LEAFPERIOD' => 'בחר תת תקופת זמן לצפיה',
  'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => 'הצג תרחישים צפויים בסיכומים',
  'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => 'הצג סיכומים לתרחיש הטוב ביותר',
  'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => 'הצג תרחיש צפוי בסיכומים',
  'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => 'הצג סיכומים לתרחיש הגרוע ביותר',
  'LBL_FORECASTS_CONFIG_RANGES' => 'אפשרויות טווח תחזית',
  'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => 'הכנס שם טווח...',
  'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => 'The Exclude Range is from 0% to the minimum of the previous Forecast Range by default.',
  'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => 'בחר כיצד לסווג {{forecastByModule}}.',
  'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => 'Range settings cannot be changed after first save draft or commit in the Forecasts module. For an upgraded instance however, Range settings cannot be changed after the initial setup as the Forecasts data is already available through the upgrade.',
  'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => 'הגדרות תחזית נשמרו',
  'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => 'This option gives a user the ability to specify {{forecastByModule}} that will be included or excluded from a Forecast.',
  'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => 'This option gives a user the ability to categorize their {{forecastByModule}} that are not included in the commit but are upside and have the potential of closing if everything goes well and {{forecastByModule}} that are to be excluded from the Forecast.',
  'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => 'Custom Ranges: This option gives a user the ability to categorize their {{forecastByModule}} to be committed into the Forecast into a committed range, excluded range and any others that you setup.',
  'LBL_FORECASTS_CONFIG_START_DATE' => 'בחר תאריך תחילת שנת כספים',
  'LBL_FORECASTS_CONFIG_TIMEPERIOD' => 'בחר סוג תקופת זמן',
  'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => 'Choose the number of past Time Periods to view in the worksheet.<br><i>This number applies to the base Time Period selected. For example, choosing 2 with Monthly Time Period will show 6 past Months</i>',
  'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => 'Choose the number of future Time Periods to view in the worksheet.<br><i>This number applies to the base Time Period selected. For example, choosing 2 with Yearly Time Period will show 8 future Quarters</i>',
  'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => 'הגדר את תקופות הזמן עבור מודול תחזית',
  'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => 'The chosen start date indicates the fiscal year may span across two years. Please choose which year to use as the Fiscal Year:',
  'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => 'לא ניתן לשנות הגדרות תקופת זמן',
  'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => 'בחר את סוג השנה שהארגון שלך משתמש בו לראיית חשבון',
  'LBL_FORECASTS_CONFIG_TITLE' => 'הגדרת תחזית',
  'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => 'צפה בתחזית גליון עבודה על ידי',
  'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => 'הגדרות תחזית',
  'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => 'תאריך תחילת שנת כספים',
  'LBL_FORECASTS_CONFIG_TITLE_RANGES' => 'טווחי תחזית',
  'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => 'תרחישים',
  'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => 'תקופת זמן',
  'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => 'עמודות גליון עבודה',
  'LBL_FORECASTS_CONFIG_VARIABLES' => 'משתנים',
  'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => 'בחר את שלב המכירה שמייצג סגור ואבוד {{forecastByModule}}:',
  'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => 'בחר את שלב המכירה שמייצג סגור והצליח{{forecastByModule}}:',
  'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => 'The formulas for the Metrics Table rely on the sales stage for {{forecastByModule}} that need to be excluded from the pipleline, i.e., {{forecastByModule}} that are closed and lost.',
  'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => 'על כן הנוסחה לצינור היא:',
  'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => 'גליון העבודה יאוכלס עם',
  'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => 'Likely is based on the amount entered in the {{forecastByModule}} module.',
  'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => 'בחר תרחישים לגליון עבודה תחזית',
  'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => 'הכי טוב',
  'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => 'סביר להניח',
  'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => 'הגרוע ביותר',
  'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => 'Select which columns should be displayed for the worksheet view. By default, the following fields will be selected:',
  'LBL_FORECASTS_CUSTOM_BASED_TITLE' => 'טווחים מותתאמים מבוססים על סבירויות',
  'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' => 'טווחים אינם מבוססים על סבירויות',
  'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => 'טווח מותאם',
  'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => 'The Forecasts module has been improperly configured and is no longer available. Sales Stage Won and Sales Stage Lost are missing from the available Sales Stages values. Please contact your Administrator.',
  'LBL_FORECASTS_MISSING_STAGE_TITLE' => 'שגיאה בהגדרות תחזית',
  'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => 'אין לך גישה להגדיר תחזית',
  'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => 'שגיאה בגישה לתחזית',
  'LBL_FORECASTS_RANGES_BASED_TITLE' => 'טווח מסובב על סבירויות',
  'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => 'הגדרת תחזית נשמרו. אני המתן לטעינה.',
  'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => 'הגדרת בהצלחה מודול תחזית. אני המתן לטעינה.',
  'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => 'הצלחה',
  'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => 'ביצעת חיוב לתחזית שלך',
  'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => 'ביצעת חיוב לתחזית שלך ל {{manager}}',
  'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => 'שמרת את גליון העבודה תחזית כטיוטה עבור תקופת הזמן שנבחרה',
  'LBL_FORECAST_DETAILS_DEFICIT' => 'גירעון',
  'LBL_FORECAST_DETAILS_EXCEED' => 'יש עודף של',
  'LBL_FORECAST_DETAILS_MEETING_QUOTA' => 'הצעת מחיר לפגישה',
  'LBL_FORECAST_DETAILS_NO_DATA' => 'אין נתונים',
  'LBL_FORECAST_DETAILS_SHORT' => 'יש חוסר של',
  'LBL_FORECAST_DETAILS_SURPLUS' => 'עודף',
  'LBL_FORECAST_FOR' => 'גיליון עבודה תחזית עבור:',
  'LBL_FORECAST_HISTORY' => 'תחזיות: הסטוריה',
  'LBL_FORECAST_HISTORY_TITLE' => 'הסטוריה',
  'LBL_FORECAST_ID' => 'ID',
  'LBL_FORECAST_OPP_BEST_CASE' => 'במקרה הטוב',
  'LBL_FORECAST_OPP_COMMIT' => 'המקרה הסביר',
  'LBL_FORECAST_OPP_COUNT' => 'הזדמנויות',
  'LBL_FORECAST_OPP_WEIGH' => 'סכום משוקלל',
  'LBL_FORECAST_OPP_WORST' => 'במקרה הגרוע',
  'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'כמות צינורות הזדמנויות',
  'LBL_FORECAST_SETTINGS' => 'הגדרות',
  'LBL_FORECAST_TIME_ID' => 'משך זמן זהות',
  'LBL_FORECAST_TITLE' => 'תחזית: {0}',
  'LBL_FORECAST_TYPE' => 'סוג תחזית',
  'LBL_FORECAST_USER' => 'משתמש',
  'LBL_FS_CASCADE' => 'Cascade?',
  'LBL_FS_CREATED_BY' => 'נותר על ידי',
  'LBL_FS_DATE_ENTERED' => 'הוזן בתאריך',
  'LBL_FS_DATE_MODIFIED' => 'שונה בתאריך',
  'LBL_FS_DELETED' => 'נמחק',
  'LBL_FS_END_DATE' => 'תאריך סיום',
  'LBL_FS_FORECAST_FOR' => 'מתוזמן עבור:',
  'LBL_FS_FORECAST_START_DATE' => 'תאריך התחלה של התחזית',
  'LBL_FS_MODULE_NAME' => 'תיזמון לתחזית',
  'LBL_FS_START_DATE' => 'תאריך התחלה',
  'LBL_FS_STATUS' => 'סטאטוס',
  'LBL_FS_TIMEPERIOD' => 'משך זמן',
  'LBL_FS_TIMEPERIOD_ID' => 'Time Period ID',
  'LBL_FS_USER_ID' => 'User ID',
  'LBL_GRAPH_COMMIT_ALTTEXT' => 'סכום שהוקצה עבור %s',
  'LBL_GRAPH_COMMIT_LEGEND' => 'תחזיות שהוקצו',
  'LBL_GRAPH_OPPS_ALTTEXT' => 'ערך ההזדמנויות שנסגרו ב %s',
  'LBL_GRAPH_OPPS_LEGEND' => 'הזדמנויות שנסגרו',
  'LBL_GRAPH_QUOTA_ALTTEXT' => 'מכזה עבור %s',
  'LBL_GRAPH_QUOTA_LEGEND' => 'מכסה',
  'LBL_GRAPH_TITLE' => 'הסטוריה לתחזית',
  'LBL_GROUP_BY' => 'ממוין על ידי',
  'LBL_HELP_RECORDS' => '',
  'LBL_IN_FORECAST' => 'בתחזית',
  'LBL_LESS' => 'פחות',
  'LBL_LIST_FORM_TITLE' => 'תחזיות שבוצעו',
  'LBL_LOADING' => 'טוען',
  'LBL_LOADING_COMMIT_HISTORY' => 'טוען היסטוריית חיובים',
  'LBL_LV_COMMIT' => 'סכום שהוקצהCommitted Amount',
  'LBL_LV_COMMIT_DATE' => 'בוצעה בתאריך',
  'LBL_LV_OPPORTUNITIES' => 'הזדמנויות',
  'LBL_LV_TIMPERIOD' => 'Time periodמשך זמן',
  'LBL_LV_TIMPERIOD_END_DATE' => 'תאריך סיום',
  'LBL_LV_TIMPERIOD_START_DATE' => 'תאריך התחלה',
  'LBL_LV_TYPE' => 'סוג תחזית',
  'LBL_LV_WEIGH' => 'סכום משוקלל',
  'LBL_MODIFIED_USER_ID' => 'שונה על ידי',
  'LBL_MODULE_NAME' => 'תחזיות',
  'LBL_MODULE_NAME_SINGULAR' => 'תחזית',
  'LBL_MODULE_TITLE' => 'תחזיות',
  'LBL_MORE' => 'עוד',
  'LBL_MY_MANAGER_LINE' => '{0} (אני)',
  'LBL_NO_ACTIVE_TIMEPERIOD' => 'No Active time periods for Forecasting.',
  'LBL_OPPORTUNITY_NAME' => 'שם ההזדמנות',
  'LBL_OPPORTUNITY_STATUS' => 'סטטוס הזדמנות',
  'LBL_OVERALL_TOTAL' => 'סך הכל כללי',
  'LBL_OW_ACCOUNTNAME' => 'חשבון',
  'LBL_OW_DESCRIPTION' => 'תיאור',
  'LBL_OW_MODULE_TITLE' => 'גיליון עבודה להזדמנות',
  'LBL_OW_NEXT_STEP' => 'השלב הבא',
  'LBL_OW_OPPORTUNITIES' => 'הזדמנות',
  'LBL_OW_PROBABILITY' => 'הסתברות',
  'LBL_OW_REVENUE' => 'סכום',
  'LBL_OW_TYPE' => 'סוג',
  'LBL_OW_WEIGHTED' => 'סכום משוקלל',
  'LBL_PERCENT' => 'אחוז',
  'LBL_PIPELINE_OPPORTUNITIES' => 'צינור הזדמנויות',
  'LBL_PIPELINE_REVENUE' => 'צינור הכנסה',
  'LBL_PREVIOUS_COMMIT' => 'חיוב אחרון',
  'LBL_PRODUCT_ID' => 'מוצר ID',
  'LBL_PRODUCT_TEMPLATE' => 'קטלוג מוצרים',
  'LBL_PROJECTED' => 'תחזית',
  'LBL_QC_COMMIT_BEST_CASE' => 'סכום שהוקצה (במקרה הטוב):',
  'LBL_QC_COMMIT_BUTTON' => 'בוצע',
  'LBL_QC_COMMIT_LIKELY_CASE' => 'סכום שהוקצה (במקרה הסביר):',
  'LBL_QC_COMMIT_VALUE' => 'סכום שהוקצה:',
  'LBL_QC_COMMIT_WORST_CASE' => 'סכום שהוקצה (במקרה הגרוע):',
  'LBL_QC_DIRECT_FORECAST' => 'תחזיות ישירות שלי:',
  'LBL_QC_HEADER_DELIM' => 'אל',
  'LBL_QC_LAST_BEST_CASE' => 'סכום סופי שהוקצה (במקרה הטוב):',
  'LBL_QC_LAST_COMMIT_VALUE' => 'סכום אחרון לביצוע:',
  'LBL_QC_LAST_DATE_COMMITTED' => 'תאריך אחרון לביצוע:',
  'LBL_QC_LAST_LIKELY_CASE' => 'סכום סופי שהוקצה (במקרה הסביר):',
  'LBL_QC_LAST_WORST_CASE' => 'סכום סופי שהוקצה (במקרה הגרוע):',
  'LBL_QC_OPPORTUNITY_COUNT' => 'הזדמנות שנלקחת בחשבון:',
  'LBL_QC_ROLLUP_FORECAST' => 'תחזיות של הקבוצה שלי:',
  'LBL_QC_ROLL_BEST_VALUE' => 'סכום שהוקצה כלפי מעלה (במקרה הטוב):',
  'LBL_QC_ROLL_COMMIT_VALUE' => 'סכום שהוקצה מוערך כלפי מעלה:',
  'LBL_QC_ROLL_LIKELY_VALUE' => 'סכום שהוקצה כלפי מעלה (במקרה הסבירר):',
  'LBL_QC_ROLL_WORST_VALUE' => 'סכום שהוקצה כלפי מעלה (במקרה הגרוע):',
  'LBL_QC_TIME_PERIOD' => 'משך זמן:',
  'LBL_QC_UPCOMING_FORECASTS' => 'התחזיות שלי',
  'LBL_QC_WEIGHT_VALUE' => 'סכום משוקלל:',
  'LBL_QC_WORKSHEET_BUTTON' => 'גיליון עבודה',
  'LBL_QUOTA' => 'הצעת מחיר',
  'LBL_QUOTA_ADJUSTED' => 'הצעת מחיר (מותאמת)',
  'LBL_QUOTA_ASSIGNED' => 'הצעת מחיר הוקצתה בהצלחה',
  'LBL_QUOTA_ID' => 'הצעת מחיר ID',
  'LBL_REPORTS_TO_USER_NAME' => 'מדווח אל',
  'LBL_RESET_CHECK' => 'All worksheet data for the selected time period and logged in user will be removed. Continue?',
  'LBL_RESET_WOKSHEET' => 'אתחל גיליון עבודה',
  'LBL_REVENUE' => 'הכנסה',
  'LBL_REVENUELINEITEM_NAME' => 'שם שורת פריט הכנסה',
  'LBL_SALES_STAGE' => 'שלב',
  'LBL_SAVE_DRAFT' => 'שמור טויוטה',
  'LBL_SAVE_WOKSHEET' => 'שמור גיליון עבודה',
  'LBL_SEARCH' => 'בחר',
  'LBL_SEARCH_LABEL' => 'בחר',
  'LBL_SHOW_CHART' => 'צפה בטבלה',
  'LBL_SVFS_CASCADE' => 'Cascade to Reports?',
  'LBL_SVFS_FORECASTDATE' => 'תזמן תאריך התחלה',
  'LBL_SVFS_HEADER' => 'תזמן תחזית:',
  'LBL_SVFS_STATUS' => 'סטאטוס',
  'LBL_SVFS_USER' => 'עבור',
  'LBL_TEAMS' => 'צוותים',
  'LBL_TIMEPERIOD_NAME' => 'שמכי זמן',
  'LBL_TOTAL' => 'סך הכל',
  'LBL_TOTAL_DISCOUNT_AMOUNT' => 'סך הכל כמות הנחה',
  'LBL_TOTAL_VALUE' => 'סיכומים:',
  'LBL_TP_QUOTA' => 'מכסה:',
  'LBL_TREE_PARENT' => 'הורה',
  'LBL_UNAUTH_FORECASTS' => 'גישה לא מורשת להגדרות חזית',
  'LBL_UP' => 'למעלה',
  'LBL_USER_NAME' => 'שם משתמש',
  'LBL_VERSION' => 'גירסה',
  'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => 'יש נתונים שלא נשמרו. אתה בטוח שאתה רוצה למיין ולאבד נתונים?',
  'LBL_WK_REVISION' => 'גירסה',
  'LBL_WK_VERSION' => 'גירסה',
  'LBL_WORKSHEET_AMOUNT' => 'סך הכל סכום מוערך',
  'LBL_WORKSHEET_EXPORT_CONFIRM' => 'רק נתונים שנשמרו או שחויבו ייוצאו. לא ניתן לבטל. אשר ייצור נתונים שנשמרו',
  'LBL_WORKSHEET_ID' => 'גליון עבודה ID',
  'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => 'יש שינויים שלא שמרת בגליון העבודה',
  'LB_FS_BEST_CASE' => 'במקרה הטוב',
  'LB_FS_KEY' => 'ID',
  'LB_FS_LIKELY_CASE' => 'במקרה הסביר',
  'LB_FS_WORST_CASE' => 'במקרה הגרוע',
  'LNK_FORECAST_LIST' => 'צפה בהסטוריה של התחזית',
  'LNK_NEW_OPPORTUNITY' => 'צור הזדמנות',
  'LNK_NEW_TIMEPERIOD' => 'צור משך זמן',
  'LNK_QUOTA' => 'הצג מכסה',
  'LNK_TIMEPERIOD_LIST' => 'צפייה במשכי זמן',
  'LNK_UPD_FORECAST' => 'גיליון עבודה לתחזית',
);
