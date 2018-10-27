<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 16.05.2018
 * Time: 21:58
 */

ini_set('max_execution_time', 3000);

require('vendor/autoload.php'); //Use composer autoload

use FileTransfer as FT;

$factory = new FT\Factory();

/*
echo "Example #1<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech', 21);
    $conn->cd('wp-includes');
    $conn->pasv(true);
    $conn->download('admin-bar.php');
    $conn->download('bookmark.php');
    $conn->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #2<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech', 21);
    $conn->cd('wp-includes');
    $conn->pasv(true);
    $conn->get('author-template.php', 'author-template.php', \FTP_BINARY);
    $conn->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #3<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech', 21);
    $conn->cd('wp-includes');
    echo $conn->pwd() . "<br>";
    $conn->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #4<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    $conn->put('1MBRemote.zip', '1MB.zip', \FTP_BINARY);
    $conn->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #5<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    echo $conn->pwd() . "<br>";
    $conn->upload('1MB.zip');
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #6<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    echo $conn->pwd() . "<br>";

    echo "<pre>";
    print_r($conn->rawlist($conn->pwd()));
    echo "</pre>";

    $conn->chdir("info");

    echo "<pre>";
    print_r($conn->rawlist($conn->pwd()));
    echo "</pre>";

    $conn->cdup();
    $conn->chmod(0777, "wp-config.php");

    echo "<pre>";
    print_r($conn->rawlist($conn->pwd()));
    echo "</pre>";

    $conn->delete("1MBRemote.zip");

    echo "<pre>";
    print_r($conn->rawlist($conn->pwd()));
    echo "</pre>";

    echo $conn->mdtm("wp-config.php") . "<br>";

    $conn->mkdir("new_directory_from_ftp");

    $conn->rename("wp-config.php", "wp-config_new.php");

    /* for PHP >= 7.2.0
    echo "<pre>";
    print_r($conn->mlsd("."));
    echo "</pre>";
    * /

    echo "<pre>";
    print_r($conn->rawlist($conn->pwd()));
    echo "</pre>";

    $conn->rename("wp-config_new.php", "wp-config.php");

    $conn->rmdir("new_directory_from_ftp");

    echo $conn->size("wp-config.php") . "<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}*/

/*
echo "Example #7<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->downloadFolder('fonts');
    //$conn->downloadFolder('wp-includes');
    $conn->downloadFolder('libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills');
    $conn->downloadFolder('info', 'downloads/1/2/3/info');
    $conn->downloadFolder('info', 'downloads/77/info');
    $conn->downloadFolder('info', 'downloads/88/info');
    $conn->downloadFolder('libs/jquery-validation', 'downloads/libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills_local');
    $conn->downloadFolder('skills', 'skills_local');

    $conn->uploadFolder('fonts');
    $conn->uploadFolder('fonts', 'uploads/fonts');
    //$conn->uploadFolder('wp-includes', 'uploads/wp-includes');
    $conn->uploadFolder('fonts', 'uploads/1/2/3/fonts');
    $conn->uploadFolder('fonts', 'uploads/77/fonts');
    $conn->uploadFolder('fonts', 'uploads/88/fonts');
    $conn->uploadFolder('libs/jquery-validation', 'uploads/libs/jquery-validation');
    $conn->uploadFolder('fonts', 'uploads/99/fonts');

    echo "done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #8<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->setOverwriteMode("skip");
    $conn->downloadFolder('fonts');
    //$conn->downloadFolder('wp-includes');
    $conn->downloadFolder('libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills');
    $conn->downloadFolder('info', 'downloads/1/2/3/info');
    $conn->downloadFolder('info', 'downloads/77/info');
    $conn->downloadFolder('info', 'downloads/88/info');
    $conn->downloadFolder('libs/jquery-validation', 'downloads/libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills_local');
    $conn->downloadFolder('skills', 'skills_local');

    $conn->uploadFolder('fonts');
    $conn->uploadFolder('fonts', 'uploads/fonts');
    //$conn->uploadFolder('wp-includes', 'uploads/wp-includes');
    $conn->uploadFolder('fonts', 'uploads/1/2/3/fonts');
    $conn->uploadFolder('fonts', 'uploads/77/fonts');
    $conn->uploadFolder('fonts', 'uploads/88/fonts');
    $conn->uploadFolder('libs/jquery-validation', 'uploads/libs/jquery-validation');
    $conn->uploadFolder('fonts', 'uploads/99/fonts');

    echo "skip done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #9<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->setOverwriteMode("rename");
    $conn->downloadFolder('fonts');
    //$conn->downloadFolder('wp-includes');
    $conn->downloadFolder('libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills');
    $conn->downloadFolder('info', 'downloads/1/2/3/info');
    $conn->downloadFolder('info', 'downloads/77/info');
    $conn->downloadFolder('info', 'downloads/88/info');
    $conn->downloadFolder('libs/jquery-validation', 'downloads/libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills_local');
    $conn->downloadFolder('skills', 'skills_local');

    $conn->uploadFolder('fonts');
    $conn->uploadFolder('fonts', 'uploads/fonts');
    //$conn->uploadFolder('wp-includes', 'uploads/wp-includes');
    $conn->uploadFolder('fonts', 'uploads/1/2/3/fonts');
    $conn->uploadFolder('fonts', 'uploads/77/fonts');
    $conn->uploadFolder('fonts', 'uploads/88/fonts');
    $conn->uploadFolder('libs/jquery-validation', 'uploads/libs/jquery-validation');
    $conn->uploadFolder('fonts', 'uploads/99/fonts');

    echo "rename done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}*/


echo "Example #10<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    //$conn->setOverwriteMode("skip");
    $conn->search('Anorexia.ttf'); echo "<hr><hr>";
    $conn->search('index'); echo "<hr><hr>";
    $conn->search('index.php', 'libs/jquery-validation'); echo "<hr><hr>";
    $conn->search('index.php', 'libs/jquery-validation', false); echo "<hr><hr>";
    $conn->search('index.php', 'libs/jquery-validation', true, 'transfer'); echo "<hr><hr>";
    $conn->search(['Anorexia.ttf', 'admin-bar.php'], true, 'list'); echo "<hr><hr>";
    $conn->search('/php/i', true, 'list'); echo "<hr>";

    $conn->search('index.php', 'libs/jquery-validation', true, 'transfer', 'upload'); echo "<hr><hr>";
    $conn->search(['Anorexia.ttf', 'admin-bar.php'], true, 'list', 'upload'); echo "<hr><hr>";

    echo "search done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #11<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->setSwapMode("move");

    $conn->download('admin-bar.php');
    $conn->download('bookmark.php');
    $conn->upload('1MB.zip');
    $conn->downloadFolder('fonts');
    //$conn->downloadFolder('wp-includes');
    $conn->downloadFolder('libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills');
    $conn->downloadFolder('info', 'downloads/1/2/3/info');
    $conn->downloadFolder('info', 'downloads/77/info');
    $conn->downloadFolder('info', 'downloads/88/info');
    $conn->downloadFolder('libs/jquery-validation', 'downloads/libs/jquery-validation');
    $conn->downloadFolder('skills', 'downloads/skills_local');
    $conn->downloadFolder('skills', 'skills_local');

    $conn->uploadFolder('fonts');
    $conn->uploadFolder('fonts', 'uploads/fonts');
    //$conn->uploadFolder('wp-includes', 'uploads/wp-includes');
    $conn->uploadFolder('fonts', 'uploads/1/2/3/fonts');
    $conn->uploadFolder('fonts', 'uploads/77/fonts');
    $conn->uploadFolder('fonts', 'uploads/88/fonts');
    $conn->uploadFolder('libs/jquery-validation', 'uploads/libs/jquery-validation');
    $conn->uploadFolder('fonts', 'uploads/99/fonts');

    $conn->search('Anorexia.ttf'); echo "<hr><hr>";
    $conn->search('index'); echo "<hr><hr>";
    $conn->search('index.php', 'libs/jquery-validation'); echo "<hr><hr>";
    $conn->search('index.php', 'libs/jquery-validation', false); echo "<hr><hr>";


    echo "move done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #12<br>";
try {
    $conn = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->download(['admin-bar.php', 'index.php']);
    $conn->upload(['1MB.zip', 'index.php']);

    echo "files transfer done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #13<br>";
try {
    $conn = $factory->getConnection('ssh', 'gsu123ln_personal', ['public_key', 'private_key'], 'gsu123ln.beget.tech', 22);

    $conn->download(['admin-bar.php', 'index.php']);
    $conn->upload(['1MB.zip', 'index.php']);

    echo "files transfer done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #14<br>";
try {
    $conn = $factory->getConnection('ssh', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn->download(['admin-bar.php', 'index.php']);
    $conn->upload(['1MB.zip', 'index.php']);

    echo "files transfer done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}


echo "Example #15<br>";
try {
    $conn1 = $factory->getConnection('ssh', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    $conn2 = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn2->downloadFolder('libs/jquery-validation');
    $conn1->download(['admin-bar.php', 'index.php']);
    $conn2->downloadFolder('skills', 'downloads/skills');
    $conn1->upload(['1MB.zip', 'index.php']);
    $conn2->downloadFolder('info', 'downloads/1/2/3/info');

    echo "files transfer done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}

echo "Example #15<br>";
try {
    $conn1 = $factory->getConnection('ssh', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    $conn2 = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');
    $conn3 = $factory->getConnection('ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech');

    $conn3->download('admin-bar.php');
    $conn3->download('bookmark.php');
    $conn3->upload('1MB.zip');
    $conn3->upload('50MB.zip');
    $conn3->download('50MB.zip');
    $conn3->downloadFolder('fonts');
    $conn3->downloadFolder('wp-includes');
    $conn3->downloadFolder('libs/jquery-validation');
    $conn3->downloadFolder('skills', 'downloads/skills');
    $conn3->downloadFolder('info', 'downloads/1/2/3/info');
    $conn3->downloadFolder('info', 'downloads/77/info');
    $conn3->downloadFolder('info', 'downloads/88/info');
    $conn3->downloadFolder('libs/jquery-validation', 'downloads/libs/jquery-validation');
    $conn3->downloadFolder('skills', 'downloads/skills_local');
    $conn3->downloadFolder('skills', 'skills_local');

    $conn3->uploadFolder('fonts');
    $conn3->uploadFolder('fonts', 'uploads/fonts');
    $conn3->uploadFolder('wp-includes', 'uploads/wp-includes');
    $conn3->uploadFolder('fonts', 'uploads/1/2/3/fonts');
    $conn3->uploadFolder('fonts', 'uploads/77/fonts');
    $conn3->uploadFolder('fonts', 'uploads/88/fonts');
    $conn3->uploadFolder('libs/jquery-validation', 'uploads/libs/jquery-validation');
    $conn3->uploadFolder('fonts', 'uploads/99/fonts');

    $conn3->search('Anorexia.ttf'); echo "<hr><hr>";
    $conn3->search('index'); echo "<hr><hr>";
    $conn3->search('index.php', 'libs/jquery-validation'); echo "<hr><hr>";
    $conn3->search('index.php', 'libs/jquery-validation', false); echo "<hr><hr>";

    $conn2->downloadFolder('libs/jquery-validation');
    $conn1->download(['admin-bar.php', 'index.php']);
    $conn2->downloadFolder('skills', 'downloads/skills');
    $conn1->upload(['1MB.zip', 'index.php']);
    $conn2->downloadFolder('info', 'downloads/1/2/3/info');

    echo "files transfer done<br>";

} catch (Exception $e) {
    echo $e->getMessage();
}