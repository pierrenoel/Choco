<?php 

namespace Choco\Core;
 
abstract class Controller
{
    public function view(string $view, array $data = [], string $layout = null)
    {
        // On extrait les données
        extract($data, EXTR_SKIP);

        // Lire le contenu de la vue
        $viewFile = __DIR__ . "/../../resources/views/{$view}.choco.html";
        $content = file_get_contents($viewFile);

          // Transformer les directives
        $replacements = [
            '/\{\{\s*(.*?)\s*\}\}/' => '<?php echo $1; ?>',
            '/@if\s*\((.*?)\)/' => '<?php if($1): ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif ?>',
            '/@foreach\s*\((.*?)\)/' => '<?php foreach($1): ?>',
            '/@endforeach/' => '<?php endforeach; ?>',
            '/@csrf/' => '<input type="hidden" name="csrf" value="<?= $_SESSION["token_csrf"] ?>"/>',
            '/@delete/' =>'<input type="hidden" name="_method" value="DELETE" />', 
            '/@put/' =>'<input type="hidden" name="_method" value="PUT" />' 
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
        $layoutFile = __DIR__ . "/../../resources/views/" . ($layout ?? "layouts/app") . ".choco.html";
        require $layoutFile; 
        return \ob_get_clean();
    }

    public function csrf()
    {
        if($_SESSION["token_csrf"] !== $_POST["csrf"]) $this->redirect("/not-found",404);
    }
}