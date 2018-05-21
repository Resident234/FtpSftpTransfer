<?php
/**
 * SSH, SCP and SFTP connections using PHPs ssh2-functions
 *
 * @author Tuomas Angervuori <tuomas.angervuori@gmail.com>
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL v3
 */

namespace FileTransfer\Protocol\Transfer;

class SSH2
{

    protected $connect;

    public function __construct($host, \FileTransfer\Protocol\Authentication\SSH2 $auth, $port = 22)
    {
        $this->connect = ssh2_connect($host, $port);
        $auth->setConnect($this->connect);
        if ($auth->authenticate() === false) {
            throw new Exception('SSH2 login is invalid.');
        }
    }

}

/*
 implements \Connection\TransferProtocol {
	
	protected $ssh;
	protected $sftp;
	protected $dir;


	/*
    protected $connect;

    public function __construct($host, SSH2 $auth, $port = 22) {
        $this->connect = ssh2_connect($host, $port);
        $auth->setConnect($this->connect);
        if ($auth->authenticate() === false) {
            throw new Exception('SSH2 login is invalid.');
        }
    }
	*//*


	/**
	 * Establish a connection
	 *//*
	public function __construct($url, array $options = null) {
		
		$parsedUrl = parse_url($url);
		$fingerprint = null;
		$pubkey = null;
		
		//Check options
		if($options) {
			foreach($options as $option => $value) {
				if($option == 'fingerprint') {
					$fingerprint = $value;
				}
				else if($option == 'pubkey') {
					$pubkey = $value;
				}
				else {
					trigger_error("Unknown option '$option'",\E_USER_NOTICE);
				}
			}
		}
		
		//Initialize connection
		$host = urldecode($parsedUrl['host']);
		if(isset($parsedUrl['port']) && $parsedUrl['port']) {
			$port = urldecode($parsedUrl['port']);
			$this->ssh = ssh2_connect($host, $port);
		}
		else {
			$this->ssh = ssh2_connect($host);
		}
		if($this->ssh === false) {
			throw new \Connection\Exception("Could not connect to '$host'");
		}
		
		//Check server fingerprint (if defined)
		if($fingerprint) {
			$serverFingerprint = $this->getFingerprint();
			if($fingerprint != $serverFingerprint) {
				throw new \Connection\Exception("Server fingerprint '$serverFingerprint' does not match!");
			}
		}
		
		//Provide authentication information
		if($pubkey) { //Using public key authentication
			if(isset($pubkey['passphrase']) && $pubkey['passphrase']) {
				$status = ssh2_auth_pubkey_file($this->ssh, $pubkey['user'], $pubkey['pubkeyfile'], $pubkey['privkeyfile'], $pubkey['passphrase']);
			}
			else {
				$status = ssh2_auth_pubkey_file($this->ssh, $pubkey['user'], $pubkey['pubkeyfile'], $pubkey['privkeyfile']);
			}
			if(!$status) {
				throw new \Connection\Exception("Could not login to '$host' as '{$pubkey['user']}' using public key authentication");
			}
		}
		else if(isset($parsedUrl['user']) && $parsedUrl['user']) { //Using login & password
			$user = urldecode($parsedUrl['user']);
			$pass = urldecode($parsedUrl['pass']);
			if(!ssh2_auth_password($this->ssh, $user, $pass)) {
				throw new \Connection\Exception("Could not login to '$host' as '$user'");
			}
		}
		
		//Set default directory
		if(isset($parsedUrl['path']) && $parsedUrl['path']) {
			$this->cd(urldecode($parsedUrl['path']));
		}
	}
	
	/**
	 * Get server fingerprint
	 * @note A ssh2/scp/sftp only feature
	 *//*
	public function getFingerprint() {
		return ssh2_fingerprint($this->ssh);
	}
	
	/**
	 * Change directory
	 *//*
	public function cd($directory) {
		return $this->dir = $directory;
	}

	/**
	 * Print working directory
	 *//*
	public function pwd() {
		return $this->dir;
	}

	/**
	 * Download a file 
	 * @note requires full path to file
	 *//*
	public function get($remoteFile) {
		
		$file = $this->_getFilename($remoteFile);
		$data = file_get_contents('ssh2.sftp://' . $this->_getSftp() . $file);
		
		if($data === false) {
			throw new \Connection\Exception("Could not download file '$file'");
		}
		
		return $data;
	}
	
	/**
	 * Upload a file 
	 *//*
	public function put($data, $remoteFile) {
		
		$file = $this->_getFilename($remoteFile);
		if(file_put_contents('ssh2.sftp://' . $this->_getSftp() . $file, $data) === false) {
			throw new \Connection\Exception("Could not upload file '$file'");
		}
		
		return true;
	}
	
	/**
	 * List current directory
	 *//*
	public function ls() {
		
		$handle = opendir('ssh2.sftp://' . $this->_getSftp() . '/' . $this->dir);
		if(!$handle) {
			throw new \Connection\Exception("Listing directory '{$this->dir}' failed");
		}
		while(false !== ($file = readdir($handle))) {
			if($file != '.' && $file != '..') {
				$dir[] = $file;
			}
		}
		closedir($handle);
		sort($dir);
		
		return $dir;
	}
	
	/**
	 * File or directory exists
	 *//*
	public function exists($path) {

        $sshPath = sprintf('ssh2.sftp://%s/%s', $this->_getSftp(), $this->dir . '/' . $path);    

        return file_exists($sshPath);
    }	
	
	/**
	 * Delete a file from remote server
	 *//*
	public function rm($remoteFile) {
		
		$file = $this->_getFilename($remoteFile);
		if(!ssh2_sftp_unlink($this->_getSftp(), $file)) {
			throw new \Connection\Exception("Could not remove file '$file'");
		}
	}
		
	/**
	 * Rename file in remote server
	 *//*
	public function mv($remoteFile, $newName) {
		
		$from = $this->_getFilename($remoteFile);
		$to = $this->_getFilename($newName);
		if(!ssh2_sftp_rename($this->_getSftp(), $from, $to)) {
			throw new \Connection\Exception("Could not rename file '$from' as '$to'");
		}
	}
	
	/**
	 * Create a directory in remote server
	 *//*
	public function mkdir($dirName) {
		
		$dir = $this->dir . '/' . $dirName;
		if(!ssh2_sftp_mkdir($this->_getSftp(), $dir)) {
			throw new \Connection\Exception("Could not create directory '$dir'");
		}
	}
	
	/**
	 * Remove a directory from remote server
	 *//*
	public function rmdir($dirName) {
		
		$dir = $this->dir . '/' . $dirName;
		if(!ssh2_sftp_rmdir($this->_getSftp(), $dir)) {
			throw new \Connection\Exception("Could not remove directory '$dir'");
		}
	}
	
	/**
	 * Return array of supported protocols
	 *//*
	public static function getAvailableProtocols() {
		$protocols = array();
		if(function_exists('ssh2_connect')) {
			$protocols = array('ssh','scp','sftp');
		}
		return $protocols;
	}
	
	/**
	 * Initialize SFTP subsystem
	 *//*
	protected function _getSftp() {
		if(!$this->sftp) {
			$this->sftp = ssh2_sftp($this->ssh);
			if($this->sftp === false) {
				throw new \Connection\Exception("Could not initialize SFTP subsystem");
			}
		}
		return $this->sftp;
	}
	
	/**
	 * Get absolute path
	 *//*
	protected function _getFilename($file) {
		if($this->dir) {
			return $this->dir . '/' . $file;
		}
		else {
			return '/' . $file;
		}
	}
}





/*
class SFTP extends AbstractProtocol{
    private $sftp;
    private $_ssh2;
    private $currentDir;
    public function __construct($hostname,$user = 'anonymous',$pass = '',$port = 22, $passiveMode = true){
        if (!extension_loaded('ssh2')) {
            throw new \Exception("PHP extension \"ssh2\" is not loaded.\n");
        }
        $this->setUser($user);
        $this->setPass($pass);
        $this->setHost($hostname);
        try{
            $this->_ssh2 = ssh2_connect($hostname,$port);
        }catch (\Exception $e){
            throw $e;
        }
        if(ssh2_auth_password($this->_ssh2,$this->getUser(),$this->getPass())){
            echo "Login success.\n";
            $this->sftp = ssh2_sftp($this->_ssh2);
        }else{
            throw new \Exception("Access denied to {$this->getHost()} to user {$this->getUser()}\n");
        }
        if(!$this->sftp){
            throw new \Exception("Could not initialize SFTP subsystem.");
        }
        $this->currentDir = "/";
    }
    public function cd($dir){
        if( !is_dir("ssh2.sftp://{$this->sftp}{$dir}") ){
            throw new \Exception("Directory {$dir} not found on server.\n");
        }
        if(substr($dir,(strlen($dir)-1), 1) != '/'){
            $dir .= "/";
        }
        $this->currentDir = $dir;
        return $this;
    }
    public function download($fileName, $localFileName = false){
        if($localFileName === false) {
            $localFileName = $fileName;
        }
        if(!preg_match("/\/[\/\w.]+/", $fileName)){
            $fileName = $this->currentDir . $fileName;
        }
        if( !is_file("ssh2.sftp://{$this->sftp}{$fileName}") ){
            throw new \Exception("Can't find {$fileName} on remote server.\n");
        }
        $stream = fopen("ssh2.sftp://{$this->sftp}{$fileName}", 'r');
        if(is_file($localFileName)){
            file_put_contents($localFileName, '');
        }else{
            file_put_contents($localFileName,'');
        }
        $fileSize = filesize("ssh2.sftp://{$this->sftp}{$fileName}");
        if($fileSize != 0){
            echo "Reciving {$fileSize} bytes of {$fileName}\n";
            $contents = fread($stream, filesize("ssh2.sftp://{$this->sftp}{$fileName}"));
            $received =  file_put_contents ($localFileName, $contents);
            echo "Writed {$received} bytes to {$localFileName}\n";
        }
        fclose($stream);
        return $this;
    }
    public function close(){
        ssh2_exec($this->_ssh2, 'exit');
    }
}*/


/*
class Ssh extends ProtocolAbstract{
    private $sftp;
    private $_ssh2;
    private $currentDir;
    public function __construct($host,$login = 'anonymous',$pass = '',$port = 22, $passiveMode = true){
        if (!extension_loaded('ssh2')) {
            throw new \Exception("PHP extension \"ssh2\" is not loaded.\n");
        }
        $this->setLogin($login);
        $this->setPassword($pass);
        $this->setHost($host);
        try{
            $this->_ssh2 = ssh2_connect($host,$port);
        }catch (\Exception $e){
            throw $e;
        }
        if(ssh2_auth_password($this->_ssh2,$this->getLogin(),$this->getPassword())){
            echo "Login success.\n";
            $this->sftp = ssh2_sftp($this->_ssh2);
        }else{
            throw new \Exception("Access denied to {$this->getHost()} to user {$this->getUser()}\n");
        }
        if(!$this->sftp){
            throw new Exception("Could not initialize SFTP subsystem.");
        }
        $this->currentDir = "/";
    }
    public function cd($dir)
    {
        if( !is_dir("ssh2.sftp://{$this->sftp}{$dir}") ){
            throw new \Exception("Directory {$dir} not found on server.\n");
        }
        if(substr($dir,(strlen($dir)-1), 1) != '/'){
            $dir .= "/";
        }
        $this->currentDir = $dir;
        return $this;
    }
    public function download($fileName, $localFileName = false)
    {
        if($localFileName === false) {
            $localFileName = $fileName;
        }
        if(!preg_match("/\/[\/\w.]+/", $fileName)){
            $fileName = $this->currentDir . $fileName;
        }
        if( !is_file("ssh2.sftp://{$this->sftp}{$fileName}") ){
            throw new \Exception("Can't find {$fileName} on remote server.\n");
        }
        $stream = fopen("ssh2.sftp://{$this->sftp}{$fileName}", 'r');
        if(is_file($localFileName)){
            file_put_contents($localFileName, '');
        }else{
            file_put_contents($localFileName,'');
        }
        $fileSize = filesize("ssh2.sftp://{$this->sftp}{$fileName}");
        if($fileSize != 0){
            echo "Reciving {$fileSize} bytes of {$fileName}\n";
            $contents = fread($stream, filesize("ssh2.sftp://{$this->sftp}{$fileName}"));
            $received =  file_put_contents ($localFileName, $contents);
            echo "Writed {$received} bytes to {$localFileName}\n";
        }
        fclose($stream);
        return $this;
    }
    public function close()
    {
        ssh2_exec($this->_ssh2, 'exit');
    }
}

*/


/*
class SSH implements ConnectionInterface
{
    protected $connection;

    public function __construct($user, $pass, $hostname, $port = 22){ //check $port param
        $this->connection = ssh2_connect($hostname, $port);
        if (!$this->connection) {
            throw new \Exception("Couldn't connect to $hostname:$port");
        }
        if (!ssh2_auth_password($this->connection, $user, $pass)) {
            throw new \Exception("Couldn't authorize with $user:$pass on $hostname:$port");
        }
    }

    //TODO is $path exists
    public function cd($path){
        if (!is_string($path)) {
            throw new \InvalidArgumentException('$path should be a string');
        }
        if (!ssh2_exec($this->connection, "cd $path")){
            throw new \Exception("Couldn't change directory to $path");
        }
    }

    public function pwd(){
        $stream = ssh2_exec($this->connection, '${PWD##*//*}');
        if (!$stream) {
            throw new \Exception("Cannot read current directory or exec error");
        }

        stream_set_blocking( $stream, true );
        $response = '';
        while ($line=fgets($stream))
        {
            $response .= $line;
        }
        return $response;
    }

    //TODO is file exists
    //TODO is enough space
    public function download($fileName){
        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('$fileName should be a string');
        }
        if (!ssh2_scp_recv($this->connection, $fileName, $fileName)){
            throw new \Exception("Cannot download file $filename");
        }
    }

    //TODO is file exists
    //TODO is enough space
    public function upload($fileName){
        if (!is_string($fileName)) {
            throw new \InvalidArgumentException('$fileName should be a string');
        }
        if (!ssh2_scp_send($this->connection, $fileName, $fileName)){
            throw new \Exception("Cannot upload file $filename");
        }
    }

    public function exec($command){
        if (!is_string($command)) {
            throw new \InvalidArgumentException('$command should be a string');
        }
        $stream = ssh2_exec($this->connection, $command);
        if (!$stream){
            throw new \Exception("Couldn't execute command $command");
        }

        stream_set_blocking($stream, true);
        $response = '';
        while ($line=fgets($stream)) {
            $response .= $line;
        }
        return $response;
    }

    public function close(){
        $closeResult = ssh2_exec($this->connection, 'exit;');
        if (!$closeResult) {
            throw new \Exception("Cannot read current directory");
        }
        return $closeResult;
    }
}*/