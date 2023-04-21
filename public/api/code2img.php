<?php

class Code2ImgAPI
{
    
    private $base_url = "https://code2img.vercel.app/api/to-image";

    private $valid_themes = array(
        "a11y-dark",
        "atom-dark",
        "base16-ateliersulphurpool.light",
        "cb",
        "darcula",
        "default",
        "dracula",
        "duotone-dark",
        "duotone-earth",
        "duotone-forest",
        "duotone-light",
        "duotone-sea",
        "duotone-space",
        "ghcolors",
        "hopscotch",
        "material-dark",
        "material-light",
        "material-oceanic",
        "nord",
        "pojoaque",
        "shades-of-purple",
        "synthwave84",
        "vs",
        "vsc-dark-plus",
        "xonokai"
    );

    private $valid_languages = array(
        "c",
        "css",
        "cpp",
        "go",
        "html",
        "java",
        "javascript",
        "jsx",
        "php",
        "python",
        "rust",
        "typescript"
    );

    public function get_image($code, $theme = "default", $language = "plain")
    {
        // Check if the theme and language are valid
        if (!in_array($theme, $this->valid_themes)) {
            throw new InvalidArgumentException("Invalid theme provided");
        }
        if (!in_array($language, $this->valid_languages)) {
            throw new InvalidArgumentException("Invalid language provided");
        }

        $url = $this->base_url . "?theme=" . urlencode($theme) . "&language=" . urlencode($language) . "&show-background=false" . "&scale=1";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $code);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/plain"));
        $response = curl_exec($ch);

        // Check if the request was successful
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            throw new Exception("Request failed with HTTP code " . $http_code);
        }

        curl_close($ch);

        return $response;
    }
}

?>
