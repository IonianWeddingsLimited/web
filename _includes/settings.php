<?php

session_start();

date_default_timezone_set("GMT");

$database_host = "localhost";
$database_username = "ionianwe_couk";
$database_password = "G*}Eoo%0bKH^";
$database_name = "ionianwe_couk";

// Override default DB connection settings depending on the
if ($_SERVER['HTTP_HOST'] == '46.101.34.101') {
    if (stristri($_SERVER['REQUEST_URI'], '/ionian/live')) {
        $database_username = "live_ionian";
        $database_password = "vK3hU6p5uVTDGm7a";
        $database_name = "live_ionian";
    }
    else if (stristri($_SERVER['REQUEST_URI'], '/ionian/test')) {
        $database_username = "test_ionian";
        $database_password = "huL9y43Cb9SG5JaU";
        $database_name = "test_ionian";
    }
}

$payLimit = 10000;

$database_country = "country_list";
$database_preffered_time = "time_list";
$database_typeofceremony = "type_of_ceremony";
$database_hearaboutus = "hear_aboutus";
$database_plantobook = "plan_to_book";
$database_testimonials = "testimonials";
$database_info_contactus = "info_contactus";
$database_info_bookacallback = "info_bookacallback";
$database_info_personal_consultations = "info_personal_consultations";
$database_info_wedding_questionnaire = "info_wedding_questionnaire";
$database_news = "news";
$database_press = "press";
$database_pendingimages = "pending_images";
$database_gallery = "gallery";
$database_gallery_pics = "gallery_pictures";
$database_gallery_mason = "gallery_masonary";
$database_clients = "clients";
$database_users = "users";
$database_gray_feature = "gray_features";
$database_feature_packages = "feature_packages";
$database_home_feature = "home_features";
$database_questionaire_destinations = "questionaire_dest";
$database_meta_tags = "meta_tags";
$database_navigation = "menu_navigation";
$database_supplier_details = "supplier_details";
$database_package_extras = "package_extras";
$database_category_extras = "category_extras";
$database_menus = "menu";
$database_menu_options = "menu_options";
$database_venue_names = "venue_names";
$database_packages = "package_names";
$database_ceremonies = "ceremony_names";
$database_receptions = "receiption_names";
$database_package_includes = "package_includes";
$database_package_info = "package_info";
$database_order_details = "order_details";
$database_order_history = "order_history";
$database_supplier_invoices = "supplier_invoices";
$database_customer_invoices  = "customer_invoices";
$database_customer_invoices_items = "invoice_items";
$database_order_discounts = "order_discounts";
$database_info_feedback = "info_feedback";
$database_show_features = "show_features";
$database_client_historyinfo = "client_historyinfo";
$database_supplier_historyinfo = "supplier_historyinfo";
$database_invoice_history = "order_invoice_history";
$database_customer_correspondence = "customer_correspondence";
$database_notes = "customer_notes";
$database_clients_options = "clients_options";
$db_currency_conversion = "currency_conversion";
$database_emails = "emails";

$database_supplier_invoices_main = "supplier_invoice_main";
$database_supplier_invoices_details = "supplier_invoice_details";

$database_pdf_generator = "pdf_generator";
$database_image_module = "image_module";
$database_pdf_subfolders = "pdf_subfolders";

$site_url2 = "http://www.ionianweddings.co.uk";

$enquire_email = "graemegraeme@hotmail.com";

$base_directory = "/home/ionianwe/public_html";

$smtp_email = "ionianweddings@digitav.com";
$smtp_password = "#T?^nqL6C?Na";


$time= time();

$admin_editor_min = "<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor_min',
					{
						skin : 'kama',
						toolbar : 'Min',
						width: 530,
						height: 100,
					});
</script>";

$admin_editor_basic = "<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor_basic',
					{
						skin : 'kama',
						toolbar : 'Basic',
						width: 530,
						height: 100,
					});
</script>";

$admin_editor = "<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor',
					{
						skin : 'kama',
						toolbar : 'Full',
						height: 240,
					});

    CKFinder.setupCKEditor( null, '/ckeditor/ckfinder/' );
</script>";


$admin_editor4 = "<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor1',
					{
						skin : 'kama',
						toolbar : 'Full',
						height: 240,
					});

    CKFinder.setupCKEditor( null, '/ckeditor/ckfinder/' );
</script>
<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor2',
					{
						skin : 'kama',
						toolbar : 'Full',
						height: 240,
					});

    CKFinder.setupCKEditor( null, '/ckeditor/ckfinder/' );
</script>
<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor3',
					{
						skin : 'kama',
						toolbar : 'Full',
						height: 240,
					});

    CKFinder.setupCKEditor( null, '/ckeditor/ckfinder/' );
</script>
<script type=\"text/javascript\">
				CKEDITOR.replace( 'the_editor4',
					{
						skin : 'kama',
						toolbar : 'Full',
						height: 240,
					});

    CKFinder.setupCKEditor( null, '/ckeditor/ckfinder/' );
</script>
";
            
// Emails
$send_contact_form = "weddings@ionianweddings.co.uk";
?>