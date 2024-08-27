# Sanjeev_SpamContentBlocker Magento 2 module

Sanjeev_ImportExportCms is a module for Magento 2. This module helps to import or export cms pages and/or cms blocks from admin interface.

## Install with Composer
```
composer require sanjeev-kr/magento2-spam-content-blocker
php bin/magento module:enable Sanjeev_SpamContentBlocker
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

## Install Manually
- Download zip and extract
- Create a new directory Sanjeev/SpamContentBlocker in app/code directory and copy files from magento2-spam-content-blocker folder and paste files in Sanjeev/SpamContentBlocker directory.
- And run below commands

```
php bin/magento module:enable Sanjeev_SpamContentBlocker
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```
