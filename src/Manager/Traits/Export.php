<?php

namespace Denisok94\SymfonyExportXlsxBundle\Manager\Traits;

use Denisok94\SymfonyExportXlsxBundle\Exception\ExportException;
use Denisok94\SymfonyExportXlsxBundle\Model\ExportBaseInterface;

/**
 *
 */
trait Export
{
    /**
     * Экспорт данных
     * @param string $type
     * @param mixed $data
     * @param ExportBaseInterface $exportClass
     * @return array [file, name]
     * @throws ExportException
     */
    public function export(string $type, $data, ExportBaseInterface $exportClass): array
    {
        if (in_array($type, $exportClass->getExportType())) {
            $exportService = $exportClass->setType($type)->getExportService();
            $items = $exportClass->setRawData($data);
            $exportService->setType($type)->setExportClass($exportClass)->start();

            $exportClass->preCallback($exportService);
            foreach ($items as $i => $item) {
                $exportClass->preCallbackItem($item, $exportService->getResult(), $i);
                $exportService->parse($exportClass->getItem($item), $i);
                $exportClass->postCallbackItem($item, $exportService->getResult(), $i);
            }
            $exportClass->callback($exportService->getResult());
            $exportService->end();
            return [$exportService->getFile(), $exportService->getFileName()];
        }
        throw new ExportException('api.export.error.type');
    }
}
