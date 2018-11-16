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

    public function testSetConnectionParameters()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );
        $connFTP->setConnectionParameters(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3]
        );

        $this->assertEquals($connFTP->getConnectionType(), $this->arFTPConnectionSettings[0]);
        $this->assertEquals($connFTP->getConnectionUser(), $this->arFTPConnectionSettings[1]);
        $this->assertEquals($connFTP->getConnectionPass(), $this->arFTPConnectionSettings[2]);
        $this->assertEquals($connFTP->getConnectionHostname(), $this->arFTPConnectionSettings[3]);

    }

    ///////////////////////////////////////////////////////////////////

    public function testEnablePassiveConnectionMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $this->assertTrue($connFTP->enablePassiveConnectionMode());
        $this->assertTrue($connFTP->isPassiveMode());
    }

    public function testDisablePassiveConnectionMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $this->assertFalse($connFTP->disablePassiveConnectionMode());
        $this->assertFalse($connFTP->isPassiveMode());
    }

    public function testSetDefaultConnectionMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $connFTP->setDefaultConnectionMode();
        $this->assertTrue($connFTP->isPassiveMode());
    }

    ///////////////////////////////////////////////////////////////////

    public function testSetBinaryTransferMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $this->assertEquals($connFTP->setBinaryTransferMode(), \FTP_BINARY);
        $this->assertEquals($connFTP->getTransferMode(), \FTP_BINARY);
    }

    public function testSetTextualTransferMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $this->assertEquals($connFTP->setTextualTransferMode(), \FTP_ASCII);
        $this->assertEquals($connFTP->getTransferMode(), \FTP_ASCII);
    }

    public function testSetDefaultTransferMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $connFTP->setDefaultTransferMode();
        $this->assertEquals($connFTP->getTransferMode(), \FTP_BINARY);
    }


    ///////////////////////////////////////////////////////////////////

    public function testSetSwapMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $connFTP->setSwapMode("copy");

        $this->assertEquals($connFTP->getSwapMode(), "copy");

    }

    public function testSetDefaultSwapMode()
    {
        $factory = new FT\Factory();
        $connFTP = $factory->getConnection(
            $this->arFTPConnectionSettings[0],
            $this->arFTPConnectionSettings[1],
            $this->arFTPConnectionSettings[2],
            $this->arFTPConnectionSettings[3],
            $this->arFTPConnectionSettings[4]
        );

        $connFTP->setDefaultSwapMode();
        $this->assertEquals($connFTP->getSwapMode(), "copy");
    }

    ///////////////////////////////////////////////////////////////////



}