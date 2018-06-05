# [Luna PHP MVC framework](http://github.com/.../Luna)
    Built by Aouf Ali 2018

## Features
* **Architecture:**
    * **M**odel **V**iew **C**ontroller workflow.
    * **G**reat routing system, easy to adapt, easier to use.
    * **T**we routing system, simple and advanced.
    * **S**eparating the source code from the developer areas.
    * **L**arge number of ``Providers``.
    * **C**ool form validator.
    * **S**trong built in basic classes.
    * **A**wesome connection between the classes of the app.
    * **B**eautiful code.  

* **Database:**
    * Default database connection with ``mysql`` objects.
    * using the ``PDO`` methods in the database connection.
    * providing the database connection to any class with an object to make it easier to handle.
    * a lot of useful built in functions easy to adapt.  

* **Others**
    * **Autoloading dependencies** through ``composer``

## Set Up
* edit the files in the ``config`` directory as it fits you.
* add you're own controllers, models, view in the ``app`` directory, don't forget to set the controllers up by adding theme in ``config/routes.config.php``

>**WARNING** be careful, it's rather that you don't edit the ``php.config.php`` if you're not sure about it.

## Global constants 
   - **Directories:**
   > to edit this constants go to `config\dir.config.php`.

## Routing

   **I- simple router**
   * to use the simple routing set `routing` to `simple` in ``config/routes.config.php``
   * if no url is set it will redirect to the home page
   * Unmatched urls will be redirected to not found page (404)
   * Register routes in ``config/routes.config.php``
```php
    RouteProvider::add('url','controller', 'method');
```
   >**notice:** if no method is set the system will set it automatically in the `GET` and `POST`

   * simple call
   ```php
       RouteProvider::add('test');
   ```

  **I-II- how does it work**
  
   with the start of the framework the system check if no url is set if so, 
   it will launch the `index` method in the `home` controller.
   else if the url it will look for the corespondent controller then the corespondent method.
   if no controller has been found it will look for a method with the same name in the home controller.
   if nothing has been found it will launch the `notfound` view.
   
   >**notice:** this type of routing does not support callbacks or `url parameters`. 


   **II- advanced router**
   * similar to laravel's routing system.
   * support `function callback`, `url parameters` ...
   * Register routes in ``config/routes.config.php``.
       
   **II-II- how to use:**
   
* controller/method
```php
    Router->get('example', 'method@controller');
```   
   
* Callback 
```php
    Router->get('example', function() {
        View::launch([
            'template' => '<html> ... </html>'
            ]);
    });
```
* Parameters 
```php
    Router->get('example/$id', function($data) {
        View::launch([
            'classname' => "welcome",
            'id' => $data['id']
            ]);
    });
```

## Controllers
* Default controller - ``app/controllers/homeController.php``
* Creating new controllers
    * Please follow the naming convention
        > Ex 1) Controller for user model - UserController.php
    * rewrite the `index` method, its **obligated** 
    
* **built-in function:**
    * `protected function model($model, $pram = null)` => to include a model it returns a new instance of the model.
    * `protected function view($data = [])` => load a view.
    * `public function index($pram = null)` => the default controller method.
...

#more details will be added soon!
