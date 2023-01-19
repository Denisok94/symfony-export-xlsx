<?php

namespace Denisok94\SymfonyExportXlsxBundle\Export;

use Denisok94\SymfonyExportXlsxBundle\Exception\ExportException;
use Denisok94\SymfonyExportXlsxBundle\Service\XlsxService;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportBaseInterface;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;

/**
 * Class TableExport
 * @package Denisok94\SymfonyExportXlsxBundle\Export
 */
class TableExport implements ExportInterface
{
    const CSV = 'csv';
    const XLS = 'xls';
    const XLSX = 'xlsx';
    const EXPORTS = [self::CSV, self::XLS, self::XLSX];

    /** @var ExportInterface */
    private $exportClass;
    /**  @var XlsxService|resource */
    private $export;
    /** @var string */
    private $type;
    /** @var string */
    private $fileName;
    private $file;

    /**
     * @return self
     * @throws ExportException
     */
    public function start(): self
    {
        $date = (new \DateTime())->format('d.m.Y_H.i.s');
        $fileName = $date . '.' . $this->type;
        $tempFile = tempnam(sys_get_temp_dir(), 'temp_export_' . $fileName);
        // start
        $export = '';
        switch ($this->type) {
            case self::CSV:
                $export = fopen($tempFile, 'w');
                fputs($export, chr(0xEF) . chr(0xBB) . chr(0xBF));
                break;
            case self::XLS:
                $export = new XlsxService();
                $export->setFile($tempFile)->open();
                break;
            case self::XLSX:
                $export = new XlsxService();
                $export->setFile($tempFile)->open();
                break;
        }
        $this->fileName = $fileName;
        $this->file = $tempFile;
        $this->export = $export;
        return $this;
    }

    public function addPages($name)
    {
        $id = count($this->pages);
        $this->pages[$name] = [
            'id' => $id,
            'name' => $name,
            'items' => 0,
            'headers' => [],
            'item' => [],
        ];
        if (in_array($this->type, [self::XLS, self::XLSX])) {
            if ($id > 0) {
                $this->export->position = 2;
                $this->export->getSpreadsheet()->createSheet($id);
                $this->export->getSpreadsheet()->setActiveSheetIndex($id);
            }
            $this->export->getActiveSheet()->setTitle($name);
        }
    }

    private $pages = [];

    /**
     * @param ExportItemInterface $item
     * @param int $i
     * @throws ExportException
     */
    public function parse(ExportItemInterface $item, $i)
    {
        if (!is_array($item->getPageHeaders()) or !is_array($item->getPageData())) {
            throw new \Exception("perhaps, getPageData and/or getPageHeaders return not array: ");
        }
        $name = $item->getPageName();
        if (!isset($this->pages[$name])) {
            $this->addPages($name);
            $this->pages[$name]['headers'] = $item->getPageHeaders();
        }
        $this->pages[$name]['item'] = $item->getPageData();

        $this->addInExport($this->pages[$name]);

        $this->pages[$name]['items'] = $this->pages[$name]['items'] + 1;
    }

    /**
     * @param array $pages
     * @throws ExportException|\Exception
     */
    public function addInExport(array $pages)
    {
        $i = $pages['items'];
        $line = $pages['item'];
        $headers = $pages['headers'];
        switch ($this->type) {
            case self::CSV:
                if ($i == 0) {
                    $id = count($this->pages);
                    if ($id > 1) fputcsv($this->export, ['sheet', $pages['name']], ';');
                    fputcsv($this->export, $headers, ';');
                }
                fputcsv($this->export, $line, ';');
                break;
            case self::XLS:
                $this->export->write($line);
                break;
            case self::XLSX:
                $this->export->write($line);
                break;
        }
    }

    /**
     * @return XlsxService|resource
     */
    public function getResult(): mixed
    {
        return $this->export;
    }

    /**
     * @throws ExportException
     */
    public function end()
    {
        switch ($this->type) {
            case self::CSV:
                fclose($this->export);
                break;
            case self::XLS:
                $this->export->close();
                break;
            case self::XLSX:
                $this->export->close();
                break;
        }
    }

    /**
     * Set the value of type
     * @param string $type 
     * @return self
     * @throws ExportException
     */
    public function setType(string $type): self
    {
        if (in_array($type, self::EXPORTS)) {
            $this->type = $type;
            return $this;
        }
        throw new ExportException('api.export.error.type');
    }

    /**
     * Set the value of exportClass
     * @param ExportBaseInterface $exportClass 
     * @return self
     */
    public function setExportClass(ExportBaseInterface $exportClass): self
    {
        $this->exportClass = $exportClass;
        return $this;
    }

    /**
     * Get the value of fileName
     * @return string
     */
    public function getFileName(): string
    {
        return $this->exportClass->getFileName() . "_" . $this->fileName;
    }

    /**
     * Get the value of file
     * @return mixed
     */
    public function getFile(): mixed
    {
        return $this->file;
    }
}
