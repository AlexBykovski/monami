# Install project

Для начала скопируем проект из репозитория в папку, где он будет находиться:

```sh
git clone https://github.com/AlexBykovski/monami.git
```

*Все действия, которые будут описаны ниже, будут выполняться из корня проекта!*

Далее нужно поставить модуль zip для работы с zip файлами.
```sh
sudo apt-get install php7.2-zip
```
После устанавливаем все модули, которые описанны в composer.json командой:

```sh
sudo apt install composer
composer install
```

Дальше нужно установить bower. Он нужен для управления фреймворками, библиотеками, css и javascript файлами.
Установим с помощью следующей команды:

```sh
sudo apt install npm
sudo npm install -g bower
```

Предоставим root права для bower:

```sh
sudo bower install --allow-root
```

Если выдаст ошибку приведённую ниже:

```jsregexp
bower       invalid-meta for:/home/.../monami/bower.json
bower       invalid-meta The "name" is recommended to be lowercase, can contain digits, dots, dashes
```

то запишите название проекта маленькими буквами в файле bower.json и повторите команду. 
Создадим базу данных для проекта с именем, указанным в файле .env:

```sh
php bin/console doctrine:database:create
```

Далее необходимо запустить запросы для базы данных monami из файла name.sql:

```sh
mysql -u root -p monami < name.sql
```

Запускаем сервер:

```sh
php bin/console server:run
```

Для корректной работы стоит ещё установить asset. Он занимается сборкой css и js файлов для пакетов:

```sh
php bin/console assets:install
```

Нужно также выполнить следующую команду, чтобы спарсить scss файлы, собрать js и css файлы в один файл, сжать их и также собрать изображения:

```sh
npm install
gulp build
```

Дальше рекомендуется зайти в файлы, которые находятся по пути "src/command/" и убрать комментарии со всех строк, чтобы программа их могла видеть и выполнить.
Это нужно для того, чтобы видеть сообщения об возможных неполадках и дополнительные сведения об процессе выполнение следующих комманд.
После этого нужно проверить актуальность адресов в http://монами.бел/admin/app/importdetail/list для products, manager, clients.
Теперь можно запускать следующие команды, чтобы заполнить базу данных:

```sh
php bin/console app:import:clients
php bin/console app:import:managers
php bin/console app:import:products
```
После всех этих действия сайт должен работать исправно.