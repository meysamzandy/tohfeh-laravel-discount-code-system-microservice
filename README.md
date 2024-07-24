## Tohfeh Installation Tips
tohfeh is a discount code microservice for filmgardi.com

### Requirements installation

Install web server (nginx) <br>
Install php > 7.4  last stable version<br>
Install mariadb last stable version<br>
Install the composer v2<br>
Enable the event on mariadb<br>

Install supervisor last version<br>
configure supervisor like this:<br>
`[program:tohfeh]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tohfeh/artisan queue:work database
autostart=true
autorestart=true
user=django
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/tohfeh/storage/logs/worker.log`

### Requirements Command
run `git clone https://github.com/meysamzandy/tohfeh-laravel-discount-code-system-microservice.git` <br>
run `cd /var/www/tohfeh` <br>
run `composer install` <br>
Duplicate .env.example to .env <br>
configure mysql connection on .env <br>
run `php artisan key:generate` <br>
run `php artisan migrate` <br>

