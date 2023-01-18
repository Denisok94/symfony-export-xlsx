<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use RuntimeException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;
use Denisok94\SymfonyExportXlsxBundle\Service\ExportServiceInterface;

interface ExportBaseInterface
{
  /**
   * Массив поддерживаемых типов экспорта
   * @return array
   * @throws RuntimeException
   */
  public function getExportType(): array;

  /**
   * Класс сервиса экспорта
   * @return ExportServiceInterface
   * @throws RuntimeException
   */
  public function getExportService(): ExportServiceInterface;

  /**
   * Тип/формат выбранного экспорта
   * @param string $type 
   * @return self
   * @throws RuntimeException
   */
  public function setType(string $type): self;

  /**
   * Начальное название файла
   * @return string
   * @throws RuntimeException
   */
  public function getFileName(): string;

  /**
   * Какие-то исходные данные
   * @param mixed $data массив готовых данных
   * @return array
   * @throws RuntimeException
   */
  public function setRawData($data): array;

  /**
   * Готовые данные для вставки в файл экспорта
   * @param mixed $item 
   * @return ExportItemInterface
   * @throws RuntimeException
   */
  public function getItem($item): ExportItemInterface;

  /**
   * 
   * @param mixed $item
   * @param mixed $result
   * @throws RuntimeException
   */
  public function preCallbackItem($item, $result): void;

  /**
   * 
   * @param mixed $item
   * @param mixed $result
   * @throws RuntimeException
   */
  public function postCallbackItem($item, $result): void;

  /**
   * Получить готовый объект/файл полученного экспорта
   * @param mixed $result
   * @throws RuntimeException
   */
  public function callback($result): void;
}
