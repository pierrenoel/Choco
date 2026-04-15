<?php 

namespace Choco\Core;

use Choco\Core\Services\ValidationService;
 
abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = null)
    {
        // On extrait les données
        extract($data, EXTR_SKIP);

        // Lire le contenu de la vue
        $viewFile = __DIR__ . "/../../resources/views/{$view}.choco.html";

        $content = file_get_contents($viewFile);

        // Native methods in If()
        $content = preg_replace_callback(
            '/@if\s*(\((?:[^()]+|(?1))*\))/s', 
            fn ($m) => '<?php if ' . trim($m[1]) . ': ?>',
            $content
        );

        // OLD
        $content = preg_replace_callback(
            '/@old\((.*?)\)/',
            function ($m) {
                $key = trim($m[1]);
                return '<?= $_SESSION["old"]["' . $key . '"] ?? "" ?>';
            },
            $content
        );
        // Errors
        $content = preg_replace_callback(
            '/@error\((.*?)\)/',
            function ($m) {
                $key = trim($m[1]);
                return '<?= $_SESSION["errors"]["' . $key . '"][0] ?? "" ?>';
            },
            $content
        );
        
        $replacements = [
            '/\{\{\s*(.*?)\s*\}\}/' => '<?php echo $1; ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif; ?>',
            '/@foreach\s*\((.*?)\)/' => '<?php foreach($1): ?>',
            '/@endforeach/' => '<?php endforeach; ?>',
            '/@csrf/' => '<input type="hidden" name="csrf" value="<?= $_SESSION["token_csrf"] ?>"/>',
            '/@delete/' =>'<input type="hidden" name="_method" value="DELETE" />', 
            '/@put/' =>'<input type="hidden" name="_method" value="PUT" />',
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

    protected function csrf()
    {
        if($_SESSION["token_csrf"] !== $_POST["csrf"]) $this->redirect("/not-found",404);
    }

    protected function validate($object)
    {
        $reflection = new \ReflectionClass($object);
        $name = $reflection->getShortName();
        
        $validation = new ValidationService($name);   
        return $validation->execute();
    }

}