<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use Denisok94\SymfonyExportXlsxBundle\Exception\ExportException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportBaseInterface;
use Denisok94\SymfonyExportXlsxBundle\Export\TableExport;
use Denisok94\SymfonyExportXlsxBundle\Export\ExportInterface;
use Denisok94\SymfonyExportXlsxBundle\Service\XlsxService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

abstract class BaseExport implements ExportBaseInterface
{
    public const CSV = 'csv';
    public const XLS = 'xls';
    public const XLSX = 'xlsx';
    public const EXPORTS = [self::CSV, self::XLS, self::XLSX];
    public const TABLE_EXPORTS = [self::CSV, self::XLS, self::XLSX];

    public string $type;
    public string $fileName = 'export';

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportType(): array
    {
        return self::EXPORTS;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportService(): ExportInterface
    {
        if (in_array($this->type, self::TABLE_EXPORTS)) {
            return new TableExport();
        }
        throw new ExportException('api.export.error.format');
    }

    /**
     * {@inheritdoc}
     */
    public function preCallback(ExportInterface $export): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function preCallbackItem($item, $export, $i): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($line): ItemExport
    {
        $item = [];
        try {
            $this->parse($item, $line);
        } catch (\Throwable $th) {
            throw new ExportException($th->getMessage());
        }
        return (new ItemExport())->setPageName('Лист 1')->setPageHeaders(array_keys($item))->setPageData($item);
    }

    /**
     * @param array $array
     * @param array $line
     */
    public function parse(&$array, $line): void
    {
        foreach ($line as $key => $value) {
            if ($value instanceof RichText) {
                $array[$key] = $value;
            } elseif (!is_object($value) && !is_array($value)) {
                $array[$key] = $value;
            } elseif (is_object($value) || is_array($value)) {
                $this->parse($array, $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postCallbackItem($item, $export, $i): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function callback($export): void
    {
        if ($export instanceof XlsxService) {
            $worksheet = $export->getActiveSheet();
            $x = $y = 1;
            while ($worksheet->getCell([$y, 1])->getValue() != null) {
                $y++;
            }
            while ($worksheet->getCell([1, $x])->getValue() != null) {
                $x++;
            }
            $cell = $worksheet->getStyle([1, 1, $y - 1, 1]);
            $cell->getBorders()->applyFromArray([
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['argb' => 'f2f2f2']
                ]
            ]);
            $cell->getFont()->applyFromArray([
                'italic' => true,
            ]);
            $cell->getFill()->applyFromArray([
                'fillType' => 'solid',
                'startColor' => ['argb' => 'f2f2f2']
            ]);
            $cell2 = $worksheet->getStyle([1, 1, $y - 1, $x - 1]);
            $cell2->getAlignment()->setWrapText(true);
            $worksheet->setSelectedCell('A1');
        }
    }
}
