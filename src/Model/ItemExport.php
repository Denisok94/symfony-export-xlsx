<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;

/**
 * Class ItemExport
 * @package Denisok94\SymfonyExportXlsxBundle\Model
 */
class ItemExport implements ExportItemInterface
{
    /**
     * Название страницы
     * @var string
     */
    public string $pageName = '';

    /**
     * Массив с данными
     * @var array
     * ```php
     * $item = [
     *  'name' => 'Ivanov',
     *  'value' => 32
     * ];
     * ```
     */
    public array $pageData = [];

    /**
     * Список заголовков столбцов 
     * @var array
     * ```php
     * $headers = [
     *  'name',
     *  'value',
     * ];
     * ```
     */
    public array $pageHeaders = [];

    /**
     * {@inheritdoc}
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageName(string $pageName): self
    {
        $this->pageName = $pageName;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageData(): array
    {
        return $this->pageData;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageData($pageData): self
    {
        $this->pageData = $pageData;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageHeaders(): array
    {
        return $this->pageHeaders;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageHeaders($pageHeaders): self
    {
        $this->pageHeaders = $pageHeaders;
        return $this;
    }
}
