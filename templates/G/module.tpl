{php}
    if ($GLOBALS['conf']['safe_mode']) {
        if (!strpos($_GET['file'], '..')) {
            include_once(dirname(__FILE__).'/..'.addslashes($_GET['file']));
        }
    } else {
        if (!strpos($_GET['file'], '..')) {
            include_once(dirname(__FILE__).'/../../..'.addslashes($_GET['file']));
        }
    }
{/php}