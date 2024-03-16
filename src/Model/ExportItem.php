<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;

/**
 * Class ExportItem (test)
 * @package Denisok94\SymfonyExportXlsxBundle\Model
 */
class ExportItem implements ExportItemInterface
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
     *  'Ivanov' => [x,y],
     *  '32' => [x,y]
     * ];
     * ```
     */
    public array $pageData = [];

    /**
     * Заголовки
     * @var array
     * ```php
     * $headers = [
     *  'name' => [x,y],,
     *  'value' => [x,y],
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
