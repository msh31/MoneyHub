<?php
class Language
{
    private $translations = [];
    private $currentLang = 'en';
    
    public function __construct($lang = 'en')
    {
        $this->setLanguage($lang);
    }
    
    public function setLanguage($lang)
    {
        $this->currentLang = $lang;
        $langFile = __DIR__ . '/' . $lang . '.php';
        
        if (file_exists($langFile)) {
            $this->translations = include $langFile;
        } else {
            $this->translations = include __DIR__ . '/en.php';
        }
    }
    
    public function get($key, $default = null)
    {
        return isset($this->translations[$key]) ? $this->translations[$key] : ($default ?: $key);
    }
    
    public function getCurrentLanguage()
    {
        return $this->currentLang;
    }
}

function t($key, $default = null)
{
    global $lang;
    return $lang->get($key, $default);
}
?>
