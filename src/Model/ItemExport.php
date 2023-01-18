<?php

namespace Denisok94\SymfonyExportXlsxBundle\Model;

use Denisok94\SymfonyExportXlsxBundle\Model\ExportItemInterface;

/**
 * Class ItemExport
 * @package Denisok94\SymfonyExportXlsxBundle\Model
 */
class ItemExport implements ExportItemInterface
{
    public string $pageName;
    public $pageData;
    public $pageHeaders;

    /**
     * Get the value of pageData
     * @return mixed
     */
    public function getPageData(): mixed
    {
        return $this->pageData;
    }

    /**
     * Set the value of pageData
     * @param mixed $pageData 
     * @return self
     */
    public function setPageData($pageData): self
    {
        $this->pageData = $pageData;
        return $this;
    }

    /**
     * Get the value of pageHeaders
     * @return mixed
     */
    public function getPageHeaders(): mixed
    {
        return $this->pageHeaders;
    }

    /**
     * Set the value of pageHeaders
     * @param mixed $pageHeaders 
     * @return self
     */
    public function setPageHeaders($pageHeaders): self
    {
        $this->pageHeaders = $pageHeaders;
        return $this;
    }

    /**
     * Get the value of pageName
     * @return string
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * Set the value of pageName
     * @param string $pageName 
     * @return self
     */
    public function setPageName(string $pageName): self
    {
        $this->pageName = $pageName;
        return $this;
    }
}
