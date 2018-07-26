<?php
$opts = getopt('f:');
if (!isset($opts['f'])) {
    usage();
}
$filePath = $opts['f'];

if (!file_exists($filePath)) {
    error("$filePath does not exist.");
}
if (!is_file($filePath)) {
    error("$filePath is not a file.");
}
if (!is_readable($filePath)) {
    error("$filePath is not readable.");
}

$openFile = fopen($filePath, 'r');
$tables = [];
$currentTableName = null;
while ($line = fgets($openFile)) {
    $line = trim($line);
    if (strpos($line, 'CREATE TABLE') === 0) {
        $tableName = null;
        list($x, $tableName) = explode('`', $line, 3);
        if (empty($tableName)) {
            list($x, $tableName) = explode('"', $line, 3);
        }

        $currentTableName = $tableName;
        $tables[$currentTableName] = [];
        $tables[$currentTableName]['name'] = $tableName;
    } elseif (strpos($line, 'PRIMARY KEY') > 0) {
        list($x, $field, $remnants) = explode('`', $line, 3);
        if ($field == '') {
            list($x, $field, $remnants) = explode('"', $line, 3);
        }
        $tables[$currentTableName]['pk'] = $field;
        $tables[$currentTableName]['fields'][] = [
            'name' => $field,
        ];

    } else {
        if (strpos($line, '`') === 0 || strpos($line, '"') === 0) {
            list($x, $field, $remnants) = explode('`', $line, 3);
            if ($field == '') {
                list($x, $field, $remnants) = explode('"', $line, 3);
            }
            $comment = null;
            if (strpos($line, 'COMMENT') > 1) {
                $tempComment = null;
                list($x, $tempComment) = explode('COMMENT', $line, 2);
                $comment = null;
                list($x, $comment, $x) = explode("'", $tempComment, 3);
            } else {
                $comment = null;
            }

            $tables[$currentTableName]['fields'][] = [
                'name' => $field,
                'comment' => $comment,
            ];

            if (preg_match('/ REFERENCES "(.*?)" \(\"(.*?)\"\)/', $remnants, $matches)) {
                array_shift($matches);
                $local_column = $field;
                list($foreign_table, $foreign_column) = $matches;
                # store
                $tables[$currentTableName]['fk'][$local_column] = array(
                    'table' => $foreign_table,
                    'column' => $foreign_column
                );
            }
        } elseif (strpos($line, '` FOREIGN KEY (`') > 1) {
            list($x, $fk_name, $x, $local_column, $x, $foreign_table, $x, $foreign_column, $remnants) = explode('`',
                $line, 9);

            $tables[$currentTableName]['fk'][] = [
                'from_column' => $local_column,
                'to_table' => $foreign_table,
                'to_column' => $foreign_column,
            ];
        }


    }
}
fclose($openFile);

print 'digraph H { ' . PHP_EOL;
print 'rankdir=LR;';
foreach ($tables as $table) {
    $tableName = $table['name'];
    print <<< EOF
    ${tableName} [
    shape=plaintext
    label=<
     <table border='1' cellborder='1' cellspacing='0'>
       <tr><td>$tableName</td></tr>
EOF;
    foreach ($table['fields'] as $field) {
        $fieldName = $field['name'];
        $fieldComment = $field['comment'];
        print "<tr>";
        print "<td port='port_${tableName}_${fieldName}_name'>${fieldName}</td>";
        // print "<td port='port_${tableName}_${fieldName}_comment'>${fieldComment}</td>";
        print "</tr>";
        print PHP_EOL;
    }
    print <<< EOF
     </table>
  >];
EOF;
    print PHP_EOL;
}

/*
Foreign Keys
print "admin_menu:port_admin_menu_uri_name -> admin_role_users:port_admin_role_users_user_id_name;";
print "admin_menu:port_admin_menu_parent_id_name -> admin_roles;";
*/
foreach ($tables as $table) {
    if (array_key_exists('fk', $table)) {
        $fks = $table['fk'];
        foreach ($fks as $fk) {
            print $table['name'] . ":port_" . $table['name'] . "_" . $fk['from_column'] . "_name -> " . $fk['to_table'] . ":port_" . $fk['to_table'] . "_" . $fk['to_column'] . "_name;";
            print PHP_EOL;
        }
    }
}

print '}' . PHP_EOL;

exit(0);

# usage information
function usage()
{
    global $argv;
    print '[' . basename($argv[0]) . " - mysql database visualisation tool]\n\n";
    print "usage:\n";
    print "  " . $argv[0] . " -f <sqldumpfile> [-r]\n";
    print "toolchain:\n";
    print " $ mysqldump -d db >db.sql          # MySQL: -d = 'no data', only structure\n";
    print " $ $argv[0] -f ./db.sql >./db.dot # 'dot' is a graphviz format.\n";
    print " $ dot -Tpng db.dot >db.png         # generate image with graphviz\n\n";
    exit(0);
}

# display error and exit
function error($error)
{
    print "ERROR: $error\n";
    exit(1);
}

# display warning and continue
function warning($warning)
{
    $stderr = fopen('php://stderr', 'w');
    fwrite($stderr, "(WARNING: $warning)\n");
    fclose($stderr);
}
