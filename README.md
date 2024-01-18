# Size Chart GraphQL

**Size Chart GraphQL is a part of MageINIC Size Chart extension that adds GraphQL features.** This extension extends Size Chart definitions.

## 1. How to install

Run the following command in Magento 2 root folder:

```
composer require mageinic/size-chart-graphql

php bin/magento maintenance:enable
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento maintenance:disable
php bin/magento cache:flush
```

**Note:**
Magento 2 Size Chart GraphQL requires installing [MageINIC Size Chart](https://github.com/mageinic/Size-Chart) in your Magento installation.

**Or Install via composer [Recommend]**
```
composer require mageinic/size-chart
```

## 2. How to use

- To view the queries that the **MageINIC Size Chart GraphQL** extension supports, you can check `Size Chart GraphQl User Guide.pdf` Or run `Size Chart Graphql.postman_collection.json` in Postman.

## 3. Get Support

- Feel free to [contact us](https://www.mageinic.com/contact.html) if you have any further questions.
- Like this project, Give us a **Star**
