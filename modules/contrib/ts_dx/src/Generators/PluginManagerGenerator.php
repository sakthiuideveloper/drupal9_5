<?php

namespace Drupal\ts_dx\Generators;

use Drupal\Core\Extension\ModuleHandlerInterface;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Helper\Renderer;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PluginManagerGenerator.
 *
 * Générateur de plugins via drush (Yoohoo!)
 *
 * @package Drupal\config_entity_cloner\Generators
 */
class PluginManagerGenerator extends BaseGenerator {

  /**
   * The command name.
   *
   * @var string
   */
  protected $name = 'plugin-manager-generator';

  /**
   * The description.
   *
   * @var string
   */
  protected $description = 'Generates a plugin manager.';

  /**
   * The alias.
   *
   * @var string
   */
  protected $alias = 'plugin-manager';

  /**
   * The template dir path.
   *
   * @var string
   */
  protected $templatePath = __DIR__;

  /**
   * Module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * Root.
   *
   * @var string
   */
  protected $root;

  /**
   * PluginManagerGenerator constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param string $root
   *   Thes root.
   * @param string $name
   *   The name.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler = NULL, string $root = NULL, $name = NULL) {
    parent::__construct($name);
    $this->moduleHandler = $moduleHandler;
    $this->root = $root;
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $questions = [];
    $questions['machine_name'] = new Question('Module machine name');
    $questions['machine_name']->setValidator([
      Utils::class,
      'validateMachineName',
    ]);
    $questions['prefix'] = new Question('Manager Prefix');
    $questions['prefix']->setValidator([Utils::class, 'validateRequired']);

    // Init weight.
    $questions['has_weight'] = new ConfirmationQuestion('Activer la gestion de poids ?');

    $this->collectVars($input, $output, $questions);

    $this->vars['camel_prefix'] = Utils::camelize($this->vars['prefix']);
    $this->vars['slug_prefix'] = Utils::camel2machine($this->vars['camel_prefix']);
    $this->vars['human_slug_prefix'] = str_replace('_', '-', $this->vars['slug_prefix']);

    // Generate plugin manager.
    $this->generatePluginManager();

    // Generate generator.
    $this->generateGenerator();
  }

  /**
   * Generate plugin manager.
   */
  protected function generatePluginManager() {
    // Create Plugin Manager.
    $this->addFile()
      ->path('src/PluginManager/' . $this->vars['camel_prefix'] . '/' . $this->vars['camel_prefix'] . 'PluginManager.php')
      ->template('templates/plugin-manager.twig');

    // Annotation.
    $this->addFile()
      ->path('src/PluginManager/' . $this->vars['camel_prefix'] . '/' . $this->vars['camel_prefix'] . 'Annotation.php')
      ->template('templates/plugin-annotation.twig');

    // Interface.
    $this->addFile()
      ->path('src/PluginManager/' . $this->vars['camel_prefix'] . '/' . $this->vars['camel_prefix'] . 'Interface.php')
      ->template('templates/plugin-interface.twig');

    // Wrapper.
    $this->addFile()
      ->path('src/PluginManager/' . $this->vars['camel_prefix'] . '/Abstract' . $this->vars['camel_prefix'] . '.php')
      ->template('templates/plugin-abstract.twig');
  }

  /**
   * Generate generator files.
   */
  protected function generateGenerator() {
    // Generator.
    $this->addFile()
      ->path('src/Generators/' . $this->vars['camel_prefix'] . '/' . $this->vars['camel_prefix'] . 'PluginGenerator.php')
      ->template('templates/generator-generator.twig');

    // Template.
    $this->addFile()
      ->path('src/Generators/' . $this->vars['camel_prefix'] . '/templates/plugin.twig')
      ->template('templates/generator-plugin-template.twig');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $result = parent::execute($input, $output);

    // Render all assets.
    $renderer = $this->getHelper('dcg_renderer');
    $this->appendInYml($renderer, $this->vars['machine_name'] . '.services.yml', 'templates/plugin-services.twig');
    $this->appendInYml($renderer, 'drush.services.yml', 'templates/generator-services.twig');

    drupal_flush_all_caches();

    return $result;
  }

  /**
   * Append in yml files.
   *
   * @param \DrupalCodeGenerator\Helper\Renderer $renderer
   *   The renderer.
   * @param string $relativePath
   *   THe destination path.
   * @param string $content
   *   THe content.
   */
  protected function appendInYml(Renderer $renderer, string $relativePath, string $content) {
    $adds = $renderer->render($content, $this->vars) . "\n";

    $destinationDir = $this->root . '/' . drupal_get_path('module', $this->vars['machine_name']) . '/' . $relativePath;

    if (file_exists($destinationDir)) {
      $content = file_get_contents($destinationDir);
    }
    else {
      $content = 'services :';
    }
    file_put_contents($destinationDir, $content . $adds);
  }

}
