<?php

# ==============================================================
# ----------------- AUTO-LOADERS $ URL-PARSER ------------------
# ==============================================================
require_once 'core/Request.php';
require_once 'core/Bootstrap.php';
require_once 'core/Controller.php';


# ==============================================================
# -------------------- APP CONFIGURATIONS ---------------------
# ==============================================================
/* ---- Database Constraints ---- */
require_once 'config/database.php';
/* ---- Path Constraints ---- */
require_once 'config/paths.php';


# ==============================================================
# ------------------------- LIBRARIES --------------------------
# ==============================================================
/* ---- Database Configuration ---- */
require_once 'core/Database.php';
/* ---- User Sessions ---- */
require_once 'core/Session.php';


# ==============================================================
# ------------------------- UTILITIES --------------------------
# ==============================================================
/* ---- Function to Autoload Utility Classes ---- */
require_once 'utilities/autoload.php';

?>
