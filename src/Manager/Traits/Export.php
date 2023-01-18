<?php

namespace Denisok94\SymfonyExportXlsxBundle\Manager\Traits;

use RuntimeException;
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
     * @throws RuntimeException
     */
    public function export(string $type, $data, ExportBaseInterface $exportClass): array
    {
        if (in_array($type, $exportClass->getExportType())) {
            $exportService = $exportClass->setType($type)->getExportService();
            $items = $exportClass->setRawData($data);
            $exportService->setType($type)->setExportClass($exportClass)->start();

            foreach ($items as $item) {
                $exportClass->preCallbackItem($item, $exportService->getResult());
                $exportService->parse($exportClass->getItem($item));
                $exportClass->postCallbackItem($item, $exportService->getResult());
            }
            $exportClass->callback($exportService->getResult());
            $exportService->end();
            return [$exportService->getFile(), $exportService->getFileName()];
        }
        throw new RuntimeException('api.export.error.type');
    }
}
