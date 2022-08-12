<?php

namespace Denisok94\SymfonyExportXlsxBundle\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class XlsxService
 * 
 * ```php
 * $fileName = 'my_first_excel_symfony4.xlsx';
 * $temp_file = tempnam(sys_get_temp_dir(), $fileName);
 * $export->setFile($temp_file)->open();
 * $test = [
 *  ['header1' => 'value1','header2' => 'value2','header3' => 'value3'], 
 *  ['header1' => 'value3','header2' => 'value4','header3' => 'value5']
 * ];
 * foreach ($test as $line) {$export->write($line);}
 * $export->close();
 * return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
 * ```
 * @link https://github.com/Denisok94/symfony-export-xlsx
 * @package Denisok94\SymfonyExportXlsxBundle\Service
 */
class XlsxService
{
    protected const LABEL_COLUMN = 1;
    private const DATE_PARTS = [
        'd' => 'D',
        'm' => 'M',
        'y' => 'Y',
    ];
    private const TIME_PARTS = [
        'h' => 'H',
        'i' => 'M',
        's' => 'S',
    ];

    /** @var string default DateTime format */
    protected $dateTimeFormat;
    /** @var  Spreadsheet */
    protected $phpExcelObject;
    /** @var array */
    protected $headerColumns = [];
    /** @var string */
    protected $file;
    /** @var int */
    protected $position = 2;

    /**
     * @param string $dateTimeFormat
     */
    public function __construct(string $dateTimeFormat = 'r')
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @param string $file
     * @return self
     * 
     * ```php
     * $fileName = 'my_first_excel_symfony4.xlsx';
     * $temp_file = tempnam(sys_get_temp_dir(), $fileName);
     * $export->setFile($temp_file);
     * ```
     */
    public function setFile(string $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @param string $dateTimeFormat  ~'Y-m-d H:i:s'
     * @return self
     */
    public function setDateTimeFormat(string $dateTimeFormat): self
    {
        $this->dateTimeFormat = $dateTimeFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    //-------------------------------------

    /**
     * Create Excel object and set defaults
     */
    public function open()
    {
        $this->phpExcelObject = new Spreadsheet();
    }

    /**
     *
     * @param array $data
     * 
     * ```php
     * $test = [[
     *   'header1' => 'value1',
     *   'header2' => 'value2',
     *   'header3' => 'value3',
     *  ], [
     *   'header1' => 'value3',
     *   'header2' => 'value4',
     *   'header3' => 'value5',
     *  ]];
     * foreach ($test as $line) {$export->write($line);}
     * ```
     */
    public function write(array $data)
    {
        $this->init($data);

        foreach ($data as $header => $value) {
            $this->setCellValue($this->getColumn($header), $this->getValue($value));
        }

        ++$this->position;
    }

    /**
     * Set labels
     * @param mixed $data
     *
     * @return void
     */
    protected function init($data)
    {
        if ($this->position > 2) {
            return;
        }
        $i = 0;
        foreach ($data as $header => $value) {
            $column = self::formatColumnName($i);
            $this->setHeader($column, $header);
            $i++;
        }

        $this->setBoldLabels();
    }

    /**
     * Save Excel file
     * ```php
     * $export->close();
     * return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
     * ```
     */
    public function close()
    {
        $writer = IOFactory::createWriter($this->phpExcelObject, 'Xlsx');
        $writer->save($this->file);
    }

    //-------------------------------------

    /**
     * {@inheritdoc}
     * @link https://phpoffice.github.io/PhpSpreadsheet/namespaces/phpoffice-phpspreadsheet.html
     */
    public function getSpreadsheet()
    {
        return $this->phpExcelObject;
    }

    /**
     * {@inheritdoc}
     * @link https://phpoffice.github.io/PhpSpreadsheet/namespaces/phpoffice-phpspreadsheet-worksheet.html
     */
    public function getActiveSheet()
    {
        return $this->phpExcelObject->getActiveSheet();
    }

    /**
     * {@inheritdoc}
     * @link https://phpoffice.github.io/PhpSpreadsheet/classes/PhpOffice-PhpSpreadsheet-Document-Properties.html
     */
    public function getProperties()
    {
        return $this->phpExcelObject->getProperties();
    }

    //-------------------------------------

    /**
     * Returns letter for number based on Excel columns
     * @param int $number
     * @return string
     */
    protected static function formatColumnName(int $number): string
    {
        for ($char = ""; $number >= 0; $number = intval($number / 26) - 1) {
            $char = chr($number % 26 + 0x41) . $char;
        }
        return $char;
    }

    /**
     * Makes header bold
     */
    protected function setBoldLabels()
    {
        $this->getActiveSheet()->getStyle(
            sprintf(
                "%s1:%s1",
                reset($this->headerColumns),
                end($this->headerColumns)
            )
        )->getFont()->setBold(true);
    }

    /**
     * Sets cell value
     * @param string $column
     * @param string $value
     */
    protected function setCellValue($column, $value)
    {
        $this->getActiveSheet()->setCellValue($column, $value);
    }

    /**
     * Set column label and make column auto size
     * @param string $column
     * @param string $value
     */
    protected function setHeader($column, $value)
    {
        $this->setCellValue($column . self::LABEL_COLUMN, $value);
        $this->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        $this->headerColumns[$value] = $column;
    }

    /**
     * Get column name
     * @param string $name
     * @return string
     */
    protected function getColumn($name)
    {
        return $this->headerColumns[$name] . $this->position;
    }

    /**
     * @param mixed $value
     *
     * @return bool|int|float|string|null
     */
    private function getValue($value)
    {
        switch (true) {
            case \is_array($value):
                return '[' . implode(', ', array_map([$this, 'getValue'], $value)) . ']';
            case $value instanceof \Traversable:
                return '[' . implode(', ', array_map([$this, 'getValue'], iterator_to_array($value))) . ']';
            case $value instanceof \DateTimeInterface:
                return $value->format($this->dateTimeFormat);
            case $value instanceof \DateInterval:
                return $this->getDuration($value);
            case \is_object($value):
                return method_exists($value, '__toString') ? (string) $value : null;
            default:
                return $value;
        }
    }

    /**
     * NEXT_MAJOR: Change the method visibility to private.
     *
     * @return string An ISO8601 duration
     */
    private function getDuration(\DateInterval $interval): string
    {
        $datePart = '';
        foreach (self::DATE_PARTS as $datePartAttribute => $datePartAttributeString) {
            if ($interval->$datePartAttribute !== 0) {
                $datePart .= $interval->$datePartAttribute . $datePartAttributeString;
            }
        }

        $timePart = '';
        foreach (self::TIME_PARTS as $timePartAttribute => $timePartAttributeString) {
            if ($interval->$timePartAttribute !== 0) {
                $timePart .= $interval->$timePartAttribute . $timePartAttributeString;
            }
        }

        if ('' === $datePart && '' === $timePart) {
            return 'P0Y';
        }

        return 'P' . $datePart . ('' !== $timePart ? 'T' . $timePart : '');
    }
}
