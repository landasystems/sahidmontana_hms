<div class="row-fluid">

    <div class="span6">
        Method : <b><?php
            $siteConfig = SiteConfig::model()->listSiteConfig();
            echo $siteConfig['method'];
            ?></b>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Code</th>
            <th rowspan="2">Name</th>
            <?php
//            if ($type == 'assembly') {
//                echo '<th rowspan="2">Items Needed</th><th rowspan="2">Stock Items</th>';
//            }
            ?>
            <th rowspan="2">Stock</th>
        </tr>

    </thead>
    <tbody>
        <?php
        // $category=  ProductCategory::model()->findAll(array('condition'=>'id='.$_GET['product_category_id']));
        // $model=  Product::model()->findAll(array('condition'=>'product_category_id='.$_GET['product_category_id']));

        foreach ($stockItem as $m) {
            $type = $m->type;
            if ($type == 'assembly') {
                $products = json_decode($m->assembly_product_id, true);
                $items='';
                $itemStoks='';
                $stokUnprocess =999999999;
                for ($i = 0; $i < count($products['product_id']); $i++) {
                    $itemStok = floor(ProductStock::model()->departement($inventory[$products['product_id'][$i]], $_POST['departement_id']) / $products['qty'][$i]);
                    
                    $stokUnprocess = ($itemStok < $stokUnprocess) ? $itemStok : $stokUnprocess;
                    $items .='- '. $inventoryName[$products['product_id'][$i]].' ['.$products['qty'][$i].'] <br>';
                    $itemStoks.='Item Stock : '. ProductStock::model()->departement($inventory[$products['product_id'][$i]], $_POST['departement_id']).'<br>';
                }          
                if (count($products['product_id'])==0)
                    $stokUnprocess = 0;
                               
                $stokUnprocess = ' -> Available Process : ['.$stokUnprocess.']';
            } else {
                $stokUnprocess ='';
                $itemStoks='';
                $items='';
            }
            $stok = ProductStock::model()->departement($m->stock, $_POST['departement_id']);
            echo'
                <tr>
                <td>' . $m->code . '</td>
                <td>' . strtoupper($m->name) .$stokUnprocess;
                if ($type == 'assembly') {
                    echo (!empty($items))?'<table><tr style="border:0"><td class="span4" style="border : 0">'.$items.'</td><td style="border : 0">'.$itemStoks.'</td></tr></table>':"";
                }
                echo '</td>';
                if ($type == 'assembly') {
//                    echo '<td>'.$items.'</td><td>'.$itemStoks.'</td>';
                }
            echo '<td>' . $stok .'</td>
                  </tr>';
        }
        ?>
    </tbody>

</table>