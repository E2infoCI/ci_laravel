## phpDocumentor.phar
composer require --dev phpdocumentor/phpdocumentor
を実行した時に以下のエラーが発生するため、CI実行時にはpharファイルをmvして使う

```
> composer require --dev phpdocumentor/phpdocumentor
Using version ^2.9 for phpdocumentor/phpdocumentor
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)
Your requirements could not be resolved to an installable set of packages.

  Problem 1
    - Installation request for phpdocumentor/phpdocumentor ^2.9 -> satisfiable by phpdocumentor/phpdo
cumentor[v2.9.0].
    - Conclusion: remove nikic/php-parser v4.0.2
    - Conclusion: don't install nikic/php-parser v4.0.2
    - phpdocumentor/phpdocumentor v2.9.0 requires phpdocumentor/reflection ^3.0 -> satisfiable by php
documentor/reflection[3.0.0, 3.0.1, 3.0.x-dev].
    - phpdocumentor/reflection 3.0.0 requires nikic/php-parser ^1.0 -> satisfiable by nikic/php-parse
r[1.x-dev, v1.0.0, v1.0.0beta1, v1.0.0beta2, v1.0.1, v1.0.2, v1.1.0, v1.2.0, v1.2.1, v1.2.2, v1.3.0,
v1.4.0, v1.4.1].
    - phpdocumentor/reflection 3.0.1 requires nikic/php-parser ^1.0 -> satisfiable by nikic/php-parse
r[1.x-dev, v1.0.0, v1.0.0beta1, v1.0.0beta2, v1.0.1, v1.0.2, v1.1.0, v1.2.0, v1.2.1, v1.2.2, v1.3.0,
v1.4.0, v1.4.1].
    - phpdocumentor/reflection 3.0.x-dev requires nikic/php-parser ^1.0 -> satisfiable by nikic/php-p
arser[1.x-dev, v1.0.0, v1.0.0beta1, v1.0.0beta2, v1.0.1, v1.0.2, v1.1.0, v1.2.0, v1.2.1, v1.2.2, v1.3
.0, v1.4.0, v1.4.1].
    - Can only install one of: nikic/php-parser[1.x-dev, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.2.0, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.2.1, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.2.2, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.3.0, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.4.0, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.4.1, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.0.0, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.0.0beta1, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.0.0beta2, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.0.1, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.0.2, v4.0.2].
    - Can only install one of: nikic/php-parser[v1.1.0, v4.0.2].
    - Installation request for nikic/php-parser (locked at v4.0.2) -> satisfiable by nikic/php-parser
[v4.0.2].

Installation failed, reverting ./composer.json to its original content.
```
