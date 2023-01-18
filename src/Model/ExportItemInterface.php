<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

interface ExportItemInterface
{
    /**
     * Задать название таблицы
     * @return string
     */
    public function getPageName(): string;

    /**
     * @param string $pageName 
     * @return self
     */
    public function setPageName(string $pageName): self;

    /**
     * Заголовки страницы
     * @return mixed
     */
    public function getPageHeaders(): mixed;

    /**
     * @param mixed $pageHeaders 
     * @return self
     */
    public function setPageHeaders($pageHeaders): self;

    /**
     * Данные страницы
     * @return mixed
     */
    public function getPageData(): mixed;

    /**
     * @param mixed $pageData 
     * @return self
     */
    public function setPageData($pageData): self;
}
