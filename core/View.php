<?php
declare(strict_types=1);

namespace Core;

/**
 * @file View.php
 * @brief Moteur de rendu minimaliste pour les vues PHP.
 *
 * @details
 * - La méthode statique `render()` charge un fichier de vue
 *   (dans `app/Views/`) et injecte des données.
 * - Les variables fournies dans `$data` deviennent disponibles
 *   dans le fichier de vue grâce à `extract()`.
 * - La sortie HTML est capturée via un buffer (`ob_start()`),
 *   puis renvoyée sous forme de chaîne.
 *
 * Exemple :
 * @code
 * $html = View::render("selection.php", [
 *   "emotions" => $map->all(),
 *   "selected" => "colere"
 * ]);
 * $res->html($html);
 * @endcode
 */
final class View
{
  /**
   * @brief Rend une vue en lui injectant des données.
   *
   * @param string $template Nom du fichier de vue (relatif à `app/Views/`)
   * @param array $data Variables à rendre disponibles dans la vue
   * @return string Code HTML généré
   *
   * @throws \RuntimeException Si le fichier de vue n’existe pas.
   */
  public static function render(string $template_name, array $data = []): string
  {
    // Construit le chemin absolu vers le fichier de vue
    $viewPath = __DIR__ . '/../www/partials/' . $template_name;

    if (!is_file($viewPath)) {
      throw new \RuntimeException("Vue introuvable: $template_name");
    }

    // Transforme chaque clé de $data en variable locale
    // Exemple: ["emotions" => [...]] → $emotions
    extract($data, EXTR_SKIP);

    // Active la mise en tampon de sortie pour capturer le HTML produit
    ob_start();
    include $viewPath;

    // Retourne le HTML généré
    return (string) ob_get_clean();
  }
}
