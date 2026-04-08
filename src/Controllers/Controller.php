<?php 

namespace Cariboo\Choco\Controllers;
 
abstract class Controller
{
    public function view(string $view, array $data = [], string $layout = null)
    {
        // On extrait les données
        extract($data, EXTR_SKIP);

        // Lire le contenu de la vue
        $viewFile = __DIR__ . "/../../views/{$view}.choco.html";
        $content = file_get_contents($viewFile);

          // Transformer les directives
        $replacements = [
            '/\{\{\s*(.*?)\s*\}\}/' => '<?php echo $1; ?>',
            '/@if\s*\((.*?)\)/' => '<?php if($1): ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif ?>',
            '/@foreach\s*\((.*?)\)/' => '<?php foreach($1): ?>',
            '/@endforeach/' => '<?php endforeach; ?>'
        ];
        
        $content = preg_replace(array_keys($replacements), array_values($replacements), $content);

        // On crée le fichier temporaire
        $tmpFile = sys_get_temp_dir() . '/tpl_' . uniqid() . '.php';
        file_put_contents($tmpFile, $content);

        // On execute la vue et on récupère le contenu
        \ob_start();
        require $tmpFile;
        $slot = \ob_get_clean();
        
        unlink($tmpFile); // Supprimer le fichier temporaire

        // Charger le layout et injecter le rendu
        \ob_start();
        $layoutFile = __DIR__ . "/../../views/" . ($layout ?? "layouts/app") . ".choco.html";
        require $layoutFile; 
        return \ob_get_clean();
    }

    public function redirect(string $url, int $status)
    {
        \http_response_code($status);
        header("location: {$url}");
    }
}