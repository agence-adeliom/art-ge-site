<?php

declare(strict_types=1);

namespace App\EventListener\Admin;

use App\Controller\Admin\TerritoireCrudController;
use App\Entity\Territoire;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

#[AsEventListener(event: BeforeCrudActionEvent::class, method: 'setFileDataInRequestAttribute')]
#[AsEventListener(event: BeforeEntityUpdatedEvent::class, method: 'updateEntityWithRequestAttributes')]
#[AsEventListener(event: BeforeEntityPersistedEvent::class, method: 'updateEntityWithRequestAttributes')]
class UpdateTerritoireZipsListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function setFileDataInRequestAttribute(BeforeCrudActionEvent $event): void
    {
        if (TerritoireCrudController::class !== $event->getAdminContext()?->getCrud()?->getControllerFqcn()) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $datas = [];
        $zips = [];
        $fieldName = 'postalCodesFile';

        if (null !== $request) {
            $files = $this->requestStack->getCurrentRequest()?->files->get('Territoire');
            if (isset($files[$fieldName]['file'])) {
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile|null $file */
                $file = $files[$fieldName]['file'];
                try {
                    if ($file) {
                        $csvEncoder = new CsvEncoder();
                        $datas = $csvEncoder->decode((string) file_get_contents($file->getRealPath()), 'csv');
                    }
                } catch (\Exception $exception) {
                    /** @var \Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface|null $session */
                    $session = $request->getSession();
                    $session?->getFlashBag()?->add('danger', $exception->getMessage());
                }
            }
            foreach ($datas as $row) {
                $zips[] = $row['zip'];
            }

            $request->attributes->set('_zips', $zips);
        }
    }

    public function updateEntityWithRequestAttributes(BeforeEntityPersistedEvent | BeforeEntityUpdatedEvent $event): void
    {
        /** @var Territoire $entity */
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Territoire) {
            return;
        }

        $zips = $this->requestStack->getCurrentRequest()?->attributes->get('_zips');

        if ([] === $zips) {
            return;
        }

        $entity->setZips(array_values($zips));
    }
}
