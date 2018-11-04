<?php

namespace FileTransfer\Protocol\Transfer;

use PHPUnit\Framework\TestCase;
use FileTransfer as FT;

class TransferTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new FT\Factory();
        $this->assertInstanceOf(FT\Factory::class, $factory);
    }

    public function testSetFolderCurrentSize()
    {

    }

    public function testSetFolderCurrentCountFiles()
    {

    }


    public function SetFolderDefaultCurrentSize()
    {

    }

    public function SetFolderDefaultCurrentCountFiles()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function AddToCache($data, $arTags)
    {

    }

    public function CheckCache($arTags)
    {

    }

    public function ClearAllCache()
    {
    }

    public function ClearCacheByTag($arTags)
    {

    }

    public function GetCacheTags($arAdditionalsTags)
    {

    }


    ///////////////////////////////////////////////////////////////////
    public function PrepareArguments()
    {

    }

    public function CheckIsTransferFunction()
    {
    }


    public function CheckIsHightLevelFunction()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function SetDefaultParameters()
    {

    }

    ///////////////////////////////////////////////////////////////////

    public function SetConnectionParameters()
    {

    }

    public function GetConnectionType()
    {
    }

    public function GetConnectionUser()
    {
    }

    public function GetConnectionPass()
    {

    }

    public function GetConnectionHostname()
    {
    }
    ///////////////////////////////////////////////////////////////////

    public function EnablePassiveConnectionMode()
    {
    }

    public function DisablePassiveConnectionMode()
    {
    }

    public function IsPassiveMode()
    {
    }

    public function SetDefaultConnectionMode()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function SetBinaryTransferMode()
    {
    }

    public function SetTextualTransferMode()
    {
    }

    public function GetTransferMode()
    {
    }

    public function SetDefaultTransferMode()
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function SetSwapMode($strSwapMode)
    {


    }

    public function GetSwapMode()
    {
    }

    public function SetDefaultSwapMode()
    {

    }

    ///////////////////////////////////////////////////////////////////

    public function EnableShowProgress()
    {
    }

    public function DisableShowProgress()
    {
    }

    public function GetShowProgress()
    {
    }

    public function SetDefaultShowProgress()
    {

    }

    public function GetCurrentShowProgressPrefix()
    {

    }


    ///////////////////////////////////////////////////////////////////

    public function Search(
    ) {


    }

    ///////////////////////////////////
    private function ActionListPreparingBeforeSync()
    {

    }

    private function ActionListPreparingAfterSync()
    {

    }

    private function ActionListSync($strFolder)
    {

    }

    private function ActionTransferPreparingBeforeSync()
    {

    }

    private function ActionTransferPreparingAfterSync()
    {

    }

    private function ActionTransferSync()
    {

    }


    ///////////////////////////////////

    private function SearchingPreparingBefore()
    {

    }

    private function SearchingPreparingAfter()
    {

    }

    ///////////////////////////////////
    public function DownloadFolder($strRemoteFolder, $strLocalFolder = "")
    {

    }

    public function UploadFolder($strLocalFolder, $strRemoteFolder = "")
    {

    }

    ///////////////////////////////////
    public function PreparingTransferFolder($strFolder)
    {

    }

    public function PreparingTransferFolderNB($strFolder)
    {

    }

    ///////////////////////////////////
    public function SetLocalFolderSize($strLocalFolder)
    {


    }

    public function SetRemoteFolderSize($strRemoteFolder)
    {

    }

    public function GetFolderSize()
    {
    }

    ///////////////////////////////////
    public function SetLocalFolderCountFiles($strLocalFolder)
    {


    }

    public function SetRemoteFolderCountFiles($strRemoteFolder)
    {


    }

    public function GetFolderCountFiles()
    {
    }

    ///////////////////////////////////
    public function Sync($strOriginFolder, $strReceiverFolder = "")
    {

    }

    /////////////////////////////////////////////
    public function ClearFullNamesFilesList()
    {
    }

    public function GetFullNamesFilesList()
    {
    }

    public function PrintFullNamesFilesList()
    {
    }

    public function PutToFullNamesFilesList($strFileFullName)
    {
    }

    public function ClearCurrentPathOrigin()
    {
    }

    public function GetCurrentPathOrigin()
    {
    }

    public function AddToCurrentPathOrigin($strFolderOrFile)
    {
    }

    public function RemoveLastElementFormCurrentPathOrigin()
    {
    }



    public function ClearCurrentPathReceiver()
    {
    }

    public function GetCurrentPathReceiver()
    {
    }

    public function AddToCurrentPathReceiver($strFolderOrFile)
    {

    }

    public function RemoveLastElementFormCurrentPathReceiver()
    {

    }


    public function DisableMkDirLogMode()
    {
    }

    public function EnableMkDirLogMode()
    {
    }

    public function IsMkDirLogMode()
    {
    }


    ///////////////////////////////////
    public function SyncChdirOrigin($strFolder)
    {

    }

    public function SyncChdirReceiver($strFolder)
    {

    }

    public function SyncIsDirReceiver($strFolder)
    {

    }

    public function SyncIsDirOrigin($strFolder)
    {

    }

    public function SyncMkDir($strFolder)
    {

    }

    public function SyncNlist($strFolder)
    {

    }

    public function SyncAction($file, $strActionMethodName)
    {

    }

    public function SyncTransfer($file)
    {

    }

    public function SyncFolderTransferNB($file)
    {

    }

    public function SyncFolderTransfer()
    {

    }

    public function SyncList($file)
    {

    }

    ///////////////////////////////////
    public function SyncSwapFileCopy($file)
    {
    }

    public function SyncSwapFileMove($file)
    {

    }

    public function SyncSwapFolderCopy($folder)
    {

    }

    public function SyncSwapFolderMove($folder)
    {

    }
    ///////////////////////////////////
    public function SyncChdirOriginDownload($strFolder)
    {

    }

    public function SyncChdirOriginUpload($strFolder)
    {

    }

    public function SyncChdirReceiverDownload($strFolder)
    {

    }

    public function SyncChdirReceiverUpload($strFolder)
    {

    }

    public function SyncIsDirReceiverDownload($strFolder)
    {

    }

    public function SyncIsDirReceiverUpload($strFolder)
    {

    }

    public function SyncIsDirOriginDownload($strFolder)
    {

    }

    public function SyncIsDirOriginUpload($strFolder)
    {

    }

    public function SyncMkDirDownload($strFolder)
    {

    }

    public function SyncMkDirUpload($strFolder)
    {

    }

    public function SyncNlistDownload($strFolder)
    {

    }

    public function SyncNlistUpload($strFolder)
    {

    }

    public function SyncTransferDownload($file)
    {
    }

    public function SyncTransferUpload($file)
    {
    }

    public function SyncFileTransferNB($file, $strDirection)
    {
    }

    public function SyncFileTransfer($file, $strDirection)
    {
    }

    public function GetSyncFileTransferDirectionGetPercentProgress($localFile, $remoteFile)
    {

    }

    public function GetSyncFileTransferDirectionPutPercentProgress($remoteFile, $localFile)
    {

    }

    public function GetSyncFileTransferDirectionGetSizeProgress($localFile, $remoteFile)
    {

    }

    public function GetSyncFileTransferDirectionPutSizeProgress($remoteFile, $localFile)
    {

    }

    public function GetRemoteFileSize($remoteFile)
    {

    }

    public function GetLocalFileSize($localFile)
    {

    }

    public function HumanFilesize($bytes, $decimals = 2) {

    }

    ///////////////////////////////////

    private function IsDir($dir)
    {

    }

    public function FileExistGet($strFileName)
    {

    }

    public function FileExistPut($strFileName)
    {

    }

    ///////////////////////////////////
    public function Cd($strDirectory)
    {

    }

    public function Download($strRemoteFile)
    {

    }

    public function Upload($strLocalFile)
    {

    }

    /////////////////////////////////////////////
    public function SetOverwriteMode($strOverwriteMode)
    {

    }

    public function GetOverwriteMode()
    {
    }

    public function SetDefaultOverwriteMode()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function SetAction($strAction)
    {

    }

    public function GetAction()
    {
    }

    public function SetDefaultAction()
    {
    }

    ///////////////////////////////////////////////////////////////////
    public function SetDirection($strDirection)
    {

    }

    public function GetDirection()
    {
    }

    public function SetDefaultDirection()
    {
    }


    /////////////////////////////////////////////
    public function SetSearchingFilesFilter($strRegex)
    {

    }

    public function GetSearchingFilesFilter()
    {
    }

    public function UnsetSearchingFilesFilter()
    {
    }

    public function IsSearchingFilesFilter()
    {

    }

    public function IsTriggeringSearchingFilesFilter($file)
    {

    }


    /////////////////////////////////////////////
    public function SetRecursively($isRecursively)
    {

    }

    public function IsRecursively()
    {

    }

    public function SetDefaultRecursively()
    {

    }

    //////////////////////////////////////////////
    public function TransferSkip()
    {

    }

    public function TransferRename()
    {

    }

    public function TransferOverwrite()
    {

    }

    //////////////////////////////////////////////
    public function CallArgumentsStashing()
    {

    }

    public function CallArgumentsUnstashing()
    {

    }


}