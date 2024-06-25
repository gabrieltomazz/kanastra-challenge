## Getting started

### build and run app
./vendor/bin/sail up

### run migration
./vendor/bin/sail artisan migrate 

## run jobs
./vendor/bin/sail artisan queue:work 

## create folder to store pdf 
cd public 
mkdir bankslip
chmod 777 public/bankslip

## main 
open http://localhost:8025/ to see the mail box

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT)