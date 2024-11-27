<?php 

require '../config/function.php';

$paramResultId = checkParam('id');
if(is_numeric($paramResultId)) {
    $categoryId = validate($paramResultId);

    $category = getById('categories', $categoryId);

    if($category['status'] == 200){
        $categoryDeleteRes = delete('categories', $categoryId);
        if($categoryDeleteRes) {
            redirect('categories.php','Category deleted successfully!');
        } else {
            redirect('categories.php','Something went wrong.');
        }
    } else {
        redirect('categories.php',$category['message']);
    }
} else {
    redirect('categories.php','Something went wrong.');
}

?>