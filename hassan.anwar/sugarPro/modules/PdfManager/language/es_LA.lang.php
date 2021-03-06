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
  'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
  'LBL_ALERT_SWITCH_BASE_MODULE' => 'ADVERTENCIA: Si cambia el módulo primario, todos los campos ya agregados a la plantilla tendrán que ser eliminados.',
  'LBL_ASSIGNED_TO_ID' => 'ID de Usuario Asignado',
  'LBL_ASSIGNED_TO_NAME' => 'Asignado a',
  'LBL_AUTHOR' => 'Autor',
  'LBL_BASE_MODULE' => 'Módulo',
  'LBL_BASE_MODULE_POPUP_HELP' => 'Seleccione un módulo donde quiera disponer de esta plantilla.',
  'LBL_BODY_HTML' => 'Plantilla',
  'LBL_BODY_HTML_POPUP_HELP' => 'Cree la plantilla utilizando el editor HTML. Después de guardar la plantilla, podrá ver una vista previa de la versión en PDF de la misma.',
  'LBL_BODY_HTML_POPUP_QUOTES_HELP' => 'Cree la plantilla utilizando el editor HTML. Después de guardar la plantilla, podrá ver una vista previa de la versión en PDF de la misma.<br /><br />Para editar el bucle utilizado para crear los elementos de la línea de Productos, haga clic en el botón "HTML" del editor para acceder al código. El código está dentro de &lt;!--START_BUNDLE_LOOP--&gt;, &lt;!--START_PRODUCT_LOOP--&gt;, &lt;!--END_PRODUCT_LOOP--&gt; and &lt;!--END_BUNDLE_LOOP--&gt;.',
  'LBL_BTN_INSERT' => 'Insertar',
  'LBL_CREATED' => 'Creado Por',
  'LBL_CREATED_ID' => 'Creado Por Id',
  'LBL_CREATED_USER' => 'Creada por Usuario',
  'LBL_DATE_ENTERED' => 'Fecha de Creación',
  'LBL_DATE_MODIFIED' => 'Fecha de Modificación',
  'LBL_DELETED' => 'Eliminado',
  'LBL_DESCRIPTION' => 'Descripción',
  'LBL_EDITVIEW_PANEL1' => 'Propiedades del Documento PDF',
  'LBL_EMAIL_PDF_DEFAULT_DESCRIPTION' => 'Aquí está el archivo que ha solicitado (Puede cambiar este texto)',
  'LBL_FIELD' => 'Campo',
  'LBL_FIELDS_LIST' => 'Campos',
  'LBL_FIELD_POPUP_HELP' => 'Seleccione un campo para introducir la variable para el valor del campo. Para seleccionar campos de un módulo principal, seleccione primero el módulo en el área de enlaces en la parte inferior de la lista de campos en el primer menú desplegable, posteriormente seleccione el campo en el segundo menú desplegable.',
  'LBL_FOOTER_TEXT' => 'Texto de pie',
  'LBL_HEADER_LOGO_FILE' => 'Archivo de Logo Encabezador',
  'LBL_HEADER_LOGO_POPUP_HELP' => 'El archivo subido no será mostrado mientras haya ediciones. Para ver la plantilla final deberás de guardarlo y luego previsualizarlo.',
  'LBL_HEADER_TEXT' => 'Texto de cabecera',
  'LBL_HEADER_TITLE' => 'Título de la Cabecera',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Ver Historial',
  'LBL_HOMEPAGE_TITLE' => 'Mis Plantillas PDF',
  'LBL_ID' => 'ID',
  'LBL_KEYWORDS' => 'Palabra(s) clave',
  'LBL_KEYWORDS_POPUP_HELP' => 'Asociar Palabras clave con el documento, generalmente en la forma "palabra1 palabra2..."',
  'LBL_LINK_LIST' => 'Enlaces',
  'LBL_LIST_FORM_TITLE' => 'Lista de Plantillas PDF',
  'LBL_LIST_NAME' => 'Nombre',
  'LBL_MODIFIED' => 'Modificado Por',
  'LBL_MODIFIED_ID' => 'Modificado Por Id',
  'LBL_MODIFIED_NAME' => 'Modificado Por Nombre',
  'LBL_MODIFIED_USER' => 'Modificada por Usuario',
  'LBL_MODULE_NAME' => 'Administrador PDF',
  'LBL_MODULE_NAME_SINGULAR' => 'Administrador PDF',
  'LBL_MODULE_TITLE' => 'Administrador PDF',
  'LBL_NAME' => 'Nombre',
  'LBL_NEW_FORM_TITLE' => 'Nueva Plantilla PDF',
  'LBL_PAYMENT_TERMS' => 'Forma de Pago:',
  'LBL_PDFMANAGER_SUBPANEL_TITLE' => 'Administrador PDF',
  'LBL_PREVIEW' => 'Vista Preliminar',
  'LBL_PUBLISHED' => 'Publicado',
  'LBL_PUBLISHED_POPUP_HELP' => 'Publicar una plantilla para que pueda estar disponible para los usuarios.',
  'LBL_PURCHASE_ORDER_NUM' => 'Núm. Pedido de Compra:',
  'LBL_SEARCH_FORM_TITLE' => 'Búsqueda en PDF Manager',
  'LBL_SUBJECT' => 'Asunto',
  'LBL_TEAM' => 'Equipos',
  'LBL_TEAMS' => 'Equipos',
  'LBL_TEAM_ID' => 'Id de Equipo',
  'LBL_TITLE' => 'Título',
  'LBL_TPL_BILL_TO' => 'Cobrar a',
  'LBL_TPL_CURRENCY' => 'Moneda:',
  'LBL_TPL_DISCOUNT' => 'Descuento:',
  'LBL_TPL_DISCOUNTED_SUBTOTAL' => 'Subtotal Descontado:',
  'LBL_TPL_EXT_PRICE' => 'Precio Ext.',
  'LBL_TPL_GRAND_TOTAL' => 'Totales',
  'LBL_TPL_INVOICE' => 'Factura',
  'LBL_TPL_INVOICE_DESCRIPTION' => 'Esta plantilla es utilizada para imprimir Facturas en PDF.',
  'LBL_TPL_INVOICE_NAME' => 'Factura',
  'LBL_TPL_INVOICE_NUMBER' => 'Número de factura:',
  'LBL_TPL_INVOICE_TEMPLATE_NAME' => 'factura',
  'LBL_TPL_LIST_PRICE' => 'Precio de Lista',
  'LBL_TPL_PART_NUMBER' => 'Número de Pieza',
  'LBL_TPL_PRODUCT' => 'Producto',
  'LBL_TPL_QUANTITY' => 'Cantidad',
  'LBL_TPL_QUOTE' => 'Cotización',
  'LBL_TPL_QUOTE_DESCRIPTION' => 'Esta plantilla es utilizada para imprimir Presupuestos en PDF.',
  'LBL_TPL_QUOTE_NAME' => 'Cotización',
  'LBL_TPL_QUOTE_NUMBER' => 'Número de Cotización:',
  'LBL_TPL_QUOTE_TEMPLATE_NAME' => 'cotización',
  'LBL_TPL_RLI' => 'Ganancia de Artículo',
  'LBL_TPL_SALES_PERSON' => 'Vendedor:',
  'LBL_TPL_SHIPPING' => 'Envío:',
  'LBL_TPL_SHIPPING_PROVIDER' => 'Proveedor de Transporte:',
  'LBL_TPL_SHIP_TO' => 'Enviar a',
  'LBL_TPL_SUBTOTAL' => 'Subtotal:',
  'LBL_TPL_TAX' => 'Impuesto:',
  'LBL_TPL_TAX_RATE' => 'Tipo de Impuesto:',
  'LBL_TPL_TOTAL' => 'Total',
  'LBL_TPL_UNIT_PRICE' => 'Precio Unitario',
  'LBL_TPL_VALID_UNTIL' => 'Válido hasta:',
  'LNK_EDIT_PDF_TEMPLATE' => 'Editar plantilla PDF',
  'LNK_IMPORT_PDFMANAGER' => 'Importar Plantillas PDF',
  'LNK_LIST' => 'Ver Plantillas PDF',
  'LNK_NEW_RECORD' => 'Crear Plantilla PDF',
);

