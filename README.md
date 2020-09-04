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
