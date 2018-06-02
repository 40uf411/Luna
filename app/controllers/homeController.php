<?php

use Luna\Core\Controller;
use Luna\App\Model\User;
use Luna\lib\migration\Migration;
use Dompdf\Dompdf;
use Luna\Helpers\Loader;

class homeController extends Controller
{
    public static function init()
    {
        parent::init();

        // the rest of the code

    }

    public static function home()
    {

    }

    public  function index($pram = null)
    {

/*
        Migration::update([
            "table" => "users",
            "condition" => "id = 25",
            "values" => ["name" => "Linda"]
        ]);
*/



        //$data = Migration::execute(SQL_PATH . "test.sql");
        //echo "<pre>";
        //print_r($data->fetchAll());
        //echo "</pre>";

        //var_dump(ob_get_contents());


     /*    Migration::build([
            'db_name' => "tye",
            'table_name' => "test",
            'attribute' => [
                'id' => [
                    'type' => "int",
                    'length' => '20',
                    'null' => false,
                    'auto_increment' => true,
                ],
            ],
            'engine' => 'InnoDB',
            'charset' => 'UTF8',
            'primary_key' => 'id',
        ]);


/*

              var_dump(Auth::form([
            'name' => "ali aouf",
            'password' => 'hi there',
            'co-pass' => 'hi there',
            'input' => 'http://hi.com'
        ],[
            'name' => ['min_len' => 5,
                        'max_len' => 10,
                        'verify' => 'empty'

                        ],
            'password' => [
                            'min_len' => 3,
                            ],
            'co-pass' => ['equal' => 'password'],
            'input' =>['min_len' => 5,'verify' => 'email']
        ]));

             //print_r($pram);

*//*

        self::model('User');


        if (isset($pram['id']) && $pram['id'] != null && intval($pram['id']) != 0 )
        {
            $user = new User($pram['id']);

            $name = $user->name;
        }

        elseif(isset($pram['id']) && $pram['id'] != null)

            $name = $pram['id'];

        else
            $name = 'here';


//        echo \Luna\lib\speller\Speller::render_string(4780052);


        self::view([
            'classname' => "welcome",

            //'html_page' => Loader::html("welcome",true,false),

            //'php_page' => Loader::php("welcome"),

            //'template' => "<html><head></head><body><h1>Wow! we did it!</h1></body></html>",

            //'head' => '<head></head>',
            //'body' => '<body></body>',

            //'on_error' => 'sorry',

            'title' => "hi $name",
            'name' => $name
        ]);*/

        //\Luna\ServicesProviders\Cash::save("user","ali");
        //\Luna\ServicesProviders\Cash::save("color","red");

        echo \Luna\ServicesProviders\Cash::load("user","ali");

    }

    public static function hi($data = null)
    {
        var_dump($data);


    }

}