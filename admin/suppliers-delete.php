<?php 

require '../config/function.php';

$paramResultId = checkParam('id');
if(is_numeric($paramResultId)) {
    $supplierId = validate($paramResultId);

    $supplier = getById('suppliers', $supplierId);

    if($supplier['status'] == 200){
        $supplierDeleteRes = delete('suppliers', $supplierId);
        if($supplierDeleteRes) {
            redirect('suppliers.php','Supplier deleted successfully!');
        } else {
            redirect('suppliers.php','Something went wrong.');
        }
    } else {
        redirect('suppliers.php',$supplier['message']);
    }
} else {
    redirect('suppliers.php','Something went wrong.');
}

?>