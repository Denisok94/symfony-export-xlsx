<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use Denisok94\SymfonyExportXlsxBundle\Exception\ExportException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;
use Denisok94\SymfonyExportXlsxBundle\Export\ExportInterface;

interface ExportBaseInterface
{
  /**
   * Массив поддерживаемых типов экспорта
   * @return array
   * @throws ExportException
   */
  public function getExportType(): array;

  /**
   * Класс сервиса экспорта
   * @return ExportInterface
   * @throws ExportException
   */
  public function getExportService(): ExportInterface;

  /**
   * Тип/формат выбранного экспорта
   * @param string $type 
   * @return self
   * @throws ExportException
   */
  public function setType(string $type): self;

  /**
   * Начальное название файла
   * @return string
   * @throws ExportException
   */
  public function getFileName(): string;

  /**
   * Какие-то исходные данные
   * @param mixed $data массив готовых данных
   * @return array
   * @throws ExportException
   */
  public function setRawData($data): array;
  
  /**
   * исходные данные
   * @return mixed
   * @throws ExportException
   */
  public function getRawData(): mixed;

  /**
   * Сколько будет данных
   * @return integer
   * @throws ExportException
   */
  public function getCountItems(): int;

  /**
   * Готовые данные для вставки в файл экспорта
   * @param mixed $line 
   * @return ExportItemInterface
   * @throws ExportException
   */
  public function getItem($line): ExportItemInterface;

  /**
   * Подготовка к экспорту
   * @param ExportItemInterface $export объект экспорта
   * @throws ExportInterface
   */
  public function preCallback(ExportInterface $export): void;

  /**
   * @param mixed $line
   * @param ExportInterface $export объект экспорта
   * @param int $i
   * @throws ExportException
   */
  public function preCallbackItem($line, $export, $i): void;

  /**
   * @param mixed $line
   * @param ExportInterface $export объект экспорта
   * @param int $i
   * @throws ExportException
   */
  public function postCallbackItem($line, $export, $i): void;

  /**
   * Получить готовый объект/файл полученного экспорта
   * @param ExportInterface $export объект экспорта
   * @throws ExportException
   */
  public function callback($export): void;
}
