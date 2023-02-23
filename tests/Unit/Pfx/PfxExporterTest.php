<?php

declare(strict_types=1);

namespace PhpCfdi\Credentials\Tests\Unit\Pfx;

use PhpCfdi\Credentials\Credential;
use PhpCfdi\Credentials\Pfx\PfxExporter;
use PhpCfdi\Credentials\Pfx\PfxReader;
use PhpCfdi\Credentials\Tests\TestCase;

class PfxExporterTest extends TestCase
{
    public function testExport(): void
    {
        $credential = Credential::openFiles(
            $this->filePath('CSD01_AAA010101AAA/certificate.cer'),
            $this->filePath('CSD01_AAA010101AAA/private_key_protected.key.pem'),
            trim($this->fileContents('CSD01_AAA010101AAA/password.txt'))
        );
        $pfxExporter = new PfxExporter($credential);
        $this->assertInstanceOf(PfxExporter::class, $pfxExporter);
        $derPfx = $pfxExporter->export('');
        $this->assertSame(
            PfxReader::convertDerToPem(
                $this->fileContents('CSD01_AAA010101AAA/certificate.pfx')
            ),
            PfxReader::convertDerToPem($derPfx)
        );
    }

    public function testExportToFile(): void
    {
        $credential = Credential::openFiles(
            $this->filePath('CSD01_AAA010101AAA/certificate.cer'),
            $this->filePath('CSD01_AAA010101AAA/private_key_protected.key.pem'),
            trim($this->fileContents('CSD01_AAA010101AAA/password.txt'))
        );
        $pfxExporter = new PfxExporter($credential);
        $this->assertInstanceOf(PfxExporter::class, $pfxExporter);
        /** @var string $name */
        $name = tempnam('', '');
        $created = $pfxExporter->exportToFile($name, '');
        $this->assertTrue($created);
        $this->assertInstanceOf(
            Credential::class,
            PfxReader::openFile($name, '')
        );
    }
}