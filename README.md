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

To generate new CRUD menu item first of all you need to create json file with list of fields params. Sample file located here:
`config/infyom/fields_config.json`
After that you need to run following command:
`php artisan uco:scaffold Sample --fieldsFile=config/infyom/sample.json --paginate=20`
This command will create following files:
- Model in App\Model folder
- Controller in App\Http\Controllers\Admin
- Requests in App\Http\Requests folder
- View files. It will create new folder in view folder and creates index, create, edit blade view files.
- Translation file for this model.
- New migration to create new table.
- Seeder.
- Also it will create route in web.php, add new menu item in menu.blade.php.

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

 If you want to change permissions in downloaded panel, you can log in as Administrator user and go to menu item User Management -> Roles, and then assign all permissions you want to a particular role, by editing it.
 
 In the generated code, we check the permissions in every method of Controller, see Gate and abort_unless() methods.
 
 On top of that, we add a check in Form Request classes.
 
 For more information, how Gates work in Laravel, see official Laravel documentation.

#### Global Search
in the generated panel, in top-left corner, you will see a search field, which, after you type in at least 3 characters, will look for all records in all CRUDs/Fields.

If you want to customize the behavior of Global Search, it's all in the generated file app/Http/Controllers/Admin/GlobalSearchController.php, here's the main method.

#### What should you do after adding new scaffold
##### Add date getter and setter functions
In newly created model add date functions. You need it if your model has date fields. For example, you have a date field with name sample_date:

    public function getSampleDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSampleDateAttribute($value)
    {
        $this->attributes['sample_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

Then check dates property in your model. With sample_date field it should be following:

    protected $dates = [
        'sample_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

##### Add constants for select fields
If you have a select field, please add constant with array for key->value pairs. For example for status field add this constant to Model:

    const STATUS_SELECT = [
        'active' => 'Active',
        'closed' => 'Closed',
    ];

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

