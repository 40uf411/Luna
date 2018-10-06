<?php

use Luna\lib\Factory\Template;

class Controller extends Template
{
    public function make($data = null)
    {
        $name = $data['name'];
        $subFolder = (isset($data['folder']) and is($data['folder'],'str')) ? $data['folder'] : null;
        $path = CONTROLLERS_PATH . $subFolder . $name . 'Controller.php';
        $conent = "
<?php

use Luna\Core\Controller;

class " . $name ."Controller extends Controller
{
    public function index(\$data,Request \$request,Response \$response)
    {
        return 'done!';
    }
}";
        return [
            "path" => $path,
            "content" => $conent
        ];
    }
}