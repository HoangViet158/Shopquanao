<?php require_once "sidebar.php"
?>
<div id="statistic-content" class="content-container">
    <div class="Mange_client">
        <h3> Chi tiết đơn hàng </h3>
    </div>
</div>
<script>
    const actionPermissions = {
        canView: <?php echo json_encode(hasAction(1, 'view')); ?>,
        canEdit: <?php echo json_encode(hasAction(1, 'edit')); ?>,
        canDelete: <?php echo json_encode(hasAction(1, 'delete')); ?>,
        canAdd: <?php echo json_encode(hasAction(1, 'add')); ?>
    };
</script>

<div id="product-permissions"
     data-can-view="<?php echo json_encode(hasAction(1, 'view'));?>"
     data-can-edit="<?php echo json_encode(hasAction(1, 'edit')); ?>"
     data-can-delete="<?php echo json_encode(hasAction(1, 'delete')); ?>"
     data-can-add="<?php echo json_encode(hasAction(1, 'add')); ?>">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/admin/js/product.js"></script>

