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
  'ERR_DELETE_RECORD' => '必须指定记录编号才能删除帐户。',
  'ERR_EMAIL_INCORRECT' => '请提供有效的电子邮件地址以便生成密码发送给您。',
  'ERR_EMAIL_NOT_SENT_ADMIN' => '系统无法处理您的请求。请检查：',
  'ERR_EMAIL_NO_OPTS' => '找不到收入电子邮件的最佳设置。',
  'ERR_ENTER_CONFIRMATION_PASSWORD' => '请输入确认密码。',
  'ERR_ENTER_NEW_PASSWORD' => '请输入新密码。',
  'ERR_ENTER_OLD_PASSWORD' => '请输入现有密码。',
  'ERR_IE_FAILURE1' => '[点击这儿返回]',
  'ERR_IE_FAILURE2' => '无法连接电子邮件帐户。请检查您的设置后再试一次。',
  'ERR_IE_MISSING_REQUIRED' => '收件邮箱的设置尚缺必须信息。请查看您的设置后重试。如果您不要设置收件邮箱，请清除这个段的所有字段。',
  'ERR_INVALID_PASSWORD' => '您必须指定一个有效的用户名和密码。',
  'ERR_LAST_ADMIN_1' => '用户名“',
  'ERR_LAST_ADMIN_2' => '”是最后一个具有管理员权限的用户。至少有一个用户必须是管理员。',
  'ERR_NO_LOGIN_MOBILE' => '您第一次登录本系统必须使用非移动浏览器或者使用常规模式。请使用全能浏览器或者单击下面的常规链接。为此造成不便，我们深表歉意。',
  'ERR_PASSWORD_CHANGE_FAILED_1' => '用户密码更改失败为',
  'ERR_PASSWORD_CHANGE_FAILED_2' => '失败。必须设置新密码。',
  'ERR_PASSWORD_CHANGE_FAILED_3' => '。新密码无效。',
  'ERR_PASSWORD_INCORRECT_OLD_1' => '用户的现有密码不正确',
  'ERR_PASSWORD_INCORRECT_OLD_2' => '。重新输入密码信息。',
  'ERR_PASSWORD_LINK_EXPIRED' => '您的链接已过期，请生成一个新链接',
  'ERR_PASSWORD_MISMATCH' => '密码不匹配。',
  'ERR_PASSWORD_USERNAME_MISSMATCH' => '您必须指定一个有效的用户名和电子邮件地址。',
  'ERR_REASS_DIFF_USERS' => '请选择一个分配给的用户，与从用户中指派不同.',
  'ERR_REASS_SELECT_MODULE' => '请退回并至少选择一个模块.',
  'ERR_RECIPIENT_EMAIL' => '收件人电子邮件地址',
  'ERR_REENTER_PASSWORDS' => '“新密码”和“确认密码”不匹配。',
  'ERR_REPORT_LOOP' => '系统检测到一个汇报循环。用户不可以向自己汇报，用户的经理也不可以向他们汇报。',
  'ERR_RULES_NOT_MET' => '您输入的密码不符合密码的要求，请再试一遍。',
  'ERR_SERVER_SMTP_EMPTY' => '系统无法发送邮件给用户。请到 <a href="index.php?module=EmailMan&action=config">电子邮件设置</a>检查系统发信配置.',
  'ERR_SERVER_STATUS' => '您的服务器状态',
  'ERR_SMTP_URL_SMTP_PORT' => 'SMTP服务器URL和端口',
  'ERR_SMTP_USERNAME_SMTP_PASSWORD' => 'SMTP用户名和密码',
  'ERR_USER_INFO_NOT_FOUND' => '未找到用户信息',
  'ERR_USER_IS_LOCKED_OUT' => '当前用户已被锁在Sugar系统外，无法使用现有密码登录。',
  'ERR_USER_NAME_EXISTS_1' => '用户名',
  'ERR_USER_NAME_EXISTS_2' => '已存在。用户名不可以重复。用户名必须是唯一的。',
  'LBL_ACCOUNT_NAME' => '客户名称',
  'LBL_ADDRESS' => '地址',
  'LBL_ADDRESS_CITY' => '城市',
  'LBL_ADDRESS_COUNTRY' => '国家',
  'LBL_ADDRESS_INFORMATION' => '地址信息',
  'LBL_ADDRESS_POSTALCODE' => '邮编',
  'LBL_ADDRESS_STATE' => '省份',
  'LBL_ADDRESS_STREET' => '街道',
  'LBL_ADDRESS_STREET_2' => '地址/街道第2行',
  'LBL_ADDRESS_STREET_3' => '地址/街道第3行',
  'LBL_ADMIN' => '管理员',
  'LBL_ADMIN_DESC' => '用户可以跳过团队安全和角色权限访问所有模块和记录.',
  'LBL_ADMIN_USER' => '系统管理员用户',
  'LBL_ADVANCED' => '高级',
  'LBL_AFFECTED' => '条记录',
  'LBL_ANY_ADDRESS' => '任何地址:',
  'LBL_ANY_EMAIL' => '任何电子邮件',
  'LBL_ANY_PHONE' => '任何电话',
  'LBL_APPLY_OPTIMUMS' => '提交最佳设置',
  'LBL_ASSIGN_PRIVATE_TEAM' => '(保存时的私有团队)',
  'LBL_ASSIGN_TEAM' => '指派给团队',
  'LBL_ASSIGN_TO_USER' => '指派给用户',
  'LBL_AUTHENTICATE_ID' => '认证ID',
  'LBL_BASIC' => '收件箱设置',
  'LBL_BUTTON_CREATE' => '新增',
  'LBL_BUTTON_EDIT' => '编辑',
  'LBL_CALENDAR_OPTIONS' => '日程安排选项',
  'LBL_CANCEL' => '取消',
  'LBL_CANNOT_SEND_PASSWORD' => '不能发送密码',
  'LBL_CERT' => '证书验证',
  'LBL_CERT_DESC' => '强制验证邮件服务器的安全证书–如果是自我签署，请不要使用',
  'LBL_CHANGE_PASSWORD' => '更改密码',
  'LBL_CHANGE_PASSWORD_TITLE' => '更改密码',
  'LBL_CHANGE_SYSTEM_PASSWORD' => '请输入一个新密码.',
  'LBL_CHECKMARK' => '检查标识',
  'LBL_CHOOSE_A_KEY' => '选择一个密钥来阻止未授权的人公布您的日程安排',
  'LBL_CHOOSE_EMAIL_PROVIDER' => '请选择邮件提供商',
  'LBL_CHOOSE_WHICH' => '选择要显示的标签',
  'LBL_CITY' => '城市',
  'LBL_CLEAR_BUTTON_TITLE' => '清除',
  'LBL_CONFIRM_PASSWORD' => '确认密码',
  'LBL_CONFIRM_REGULAR_USER' => '您已将该用户从系统管理员转换为普通用户. 保存修改之后, 该用户将没有系统管理权限.\\n\\n确认操作请点击 “确定” .\\n取消操作请点击 “返回”.',
  'LBL_COUNTRY' => '国家',
  'LBL_CREATED_BY_NAME' => '由创建',
  'LBL_CURRENCY' => '货币',
  'LBL_CURRENCY_EXAMPLE' => '货币显示实例',
  'LBL_CURRENCY_SHOW_PREFERRED' => '显示首选货币',
  'LBL_CURRENCY_SHOW_PREFERRED_TEXT' => '转换基准货币，以用户首选列表和记录意见',
  'LBL_CURRENCY_SIG_DIGITS' => '货币精确度',
  'LBL_CURRENCY_SIG_DIGITS_DESC' => '显示货币小数的个数',
  'LBL_CURRENCY_TEXT' => '选择默认的货币',
  'LBL_DATE_ENTERED' => '输入日期',
  'LBL_DATE_FORMAT' => '日期格式',
  'LBL_DATE_FORMAT_TEXT' => '设置日期戳的显示格式',
  'LBL_DATE_MODIFIED' => '修改日期',
  'LBL_DECIMAL_SEP' => '小数符号',
  'LBL_DECIMAL_SEP_TEXT' => '分隔小数部分的字符',
  'LBL_DEFAULT_PRIMARY_TEAM' => '默认主团队',
  'LBL_DEFAULT_SUBPANEL_TITLE' => '用户',
  'LBL_DEFAULT_TEAM' => '默认团队',
  'LBL_DEFAULT_TEAM_TEXT' => '纪录上默认显示的团队是您是其成员的团队。',
  'LBL_DELETED' => '删除',
  'LBL_DELETE_GROUP_CONFIRM' => '您是否确定删除此组用户？单击OK即可删除用户纪录。<br/>在点击OK后，您将有权限把指派给组用户的纪录另指派给另一用户。',
  'LBL_DELETE_PORTAL_CONFIRM' => '您是否确定删除此平台API用户？单击OK即可删除用户纪录。',
  'LBL_DELETE_USER' => '删除用户',
  'LBL_DELETE_USER_CONFIRM' => '删除用户纪录的同时也删除了对应的员工纪录。 用户一经删除，任何与此用户相关的工作流定义和报表可能需要更新。<br/><br/>删除用户纪录的动作不可解除。',
  'LBL_DEPARTMENT' => '部门',
  'LBL_DESCRIPTION' => '说明',
  'LBL_DISPLAY_TABS' => '显示标签',
  'LBL_DOWNLOADS' => '下载',
  'LBL_DST_INSTRUCTIONS' => '(+DST)指示您遵循夏令时',
  'LBL_EAPM_SUBPANEL_TITLE' => '外部客户',
  'LBL_EDIT' => '编辑',
  'LBL_EDITLAYOUT' => '编辑布局',
  'LBL_EDIT_TABS' => '编辑标签',
  'LBL_EMAIL' => '电子邮件地址',
  'LBL_EMAILS' => '电邮',
  'LBL_EMAIL_ADDRESS' => '电子邮件地址',
  'LBL_EMAIL_CHARSET' => '发件箱字符集',
  'LBL_EMAIL_EDITOR_OPTION' => '撰写格式',
  'LBL_EMAIL_GMAIL_DEFAULTS' => '预先填入Gmail默认值',
  'LBL_EMAIL_INBOUND_TITLE' => '收件箱设置',
  'LBL_EMAIL_LINK_TYPE' => '电子邮件客户端',
  'LBL_EMAIL_LINK_TYPE_HELP' => '系统默认邮件客户端 : 默认邮件客户端由系统管理员设置. SugarCRM邮件客户端 : 该客户端在Sugar的电子邮件模块中.	外部邮件客户端 : 其他邮件客户端, 例如Microsoft Outlook.',
  'LBL_EMAIL_NOT_SENT' => '系统无法处理您的请求. 请联系系统管理员.',
  'LBL_EMAIL_OTHER' => '电子邮件2',
  'LBL_EMAIL_OUTBOUND_TITLE' => '发件箱设置',
  'LBL_EMAIL_PROVIDER' => '邮件提供商',
  'LBL_EMAIL_SHOW_COUNTS' => '显示电子邮件总数?',
  'LBL_EMAIL_SIGNATURE_ERROR1' => '这个签名需要一个名字。',
  'LBL_EMAIL_SMTP_SSL' => '在SMTP启用SSL',
  'LBL_EMAIL_TEMPLATE_MISSING' => '尚未选择邮件模板来发送包含密码的邮件给用户. 请在密码管理页面中选择一个邮件模板.',
  'LBL_EMPLOYEE_INFORMATION' => '员工信息',
  'LBL_EMPLOYEE_STATUS' => '员工状态',
  'LBL_ERROR' => '错误',
  'LBL_EXCHANGE_SMTPPASS' => 'Exchange 密码：',
  'LBL_EXCHANGE_SMTPPORT' => 'Exchange 服务器端口：',
  'LBL_EXCHANGE_SMTPSERVER' => 'Exchange 服务器：',
  'LBL_EXCHANGE_SMTPUSER' => 'Exchange 用户名：',
  'LBL_EXPORT_CHARSET' => '导入/导出字符集',
  'LBL_EXPORT_CHARSET_DESC' => '选择您所在地区使用的字符集。这个属性用于导入数据，发送电子邮件，导出.csv文件，生成PDF文件，和生成vCard文件。',
  'LBL_EXPORT_CREATED_BY' => '由ID创建',
  'LBL_EXPORT_DELIMITER' => '导出分隔符',
  'LBL_EXPORT_DELIMITER_DESC' => '指定用于分隔导出数据的字符。',
  'LBL_EXTERNAL_AUTH_ONLY' => '仅使该用户通过认证',
  'LBL_EXT_AUTHENTICATE' => '外部认证',
  'LBL_FAX' => '传真',
  'LBL_FAX_PHONE' => '传真',
  'LBL_FDOW' => '一周首日',
  'LBL_FDOW_TEXT' => '显示在每周，每月和每年的首日',
  'LBL_FILTER_USERS_REPORTS' => '用户报告',
  'LBL_FIND_OPTIMUM_KEY' => 'f',
  'LBL_FIND_OPTIMUM_MSG' => '<br>寻找最佳连接变量。',
  'LBL_FIND_OPTIMUM_TITLE' => '寻找最佳配置',
  'LBL_FIRST_NAME' => '名',
  'LBL_FORCE' => '强制否定',
  'LBL_FORCE_DESC' => '一些IMAP/POP3服务器需要特殊的交换机。当连接失败的时候，请检查交换机(比如 /notls)',
  'LBL_FORECASTS' => '销售预测',
  'LBL_FORGOTPASSORD_NOT_ENABLED' => '目前未启用。请联系您的系统管理员',
  'LBL_FOUND_OPTIMUM_MSG' => '<br>发现最优设置。点击下面的按钮来提交他们到您的邮箱。',
  'LBL_GENERATE_PASSWORD' => '重设密码',
  'LBL_GENERATE_PASSWORD_BUTTON_KEY' => 'G',
  'LBL_GENERATE_PASSWORD_BUTTON_LABEL' => '重设密码',
  'LBL_GENERATE_PASSWORD_BUTTON_TITLE' => '重设密码',
  'LBL_GMAIL_SMTPPASS' => 'Gmail 密码：',
  'LBL_GMAIL_SMTPUSER' => 'Gmail 邮箱地址：',
  'LBL_GROUP_DESC' => '作为分组用户。这个用户不可以通过Sugar Suite网页登录。这个用户只能用于通过收件箱功能指派记录到分组。',
  'LBL_GROUP_USER' => '组用户',
  'LBL_GROUP_USER_STATUS' => '组用户',
  'LBL_HELP' => '帮助',
  'LBL_HIDEOPTIONS' => '隐藏选项',
  'LBL_HIDE_TABS' => '隐藏标签',
  'LBL_HOME_PHONE' => '家庭电话',
  'LBL_ICAL_PUB_URL' => 'iCal集成URL',
  'LBL_ICAL_PUB_URL_HELP' => '使用这个URL来在iCal内订阅Sugar日历',
  'LBL_INBOUND_TITLE' => '客户信息',
  'LBL_IS_ADMIN' => '是管理员',
  'LBL_IS_GROUP' => '小组',
  'LBL_LANGUAGE' => '语言',
  'LBL_LAST_ADMIN_NOTICE' => '您选择了自己。您不能更改用户类型或自己的状态。',
  'LBL_LAST_NAME' => '姓',
  'LBL_LAST_NAME_SLASH_NAME' => '姓/名',
  'LBL_LAYOUT_OPTIONS' => '布局选项',
  'LBL_LDAP' => 'LDAP',
  'LBL_LDAP_AUTHENTICATION' => 'LDAP认证',
  'LBL_LDAP_ERROR' => 'LDAP错误:请和管理员联系',
  'LBL_LDAP_EXTENSION_ERROR' => 'LDAP错误:服务未加载',
  'LBL_LIST_ACCEPT_STATUS' => '接收状态',
  'LBL_LIST_ADMIN' => '管理员',
  'LBL_LIST_DEPARTMENT' => '部门',
  'LBL_LIST_DESCRIPTION' => '说明',
  'LBL_LIST_EMAIL' => '电子邮件',
  'LBL_LIST_FORM_TITLE' => '用户',
  'LBL_LIST_GROUP' => '组',
  'LBL_LIST_LAST_NAME' => '姓',
  'LBL_LIST_MEMBERSHIP' => '成员关系',
  'LBL_LIST_NAME' => '姓名',
  'LBL_LIST_PASSWORD' => '密码',
  'LBL_LIST_PRIMARY_PHONE' => '常用电话',
  'LBL_LIST_STATUS' => '状态',
  'LBL_LIST_TITLE' => '职称',
  'LBL_LIST_USER_NAME' => '用户名',
  'LBL_LOCALE_DEFAULT_NAME_FORMAT' => '名字显示格式',
  'LBL_LOCALE_DESC_FIRST' => '[名]',
  'LBL_LOCALE_DESC_LAST' => '[姓]',
  'LBL_LOCALE_DESC_SALUTATION' => '[称谓]',
  'LBL_LOCALE_DESC_TITLE' => '[标题]',
  'LBL_LOCALE_EXAMPLE_NAME_FORMAT' => '实例',
  'LBL_LOCALE_NAME_FORMAT_DESC' => '设置如何显示名字。',
  'LBL_LOCALE_NAME_FORMAT_DESC_2' => '<i>“s”称谓<br>“f”名<br>“l”姓</i>',
  'LBL_LOGGED_OUT_1' => '您已经登出。重新登陆请点击',
  'LBL_LOGGED_OUT_2' => '这里',
  'LBL_LOGGED_OUT_3' => '。',
  'LBL_LOGIN' => '用户名',
  'LBL_LOGIN_ADMIN_CALL' => '请联系系统管理员或者使用找回密码.',
  'LBL_LOGIN_ATTEMPTS_OVERRUN' => '登录失败次数过多.',
  'LBL_LOGIN_BUTTON_KEY' => 'L',
  'LBL_LOGIN_BUTTON_LABEL' => '登录',
  'LBL_LOGIN_BUTTON_TITLE' => '登录[Alt+L]',
  'LBL_LOGIN_FORGOT_PASSWORD' => '忘记密码?',
  'LBL_LOGIN_LOGIN_TIME_ALLOWED' => '请重新登录',
  'LBL_LOGIN_LOGIN_TIME_DAYS' => '天.',
  'LBL_LOGIN_LOGIN_TIME_HOURS' => '小时.',
  'LBL_LOGIN_LOGIN_TIME_MINUTES' => '分钟.',
  'LBL_LOGIN_LOGIN_TIME_SECONDS' => '秒.',
  'LBL_LOGIN_OPTIONS' => '选项',
  'LBL_LOGIN_SUBMIT' => '提交',
  'LBL_LOGIN_WELCOME_TO' => '欢迎来到',
  'LBL_MAILBOX' => '已监听的文件夹',
  'LBL_MAILBOX_DEFAULT' => '收件箱',
  'LBL_MAILBOX_SSL_DESC' => '连接时使用SSL。如果不能连接，请检查您在安装PHP的时候，配置项中是否包含了“--with-imap-ssl”。',
  'LBL_MAILBOX_TYPE' => '可能的动作',
  'LBL_MAILMERGE' => '邮件合并',
  'LBL_MAILMERGE_TEXT' => '启用邮件合并(邮件合并必须由管理员在配置中启用)',
  'LBL_MAIL_FROMADDRESS' => '收件人地址',
  'LBL_MAIL_FROMNAME' => '收件人姓名',
  'LBL_MAIL_OPTIONS_TITLE' => '电子邮件选项',
  'LBL_MAIL_SENDTYPE' => '邮件传送代理',
  'LBL_MAIL_SMTPAUTH_REQ' => '使用SMTP 认证?',
  'LBL_MAIL_SMTPPASS' => 'SMTP 密码:',
  'LBL_MAIL_SMTPPORT' => 'SMTP 端口:',
  'LBL_MAIL_SMTPSERVER' => 'SMTP 服务器:',
  'LBL_MAIL_SMTPTYPE' => 'SMTP 服务器类型:',
  'LBL_MAIL_SMTPUSER' => 'SMTP 用户名:',
  'LBL_MAIL_SMTP_SETTINGS' => 'SMTP服务器规格',
  'LBL_MARK_READ' => '在服务器上保留消息',
  'LBL_MARK_READ_DESC' => '导入并标记邮件服务器上已读的消息；不要删除它们。',
  'LBL_MARK_READ_NO' => '导入后删除标记过的电子邮件',
  'LBL_MARK_READ_YES' => '导入后在服务器上保留电子邮件',
  'LBL_MAX_SUBTAB' => '子标签的数量',
  'LBL_MAX_SUBTAB_DESCRIPTION' => '显示在益出菜单前，每个标签中子标签的数量。',
  'LBL_MAX_TAB' => '标签的数量',
  'LBL_MAX_TAB_DESCRIPTION' => '显示在益出菜单前，页面上方的标签的数量。',
  'LBL_MESSENGER_ID' => 'IM名称',
  'LBL_MESSENGER_TYPE' => 'IM类型',
  'LBL_MISSING_DEFAULT_OUTBOUND_SMTP_SETTINGS' => '管理员尚未设置默的认邮件发送服务器。不能发送测试邮件。',
  'LBL_MOBILE_PHONE' => '手机',
  'LBL_MODIFIED_BY' => '修改人',
  'LBL_MODIFIED_BY_ID' => '修改人编号',
  'LBL_MODIFIED_USER_ID' => '修改用户 ID',
  'LBL_MODULE_NAME' => '用户',
  'LBL_MODULE_NAME_SINGULAR' => '用户',
  'LBL_MODULE_TITLE' => '用户:首页',
  'LBL_MY_TEAMS' => '我的团队',
  'LBL_NAME' => '全名',
  'LBL_NAVIGATION_PARADIGM' => '导航范例',
  'LBL_NEW_FORM_TITLE' => '新增用户',
  'LBL_NEW_PASSWORD' => '新密码',
  'LBL_NEW_PASSWORD1' => '密码',
  'LBL_NEW_PASSWORD2' => '确认密码',
  'LBL_NEW_USER_BUTTON_KEY' => 'N',
  'LBL_NEW_USER_BUTTON_LABEL' => '新增用户',
  'LBL_NEW_USER_BUTTON_TITLE' => '新增用户[Alt+N]',
  'LBL_NEW_USER_PASSWORD_1' => '密码修改成功.',
  'LBL_NEW_USER_PASSWORD_2' => '包含有系统生成链接的电子邮件已发送到该用户. 用户必须提供一个新的密码.',
  'LBL_NEW_USER_PASSWORD_3' => '密码生成成功.',
  'LBL_NORMAL_LOGIN' => '切换到常规视图',
  'LBL_NOTES' => '备忘录',
  'LBL_NO_KEY' => '关键字没有设置。请设置关键字使其能发布。',
  'LBL_NUMBER_GROUPING_SEP' => '千分符',
  'LBL_NUMBER_GROUPING_SEP_TEXT' => '分隔千位的字符',
  'LBL_OAUTH_TOKENS' => '授权标识',
  'LBL_OAUTH_TOKENS_SUBPANEL_TITLE' => '授权通道标识',
  'LBL_OFFICE_PHONE' => '办公电话',
  'LBL_OK' => '确定',
  'LBL_OLD_PASSWORD' => '旧密码',
  'LBL_ONLY' => '仅',
  'LBL_ONLY_SINCE' => '导入最后确认',
  'LBL_ONLY_SINCE_DESC' => '当使用POP3时，PHP将不能过滤新信息或未读信息。此标签可以核对上次收件箱里的信息。如果您的邮件服务器不支持IMAP，它将对此有很大改进。',
  'LBL_ONLY_SINCE_NO' => '不。检查此邮件服务器上的所有邮件。',
  'LBL_ONLY_SINCE_YES' => '是。',
  'LBL_OTHER' => '其它',
  'LBL_OTHER_EMAIL' => '其它电子邮件地址',
  'LBL_OTHER_PHONE' => '其它电话',
  'LBL_OWN_OPPS' => '无商业机会',
  'LBL_OWN_OPPS_DESC' => '如果这个用户未给指派商业机会，设置它为真。您应该忽略这个用户标记，他不是经理，没有涉及销售活动。在销售预测模块中使用。',
  'LBL_PASSWORD' => '密码',
  'LBL_PASSWORD_EXPIRATION_GENERATED' => '您的密码为系统生成',
  'LBL_PASSWORD_EXPIRATION_LOGIN' => '您的密码已过期. 请提供一个新密码.',
  'LBL_PASSWORD_EXPIRATION_TIME' => '您的密码已过期. 请提供一个新密码.',
  'LBL_PASSWORD_GENERATED' => '已生成新密码',
  'LBL_PASSWORD_SENT' => '密码已经更新',
  'LBL_PDF_FONT_NAME_DATA' => '资料字体名称',
  'LBL_PDF_FONT_NAME_DATA_TEXT' => '资料字体通常用于页脚',
  'LBL_PDF_FONT_NAME_MAIN' => '主字体名称',
  'LBL_PDF_FONT_NAME_MAIN_TEXT' => '主字体一般用于标题和正文',
  'LBL_PDF_FONT_SIZE_DATA' => '资料字体大小',
  'LBL_PDF_FONT_SIZE_DATA_TEXT' => '',
  'LBL_PDF_FONT_SIZE_MAIN' => '主字体大小',
  'LBL_PDF_FONT_SIZE_MAIN_TEXT' => '',
  'LBL_PDF_MARGIN_BOTTOM' => '下边距',
  'LBL_PDF_MARGIN_BOTTOM_TEXT' => '',
  'LBL_PDF_MARGIN_FOOTER' => '页脚',
  'LBL_PDF_MARGIN_FOOTER_TEXT' => '',
  'LBL_PDF_MARGIN_HEADER' => '页眉',
  'LBL_PDF_MARGIN_HEADER_TEXT' => '',
  'LBL_PDF_MARGIN_LEFT' => '左边距',
  'LBL_PDF_MARGIN_LEFT_TEXT' => '',
  'LBL_PDF_MARGIN_RIGHT' => '右边距',
  'LBL_PDF_MARGIN_RIGHT_TEXT' => '',
  'LBL_PDF_MARGIN_TOP' => '上边距',
  'LBL_PDF_MARGIN_TOP_TEXT' => '',
  'LBL_PDF_PAGE_FORMAT' => '页面格式',
  'LBL_PDF_PAGE_FORMAT_TEXT' => '页面使用的格式',
  'LBL_PDF_PAGE_ORIENTATION' => '以页面为基准',
  'LBL_PDF_PAGE_ORIENTATION_L' => '横向',
  'LBL_PDF_PAGE_ORIENTATION_P' => '纵向',
  'LBL_PDF_PAGE_ORIENTATION_TEXT' => '',
  'LBL_PDF_SETTINGS' => 'PDF 设置',
  'LBL_PHONE' => '电话',
  'LBL_PHONE_FAX' => '电话 传真',
  'LBL_PHONE_HOME' => '电话 首页',
  'LBL_PHONE_MOBILE' => '移动电话',
  'LBL_PHONE_OTHER' => '其它电话',
  'LBL_PHONE_WORK' => '工作电话',
  'LBL_PICK_TZ_DESCRIPTION' => '在继续以前，请从下面的时区列表中确认您的时区。您可以通过访问“我的帐户”来修改您的时区。',
  'LBL_PICK_TZ_WELCOME' => '欢迎来到Sugar Suite。',
  'LBL_PICTURE_FILE' => '照片',
  'LBL_PORT' => '邮件服务器端口',
  'LBL_PORTAL_ONLY' => '仅门户站点',
  'LBL_PORTAL_ONLY_DESC' => '门户服务专用. 此类型不能用于Sugar的web界面登录.',
  'LBL_PORTAL_ONLY_USER' => '门户专用用户',
  'LBL_POSTAL_CODE' => '邮编',
  'LBL_PRIMARY_ADDRESS' => '主要地址',
  'LBL_PRIVATE_TEAM_FOR' => '私有团队属于',
  'LBL_PROCESSING' => '正在处理',
  'LBL_PROMPT_TIMEZONE' => '时区提示',
  'LBL_PROMPT_TIMEZONE_TEXT' => '检查并提示用户在登录时确认时区。',
  'LBL_PROSPECT_LIST' => '目标列表',
  'LBL_PROVIDE_USERNAME_AND_EMAIL' => '提供一个用户名和电子邮件地址.',
  'LBL_PSW_MODIFIED' => '密码最后更新时间',
  'LBL_PUBLISH_KEY' => '公布密钥',
  'LBL_QUOTAS' => '定额',
  'LBL_REASS_ASSESSING' => '正在评估',
  'LBL_REASS_BUTTON_CLEAR' => '清除',
  'LBL_REASS_BUTTON_CONTINUE' => '继续',
  'LBL_REASS_BUTTON_GO_BACK' => '退回',
  'LBL_REASS_BUTTON_REASSIGN' => '重新指定',
  'LBL_REASS_BUTTON_RESTART' => '重新开始',
  'LBL_REASS_BUTTON_RETURN' => '返回',
  'LBL_REASS_BUTTON_SUBMIT' => '提交',
  'LBL_REASS_CANNOT_PROCESS' => '不能处理:',
  'LBL_REASS_CONFIRM_REASSIGN' => '您真要重新分配这个用户的所有记录吗?',
  'LBL_REASS_CONFIRM_REASSIGN_NO' => '否',
  'LBL_REASS_CONFIRM_REASSIGN_TITLE' => '重新分配',
  'LBL_REASS_CONFIRM_REASSIGN_YES' => '是',
  'LBL_REASS_DESC_PART1' => '选择包含记录的模块，从特定的用户分配给另一用户. <br/><br/><br />                                                            点击继续查看会在每个选中的模块中更新的记录数据.<br />                                                            点击取消退出页面，不重新分配任何记录.',
  'LBL_REASS_DESC_PART2' => '选择哪些模块，用以运行工作流程，发送分配通知，并在重新分配时跟踪审计。',
  'LBL_REASS_FAILED' => '失败',
  'LBL_REASS_FAILED_SAVE' => '记录保存失败',
  'LBL_REASS_FILTERS' => '过滤器',
  'LBL_REASS_FROM' => '从',
  'LBL_REASS_HAVE_BEEN_UPDATED' => '已经被更新:',
  'LBL_REASS_MOD_REASSIGN' => '模块包括在调动中:',
  'LBL_REASS_NONE' => '无',
  'LBL_REASS_NOTES_ONE' => '给自己分配记录将不触发分配通知.',
  'LBL_REASS_NOTES_THREE' => '即使你不包含审计, 修改日期和修改人仍然会随着更新.',
  'LBL_REASS_NOTES_TITLE' => '注释:',
  'LBL_REASS_NOTES_TWO' => '在分配中包括工作流程，通知，跟踪审计会显著变慢的.',
  'LBL_REASS_NOT_PROCESSED' => '不能被处理:',
  'LBL_REASS_RECORDS_FROM' => '条记录来源于',
  'LBL_REASS_SCRIPT_TITLE' => '记录重新分配',
  'LBL_REASS_STEP2_DESC' => '下面列示的是有效的在用户团队窗体，但是并不在用户团队。所有的纪录，在由用户团队将不会显示在用户的团队，除非团队价值的映射.',
  'LBL_REASS_STEP2_TITLE' => '团队重新分配',
  'LBL_REASS_SUCCESSFUL' => '成功',
  'LBL_REASS_SUCCESS_ASSIGN' => '成功分配',
  'LBL_REASS_TEAMS_GOOD_MSG' => '该用户已访问到所有用户团队窗口. 没有映射的必要. 在5秒钟重定向到下页.',
  'LBL_REASS_TEAM_NO_CHANGE' => '-- 无改变 --',
  'LBL_REASS_TEAM_SET_TO' => '并且团队被设置为',
  'LBL_REASS_TEAM_TO' => '设置团队:',
  'LBL_REASS_THE_FOLLOWING' => '下面',
  'LBL_REASS_TO' => '到',
  'LBL_REASS_UPDATE_COMPLETE' => '完全更新',
  'LBL_REASS_USER_FROM' => '从用户:',
  'LBL_REASS_USER_FROM_TEAM' => '从用户团队:',
  'LBL_REASS_USER_TO' => '到用户:',
  'LBL_REASS_USER_TO_TEAM' => '到用户团队:',
  'LBL_REASS_VERBOSE_HELP' => '选择此选项以查看有关重新分配任务，涉及工作流程的详细资料。',
  'LBL_REASS_VERBOSE_OUTPUT' => '冗长的输出(只应用在工作流中的调动任务)',
  'LBL_REASS_WILL_BE_UPDATED' => '将被更新.',
  'LBL_REASS_WORK_NOTIF_AUDIT' => '包括工作流/通知/审计 (显著放慢)',
  'LBL_RECAPTCHA_FILL_FIELD' => '输入图片中的文本.',
  'LBL_RECAPTCHA_IMAGE' => '切换到图像',
  'LBL_RECAPTCHA_INSTRUCTION' => '输入下面的两个字',
  'LBL_RECAPTCHA_INSTRUCTION_OPPOSITE' => '在右边输入两个字',
  'LBL_RECAPTCHA_INVALID_PRIVATE_KEY' => '无效的Recaptcha密钥',
  'LBL_RECAPTCHA_INVALID_REQUEST_COOKIE' => '验证码不正确.',
  'LBL_RECAPTCHA_NEW_CAPTCHA' => '重新获取CAPTCHA',
  'LBL_RECAPTCHA_SOUND' => '切换到声音',
  'LBL_RECAPTCHA_UNKNOWN' => '未知的Recaptcha错误',
  'LBL_RECEIVE_NOTIFICATIONS' => '在指派的时候通知',
  'LBL_RECEIVE_NOTIFICATIONS_TEXT' => '当有记录指派给您的时候，接收电子邮件通知。',
  'LBL_REGISTER' => '新用户? 请注册',
  'LBL_REGULAR_DESC' => '用户根据团队安全和角色权限访问模块和记录.',
  'LBL_REGULAR_USER' => '普通用户',
  'LBL_REMINDER' => '显示提醒?',
  'LBL_REMINDER_EMAIL' => '电子邮件',
  'LBL_REMINDER_EMAIL_ALL_INVITEES' => '所有被邀请者邮件',
  'LBL_REMINDER_POPUP' => '弹出',
  'LBL_REMINDER_TEXT' => '为即将到来的电话或者会议，弹出提醒',
  'LBL_REMOVED_TABS' => '管理员移除的标签',
  'LBL_REPORTS_TO' => '经理',
  'LBL_REPORTS_TO_ID' => '经理编号:',
  'LBL_REPORTS_TO_NAME' => '经理',
  'LBL_REQUEST_SUBMIT' => '您的请求已提交.',
  'LBL_RESET_DASHBOARD' => '统计图',
  'LBL_RESET_PREFERENCES' => '恢复默认值',
  'LBL_RESET_PREFERENCES_WARNING' => '您确定要重新设置您所有的参数?',
  'LBL_RESET_PREFERENCES_WARNING_USER' => '你确定要重置该用户的偏好设置吗？',
  'LBL_RESET_TO_DEFAULT' => '重设为默认值',
  'LBL_RESOURCE_NAME' => '名称',
  'LBL_RESOURCE_TYPE' => '类型',
  'LBL_ROLES_SUBPANEL_TITLE' => '角色',
  'LBL_SALUTATION' => '问候',
  'LBL_SAVED_SEARCH' => '保存查找 & 布局',
  'LBL_SEARCH_FORM_TITLE' => '查找用户',
  'LBL_SEARCH_URL' => '查找数据单元',
  'LBL_SELECT_CHECKED_BUTTON_LABEL' => '选择打钩的用户',
  'LBL_SELECT_CHECKED_BUTTON_TITLE' => '选择打钩的用户',
  'LBL_SERVER_OPTIONS' => '高级设置',
  'LBL_SERVER_TYPE' => '邮件服务器协议',
  'LBL_SERVER_URL' => '邮件服务器地址',
  'LBL_SESSION_EXPIRED' => '您已经登出，因为您的会话已过期。',
  'LBL_SETTINGS_URL' => '网址',
  'LBL_SETTINGS_URL_DESC' => '当为Microsoft&reg;Outlook&reg;和Microsoft&reg;Word&reg;的Sugar插件建立登录设置时，使用这个网址。',
  'LBL_SHOWOPTIONS' => '显示选项',
  'LBL_SHOW_ON_EMPLOYEES' => '显示员工激励',
  'LBL_SIGNATURE' => '签名',
  'LBL_SIGNATURES' => '签名',
  'LBL_SIGNATURE_DEFAULT' => '使用签名?',
  'LBL_SIGNATURE_HTML' => 'HTML签名',
  'LBL_SIGNATURE_NAME' => '姓名',
  'LBL_SIGNATURE_PREPEND' => '使用上面签名回复?',
  'LBL_SMTP_SERVER_HELP' => '该SMTP邮件服务器用来发送电子邮件。请提供您账户的用户名和密码以便使用此邮件服务器。',
  'LBL_SSL' => '使用SSL',
  'LBL_SSL_DESC' => '连接到您的邮件服务器的时候，使用安全端口层。',
  'LBL_STATE' => '省份',
  'LBL_STATUS' => '状态',
  'LBL_SUBPANEL_LINKS' => '面板链接',
  'LBL_SUBPANEL_LINKS_DESCRIPTION' => '在详细视图中，显示一行面板的快速链接。',
  'LBL_SUGAR_LOGIN' => '是sugar用户',
  'LBL_SUPPORTED_THEME_ONLY' => '只有影响主题才支持这个选项。',
  'LBL_SWAP_LAST_VIEWED_DESCRIPTION' => '如果选中，在边上显示最近查看栏。否则的话，它会在顶部显示。',
  'LBL_SWAP_LAST_VIEWED_POSITION' => '边上显示最近查看',
  'LBL_SWAP_SHORTCUT_DESCRIPTION' => '如果选中，在顶部显示快捷方式栏。否则的话，它会在边上显示。',
  'LBL_SWAP_SHORTCUT_POSITION' => '顶部显示快捷方式',
  'LBL_SYSTEM_GENERATED_PASSWORD' => '系统生成密码',
  'LBL_SYSTEM_SIG_DIGITS' => '系统重要数字',
  'LBL_SYSTEM_SIG_DIGITS_DESC' => '全系统所用十进制和浮点应采用的十进制小数位数，例如报表内的货币和平均值。',
  'LBL_TAB_TITLE_EMAIL' => '电子邮件设置',
  'LBL_TAB_TITLE_USER' => '用户设置',
  'LBL_TEAMS' => '团队',
  'LBL_TEAM_MEMBERSHIP' => '团队成员',
  'LBL_TEAM_SET' => '团队设置',
  'LBL_TEAM_UPLINE' => '成员汇报给',
  'LBL_TEAM_UPLINE_EXPLICIT' => '成员',
  'LBL_TEST_BUTTON_KEY' => 't',
  'LBL_TEST_BUTTON_TITLE' => '测试[Alt+T]',
  'LBL_TEST_SETTINGS' => '测试设置',
  'LBL_TEST_SUCCESSFUL' => '连接完全成功。',
  'LBL_THEME' => '主题',
  'LBL_THEMEPREVIEW' => '预览',
  'LBL_THEME_COLOR' => '颜色',
  'LBL_THEME_FONT' => '字体',
  'LBL_TIMEZONE' => '时区',
  'LBL_TIMEZONE_DST' => '夏令时',
  'LBL_TIMEZONE_DST_TEXT' => '遵循夏令时',
  'LBL_TIMEZONE_TEXT' => '设置当前的时区',
  'LBL_TIME_FORMAT' => '时间格式',
  'LBL_TIME_FORMAT_TEXT' => '设置时间戳的显示格式',
  'LBL_TITLE' => '职称',
  'LBL_TLS' => '使用TLS',
  'LBL_TLS_DESC' => '连接邮件服务器时使用传输层安全协议–如果您的邮件服务器支持这此协议，请只使用这一个。',
  'LBL_TOGGLE_ADV' => '显示高级选项',
  'LBL_TOO_MANY_CONCURRENT' => '本次会议已经结束，因为该用户已在另一地址登录。',
  'LBL_UPDATE_FINISH' => '更新完成',
  'LBL_USER' => '用户',
  'LBL_USER_ACCESS' => '进入',
  'LBL_USER_HASH' => '密码',
  'LBL_USER_HOLIDAY_SUBPANEL_TITLE' => '用户假期',
  'LBL_USER_INFORMATION' => '用户信息',
  'LBL_USER_LOCALE' => '区域设置',
  'LBL_USER_NAME' => '用户名',
  'LBL_USER_NAME_FOR_ROLE' => '用户/团队/角色',
  'LBL_USER_PREFERENCES' => '用户首选项',
  'LBL_USER_SETTINGS' => '用户设置',
  'LBL_USER_TYPE' => '用户类型',
  'LBL_USE_GROUP_TABS' => '分组模块',
  'LBL_USE_REAL_NAMES' => '显示全名',
  'LBL_USE_REAL_NAMES_DESC' => '显示用户全名来代替用户名',
  'LBL_WIZARD_BACK_BUTTON' => '< 退后',
  'LBL_WIZARD_FINISH' => '点击下面的 <b>结束</b> 保存您的设置并且开始使用Sugar。更多关于使用Sugar的信息:<br /><br /><br /><table cellpadding=0 cellspacing=0><br /><tr><td><img src=include/images/university.png style="margin-right: 5px;"></td><td><a href="http://www.sugarcrm.com/university" target="_blank"><b>Sugar University</b></a><br>End-user and System Administrator Training and Resources</td></tr><br /><tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr><br /><tr><td><img src=include/images/docs.png style="margin-right: 5px;"></td><td><a href="http://docs.sugarcrm.com/" target="_blank"><b>Documentation</b></a><br>Product Guides and Release Notes</td></tr><br /><tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr><br /><tr><td><img src=include/images/kb.png style="margin-right: 5px;"></td><td><a href="http://kb.sugarcrm.com/" target="_blank"><b>Knowledge Base</b></a><br>Tips from SugarCRM Support for performing common tasks and processes in Sugar</td></tr><br /><tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr><br /><tr><td><img src=include/images/forums.png style="margin-right: 5px;"></td><td><a href="http://www.sugarcrm.com/forums" target="_blank"><b>Forums</b></a><br>Forums dedicated to the Sugar Community to discuss topics of interest with each other and with SugarCRM Developers</td></tr><br /></table>',
  'LBL_WIZARD_FINISH1' => '接下来您要做什么?',
  'LBL_WIZARD_FINISH10' => '使用工作室创建和管理程序字段和版式.',
  'LBL_WIZARD_FINISH11' => '参观Sugar大学',
  'LBL_WIZARD_FINISH12' => '查找可以帮助您以系统管理员或者终端用户角色的培训资料和课程.',
  'LBL_WIZARD_FINISH14' => '文件',
  'LBL_WIZARD_FINISH15' => '产品介绍和发行说明',
  'LBL_WIZARD_FINISH16' => '知识库',
  'LBL_WIZARD_FINISH17' => 'ugarCRM提供在Sugar内开展一般任务和流程的小知识',
  'LBL_WIZARD_FINISH18' => '论坛',
  'LBL_WIZARD_FINISH19' => '论坛致力于为Sugar社区提供与每位成员之间以及和SugarCRM开发者之间相互讨论的平台',
  'LBL_WIZARD_FINISH2' => '开始使用Sugar',
  'LBL_WIZARD_FINISH2DESC' => '直接到程序首页.',
  'LBL_WIZARD_FINISH3' => '导入数据',
  'LBL_WIZARD_FINISH4' => '从外部资源导入数据到系统内.',
  'LBL_WIZARD_FINISH5' => '创建用户',
  'LBL_WIZARD_FINISH6' => '创建新的用户账号以进入程序.',
  'LBL_WIZARD_FINISH7' => '查看和管理程序设置',
  'LBL_WIZARD_FINISH8' => '管理高级设置，包括默认程序设置.',
  'LBL_WIZARD_FINISH9' => '设置程序',
  'LBL_WIZARD_FINISH_BUTTON' => '结束',
  'LBL_WIZARD_FINISH_TAB' => '结束',
  'LBL_WIZARD_FINISH_TITLE' => '您已经装备好了使用Sugar!',
  'LBL_WIZARD_LOCALE' => '您的本地设置',
  'LBL_WIZARD_LOCALE_DESC' => '指定您在Sugar中的时区和日期，以及货币和姓名。',
  'LBL_WIZARD_NEXT_BUTTON' => '下一步 >',
  'LBL_WIZARD_PERSONALINFO' => '您的信息',
  'LBL_WIZARD_PERSONALINFO_DESC' => '提供个人信息。您提供的个人信息将会被其他的Sugar用户看到。标有*是必填项。',
  'LBL_WIZARD_SKIP_BUTTON' => '跳过',
  'LBL_WIZARD_SMTP' => '您的邮件帐户',
  'LBL_WIZARD_SMTP_DESC' => '为默认的发送电子邮件服务器提供您的账号和密码。',
  'LBL_WIZARD_TITLE' => '用户向导',
  'LBL_WIZARD_WELCOME' => '点击下一步来配置使用Sugar一些基本设置。',
  'LBL_WIZARD_WELCOME_NOSMTP' => '点击下一步来配置使用Sugar一些基本设置。',
  'LBL_WIZARD_WELCOME_TAB' => '欢迎',
  'LBL_WIZARD_WELCOME_TITLE' => '欢迎来到Sugar!',
  'LBL_WORKSHEETS' => '工作表',
  'LBL_WORK_PHONE' => '办公电话',
  'LBL_YAHOOMAIL_SMTPPASS' => 'Yahoo! Mail密码：',
  'LBL_YAHOOMAIL_SMTPUSER' => 'Yahoo! Mail ID：',
  'LBL_YOUR_PUBLISH_URL' => '在我的位置发布',
  'LBL_YOUR_QUERY_URL' => '您的查询URL',
  'LNK_EDIT_TABS' => '编辑标签',
  'LNK_IMPORT_USERS' => '导入用户',
  'LNK_NEW_GROUP_USER' => '创建组用户',
  'LNK_NEW_PORTAL_USER' => '创建门户用户',
  'LNK_NEW_USER' => '新增用户',
  'LNK_REASSIGN_RECORDS' => '重新分配记录',
  'LNK_USER_LIST' => '用户',
  'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '您是否确定要移除这个用户的成员关系？',
);
