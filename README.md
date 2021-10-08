# <p align="center"> <img  alt="TidyPHP" width="310" height="100"  src="public/assets/images/TidyPHP.png" draggable="false" /> </p>

**_TidyPHP_** is a micro MVC PHP Framework made to understand how PHP Frameworks work behind the scense and build fast and tidy php web applications.

This framework made for learning prupose and may have some issues and still under development. 

## Getting Started 

Run this command from any directory to install new TidyPHP Application. Required PHP 7.4.7 or newer.

```bash
composer create-project tidyphp/tidyphp [app-name]
```
Replace ``[app-name]`` with your need directory name for your new application.
and to run the application for development you must start from ``public`` path. or simply do this.
```bash
cd [app-name] 
composer serve
```

## Documentation

- [Configrations](#Configrations)
- [Routing](#Routing)
- [Controllers](#Controllers)
- [Views](#Views)
- [Validation](#Validation)
- [Database](#Database)
  - [Query Builder](#Query-Builder)
  - [Eloquent Model](#Eloquent-Model)

## Configrations
using the power of ``.env`` file you can change your database informations.
you can change app name or url. 

``Note`` url will be generated automaticlly without attach it to ``.env`` file. 

```env 
APP_NAME=TidyPHP
URL = http://localhost:8080
DB_DRIVER=mysql
DB_DATABASE=tidy
DB_USERNAME=root
DB_PASSWORD=
DB_HOST=localhost
```
## Routing 

All the routes are defined in ``routes/web.php`` file. 
you can use two methods ``get`` or ``post`` . if you want to go ``yoururl/profile`` you can do one ot these ways.

```php
<?php

use App\Controllers\HomeController;
use Tidy\Http\Route;

Route::get('/profile' , function() {
     return view('home');
});

Route::get('/profile', [HomeController::class , 'index']);

```
you can also accept paramters in your route and nameing the route to get the route by ``route()`` function.

```php 
<?php

use App\Controllers\HomeController;
use Tidy\Http\Route;

Route::get('/user/{id}', [HomeController::class , 'user'])->name('user.profile');

Route::post('/user/store', [HomeController::class , 'store'])->name('store');
```

in your view you can route to any exists route. if the route not exists 404 page will be returned.
```html

<form action="<?= route('store') ?>" method="post">
  
<input type="text" name="user_name">
<input type="submit" name="sumbit"value="">

</form>
```
you can path the paramter you need in your route. in the case of ``user.profile`` route accept ``{id}`` so you need to path number to the ``route()`` method. if there exist route have two params like ``user/{id}/{secid}`` you must path array have 2 numbers to ``route()`` method.
also you can use ``redirect()`` method to redirect to the route you need to go.
```php 
// App/Controllers/HomeController.php

public function index() {
    return redirect(route('user.profile' , $id));
    // route('some.name' , [$id , $secid]);
}

```

## Controllers

as we mention in Routing you can define route

```php 
Route::get('/user/{id}', [HomeController::class , 'user'])->name('user.profile');
```
you can find the Controllers in ``App/Controllers`` and the previews route will lead you to the method ``user`` and the route paramters will be passed to the method 

```php 
<?php
namespace App\Controllers;

use App\Models\User;

class HomeController {

    public function user ($id) {
   
        $data['user'] = User::find($id);
            return view('user.index' , $data);
    }
} 
```

## Views
The main file that Views rendered from is ``views/layouts/app.php``. just put in it the basic structure of your HTML , css and js links.

and add ``{{content}}`` string inside the body.
```html 
<body>
    {{content}}
</body>
```
The other files will extend from ``views/layouts/app.php`` file and replace the ``{{content}}`` string with the file content.  


if you have assets like css,images,js make sure to put them in ``public/assets`` and use ``asset()`` method 
```php 
<link type="text/css" rel="stylesheet" href="<?= asset('css/style.css') ?>" />
```


In Controller you can render view with the data you need in the view

```php 
<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Post;

class HomeController {

    public function user ($id) {
   
        $data['user'] = User::find($id);
        $data['posts'] = Post::where(['user_id' , '=' , $id])->get();
            return view('user.index' , $data);
    }
} 
```
when i path ``user.index`` to ``view()`` function that mean there is directory ``user/index.php`` locatied in views path. 

And in ``views/user/index.php`` i can use the data like this : 

```php 
  <p> <?= $user->name ?> Posts :</p>
<?php foreach($posts as $post) : ?>
    <p> <?= $post->status ?>  </p>
 <?php endforeach; ?>   

```

you may need rendering view not extend ``views/layouts/app.php`` in this case you can use ``makeView()`` and path to it dirictory name and needed data if exists 

if i have ``main/index.php`` path and i need to path to it all users and apply make the file does not extend from the defualt path i can use this : 

```php 
<?php
namespace App\Controllers;

use App\Models\User;
use Tidy\View\View;

class HomeController {

    public function index () {
  
        $data['users'] = User::all();
        return View::makeView('main.index'  , $data);
    }

```


## Validation 

you may have a form and you want to validate it after submit, so we simply make the action go to our route and then make a validate to the request by the ``validate()`` method.

if the validate have any errors it will redirect with the errors to the same page.

so let's define our route : 

```php
Route::post('/store/{id}', [HomeController::class , 'store'])->name('store');
```
you can path an id or not , if you path an id you may need to accept it at the ``store()`` function in controller and do your validatation!

so if you have this simple form routes to our route name ``store``

```php 
<form action="<?= route('store' , 1 ) ?>" method="post">
 
    <input type="text" name="name" id="" value="<?= old('name') ?>">
    <br>
    <input type="email" name="email" id="" value="<?= old('email') ?>">
    <br>

    <input type="file" name="image" id="" value="<?= old('image') ?>">
    <input type="submit" name="sumbit"value="Submit">
</form>

```

in HomeController , ``store()`` method we do our validation and use ``request()`` method to get the requested form data by the names of the form. 

```php 
<?php
namespace App\Controllers;

use App\Models\User;
use Tidy\Http\Request;
use Tidy\View\View;

class HomeController {
    public function store (Request $request , $id ) {
       
        $data = $request->validate(
                ['name' => 'required|string|max:50',
                'email'=> 'required|email|between:6,50' , 
                'image' => 'image|mimes:jpg,png' 
                ]
        );
            User::create( [
            'name' => request('name') , 
            'email' => request('email') , 
            'image' => request('image') , 
            ] 
            );
        
        return redirect(route('home' , $id));
    }

```

After this you may need to show the errors after redirect back if the errors exists.
you can simply render all errors or render specific error with ``error()`` method.
```php 
// you may need to load all errors
<?php if (hasErrors()): ?>
    <?php foreach(errors() as $error): ?>
            <p class="text-danger">
            <?= $error ?>
            </p>     
            <br>
    <?php endforeach; ?>    
<?php endif; ?>
// or load specific error by error() method
  <?php if (hasErrors()): ?>   
        <p class="has-text-danger">
                <?= error('name'); ?>
        </p>
 <?php endif; ?>  
```
#### The rules of validation
- ``string`` check if the value is string.
- ``number`` check if the value is numeric.
- ``alnum`` check if the value is alpha numbers.
- ``required`` return error if the value empty string.
- ``image`` check if the value is image.
- ``mimes`` check the file extention example: ``mimes:jpeg,png``.
- ``email`` check if the value is email.
- ``between`` check if the value length between two numbers. example: ``between:5,50`` .
- ``email`` check if the value is email.
- ``max`` check if the value length not exceed than the number example : ``max:60`` .
- ``min`` check if the value length not less than the number example : ``min:6`` .

## Database 

### Query Builder 

to runing database queries must use the powerfull ``DB`` class. you can execute on it many options like where , orWhere, orderBy, groupBy, whereIn , and many other options to make it eaiser than runing simple queries.

in example we are in controller and we need to get all of users table , we can use the `DB` like this.
```php 
use Tidy\Database\DB;
// App/Controllers/HomeCotroller.php
class HomeController {
  public function index () {
  
        $data = DB::table('users')->get();
        return view('home'  , $data);
    }
```
#### Select
if you want to select specific colms from your table. you can use ``select()`` method.
```php 
     DB::table('users')->select('id')->get();
    // OR 
     DB::table('users')->select(['name' , 'email'])->get();
```
#### Where Clauses
you can control your query more than this by applying methods like 
- ``where()`` , ``orWhere()`` , ``andWhere()`` 
- ``whereIn()``, ``orWhereIn()`` , ``andWhereIn()`` 
- ``notWhereIn()`` , ``andWhereNotIn()`` , ``orWhereNotIn()`` 

you can not apply and , or methods without applying where methods first to  make the query works fine. 

```php 
DB::table('users')->select('id')->where(['country' , '=' , 'egypt'])
->orWhereNotIn('id' , ['1' , '2' , '3'])->get();

DB::table('users')->whereIn('id' , ['1' , '2' , '3'])->andWhere(['id' , '>' , '6'])->get();

DB::table('users')->where(['id' , '>' , '6'])->andWhere(['country' , '=' , 'egypt'])->get();
```
#### Ordering
``orderBy()`` method accept the defult order the given argument asc order, you can order by desc order by passing second paramter ``'desc'`` to the function.
also there is ``latest()`` method that return the latest rows inserted to your table, it will return by defualt the latest rows by the ``id`` you can override it to ``date`` or anything.

```php
DB::table('users')->where(['id' , '>' , '6'])->orderBy('id')->get();
DB::table('users')->where(['id' , '>' , '6'])->orderBy('id' , 'desc')->get(); // DESC
DB::table('users')->where(['id' , '>' , '6'])->orWhereIn('country' , ['egypt' , 'uk' , 'us'])
->latest('date')->get(); // latest() return ordered by id by defualt
```
#### Grouping

Grouping by ``groupBy()`` and ``having()`` methods. for aggregate functions like this: 
```php
DB::table('users')->groupBy('id')->having('id', '>', 6)->get();
```
let's introduce our ``first()`` and ``take()`` methods by simple example. you can use ``first()`` instead of ``get()`` method. let's do a **_simple query to get the most frequency name in your table_**

```php
DB::table('users')->select(['COUNT(name) as name_count' , 'name'])
->groupBy('name')->orderBy('name_count' , 'desc')->first();

// you can also take the first 3 by take() function
DB::table('users')->select(['COUNT(name) as name_count' , 'name'])
->groupBy('name')->latest('name_count')->take(3)->get();

```
there is also another methods we can use instead of ``get()`` . ``exists()`` methods return true if there is availabe rows in your query. also you have ``count()`` method that return the number of rows returned from query.

```php 
DB::table('users')->where(['id' , '=' , 3])->exists();
// how many users located in egypt,germany,canda.
DB::table('users')->whereIn('country' , ['egypt' , 'germany' , 'canda'])->count();
```

###  Eloquent Model

models are located in ``App/Models`` path and it must extend ``Model`` class to interacte with database table. the model let you ``read``, ``insert``, ``update`` , ``delete`` rows from the table.

#### Table Name 

The default table name will be the plural of the class name , so if class ``User`` the table name will be ``users``.
but you can simply override the default by ``$table`` property . 

```php 
<?php 
namespace App\Models;

use Tidy\Database\Model\Model;

class User extends Model {
     protected static $table = 'my_users';
}

```

#### Retrieving Data 

Once you created your model, you can start retrieving data from your database. first you can use ``all()`` method to retrieve all the rows from your table 
```php
 foreach(User::all() as $user ) {
      echo $user->name;
 }
 ```  

 also every model has a **_Query builder_** too , you can apply to it all ``where()`` ``orderBy()`` ``groupBy()`` methods and use the power of ``get()`` ``first()`` ``count()`` ``exists()`` methods on it. also you can select specific colms to retrieve but in another way this time .

``where()`` ``orderBy()`` ``groupBy()`` methods accept another paramter ``$columns`` i can path to it an array or just simple string to select specific colms.

methods who accept two paramters the third paramter will be the colums paramter , and if accept one paramter the second will be the columns paramter . it will be default ``*`` . 
 ```php  
 // examples:
 User::where(['id' , '=' , 5] , 'id')->orWhereIn('id' , [1,7,8])->get();
 User::whereIn( 'id' , [1,7,8], ['id', 'name'])->latest()->get();
 // most frequency of names in your table
 User::groupBy('name' , ['COUNT(name) as name_count' , 'name'])->latest('name_count')->first();
// count the people in specific countries.
 User::whereIn('country' , ['egypt' , 'us'], ['name' , 'country'])->count();
 ```

 you can use ``find()``method to find row by the id.
 ```php 
  $user = User::find(2);
 ```
#### Affect tables
 also you can apply ``create()`` ``update()`` ``delete()`` methods also on the model.
 ```php
 // insert
User::create(
    [
        'name' => 'aminyasser' , 
        'email' => 'alaminyasser0@gmail.com',
        'password' => md5('122131') ,
    ]
);
// update
User::update( 5 , 
        [
            'name' => 'aminyasser' , 
            'email' => 'alaminyasser0@gmail.com',
            'password' => md5('122131') ,
        ]
);
// delete
User::delete(5);

 ```


