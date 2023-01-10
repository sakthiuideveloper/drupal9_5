<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__1fdb03fa36a6a4fadf93499ff9e7383a7693c0d12f53126fc5ccd550d8ace648 */
class __TwigTemplate_db27f864c972ca3f91fd6e626c15f1fca881947b307adea9a80478d0e1cf9149 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"image-top\">
<div class=\"green-striped\"><i class=\"fa fa-star\" aria-hidden=\"true\"></i></div>
<div class=\"slider-image\">";
        // line 3
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_images"] ?? null), 3, $this->source), "html", null, true);
        echo "</div>
<div class=\"price\">";
        // line 4
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_prices_"] ?? null), 4, $this->source), "html", null, true);
        echo "</div>
</div>
<div class=\"content-slider-wrap\">
<div class=\"content-bottom xplr pt\">
<h2 class=\"content-title\">";
        // line 8
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 8, $this->source), "html", null, true);
        echo "</h2>
<div class=\"tags\"><i class=\"fa fa-tags\" aria-hidden=\"true\"></i> ";
        // line 9
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_tag"] ?? null), 9, $this->source), "html", null, true);
        echo " </div>
<div class=\"date\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i>";
        // line 10
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_date"] ?? null), 10, $this->source), "html", null, true);
        echo "</div>
<div class=\"location\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> ";
        // line 11
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_locations"] ?? null), 11, $this->source), "html", null, true);
        echo "</div>
</div>
<div class=\"social-wrap\">
<div class=\"social-share flex space-between\">
<i class=\"fa fa-heart-o\" aria-hidden=\"true\"></i>
<i class=\"fa fa-cloud-download\" aria-hidden=\"true\"></i>
<i class=\"fa fa-share-alt\" aria-hidden=\"true\"></i>
<div class=\"view-more\"><a href=\"#\">View Details</a></div>
</div>
</div>
</div>";
    }

    public function getTemplateName()
    {
        return "__string_template__1fdb03fa36a6a4fadf93499ff9e7383a7693c0d12f53126fc5ccd550d8ace648";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 11,  62 => 10,  58 => 9,  54 => 8,  47 => 4,  43 => 3,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{# inline_template_start #}<div class=\"image-top\">
<div class=\"green-striped\"><i class=\"fa fa-star\" aria-hidden=\"true\"></i></div>
<div class=\"slider-image\">{{ field_images }}</div>
<div class=\"price\">{{ field_prices_ }}</div>
</div>
<div class=\"content-slider-wrap\">
<div class=\"content-bottom xplr pt\">
<h2 class=\"content-title\">{{ title }}</h2>
<div class=\"tags\"><i class=\"fa fa-tags\" aria-hidden=\"true\"></i> {{ field_tag }} </div>
<div class=\"date\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i>{{ field_date }}</div>
<div class=\"location\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> {{ field_locations }}</div>
</div>
<div class=\"social-wrap\">
<div class=\"social-share flex space-between\">
<i class=\"fa fa-heart-o\" aria-hidden=\"true\"></i>
<i class=\"fa fa-cloud-download\" aria-hidden=\"true\"></i>
<i class=\"fa fa-share-alt\" aria-hidden=\"true\"></i>
<div class=\"view-more\"><a href=\"#\">View Details</a></div>
</div>
</div>
</div>", "__string_template__1fdb03fa36a6a4fadf93499ff9e7383a7693c0d12f53126fc5ccd550d8ace648", "");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 3);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
