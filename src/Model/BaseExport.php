<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use RuntimeException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportBaseInterface;
use Denisok94\SymfonyExportXlsxBundle\Service\TableService;
use Denisok94\SymfonyExportXlsxBundle\Service\ExportServiceInterface;
use Denisok94\SymfonyExportXlsxBundle\Service\XlsxService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    public function getExportService(): ExportServiceInterface
    {
        if (in_array($this->type, self::TABLE_EXPORTS)) {
            return new TableService();
        }
        throw new RuntimeException('api.export.error.format');
    }

    /**
     * {@inheritdoc}
     */
    public function preCallbackItem($item, $result): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postCallbackItem($item, $result): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function callback($result): void
    {
        if ($result instanceof XlsxService) {
            $worksheet = $result->getActiveSheet();
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
