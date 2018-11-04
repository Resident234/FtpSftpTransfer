<?php

namespace FileTransfer\Protocol\Transfer;

use FileTransfer\Helper;

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
    protected $numberFolderSize = 0;// bytes
    protected $numberFolderCountFiles;
    protected $numberFolderCurrentSize = 0;
    protected $numberFolderCurrentCountFiles;

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
        "Swap" => "copy",
        "ShowProgress" => "disable" //disable | enable
    );

    protected $arFullNamesFilesList = [];
    protected $strCurrentPathOrigin = "";
    protected $arCurrentPathOrigin = [];
    protected $arCurrentPathReceiver = [];
    protected $isMkDirLogMode = false;
    protected $isPassiveConnectionMode = true;
    protected $strTransferMode = \FTP_BINARY;
    protected $enumSwapMode = "copy"; // copy | move
    protected $isShowProgress = false;

    protected $arNlistCache = [];

    protected $connectionType;
    protected $connectionUser;
    protected $connectionPass;
    protected $connectionHostname;

    protected $arDirectionPlaceMap = array(
        "Origin" => array("Download" => "Remote", "Upload" => "Local"),
        "Receiver" => array("Download" => "Local", "Upload" => "Remote")
    );

    protected $strCurrentTransferedFolder = "";

    protected $arEnumAllowableEnums = array(
        "enumSwapMode" => ["copy", "move"],
        "enumAction" => ["transfer", "list"],
        "enumOverwriteMode" => ["overwrite", "skip", "rename"],
        "enumDirection" => ["Upload", "Download"]
    );

    ///////////////////////////////////////////////////////////////////
    protected function setFolderCurrentSize($numberFolderCurrentSize)
    {
        if(!$numberFolderCurrentSize) $numberFolderCurrentSize = 0;

        $this->numberFolderCurrentSize = floatval($numberFolderCurrentSize);
    }

    protected function setFolderCurrentCountFiles($numberFolderCurrentCountFiles)
    {
        if(!$numberFolderCurrentCountFiles) $numberFolderCurrentCountFiles = 0;

        $this->numberFolderCurrentCountFiles = intval($numberFolderCurrentCountFiles);
    }

    protected function setFolderDefaultCurrentSize()
    {
        $this->setFolderCurrentSize(0);
    }

    protected function setFolderDefaultCurrentCountFiles()
    {
        $this->setFolderCurrentCountFiles(0);
    }

    ///////////////////////////////////////////////////////////////////
    protected function addToCache($data, $arTags)
    {
        if(!$data || !$arTags) return false;
        if(!is_array($data) || !is_array($arTags)) return false;

        return $this->arNlistCache[implode("|", $arTags)] = $data;
    }

    protected function checkCache($arTags)
    {
        if(!$arTags) return false;
        if(!is_array($arTags)) return false;

        $strTags = implode("|", $arTags);
        if($this->arNlistCache[$strTags]) {
            return $this->arNlistCache[$strTags];
        } else {
            return false;
        }
    }

    protected function clearAllCache()
    {
        $this->arNlistCache = [];
    }

    protected function clearCacheByTag($arTags)
    {
        if($arTags && is_array($arTags)) {
            unset($this->arNlistCache[implode("|", $arTags)]);
        }
    }

    protected function getCacheTags($arAdditionalsTags)
    {
        if(!$arAdditionalsTags) $arAdditionalsTags = [];
        if(!is_array($arAdditionalsTags)) $arAdditionalsTags = [];

        return implode("|", array_merge([$this->connectionHostname, $this->connectionType, $this->connectionUser, $this->connectionPass, $this->pwd()], $arAdditionalsTags));
    }


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
        $this->setDefaultShowProgress();
    }

    ///////////////////////////////////////////////////////////////////

    public function setConnectionParameters($type, $user, $pass, $hostname)
    {
        if(!$type || !is_string($type)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionType"));
        }

        if(!$user || !is_string($user)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionUser"));
        }

        if(!$pass || (!is_string($pass) && !is_array($pass))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionPass"));
        }

        if(!$hostname || (!is_string($hostname))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionHostname"));
        }


        $this->connectionType = $type;
        $this->connectionUser = $user;
        $this->connectionPass = $pass;
        $this->connectionHostname = $hostname;
    }

    public function getConnectionType()
    {
        return $this->connectionType;
    }

    public function getConnectionUser()
    {
        return $this->connectionUser;
    }

    public function getConnectionPass()
    {
        if(is_array($this->connectionPass)) return implode("|", $this->connectionPass);
        return $this->connectionPass;
    }

    public function getConnectionHostname()
    {
        return $this->connectionHostname;
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

    public function setDefaultConnectionMode()
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

    public function setDefaultTransferMode()
    {
        $this->setBinaryTransferMode();
    }

    ///////////////////////////////////////////////////////////////////

    public function setSwapMode($strSwapMode)
    {
        if ($this->arEnumAllowableEnums["enumSwapMode"][$strSwapMode]) {
            $this->enumSwapMode = $strSwapMode;
        };

    }

    public function getSwapMode()
    {
        return $this->enumSwapMode;
    }

    public function setDefaultSwapMode()
    {
        if($this->arDefaultValues["Swap"]) {
            $this->setSwapMode($this->arDefaultValues["Swap"]);
        }
    }

    ///////////////////////////////////////////////////////////////////

    public function enableShowProgress()
    {
        $this->isShowProgress = true;
    }

    public function disableShowProgress()
    {
        $this->isShowProgress = false;
    }

    protected function getShowProgress()
    {
        return $this->isShowProgress;
    }

    public function setDefaultShowProgress()
    {
        if($this->arDefaultValues["ShowProgress"]) {
            $strShowProgress = $this->arDefaultValues["ShowProgress"];
            $this->$strShowProgress . ShowProgress();
        }

        $this->setFolderDefaultCurrentSize();
        $this->setFolderDefaultCurrentCountFiles();
    }

    protected function getCurrentShowProgressPrefix()
    {
        if($this->getShowProgress()) return "nb";
        else return "";
    }


    ///////////////////////////////////////////////////////////////////

    public function search(
        $strNeedleFileName,
        $strHaystackFolderName = ".",
        $isRecursively = true,
        $strAction = "list",
        $strDirection = "download"
    ) {

        if(!$strNeedleFileName || !is_string($strNeedleFileName)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("NeedleFileName"));
        }

        if(!is_string($strHaystackFolderName)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("HaystackFolderName"));
        }

        if(!is_bool($isRecursively)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("Recursively"));
        }

        if(!is_string($strAction) || !$this->arEnumAllowableEnums["enumAction"][$strAction]) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("Action"));
        }

        if(!is_string($strDirection) || !$this->arEnumAllowableEnums["enumDirection"][$strDirection]) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("Direction"));
        }


        $this->setSearchingFilesFilter($strNeedleFileName);
        $this->setRecursively($isRecursively);
        $this->setAction($strAction);
        $this->setDirection($strDirection);

        $this->action . ucwords($this->getAction()) . PreparingBeforeSync();

        $this->searchingPreparingBefore();

        $strMethodName = $strDirection . "Folder";
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
        if (!$strFolder) return;
        if (!is_string($strFolder)) return;

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
        if(!$strRemoteFolder || (!is_string($strRemoteFolder))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . 'error: incorrect' . Helper::camelCaseToText("RemoteFolder"));
        }

        if((!is_string($strLocalFolder))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . 'error: incorrect' . Helper::camelCaseToText("LocalFolder"));
        }

        $this->setDirection("Download");
        $this->strCurrentTransferedFolder = $strRemoteFolder;
        $this->preparingTransferFolder . ucwords($this->getCurrentShowProgressPrefix())($strRemoteFolder);
        $this->sync($strRemoteFolder, $strLocalFolder);
        $this->setDefaultDirection();

    }

    public function uploadFolder($strLocalFolder, $strRemoteFolder = "")
    {
        if(!$strLocalFolder || (!is_string($strLocalFolder))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . 'error: incorrect' . Helper::camelCaseToText("LocalFolder"));
        }

        if((!is_string($strRemoteFolder))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . 'error: incorrect' . Helper::camelCaseToText("RemoteFolder"));
        }

        $this->setDirection("Upload");
        $this->strCurrentTransferedFolder = $strLocalFolder;
        $this->preparingTransferFolder . ucwords($this->getCurrentShowProgressPrefix())($strLocalFolder);
        $this->sync($strLocalFolder, $strRemoteFolder);
        $this->setDefaultDirection();

    }

    ///////////////////////////////////
    protected function preparingTransferFolder($strFolder)
    {

    }

    protected function preparingTransferFolderNB($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $strPlace = $this->arDirectionPlaceMap["Origin"][$this->getDirection()];
        $this->set . $strPlace . FolderSize($strFolder);
        $this->set . $strPlace . FolderCountFiles($strFolder);

    }

    ///////////////////////////////////
    protected function setLocalFolderSize($strLocalFolder)
    {
        if(!$strLocalFolder) return 0;
        if(!is_string($strLocalFolder)) return 0;

        if($this->numberFolderSize = $this->checkCache($this->getCacheTags([__FUNCTION__, $strLocalFolder, $this->getDirection()]))) {
            return $this->numberFolderSize;
        } else {
            $this->numberFolderSize = 0;
            foreach (glob(rtrim($strLocalFolder, '/').'/*', GLOB_NOSORT) as $each) {
                $this->numberFolderSize += is_file($each) ? filesize($each) : folderSize($each);
            }
            return $this->addToCache($this->numberFolderSize, $this->getCacheTags([__FUNCTION__, $strLocalFolder, $this->getDirection()]));
        }

    }

    protected function setRemoteFolderSize($strRemoteFolder)
    {
        if(!$strRemoteFolder) return 0;
        if(!is_string($strRemoteFolder)) return 0;

        if($this->numberFolderSize = $this->checkCache($this->getCacheTags([__FUNCTION__, $strRemoteFolder, $this->getDirection()]))) {
            return $this->numberFolderSize;
        } else {

            $this->numberFolderSize = 0;

            $numberTotalSize = 0;
            $contentsOnServer = $this->nlist($strRemoteFolder);
            foreach ($contentsOnServer as $userFile) {
                if ($this->size($userFile) == -1) {
                    $directory = $userFile;
                    $this->setRemoteFolderSize($directory);
                } else {
                    $numberTotalSize += $this->size($userFile);
                }
            }

            $this->numberFolderSize = $numberTotalSize;

            return $this->addToCache($this->numberFolderSize, $this->getCacheTags([__FUNCTION__, $strRemoteFolder, $this->getDirection()]));
        }

    }

    protected function getFolderSize()
    {
        return $this->numberFolderSize;
    }

    ///////////////////////////////////
    protected function setLocalFolderCountFiles($strLocalFolder)
    {
        if(!$strLocalFolder) return 0;
        if(!is_string($strLocalFolder)) return 0;

        if($this->numberFolderCountFiles = $this->checkCache($this->getCacheTags([__FUNCTION__, $strLocalFolder, $this->getDirection()]))) {
            return $this->numberFolderCountFiles;
        } else {
            $this->numberFolderCountFiles = 0;
            $files = glob($strLocalFolder . '/{,.}*');

            if ( $files !== false )
            {
                $this->numberFolderCountFiles = count( $files );
            }
            return $this->addToCache($this->numberFolderCountFiles, $this->getCacheTags([__FUNCTION__, $strLocalFolder, $this->getDirection()]));
        }

    }

    protected function setRemoteFolderCountFiles($strRemoteFolder)
    {
        if(!$strRemoteFolder) return 0;
        if(!is_string($strRemoteFolder)) return 0;

        if($this->numberFolderCountFiles = $this->checkCache($this->getCacheTags([__FUNCTION__, $strRemoteFolder, $this->getDirection()]))) {
            return $this->numberFolderCountFiles;
        } else {
            $this->numberFolderCountFiles = 0;

            $numberCountFiles = 0;
            $contentsOnServer = $this->nlist($strRemoteFolder);
            foreach ($contentsOnServer as $userFile) {
                if ($this->size($userFile) == -1) {
                    $directory = $userFile;
                    $this->setRemoteFolderCountFiles($directory);
                } else {
                    $numberCountFiles++;
                }
            }

            $this->numberFolderCountFiles = $numberCountFiles;

            return $this->addToCache($this->numberFolderCountFiles, $this->getCacheTags([__FUNCTION__, $strRemoteFolder, $this->getDirection()]));
        }

    }

    protected function getFolderCountFiles()
    {
        return $this->numberFolderCountFiles;
    }

    ///////////////////////////////////
    protected function sync($strOriginFolder, $strReceiverFolder = "")
    {
        if(!$strOriginFolder) return;
        if(!is_string($strOriginFolder) || !is_string($strReceiverFolder)) return;

        if (empty($strReceiverFolder)) {
            $strReceiverFolder = $strOriginFolder;
        }

        $intCountOriginFolders = 0;
        $intCountReceiverFolders = 0;

        if ($strOriginFolder != ".") {
            if ($this->syncChdirOrigin($strOriginFolder) == false) {
                throw new \Exception(Helper::camelCaseToText("ChdirOrigin") . 'error');
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

        if($contents) {
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

                }
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
        if(!$strFileFullName) return;
        if(!is_string($strFileFullName)) return;

        $this->arFullNamesFilesList[] = $strFileFullName;
    }

    protected function clearCurrentPathOrigin()
    {
        $this->arCurrentPathOrigin = [];
    }

    public function getCurrentPathOrigin()
    {
        return $this->arCurrentPathOrigin;
    }

    protected function addToCurrentPathOrigin($strFolderOrFile)
    {
        if(!$strFolderOrFile) return;
        if(!is_string($strFolderOrFile)) return;

        $this->arCurrentPathOrigin[] = $strFolderOrFile;
    }

    protected function removeLastElementFormCurrentPathOrigin()
    {
        if(!$this->arCurrentPathOrigin) return;

        //remove last element from array
        array_splice($this->arCurrentPathOrigin, count($this->arCurrentPathOrigin) - 1, 1);
    }



    protected function clearCurrentPathReceiver()
    {
        $this->arCurrentPathReceiver = [];
    }

    public function getCurrentPathReceiver()
    {
        return $this->arCurrentPathReceiver;
    }

    protected function addToCurrentPathReceiver($strFolderOrFile)
    {
        if(!$strFolderOrFile) return;
        if(!is_string($strFolderOrFile)) return;

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
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $this->action . ucwords($this->getAction()) . Sync($strFolder);

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncChdirReceiver($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncIsDirReceiver($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncIsDirOrigin($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncMkDir($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

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
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($strFolder);
    }

    protected function syncAction($file, $strActionMethodName)
    {
        if(!$file || !$strActionMethodName) return;
        if(!is_string($file) || !is_string($strActionMethodName)) return;

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
        if(!$file) return;
        if(!is_string($file)) return;

        $this->syncFolderTransfer . $this->getCurrentShowProgressPrefix()($file);
        $method = __FUNCTION__ . $this->enumDirection;
        return $this->$method($file);
    }

    protected function syncFolderTransferNB($file)
    {
        if(!$file) return;
        if(!is_string($file)) return;

        echo $this->strCurrentTransferedFolder . ": " . $this->humanFilesize($this->numberFolderCurrentSize += $this->get . $this->arDirectionPlaceMap["Origin"][$this->getDirection()] . FileSize($file)) . " / " . $this->humanFilesize($this->numberFolderSize) . ", ";
        echo $this->humanFilesize($this->numberFolderCurrentCountFiles++) . " / " . $this->humanFilesize($this->numberFolderCountFiles) . "\n";
    }

    protected function syncFolderTransfer()
    {

    }

    protected function syncList($file)
    {
        if(!$file) return;
        if(!is_string($file)) return;

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
        if(!$file) return;
        if(!is_string($file)) return;

        $this->delete($file);
        return true;
    }

    protected function syncSwapFolderCopy($folder)
    {
        return true;
    }

    protected function syncSwapFolderMove($folder)
    {
        if(!$folder) return;
        if(!is_string($folder)) return;

        $this->rmdir($folder);
        return true;
    }
    ///////////////////////////////////
    protected function syncChdirOriginDownload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return $this->chdir($strFolder);
    }

    protected function syncChdirOriginUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return chdir($strFolder);
    }

    protected function syncChdirReceiverDownload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return chdir($strFolder);
    }

    protected function syncChdirReceiverUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return $this->chdir($strFolder);
    }

    protected function syncIsDirReceiverDownload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return is_dir($strFolder);
    }

    protected function syncIsDirReceiverUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return $this->isDir($strFolder);
    }

    protected function syncIsDirOriginDownload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return $this->isDir($strFolder);
    }

    protected function syncIsDirOriginUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        return is_dir($strFolder);
    }

    protected function syncMkDirDownload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        mkdir($strFolder, 0777, true);
        return chdir($strFolder);
    }

    protected function syncMkDirUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

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
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        if($nlist = $this->checkCache($this->getCacheTags([$strFolder, "Download"]))) {
            return $nlist;
        } else {
            return $this->addToCache($this->nlist($strFolder), $this->getCacheTags([$strFolder, "Download"]));
        }
    }

    protected function syncNlistUpload($strFolder)
    {
        if(!$strFolder) return;
        if(!is_string($strFolder)) return;

        if($nlist = $this->checkCache($this->getCacheTags([$strFolder, "Upload"]))) {
            return $nlist;
        } else {
            $arFiles = array();
            $all = opendir($strFolder);
            while ($file = readdir($all)) {
                $arFiles[] = $file;
            }
            closedir($all);
            unset($all);

            return $this->addToCache($arFiles, $this->getCacheTags([$strFolder, "Upload"]));
        }
    }

    protected function syncTransferDownload($file)
    {
        if(!$file) return;
        if(!is_string($file)) return;

        return syncFileTransfer . ucwords($this->getCurrentShowProgressPrefix())($file, "get");
    }

    protected function syncTransferUpload($file)
    {
        if(!$file) return;
        if(!is_string($file)) return;

        return syncFileTransfer . ucwords($this->getCurrentShowProgressPrefix())($file, "put");
    }

    protected function syncFileTransferNB($file, $strDirection)
    {
        if(!$file || !$strDirection) return;
        if(!is_string($file) || !is_string($strDirection)) return;

        $strRet = $this->nb_ . $strDirection($file, $file, $this->getTransferMode());

        while ($strRet == FTP_MOREDATA) {
            echo $this->pwd() . "/" . $file . ": " . $this->getSyncFileTransferDirection . ucwords($strDirection) . PercentProgress($file, $file) . " %";
            echo $this->getSyncFileTransferDirection . ucwords($strDirection) . SizeProgress($file, $file);

            $strRet = $this->nb_continue();
        }

        return $strRet;
    }

    protected function syncFileTransfer($file, $strDirection)
    {
        if(!$file || !$strDirection) return;
        if(!is_string($file) || !is_string($strDirection)) return;

        return $this->$strDirection($file, $file, $this->getTransferMode());
    }

    protected function getSyncFileTransferDirectionGetPercentProgress($localFile, $remoteFile)
    {
        if(!$localFile || !$remoteFile) return;
        if(!is_string($localFile) || !is_string($remoteFile)) return;

        return round(($this->getLocalFileSize($localFile) / $this->getRemoteFileSize($remoteFile)) * 100);
    }

    protected function getSyncFileTransferDirectionPutPercentProgress($remoteFile, $localFile)
    {
        if(!$localFile || !$remoteFile) return;
        if(!is_string($localFile) || !is_string($remoteFile)) return;

        return round(($this->getRemoteFileSize($remoteFile) / $this->getLocalFileSize($localFile)) * 100);
    }

    protected function getSyncFileTransferDirectionGetSizeProgress($localFile, $remoteFile)
    {
        if(!$localFile || !$remoteFile) return;
        if(!is_string($localFile) || !is_string($remoteFile)) return;

        return humanFilesize($this->getLocalFileSize($localFile)) . " / " . humanFilesize($this->getRemoteFileSize($remoteFile));
    }

    protected function getSyncFileTransferDirectionPutSizeProgress($remoteFile, $localFile)
    {
        if(!$localFile || !$remoteFile) return;
        if(!is_string($localFile) || !is_string($remoteFile)) return;

        return humanFilesize($this->getRemoteFileSize($remoteFile)) . " / " . humanFilesize($this->getLocalFileSize($localFile));
    }

    public function getRemoteFileSize($remoteFile)
    {
        if(!$remoteFile) return;
        if(!is_string($remoteFile)) return;

        return $this->size($remoteFile);
    }

    public function getLocalFileSize($localFile)
    {
        if(!$localFile) return;
        if(!is_string($localFile)) return;

        return filesize($localFile);
    }

    protected function humanFilesize($bytes, $decimals = 2) {
        if(!$bytes) return;
        if(!is_numeric($bytes) || !is_numeric($decimals)) return;

        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    ///////////////////////////////////

    private function isDir($dir)
    {
        if(!$dir) return false;
        if(!is_string($dir)) return false;

        $is_dir = false;

        $origin = $this->pwd();

        if (@$this->chdir($dir)) {
            $this->chdir($origin);
            $is_dir = true;
        }
        return $is_dir;
    }

    protected function fileExistGet($strFileName)
    {
        if(!$strFileName) return false;
        if(!is_string($strFileName)) return false;

        $content = $this->syncNlistUpload(".");

        if (in_array($strFileName, $content)) {
            return true;
        } else {
            return false;
        };
    }

    protected function fileExistPut($strFileName)
    {
        if(!$strFileName) return false;
        if(!is_string($strFileName)) return false;

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
        if(!$strDirectory) return false;
        if(!is_string($strDirectory)) return false;

        return $strDirectory;
    }

    protected function download($strRemoteFile)
    {
        if(!$strRemoteFile) return false;
        if(!is_string($strRemoteFile) && !is_array($strRemoteFile)) return false;

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
        if(!$strLocalFile) return false;
        if(!is_string($strLocalFile) && !is_array($strLocalFile)) return false;

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
        if(!$strOverwriteMode) return;
        if(!is_string($strOverwriteMode)) return;

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
        if(!$strAction) return;
        if(!is_string($strAction)) return;

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
        if(!$strDirection) return;
        if(!is_string($strDirection)) return;

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
        if(!$strRegex) return;
        if(!is_string($strRegex)) return;

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

    protected function isTriggeringSearchingFilesFilter($file)
    {
        if(!$file) return false;
        if(!is_string($file)) return false;

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

        return $isTriggering;

    }


    /////////////////////////////////////////////
    public function setRecursively($isRecursively)
    {
        if(!$isRecursively) return;
        if(!is_string($isRecursively)) return;

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
