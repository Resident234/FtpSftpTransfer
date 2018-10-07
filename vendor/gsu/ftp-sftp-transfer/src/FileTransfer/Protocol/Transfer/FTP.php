<?php
/**
 * FTP connections using PHPs ftp-functions
 *
 * @author Tuomas Angervuori <tuomas.angervuori@gmail.com>
 * @note Does not support connections through proxy
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL v3
 */

namespace FileTransfer\Protocol\Transfer;

class FTP extends Transfer
{

    protected $connect;


    public function __construct($host, \FileTransfer\Protocol\Authentication\Authentication $auth, $port = 21)
    {
        $this->strFunctionPrefix = 'ftp_';
        $this->connect = ftp_connect($host, $port);
        $auth->setConnect($this->connect);
        if ($auth->authenticate() === false) {
            throw new \Exception('FTP login is invalid.');
        }
    }

    public function __call($func, $args)
    {
        $this->func = $func;
        $this->args = $args;

        $this->prepareArguments();

        if (function_exists($this->func)) {

            array_unshift($this->args, $this->connect);

            $result = call_user_func_array($this->func, $this->args);
            //if(!$result) throw new \Exception($this->func . ' runtime error. Args: ' . var_dump($this->args));
            return $result;
        } else {
            $e = new \Exception($this->func . ' is not a valid FTP function.');
            var_dump($e->getTraceAsString());
            throw $e;
        }


    }

    /*protected $connect;

    public function __construct($host, $user = 'anonymous', $pass = '', $port = 21, $passiveMode = true)
    {
        $this->connect = ftp_connect($host, $port);
        if (!$this->connect) {
            throw new \Exception("Couldn't connect to $host:$port");
        }
        if (!ftp_login($this->connect, $user, $pass)) {
            throw new \Exception("Couldn't authorize with $user:$pass on $host:$port");
        }
    }*/

}

/*
protected $conn;

/**
 * Establish a connection
 * @todo Options...
 *//*
    public function __construct($url, array $options = null)
    {

        $data = parse_url($url);
        //echo $data['scheme'];

        //Establish connection to the server
        if (isset($data['scheme']) && strtolower($data['scheme']) == 'ftps') {
            if (isset($data['port']) && $data['port']) {
                $this->conn = ftp_ssl_connect($data['host'], $data['port']);
                if (!$this->conn) {
                    throw new \Connection\Exception("Could not connect to 'ftps://{$data['host']}:{$data['port']}'");
                }
            } else {
                $this->conn = ftp_ssl_connect($data['host']);
                if (!$this->conn) {
                    throw new \Connection\Exception("Could not connect to 'ftps://{$data['host']}'");
                }
            }
        } else {
            if (isset($data['port']) && $data['port']) {
                $this->conn = ftp_connect($data['host'], $data['port']);
                if (!$this->conn) {
                    throw new \Connection\Exception("Could not connect to 'ftp://{$data['host']}:{$data['port']}'");
                }
            } else {
                $this->conn = ftp_connect($data['host']);
                if (!$this->conn) {
                    throw new \Connection\Exception("Could not connect to 'ftp://{$data['host']}'");
                }
            }
        }

        //Provide username and password
        if (isset($data['user'])) {
            $user = urldecode($data['user']);
        } else {
            $user = 'anonymous';
        }
        if (isset($data['pass'])) {
            $pass = urldecode($data['pass']);
        } else {
            $pass = '';
        }
        if (!ftp_login($this->conn, $user, $pass)) {
            throw new \Connection\Exception("Could not login to '{$data['host']}' as '$user'");
        }

        //Use firewall friendly passive mode
        if (!ftp_pasv($this->conn, true)) {
            trigger_error("Passive mode failed", \E_USER_WARNING);
        }

        //Go to defined directory
        if (isset($data['path']) && $data['path']) {
            // Make sure the $path ends with a slash.
            $data['path'] = rtrim($data['path'], '/') . '/';
            $this->cd($data['path']);
        }
    }

    /**
     * Close connection on exit
     *//*
    public function __destruct()
    {
        if ($this->conn) {
            ftp_close($this->conn);
        }
    }

    /**
     * Change directory
     *//*
    public function cd($directory)
    {
        if (!ftp_chdir($this->conn, $directory)) {
            throw new \Connection\Exception("Changing directory to '$directory' failed");
        }
        return true;
    }

    /**
     * Print working directory
     *//*
    public function pwd()
    {
        if (!$pwd = ftp_pwd($this->conn)) {
            throw new \Connection\Exception("Printing working directory failed");
        }
        return $pwd;
    }

    /**
     * Download a file
     *//*
    public function get($remoteFile)
    {

        $tmpFile = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $remoteFile;
        $this->tmpFiles[] = $tmpFile;
        $tmp = fopen($tmpFile, 'w');
        ftp_pasv($this->conn, true);
        if (!ftp_fget($this->conn, $tmp, $remoteFile, \FTP_BINARY)) {
            throw new \Connection\Exception("Could not download file '$remoteFile'");
        }
        //ftp_close($this->conn);
        fclose($tmp);
        return $tmpFile;

    }

    /**
     * Upload a file
     *//*
    public function put($file, $remoteFile)
    {

        $fp = fopen($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $file, 'r');
        if (!ftp_fput($this->conn, $remoteFile, $fp, \FTP_BINARY)) {
            throw new \Connection\Exception("Could not upload file '$remoteFile'");
        }

        return true;
    }

    /**
     * List current directory
     * @todo add more info about files (size: ftp_size, modified: ftp_mdtm, is directory...)
     *//*
    public function ls()
    {
        $dir = ftp_nlist($this->conn, '.');
        if ($dir === false) {
            throw new \Connection\Exception("Listing directory failed");
        }
        return $dir;
    }

    /**
     * File or directory exists
     *//*
    public function exists($path)
    {
        $listing = @ftp_nlist($this->conn, $path);
        if (empty($listing)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Delete file from remote server
     *//*
    public function rm($remoteFile)
    {
        if (!ftp_delete($this->conn, $remoteFile)) {
            throw new \Connection\Exception("Could not remove file '$remoteFile'");
        }
    }

    /**
     * Rename file in remote server
     *//*
    public function mv($remoteFile, $newName)
    {
        if (!ftp_rename($this->conn, $remoteFile, $newName)) {
            throw new \Connection\Exception("Could not rename file '$remoteFile' as '$newName'");
        }
    }

    /**
     * Create a directory in remote server
     *//*
    public function mkdir($dirName)
    {
        if (!ftp_mkdir($this->conn, $dirName)) {
            throw new \Connection\Exception("Could not create directory '$dirName'");
        }
    }

    /**
     * Remove a directory from remote server
     *//*
    public function rmdir($dirName)
    {
        if (!ftp_rmdir($this->conn, $dirName)) {
            throw new \Connection\Exception("Could not remove directory '$dirName'");
        }
    }

    /**
     * Return array of supported protocols
     *//*
    public static function getAvailableProtocols()
    {
        $protocols = array();
        if (function_exists('ftp_connect')) {
            $protocols[] = 'ftp';
        }
        if (function_exists('ftp_ssl_connect')) {
            $protocols[] = 'ftps';
        }
        return $protocols;
    }*/


/*
class FTP extends AbstractProtocol
{
    private $conn;

    public function __construct($hostname, $user = 'anonymous', $pass = '', $port = 21, $passiveMode = true)
    {
        if (!extension_loaded('ftp')) {
            throw new \Exception("PHP extension FTP is not loaded.\n");
        }
        $this->setUser($user);
        $this->setPass($pass);
        $this->setHost($hostname);
        try {
            $this->conn = ftp_connect($hostname, $port);
        } catch (\Exception $e) {
            throw $e;
        }
        if (ftp_login($this->conn, $this->getUser(), $this->getPass())) {
            echo "Login success.\n";
            ftp_pasv($this->conn, $passiveMode);
        } else {
            throw new \Exception("Access denied to {$this->getHost()} to user {$this->getUser()}\n");
        }
    }

    public function __desctruct()
    {
        ftp_close($this->conn);
    }

    public function upload($localFile, $remoteFile = false)
    {
        if ($remoteFile == false) {
            $remoteFile = $localFile;
        }
        if (!is_file($localFile)) {
            throw new \Exception("Can't find local\n");
        }
        if (ftp_put($this->conn, $remoteFile, $localFile, FTP_BINARY)) {
            echo "{$localFile} uploaded\n";
        } else {
            echo "Failed to upload {$localFile}\n";
        }
    }

    public function exec($cmd)
    {
        $siteCommands = ftp_raw($this->conn, 'SITE HELP');
        if (is_array($siteCommands) && in_array(' EXEC', $siteCommands)) {
            return ftp_raw($this->conn, "SITE EXEC " . $cmd);
        } else {

            throw new \Exception("FTP server not support \"EXEC\" command\n");
        }
    }

//Добавил этот метод тк скорее всего pwd значит текущую команду
    public function pwd()
    {
        return $this->exec('pwd');
    }
}
*/


/*
class Ftp extends ProtocolAbstract{
    private $conn;
    public function __construct($host,$login = 'anonymous',$pass = '',$port = 21, $passiveMode = true){
        if (!extension_loaded('ftp')) {
            throw new \Exception("PHP extension FTP is not loaded.\n");
        }
        $this->setLogin($login);
        $this->setPassword($pass);
        $this->setHost($host);
        try{
            $this->conn = ftp_connect($host,$port);
        }catch (\Exception $e){
            throw $e;
        }
        if(ftp_login($this->conn,$this->getLogin(),$this->getPassword())){
            echo "Login success.\n";
            ftp_pasv($this->conn, $passiveMode);
        }else{
            throw new \Exception("Access denied to {$this->getHost()} to user {$this->getUser()}\n");
        }
    }
    public function __desctruct()
    {
        ftp_close($this->conn);
    }
    public function upload($localFile, $remoteFile = false)
    {
        if($remoteFile == false){
            $remoteFile = $localFile;
        }
        if(!is_file($localFile)){
            throw new \Exception("Can't find local\n");
        }
        if(ftp_put($this->conn, $remoteFile, $localFile, FTP_BINARY)){
            echo "{$localFile} uploaded\n";
        }else{
            echo "Failed to upload {$localFile}\n";
        }
    }
    public function exec($cmd)
    {
        $siteCommands = ftp_raw($this->conn, 'SITE HELP');
        if(is_array($siteCommands) && in_array(' EXEC',$siteCommands)){
            return ftp_raw($this->conn, "SITE EXEC " . $cmd);
        }else{

            throw new \Exception("FTP server not support \"EXEC\" command\n");
        }
    }
}*/


/*
class FTP implements ConnectionInterface
{
    protected $connection;

    public function __construct($user, $pass, $hostname, $port = 21){ //check $port param
        $this->connection = ftp_connect($hostname, $port);
        if (!$this->connection) {
            throw new \Exception("Couldn't connect to $hostname:$port");
        }
        if (!ftp_login($this->connection, $user, $pass)) {
            throw new \Exception("Couldn't authorize with $user:$pass on $hostname:$port");
        }
    }

    //TODO is $path exists
    public function cd($path){
        if (!is_string($path)) {
            throw new \InvalidArgumentException('$path should be a string');
        }
        if (!ftp_chdir($this->connection, $path)){
            throw new \Exception("Couldn't change directory to $path");
        }
    }

    public function pwd(){
        $currentPath = ftp_pwd($this->connection);
        if (!$currentPath) {
            throw new \Exception("Cannot read current directory");
        }
        return $currentPath;
    }

    //TODO is file exists
    //TODO is enough space
    public function download($fileName){
        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('$fileName should be a string');
        }
        if (!ftp_get($this->connection, $fileName, $fileName, FTP_BINARY)){
            throw new \Exception("Cannot download file $filename");
        }
    }

    //TODO is file exists
    //TODO is enough space
    public function upload($fileName){
        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('$fileName should be a string');
        }
        if (!ftp_put($this->connection, $fileName, $fileName, FTP_BINARY)){
            throw new \Exception("Cannot upload file $filename");
        }
    }

    //TODO check is there ways to get output instead of status of command execution
    public function exec($command){
        if (!is_string($command)) {
            throw new \InvalidArgumentException('$command should be a string');
        }
        if (!ftp_exec($this->connection, $command)){
            throw new \Exception("Couldn't execute command $command");
        }
    }

    public function close(){
        $closeResult = ftp_close($this->connection);
        if (!$closeResult) {
            throw new \Exception("Cannot read current directory");
        }
        return $closeResult;
    }
}*/


