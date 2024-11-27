<?php
require '../config/function.php';

$paramResult = checkParam('index');

if(is_numeric($paramResult)){

    $indexValue = validate($paramResult);

    if(isset($_SESSION['soItems']) && isset($_SESSION['soItemIds'])){
        unset($_SESSION['soItems'][$indexValue]);
        unset($_SESSION['soItemIds'][$indexValue]);

        redirect('stock-out.php', 'Item Removed!');
    }else{
        redirect('stock-out.php', 'There is no item.');
    }

}else{
    redirect('stock-out.php', 'param not numeric');
}
?>