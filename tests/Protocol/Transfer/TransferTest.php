<?php

namespace FileTransfer\Protocol\Transfer;

use PHPUnit\Framework\TestCase;
use FileTransfer as FT;

class TransferTest extends TestCase
{
    private $arFTPConnectionSettings = ['ftp', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech', 21];
    private $arSSHConnectionSettings = ['ssh', 'gsu123ln_personal', 'w4z&0GH0', 'gsu123ln.beget.tech', 22];

    public function testConstruct()
    {
        $factory = new FT\Factory();
        $this->assertInstanceOf(FT\Factory::class, $factory);

        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );
        $this->assertInstanceOf(FileTransfer\Protocol\Transfer\FTP::class, $connFTP);

        $connSSH = $factory->getConnection(
            $this->arSSHConnectionSettings[0],
            $this->arSSHConnectionSettings[1],
            $this->arSSHConnectionSettings[2],
            $this->arSSHConnectionSettings[3],
            $this->arSSHConnectionSettings[4]
        );
        $this->assertInstanceOf(FileTransfer\Protocol\Transfer\SSH2Transfer\SSH2SFTP::class, $connSSH);
    }

    public function testSetFolderCurrentSize()
    {
        $factory = new FT\Factory();

    }

    public function testSetFolderCurrentCountFiles()
    {

    }


    public function testSetFolderDefaultCurrentSize()
    {

    }

    public function testSetFolderDefaultCurrentCountFiles()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function testAddToCache($data, $arTags)
    {

    }

    public function testCheckCache($arTags)
    {

    }

    public function testClearAllCache()
    {
    }

    public function testClearCacheByTag($arTags)
    {

    }

    public function testGetCacheTags($arAdditionalsTags)
    {

    }


    ///////////////////////////////////////////////////////////////////
    public function testPrepareArguments()
    {

    }

    public function testCheckIsTransferFunction()
    {
    }


    public function testCheckIsHightLevelFunction()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function testSetDefaultParameters()
    {

    }

    ///////////////////////////////////////////////////////////////////

    public function testSetConnectionParameters()
    {

    }

    public function testGetConnectionType()
    {
    }

    public function testGetConnectionUser()
    {
    }

    public function testGetConnectionPass()
    {

    }

    public function testGetConnectionHostname()
    {
    }
    ///////////////////////////////////////////////////////////////////

    public function testEnablePassiveConnectionMode()
    {
    }

    public function testDisablePassiveConnectionMode()
    {
    }

    public function testIsPassiveMode()
    {
    }

    public function testSetDefaultConnectionMode()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function testSetBinaryTransferMode()
    {
    }

    public function testSetTextualTransferMode()
    {
    }

    public function testGetTransferMode()
    {
    }

    public function testSetDefaultTransferMode()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function testSetSwapMode($strSwapMode)
    {


    }

    public function testGetSwapMode()
    {
    }

    public function testSetDefaultSwapMode()
    {

    }

    ///////////////////////////////////////////////////////////////////

    public function testEnableShowProgress()
    {
    }

    public function testDisableShowProgress()
    {
    }

    public function testGetShowProgress()
    {
    }

    public function testSetDefaultShowProgress()
    {

    }

    public function testGetCurrentShowProgressPrefix()
    {

    }


    ///////////////////////////////////////////////////////////////////

    public function testSearch(
    ) {


    }

    ///////////////////////////////////
    private function testActionListPreparingBeforeSync()
    {

    }

    private function testActionListPreparingAfterSync()
    {

    }

    private function testActionListSync($strFolder)
    {

    }

    private function testActionTransferPreparingBeforeSync()
    {

    }

    private function testActionTransferPreparingAfterSync()
    {

    }

    private function testActionTransferSync()
    {

    }


    ///////////////////////////////////

    private function testSearchingPreparingBefore()
    {

    }

    private function testSearchingPreparingAfter()
    {

    }

    ///////////////////////////////////
    public function testDownloadFolder($strRemoteFolder, $strLocalFolder = "")
    {

    }

    public function testUploadFolder($strLocalFolder, $strRemoteFolder = "")
    {

    }

    ///////////////////////////////////
    public function testPreparingTransferFolder($strFolder)
    {

    }

    public function testPreparingTransferFolderNB($strFolder)
    {

    }

    ///////////////////////////////////
    public function testSetLocalFolderSize($strLocalFolder)
    {


    }

    public function testSetRemoteFolderSize($strRemoteFolder)
    {

    }

    public function testGetFolderSize()
    {
    }

    ///////////////////////////////////
    public function testSetLocalFolderCountFiles($strLocalFolder)
    {


    }

    public function testSetRemoteFolderCountFiles($strRemoteFolder)
    {


    }

    public function testGetFolderCountFiles()
    {
    }

    ///////////////////////////////////
    public function testSync($strOriginFolder, $strReceiverFolder = "")
    {

    }

    /////////////////////////////////////////////
    public function testClearFullNamesFilesList()
    {
    }

    public function testGetFullNamesFilesList()
    {
    }

    public function testPrintFullNamesFilesList()
    {
    }

    public function testPutToFullNamesFilesList($strFileFullName)
    {
    }

    public function testClearCurrentPathOrigin()
    {
    }

    public function testGetCurrentPathOrigin()
    {
    }

    public function testAddToCurrentPathOrigin($strFolderOrFile)
    {
    }

    public function testRemoveLastElementFormCurrentPathOrigin()
    {
    }



    public function testClearCurrentPathReceiver()
    {
    }

    public function testGetCurrentPathReceiver()
    {
    }

    public function testAddToCurrentPathReceiver($strFolderOrFile)
    {

    }

    public function testRemoveLastElementFormCurrentPathReceiver()
    {

    }


    public function testDisableMkDirLogMode()
    {
    }

    public function testEnableMkDirLogMode()
    {
    }

    public function testIsMkDirLogMode()
    {
    }


    ///////////////////////////////////
    public function testSyncChdirOrigin($strFolder)
    {

    }

    public function testSyncChdirReceiver($strFolder)
    {

    }

    public function testSyncIsDirReceiver($strFolder)
    {

    }

    public function testSyncIsDirOrigin($strFolder)
    {

    }

    public function testSyncMkDir($strFolder)
    {

    }

    public function testSyncNlist($strFolder)
    {

    }

    public function testSyncAction($file, $strActionMethodName)
    {

    }

    public function testSyncTransfer($file)
    {

    }

    public function testSyncFolderTransferNB($file)
    {

    }

    public function testSyncFolderTransfer()
    {

    }

    public function testSyncList($file)
    {

    }

    ///////////////////////////////////
    public function testSyncSwapFileCopy($file)
    {
    }

    public function testSyncSwapFileMove($file)
    {

    }

    public function testSyncSwapFolderCopy($folder)
    {

    }

    public function testSyncSwapFolderMove($folder)
    {

    }
    ///////////////////////////////////
    public function testSyncChdirOriginDownload($strFolder)
    {

    }

    public function testSyncChdirOriginUpload($strFolder)
    {

    }

    public function testSyncChdirReceiverDownload($strFolder)
    {

    }

    public function testSyncChdirReceiverUpload($strFolder)
    {

    }

    public function testSyncIsDirReceiverDownload($strFolder)
    {

    }

    public function testSyncIsDirReceiverUpload($strFolder)
    {

    }

    public function testSyncIsDirOriginDownload($strFolder)
    {

    }

    public function testSyncIsDirOriginUpload($strFolder)
    {

    }

    public function testSyncMkDirDownload($strFolder)
    {

    }

    public function testSyncMkDirUpload($strFolder)
    {

    }

    public function testSyncNlistDownload($strFolder)
    {

    }

    public function testSyncNlistUpload($strFolder)
    {

    }

    public function testSyncTransferDownload($file)
    {
    }

    public function testSyncTransferUpload($file)
    {
    }

    public function testSyncFileTransferNB($file, $strDirection)
    {
    }

    public function testSyncFileTransfer($file, $strDirection)
    {
    }

    public function testGetSyncFileTransferDirectionGetPercentProgress($localFile, $remoteFile)
    {

    }

    public function testGetSyncFileTransferDirectionPutPercentProgress($remoteFile, $localFile)
    {

    }

    public function testGetSyncFileTransferDirectionGetSizeProgress($localFile, $remoteFile)
    {

    }

    public function testGetSyncFileTransferDirectionPutSizeProgress($remoteFile, $localFile)
    {

    }

    public function testGetRemoteFileSize($remoteFile)
    {

    }

    public function testGetLocalFileSize($localFile)
    {

    }

    public function testHumanFilesize($bytes, $decimals = 2) {

    }

    ///////////////////////////////////

    private function testIsDir($dir)
    {

    }

    public function testFileExistGet($strFileName)
    {

    }

    public function testFileExistPut($strFileName)
    {

    }

    ///////////////////////////////////
    public function testCd($strDirectory)
    {

    }

    public function testDownload($strRemoteFile)
    {

    }

    public function testUpload($strLocalFile)
    {

    }

    /////////////////////////////////////////////
    public function testSetOverwriteMode($strOverwriteMode)
    {

    }

    public function testGetOverwriteMode()
    {
    }

    public function testSetDefaultOverwriteMode()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function testSetAction($strAction)
    {

    }

    public function testGetAction()
    {
    }

    public function testSetDefaultAction()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function testSetDirection($strDirection)
    {

    }

    public function testGetDirection()
    {
    }

    public function testSetDefaultDirection()
    {
    }


    /////////////////////////////////////////////
    public function testSetSearchingFilesFilter($strRegex)
    {

    }

    public function testGetSearchingFilesFilter()
    {
    }

    public function testUnsetSearchingFilesFilter()
    {
    }

    public function testIsSearchingFilesFilter()
    {

    }

    public function testIsTriggeringSearchingFilesFilter($file)
    {

    }


    /////////////////////////////////////////////
    public function testSetRecursively($isRecursively)
    {

    }

    public function testIsRecursively()
    {

    }

    public function testSetDefaultRecursively()
    {

    }

    //////////////////////////////////////////////
    public function testTransferSkip()
    {

    }

    public function testTransferRename()
    {

    }

    public function testTransferOverwrite()
    {

    }

    //////////////////////////////////////////////
    public function testCallArgumentsStashing()
    {

    }

    public function testCallArgumentsUnstashing()
    {

    }


}