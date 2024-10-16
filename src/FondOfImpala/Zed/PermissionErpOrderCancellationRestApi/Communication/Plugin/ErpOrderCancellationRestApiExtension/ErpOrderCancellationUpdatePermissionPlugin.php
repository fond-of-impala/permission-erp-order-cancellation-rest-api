<?php

namespace FondOfImpala\Zed\PermissionErpOrderCancellationRestApi\Communication\Plugin\ErpOrderCancellationRestApiExtension;

use FondOfImpala\Shared\ErpOrderCancellationRestApiExtension\ErpOrderCancellationRestApiExtensionConstants;
use FondOfImpala\Zed\ErpOrderCancellationRestApiExtension\Dependency\Plugin\ErpOrderCancellationPermissionPluginInterface;
use FondOfImpala\Zed\PermissionErpOrderCancellationRestApi\Communication\Plugin\PermissionExtension\ErpOrderCancellationCreatePermissionPlugin as PermissionErpOrderCancellationCreatePermissionPlugin;
use FondOfImpala\Zed\PermissionErpOrderCancellationRestApi\Communication\Plugin\PermissionExtension\ErpOrderCancellationManagePermissionPlugin;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ErpOrderCancellationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfImpala\Zed\PermissionErpOrderCancellationRestApi\Persistence\PermissionErpOrderCancellationRestApiRepositoryInterface getRepository()
 */
class ErpOrderCancellationUpdatePermissionPlugin extends AbstractPlugin implements ErpOrderCancellationPermissionPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErpOrderCancellationTransfer $erpOrderCancellationTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string|null $type
     *
     * @return bool
     */
    public function isApplicable(
        ErpOrderCancellationTransfer $erpOrderCancellationTransfer,
        CompanyUserTransfer $companyUserTransfer,
        ?string $type = null
    ): bool {
        return $type === ErpOrderCancellationRestApiExtensionConstants::PERMISSION_TYPE_UPDATE;
    }

    /**
     * @param \Generated\Shared\Transfer\ErpOrderCancellationTransfer $erpOrderCancellationTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function can(ErpOrderCancellationTransfer $erpOrderCancellationTransfer, CompanyUserTransfer $companyUserTransfer): bool
    {
        if ($this->getRepository()->hasPermission($companyUserTransfer->getIdCompanyUser(), ErpOrderCancellationManagePermissionPlugin::KEY)) {
            return true;
        }

        return $this->canWithReducedPermissions($erpOrderCancellationTransfer, $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ErpOrderCancellationTransfer $erpOrderCancellationTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function canWithReducedPermissions(ErpOrderCancellationTransfer $erpOrderCancellationTransfer, CompanyUserTransfer $companyUserTransfer): bool
    {
        if (!$this->getRepository()->hasPermission($companyUserTransfer->getIdCompanyUser(), PermissionErpOrderCancellationCreatePermissionPlugin::KEY)) {
            return false;
        }

        return in_array($erpOrderCancellationTransfer->getState(), [null, 'new', 'ready'], true);
    }
}