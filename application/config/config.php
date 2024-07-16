<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will try guess the protocol, domain
| and path to your installation. However, you should always configure this
| explicitly and never rely on auto-guessing, especially in production
| environments.
|
*/

if (!isset($_SERVER['HTTP_HOST']))
{
	$_SERVER['HTTP_HOST']='127.0.0.1';
}

//$config['base_url']	= "http://localhost/nada3/";
$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://".$_SERVER['HTTP_HOST'];
$config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= "english";

//List of supported languages. type language name in lower case
$config['supported_languages']=array();//array("english","french","arabic","russian","spanish","mongolian");

/*
|--------------------------------------------------------------------------
| Allowed IP addresses to access site administration
|--------------------------------------------------------------------------
|
| This option allows you to restrict the access to site administration by IP
| addresses. If values are provided then only the supplied IP addresses can be
| used to access the site administration.
|*/
$config['admin_allowed_ip']=array();

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = TRUE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = ',a-z 0-9~%.:_\-=';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = './logs/';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = './cache/';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| http://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = "CzYs1979";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ihsn_nada';
$config['sess_expiration'] = 7200; //7200 default
$config['sess_save_path'] = FCPATH.'files/sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 0;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'ncsrf';
$config['csrf_cookie_name'] = 'ccsrf';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array(
    'auth/.*+',
    'api/.*+',
    'catalog/.*+'    
);

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';


/*
|--------------------------------------------------------------------------
| PHP Date format
|--------------------------------------------------------------------------
|
| Define how you want the date to be displayed. Use the php reference manual
| on date formats.
|
*/
$config['date_format']='m/d/Y';
$config['date_format_long']='m/d/Y H:i';


/*
|--------------------------------------------------------------------------
| NADA SSL configurations
|--------------------------------------------------------------------------
|
| Enable SSL for the site login, user registration, etc pages. You must have SSL
| enabled on your server before using these settings.
|
|	enable_ssl	= 	TRUE/FALSE	whether to enable SSL or not
|	http_port	=  	port number application is using. e.g. if you are running website on a
|					non-standard port e.g. http://localhost:81/nada, enter 81
|	proxy_ssl				=	TRUE/FALSE - use when the proxy server is managing SSL and SSL info is sent in an HTTP Header
|	proxy_ssl_header		=	Name of the $_SERVER variable sent by the proxy server
|	proxy_ssl_header_value	=	Value that indicate page is served using SSL
|
|
*/
$config['enable_ssl']=FALSE;
$config['http_port']=80;
$config['proxy_ssl']=FALSE;
$config['proxy_ssl_header']='HTTP_X_FORWARDED_PROTO'; //$_SERVER variable name
$config['proxy_ssl_header_value']='80, 443'; //$_SERVER variable value for SSL pages

/*
|--------------------------------------------------------------------------
| NADA RTL Support [Experimental]
|--------------------------------------------------------------------------
|
| Enable Right-to-Left layout support for languages such as Arabic, Urdu, etc
|
|	enable_rtl	= TRUE	changes site template and other UI to use RTl
|
*/
$config['enable_rtl']=FALSE;


/*
|--------------------------------------------------------------------------
| PDF settings
|--------------------------------------------------------------------------
|
| Set PDF generator codepage settings
|
| pdf_codepage	set the codepage to be used for rendering PDF files
|
|	Win-1252	- Latin alphabets - English, Italian, Spanish, Portuguese, French, German, Dutch, Danish, Swedish, Norwegian, and Icelandic
|	Win-1251	- Cyrillic alphabets - Russian, Bulgarian, Serbian, Macedonian and Bulgarian
|
| For other encodings see mPDF documentation: http://mpdf1.com/manual/index.php
|
*/
$config['pdf_codepage']='Win-1252';


/*
|--------------------------------------------------------------------------
| thumbnail to use for Facebook sharing
|--------------------------------------------------------------------------
|
| Provide relative path the image to use for posting links on Facebook using the like or share button
*/
$config['fb_catalog_image']='images/nada-logo.gif';

/*
|--------------------------------------------------------------------------
| Enable Fulltext search for SQLSRV drivers
|--------------------------------------------------------------------------
|
| Enable or disable fulltext searching for Microsoft SQLSRV database
*/
$config['sqlsrv_use_fulltext']=TRUE;
$config['site_user_register']='yes';

/*
|--------------------------------------------------------------------------
| Allowed file extension types for uploading
|--------------------------------------------------------------------------
|
| allowed_resource_types	comma seperated list of file extensions that are allowed for uploading external resources
*/
$config['allowed_resource_types']='jpg,gif,png,zip,doc,docx,pdf,sav,dta,txt,xls,xlsx,ppt,csv,rar,do,r';


/*
|--------------------------------------------------------------------------
| Max file upload size for external resources
|--------------------------------------------------------------------------
|
| max_resource_upload_size	maximum file upload size in MB
|
*/
$config['max_resource_upload_size']='3000';


/*
|--------------------------------------------------------------------------
| Folder path for storing citation attachments
|--------------------------------------------------------------------------
|
| citations_storage_path   relative or absolute path to a folder
|
*/
$config['citations_storage_path']='datafiles/citations'; //don't add a trailing slash



/*
|--------------------------------------------------------------------------
| Filestore
|--------------------------------------------------------------------------
|
| Public files storage
|
| Path for storing public files
|
*/

$config['filestore_path']='files/public';


/*
|--------------------------------------------------------------------------
| User data folder
|--------------------------------------------------------------------------
|
| For storing user customizations and overrides.
| 
| Override language translations: 
| For personalizing translations for your language, copy the language folder (e.g. application/language/english) and copy
|  to [user_datapath/language/english] folder. 
|
|
*/
$config['userdata_path']='userdata'; //relative or absolute path


/*
|--------------------------------------------------------------------------
| Catalog entries sort order
|--------------------------------------------------------------------------
|
| Set default sort for catalog results
|
| catalog_default_sort_by       Sort by field
| 
|   valid values are:   
|   'proddate','title','labl','nation','popularity','rank'
|
|
| catalog_default_sort_order    Sort order
|
| valid values are: 'desc', 'asc'
|
*/
$config['catalog_default_sort_by']='';
$config['catalog_default_sort_order']='';


$config['catalog_variable_view']=false;

/*
|--------------------------------------------------------------------------
| Search providers
|--------------------------------------------------------------------------
|
| options are DB and SOLR
|
| db - use database builtin search - no configurations required
| solr - use solr for search. solr instance info must be added to solr config file
|
*/

$config['search_provider']='db';


/**
 * 
 * Maintenance mode
 * 
 * @maintenance_mode 1=enable, 0=disable
 * 
*/
$config['maintenance_mode']=0;



/*
|--------------------------------------------------------------------------
| OTP verification for Admin users
|--------------------------------------------------------------------------
|
| This enables One Time Password (OTP) verification for accessing site adminstration.
|
| OTP codes are sent via email, make sure the email system works.
| 
| expiry time - Codes are valid for 15 minutes only
| 
*/

$config['otp_verification']=0;


/*
|--------------------------------------------------------------------------
| Import access policy from DDI
|--------------------------------------------------------------------------
|
| Enable importing Access Policy from DDI field study_desc/data_access/dataset_use/conditions
|
| Support access policy values are:
|
|   Open data access [open]
|   Direct data access [direct]
|   Public use data files [public]
|   Licensed data files [licensed]
|   Data not available [data_na]
| 
|   The part in the bracket is the code and must match.
| 
*/

$config['enable_access_policy_import']=false;


/*
|--------------------------------------------------------------------------
| Hide 'Get Microdata' tab for guest users
|--------------------------------------------------------------------------
|
| 
*/
$config['guests_hide_microdata_tab']=false;

//show tabs for each data type on search page
$config['data_types_nav_bar']=false;

//catalog search box location
$config['search_box_orientation']='default'; //inline, default


/*
|--------------------------------------------------------------------------
| Google Maps API key
|--------------------------------------------------------------------------
|
| API key for Google maps
| 
| 
*/

$config['google_maps_api_key']='';

/*
|--------------------------------------------------------------------------
| NADA fallback configurations
|--------------------------------------------------------------------------
|
| All of these settings are stored/read from the database. The settings defined here
| are used as fallback settings, incase the database configurations were not readable.
|
| DO NOT EDIT THE SETTINGS HERE
*/
$config['catalog_root']='datafiles';
$config['ddi_import_folder']='imports';
$config['collection_search']='yes';

$config['modules_locations'] = array(
    APPPATH.'modules/' => '../modules/',
);

/* End of file config.php */
/* Location: ./system/application/config/config.php */
