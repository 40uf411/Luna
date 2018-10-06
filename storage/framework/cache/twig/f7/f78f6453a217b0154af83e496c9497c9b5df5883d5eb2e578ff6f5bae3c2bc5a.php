<?php

/* twig\index.twig */
class __TwigTemplate_3b3c744ffa054b1bcdbeaedecd4b58ab58eb49142a0a00257a04adc2ba13e314 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<header>
    <title>test</title>
</header>
<body>
    <h1 style='font-family: \"Segoe UI\"'>Form</h1>
    <form action=\"/luna/upload/\" enctype=\"multipart/form-data\" method=\"post\">
        <input type=\"file\" name=\"file\">
        <input type=\"submit\" value=\"send\">
    </form>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "twig\\index.twig";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<html>
<header>
    <title>test</title>
</header>
<body>
    <h1 style='font-family: \"Segoe UI\"'>Form</h1>
    <form action=\"/luna/upload/\" enctype=\"multipart/form-data\" method=\"post\">
        <input type=\"file\" name=\"file\">
        <input type=\"submit\" value=\"send\">
    </form>
</body>
</html>", "twig\\index.twig", "C:\\xampp\\htdocs\\luna\\resources\\views\\twig\\index.twig");
    }
}
