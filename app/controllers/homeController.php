<?php
use Luna\Providers\SessionProvider;
use Luna\Providers\CookiesProvider;
use Luna\Providers\HttpProvider as http;
use Luna\Providers\FilesProvider;
use Luna\Core\Controller;
use Luna\App\Model\User;
use Luna\Helpers\Loader;

class homeController extends Controller
{

    public  function index($pram = null)
    {

        $this->model('User');

        if (isset($pram[0]) && $pram[0] != null && intval($pram[0]) != 0 )
        {
            $user = new User($pram[0]);

            $name = $user->name;
        }

        elseif(isset($pram[0]) && $pram[0] != null)
            $name = $pram[0];

        else
            $name = 'here';

        self::view('welcome', $name);
    }

    public static function file()
    {
        echo "<br>";
        var_dump($_FILES);
        echo "<br>";

        $file = FilesProvider::upload('file',false,false);

        $name = $file['file_name'];
        $type = $file['file_type'];
        http::redirect("home/download/$name/$type");
    }

    public static function download($url)
    {
        //var_dump($url);
        FilesProvider::download(UPLOAD_PATH . $url[0],null,$url[1] . '/' . $url[2]);
    }
}