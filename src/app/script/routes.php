<?php

/**
 * Part of The Straight Framework.
 */

/* load the Straight Framework  */
require PATH_LIB . '/Straight/straight.php';

require PATH_LIB . '/Dropdown.php';

/* English dictionary */
require PATH_I18N . '/en/en.php';

require PATH_CONFIG . '/settings.php';
require_once PATH_CONFIG . "/rb_config.php";
require_once PATH_CONFIG . "/constants.php";

$isAjax = strpos($_SERVER["REQUEST_URI"], 'ajax') > 0 || strpos($_SERVER["REQUEST_URI"], 'webhook') > 0 || strpos($_SERVER["REQUEST_URI"], 'corn') > 0;

function isLoginSessionExpired()
{

    $login_session_duration = 60 * 60 * 24;
    $current_time = time();
    if (isset($_SESSION["session_time"]) and isset($_SESSION["user_id"])) {
        if (((time() - $_SESSION["session_time"]) > $login_session_duration)) {
            return true;
        }
    }
    return false;

}

function secure($min_level = 0)
{

    if (empty($_SESSION["user_id"]) || isLoginSessionExpired()) {
        session_destroy();
        header("Location: /login?session_invalid");
        exit();
    } else if ($_SESSION['access'] < $min_level) {
        header("Location: /error-500.html?permission_denied");
        exit();
    }
    $isAjax = strpos($_SERVER["REQUEST_URI"], 'ajax') > 0;

    if (!$isAjax) {
        view('layout/head');
        view('layout/partial/navigation');
    }
}
/* Define your routes like this */

/* URL: GET / */
function __do_get()
{

    secure();
    view('welcome', ['greeting' => dict('welcome')]);
}

function __do_get_login()
{
    view('layout/head');
    view('login', ['email' => isset($_COOKIE['email']) ? $_COOKIE['email'] : '', 'message' => '']);
}
function __do_post_login()
{
    require_once PATH_CNTRL . "/login.controller.php";
    _login_attempt();

}
function __do_get_logout()
{

    session_destroy();
    header("Location: /login");
    exit();
}

function __do_get_archive()
{
    secure();
    view("empty");
}

function __do_get_rfqexcelajax($part)
{
    secure();
    $action = "rfq_excel";
    require_once PATH_CNTRL . "/rfqdocs.controller.php";
}
function __do_post_rfqanalyzeajax()
{
    secure();
    require_once PATH_CNTRL . "/rfqanalyze.controller.php";
}
function __do_get_rfqpdfajax($part)
{
    secure();
    $action = "rfq_pdf";
    require_once PATH_CNTRL . "/rfqdocs.controller.php";
}
function __do_get_rfqorderpdfajax($part)
{
    secure();
    $action = "rfq_order_pdf";
    require_once PATH_CNTRL . "/rfqdocs.controller.php";
}
function __do_post_sendToSupplerajax($part)
{
    secure();
    $action = "email_rfq_excel";
    require_once PATH_CNTRL . "/rfqdocs.controller.php";
}
function __do_get_aserial()
{
    secure();
    view("aserial");
}
function __do_get_newitemserial()
{
    secure();
    view("newitemserial");
}

function __do_get_admin($part)
{
    secure();
    view("admin", ["part" => $part]);
}

function __do_post_adminajax($part)
{
    secure();
    $action = $_POST["action"];
    if (isset($_POST["t"])) {
        $part = $_POST["t"];
    }

    if (isset($_POST["f"])) {
        $key = $_POST["f"];
    }

    require_once PATH_CNTRL . "/admin.controller.php";
}

function __do_get_accstocks($part)
{
    secure();

    $BATCHED = isset($_GET["BATCHED"]) ? $_GET["BATCHED"] : 0;

    view("accstocks", ["part" => $part, "BATCHED" => $BATCHED]);
}
function __do_get_accstocks_batch($part)
{
    secure();
    view("accstocks", ["part" => $part, "BATCHED" => 1]);
}

function __do_get_componentstocks($part)
{
    secure();

    $BATCHED = isset($_GET["BATCHED"]) ? $_GET["BATCHED"] : 0;

    view("componentstocks", ["part" => $part, "BATCHED" => $BATCHED]);
}
function __do_get_componentstocks_batch($part)
{
    secure();
    view("componentstocks", ["part" => $part, "BATCHED" => 1]);
}

function __do_post_componentstocksajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/componentstock.controller.php";
}

function __do_get_stocks($part)
{
    secure();
    view("stocks", ["part" => $part]);
}

function __do_get_stocks_batch($part)
{
    secure();
    view("stocks", ["part" => $part, "BATCHED" => 1]);
}

function __do_get_report($part)
{
    secure();
    if ($part == "faulty_acc_report") {
        view("report_faulty_acc_report", ["part" => $part]);
    } else if ($part == "sold_laptops_check") {
        view("report_sold_laptops_check", ["part" => $part]);
    } else if ($part == "laptop_order_profit") {
        view("report_laptop_order_profit", ["part" => $part]);
    } else if ($part == "laptop_sales_date") {
        view("report_laptop_sales_date", ["part" => $part]);
    } else if ($part == "laptop_stock") {
        view("report_laptop_stock", ["part" => $part]);
    } else if ($part == "nwp_stock") {
        view("nwp_stock", ["part" => $part]);
    } else if ($part == "dp_stock") {
        view("dp_stock", ["part" => $part]);
    } else if ($part == "acc_stock") {
        view("acc_stock", ["part" => $part]);
    } else if ($part == "acc_order_profit") {
        view("report_acc_order_profit", ["part" => $part]);
    } else {
        view("report", ["part" => $part]);
    }
}

function __do_get_tool2($part)
{
    secure();
    view("tool2", ["part" => $part]);
}
function __do_post_tool2($part)
{
    secure();
    view("tool2", ["part" => $part]);
}

function __do_post_tool2ajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/tool2.controller.php";
}

function __do_get_tool($part)
{
    secure();
    view("tool", ["part" => $part]);
}
function __do_post_tool($part)
{
    secure();
    view("tool", ["part" => $part]);
}

function __do_post_toolajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/tool.controller.php";
}

function __do_get_supplier()
{

    secure();
    view("supplier", []);
}

function __do_get_rfqstocks()
{
    secure();
    view("rfqstocks", []);
}

function __do_get_rfqstockslow()
{
    secure();
    view("rfqstocks", ["option" => "low"]);
}

function __do_get_rfqstocksqty()
{
    secure();
    view("rfqstocks", ["option" => "qty"]);
}

function __do_get_rfqstocksoos()
{
    secure();
    view("rfqstocks", ["option" => "oos"]);
}

function __do_get_rfqstocksdisabled()
{
    secure();
    view("rfqstocks", ["option" => "dis"]);
}

function __do_get_rfqstocksqtyignored()
{
    secure();
    view("rfqstocks", ["option" => "qtyignored"]);
}

function __do_get_rfqstocksisassembled()
{
    secure();
    view("rfqstocks", ["option" => "isassembled"]);
}

function __do_get_assemblysuggestions($part)
{
    secure();
    if ($part == "existing_allowed") {
        view("assemblysuggestions", ["new" => 0, "ignored" => 0]);
    } else if ($part == "existing_ignored") {
        view("assemblysuggestions", ["new" => 0, "ignored" => 1]);
    } else if ($part == "new_allowed") {
        view("assemblysuggestions", ["new" => 1, "ignored" => 0]);
    } else {
       view("assemblysuggestions", ["new" => 1, "ignored" => 1]);
    }
}

function __do_post_assemblysuggestionsajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/assemblysuggestions.controller.php";
}

function __do_get_assemblysuggestionsexcelajax()
{
    secure();
    $action = "assemblysuggestions_excel";
    require_once PATH_CNTRL . "/assemblysuggestions.controller.php";
}

function __do_post_laptopsalesdateexcelajax()
{
    secure();
    $action = "laptopsalesdate_excel";
    require_once PATH_CNTRL . "/laptopsalesdatereport.controller.php";
}

function __do_get_newassemblysuggestionsexcelajax()
{
    secure();
    $action = "assemblysuggestions_excel_new";
    require_once PATH_CNTRL . "/assemblysuggestions.controller.php";
}

function __do_get_rfqstocksonorder()
{
    secure();
    view("rfqstocksonorder", []);
}

function __do_post_rfqstocksonorderajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/rfqstocksonorder.controller.php";
}

function __do_post_rfqstocksajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/rfqstocks.controller.php";
}

function __do_get_rfqstockcorn()
{
    $action = "sync";
    require_once PATH_CNTRL . "/rfqstocks.controller.php";
}

function __do_post_supplierajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/supplier.controller.php";
}
function __do_get_stock($id)
{
    secure();
    view("stock", ["id" => $id]);
}

function __do_get_accorder($id)
{
    secure();
    view("accorder", ["id" => $id]);
}

function __do_get_accorders()
{
    secure();
    view("accorders", []);
}

function __do_post_accordersajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/accorder.controller.php";
}
function __do_get_accorderin($id)
{
    secure();
    view("accorder", ["id" => $id, "in" => true]);
}

function __do_get_orders()
{
    secure();
    view("orders", []);
}

function __do_get_ebayorders()
{
    secure();
    view("ebay_orders", []);
}

function __do_post_ordersajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/order.controller.php";
}

function __do_post_ebayordersajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/ebayorder.controller.php";
}

function __do_get_order($id)
{
    secure();
    view("order", ["id" => $id]);
}

function __do_get_ebayorder($id)
{
    secure();
    view("ebay_order", ["id" => $id]);
}

function __do_get_orderin($id)
{
    secure();
    view("order", ["id" => $id, "in" => true]);
}

function __do_get_neworder()
{
    secure();
    view("order", []);
}

function __do_get_newebayorder()
{
    secure();
    view("ebay_order", []);
}
function __do_get_newaccorder()
{
    secure();
    view("accorder", []);
}
function __do_post_rmacprintajax($part)
{

    secure();
    view("rmacprint", ["part" => $part]);
}

function __do_post_newitemstocksprintajax($part)
{

    secure();
    view("newitemstocksprint", ["part" => $part]);
}
function __do_get_newitemstocksprintajax($part)
{

    secure();

    view("newitemstocksprint", ["part" => $part]);
}

function __do_post_componentstocksprintajax($part)
{

    secure();
    view("componentstocksprint", ["part" => $part]);
}
function __do_get_componentstocksprintajax($part)
{

    secure();

    view("componentstocksprint", ["part" => $part]);
}

function __do_post_accstocksprintajax($part)
{

    secure();
    view("accstocksprint", ["part" => $part]);
}
function __do_get_accstocksprintajax($part)
{

    secure();

    view("accstocksprint", ["part" => $part]);
}
function __do_post_stocksprintajax($part)
{

    secure();
    view("stocksprint", ["part" => $part]);
}
function __do_get_stocksprintajax($part)
{

    secure();
    view("stocksprint", ["part" => $part]);
}
function __do_post_stocksajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/stock.controller.php";
}

function __do_post_accstocksajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/accstock.controller.php";
}
function __do_get_label()
{
    secure();
    view("label", []);
}
function __do_post_labelajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/label.controller.php";
}

function __do_get_productlookup()
{
    secure();
    view("productlookup", []);
}
function __do_post_productlookupajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/productlookup.controller.php";
}

function __do_get_dispatch()
{
    secure();
    view("dispatch", []);
}
function __do_post_dispatchajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/dispatch.controller.php";
}

function __do_get_sales()
{
    secure();
    view("sales", []);
}

function __do_post_salesajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/sales.controller.php";
}

function __do_post_webhook()
{
    //secure();
    $post = json_decode(file_get_contents('php://input'), true);
    require_once PATH_CNTRL . "/webhook.controller.php";
}

function __do_get_settings()
{
    secure();
    view("settings", []);
}

function __do_post_settingsajax()
{
    //secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/settings.controller.php";
}

function __do_get_skualerts()
{
    secure();
    view("skualerts", []);
}

function __do_post_skualertsajax()
{
    //secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/skualerts.controller.php";
}

function __do_get_skualertsmessages()
{
    secure();
    view("skualertsmessages", []);
}

function __do_post_skualertsmessagesajax()
{
    //secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/skualertsmessages.controller.php";
}
if (!fmap([], '__do')) {
    http_response_code(404);
    view('layout/head');
    view('layout/partial/navigation');
    view('404');
}

if (!$isAjax) {
    view('layout/foot');
}

function __do_get_newitemorder()
{
    secure();
    view("newitemorder", []);
}

function __do_get_newcomponentorder()
{
    secure();
    view("componentorder", []);
}
function __do_get_newsupplier($id)
{
    secure();
    view("newsupplier", ["id" => $id]);
}
/*function __do_get_newsupplier($id)
{
    secure();
    view("newsupplier", ["id" => $id]);
}*/
function __do_get_newitemorders()
{
    secure();
    view("newitemorders", []);
}

function __do_get_newitemrfq()
{
    secure();
    view("newitemrfq", []);
}

function __do_post_itemrfq($id)
{
    secure();
    view("newitemrfq", ["id" => $id]);
}
function __do_post_getSupplierajax()
{
    secure();
  require_once PATH_CNTRL . "/suppliersearch.controller.php";
}

function __do_post_getCustomerajax()
{
    secure();
  require_once PATH_CNTRL . "/customerSearch.controller.php";
}

function __do_get_itemrfq($id)
{
    secure();
    view("newitemrfq", ["id" => $id]);
}

function __do_get_newinvoiceitem($id)
{
    secure();
    view("newinvoice", ["id" => $id]);
}
function __do_post_newinvoiceitem($id)
{
    secure();
    view("newinvoice", ["id" => $id]);
}

function __do_get_newitemrfqs()
{
    secure();
    view("newitemrfqs", []);
}

function __do_post_newitemrfqsajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/newitemrfq.controller.php";
}
function __do_get_invoices()
{
    secure();
    view("invoices", []);
}
function __do_get_invoiceitem($id)
{
    secure();
    view("invoicedetail", ["id" => $id]);
}
function __do_get_newinvoice()
{
    secure();
    //echo "here";exit;
    view("newinvoice", []);
}

function __do_post_newinvoiceajax()
{
    secure();
   $action = $_POST["action"];
    require_once PATH_CNTRL . "/invoices.controller.php";
}
function __do_post_iteminvoice($id)
{
    secure();
    view("newinvoice", ["id" => $id]);
}
function __do_get_iteminvoice($id)
{
    secure();
    view("newinvoice", ["id" => $id]);
}
function __do_post_invoicesajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/invoices.controller.php";
}

function __do_get_rfqorderitem($id)
{
    secure();
    view("rfqorderitem", ["id" => $id]);
}

function __do_get_rfqorders()
{
    secure();
    view("rfqorders", []);
}

function __do_post_rfqorderajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/rfqorder.controller.php";
}

function __do_post_soldlaptopreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/soldlaptopreport.controller.php";
}

function __do_post_laptoporderprofitreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/laptoporderprofitreport.controller.php";
}

function __do_post_accorderprofitreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/accorderprofitreport.controller.php";
}

function __do_post_laptopsalesdatereportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/laptopsalesdatereport.controller.php";
}

function __do_post_nwpstockreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/nwpstockreport.controller.php";
}

function __do_post_dpstockreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/dpstockreport.controller.php";
}

function __do_post_accstockreportajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/accstockreport.controller.php";
}

function __do_get_laptopprofit($id)
{
    secure();
    view("laptopprofit", ["id" => $id]);
}

function __do_get_accprofit($id)
{
    secure();
    view("accprofit", ["id" => $id]);
}

function __do_get_componentorders()
{
    secure();
    view("componentorders", []);
}

function __do_get_strippedlaptops()
{
    secure();
    view("stripped_laptops", []);
}

function __do_post_itemordersajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/newitemorder.controller.php";
}

function __do_post_componentordersajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/componentorder.controller.php";
}

function __do_post_strippedlaptopsajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/strippedlaptops.controller.php";
}

function __do_get_itemorderin($id)
{
    secure();
    view("newitemorder", ["id" => $id, "in" => true]);
}

function __do_get_componentorderin($id)
{
    secure();
    view("componentorder", ["id" => $id, "in" => true]);
}

function __do_get_itemorder($id)
{
    secure();
    view("newitemorder", ["id" => $id]);
}

function __do_get_componentorder($id)
{
    secure();
    view("componentorder", ["id" => $id]);
}

function __do_get_newitemstocks($part)
{
    secure();
    view("newitemstocks", ["part" => $part, "BATCHED" => 0]);
}
function __do_get_newitemstocks_batch($part)
{
    secure();
    view("newitemstocks", ["part" => $part, "BATCHED" => 1]);
}

function __do_post_newitemstocksajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/newitemstock.controller.php";
}

function __do_get_newrmac()
{
    secure();
    view("newrmac", []);
}

function __do_get_rmacitem($id)
{
    secure();
    view("newrmac", ["id" => $id]);
}

function __do_post_newrmacajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/newrmac.controller.php";
}

function __do_post_newrmacstocksajax($part)
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/newrmac.controller.php";
}

function __do_get_rmac($part)
{
    secure();
    view("newrmacstocks", ["part" => $part, "BATCHED" => 0]);
}

function __do_get_rmachistory($id)
{
    secure();
    view("rmachistory", ["id" => $id]);
}

function __do_get_supplierrmac()
{
    secure();
    view("supplierrmac", []);
}

function __do_get_supplierrmacitem($id)
{
    secure();
    view("supplierrmacitem", ["id" => $id]);
}

function __do_post_supplierrmacajax()
{
    secure();
    $action = $_POST["action"];
    require_once PATH_CNTRL . "/supplierrmac.controller.php";
}

function __do_get_magentoprodcorn()
{
    require_once PATH_CNTRL . "/magentoprod.controller.php";
}