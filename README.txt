Стуктура библиотеки:

composer.json - необходимые пакеты
composer.phar - composer
Makefile  - запуск тестов через make
README.txt - этот фаил
src/ - файлы библиотеки
src/Exceptions/ - различные исключения
src/Filter.php/ - класс который используют внешние пользователи библиотеки
src/Grammar.syn - описание грамматики в формате утилиты syntax-cli (https://www.npmjs.com/package/syntax-cli#php-plugin), содержит описание грамматики для фильтра.
                  этот фаил нужно отредактировать для добавления in в язык.
src/Parser.php - автогенерируемый фаил. Для генерации используется утилита syntax-cli.
                  Прим. запуска $> syntax-cli -g Grammar.syn -m SLR1 -o Parser.php
src/Tokens/ - содержит код с классами для всех узлов AST дерева.
              Все классы наследуют интерфейс ASTNode (см src/Tokens/ASTNode).
              Сюда нужно будет добавить класс для IN.
tests/ - файлы тестов (PHPUnit)
