# ExamSymfNf

## Start the project

* Clone the project:
```
git clone https://github.com/Myrendir/ExamSymfNF.git
```

* Install the component
```
composer install
```

* Generate the jwt with lexik
```
php bin/console lexik:jwt:generate-keypair
```

* Start the server
```
php -S localhost:8000 -t public
```
