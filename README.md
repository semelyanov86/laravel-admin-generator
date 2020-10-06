## Sample Laravel Application

###### Installation Instruction
- Run `git clone git@gitlab.uco.co.il:se/sample.git` command to clone a repository
- Rin `cd sample` to change a directory
- Run `cp .env.example .env` file to copy example file to `.env`
- Then edit your `.env` file with DB credentials and other settings.
- Run `composer install` command
- Run `php artisan migrate --seed` command.

Notice: seed is important, because it will create the first admin user for you.
- Run `php artisan key:generate` command.
- If you have file/photo fields, run `php artisan storage:link` command.
And that's it, go to your domain and login:

Username:	admin@admin.com

Password:	password

##### Adding new model
This admin panel uses [Infyom Laravel Generator](https://labs.infyom.com/laravelgenerator) package with advanced features.
To generate new CRUD menu item first of all you need to create json file with list of fields params. Sample file located here:
`config/infyom/fields_config.json`
After that you need to run following command:
`php artisan uco:scaffold Sample --fieldsFile=config/infyom/sample.json`
This command will create following files:
- Model in App\Model folder
- Controller in App\Http\Controllers\Admin
- Requests in App\Http\Requests folder
- View files. It will create new folder in view folder and creates index, create, edit blade view files.
- Translation file for this model.
- New migration to create new table.
- Seeder.
- Also it will create route in web.php, add new menu item in menu.blade.php.

###### Package configuration
You can configure paths, namespaces, options, add_ons from published config file `config\infyom\laravel_generator.php`.

**Paths**

- _migration_ - path where migration file should be generated
- _model_ - path where the model file should be generated
- _datatables_ - path where DataTable files should be generated
- _repository_ - path where repository file should be generated
- _routes_ - path of routes file where routes should be added
- _api_routes_ - path of api_routes.php (this file will contain all API routes)
- _request_ - path where request file should be generated
- _api_request_ - path where API request file should be generated
- _controller_ - path where scaffold controller file should be generated
- _api_controller_ - path where API controller file should be generated
- _tests_ - path to tests directory
- _repository_test_ - path where repository test file should be generated
- _api_test_ - path where api test files should be generated
- _views_ - path where views file should be generated
- _schema_files_ - path where all schema files should be stored
- _templates_dir_ - path where all templates should be published
- _seeder_ - path where all seeders are stored
- _database_seeder_ - path to main DatabaseSeeder file
- _factory_ - path where all factories are stored
- _view_provider_ - path to ViewServiceProvider file

**Namespaces**
- _model_ - Namespace of Model
- _datatables_ - Namespace of DataTable files
- _repository_ - Namespace of Repository
- _controller_ - Namespace of scaffold Controller
- _api_controller_ - Namespace of API Controller
- _request_ - Namespace of scaffold Request
- _api_request_ - Namespace of API Request
- _tests_ - Namespace of Tests
- _repository_test_ - Namespace of Repository Tests
- _api_test_ - Namespace of API Tests

**Scaffold Templates**
- _templates_ - Templates types (coreui-templates, adminlte-templates)

You can build your own templates package as well and can give a full path here. for e.g. base_path('vendor/mitul/mitul-templates').

For any of InfyOmLabs templates, you can simple give a name only.

**Model extend class**
- _model_extend_class_ - Model Extends Class
API routes prefix & version
- _api_prefix_ - API prefix
- _api_version_ - API version

**Options**
- _softDelete_ - use soft delete with models
- _save_schema_file_ - By default save model schema file or not
- _localized_ - Create localization CRUD
- _tables_searchable_default_ - By default make all fields searchable in Datatable except primary key and timestamps
- _repository_pattern_ - Use repository pattern with controllers or not
- _excluded_fields_ - Array of fields that will be skipped while creating module

**Prefixes**
- _route_ - route prefix
- _path_ - path prefix
- _view_ - view prefix
- _public_ - public folder prefix

**Add-Ons**
- _swagger_ - generate swagger annotations for APIs
- _tests_ - generate test cases for APIs
- _datatables_ - generate CRUD index file with datatables
- _menu_ - If you are using generator's default layout then make it true to generate sidebar menu for module

**Timestamps**
- _enabled_ - enable timestamps
- _created_at_ - Created At timestamp field name
- _updated_at_ - Updated At timestamp field name
- _deleted_at_ - Deleted At timestamp field name

**From Table**
- _doctrine_mappings_ - Custom doctrine mappings to skip mapping errors while generating from table. e.g.['enum' => 'string', 'json' => 'text']

##### What Files are Inside the CRUD
###### Default MVC Files

When you create a CRUD, minimum of 14 new files are generated automatically - 10 new files, and 4 old ones re-generated.

There may be more changes, depending on CRUDs fields and modules involved.
For example, if you create CRUD called Transactions with a few simple columns like "amount" and "transaction_date", here's the minimum list of generated files:

**New Model**
- app/Transaction.php

**New Controller**
- app/Http/Controllers/Admin/TransactionsController.php

**New Form Requests**
- app/Http/Requests/MassDestroyTransactionRequest.php
- app/Http/Requests/StoreTransactionRequest.php
- app/Http/Requests/UpdateTransactionRequest.php

**New database migration**
- database/migrations/2019_12_02_000005_create_transactions_table.php

**New Blade views**
- resources/views/admin/transactions/create.blade.php
- resources/views/admin/transactions/edit.blade.php
- resources/views/admin/transactions/index.blade.php
- resources/views/admin/transactions/show.blade.php

**Changed main menu Blade view**
- resources/views/partials/menu.blade.php

**Changed main routes**
- routes/web.php

**Changed Seeds for Permissions**
- database/seeds/PermissionsTableSeeder.php

**Changed Translation Files for new CRUD**
- resources/lang/en/cruds.php

###### Database Migrations: Important Notice
After every new or changed CRUD, we regenerate all migration files to make sure they are in the right order, to avoid creating foreign keys on non-existing tables. Therefore, keep in mind that you need to double-check the migration files manually, so they still work after you merge changes.

######Additional Files - Depending on Parameters
A few more files may change with each new CRUD.

For example, if you add a belongsTo relationship column to other CRUD like Users, then additional these files are touched:

**Changed** app/User.php
**New** database/migrations/2019_12_02_relationships_transactions_table.php

 If you ticked the checkbox to generate API, another list of new files:
**New** app/Http/Controllers/Api/V1/Admin/TransactionsApiController.php
**New** app/Http/Resources/Admin/TransactionResource.php
**Changed** routes/api.php

And the list may be bigger, with additional field types or modules.
But, in short, these are the minimum files you need to download and copy-paste or merge into your existing project code.


##### Fields Input Guide

While passing htmlType in a field, we have different syntax for different fields. Here is the full guide for each supported field.

| Supported HTML Input Types & Formats   |      Valid Examples      |
|----------|:-------------:|
| text |  text |
| textarea |    textarea   |
| email | email |
| date | date |
| number | number |
| password | password |
| file (partially supported) | file |
| toggle switch | toggle-switch |
| **select** |
|  select,value1,value2,value3 | select,Daily,Weekly,Monthly                 
|  select,label1:value1,label2:value2,label3:value3 | select,Sunday:0,Monday:1,Tuesday:2
| **select from existing table** |
|  selectTable:tableName:column1,column2 | selectTable:users:name,id
|  Note:where column1 is Option Label and column2 is Option Value | selectTable:categories:title,id
| **checkbox** | **checkbox**             
|  checkbox,value | checkbox,yes
| |  checkbox,1
| **radio** |
|  radio,label1,label2 | radio,Male,Female
|  radio,label1:value1,label2:value2 | radio,Yes:1,No:0

##### Roles and Permissions

In default generator, we generate two user roles - Administrator and Simple User. They both have the same permissions for all CRUDs and Modules, except for User Management which is available only for administrator.

The whole Permissions system is stored in the database in these DB tables:
- permissions
- roles
- permission_role
- role_user

Every CRUD has five default permissions generated:
- *_access (whether user sees menu item in sidebar)
- *_create (whether user can access create form and add new record)
- *_edit (whether user can access edit form and update existing record)
- *_show (whether user can access "show" page of a record)
- *_delete (whether user can delete records)

These records are seeded with Seeder files

 If you want to change permissions in downloaded panel, you can log in as Administrator user and go to menu item **User Management -> Roles**, and then assign all permissions you want to a particular role, by editing it.
 
 In the generated code, we check the permissions in every method of **Controller**, see **Gate** and `abort_unless()` methods.
 ```
class BooksController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('book_access'), 403);

        $books = Book::all();

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('book_create'), 403);

        return view('admin.books.create');
    }

    public function store(StoreBookRequest $request)
    {
        abort_unless(\Gate::allows('book_create'), 403);

        $book = Book::create($request->all());

        return redirect()->route('admin.books.index');
    }

    public function edit(Book $book)
    {
        abort_unless(\Gate::allows('book_edit'), 403);

        return view('admin.books.edit', compact('book'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        abort_unless(\Gate::allows('book_edit'), 403);

        $book->update($request->all());

        return redirect()->route('admin.books.index');
    }

    public function show(Book $book)
    {
        abort_unless(\Gate::allows('book_show'), 403);

        return view('admin.books.show', compact('book'));
    }

    public function destroy(Book $book)
    {
        abort_unless(\Gate::allows('book_delete'), 403);

        $book->delete();

        return back();
    }
}
```
 
 On top of that, we add a check in [Form Request classes](https://laravel.com/docs/validation#creating-form-requests).
 
 ```
class StoreBookRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('book_create');
    }
}
```
 For more information, how Gates work in Laravel, see [official Laravel documentation](https://laravel.com/docs/authorization#writing-gates).

#### Global Search
in the generated panel, in top-left corner, you will see a search field, which, after you type in at least 3 characters, will look for all records in all CRUDs/Fields.

If you want to customize the behavior of Global Search, it's all in the generated file app/Http/Controllers/Admin/GlobalSearchController.php, here's the main method.

```
    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search === null || !isset($search['term'])) {
            abort(400);
        }

        $term           = $search['term'];
        $searchableData = [];

        foreach ($this->models as $model => $translation) {
            $modelClass = 'App\\' . $model;
            $query      = $modelClass::query();

            $fields = $modelClass::$searchable;

            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', '%' . $term . '%');
            }

            $results = $query->take(10)
                ->get();

            foreach ($results as $result) {
                $parsedData           = $result->only($fields);
                $parsedData['model']  = trans($translation);
                $parsedData['fields'] = $fields;
                $formattedFields      = [];

                foreach ($fields as $field) {
                    $formattedFields[$field] = Str::title(str_replace('_', ' ', $field));
                }

                $parsedData['fields_formated'] = $formattedFields;

                $parsedData['url'] = url('/admin/' . Str::plural(Str::snake($model, '-')) . '/' . $result->id . '/edit');

                $searchableData[] = $parsedData;
            }
        }

        return response()->json(['results' => $searchableData]);
    }
```
So if, for example, you want the default click to lead to SHOW method instead of default EDIT, you need to change this line:
```
$parsedData['url'] = url('/admin/' . Str::plural(Str::snake($model, '-')) . '/' . $result->id . '/edit');
```

### Dependent Dropdowns: Parent-Child
In our Admin Panel, we don't have such feature like parent-child dropdowns, for example Country-City relationship where change of Country value refreshes the values of Cities.

However, you can build it yourself quite easily.

In summary, you need to add jQuery code with AJAX call, similar to this:
```
@section('scripts')
    <script type="text/javascript">
    $("#country").change(function(){
        $.ajax({
            url: "{{ route('admin.cities.get_by_country') }}?country_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#city').html(data.html);
            }
        });
    });
    </script>
@endsection
```

Your blade file will be looking like this:

```
<form action="{{ route("admin.offices.store") }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="country">{{ trans('global.office.fields.country') }}</label>
        <select name="country_id" id="country" class="form-control">
            @foreach($countries as $id => $country)
                <option value="{{ $id }}">
                    {{ $country }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
        <label for="city">{{ trans('global.office.fields.city') }}</label>
        <select name="city_id" id="city" class="form-control">
            <option value="">{{ trans('global.pleaseSelect') }}</option>
        </select>
        @if($errors->has('city_id'))
            <p class="help-block">
                {{ $errors->first('city_id') }}
            </p>
        @endif
    </div>
```
Here’s the code for app/Http/Controllers/Admin/OfficesController.php:

```
public function create()
{
    abort_unless(\Gate::allows('office_create'), 401);
    $countries = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

    return view('admin.offices.create', compact('countries'));
}
```
app/Http/Controllers/Admin/CitiesController.php:

```
public function get_by_country(Request $request)
{
    abort_unless(\Gate::allows('city_access'), 401);

    if (!$request->country_id) {
        $html = '<option value="">'.trans('global.pleaseSelect').'</option>';
    } else {
        $html = '';
        $cities = City::where('country_id', $request->country_id)->get();
        foreach ($cities as $city) {
            $html .= '<option value="'.$city->id.'">'.$city->name.'</option>';
        }
    }

    return response()->json(['html' => $html]);
}
```

### Add Front User Without Admin Permissions
By default, Admin Panel generates two roles: Admin and Simple User, both being able to access the admin panel, with a bit different permissions. But what if you want to have Simple User as a front-end user, with only their own front pages you would build, and they wouldn't even see/access the admin panel?
####Step 1. Front Homepage: Remove default redirect to /login
In the first line of routes/web.php file, we have this redirect:
Route::redirect('/', '/login');
 
 It means there is no front homepage, just adminpanel. But you can change it easily, to this:
 ```
 Route::view('/', 'welcome');
```
The view `resources/views/welcome.blade.php` comes from default Laravel, and now you have your front-end homepage, with Login link on the top-right.

You can customize that Blade file however you want, to build a proper designed homepage.
#### Step 2. Better "Welcome" page
Instead of default "empty" welcome page, let's take a bit more advanced, but still default Laravel template - from Laravel UI package (it used to be core Laravel before Laravel 7), and we need this file: `layouts/app.stub` to become our `resources/views/layouts/user.blade.php`:

Then, we can change our `resources/views/welcome.blade.php` into this:
```
@extends('layouts.user')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body p-4">
                Welcome!
            </div>
        </div>
    </div>
</div>

@endsection
```

Notice: the structure is partly taken from default Laravel login page. And now, we have this homepage!

Why did we do it? Not only to make homepage more structured, but so that we can re-use the same layout for the home page of a logged-in user.

#### Step 3. Logged-in User's Homepage
Now, let's create an inside page that would be a homepage after logging in. We copy-paste the welcome.blade.php from above example, changing just the inner text. And this will become our `resources/views/user/home.blade.php`:
```
@extends('layouts.user')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body p-4">
                You are logged in!
            </div>
        </div>
    </div>
</div>

@endsection
```
To show that page, we will create a Controller, and separate it in a subfolder for all future front-user Controllers.

Here's `app/Http/Controllers/User/HomeController.php`:
```
namespace App\Http\Controllers\User;

class HomeController
{
    public function index()
    {
        return view('user.home');
    }
}
```
Notice: Don't forget the namespace! Cause we already have a HomeController with the same name in Admin namespace. 

Finally, we need to make a route for it. But let's create the whole Route Group for all the future front-user routes. We almost copy-paste the part of `Route::group()` for admins, just changing "admin" to "user" everywhere.

 **routes/web.php:**
```
// Old existing route group for administrators - we don't touch it
Route::group([
    'prefix' => 'admin', 
    'as' => 'admin.', 
    'namespace' => 'Admin', 
    'middleware' => ['auth']
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    
    // ... other admin routes
});

// Our NEW group - for front-end users
Route::group([
    'prefix' => 'user', 
    'as' => 'user.', 
    'namespace' => 'User', 
    'middleware' => ['auth']
], function () {
    Route::get('/', 'HomeController@index')->name('home');
});	
```
Notice: we give the same name to the routes: `->name('home')`. But since `Route::group()` parts have different prefix, the actual route names will be different: admin.home and user.home.

 Now, we have two separate designs and pages for admins and users.

#### Step 4. Check Role: Redirect after login
By default in Laravel, the page to redirect after login is defined in **app/Http/Controllers/Auth/LoginController.php**, in property $redirectTo:
```
class LoginController extends Controller
{
    // This is code in Laravel 7 
    // In earlier version it may be different
    protected $redirectTo = RouteServiceProvider::HOME;	

    // ... other code
```
We need to override it, and we can do it by just defining a method called `redirectPath()` in the same LoginController.

And we need to check the role - if the user is administrator, or not. Luckily, Admin Panel has generated a helper method inside of app/User.php model:
```
class User extends Authenticatable
{
    // ...

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }
```
So, here's what we would need to do in LoginController:
```
class LoginController extends Controller
{
    // ...

    public function redirectPath()
    {
        $user = auth()->user()->is_admin ? 'admin' : 'user';
        return route($user . '.home');
    }
}	
```
If you don't understand how `getIsAdminAttribute()` became `auth()->user()->is_admin`, you can read more about Eloquent Accessors here in the official Laravel docs.

So, now, every user will be redirected to their own page/section after login. So simple users wouldn't even see the administrator page design.

But wait, they can still access it if they enter the URL in the browser! Let's work on changing the permissions now - it will be our final step.

#### Step 5. Remove Admin Permissions from User
First, let's remove a piece of code in routes/web.php which automatically redirects to admin.home - we don't need this anymore:
```
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
```
Next, we need to remove the permissions from Users. They are assigned in a file `database/seeds/PermissionRoleTableSeeder.php`:
```
public function run()
{
    // These are administrator permissions, they should stay
    $admin_permissions = Permission::all();
    Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

    // These are users permissions to admin area
    // That block should be removed
    $user_permissions = $admin_permissions->filter(function ($permission) {
        return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
    });
    Role::findOrFail(2)->permissions()->sync($user_permissions);
}
```
 After you remove everything related to $user_permissions you need to re-seed the database from scratch, by running this command:

`php artisan migrate:fresh --seed`

IMPORTANT! This command above will delete ALL YOUR DATABASE and migrate/seed from zero. So if you already have important data, you better remove permissions manually - by deleting entries in permission_role DB table with `role_id = 2`.

Finally, let's create a Middleware class that we would assign to the `Route::group` of administrators - it will allow those routes to be accessed only by administrators.

`php artisan make:middleware IsAdminMiddleware`	

And then we put this code in **app/Http/Middleware/IsAdminMiddleware.php**:
```
class IsAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('user.home');
        }
        
        return $next($request);
    }
}
```
We just redirect to user's area, if they are not an administrator.

Now, we need to assign that Middleware class to the routes. To do that, we need to give it a name. Let's call it "admin", and we register it in **app/Http/Kernel.php**:
```
class Kernel extends HttpKernel
{
    // ...

    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,

        // ... other routes

        'admin' => \App\Http\Middleware\IsAdminMiddleware::class,
    ];
}
```
And now we can assign it to the Route group in routes/web.php:
```
Route::group([
    'prefix' => 'admin', 
    'as' => 'admin.', 
    'namespace' => 'Admin', 
    'middleware' => ['auth', 'admin'] // <= This is our new middleware
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // ... other admin routes
});	
```
And that's it, if some simple user would try to load /admin/[something] in the URL, they would automatically be redirected to /user homepage.


### How to upgrade to CoreUI Pro?
Within our license boundaries, we are using Free version of all themes, including CoreUI. Unfortunately, we don't have any tutorial for the upgrade to Pro. [Please check their documentation.](https://coreui.io/pro/)

## AJAX Datatables
This panel uses Datatables.net for listing the data. By default, it accepts all entries from the database, and then JavaScript takes care of pagination, search, filtering, ordering etc.
 
 And it is a problem for bigger amount of data, thousands of entries could slow down the page load significantly.
  
  This AJAX Datatables module uses the data loading page by page, at the time that it's needed, via AJAX.
   
   It's also called Server-side processing - Datatables script accept the URL of separate API script which actually loads the data.
   
   ```
   $('#example').DataTable( {
       "processing": true,
       "serverSide": true,
       "ajax": "../server_side/scripts/server_processing.php"
   } );
```

### How does the result look in Admin Panel code?
Without this module, generated code looks something like this.

**Controller:**

```
public function index()
{
    $courses = Course::all();
    return view('admin.courses.index', compact('courses'));
}
```
**View:**
```
<table class="table datatable">
<thead>
	<tr>
		<th>...</th>
		<th>...</th>
		<th>...</th>
		<th>...</th>
	</tr>
</thead>

<tbody>
@if (count($courses) > 0)
    @foreach ($courses as $course)
	<tr>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
	</tr>
	@endforeach
@else
	<tr>
		<td colspan="4">No courses.</td>
	</tr>
@endif
</tbody>
</table>
```

**JavaScript:**

```
$('.datatable').each(function () {
    $(this).dataTable(window.dtDefaultOptions);
});
```

Now, imagine if there were 5000 courses in the table. It would put a heavy load on the browser with JavaScript trying to paginate and filter the data inside of the datatables.
 
 So how does it look with AJAX Datatables?
 
 **composer.json:**
 
 ```
...
"yajra/laravel-datatables-oracle": "^9.0",
...
```

**Controller:**

```
public function index()
{
    if (request()->ajax()) {
        $query = Course::query();
        $query->with("teachers");
        $template = 'actionsTemplate';
        $table = Datatables::of($query);

        $table->addColumn('actions', ' ');
        $table->editColumn('actions', function ($row) use ($template) {
            $gateKey  = 'course_';
            $routeKey = 'admin.courses';
            return view($template, compact('row', 'gateKey', 'routeKey'));
        });

        // ... More columns described

        $table->editColumn('published', function ($row) {
            return \Form::checkbox("published", 1, $row->published == 1, ["disabled"]);
        });

        return $table->make(true);
    }

    return view('admin.courses.index');
}
```

**View:**
```
<table class="table ajaxTable">
<thead>
	<tr>
		<th>...</th>
		<th>...</th>
		<th>...</th>
		<th>...</th>
	</tr>
</thead>
</table>
```
**JavaScript:**
```
$('.ajaxTable').each(function () {
    window.dtDefaultOptions.processing = true;
    window.dtDefaultOptions.serverSide = true;
    $(this).DataTable(window.dtDefaultOptions);
});
```
 As you can see, a lot more logic now goes into Controller - it processes AJAX call to the API and parses the column.
 
 In the Blade file the table itself is empty - there are no tr's or td's, it's all loaded via AJAX.
 
 In other words, whole page is loaded really fast with empty table, and then after a while the data is being loaded.
 
 We use [Laravel Datatables](https://github.com/yajra/laravel-datatables) package for this function.

#### What should you do after adding new scaffold
##### Add date getter and setter functions
In newly created model add date functions. You need it if your model has date fields. For example, you have a date field with name sample_date:
```
    public function getSampleDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSampleDateAttribute($value)
    {
        $this->attributes['sample_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
```
Then check dates property in your model. With sample_date field it should be following:
```
    protected $dates = [
        'sample_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
```
##### Add constants for select fields
If you have a select field, please add constant with array for key->value pairs. For example for status field add this constant to Model:
```
    const STATUS_SELECT = [
        'active' => 'Active',
        'closed' => 'Closed',
    ];
```
##### Check relation field name in controller

In generated controller check relation field name.
For example, rename 
`$table->editColumn('country_id'`
to this 
`$table->editColumn('country_name'`

##### Add Mass Destroy Request

If you need a mass destroy functionality you need to add MassDestroyRequest:

```
namespace App\Http\Requests;
use App\Sample;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySampleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('sample_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:samples,id',
        ];
    }
}
```
