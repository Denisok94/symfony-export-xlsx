<?php

namespace Denisok94\SymfonyExportXlsxBundle\Service;

use RuntimeException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportBaseInterface;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;

/**
 * interface ExportServiceInterface
 * @package Denisok94\SymfonyExportXlsxBundle\Service
 */
interface ExportServiceInterface
{
  /**
   * Предварительная подготовка, создание файла экспорта
   * @return self
   * @throws RuntimeException
   */
  public function start(): self;

  /**
   * Добавить данные в экспорт
   * @param ExportItemInterface $item
   * @throws RuntimeException
   */
  public function parse(ExportItemInterface $item);
  
  /**
   * Объект/файл полученного экспорта
   * @return mixed
   */
  public function getResult(): mixed;

  /**
   * Завершить/закрыть экспорта
   * @throws RuntimeException
   */
  public function end();

  //----------

  /**
   * Тип/формат экспорта
   * @param string $type 
   * @return self
   * @throws RuntimeException
   */
  public function setType(string $type): self;

  /**
   * Класс экспорта
   * @param ExportBaseInterface $exportClass 
   * @return self
   */
  public function setExportClass(ExportBaseInterface $exportClass): self;

  //----------

  /**
   * Имя полученного файла
   * @return string
   */
  public function getFileName(): string;

  /**
   * Сам файл экспорта
   * @return mixed
   */
  public function getFile(): mixed;
}
