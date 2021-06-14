<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/view_404_page';
$route['translate_uri_dashes'] = FALSE;

$route["login"] = "Auth/view_login_page";
$route["validation_login"] = "Auth/process_validation_login";

$route["dashboard"] = "Admin/view_dashboard_page";
$route["logout"] = "Auth/process_logout";

//About Me
$route["about_me"] = "About_me/view_about_me_management";
$route["validation_about_me_add"] = "About_me/validation_about_me_add";

// Skill Management
$route["skill_management"] = "Skill/view_skill_management";
$route["get_skill"] = "Skill/get_skill_listed";
$route["add_skill"] = "Skill/validate_skill_add";
$route["get_skill_detail"] = "Skill/get_skill_detail_json";
$route["edit_skill"] = "Skill/validation_skill_edit";
$route["delete_skill"] = "Skill/process_skill_delete";

// Portofolio
$route["portofolio_management"] = "Portofolio/view_portofolio_management";
$route["get_portofolio"] = "Portofolio/get_portofolio_listed";
$route["add_portofolio"] = "Portofolio/validate_portofolio_add";
$route["get_portofolio_detail"] = "Portofolio/get_portofolio_detail_json";
$route["edit_portofolio"] = "Portofolio/validate_portofolio_edit";
$route["delete_portofolio"] = "Portofolio/process_portofolio_delete";

// Social & Inquiry
$route["social_management"] = "Social/view_social_management";
$route["get_kontak"] = "Social/get_kontak_listed";
$route["add_kontak"] = "Social/validate_kontak_add";
$route["get_kontak_detail"] = "Social/get_kontak_detail_json";
$route["edit_kontak"] = "Social/validate_kontak_edit";
$route["delete_kontak"] = "Social/process_kontak_delete";

$route["get_inquiry"] = "Social/get_inquiry_listed";
$route["response_inquiry"] = "Social/process_inquiry_response";
$route["add_inquiry"] = "Welcome/validate_inquiry_add";