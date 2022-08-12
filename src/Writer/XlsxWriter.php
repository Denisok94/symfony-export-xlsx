<?php

namespace Denisok94\SymfonyExportXlsxBundle\Writer;

use \Denisok94\SymfonyExportXlsxBundle\Service\XlsxService;
use Sonata\Exporter\Writer\TypedWriterInterface;

/**
 * Class XlsxWriter
 * 
 * @link https://github.com/Denisok94/symfony-export-xlsx
 * @package Denisok94\SymfonyExportXlsxBundle\Writer
 */
class XlsxWriter extends XlsxService implements TypedWriterInterface
{
    /**
     * @param string $file
     * @throws \RuntimeException
     */
    public function __construct(string $file)
    {
        if (is_file($file)) {
            throw new \RuntimeException(sprintf('The file %s already exists', $file));
        }
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return 'xlsx';
    }
    
    public function write(array $data)
    {
        $this->init($data);

        foreach ($data as $header => $value) {
            $this->setCellValue($this->getColumn($header), $value);
        }

        ++$this->position;
    }
}
