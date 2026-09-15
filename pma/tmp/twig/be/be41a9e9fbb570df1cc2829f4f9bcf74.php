<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* setup/config/index.twig */
class __TwigTemplate_2f60ab6179674d8c35db363cba7bd363 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "setup/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("setup/base.twig", "setup/config/index.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 3
        yield "
<h2>";
yield _gettext("Configuration file");
        // line 4
        yield "</h2>

<div class=\"card\">
  <h5 class=\"card-header\">config.inc.php</h5>
  <div class=\"card-body\">
    <form id=\"configFileForm\" method=\"post\" action=\"config.php\">
      ";
        // line 10
        if (($context["has_check_page_refresh"] ?? null)) {
            // line 11
            yield "        <input type=\"hidden\" name=\"check_page_refresh\" id=\"check_page_refresh\" value=\"\">
      ";
        }
        // line 13
        yield "      ";
        yield PhpMyAdmin\Url::getHiddenInputs("", "", 0, "server");
        yield "
      <input type=\"hidden\" name=\"eol\" value=\"";
        // line 14
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["eol"] ?? null), "html", null, true);
        yield "\">

      <textarea class=\"form-control font-monospace\" rows=\"20\" name=\"textconfig\" spellcheck=\"false\" aria-label=\"";
yield _gettext("Generated configuration file");
        // line 16
        yield "\">";
        // line 17
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["config"] ?? null), "html", null, true);
        // line 18
        yield "</textarea>
    </form>
  </div>
  <div class=\"card-footer\">
    <input class=\"btn btn-primary\" type=\"submit\" form=\"configFileForm\" name=\"submit_download\" value=\"";
yield _gettext("Download");
        // line 22
        yield "\">
  </div>
</div>

";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "setup/config/index.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  91 => 22,  84 => 18,  82 => 17,  80 => 16,  74 => 14,  69 => 13,  65 => 11,  63 => 10,  55 => 4,  51 => 3,  47 => 2,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "setup/config/index.twig", "/home/mcdl.mul.edu.pk/public_html/pma/templates/setup/config/index.twig");
    }
}
