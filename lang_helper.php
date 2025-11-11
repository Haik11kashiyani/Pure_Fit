<?php
// Language Helper Functions

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default language
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en'; // Default to English
}

// Load language file
function loadLanguage($lang = null) {
    if ($lang === null) {
        $lang = $_SESSION['language'] ?? 'en';
    }
    
    $lang_file = __DIR__ . '/languages/' . $lang . '.php';
    
    if (file_exists($lang_file)) {
        return include $lang_file;
    }
    
    // Fallback to English if language file not found
    return include __DIR__ . '/languages/en.php';
}

// Get translation
function __($key, $lang = null) {
    static $translations = null;
    
    if ($translations === null || $lang !== null) {
        $translations = loadLanguage($lang);
    }
    
    return $translations[$key] ?? $key;
}

// Change language
function changeLanguage($lang) {
    $allowed_languages = ['en', 'hi', 'gu', 'es'];
    
    if (in_array($lang, $allowed_languages)) {
        $_SESSION['language'] = $lang;
        return true;
    }
    
    return false;
}

// Get current language
function getCurrentLanguage() {
    return $_SESSION['language'] ?? 'en';
}

// Get available languages
function getAvailableLanguages() {
    return [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇺🇸'],
        'hi' => ['name' => 'Hindi', 'native' => 'हिंदी', 'flag' => '🇮🇳'],
        'gu' => ['name' => 'Gujarati', 'native' => 'ગુજરાતી', 'flag' => '🇮🇳'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸'],
    ];
}
?>
