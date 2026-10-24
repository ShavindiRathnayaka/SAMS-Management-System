<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$fullName = trim($_SESSION["full_name"] ?? "User");
if ($fullName === "") { $fullName = "User"; }
$firstName = explode(" ", $fullName)[0];
$role = trim($_SESSION["role"] ?? "User");
$initials = "";
foreach (preg_split('/\s+/', $fullName) as $part) { if ($part !== "") { $initials .= strtoupper(substr($part, 0, 1)); } }
$initials = substr($initials, 0, 2);
$greetingName = htmlspecialchars($firstName, ENT_QUOTES, "UTF-8");
$escapedFullName = htmlspecialchars($fullName, ENT_QUOTES, "UTF-8");
$escapedRole = htmlspecialchars($role, ENT_QUOTES, "UTF-8");
$escapedInitials = htmlspecialchars($initials, ENT_QUOTES, "UTF-8");
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-sites-preview-theme="custom">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SAMS — Scrutiny Board Management System</title>
<style>
:root {
  color-scheme: light;
  --site-primary: #2563EB;
  --site-on-primary: #FFFFFF;
  --site-secondary: #0F172A;
  --site-on-secondary: #FFFFFF;
  --site-background: #F8FAFC;
  --site-on-background: #0F172A;
  --site-font-body: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  --site-font-heading: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  --site-heading-scale: 1;
  --site-spacing-scale: 1;
  --site-radius-scale: 1;
  --site-decoration-opacity: 1;
  --surface: #FFFFFF;
  --surface-2: #F1F5F9;
  --ink: #0F172A;
  --muted: #52627A;
  --line: #DCE4EF;
  --primary-soft: #DBEAFE;
  --green: #168A65;
  --green-bg: #E6F7F0;
  --amber: #B87817;
  --amber-bg: #FFF3DC;
  --red: #C94B61;
  --red-bg: #FFE9ED;
  --blue: #4776D0;
  --blue-bg: #EAF1FF;
  --shadow: 0 12px 34px rgba(25, 32, 66, .055);
}
* { box-sizing: border-box; }
body { margin: 0; font-family: var(--site-font-body); background: var(--site-background); color: var(--ink); }
button, input, select, textarea { font: inherit; }
button { cursor: pointer; }
#app { min-height: 100vh; width: 100%; background-color: var(--site-background); color: var(--site-on-background); border-radius: 0; }
.layout { display: grid; grid-template-columns: 250px minmax(0,1fr); min-height: 100vh; }
.sidebar { background: var(--site-secondary); color: #F7F7FF; padding: 26px 16px 18px; display: flex; flex-direction: column; gap: 26px; }
.brand { display:flex; align-items:center; gap:11px; padding: 0 9px; }
.brand-mark { height: 42px; width: 42px; border-radius: 14px; display:grid; place-items:center; background: linear-gradient(145deg,#3B82F6,#1D4ED8); box-shadow: 0 7px 20px #2563eb44; color:white; font-weight:850; font-size:18px; }
.brand-name { font-weight:800; font-size:20px; letter-spacing:-.8px; }
.brand-sub { color:#AEB6D1; font-size:9px; letter-spacing:1.35px; margin-top:3px; }
.workspace { margin: 0 5px; border: 1px solid #ffffff20; background:#ffffff0a; border-radius:13px; padding:12px; display:flex; align-items:center; gap:10px; }
.workspace-icon { width:32px;height:32px;border-radius:10px;background:#ffffff16;display:grid;place-items:center;font-size:15px; }
.workspace small { display:block;color:#AEB6D1;font-size:10px;margin-bottom:3px; }
.workspace strong { font-size:12px;font-weight:650; }
.nav-label { color:#818BAA; text-transform:uppercase; letter-spacing:1.5px; font-size:9px; font-weight:800; padding:0 12px; margin-bottom:8px; }
.nav-list { display:grid; gap:5px; }
.nav-item { display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:11px;border:0;background:transparent;color:#BFC6DD;text-align:left;font-size:12px;font-weight:600;width:100%; transition:.18s; }
.nav-item:hover { background:#ffffff0c;color:white; }
.nav-item.active { color:white;background:linear-gradient(100deg,#2563EB,#1D4ED8);box-shadow:0 7px 18px #1d4ed833; }
.nav-icon { width:19px;display:inline-grid;place-items:center;font-size:16px; }
.nav-count { margin-left:auto;font-size:10px;background:#ffffff18;padding:3px 7px;border-radius:7px; }
.sidebar-bottom { margin-top:auto; }
.help-card { border:1px solid #ffffff1b;background:#ffffff09;padding:14px;border-radius:14px;margin:0 3px 18px; }
.help-card .spark { color:#93C5FD;font-size:18px; }
.help-card strong { display:block;font-size:12px;margin:8px 0 5px; }
.help-card p { color:#AEB6D1;font-size:10px;line-height:1.6;margin:0 0 12px; }
.help-card button { background:#ffffff16;color:#fff;border:1px solid #ffffff16;border-radius:9px;padding:8px 10px;font-size:10px;width:100%; }
.profile { border-top:1px solid #ffffff1a;padding:17px 7px 0;display:flex;align-items:center;gap:10px; }
.avatar { width:35px;height:35px;display:grid;place-items:center;border-radius:12px;background:#DBEAFE;color:#1D4ED8;font-size:11px;font-weight:800;flex-shrink:0; }
.profile strong { display:block;font-size:11px; }
.profile small { display:block;color:#AEB6D1;font-size:10px;margin-top:4px; }
.profile .dots { margin-left:auto;color:#AEB6D1; }
.main { min-width:0; }
.topbar { height:76px; display:flex; align-items:center; justify-content:space-between; padding:0 38px; background:var(--surface); border-bottom:1px solid var(--line); position:sticky;top:0;z-index:5; }
.crumb { font-size:11px;color:var(--muted);display:flex;gap:9px;align-items:center; }
.crumb b { color:var(--ink);font-weight:700; }
.top-actions { display:flex;align-items:center;gap:13px; }
.searchbox { display:flex;align-items:center;gap:8px;border:1px solid var(--line);border-radius:11px;padding:9px 12px;width:220px;background:var(--surface-2); }
.searchbox span { color:var(--muted);font-size:14px; }
.searchbox input { border:0;outline:0;background:transparent;color:var(--ink);font-size:11px;width:100%;min-width:0; }
.icon-btn { width:37px;height:37px;border:1px solid var(--line);border-radius:11px;background:var(--surface);color:var(--ink);display:grid;place-items:center;position:relative;font-size:16px; }
.notice-dot { position:absolute;width:6px;height:6px;border-radius:50%;background:#F06E87;right:8px;top:7px;border:1px solid var(--surface); }
.content { padding:30px 38px 45px;max-width:1600px;margin:auto; }
.welcome-row { display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:25px; }
.eyebrow { color:var(--site-primary);font-size:10px;letter-spacing:1.65px;text-transform:uppercase;font-weight:800;margin-bottom:10px; }
h1 { font-family:var(--site-font-heading);font-size:calc(29px * var(--site-heading-scale));letter-spacing:-1.25px;line-height:1.2;margin:0 0 9px;font-weight:800; }
.subtitle { color:var(--muted);font-size:12px;line-height:1.7;margin:0; }
.primary-btn { display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--site-primary);color:var(--site-on-primary);border:0;border-radius:11px;padding:12px 16px;font-size:11px;font-weight:750;box-shadow:0 6px 15px #2563EB2b;transition:transform .15s,filter .15s;white-space:nowrap; }
.primary-btn:hover { filter:brightness(1.06);transform:translateY(-1px); }
.secondary-btn { display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--surface);color:var(--ink);border:1px solid var(--line);border-radius:10px;padding:10px 13px;font-size:11px;font-weight:700;white-space:nowrap; }
.stats-grid { display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:22px; }
.stat-card { background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:18px 18px 16px;box-shadow:var(--shadow);min-width:0; }
.stat-top { display:flex;justify-content:space-between;align-items:center;gap:8px; }
.stat-label { color:var(--muted);font-size:11px;font-weight:600; }
.stat-icon { width:36px;height:36px;border-radius:12px;display:grid;place-items:center;font-size:17px; }
.si-violet { background:var(--primary-soft);color:var(--site-primary); }
.si-green { background:var(--green-bg);color:var(--green); }
.si-amber { background:var(--amber-bg);color:var(--amber); }
.si-blue { background:var(--blue-bg);color:var(--blue); }
.stat-number { font-size:29px;font-weight:800;letter-spacing:-1px;margin:13px 0 8px; }
.stat-foot { display:flex;align-items:center;gap:6px;color:var(--muted);font-size:10px; }
.trend { color:var(--green);font-weight:800; }
.panel { background:var(--surface);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow);min-width:0; }
.panel-head { padding:19px 20px;display:flex;align-items:center;justify-content:space-between;gap:15px;border-bottom:1px solid var(--line); }
.panel-title { font-size:13px;font-weight:800;letter-spacing:-.15px; }
.panel-sub { font-size:10px;color:var(--muted);margin-top:5px; }
.text-link { color:var(--site-primary);font-size:10px;font-weight:750;border:0;background:transparent;white-space:nowrap; }
.main-grid { display:grid;grid-template-columns:minmax(0,1.7fr) minmax(270px,1fr);gap:20px;margin-bottom:20px; }
.table-wrap { overflow-x:auto; }
table { border-collapse:collapse;width:100%;white-space:nowrap; }
th { background:var(--surface-2);color:var(--muted);text-align:left;text-transform:uppercase;letter-spacing:.8px;font-size:9px;font-weight:800;padding:13px 18px; }
td { border-top:1px solid var(--line);padding:15px 18px;font-size:10px; }
.paper-cell { display:flex;align-items:center;gap:10px; }
.paper-icon { width:33px;height:36px;border-radius:9px;background:var(--primary-soft);color:var(--site-primary);display:grid;place-items:center;font-size:15px; }
.paper-cell strong { display:block;font-size:10px;font-weight:750; }
.paper-cell small { display:block;color:var(--muted);font-size:9px;margin-top:4px; }
.status { display:inline-flex;align-items:center;gap:5px;border-radius:7px;padding:6px 8px;font-size:9px;font-weight:750; }
.status:before { content:"";width:5px;height:5px;border-radius:50%;background:currentColor; }
.s-green { color:var(--green);background:var(--green-bg); }
.s-amber { color:var(--amber);background:var(--amber-bg); }
.s-red { color:var(--red);background:var(--red-bg); }
.s-blue { color:var(--blue);background:var(--blue-bg); }
.s-neutral { color:var(--muted);background:var(--surface-2); }
.row-menu { background:transparent;border:0;color:var(--muted);font-size:17px; }
.deadline-list { padding:3px 20px 8px; }
.deadline-item { display:flex;gap:12px;padding:15px 0;border-bottom:1px solid var(--line); }
.deadline-item:last-child { border-bottom:0; }
.date-tile { flex-shrink:0;width:43px;height:48px;border-radius:11px;background:var(--surface-2);display:flex;flex-direction:column;align-items:center;justify-content:center; }
.date-tile b { font-size:16px;line-height:1.1; }
.date-tile small { font-size:8px;color:var(--muted);margin-top:4px;text-transform:uppercase;font-weight:800; }
.deadline-info { flex:1;min-width:0; }
.deadline-info strong { font-size:10px;display:block;margin:2px 0 6px; }
.deadline-info p { margin:0;color:var(--muted);font-size:9px;line-height:1.5; }
.deadline-info .status { margin-top:8px; }
.workflow { padding:20px; }
.workflow-row { display:flex;align-items:center;gap:10px;margin:0 0 17px; }
.workflow-row:last-child { margin-bottom:0; }
.workflow-name { width:112px;flex-shrink:0;font-size:10px;font-weight:650; }
.track { height:7px;border-radius:10px;background:var(--surface-2);overflow:hidden;flex:1; }
.fill { height:100%;border-radius:10px;background:var(--site-primary); }
.workflow-count { width:25px;text-align:right;font-size:10px;font-weight:800; }
.lower-grid { display:grid;grid-template-columns:minmax(0,1.35fr) minmax(270px,1fr);gap:20px; }
.activity { padding:5px 20px 8px; }
.activity-item { display:flex;gap:12px;padding:13px 0; }
.activity-icon { width:31px;height:31px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;font-size:14px; }
.activity-body { flex:1;min-width:0; }
.activity-body p { margin:1px 0 6px;font-size:10px;line-height:1.6; }
.activity-body p strong { font-weight:800; }
.activity-body small { color:var(--muted);font-size:9px; }
.quick-actions { padding:16px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px; }
.quick-card { border:1px solid var(--line);border-radius:13px;background:var(--surface);padding:15px 13px;text-align:left;transition:.15s;color:var(--ink); }
.quick-card:hover { border-color:var(--site-primary);background:var(--primary-soft);transform:translateY(-1px); }
.quick-card .qicon { width:33px;height:33px;border-radius:10px;background:var(--primary-soft);color:var(--site-primary);display:grid;place-items:center;font-size:16px;margin-bottom:12px; }
.quick-card strong { display:block;font-size:10px;margin-bottom:5px; }
.quick-card small { color:var(--muted);font-size:9px;line-height:1.5; }
.page-view { display:none; }
.page-view.active { display:block;animation:fadein .2s ease; }
@keyframes fadein { from{opacity:.5;transform:translateY(3px)}to{opacity:1;transform:none} }
.page-toolbar { display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px; }
.filters { display:flex;gap:8px;align-items:center;flex-wrap:wrap; }
.filters select,.filters input { border:1px solid var(--line);background:var(--surface);color:var(--ink);border-radius:9px;padding:10px 12px;font-size:10px;outline:0; }
.data-panel { overflow:hidden; }
.empty-note { color:var(--muted);font-size:11px;text-align:center;padding:30px; }
.card-grid { display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px; }
.info-card { background:var(--surface);border:1px solid var(--line);border-radius:15px;padding:18px;box-shadow:var(--shadow); }
.info-card .card-symbol { height:39px;width:39px;border-radius:12px;background:var(--primary-soft);color:var(--site-primary);display:grid;place-items:center;font-size:17px;margin-bottom:14px; }
.info-card h3 { font-size:13px;margin:0 0 7px; }
.info-card p { font-size:10px;line-height:1.7;color:var(--muted);margin:0 0 15px; }
.info-card .card-bottom { display:flex;align-items:center;justify-content:space-between;gap:8px;border-top:1px solid var(--line);padding-top:12px; }
.info-card .card-bottom small { font-size:9px;color:var(--muted); }
.board-row { display:flex;align-items:center;gap:15px;padding:16px 20px;border-bottom:1px solid var(--line); }
.board-row:last-child { border-bottom:0; }
.board-date { width:50px;height:54px;border-radius:12px;background:var(--primary-soft);color:var(--site-primary);display:grid;place-items:center;align-content:center;flex-shrink:0; }
.board-date b { font-size:19px;line-height:1.05; }
.board-date small { font-size:8px;text-transform:uppercase;font-weight:800;margin-top:4px; }
.board-details { flex:1;min-width:0; }
.board-details strong { font-size:11px;display:block;margin-bottom:5px; }
.board-details p { margin:0;color:var(--muted);font-size:10px;line-height:1.6; }
.board-side { text-align:right; }
.board-side small { display:block;color:var(--muted);font-size:9px;margin-top:6px; }
.report-row { display:flex;align-items:center;gap:12px;padding:15px 20px;border-bottom:1px solid var(--line); }
.report-row:last-child { border-bottom:0; }
.report-file { width:37px;height:40px;border-radius:10px;background:var(--green-bg);color:var(--green);display:grid;place-items:center;font-size:16px; }
.report-meta { flex:1;min-width:0; }
.report-meta strong { display:block;font-size:10px;margin-bottom:5px; }
.report-meta small { color:var(--muted);font-size:9px; }
.toast { position:fixed;right:25px;bottom:25px;background:var(--site-secondary);color:white;padding:13px 17px;border-radius:12px;box-shadow:0 12px 35px #11152a3d;font-size:11px;z-index:30;opacity:0;transform:translateY(10px);pointer-events:none;transition:.22s;max-width:min(380px,calc(100vw - 30px)); }
.toast.show { opacity:1;transform:translateY(0); }
.modal-backdrop { display:none;position:fixed;inset:0;background:#11152a80;backdrop-filter:blur(4px);z-index:20;align-items:center;justify-content:center;padding:20px; }
.modal-backdrop.open { display:flex; }
.modal { width:min(540px,100%);background:var(--surface);color:var(--ink);border:1px solid var(--line);border-radius:19px;box-shadow:0 25px 80px #10132945;overflow:hidden; }
.modal-head { padding:21px 23px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;gap:12px; }
.modal-head h2 { font-size:17px;margin:0;letter-spacing:-.4px; }
.modal-head p { margin:5px 0 0;color:var(--muted);font-size:10px; }
.close-btn { border:1px solid var(--line);border-radius:9px;background:var(--surface-2);color:var(--ink);width:32px;height:32px;font-size:18px; }
.modal-form { padding:22px 23px;display:grid;grid-template-columns:1fr 1fr;gap:15px; }
.field { display:flex;flex-direction:column;gap:7px;min-width:0; }
.field.full { grid-column:1/-1; }
.field label { font-size:10px;font-weight:750; }
.field input,.field select,.field textarea { width:100%;border:1px solid var(--line);background:var(--surface-2);color:var(--ink);border-radius:10px;padding:11px 12px;font-size:11px;outline:0; }
.field input:focus,.field select:focus,.field textarea:focus { border-color:var(--site-primary);box-shadow:0 0 0 3px #2563EB1b; }
.field textarea { resize:vertical;min-height:72px; }
.modal-foot { display:flex;justify-content:flex-end;gap:9px;padding:15px 23px;border-top:1px solid var(--line);background:var(--surface-2); }
.mobile-menu { display:none; }
@media(max-width:1150px) { .layout{grid-template-columns:220px minmax(0,1fr)}.content{padding:25px 25px 35px}.topbar{padding:0 25px}.stats-grid{gap:10px}.stat-card{padding:15px}.main-grid,.lower-grid{grid-template-columns:minmax(0,1fr)}.card-grid{grid-template-columns:repeat(2,minmax(0,1fr))} }
@media(max-width:760px) { .layout{grid-template-columns:1fr}.sidebar{position:fixed;z-index:15;left:0;top:0;bottom:0;width:260px;transform:translateX(-105%);transition:transform .2s;box-shadow:20px 0 50px #0002}.sidebar.open{transform:translateX(0)}.mobile-menu{display:grid}.topbar{height:64px;padding:0 16px}.content{padding:23px 16px 30px}.welcome-row{align-items:flex-start}.welcome-row .primary-btn{padding:10px 11px;font-size:10px}.welcome-row h1{font-size:25px}.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.stat-number{font-size:25px}.searchbox{width:160px}.top-actions{gap:7px}.crumb{display:none}.card-grid{grid-template-columns:1fr}.panel-head{padding:16px}.quick-actions{padding:12px}.modal-form{grid-template-columns:1fr}.field.full{grid-column:auto}.board-row{padding:13px;gap:10px}.board-side .status{font-size:8px}.workflow-name{width:90px}.workflow{padding:16px}.main-grid{gap:14px}.lower-grid{gap:14px} }
@media(max-width:420px) { .searchbox{width:125px;padding:8px}.searchbox input{font-size:10px}.top-actions .icon-btn:nth-of-type(2){display:none}.welcome-row{gap:8px}.welcome-row .primary-btn{white-space:normal;max-width:115px}.stat-card{padding:13px}.stat-icon{width:31px;height:31px}.stat-label{font-size:10px}.stat-foot{font-size:9px} }

/* Readability refresh: matching the blue-and-white home/login pages */
body, #app {
  background: #F8FAFC;
  color: #0F172A;
  color-scheme: light;
}
.sidebar {
  background: #FFFFFF;
  color: #0F172A;
  border-right: 1px solid #DCE4EF;
}
.brand-name { color: #0F172A; }
.brand-sub { color: #64748B; }
.workspace {
  background: #F1F5F9;
  border-color: #DCE4EF;
  color: #0F172A;
}
.workspace-icon {
  background: #DBEAFE;
  color: #1D4ED8;
}
.workspace small, .profile small, .profile .dots { color: #64748B; }
.nav-label { color: #64748B; }
.nav-item { color: #475569; }
.nav-item:hover {
  background: #EFF6FF;
  color: #1D4ED8;
}
.nav-item.active {
  color: #FFFFFF;
  background: linear-gradient(100deg, #2563EB, #1D4ED8);
  box-shadow: 0 5px 14px #2563EB24;
}
.nav-count {
  background: #E2E8F0;
  color: #334155;
}
.nav-item.active .nav-count {
  background: #FFFFFF2B;
  color: #FFFFFF;
}
.help-card {
  background: #F8FAFC;
  border-color: #DCE4EF;
  color: #0F172A;
}
.help-card .spark { color: #2563EB; }
.help-card p { color: #52627A; }
.help-card button {
  background: #DBEAFE;
  color: #1D4ED8;
  border-color: #BFDBFE;
}
.profile { border-top-color: #DCE4EF; }
.topbar { background: #FFFFFF; }
.content { color: #0F172A; }
.stat-card, .panel, .info-card, .quick-card, .modal {
  background: #FFFFFF;
}
th { color: #475569; background: #F1F5F9; }
td { color: #1E293B; }
.searchbox, .filters select, .filters input,
.field input, .field select, .field textarea {
  background: #FFFFFF;
  color: #0F172A;
}
.searchbox input { color: #0F172A; }
.searchbox input::placeholder,
.filters input::placeholder,
.field input::placeholder,
.field textarea::placeholder { color: #64748B; opacity: 1; }
.secondary-btn, .icon-btn, .close-btn {
  background: #FFFFFF;
  color: #1E293B;
}
.modal-foot { background: #F8FAFC; }

/* Dashboard labels were unusually small; increase them so details are easier to read. */
.stat-label, .panel-title { font-size: 13px; }
.stat-foot, .panel-sub, .text-link, .filters select, .filters input,
td, .paper-cell strong, .deadline-info p, .deadline-info strong,
.workflow-name, .workflow-count, .activity-body p,
.quick-card strong, .info-card p, .board-details p,
.board-details strong, .report-meta strong, .report-meta small,
.field label, .field input, .field select, .field textarea {
  font-size: 12px;
}
.paper-cell small, .deadline-info .status, .activity-body small,
.quick-card small, .info-card .card-bottom small, .board-side small {
  font-size: 11px;
}
th { font-size: 10px; }
.subtitle { font-size: 14px; }
@media (max-width: 760px) {
  .sidebar {
    border-right: 1px solid #DCE4EF;
    box-shadow: 20px 0 50px #0F172A12;
  }
}



/* SAMS readability update: modest increase for dashboard text */
html { font-size: 16px; }
body { font-size: 15px; }
.sidebar .nav-item, .sidebar a, .nav-item { font-size: 14px; }
.page-title, .main-title, h1 { font-size: 25px; }
h2 { font-size: 20px; }
h3 { font-size: 17px; }
p, .subtitle, .description { font-size: 15px; }
label, input, select, textarea { font-size: 15px; }
button, .btn, .action-btn { font-size: 14px; }
th, td { font-size: 13px; }
small, .muted, .eyebrow, .card-label, .status { font-size: 12px; }
@media (max-width: 760px) {
  body { font-size: 14px; }
  .page-title, .main-title, h1 { font-size: 23px; }
  th, td { font-size: 12px; }
}

</style>
</head>
<body>
<div id="app">
<div class="layout">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="brand-mark">S</div><div><div class="brand-name">SAMS<span style="color:#93C5FD">.</span></div><div class="brand-sub">ACADEMIC WORKFLOW</div></div></div>
  <div class="workspace"><div class="workspace-icon">⌂</div><div><small>WORKSPACE</small><strong>Department of Computing</strong></div><span style="margin-left:auto;color:#AEB6D1">⌄</span></div>
  <div><div class="nav-label">Workspace</div><nav class="nav-list">
    <button class="nav-item active" data-page="overview"><span class="nav-icon">◫</span>Overview</button>
    <button class="nav-item" data-page="papers"><span class="nav-icon">▤</span>Examination Papers<span class="nav-count" id="paperNavCount">12</span></button>
    <button class="nav-item" data-page="moderation"><span class="nav-icon">◉</span>Moderation</button>
    <button class="nav-item" data-page="boards"><span class="nav-icon">▦</span>Scrutiny Boards<span class="nav-count">3</span></button>
    <button class="nav-item" data-page="invitations"><span class="nav-icon">✉</span>Panel Invitations</button>
  </nav></div>
  <div><div class="nav-label">Administration</div><nav class="nav-list">
    <button class="nav-item" data-page="deadlines"><span class="nav-icon">◷</span>Deadlines & Exceptions<span class="nav-count">4</span></button>
    <button class="nav-item" data-page="reports"><span class="nav-icon">▧</span>Final Reports</button>
    <button class="nav-item" data-page="submissions"><span class="nav-icon">⇧</span>Exam Division</button>
    <button class="nav-item" data-page="users"><span class="nav-icon">♙</span>Users & Roles</button>
  </nav></div>
  <div class="sidebar-bottom"><div class="help-card"><div class="spark">✳</div><strong>Need a hand?</strong><p>Find your way around the scrutiny workflow and its key steps.</p><button id="helpBtn">Explore the workflow ↗</button></div><div class="profile"><div class="avatar"><?= $escapedInitials ?></div><div><strong><?= $escapedFullName ?></strong><small><?= $escapedRole ?></small></div><span class="dots">•••</span></div></div>
</aside>
<main class="main">
<header class="topbar"><div style="display:flex;align-items:center;gap:12px"><button class="icon-btn mobile-menu" id="mobileMenu" aria-label="Open menu">☰</button><div class="crumb"><span>Workspace</span><span>›</span><b id="crumbCurrent">Overview</b></div></div><div class="top-actions"><label class="searchbox"><span>⌕</span><input id="globalSearch" placeholder="Search anything..." aria-label="Search anything"/><span style="font-size:9px;border:1px solid var(--line);border-radius:4px;padding:2px 4px">/</span></label><button class="icon-btn" id="notificationBtn" aria-label="Notifications">♧<i class="notice-dot"></i></button><button class="icon-btn" id="roleBtn" title="Log out" aria-label="Log out">♙</button></div></header>
<div class="content">
<section class="page-view active" id="page-overview">
  <div class="welcome-row"><div><div class="eyebrow">FRIDAY, 23 OCTOBER 2026 · EXAM CYCLE 2026/27</div><h1 id="greeting">Good afternoon, <?= $greetingName ?> <span style="font-size:25px">✦</span></h1><p class="subtitle">Here's what's happening across your examination scrutiny workflow today.</p></div><button class="primary-btn" id="newPaperBtn"><span style="font-size:17px">＋</span> New paper submission</button></div>
  <div class="stats-grid">
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Total examination papers</span><span class="stat-icon si-violet">▤</span></div><div class="stat-number" id="statTotal">24</div><div class="stat-foot"><span class="trend">↗ 12%</span><span>from previous cycle</span></div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Awaiting moderation</span><span class="stat-icon si-amber">◷</span></div><div class="stat-number" id="statModeration">06</div><div class="stat-foot"><span style="color:var(--amber);font-weight:800">Needs attention</span><span>· 2 overdue</span></div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Board approval</span><span class="stat-icon si-green">✓</span></div><div class="stat-number" id="statApproved">14</div><div class="stat-foot"><span class="trend">58%</span><span>of cycle completed</span></div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Urgent actions</span><span class="stat-icon si-blue">⚑</span></div><div class="stat-number">04</div><div class="stat-foot"><span style="color:var(--red);font-weight:800">2 critical</span><span>deadlines approaching</span></div></article>
  </div>
  <div class="main-grid">
    <section class="panel"><div class="panel-head"><div><div class="panel-title">Recent examination papers</div><div class="panel-sub">Keep an eye on the latest submissions and decisions</div></div><button class="text-link" data-goto="papers">View all papers ↗</button></div><div class="table-wrap"><table><thead><tr><th>Paper / Course</th><th>Setter</th><th>Current status</th><th>Due date</th><th></th></tr></thead><tbody id="recentPapers"></tbody></table></div><div id="recentEmpty" class="empty-note" style="display:none">No papers match your search.</div></section>
    <section class="panel"><div class="panel-head"><div><div class="panel-title">Upcoming deadlines</div><div class="panel-sub">Don't let a critical date slip by</div></div><button class="text-link" data-goto="deadlines">View calendar ↗</button></div><div class="deadline-list" id="deadlineList"></div></section>
  </div>
  <div class="main-grid">
    <section class="panel"><div class="panel-head"><div><div class="panel-title">Workflow at a glance</div><div class="panel-sub">Paper distribution across each scrutiny stage</div></div><span class="status s-blue">Live overview</span></div><div class="workflow" id="workflowBars"></div></section>
    <section class="panel"><div class="panel-head"><div><div class="panel-title">Recent activity</div><div class="panel-sub">Latest actions from your team</div></div><button class="text-link" id="activityMore">View log ↗</button></div><div class="activity" id="activityList"></div></section>
  </div>
  <section class="panel"><div class="panel-head"><div><div class="panel-title">Quick actions</div><div class="panel-sub">Jump straight into your most common tasks</div></div></div><div class="quick-actions"><button class="quick-card" data-action="newPaper"><span class="qicon">＋</span><strong>Submit an examination paper</strong><small>Register a new paper for the current cycle.</small></button><button class="quick-card" data-goto="boards"><span class="qicon">▦</span><strong>Schedule a board meeting</strong><small>Plan a scrutiny session and check panel adequacy.</small></button><button class="quick-card" data-goto="deadlines"><span class="qicon">◷</span><strong>Review deadline requests</strong><small>Review extension requests from lecturers.</small></button><button class="quick-card" data-goto="reports"><span class="qicon">▧</span><strong>Access final reports</strong><small>Find approved reports and revision records.</small></button></div></section>
</section>
<section class="page-view" id="page-papers"><div class="welcome-row"><div><div class="eyebrow">PAPER LIFECYCLE</div><h1>Examination papers</h1><p class="subtitle">Track every paper from submission through moderation to board approval.</p></div><button class="primary-btn" id="newPaperBtn2">＋ New paper submission</button></div><div class="page-toolbar"><div class="filters"><input id="paperSearch" placeholder="Search papers or setters..."/><select id="paperStatusFilter"><option value="all">All statuses</option><option>Awaiting moderation</option><option>In moderation</option><option>Scheduled</option><option>Minor corrections</option><option>Major corrections</option><option>Accepted</option></select></div><button class="secondary-btn" id="exportPapers">⇩ Export CSV</button></div><section class="panel data-panel"><div class="table-wrap"><table><thead><tr><th>Paper / Course</th><th>Setter</th><th>Moderator</th><th>Current status</th><th>Due date</th><th>Actions</th></tr></thead><tbody id="allPapers"></tbody></table></div><div id="paperEmpty" class="empty-note" style="display:none">No papers found. Try a different search or add a new paper.</div></section></section>
<section class="page-view" id="page-moderation"><div class="welcome-row"><div><div class="eyebrow">QUALITY ASSURANCE</div><h1>Moderation centre</h1><p class="subtitle">Manage moderator appointments, review rounds, comments, and satisfaction status.</p></div><button class="primary-btn" id="assignModeratorBtn">＋ Assign moderator</button></div><div class="card-grid" id="moderationCards"></div></section>
<section class="page-view" id="page-boards"><div class="welcome-row"><div><div class="eyebrow">MEETINGS & PANEL MANAGEMENT</div><h1>Scrutiny boards</h1><p class="subtitle">Schedule board sessions and ensure each panel is ready before confirmation.</p></div><button class="primary-btn" id="scheduleBoardBtn">＋ Schedule a board</button></div><div class="panel" style="margin-bottom:18px"><div class="panel-head"><div><div class="panel-title">Upcoming board sessions</div><div class="panel-sub">Panel adequacy and invitation acknowledgement are required to confirm a session.</div></div><span class="status s-amber">3 need attention</span></div><div id="boardList"></div></div><div class="card-grid"><article class="info-card"><div class="card-symbol">♙</div><h3>Panel adequacy check</h3><p>Every board requires the paper setter plus two additional lecturers. The HOD acts as convener.</p><div class="card-bottom"><small>Required panel</small><strong style="font-size:10px">3 lecturers + HOD</strong></div></article><article class="info-card"><div class="card-symbol">✉</div><h3>Invitation acknowledgement</h3><p>Track invitations and acknowledgements. A meeting should not be confirmed until the required panel has acknowledged.</p><div class="card-bottom"><small>Confirmation rule</small><strong style="font-size:10px">All required members</strong></div></article><article class="info-card"><div class="card-symbol">⚑</div><h3>Major corrections</h3><p>Major corrections need an urgent repeat session within the required two-to-three business day window.</p><div class="card-bottom"><small>Rescheduling window</small><strong style="font-size:10px">2–3 business days</strong></div></article></div></section>
<section class="page-view" id="page-invitations"><div class="welcome-row"><div><div class="eyebrow">PANEL COMMUNICATION</div><h1>Panel invitations</h1><p class="subtitle">Monitor sent invitations and acknowledgements before board sessions.</p></div><button class="primary-btn" id="sendInvitationBtn">＋ Create invitation</button></div><section class="panel"><div class="panel-head"><div><div class="panel-title">Invitation tracker</div><div class="panel-sub">Demo data for upcoming scrutiny sessions</div></div><span class="status s-amber">2 pending</span></div><div id="invitationList"></div></section></section>
<section class="page-view" id="page-deadlines"><div class="welcome-row"><div><div class="eyebrow">TIME & EXCEPTIONS</div><h1>Deadlines & exceptions</h1><p class="subtitle">Keep submission dates visible and manage formal extension requests.</p></div><button class="primary-btn" id="newDeadlineBtn">＋ Add deadline</button></div><div class="stats-grid"><article class="stat-card"><div class="stat-label">Upcoming this week</div><div class="stat-number">04</div><div class="stat-foot">Across current exam cycle</div></article><article class="stat-card"><div class="stat-label">Overdue submissions</div><div class="stat-number" style="color:var(--red)">02</div><div class="stat-foot">Requires follow-up</div></article><article class="stat-card"><div class="stat-label">Pending exceptions</div><div class="stat-number">03</div><div class="stat-foot">Awaiting HOD decision</div></article><article class="stat-card"><div class="stat-label">Approved exceptions</div><div class="stat-number">05</div><div class="stat-foot">In this examination cycle</div></article></div><div class="main-grid"><section class="panel"><div class="panel-head"><div><div class="panel-title">Deadline calendar</div><div class="panel-sub">Key dates for papers and moderation reports</div></div></div><div id="deadlineFullList" class="deadline-list"></div></section><section class="panel"><div class="panel-head"><div><div class="panel-title">Exception requests</div><div class="panel-sub">Lecturer requests awaiting a decision</div></div></div><div id="exceptionList"></div></section></div></section>
<section class="page-view" id="page-reports"><div class="welcome-row"><div><div class="eyebrow">AUDITABLE RECORDS</div><h1>Final reports</h1><p class="subtitle">Find accepted papers, signed reports, and retained revision history.</p></div><button class="secondary-btn" id="reportExport">⇩ Export report list</button></div><div class="stats-grid"><article class="stat-card"><div class="stat-label">Reports generated</div><div class="stat-number">14</div><div class="stat-foot">Current exam cycle</div></article><article class="stat-card"><div class="stat-label">Awaiting signature</div><div class="stat-number">03</div><div class="stat-foot">Follow up with convener</div></article><article class="stat-card"><div class="stat-label">Revision records</div><div class="stat-number">28</div><div class="stat-foot">Preserved across review rounds</div></article><article class="stat-card"><div class="stat-label">Ready to submit</div><div class="stat-number">08</div><div class="stat-foot">Final approval complete</div></article></div><section class="panel"><div class="panel-head"><div><div class="panel-title">Report archive</div><div class="panel-sub">Illustrative report records — generated files require backend integration.</div></div></div><div id="reportList"></div></section></section>
<section class="page-view" id="page-submissions"><div class="welcome-row"><div><div class="eyebrow">FINAL HANDOVER</div><h1>Examination Division</h1><p class="subtitle">Record the handover and sealing status of each approved examination paper.</p></div><button class="primary-btn" id="recordSubmissionBtn">＋ Record submission</button></div><section class="panel"><div class="panel-head"><div><div class="panel-title">Final paper submissions</div><div class="panel-sub">Target: at least three working days before the exam date.</div></div><span class="status s-blue">HOD-managed</span></div><div class="table-wrap"><table><thead><tr><th>Paper / Course</th><th>Exam date</th><th>Required deadline</th><th>Submitted at</th><th>Status</th><th></th></tr></thead><tbody id="submissionTable"></tbody></table></div></section></section>
<section class="page-view" id="page-users"><div class="welcome-row"><div><div class="eyebrow">ACCESS CONTROL</div><h1>Users & roles</h1><p class="subtitle">The right access for every person involved in the scrutiny workflow.</p></div><button class="primary-btn" id="addUserBtn">＋ Add user</button></div><div class="card-grid"><article class="info-card"><div class="card-symbol">♙</div><h3>Head of Department</h3><p>Oversees the workflow, verifies moderation, schedules boards, records decisions, manages exceptions and tracks final submissions.</p><div class="card-bottom"><small>Permissions</small><strong style="font-size:10px">Full administration</strong></div></article><article class="info-card"><div class="card-symbol">▤</div><h3>Lecturer / Paper setter</h3><p>Submits papers and marking schemes, appoints a moderator, tracks progress, responds to invitations, and requests exceptions.</p><div class="card-bottom"><small>Permissions</small><strong style="font-size:10px">Own papers & requests</strong></div></article><article class="info-card"><div class="card-symbol">◉</div><h3>Moderator</h3><p>Accepts an appointment, accesses assigned paper materials, records comments, and submits a moderation report.</p><div class="card-bottom"><small>Permissions</small><strong style="font-size:10px">Assigned reviews</strong></div></article></div><div class="panel" style="margin-top:18px"><div class="panel-head"><div><div class="panel-title">Security principles</div><div class="panel-sub">Required when connecting the real application database.</div></div></div><div class="quick-actions"><div class="quick-card"><span class="qicon">♢</span><strong>Role-based access</strong><small>Restrict data and actions by authenticated role.</small></div><div class="quick-card"><span class="qicon">◷</span><strong>Audit trail</strong><small>Record submissions, approvals, and status changes.</small></div><div class="quick-card"><span class="qicon">▣</span><strong>Confidential files</strong><small>Protect pre-release examination material.</small></div><div class="quick-card"><span class="qicon">⟲</span><strong>Backup & recovery</strong><small>Plan database backups to protect records.</small></div></div></div></section>
</div></main></div></div>
<div class="modal-backdrop" id="modalBackdrop" role="dialog" aria-modal="true" aria-labelledby="modalTitle"><section class="modal"><div class="modal-head"><div><h2 id="modalTitle">New paper submission</h2><p id="modalSubtitle">Add a paper to the current examination cycle.</p></div><button class="close-btn" id="modalClose" aria-label="Close dialog">×</button></div><form id="modalForm"><div class="modal-form" id="modalFields"></div><div class="modal-foot"><button type="button" class="secondary-btn" id="modalCancel">Cancel</button><button type="submit" class="primary-btn" id="modalSubmit">Save record</button></div></form></section></div>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script>
const papers = [
 {id:'ICT1407',name:'Database Systems',code:'ICT 1407',setter:'R. Bandara',moderator:'Dr. N. Perera',status:'In moderation',due:'28 Oct 2026',dueDay:'28',dueMonth:'OCT',round:1},
 {id:'ICT1306',name:'Object Oriented Programming',code:'ICT 1306',setter:'A. Silva',moderator:'Prof. K. Jayasuriya',status:'Scheduled',due:'29 Oct 2026',dueDay:'29',dueMonth:'OCT',round:2},
 {id:'ICT1308',name:'Operating Systems',code:'ICT 1308',setter:'S. Hasarandi',moderator:'Dr. M. Fernando',status:'Awaiting moderation',due:'25 Oct 2026',dueDay:'25',dueMonth:'OCT',round:1},
 {id:'ICT1402',name:'Data Structures',code:'ICT 1402',setter:'D. Wickramasingha',moderator:'Dr. P. Silva',status:'Major corrections',due:'26 Oct 2026',dueDay:'26',dueMonth:'OCT',round:2},
 {id:'ICT1204',name:'Computer Architecture',code:'ICT 1204',setter:'R. Bandara',moderator:'Dr. H. Kumara',status:'Accepted',due:'22 Oct 2026',dueDay:'22',dueMonth:'OCT',round:1},
 {id:'ICT1405',name:'Database Lab',code:'ICT 1405',setter:'A. Silva',moderator:'Pending assignment',status:'Awaiting moderation',due:'27 Oct 2026',dueDay:'27',dueMonth:'OCT',round:1},
 {id:'ICT1302',name:'Digital Logic',code:'ICT 1302',setter:'S. Hasarandi',moderator:'Prof. N. Perera',status:'Minor corrections',due:'30 Oct 2026',dueDay:'30',dueMonth:'OCT',round:1},
 {id:'ICT1101',name:'Introduction to IT',code:'ICT 1101',setter:'D. Wickramasingha',moderator:'Dr. M. Fernando',status:'Accepted',due:'21 Oct 2026',dueDay:'21',dueMonth:'OCT',round:1}
];
const deadlines = [
 {day:'25',month:'OCT',title:'ICT 1308 · Moderation report',desc:'Operating Systems · S. Hasarandi',status:'Due in 2 days',tone:'s-amber'},
 {day:'26',month:'OCT',title:'ICT 1402 · Repeat board session',desc:'Data Structures · Major corrections',status:'Urgent · 2–3 day window',tone:'s-red'},
 {day:'28',month:'OCT',title:'ICT 1407 · Paper submission',desc:'Database Systems · R. Bandara',status:'Upcoming',tone:'s-blue'},
 {day:'31',month:'OCT',title:'Final papers to Exam Division',desc:'Cycle 2026/27 · HOD handover',status:'Plan ahead',tone:'s-neutral'}
];
const boards = [
 {day:'26',month:'OCT',title:'Repeat scrutiny · ICT 1402',meta:'Data Structures · 10:00 AM – 10:30 AM · Seminar Room 2',panel:'2 / 3 acknowledged',tone:'s-red',status:'Urgent reschedule'},
 {day:'28',month:'OCT',title:'Scrutiny board · ICT 1407 & ICT 1306',meta:'Database Systems, OOP · 9:00 AM – 11:00 AM · Board Room',panel:'3 / 3 acknowledged',tone:'s-green',status:'Panel ready'},
 {day:'30',month:'OCT',title:'Scrutiny board · ICT 1302',meta:'Digital Logic · 1:00 PM – 1:45 PM · Seminar Room 1',panel:'1 / 3 acknowledged',tone:'s-amber',status:'Awaiting responses'}
];
const activities = [
 {icon:'✓',tone:'si-green',text:'<strong>Dr. N. Perera</strong> submitted a moderation report for <strong>ICT 1407</strong>.',time:'12 minutes ago'},
 {icon:'✉',tone:'si-blue',text:'Board invitation sent to <strong>Prof. K. Jayasuriya</strong>.',time:'48 minutes ago'},
 {icon:'⚑',tone:'si-amber',text:'<strong>ICT 1402</strong> flagged for urgent repeat scrutiny.',time:'2 hours ago'},
 {icon:'▤',tone:'si-violet',text:'<strong>A. Silva</strong> submitted a new examination paper.',time:'Yesterday, 3:42 PM'}
];
const exceptions = [
 {name:'R. Bandara',paper:'ICT 1407 · Database Systems',reason:'Unexpected timetable conflict',status:'Pending',tone:'s-amber'},
 {name:'S. Hasarandi',paper:'ICT 1308 · Operating Systems',reason:'Request for 2-day extension',status:'Pending',tone:'s-amber'},
 {name:'D. Wickramasingha',paper:'ICT 1402 · Data Structures',reason:'Revision time after major corrections',status:'Under review',tone:'s-blue'}
];
const invitations = [
 {name:'Prof. K. Jayasuriya',role:'Witness lecturer',meeting:'ICT 1407 · 28 Oct, 9:00 AM',status:'Acknowledged',tone:'s-green'},
 {name:'Dr. M. Fernando',role:'Witness lecturer',meeting:'ICT 1407 · 28 Oct, 9:00 AM',status:'Acknowledged',tone:'s-green'},
 {name:'Dr. P. Silva',role:'Witness lecturer',meeting:'ICT 1402 · 26 Oct, 10:00 AM',status:'Pending response',tone:'s-amber'},
 {name:'Dr. H. Kumara',role:'Witness lecturer',meeting:'ICT 1302 · 30 Oct, 1:00 PM',status:'Pending response',tone:'s-amber'}
];
const reports = [
 {name:'ICT 1204 · Computer Architecture',id:'SAMS-RPT-2026-014',date:'22 Oct 2026',status:'Signed',tone:'s-green'},
 {name:'ICT 1101 · Introduction to IT',id:'SAMS-RPT-2026-013',date:'21 Oct 2026',status:'Signed',tone:'s-green'},
 {name:'ICT 1306 · Object Oriented Programming',id:'SAMS-RPT-2026-012',date:'20 Oct 2026',status:'Awaiting signature',tone:'s-amber'},
 {name:'ICT 1302 · Digital Logic',id:'SAMS-RPT-2026-011',date:'18 Oct 2026',status:'Revision required',tone:'s-red'}
];
let currentRole='Head of Department';
const $=id=>document.getElementById(id);
function statusClass(status){ if(/Accepted|Signed|acknowledged|Panel ready/i.test(status))return 's-green';if(/Major|Urgent|overdue|Revision required/i.test(status))return 's-red';if(/Awaiting|moderation|Pending|corrections|review|attention|reschedule/i.test(status))return 's-amber';if(/Scheduled|In moderation|Ready|Received/i.test(status))return 's-blue';return 's-neutral'; }
function esc(s){return String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
function paperIcon(){return '<span class="paper-icon">▤</span>';}
function paperRow(p,compact=false){return `<tr><td><div class="paper-cell">${paperIcon()}<div><strong>${esc(p.name)}</strong><small>${esc(p.code)}</small></div></div></td><td>${esc(p.setter)}</td>${compact?'':`<td>${esc(p.moderator)}</td>`}<td><span class="status ${statusClass(p.status)}">${esc(p.status)}</span></td><td>${esc(p.due)}</td><td><button class="row-menu" data-paper-action="${esc(p.id)}" aria-label="View ${esc(p.code)}">···</button></td></tr>`;}
function renderPapers(){const q=(($('paperSearch')?.value||$('globalSearch')?.value||'').toLowerCase().trim());const filter=$('paperStatusFilter')?.value||'all';const list=papers.filter(p=>(filter==='all'||p.status===filter)&&(!q||[p.id,p.name,p.code,p.setter,p.moderator,p.status].some(s=>s.toLowerCase().includes(q))));$('recentPapers').innerHTML=list.slice(0,5).map(p=>paperRow(p,true)).join('');$('allPapers').innerHTML=list.map(p=>paperRow(p,false)).join('');$('recentEmpty').style.display=list.length?'none':'block';$('paperEmpty').style.display=list.length?'none':'block';$('paperNavCount').textContent=papers.length;}
function renderDeadlines(){const html=deadlines.map(d=>`<div class="deadline-item"><div class="date-tile"><b>${d.day}</b><small>${d.month}</small></div><div class="deadline-info"><strong>${esc(d.title)}</strong><p>${esc(d.desc)}</p><span class="status ${d.tone}">${esc(d.status)}</span></div></div>`).join('');$('deadlineList').innerHTML=html;$('deadlineFullList').innerHTML=html;}
function renderWorkflow(){const stages=[['Paper setting',24,'100%'],['Moderation',6,'25%'],['Board scheduled',4,'17%'],['Corrections',5,'21%'],['Accepted',14,'58%']];$('workflowBars').innerHTML=stages.map(([n,c,w])=>`<div class="workflow-row"><div class="workflow-name">${n}</div><div class="track"><div class="fill" style="width:${w};${n==='Corrections'?'background:#E6A33C':n==='Accepted'?'background:#22A47A':''}"></div></div><div class="workflow-count">${c}</div></div>`).join('');}
function renderActivity(){ $('activityList').innerHTML=activities.map(a=>`<div class="activity-item"><div class="activity-icon ${a.tone}">${a.icon}</div><div class="activity-body"><p>${a.text}</p><small>${a.time}</small></div></div>`).join(''); }
function renderBoards(){ $('boardList').innerHTML=boards.map((b,i)=>`<div class="board-row"><div class="board-date"><b>${b.day}</b><small>${b.month}</small></div><div class="board-details"><strong>${esc(b.title)}</strong><p>${esc(b.meta)}</p><p style="margin-top:6px">Panel: ${esc(b.panel)}</p></div><div class="board-side"><span class="status ${b.tone}">${esc(b.status)}</span><small><button class="text-link" data-board-action="${i}">View details ↗</button></small></div></div>`).join(''); }
function renderInvitations(){ $('invitationList').innerHTML=invitations.map((i,idx)=>`<div class="report-row"><div class="report-file" style="background:var(--blue-bg);color:var(--blue)">✉</div><div class="report-meta"><strong>${esc(i.name)} <span style="color:var(--muted);font-weight:500">· ${esc(i.role)}</span></strong><small>${esc(i.meeting)}</small></div><span class="status ${i.tone}">${esc(i.status)}</span><button class="secondary-btn" data-invite-action="${idx}" style="padding:8px 10px">${i.status==='Acknowledged'?'Details':'Remind'}</button></div>`).join(''); }
function renderExceptions(){ $('exceptionList').innerHTML=exceptions.map((e,i)=>`<div class="report-row"><div class="report-file" style="background:var(--amber-bg);color:var(--amber)">◷</div><div class="report-meta"><strong>${esc(e.name)}</strong><small>${esc(e.paper)}<br>${esc(e.reason)}</small><span class="status ${e.tone}" style="margin-top:7px">${esc(e.status)}</span></div><button class="secondary-btn" data-exception-action="${i}" style="padding:8px">Review</button></div>`).join(''); }
function renderReports(){ $('reportList').innerHTML=reports.map((r,i)=>`<div class="report-row"><div class="report-file">▧</div><div class="report-meta"><strong>${esc(r.name)}</strong><small>${esc(r.id)} · ${esc(r.date)}</small></div><span class="status ${r.tone}">${esc(r.status)}</span><button class="secondary-btn" data-report-action="${i}" style="padding:8px 10px">Details</button></div>`).join(''); }
function renderSubmissions(){ $('submissionTable').innerHTML=papers.filter(p=>p.status==='Accepted'||p.status==='Major corrections').map((p,i)=>`<tr><td><div class="paper-cell">${paperIcon()}<div><strong>${esc(p.name)}</strong><small>${esc(p.code)}</small></div></div></td><td>04 Nov 2026</td><td>30 Oct 2026</td><td>${p.status==='Accepted'?'22 Oct 2026':'—'}</td><td><span class="status ${p.status==='Accepted'?'s-green':'s-amber'}">${p.status==='Accepted'?'Submitted':'Awaiting approval'}</span></td><td><button class="row-menu" data-submission-action="${i}">···</button></td></tr>`).join(''); }
function renderModeration(){const list=papers.filter(p=>p.status!=='Accepted');$('moderationCards').innerHTML=list.slice(0,6).map(p=>`<article class="info-card"><div style="display:flex;justify-content:space-between;gap:8px"><div class="card-symbol">◉</div><span class="status ${statusClass(p.status)}" style="height:max-content">${esc(p.status)}</span></div><h3>${esc(p.name)}</h3><p>${esc(p.code)} · Setter: ${esc(p.setter)}<br>Moderator: ${esc(p.moderator)}<br>Review round: ${p.round}</p><div class="card-bottom"><small>Due ${esc(p.due)}</small><button class="text-link" data-moderation-action="${esc(p.id)}">Open review ↗</button></div></article>`).join(''); }
function renderAll(){renderPapers();renderDeadlines();renderWorkflow();renderActivity();renderBoards();renderInvitations();renderExceptions();renderReports();renderSubmissions();renderModeration();}
let toastTimer;function toast(msg){const t=$('toast');t.textContent=msg;t.classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>t.classList.remove('show'),3000);}
const titles={overview:'Overview',papers:'Examination Papers',moderation:'Moderation',boards:'Scrutiny Boards',invitations:'Panel Invitations',deadlines:'Deadlines & Exceptions',reports:'Final Reports',submissions:'Exam Division',users:'Users & Roles'};
function go(page){if(!titles[page])return;document.querySelectorAll('.page-view').forEach(v=>v.classList.remove('active'));$('page-'+page).classList.add('active');document.querySelectorAll('.nav-item').forEach(b=>b.classList.toggle('active',b.dataset.page===page));$('crumbCurrent').textContent=titles[page];$('sidebar').classList.remove('open');window.scrollTo({top:0,behavior:'smooth'});}
function showModal({title,subtitle,fields,submit='Save record',onSave}){ $('modalTitle').textContent=title;$('modalSubtitle').textContent=subtitle;$('modalSubmit').textContent=submit;$('modalFields').innerHTML=fields.map(f=>{let type=f.type||'text';let control=type==='select'?`<select name="${f.name}" ${f.required?'required':''}>${(f.options||[]).map(o=>`<option value="${esc(o.value??o)}">${esc(o.label??o)}</option>`).join('')}</select>`:type==='textarea'?`<textarea name="${f.name}" placeholder="${esc(f.placeholder||'')}" ${f.required?'required':''}></textarea>`:`<input name="${f.name}" type="${type}" placeholder="${esc(f.placeholder||'')}" ${f.required?'required':''} ${f.min?'min="'+f.min+'"':''}/>`;return `<div class="field ${f.full?'full':''}"><label>${esc(f.label)}</label>${control}</div>`;}).join('');$('modalBackdrop').classList.add('open');$('modalForm').onsubmit=e=>{e.preventDefault();const data=Object.fromEntries(new FormData(e.target).entries());if(onSave)onSave(data);closeModal();};setTimeout(()=>$('modalFields').querySelector('input,select,textarea')?.focus(),50);}
function closeModal(){$('modalBackdrop').classList.remove('open');$('modalForm').onsubmit=null;}
function openPaperModal(){showModal({title:'New paper submission',subtitle:'Register an examination paper for the current cycle.',fields:[{name:'code',label:'Course code',placeholder:'e.g. ICT 1407',required:true},{name:'name',label:'Course name',placeholder:'Database Systems',required:true},{name:'setter',label:'Paper setter',placeholder:'Lecturer full name',required:true},{name:'moderator',label:'Moderator',placeholder:'Assign later if unknown'},{name:'due',label:'Submission deadline',type:'date',required:true},{name:'status',label:'Initial status',type:'select',options:['Awaiting moderation','In moderation']}],submit:'Create paper',onSave:d=>{const date=d.due?new Date(d.due+'T12:00:00'):null;const p={id:d.code.toUpperCase().replace(/\s/g,''),code:d.code.toUpperCase(),name:d.name,setter:d.setter,moderator:d.moderator||'Pending assignment',status:d.status||'Awaiting moderation',due:date?date.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}):'Not set',dueDay:date?String(date.getDate()).padStart(2,'0'):'—',dueMonth:date?date.toLocaleDateString('en-US',{month:'short'}).toUpperCase():'',round:1};papers.unshift(p);activities.unshift({icon:'▤',tone:'si-violet',text:`<strong>${esc(d.setter)}</strong> registered <strong>${esc(p.code)}</strong> in the demo workspace.`,time:'Just now'});renderAll();toast('Paper added to this prototype. Backend persistence is not connected yet.');go('papers');}});}
function openBoardModal(){showModal({title:'Schedule a scrutiny board',subtitle:'Create a draft meeting. The real system must validate panel adequacy and acknowledgements before confirmation.',fields:[{name:'title',label:'Meeting title',placeholder:'e.g. Scrutiny board · ICT 1407',required:true,full:true},{name:'date',label:'Meeting date',type:'date',required:true},{name:'time',label:'Start time',type:'time',required:true},{name:'venue',label:'Venue',placeholder:'Board Room',required:true},{name:'setter',label:'Paper setter',placeholder:'Setter name',required:true},{name:'witness1',label:'Witness lecturer 1',placeholder:'Lecturer name',required:true},{name:'witness2',label:'Witness lecturer 2',placeholder:'Lecturer name',required:true}],submit:'Create draft meeting',onSave:d=>{let dt=new Date(d.date+'T12:00:00');boards.unshift({day:String(dt.getDate()).padStart(2,'0'),month:dt.toLocaleDateString('en-US',{month:'short'}).toUpperCase(),title:d.title,meta:`${d.time} · ${d.venue}`,panel:'0 / 3 acknowledged',tone:'s-amber',status:'Draft · verify panel'});renderBoards();toast('Draft meeting added. Panel validation still needs backend logic.');go('boards');}});}
function openDeadlineModal(){showModal({title:'Add a deadline',subtitle:'Create a visible deadline for the current exam cycle.',fields:[{name:'title',label:'Deadline title',placeholder:'Paper submission',required:true,full:true},{name:'date',label:'Due date',type:'date',required:true},{name:'type',label:'Deadline type',type:'select',options:['Paper submission','Moderation report','Board rescheduling','Final submission']},{name:'description',label:'Description',type:'textarea',full:true}],submit:'Add deadline',onSave:d=>{const dt=new Date(d.date+'T12:00:00');deadlines.push({day:String(dt.getDate()).padStart(2,'0'),month:dt.toLocaleDateString('en-US',{month:'short'}).toUpperCase(),title:d.title,desc:d.description||d.type,status:'Upcoming',tone:'s-blue'});renderDeadlines();toast('Deadline added to the prototype.');}});}
function openExceptionModal(){showModal({title:'Request a deadline exception',subtitle:'Demo lecturer workflow. In the complete system, the HOD reviews the request.',fields:[{name:'paper',label:'Paper / course code',placeholder:'ICT 1407',required:true},{name:'lecturer',label:'Lecturer name',placeholder:'Full name',required:true},{name:'reason',label:'Reason for request',type:'textarea',full:true,required:true},{name:'date',label:'Requested new deadline',type:'date',required:true}],submit:'Submit request',onSave:d=>{exceptions.unshift({name:d.lecturer,paper:d.paper,reason:d.reason,status:'Pending',tone:'s-amber'});renderExceptions();toast('Exception request added to demo data.');}});}
function openReportDetails(i){const r=reports[i];toast(`${r.name} · ${r.id}. Actual report files and signatures require backend integration.`);}
function downloadCSV(){const rows=[['Course code','Course name','Setter','Moderator','Status','Due date'],...papers.map(p=>[p.code,p.name,p.setter,p.moderator,p.status,p.due])];const csv=rows.map(r=>r.map(v=>'"'+String(v).replace(/"/g,'""')+'"').join(',')).join('\r\n');const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='sams-examination-papers.csv';a.click();URL.revokeObjectURL(url);toast('CSV export prepared from current demo data.');}
document.querySelectorAll('[data-page]').forEach(b=>b.addEventListener('click',()=>go(b.dataset.page)));
document.querySelectorAll('[data-goto]').forEach(b=>b.addEventListener('click',()=>go(b.dataset.goto)));
document.querySelectorAll('[data-action="newPaper"]').forEach(b=>b.addEventListener('click',openPaperModal));
$('newPaperBtn').onclick=openPaperModal;$('newPaperBtn2').onclick=openPaperModal;$('scheduleBoardBtn').onclick=openBoardModal;$('newDeadlineBtn').onclick=openDeadlineModal;$('assignModeratorBtn').onclick=()=>showModal({title:'Assign a moderator',subtitle:'Create a draft moderator assignment.',fields:[{name:'paper',label:'Paper / course',type:'select',options:papers.map(p=>({value:p.code,label:p.code+' · '+p.name}))},{name:'moderator',label:'Moderator name',placeholder:'Full name',required:true},{name:'email',label:'Moderator email',type:'email',placeholder:'name@university.edu'},{name:'date',label:'Assignment date',type:'date'}],submit:'Save assignment',onSave:d=>{const p=papers.find(p=>p.code===d.paper);if(p)p.moderator=d.moderator;renderAll();toast('Moderator assignment saved in demo data.');}});
$('sendInvitationBtn').onclick=()=>showModal({title:'Create panel invitation',subtitle:'Record a new invitation. Sending email requires a configured backend.',fields:[{name:'name',label:'Panel member',placeholder:'Lecturer name',required:true},{name:'role',label:'Panel role',type:'select',options:['Setter','Witness lecturer','Convener']},{name:'meeting',label:'Board session',type:'select',options:boards.map(b=>({value:b.title,label:b.title}))},{name:'email',label:'Email address',type:'email',placeholder:'name@university.edu'}],submit:'Create invitation',onSave:d=>{invitations.unshift({name:d.name,role:d.role,meeting:d.meeting,status:'Pending response',tone:'s-amber'});renderInvitations();toast('Invitation record created; no email was sent in this prototype.');}});
$('recordSubmissionBtn').onclick=()=>showModal({title:'Record final submission',subtitle:'Record the HOD handover to the Examination Division.',fields:[{name:'paper',label:'Approved paper',type:'select',options:papers.map(p=>({value:p.code,label:p.code+' · '+p.name}))},{name:'date',label:'Submission date',type:'date',required:true},{name:'time',label:'Submission time',type:'time'},{name:'status',label:'Handover status',type:'select',options:['Submitted','Sealed','Awaiting submission']}],submit:'Save handover',onSave:d=>toast(`Handover record for ${d.paper} captured in demo only.`)});
$('addUserBtn').onclick=()=>showModal({title:'Add a user',subtitle:'Prototype form; authentication and secure account creation are not connected yet.',fields:[{name:'name',label:'Full name',placeholder:'Full name',required:true},{name:'email',label:'University email',type:'email',placeholder:'name@university.edu',required:true},{name:'role',label:'Role',type:'select',options:['Lecturer / Paper setter','Moderator','Head of Department']},{name:'department',label:'Department',placeholder:'Department of Computing'}],submit:'Create user record',onSave:d=>toast(`Demo user record created for ${d.name}. No account or password was created.`)});
$('notificationBtn').onclick=()=>toast('You have 2 pending panel acknowledgements and 2 critical deadlines in this demo.');$('roleBtn').onclick=()=>{window.location.href='logout.php';};
$('helpBtn').onclick=()=>toast('Workflow: submit paper → moderation → HOD verification → board meeting → decision and revisions → signed report → Examination Division.');$('activityMore').onclick=()=>toast('The full audit log will be connected to the database in a later development step.');$('exportPapers').onclick=downloadCSV;$('reportExport').onclick=()=>{const rows=[['Report','Report ID','Date','Status'],...reports.map(r=>[r.name,r.id,r.date,r.status])];const csv=rows.map(r=>r.map(v=>'"'+String(v).replace(/"/g,'""')+'"').join(',')).join('\r\n');const url=URL.createObjectURL(new Blob([csv],{type:'text/csv'}));const a=document.createElement('a');a.href=url;a.download='sams-report-list.csv';a.click();URL.revokeObjectURL(url);toast('Report list exported from demo data.');};
$('paperSearch').addEventListener('input',()=>{ $('globalSearch').value='';renderPapers(); });$('paperStatusFilter').addEventListener('change',renderPapers);$('globalSearch').addEventListener('input',()=>{if(!$('page-papers').classList.contains('active'))go('papers');$('paperSearch').value='';renderPapers();});
$('modalClose').onclick=closeModal;$('modalCancel').onclick=closeModal;$('modalBackdrop').addEventListener('click',e=>{if(e.target===$('modalBackdrop'))closeModal();});document.addEventListener('keydown',e=>{if(e.key==='Escape')closeModal();if(e.key==='/'&&!['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)){e.preventDefault();$('globalSearch').focus();}});$('mobileMenu').onclick=()=>$('sidebar').classList.toggle('open');
document.addEventListener('click',e=>{const pa=e.target.closest('[data-paper-action]');if(pa){const p=papers.find(p=>p.id===pa.dataset.paperAction);if(p)showModal({title:p.code+' · '+p.name,subtitle:'Paper details in the SAMS prototype.',fields:[{name:'status',label:'Update status',type:'select',options:['Awaiting moderation','In moderation','Scheduled','Minor corrections','Major corrections','Accepted']},{name:'moderator',label:'Moderator',placeholder:p.moderator},{name:'due',label:'Deadline',placeholder:p.due},{name:'notes',label:'Internal note',type:'textarea',full:true}],submit:'Save changes',onSave:d=>{p.status=d.status;p.moderator=d.moderator||p.moderator;p.due=d.due||p.due;renderAll();toast('Paper updated in demo data.');}});}
const ba=e.target.closest('[data-board-action]');if(ba){const b=boards[Number(ba.dataset.boardAction)];toast(`${b.title}: ${b.panel}. Production workflow must check setter + two witnesses and required acknowledgements.`)}const ia=e.target.closest('[data-invite-action]');if(ia){const item=invitations[Number(ia.dataset.inviteAction)];if(item.status!=='Acknowledged'){item.status='Acknowledged';item.tone='s-green';renderInvitations();toast('Demo acknowledgement recorded. Real acknowledgements must be tied to authenticated users.');}else toast(`${item.name}: ${item.status}.`)}const ea=e.target.closest('[data-exception-action]');if(ea){const item=exceptions[Number(ea.dataset.exceptionAction)];showModal({title:'Review exception request',subtitle:`${item.name} · ${item.paper}`,fields:[{name:'decision',label:'HOD decision',type:'select',options:['Pending','Approved','Rejected']},{name:'comment',label:'HOD comment',type:'textarea',full:true}],submit:'Save decision',onSave:d=>{item.status=d.decision;item.tone=d.decision==='Approved'?'s-green':d.decision==='Rejected'?'s-red':'s-amber';renderExceptions();toast('Decision recorded in demo data.');}})}const ra=e.target.closest('[data-report-action]');if(ra)openReportDetails(Number(ra.dataset.reportAction));const ma=e.target.closest('[data-moderation-action]');if(ma){const p=papers.find(p=>p.id===ma.dataset.moderationAction);showModal({title:'Moderation review',subtitle:`Review round ${p.round} · ${p.code}`,fields:[{name:'satisfaction',label:'Moderator satisfaction',type:'select',options:['Not satisfied','Satisfied']},{name:'comments',label:'Review comments',type:'textarea',full:true,required:true},{name:'report',label:'Report reference',placeholder:'Report filename or reference'}],submit:'Submit review result',onSave:d=>{p.status=d.satisfaction==='Satisfied'?'Scheduled':'In moderation';activities.unshift({icon:'✓',tone:'si-green',text:`Moderation status updated for <strong>${esc(p.code)}</strong>.`,time:'Just now'});renderAll();toast('Review result saved in demo data.');}})}const sa=e.target.closest('[data-submission-action]');if(sa)toast('Submission record details will be connected to the database.');});
renderAll();
</script>
</body>
</html>