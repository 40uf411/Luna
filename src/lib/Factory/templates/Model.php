<?php

use Luna\lib\Factory\Template;

class Model extends Template{

    public function make($data=null){
        $name = $data['name'];
        $subFolder = (isset($data['folder']) and is($data['folder'],'str')) ? $data['folder'] : null ;
        $path = MODELS_PATH . $subFolder . $name . 'Model.php';
        $content = "
<?php

use Luna\Core\Model;

class " . $name . "Model extends Model
{
            
    private \$attributes = [];
    private \$hidden = [];
            
    public function __constructor(){}
}";
        return [
            'path' => $path ,
            'content' => $content
        ];
    }
    
}


?>