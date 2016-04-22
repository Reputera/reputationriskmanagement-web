### Setup for Reputera
__*If you are using the "sync" option for your local queue, then there is no need to follow this setup.*__

If running on a local VM, like vagrant, you will need to add the following so beanstalkd will work as a daemon on your local machine. You may also use ```php artisan queue:listen``` to sit and listen like a daemon does (but the daemon does not need multiple terminal windows open like this will to listen and do other commands). You can also use ```php artisan queue:work``` to process the first thing in the queue. If several things are in the queue, you will need to run this several times. If you are using a diffrent queuing mechanism, like redis, this will still work, but you will need to edit the config below to use redis instead.

1. Create a file called ```laravel-worker.conf``` under the ```/etc/supervisor/conf.d/``` directory.

    One way this can be done is like such: ```sudo vim /etc/supervisor/conf.d/laravel-worker.conf``` - This will create an empty file.

2. Next we need to populate the file with supervisor data to allow it to constantly run Please make note of the ```command```, ```user``` and ```stdout_logfile```. Additionally, I set my number of prcoesses to a low number because I set this up in a VM. A Larger number may be a better choice for production.

        [program:laravel-worker]
        process_name=%(program_name)s_%(process_num)02d
        command=php {pathToYourReputeraProject}/artisan queue:work beanstalkd --sleep=3     --tries=3 --daemon
        autostart=true
        autorestart=true
        user=vagrant
        numprocs=1
        redirect_stderr=true
        stdout_logfile={pathToYourReputeraProject}/storage/logs/worker.log


3. Once the file is saved, we can make sure supervisor sees the new file we created (or updated) with ```sudo supervisorctl reread```.

4. We will then need to run ```sudo supervisorctl update``` to update supervisor.

5. Finally we will need to run ```sudo supervisorctl start laravel-worker:*``` to have the worker "put into action", so to speak.

If you need to adjust the file, do so and do steps 3 - 5 again.

To stop the worker, ```sudo supervisorctl stop laravel-worker:*```. After a few moments you will get output like this to tell you it's complete: ```laravel-worker_00: stopped```