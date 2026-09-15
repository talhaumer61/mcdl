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

/* setup/servers/index.twig */
class __TwigTemplate_26a6dd6492e52cec91157e6931da3fc6 extends Template
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
        $this->parent = $this->loadTemplate("setup/base.twig", "setup/servers/index.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 3
        yield "
";
        // line 4
        if (((($context["mode"] ?? null) == "edit") && ($context["has_server"] ?? null))) {
            // line 5
            yield "  <h2>
    ";
yield _gettext("Edit server");
            // line 7
            yield "    ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["server_id"] ?? null), "html", null, true);
            yield "
    <small>(";
            // line 8
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["server_dsn"] ?? null), "html", null, true);
            yield ")</small>
  </h2>
";
        } elseif (((        // line 10
($context["mode"] ?? null) != "revert") ||  !($context["has_server"] ?? null))) {
            // line 11
            yield "  <h2>";
yield _gettext("Add a new server");
            yield "</h2>
";
        }
        // line 13
        yield "
";
        // line 14
        if ((((($context["mode"] ?? null) == "add") || (($context["mode"] ?? null) == "edit")) || (($context["mode"] ?? null) == "revert"))) {
            // line 15
            yield "  ";
            yield ($context["page"] ?? null);
            yield "
";
        } else {
            // line 17
            yield "  <p>";
yield _gettext("Something went wrong.");
            yield "</p>
";
        }
        // line 19
        yield "
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "setup/servers/index.twig";
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
        return array (  95 => 19,  89 => 17,  83 => 15,  81 => 14,  78 => 13,  72 => 11,  70 => 10,  65 => 8,  60 => 7,  56 => 5,  54 => 4,  51 => 3,  47 => 2,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "setup/servers/index.twig", "/home/mcdl.mul.edu.pk/public_html/pma/templates/setup/servers/index.twig");
    }
}
