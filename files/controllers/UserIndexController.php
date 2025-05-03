<?php

/*
 * @author leeda
 */
require_once 'models/CategoryModel.php';
require_once 'models/ProductModel.php';
require_once 'models/CartModel.php';
require_once 'apis/CurrencyConverter.php';

class UserIndexController {

    public function index() {
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        $currencyConverter = new CurrencyConverter();

        $search = $_GET['search'] ?? null;
        $selectedCategory = $_GET['category'] ?? null;
        $selectedCurrency = $_GET['currency'] ?? 'MYR';

        $conversionRate = 1;
        if ($selectedCurrency !== 'MYR') {
            $conversionRate = $currencyConverter->getConversionRate($selectedCurrency);
        }

        $xmlProducts = new DOMDocument();
        $xmlProducts->load('../xml-files/products.xml');

        $xmlCategories = new DOMDocument();
        $xmlCategories->load('../xml-files/categories.xml');

        $xslProducts = new DOMDocument();
        $xslProducts->load('xslt/user_product_view.xsl');

        $xslCategories = new DOMDocument();
        $xslCategories->load('xslt/category_sidebar.xsl');

        $procProducts = new XSLTProcessor();
        $procProducts->importStylesheet($xslProducts);
        $procProducts->setParameter('', 'currency', $selectedCurrency);
        $procProducts->setParameter('', 'conversionRate', $conversionRate);
        $procProducts->setParameter('', 'search', $search ?? '');
        $procProducts->setParameter('', 'category', $selectedCategory ?? '');

        $productTable = $procProducts->transformToXML($xmlProducts);

        $procCategories = new XSLTProcessor();
        $procCategories->setParameter('', 'currency', $selectedCurrency);
        $procCategories->importStylesheet($xslCategories);

        $CategorySideBar = $procCategories->transformToXml($xmlCategories);

        include 'views/user_home.php';
    }
}
