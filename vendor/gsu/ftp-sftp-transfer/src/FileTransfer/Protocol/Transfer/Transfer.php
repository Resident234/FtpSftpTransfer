<?php
/**
 * Interface for backend types
 *
 * @author Tuomas Angervuori <tuomas.angervuori@gmail.com>
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL v3
 */

namespace FileTransfer\Protocol\Transfer;

abstract class Transfer
{
    protected $strFunctionPrefix = '';
    protected $args;
    protected $func;
    protected $argsCalledFromCall;
    protected $funcCalledFromCall;
    protected $enumDirection; // Upload | Download
    protected $enumOverwriteMode = "overwrite"; // overwrite | skip | rename
    protected $argsStash;
    protected $funcStash;
    protected $arCurrentNlistContent = [];
    protected $arCurrentReaddirContent = [];
    protected $arSearchingFilesFilter = "";
    protected $isRecursively = true;
    protected $enumAction = "transfer"; // transfer | list

    protected $arCustomFunctions = array(
        "cd" => "chdir",
        "download" => "get",
        "upload" => "put"
    );

    protected $arTransferFunctions = ["get", "put"];

    protected $arDefaultValues = array(
        "Direction" => "Download",
        "OverwriteMode" => "overwrite",
        "Recursively" => true,
        "Action" => "transfer",
        "Swap" => "copy"
    );

    protected $arFullNamesFilesList = [];
    protected $strCurrentPathOrigin = "";
    protected $arCurrentPathOrigin = [];
    protected $arCurrentPathReceiver = [];
    protected $isMkDirLogMode = false;
    protected $isPassiveConnectionMode = true;
    protected $strTransferMode = \FTP_BINARY;
    protected $enumSwapMode = "copy"; // copy | move

    ///////////////////////////////////////////////////////////////////
    protected function prepareArguments()
    {
        if (isset($this->arCustomFunctions[$this->func])) {
            $funcName = $this->func;
            $this->args = $this->$funcName($this->args);
            $this->func = $this->arCustomFunctions[$this->func];
        }

        if ($this->checkIsTransferFunction()) {
            $transferFunctionName = "transfer" . ucwords($this->enumOverwriteMode);
            $this->$transferFunctionName();
        }

        if (!$this->checkIsHightLevelFunction()) {
            $this->func = $this->strFunctionPrefix . $this->func;
        }

    }

    protected function checkIsTransferFunction()
    {
        return in_array($this->func, $this->arTransferFunctions);//array_key_exists
    }


    protected function checkIsHightLevelFunction()
    {
        return method_exists($this, $this->func);
    }

    ///////////////////////////////////////////////////////////////////

    public function setDefaultParameters()
    {
        $this->setDefaultRecursively();
        $this->setDefaultAction();
        $this->setDefaultOverwriteMode();
        $this->setDefaultDirection();
        $this->unsetSearchingFilesFilter();
        $this->clearCurrentPathOrigin();
        $this->clearFullNamesFilesList();
        $this->setDefaultConnectionMode();
        $this->setDefaultTransferMode();
        $this->setDefaultSwapMode();
    }


    ///////////////////////////////////////////////////////////////////

    public function enablePassiveConnectionMode()
    {
        return $this->isPassiveConnectionMode = true;
    }

    public function disablePassiveConnectionMode()
    {
        return $this->isPassiveConnectionMode = false;
    }

    public function isPassiveMode()
    {
        return $this->isPassiveMode;
    }

    protected function setDefaultConnectionMode()
    {
        $this->pasv($this->enablePassiveConnectionMode());
    }

    ///////////////////////////////////////////////////////////////////

    public function setBinaryTransferMode()
    {
        return $this->strTransferMode = \FTP_BINARY;
    }

    public function setTextualTransferMode()
    {
        return $this->strTransferMode = \FTP_ASCII;
    }

    public function getTransferMode()
    {
        return $this->strTransferMode;
    }

    protected function setDefaultTransferMode()
    {
        $this->setBinaryTransferMode();
    }

    ///////////////////////////////////////////////////////////////////

    public function setSwapMode($strSwapMode)
    {
        $this->enumSwapMode = $strSwapMode;
    }

    public function getSwapMode()
    {
        return $this->enumSwapMode;
    }

    protected function setDefaultSwapMode()
    {
        $this->setSwapMode($this->arDefaultValues["Swap"]);
    }

    ///////////////////////////////////////////////////////////////////

    public function search(
        $strNeedleFileName,
        $strHaystackFolderName = ".",
        $isRecursively = true,
        $strAction = "list",
        $strDirection = "download"
    ) {

        echo "strNeedleFileName<pre>";
        print_r($strNeedleFileName);
        echo "</pre>";
        // Anorexia.ttf index ['Anorexia.ttf', 'admin-bar.php'] /php/i
        $this->setSearchingFilesFilter($strNeedleFileName);

        // false true
        $this->setRecursively($isRecursively);

        // list transfer
        $this->setAction($strAction);

        // download upload
        $this->setDirection($strDirection);

        $this->action . ucwords($this->getAction()) . PreparingBeforeSync();

        $this->searchingPreparingBefore();

        $strMethodName = $strDirection . "Folder";
        //echo $strMethodName . "<br>";
        $this->$strMethodName($strHaystackFolderName);

        $this->action . ucwords($this->getAction()) . PreparingAfterSync();

        $this->searchingPreparingAfter();

        $this->unsetSearchingFilesFilter();
        $this->setDefaultRecursively();
        $this->setDefaultAction();
        $this->setDefaultDirection();

    }

    ///////////////////////////////////
    private function actionListPreparingBeforeSync()
    {
        $this->clearFullNamesFilesList();
        $this->clearCurrentPathOrigin();
    }

    private function actionListPreparingAfterSync()
    {
        $this->printFullNamesFilesList();

        $this->clearFullNamesFilesList();
        $this->clearCurrentPathOrigin();
    }

    private function actionListSync($strFolder)
    {
        if ($strFolder == "..") {
            $this->removeLastElementFormCurrentPathOrigin();
        } else {
            $this->addToCurrentPathOrigin($strFolder);
        }
    }

    private function actionTransferPreparingBeforeSync()
    {

    }

    private function actionTransferPreparingAfterSync()
    {

    }

    private function actionTransferSync()
    {

    }


    /*private function actionMovePreparingBeforeSync()
    {

    }

    private function actionMovePreparingAfterSync()
    {

    }

    private function actionMoveSync()
    {

    }*/
    ///////////////////////////////////

    private function searchingPreparingBefore()
    {
        if($this->isSearchingFilesFilter()) {
            $this->enableMkDirLogMode();
        }
    }

    private function searchingPreparingAfter()
    {
        if($this->isSearchingFilesFilter()) {
            $this->disableMkDirLogMode();
            $this->clearCurrentPathReceiver();
        }
    }

    ///////////////////////////////////
    public function downloadFolder($strRemoteFolder, $strLocalFolder = "")
    {
        $this->setDirection("Download");
        $this->sync($strRemoteFolder, $strLocalFolder);
        $this->setDefaultDirection();

    }

    public function uploadFolder($strLocalFolder, $strRemoteFolder = "")
    {
        $this->setDirection("Upload");
        $this->sync($strLocalFolder, $strRemoteFolder);
        $this->setDefaultDirection();

    }

    ///////////////////////////////////
    protected function sync($strOriginFolder, $strReceiverFolder = "")
    {
        //echo $strOriginFolder . " OriginFolder<br>";
        //echo $strReceiverFolder . " ReceiverFolder<br>";

        if (empty($strReceiverFolder)) {
            $strReceiverFolder = $strOriginFolder;
        }

        $intCountOriginFolders = 0;
        $intCountReceiverFolders = 0;

        if ($strOriginFolder != ".") {
            if ($this->syncChdirOrigin($strOriginFolder) == false) {
                throw new \Exception("Change Dir Failed: $strOriginFolder");
            }

            if (!($this->syncIsDirReceiver($strReceiverFolder))) {
                $this->syncMkDir($strReceiverFolder);
            } else {
                $this->syncChdirReceiver($strReceiverFolder);
            }

            $intCountOriginFolders = count(explode("/", $strOriginFolder));
            $intCountReceiverFolders = count(explode("/", $strReceiverFolder));

        }

        $contents = $this->syncNlist(".");

        echo "contents<pre>";
        print_r($contents);
        echo "</pre>";

        foreach ($contents as $file) {

            if ($file == '.' || $file == '..') {
                continue;
            }

            if ($this->syncIsDirOrigin($file)) {
                if ($this->isRecursively()) {
                    $this->sync($file);
                }
            } else {
                $strActionMethodName = $this->getAction();
                $this->syncAction($file, $strActionMethodName);
                $this->syncSwapFile . ucwords($this->getSwapMode())($file);

                //$this->pasv(true);
                //$this->sync . ucwords($strActionMethodName)($file);

            }
        }

        for ($i = 0; $i < $intCountOriginFolders; $i++) {
            $this->syncChdirOrigin("..");
        }

        for ($i = 0; $i < $intCountReceiverFolders; $i++) {
            $this->syncChdirReceiver("..");
        }

        $this->syncSwapFolder . ucwords($this->getSwapMode())($strOriginFolder);

    }

    /// ftp_rmdir
    /// ftp_delete

    /////////////////////////////////////////////
    protected function clearFullNamesFilesList()
    {
        $this->arFullNamesFilesList = [];
    }

    protected function getFullNamesFilesList()
    {
        return $this->arFullNamesFilesList;
    }

    public function printFullNamesFilesList()
    {
        echo implode("<br>", $this->getFullNamesFilesList());
    }

    protected function putToFullNamesFilesList($strFileFullName)
    {
        $this->arFullNamesFilesList[] = $strFileFullName;
    }

    protected function clearCurrentPathOrigin()
    {
        $this->arCurrentPathOrigin = [];
    }

    protected function getCurrentPathOrigin()
    {
        return $this->arCurrentPathOrigin;
    }

    protected function addToCurrentPathOrigin($strFolderOrFile)
    {
        $this->arCurrentPathOrigin[] = $strFolderOrFile;
    }

    protected function removeLastElementFormCurrentPathOrigin()
    {
        //remove last element from array
        array_splice($this->arCurrentPathOrigin, count($this->arCurrentPathOrigin) - 1, 1);
    }



    protected function clearCurrentPathReceiver()
    {
        $this->arCurrentPathReceiver = [];
    }

    protected function getCurrentPathReceiver()
    {
        return $this->arCurrentPathReceiver;
    }

    protected function addToCurrentPathReceiver($strFolderOrFile)
    {
        $this->arCurrentPathReceiver[] = $strFolderOrFile;
    }

    protected function removeLastElementFormCurrentPathReceiver()
    {
        //remove last element from array
        if($this->arCurrentPathReceiver) {
            array_splice($this->arCurrentPathReceiver, count($this->arCurrentPathReceiver) - 1, 1);
        }
    }


    protected function disableMkDirLogMode()
    {
        $this->isMkDirLogMode = false;
    }

    protected function enableMkDirLogMode()
    {
        $this->isMkDirLogMode = true;
    }

    protected function isMkDirLogMode()
    {
        return $this->isMkDirLogMode;
    }


    ///////////////////////////////////
    protected function syncChdirOrigin($strFolder)
    {
        $this->action . ucwords($this->getAction()) . Sync($strFolder);

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncChdirReceiver($strFolder)
    {
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncIsDirReceiver($strFolder)
    {
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncIsDirOrigin($strFolder)
    {
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncMkDir($strFolder)
    {

        if ($this->isSearchingFilesFilter() && $this->isMkDirLogMode()) {
            $this->addToCurrentPathReceiver($strFolder);
            return true;
        } else {
            if ($this->isSearchingFilesFilter()) {
                $this->clearCurrentPathReceiver();
            }
            $method = __FUNCTION__ . $this->enumDirection;
            return $this->$method($strFolder);
        }
    }

    protected function syncNlist($strFolder)
    {
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncAction($file, $strActionMethodName)
    {
        if ($this->isSearchingFilesFilter()) {
            if ($this->isTriggeringSearchingFilesFilter($file)) {
                $this->disableMkDirLogMode();
                $this->syncMkDir(implode(DIRECTORY_SEPARATOR, $this->getCurrentPathReceiver()));
                $this->clearCurrentPathReceiver();
                $this->enableMkDirLogMode();
                $this->pasv($this->isPassiveMode());
                $this->sync . ucwords($strActionMethodName)($file);
            };
        } else {
            $this->pasv($this->isPassiveMode());
            $this->sync . ucwords($strActionMethodName)($file);
        }
    }

    protected function syncTransfer($file)
    {
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($file);
    }

    protected function syncList($file)
    {
        $this->addToCurrentPathOrigin($file);
        return $this->putToFullNamesFilesList(implode(DIRECTORY_SEPARATOR, $this->getCurrentPathOrigin()));

        //$method = __FUNCTION__ . $this->enumDirection;
        //return $this->$method($file);
    }

    ///////////////////////////////////
    protected function syncSwapFileCopy($file)
    {
        return true;
    }

    protected function syncSwapFileMove($file)
    {
        $this->delete($file);
        return true;
    }

    protected function syncSwapFolderCopy($folder)
    {
        return true;
    }

    protected function syncSwapFolderMove($folder)
    {
        $this->rmdir($folder);
        return true;
    }
    ///////////////////////////////////
    protected function syncChdirOriginDownload($strFolder)
    {

        return $this->chdir($strFolder);
    }

    protected function syncChdirOriginUpload($strFolder)
    {
        return chdir($strFolder);
    }

    protected function syncChdirReceiverDownload($strFolder)
    {
        return chdir($strFolder);
    }

    protected function syncChdirReceiverUpload($strFolder)
    {
        return $this->chdir($strFolder);
    }

    protected function syncIsDirReceiverDownload($strFolder)
    {
        return is_dir($strFolder);
    }

    protected function syncIsDirReceiverUpload($strFolder)
    {
        return $this->isDir($strFolder);
    }

    protected function syncIsDirOriginDownload($strFolder)
    {
        return $this->isDir($strFolder);
    }

    protected function syncIsDirOriginUpload($strFolder)
    {
        return is_dir($strFolder);
    }

    protected function syncMkDirDownload($strFolder)
    {
        mkdir($strFolder, 0777, true);
        return chdir($strFolder);
    }

    protected function syncMkDirUpload($strFolder)
    {
        $parts = explode('/', $strFolder); // 2013/06/11/username
        foreach ($parts as $part) {
            if (!@$this->chdir($part)) {
                $this->mkdir($part);
                $this->chdir($part);
            }
        }
        return true;
    }

    protected function syncNlistDownload($strFolder)
    {
        return $this->nlist($strFolder);
    }


    protected function syncNlistUpload($strFolder)
    {
        $arFiles = array();
        $all = opendir($strFolder);
        while ($file = readdir($all)) {
            $arFiles[] = $file;
        }
        closedir($all);
        unset($all);

        return $arFiles;
    }

    /*protected function getLocalDirContent($strFolder){

    }

    protected function getRemoteDirContent($strFolder){

    }*/

    protected function syncTransferDownload($file)
    {
        return $this->get($file, $file, $this->getTransferMode());
    }

    protected function syncTransferUpload($file)
    {
        return $this->put($file, $file, $this->getTransferMode());
    }


    /*protected function syncMoveDownload($file)
    {
        $this->get($file, $file, $this->getTransferMode());
        return true;
    }

    protected function syncMoveUpload($file)
    {
        $this->put($file, $file, $this->getTransferMode());
        return true;
    }*/


    ///////////////////////////////////


    /**
     * Test if a directory exist
     *
     * @param string $dir
     * @return bool $is_dir
     */
    private function isDir($dir)
    {
        $is_dir = false;

        # Get the current working directory
        $origin = $this->pwd();

        # Attempt to change directory, suppress errors
        if (@$this->chdir($dir)) {
            # If the directory exists, set back to origin
            $this->chdir($origin);
            $is_dir = true;
        }
        return $is_dir;
    }

    protected function fileExistGet($strFileName)
    {
        $content = $this->syncNlistUpload(".");

        if (in_array($strFileName, $content)) {
            return true;
        } else {
            return false;
        };
    }

    protected function fileExistPut($strFileName)
    {
        $this->callArgumentsStashing();

        $content = $this->syncNlistDownload(".");

        $this->callArgumentsUnstashing();

        if (in_array($strFileName, $content)) {
            return true;
        } else {
            return false;
        };
    }

    ///////////////////////////////////
    protected function cd($strDirectory)
    {
        return $strDirectory;
    }

    protected function download($strRemoteFile)
    {
        if(is_array($strRemoteFile)) {
            foreach($strRemoteFile as $file) {
                $this->download($file);
            }
        } else {
            $strRemoteFile = current($strRemoteFile);
            $strLocalFile = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $strRemoteFile;
            return [$strLocalFile, $strRemoteFile, $this->getTransferMode()];
        }
    }

    protected function upload($strLocalFile)
    {
        if(is_array($strLocalFile)) {
            foreach($strLocalFile as $file) {
                $this->upload($file);
            }
        } else {
            $strLocalFile = current($strLocalFile);
            $strRemoteFile = $strLocalFile;
            return [$strRemoteFile, $strLocalFile, $this->getTransferMode()];
        }
    }

    /////////////////////////////////////////////
    public function setOverwriteMode($strOverwriteMode)
    {
        $this->enumOverwriteMode = $strOverwriteMode;
    }

    public function getOverwriteMode()
    {
        return $this->enumOverwriteMode;
    }

    public function setDefaultOverwriteMode()
    {
        $this->setOverwriteMode($this->arDefaultValues["OverwriteMode"]);
    }

    ///////////////////////////////////////////////////////////////////
    public function setAction($strAction)
    {
        $this->enumAction = $strAction;
    }

    public function getAction()
    {
        return $this->enumAction;
    }

    public function setDefaultAction()
    {
        $this->setAction($this->arDefaultValues["Action"]);
    }

    ///////////////////////////////////////////////////////////////////
    public function setDirection($strDirection)
    {
        $this->enumDirection = $strDirection;
    }

    public function getDirection()
    {
        return $this->enumDirection;
    }

    public function setDefaultDirection()
    {
        $this->setDirection($this->arDefaultValues["Direction"]);
    }


    /////////////////////////////////////////////
    public function setSearchingFilesFilter($strRegex)
    {
        $this->arSearchingFilesFilter = $strRegex;
    }

    public function getSearchingFilesFilter()
    {
        return $this->arSearchingFilesFilter;
    }

    public function unsetSearchingFilesFilter()
    {
        $this->arSearchingFilesFilter = "";
    }

    public function isSearchingFilesFilter()
    {
        if (empty($this->arSearchingFilesFilter)) {
            return false;
        } else {
            return true;
        }
    }

    public function isTriggeringSearchingFilesFilter($file)
    {
        $strSearchingFilesFilter = $this->getSearchingFilesFilter();

        if(!$strSearchingFilesFilter) return false;

        if(!is_array($strSearchingFilesFilter)){
            $arSearchingFilesFilter = [];
            $arSearchingFilesFilter[] = $strSearchingFilesFilter;
        } else {
            $arSearchingFilesFilter = $strSearchingFilesFilter;
        }

        $isTriggering = false;

        foreach($arSearchingFilesFilter as $strItemSearchingFilesFilter) {
            $bPregMathResult = @preg_match($strItemSearchingFilesFilter, $file);
            if($bPregMathResult === 1){
                $isTriggering = true;
                break;
            } else if ($bPregMathResult === false) {
                if (strpos($file, $strItemSearchingFilesFilter) !== false) {
                    $isTriggering  = true;
                    break;
                }
            }
        }

        if($isTriggering) {
            echo $strSearchingFilesFilter . "<br>";
            echo $file . "<br>";
            echo "<hr>";
        }

        return $isTriggering;


    }


    /////////////////////////////////////////////
    public function setRecursively($isRecursively)
    {
        $this->isRecursively = $isRecursively;
    }

    public function isRecursively()
    {
        return $this->isRecursively;
    }

    public function setDefaultRecursively()
    {
        $this->setRecursively($this->arDefaultValues["Recursively"]);
    }

    //////////////////////////////////////////////
    protected function transferSkip()
    {

        $intArgNumber = 0;

        $fileExistFunctionName = "fileExist" . ucwords($this->func);

        $arFileInfo = pathinfo($this->args[$intArgNumber]);
        $strFile = $arFileInfo["filename"] . "." . $arFileInfo["extension"];//$arFileInfo["dirname"] . "/" .

        if ($this->$fileExistFunctionName($strFile)) {
            //пропустить выполнение функции
            $this->func = "exec"; //TODO: заменить на самую легковесную команду
            $this->args = ["ls -al"];
        } else {
            return true;
        }

    }

    protected function transferRename()
    {

        $intArgNumber = 0; //$this->arTransferFunctions[$this->func];

        $fileExistFunctionName = "fileExist" . ucwords($this->func);

        $arFileInfo = pathinfo($this->args[$intArgNumber]);
        $intNumberCopy = 1;
        $strNewFile = $arFileInfo["filename"] . "." . $arFileInfo["extension"];//$arFileInfo["dirname"] . "/" .

        if ($this->$fileExistFunctionName($strNewFile)) {
            while (1) {
                $strLocalNewFile = $arFileInfo["filename"] . "(" . $intNumberCopy . ")." . $arFileInfo["extension"];
                if (!$this->$fileExistFunctionName($strLocalNewFile)) {
                    $strNewFile = $strLocalNewFile;
                    break;
                }
                $intNumberCopy++;
            }
        }
        $this->args[$intArgNumber] = $strNewFile;

        return true;
    }

    protected function transferOverwrite()
    {
        return true;
    }

    //////////////////////////////////////////////
    protected function callArgumentsStashing()
    {
        $this->funcStash = $this->func;
        $this->argsStash = $this->args;
    }

    protected function callArgumentsUnstashing()
    {
        $this->func = $this->funcStash;
        $this->args = $this->argsStash;
    }

}
